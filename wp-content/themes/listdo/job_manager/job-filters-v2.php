<?php
$sidebar_position = listdo_get_archive_layout();

$layout = listdo_get_listing_archive_version();

$class = '';
if ( $layout == 'default' && $sidebar_position == 'main' ) {
	$class = 'col-xs-12';
}

$filter_order = listdo_get_listing_sortby_default();

$atts['atts'] = $atts;
$atts['keywords'] = $keywords;
$atts['filter_version'] = 'v2';

if ( !empty($class) ) {
	?>
	<div class="<?php echo esc_attr($class); ?>">
	<?php
}
?>
	<div class="wrapper-filters2 filter-v2 clearfix">
		<form class="job_filters job_filters-location <?php echo esc_attr(($layout == 'default' && $sidebar_position == 'main')?'top-filters':''); ?>">
			<?php
			$display_mode = listdo_get_listing_display_mode();
			$listing_columns = listdo_get_listing_item_columns();
			?>
			<input type="hidden" name="filter_display_mode" value="<?php echo esc_attr($display_mode); ?>">
			<input type="hidden" name="filter_listing_columns" value="<?php echo esc_attr($listing_columns); ?>">
			<input id="input_filter_order" type="hidden" name="filter_order" value="<?php echo esc_attr($filter_order); ?>">
			<div class="filter-inner clearfix">
				<?php do_action( 'job_manager_job_filters_start', $atts ); ?>
				<div class="fields-filter search_jobs">
					<?php do_action( 'job_manager_job_filters_search_jobs_start', $atts ); ?>
					<div class="clear-flow">
						<div class="row">
							<div class="col-sm-6 list-inner-full">
								<?php get_job_manager_template( 'filter-fields/keyword.php', $atts ); ?>
							</div>
							<?php
								// all types
								if ( listdo_get_config('listing_filter_show_categories') && $show_categories && get_terms( array( 'taxonomy' => 'job_listing_category' ) ) ) {
									?>
									<div class="col-sm-6 list-inner-full">
										<?php get_job_manager_template( 'filter-fields/categories.php', $atts ); ?>
									</div>
									<?php
								}
								
								if ( listdo_get_config('listing_filter_show_types') ) {
									?>
									<div class="col-sm-6 list-inner-full">
										<?php get_job_manager_template( 'filter-fields/types.php', $atts ); ?>
									</div>
									<?php
								}
								if ( listdo_get_config('listing_filter_show_regions') ) {
									?>
										<?php get_job_manager_template( 'filter-fields/regions.php', $atts ); ?>
									
									<?php
								}
								if ( listdo_get_config('listing_filter_show_location') ) {
									?>
									<div class="col-sm-6 list-inner-full">
										<?php get_job_manager_template( 'filter-fields/location.php', $atts ); ?>
									</div>
									<?php
								}
								if ( listdo_get_config('listing_filter_show_location') && listdo_get_config('listing_filter_show_distance') ) {
									?>
									<div class="col-sm-6 list-inner-full">
										<?php get_job_manager_template( 'filter-fields/distance.php', $atts ); ?>
									</div>
									<?php
								}
								if ( listdo_get_config('listing_filter_show_price_range') ) {
									?>
									<div class="col-sm-6 list-inner-full">
										<?php get_job_manager_template( 'filter-fields/price_range.php', $atts ); ?>
									</div>
									<?php
								}
								if ( listdo_get_config('listing_filter_show_price_slider') ) {
									?>
									<div class="col-sm-6 list-inner-full">
										<?php get_job_manager_template( 'filter-fields/price_slider.php', $atts ); ?>
									</div>
									<?php
								}
								
								// all types
								if ( listdo_get_config('listing_filter_show_amenities') ) {
									?>
									<div class="col-xs-12 list-inner-full">
										<?php get_job_manager_template( 'filter-fields/amenities.php', $atts ); ?>
									</div>
									<?php
								}
							?>
						</div>
					</div>
					<div class="submit-filter">
						<button class="btn btn-theme btn-filter" type="button"><?php esc_html_e( 'Filter', 'listdo' ); ?></button>
					</div>
					<div class="listing-search-result-filter"></div>
					<?php do_action( 'job_manager_job_filters_search_jobs_end', $atts ); ?>
				</div>
				<div class="mobile-groups-button hidden-lg hidden-md">
					<button class="btn btn-show-filter btn-xs btn-theme" type="button"><i class="fas fa-sliders-h" aria-hidden="true"></i> <?php esc_html_e( 'Filter Listings', 'listdo' ); ?></button>
					<?php
					$layout = listdo_get_listing_archive_version();
					$layouts = listdo_get_listing_all_half_map_version();
					if( in_array($layout, $layouts)) { ?>
						<button class="pull-right btn btn-xs btn-second btn-view-map" type="button"><i class="far fa-map" aria-hidden="true"></i> <?php esc_html_e( 'Map View', 'listdo' ); ?></button>
						<button class="pull-right btn btn-xs btn-second  btn-view-listing hidden-sm hidden-xs" type="button"><i class="fas fa-list" aria-hidden="true"></i> <?php esc_html_e( 'Listing View', 'listdo' ); ?></button>
					<?php } ?>
				</div>
				<?php do_action( 'job_manager_job_filters_end', $atts ); ?>
			</div>

			<?php if ( is_tax('job_listing_tag') ) {
				global $wp_query;
				$term =	$wp_query->queried_object;
			?>
				<input type="hidden" value="<?php echo esc_attr($term->slug); ?>" name="filter_job_tag[]">
			<?php } ?>
		</form>
		
	</div>
	<?php do_action( 'job_manager_job_filters_after', $atts ); ?>
<?php
if ( !empty($class) ) {
	?>
	</div>
	<?php
}	