<?php

global $post;

$terms = get_the_terms(get_the_ID(), 'job_listing_category');
$termString = '';
if ( is_array($terms) || is_object($terms) ) {
	$count = 1;
	foreach ( $terms as $term ) {
		$termString .= $term->name;
		if ( $count != count($terms) ) {
			$termString .= ', ';
		}
		$count++;
	}
}

$listing_is_claimed = false;
if ( class_exists( 'WP_Job_Manager_Claim_Listing' ) ) {
	$classes = WP_Job_Manager_Claim_Listing()->listing->add_post_class( array(), '', $post->ID );

	if ( isset( $classes[0] ) && ! empty( $classes[0] ) ) {
		if ( $classes[0] == 'claimed' )
			$listing_is_claimed = true;
	}
} ?>
<div class="apus-single-listing" itemscope itemtype="http://schema.org/JobPosting"
	data-latitude="<?php echo esc_attr(get_post_meta($post->ID, 'geolocation_lat', true)); ?>"
	data-longitude="<?php echo esc_attr(get_post_meta($post->ID, 'geolocation_long', true)); ?>"
	data-categories="<?php echo esc_attr($termString); ?>">
	<meta itemprop="title" content="<?php echo esc_attr( $post->post_title ); ?>" />

	<?php if ( get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
		<div class="job-manager-info"><?php esc_html_e( 'This listing has expired.', 'listdo' ); ?></div>
	<?php else : ?>
		<div class="row">
			<div class="col-md-8 column-content  entry-content">
				<header class="entry-header">
					<?php
					// listing breadscrumb
					?>

					<h1 class="entry-title" itemprop="name">
						<?php echo get_the_title();
						if ( $listing_is_claimed ) {
							?>
							<span class="listing-claimed-icon"> <i class="fas fa-check"></i> <span>
					<?php } ?>
					</h1>
					<?php the_company_tagline( '<span class="entry-subtitle" itemprop="description">', '</span>' ); ?>

					<?php
					/**
					 * single_job_listing_start hook
					 *
					 * @hooked job_listing_meta_display - 20
					 * @hooked job_listing_company_display - 30
					 */
					do_action( 'single_job_listing_start' );
					?>
				</header><!-- .entry-header -->

				<div class="listing-main-content">
					<div class="listing-video">
						<!-- Video -->
						<?php the_company_video(); ?>
					</div>
					<div class="job_description" itemprop="description">
						<?php the_content(); ?>
					</div>
					<div class="listing-review">
						<!-- Review -->
						<?php comments_template(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-4 column-sidebar">
				<div class="listing-map">
					<div id="apus-listing-map" class="apus-single-listing-map"></div>
					<?php the_job_location(); ?>
				</div>
				<div class="listing-contact listing-widget">
					<!-- contact -->
					<h3 class="widget-title"><?php esc_html_e( 'Contact', 'listdo' ); ?></h3>
					<?php get_template_part( 'job_manager/single/parts/contact' ); ?>
				</div>
				<div class="listing-photos listing-widget">
					<!-- Photos -->
					<?php get_template_part( 'job_manager/single/parts/photos' ); ?>
				</div>
				<div class="listing-hours listing-widget">
					<!-- Open Hours -->
					<?php get_template_part( 'job_manager/single/parts/hours' ); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>