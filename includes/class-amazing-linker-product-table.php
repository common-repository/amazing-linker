<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://shoroar.com
 * @since      1.0.0
 *
 * @package    Wp_Crud
 * @subpackage Wp_Crud/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Wp_Crud
 * @subpackage Wp_Crud/includes
 * @author     CodeRockz
 */


if( !class_exists( 'Amazing_Linker_Product_Table' ) ) {

	class Amazing_Linker_Product_Table {

		public function amazing_linker_database_product_table(){
            global $wpdb;
            return $wpdb->prefix."amazing_linker_product"; 
	    }

	    public function amazing_linker_database_list_table(){
            global $wpdb;
            return $wpdb->prefix."amazing_linker_list"; 
	    }

	    public function amazing_linker_database_comparison_table(){
            global $wpdb;
            return $wpdb->prefix."amazing_linker_comparison_table"; 
	    }

	}

}