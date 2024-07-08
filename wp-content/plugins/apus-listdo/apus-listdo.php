<?php
/**
 * Plugin Name: Apus Listdo
 * Plugin URI: http://apusthemes.com/apus-listdo/
 * Description: Apus Listdo is a plugin for Listdo directory listing theme
 * Version: 1.0
 * Author: ApusTheme
 * Author URI: http://apusthemes.com
 * Requires at least: 3.8
 * Tested up to: 5.2
 *
 * Text Domain: apus-listdo
 * Domain Path: /languages/
 *
 * @package apus-listdo
 * @category Plugins
 * @author ApusTheme
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists("ApusListdo") ) {
	
	final class ApusListdo {

		private static $instance;

		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof ApusListdo ) ) {
				self::$instance = new ApusListdo;
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 *
		 */
		public function setup_constants(){
			// Plugin Folder Path
			if ( ! defined( 'APUSLISTDO_PLUGIN_DIR' ) ) {
				define( 'APUSLISTDO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'APUSLISTDO_PLUGIN_URL' ) ) {
				define( 'APUSLISTDO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'APUSLISTDO_PLUGIN_FILE' ) ) {
				define( 'APUSLISTDO_PLUGIN_FILE', __FILE__ );
			}

			// Prefix
			if ( ! defined( 'APUSLISTDO_PREFIX' ) ) {
				define( 'APUSLISTDO_PREFIX', 'apus_listdo_' );
			}
		}

		public function includes() {
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/mixes-functions.php';
			
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/class-template-loader.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/class-claim.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/class-fields-manager.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/class-custom-fields-html.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/class-custom-fields.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/class-taxonomies.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/class-custom-fields-display.php';
			

			require_once APUSLISTDO_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-job-manager-amenities.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-job-manager-categories.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-job-manager-regions.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-job-manager-tags.php';
			require_once APUSLISTDO_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-job-manager-types.php';

			require_once APUSLISTDO_PLUGIN_DIR . 'inc/post-types/class-post-type-job_claim.php';

			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		}
		public function scripts() {
			wp_register_script( 'apuslistdo-scripts', APUSLISTDO_PLUGIN_URL . 'assets/scripts.js', array( 'jquery' ), '', true );

			wp_localize_script( 'apuslistdo-scripts', 'apuslistdo_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			));
			wp_enqueue_script( 'apuslistdo-scripts' );
		}
		/**
		 *
		 */
		public function load_textdomain() {
			// Set filter for ApusListdo's languages directory
			$lang_dir = dirname( plugin_basename( APUSLISTDO_PLUGIN_FILE ) ) . '/languages/';
			$lang_dir = apply_filters( 'apuslistdo_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'apus-listdo' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'apus-listdo', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/apus-listdo/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/apus-listdo folder
				load_textdomain( 'apus-listdo', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/apus-listdo/languages/ folder
				load_textdomain( 'apus-listdo', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'apus-listdo', false, $lang_dir );
			}
		}
	}
}

function ApusListdo() {
	return ApusListdo::getInstance();
}

ApusListdo();
