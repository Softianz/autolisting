<?php
global $post;
?>
<div id="listing-description" class="job_description" itemprop="description">
	<h2 class="widget-title">
		<i class="flaticon-menu"></i><span><?php esc_html_e('Description', 'listdo'); ?></span>
	</h2>
	<div class="box-inner">
		<?php
			$obj = WP_Job_Manager_Post_Types::instance();
			call_user_func( implode('_', array('remove', 'filter')), 'the_content', array( $obj, 'job_content' ) );
			the_content();
		?>

		<?php do_action('listdo-single-listing-description', $post); ?>
	</div>
</div>