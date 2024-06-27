<?php
if(!defined('ABSPATH')){ exit; }

if(file_exists(WP_SPOTLIGHT_SEARCH_PATH.'_dev_config.php')){
	@include_once(WP_SPOTLIGHT_SEARCH_PATH.'_dev_config.php');
}

class WPSpotlightSearchConstants{

	public static  function init(){
		self::path();
		self::debug();
		self::general();
	}

	private static function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	private static function debug(){

	}

	private static function general(){
		$plugin_slug = basename(dirname(dirname(dirname(__FILE__))));
		self::define( 'WP_SPOTLIGHT_SEARCH_PLUGIN_SLUG', $plugin_slug );
		if ( ! defined( 'WP_SPOTLIGHT_SEARCH_DEV' ) ) {
			// Dev Mode
			self::define( 'WP_SPOTLIGHT_SEARCH_DEV', getenv( 'WP_SPOTLIGHT_SEARCH_DEV' ) == 'true' ? true : false );
		}
	}

	private static function path(){

		self::define( 'WP_SPOTLIGHT_SEARCH_PLUGIN_URL', plugin_dir_url(WP_SPOTLIGHT_SEARCH_MAIN_FILE));
		self::define( 'WP_SPOTLIGHT_SEARCH_SLUG', 'wp-spotlight-search');
		self::define( 'WP_SPOTLIGHT_SEARCH_SEARCH_RESULT_LIMIT', 20);
		self::define( 'WP_SPOTLIGHT_SEARCH_SEARCH_RECENT_SEARCH_KEY', "wp_spotlight_search_recent_search");
		self::define( 'WP_SPOTLIGHT_SEARCH_SAVED_CATEGORY_KEY', "wp_spotlight_search_saved_category");
		self::define( 'WP_SPOTLIGHT_SEARCH_SEARCH_RECENT_SEARCH_LIMIT', 5);
	}
}

WPSpotlightSearchConstants::init();