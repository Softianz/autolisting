<?php
/**
 * product type: package
 *
 * @package    apus-wjm-wc-paid-listings
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  25/10/2018 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


function apus_wjm_wc_paid_listings_register_package_product_type() {
	if ( class_exists('WC_Product_Simple') ) {
		class ApusWJMWCPaidListings_Product_Type_Package extends WC_Product_Simple {
			
			public function __construct( $product ) {
				$this->product_type = 'listing_package';
				parent::__construct( $product );
			}

			public function get_type() {
		        return 'listing_package';
		    }

		    public function is_sold_individually() {
				return apply_filters( 'apus_wjm_wc_paid_listings_' . $this->product_type . '_is_sold_individually', true );
			}

			public function is_purchasable() {
				return true;
			}

			public function is_virtual() {
				return true;
			}
		}
	}
}

add_action( 'init', 'apus_wjm_wc_paid_listings_register_package_product_type' );

function apus_wjm_wc_paid_listings_add_listing_package_product( $types ){

	$types[ 'listing_package' ] = __( 'Listing Package' );

	return $types;

}
add_filter( 'product_type_selector', 'apus_wjm_wc_paid_listings_add_listing_package_product' );

function apus_wjm_wc_paid_listings_woocommerce_product_class( $classname, $product_type ) {

    if ( $product_type == 'listing_package' ) { // notice the checking here.
        $classname = 'ApusWJMWCPaidListings_Product_Type_Package';
    }

    return $classname;
}

add_filter( 'woocommerce_product_class', 'apus_wjm_wc_paid_listings_woocommerce_product_class', 10, 2 );



/**
 * Show pricing fields for package product.
 */
function apus_wjm_wc_paid_listings_package_custom_js() {

	if ( 'product' != get_post_type() ) {
		return;
	}

	?><script type='text/javascript'>
		jQuery( document ).ready( function() {
			jQuery('.product_data_tabs .general_tab').show();
        	jQuery('#general_product_data .pricing').addClass('show_if_listing_package').show();

			jQuery('.inventory_options').addClass('show_if_listing_package').show();
			jQuery('.inventory_options').addClass('show_if_listing_package').show();
            jQuery('#inventory_product_data ._manage_stock_field').addClass('show_if_listing_package').show();
            jQuery('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_listing_package').show();
            jQuery('#inventory_product_data ._sold_individually_field').addClass('show_if_listing_package').show();
		});
	</script><?php
}
add_action( 'admin_footer', 'apus_wjm_wc_paid_listings_package_custom_js' );

function apus_wjm_wc_paid_listings_custom_product_tabs( $tabs) {
	$tabs['listing_package_option'] = array(
		'label'		=> __( 'Package Options', 'apus-wjm-wc-paid-listings' ),
		'target'	=> 'package_options',
		'class'		=> array( 'show_if_listing_package' ),
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'apus_wjm_wc_paid_listings_custom_product_tabs' );
/**
 * Contents of the package options product tab.
 */
function apus_wjm_wc_paid_listings_package_options_product_tab_content() {
	global $post;
	?>
	<div id='package_options' class='panel woocommerce_options_panel'>
		<div class='options_group'>
			<?php
				woocommerce_wp_checkbox( array(
					'id' 		=> '_feature_listings',
					'label' 	=> __( 'Feature Listings?', 'apus-wjm-wc-paid-listings' ),
					'description'	=> __( 'Feature this listing - it will be styled differently and sticky.', 'apus-wjm-wc-paid-listings' ),
				) );
				woocommerce_wp_text_input( array(
					'id'			=> '_listings_limit',
					'label'			=> __( 'Listings Limit', 'apus-wjm-wc-paid-listings' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The number of listings a user can post with this package', 'apus-wjm-wc-paid-listings' ),
					'type' 			=> 'number',
				) );
				woocommerce_wp_text_input( array(
					'id'			=> '_listings_duration',
					'label'			=> __( 'Listings Duration (Days)', 'apus-wjm-wc-paid-listings' ),
					'desc_tip'		=> 'true',
					'description'	=> __( 'The number of days that the listings will be active', 'apus-wjm-wc-paid-listings' ),
					'type' 			=> 'number',
				) );

				do_action('apus_wjm_wc_paid_listings_package_options_product_tab_content');
			?>
		</div>
	</div><?php
}
add_action( 'woocommerce_product_data_panels', 'apus_wjm_wc_paid_listings_package_options_product_tab_content' );
/**
 * Save the custom fields.
 */
function apus_wjm_wc_paid_listings_save_package_option_field( $post_id ) {
	
	$feature_listings = isset( $_POST['_feature_listings'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_feature_listings', $feature_listings );
	
	if ( isset( $_POST['_listings_limit'] ) ) {
		update_post_meta( $post_id, '_listings_limit', sanitize_text_field( $_POST['_listings_limit'] ) );
	}

	if ( isset( $_POST['_listings_duration'] ) ) {
		update_post_meta( $post_id, '_listings_duration', sanitize_text_field( $_POST['_listings_duration'] ) );
	}
}
add_action( 'woocommerce_process_product_meta_listing_package', 'apus_wjm_wc_paid_listings_save_package_option_field'  );