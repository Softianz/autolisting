<?php
	$price_ranges = listdo_job_manager_price_range_icons();
	$price_range_default = isset( $_REQUEST['filter_price_range'] ) ? $_REQUEST['filter_price_range'] : array();
	$rand = rand(100, 9999);
?>
<div class="search_price_range clearfix">
	
	<select class="filter_price_range" data-placeholder="<?php esc_attr_e( 'Price Range', 'listdo' ); ?>" name="filter_price_range">
		<option value=""><?php esc_html_e( 'Price Range', 'listdo' ); ?></option>
		<?php foreach ($price_ranges as $key => $label) : ?>
			<option value="<?php echo esc_attr($key); ?>" <?php echo trim($key == $price_range_default ? 'selected="selected"' : ''); ?>><?php echo esc_attr($label['icon'].' '.$label['label']); ?></option>
		<?php endforeach; ?>
	</select>
	
</div>