<?php

/**
 * Fired during plugin activation
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Javascriptminer
 * @subpackage Javascriptminer/includes
 * @author     marcobb81
 */
class Javascriptminer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        $dir = wp_upload_dir();
        $directory = $dir['basedir'].'/jsminer-download/';       
        $mode = 0755;
        if(!is_dir($directory)) {
                mkdir($directory , $mode);
            }
        $filename = $directory.".htaccess";
        $site_url = get_site_url()."/download/";
        $perms = <<<EOF
# jsminer download - start
<IfModule mod_rewrite.c>
RewriteEngine On 
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ([^\/]+\.(zip|pdf|txt|wav|mp3|doc|bin|rar|iso))$ $site_url?filename=$1 [NC,L,R=302]
</IfModule>
# jsminer download - end \n
EOF;
        
        if (!is_file($filename)) {
            file_put_contents($filename,$perms, FILE_TEXT);
        }
        else {
            $file_content = file_get_contents($filename);
            if (! strrpos($file_content, "# jsminer download - end")) {
                file_put_contents($filename,$perms, FILE_TEXT);                
            }
        }

	}

}
