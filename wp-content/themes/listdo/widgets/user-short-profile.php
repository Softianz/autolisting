<?php
extract( $args );
extract( $instance );
global $apus_author;
$user = $apus_author;
$data = get_userdata( $user->ID );
$url = listdo_get_user_url($user->ID, $data->user_nicename);
$url_listings = listdo_get_user_url($user->ID, $data->user_nicename, array('action' => 'listings'));
$url_bookmarks = listdo_get_user_url($user->ID, $data->user_nicename, array('action' => 'bookmarks'));
$url_reviews = listdo_get_user_url($user->ID, $data->user_nicename, array('action' => 'reviews'));
$url_following = listdo_get_user_url($user->ID, $data->user_nicename, array('action' => 'following'));
$url_follower = listdo_get_user_url($user->ID, $data->user_nicename, array('action' => 'follower'));
$address = get_the_author_meta( 'apus_address', $user->ID );
$action = !empty($_GET['action']) ? $_GET['action'] : '';
$registered = $data->user_registered;
?>
<div class="widget-view-user-profile">
	<div class="container">
	<div class="user-heading">
		<div class="row row-30 flex-bottom">
			<div class="col-xs-12 col-md-8">
				<div class="inner-left flex-middle">
					<div class="user-avatar">
						<a href="<?php echo esc_url($url); ?>">
							<?php echo get_avatar($user->ID,100); ?>
						</a>
					</div>
					<div class="wrapper-title">
						<h3 class="author"><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($data->display_name); ?></a></h3>
						<?php if ( !empty($address) ) { ?>
							<div class="address"><?php echo esc_attr($address); ?></div>
						<?php } ?>
						<div class="date-created">
							<?php 
								printf(esc_html__('Member Since : %s', 'listdo'), date( "M Y", strtotime( $registered ) )  );
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="inner-right pull-right">
					<?php if ( $show_user_following_link ) { ?>
						<span <?php echo trim($action == "following"? 'class="active"' : ''); ?>><a class="btn" href="<?php echo esc_url($url_following); ?>"><i class="ap-following"></i> <?php esc_html_e('Following', 'listdo'); ?>
							<?php
							$count = 0;
							$user_ids = get_user_meta( $apus_author->ID, '_apus_following', true );
							if ( !empty($user_ids) && is_array($user_ids) ) {
								$count = count($user_ids);
							}
							?>
							(<?php echo intval($count); ?>)
						</a></span>
					<?php } ?>
					<?php if ( $show_user_follower_link ) { ?>
						<span <?php echo trim($action == "follower"? 'class="active"' : ''); ?> ><a class="btn" href="<?php echo esc_url($url_follower); ?>"><i class="ap-followers"></i> <?php esc_html_e('Follower', 'listdo'); ?>
							<?php
							$count = 0;
							$user_ids = get_user_meta( $apus_author->ID, '_apus_followers', true );
							if ( !empty($user_ids) && is_array($user_ids) ) {
								$count = count($user_ids);
							}
							?>
							(<?php echo intval($count); ?>)
						</a></span>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="clearfix inner-bottom-tab">
		<div class="container">
		<ul class="user-menu navbar-nav">
			<?php if ( $show_user_profile_link ) { ?>
				<li <?php echo trim($action == ''? 'class="active"' : ''); ?>><a href="<?php echo esc_url($url); ?>"> <?php esc_html_e('Profile', 'listdo'); ?></a></li>
			<?php } ?>
			<?php if ( $show_user_listings_link ) { ?>
				<li <?php echo trim($action == "listings"? 'class="active"' : ''); ?>><a href="<?php echo esc_url($url_listings); ?>"><?php esc_html_e('Listing', 'listdo'); ?>
					<?php
						$args = array(
							'post_type'           => 'job_listing',
							'post_status'         => array( 'publish' ),
							'ignore_sticky_posts' => 1,
							'posts_per_page'      => 1,
							'orderby'             => 'date',
							'order'               => 'desc',
							'author'              => $user->ID
						);

						$jobs_query = new WP_Query;
						$jobs = $jobs_query->query( $args );
						$count = $jobs_query->max_num_pages;
					?>
					(<?php echo intval($count); ?>)
					</a></li>
			<?php } ?>
			<?php if ( $show_user_bookmarks_link ) { ?>
				<li <?php echo trim($action == "bookmarks"? 'class="active"' : ''); ?>><a href="<?php echo esc_url($url_bookmarks); ?>"> <?php esc_html_e('Bookmark', 'listdo'); ?>
					<?php
					$ids = get_user_meta($apus_author->ID, '_bookmark', true);
					$count = 0;
					if ( !empty($ids) ) {
						$args = array(
							'post_type'           => 'job_listing',
							'post_status'         => array( 'publish' ),
							'ignore_sticky_posts' => 1,
							'posts_per_page'      => 1,
							'post__in' => $ids
						);

						$jobs_query = new WP_Query;
						$jobs = $jobs_query->query( $args );
						$count = $jobs_query->max_num_pages;
					}
					?>
					(<?php echo intval($count); ?>)
				</a></li>
			<?php } ?>
			<?php if ( $show_user_reviews_link ) { ?>
				<li <?php echo trim($action == "reviews"? 'class="active"' : ''); ?>><a href="<?php echo esc_url($url_reviews); ?>"><?php esc_html_e('Reviews', 'listdo'); ?>
					<?php
					$args = array( 'user_id' => $apus_author->ID );
					$comments = listdo_get_review_comments( $args );
					?>
					(<?php echo count($comments); ?>)
				</a></li>
			<?php } ?>
		</ul>
		<?php if ( is_user_logged_in() && get_current_user_id() != $user->ID ) {
			$check = listdo_check_follow_user($user->ID);
		?>
			<div class="fllow pull-right">
				<a href="#follow-btn" class="btn btn-theme btn-block btn-follow-following <?php echo esc_attr($check ? 'btn-following-user' : 'btn-follow-user'); ?>" data-id="<?php echo esc_attr($user->ID); ?>">
					<?php if ( $check ) { ?>
						<span class="text-following"><?php esc_html_e('Following', 'listdo'); ?></span>
						<span class="text-following-hover"><?php esc_html_e('Unfollow', 'listdo'); ?></span>
					<?php } else { ?>
						<?php esc_html_e('Follow', 'listdo'); ?>
					<?php } ?>
				</a>
			</div>
		<?php } ?>
		</div>
	</div>
</div>