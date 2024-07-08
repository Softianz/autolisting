<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !is_user_logged_in() ) {
    ?>
    <div class="alert alert-warning"><?php esc_html_e('You need login to edit review.', 'listdo'); ?></div>
    <?php
}
$comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : 0;
if ( empty($comment_id) ) {
    ?>
    <div class="alert alert-warning"><?php esc_html_e('This review is not exists.', 'listdo'); ?></div>
    <?php
}
$comment = get_comment($comment_id);
if ( !empty($comment) ) {
	$rating = get_comment_meta( $comment_id, '_apus_rating', true );
	?>
	<div class="edit-review-form-wrapper">
		<h3 class="margin-top-1"><?php esc_html_e('Edit review', 'listdo'); ?></h3>
		<form class="edit-review-form">
			<input type="hidden" name="comment_id" value="<?php echo esc_attr($comment_id); ?>">
			<input type="hidden" name="post_id" value="<?php echo esc_attr($comment->comment_post_ID); ?>">
			<?php wp_nonce_field( 'edit-review', 'edit-review-nonce' ); ?>

			<?php
				if ( metadata_exists('comment', $comment_id, '_apus_rating') && empty($comment->comment_parent) ) {
					echo listdo_listing_review_rating_field($rating);
				}
			?>

			<div class="form-group">
				<label><?php esc_html__( 'Review', 'listdo' ); ?></label>
				<textarea class="form-control style2" placeholder="<?php esc_attr_e( 'Your review', 'listdo' ); ?>" name="comment" cols="45" rows="5" aria-required="true"><?php echo esc_attr($comment->comment_content); ?></textarea>
			</div>

			<button class="btn btn-theme btn-edit-review"><?php esc_html_e('Save', 'listdo'); ?></button>
		</form>
	</div>
	<?php
} else {
	?>
    <div class="alert alert-warning"><?php esc_html_e('This review is not exists.', 'listdo'); ?></div>
    <?php
}
