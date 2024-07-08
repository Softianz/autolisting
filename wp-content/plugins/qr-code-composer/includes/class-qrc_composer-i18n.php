<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://sharabindu.com
 * @since      2.0.5
 *
 * @package    Qrc_composer
 * @subpackage Qrc_composer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.5
 * @package    Qrc_composer
 * @subpackage Qrc_composer/includes
 * @author     Sharabindu Bakshi <sharabindu86@gmail.com>
 */
class Qrc_composer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.5
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain('qr-code-composer',false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
