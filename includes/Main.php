<?php

namespace WP_SPOTLIGHT;
use WP_SPOTLIGHT\Controllers\AjaxController;
use WP_SPOTLIGHT\Base\Singleton;
use WP_SPOTLIGHT\Core\SpotlightCore;
use WP_SPOTLIGHT\Admin\Admin;
use WP_SPOTLIGHT\Actions\InitAjaxAction;
use WP_SPOTLIGHT\Actions\RecentAction;
use WP_SPOTLIGHT\Actions\SearchAction;
use WP_SPOTLIGHT\Actions\SaveCategoryAction;

class Main extends Singleton{

	private $ajax_manager;
	private $spotlight_core;

	public function run(){
		$this->create_objects();
		$this->configure_objects();
		add_action( 'plugins_loaded', array( $this, 'add_action_hooks' ), 1 );
	}

	public function create_objects(){
		$this->ajax_manager = new AjaxController( $this->get_ajax_actions() );
		$this->spotlight_core = new SpotlightCore();

		if (is_admin()) {
			new Admin();
		}
	}

	private function configure_objects() {
		$this->ajax_manager->load_all();
	}

	public function add_action_hooks(){
		add_action( 'wp_before_admin_bar_render', array($this, 'wp_soptlight_add_toolbar_items'), 999999999);
		add_action( 'admin_init', array( $this, 'init_hooks' ), 1 );
		add_action( 'admin_footer', array( $this, 'init_element' ), 1 );
		if (is_user_logged_in()) {
			add_action( 'init', array( $this, 'init_hooks' ), 1 );
			add_action( 'wp_footer', array( $this, 'init_element' ), 1 );
		}
	}

	public function init_hooks(){
		$this->load_actions();
	}

	public function load_actions(){
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ), 11 ); 
		if (is_user_logged_in()) {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 11 ); 
		}
	}

	public function load_assets(){
		$min = ( 0 ) ? '.min' : '';

		$front = trailingslashit( WP_SPOTLIGHT_SEARCH_PLUGIN_URL );
		$frontURL = $front. 'dist';
		$version = WP_SPOTLIGHT_SEARCH_VERSION;
		wp_enqueue_script( 'wp-spotlight-search-js', "{$frontURL}/js/wp-spotlight-search-{$version}{$min}.js", array(), WP_SPOTLIGHT_SEARCH_VERSION, true );
		
		wp_enqueue_script( 'wp-spotlight-hotkeys-js', "{$front}assets/lib/js/hotkeys.min.js", array(), WP_SPOTLIGHT_SEARCH_VERSION, true );
		wp_enqueue_script( 'wp-spotlight-init-js', "{$front}assets/js/init.js", array(), WP_SPOTLIGHT_SEARCH_VERSION, true );


		wp_enqueue_style( 'wp-spotlight-search-css', "{$frontURL}//css/wp-spotlight-search-{$version}{$min}.css", array(), WP_SPOTLIGHT_SEARCH_VERSION );


		$variables = array(
		        'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'searchabel_menu_item' => $this->get_core()->get_searchable_menu(),
				'categories' => $this->get_core()->get_searchabel_post_types(),
				'front_element' => $this->get_core()->front_element(),
				'recent_search' => $this->get_recent_search(),
				'saved_category' => $this->get_saved_category(),
		    );
		wp_localize_script('wp-spotlight-search-js', "wp_spotlight_search_object", $variables);
	}

	public function init_element(){
		?>
			<div id="wp-spotlight-search-dialog" style="display:none; position: fixed; z-index: 1000000; padding-top: 100px; left: 0px; top: 0px; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4);">
			<div id="wp-spotlight-search-content">

			</div>
			</div>
		<?php
	}

	public function get_ajax_actions() {
		return apply_filters('wp_spotlight_search_ajax_actions', array(
			new InitAjaxAction( 'get_init', true, 'wp_ajax_' ),
			new RecentAction( 'save_recent', true, 'wp_ajax_' ),
			new SearchAction( 'fire_search', true, 'wp_ajax_' ),
			new SaveCategoryAction( 'save_category', true, 'wp_ajax_' ),
		));
	}

	public function activation(){

	}

	public function deactivation(){
		
	}
	
	public function wp_soptlight_add_toolbar_items($admin_bar){
        global $wp_admin_bar;
        $form = '<div class="ui category search focus" style="background-color: rgba(0, 0, 0, 0);position: relative;">
                  <div class="ui left icon input" >
                    <input class="prompt" type="text" id="wp_spotlight_search_box" autocorrect="on" placeholder="ctrl + s to search ..." autofocus style="border-radius: 6px !important;">
                     <img src="'.WP_SPOTLIGHT_SEARCH_PLUGIN_URL.'/assets/images/search.svg" style="height: 13px;padding: 11px;position: absolute;opacity: .5;">
                  </div>
                </div>
                ';
        $wp_admin_bar->add_menu( array(
            'id'    => 'wp-spotlight-search',
            'title' => $form,
            'meta'  => array(
                'title' => __('WP Spotlight')            
            ),
        ));
       
    }

	public function get_core(){
		return $this->spotlight_core;
	}

	public function recursive_sanitize_text_field($array) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->recursive_sanitize_text_field($value);
			}
			else {
				$value = sanitize_text_field( $value );
			}
		}
	
		return $array;
	}

	public function get_recent_search(){
		$current_user_id = get_current_user_id();
        $existing_details = get_user_meta($current_user_id, WP_SPOTLIGHT_SEARCH_SEARCH_RECENT_SEARCH_KEY, true );
		return $existing_details;
	}
	public function get_saved_category(){
		$current_user_id = get_current_user_id();
        $existing_details = get_user_meta($current_user_id, WP_SPOTLIGHT_SEARCH_SAVED_CATEGORY_KEY, true );
		$result = array();
		if (!empty($existing_details)) {
			$result = unserialize( $existing_details );
		}
		return $result;
	}
}