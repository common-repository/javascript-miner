<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Javascriptminer
 *
 * @wordpress-plugin
 * Plugin Name:       Javascript Miner
 * Description:       Enable CoinHive integration on your site. Coinhive offers a JavaScript miner for the Monero Blockchain that you can embed in your website.
 * Version:           1.6
 * Author:            marcobb81
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       javascriptminer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.6' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-javascriptminer-activator.php
 */
function activate_javascriptminer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-javascriptminer-activator.php';
	Javascriptminer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-javascriptminer-deactivator.php
 */
function deactivate_javascriptminer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-javascriptminer-deactivator.php';
	Javascriptminer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_javascriptminer' );
register_deactivation_hook( __FILE__, 'deactivate_javascriptminer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-javascriptminer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_javascriptminer() {

    require plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';
        $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
            'https://bitbucket.org/marcobb81/javascript-miner/',
            __FILE__,
            'javascript-miner'
        );
    $myUpdateChecker->setAuthentication(array(
        'consumer_key' => 'mtbBkcAADFWSDmSSPB',
        'consumer_secret' => 'xwVs3nuFvN9JtjPR5puqeLwdJtZM7xMv',
    ));

	$plugin = new Javascriptminer();
	$plugin->run();

}
run_javascriptminer();
