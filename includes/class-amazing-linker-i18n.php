<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 * @author     CodeRockz
 */

if( !class_exists( 'Amazing_Linker_i18n' ) ) {

	class Amazing_Linker_i18n {


		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain(
				'amazing-linker',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}

	}

}