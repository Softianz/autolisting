<?php

if ( ! function_exists( 'listdo_get_post_image_src' ) ) {
	function listdo_get_post_image_src( $post_id = null, $size = 'thumbnail' ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		if ( has_post_thumbnail( $post_id ) ) {
			$attach_id = esc_sql( get_post_thumbnail_id( $post_id ) );
		}
		if ( !empty($attach_id) ) {
			$gallery_ids = listdo_get_listing_gallery( $post_id );
			if (!empty($gallery_ids) && isset($gallery_ids[0]) && $gallery_ids[0]) {
				$attach_id = $gallery_ids[0];
			}
		}
		if ( !empty($attach_id) ) {
			$data = wp_get_attachment_image_src( $gallery_ids[0], $size );
			if ( isset( $data[0] ) && ! empty ( $data ) ) {
				return $data[0];
			}
		}
		return false;
	}
}

if ( ! function_exists( 'listdo_get_listing_gallery' ) ) {
	function listdo_get_listing_gallery( $post_id ) {
		$ids = array();
		if ( has_post_thumbnail( $post_id ) ) {
			$attach_id = esc_sql( get_post_thumbnail_id( $post_id ) );
			$ids[] = $attach_id;
		}

		$return = get_post_meta( $post_id, '_job_gallery_images', true );

		if ( !empty($return) ) {
			$ids = array_merge($ids, $return);
		}
		return $ids;
	}
}

function listdo_sort_array_by_priority( $a, $b ) {
	if ( $a['priority'] == $b['priority'] ) {
		return 0;
	}

	return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
}

function listdo_get_current_time() {
	global $wp_locale;
	$timezone  = get_option('gmt_offset');
	$time = gmdate(get_option('time_format'), time() + 3600*($timezone+date("I"))); 
	
	$time = strtotime($time);
	$day_of_week = date('N',$time);
	if ( $day_of_week == 7 ) {
		$day_of_week = 0;
	}
	$day = $wp_locale->get_weekday( $day_of_week );
	
	$day = ucfirst($day);
	return array( 'day' => $day, 'time' => $time );
}

function listdo_get_current_time_status($listing_id) {
	$current = listdo_get_current_time();
	$current_day = strtolower($current['day']);
	$current_time = $current['time'];
	$hours = get_post_meta( $listing_id, '_job_hours', true );
	
	if ( !empty($hours['day']) ) {
		$days = listdo_get_day_hours($hours['day']);
		
		if ( !empty($days[$current_day]) || !empty($days[$current['day']]) ) {
			$times = !empty($days[$current_day]) ? $days[$current_day] : $days[$current['day']];
			
			if ( is_array($times) ) {
				foreach ($times as $time) {
					$opentime = strtotime($time[0]);	
					$closedtime = strtotime($time[1]);
					if ( $opentime <= $closedtime ) {
						if ( $current_time >= $opentime && $current_time <= $closedtime ) {
							return true;
						}
					} else {
						$is_open = true;
						if( $current_time < $opentime ){
				            if( $current_time > $closedtime ){
				                $is_open = false;
				            }
				        }
				        return $is_open;
					}
				}
			} elseif ( $times == 'open' ) {
				return true;
			} elseif ( $times == 'closed' ) {
				return false;
			}
		} else {
			return true;
		}
	}
	return false;
}

function listdo_get_day_hours($hours) {
	global $wp_locale;
	if (empty($hours) || !is_array($hours)) {
		return;
	}
    $numericdays = listdo_get_days_of_week();
    $days = array();

    foreach ( $numericdays as $key => $i ) {
        $day = $wp_locale->get_weekday( $i );
        if ( isset($hours[ $i ][ 'type' ]) && $hours[ $i ][ 'type' ] == 'enter_hours' ) {
        	if ( !empty($hours[ $i ][ 'from' ]) && !empty($hours[ $i ][ 'to' ]) ) {
        		$t_day = array();
        		foreach ($hours[ $i ][ 'from' ] as $key => $value) {
        			$start = $value;
        			$end = !empty($hours[ $i ][ 'to' ][$key]) ? $hours[ $i ][ 'to' ][$key] : false;
        			if ( $start && $end ) {
        				$start = strtotime($start);
        				$end = strtotime($end);
	        			$t_day[] = array( date(get_option('time_format'), $start), date(get_option('time_format'), $end) );
	        		}
        		}
        		$days[ $day ] = $t_day;
        	}

	    } elseif ( isset($hours[ $i ][ 'type' ]) && $hours[ $i ][ 'type' ] == 'open_all_day' ) {
	    	$days[ $day ] = 'open';
	    } elseif ( isset($hours[ $i ][ 'type' ]) && $hours[ $i ][ 'type' ] == 'closed_all_day' ) {
	    	$days[ $day ] = 'closed';
	    }
    }
    return $days;
}

function listdo_check_menu_prices_empty($section) {
	$titles_value = !empty($section['title']) ? $section['title'] : array();
	$items_prices = !empty($section['price']) ?  $section['price'] : array();
	$items_descriptions = !empty($section['description']) ? $section['description'] : array();
	if ( !empty($titles_value) && count($titles_value) > 0 ) {
		foreach ($titles_value as $key => $value) {
			if ( !empty($value) || !empty($items_prices[$key]) || !empty($items_descriptions[$key])  ) {
				return true;
			}
		}
	}
	return false;
}

function listdo_check_event_schedule_empty($section) {
	$titles_value = !empty($section['title']) ? $section['title'] : array();
	$items_times = !empty($section['time']) ?  $section['time'] : array();
	$items_descriptions = !empty($section['description']) ? $section['description'] : array();
	if ( !empty($titles_value) && count($titles_value) > 0 ) {
		foreach ($titles_value as $key => $value) {
			if ( !empty($value) || !empty($items_times[$key]) || !empty($items_descriptions[$key])  ) {
				return true;
			}
		}
	}
	return false;
}


function listdo_price_format_number( $price ) {
	if ( empty( $price ) || !is_numeric( $price ) ) {
		$price = 0;
	}

	$money_decimals = listdo_get_config('listing_currency_decimal_places', 0);
	$money_dec_point = listdo_get_config('listing_currency_decimal_separator', 0);
	$money_thousands_separator = listdo_get_config('listing_currency_thousands_separator', 0);


	$price_parts_dot = explode( '.', $price );
	$price_parts_col = explode( ',', $price );

	if ( count( $price_parts_dot ) > 1 || count( $price_parts_col ) > 1 ) {
		$decimals = ! empty( $money_decimals ) ? $money_decimals : '0';
	} else {
		$decimals = 0;
	}

	$dec_point = ! empty( $money_dec_point ) ? $money_dec_point : '.';
	$thousands_separator = ! empty( $money_thousands_separator ) ? $money_thousands_separator : ',';

	$price = number_format( $price, $decimals, $dec_point, $thousands_separator );

	return $price;
}

function listdo_job_manager_price_range_icons() {
	$symbol = listdo_get_config('listing_currency_symbol', '$');
	$currency_symbol = ! empty( $symbol ) ? $symbol : '$';
	return apply_filters( 'listdo_job_manager_price_range_icons', array(
		'inexpensive' => array(
						'icon' => $currency_symbol,
						'label' => esc_html__('Inexpensive', 'listdo')
					),
		'moderate' => array(
						'icon' => $currency_symbol.$currency_symbol,
						'label' => esc_html__('Moderate', 'listdo')
					),
		'pricey' => array(
						'icon' => $currency_symbol.$currency_symbol.$currency_symbol,
						'label' => esc_html__('Pricey', 'listdo')
					),
		'ultra_high_end' =>array(
						'icon' => $currency_symbol.$currency_symbol.$currency_symbol.$currency_symbol,
						'label' => esc_html__('Ultra High', 'listdo')
					),
	));
}

function listdo_get_min_max_meta_value( $key ){

    global $wpdb;
    $cash_key = md5($key);
    $results = wp_cache_get($cash_key);

    if ($results === false) {

    	$sql  = "SELECT min( CAST( postmeta.meta_value AS UNSIGNED ) ) as min, max( CAST( postmeta.meta_value AS UNSIGNED ) ) as max FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as postmeta ON {$wpdb->posts}.ID = postmeta.post_id ";
		$sql .= " 	WHERE {$wpdb->posts}.post_type = 'job_listing'
					AND {$wpdb->posts}.post_status = 'publish'
					AND postmeta.meta_key='%s' ";

        $query = $wpdb->prepare( $sql, $key);
        
        return $wpdb->get_row( $query );

    }

    return $results;
}

function listdo_get_field_options($field_type) {
	$fields = array();
	if ( !class_exists('ApusListdo_Custom_Fields') ) {
		return $fields;
	}
	$custom_all_fields = apuslistdo_get_custom_fields_data();
	if (is_array($custom_all_fields) && sizeof($custom_all_fields) > 0) {
		
		$dtypes = apuslistdo_get_all_field_types();
        $available_types = apuslistdo_all_types_available_fields();
        $required_types = apuslistdo_all_types_required_fields();

		foreach ($custom_all_fields as $key => $custom_field) {
			$fieldkey = isset($custom_field['type']) ? $custom_field['type'] : '';
			if ( $fieldkey === $field_type ) {
				if ( !empty($fieldkey) ) {
					$type = '';
					if ( isset($required_types[$fieldkey]) ) {
						$field_data = wp_parse_args( $custom_field, $required_types[$fieldkey]);
						$fieldtype = isset($required_types[$fieldkey]['type']) ? $required_types[$fieldkey]['type'] : '';
					} elseif ( isset($available_types[$fieldkey]) ) {
						$field_data = wp_parse_args( $custom_field, $available_types[$fieldkey]);
						$fieldtype = isset($available_types[$fieldkey]['type']) ? $available_types[$fieldkey]['type'] : '';
					} elseif ( in_array($fieldkey, $dtypes) ) {
						$fieldkey = isset($custom_field['key']) ? $custom_field['key'] : '';
						$fieldtype = isset($custom_field['type']) ? $custom_field['type'] : '';
						$field_data = $custom_field;
					}
					if ( $fieldtype ) {
						$fields = ApusListdo_Custom_Fields::render_field($field_data, $fieldkey, $fieldtype, 1);
					}
				}
				return $fields;
			}
		}
	}
}

function listdo_job_manager_dropdown_types( $args = '' ) {
	$defaults = array(
		'orderby'         => 'id',
		'order'           => 'ASC',
		'show_count'      => 0,
		'hide_empty'      => 1,
		'parent'          => '',
		'child_of'        => 0,
		'exclude'         => '',
		'echo'            => 1,
		'selected'        => 0,
		'hierarchical'    => 0,
		'name'            => 'cat',
		'id'              => '',
		'class'           => 'job-manager-category-dropdown ' . ( is_rtl() ? 'chosen-rtl' : '' ),
		'depth'           => 0,
		'taxonomy'        => 'job_listing_type',
		'value'           => 'slug',
		'multiple'        => false,
		'show_option_all' => false,
		'placeholder'     => esc_html__( 'Choose a category&hellip;', 'listdo' ),
		'no_results_text' => esc_html__( 'No results match', 'listdo' ),
		'multiple_text'   => esc_html__( 'Select Some Options', 'listdo' ),
	);

	$r = wp_parse_args( $args, $defaults );

	if ( ! isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
		$r['pad_counts'] = true;
	}

	/** This filter is documented in wp-job-manager.php */
	$r['lang'] = apply_filters( 'wpjm_lang', null );

	// Store in a transient to help sites with many cats.
	$categories_hash = 'jm_cats_' . md5( wp_json_encode( $r ) . WP_Job_Manager_Cache_Helper::get_transient_version( 'jm_get_' . $r['taxonomy'] ) );
	$categories      = get_transient( $categories_hash );

	if ( empty( $categories ) ) {
		$categories = get_terms(
			array(
				'taxonomy'     => $r['taxonomy'],
				'orderby'      => $r['orderby'],
				'order'        => $r['order'],
				'hide_empty'   => $r['hide_empty'],
				'parent'       => $r['parent'],
				'child_of'     => $r['child_of'],
				'exclude'      => $r['exclude'],
				'hierarchical' => $r['hierarchical'],
			)
		);
		set_transient( $categories_hash, $categories, DAY_IN_SECONDS * 7 );
	}

	$id = $r['id'] ? $r['id'] : $r['name'];

	$output = "<select name='" . esc_attr( $r['name'] ) . "[]' id='" . esc_attr( $id ) . "' class='" . esc_attr( $r['class'] ) . "' " . ( $r['multiple'] ? "multiple='multiple'" : '' ) . " data-placeholder='" . esc_attr( $r['placeholder'] ) . "' data-no_results_text='" . esc_attr( $r['no_results_text'] ) . "' data-multiple_text='" . esc_attr( $r['multiple_text'] ) . "'>\n";

	if ( $r['show_option_all'] ) {
		$output .= '<option value="">' . esc_html( $r['show_option_all'] ) . '</option>';
	}

	if ( ! empty( $categories ) ) {
		include_once get_template_directory() . '/inc/vendors/wp-job-manager/class-wp-job-manager-category-walker.php';

		$walker = new Listdo_WP_Job_Manager_Category_Walker();

		if ( $r['hierarchical'] ) {
			$depth = $r['depth'];  // Walk the full depth.
		} else {
			$depth = -1; // Flat.
		}

		$output .= $walker->walk( $categories, $depth, $r );
	}

	$output .= "</select>\n";

	if ( $r['echo'] ) {
		echo trim($output); // WPCS: XSS ok.
	}

	return $output;
}

// amenity field
add_action( 'wp_ajax_listdo_process_change_category_amenity', 'listdo_process_change_category_amenity' );
add_action( 'wp_ajax_nopriv_listdo_process_change_category_amenity', 'listdo_process_change_category_amenity' );
function listdo_process_change_category_amenity() {

	check_ajax_referer( 'listdo-ajax-nonce', 'security' );
	
	$category_parent = !empty($_POST['category_parent']) ? $_POST['category_parent'] : '';
	
	$g_fields = apply_filters('submit_job_form_fields', array());
	if ( !empty($g_fields['job']) && !empty($g_fields['job']['job_amenities']) ) {
		$field = $g_fields['job']['job_amenities'];
		if ( !empty($_POST['job_id']) ) {
			$field['value'] = wp_get_object_terms( $_POST['job_id'], 'job_listing_amenity', array( 'fields' => 'ids' ) );
		} else {
			$field['value'] = 0;
		}
		$field['category_parent'] = $category_parent;
		?>
		<div class="field <?php echo ( isset($field['required']) && $field['required'] ) ? 'required-field' : ''; ?>">
			<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => 'job_amenities', 'field' => $field ) ); ?>
		</div>
		<?php
	}

	die();
}

// amenity admin field
add_action( 'wp_ajax_listdo_process_change_category_amenity_admin', 'listdo_process_change_category_amenity_admin' );
add_action( 'wp_ajax_nopriv_listdo_process_change_category_amenity_admin', 'listdo_process_change_category_amenity_admin' );
function listdo_process_change_category_amenity_admin() {

	check_ajax_referer( 'listdo-ajax-nonce', 'security' );
	
	$category_parent = !empty($_POST['category_parent']) ? $_POST['category_parent'] : '';
	
	$g_fields = apply_filters('job_manager_job_listing_data_fields', array());
	if ( !empty($g_fields) && !empty($g_fields['_job_amenities']) ) {
		$field = $g_fields['_job_amenities'];
		if ( !empty($_POST['job_id']) ) {
			$field['value'] = wp_get_object_terms( $_POST['job_id'], 'job_listing_amenity', array( 'fields' => 'ids' ) );
		} else {
			$field['value'] = 0;
		}
		$field['category_parent'] = $category_parent;
		?>
		<div class="field <?php echo ( isset($field['required']) && $field['required'] ) ? 'required-field' : ''; ?>">
			<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => '_job_amenities', 'field' => $field ) ); ?>
		</div>
		<?php
	}

	die();
}


add_action( 'wp_ajax_listdo_process_change_category_amenities', 'listdo_process_change_category_amenities' );
add_action( 'wp_ajax_nopriv_listdo_process_change_category_amenities', 'listdo_process_change_category_amenities' );
function listdo_process_change_category_amenities() {
	check_ajax_referer( 'listdo-ajax-nonce', 'security' );
	$category_parent = !empty($_POST['category_parent']) ? $_POST['category_parent'] : '';
	if ( $category_parent && ($cat_term = get_term_by('term_id', $category_parent, 'job_listing_category')) ) {
	?>
		<div class="amenities-wrap">
			<?php
				$args = array(
					'hierarchical' => 1,
					'hide_empty' => false,
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'apus_category_parent',
							'value' => '"' . $cat_term->slug . '"',
							'compare' => 'LIKE',
						),
						array(
							'key' => 'apus_category_parent',
							'value' => '',
						),
						array(
							'key' => 'apus_category_parent',
							'compare' => 'NOT EXISTS',
						)
					)
				);
				$job_amenities = get_terms( array( 'job_listing_amenity' ), $args );

				if ( !empty( $job_amenities ) ) {
					$selected = '';
					if ( is_tax( 'job_listing_amenity' )  ) {
						global $wp_query;
						$term =	$wp_query->queried_object;
						$selected = $term->slug;
					}
					$rand = rand(100, 9999);
				?>
					<ul class="job_amenities">
						<?php foreach ( $job_amenities as $amenity ) : ?>
							<li>
								<label for="job_amenity_<?php echo esc_attr($amenity->slug.'-'.$rand); ?>" class="<?php echo sanitize_title( $amenity->name ); ?>">
									<input type="checkbox" name="filter_job_amenity[]" value="<?php echo trim($amenity->slug); ?>" id="job_amenity_<?php echo esc_attr($amenity->slug.'-'.$rand); ?>" <?php echo trim($selected == $amenity->slug ? 'checked="checked"' : ''); ?> /> <?php echo trim($amenity->name); ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
			<?php } ?>
		</div>
		<?php
	} else {
		?>
		<div class="amenities-wrap">
			<div class="alert alert-warning"><?php esc_html_e('Please choose category to display filters', 'listdo'); ?></div>
		</div>
		<?php
	}
	die();
}

function listdo_all_countries() {
    $countries = array(
    	''	=> esc_html__('All Countries', 'listdo'),
        'af' => 'Afghanistan',
        'ax' => 'Islands',
        'al' => 'Albania',
        'dz' => 'Algeria',
        'as' => 'American Samoa',
        'ad' => 'Andorra',
        'ao' => 'Angola',
        'ai' => 'Anguilla',
        'aq' => 'Antarctica',
        'ag' => 'Antigua and Barbuda',
        'ar' => 'Argentina',
        'am' => 'Armenia',
        'aw' => 'Aruba',
        'au' => 'Australia',
        'at' => 'Austria',
        'az' => 'Azerbaijan',
        'bs' => 'Bahamas',
        'bh' => 'Bahrain',
        'bd' => 'Bangladesh',
        'bb' => 'Barbados',
        'by' => 'Belarus',
        'be' => 'Belgium',
        'bz' => 'Belize',
        'bj' => 'Benin',
        'bm' => 'Bermuda',
        'bt' => 'Bhutan',
        'bo' => 'Bolivia, Plurinational State of',
        'bq' => 'Bonaire, Sint Eustatius and Saba',
        'ba' => 'Bosnia and Herzegovina',
        'bw' => 'Botswana',
        'bv' => 'Bouvet Island',
        'br' => 'Brazil',
        'io' => 'British Indian Ocean Territory',
        'bn' => 'Brunei Darussalam',
        'bg' => 'Bulgaria',
        'bf' => 'Burkina Faso',
        'bi' => 'Burundi',
        'kh' => 'Cambodia',
        'cm' => 'Cameroon',
        'ca' => 'Canada',
        'cv' => 'Cape Verde',
        'ky' => 'Cayman Islands',
        'cf' => 'Central African Republic',
        'td' => 'Chad',
        'cl' => 'Chile',
        'cn' => 'China',
        'cx' => 'Christmas Island',
        'cc' => 'Cocos (Keeling) Islands',
        'co' => 'Colombia',
        'km' => 'Comoros',
        'cg' => 'Congo',
        'cd' => 'Congo, the Democratic Republic of the',
        'ck' => 'Cook Islands',
        'cr' => 'Costa Rica',
        'ci' => 'Côte d\'Ivoire',
        'hr' => 'Croatia',
        'cu' => 'Cuba',
        'cw' => 'Curaçao',
        'cy' => 'Cyprus',
        'cz' => 'Czech Republic',
        'dk' => 'Denmark',
        'dj' => 'Djibouti',
        'dm' => 'Dominica',
        'do' => 'Dominican Republic',
        'ec' => 'Ecuador',
        'eg' => 'Egypt',
        'sv' => 'El Salvador',
        'gq' => 'Equatorial Guinea',
        'er' => 'Eritrea',
        'ee' => 'Estonia',
        'et' => 'Ethiopia',
        'fk' => 'Falkland Islands (Malvinas)',
        'fo' => 'Faroe Islands',
        'fj' => 'Fiji',
        'fi' => 'Finland',
        'fr' => 'France',
        'gf' => 'French Guiana',
        'pf' => 'French Polynesia',
        'tf' => 'French Southern Territories',
        'ga' => 'Gabon',
        'gm' => 'Gambia',
        'ge' => 'Georgia',
        'de' => 'Germany',
        'gh' => 'Ghana',
        'gi' => 'Gibraltar',
        'gr' => 'Greece',
        'gl' => 'Greenland',
        'gd' => 'Grenada',
        'gp' => 'Guadeloupe',
        'gu' => 'Guam',
        'gt' => 'Guatemala',
        'gg' => 'Guernsey',
        'gn' => 'Guinea',
        'gw' => 'Guinea-Bissau',
        'gy' => 'Guyana',
        'ht' => 'Haiti',
        'hm' => 'Heard Island and McDonald Islands',
        'va' => 'Holy See (Vatican City State)',
        'hn' => 'Honduras',
        'hk' => 'Hong Kong',
        'hu' => 'Hungary',
        'is' => 'Iceland',
        'in' => 'India',
        'id' => 'Indonesia',
        'ir' => 'Iran, Islamic Republic of',
        'iq' => 'Iraq',
        'ie' => 'Ireland',
        'im' => 'Isle of Man',
        'il' => 'Israel',
        'it' => 'Italy',
        'jm' => 'Jamaica',
        'jp' => 'Japan',
        'je' => 'Jersey',
        'jo' => 'Jordan',
        'kz' => 'Kazakhstan',
        'ke' => 'Kenya',
        'ki' => 'Kiribati',
        'kp' => 'Korea, Democratic People\'s Republic of',
        'kr' => 'Korea, Republic of',
        'kw' => 'Kuwait',
        'kg' => 'Kyrgyzstan',
        'la' => 'Lao People\'s Democratic Republic',
        'lv' => 'Latvia',
        'lb' => 'Lebanon',
        'ls' => 'Lesotho',
        'lr' => 'Liberia',
        'ly' => 'Libya',
        'li' => 'Liechtenstein',
        'lt' => 'Lithuania',
        'lu' => 'Luxembourg',
        'mo' => 'Macao',
        'mk' => 'Macedonia, the Former Yugoslav Republic of',
        'mg' => 'Madagascar',
        'mw' => 'Malawi',
        'my' => 'Malaysia',
        'mv' => 'Maldives',
        'ml' => 'Mali',
        'mt' => 'Malta',
        'mh' => 'Marshall Islands',
        'mq' => 'Martinique',
        'mr' => 'Mauritania',
        'mu' => 'Mauritius',
        'yt' => 'Mayotte',
        'mx' => 'Mexico',
        'fm' => 'Micronesia, Federated States of',
        'md' => 'Moldova, Republic of',
        'mc' => 'Monaco',
        'mn' => 'Mongolia',
        'me' => 'Montenegro',
        'ms' => 'Montserrat',
        'ma' => 'Morocco',
        'mz' => 'Mozambique',
        'mm' => 'Myanmar',
        'na' => 'Namibia',
        'nr' => 'Nauru',
        'np' => 'Nepal',
        'nl' => 'Netherlands',
        'nc' => 'New Caledonia',
        'nz' => 'New Zealand',
        'ni' => 'Nicaragua',
        'ne' => 'Niger',
        'ng' => 'Nigeria',
        'nu' => 'Niue',
        'nf' => 'Norfolk Island',
        'mp' => 'Northern Mariana Islands',
        'no' => 'Norway',
        'om' => 'Oman',
        'pk' => 'Pakistan',
        'pw' => 'Palau',
        'ps' => 'Palestine, State of',
        'pa' => 'Panama',
        'pg' => 'Papua New Guinea',
        'py' => 'Paraguay',
        'pe' => 'Peru',
        'ph' => 'Philippines',
        'pn' => 'Pitcairn',
        'pl' => 'Poland',
        'pt' => 'Portugal',
        'pr' => 'Puerto Rico',
        'qa' => 'Qatar',
        're' => 'Réunion',
        'ro' => 'Romania',
        'ru' => 'Russian Federation',
        'rw' => 'Rwanda',
        'bl' => 'Saint Barthélemy',
        'sh' => 'Saint Helena, Ascension and Tristan da Cunha',
        'kn' => 'Saint Kitts and Nevis',
        'lc' => 'Saint Lucia',
        'mf' => 'Saint Martin (French part)',
        'pm' => 'Saint Pierre and Miquelon',
        'vc' => 'Saint Vincent and the Grenadines',
        'ws' => 'Samoa',
        'sm' => 'San Marino',
        'st' => 'Sao Tome and Principe',
        'sa' => 'Saudi Arabia',
        'sn' => 'Senegal',
        'rs' => 'Serbia',
        'sc' => 'Seychelles',
        'sl' => 'Sierra Leone',
        'sg' => 'Singapore',
        'sx' => 'Sint Maarten (Dutch part)',
        'sk' => 'Slovakia',
        'si' => 'Slovenia',
        'sb' => 'Solomon Islands',
        'so' => 'Somalia',
        'za' => 'South Africa',
        'gs' => 'South Georgia and the South Sandwich Islands',
        'ss' => 'South Sudan',
        'es' => 'Spain',
        'lk' => 'Sri Lanka',
        'sd' => 'Sudan',
        'sr' => 'Suriname',
        'sj' => 'Svalbard and Jan Mayen',
        'sz' => 'Swaziland',
        'se' => 'Sweden',
        'ch' => 'Switzerland',
        'sy' => 'Syrian Arab Republic',
        'tw' => 'Taiwan, Province of China',
        'tj' => 'Tajikistan',
        'tz' => 'Tanzania, United Republic of',
        'th' => 'Thailand',
        'tl' => 'Timor-Leste',
        'tg' => 'Togo',
        'tk' => 'Tokelau',
        'to' => 'Tonga',
        'tt' => 'Trinidad and Tobago',
        'tn' => 'Tunisia',
        'tr' => 'Turkey',
        'tm' => 'Turkmenistan',
        'tc' => 'Turks and Caicos Islands',
        'tv' => 'Tuvalu',
        'ug' => 'Uganda',
        'ua' => 'Ukraine',
        'ae' => 'United Arab Emirates',
        'gb' => 'United Kingdom',
        'us' => 'United States',
        'um' => 'United States Minor Outlying Islands',
        'uy' => 'Uruguay',
        'uz' => 'Uzbekistan',
        'vu' => 'Vanuatu',
        've' => 'Venezuela, Bolivarian Republic of',
        'vn' => 'Viet Nam',
        'vg' => 'Virgin Islands, British',
        'vi' => 'Virgin Islands, U.S.',
        'wf' => 'Wallis and Futuna',
        'eh' => 'Western Sahara',
        'ye' => 'Yemen',
        'zm' => 'Zambia',
        'zw' => 'Zimbabwe'
    );

    return apply_filters( 'listdo-all-countries', $countries );
}