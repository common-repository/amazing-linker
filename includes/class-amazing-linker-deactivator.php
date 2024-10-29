<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 * @author     CodeRockz
 */


if( !class_exists( 'Amazing_Linker_Deactivator' ) ) {

	class Amazing_Linker_Deactivator {

		
		public function deactivate() {

			$htaccess = get_home_path().'.htaccess';
			insert_with_markers($htaccess,'increase max execution time','');
			

			//find out when the last event was scheduled

			$timestamp = wp_next_scheduled('amazing_linker_product_update');

			//unschedule previous event if any

			wp_unschedule_event($timestamp,'amazing_linker_product_update');

			wp_clear_scheduled_hook('amazing_linker_product_update');
		}	

	}

}