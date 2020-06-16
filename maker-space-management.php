<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makerspace.experimenta.science
 * @since             1.0.0
 * @package           ms_devices
 *
 * @wordpress-plugin
 * Plugin Name:       Maker Space Management
 * Plugin URI:        https://makerspace.experimenta.science/ms-device-management
 * Description:       Plugin to manage and display devices and device bookings for a maker space
 * Version:           1.0.0
 * Author:            Jonathan Günz
 * Author URI:        https://hmnd.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ms-device-management
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('MSM_FILE', __FILE__);
define('MSM_DIR', __DIR__);

define('MSM_DATETIME_FORMAT', "Y-m-d H:i:s");


function get_datetime()
{
	return new DateTime(current_time(MSM_DATETIME_FORMAT));
}



// the main plugin class
require_once dirname(__FILE__) . '/src/main.php';

MS_Devices_Main::instance();

register_activation_hook(__FILE__, array('MS_Devices_Main', 'activate'));
register_deactivation_hook(__FILE__, array('MS_Devices_Main', 'deactivate'));
