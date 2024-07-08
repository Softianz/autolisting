<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://sharabindu.com
 * @since      2.0.5
 *
 * @package    Qrc_composer
 * @subpackage Qrc_composer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.5
 * @package    Qrc_composer
 * @subpackage Qrc_composer/includes
 * @author     Sharabindu Bakshi <sharabindu86@gmail.com>
 */
class Qrc_composer_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.5
	 */
	public static function deactivate() {

		flush_rewrite_rules();
	}

}
