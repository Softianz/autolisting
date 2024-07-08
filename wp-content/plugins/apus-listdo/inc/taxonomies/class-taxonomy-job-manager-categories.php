<?php
/**
 * Categories
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ApusListdo_Taxonomy_Categories{

	/**
	 *
	 */
	public static function init() {
		add_filter( 'register_taxonomy_job_listing_category_args', array( __CLASS__, 'change_taxonomy_category_label' ), 10 );
	}

	public static function change_taxonomy_category_label($args) {
		$singular = apply_filters( 'apus-listdo-job-listing-category-singular', esc_html__( 'Category', 'apus-listdo' ) );
		$plural   = apply_filters( 'apus-listdo-job-listing-category-plural', esc_html__( 'Categories', 'apus-listdo' ) );

		$args['label'] = $plural;

		$args['labels'] = array(
			'name'              => $plural,
			'singular_name'     => $singular,
			'menu_name'         => ucwords( $plural ),
			// translators: Placeholder %s is the plural label of the job listing category taxonomy type.
			'search_items'      => sprintf( __( 'Search %s', 'wp-job-manager' ), $plural ),
			// translators: Placeholder %s is the plural label of the job listing category taxonomy type.
			'all_items'         => sprintf( __( 'All %s', 'wp-job-manager' ), $plural ),
			// translators: Placeholder %s is the singular label of the job listing category taxonomy type.
			'parent_item'       => sprintf( __( 'Parent %s', 'wp-job-manager' ), $singular ),
			// translators: Placeholder %s is the singular label of the job listing category taxonomy type.
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-job-manager' ), $singular ),
			// translators: Placeholder %s is the singular label of the job listing category taxonomy type.
			'edit_item'         => sprintf( __( 'Edit %s', 'wp-job-manager' ), $singular ),
			// translators: Placeholder %s is the singular label of the job listing category taxonomy type.
			'update_item'       => sprintf( __( 'Update %s', 'wp-job-manager' ), $singular ),
			// translators: Placeholder %s is the singular label of the job listing category taxonomy type.
			'add_new_item'      => sprintf( __( 'Add New %s', 'wp-job-manager' ), $singular ),
			// translators: Placeholder %s is the singular label of the job listing category taxonomy type.
			'new_item_name'     => sprintf( __( 'New %s Name', 'wp-job-manager' ), $singular ),
		);


		return $args;
	}

}

ApusListdo_Taxonomy_Categories::init();