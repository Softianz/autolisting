<div class="search_location">
	<?php
		$search_location = isset( $_REQUEST['search_location'] ) ? $_REQUEST['search_location'] : '';
		$search_lat = isset( $_REQUEST['search_lat'] ) ? $_REQUEST['search_lat'] : '';
		$search_lng = isset( $_REQUEST['search_lng'] ) ? $_REQUEST['search_lng'] : '';
		
	?>
	<input type="text" class="form-control style2" name="search_location" id="search_location<?php echo esc_attr(listdo_get_config('listing_filter_show_distance') ? '_distance' : ''); ?>" placeholder="<?php esc_attr_e('Location', 'listdo'); ?>" value="<?php echo esc_attr($search_location); ?>" autocomplete="off" />
	<span class="clear-location"><i class="ti-close"></i></span>
	<?php if ( listdo_get_config('listing_filter_show_distance') ) { ?>
		<span class="loading-me"></span>
		<span class="find-me"><?php get_template_part( 'images/icon/location' ); ?></span>
		<input type="hidden" name="search_lat" value="<?php echo esc_attr($search_lat); ?>" />
		<input type="hidden" name="search_lng" value="<?php echo esc_attr($search_lng); ?>" />
	<?php } ?>
</div>