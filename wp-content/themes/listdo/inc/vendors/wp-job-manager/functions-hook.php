<?php

function listdo_wrap_the_listings( $html ) {
	$output = '';

	$layout_version = listdo_get_listing_archive_version();
	ob_start();
		set_query_var( 'html_content', $html );
		get_template_part( 'job_manager/loop-layout/'.$layout_version );
		$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

add_filter( 'job_manager_job_listings_output', 'listdo_wrap_the_listings', 10, 1 );


remove_action( 'single_job_listing_start', 'job_listing_company_display', 30 );



function listdo_listing_template( $template ) {
	global $wp_query;
	$post_type = get_query_var( 'post_type' );
	if ( $wp_query->is_search && $post_type == 'job_listing' ) {
		return get_template_directory() . '/search-job_listing.php';  //  redirect to archive-search.php
	}

	return $template;
}

add_filter( 'template_include', 'listdo_listing_template' );



function listdo_get_job_listings_query_args($query_args, $args) {
	global $wpdb, $wp_query, $listdo_distances;
	$listdo_distances = array();

	if (isset($_REQUEST['form_data']) || isset($_REQUEST['$form_data'])) {
		if ( isset($_REQUEST['form_data']) ) {
			$form_data = urldecode($_REQUEST['form_data']);
		} else {
			$form_data = urldecode($_REQUEST['$form_data']);
		}
		parse_str($form_data, $datas);

		// order by
		if ( isset( $datas['filter_order'] ) ) {
			if ( 'default' === $datas['filter_order'] ) { // Default show featured.
				$query_args['orderby'] = array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				);
				$query_args['order'] = 'DESC';
			} else {
				$query_args['listdo_proximity_filter'] = false;
				$query_args = listdo_sort_listings_query( $query_args, $datas['filter_order'] );
			}
		}

		$tax_querys = array();
		if (isset($datas['job_region_select']) && $datas['job_region_select']) {
			if ( is_array($datas['job_region_select']) ) {
				$region_tax_querys = array();
				foreach ($datas['job_region_select'] as $value) {
					if ( !empty($value) ) {
						$region_tax_querys[] = array(
							'taxonomy'         => 'job_listing_region',
							'field'            => 'slug',
							'terms'            => $value,
							'operator'         => 'IN'
						);
					}
				}
				if ( !empty($region_tax_querys) ) {
					$tax_querys[] = array_merge(array('relation' => 'AND'), $region_tax_querys);
				}
			} else {
				$tax_querys[] = array(
					'taxonomy'         => 'job_listing_region',
					'field'            => 'slug',
					'terms'            => $datas['job_region_select'],
					'operator'         => 'IN'
				);
			}
		}
		if (isset($datas['job_type_select']) && $datas['job_type_select']) {
			if ( is_array($datas['job_type_select']) ) {
				$region_tax_querys = array();
				foreach ($datas['job_type_select'] as $value) {
					if ( !empty($value) ) {
						if ( is_numeric($value) ) {
							$region_tax_querys[] = array(
								'taxonomy'         => 'job_listing_type',
								'field'            => 'term_id',
								'terms'            => $value,
								'operator'         => 'IN'
							);
						} else {
							$region_tax_querys[] = array(
								'taxonomy'         => 'job_listing_type',
								'field'            => 'slug',
								'terms'            => $value,
								'operator'         => 'IN'
							);
						}
					}
				}
				if ( !empty($region_tax_querys) ) {
					$tax_querys[] = array_merge(array('relation' => 'AND'), $region_tax_querys);
				}
			} else {
				if ( is_numeric($datas['job_type_select']) ) {
					$tax_querys[] = array(
						'taxonomy'         => 'job_listing_type',
						'field'            => 'term_id',
						'terms'            => $datas['job_type_select'],
						'operator'         => 'IN'
					);
				} else {
					$tax_querys[] = array(
						'taxonomy'         => 'job_listing_type',
						'field'            => 'slug',
						'terms'            => $datas['job_type_select'],
						'operator'         => 'IN'
					);
				}
			}
		}
		if (isset($datas['filter_job_amenity']) && $datas['filter_job_amenity']) {
			$tax_querys[] = array(
				'taxonomy'         => 'job_listing_amenity',
				'field'            => 'slug',
				'terms'            => $datas['filter_job_amenity'],
				'operator'         => 'IN'
			);
		}
		if (isset($datas['filter_job_tag']) && $datas['filter_job_tag']) {
			$tax_querys[] = array(
				'taxonomy'         => 'job_listing_tag',
				'field'            => 'slug',
				'terms'            => $datas['filter_job_tag'],
				'operator'         => 'IN'
			);
		}
		if (!empty($tax_querys)) {
			if ( isset($query_args['tax_query']) ) {
				$query_args['tax_query'] = array_merge($query_args['tax_query'], $tax_querys);
			} else {
				$query_args['tax_query'] = $tax_querys;
			}
		}
		
		if ( isset($query_args['meta_query']) ) {
			$meta_query = $query_args['meta_query'];
		} else {
			$meta_query = array();
		}

		if (isset($datas['filter_price_range']) && $datas['filter_price_range']) {
			$meta_query[] = array(
	           'key' => '_job_price_range',
	           'value' => $datas['filter_price_range'],
	           'compare' => '=',
	       	);
		}
		if ( isset($datas['filter_price_from']) && isset($datas['filter_price_to']) ) {
			$price_meta_query = array( 'relation' => 'AND' );
			if ( isset($datas['filter_price_from']) ) {
				$price_meta_query[] = array(
		           	'key' => '_job_price_from',
		           	'value' => $datas['filter_price_from'],
		           	'compare'   => '>=',
					'type'      => 'NUMERIC',
		       	);
			}
			if ( isset($datas['filter_price_to']) ) {
				$price_meta_query[] = array(
		           	'key' => '_job_price_to',
		           	'value' => $datas['filter_price_to'],
		           	'compare'   => '<=',
					'type'      => 'NUMERIC',
		       	);
			}
			$meta_query[] = $price_meta_query;
		}
		
		if ( !empty($meta_query) ) {
			$query_args['meta_query'] = $meta_query;
		}

		$query_args = apply_filters('listdo_get_job_listings_query_args', $query_args, $args, $datas);
		
		// location
		$use_distance = isset( $datas[ 'use_search_distance' ] ) && 'on' == $datas[ 'use_search_distance' ];
		$lat = isset( $datas[ 'search_lat' ] ) ? (float) $datas[ 'search_lat' ] : false;
		$lng = isset( $datas[ 'search_lng' ] ) ? (float) $datas[ 'search_lng' ] : false;
		$distance = isset( $datas[ 'search_distance' ] ) ? (int) $datas[ 'search_distance' ] : false;
		$location = isset( $datas[ 'search_location' ] ) ? esc_attr( $datas[ 'search_location' ] ) : false;

		if ( !( $use_distance && $lat && $lng && $distance ) || !$location ) {
			return $query_args;
		}

		$earth_distance = listdo_get_config('listing_filter_distance_unit', 'km') == 'miles' ? 3959 : 6371;
		
		$sql = $wpdb->prepare( "
			SELECT $wpdb->posts.ID, 
				( %s * acos( cos( radians(%s) ) * cos( radians( latmeta.meta_value ) ) * cos( radians( longmeta.meta_value ) - radians(%s) ) + sin( radians(%s) ) * sin( radians( latmeta.meta_value ) ) ) ) AS distance, latmeta.meta_value AS latitude, longmeta.meta_value AS longitude
			FROM $wpdb->posts
			INNER JOIN $wpdb->postmeta AS latmeta ON $wpdb->posts.ID = latmeta.post_id
			INNER JOIN $wpdb->postmeta AS longmeta ON $wpdb->posts.ID = longmeta.post_id
			WHERE $wpdb->posts.post_status = 'publish' AND latmeta.meta_key='geolocation_lat' AND longmeta.meta_key='geolocation_long'
			HAVING distance < %s
			ORDER BY $wpdb->posts.menu_order ASC, distance ASC",
			$earth_distance,
			$lat,
			$lng,
			$lat,
			$distance
		);

		$post_ids = false;

		if ( apply_filters( 'get_job_listings_cache_results', true ) ) {
			$to_hash         = json_encode( array($earth_distance, $lat, $lng, $lat, $distance) );
			$query_args_hash = 'jm_' . md5( $to_hash . JOB_MANAGER_VERSION ) . WP_Job_Manager_Cache_Helper::get_transient_version( 'get_job_listings_by_location' );

			$post_ids = get_transient( $query_args_hash );
		}

		if ( ! $post_ids ) {
			$post_ids = $wpdb->get_results( $sql, OBJECT_K );
			set_transient( $query_args_hash, $post_ids, DAY_IN_SECONDS );
		}

		if ( empty( $post_ids ) || ! $post_ids ) {
            $post_ids = array(0);
		}

		if ( $wp_query ) {
			$wp_query->locations = $post_ids;
		}
		
		$listdo_distances = $post_ids;


		$query_args[ 'post__in' ] = array_keys( (array)$post_ids );
		$query_args['orderby'] = 'post__in';
		$query_args['order']   = 'asc';

		$query_args = listdo_remove_location_meta_query( $query_args );
	}
	
	return $query_args;
}
add_filter( 'get_job_listings_query_args', 'listdo_get_job_listings_query_args', 10, 2 );



function listdo_sort_listings_query( $query_args, $sort_option ) {
	if ( 'date-desc' === $sort_option ) { // Newest First (default).
		$query_args['orderby'] = 'date';
		$query_args['order'] = 'DESC';
	} elseif ( 'date-asc' === $sort_option ) { // Oldest First.
		$query_args['orderby'] = 'date';
		$query_args['order'] = 'ASC';
	} elseif ( 'rating-desc' === $sort_option ) { // Highest Rating.
		$query_args['meta_key'] = '_average_rating';
		$query_args['orderby'] = 'meta_value_num';
		$query_args['order'] = 'DESC';
	} elseif ( 'rating-asc' === $sort_option ) { // Lowest Rating.
		$query_args['meta_key'] = '_average_rating';
		$query_args['orderby'] = 'meta_value_num';
		$query_args['order'] = 'ASC';
	} elseif ( 'random' === $sort_option ) { // Random.
		$query_args['orderby'] = 'rand';
	}

	return $query_args;
}

function listdo_job_listings_cache_results( $return ) {
	if (isset($_REQUEST['form_data'])) {
		$form_data = urldecode($_REQUEST['form_data']);
		parse_str($form_data, $datas);

		// order by
		if ( 'random' === $datas['filter_order'] ) { // Default show featured.
			return false;
		}
	}
	return $return;
}
add_filter('get_job_listings_cache_results', 'listdo_job_listings_cache_results', 100);

function listdo_remove_location_meta_query( $query_args ) {
	$found = false;
	if ( ! isset( $query_args[ 'meta_query' ] ) ) {
		return $query_args;
	}
	foreach ( $query_args[ 'meta_query' ] as $query_key => $meta ) {
		foreach ( $meta as $key => $args ) {
			if ( ! is_int( $key ) ) {
				continue;
			}

			if ( 'geolocation_formatted_address' == $args[ 'key' ] ) {
				$found = true;
				unset( $query_args[ 'meta_query' ][ $query_key ] );
				break;
			}
		}

		if ( $found ) {
			break;
		}
	}

	return $query_args;
}

function listdo_job_manager_get_listings_args( $args ) {
	if ( ! isset( $_REQUEST[ 'form_data' ] ) ) {
		return $args;
	}
	parse_str( $_REQUEST[ 'form_data' ], $params );
	
	$use_distance = isset( $params[ 'use_search_distance' ] ) && 'on' == $params[ 'use_search_distance' ];
	$lat = isset( $params[ 'search_lat' ] ) && 0 != $params[ 'search_lat' ];

	if ( !( $use_distance && $lat ) || '' == $params[ 'search_location' ] ) {
		return $args;
	}

	$args[ 'orderby' ] = 'distance';
	$args[ 'order' ] = 'asc';
	
	return $args;
}
add_filter( 'job_manager_get_listings_args', 'listdo_job_manager_get_listings_args' );

function listdo_get_listings_custom_filter_text($text) {
	$after_text = array();
	if (isset($_REQUEST['form_data'])) {
		$form_data = urldecode($_REQUEST['form_data']);
		parse_str($form_data, $datas);

		if (isset($datas['search_categories']) && $datas['search_categories']) {
			$categories = array();
			if ( is_array($datas['search_categories']) ) {
				$field = is_numeric( $datas['search_categories'][0] ) ? 'term_id' : 'slug';
				foreach ($datas['search_categories'] as $job_category) {
					$term = get_term_by( $field, $job_category, 'job_listing_category' );
					if (!empty($term)) {
						$categories[] = '<span class="highlight">'.$term->name.'</span>';
					}
				}
			} else {
				$field = is_numeric( $datas['search_categories'] ) ? 'term_id' : 'slug';
				$term = get_term_by( $field, $datas['search_categories'], 'job_listing_category' );
				if (!empty($term)) {
					$categories[] = '<span class="highlight">'.$term->name.'</span>';
				}
			}
			if ( !empty($categories) ) {
				$after_text[] = sprintf( _n('<span class="text-strong">Category</span>: %s', '<span class="text-strong">Categories</span>: %s', count($categories), 'listdo'), implode(', ', $categories) );
			}
		}
		if (isset($datas['job_region_select']) && $datas['job_region_select']) {
			if ( is_array($datas['job_region_select']) ) {
				$field = is_numeric( $datas['job_region_select'][0] ) ? 'term_id' : 'slug';
				foreach ($datas['job_region_select'] as $job_category) {
					$term = get_term_by( $field, $job_category, 'job_listing_region' );
					if (!empty($term)) {
						$regions[] = '<span class="highlight">'.$term->name.'</span>';
					}
				}
			} else {
				$field = is_numeric( $datas['job_region_select'] ) ? 'term_id' : 'slug';
				$term = get_term_by( $field, $datas['job_region_select'], 'job_listing_region' );
				if (!empty($term)) {
					$regions[] = '<span class="highlight">'.$term->name.'</span>';
				}
			}
			if (!empty($regions)) {
				$after_text[] = sprintf(_n('<span class="text-strong">Region</span>: %s', '<span class="text-strong">Regions</span>: %s', count($regions), 'listdo'), implode(', ', $regions) );
			}
		}
		if (isset($datas['job_type_select']) && $datas['job_type_select']) {
			$term = get_term_by( 'id', $datas['job_type_select'], 'job_listing_type' );
			if (!empty($term)) {
				$after_text[] = '<span class="text-strong">'.esc_html__('Type', 'listdo').'</span>: <span class="highlight">'.$term->name.'</span>';
			}
		}
		if (isset($datas['filter_job_amenity']) && $datas['filter_job_amenity']) {
			$amenities = array();
			foreach ($datas['filter_job_amenity'] as $job_amenity) {
				$term = get_term_by( 'slug', $job_amenity, 'job_listing_amenity' );
				if (!empty($term)) {
					$amenities[] = '<span class="highlight">'.$term->name.'</span>';
				}
			}
			$after_text[] = sprintf(_n('<span class="text-strong">Amenity</span>: %s', '<span class="text-strong">Amenities</span>: %s', count($amenities), 'listdo'), implode(', ', $amenities) );
		}
		if (!empty($datas['filter_job_tag'])) {
			$tags = array();
			foreach ($datas['filter_job_tag'] as $tag) {
				$term = get_term_by( 'slug', $tag, 'job_listing_tag' );
				if (!empty($term)) {
					$tags[] = '<span class="highlight">'.$term->name.'</span>';
				}
			}
			$after_text[] = sprintf(_n('<span class="text-strong">Tag</span>: %s', '<span class="text-strong">Tags</span>: %s', count($tags), 'listdo'), implode(', ', $tags) );
		}
		
		$location = isset( $datas[ 'search_location' ] ) ? esc_attr( $datas[ 'search_location' ] ) : false;

		if ( $location ) {
			$after_text[] = '<span class="text-strong">'.esc_html__('Location', 'listdo').'</span>: <span class="highlight">'.$location.'</span>';
		}

		if (isset($datas['filter_price_range']) && $datas['filter_price_range']) {
			$prices_default = listdo_job_manager_price_range_icons();
			$prices = array();
			if ( is_array($datas['filter_price_range']) ) {
				foreach ($datas['filter_price_range'] as $value) {
					if ( !empty($prices_default[$value]) ) {
						$prices[] = '<span class="highlight">'.$prices_default[$value]['label'].'</span>';
					}
				}
			} else {
				$value = $datas['filter_price_range'];
				if ( !empty($prices_default[$value]) ) {
					$prices[] = '<span class="highlight">'.$prices_default[$value]['label'].'</span>';
				}
			}
			$after_text[] = '<span class="text-strong">'.esc_html__('Price Range', 'listdo').'</span>: '.implode(', ', $prices);

		}

		if ( !empty($datas['filter_event_date_from']) && !empty($datas['filter_event_date_to']) ) {
			$date_html = '';
			if ( !empty($datas['filter_event_date_from']) ) {
				$date_html = '<span class="highlight">'.$datas['filter_event_date_from'].'</span>';
			}
			if ( !empty($datas['filter_event_date_to']) ) {
				if ( !empty($date_html) ) {
					$date_html .= ' - ';
				}
				$date_html .= '<span class="highlight">'.$datas['filter_event_date_to'].'</span>';
			}
			$after_text[] = '<span class="text-strong">'.esc_html__('Date', 'listdo').'</span>: '.$date_html;

		}

		if ( !empty($datas['search_contract']) ) {
			$after_text[] = '<span class="text-strong">'.esc_html__('Contract', 'listdo').'</span>: '.$datas['search_contract'];
		}
		if ( !empty($datas['filter_price_from']) && !empty($datas['filter_price_to']) ) {
			$price_html = '';
			if ( !empty($datas['filter_price_from']) ) {
				$price_html = '<span class="highlight">'.$datas['filter_price_from'].'</span>';
			}
			if ( !empty($datas['filter_price_to']) ) {
				if ( !empty($price_html) ) {
					$price_html .= ' - ';
				}
				$price_html .= '<span class="highlight">'.$datas['filter_price_to'].'</span>';
			}
			$after_text[] = '<span class="text-strong">'.esc_html__('Price', 'listdo').'</span>: '.$price_html;
		}

	}

	if ( !empty($after_text) ) {
		return esc_html__('Showing results for ', 'listdo').' '.implode('; ', $after_text);
	} else {
		return '';
	}
}
add_filter( 'job_manager_get_listings_custom_filter_text', 'listdo_get_listings_custom_filter_text' );

function listdo_listings_custom_filter() {
	if (isset($_REQUEST['form_data'])) {
		$form_data = urldecode($_REQUEST['form_data']);
		parse_str($form_data, $datas);

		if (isset($datas['job_region_select']) && $datas['job_region_select']) {
			return true;
		}
		if (isset($datas['filter_job_amenity']) && $datas['filter_job_amenity']) {
			return true;
		}
		if (isset($datas['filter_job_tag']) && $datas['filter_job_tag']) {
			return true;
		}
		if (isset($datas['filter_price_range']) && $datas['filter_price_range']) {
			return true;
		}
	}
}
add_filter( 'job_manager_get_listings_custom_filter', 'listdo_listings_custom_filter' );

function listdo_listings_remove_rss_link($links) {
	if ( isset($links['rss_link']) ) {
		unset($links['rss_link']);
	}
	return $links;
}
add_filter( 'job_manager_job_filters_showing_jobs_links', 'listdo_listings_remove_rss_link' );


function listdo_listings_json_results( $result, $jobs ) {
	$result[ 'page' ]   = isset( $jobs->query_vars[ 'paged' ] ) ? $jobs->query_vars[ 'paged' ] : 1;
	$result[ 'offset' ] = intval($jobs->query_vars[ 'offset' ]) + 1;
	$result[ 'found' ] = $jobs->found_posts == 0 ? 0 : $jobs->found_posts;

	$total_found = $jobs->query_vars[ 'offset' ] + $jobs->post_count;
	if ( listdo_get_config('listing_show_loadmore') ) {
		$result[ 'str_found' ] = esc_html__(' (Showing ', 'listdo'). ($total_found > 0 ? ' 1' : ' 0') . ' - ' . $total_found . ')';
	} else {
		$result[ 'str_found' ] = esc_html__(' (Showing ', 'listdo'). ($total_found > 0 ? intval($jobs->query_vars[ 'offset' ]) + 1 : ' 0') . ' - '. $total_found .')';
	}

	return $result;
}
add_filter( 'job_manager_get_listings_result', 'listdo_listings_json_results', 10, 2 );

function listdo_job_manager_output_jobs_defaults($args) {
	$args['regions'] = '';
	return $args;
}
add_filter('job_manager_output_jobs_defaults', 'listdo_job_manager_output_jobs_defaults');




