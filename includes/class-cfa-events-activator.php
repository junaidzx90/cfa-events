<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.example.com/unknown
 * @since      1.0.0
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cfa_Events
 * @subpackage Cfa_Events/includes
 * @author     Developer Junayed <admin@easeare.com>
 */
class Cfa_Events_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if(get_option( 'cfa_participants_column' ) !== 'yes'){
			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cfa_registrants" );
			update_option( 'cfa_participants_column', 'yes' );
		}
	
		$cfa_registrants = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}cfa_registrants` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			`event_id` INT NOT NULL,
			`name` VARCHAR(255) NOT NULL,
			`email` VARCHAR(255) NOT NULL,
			`phone` INT NOT NULL,
			`company` VARCHAR(255) NOT NULL,
			`participants` INT NOT NULL,
			`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`ID`)) ENGINE = InnoDB";
		dbDelta($cfa_registrants);
	}
}
