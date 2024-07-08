<?php

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class Listdo_WP_Private_Message_Message {
	
	public static function init() {
		add_action( 'wp_ajax_listdo_wp_private_message_send_message',  array(__CLASS__,'process_send_message') );
		add_action( 'wp_ajax_nopriv_listdo_wp_private_message_send_message',  array(__CLASS__,'process_send_message') );

		add_filter('listdo_get_default_blocks_sidebar_content', array(__CLASS__, 'blocks_content'), 100 );
		add_filter('listdo_get_default_blocks_sidebar_content', array(__CLASS__, 'blocks_content'), 100 );
		add_filter('listdo_get_default_blocks_sidebar_content', array(__CLASS__, 'blocks_content'), 100 );
		add_filter('listdo_get_default_blocks_sidebar_content', array(__CLASS__, 'blocks_content'), 100 );
	}
	
	public static function process_send_message() {
		$return = array();
		if ( !isset( $_POST['wp-private-message-send-message-nonce'] ) || ! wp_verify_nonce( $_POST['wp-private-message-send-message-nonce'], 'wp-private-message-send-message' )  ) {
			$return = array( 'status' => false, 'msg' => esc_html__('Sorry, your nonce did not verify.', 'listdo') );
		   	echo wp_json_encode($return);
		   	exit;
		}
		if ( !is_user_logged_in() ) {
			$return = array( 'status' => false, 'msg' => esc_html__('Please login to send a message.', 'listdo') );
		   	echo wp_json_encode($return);
		   	exit;
		}
		$recipient = !empty($_POST['recipient']) ? $_POST['recipient'] : '';
		$user = get_user_by('id', $recipient);

		if ( empty($user->ID) ) {
			$return = array( 'status' => false, 'msg' => esc_html__('Recipient did not exists.', 'listdo') );
		   	echo wp_json_encode($return);
		   	exit;
		}

		// listing_id
		$listing_id = !empty($_POST['listing_id']) ? $_POST['listing_id'] : '';
		$listing = get_post($listing_id);
		if ( empty($listing) || $listing->post_type !== 'job_listing' ) {
			$return = array( 'status' => false, 'msg' => esc_html__('Listing did not exists.', 'listdo') );
		   	echo wp_json_encode($return);
		   	exit;
		}

		// parent
		$parent = self::get_message_by_listing_id($listing_id);
		if ( $parent ) {
			$post_parent = get_post($parent);

			self::process_reply_message($parent, $post_parent);

		} else {
			$user_id = get_current_user_id();
			$message_id = self::insert_message($user_id, $recipient, $listing);
			
	        if ( $message_id ) {
	        	// Send Email
	        	if ( wp_private_message_get_option('user_notice_add_new_message') ) {
		        	$sender_info = get_userdata($user_id);
		        	$email_from = $sender_info->user_email;

					$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", $email_from, $email_from );
					$email_to = $user->user_email;
					$subject = wp_private_message_get_option('user_notice_add_new_message_subject');
					$content = WP_Private_Message_Mixes::get_email_content('new_message', array(
						'message_id' => $message_id,
						'sender_info' => $sender_info,
						'user' => $user,
					));
					if ( function_exists('apus_wjm_send_mail') ) {
						apus_wjm_send_mail( $email_to, $subject, $content, $headers );
					}
				}

		        $return = array( 'status' => true, 'msg' => esc_html__('Sent message successful.', 'listdo') );
			   	echo wp_json_encode($return);
			   	exit;
		    } else {
				$return = array( 'status' => false, 'msg' => esc_html__('Send message error.', 'listdo') );
			   	echo wp_json_encode($return);
			   	exit;
			}
		}
	}

	public static function insert_message($user_id, $recipient, $listing) {
		$message_args = array(
            'post_title' => $listing->post_title,
            'post_type' => 'private_message',
            'post_content' => isset($_POST['message']) ? $_POST['message'] : '',
            'post_status' => 'publish',
            'post_author' => $user_id,
        );
		$message_args = apply_filters('wp-private-message-add-message-data', $message_args);
		do_action('wp-private-message-before-add-message');

        // Insert the post into the database
        $message_id = wp_insert_post($message_args);
        if ( $message_id ) {
        	update_post_meta($message_id, '_read_'.$user_id, 'yes');
	        update_post_meta($message_id, '_sender', $user_id);
	        update_post_meta($message_id, '_recipient', $recipient);
	        update_post_meta($message_id, '_listing_id', $listing->ID);

	        do_action('wp-private-message-after-add-message', $message_id, $recipient, $user_id);
	    }

	    return $message_id;
	}

	public static function process_reply_message($parent, $post_parent) {
		$user_id = get_current_user_id();
		$reply_args = array(
            'post_title' => 'RE: '.$post_parent->post_title,
            'post_type' => 'private_message',
            'post_content' => isset($_POST['message']) ? $_POST['message'] : '',
            'post_status' => 'publish',
            'post_parent' => $parent,
            'post_author' => $user_id,
        );
		$reply_args = apply_filters('wp-private-message-reply-message-data', $reply_args);
		do_action('wp-private-message-before-reply-message');

        // Insert the post into the database
        $reply_id = wp_insert_post($reply_args);

        if ( $reply_id ) {
        	do_action('wp-private-message-after-reply-message', $reply_id, $parent, $user_id);

        	$sender = get_post_meta($parent, '_sender', true);
        	$recipient = get_post_meta($parent, '_recipient', true);
        	
        	if ( $user_id == $sender ) {
	        	delete_post_meta($post_parent->ID, '_read_'.$recipient);
	        } else {
	        	delete_post_meta($post_parent->ID, '_read_'.$sender);
	        }

        	// Send Email
        	if ( wp_private_message_get_option('user_notice_add_new_message') ) {
	        	$sender_info = get_userdata($user_id);
	        	$email_from = $sender_info->user_email;
	        	$user = get_userdata($post_parent->post_author);

				$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", $email_from, $email_from );
				$email_to = $user->user_email;
				$subject = wp_private_message_get_option('user_notice_replied_message_subject');
				$content = WP_Private_Message_Mixes::get_email_content('reply_message', array(
					'reply_id' => $reply_id,
					'sender_info' => $sender_info,
					'user' => $user,
				));
				if ( function_exists('apus_wjm_send_mail') ) {
					apus_wjm_send_mail( $email_to, $subject, $content, $headers );
				}
			}

	        $return = array( 'status' => true, 'msg' => esc_html__('Sent message successful.', 'listdo') );
		   	echo wp_json_encode($return);
		   	exit;
	    } else {
			$return = array( 'status' => false, 'msg' => esc_html__('Reply message error.', 'listdo') );
		   	echo wp_json_encode($return);
		   	exit;
		}
	}

	public static function get_message_by_listing_id($listing_id) {
		$query_args = array(
			'post_type'         => 'private_message',
			'paged'         	=> 1,
			'posts_per_page'    => 1,
			'post_status'       => 'publish',
			'order'       		=> 'DESC',
			'orderby'       	=> 'date',
			'post_parent'       => 0,
			'fields'			=> 'ids'
		);

    	$meta_query = array(
    		array(
	    		'key'       => '_listing_id',
				'value'     => $listing_id,
				'compare'   => '=',
	    	),
    	);
	    if ( !empty($meta_query) ) {
	    	$query_args['meta_query'] = $meta_query;
	    }

	    $messages = new WP_Query( $query_args );
	    
	    $message_id = 0;
	    if ( !empty($messages) && !empty($messages->posts) ) {
	    	$message_id = $messages->posts[0];
	    }
	    return $message_id;
	}

	public static function blocks_content($contents) {
		$contents['private-message'] = esc_html__( 'Private Message', 'listdo' );
		return $contents;
	}
}
Listdo_WP_Private_Message_Message::init();