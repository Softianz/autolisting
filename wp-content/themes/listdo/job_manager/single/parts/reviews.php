<?php

global $post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !listdo_listing_review_enable($post->ID) ) {
	return;
}

?>

<?php if ( have_comments() ) : ?>
	<div id="listing-comments" class="listing-reviews-list widget">
		<h2 class="widget-title">
			<i class="flaticon-chat-comment-oval-speech-bubble-with-text-lines"></i><?php comments_number( esc_html__('0 Reviews', 'listdo'), esc_html__('1 Review', 'listdo'), esc_html__('% Reviews', 'listdo') ); ?>
		</h2>
		<div id="comments">
			<?php if ( is_user_logged_in() ) { ?>
				<div class="report-comment-form-hidden hidden">
					<form method="post" action="" class="apus-report-comment-form">
						<input class="report_comment_id" required type="hidden" name="comment_id" value="">
					    <div class="title"><?php esc_html_e('Please briefly explain why you feel this comment should be removed.', 'listdo'); ?></div>
					    <div class="msg"></div>
					    <?php wp_nonce_field('ajax-report-comment-nonce', 'report-comment-security'); ?>
					    <div class="form-group">
					        <textarea required class="report_msg" name="report_msg" cols="60" rows="5"></textarea>
					    </div>
					    <div class="description"><?php esc_html_e('We will NOT remove negative comments unless they are abusive or offensive. Once a comment has been reported it cannot be retracted and will be sent to moderators for review.', 'listdo'); ?></div>

					    <button class="button btn btn-default cancel-report btn-xs radius-3x" type="button" name="cancel-report"><?php echo esc_html__( 'Cancel', 'listdo' ); ?></button>
					    <button class="button btn btn-theme submit-report btn-xs radius-3x" type="submit" name="submit-report"><?php echo esc_html__( 'Report', 'listdo' ); ?></button>
					</form>
				</div>
			<?php } ?>
			
			<ul class="comment-list">
				<?php wp_list_comments( array( 'callback' => 'listdo_listing_comments' ) ); ?>
			</ul>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="apus-pagination">';
				paginate_comments_links( apply_filters( 'apus_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>
		</div>
	</div>
<?php endif; ?>
	
<div id="listing-reviews" class="listing-reviews widget">
	<h2 class="widget-title">
		<i class="flaticon-chat-comment-oval-speech-bubble-with-text-lines"></i><span><?php esc_html_e('Add Review & Rate', 'listdo'); ?></span>
	</h2>
	<?php $commenter = wp_get_current_commenter(); ?>
	<div id="review_form_wrapper" class="commentform">
		<div class="reply_comment_form hidden">
			<?php
				$comment_form = array(
					'title_reply'          => esc_html__( 'Reply comment', 'listdo' ),
					'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'listdo' ),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<div class="row"><div class="col-xs-12 col-sm-6"><div class="form-group"><label>'.esc_html__( 'Name', 'listdo' ).'</label>'.
						            '<input id="author" class="form-control style2" placeholder="'.esc_attr__( 'Your Name', 'listdo' ).'" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div></div>',
						'email'  => '<div class="col-xs-12 col-sm-6"><div class="form-group"><label>'.esc_html__( 'Email', 'listdo' ).'</label>' .
						            '<input id="email" placeholder="'.esc_attr__( 'your@mail.com', 'listdo' ).'" class="form-control style2" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div></div></div>',
					),
					'label_submit'  => esc_html__( 'Submit', 'listdo' ),
					'logged_in_as'  => '',
					'comment_field' => ''
				);

				$comment_form['comment_field'] .= '<div class="form-group"><textarea placeholder="'.esc_attr__( 'Write Comment', 'listdo' ).'" class="form-control style2" name="comment" cols="45" rows="5" aria-required="true" placeholder="'.esc_attr__( 'Write Comment', 'listdo' ).'"></textarea></div>';
				
				$allowed_html_array = array( 'a' => array('href' => array()) );
	        	$comment_form['must_log_in'] = '<p class="must-log-in">' .wp_kses(__( 'You must be <a href="">logged in</a> to reply.', 'listdo' ), $allowed_html_array). '</p>';
				
				listdo_comment_form($comment_form);
			?>
		</div>
		<div id="review_form">
			<?php
				add_action( 'comment_form_top', 'listdo_listing_review_rating_field_display', 10 );
				
				add_action( 'comment_form_top', 'listdo_listing_review_displayUploadField', 11 );

				$comment_form = array(
					'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'listdo' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'listdo' ), get_the_title() ),
					'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'listdo' ),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<div class="row"><div class="col-xs-12 col-sm-6"><div class="form-group"><label>'.esc_html__( 'Name', 'listdo' ).'</label>'.
						            '<input id="author" placeholder="'.esc_attr__( 'Your Name', 'listdo' ).'" class="form-control style2" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div></div>',
						'email'  => '<div class="col-xs-12 col-sm-6"><div class="form-group"><label>'.esc_html__( 'Email', 'listdo' ).'</label>' .
						            '<input id="email" placeholder="'.esc_attr__( 'your@mail.com', 'listdo' ).'" class="form-control style2" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div></div></div>',
					),
					'label_submit'  => esc_html__( 'Submit Review', 'listdo' ),
					'logged_in_as'  => '',
					'comment_field' => ''
				);

				
				$allowed_html_array = array( 'a' => array('href' => array()) );
	        	$comment_form['must_log_in'] = '<p class="must-log-in">' .wp_kses(__( 'You must be <a href="">logged in</a> to post a review.', 'listdo' ), $allowed_html_array). '</p>';
				

				$comment_form['comment_field'] = '<div class="form-group"><label>'.esc_html__( 'Review', 'listdo' ).'</label><textarea class="form-control style2" placeholder="'.esc_attr__( 'Your review', 'listdo' ).'" name="comment" cols="45" rows="5" aria-required="true"></textarea></div>';
				
				listdo_comment_form($comment_form);
			?>
		</div>
	</div>
</div>