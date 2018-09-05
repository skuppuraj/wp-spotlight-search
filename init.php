<?php
/* 
Plugin Name: WP Spotlight Search
Plugin URI: http://wpspotlight.com/
Description: You can search the content.
Author: Kuppuraj
Version: 1.0.0
Author URI: http://kuppurajs.com

*/


if (!function_exists('debug_admin_menus')):
function debug_admin_menus() {
    global $submenu, $menu, $pagenow, $kuppu, $skuppu;

}
add_action( 'admin_footer', 'debug_admin_menus' );
endif;

add_action('admin_bar_menu', 'add_toolbar_items', 9999);
add_action('admin_head', 'printConnectionModalOpenScript');
function printConnectionModalOpenScript(){
    global $submenu, $menu, $wp_admin_bar, $wpdb;
    $post_types = get_post_types('', 'object');
    $all_post_types = array();
    $available_post_types = array();
    foreach ($post_types as $key => $post) {
        if ($key == 'attachment' || $post->show_in_menu == false) {
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
function add_toolbar_items($admin_bar){
	$form = '<div class="ui search focus">
              <div class="ui left icon input">
                <input class="prompt" type="text" placeholder="Search">
              </div>
            </div>
            ';
    $admin_bar->add_menu( array(
        'id'    => 'my-item',
        'title' => $form,
        'meta'  => array(
            'title' => __('My Item'),            
        ),
    ));
   
}

function my_enqueue($hook) {

    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'js/init.js' );
    wp_enqueue_script( 'my_sematic_js', plugin_dir_url( __FILE__ ) . 'js/semantic.min.js' );
    wp_enqueue_style( 'my_sematic_css', plugin_dir_url( __FILE__ ) . 'css/semantic.min.css' );
    wp_localize_script( 'my_custom_script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );

add_action( 'wp_ajax_post_love_add_love', 'post_love_add_love' );

function post_love_add_love() {
	global $submenu, $menu, $pagenow;

	$t = array('total_count' => 1, 'incomplete_results' => false, 'items' => array('name'=> 'dddd', 'html_url' => 'https://github.com/SimplesGroup'));
	$t = json_encode($t);
	$full = array();
	foreach ($menu as $key => $value) {
    	if (!empty($submenu[$value[2]])) {
    		foreach ($submenu[$value[2]] as $k => $v) {
    			$temp['name']= $v[0];
    			$temp['html_url']= $v[2];
    			array_push($full, $temp);
    		}
    	}
   
    }

// 	$t ='{
//   "items": [
//     {
//       "name": "kuppurajs.com",
//       "private": false,
//       "html_url": "https://github.com/skuppuraj/kuppurajs.com"
//     }
//   ]
// }
// ';

file_put_contents(dirname(__FILE__)."/__debugger1.php", var_export($full,1)."\n<br><br>\n",FILE_APPEND );
	echo $t;
	wp_die(); 

}
