<div class="price_slider_wrapper">
	<?php
		$min_default = listdo_get_config('listing_filter_price_min_default', 0);
		$max_default = listdo_get_config('listing_filter_price_max_default', 1000000);
		$min = ! empty( $_REQUEST['filter-price-from'] ) ? esc_attr( $_REQUEST['filter-price-from'] ) : $min_default;
		$max = ! empty( $_REQUEST['filter-price-to'] ) ? esc_attr( $_REQUEST['filter-price-to'] ) : $max_default;

	?>
	<div class="price-wrapper"><?php echo esc_html__('Price: ', 'listdo'); ?> 
		<span class="price">
			<span class="price_from"><?php listdo_listing_display_price($min); ?></span>
			<span class="space">-</span>
			<span class="price_to"><?php listdo_listing_display_price($max); ?></span>
		</span>
	</div>
  	<div class="price_range_slider" data-max="<?php echo esc_attr($max_default); ?>" data-min="<?php echo esc_attr($min_default); ?>"></div>

  	<input type="hidden" name="filter_price_from" class="filter-price-from" value="<?php echo esc_attr($min); ?>">
  	<input type="hidden" name="filter_price_to" class="filter-price-to" value="<?php echo esc_attr($max); ?>">
</div>