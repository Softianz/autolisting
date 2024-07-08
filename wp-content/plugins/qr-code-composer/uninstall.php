<?php

/**
 * Fired when the plugin is uninstalled.
 *

 *
 * @link       https://sharabindu.com
 * @since      2.0.5
 *
 * @package    Qrc_composer
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


delete_option('qrc_composer_settings');

delete_site_option('qrc_composer_settings');