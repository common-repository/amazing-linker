<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Amazing_Linker
 * @subpackage Amazing_Linker/includes
 * @author     CodeRockz
 */

if( !class_exists( 'Amazing_Linker' ) ) {

	class Amazing_Linker {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Amazing_Linker_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */

		public function __construct() {
			if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
				$this->version = PLUGIN_NAME_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'amazing-linker';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Amazing_Linker_Loader. Orchestrates the hooks of the plugin.
		 * - Amazing_Linker_i18n. Defines internationalization functionality.
		 * - Amazing_Linker_Admin. Defines all hooks for the admin area.
		 * - Amazing_Linker_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-amazing-linker-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-amazing-linker-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-amazing-linker-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-amazing-linker-public.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/libs/dom-parser/simple_html_dom.php';

			$this->loader = new Amazing_Linker_Loader();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Amazing_Linker_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new Amazing_Linker_i18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Amazing_Linker_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 999999 );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 999999 );

			//call admin menus section
	        $this->loader->add_action( 'admin_menu', $plugin_admin, 'amazing_linker_menus_sections' );
	        $this->loader->add_filter( 'plugin_action_links_' . AMAZING_LINKER_PLUGIN , $plugin_admin, 'settings_link' );
	        $this->loader->add_action( 'save_post', $plugin_admin, 'amazing_linker_database_save_shortcode_items' );
	        $this->loader->add_action( 'widgets_init', $plugin_admin, 'register_amazing_linker_widget' );

	        $this->loader->add_action('wp_ajax_amazing_linker_get_country_flag', $plugin_admin, 'amazing_linker_get_country_flag');

	        # filter for increase auto update time for gutenberg editor
	        $this->loader->add_filter( 'block_editor_settings', $plugin_admin, 'amazing_linker_block_editor_settings', 10, 2 );

	        $this->loader->add_action( 'admin_notices', $plugin_admin, 'amazing_linker_review_notice' );
	        $this->loader->add_action('wp_ajax_amazing_linker_save_review_notice', $plugin_admin, 'amazing_linker_save_review_notice');
	        
	        $this->loader->add_action('wp_ajax_amazing_linker_update_now', $plugin_admin, 'amazing_linker_process_handler');

	        $this->loader->add_filter( 'plugin_action_links_', $plugin_admin, 'amazing_linker_plugin_action_links');

	        $this->loader->add_action('admin_footer', $plugin_admin, 'amazing_linker_deactivate_scripts');
	        $this->loader->add_action('wp_ajax_amazing-linker-submit-deactivate-reason', $plugin_admin, 'amazing_linker_deactivate_reason_submission');

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new Amazing_Linker_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 999999 );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 999999 );
			$this->loader->add_action( 'init', $plugin_public, 'amazing_linker_register_shortcodes' );
			$this->loader->add_action( 'wp_footer', $plugin_public,'load_custom_css', 50000);
			$this->loader->add_action( 'wp_footer', $plugin_public,'load_onelink_script', 50001 );

			$this->loader->add_action('amazing_linker_product_update', $plugin_public, 'amazing_linker_product_update_task');
			$this->loader->add_filter('cron_schedules', $plugin_public, 'amazing_linker_cron_job_custom_recurrence');			
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Amazing_Linker_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

	}

}