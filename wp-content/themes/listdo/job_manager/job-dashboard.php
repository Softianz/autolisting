<div id="job-manager-job-dashboard">
	<div class="job-manager-jobs clearfix">
		<?php if ( ! $jobs ) : ?>
			<div class="text-warning">
				<?php esc_html_e( 'You do not have any active listings.', 'listdo' ); ?>
			</div>
		<?php else : ?>	
			<div class="box-list">
				<h3 class="title"><i class="flaticon-list"></i><?php esc_html_e( 'My Listings', 'listdo' ); ?></h3>
				<div class="clearfix">
					<?php foreach ( $jobs as $job ) { ?>
						<?php get_job_manager_template( 'job_manager/loop/list-my-listing.php', array('job' => $job) ); ?>
					<?php } ?>
					<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>