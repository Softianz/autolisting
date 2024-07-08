<?php
global $apus_author;
$posts_per_page = listdo_get_config('user_profile_listing_number', 25);
$args = array(
	'post_type'           => 'job_listing',
	'post_status'         => array( 'publish' ),
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => $posts_per_page,
	'offset'              => ( max( 1, get_query_var('paged') ) - 1 ) * $posts_per_page,
	'orderby'             => 'date',
	'order'               => 'desc',
	'author'              => $apus_author->ID
);

$jobs_query = new WP_Query;
$jobs = $jobs_query->query( $args );
$max_num_pages = $jobs_query->max_num_pages;
?>

<div id="job-manager-job-dashboard">
	<div class="box-list box-user">
		<h3 class="title"><i class="flaticon-layers"></i><?php echo esc_html__('My Listings','listdo') ?></h3>
		<?php if ( ! $jobs ) : ?>
			<div class="text-warning">
				<?php esc_html_e( 'You do not have any active listings.', 'listdo' ); ?>
			</div>
		<?php else :?>
			<div class="inner">
				<?php foreach ( $jobs as $job ) {
					setup_postdata( $GLOBALS['post'] =& $job );
				?>
					<?php get_job_manager_template( 'job_manager/loop/list-user-listing.php', array('job' => $job) ); ?>
				<?php }
					wp_reset_postdata();
				?>
				<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>