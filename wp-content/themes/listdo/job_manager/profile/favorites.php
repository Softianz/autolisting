<?php
if ( !class_exists('Listdo_Bookmark') ) {
	return;
}
global $apus_author;
$posts_per_page = listdo_get_config('user_profile_bookmark_number', 25);
$ids = get_user_meta($apus_author->ID, '_bookmark', true);
if ( empty($ids) ) {
	?>
	<div id="job-manager-job-dashboard">
		<div class="job-manager-jobs clearfix">
			<div class="text-warning">
			<?php esc_html_e( 'You do not have any listings bookmark.', 'listdo' ); ?>
			</div>
		</div>
	</div>
	<?php
	return;
}
$args = array(
	'post_type'           => 'job_listing',
	'post_status'         => array( 'publish' ),
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => $posts_per_page,
	'offset'              => ( max( 1, get_query_var('paged') ) - 1 ) * $posts_per_page,
	'post__in' => $ids
);

$jobs_query = new WP_Query;
$jobs = $jobs_query->query( $args );
$max_num_pages = $jobs_query->max_num_pages;
?>

<div id="job-manager-job-dashboard">
	<div class="box-user box-list">
		<h3 class="title"><i class="flaticon-heart"></i><?php echo esc_html__('Your Bookmark','listdo') ?></h3>
		<?php if ( ! $jobs ) : ?>
			<div class="text-warning">
				<?php esc_html_e( 'You do not have any listings bookmark.', 'listdo' ); ?>
			</div>
		<?php else :?>
			<div class="content-inner">
				<?php foreach ( $jobs as $job ) { ?>
					<?php get_job_manager_template( 'job_manager/loop/list-bookmark.php', array('job' => $job, 'remove_action' => false) ); ?>
				<?php } ?>
			</div>
		<?php endif; ?>
	</div>
	<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
</div>
