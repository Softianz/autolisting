<?php
	global $post;
	$terms = get_the_terms( $post->ID, 'job_listing_category' );
    $termids = array();
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    	foreach ($terms as $term) {
    		$termids[] = $term->term_id;
    	}
    }
    $args = array (
        'taxonomy' => 'job_listing_category',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => true,
        'exclude' => $termids,
        'number' => 0
    );
    $terms = get_terms($args);
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		?>
		<div class="widget browse-nearby">
			<h2 class="widget-title">
				<span><?php esc_html_e('Browse Nearby', 'listdo'); ?></span>
			</h2>
			<div class="box-inner">
				<ul class="listing-cat clearfix">
					<?php

					$lat = get_post_meta($post->ID, 'geolocation_lat', true);
					$lng = get_post_meta($post->ID, 'geolocation_long', true);
					$location = get_the_job_location( $post );
					$link = listdo_get_listings_page_url();
					
					
					$link = add_query_arg( 'search_lat', $lat, remove_query_arg( 'search_lat', $link ) );
					$link = add_query_arg( 'search_lng', $lng, remove_query_arg( 'search_lng', $link ) );
					$link = add_query_arg( 'search_distance', 50, remove_query_arg( 'search_distance', $link ) );
					$link = add_query_arg( 'use_search_distance', 'on', remove_query_arg( 'use_search_distance', $link ) );
					$link = add_query_arg( 'search_location', strip_tags($location), remove_query_arg( 'search_location', $link ) );

					foreach ($terms as $term) {
						$term_link = add_query_arg( 'search_categories[]', $term->term_id, remove_query_arg( 'search_categories[]', $link ) );

						$color_value = get_term_meta( $term->term_id, 'apus_color', true );
						$style = '';
						if ( !empty($color_value) ) {
							$style = 'style=color:'.$color_value;
						}
					?>
						<li>
							<a href="<?php echo esc_url($term_link); ?>" <?php echo esc_attr($style); ?>>
								<span class="category-icon">
									<?php echo trim(listdo_listing_category_icon($term)); ?>
								</span>
								<?php echo esc_attr($term->name);?>
							</a>
						</li>
					<?php } ?>
					<?php do_action('listdo-single-listing-nearby-browse', $post); ?>
				</ul>
				<div class="bottom-link">
					<a href="<?php echo esc_url($link); ?>">
						<?php echo esc_html__('Show all', 'listdo'); ?>
					</a>
				</div>
			</div>
		</div>
	<?php
	}
?>