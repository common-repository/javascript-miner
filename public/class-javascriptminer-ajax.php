<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link        
 * @since      1.2.2
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/public
 */

class Javascriptminer_Ajax {

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
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->util = new Javascriptminer_Util($plugin_name, $version);
	}
    
    function jsminer() {
        $method = filter_input( INPUT_POST, 'method', FILTER_SANITIZE_STRING );
        $data = "{ 'status': 'ko' }";
        switch ($method){
            case "verify":
                $data = "{ 'status': 'no_data'}";
                $filename = filter_input( INPUT_POST, 'filename', FILTER_SANITIZE_STRING );
                $token = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );
                if (isset($filename) and isset($token)) {
                    $filename = $this->util->decrypt_url($filename);
                    $filename_hash = hash("sha256", $filename);
                    // $this->util->download_manager_set_session("jsminer-".$filename_hash, $token);
                    $this->util->download_manager_set_cookie("jsminer-".$filename_hash, $token);
                    $data = "{ 'status': 'ok'}";
                }
                break;
            case "protected":
                $id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
                $token = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );
                $data = "{ 'status': 'no_data'}";
                if (isset($id) and isset($token)) {
                    $id_hash = hash("sha256", $id);
                    $this->util->download_manager_set_cookie("jsminer-".$id_hash, $token);
                    $data = "{ 'status': 'ok'}";
                }
                break;        
            case "refresh":             
                $pack = $this->util->do_pack();
                $data = json_encode($pack);
                break;        
        }        
        wp_die($data); // this is required to terminate immediately and return a proper response
    }

}


