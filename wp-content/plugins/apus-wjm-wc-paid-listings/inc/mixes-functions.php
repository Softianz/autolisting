<?php
/**
 * functions
 *
 * @package    apus-wjm-wc-paid-listings
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  25/10/2018 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function apus_wjm_wc_paid_listings_create_user_package( $user_id, $product_id, $order_id ) {
	$package = wc_get_product( $product_id );

	if ( !$package->is_type( 'listing_package' ) ) {
		return false;
	}

	$args = apply_filters( 'apus_wjm_wc_paid_listings_create_user_package_data', array(
		'post_title' => $package->get_title(),
		'post_status' => 'publish',
		'post_type' => 'listing_package',
	), $user_id, $product_id, $order_id);

	$user_package_id = wp_insert_post( $args );
	if ( $user_package_id ) {
		// general metas
		update_post_meta( $user_package_id, '_product_id', $product_id );
		update_post_meta( $user_package_id, '_order_id', $order_id );
		update_post_meta( $user_package_id, '_package_count', 0 );
		update_post_meta( $user_package_id, '_user_id', $user_id );

		// listing metas
		$feature_listings = get_post_meta($product_id, '_feature_listings', true );
		$duration_listings = get_post_meta($product_id, '_listings_duration', true );
		$limit_listings = get_post_meta($product_id, '_listings_limit', true );

		update_post_meta( $user_package_id, '_feature_listings', $feature_listings );
		update_post_meta( $user_package_id, '_listings_duration', $duration_listings );
		update_post_meta( $user_package_id, '_listings_limit', $limit_listings );

		do_action('apus_wjm_wc_paid_listings_create_user_package_meta', $user_package_id, $user_id, $product_id, $order_id);
	}

	return $user_package_id;
}

function apus_wjm_wc_paid_listings_approve_listing_with_package( $listing_id, $user_id, $user_package_id ) {
	if ( apus_wjm_wc_paid_listings_package_is_valid( $user_id, $user_package_id ) ) {
		$listing = array(
			'ID'            => $listing_id,
			'post_date'     => current_time( 'mysql' ),
			'post_date_gmt' => current_time( 'mysql', 1 )
		);
		$post_type = get_post_type( $listing_id );
		if ( $post_type === 'job_listing' ) {
			delete_post_meta( $listing_id, '_job_expires' );
			$listing['post_status'] = get_option( 'job_manager_submission_requires_approval' ) ? 'pending' : 'publish';
		}

		// Do update
		wp_update_post( $listing );
		update_post_meta( $listing_id, '_user_package_id', $user_package_id );
		apus_wjm_wc_paid_listings_increase_package_count( $user_id, $user_package_id );
	}
}

function apus_wjm_wc_paid_listings_package_is_valid( $user_id, $user_package_id ) {
	$post = get_post($user_package_id);
	if ( empty($post) ) {
		return false;
	}
	$package_user_id = get_post_meta($user_package_id, '_user_id', true);
	$package_count = get_post_meta($user_package_id, '_package_count', true);
	$listings_limit = get_post_meta($user_package_id, '_listings_limit', true);

	if ( ($package_user_id != $user_id) || ($package_count >= $listings_limit && $listings_limit != 0) ) {
		return false;
	}

	return true;
}

function apus_wjm_wc_paid_listings_increase_package_count( $user_id, $user_package_id ) {
	$post = get_post($user_package_id);
	if ( empty($post) ) {
		return false;
	}
	$package_user_id = get_post_meta($user_package_id, '_user_id', true);
	
	if ( $package_user_id != $user_id ) {
		return false;
	}
	$package_count = intval(get_post_meta($user_package_id, '_package_count', true)) + 1;
	
	update_post_meta($user_package_id, '_package_count', $package_count);
}

function apus_wjm_wc_paid_listings_get_packages_by_user( $user_id, $valid = true ) {
	$query_args = array(
		'post_type' => 'listing_package',
		'post_status' => 'publish',
		'posts_per_page'   => -1,
		'order'            => 'asc',
		'orderby'          => 'menu_order',
		'meta_query' => array(
			array(
				'key'     => '_user_id',
				'value'   => $user_id,
				'compare' => '='
			)
		)
	);
	
	$packages = get_posts($query_args);
	$return = array();
	if ( $valid && $packages ) {
		foreach ($packages as $package) {
			$package_count = get_post_meta($package->ID, '_package_count', true);
			$listings_limit = get_post_meta($package->ID, '_listings_limit', true);

			if ( $package_count < $listings_limit || $listings_limit == 0 ) {
				$return[] = $package;
			}
			
		}
	} else {
		$return = $packages;
	}
	return $return;
}

