<?php
/**
 * Fired during plugin activation
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 * @author     CodeRockz
 */

if( !class_exists( 'Amazing_Linker_Activator' ) ) {

	class Amazing_Linker_Activator {

		private $table;

		public function __construct($table_object) {
	        $this->table = $table_object;
	    }

		public function activate() {

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	        global $wpdb;
	        if ($wpdb->get_var("Show tables like '" . $this->table->amazing_linker_database_product_table() . "'") != $this->table->amazing_linker_database_product_table() ) {
		        $sqlQuery = 'CREATE TABLE `' . $this->table->amazing_linker_database_product_table() . '` (
		                        `id` int(11) NOT NULL AUTO_INCREMENT,
		                        `asin` varchar(255),
		                        `title` text DEFAULT NULL,
		                        `images` text DEFAULT NULL,		                        
		                        `image_set` text DEFAULT NULL,		                        
		                        `price` varchar(255) DEFAULT NULL,
		                        `saleitem_reg_price` varchar(255) DEFAULT NULL,
		                        `sale_lebel` varchar(255) DEFAULT NULL,
		                        `features` text DEFAULT NULL,
		                        `comparison_table_features` text DEFAULT NULL,
		                        `review` varchar(255) DEFAULT NULL,
		                        `rating` DECIMAL(2,1) DEFAULT NULL,
		                        `eligible_prime` varchar(255) DEFAULT NULL,
		                        `affiliate_link` text DEFAULT NULL,
		                        `review_iframe` text DEFAULT NULL,
		                        `additional_info` text DEFAULT NULL,
		                        `updated_at` varchar(255) DEFAULT NULL,
		                        PRIMARY KEY (`id`)
		                       ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci';
		        dbDelta($sqlQuery);
	    	}

	        if ($wpdb->get_var("Show tables like '" . $this->table->amazing_linker_database_list_table() . "'") != $this->table->amazing_linker_database_list_table() ) {
		        $sqlQuery = 'CREATE TABLE `' . $this->table->amazing_linker_database_list_table() . '` (
		                        `id` int(11) NOT NULL AUTO_INCREMENT,
		                        `asin_node` varchar(255),
		                        `type` varchar(255) DEFAULT NULL,	                        
		                        `items` LONGTEXT DEFAULT NULL,
		                        `additional_info` text DEFAULT NULL,		                        
		                        `updated_at` varchar(255) DEFAULT NULL,
		                        PRIMARY KEY (`id`)
		                       ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci';
		        dbDelta($sqlQuery);
	    	}

	    	if ($wpdb->get_var("Show tables like '" . $this->table->amazing_linker_database_comparison_table() . "'") != $this->table->amazing_linker_database_comparison_table() ) {
		        $sqlQuery = 'CREATE TABLE `' . $this->table->amazing_linker_database_comparison_table() . '` (
		                        `id` int(11) NOT NULL AUTO_INCREMENT,
		                        `table_title` varchar(255),
		                        `table_product` varchar(255) DEFAULT NULL,	                        
		                        `product_features` varchar(255) DEFAULT NULL,
		                        `product_lebel` varchar(255) DEFAULT NULL,
		                        `additional_info` text DEFAULT NULL,
		                        PRIMARY KEY (`id`)
		                       ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci';
		        dbDelta($sqlQuery);
	    	}


$max_execution = "<IfModule mod_php5.c>
	php_value max_execution_time 300
</IfModule>";

			$htaccess = get_home_path().'.htaccess';
			if(file_exists( $htaccess )) {
				if(!strpos($htaccess,$max_execution)){
					insert_with_markers($htaccess,'increase max execution time',$max_execution);
				}
			}
				
			if ( !wp_next_scheduled( 'amazing_linker_product_update' ) ) {
				wp_schedule_event(time(), 'amazing_linker_corn', 'amazing_linker_product_update');
			}

			update_option('amazing-linker-activation-time',time());

		}

	}

}