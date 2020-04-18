<?php

/**
 *
 * @link              http://www.blobinator.com
 * @since             1.0.0
 * @package           Blobinator
 *
 * @wordpress-plugin
 * Plugin Name:       Cognitive Content Analyzer
 * Plugin URI:        http://www.blobinator.com
 * Description:       Plugin for discovering insights from your content. Helpful for editing and tagging your posts and pages. Powered by IBM Watson Customer Engagement.
 * Version:           2.0
 * Author:            Michael Bordash
 * Author URI:        https://github.com/mbordash
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
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
