<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;

$url = '';
if ( !empty($comment->user_id) ) {
	$data = get_userdata( $comment->user_id );
	$url = listdo_get_user_url($comment->user_id, $data->user_nicename);
}

$enable_like = listdo_get_config('listing_review_enable_like_review', true);
$enable_dislike = listdo_get_config('listing_review_enable_dislike_review', true);
$enable_love = listdo_get_config('listing_review_enable_love_review', true);
$enable_reply = listdo_get_config('listing_review_enable_reply_review', true);
$enable_edit = listdo_get_config('listing_review_enable_user_edit_his_review', true);

?>
<li itemprop="review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>" class="the-comment">
		<div class="avatar">
			<?php if ( !empty($url) ) { ?>
				<a href="<?php echo esc_url($url); ?>">
			<?php } ?>
				<?php echo get_avatar( $comment->user_id, '85', '' ); ?>
			<?php if ( !empty($url) ) { ?>
				</a>
			<?php } ?>
		</div>
		<div class="comment-box">
			<div class="comment-author meta">
				<div class="info-meta-comment clearfix">
					<div class="pull-left">
						<h3 class="title-author">
							<?php if ( !empty($url) ) { ?>
								<a href="<?php echo esc_url($url); ?>">
							<?php } ?>
								<?php comment_author(); ?>
							<?php if ( !empty($url) ) { ?>
								</a>
							<?php } ?>
						</h3> 
						<div class="flex-middle">

							<?php if ( listdo_listing_review_rating_enable() ) {						
								$rating = get_comment_meta( $comment->comment_ID, '_apus_rating_avg', true );
								if ( $rating > 0 ) {
									$rating_mode = listdo_get_config('listing_review_mode', 10);
								?>
									<div class="star-average-rating">
										<div class="star-average-inner" style="width: <?php echo round(($rating/$rating_mode * 100), 2).'%'; ?>"></div>
									</div>

								<?php } ?>
							<?php } ?>

							<?php if ( $comment->comment_approved == '0' ) : ?>
								<span class="meta"><em><?php esc_html_e( 'Your comment is awaiting approval', 'listdo' ); ?></em></span>
							<?php else : ?>
								<span class="date">
									<time itemprop="datePublished" datetime="<?php echo get_comment_date( get_option('date_format') ); ?>"><?php echo get_comment_date( get_option('date_format') ); ?></time>
								</span>
							<?php endif; ?>
							
						</div>
					</div>
				</div>
			</div>
			<div class="comment-text">
				<div class="description">
					<?php comment_text(); ?>
				</div>
				<?php echo Listdo_Attachments::displayAttachment(); ?>
			</div>
			<div id="comment-reply-wrapper-<?php comment_ID(); ?>" class="comment-actions">
				<div id="comment-actions-<?php comment_ID(); ?>" class="clearfix flex-middle-sm">
					<div class="left-action">
						<?php if ( $enable_like ) {
							$count = intval( get_comment_meta( $comment->comment_ID, '_apus_like', true ) );
							$check = listdo_check_comment_like_user($comment->comment_ID);
							?>
							<a href="#like-commnent-<?php comment_ID(); ?>" class="comment-like <?php echo esc_attr($check ? 'active' : ''); ?>" data-id="<?php comment_ID(); ?>"><i class="flaticon-like"></i>
								<?php echo esc_html__('Like', 'listdo'); ?>
								<?php echo trim($count); ?></a>
						<?php } ?>

						<?php if ( $enable_dislike ) {
							$count = intval( get_comment_meta( $comment->comment_ID, '_apus_dislike', true ) );
							$check = listdo_check_comment_dislike_user($comment->comment_ID);
							?>
							<a href="#dislike-commnent-<?php comment_ID(); ?>" class="comment-dislike <?php echo esc_attr($check ? 'active' : ''); ?>" data-id="<?php comment_ID(); ?>" ><i class="flaticon-dislike"></i> 
								<?php echo esc_html__('Dislike', 'listdo'); ?>
								<?php echo trim($count); ?></a>
						<?php } ?>

						<?php if ( $enable_love ) {
							$count = intval( get_comment_meta( $comment->comment_ID, '_apus_love', true ) );
							$check = listdo_check_comment_love_user($comment->comment_ID);
							?>
							<a href="#love-commnent-<?php comment_ID(); ?>" class="comment-love <?php echo esc_attr($check ? 'active' : ''); ?>" data-id="<?php comment_ID(); ?>"><i class="flaticon-heart"></i> 
								<?php echo esc_html__('Love', 'listdo'); ?>
								<?php echo trim($count); ?></a>
						<?php } ?>
					</div>
					<div class="right-action ali-right">
						<?php if ( $enable_reply ) {
							$max_depth = !empty($args['max_depth']) ? $args['max_depth'] : '';
						?>
							<?php comment_reply_link(array_merge( $args, array(
								'reply_text' => '<i class="ti-comment"></i> '.esc_html__('Reply', 'listdo'),
								'add_below' => 'comment-reply-wrapper',
								'depth' => 1,
								'max_depth' => $max_depth
							))) ?>
						<?php } ?>

						<?php if ( $enable_edit && listdo_check_is_review_owner($comment) ) { ?>
							<a href="javascript:void(0);" class="comment-edit-link listdo-edit-comment" data-id="<?php comment_ID(); ?>"><i class="ti-pencil-alt"></i> <?php esc_html_e('Edit', 'listdo'); ?></a>
						<?php } ?>
					</div>

				</div>
			</div>
		</div>
	</div>
</li>