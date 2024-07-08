<?php
$data = get_userdata( $user_id );

$url = listdo_get_user_url($user_id, $data->user_nicename);

$args = array(
	'post_type'           => 'job_listing',
	'post_status'         => array( 'publish' ),
	'posts_per_page'      => -1,
	'author'              => $user_id
);

$jobs = new WP_Query($args);
$listing_count = (int)$jobs->post_count;
$followers = get_user_meta( $user_id, '_apus_followers', true );
$followers = !empty($followers) && is_array($followers) ? count($followers) : 0;
$following = get_user_meta( $user_id, '_apus_following', true );
$following = !empty($following) && is_array($following) ? count($following) : 0;

$args = array(
	'user_id' => $user_id,
	'post_type' => 'job_listing'
);
$comments = get_comments( $args );
$comment_count = !empty($comments) ? count($comments) : 0;
$time = strtotime($data->user_registered);
$registed_date = date('F', $time).' '.date('Y', $time);

?>
<div class="user-item">
	<div class="media">
		<div class="media-left media-middle">
			<div class="img-comment">
				<?php if ( !empty($url) ) { ?>
					<a href="<?php echo esc_url($url); ?>">
				<?php } ?>
				<?php echo get_avatar($user_id,80); ?>
				<?php if ( !empty($url) ) { ?>
					</a>
				<?php } ?>
			</div>
		</div>
		<div class="media-body media-middle">
			<h3 class="title-user">
				<?php if ( !empty($url) ) { ?>
					<a href="<?php echo esc_url($url); ?>">
				<?php } ?>
				<?php echo esc_html($data->display_name); ?>
				<?php if ( !empty($url) ) { ?>
					</a>
				<?php } ?>
			</h3>
			<ul class="user-metas">
				<?php if ( $followers > 0 ) { ?>
					<li class="list"><?php echo sprintf(_n('<span>Follower</span> %d', '<span>Followers</span>%d ', $followers, 'listdo'), $followers); ?></li>
				<?php } ?>
				<?php if ( $following > 0 ) { ?>
					<li class="list"><?php echo sprintf(_n('<span>Following</span> %d', '<span>Following</span>%d ', $following, 'listdo'), $following); ?></li>
				<?php } ?>

				<?php if ( $listing_count > 0 ) { ?>
					<li><?php echo sprintf(_n('<span>Listing</span> %d', '<span>Listings</span>%d ', $listing_count, 'listdo'), $listing_count); ?></li>
				<?php } ?>
				<?php if ( $comment_count > 0 ) { ?>
					<li><?php echo sprintf(_n('<span>Comment</span> %d', '<span>Comments</span> %d', $comment_count, 'listdo'), $comment_count); ?></li>
				<?php } ?>
				<?php if ( $registed_date ) { ?>
					<li><span><?php esc_html_e('Member Since:', 'listdo'); ?></span> <?php echo wp_kses_post($registed_date); ?></li>
				<?php } ?>
			</ul>
		</div>
		<div class="media-right media-middle">
			<?php if ( is_user_logged_in() && get_current_user_id() != $user_id ) {
				$check = listdo_check_follow_user($user_id);
			?>
				<a href="#follow-btn" class="btn btn-theme btn-follow-following <?php echo esc_attr($check ? 'btn-following-user' : 'btn-follow-user'); ?>" data-id="<?php echo esc_attr($user_id); ?>">
					<?php if ( $check ) { ?>
						<span class="text-following"><?php esc_html_e('Following', 'listdo'); ?></span>
						<span class="text-following-hover"><?php esc_html_e('Unfollow', 'listdo'); ?></span>
					<?php } else { ?>
						<?php esc_html_e('Follow', 'listdo'); ?>
					<?php } ?>
				</a>
			<?php } ?>
		</div>
	</div>
</div>