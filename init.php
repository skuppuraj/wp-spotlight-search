<?php
/* 
Plugin Name: WP Spotlight Search
Plugin URI: http://wpspotlight.com/
Description: You can search the content.
Author: Kuppuraj
Version: 1.0.0
Author URI: http://kuppurajs.com

*/

define('WP_SPOTLITE_SEARCH_NAME', 'wp-spotlite-search');
if (!function_exists('debug_admin_menus')):
function debug_admin_menus() {
    global $submenu, $menu, $pagenow, $kuppu, $skuppu;

}
add_action( 'admin_footer', 'debug_admin_menus' );
endif;

add_action('wp_before_admin_bar_render', 'wp_soptlite_add_toolbar_items', 999999999);
// add_action('admin_init', 'wp_soptlite_admin_init', 999999999);
add_action('admin_head', 'printConnectionModalOpenScript');
function printConnectionModalOpenScript(){
    global $submenu, $menu, $wp_admin_bar, $wpdb;
    $post_types = get_post_types('', 'object');
    $all_post_types = array();
    $available_post_types = array();
    foreach ($post_types as $key => $post) {
        if ($key == 'attachment' || ($post->show_in_menu == false && $post->public == false)) {
            continue;
        }
        $post_temp = array();
        if ($key == 'shop_order') {
            $post_content = $wpdb->get_results("select ID,post_title,post_type from $wpdb->posts where post_type = '$key'", ARRAY_A);
        }else{
            $post_content = $wpdb->get_results("select ID,post_title,post_type from $wpdb->posts where post_status='publish' AND post_type = '$key'", ARRAY_A);   
        }
        foreach ($post_content as $resultKey => $content) {
            $post_temp['type'] = $key;
            $post_temp['category'] = $post->label;
            if ($key == 'shop_order') {
                $post_temp['title'] = $content['ID'];                
            }else{
                $post_temp['title'] = $content['post_title'];
            }
            $post_temp['url']= 'post.php?post='.$content['ID'].'&action=edit';
            array_push($all_post_types, $post_temp);
        }

    }
    $full = array();
    $join = array();
    foreach ($menu as $key => $value) {
        $home_url_part = pathinfo( $value[2]);
        if (!empty($submenu[$value[2]])) {
            foreach ($submenu[$value[2]] as $k => $v) {
                $temp = array();
                $temp['title']= $v[0];
                $temp['url']= $v[2];
                $temp['parent_url'] = $value[2];
                $temp['category'] = $value[0];
                $html_url_parts = pathinfo( $temp['url']);
                $parent_url_parts = pathinfo( $temp['parent_url']);
                if ((empty($html_url_parts['extension']) && empty($parent_url_parts['extension']))) {
                    $temp['url'] = 'admin.php?page='. $temp['url'];
                }elseif (empty($html_url_parts['extension']) && ( !empty($parent_url_parts['extension']) && $parent_url_parts['extension'] == 'php')){
                    $temp['url'] = $temp['parent_url'].'?page='.$temp['url'];
                }elseif (empty($html_url_parts['extension']) && ( !empty($parent_url_parts['extension']))) {
                    $temp['url'] = $temp['parent_url'].'&'.$temp['url'];
                }
                array_push($full, $temp);
            }
        }elseif(!empty($value[0])){
            $temp = array();
            $temp['title']= $value[0];
            if (empty($parent_url_parts['extension'])) {
                $temp['url'] = 'admin.php?page='. $value[2];  
            }else{
                $temp['url']= $value[2];
            }
            $temp['category'] = $value[0];
            array_push($full, $temp);
        }
   
    }
    $join = $full;
    $join = array_merge($join,$all_post_types);
    ob_start()
    ?>
    <script type="text/javascript">
        var wp_spotlite_menu = <?php echo json_encode($menu)?>;
        var wp_spotlite_submenu = <?php echo json_encode($submenu)?>;
        var wp_spotlite_full_menu = <?php echo json_encode($join);?>;
    </script>
    <?php

    $content = ob_get_clean();
    print $content;
}
function wp_soptlite_add_toolbar_items($admin_bar){
    global $wp_admin_bar;
	$form = '<div class="ui search focus" style="background-color: rgba(0, 0, 0, 0);position: relative;">
              <div class="ui left icon input" >
                <input class="prompt" type="text" placeholder="Search">
              </div>
            </div>
            ';
    $wp_admin_bar->add_menu( array(
        'id'    => 'wp-spotlite-search',
        'title' => $form,
        'meta'  => array(
            'title' => __('WP Spotlight')            
        ),
    ));
   
}

function wp_spotlite_enqueue($hook) {

    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/init.js' );
    wp_enqueue_script( 'my_sematic_js', plugin_dir_url( __FILE__ ) . 'assets/js/semantic.min.js' );
    wp_enqueue_style( 'my_sematic_css', plugin_dir_url( __FILE__ ) . 'assets/css/semantic.min.css' );
    wp_enqueue_style( 'wp_spotlite_setting_css', plugin_dir_url( __FILE__ ) . 'assets/css/settings.css' );
    wp_localize_script( 'my_custom_script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'admin_enqueue_scripts', 'wp_spotlite_enqueue' );

add_action('admin_menu', 'wp_spotlite_menu');

function wp_spotlite_menu(){
    add_menu_page('WP Spotlight Setting', 'WP Spotlight', 'manage_options', 'wp_spotlite_menu', 'wp_spotlite_menu_page');
}

function wp_spotlite_menu_page(){
    wp_spotlite_save_settings($_POST);
    require_once dirname( __FILE__ ).'/admin/view/settings.php';
}

function get_searchabel_post_types(){
    $post_types = get_post_types('', 'object');
    $searchabel_post_type = array();
    foreach ($post_types as $key => $post) {
        if ($key == 'attachment' || ($post->show_in_menu == false && $post->public == false)) {
            continue;
        }
        $post_tmep = array();
        $post_tmep['type'] = $key;
        $post_tmep['label'] = $post->label;
        array_push($searchabel_post_type, $post_tmep);
    }
    return $searchabel_post_type;
}

function get_searchabel_post_types_checkbox(){
    $searchabel_post_type = get_searchabel_post_types();
    $response = '';
    $wp_spotlite_settings = wp_spotlite_search_include_options();
    foreach ($searchabel_post_type as $key => $value) {
        $type = $value['type'];
        $label = $value['label'];
        $selected = '';
        if ($wp_spotlite_settings != false && in_array($type, $wp_spotlite_settings)) {
            $selected = 'checked';
        }
        $response .= "<label class='wp-spotlite-settings-checkbox'> <input type='checkbox' value='".$type."' name='search_include_options[]' $selected /> $label </label> <br>";
    }

    return $response;
}

function wp_spotlite_save_settings($data){
    if (empty($data['search_include_options'])) {
        return false;
    }
    $settings['search_include_options'] = $data['search_include_options'];
    update_option('wp_spotlite_setting', serialize($settings));
}

function wp_spotlite_get_settings(){
    $wp_spotlite_setting = get_option('wp_spotlite_setting');
    if (empty($wp_spotlite_setting)) {
        return false;
    }

    return unserialize($wp_spotlite_setting);

}

function wp_spotlite_search_include_options(){
    $wp_spotlite_setting = wp_spotlite_get_settings();
    if(empty($wp_spotlite_setting['search_include_options'])) {
        return false;
    }
    return $wp_spotlite_setting['search_include_options'];
}