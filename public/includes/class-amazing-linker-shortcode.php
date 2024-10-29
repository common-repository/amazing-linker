<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/admin
 * @author     CodeRockz
 */

if( !class_exists( 'Amazing_Linker_Shortcode' ) ) {

	class Amazing_Linker_Shortcode {

		private $shortcode_template;

		private $api;

		public function __construct() {

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'public/includes/class-amazing-linker-shortcode-template.php';
    		$this->shortcode_template = new Amazing_Linker_Shortcode_Template();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-api.php';
			$this->api = new Amazing_Linker_Api();
			$this->api->set_credentials();

		}

		public function amazing_linker_shortcode_display($atts) {
		    $default = array(
		        "items" => '',
		        "type"  => "hr_list",
		        "width" => null		        
		    );
		    $shortcode_data = shortcode_atts($default, $atts);
		    $items = array_map('trim', explode(",",$shortcode_data['items']));

		    if($shortcode_data['type'] == "hr_list") {

		    	$list = $this->shortcode_template->amazing_linker_shortcode_hr_list($items);
		    	return $list;

		    } elseif($shortcode_data['type'] == "link_button") {

		    	$link_button = $this->shortcode_template->amazing_linker_shortcode_link_button($shortcode_data['items']);
		    	return $link_button;

		    } elseif($shortcode_data['type'] == "link_image") {

		    	$link_image = $this->shortcode_template->amazing_linker_shortcode_link_image($shortcode_data['items'],$shortcode_data['width']);
		    	return $link_image;

		    } elseif($shortcode_data['type'] == "link_text") {

		    	$link_text = $this->shortcode_template->amazing_linker_shortcode_link_text($shortcode_data['items']);
		    	return $link_text;

		    } else {

		    	return;

		    }

		}

	}

}