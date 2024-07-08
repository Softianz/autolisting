<?php
global $post;
?>
<div class="apus-single-listing">
	<meta itemprop="title" content="<?php echo esc_attr( $post->post_title ); ?>" />
	<?php
	$cover_image = listdo_get_post_image_src($post->ID);
	if ( $cover_image ) { ?>
        <meta property="og:image" content="<?php echo esc_url($cover_image); ?>"/>
    <?php } ?>
	
	<?php if ( get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
		<div class="job-manager-info"><?php esc_html_e( 'This listing has expired.', 'listdo' ); ?></div>
	<?php else : ?>
		<div class="row">
			<div class="col-md-8 column-content entry-content">
				<div class="listing-main-content">
					<?php
					if ( !empty($block_contents) ) {
						foreach ($block_contents as $key => $content) {
							echo trim($content);
						}
					}
					?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="sidebar sidebar-detail-job sidebar-right">
					<div class="close-sidebar-btn hidden-lg hidden-md"> <i class="ti-close"></i> <span><?php esc_html_e('Close', 'listdo'); ?></span></div>
					
					<?php if ( is_active_sidebar( 'listing-sidebar-above' ) ) : ?>
						<div class="widget-area" role="complementary">
							<?php dynamic_sidebar( 'listing-sidebar-above' ); ?>
						</div>
					<?php endif; ?>

					<?php
					$contents = listdo_get_sidebar_content_sort();
					if ( !empty($contents) ) {
						foreach ($contents as $key => $title) {
							echo trim(listdo_listing_display_part($key));
						}
					}
					?>
					<?php if ( is_active_sidebar( 'listing-sidebar-bellow' ) ) : ?>
						<div class="widget-area" role="complementary">
							<?php dynamic_sidebar( 'listing-sidebar-bellow' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>