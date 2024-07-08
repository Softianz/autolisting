<?php
	global $post;
	$terms = get_the_terms( $post->ID, 'job_listing_category' );
    $term = '';
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    	$term = $terms[0];
    }
    
	if ( !empty($term)) :

		$lat = get_post_meta($post->ID, 'geolocation_lat', true);
		$lng = get_post_meta($post->ID, 'geolocation_long', true);
		$location = get_the_job_location( $post );
		$link = listdo_get_listings_page_url();
		
		$link = add_query_arg( 'search_lat', $lat, remove_query_arg( 'search_lat', $link ) );
		$link = add_query_arg( 'search_lng', $lng, remove_query_arg( 'search_lng', $link ) );
		$link = add_query_arg( 'search_distance', 50, remove_query_arg( 'search_distance', $link ) );
		$link = add_query_arg( 'use_search_distance', 'on', remove_query_arg( 'use_search_distance', $link ) );
		$link = add_query_arg( 'filter_categories[]', $term->term_id, remove_query_arg( 'filter_categories[]', $link ) );
		$link = add_query_arg( 'search_location', strip_tags($location), remove_query_arg( 'search_location', $link ) );

		?>
		<div class="widget nearby">
			<h2 class="widget-title">
				<i class="flaticon-map"></i><span><?php esc_html_e('Nearby listings', 'listdo'); ?></span>
			</h2>
			<div class="box-inner">
				<span><?php echo sprintf(esc_html__('Find more %s near', 'listdo'), $term->name); ?></span>
				<a href="<?php echo esc_url($link); ?>"><strong><?php the_title(); ?></strong></a>

				<?php do_action('listdo-single-listing-nearby', $post); ?>
			</div>
		</div>
	<?php
	endif;
?>