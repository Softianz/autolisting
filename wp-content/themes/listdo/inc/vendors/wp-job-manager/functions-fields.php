<?php
/**
 * apus listdo
 *
 * @package    listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// for custyom admin 'job_manager_input_' . $type

class Listdo_Type_Place{

	public static function init() {
		
		// required/available form
		add_filter( 'apuslistdo-custom-required-fields', array( __CLASS__, 'required_fields' ), 100 );
		add_filter( 'apuslistdo-custom-available-fields', array( __CLASS__, 'available_fields' ), 100 );
		
		add_filter( 'listdo_get_default_blocks_content', array( __CLASS__, 'blocks_content' ), 10 );
		add_filter( 'listdo_get_default_blocks_sidebar_content', array( __CLASS__, 'blocks_sidebar_content' ), 10 );

		// custom field display
		add_filter( 'apuslistdo_display_hooks', array( __CLASS__, 'custom_fields_display_hooks'), 1 );

	}

	public static function required_fields($dfields) {
		$fields['job_title'] = array(
			'label' => esc_html__('Title', 'listdo'),
			'type' => 'text',
			'required' => true,
			'placeholder' => esc_html__('Title', 'listdo'),
			'disable_check' => true,
			'show_in_submit_form'    => true,
			'show_in_admin_edit'    => true,
		);
		$fields['job_description'] = array(
			'label'       => esc_html__( 'Description', 'listdo' ),
			'type'        => 'wp-editor',
			'required'    => true,
			'show_in_submit_form'    => true,
			'show_in_admin_edit'    => true,
			'disable_check' => true
		);
		return $fields;
	}

	public static function available_fields($dfields) {
		global $wp_taxonomies;

		// general
		$fields['job_tagline'] = array(
			'label' => esc_html__('Tagline', 'listdo'),
			'type' => 'text',
			'required' => false,
			'placeholder' => esc_html__('tagline', 'listdo'),
			'show_in_submit_form'    => true,
			'show_in_admin_edit'    => true,
		);
		$fields['job_location'] = array(
			'label'       => esc_html__( 'Location', 'listdo' ),
			'type' => 'listdo-location',
			'priority' => 2.3,
			'placeholder' => esc_html__( 'e.g 34 Wigmore Street, London', 'listdo' ),
			'description' => esc_html__( 'Leave this blank if the location is not important.', 'listdo' ),
		);

		// taxonomies
		$fields['job_regions'] = array(
			'type'        => 'listdo-regions',
			'default' => '',
			'taxonomy' => 'job_listing_region',
			'placeholder' => esc_html__( 'Add Region', 'listdo' ),
			'label' => esc_html__( 'Listing Region', 'listdo' ),
		);
		$fields['job_category'] = array(
			'type'        => 'job_category',
			'select_type' => 'term-select',
			'default' => '',
			'taxonomy' => 'job_listing_category',
			'placeholder' => esc_html__( 'Add Category', 'listdo' ),
			'label' => esc_html__( 'Listing Category', 'listdo' ),
		);
		
		if ( get_option( 'job_manager_enable_types' ) ) {
			$fields['job_type'] = array(
				'type'        => 'job_type',
				'select_type' => 'term-select',
				'default' => '',
				'taxonomy' => 'job_listing_type',
				'placeholder' => esc_html__( 'Add Type', 'listdo' ),
				'label' => esc_html__( 'Listing Type', 'listdo' ),
			);
		}

		$fields['job_amenities'] = array(
			'type'        => 'job_amenities',
			'select_type' => 'term-select',
			'default' => '',
			'taxonomy' => 'job_listing_amenity',
			'placeholder' => esc_html__( 'Add Amenity', 'listdo' ),
			'label' => esc_html__( 'Listing Amenity', 'listdo' ),
		);
		
		if ( isset( $wp_taxonomies['job_listing_tag'] ) ) {
			$fields['job_tags'] = array(
				'label'       => esc_html__( 'Listing tags', 'listdo' ),
				'description' => esc_html__( 'Comma separate tags, such as required skills or technologies, for this listing.', 'listdo' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'e.g. PHP, Social Media, Management', 'listdo' ),
			);
		}
		
		// price
		$fields['job_price_range'] = array(
			'label' => esc_html__('Price Range', 'listdo'),
			'type' => 'select',
			'required' => false,
			'options' => apply_filters( 'apus_listdo_price_ranges', array() ),
			'description' => '',
		);

		$fields['job_price_from'] = array(
			'label' => esc_html__('Price From', 'listdo'),
			'type' => 'text',
			'required' => false,
			'placeholder' => esc_html__( 'e.g: 100', 'listdo' ),
		);
		$fields['job_price_to'] = array(
			'label' => esc_html__('Price To', 'listdo'),
			'type' => 'text',
			'required' => false,
			'placeholder' => esc_html__( 'e.g: 200', 'listdo' ),
		);

		// hours
		$fields['job_hours'] = array(
			'label'       => esc_html__( 'Hours of Operation', 'listdo' ),
			'type'        => 'listdo-hours',
			'required'    => false,
			'placeholder' => '',
			'default'     => '',
			'sanitize_callback' => 'listdo_sanitize_array_callback'
		);

		// contact
		$fields['job_phone'] = array(
			'label'       => esc_html__( 'Phone', 'listdo' ),
			'type'        => 'text',
			'placeholder' => esc_html__( 'e.g +84-669-996', 'listdo' ),
			'required'    => false,
		);
		$fields['job_email'] = array(
			'label'       => esc_html__( 'Email', 'listdo' ),
			'type'        => 'text',
			'placeholder' => esc_html__( 'e.g youremail@email.com', 'listdo' ),
			'required'    => false,
		);
		$fields['job_website'] = array(
			'label'       => esc_html__( 'Website', 'listdo' ),
			'type'        => 'text',
			'placeholder' => esc_html__( 'e.g yourwebsite.com', 'listdo' ),
			'required'    => false,
		);

		// Menu price
		$fields['job_menu_prices'] = array(
			'label'       => esc_html__( 'Menu Prices', 'listdo' ),
			'type' => 'listdo-menu-prices',
			'priority' => 3.9,
			'placeholder' => '',
			'description' => '',
			'sanitize_callback' => 'listdo_sanitize_array_callback'
		);

		// media
		$fields['job_logo'] = array(
			'label'       => esc_html__( 'Logo', 'listdo' ),
			'type'        => 'file',
			'description' => esc_html__( 'The image will be shown on listing cards.', 'listdo' ),
			'required'    => false,
			'multiple_files'    => false,
			'ajax' 		  => true,
			'allow_types' => array(
				'jpg|jpeg|jpe',
				'jpeg',
				'gif',
				'png',
			),
		);
		$fields['job_cover_image'] = array(
			'label'       => esc_html__( 'Cover Image', 'listdo' ),
			'type'        => 'file',
			'description' => esc_html__( 'The image will be shown on listing cards.', 'listdo' ),
			'required'    => false,
			'multiple_files'    => false,
			'ajax' 		  => true,
			'allow_types' => array(
				'jpg|jpeg|jpe',
				'jpeg',
				'gif',
				'png',
			),
		);
		$fields['job_gallery_images'] = array(
			'label' => esc_html__( 'Gallery Images', 'listdo' ),
			'priority' => 2.9,
			'required' => false,
			'type' => 'file',
			'ajax' => true,
			'placeholder' => '',
			'allow_types' => array(
				'jpg|jpeg|jpe',
				'jpeg',
				'gif',
				'png',
			),
			'multiple_files' => true,
		);
		$fields['job_video'] = array(
			'label'       => esc_html__( 'Video', 'listdo' ),
			'type'        => 'text',
			'required'    => false,
			'placeholder' => esc_html__( 'A link to a video about your company', 'listdo' ),
		);

		// socials
		$fields['job_socials'] = array(
			'label'       => esc_html__( 'Socials Link', 'listdo' ),
			'type'        => 'repeater',
			'required'    => false,
			'fields' => array(
				'network' => array(
					'label'       => esc_html__( 'Network', 'listdo' ),
					'name'        => 'job_socials_network[]',
					'type'        => 'select',
					'description' => '',
					'placeholder' => '',
					'options' => array(
						'' => esc_html__('Select Network', 'listdo'),
						'fab fa-facebook-f' => esc_html__('Facebook', 'listdo'),
						'fab fa-twitter' => esc_html__('Twitter', 'listdo'),
						'fab fa-google-plus-g' => esc_html__('Google+', 'listdo'),
						'fab fa-instagram' => esc_html__('Instagram', 'listdo'),
						'fab fa-youtube' => esc_html__('Youtube', 'listdo'),
						'fab fa-snapchat' => esc_html__('Snapchat', 'listdo'),
						'fab fa-linkedin-in' => esc_html__('LinkedIn', 'listdo'),
						'fab fa-reddit' => esc_html__('Reddit', 'listdo'),
						'fab fa-tumblr' => esc_html__('Tumblr', 'listdo'),
						'fab fa-pinterest' => esc_html__('Pinterest', 'listdo'),
					)
				),
				'network_url' => array(
					'label'       => esc_html__( 'Network Url', 'listdo' ),
					'name'        => 'job_socials_network_url[]',
					'type'        => 'text',
					'description' => '',
					'placeholder' => '',
				),
			),
			'sanitize_callback' => 'listdo_sanitize_array_callback'
		);

		// Products
		$fields['job_products'] = array(
			'label'       => esc_html__( 'Woocommerce Products', 'listdo' ),
			'type'        => 'multiselect',
			'required'    => false
		);

		return $fields;
	}

	public static function blocks_content() {
	    return apply_filters( 'listdo_listing_single_content', array(
	        'description' => esc_html__( 'Description', 'listdo' ),
	        'maps' => esc_html__( 'Maps', 'listdo' ),
	        'amenities' => esc_html__( 'Amenities', 'listdo' ),
	        'photos' => esc_html__( 'Photos', 'listdo' ),
	        'menu-prices' => esc_html__( 'Menu Prices', 'listdo' ),
	        'video' => esc_html__( 'Video', 'listdo' ),
	        'hours' => esc_html__( 'Hours', 'listdo' ),
	        'products' => esc_html__( 'Products', 'listdo' ),
	        'review-avg' => esc_html__( 'Review Average', 'listdo' ),
	        'comments' => esc_html__( 'Reviews', 'listdo' ),
	    ));
	}

	public static function blocks_sidebar_content() {
	    return apply_filters( 'listdo_listing_single_sidebar', array(
	        'amenities' => esc_html__( 'Amenities', 'listdo' ),
	        'business-info' => esc_html__( 'Business Info', 'listdo' ),
	        'contact-form' => esc_html__( 'Contact Business', 'listdo' ),
	        'hours' => esc_html__( 'Hours', 'listdo' ),
	        'nearby' => esc_html__( 'Nearby Places', 'listdo' ),
	        'nearby_browse' => esc_html__( 'Browse Nearby Places', 'listdo' ),
	        'price_range' => esc_html__( 'Price Range', 'listdo' ),
	        'review-avg' => esc_html__( 'Review Average', 'listdo' ),
	        'statistic' => esc_html__( 'Statistic', 'listdo' ),
	        'tags' => esc_html__( 'Tags', 'listdo' ),
	        'claim' => esc_html__( 'Claim Listing', 'listdo' ),
	        'products-sidebar' => esc_html__( 'Products', 'listdo' ),
	    ));
	}

	public static function custom_fields_display_hooks($hooks) {
		$hooks[''] = esc_html__('Choose a position', 'listdo');
		$hooks['listdo-single-listing-description'] = esc_html__('Single Listing - Description', 'listdo');
		$hooks['listdo-single-listing-contact'] = esc_html__('Single Listing - Business Information', 'listdo');
		$hooks['listdo-single-listing-amenities'] = esc_html__('Single Listing - Amenities Box', 'listdo');
		$hooks['listdo-single-listing-contact-form'] = esc_html__('Single Listing - Contact Form', 'listdo');
		$hooks['listdo-single-listing-hours'] = esc_html__('Single Listing - Hours', 'listdo');
		$hooks['listdo-single-listing-maps'] = esc_html__('Single Listing - Maps', 'listdo');
		$hooks['listdo-single-listing-menu-prices'] = esc_html__('Single Listing - Menu Prices', 'listdo');
		$hooks['listdo-single-listing-nearby'] = esc_html__('Single Listing - Nearby', 'listdo');
		$hooks['listdo-single-listing-nearby-browse'] = esc_html__('Single Listing - Browse Nearby', 'listdo');
		$hooks['listdo-single-listing-price-range'] = esc_html__('Single Listing - Price Range', 'listdo');
		$hooks['listdo-single-listing-review-avg'] = esc_html__('Single Listing - Review avg', 'listdo');
		$hooks['listdo-single-listing-statistic'] = esc_html__('Single Listing - Statistic', 'listdo');
		return $hooks;
	}
	
}

Listdo_Type_Place::init();