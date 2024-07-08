<?php

$options_model = [
    'OpenAI' => [
        'gpt-4o' => esc_html__('GPT 4o', 'aibuddy-openai-chatgpt'),
        'gpt-4-turbo' => esc_html__('GPT 4 Turbo', 'aibuddy-openai-chatgpt'),
        'gpt-4' => esc_html__('GPT 4', 'aibuddy-openai-chatgpt'),
        'gpt-3.5-turbo-16k' => esc_html__('GPT 3.5 Turbo 16k', 'aibuddy-openai-chatgpt'),
        'gpt-3.5-turbo' => esc_html__('GPT 3.5 Turbo', 'aibuddy-openai-chatgpt'),
        'gpt-3.5-turbo-instruct' => esc_html__('GPT 3.5 Turbo Instruct', 'aibuddy-openai-chatgpt'),
    ],
    'Google' => [
        'gemini-pro' => esc_html__('Gemini 1.0 Pro', 'aibuddy-openai-chatgpt'),
    ],
    'Claude' => [
        'claude-3-opus-20240229' => esc_html__('Claude 3 Opus', 'aibuddy-openai-chatgpt'),
        'claude-3-sonnet-20240229' => esc_html__('Claude 3 Sonnet', 'aibuddy-openai-chatgpt'),
        'claude-3-haiku-20240307' => esc_html__('Claude 3 Haiku', 'aibuddy-openai-chatgpt'),
    ],
    'OpenRouter' => [
        'anthropic/claude-1' => esc_html__('Anthropic: Claude v1', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-1.2' => esc_html__('Anthropic: Claude (older v1)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-2' => esc_html__('Anthropic: Claude v2', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-2.0' => esc_html__('Anthropic: Claude v2.0', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-2.0:beta' => esc_html__('Anthropic: Claude v2.0 (self-moderated)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-2.1' => esc_html__('Anthropic: Claude v2.1', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-2.1:beta' => esc_html__('Anthropic: Claude v2.1 (self-moderated)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-2:beta' => esc_html__('Anthropic: Claude v2 (self-moderated)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-3-haiku' => esc_html__('Anthropic: Claude 3 Haiku', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-3-haiku:beta' => esc_html__('Anthropic: Claude 3 Haiku (self-moderated)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-3-opus' => esc_html__('Anthropic: Claude 3 Opus', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-3-opus:beta' => esc_html__('Anthropic: Claude 3 Opus (self-moderated)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-3-sonnet' => esc_html__('Anthropic: Claude 3 Sonnet', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-3-sonnet:beta' => esc_html__('Anthropic: Claude 3 Sonnet (self-moderated)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-instant-1' => esc_html__('Anthropic: Claude Instant v1', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-instant-1.0' => esc_html__('Anthropic: Claude Instant (older v1)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-instant-1.1' => esc_html__('Anthropic: Claude Instant (older v1.1)', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-instant-1.2' => esc_html__('Anthropic: Claude Instant v1.2', 'aibuddy-openai-chatgpt'),
        'anthropic/claude-instant-1:beta' => esc_html__('Anthropic: Claude Instant v1 (self-moderated)', 'aibuddy-openai-chatgpt'),
        'austism/chronos-hermes-13b' => esc_html__('Chronos Hermes 13B v2', 'aibuddy-openai-chatgpt'),
        'cognitivecomputations/dolphin-mixtral-8x7b' => esc_html__('Dolphin 2.6 Mixtral 8x7B 🐬', 'aibuddy-openai-chatgpt'),
        'cohere/command' => esc_html__('Cohere: Command', 'aibuddy-openai-chatgpt'),
        'cohere/command-r' => esc_html__('Cohere: Command R', 'aibuddy-openai-chatgpt'),
        'cohere/command-r-plus' => esc_html__('Cohere: Command R+', 'aibuddy-openai-chatgpt'),
        'databricks/dbrx-instruct' => esc_html__('Databricks: DBRX 132B Instruct', 'aibuddy-openai-chatgpt'),
        'databricks/dbrx-instruct:nitro' => esc_html__('Databricks: DBRX 132B Instruct (nitro)', 'aibuddy-openai-chatgpt'),
        'fireworks/mixtral-8x22b-instruct-preview' => esc_html__('Fireworks: Mixtral-8x22B Instruct OH (preview)', 'aibuddy-openai-chatgpt'),
        'google/gemini-pro' => esc_html__('Google: Gemini Pro 1.0', 'aibuddy-openai-chatgpt'),
        'google/gemini-pro-1.5' => esc_html__('Google: Gemini Pro 1.5 (preview)', 'aibuddy-openai-chatgpt'),
        'google/gemini-pro-vision' => esc_html__('Google: Gemini Pro Vision 1.0', 'aibuddy-openai-chatgpt'),
        'google/gemma-7b-it' => esc_html__('Google: Gemma 7B', 'aibuddy-openai-chatgpt'),
        'google/gemma-7b-it:free' => esc_html__('Google: Gemma 7B (free)', 'aibuddy-openai-chatgpt'),
        'google/gemma-7b-it:nitro' => esc_html__('Google: Gemma 7B (nitro)', 'aibuddy-openai-chatgpt'),
        'google/palm-2-chat-bison' => esc_html__('Google: PaLM 2 Chat', 'aibuddy-openai-chatgpt'),
        'google/palm-2-chat-bison-32k' => esc_html__('Google: PaLM 2 Chat 32k', 'aibuddy-openai-chatgpt'),
        'google/palm-2-codechat-bison' => esc_html__('Google: PaLM 2 Code Chat', 'aibuddy-openai-chatgpt'),
        'google/palm-2-codechat-bison-32k' => esc_html__('Google: PaLM 2 Code Chat 32k', 'aibuddy-openai-chatgpt'),
        'gryphe/mythomax-l2-13b' => esc_html__('MythoMax 13B', 'aibuddy-openai-chatgpt'),
        'gryphe/mythomax-l2-13b:extended' => esc_html__('MythoMax 13B (extended)', 'aibuddy-openai-chatgpt'),
        'gryphe/mythomax-l2-13b:nitro' => esc_html__('MythoMax 13B (nitro)', 'aibuddy-openai-chatgpt'),
        'gryphe/mythomist-7b' => esc_html__('MythoMist 7B', 'aibuddy-openai-chatgpt'),
        'gryphe/mythomist-7b:free' => esc_html__('MythoMist 7B (free)', 'aibuddy-openai-chatgpt'),
        'huggingfaceh4/zephyr-7b-beta' => esc_html__('Hugging Face: Zephyr 7B', 'aibuddy-openai-chatgpt'),
        'huggingfaceh4/zephyr-7b-beta:free' => esc_html__('Hugging Face: Zephyr 7B (free)', 'aibuddy-openai-chatgpt'),
        'huggingfaceh4/zephyr-orpo-141b-a35b' => esc_html__('Zephyr 141B-A35B', 'aibuddy-openai-chatgpt'),
        'haotian-liu/llava-13b' => esc_html__('Llava 13B', 'aibuddy-openai-chatgpt'),
        'intel/neural-chat-7b' => esc_html__('Neural Chat 7B v3.1', 'aibuddy-openai-chatgpt'),
        'jebcarter/psyfighter-13b' => esc_html__('Psyfighter 13B', 'aibuddy-openai-chatgpt'),
        'jondurbin/airoboros-l2-70b' => esc_html__('Airoboros 70B', 'aibuddy-openai-chatgpt'),
        'jondurbin/bagel-34b' => esc_html__('Bagel 34B v0.2', 'aibuddy-openai-chatgpt'),
        'koboldai/psyfighter-13b-2' => esc_html__('Psyfighter v2 13B', 'aibuddy-openai-chatgpt'),
        'lizpreciatior/lzlv-70b-fp16-hf' => esc_html__('lzlv 70B', 'aibuddy-openai-chatgpt'),
        'mancer/weaver' => esc_html__('Mancer: Weaver', 'aibuddy-openai-chatgpt'),
        'meta-llama/codellama-34b-instruct' => esc_html__('Meta: CodeLlama 34B Instruct', 'aibuddy-openai-chatgpt'),
        'meta-llama/llama-2-13b-chat' => esc_html__('Meta: Llama v2 13B Chat', 'aibuddy-openai-chatgpt'),
        'meta-llama/llama-2-70b-chat' => esc_html__('Meta: Llama v2 70B Chat', 'aibuddy-openai-chatgpt'),
        'meta-llama/llama-2-70b-chat:nitro' => esc_html__('Meta: Llama v2 70B Chat (nitro)', 'aibuddy-openai-chatgpt'),
        'meta-llama/llama-3-8b-instruct:free' => esc_html__('Meta: Llama 3 8B Instruct (free)', 'aibuddy-openai-chatgpt'),
        'meta-llama/llama-3-8b-instruct:extended' => esc_html__('Meta: Llama 3 8B Instruct (extended)', 'aibuddy-openai-chatgpt'),
        'meta-llama/llama-3-70b' => esc_html__('Meta: Llama 3 70B', 'aibuddy-openai-chatgpt'),
        'meta-llama/llama-3-8b' => esc_html__('Meta: Llama 3 8B', 'aibuddy-openai-chatgpt'),
        'migtissera/synthia-70b' => esc_html__('Synthia 70B', 'aibuddy-openai-chatgpt'),
        'mistralai/mistral-7b-instruct' => esc_html__('Mistral 7B Instruct', 'aibuddy-openai-chatgpt'),
        'mistralai/mistral-7b-instruct:nitro' => esc_html__('Mistral 7B Instruct (nitro)', 'aibuddy-openai-chatgpt'),
        'mistralai/mistral-7b-openorca' => esc_html__('Mistral OpenOrca 7B', 'aibuddy-openai-chatgpt'),
        'mistralai/mistral-large' => esc_html__('Mistral Large', 'aibuddy-openai-chatgpt'),
        'mistralai/mistral-medium' => esc_html__('Mistral Medium', 'aibuddy-openai-chatgpt'),
        'mistralai/mistral-small' => esc_html__('Mistral Small', 'aibuddy-openai-chatgpt'),
        'mistralai/mistral-tiny' => esc_html__('Mistral Tiny', 'aibuddy-openai-chatgpt'),
        'mistralai/mixtral-8x22b' => esc_html__('Mistral: Mixtral 8x22B', 'aibuddy-openai-chatgpt'),
        'mistralai/mixtral-8x7b' => esc_html__('Mixtral 8x7B', 'aibuddy-openai-chatgpt'),
        'mistralai/mixtral-8x7b-instruct' => esc_html__('Mixtral 8x7B Instruct', 'aibuddy-openai-chatgpt'),
        'mistralai/mixtral-8x7b-instruct:nitro' => esc_html__('Mixtral 8x7B Instruct (nitro)', 'aibuddy-openai-chatgpt'),
        'neversleep/noromaid-20b' => esc_html__('Noromaid 20B', 'aibuddy-openai-chatgpt'),
        'neversleep/noromaid-mixtral-8x7b-instruct' => esc_html__('Noromaid Mixtral 8x7B Instruct', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-capybara-34b' => esc_html__('Nous: Capybara 34B', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-capybara-7b' => esc_html__('Nous: Capybara 7B', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-capybara-7b:free' => esc_html__('Nous: Capybara 7B (free)', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-hermes-2-mistral-7b-dpo' => esc_html__('Nous: Hermes 2 Mistral 7B DPO', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-hermes-2-vision-7b' => esc_html__('Nous: Hermes 2 Vision 7B', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-hermes-llama2-13b' => esc_html__('Nous: Hermes 13B', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-hermes-2-mixtral-8x7b-dpo' => esc_html__('Nous: Hermes 2 Mixtral 8x7B DPO', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-hermes-2-mixtral-8x7b-sft' => esc_html__('Nous: Hermes 2 Mixtral 8x7B SFT', 'aibuddy-openai-chatgpt'),
        'nousresearch/nous-hermes-yi-34b' => esc_html__('Nous: Hermes 2 Yi 34B', 'aibuddy-openai-chatgpt'),
        'openai/gpt-3.5-turbo' => esc_html__('OpenAI: GPT-3.5 Turbo', 'aibuddy-openai-chatgpt'),
        'openai/gpt-3.5-turbo-0125' => esc_html__('OpenAI: GPT-3.5 Turbo 16k', 'aibuddy-openai-chatgpt'),
        'openai/gpt-3.5-turbo-0301' => esc_html__('OpenAI: GPT-3.5 Turbo (older v0301)', 'aibuddy-openai-chatgpt'),
        'openai/gpt-3.5-turbo-0613' => esc_html__('OpenAI: GPT-3.5 Turbo (older v0613)', 'aibuddy-openai-chatgpt'),
        'openai/gpt-3.5-turbo-1106' => esc_html__('OpenAI: GPT-3.5 Turbo 16k (older v1106)', 'aibuddy-openai-chatgpt'),
        'openai/gpt-3.5-turbo-16k' => esc_html__('OpenAI: GPT-3.5 Turbo 16k', 'aibuddy-openai-chatgpt'),
        'openai/gpt-3.5-turbo-instruct' => esc_html__('OpenAI: GPT-3.5 Turbo Instruct', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4' => esc_html__('OpenAI: GPT-4', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4-0314' => esc_html__('OpenAI: GPT-4 (older v0314)', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4-1106-preview' => esc_html__('OpenAI: GPT-4 Turbo (older v1106)', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4-32k' => esc_html__('OpenAI: GPT-4 32k', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4-32k-0314' => esc_html__('OpenAI: GPT-4 32k (older v0314)', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4-turbo' => esc_html__('OpenAI: GPT-4 Turbo', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4-turbo-preview' => esc_html__('OpenAI: GPT-4 Turbo Preview', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4-vision-preview' => esc_html__('OpenAI: GPT-4 Vision', 'aibuddy-openai-chatgpt'),
        'openai/gpt-4o' => esc_html__('OpenAI: GPT-4o', 'aibuddy-openai-chatgpt'),
        'openchat/openchat-7b' => esc_html__('OpenChat 3.5', 'aibuddy-openai-chatgpt'),
        'openchat/openchat-7b:free' => esc_html__('OpenChat 3.5 (free)', 'aibuddy-openai-chatgpt'),
        'open-orca/mistral-7b-openorca' => esc_html__('Mistral OpenOrca 7B', 'aibuddy-openai-chatgpt'),
        'openrouter/cinematika-7b' => esc_html__('Cinematika 7B', 'aibuddy-openai-chatgpt'),
        'openrouter/cinematika-7b:free' => esc_html__('Cinematika 7B (free)', 'aibuddy-openai-chatgpt'),
        'perplexity/pplx-70b-chat' => esc_html__('Perplexity: PPLX 70B Chat', 'aibuddy-openai-chatgpt'),
        'perplexity/pplx-70b-online' => esc_html__('Perplexity: PPLX 70B Online', 'aibuddy-openai-chatgpt'),
        'perplexity/pplx-7b-chat' => esc_html__('Perplexity: PPLX 7B Chat', 'aibuddy-openai-chatgpt'),
        'perplexity/pplx-7b-online' => esc_html__('Perplexity: PPLX 7B Online', 'aibuddy-openai-chatgpt'),
        'perplexity/sonar-medium-chat' => esc_html__('Perplexity: Sonar 8x7B', 'aibuddy-openai-chatgpt'),
        'perplexity/sonar-medium-online' => esc_html__('Perplexity: Sonar 8x7B Online', 'aibuddy-openai-chatgpt'),
        'perplexity/sonar-small-chat' => esc_html__('Perplexity: Sonar 7B', 'aibuddy-openai-chatgpt'),
        'perplexity/sonar-small-online' => esc_html__('Perplexity: Sonar 7B Online', 'aibuddy-openai-chatgpt'),
        'phind/phind-codellama-34b' => esc_html__('Phind: CodeLlama 34B v2', 'aibuddy-openai-chatgpt'),
        'pygmalionai/mythalion-13b' => esc_html__('Pygmalion: Mythalion 13B', 'aibuddy-openai-chatgpt'),
        'recursal/eagle-7b' => esc_html__('RWKV v5: Eagle 7B', 'aibuddy-openai-chatgpt'),
        'recursal/rwkv-5-3b-ai-town' => esc_html__('RWKV v5 3B AI Town', 'aibuddy-openai-chatgpt'),
        'rwkv/rwkv-5-world-3b' => esc_html__('RWKV v5 World 3B', 'aibuddy-openai-chatgpt'),
        'sophosympatheia/midnight-rose-70b' => esc_html__('Midnight Rose 70B', 'aibuddy-openai-chatgpt'),
        'teknium/openhermes-2-mistral-7b' => esc_html__('OpenHermes 2 Mistral 7B', 'aibuddy-openai-chatgpt'),
        'teknium/openhermes-2.5-mistral-7b' => esc_html__('OpenHermes 2.5 Mistral 7B', 'aibuddy-openai-chatgpt'),
        'togethercomputer/stripedhyena-hessian-7b' => esc_html__('StripedHyena Hessian 7B', 'aibuddy-openai-chatgpt'),
        'togethercomputer/stripedhyena-nous-7b' => esc_html__('StripedHyena Nous 7B', 'aibuddy-openai-chatgpt'),
        'undi95/remm-slerp-l2-13b' => esc_html__('ReMM SLERP 13B', 'aibuddy-openai-chatgpt'),
        'undi95/remm-slerp-l2-13b:extended' => esc_html__('ReMM SLERP 13B (extended)', 'aibuddy-openai-chatgpt'),
        'undi95/toppy-m-7b' => esc_html__('Toppy M 7B', 'aibuddy-openai-chatgpt'),
        'undi95/toppy-m-7b:free' => esc_html__('Toppy M 7B (free)', 'aibuddy-openai-chatgpt'),
        'xwin-lm/xwin-lm-70b' => esc_html__('Xwin 70B', 'aibuddy-openai-chatgpt'),
    ]
];

$fse_default_model = isset($general_setting['fse']['model']) ? $general_setting['fse']['model'] : false;
$default_model = isset($default_model) ? $default_model : $fse_default_model;
$general_setting = get_option( 'ai_buddy', array() );
foreach ( $options_model as $brand => $models ) {
    ?>
    <optgroup label="<?php echo esc_html($brand) ?>">
    <?php
    foreach ($models as $model => $text) {
        switch ($brand) {
            case 'OpenAI':
                ?>
                <option value="<?php echo esc_attr( $model ); ?>" <?php echo $default_model === $model ? 'selected' : '' ?>><?php echo esc_attr( $text ); ?></option>
                <?php
                break;
            case 'Google':
                $isGeminiExist = isset( $general_setting['googleai']['apikey'] ) && !empty($general_setting['googleai']['apikey']) ? true : false;
                ?>
                <option value="<?php echo esc_attr( $model ); ?>" <?php echo $isGeminiExist ? '' : 'disabled' ?> <?php echo $default_model === $model ? 'selected' : '' ?>><?php echo esc_attr( $text ); ?><?php echo ($isGeminiExist ? '' : __(' (API Key not entered)', 'aibuddy-openai-chatgpt')); ?></option>
                
                <?php
                break;
            case 'Claude':
                $isClaudExist = isset( $general_setting['claude']['apikey'] ) && !empty($general_setting['claude']['apikey']) ? true : false;
                ?>
                <option value="<?php echo esc_attr( $model ); ?>" <?php echo $isClaudExist ? '' : 'disabled' ?> <?php echo $default_model === $model ? 'selected' : '' ?>><?php echo esc_attr( $text ); ?><?php echo ($isClaudExist ? '' : __(' (API Key not entered)', 'aibuddy-openai-chatgpt')); ?></option>
                <?php
                break;
            case 'OpenRouter':
                $isClaudExist = isset( $general_setting['openrouter']['apikey'] ) && !empty($general_setting['openrouter']['apikey']) ? true : false;
                ?>
                <option value="<?php echo esc_attr( $model ); ?>" <?php echo $isClaudExist ? '' : 'disabled' ?> <?php echo $default_model === $model ? 'selected' : '' ?>><?php echo esc_attr( $text ); ?><?php echo ($isClaudExist ? '' : __(' (API Key not entered)', 'aibuddy-openai-chatgpt')); ?></option>
                <?php
                break;
        }
    }
    ?>
    </optgroup>
    <?php
} 
