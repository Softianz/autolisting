<?php
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class Listdo_Products {

	public static function init() {
        // Add products field to submit form
        add_filter( 'apuslistdo-types-render_field', array( __CLASS__, 'form_fields' ), 10, 5 );
	}

    public static function form_fields($field, $field_data, $fieldkey, $fieldtype, $priority) {
        global $current_user;
        if ( $fieldkey !== 'job_products' ) {
            return $field;
        }
        $options        = array();
        $args   = array(
            'post_type'         => 'product',
            'posts_per_page'    => '-1',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'slug',
                    'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
                    'operator' => 'NOT IN',
                ),
                array(
                    'taxonomy' => 'product_type',
                    'field' => 'slug',
                    'terms' => array( 'job_package', 'job_package_subscription', 'resume_package', 'resume_package_subscription', 'listing_package' ),
                    'operator' => 'NOT IN'
                ),
            ),
            'meta_query' => array(
                'relation' => 'OR',
                array(
                   'key' => '_ywsbs_subscription',
                   'value' => 'yes',
                   'compare' => '!=',
                ),
                array( //if no date has been added show these posts too
                    'key' => '_ywsbs_subscription',
                    'value' => 'yes',
                    'compare' => 'NOT EXISTS'
                )
            )
        );
        $product_type = isset($field_data['product_type']) ? $field_data['product_type'] : 'own';
        if ( 'own' == $product_type && !array_key_exists( 'administrator', $current_user->caps ) ) {
            // Don't show this field when user is not logged in
            if ( ! is_user_logged_in() ) {
                $field['options'] = array();
                return $field;
            }
            $args['author'] = get_current_user_id();
        }

        $products = get_posts( apply_filters( 'listdo_products_field_form_args', $args ) );

        foreach ( $products as $p ) {
            $options[ $p->ID ] = $p->post_title;
        }

        if ( class_exists( 'WC_Product_Vendors_Utils' ) ) {
            $vproducts = WC_Product_Vendors_Utils::get_vendor_product_ids();

            foreach ( $vproducts as $p ) {
                $options[ $p ] = get_the_title( $p );
            }
        }

        if ( empty( $options ) ) {
            $field['options'] = array();
            return $field;
        }

        $field['options'] = $options;

        return $field;
    }


}

Listdo_Products::init();