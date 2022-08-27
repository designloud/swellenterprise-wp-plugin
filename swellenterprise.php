<?php

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
 * @package           SWELLEnterprise
 *
 * @wordpress-plugin
 * Plugin Name:       SWELLEnterprise
 * Plugin URI:        https://swellsystem.com/swellenterprise-wordpress-plugin/
 * Description:       A plugin that connects your website to the SWELLEnterprise services.
 * Version:           1.0.0
 * Author:            DesignLoud, Inc
 * Author URI:        http://designloud.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       swellenterprise
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$swell_task_status['Pending'] = 0;
$swell_task_status['In Progress'] = 1;
$swell_task_status['Complete'] = 2;
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SWELLENTERPRISE_VERSION', '1.0.0');
define('SWELLENTERPRISE_BASE_NAME', plugin_basename(__FILE__));
define('SWELLENTERPRISE_BASE_URL', 'https://app.swellsystem.com/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-swellenterprise-activator.php
 */
function activate_swellenterprise() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-swellenterprise-activator.php';
    SWELLEnterprise_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-swellenterprise-deactivator.php
 */
function deactivate_swellenterprise() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-swellenterprise-deactivator.php';
    SWELLEnterprise_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_swellenterprise');
register_deactivation_hook(__FILE__, 'deactivate_swellenterprise');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-swellenterprise.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_swellenterprise() {

    $plugin = new SWELLEnterprise();
    $plugin->run();
}

run_swellenterprise();

// define the updated_option callback 
function swell_action_updated_option($callable, $int, $int1) {

    ini_set("log_errors", 1);
    ini_set("error_log", plugin_dir_path(__DIR__) . "/swell.txt");
    error_log(($callable));

    if ($callable == 'swellenterprise') {
        $plugin_api = new SWELLEnterprise_API();
        // $plugin_api->registerWebhooks();
    }
}
