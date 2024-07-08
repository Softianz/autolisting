<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'Listdo_Elementor_Extensions' ) ) {
    final class Listdo_Elementor_Extensions {

        private static $_instance = null;

        
        public function __construct() {
            add_action( 'elementor/elements/categories_registered', array( $this, 'add_widget_categories' ) );
            add_action( 'init', array( $this, 'elementor_widgets' ),  100 );
            add_filter( 'listdo_generate_post_builder', array( $this, 'render_post_builder' ), 10, 2 );

            add_action( 'elementor/controls/controls_registered', array( $this, 'modify_controls' ), 10, 1 );
            add_action('elementor/editor/before_enqueue_styles', array( $this, 'style' ) );
        }

        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        public function add_widget_categories( $elements_manager ) {
            $elements_manager->add_category(
                'listdo-elements',
                [
                    'title' => esc_html__( 'Listdo Elements', 'listdo' ),
                    'icon' => 'fa fa-shopping-bag',
                ]
            );

            $elements_manager->add_category(
                'listdo-listings-elements',
                [
                    'title' => esc_html__( 'Listdo Listing Elements', 'listdo' ),
                ]
            );

            $elements_manager->add_category(
                'listdo-header-elements',
                [
                    'title' => esc_html__( 'Listdo Header Elements', 'listdo' ),
                ]
            );

        }

        public function elementor_widgets() {
            // general elements
            get_template_part( 'inc/vendors/elementor/widgets/heading' );
            get_template_part( 'inc/vendors/elementor/widgets/posts' );
            get_template_part( 'inc/vendors/elementor/widgets/call_to_action' );
            get_template_part( 'inc/vendors/elementor/widgets/features_box' );
            get_template_part( 'inc/vendors/elementor/widgets/icon_list' );
            get_template_part( 'inc/vendors/elementor/widgets/social_links' );
            get_template_part( 'inc/vendors/elementor/widgets/testimonials' );
            get_template_part( 'inc/vendors/elementor/widgets/brands' );
            get_template_part( 'inc/vendors/elementor/widgets/popup_video' );
            get_template_part( 'inc/vendors/elementor/widgets/banner' );
            get_template_part( 'inc/vendors/elementor/widgets/countdown' );
            get_template_part( 'inc/vendors/elementor/widgets/nav_menu' );
            get_template_part( 'inc/vendors/elementor/widgets/counter' );
            get_template_part( 'inc/vendors/elementor/widgets/team' );
            if ( listdo_is_revslider_activated() ) {
                get_template_part( 'inc/vendors/elementor/widgets/revslider' );
            }

            // header elements
            get_template_part( 'inc/vendors/elementor/header_widgets/logo' );
            get_template_part( 'inc/vendors/elementor/header_widgets/primary_menu' );
            get_template_part( 'inc/vendors/elementor/header_widgets/user_info' );

            if ( listdo_is_mailchimp_activated() ) {
                get_template_part( 'inc/vendors/elementor/widgets/mailchimp' );
            }

            if ( listdo_is_wp_job_manager_activated() ) {
                get_template_part( 'inc/vendors/elementor/listing_widgets/listings' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/listings_maps' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/search_form' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/category_banner' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/category_banner_list' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/city_banner' );
                
                get_template_part( 'inc/vendors/elementor/listing_widgets/edit_profile' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/my_bookmark' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/my_dashboard' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/my_follow' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/my_reviews' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/user_menu' );

                // header
                get_template_part( 'inc/vendors/elementor/listing_widgets/header_search_form' );
                get_template_part( 'inc/vendors/elementor/listing_widgets/add_listing_btn' );
            }

            if ( listdo_is_apus_wc_paid_listings_activated() ) {
                get_template_part( 'inc/vendors/elementor/wc_paid_widgets/packages' );
                get_template_part( 'inc/vendors/elementor/wc_paid_widgets/user_packages' );
            }

            if ( listdo_is_woocommerce_activated() ) {
                get_template_part( 'inc/vendors/elementor/woo_header_widgets/woo_cart' );
            }
        }

        public function style() {
            wp_enqueue_style('flaticon',  get_template_directory_uri() . '/css/flaticon.css');
            wp_enqueue_style('themify-icons',  get_template_directory_uri() . '/css/themify-icons.css');
            wp_enqueue_style('et-line',  get_template_directory_uri() . '/css/et-line.css');
        }

        public function modify_controls( $controls_registry ) {
            // Get existing icons
            $icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
            // Append new icons
            $new_icons = array_merge(
                array(
                    'flaticon-magnifying-glass' => 'flaticon-magnifying-glass',
                    'flaticon-plus-symbol' => 'flaticon-plus-symbol',
                    'flaticon-shopping-bag' => 'flaticon-shopping-bag',
                    'flaticon-user' => 'flaticon-user',
                    'flaticon-placeholder' => 'flaticon-placeholder',
                    'flaticon-placeholder-1' => 'flaticon-placeholder-1',
                    'flaticon-pin' => 'flaticon-pin',
                    'flaticon-hamburger' => 'flaticon-hamburger',
                    'flaticon-fork' => 'flaticon-fork',
                    'flaticon-chef' => 'flaticon-chef',
                    'flaticon-tray' => 'flaticon-tray',
                    'flaticon-beer' => 'flaticon-beer',
                    'flaticon-coffee-cup' => 'flaticon-coffee-cup',
                    'flaticon-shopping-cart' => 'flaticon-shopping-cart',
                    'flaticon-price-tag' => 'flaticon-price-tag',
                    'flaticon-hotel' => 'flaticon-hotel',
                    'flaticon-hotel-bell' => 'flaticon-hotel-bell',
                    'flaticon-bed' => 'flaticon-bed',
                    'flaticon-hotel-1' => 'flaticon-hotel-1',
                    'flaticon-wine-glass' => 'flaticon-wine-glass',
                    'flaticon-wine' => 'flaticon-wine',
                    'flaticon-musical-note' => 'flaticon-musical-note',
                    'flaticon-headphones' => 'flaticon-headphones',
                    'flaticon-compact-disc' => 'flaticon-compact-disc',
                    'flaticon-radio' => 'flaticon-radio',
                    'flaticon-multimedia' => 'flaticon-multimedia',
                    'flaticon-button' => 'flaticon-button',
                    'flaticon-video' => 'flaticon-video',
                    'flaticon-cinema' => 'flaticon-cinema',
                    'flaticon-guitar' => 'flaticon-guitar',
                    'ti-location-pin' => 'ti-location-pin',
                    'flaticon-call' => 'flaticon-call',
                    'flaticon-mail' => 'flaticon-mail',
                    'flaticon-unlink' => 'flaticon-unlink',
                    'flaticon-weightlifting' => 'flaticon-weightlifting',
                    'flaticon-museum' => 'flaticon-museum',
                    'flaticon-tent' => 'flaticon-tent',
                    'ti-email' => 'ti-email',
                    'ti-mobile' => 'ti-mobile',
                    'ti-world' => 'ti-world',
                    'icon-trophy' => 'icon-trophy',
                    'icon-layers' => 'icon-layers',
                    'icon-happy' => 'icon-happy',
                    'icon-dial' => 'icon-dial',
                    'ti-map-alt' => 'ti-map-alt',
                    'ti-user' => 'ti-user',
                ),
                $icons
            );
            // Then we set a new list of icons as the options of the icon control
            $controls_registry->get_control( 'icon' )->set_settings( 'options', $new_icons );
        }
        

        public function render_page_content($post_id) {
            if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new Elementor\Core\Files\CSS\Post( $post_id );
                $css_file->enqueue();
            }

            return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
        }

        public function render_post_builder($html, $post) {
            if ( !empty($post) && !empty($post->ID) ) {
                return $this->render_page_content($post->ID);
            }
            return $html;
        }
    }
}

if ( did_action( 'elementor/loaded' ) ) {
    // Finally initialize code
    Listdo_Elementor_Extensions::instance();
}