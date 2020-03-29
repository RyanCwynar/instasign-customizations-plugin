<?php:

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Customizations
 *
 * @wordpress-plugin
 * Plugin Name:       Instasign Customizations
 * Plugin URI:        http://instasign.com/
 * Description:       Contains all the specific modifications required for the Instasign website.
 * Version:           1.0.0
 * Author:            Fueled on Bacon
 * Author URI:        http://fueledonbacon.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       customizations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CUSTOMIZATIONS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-customizations-activator.php
 */
function activate_customizations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customizations-activator.php';
	Customizations_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-customizations-deactivator.php
 */
function deactivate_customizations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customizations-deactivator.php';
	Customizations_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_customizations' );
register_deactivation_hook( __FILE__, 'deactivate_customizations' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-customizations.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_customizations() {

	$plugin = new Customizations();
	$plugin->run();

}
run_customizations();
