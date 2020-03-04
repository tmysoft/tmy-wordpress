<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    TMY_G11n
 * @subpackage TMY_G11n/includes
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
 * @package    TMY_G11n
 * @subpackage TMY_G11n/includes
 * @author     Yu Shao <yu.shao.gm@gmail.com>
 */
class TMY_G11n {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      TMY_G11n_Loader    $loader    Maintains and registers all hooks for the plugin.
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

	protected $translator;

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
		if ( defined( 'ARETE_G11N_VERSION' ) ) {
			$this->version = ARETE_G11N_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'tmy-g11n';

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
	 * - TMY_G11n_Loader. Orchestrates the hooks of the plugin.
	 * - TMY_G11n_i18n. Defines internationalization functionality.
	 * - TMY_G11n_Admin. Defines all hooks for the admin area.
	 * - TMY_G11n_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tmy-g11n-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tmy-g11n-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tmy-g11n-translator.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tmy-g11n-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tmy-g11n-public.php';

		$this->loader = new TMY_G11n_Loader();

		$this->translator = new TMY_G11n_Translator();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the TMY_G11n_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new TMY_G11n_i18n();

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

		$plugin_admin = new TMY_G11n_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_translator() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'tmy_plugin_register_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'tmy_plugin_register_admin_menu' );

		$this->loader->add_action( 'wp_ajax_tmy_create_server_project', $plugin_admin, 'tmy_g11n_create_server_project' );
		$this->loader->add_action( 'wp_ajax_tmy_create_clear_plugin_data', $plugin_admin, 'tmy_g11n_clear_plugin_data' );
		$this->loader->add_action( 'wp_ajax_tmy_create_sync_translation', $plugin_admin, 'tmy_create_sync_translation' );

		$this->loader->add_filter( 'views_edit-post', $plugin_admin, 'g11n_edit_posts_views' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	private function define_public_hooks() {

		$plugin_public = new TMY_G11n_Public( $this->get_plugin_name(), $this->get_version(), $this->get_translator() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'G11nStartSession', 1 );
		$this->loader->add_action( 'init', $plugin_public, 'g11n_setcookie' );
		$this->loader->add_action( 'wp_login', $plugin_public, 'G11nEndSession' );
		$this->loader->add_action( 'wp_logout', $plugin_public, 'G11nEndSession' );

		$this->loader->add_action( 'publish_post', $plugin_public, 'g11n_post_published_notification', 10, 2 );
		$this->loader->add_action( 'publish_page', $plugin_public, 'g11n_post_published_notification', 10, 2 );

		$this->loader->add_action( 'publish_product', $plugin_public, 'g11n_post_published_notification', 10, 2 );

		$this->loader->add_action( 'init', $plugin_public, 'g11n_create_post_type_translation' );
		$this->loader->add_action( 'get_sidebar', $plugin_public, 'add_before_my_siderbar' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_public, 'myprefix_edit_form_after_title' );
		$this->loader->add_action( 'edit_form_after_editor', $plugin_public, 'g11n_push_status_div' );

                $this->loader->add_filter( 'bloginfo', $plugin_public, 'g11n_wp_title_filter', 10, 2 );
		$this->loader->add_filter( 'the_title', $plugin_public, 'g11n_title_filter', 10, 2 );
		$this->loader->add_filter( 'the_content', $plugin_public, 'g11n_content_filter' );
		$this->loader->add_filter( 'the_excerpt', $plugin_public, 'g11n_excerpt_filter' );
		$this->loader->add_filter( 'the_posts', $plugin_public, 'g11n_the_posts_filter' );

		$this->loader->add_filter( 'pre_update_option_blogname', $plugin_public, 'g11n_pre_option_blogname');
		$this->loader->add_filter( 'pre_update_option_blogdescription', $plugin_public, 'g11n_pre_option_blogdescription'); 
                $this->loader->add_filter( 'locale', $plugin_public, 'g11n_locale_filter', 10);

                $this->loader->add_filter( 'use_block_editor_for_post', $plugin_public, 'g11n_option_editor_change', 10, 1);

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
	 * @return    TMY_G11n_Loader    Orchestrates the hooks of the plugin.
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

	public function get_translator() {
		return $this->translator;
	}

}
