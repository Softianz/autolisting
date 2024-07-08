<?php
$sidebar_position = listdo_get_archive_layout();

$layout = listdo_get_listing_archive_version();
$layouts = listdo_get_listing_all_half_map_version();
$sidebar = listdo_get_listings_sidebar_configs();

$wrapper_class = 'col-md-4 col-xs-12 '.$sidebar['class'];

if ( $sidebar_position == 'main' ) {
	$wrapper_class = 'col-xs-12';
}
if ( in_array($layout, $layouts) || ($layout == 'default' && $sidebar_position == 'main') ) {
	$wrapper_class = '';
}

$filter_order = listdo_get_listing_sortby_default();
$atts['atts'] = $atts;
$atts['keywords'] = $keywords;
$atts['filter_version'] = 'v1';

if ( !empty($wrapper_class) ) {
	?>
	<div class="<?php echo esc_attr($wrapper_class); ?>">
	<?php
}
?>
	<div class="wrapper-filters1 filter-v1 clearfix">
			<?php
			$layout = listdo_get_listing_archive_version();
			$layouts = listdo_get_listing_all_half_map_version();

			if( in_array($layout, $layouts)) { ?>
				<div class="mobile-groups-button hidden-lg hidden-md clearfix text-center">
				<button class=" btn btn-sm btn-theme btn-view-map" type="button"><i class="far fa-map"></i> <?php esc_html_e( 'Map View', 'listdo' ); ?></button>
				<button class=" btn btn-sm btn-theme  btn-view-listing hidden-sm hidden-xs" type="button"><i class="fas fa-list"></i> <?php esc_html_e( 'Listing View', 'listdo' ); ?></button>
				</div>
			<?php } ?>
		<span class="show-filter show-filter1 hidden-lg btn btn-xs btn-theme">
			<i class="fas fa-sliders-h"></i>
		</span>
		<form class="job_filters job_filters-location">
			<?php
			$display_mode = listdo_get_listing_display_mode();
			$listing_columns = listdo_get_listing_item_columns();
			?>
			<input type="hidden" name="filter_display_mode" value="<?php echo esc_attr($display_mode); ?>">
			<input type="hidden" name="filter_listing_columns" value="<?php echo esc_attr($listing_columns); ?>">
			<input id="input_filter_order" type="hidden" name="filter_order" value="<?php echo trim($filter_order); ?>">
			<div class="filter-inner search_jobs">
				<?php do_action( 'job_manager_job_filters_start', $atts ); ?>
				<div class="fields-filter list-inner-full">
					<?php do_action( 'job_manager_job_filters_search_jobs_start', $atts ); ?>
					<div class="wrapper-top-filter clearfix">
						<?php
							get_job_manager_template( 'filter-fields/keyword.php', $atts );

							// all types
							if ( listdo_get_config('listing_filter_show_categories') && $show_categories && get_terms( array( 'taxonomy' => 'job_listing_category' ) ) ) {
								get_job_manager_template( 'filter-fields/categories.php', $atts );
							}
							if ( listdo_get_config('listing_filter_show_types') ) {
								get_job_manager_template( 'filter-fields/types.php', $atts );
							}
							if ( listdo_get_config('listing_filter_show_regions') ) {
								get_job_manager_template( 'filter-fields/regions.php', $atts );
							}
							if ( listdo_get_config('listing_filter_show_location') ) {
								get_job_manager_template( 'filter-fields/location.php', $atts );
							}
							if ( listdo_get_config('listing_filter_show_location') && listdo_get_config('listing_filter_show_distance') ) {
								get_job_manager_template( 'filter-fields/distance.php', $atts );
							}
							if ( listdo_get_config('listing_filter_show_price_range') ) {
								get_job_manager_template( 'filter-fields/price_range.php', $atts );
							}
							if ( listdo_get_config('listing_filter_show_price_slider') ) {
								get_job_manager_template( 'filter-fields/price_slider.php', $atts );
							}
						?>
					</div>
					<?php 
						// all types
						if ( listdo_get_config('listing_filter_show_amenities') ) {
							get_job_manager_template( 'filter-fields/amenities.php', $atts );
						}
					?>
					
					<div class="submit-filter">
						<button class="btn btn-filter" type="button"><?php esc_html_e( 'Search', 'listdo' ); ?><i class="flaticon-magnifying-glass"></i></button>
					</div>

					<div class="listing-search-result-filter"></div>
					
					<?php do_action( 'job_manager_job_filters_search_jobs_end', $atts ); ?>
				</div>

				<?php do_action( 'job_manager_job_filters_end', $atts ); ?>

				<?php if ( is_tax('job_listing_tag') ) {
					global $wp_query;
					$term =	$wp_query->queried_object;
				?>
					<input type="hidden" value="<?php echo esc_attr($term->slug); ?>" name="filter_job_tag[]">
				<?php } ?>
			</div>
		</form>
	</div>
	<?php do_action( 'job_manager_job_filters_after', $atts ); ?>

	<?php if ( $layout == 'default' ) { ?>
		<?php if ( is_active_sidebar( $sidebar['sidebar'] ) ): ?>
	  		<aside class="sidebar" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
	   			<?php dynamic_sidebar( $sidebar['sidebar'] ); ?>
	   		</aside>
   		<?php endif; ?>
   	<?php } ?>

<?php
if ( !empty($wrapper_class) ) {
	?>
	</div>
	<?php
}	