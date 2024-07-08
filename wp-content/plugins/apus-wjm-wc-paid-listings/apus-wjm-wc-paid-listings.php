<?php
/**
 * Plugin Name: Apus WP Job Manager - WooCommerce Paid Listings
 * Plugin URI: http://apusthemes.com/apus-wjm-wc-paid-listings/
 * Description: Add paid listing functionality via WooCommerce
 * Version: 1.2
 * Author: ApusTheme
 * Author URI: http://apusthemes.com
 * Requires at least: 3.8
 * Tested up to: 4.1
 *
 * Text Domain: apus-wjm-wc-paid-listings
 * Domain Path: /languages/
 *
 * @package apus-wjm-wc-paid-listings
 * @category Plugins
 * @author ApusTheme
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists("ApusWJMWCPaidListings") ) {
	
	final class ApusWJMWCPaidListings {

		private static $instance;

		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof ApusWJMWCPaidListings ) ) {
				self::$instance = new ApusWJMWCPaidListings;
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 *
		 */
		public function setup_constants() {
			
			// Plugin Folder Path
			if ( ! defined( 'APUSWJMWCPAIDLISTINGS_PLUGIN_DIR' ) ) {
				define( 'APUSWJMWCPAIDLISTINGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'APUSWJMWCPAIDLISTINGS_PLUGIN_URL' ) ) {
				define( 'APUSWJMWCPAIDLISTINGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'APUSWJMWCPAIDLISTINGS_PLUGIN_FILE' ) ) {
				define( 'APUSWJMWCPAIDLISTINGS_PLUGIN_FILE', __FILE__ );
			}

			// Prefix
			if ( ! defined( 'APUSWJMWCPAIDLISTINGS_PREFIX' ) ) {
				define( 'APUSWJMWCPAIDLISTINGS_PREFIX', 'apus_suchen_' );
			}
		}

		public function includes() {
			global $job_manager;

			require_once APUSWJMWCPAIDLISTINGS_PLUGIN_DIR . 'inc/mixes-functions.php';
			
			// post type
			require_once APUSWJMWCPAIDLISTINGS_PLUGIN_DIR . 'inc/post-types/class-post-type-job_package.php';
			
			// class
			require_once APUSWJMWCPAIDLISTINGS_PLUGIN_DIR . 'inc/class-product-type-package.php';
			require_once APUSWJMWCPAIDLISTINGS_PLUGIN_DIR . 'inc/class-submit-form.php';
			require_once APUSWJMWCPAIDLISTINGS_PLUGIN_DIR . 'inc/class-wc-cart.php';
			require_once APUSWJMWCPAIDLISTINGS_PLUGIN_DIR . 'inc/class-wc-order.php';

			// template loader
			require_once APUSWJMWCPAIDLISTINGS_PLUGIN_DIR . 'inc/class-template-loader.php';

			if ( is_object($job_manager) ) {
				register_post_status( 'pending_payment', array(
					'label'                     => _x( 'Pending Payment', 'job_listing', 'apus-wjm-wc-paid-listings' ),
					'public'                    => false,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => false,
					'show_in_admin_status_list' => true,
					'label_count'               => _n_noop( 'Pending Payment <span class="count">(%s)</span>', 'Pending Payment <span class="count">(%s)</span>', 'apus-wjm-wc-paid-listings' ),
				) );

				add_action( 'pending_payment_to_publish', array( $job_manager->post_types, 'set_expirey' ) );
			}
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'style' ) );
		}

		public static function style() {
			wp_enqueue_style('apus-wjm-wc-paid-listings-style',  APUSWJMWCPAIDLISTINGS_PLUGIN_URL . 'assets/style.css');
		}
		/**
		 *
		 */
		public function load_textdomain() {
			// Set filter for ApusWJMWCPaidListings's languages directory
			$lang_dir = dirname( plugin_basename( APUSWJMWCPAIDLISTINGS_PLUGIN_FILE ) ) . '/languages/';
			$lang_dir = apply_filters( 'apus_wjmtypes_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'apus-wjm-wc-paid-listings' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'apus-wjm-wc-paid-listings', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/apus-wjm-wc-paid-listings/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/apus-wjm-wc-paid-listings folder
				load_textdomain( 'apus-wjm-wc-paid-listings', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/apus-wjm-wc-paid-listings/languages/ folder
				load_textdomain( 'apus-wjm-wc-paid-listings', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'apus-wjm-wc-paid-listings', false, $lang_dir );
			}
		}
	}
}

function ApusWJMWCPaidListings() {
	return ApusWJMWCPaidListings::getInstance();
}

add_action( 'plugins_loaded', 'ApusWJMWCPaidListings' );