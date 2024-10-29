<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/public
 * @author     CodeRockz
 */

if( !class_exists( 'Amazing_Linker_Public' ) ) {

	class Amazing_Linker_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $plugin_name       The name of the plugin.
		 * @param      string    $version    The version of this plugin.
		 */

		public $amazing_linker_shortcode;

		private $helper;

		private $api;

		private $database_query;

		private $settings;

		private $new_tab;

		private $no_follow;

		private $show_review;

		private $show_rating;

		private $button_text;

		private $button_color;

		private $button_text_color;

		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;
			require_once plugin_dir_path( __FILE__ ) . '/includes/class-amazing-linker-shortcode.php';
			$this->amazing_linker_shortcode = new Amazing_Linker_Shortcode();

			require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-helper.php';
    		$this->helper = new Amazing_Linker_Helper();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-api.php';
			$this->api = new Amazing_Linker_Api();
			$this->api->set_credentials();

			require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-database-query.php';
    		$this->database_query = new Amazing_Linker_Database_Query();

    		$this->settings = (array)get_option('amazing_linker_settings_tab_option');

    		$this->new_tab = '_blank';
    		$this->no_follow = 'nofollow';
    		$this->show_review = false;
    		$this->show_rating = false;
    		$this->button_text = "SEE DETAILS";
            $this->button_color = "#222f3d";
            $this->button_text_color = "#ffffff";

            if( $this->settings != false ) {
                if(array_key_exists('new_tab_open', $this->settings)) {
                	$this->settings['new_tab_open'] ? $this->new_tab = '_blank' : $this->new_tab = '';
                } 
                if(array_key_exists('nofollow_attribute', $this->settings)) {
                	$this->settings['nofollow_attribute'] ? $this->no_follow = 'nofollow' : $this->no_follow = '';
                }
                if(array_key_exists('show_review', $this->settings)) {
                	$this->settings['show_review'] ? $this->show_review = true : $this->show_review = false;
                }
                if(array_key_exists('show_rating', $this->settings)) {
                	$this->settings['show_rating'] ? $this->show_rating = true : $this->show_rating = false;
                }
                
                if(array_key_exists('button_text', $this->settings)) {
                	$this->button_text = sanitize_text_field($this->settings['button_text']);
                }
                
                if(array_key_exists('button_color', $this->settings)) {
                	$this->button_color = sanitize_text_field($this->settings['button_color']);
                }
                
                if(array_key_exists('button_text_color', $this->settings)) {
                	$this->button_text_color = sanitize_text_field($this->settings['button_text_color']);
                }
                 
            }

		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Amazing_Linker_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Amazing_Linker_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/amazing-linker-public.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Amazing_Linker_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Amazing_Linker_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/amazing-linker-public.js', array( 'jquery' ), $this->version, true );

			$amazing_linker_nonce = wp_create_nonce('amazing_linker_nonce');
	        wp_localize_script($this->plugin_name, 'amazing_linker_ajax_obj', array(
	            'amazing_linker_ajax_url' => admin_url('admin-ajax.php'),
	            'nonce' => $amazing_linker_nonce,
	        ));

        	wp_enqueue_script($this->plugin_name);

		}

		public function amazing_linker_register_shortcodes() {
			add_shortcode( 'amlinker', array( $this->amazing_linker_shortcode, 'amazing_linker_shortcode_display') );
		}

		public function load_onelink_script() {
			$options = (array)get_option( 'amazing_linker_associate_tab_option' );
			if($options !=false && array_key_exists('onelink_ad_instance_id', $options) && !isset($options['onelink_ad_instance_id'])) {
				$onelink_ad_instance_id = sanitize_text_field($options['onelink_ad_instance_id']);
				echo '<div id="amzn-assoc-ad-'.$onelink_ad_instance_id.'"></div><script async src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId='.$onelink_ad_instance_id.'"></script>';
			}
		}

		public function load_custom_css() {
			$options = (array)get_option( 'amazing_linker_settings_tab_option' );
			if($options !=false && array_key_exists('custom_css', $options)) { 
				$custom_css = wp_unslash($options['custom_css']);
				echo '<style>' . $custom_css . '</style>';
			}
		}

		//add custom interval

		public function amazing_linker_cron_job_custom_recurrence($schedules) {

			$option = (array)get_option('amazing_linker_settings_tab_option');
			if($option !=false && array_key_exists('update_product', $option)) {
				$interval = $option['update_product'];				
			} else {
				$interval=43200;
			}
			$schedules['amazing_linker_corn']=array(
			   'interval'=>$interval,
			   'display'=>__('Amazing Linker Product Update Interval', 'amazing-linker')
			
			);		
			return $schedules;	
		}

		protected function amazing_linker_cron_loop ($post_type) { 

			global $single_items;

			$args = array(
				'post_type' => $post_type,
				'posts_per_page'=> -1
			);

			$the_query = new WP_Query( $args );
		
			if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

				$post_id = get_the_ID();
				$content = get_post($post_id);
				if ( shortcode_exists( 'amlinker' ) && has_shortcode( $content->post_content, 'amlinker' )) {
				$shortcode_attributes = $this->helper->shortcode_get_all_attributes('amlinker', $content->post_content);
				
				foreach($shortcode_attributes as $shortcode_attribute) {

					$shortcode_type = sanitize_text_field($shortcode_attribute['type']);

					if($shortcode_type != 'similar_items' && ($shortcode_type != 'bestseller_vertical' && $shortcode_type != 'bestseller_horizontal') && ($shortcode_type != 'newrelease_vertical' && $shortcode_type != 'newrelease_horizontal')) {

						$shortcode_sanitized_items = sanitize_text_field($shortcode_attribute['items']);

						$shortcode_items = array_map('trim', explode(",",$shortcode_sanitized_items));
						foreach($shortcode_items as $shortcode_item) {
							array_push($single_items, $shortcode_item);
						}

					}

				}

			}

			endwhile; wp_reset_postdata();
			endif;

		}

		public function amazing_linker_product_update_task() {

			set_time_limit(0);

			global $single_items;
			
			$single_items = [];

			$this->amazing_linker_cron_loop('any');

			// add widget's single product items with post's single product items then delete the redundent products from the product database table then do the update task
			
			$widget_items_option= (array)get_option('widget_amazing_linker_product_widget');
			if($widget_items_option !=false && array_key_exists('asin',$widget_items_option)) {
				$widget_items = array_map('trim', explode(",",sanitize_text_field($widget_items_option['asin'])));
				foreach($widget_items as $widget_item) {
					array_push($single_items,$widget_item);
				}
			} 
			$single_items = array_values(array_unique($single_items,SORT_REGULAR));
			$database_products = $this->database_query->asin_column_query();
			foreach($database_products as $database_product) {
				if (!in_array($database_product['asin'], $single_items)) {
					$this->database_query->delete_redundent_product_entry_database($database_product['asin']);
				}
			}
			$item_count = count($single_items);
            if(isset($single_items) && count($single_items)>0) {
                $single_items = $this->api->item_lookup($single_items);
            }
            if($item_count == 1 ){
                $temp_item = [];
                array_push($temp_item,$single_items);
                $single_items = $temp_item;

            }
            if(isset($single_items) && count($single_items)>0) {
                $this->database_query->update_item_database($single_items);
            }		

		}

	}

}