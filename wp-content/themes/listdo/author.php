<?php
get_header();
$sidebar_configs = listdo_get_blog_layout_configs();
global $apus_author;
$apus_author = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

$style = '';
$bg_img = listdo_get_config('profile_background_image');
if ( isset($bg_img['url']) && !empty($bg_img['url']) ) {
    $style = 'style="background-image:url(\''.esc_url($bg_img['url']).'\');"';
}
?>
<section class="breadscrumb-author" <?php echo trim($style); ?> >
	<?php if ( is_active_sidebar( 'view-profile-sidebar' ) ): ?>
		<?php dynamic_sidebar( 'view-profile-sidebar' ); ?>
	<?php endif; ?>
</section>
<section id="main-container" class="main-content content-space container inner">
	<a href="javascript:void(0)" class="mobile-sidebar-btn hidden-lg hidden-md"> <i class="fas fa-bars"></i> <?php echo esc_html__('Show Sidebar', 'listdo'); ?></a>
	<div class="mobile-sidebar-panel-overlay"></div>
	
	<div class="row row-30">
		<div id="main-content" class="col-md-8 col-xs-12">
			<main id="main" class="site-main layout-user" role="main">
				<?php
					$action = isset($_GET['action']) ? $_GET['action'] : '';
					switch ($action) {
						case 'following':
							$user_ids = get_user_meta( $apus_author->ID, '_apus_following', true );
							get_job_manager_template( 'job_manager/profile/users.php', array('user_ids' => $user_ids, 'member' => 1 ) );
							break;
						case 'follower':
							$user_ids = get_user_meta( $apus_author->ID, '_apus_followers', true );
							get_job_manager_template( 'job_manager/profile/users.php', array('user_ids' => $user_ids, 'member' => 1 ) );
							break;
						case 'listings':
							get_template_part( 'job_manager/profile/listings' );
							break;
						case 'bookmarks':
							get_template_part( 'job_manager/profile/favorites' );
							break;
						case 'reviews':
							get_template_part( 'job_manager/profile/reviews' );
							break;
						default:
							get_template_part( 'job_manager/profile/user-profile' );
							break;
					}
				?>
			</main><!-- .site-main -->
		</div><!-- .content-area -->
		<div class="col-xs-12 col-md-4 sidebar sidebar-right">
			<?php if ( is_active_sidebar( 'view-profile-right-sidebar' ) ): ?>
				<?php dynamic_sidebar( 'view-profile-right-sidebar' ); ?>
			<?php endif; ?>
			<?php if ( listdo_get_config('user_profile_show_contact_form', 1) ) { ?>
				<div class="user-contact-form">
					<?php get_template_part( 'job_manager/profile/contact-form' ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>