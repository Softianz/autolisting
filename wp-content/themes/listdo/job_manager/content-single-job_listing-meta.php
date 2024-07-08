<?php
/**
 * Single view Job meta box
 *
 * Hooked into single_job_listing_start priority 20
 *
 * @since  1.14.0
 */
global $post;

return;

do_action( 'single_job_listing_meta_before' ); ?>

<ul class="job-listing-meta meta">
	<?php do_action( 'single_job_listing_meta_start' ); ?>

	<li class="job-type <?php echo get_the_job_type() ? sanitize_title( get_the_job_type()->slug ) : ''; ?>" itemprop="employmentType"><i class="fas fa-folder-open"></i> <?php the_job_type(); ?></li>
	<li class="date-posted" itemprop="datePosted"><date><i class="far fa-clock"></i><?php printf( esc_html__( 'Posted %s ago', 'listdo' ), human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ) ); ?></date></li>
	<?php do_action( 'single_job_listing_meta_end' ); ?>
</ul>
<?php do_action( 'single_job_listing_meta_after' ); ?>