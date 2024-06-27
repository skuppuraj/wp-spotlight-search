<?php
/* 
Plugin Name: WP Spotlight Search
Plugin URI: https://wordpress.org/plugins/wp-spotlight-search/
Description: WP Spotlight search is a powerful global utility search plugin for WordPress Dashboard - it is an advancement of the default WordPress dashboard search.
Author: Kuppuraj
Version: 2.0.0
Author URI: https://github.com/skuppuraj

*/
if(!defined( 'ABSPATH' )){ exit;}

define( 'WP_SPOTLIGHT_SEARCH_VERSION', '2.0.0' );
define( 'WP_SPOTLIGHT_SEARCH_MAIN_FILE', __FILE__ );
define( 'WP_SPOTLIGHT_SEARCH_PATH', plugin_dir_path( WP_SPOTLIGHT_SEARCH_MAIN_FILE ) );

use WP_SPOTLIGHT\Main;

require_once WP_SPOTLIGHT_SEARCH_PATH . 'vendor/autoload.php';
require_once WP_SPOTLIGHT_SEARCH_PATH . 'resources/php/constants.php';

function wp_spotlight_plugin_init() {

	global $wp_spotlight;

	$wp_spotlight = Main::instance();
    $wp_spotlight->run();
}

function wp_spotlight_get_main() {
	return Main::instance();
}

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/
wp_spotlight_plugin_init();
// Use the global instance
global $wp_spotlight;

/**
 * Activation hook
 */
register_activation_hook( __FILE__, array( $wp_spotlight, 'activation' ) );

/**
 * Deactivation hook
 */
register_deactivation_hook( __FILE__, array( $wp_spotlight, 'deactivation' ) );


