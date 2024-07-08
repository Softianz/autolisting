<?php
/**
 * Packages
 *
 * @package    apus-wjm-wc-paid-listings
 * @author     Apusthemes <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Apus Framework
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class ApusWJMWCPaidListings_Post_Type_Packages {

  	public static function init() {
    	add_action( 'init', array( __CLASS__, 'register_post_type' ) );

    	add_action( 'add_meta_boxes', array( __CLASS__, 'metaboxes' ) );
	  	add_action( 'save_post', array( __CLASS__, 'save' ), 20, 2 );
  	}

  	public static function register_post_type() {
	    $labels = array(
			'name'                  => esc_html__( 'Package', 'apus-wjm-wc-paid-listings' ),
			'singular_name'         => esc_html__( 'Package', 'apus-wjm-wc-paid-listings' ),
			'add_new'               => esc_html__( 'Add New Package', 'apus-wjm-wc-paid-listings' ),
			'add_new_item'          => esc_html__( 'Add New Package', 'apus-wjm-wc-paid-listings' ),
			'edit_item'             => esc_html__( 'Edit Package', 'apus-wjm-wc-paid-listings' ),
			'new_item'              => esc_html__( 'New Package', 'apus-wjm-wc-paid-listings' ),
			'all_items'             => esc_html__( 'Packages', 'apus-wjm-wc-paid-listings' ),
			'view_item'             => esc_html__( 'View Package', 'apus-wjm-wc-paid-listings' ),
			'search_items'          => esc_html__( 'Search Package', 'apus-wjm-wc-paid-listings' ),
			'not_found'             => esc_html__( 'No Packages found', 'apus-wjm-wc-paid-listings' ),
			'not_found_in_trash'    => esc_html__( 'No Packages found in Trash', 'apus-wjm-wc-paid-listings' ),
			'parent_item_colon'     => '',
			'menu_name'             => esc_html__( 'Packages', 'apus-wjm-wc-paid-listings' ),
	    );

	    register_post_type( 'listing_package',
	      	array(
		        'labels'            => apply_filters( 'apus_suchen_postype_package_fields_labels' , $labels ),
		        'supports'          => array( 'title' ),
		        'public'            => true,
		        'has_archive'       => false,
		        'publicly_queryable' => false,
		        'menu_position'     => 52,
		        'show_in_menu'		=> 'edit.php?post_type=job_listing',
	      	)
	    );
  	}
  	
  	public static function metaboxes( $post_type ) {
		$post_types = array( 'listing_package' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'listing_package_general_settings',
                esc_html__( 'General Settings', 'apus-wjm-wc-paid-listings' ),
                array( __CLASS__, 'render_meta_box' ),
                $post_type,
                'advanced',
                'high'
            );
        }
	}

	public static function render_meta_box( $post ) {
	    echo ApusWJMWCPaidListings_Template_Loader::get_template_part( 'admin/meta-box', array('post' => $post) );
  	}

  	public static function save($post_id, $post) {
		if ( !empty($post) && $post->post_type == 'listing_package' ) {
			if ( isset($_POST['_product_id']) ) {
				update_post_meta($post_id, '_product_id', $_POST['_product_id']);
			}
			if ( isset($_POST['_order_id']) ) {
				update_post_meta($post_id, '_order_id', $_POST['_order_id']);
			}
			if ( isset($_POST['_package_count']) ) {
				update_post_meta($post_id, '_package_count', $_POST['_package_count']);
			}
			if ( isset($_POST['_user_id']) ) {
				update_post_meta($post_id, '_user_id', $_POST['_user_id']);
			}
			$_feature_listings = isset($_POST['_feature_listings']) ? $_POST['_feature_listings'] : 'no';
			update_post_meta($post_id, '_feature_listings', $_feature_listings);

			if ( isset($_POST['_listings_duration']) ) {
				update_post_meta($post_id, '_listings_duration', $_POST['_listings_duration']);
			}
			if ( isset($_POST['_listings_limit']) ) {
				update_post_meta($post_id, '_listings_limit', $_POST['_listings_limit']);
			}
		}
	}

}

ApusWJMWCPaidListings_Post_Type_Packages::init();