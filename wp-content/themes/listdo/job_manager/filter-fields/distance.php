<?php
$distance_type = listdo_get_config('listing_filter_distance_unit', 'km');
$search_distance = isset( $_REQUEST['search_distance'] ) ? $_REQUEST['search_distance'] : listdo_get_config('listing_filter_distance_default', 50);
?>
<div class="search_distance_wrapper clearfix">
	<div class="search-distance-label">
		<label for="use_search_distance">
			<input name="use_search_distance" checked="checked" type="checkbox">
			<?php echo sprintf(wp_kses(__('Radius: <span class="text-distance">%s</span> %s', 'listdo'), array('span' => array('class' => array()))), $search_distance, $distance_type); ?>
		</label>
	</div>
	<div class="search-distance-wrapper">
		<input type="hidden" name="search_distance" value="<?php echo esc_html($search_distance); ?>" />
		<div class="search-distance-slider"><div class="ui-slider-handle distance-custom-handle"></div></div>
	</div>
</div>