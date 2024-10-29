<?php

/**
 * @link              https://atakanau.blogspot.com/2022/10/auto-gallery-image-sync-wp-plugin.html
 * @since             1.0.0
 * @package           Auto_Gallery_And_Image_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       Automatic Gallery And Featured Image Sync
 * Plugin URI:        https://atakanau.blogspot.com/2022/10/auto-gallery-image-sync-wp-plugin.html
 * Description:       Automatically sync posts (or WooCommerce Product) and media images as featured image and gallery.
 * Version:           1.0.1
 * Author:            Atakan Au
 * Author URI:        https://atakanau.blogspot.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       auto-gallery-image-sync
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'ATAKANAU_AGISYNC_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-auto-gallery-image-sync-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-gallery-image-sync-activator.php';
	Auto_Gallery_And_Image_Sync_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-auto-gallery-image-sync-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-gallery-image-sync-deactivator.php';
	Auto_Gallery_And_Image_Sync_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-auto-gallery-image-sync.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Auto_Gallery_And_Image_Sync();
	$plugin->run();

}
run_plugin_name();
