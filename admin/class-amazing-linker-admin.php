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


if( !class_exists( 'Amazing_Linker_Admin' ) ) {

	class Amazing_Linker_Admin {

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
		 * @param      string    $plugin_name       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */

		public $settings;

		public $callbacks_mngr;

		private $api;

		private $table;

		private $helper;

		public $process_all;

		private $database_query;

		public $credentials;

		public $associate;

		public $background_update;


		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;

			require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-callbackmanager.php';
			$this->callbacks_mngr = new Amazing_Linker_Manager_Callbacks();

			require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-settingapi.php';
			$this->settings = new Amazing_Linker_Settings_Api();

			require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-api.php';
			$this->api = new Amazing_Linker_Api();
			$this->api->set_credentials();

			require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-product-table.php';
    		$this->table = new Amazing_Linker_Product_Table();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-database-query.php';
    		$this->database_query = new Amazing_Linker_Database_Query();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-helper.php';
    		$this->helper = new Amazing_Linker_Helper();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'includes/class-amazing-linker-background-update.php';
			$this->background_update = new Amazing_Linker_Background_Update();

    		require_once AMAZING_LINKER_PLUGIN_DIR . 'admin/includes/class-amazing-linker-product-widget.php';

			$this->credentials = array (
				'access_key_id' => 'Access Key ID',
				'secret_access_key' => 'Secret Access Key'
			);

			$this->associate = array (
				'associate_country' => 'Country',
				'tracking_id' => 'Tracking ID',
				'onelink_ad_instance_id' => 'OneLink Ad Instance ID'
			);

			$this->setting = array (
				'button_text' => 'Small Button Text',
				'large_button_text' => 'Large Button Text',
				'button_color' => 'Button Color',
				'button_text_color' => 'Button Text Color',
				'new_tab_open' => 'Open link in new tab',
				'nofollow_attribute' => 'Link has nofollow attribute',
				'update_info' => 'Update Information at bottom',
				'show_review' => 'Showing review quantity',
				'show_rating' => 'Showing star rating',
				'show_popup' => 'Showing product info popup',
				'update_product' => 'Update Product Info Every',
				'custom_css' => 'Custom CSS'
			);

			$this->set_settings();
			$this->set_sections();
			$this->set_fields();

			

		}

		/**
		 * Register the stylesheets for the admin area.
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

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/amazing-linker-admin.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the admin area.
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

			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/amazing-linker-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );

			$amazing_linker_nonce = wp_create_nonce('amazing_linker_nonce');
	        wp_localize_script($this->plugin_name, 'amazing_linker_ajax_obj', array(
	            'amazing_linker_ajax_url' => admin_url('admin-ajax.php'),
	            'nonce' => $amazing_linker_nonce,
	        ));

        	wp_enqueue_script($this->plugin_name);

        	# code editor for custom css input field of settings tab
        	wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
			
		}

		public function amazing_linker_menus_sections() {

	        add_menu_page(
				__('Amazing Linker', 'amazing-linker'),
	            __('Amazing Linker', 'amazing-linker'),
				'manage_options',
				'amazing-linker-settings',
				array($this, "amazing_linker_main_layout"),
				AMAZING_LINKER_PLUGIN_URL . 'admin/images/menu-icon.png',
				null
			);

			add_submenu_page(
		        'amazing-linker-settings',           // The slug for the parent menu page to which this sub menu belongs
		        'Settings',               // The text that's rendered in the browser title bar
		        'Settings',               // The text to be rendered in the menu
		        'manage_options',               // The capability required to access this menu item
		        'amazing-linker-settings',               // The slug by which this sub menu is identified
		        array($this, "amazing_linker_main_layout")    // The function used to display this options for this menu's page
		    );

	    }

	    public function amazing_linker_main_layout() {
	        include_once AMAZING_LINKER_PLUGIN_DIR . '/admin/partials/amazing-linker-admin-layout.php';
	    }

	    public function settings_link( $links ) {

	    	if ( array_key_exists( 'deactivate', $links ) ) {
				$links['deactivate'] = str_replace( '<a', '<a class="amazing-linker-deactivate-link"', $links['deactivate'] );
			}

	        $links[] = '<a href="admin.php?page=amazing-linker-settings">Settings</a>';
	        return $links;
	    }


		public function set_sections()
		{
			$args = array(
				
				array(
					'id' => 'amazing_linker_credential_tab_section',
					'title' =>  __( 'Credential Details', 'amazing-linker' ),
					'callback' => array( $this->callbacks_mngr, 'credential_tab_helper_text' ),
					'page' => 'amazing_linker_credential_tab'
				),
				array(
					'id' => 'amazing_linker_associate_tab_section',
					'title' => __( 'Amazon Associate Details', 'amazing-linker' ),
					'callback' => array( $this->callbacks_mngr, 'associate_tab_helper_text' ),
					'page' => 'amazing_linker_associate_tab'
				),
				array(
					'id' => 'amazing_linker_settings_tab_section',
					'title' => __( 'Amazing Linker Settings', 'amazing-linker' ),
					'page' => 'amazing_linker_settings_tab'
				)
			);

			$this->settings->amazing_linker_set_sections( $args );
		}

		public function set_fields()
		{
			$args = array();

			foreach ( $this->credentials as $key => $value ) {
				
				$args[] = array(
					'id' => $key,
					'title' => $value,
					'callback' => array( $this->callbacks_mngr, 'amazing_linker_input_field' ),
					'page' => 'amazing_linker_credential_tab',
					'section' => 'amazing_linker_credential_tab_section',
					'args' => array(
						'option_name' => 'amazing_linker_credential_tab_option',
						'label_for' => $key,
						'placeholder' => $value
					)

				);

			}

			foreach ( $this->associate as $key => $value ) { 

				if ($key == 'associate_country') {
					$callback = 'amazing_linker_county_select_field';
				} elseif($key == 'tracking_id' || $key == 'onelink_ad_instance_id') {
					$callback = 'amazing_linker_input_field';
				}

				$args[] = array(
					'id' => $key,
					'title' => $value,
					'callback' => array( $this->callbacks_mngr, $callback ),
					'page' => 'amazing_linker_associate_tab',
					'section' => 'amazing_linker_associate_tab_section',
					'args' => array(
						'option_name' => 'amazing_linker_associate_tab_option',
						'label_for' => $key,
						'placeholder' => $value
					)

				);
			}

			foreach ( $this->setting as $key => $value ) {

				if ($key == 'button_text' || $key == 'large_button_text') {
					$callback = 'amazing_linker_input_field';
					$class='';
				} elseif($key == 'button_color' || $key == 'button_text_color') {
					$callback = 'amazing_linker_colorpicker_field';
					$class='';
				} elseif($key == 'show_review' || $key == 'show_rating' || $key == 'show_popup' || $key == 'new_tab_open' || $key == 'nofollow_attribute' || $key == 'update_info') {
					$callback = 'amazing_linker_checkbox_field';
					$class='ui-toggle';
				} elseif($key == 'update_product') {
					$callback = 'amazing_linker_interval_select_field';
					$class='';
				} elseif($key == 'custom_css') {
					$callback = 'amazing_linker_textarea_field';
					$class='';
				}

				$args[] = array(
					'id' => $key,
					'title' => $value,
					'callback' => array( $this->callbacks_mngr, $callback ),
					'page' => 'amazing_linker_settings_tab',
					'section' => 'amazing_linker_settings_tab_section',
					'args' => array(
						'option_name' => 'amazing_linker_settings_tab_option',
						'label_for' => $key,
						'placeholder' => $value,
						'class' => $class
					)
				);
			}

			$this->settings->amazing_linker_set_fields( $args );
		}


		public function set_settings()
		{
			$args = array();

			foreach( $this->credentials as $key => $value ) {
				$args[] = array(
							'option_group' => 'amazing_linker_credential_tab_section',
							'option_name' => 'amazing_linker_credential_tab_option',
							'callback' => array( $this->callbacks_mngr, 'input_field_sanitization' )
						);
			}	

			foreach( $this->associate as $key => $value ) {
				if ($key == 'associate_country') {
					$sanitize_callback = 'select_field_sanitization';
				} elseif($key == 'tracking_id' || $key == 'onelink_ad_instance_id') {
					$sanitize_callback = 'input_field_sanitization';
				}

				$args[] = array(
							'option_group' => 'amazing_linker_associate_tab_section',
							'option_name' => 'amazing_linker_associate_tab_option',
							'callback' => array( $this->callbacks_mngr, $sanitize_callback )
						);

			}

			foreach( $this->setting as $key => $value ) { 

				if ($key == 'button_text' || $key == 'large_button_text' || $key == 'button_color' || $key == 'button_text_color') {
					$sanitize_callback = 'input_field_sanitization';
				} elseif($key == 'show_review' || $key == 'show_rating' || $key == 'new_tab_open' || $key == 'nofollow_attribute' || $key == 'update_info' ) {
					$sanitize_callback = 'checkbox_field_sanitization';
				} elseif($key == 'update_product') {
					$sanitize_callback = 'select_field_sanitization';
				} elseif($key == 'custom_css') {
					$sanitize_callback = 'textarea_field_sanitization';
				}

				$args[] = array(
					'option_group' => 'amazing_linker_settings_tab_section',
					'option_name' => 'amazing_linker_settings_tab_option',
					'callback' => array( $this->callbacks_mngr, $sanitize_callback )
				);
			}

		

			$this->settings->amazing_linker_set_settings( $args );
		}

		public function amazing_linker_database_save_shortcode_items($post_id) {

			$content = get_post($post_id);

			set_time_limit(0);
			if ( shortcode_exists( 'amlinker' ) && has_shortcode( $content->post_content, 'amlinker' ) ) {
				$shortcode_attributes = $this->helper->shortcode_get_all_attributes('amlinker', $content->post_content);
				$single_items = [];

				foreach($shortcode_attributes as $shortcode_attribute) {
					
					$shortcode_type = sanitize_text_field($shortcode_attribute['type']);
					
					if($shortcode_type != 'similar_items' && ($shortcode_type != 'bestseller_vertical' && $shortcode_type != 'bestseller_horizontal') && ($shortcode_type != 'newrelease_vertical' && $shortcode_type != 'newrelease_horizontal')) {
						
						$shortcode_sanitized_items = sanitize_text_field($shortcode_attribute['items']);

						$shortcode_items = array_map('trim', explode(",",$shortcode_sanitized_items));
						foreach($shortcode_items as $shortcode_item) {
							array_push($single_items,$shortcode_item);
						}

						$single_items = array_values(array_unique($single_items,SORT_REGULAR));

                        $single_items_confirm = [];
                        foreach($single_items as $single_item) {
                            if(!$this->database_query->item_exists_database($single_item,"single")) {
                                $single_items_confirm[] = $single_item;
                            }
                        }


                        $single_items = $single_items_confirm;

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
                            $this->database_query->insert_item_database($single_items);
                        }

					}
				}

			}
		}


		public function register_amazing_linker_widget() {
			register_widget('Amazing_Linker_Product_Widget');
		}

		public function amazing_linker_get_country_flag() {
			check_ajax_referer('amazing_linker_nonce');
			$country = $_POST['country'];

			switch ($country) {
				    case "com":
				        $class = "flag flag-us";
				        break;
				    case "co.uk":
				        $class = "flag flag-gb";
				        break;
				    case "ca":
				        $class = "flag flag-ca";
				        break;
				    case "es":
				        $class = "flag flag-es";
				        break;
				    case "fr":
				        $class = "flag flag-fr";
				        break;
				    case "it":
				        $class = "flag flag-it";
				        break;
				    case "de":
				        $class = "flag flag-de";
				        break;
				    case "com.mx":
				        $class = "flag flag-mx";
				        break;
				    case "cn":
				        $class = "flag flag-cn";
				        break;
				    case "co.jp":
				        $class = "flag flag-jp";
				        break;
				    case "com.br":
				        $class = "flag flag-br";
				        break;
				    case "in":
				        $class = "flag flag-in";
				        break;
				    default:
				        break;
				}

			$data=array(
	            "success"=>true,
	            "class"=>$class,
	        );

	        wp_send_json_success($data);
		}


		# increase auto update time for gutenberg editor
		public function amazing_linker_block_editor_settings( $editor_settings, $post ) {
			
			$editor_settings['autosaveInterval'] = 86400; //number of second [default value is 10]
			return $editor_settings;
		}

		public function amazing_linker_review_notice() {
		    $options = get_option('amazing_linker_review_notice');

		    $activation_time = get_option('amazing-linker-activation-time');

		    $notice = '<div class="amazing-linker-review-notice notice notice-success is-dismissible">';
	        $notice .= '<img class="amazing-linker-review-notice-left" src="'.AMAZING_LINKER_PLUGIN_URL.'admin/images/amazing-linker-logo.png" alt="amazing-linker">';
	        $notice .= '<div class="amazing-linker-review-notice-right">';
	        $notice .= '<p><b>We have worked relentlessly to develop the plugin and it would really appreciate us if you dropped a short review about the plugin. Your review means a lot to us and we are working to make the plugin more awesome. Thanks for using Amazing Linker.</b></p>';
	        $notice .= '<ul>';
	        $notice .= '<li><a val="later" href="#">Remind me later</a></li>';
	        $notice .= '<li><a class="amazing-linker-review-request-btn" style="font-weight:bold;" val="given" href="#" target="_blank">Review Here</a></li>';
    		$notice .= '<li><a val="never" href="#">I would not</a></li>';	        
	        $notice .= '</ul>';
	        $notice .= '</div>';
	        $notice .= '</div>';
	        
		    if(!$options && time()>= $activation_time + (60*60*24*15)){
		        echo $notice;
		    } else if(is_array($options)) {
		        if((!array_key_exists('review_notice',$options)) || ($options['review_notice'] =='later' && time()>=($options['updated_at'] + (60*60*24*30) ))){
		            echo $notice;
		        }
		    }
		}

		public function amazing_linker_save_review_notice() {
		    $notice = sanitize_text_field($_POST['notice']);
		    $value = array();
		    $value['review_notice'] = $notice;
		    $value['updated_at'] = time();

		    update_option('amazing_linker_review_notice',$value);
		    wp_send_json_success($value);
		}


		public function amazing_linker_process_handler() {
			check_ajax_referer('amazing_linker_nonce');
			$this->amazing_linker_update_product_now();
		}

		protected function amazing_linker_update_product_now() {
			

			$items = $this->amazing_linker_get_items();

            foreach ( $items as $item ) {
				$this->background_update->push_to_queue( $item );
			}

			$this->background_update->save()->dispatch();


			wp_send_json_success();
		}


		protected function amazing_linker_update_btn_loop ($post_type) {

			global $items;

			$args = array(
					'post_type' => $post_type,
					'posts_per_page'=> -1
				);

			$the_query = new WP_Query( $args );

			if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

				$post_id = get_the_ID();
				$content = get_post($post_id);

				if ( shortcode_exists( 'amlinker' ) && has_shortcode( $content->post_content, 'amlinker' ) ) {
				$shortcode_attributes = $this->helper->shortcode_get_all_attributes('amlinker', $content->post_content);
				
				foreach($shortcode_attributes as $shortcode_attribute) {

					$shortcode_type = sanitize_text_field($shortcode_attribute['type']);

					if($shortcode_type != 'similar_items' && ($shortcode_type != 'bestseller_vertical' && $shortcode_type != 'bestseller_horizontal') && ($shortcode_type != 'newrelease_vertical' && $shortcode_type != 'newrelease_horizontal')) {

						$shortcode_sanitized_items = sanitize_text_field($shortcode_attribute['items']);

						$shortcode_items = array_map('trim', explode(",",$shortcode_sanitized_items));
						foreach($shortcode_items as $shortcode_item) {
							array_push($items,$shortcode_item . '-single');
						}

					}

				}

			}


			endwhile; wp_reset_postdata();

			endif;
		}


		protected function amazing_linker_get_items() {

			set_time_limit(0);

			global $items;
			$items =[];

			$this->amazing_linker_update_btn_loop('any');

			$widget_items_option= (array)get_option('widget_amazing_linker_product_widget');
			if($widget_items_option !=false && array_key_exists('asin',$widget_items_option)) {
				$widget_items = array_map('trim', explode(",",sanitize_text_field($widget_items_option['asin'])));
				foreach($widget_items as $widget_item) {
					array_push($items,$widget_item.'-single');
				}
			}

            $items = array_values(array_unique($items,SORT_REGULAR));

            return $items;
		}


		public function amazing_linker_get_deactivate_reasons() {

			$reasons = array(
				array(
					'id'          => 'could-not-understand',
					'text'        => 'I couldn\'t understand how to make it work',
					'type'        => 'textarea',
					'placeholder' => 'Would you like us to assist you?'
				),
				array(
					'id'          => 'found-better-plugin',
					'text'        => 'I found a better plugin',
					'type'        => 'text',
					'placeholder' => 'Which plugin?'
				),
				array(
					'id'          => 'not-have-that-feature',
					'text'        => 'I need specific feature that you don\'t support',
					'type'        => 'textarea',
					'placeholder' => 'Could you tell us more about that feature?'
				),
				array(
					'id'          => 'is-not-working',
					'text'        => 'The plugin is not working',
					'type'        => 'textarea',
					'placeholder' => 'Could you tell us a bit more whats not working?'
				),
				array(
					'id'          => 'temporary-deactivation',
					'text'        => 'It\'s a temporary deactivation',
					'type'        => '',
					'placeholder' => ''
				),
				array(
					'id'          => 'other',
					'text'        => 'Other',
					'type'        => 'textarea',
					'placeholder' => 'Could you tell us a bit more?'
				),
			);

			return $reasons;
		}

		public function amazing_linker_deactivate_reason_submission(){
			check_ajax_referer('amazing_linker_nonce');
			global $wpdb;

			if ( ! isset( $_POST['reason_id'] ) ) { // WPCS: CSRF ok, Input var ok.
				wp_send_json_error();
			}

			$current_user = new WP_User(get_current_user_id());

			$data = array(
				'reason_id'     => sanitize_text_field( $_POST['reason_id'] ), // WPCS: CSRF ok, Input var ok.
				'plugin'        => "Amazing Linker Free",
				'url'           => home_url(),
				'user_email'    => $current_user->data->user_email,
				'user_name'     => $current_user->data->display_name,
				'reason_info'   => isset( $_REQUEST['reason_info'] ) ? trim( stripslashes( $_REQUEST['reason_info'] ) ) : '',
				'software'      => $_SERVER['SERVER_SOFTWARE'],
				'date'			=> time(),
				'php_version'   => phpversion(),
				'mysql_version' => $wpdb->db_version(),
				'wp_version'    => get_bloginfo( 'version' )
			);


			$this->amazing_linker_deactivate_send_request( $data);
			wp_send_json_success();

		}

		public function amazing_linker_deactivate_send_request( $params) {
			$api_url = "https://coderockz.com/wp-json/coderockz-api/v1/deactivation-reason";
			return  wp_remote_post($api_url, array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => false,
					'headers'     => array( 'user-agent' => 'AmazingLinker/' . md5( esc_url( home_url() ) ) . ';' ),
					'body'        => $params,
					'cookies'     => array()
				)
			);
		}

		function amazing_linker_deactivate_scripts() {

			global $pagenow;

			if ( 'plugins.php' != $pagenow ) {
				return;
			}

			$reasons = $this->amazing_linker_get_deactivate_reasons();
			?>
			<!--pop up modal-->
			<div class="amazing_linker_deactive_plugin-modal" id="amazing_linker_deactive_plugin-modal">
				<div class="amazing_linker_deactive_plugin-modal-wrap">
					<div class="amazing_linker_deactive_plugin-modal-header">
						<h2 style="margin:0;"><span class="dashicons dashicons-testimonial"></span><?php _e( ' QUICK FEEDBACK' ); ?></h2>
					</div>

					<div class="amazing_linker_deactive_plugin-modal-body">
						<p style="font-size:15px;font-weight:bold"><?php _e( 'If you have a moment, please share why you are deactivating Amazing Linker', 'amazing-linker' ); ?></p>
						<ul class="reasons">
							<?php foreach ($reasons as $reason) { ?>
								<li data-type="<?php echo esc_attr( $reason['type'] ); ?>" data-placeholder="<?php echo esc_attr( $reason['placeholder'] ); ?>">
									<label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?></label>
								</li>
							<?php } ?>
						</ul>
					</div>

					<div class="amazing_linker_deactive_plugin-modal-footer">
						<a href="#" class="amazing-linker-skip-deactivate"><?php _e( 'Skip & Deactivate', 'amazing-linker' ); ?></a>
						<div style="float:left">
						<button class="amazing-linker-deactivate-button button-primary"><?php _e( 'Submit & Deactivate', 'amazing-linker' ); ?></button>
						<button class="amazing-linker-cancel-button button-secondary"><?php _e( 'Cancel', 'amazing-linker' ); ?></button>
						</div>
					</div>
				</div>
			</div>

			<?php
		}

	}
}