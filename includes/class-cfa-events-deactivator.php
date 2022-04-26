<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.example.com/unknown
 * @since      1.0.0
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Cfa_Events
 * @subpackage Cfa_Events/includes
 * @author     Developer Junayed <admin@easeare.com>
 */
class Cfa_Events_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'cfa_events_permalinks_flush' );
	}

}
