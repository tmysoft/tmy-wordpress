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
		if ( defined( 'TMY_G11N_VERSION' ) ) {
			$this->version = TMY_G11N_VERSION;
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

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'tmy_load_plugin_textdomain' );

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
		$this->loader->add_action( 'wp_ajax_tmy_get_project_status', $plugin_admin, 'tmy_g11n_get_project_status' );
		$this->loader->add_action( 'wp_ajax_tmy_get_local_translation_status', $plugin_admin, 'tmy_g11n_get_local_translation_status' );
		$this->loader->add_action( 'wp_ajax_tmy_create_sync_translation', $plugin_admin, 'tmy_create_sync_translation' );
		$this->loader->add_action( 'wp_ajax_tmy_get_post_translation_status', $plugin_admin, 'tmy_get_post_translation_status' );

		$this->loader->add_filter( 'views_edit-post', $plugin_admin, 'g11n_edit_posts_views' );
                $this->loader->add_filter( 'pre_update_option', $plugin_admin,'tmy_plugin_option_update', 10, 3 );

                $all_post_type_options = tmy_g11n_available_post_type_options();
                foreach ( $all_post_type_options  as $option_name ) {
		    $this->loader->add_filter( $option_name, $plugin_admin, 'tmy_plugin_option_update_after', 10, 3 );
                }

		//$this->loader->add_filter( 'update_option_g11n_l10n_props_desc', $plugin_admin, 'tmy_plugin_option_update_after', 10, 3 );
		//$this->loader->add_filter( 'update_option_g11n_l10n_props_blogname', $plugin_admin, 'tmy_plugin_option_update_after', 10, 3 );
		//$this->loader->add_filter( 'update_option_g11n_l10n_props_posts', $plugin_admin, 'tmy_plugin_option_update_after', 10, 3 );
		//$this->loader->add_filter( 'update_option_g11n_l10n_props_pages', $plugin_admin, 'tmy_plugin_option_update_after', 10, 3 );

                $this->loader->add_filter( 'manage_g11n_translation_posts_columns', $plugin_admin,'tmy_plugin_g11n_translation_set_columns');
                $this->loader->add_filter( 'manage_edit-g11n_translation_sortable_columns', $plugin_admin,'tmy_plugin_g11n_translation_set_sortable');
                $this->loader->add_action( 'manage_g11n_translation_posts_custom_column', $plugin_admin,'tmy_plugin_g11n_translation_set_columns_value',10,2);

                $this->loader->add_filter( 'manage_pages_columns', $plugin_admin,'tmy_plugin_page_set_columns');
                $this->loader->add_action( 'manage_pages_custom_column', $plugin_admin,'tmy_plugin_post_set_columns_value',10,2);

                $this->loader->add_filter( 'manage_posts_columns', $plugin_admin,'tmy_plugin_post_set_columns', 10, 2);
                $this->loader->add_action( 'manage_posts_custom_column', $plugin_admin,'tmy_plugin_post_set_columns_value',10,2);
                //$this->loader->add_filter( 'manage_edit-post_sortable_columns', $plugin_admin,'tmy_plugin_post_set_sortable');

                $this->loader->add_action( 'pre_get_posts', $plugin_admin,'tmy_plugin_g11n_translation_set_columns_orderby');
                //$this->loader->add_action( 'edit_form_top', $plugin_admin,'tmy_plugin_g11n_edit_form_top');

                //$this->loader->add_filter( 'bulk_actions-edit-post', $plugin_admin,'tmy_plugin_g11n_register_bulk_actions');
                //$this->loader->add_filter( 'handle_bulk_actions-edit-post', $plugin_admin, 'tmy_plugin_g11n_bulk_action_handler', 10, 3 );
                //$this->loader->add_filter( 'bulk_actions-edit-page', $plugin_admin,'tmy_plugin_g11n_register_bulk_actions');
                //$this->loader->add_filter( 'handle_bulk_actions-edit-page', $plugin_admin, 'tmy_plugin_g11n_bulk_action_handler', 10, 3 );

                $this->loader->add_action( 'admin_notices', $plugin_admin, 'tmy_plugin_g11n_admin_notice' );
                $this->loader->add_action( 'admin_head', $plugin_admin, 'tmy_plugin_g11n_admin_head' );
                //$this->loader->add_action( 'admin_init', $plugin_admin, 'tmy_plugin_g11n_update_htaccess' );
 
 
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
		//$this->loader->add_action( 'init', $plugin_public, 'g11n_setcookie' );
		$this->loader->add_action( 'wp_login', $plugin_public, 'G11nEndSession' );
		$this->loader->add_action( 'wp_logout', $plugin_public, 'G11nEndSession' );

                // Sep 2022
		//$this->loader->add_action( 'publish_post', $plugin_public, 'g11n_post_published_notification', 10, 2 );
		//$this->loader->add_action( 'publish_page', $plugin_public, 'g11n_post_published_notification', 10, 2 );
		//$this->loader->add_action( 'publish_product', $plugin_public, 'g11n_post_published_notification', 10, 2 );

		//$this->loader->add_action( 'save_post', $plugin_public, 'g11n_post_saved_notification', 10, 2 );

	        $this->loader->add_action( 'init', $plugin_public, 'g11n_create_post_type_translation' );
		//$this->loader->add_action( 'init', $plugin_public, 'g11n_create_rewrite_rule' );

                if ((strcmp(trim(get_option('permalink_structure')),'')!==0) &&
                    (strcmp(trim(get_option('g11n_seo_url_enable')),'Yes')===0)) {

		    $this->loader->add_filter( 'post_type_link', $plugin_public, 'rewrite_tag_permalink_post_link', 10, 3 );
		    $this->loader->add_filter( 'post_link', $plugin_public, 'rewrite_tag_permalink_post_link', 10, 3 );
		    $this->loader->add_filter( 'page_link', $plugin_public, 'rewrite_tag_permalink_post_link', 10, 3 );

		    $this->loader->add_filter( 'year_link', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    $this->loader->add_filter( 'month_link', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    $this->loader->add_filter( 'day_link', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    //$this->loader->add_filter( 'post_type_archive_link', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    $this->loader->add_filter( 'tag_link', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    $this->loader->add_filter( 'category_link', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    $this->loader->add_filter( 'search_link', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    //$this->loader->add_filter( 'home_url', $plugin_public, 'tmy_rewrite_permalink_links', 10, 1 );
		    $this->loader->add_action( 'wp_head', $plugin_public, 'tmy_g11n_html_head_handler');

                }


		$this->loader->add_action( 'get_sidebar', $plugin_public, 'add_before_my_siderbar' );
		//$this->loader->add_action( 'before_sidebar', $plugin_public, 'add_before_my_siderbar' );
		$this->loader->add_action( 'dynamic_sidebar', $plugin_public, 'add_before_dynamic_siderbar' );
		//$this->loader->add_action( 'dynamic_sidebar_params', $plugin_public, 'add_before_my_siderbar' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_public, 'myprefix_edit_form_after_title' );
		//$this->loader->add_action( 'edit_form_after_editor', $plugin_public, 'g11n_push_status_div' );
		//$this->loader->add_filter( 'widget_title', $plugin_public, 'g11n_widget_title', 10, 2 );
		$this->loader->add_action(  'wp_footer', $plugin_public, 'g11n_add_floating_menu' );

                $this->loader->add_filter( 'bloginfo', $plugin_public, 'g11n_wp_title_filter', 10, 2 );
		$this->loader->add_filter( 'the_title', $plugin_public, 'g11n_title_filter', 10, 2 );
		$this->loader->add_filter( 'the_content', $plugin_public, 'g11n_content_filter' );
		//$this->loader->add_filter( 'the_excerpt', $plugin_public, 'g11n_excerpt_filter' );
		//$this->loader->add_filter( 'get_the_excerpt', $plugin_public, 'g11n_excerpt_filter' );
		$this->loader->add_filter( 'the_posts', $plugin_public, 'g11n_the_posts_filter' );

		$this->loader->add_filter( 'pre_option_blogname', $plugin_public, 'g11n_pre_get_option_blogname',10, 2);
		$this->loader->add_filter( 'pre_option_blogdescription', $plugin_public, 'g11n_pre_get_option_blogdescription',10, 2);

		$this->loader->add_filter( 'pre_update_option_blogname', $plugin_public, 'g11n_pre_option_blogname');
		$this->loader->add_filter( 'pre_update_option_blogdescription', $plugin_public, 'g11n_pre_option_blogdescription'); 

                $this->loader->add_filter( 'locale', $plugin_public, 'g11n_locale_filter', 10);

                $this->loader->add_filter( 'use_block_editor_for_post', $plugin_public, 'g11n_option_editor_change', 10, 1);

		$this->loader->add_action( 'init', $plugin_public, 'tmy_g11n_blocks_init');
		$this->loader->add_action( 'template_redirect', $plugin_public, 'tmy_g11n_template_redirect');
		//$this->loader->add_filter( 'site_url', $plugin_public, 'tmy_g11n_site_url', 10, 2);

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
