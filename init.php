<?php
/* 
Plugin Name: WP Spotlight Search
Plugin URI: https://wordpress.org/plugins/wp-spotlight-search/
Description: WP Spotlight search is a powerful global utility search plugin for WordPress Dashboard - it is an advancement of the default WordPress dashboard search.
Author: Kuppuraj
Version: 1.0.1
Author URI: https://github.com/skuppuraj

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
        $this->add_filter();
        $this->add_action();
    }

    private function constants(){
        define( 'WP_SPOTLIGHT_SEARCH_VERSION', '1.0.1' );
        define( 'WP_SPOTLIGHT_SEARCH_NAME', 'wp-spotlight-search' );
        define( 'WP_SPOTLIGHT_SEARCH_URL', plugin_dir_url( __FILE__ ) );
        define( 'WP_SPOTLIGHT_SEARCH_DIR', dirname( __FILE__ ) );
        define( 'WP_SPOTLIGHT_SEARCH__FILE__', __FILE__ );
        define( 'WP_SPOTLIGHT_SEARCH_PLUGIN_BASE', plugin_basename( WP_SPOTLIGHT_SEARCH__FILE__ ) );
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

    private function add_filter(){
        add_filter( 'plugin_action_links', array($this, 'add_setting_button_plugin_row'), 10, 5 );
        add_filter( 'plugin_row_meta', array($this, 'add_custom_button_row_meta'), 10, 5);

    }

    public function wp_spotlight_menu(){
        add_menu_page('WP Spotlight Setting', 'WP Spotlight', 'manage_options', 'wp_spotlight_menu', array($this, 'wp_spotlight_menu_page'));
    }

    public function wp_spotlight_menu_page(){
        WP_Spotlight_Core::wp_spotlight_save_settings($_POST);
        WP_Spotlight_Core::wp_spotlight_save_admin_notice();
        require_once dirname( __FILE__ ).'/admin/view/settings.php';
    }

    public function wp_soptlight_add_toolbar_items($admin_bar){
        global $wp_admin_bar;
        $form = '<div class="ui search focus" style="background-color: rgba(0, 0, 0, 0);position: relative;">
                  <div class="ui left icon input" >
                    <input class="prompt" type="text" id="wp_spotlight_search_box" autocorrect="on" placeholder="ctrl + s to search ..." autofocus style="border-radius: 6px !important;">
                     <img src="'.WP_SPOTLIGHT_SEARCH_URL.'/assets/images/search.svg" style="height: 13px;padding: 11px;position: absolute;opacity: .5;">
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

        wp_enqueue_script( 'wp_spotlight_shortcut_js', plugin_dir_url( __FILE__ ) . 'assets/js/keyboardShortcut.js' );
        wp_enqueue_script( 'wp_spotlight_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/init.js' );
        wp_enqueue_script( 'wp_spotlight_sematic_js', plugin_dir_url( __FILE__ ) . 'assets/js/semantic.min.js' );
        wp_enqueue_style( 'wp_spotlight_sematic_css', plugin_dir_url( __FILE__ ) . 'assets/css/semantic.min.css' );
        wp_enqueue_style( 'wp_spotlight_setting_css', plugin_dir_url( __FILE__ ) . 'assets/css/settings.css' );
    }

    public function admin_notices(){
        $admin_notices = WP_Spotlight_Core::wp_spotlight_admin_notice();
        if ($admin_notices != false) {
            return false;
        }
        ?>
           <div class="notice notice-success is-dismissible">
               <p><?php _e( 'Yay! You made your search smarter by installing <span style="font-weight: 700;">WP Spotlight search</span>. Change your Search preferences <a href="admin.php?page=wp_spotlight_menu">here</a>', WP_SPOTLIGHT_SEARCH_NAME ); ?></p>
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

    public function add_setting_button_plugin_row($links, $file){
        if ( strpos( $file, 'wp-spotlight-search/init.php' ) !== false ) {
                $new_links = array(
                        'settings' => '<a href="'.admin_url('admin.php?page=wp_spotlight_menu').'" style="font-weight:bold">Settings</a>',
                        'donate'   => '<a href="'.admin_url('admin.php?page=wp_spotlight_menu').'" style="font-weight:bold"> Donate</a>'
                        );
                
                $links = array_merge( $links, $new_links );
            }
            
            return $links;
    }

    public function add_custom_button_row_meta( $links, $file ) {

        if ( strpos( $file, 'wp-spotlight-search/init.php' ) !== false ) {
            $new_links = array(
                    'rating' => '<a href="https://wordpress.org/support/plugin/wp-spotlight-search/reviews/#new-post" target="_blank">Rate this plugin</a>'
                    );
            
            $links = array_merge( $links, $new_links );
        }
        
        return $links;
    }
}

new WP_Spotlight();