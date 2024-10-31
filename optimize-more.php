<?php
	
/**
* Plugin Name: Optimize More! 
* Description: A lightweight but powerful ‘Do It Yourself’ WordPress Page Speed Optimization Pack.
* Author: Arya Dhiratara
* Author URI: https://dhiratara.com/
* Version: 2.0.3
* Requires at least: 5.8
* Requires PHP: 7.4
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: opm
*/

namespace OptimizeMore;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('OPTIMIZEMORE_NAME', 'Optimize More!');
define('OPTIMIZEMORE_DESCRIPTION', 'A \'Do It Yourself\' WordPress Page Speed Optimization Pack.');
define('OPTIMIZEMORE_PLUGIN_NAME', 'Main Plugin');
define('OPTIMIZEMORE_SLUG', 'optimize-more');
define('OPTIMIZEMORE_VERSION', '2.0.3');
define("OPTIMIZEMORE_DIR", plugin_dir_path(__FILE__));
define("OPTIMIZEMORE_URL", plugin_dir_url(__FILE__));
define("OPTIMIZEMORE_ASSETS_URL", plugin_dir_url(__FILE__) . 'assets/');
define("OPTIMIZEMORE_PUBLIC_URL", plugin_dir_url(__FILE__) . 'public/');
define("OPTIMIZEMORE_CLASSES_DIR", plugin_dir_path(__FILE__) . 'includes/classes/');
define("OPTIMIZEMORE_FUNCTIONS_URL", plugin_dir_url(__FILE__) . 'includes/functions/');
define("OPTIMIZEMORE_FUNCTIONS_DIR", plugin_dir_path(__FILE__) . 'includes/functions/');
define("OPTIMIZEMORE_LIBRARY_DIR", plugin_dir_path(__FILE__) . 'vendor/');
define("OPTIMIZEMORE_BASENAME", plugin_basename(__FILE__));
define("OPTIMIZEMORE_ASSETS_DIR", OPTIMIZEMORE_DIR . 'assets/');
define("OPTIMIZEMORE_CUSTOM_DIR", OPTIMIZEMORE_DIR . 'custom/');
define("OPTIMIZEMORE_CUSTOM_URL", plugin_dir_url(__FILE__) . 'custom/');

// include the required plugin files
include_once(OPTIMIZEMORE_DIR . 'includes/classes/Loader.php');
include_once(OPTIMIZEMORE_DIR . 'includes/Functions.php');


global $opm_instance;

function opm_instance() {
    global $opm_instance;
    $opm_instance = opmPluginLoader::getInstance();
    return $opm_instance;
}

opm_instance();

spl_autoload_register('OptimizeMore\opm_autoloader');
function opm_autoloader($class) {
    $class = str_replace('OptimizeMore\\', '', $class);
    $file = OPTIMIZEMORE_CLASSES_DIR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

register_activation_hook(__FILE__, 'OptimizeMore\opm_plugin_activate');
function opm_plugin_activate() {
    add_option('opm_do_activation_redirect', true);
}

add_action('admin_init', 'OptimizeMore\opm_activation_redirect');
function opm_activation_redirect() {
	// If plugin is activated in network admin options or on a multisite, skip redirect.
    if (is_network_admin() || is_multisite()) {
        return;
    }
	if (get_option('opm_do_activation_redirect', false)) {
		delete_option('opm_do_activation_redirect');
		// If plugin is activated using the bulk action, skip redirect.
		if(!isset($_GET['activate-multi'])) {
			exit( wp_redirect( admin_url( 'admin.php?page=optimize-more' ) ) );
		}		
	}
}