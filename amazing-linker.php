<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://coderockz.com
 * @since             1.0.0
 * @package           Amazing_Linker
 *
 * @wordpress-plugin
 * Plugin Name:       Amazing Linker
 * Description:       Amazing Linker is a WordPress plugin that gives you all the facilities that you need to place your affiliate link in your content in a different format. It has a lot of customization option that you will surprise how easy to put an Amazon affiliate link. It's only a matter of shortcode, yes only a shortcode.
 * Version:           1.0.8
 * Author:            CodeRockz
  * Author URI:        https://coderockz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       amazing-linker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if(!defined("AMAZING_LINKER_PLUGIN_DIR"))
    define("AMAZING_LINKER_PLUGIN_DIR",plugin_dir_path(__FILE__));
if(!defined("AMAZING_LINKER_PLUGIN_URL"))
    define("AMAZING_LINKER_PLUGIN_URL",plugin_dir_url(__FILE__));
if(!defined("AMAZING_LINKER_PLUGIN"))
    define("AMAZING_LINKER_PLUGIN",plugin_basename(__FILE__));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AMAZING_LINKER_VERSION', '1.0.8' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-amazing-linker-activator.php
 */
function activate_amazing_linker() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-amazing-linker-product-table.php';
    $table = new Amazing_Linker_Product_Table();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-amazing-linker-activator.php';
	$activator = new Amazing_Linker_Activator($table);
    $activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-amazing-linker-deactivator.php
 */
function deactivate_amazing_linker() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-amazing-linker-product-table.php';
    $table = new Amazing_Linker_Product_Table();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-amazing-linker-deactivator.php';
	$deactivator = new Amazing_Linker_Deactivator();
    $deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_amazing_linker' );
register_deactivation_hook( __FILE__, 'deactivate_amazing_linker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-amazing-linker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_amazing_linker() {

	$plugin = new Amazing_Linker();
	$plugin->run();

}
run_amazing_linker();