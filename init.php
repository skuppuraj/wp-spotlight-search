<?php
/* 
Plugin Name: WP Spotlight Search
Plugin URI: http://wpspotlight.com/
Description: You can search the content.
Author: Kuppuraj
Version: 1.0.0
Author URI: http://kuppurajs.com

*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

class WP_Spotlight {

    public function __construct(){
        $this->init();
    }

    private function init(){
        $this->constants();
        $this->hooks();
        $this->add_action();
    }

    private function constants(){
        define( 'WP_SPOTLIGHT_SEARCH_VERSION', '1.0.0' );
        define( 'WP_SPOTLIGHT_SEARCH_NAME', 'wp-spotlight' );
        define( 'WP_SPOTLIGHT_SEARCH_URL', plugin_dir_url( __FILE__ ) );
        define( 'WP_SPOTLIGHT_SEARCH_DIR', dirname( __FILE__ ) );
        define( 'WP_SPOTLIGHT_SEARCH__FILE__', __FILE__ );
        define( 'WP_SPOTLIGHT_SEARCH_PLUGIN_BASE', plugin_basename( E4E_PLUGIN__FILE__ ) );
    }

    private function hooks(){

        require_once WP_SPOTLIGHT_SEARCH_DIR . '/includes/core.php';

        register_deactivation_hook( __FILE__, array($this, 'deactivate') );

    }

    public function deactivate() {

    }

    private function add_action(){
        add_action( 'admin_notices', array($this, 'admin_notices') );
        add_action( 'admin_menu', array($this, 'wp_spotlight_menu'));
        add_action( 'admin_enqueue_scripts', array($this, 'wp_spotlight_enqueue') );
        add_action( 'wp_before_admin_bar_render', array($this, 'wp_soptlight_add_toolbar_items'), 999999999);
        add_action( 'admin_footer', array($this, 'send_source_to_admin'), 999999999);
    }

    public function wp_spotlight_menu(){
        add_menu_page('WP Spotlight Setting', 'WP Spotlight', 'manage_options', 'wp_spotlight_menu', array($this, 'wp_spotlight_menu_page'));
    }

    public function wp_spotlight_menu_page(){
        WP_Spotlight_Core::wp_spotlight_save_settings($_POST);
        require_once dirname( __FILE__ ).'/admin/view/settings.php';
    }

    public function wp_soptlight_add_toolbar_items($admin_bar){
        global $wp_admin_bar;
        $form = '<div class="ui search focus" style="background-color: rgba(0, 0, 0, 0);position: relative;">
                  <div class="ui left icon input" >
                    <input class="prompt" type="text" accesskey="s" autocorrect="on" placeholder="ctrl + alt + s to search ..." autofocus style="border-radius: 6px !important;">
                    <i class="search icon" style="font-family: FontAwesome;cursor: default;position: absolute;line-height: 1;text-align: center;top: 0;right: 0;margin: 0;height: 100%;width: 2.67142857em;opacity: .5;border-radius: 0 .28571429rem .28571429rem 0;-webkit-transition: opacity .3s ease;transition: opacity .3s ease;background-color: rgba(0, 0, 0, 0);"></i>
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
    public function wp_spotlight_enqueue($hook) {

        wp_enqueue_script( 'wp_spotlight_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/init.js' );
        wp_enqueue_script( 'wp_spotlight_sematic_js', plugin_dir_url( __FILE__ ) . 'assets/js/semantic.min.js' );
        wp_enqueue_style( 'wp_spotlight_sematic_css', plugin_dir_url( __FILE__ ) . 'assets/css/semantic.min.css' );
        wp_enqueue_style( 'wp_spotlight_setting_css', plugin_dir_url( __FILE__ ) . 'assets/css/settings.css' );
    }

    public function admin_notices(){
        $settings = WP_Spotlight_Core::wp_spotlight_get_settings();
        if ($settings != false) {
            return false;
        }
        ?>
           <div class="notice notice-success is-dismissible">
               <p><?php _e( 'Thank you for installing the WP Spotlight Search! you can modify the search option <a href="admin.php?page=wp_spotlight_menu_page">here</a>', WP_SPOTLIGHT_SEARCH_NAME ); ?></p>
           </div>
           <?php
    }

    public function send_source_to_admin(){
        $data = WP_Spotlight_Core::get_search_content();

        ob_start()
        ?>
        <script type="text/javascript">
            var wp_spotlight_full_menu = <?php echo json_encode($data)?>;
        </script>
        <?php

        $content = ob_get_clean();
        print $content;
    }
}

new WP_Spotlight();