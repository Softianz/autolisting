<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Listdo
 * @since Listdo 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div class="wrapper-comment">
	<?php if ( have_comments() ) : ?>
		<div class="box-list">
			<div class="top-comment">
		        <h3 class="comments-title"><?php comments_number( esc_html__('0 Comments', 'listdo'), esc_html__('1 Comment', 'listdo'), esc_html__('% Comments', 'listdo') ); ?></h3>
				<?php listdo_comment_nav(); ?>
				<ol class="comment-list">
					<?php wp_list_comments('callback=listdo_list_comment'); ?>
				</ol><!-- .comment-list -->

				<?php listdo_comment_nav(); ?>
			</div>
		</div>
	<?php endif; // have_comments() ?>
	<div id="comments" class="comments-area">
		<?php
			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'listdo' ); ?></p>
		<?php endif; ?>

		<?php
	        $aria_req = ( $req ? " aria-required='true'" : '' );
	        $comment_args = array(
	                        'title_reply'=> '<h4 class="title">'.esc_html__('Leave a Comment','listdo').'</h4>',
	                        'comment_field' => '<div class="form-group space-bottom-30">
	                        					<label class="hidden">'.esc_html__('Comment', 'listdo').'</label>
	                                                <textarea rows="6" placeholder="'.esc_attr__('Your Comment', 'listdo').'" id="comment" class="form-control"  name="comment"'.$aria_req.'></textarea>
	                                            </div>',
	                        'fields' => apply_filters(
	                        	'comment_form_default_fields',
		                    		array(
		                                'author' => '<div class="row"><div class="col-xs-12 col-sm-6"><div class="form-group ">
		                                			<label class="hidden">'.esc_html__('Name', 'listdo').'</label>
		                                            <input type="text" placeholder="'.esc_attr__('Name', 'listdo').'"   name="author" class="form-control" id="author" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' />
		                                            </div></div>',
		                                'email' => ' <div class="col-xs-12 col-sm-6"><div class="form-group ">
		                               				 <label class="hidden">'.esc_html__('Email', 'listdo').'</label>
		                                            <input id="email" placeholder="'.esc_attr__(' Email', 'listdo').'"  name="email" class="form-control" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' />
		                                            </div></div>',
		                                'url' => '<div class="col-xs-12 col-sm-12 hidden"><div class="form-group ">
	                                				<label class="hidden">'.esc_html__('Website', 'listdo').'</label>
		                                            <input id="url" placeholder="'.esc_attr__('Website', 'listdo').'" name="url" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '"  />
		                                            </div></div></div>',
		                            )
								),
		                        'label_submit' => esc_html__('Post Comment', 'listdo'),
								'comment_notes_before' => '<div class="form-group h-info">'.esc_html__('Your email address will not be published.','listdo').'</div>',
								'comment_notes_after' => '',
	                        );
        	$allowed_html_array = array( 'a' => array('href' => array()) );

	        $comment_args['must_log_in'] = '<p class="must-log-in">' .wp_kses(__( 'You must be <a href="">logged in</a> to post a review.', 'listdo' ), $allowed_html_array). '</p>';
	    ?>

		<?php listdo_comment_form($comment_args); ?>
	</div><!-- .comments-area -->
</div>