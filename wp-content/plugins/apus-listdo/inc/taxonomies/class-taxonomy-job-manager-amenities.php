<?php
/**
 * Amenities
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ApusListdo_Taxonomy_Amenities{

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
			'name'              => __( 'Amenities', 'apus-listdo' ),
			'singular_name'     => __( 'Amenity', 'apus-listdo' ),
			'search_items'      => __( 'Search Amenities', 'apus-listdo' ),
			'all_items'         => __( 'All Amenities', 'apus-listdo' ),
			'parent_item'       => __( 'Parent Amenity', 'apus-listdo' ),
			'parent_item_colon' => __( 'Parent Amenity:', 'apus-listdo' ),
			'edit_item'         => __( 'Edit', 'apus-listdo' ),
			'update_item'       => __( 'Update', 'apus-listdo' ),
			'add_new_item'      => __( 'Add New', 'apus-listdo' ),
			'new_item_name'     => __( 'New Amenity', 'apus-listdo' ),
			'menu_name'         => __( 'Amenities', 'apus-listdo' ),
		);

		register_taxonomy( 'job_listing_amenity', 'job_listing', array(
			'labels'            => apply_filters( 'apus_listdo_taxomony_booking_amenities_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'amenity',
			'rewrite'           => array( 'slug' => __( 'amenity', 'apus-listdo' ) ),
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'		=> true
		) );
	}

}

ApusListdo_Taxonomy_Amenities::init();