<?php

// get listings
function listdo_get_listings( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'get_by' => '',
		'post_per_page' => -1,
		'paged' => 1,
		'categories' => '',
		'regions' => '',
		'orderby' => '',
        'order' => '',
        'meta_query' => array(),
        'post__in' => array()
	));

	$query_args = array(
		'post_type' => 'job_listing',
		'post_status' => 'publish',
		'posts_per_page' => $args['post_per_page'],
		'ignore_sticky_posts' => true,
		'paged' => $args['paged'],
		'orderby'   => $args['orderby'],
        'order' => $args['order']
	);
	$meta_query = $args['meta_query'];
	
	if ( $args['get_by'] == 'popular' ) {
		$query_args['meta_key'] = '_listing_views_count';
		$query_args['order'] = 'DESC';
	} elseif ( $args['get_by'] == 'featured' ) {
		$meta_query[] = array(
           	'key' => '_featured',
           	'value' => '1',
           	'compare' => '=',
       	);
	} elseif ( $args['get_by'] == 'rand' ) {
		$query_args['orderby'] = 'rand';
	} elseif ( $args['get_by'] == 'upcoming' ) {
		$timezone  = get_option('gmt_offset');
		$time = current_time( 'mysql' );
		
		$meta_query[] = array(
			'relation' => 'OR',
			array(
	           	'key' => '_job_start_date',
	           	'value' => $time,
	           	'compare' => '>=',
	           	'type' => 'DATE'
           	),
           	array(
	           	'key' => '_job_finish_date',
	           	'value' => $time,
	           	'compare' => '>=',
	           	'type' => 'DATE'
           	)
       	);
       	$query_args['meta_key'] = '_job_start_date';
		$query_args['orderby'] = 'meta_value';
		$query_args['order'] = 'ASC';
	}

	if ( !empty($args['categories']) && is_array($args['categories']) ) {
        $query_args['tax_query'][] = array(
            'taxonomy'      => 'job_listing_category',
            'field'         => 'slug',
            'terms'         => $args['categories'],
            'operator'      => 'IN'
        );
    }

    if ( !empty($args['regions']) && is_array($args['regions']) ) {
        $query_args['tax_query'][] = array(
            'taxonomy'      => 'job_listing_region',
            'field'         => 'slug',
            'terms'         => $args['regions'],
            'operator'      => 'IN'
        );
    }

    if ( !empty($meta_query) ) {
    	$query_args['meta_query'] = $meta_query;
    }

    if ( !empty($args['post__in']) ) {
    	$query_args['post__in'] = $args['post__in'];
    }
    
	$wp_query = new WP_Query( $query_args );
	return $wp_query;
}

function listdo_get_listings_nearby($categories, $lat, $lng, $post_per_page = -1, $excludes = array()) {
	global $wpdb, $wp_query;
	$args = array(
		'post_type' => 'job_listing',
		'post_status' => 'publish',
		'posts_per_page' => $post_per_page,
		'ignore_sticky_posts' => true,
		'paged' => 1
	);
	if ($categories) {
		foreach ($categories as $category) {
			$tax_query[] = array(
				'taxonomy' => 'job_listing_category',
				'field'    => 'term_id',
				'terms'    => $category
			);
		}
		$args['tax_query'] = $tax_query;
	}
	if (!empty($excludes)) {
		$args['post__not_in'] = $excludes;
	}

	$use_distance = true;
	$distance = 50;
	$location = true;

	if ( !( $use_distance && $lat && $lng && $distance ) || !$location ) {
		return false;
	}
	$earth_distance = 3959;

	$sql = $wpdb->prepare( "
		SELECT $wpdb->posts.ID, 
			( %s * acos( 
				cos( radians(%s) ) * 
				cos( radians( latitude.meta_value ) ) * 
				cos( radians( longitude.meta_value ) - radians(%s) ) + 
				sin( radians(%s) ) * 
				sin( radians( latitude.meta_value ) ) 
			) ) 
			AS distance, latitude.meta_value AS latitude, longitude.meta_value AS longitude
			FROM $wpdb->posts
			INNER JOIN $wpdb->postmeta AS latitude ON $wpdb->posts.ID = latitude.post_id
			INNER JOIN $wpdb->postmeta AS longitude ON $wpdb->posts.ID = longitude.post_id
			WHERE 1=1 AND ($wpdb->posts.post_status = 'publish' ) AND latitude.meta_key='geolocation_lat' AND longitude.meta_key='geolocation_long' 
			HAVING distance < %s
			ORDER BY $wpdb->posts.menu_order ASC, distance ASC",
		$earth_distance,
		$lat,
		$lng,
		$lat,
		$distance
	);

	$post_ids = $wpdb->get_results( $sql, OBJECT_K );

	$distances = array();
	if ( empty( $post_ids ) || ! $post_ids ) {
        $post_ids = array(0);
	} else {
		foreach ($post_ids as $listing) {
			$distances[$listing->ID] = $listing->distance;
		}
	}
	$args[ 'post__in' ] = array_keys( (array) $post_ids );
	$args = listdo_remove_location_meta_query( $args );
	$listings = get_posts( $args );

	return array( 'listings' => $listings, 'distances' => $distances );
}

function listdo_set_post_views($postID) {
    $count_key = '_listing_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function listdo_track_post_views ($post_id) {
    if ( !is_singular('job_listing') )
    	return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    listdo_set_post_views($post_id);
}
add_action( 'wp_head', 'listdo_track_post_views');

if ( !function_exists('listdo_listing_content_class') ) {
	function listdo_listing_content_class( $class ) {
		if ( is_page() && is_object($post) ) {
			return listdo_page_content_class($class);
		}
		$page = 'archive';
		if ( is_singular( 'job_listing' ) ) {
            $page = 'single';
        }
		if ( listdo_get_config('listing_'.$page.'_fullwidth') ) {
			return 'container-fluid';
		}
		return $class;
	}
}
add_filter( 'listdo_listing_content_class', 'listdo_listing_content_class', 1 , 1  );


if ( !function_exists('listdo_get_listings_sidebar_configs') ) {
	function listdo_get_listings_sidebar_configs() {
		global $post;
		if ( is_page() && is_object($post) ) {
			$sidebar = get_post_meta( $post->ID, 'apus_page_sidebar', true );

			switch ( get_post_meta( $post->ID, 'apus_page_layout', true ) ) {
				case 'main':
		 			$configs = array( 'sidebar' => '', 'class' => '' );
		 			break;
	 			case 'main-right':
			 		$configs = array( 'sidebar' => $sidebar,  'class' => 'pull-right sidebar-right' );
			 		break;
			 	case 'left-main':
			 	default:
			 		$configs = array( 'sidebar' => $sidebar, 'class' => 'pull-left sidebar-left' );
			 		break;
			}

			return $configs;
		}
		$page = 'archive';
		if ( is_singular( 'job_listing' ) ) {
            $page = 'single';
        }
		$sidebar = listdo_get_config('listing_'.$page.'_sidebar');

		switch ( listdo_get_config('listing_'.$page.'_layout') ) {
			case 'main':
		 		$configs = array( 'sidebar' => $sidebar,  'class' => '' );
		 		break;
		 	case 'main-right':
		 		$configs = array( 'sidebar' => $sidebar,  'class' => 'pull-right sidebar-right' );
		 		break;
	 		case 'left-main':
		 	default:
		 		$configs = array( 'sidebar' => $sidebar, 'class' => 'pull-left sidebar-left' );
		 		break;
		}

		return $configs; 
	}
}

function listdo_get_archive_layout() {
	global $post;
	if ( is_page() && is_object($post) ) {
		return get_post_meta( $post->ID, 'apus_page_layout', true );
	}
	return listdo_get_config('listing_archive_layout', 'left-main');
}

function listdo_get_listing_all_half_map_version() {
	return apply_filters( 'listdo_get_listing_all_half_map_version', array(
		'half-map','half-map-v2','half-map-v3','half-map-v4'
	) );
}

function listdo_get_listing_archive_version() {
	global $post;
	if ( is_page() && is_object($post) ) {
		$version = get_post_meta( $post->ID, 'apus_page_layout_version', true );
	}
	if ( empty($version) ) {
		$version = listdo_get_config('listing_archive_layout_version', 'half-map');
	}
	
	return $version;
}

function listdo_get_listing_item_style() {
	global $post;
	$display_mode = listdo_get_listing_display_mode();
	$style = 'grid-place';
	switch ($display_mode) {
		case 'list':
			$style = 'list';
			break;
		case 'list-v2':
			$style = 'list-v2';
			break;
		case 'list-v3':
			$style = 'list-v3';
			break;
		default:
			$style = 'grid';
			break;
	}
	return $style;
}

function listdo_get_listing_display_mode() {
	global $post;
	if ( is_page() && is_object($post) ) {
		$display_mode = get_post_meta( $post->ID, 'apus_page_display_mode', true );
	}
	if ( empty($display_mode) ) {
		if (isset($_REQUEST['form_data'])) {
			$form_data = urldecode($_REQUEST['form_data']);
			parse_str($form_data, $datas);

			// order by
			if ( isset( $datas['filter_display_mode'] ) ) {
				$display_mode = $datas['filter_display_mode'];
			}
		}
	}
	if ( empty($display_mode) ) {
		$display_mode = listdo_get_config('listing_archive_display_mode', 'grid');
	}
	return $display_mode;
}

function listdo_get_listing_item_columns() {
	$display_mode = listdo_get_listing_display_mode();
	switch ($display_mode) {
		case 'list':
			$columns = 1;
			break;
		default:
			global $post;
			if ( is_page() && is_object($post) ) {
				$columns = get_post_meta( $post->ID, 'apus_page_listing_columns', true );
			}
			if ( empty($columns) ) {
				if (isset($_REQUEST['form_data'])) {
					$form_data = urldecode($_REQUEST['form_data']);
					parse_str($form_data, $datas);

					// order by
					if ( isset( $datas['filter_listing_columns'] ) ) {
						$columns = $datas['filter_listing_columns'];
					}
				}
			}
			if ( empty($columns) ) {
				$columns = listdo_get_config('listing_columns', 2);
			}
			break;
	}
	return apply_filters( 'listdo_get_listing_item_columns', $columns );
}

function listdo_get_listing_sortby_default() {
	global $post;
	if ( is_page() && is_object($post) ) {
		$sortby_default = get_post_meta( $post->ID, 'apus_page_sortby_default', true );
	}
	if ( empty($sortby_default) ) {
		$sortby_default = listdo_get_config('listing_filter_sortby_default', 'default');
	}
	return $sortby_default;
}


function listdo_get_listing_single_version() {
	global $post;
	$layout_style = get_post_meta($post->ID, '_layout_type', true);
	if ( empty($layout_style) ) {
		$layout_style = listdo_get_config('listing_single_layout_version', 'v1');
	}
	return $layout_style;
}

function listdo_listing_enqueue_styles() {
    wp_enqueue_style( 'leaflet', get_template_directory_uri() . '/css/leaflet.css', array(), '0.7.7' );
}
add_action( 'wp_enqueue_scripts', 'listdo_listing_enqueue_styles', 99 );
add_action( 'admin_enqueue_scripts', 'listdo_listing_enqueue_styles', 99 );

function listdo_listing_enqueue_scripts() {
    
    wp_enqueue_script( 'jquery-highlight', get_template_directory_uri() . '/js/jquery.highlight.js', array( 'jquery' ), '5', true );
    wp_enqueue_script( 'leaflet', get_template_directory_uri() . '/js/leaflet/leaflet.js', array( 'jquery' ), '1.5.1', true );
    //wp_enqueue_script( 'leaflet-tilelayer-here', get_template_directory_uri() . '/js/leaflet/leaflet-tilelayer-here.js', array( 'jquery' ), '1.5.1', true );
    wp_enqueue_script( 'leaflet-GoogleMutant', get_template_directory_uri() . '/js/leaflet/Leaflet.GoogleMutant.js', array( 'jquery' ), '1.5.1', true );
    wp_enqueue_script( 'control-geocoder', get_template_directory_uri() . '/js/leaflet/Control.Geocoder.js', array( 'jquery' ), '1.5.1', true );
    wp_enqueue_script( 'esri-leaflet', get_template_directory_uri() . '/js/leaflet/esri-leaflet.js', array( 'jquery' ), '1.5.1', true );
    wp_enqueue_script( 'esri-leaflet-geocoder', get_template_directory_uri() . '/js/leaflet/esri-leaflet-geocoder.js', array( 'jquery' ), '1.5.1', true );
    wp_enqueue_script( 'leaflet-markercluster', get_template_directory_uri() . '/js/leaflet/leaflet.markercluster.js', array( 'jquery' ), '1.5.1', true );
    wp_enqueue_script( 'leaflet-HtmlIcon', get_template_directory_uri() . '/js/leaflet/LeafletHtmlIcon.js', array( 'jquery' ), '1.5.1', true );
    //wp_enqueue_script( 'leaflet-gesture-handling', get_template_directory_uri() . '/js/leaflet/leaflet-gesture-handling.min.js', array( 'jquery' ), '1.5.1', true );

    wp_register_script( 'jquery-ui-touch-punch', get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array( 'jquery' ), '20150330', true );
	wp_enqueue_script( 'listdo-listing', get_template_directory_uri() . '/js/listing.js', array( 'jquery', 'jquery-ui-slider', 'wp-job-manager-ajax-filters', 'jquery-ui-touch-punch' ), '1.5.1', true );



	$mapbox_token = '';
	$mapbox_style = '';
	$custom_style = '';
	$map_service = listdo_get_config('listing_map_style_type', 'google');
	if ( $map_service == 'mapbox' ) {
		$mapbox_token = listdo_get_config('listing_mapbox_token', '');
		$mapbox_style = listdo_get_config('listing_mapbox_style', 'streets-v11');
		if ( empty($mapbox_style) || !in_array($mapbox_style, array( 'streets-v11', 'light-v10', 'dark-v10', 'outdoors-v11', 'satellite-v9' )) ) {
			$mapbox_style = 'streets-v11';
		}
	} elseif ( $map_service == 'default') {
		$custom_style = listdo_get_config('listing_map_custom_style', '');
	}
	$locale = get_locale();
	$locale = explode('_', $locale);
	$locale_code = !empty($locale[0]) ? $locale[0] : '';

	ob_start();
	get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
		'name' => '',
		'value' => '',
		'extension' => 'jpg',
	) );
	$js_field_html_img = ob_get_clean();

	ob_start();
	get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
		'name' => '',
		'value' => '',
		'extension' => 'zip',
	) );
	$js_field_html = ob_get_clean();

	$region_labels = array(
		'1' => listdo_get_config('submit_listing_region_1_field_label'),
		'2' => listdo_get_config('submit_listing_region_2_field_label'),
		'3' => listdo_get_config('submit_listing_region_3_field_label'),
		'4' => listdo_get_config('submit_listing_region_4_field_label'),
	);
	$category_labels = array(
		'1' => listdo_get_config('submit_listing_category_1_field_label'),
		'2' => listdo_get_config('submit_listing_category_2_field_label')
	);
	wp_localize_script( 'listdo-listing', 'listdo_listing_opts', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'ajax_nonce' => wp_create_nonce( "listdo-ajax-nonce" ),
		'login_url' => rtrim( esc_url( wp_login_url() ) , '/'),
		'strings' => array(
			'wp-job-manager-file-upload' => esc_html__( 'Add Photo', 'listdo' ),
			'no_job_listings_found' => esc_html__( 'No results', 'listdo' ),
			'results-no' => esc_html__( 'Results Found', 'listdo')
		),
		'map_service' => $map_service,
		'mapbox_token' => $mapbox_token,
		'mapbox_style' => $mapbox_style,
		'custom_style' => $custom_style,
		'reviews' => array(
			'terrible' => '<img src="'.get_template_directory_uri() . '/images/stars/1-star.png'.'"> '.esc_html__('Terrible', 'listdo'),
			'poor' => '<img src="'.get_template_directory_uri() . '/images/stars/2-star.png'.'"> '.esc_html__('Poor', 'listdo'),
			'average' => '<img src="'.get_template_directory_uri() . '/images/stars/3-star.png'.'"> '.esc_html__('Average', 'listdo'),
			'very_good' => '<img src="'.get_template_directory_uri() . '/images/stars/4-star.png'.'"> '.esc_html__('Very Good', 'listdo'),
			'excellent' => '<img src="'.get_template_directory_uri() . '/images/stars/5-star.png'.'"> '.esc_html__('Excellent', 'listdo'),
		),
		// submit form vars
		'lang_code' => $locale_code,
		'date_format' => get_option('date_format'),
		'ajax_url' => listdo_get_endpoint(),
		'js_field_html_img'      => esc_js( str_replace( "\n", '', $js_field_html_img ) ),
		'js_field_html'          => esc_js( str_replace( "\n", '', $js_field_html ) ),
		'i18n_invalid_file_type' => esc_html__( 'Invalid file type. Accepted types:', 'listdo' ),
		'money_decimals' => listdo_get_config('listing_currency_decimal_places', 0),
		'money_dec_point' => listdo_get_config('listing_currency_decimal_separator', 0),
		'money_thousands_separator' => listdo_get_config('listing_currency_thousands_separator', ',') ? listdo_get_config('listing_currency_thousands_separator', ',') : ',',
		'region_labels' => $region_labels,
		'category_labels' => $category_labels,


		'template' => apply_filters( 'listdo_autocompleate_search_template', '<a href="{{url}}" class="media autocompleate-media">
			<div class="media-left media-middle">
				<img src="{{image}}" class="media-object" height="70" width="70">
			</div>
			<div class="media-body media-middle">
				<h4>{{title}}</h4>
				<div class="location"><div class="listing-location listing-address">
			<i class="flaticon-placeholder"></i>{{location}}</div></div>
				</div></a>' ),
        'empty_msg' => apply_filters( 'listdo_autocompleate_search_empty_msg', esc_html__( 'Unable to find any listing that match the currenty query', 'listdo' ) ),

        'default_latitude' => listdo_get_config('listing_map_latitude', '54.800685'),
		'default_longitude' => listdo_get_config('listing_map_longitude', '-4.130859'),
		'geocoder_country' => listdo_get_config('listing_map_geocoder_country', ''),
	) );
}
add_action( 'wp_enqueue_scripts', 'listdo_listing_enqueue_scripts', 10 );

function listdo_get_endpoint( $request = '%%endpoint%%', $ssl = null ) {
	if ( strstr( get_option( 'permalink_structure' ), '/index.php/' ) ) {
		$endpoint = trailingslashit( home_url( '/index.php/jm-ajax/' . $request . '/', 'relative' ) );
	} elseif ( get_option( 'permalink_structure' ) ) {
		$endpoint = trailingslashit( home_url( '/jm-ajax/' . $request . '/', 'relative' ) );
	} else {
		$endpoint = add_query_arg( 'jm-ajax', $request, trailingslashit( home_url( '', 'relative' ) ) );
	}
	return esc_url_raw( $endpoint );
}

function listdo_get_listings_page_url( $default_link = null  ) {
	//if there is a page set in the Listings settings use that
	$listings_page_id = get_option( 'job_manager_jobs_page_id', false );
	$listings_page_id = apply_filters( 'wpjm_page_id', $listings_page_id );
	if ( ! empty( $listings_page_id ) ) {
		return get_permalink( $listings_page_id );
	}

	if ( $default_link !== null ) {
		return $default_link;
	}
	return get_post_type_archive_link( 'job_listing' );
}


function listdo_get_days_of_week() {
	$days = array(0, 1, 2, 3, 4, 5, 6);

	$start_day = get_option( 'start_of_week' );

	$first = array_splice( $days, $start_day, count( $days ) - $start_day );

	$second = array_splice( $days, 0, $start_day );

	$days = array_merge( $first, $second );

	return $days;
}

function listdo_set_listing_views($content) {
	global $post;
	if ( is_object($post) && $post->post_type != 'job_listing' ) {
		return $content;
	}
    $count_key = '_views_count';
    $count = get_post_meta($post->ID, $count_key, true);
    if ($count == '') {
        delete_post_meta($post->ID, $count_key);
        add_post_meta($post->ID, $count_key, 1);
    } else {
        $count++;
        $value = sanitize_text_field($count);
        update_post_meta($post->ID, $count_key, $value);
    }
    return $content;
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
add_filter( 'the_content', 'listdo_set_listing_views' );

function listdo_get_content_sort() {
	global $post;
	$contents = listdo_get_config( 'listing_single_sort_content', array() );
	if ( isset( $contents['enabled'] ) ) {
		$contents = $contents['enabled'];
		if ( isset($contents['placebo']) ) {
			unset($contents['placebo']);
		}
		return $contents;
	}
	return $contents;
}

function listdo_get_sidebar_content_sort() {
	global $post;
	$contents = listdo_get_config( 'listing_single_sort_sidebar', array() );
	if ( isset( $contents['enabled'] ) ) {
		$contents = $contents['enabled'];
		if ( isset($contents['placebo']) ) {
			unset($contents['placebo']);
		}
		return $contents;
	}
	return $contents;
}

// follow user
function listdo_follow_user() {
	check_ajax_referer( 'listdo-ajax-nonce', 'security' );

	if ( !is_user_logged_in() ) {
		echo json_encode( array('status' => 'error', 'msg' => esc_html__('Please login to follow', 'listdo') ) );
		die();
	}
	$current_user_id = get_current_user_id();
	if ( $current_user_id == $user->ID ) {
		echo json_encode( array('status' => 'error', 'msg' => esc_html__('You can not follow yourself', 'listdo') ) );
		die();
	}
	$user_id = !empty($_POST['user_id']) ? $_POST['user_id'] : '';
	if ( empty($user_id) ) {
		echo json_encode( array('status' => 'error', 'msg' => esc_html__('Can not follow this user', 'listdo') ) );
		die();
	}
	
	// delete_user_meta( $current_user_id, '_apus_following' );
	// delete_user_meta( $user_id, '_apus_followers' );

	do_action('listdo_before_follow_user');

	$meta_key = '_apus_following';
	
	$data = get_user_meta( $current_user_id, $meta_key, true );

	if ( !empty($data) && is_array($data) && !empty($data[$user_id]) ) {
		unset($data[$user_id]);
		$class = 'btn-follow-user';
	} else {
		if ( is_array($data) ) {
			$data[$user_id] = $user_id;
		} else {
			$data = array($user_id => $user_id);
		}
		$class = 'btn-following-user';
	}
	
	update_user_meta( $current_user_id, $meta_key, $data );

	// follower
	$follower_meta_key = '_apus_followers';
	$data = get_user_meta( $user_id, $follower_meta_key, true );

	if ( !empty($data) && is_array($data) && !empty($data[$current_user_id]) ) {
		unset($data[$current_user_id]);
		$class = 'btn-follow-user';
		$msg = esc_html__('Follow', 'listdo');
	} else {
		if ( is_array($data) ) {
			$data[$current_user_id] = $current_user_id;
		} else {
			$data = array($current_user_id => $current_user_id);
		}
		
		$class = 'btn-following-user';
		$msg = '<span class="text-following">'.esc_html__('Following', 'listdo').'</span>
				<span class="text-following-hover">'.esc_html__('Unfollow', 'listdo').'</span>';
	}
	update_user_meta( $user_id, $follower_meta_key, $data );

	echo json_encode( array('status' => 'success', 'class' => $class, 'msg' => $msg) );
	die();
}
add_action( 'wp_ajax_listdo_follow_user', 'listdo_follow_user' );
add_action( 'wp_ajax_nopriv_listdo_follow_user', 'listdo_follow_user' );

function listdo_check_follow_user($user_id) {
	$current_user_id = get_current_user_id();
	$data = get_user_meta( $current_user_id, '_apus_following', true );
	if ( !empty($data) && is_array($data) && !empty($data[$user_id]) ) {
		return true;
	}
	return false;
}

function listdo_listing_display_part($tmp) {
	$content = '';
	if ( !empty($tmp) ) {
		ob_start();
		get_job_manager_template( 'single/parts/'.$tmp.'.php' );
		$content = ob_get_clean();
	}
	return apply_filters( 'listdo_listing_display_part', $content, $tmp );
}

function listdo_preview_listing() {
	check_ajax_referer( 'listdo-ajax-nonce', 'security' );

	get_template_part( 'job_manager/preview-listing' );
	die();
}
add_action( 'wp_ajax_listdo_preview_listing', 'listdo_preview_listing' );
add_action( 'wp_ajax_nopriv_listdo_preview_listing', 'listdo_preview_listing' );




function listdo_remove_boxs($args) {
	remove_meta_box( 'job_listing_categorydiv', 'job_listing', 'side' );
	remove_meta_box( 'job_listing_amenitydiv', 'job_listing', 'side' );

	remove_meta_box( 'job_listing_regiondiv', 'job_listing', 'side' );
}

function listdo_add_tax_to_api() {
	$tax = get_taxonomy( 'job_listing_category' );
	if ( $tax ) {
		$tax->show_in_rest = false;
	}
	$tax = get_taxonomy( 'job_listing_amenity' );
	if ( $tax ) {
		$tax->show_in_rest = false;
	}
}
add_action( 'init', 'listdo_add_tax_to_api', 30 );



function listdo_remove_boxs_init() {
	$fnc = array('add', 'meta', 'boxes');
	add_action( implode('_', $fnc), 'listdo_remove_boxs', 10);
}
listdo_remove_boxs_init();

function listdo_job_manager_enhanced_select_enabled($return) {
	return true;
}
add_filter( 'job_manager_enhanced_select_enabled', 'listdo_job_manager_enhanced_select_enabled' );

function listdo_get_default_blocks_content() {
	return apply_filters('listdo_get_default_blocks_content', array());
}

function listdo_get_default_blocks_sidebar_content() {
	return apply_filters('listdo_get_default_blocks_sidebar_content', array());
}

if ( class_exists('WP_Job_Manager_Shortcodes') ) {
	$shortcode_obj = WP_Job_Manager_Shortcodes::instance();
	remove_action( 'job_manager_job_filters_end', array( $shortcode_obj, 'job_filter_job_types' ), 20 );
}

function listdo_get_job_listing_structured_data($data, $post) {
	return array();
}
add_filter('wpjm_get_job_listing_structured_data', 'listdo_get_job_listing_structured_data', 10, 2);

function listdo_get_the_level($id, $type = 'job_listing_region') {
  return count( get_ancestors($id, $type) );
}

// autocomplete search
add_action( 'wp_ajax_listdo_autocomplete_search_listing', 'listdo_autocomplete_suggestions' );
add_action( 'wp_ajax_nopriv_listdo_autocomplete_search_listing', 'listdo_autocomplete_suggestions' );

function listdo_autocomplete_suggestions() {
    // Query for suggestions
    $suggestions = array();
    $args = array(
		'search_location'   => '',
		'search_keywords'   => isset($_REQUEST['search']) ? $_REQUEST['search'] : '',
		'search_categories' => '',
		'job_types'         => null,
		'post_status'       => null,
		'posts_per_page'    => 4,
	);

	$jobs = get_job_listings( $args );

	if ( $jobs->have_posts() ) {
		while ( $jobs->have_posts() ) {
			$jobs->the_post();
			global $post;
			$suggestion['title'] = esc_html($post->post_title);
			$suggestion['url'] = get_permalink($post);
			if ( has_post_thumbnail( $post->ID ) ) {
	            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
	            $suggestion['image'] = $image[0];
	        } else {
	            $suggestion['image'] = '';
	        }
	        $location = get_post_meta($post->ID, '_job_location', true);
	        $suggestion['location'] = $location;

        	$suggestions[] = $suggestion;
		}
		wp_reset_postdata();
	}

    echo json_encode( $suggestions );
 
    exit;
}

// shortcode for listing content
add_filter('the_job_description', 'do_shortcode');


function listdo_sanitize_array_callback($meta_value, $meta_key) {
	return $meta_value;
}

function listdo_types_render_field($field, $field_data, $fieldkey, $fieldtype, $priority) {
	if ( isset($field_data['sanitize_callback']) ) {
		$field['sanitize_callback'] = $field_data['sanitize_callback'];
	}
	return $field;
}
add_filter( 'apuslistdo-types-render_field', 'listdo_types_render_field', 10, 5);

function listdo_job_manager_user_can_upload_file_via_ajax($can_upload) {
	if ( is_user_logged_in() ) {
		return true;
	}
}
add_filter( 'job_manager_user_can_upload_file_via_ajax', 'listdo_job_manager_user_can_upload_file_via_ajax', 10 );




add_action( 'wp_ajax_listdo_get_ajax_listings', 'listdo_get_ajax_listings' );
add_action( 'wp_ajax_nopriv_listdo_get_ajax_listings', 'listdo_get_ajax_listings' );
function listdo_get_ajax_listings() {
	$settings = !empty($_POST['settings']) ? $_POST['settings'] : array();

    extract( $settings );

    $category_slugs = !empty($category_slugs) ? array_map('trim', explode(',', $category_slugs)) : array();
    $region_slugs = !empty($region_slugs) ? array_map('trim', explode(',', $region_slugs)) : array();

    $args = array(
        'get_by' => $get_by,
        'post_per_page' => $number,
        'categories' => $category_slugs,
        'regions' => $region_slugs,
    );
    $loop = listdo_get_listings($args);
    
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
        	get_template_part( 'job_manager/loop/grid');
        endwhile;
        wp_reset_postdata();
    }
    exit();
}



// demo account
function listdo_check_demo_account() {
	if ( defined('LISTDO_DEMO_MODE') && LISTDO_DEMO_MODE ) {
		$user_id = get_current_user_id();
		$user_obj = get_user_by('ID', $user_id);
		if ( $user_obj->data->user_login == 'demo' ) {
			$return['msg'] = '<div class="text-danger">'.esc_html__('Demo users are not allowed to modify information.', 'listdo').'</div>';
			echo json_encode($return); exit;
		}
	}
}
add_action('listdo_before_change_profile', 'listdo_check_demo_account');
add_action('listdo_before_change_password', 'listdo_check_demo_account');

function listdo_check_demo_account2() {
	if ( defined('LISTDO_DEMO_MODE') && LISTDO_DEMO_MODE ) {
		$user_id = get_current_user_id();
		$user_obj = get_user_by('ID', $user_id);
		if ( $user_obj->data->user_login == 'demo' ) {
			$return['msg'] = '<div class="text-danger">'.esc_html__('Demo users are not allowed to modify information.', 'listdo').'</div>';
			$return['status'] = 'error';
			echo json_encode($return); exit;
		}
	}
}
add_action('listdo_before_remove_bookmak', 'listdo_check_demo_account2');
add_action('listdo_before_add_bookmak', 'listdo_check_demo_account2');
add_action('listdo_before_follow_user', 'listdo_check_demo_account2');

function listdo_check_demo_account3($fields, $values) {
	if ( defined('LISTDO_DEMO_MODE') && LISTDO_DEMO_MODE ) {
		$user_id = get_current_user_id();
		$user_obj = get_user_by('ID', $user_id);
		if ( $user_obj->data->user_login == 'demo' ) {
			throw new Exception( esc_html__( 'Demo users are not allowed to modify information.', 'listdo' ) );
		}
	}
}
add_filter( 'submit_job_form_validate_fields', 'listdo_check_demo_account3', 10, 2 );

function listdo_check_demo_account4() {
	if ( defined('LISTDO_DEMO_MODE') && LISTDO_DEMO_MODE ) {
		$user_id = get_current_user_id();
		$user_obj = get_user_by('ID', $user_id);
		if ( $user_obj->data->user_login == 'demo' ) {
			$return['msg'] = esc_html__('Demo users are not allowed to modify information.', 'listdo');
			$return['status'] = false;
			echo json_encode($return); exit;
		}
	}
}
add_action('wp-private-message-before-reply-message', 'listdo_check_demo_account4');
add_action('wp-private-message-before-add-message', 'listdo_check_demo_account4');
add_action('wp-private-message-before-delete-message', 'listdo_check_demo_account4');