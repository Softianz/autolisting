<?php
/**
 * claim
 *
 * @package    apus-listdo
 * @author     Apusthemes <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2015-2016 Apus Framework
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class ApusListdo_Post_Type_Claim {

  	public static function init() {
    	add_action( 'init', array( __CLASS__, 'register_post_type' ) );

    	add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
    	add_filter( 'manage_edit-job_claim_columns', array( __CLASS__, 'custom_columns' ) );
		add_action( 'manage_job_claim_posts_custom_column', array( __CLASS__, 'custom_columns_manage' ) );

  	}

  	public static function register_post_type() {
	    $labels = array(
			'name'                  => esc_html__( 'Claim', 'apus-listdo' ),
			'singular_name'         => esc_html__( 'Claim', 'apus-listdo' ),
			'add_new'               => esc_html__( 'Add New Claim', 'apus-listdo' ),
			'add_new_item'          => esc_html__( 'Add New Claim', 'apus-listdo' ),
			'edit_item'             => esc_html__( 'Edit Claim', 'apus-listdo' ),
			'new_item'              => esc_html__( 'New Claim', 'apus-listdo' ),
			'all_items'             => esc_html__( 'Claims', 'apus-listdo' ),
			'view_item'             => esc_html__( 'View Claim', 'apus-listdo' ),
			'search_items'          => esc_html__( 'Search Claim', 'apus-listdo' ),
			'not_found'             => esc_html__( 'No Claims found', 'apus-listdo' ),
			'not_found_in_trash'    => esc_html__( 'No Claims found in Trash', 'apus-listdo' ),
			'parent_item_colon'     => '',
			'menu_name'             => esc_html__( 'Claims', 'apus-listdo' ),
	    );

	    register_post_type( 'job_claim',
	      	array(
		        'labels'            => apply_filters( 'apus_listdo_postype_fields_labels' , $labels ),
		        'supports'          => array( 'title' ),
		        'public'            => true,
		        'has_archive'       => false,
		        'publicly_queryable' => false,
		        'menu_position'     => 52,
		        'show_in_menu'		=> 'edit.php?post_type=job_listing',
	      	)
	    );

  	}
  	
  	public static function metaboxes(array $metaboxes){
		$prefix = 'listdo_claim_';
	    
	    $metaboxes[ $prefix . 'settings' ] = array(
			'id'                        => $prefix . 'settings',
			'title'                     => esc_html__( 'Claim Information', 'apus-listdo' ),
			'object_types'              => array( 'job_claim' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_fields()
		);

	    return $metaboxes;
	}

	public static function metaboxes_fields() {
		
		$prefix = 'listdo_claim_';
		$listings = array();
		if ( is_admin() ) {
			$args = array(
				'post_type' => 'job_listing',
				'posts_per_page' => -1,
				'status' => 'public'
			);
			$loop = new WP_Query($args);

			if ( $loop->have_posts() ) {
				foreach ($loop->posts as $listing) {
					$listings[$listing->ID] = $listing->post_title;
				}
			}
		}
		$fields =  array(
			array(
				'name' => esc_html__( 'Claim For', 'apus-listdo' ),
				'id'   => $prefix."claim_for",
				'type' => 'select',
				'options' => $listings
			),
			array(
				'name' => esc_html__( 'Status', 'apus-listdo' ),
				'id'   => $prefix."status",
				'type' => 'select',
				'options' => array(
					'pending' => esc_html__( 'Pending', 'apus-listdo' ),
					'approved' => esc_html__( 'Approved', 'apus-listdo' ),
					'decline' => esc_html__( 'Decline', 'apus-listdo' ),
				)
			),
			array(
				'name' => esc_html__( 'Claim Detail', 'apus-listdo' ),
				'id'   => $prefix."detail",
				'type' => 'textarea',
			),
			
		);  
		
		return apply_filters( 'apus_listdo_postype_fields_metaboxes_fields' , $fields );
	}

	public static function custom_columns($fields) {
		$fields = array(
			'cb' 				=> '<input type="checkbox" />',
			'title' 			=> esc_html__( 'Title', 'apus-listdo' ),
			'author' 			=> esc_html__( 'Author', 'apus-listdo' ),
			'status' 		=> esc_html__( 'Status', 'apus-listdo' ),
			'date' 		=> esc_html__( 'Date', 'apus-listdo' ),
		);
		
		return $fields;
	}

	public static function custom_columns_manage( $column ) {
		global $post;
		switch ( $column ) {
			case 'status':
				$status = get_post_meta( get_the_ID(), 'listdo_claim_status', true );
				$statuses = array(
					'pending' => esc_html__( 'pending', 'apus-listdo' ),
					'approved' => esc_html__( 'Approved', 'apus-listdo' ),
					'decline' => esc_html__( 'Decline', 'apus-listdo' ),
				);
				echo isset($statuses[$status]) ? $statuses[$status] : '';
				break;
		}
	}
}

ApusListdo_Post_Type_Claim::init();