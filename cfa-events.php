<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.example.com/unknown
 * @since             1.0.0
 * @package           Cfa_Events
 *
 * @wordpress-plugin
 * Plugin Name:       CFA Events
 * Plugin URI:        https://www.example.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.9
 * Author:            Developer Junayed
 * Author URI:        https://www.example.com/unknown
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cfa-events
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
define( 'CFA_EVENTS_VERSION', '1.0.9' );

function get_local_timezone() {
    $timezone_string = get_option( 'timezone_string' );

    if ( ! empty( $timezone_string ) ) {
      return str_replace( '_', ' ', $timezone_string );
    }

    $gmt_offset = get_option( 'gmt_offset', 0 );

    $formatted_gmt_offset = sprintf( '%+g', (float) $gmt_offset );

    $formatted_gmt_offset = str_replace(
      array( '.25', '.5', '.75' ),
      array( ':15', ':30', ':45' ),
      (string) $formatted_gmt_offset
    );

    /* translators: %s is UTC offset, e.g. "+1" */
    return sprintf( __( 'UTC%s', 'cfa-events' ), $formatted_gmt_offset );
}

function get_active_event($event_id){
	$defaultZone = wp_timezone_string();
	if($defaultZone){
		date_default_timezone_set($defaultZone);
	}

	$eventDate = get_post_meta($event_id, '__event_date', true); // date from input
	$start_time = get_post_meta($event_id, '__event_start_time', true); // date from input
	$end_time = get_post_meta($event_id, '__event_end_time', true); // time from input

	if($eventDate && $end_time && $start_time){
		$end_time = date("h:ia", strtotime($end_time)); // Time format
		$todayWithTime = strtotime(date("Y-m-d h:ia")); // Today timestamps

		$eventDate = strtotime($eventDate." ".$end_time); // Event end datetimestamps
	
		if($todayWithTime <= $eventDate){
			return true;
		}
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cfa-events-activator.php
 */
function activate_cfa_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cfa-events-activator.php';
	Cfa_Events_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cfa-events-deactivator.php
 */
function deactivate_cfa_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cfa-events-deactivator.php';
	Cfa_Events_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cfa_events' );
register_deactivation_hook( __FILE__, 'deactivate_cfa_events' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cfa-events.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cfa_events() {

	$plugin = new Cfa_Events();
	$plugin->run();

}
run_cfa_events();
