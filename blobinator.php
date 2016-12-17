<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.blobinator.com
 * @since             1.0.0
 * @package           Blobinator
 *
 * @wordpress-plugin
 * Plugin Name:       Blobinator
 * Plugin URI:        http://www.blobinator.com
 * Description:       Wordpress Plugin for appending insights to blobs of text or images. Helpful for editing your content and articles.
 * Version:           1.0.0
 * Author:            Michael Bordash
 * Author URI:        MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       blobinator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blobinator-activator.php
 */
function activate_blobinator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blobinator-activator.php';
	Blobinator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blobinator-deactivator.php
 */

function deactivate_blobinator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blobinator-deactivator.php';
	Blobinator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_blobinator' );
register_deactivation_hook( __FILE__, 'deactivate_blobinator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blobinator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_blobinator() {

	$plugin = new Blobinator();
	$plugin->run();

}
run_blobinator();
