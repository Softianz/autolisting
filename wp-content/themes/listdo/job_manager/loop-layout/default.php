<?php 
	$sidebar_position = listdo_get_archive_layout();
?>
<div class="apus-grid-layout style-grid container apus-listing-warpper <?php echo esc_attr(($sidebar_position == 'main')?'only_main':'has-sidebar'); ?>">
	<div class="row">
		<?php echo trim($html_content); ?>
	</div>
</div>