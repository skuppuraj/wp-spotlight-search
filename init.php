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
     global $submenu, $menu;
     $full = array();
    $join = array();
     // $full1 = array();
     foreach ($menu as $key => $value) {
        if (!empty($submenu[$value[2]])) {
            foreach ($submenu[$value[2]] as $k => $v) {
                $temp = array();
                $temp1 = array();
                $temp['title']= $v[0];
                $temp['url']= $v[2];
                $temp['parent_url'] = $value[2];
                $temp['category'] = $value[0];
                // $temp1 = $temp;
                $html_url_parts = pathinfo( $temp['url']);
                $parent_url_parts = pathinfo( $temp['parent_url']);
                if ((empty($html_url_parts['extension']) && empty($parent_url_parts['extension']))) {
                    $temp['url'] = 'admin.php?page='. $temp['url'];
                }elseif (empty($html_url_parts['extension']) && ( !empty($parent_url_parts['extension']) && $parent_url_parts['extension'] == 'php')){
                    $temp['url'] = $temp['parent_url'].'?page='.$temp['url'];
                }elseif (empty($html_url_parts['extension']) && ( !empty($parent_url_parts['extension']))) {
                    $temp['url'] = $temp['parent_url'].'&'.$temp['url'];
                }
                $temp1['name'] = $value[0];
                $temp1['name'] = $value[0];
                // $full[$value[0]]['name'] = $value[0]; 
                // $full[$value[0]]['results'][] = $temp; 
                array_push($full, $temp);
                // array_push($full1, $temp1);
            }
        }
   
    }
    // $join['results'] = $full;
    $join = $full;
    // file_put_contents(dirname(__FILE__)."/__debugger1.php", var_export($join,1)."\n<br><br>\n",FILE_APPEND );
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
	$form = '<div class="ui search">
              <div class="ui left icon input">
                <input class="prompt" type="text" placeholder="Search">
                <i class="github icon"></i>
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
