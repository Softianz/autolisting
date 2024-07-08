<?php

namespace AiBuddy;

use AiBuddy\OpenAi\Api;
use AiBuddy\Google\Api as GoogleAi;
use AiBuddy\Claude\Api as Claude;
use AiBuddy\OpenRouter\Api as OpenRouter;
use AiBuddy\OpenAi\ImageQuery;
use AiBuddy\OpenAi\Query;
use AiBuddy\OpenAi\Response;
use AiBuddy\OpenAi\TextQuery;
use AiBuddy\OpenAi\MessageQuery;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class AiContentGenerator {
	private Api $api;
	
	private GoogleAi $googleAi;
	private Claude $claude;

    private OpenRouter $openRouter;

	public function __construct( Api $api, GoogleAi $googleAi, Claude $claude, OpenRouter $openRouter ) {
		$this->api = $api;
		$this->googleAi = $googleAi;
		$this->claude = $claude;
		$this->openRouter = $openRouter;
	}

	/**
	 * @return Response
	 */
	public function exec( Query $query ) {
		try {
			if ( $query instanceof TextQuery ) {
                if (str_contains($query->model, 'ft:gpt-3.5') || $query->model === 'gpt-3.5-turbo-16k' || $query->model === 'gpt-3.5-turbo' || $query->model === 'gpt-3.5-turbo-instruct' || $query->model === 'gpt-4' || $query->model === 'gpt-4-turbo' || $query->model === 'gpt-4o'  ) {
                    $data = $this->api->create_completions($query->to_request_body());
                    $response = new Response(
                        $query,
                        !is_string($data) ? [$data['choices'][0]['message']['content']] : [$data],
                        !is_string($data) ? $data : [$data],
                    );
                } elseif ($query->model === 'gemini-pro') {
                    $data = $this->googleAi->create_completions($query->to_request_body());

                    $response = new Response(
                        $query,
                        !is_string($data) ? [$data['candidates'][0]['content']['parts'][0]['text']] : [$data],
                        !is_string($data) ? $data : [$data],
                    );

                    return $response;
                } elseif ($query->model === 'claude-3-opus-20240229' || $query->model === 'claude-3-sonnet-20240229' || $query->model === 'claude-3-haiku-20240307') {
                    $data = $this->claude->create_completions($query->to_request_body());

                    $response = new Response(
                        $query,
                        !is_string($data) ? [$data['content'][0]['text']] : [$data],
                        !is_string($data) ? $data : [$data],
                    );

                    return $response;
                } else {
                    $data = $this->openRouter->create_completions($query->to_request_body());
                    
                    $response = new Response(
                        $query,
                        !is_string($data) ? [$data['choices'][0]['message']['content']] : [$data],
                        !is_string($data) ? $data : [$data],
                    );
                    
                    return $response;
                }
            } elseif ($query instanceof ImageQuery ) {
				$data     = $this->api->create_images( $query->to_request_body() );
				$response = new Response(
					$query,
					! is_string( $data ) ? array_column( $data['data'], 'url' ) : array( $data ),
					! is_string( $data ) ? $data : array( $data ),
				);
			} elseif ( $query instanceof MessageQuery ) {
				$data     = $this->api->create_message_completions( $query->to_request_body() );
				$response = new Response(
					$query,
					$data['choices'][0]['message'],
					$data
				);
			} else {
				throw new InvalidArgumentException( 'Unknown query type' );
			}
			
			UsageLogger::log( $response );
			return $response;
		} catch ( Exception $e ) {
			throw new RuntimeException( $e->getMessage() );
		}
	}
}
