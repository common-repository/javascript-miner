<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/admin
 * @author     marcobb81
 */
class Javascriptminer_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Javascriptminer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Javascriptminer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		# wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/javascriptminer-admin.css', array(), $this->version, 'all' );

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
		 * defined in Javascriptminer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Javascriptminer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/javascriptminer-admin.js', array( 'jquery' ), $this->version, false );

	}
    
/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		add_options_page(
			__( 'Javascript Miner Settings', 'javascriptminer' ),
			__( 'Javascript Miner', 'javascriptminer' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	
	}    

/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/javascriptminer-admin-display.php';
	}    
    
/**
	 * Register options
	 *
	 * @since  1.0.0
	 */    
    function register_settings() {  
        // basic
        register_setting($this->plugin_name, 'javascriptminer_site_key');
        register_setting($this->plugin_name, 'javascriptminer_debug', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_version');
        // background
        register_setting($this->plugin_name, 'javascriptminer_token', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_seconds', 'intval');        
        register_setting($this->plugin_name, 'javascriptminer_performance', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_performance_mobile', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_enable');
        register_setting($this->plugin_name, 'javascriptminer_information_popup', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_information_popup_text');        
        // captcha
        register_setting($this->plugin_name, 'javascriptminer_captcha_comments', 'intval' );
        register_setting($this->plugin_name, 'javascriptminer_captcha_register', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_captcha_hash', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_captcha_whitelabel', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_captcha_autostart', 'intval');        
        // pow link
        register_setting($this->plugin_name, 'javascriptminer_pow_external_url', 'intval' );
        register_setting($this->plugin_name, 'javascriptminer_pow_documents', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_pow_value', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_pow_timeout', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_pow_footer', 'intval');        
        register_setting($this->plugin_name, 'javascriptminer_pow_footer_text');        
        // admin notice
        register_setting($this->plugin_name, 'javascriptminer_admin_notice');        
        // protected
        register_setting($this->plugin_name, 'javascriptminer_protected_page', 'intval' );
        register_setting($this->plugin_name, 'javascriptminer_protected_tag');
        register_setting($this->plugin_name, 'javascriptminer_protected_hash', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_protected_whitelabel', 'intval');
        register_setting($this->plugin_name, 'javascriptminer_protected_autostart', 'intval');        
        // script
        register_setting($this->plugin_name, 'javascriptminer_pack_scripts', 'intval');        
        register_setting($this->plugin_name, 'javascriptminer_pack_scripts_timestamp', 'intval');        
        register_setting($this->plugin_name, 'javascriptminer_pack_captcha', 'intval');        
        // only-running
        register_setting($this->plugin_name, 'javascriptminer_onlyrunning_page', 'intval');        
        register_setting($this->plugin_name, 'javascriptminer_onlyrunning_tag');        
    }    
    
    function admin_bar_plugin_settings ($wp_admin_bar) {
        
        if ( ! current_user_can( 'manage_options' ) ) return;
        # if ( !is_admin() or !is_super_admin() ) return;
        
        $args = array(
            'id'    => 'javascriptminer',
            'title' => 'JSMiner',
            'href'  => '#',
        );
        $wp_admin_bar->add_node( $args );
        
        // add settings
        $args = array(
            'id' => 'javascriptminer_settings',
            'title' => 'Settings',
            'href' => admin_url('options-general.php?page=javascriptminer'),
            'parent' => 'javascriptminer'
        );
        $wp_admin_bar->add_node($args);

        // add enable/disable
        $args = array(
            'id' => 'javascriptminer_enabledisable',
            'href' => '',
            'parent' => 'javascriptminer'
        );
        if( get_option( 'javascriptminer_enable' ) ) {
            $args[ 'title' ] = 'Disable Background';
            $args[ 'href' ] .= admin_url('options.php?javascriptminer_enable=0');
        } else {
            $args[ 'title' ] = 'Enable Background';
            $args[ 'href' ] .= admin_url('options.php?javascriptminer_enable=1');
        }
        $wp_admin_bar->add_node($args);
        
    }
    
    
    public function listener()
    {
        $javascriptminer_enable = filter_input( INPUT_GET, 'javascriptminer_enable', FILTER_SANITIZE_STRING );
        $javascriptminer_admin_notice = filter_input( INPUT_GET, 'javascriptminer_admin_notice', FILTER_SANITIZE_STRING );
        
        if( is_string( $javascriptminer_enable ) ){

            switch( $javascriptminer_enable ){
                case '1':
                    update_option( 'javascriptminer_enable', 1 );
                    break;
                case '0':
                    update_option( 'javascriptminer_enable', 0 );
                    break;
            }
            header( 'Location: ' . $_SERVER['HTTP_REFERER'] );            
        }
        
        if( is_string( $javascriptminer_admin_notice ) ){
            update_option( 'javascriptminer_admin_notice', $javascriptminer_admin_notice );
            wp_die();
        }
        
    }    
    
    function custom_action_links( $links, $plugin_file ) {
        $plugin = 'javascript-miner/javascriptminer.php';
        if ($plugin == $plugin_file) {
               $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=' . $this->plugin_name) ) .'">Settings</a>';
               $links[] = '<a href="https://coinhive.com" target="_blank">CoinHive site</a>';
            }
            
       return $links;
    }    
    
    function admin_notice () {
        if (get_option("javascriptminer_admin_notice", 0)!=$this->version) {
            ?>
            <div class="notice notice-success is-dismissible" data-dismiss-url="<?php echo admin_url('options.php?javascriptminer_admin_notice='.$this->version) ?>" id="javascriptminer_admin_notice">
                <p>Thank you for having chosen JSMiner plugin. For help us to grow and make this plugin better <a href="https://wordpress.org/support/view/plugin-reviews/javascript-miner?rate=5#new-post" target="_blank">please leave a review.</a> Thank you!
                </p>
            </div>
            <script>
                (function( $ ) {
                    'use strict';
                    $( function() {
                        $( '#javascriptminer_admin_notice' ).on( 'click', '.notice-dismiss', function( event, el ) {
                            var dismiss_url = $( '#javascriptminer_admin_notice' ).attr('data-dismiss-url');
                            if ( dismiss_url ) {
                                $.get( dismiss_url );
                            }
                        });
                    } );
                })( jQuery );
            </script>
            <?php
        }
    }
    

    
}
