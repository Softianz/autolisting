<?php
namespace BPPIV\Base;

class EnqueueAssets{

    public function register(){
        add_action('wp_enqueue_scripts', [$this, 'registerFrontEndAssets']);
        add_action('admin_enqueue_scripts', [$this, 'registerBackendAssets']);
    }

    public function registerFrontEndAssets(){
        wp_register_script( 'bppiv-three', BPPIV_PLUGIN_DIR . 'public/assets/js/three.min.js',[], BPPIV_VERSION, true);
        wp_register_script( 'bppiv-panolens', BPPIV_PLUGIN_DIR . 'public/assets/js/panolens.min.js', array( 'bppiv-three' ), BPPIV_VERSION, true );
        wp_register_script( 'videojs', BPPIV_PLUGIN_DIR . 'public/assets/js/library/video.min.js', [], BPPIV_VERSION, true );
        wp_register_script( 'videojs-pannellum-plugin', BPPIV_PLUGIN_DIR . 'public/assets/js/library/videojs-pannellum-plugin.js', [], BPPIV_VERSION, true );

        //Pannellum
        wp_register_script( 'bppiv-pannellum-js', BPPIV_PLUGIN_DIR . 'public/assets/js/library/pannellum.js', [], BPPIV_VERSION, true);
        wp_register_script( 'bppiv-init', BPPIV_PLUGIN_DIR . 'public/assets/js/scripts.js', array( 'jquery', 'bppiv-three', 'bppiv-panolens' ), BPPIV_VERSION, true );

        wp_register_script( 'bppiv-pro', BPPIV_PLUGIN_DIR . 'public/assets/js/scripts-pro.js', array( 'jquery', 'bppiv-three', 'bppiv-panolens', 'bppiv-pannellum-js' ), BPPIV_VERSION, true );

        wp_register_script( 'bppiv-product', BPPIV_PLUGIN_DIR . 'public/assets/js/product.js', array(), BPPIV_VERSION, true );
        wp_register_style( 'bppiv-product', BPPIV_PLUGIN_DIR . 'public/assets/css/product.css', array(), BPPIV_VERSION, 'all' );
        
        //Pannellum CSS
        wp_register_style( 'bppiv-pannellum-css', BPPIV_PLUGIN_DIR . 'public/assets/css/library/pannellum.min.css', [], '2.5.6' );
        wp_register_style( 'videojs', BPPIV_PLUGIN_DIR . 'public/assets/css/library/video-js.min.css', [], '2.5.6' );
        // style
        wp_register_style( 'bppiv-font-material', '//fonts.googleapis.com/icon?family=Material+Icons' );
        wp_register_style( 'bppiv-main-style', BPPIV_PLUGIN_DIR . 'public/assets/css/style.css', [], BPPIV_VERSION );

    }
    
    public function registerBackendAssets(){
        wp_register_style( 'bppiv-custom-style', BPPIV_PLUGIN_DIR . 'public/assets/css/admin-style.css',[], BPPIV_VERSION );
        wp_enqueue_style( 'bppiv-custom-style' );
        // Script
        wp_register_script( 'bppiv-admin-script', BPPIV_PLUGIN_DIR . 'public/assets/js/admin-scripts.js', ['jquery'], BPPIV_VERSION, true );
        wp_enqueue_script( 'bppiv-admin-script' );
        
        wp_register_style( 'bppiv-readonly', BPPIV_PLUGIN_DIR . 'public/assets/css/readonly.css', [], BPPIV_VERSION );
    }
}