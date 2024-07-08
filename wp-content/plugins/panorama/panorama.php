<?php

/**
 * Plugin Name: Panorama
 * Description: A lite Weight Plugin that helps you, Easily display panoramic 360 degree images / videos into WordPress Website in Post, Page, Widget Area using shortCode. 
 * Plugin URI:  https://wordpress.org/plugins/
 * Version:    1.1.3
 * Author: bPlugins
 * Author URI: http://abuhayatpolash.com
 * License: GPLv3
 * Text Domain: panorama-viewer
 * Domain Path:  /languages
 */
// Security check
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'panorama_fs' ) ) {
    panorama_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'panorama_fs' ) ) {
        // ... Freemius integration snippet ...
        function panorama_fs()
        {
            global  $panorama_fs ;
            
            if ( !isset( $panorama_fs ) ) {
                if ( !defined( 'WP_FS__PRODUCT_8824_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_8824_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $panorama_fs = fs_dynamic_init( array(
                    'id'             => '8824',
                    'slug'           => 'panorama',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_a112d1d1d66d3b480dbea5690d3ff',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                    'menu'           => array(
                    'slug' => 'edit.php?post_type=bppiv-image-viewer',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $panorama_fs;
        }
        
        // Init Freemius.
        panorama_fs();
        // Signal that SDK was initiated.
        do_action( 'panorama_fs_loaded' );
    }
    
    // ... Your plugin's main file logic ...
    /* Plugin Initialization */
    define( 'BPPIV_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'BPPIV_VERSION', '1.1.3' );
    defined( 'BPPIV_PATH' ) or define( 'BPPIV_PATH', plugin_dir_path( __FILE__ ) );
    defined( 'BPPIV__FILE__' ) or define( 'BPPIV__FILE__', __FILE__ );
    add_action( 'plugin_loaded', 'bppiv_textdomain' );
    function bppiv_textdomain()
    {
        load_textdomain( 'panorama-viewer', BPPIV_PLUGIN_DIR . 'languages' );
    }
    
    /*-------------------------------------------------------------------------------*/
    /*   FRAMEWORK + OTHER INC
        /*-------------------------------------------------------------------------------*/
    require_once 'inc/csf/codestar-framework.php';
    require_once 'admin/ads/submenu.php';
    $init_file = BPPIV_PATH . 'inc/Init.php';
    if ( file_exists( $init_file ) ) {
        require_once $init_file;
    }
    if ( class_exists( 'BPPIV\\Init' ) ) {
        \BPPIV\Init::instance();
    }
    function bppiv_get_woo_template( $template )
    {
        $path = BPPIV_PATH . 'inc/Woocommerce/template/' . $template;
        if ( file_exists( $path ) ) {
            require $path;
        }
    }
    
    // After activation redirect
    function bppiv_plugin_activate()
    {
        add_option( 'bppiv_plugin_do_activation_redirect', true );
    }
    
    register_activation_hook( __FILE__, 'bppiv_plugin_activate' );
    function bppiv_plugin_redirect()
    {
        if ( get_option( 'bppiv_plugin_do_activation_redirect', false ) ) {
            delete_option( 'bppiv_plugin_do_activation_redirect' );
        }
    }
    
    add_action( 'admin_init', 'bppiv_plugin_redirect' );
    //free version code
    
    if ( panorama_fs()->is_free_plan() ) {
        require_once 'inc/metabox-options-free.php';
        require_once 'public/shortcode/shortcode.php';
    }

}

//End Of Freemius 335