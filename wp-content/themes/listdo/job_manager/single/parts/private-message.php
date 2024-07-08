<?php
global $post;

if ( $post->post_author ) {
?>
	<div class="send-message-form-wrapper widget">
		<h2 class="widget-title">
			<i class="flaticon-chat-comment-oval-speech-bubble-with-text-lines"></i><span><?php esc_html_e('Send private message', 'listdo'); ?></span>
		</h2>
	    <?php
	        if ( is_user_logged_in() ) {
	    ?>
	        	<form id="send-message-form" class="send-message-form" action="?" method="post">
	                <div class="form-group space-30">
	                    <textarea class="form-control message style2" name="message" placeholder="<?php esc_attr_e( 'Message', 'listdo' ); ?>" required="required"></textarea>
	                </div><!-- /.form-group -->

	                <?php wp_nonce_field( 'wp-private-message-send-message', 'wp-private-message-send-message-nonce' ); ?>
	              	<input type="hidden" name="recipient" value="<?php echo esc_attr($post->post_author); ?>">
	              	<input type="hidden" name="listing_id" value="<?php echo esc_attr($post->ID); ?>">

	              	<input type="hidden" name="action" value="listdo_wp_private_message_send_message">

	                <button class="button btn btn-theme btn-block send-message-btn"><?php echo esc_html__( 'Send Message', 'listdo' ); ?></button>
	        	</form>
	    <?php } else { ?>
	        <a href="javascript:void(0);" class="login-form-popup-message"><?php esc_html_e('Login to send a private message', 'listdo'); ?></a>
	    <?php } ?>
	</div>
<?php } ?>