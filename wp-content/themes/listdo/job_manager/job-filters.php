<?php wp_enqueue_script( 'wp-job-manager-ajax-filters' ); ?>

<?php
	do_action( 'job_manager_job_filters_before', $atts );
	$atts['atts'] = $atts;
	$atts['keywords'] = $keywords;
	
?>
<?php get_job_manager_template( 'job-filters-v1.php', $atts ); ?>
