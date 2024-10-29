<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://atakanau.blogspot.com/2022/10/auto-gallery-image-sync-wp-plugin.html
 * @since      1.0.0
 *
 * @package    Auto_Gallery_And_Image_Sync
 * @subpackage Auto_Gallery_And_Image_Sync/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Auto_Gallery_And_Image_Sync
 * @subpackage Auto_Gallery_And_Image_Sync/includes
 * @author     Your Name <email@example.com>
 */
class Auto_Gallery_And_Image_Sync_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'auto-gallery-image-sync',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
