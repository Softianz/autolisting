<?php

/**
 * Fired during plugin activation
 *
 * @link       https://sharabindu.com
 * @since      2.0.5
 *
 * @package    Qrc_composer
 * @subpackage Qrc_composer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.5
 * @package    Qrc_composer
 * @subpackage Qrc_composer/includes
 * @author     Sharabindu Bakshi <sharabindu86@gmail.com>
 */
class Qrc_composer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.5
	 */
	public static function activate() {

	flush_rewrite_rules();


		$tmp = get_option( 'qrc_composer_settings' );
		if(isset($_POST['action']) && current_user_can('manage_options')) {

		  update_option( 'qrc_composer_settings' , sanitize_text_field($_POST['qrc_composer_settings']));

		}



		
	}

}
