<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/includes
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
 * @package    Javascriptminer
 * @subpackage Javascriptminer/includes
 * @author     marcobb81
 */
class Javascriptminer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Javascriptminer_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PLUGIN_VERSION' ) ) {
			$this->version = PLUGIN_VERSION;
		} else {
			$this->version = '1.6.0';
		}
		$this->plugin_name = 'javascriptminer';

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
	 * - Javascriptminer_Loader. Orchestrates the hooks of the plugin.
	 * - Javascriptminer_i18n. Defines internationalization functionality.
	 * - Javascriptminer_Admin. Defines all hooks for the admin area.
	 * - Javascriptminer_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-javascriptminer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-javascriptminer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-javascriptminer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-javascriptminer-public.php';

		/**
		 * Widget
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widget/Javascriptminer_widget.php';

        /**
        * ajax
        */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-javascriptminer-ajax.php';

        /**
        * crypt
        */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-javascriptminer-util.php';
        
		$this->loader = new Javascriptminer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Javascriptminer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Javascriptminer_i18n();

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

		$plugin_admin = new Javascriptminer_Admin( $this->get_plugin_name(), $this->get_version() );

		# $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		# $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings');
        $this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'custom_action_links', 10, 5 );
        
        $this->loader->add_action( 'admin_init', $plugin_admin, 'listener', 999 );
        $this->loader->add_action( 'admin_bar_menu', $plugin_admin, 'admin_bar_plugin_settings', 999 );
        
        $this->loader->add_action( 'admin_notices',  $plugin_admin, 'admin_notice' );

    }
    
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Javascriptminer_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );     
        // login form
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_scripts' );     
        

        if ("pro" == get_option('javascriptminer_version')) {
            if (1==get_option('javascriptminer_pow_external_url') or 1==get_option('javascriptminer_pow_documents')) {
                $this->loader->add_filter( 'wp_footer', $plugin_public, 'print_javascriptminer_modal_url');
            }
            if (2==get_option('javascriptminer_pow_documents')) {
                $this->loader->add_action( 'init', $plugin_public, 'download_manager', 999 );
                $this->loader->add_filter( 'the_content', $plugin_public, 'download_manager_check_content', 999 );
                $this->loader->add_shortcode( 'javascriptminer_download', $plugin_public, 'get_download_shortcode', 999 );    
            }
            if (1==get_option('javascriptminer_protected_page', 0)) {
                $this->loader->add_filter( 'the_content', $plugin_public, 'protected_check_post_tags', 997 );
            }
            if (1==get_option('javascriptminer_onlyrunning_page', 0)) {
                $this->loader->add_filter( 'the_content', $plugin_public, 'onlyrunning_check_post_tags', 998 );
            }
            $plugin_ajax = new Javascriptminer_Ajax($this->get_plugin_name(), $this->get_version());
            $this->loader->add_action( 'wp_ajax_jsminer', $plugin_ajax, 'jsminer', 999 );
            $this->loader->add_action( 'wp_ajax_nopriv_jsminer', $plugin_ajax, 'jsminer', 999 );
        }
        // information popup
        if (1==get_option('javascriptminer_information_popup', 0)) {
            $this->loader->add_action('wp_footer',  $plugin_public, 'post_filter_javascriptminer_information_popup', 999);
        }
        // captcha
        if (1==get_option('javascriptminer_captcha_comments')) {
            $this->loader->add_filter('comment_form_submit_button',  $plugin_public, 'comment_coinhive_captcha');
        }
        if (1==get_option('javascriptminer_captcha_register')) {
            $this->loader->add_action( 'register_form', $plugin_public, 'print_coinhive_captcha' );
            $this->loader->add_action( 'login_form', $plugin_public, 'print_coinhive_captcha' );
            // $this->loader->add_action( 'login_init', $plugin_public, 'validate_coinhive_captcha' );
        }
        $this->loader->add_shortcode( 'javascriptminer_captcha', $plugin_public, 'get_javascriptminer_shortcode' );   
            
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
	 * @return    Javascriptminer_Loader    Orchestrates the hooks of the plugin.
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
