<?php if ( (empty($instance['show_on_homepage']) || !$instance['show_on_homepage']) && !is_front_page() || (!empty($instance['show_on_homepage']) && $instance['show_on_homepage'])  ) : ?>
	<div class="widget-header-search">
		<?php get_job_manager_template( 'job-filters-simple.php', $instance ); ?>
	</div>
<?php endif; ?>