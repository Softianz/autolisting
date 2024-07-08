<?php
/**
 * Regions
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ApusListdo_Taxonomy_Regions{

	/**
	 *
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ), 1 );
	}

	/**
	 *
	 */
	public static function definition() {
		$labels = array(
			'name'              => __( 'Regions', 'apus-listdo' ),
			'singular_name'     => __( 'Region', 'apus-listdo' ),
			'search_items'      => __( 'Search Regions', 'apus-listdo' ),
			'all_items'         => __( 'All Regions', 'apus-listdo' ),
			'parent_item'       => __( 'Parent Region', 'apus-listdo' ),
			'parent_item_colon' => __( 'Parent Region:', 'apus-listdo' ),
			'edit_item'         => __( 'Edit', 'apus-listdo' ),
			'update_item'       => __( 'Update', 'apus-listdo' ),
			'add_new_item'      => __( 'Add New', 'apus-listdo' ),
			'new_item_name'     => __( 'New Region', 'apus-listdo' ),
			'menu_name'         => __( 'Regions', 'apus-listdo' ),
		);

		register_taxonomy( 'job_listing_region', 'job_listing', array(
			'labels'            => apply_filters( 'apuslistdo_taxomony_booking_amenities_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'region',
			'rewrite'           => array( 'slug' => __( 'region', 'apus-listdo' ) ),
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'		=> false
		) );
	}
	
}

ApusListdo_Taxonomy_Regions::init();