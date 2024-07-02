<?php
namespace WP_SPOTLIGHT\Core;
use WP_SPOTLIGHT\Module\Module;
class SpotlightCore{
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		add_filter('wp_spotlight_search_init_inject_search', array($this, 'get_searchable_post'), 10, 1);
		add_filter('wp_spotlight_search_init_inject_search', array($this, 'get_users'), 11, 1);
		add_filter('wp_spotlight_search_init_inject_search', array($this, 'get_comments'), 12, 1);
	}

    public function init_search(){
        $out = array();
        $out['categories'] = $this->get_searchabel_post_types();
        $out['post_types'] = apply_filters("wp_spotlight_search_init_inject_search", array());
        return $out;
    }
	
    public function get_searchabel_post_types(){
        $searchabel_post_type = array();
        $searchabel_post_type[] = array('type'=>'menu', 'label' => 'Menus');
	    $post_types = $this->get_post_types();
	    foreach ($post_types as $key => $post) { 
            if ($key == 'attachment' || ($post->show_in_menu == false && $post->public == false)) {
                continue;
	        }
	        $post_tmep = array();
	        $post_tmep['type'] = $key;
	        $post_tmep['label'] = $post->label;
	        array_push($searchabel_post_type, $post_tmep);
	    }
        $searchabel_post_type[] = array('type'=>'users', 'label' => 'Users');
        $searchabel_post_type[] = array('type'=>'comments', 'label' => 'Comments');
	    return $searchabel_post_type;
	}

    public function get_searchable_menu(){
		global $submenu, $menu;
		$full_array = array();
		$full_array = $this->menu_structure($menu, $submenu);
		return $full_array;
	}

    private function menu_structure( $menu, $submenu, $submenu_as_parent = true ) {
		$full_array = array();
		$first = true;
		if (empty($menu)) {
			return $this->get_menus();
		}
		// 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes, 5 = hookname, 6 = icon_url
		foreach ( $menu as $key => $item ) {
			if ($item[0] == '') {
				continue;
			}

			$submenu_items = array();
			if ( ! empty( $submenu[ $item[2] ] ) ) {
				$submenu_items = $submenu[ $item[2] ];
			}

			$title = wptexturize( $item[0] );
			$temp = array();
			$temp['title']= strip_tags($title);
            $temp['parent'] = strip_tags($item[0]);
            $temp['category'] = "menu";
            $temp['type'] = "menu";
			if ( $submenu_as_parent && ! empty( $submenu_items ) ) {
				$submenu_items = array_values( $submenu_items );  // Re-index.
				$menu_hook     = get_plugin_page_hook( $submenu_items[0][2], $item[2] );
				$menu_file     = $submenu_items[0][2];
				$pos           = strpos( $menu_file, '?' );
				if ( false !== $pos ) {
					$menu_file = substr( $menu_file, 0, $pos );
				}
				if ( ! empty( $menu_hook ) || ( ( 'index.php' != $submenu_items[0][2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
	            	$temp['url']= 'admin.php?page='.$submenu_items[0][2];
					$full_array [] = $temp;
				} else {
	            	$temp['url']= $submenu_items[0][2];
					$full_array [] = $temp;
				}
			} elseif ( ! empty( $item[2] ) && current_user_can( $item[1] ) ) {
				$menu_hook = get_plugin_page_hook( $item[2], 'admin.php' );
				$menu_file = $item[2];
				$pos       = strpos( $menu_file, '?' );
				if ( false !== $pos ) {
					$menu_file = substr( $menu_file, 0, $pos );
				}
				if ( ! empty( $menu_hook ) || ( ( 'index.php' != $item[2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
					$temp['url']= 'admin.php?page='.$item[2];
					$full_array [] = $temp;
				} else {
					$temp['url']= $item[2];
					$full_array [] = $temp;
				}
			}

			if ( ! empty( $submenu_items ) ) {

				$first = true;

				foreach ( $submenu_items as $sub_key => $sub_item ) {
				// 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes

					$menu_file = $item[2];

					$pos = strpos( $menu_file, '?' );
					if ( false !== $pos ) {
						$menu_file = substr( $menu_file, 0, $pos );
					}

					$menu_hook = get_plugin_page_hook( $sub_item[2], $item[2] );
					$sub_file  = $sub_item[2];
					$pos       = strpos( $sub_file, '?' );
					if ( false !== $pos ) {
						$sub_file = substr( $sub_file, 0, $pos );
					}

					$title = wptexturize( $sub_item[0] );
					$temp = array();
					$temp['title']= strip_tags($title);
		            $temp['parent'] = strip_tags($item[0]);
		            $temp['category'] = "menu";
		            $temp['type'] = "menu";

					if ( ! empty( $menu_hook ) || ( ( 'index.php' != $sub_item[2] ) && file_exists( WP_PLUGIN_DIR . "/$sub_file" ) && ! file_exists( ABSPATH . "/wp-admin/$sub_file" ) ) ) {
						// If admin.php is the current page or if the parent exists as a file in the plugins or admin dir
						if ( ( file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! is_dir( WP_PLUGIN_DIR . "/{$item[2]}" ) ) || file_exists( $menu_file ) ) {
							$sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), $item[2] );
						} else {
							$sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), 'admin.php' );
						}

						$sub_item_url = esc_url( $sub_item_url );
						$temp['url']= $sub_item_url;
						$full_array [] = $temp;
					} else {
						$temp['url']= $sub_item[2];
						$full_array [] = $temp;
					}
				}
			}
		}
		$this->save_menus($full_array);
		return $full_array;
	}

	public function get_searchable_post( $all_post_types = array(), $limit = WP_SPOTLIGHT_SEARCH_SEARCH_RESULT_LIMIT, $category = 'all'){
		global $wpdb;
		$post_types = $this->get_post_types();
		foreach ($post_types as $key => $post) {
		    if (($category != "all" && $key != $category) || $key == 'attachment' || ($post->show_in_menu == false && $post->public == false)) {
		        continue;
		    }
		    $post_temp = array();
		    if ($key == 'shop_order') {
		        $all_post_types = $this->get_shop_orders($all_post_types, $key, $limit);
		    }else{
		        $post_content = $wpdb->get_results("select ID,post_title,post_type from $wpdb->posts where post_status='publish' AND post_type = '".esc_attr($key)."' LIMIT ".$limit, ARRAY_A);   
				foreach ($post_content as $resultKey => $content) {
					$post_temp['type'] = $key;
					$post_temp['category'] = $post->label;
					$post_temp['ID'] = $content['ID']; 
					if (!empty($content['post_title'])) {
						$post_temp['title'] = $content['post_title']; 
					}
					if ($key == 'shop_order') {
						$meta = get_post_meta($content['ID']);
						$_order_currency = $meta['_order_currency'][0];
						$_order_total = $meta['_order_total'][0];
						$post_temp['price'] = $_order_currency.' '.$_order_total;
					}elseif($key == 'product'){
						$meta = get_post_meta($content['ID']);
						$_price = $meta['_price'][0];
						$currency = get_option('woocommerce_currency');
						$post_temp['price'] = $currency.' '.$_price;
	
					}else{
						$meta = $this->get_post_meta( $content['ID'], $key );
						if ( $meta != '' ) {
							$post_temp['description'] = $meta;
						}
					}
					if ($key == 'elementor_library') {
						$post_temp['url']= 'post.php?post='.$content['ID'].'&action=elementor';
					}else{
						$post_temp['url']= 'post.php?post='.$content['ID'].'&action=edit';
					}
					array_push($all_post_types, $post_temp);
				}
		    }

		}
		return $all_post_types;
	}

	public function get_post_meta( $id, $type ){
		$li_html = '';
		$keys = get_post_custom_keys( $id );
		if ( $keys && in_array($type, array('post','page'))) {
			foreach ( (array) $keys as $key ) {
				$keyt = trim( $key );
				if ( is_protected_meta( $keyt, 'post' ) ) {
					continue;
				}

				$values = array_map( 'trim', get_post_custom_values( $key, $id ) );
				$value  = implode( ', ', $values );

				$html = sprintf(
					"%s %s\n",
					/* translators: %s: Post custom field name. */
					sprintf( _x( '%s=', 'Post custom field name' ), $key ),
					$value
				);
				
				$li_html .= $html;
			}
			if ( $li_html ) {
				$li_html = 'Meta values: '.$li_html;
			}
		}

		return $li_html;
	}

	public function get_users($user_results){
		$options = array("orderby"=> 'ID', 'order' => "DESC", "number"=> WP_SPOTLIGHT_SEARCH_SEARCH_RESULT_LIMIT );
		$users = get_users($options);
		foreach ($users as $key => $value) {
			$user_temp = array();
			$user_temp['ID'] = $value->data->ID;
			$meta = get_user_meta($value->data->ID);
			$user_temp['title'] = $value->data->user_login;
			$user_temp['category'] = 'Users';
			$user_temp['type'] = 'users';
			$user_temp['parent'] = 'users';
			$user_temp['url'] = add_query_arg( 'user_id', $value->data->ID, self_admin_url( 'user-edit.php' ) );
			$user_temp['more'] = array();
			$user_temp['more'][] = array('actions'=> array(
				array('title' => 'Edit', 'url' => $user_temp['url'] ),
				array('title' => 'View', 'url' => get_author_posts_url( $value->data->ID ) ),
			));
			$user_temp['more'][] = array('title'=> 'Role', 'value'=> $value->roles[0]);
			$user_temp['more'][] = array('title'=> 'Email', 'value'=> $value->data->user_email);
			$user_temp['more'][] = array('title'=> 'Display name', 'value'=> $value->data->display_name);
			$user_temp['more'][] = array('title'=> 'Last name', 'value'=> $meta['last_name'][0]);
			$user_temp['more'][] = array('title'=> 'First name', 'value'=> $meta['first_name'][0]);
			array_push($user_results, $user_temp);
		}
		return $user_results;
	}

	public function get_comments($comment_results){
		$options = array("orderby"=> 'ID', 'order' => "DESC", "number"=> WP_SPOTLIGHT_SEARCH_SEARCH_RESULT_LIMIT );
		$comments = get_comments($options);
		foreach ($comments as $key => $value) {
			$comment_temp = array();
			$comment_temp['ID'] = $value->comment_ID;
			$comment_temp['title'] = $value->comment_content;
			$comment_temp['email'] = $value->comment_author_email;
			$comment_temp['category'] = 'Comments';
			$comment_temp['type'] = 'comments';
			$comment_temp['parent'] = 'comments';
			$comment_temp['url'] = 'comment.php?action=editcomment&c='.$value->comment_ID;
			array_push($comment_results, $comment_temp);
		}
		
		return $comment_results;
	}

	public function front_element() {
		$elements = array();
		$elements['search_place_holder'] = esc_html__( 'Search for anything', 'wp-spotlight-search' );
		$elements['help_text_cmd'] = esc_html__( 'or enter / for command list', 'wp-spotlight-search' );
		$elements['help_text_navigation'] = esc_html__( 'Navigate using ↑↓ arrow keys • Hit Enter to apply', 'wp-spotlight-search' );
		$elements['category_title'] = esc_html__( 'Search In', 'wp-spotlight-search' );
		$elements['admin_category_title'] = esc_html__( 'Admin Menu Navigation', 'wp-spotlight-search' );
		$elements['commands_title'] = esc_html__( 'Comments', 'wp-spotlight-search' );
		$elements['clear_title'] = esc_html__( 'Clear', 'wp-spotlight-search' );

		return apply_filters( 'wp_spotlight_search_front_end_element_text', $elements);
	}
	
	public function save_menus( $menu ){
		$menu_hash = get_option('wp_spotlight_menu_hash');
		$save_menu = base64_encode(serialize($menu));
		$new_hash = md5($save_menu);
		if (empty($menu_hash) || ($menu_hash != $new_hash)) {
			update_option('wp_spotlight_all_admin_menu', $save_menu);
			update_option('wp_spotlight_menu_hash', $new_hash);
		}
	}

	public function get_post_types(){
		return get_post_types('', 'object');
	}

	public function get_menus(){
		$menu = get_option('wp_spotlight_all_admin_menu');
		
		if (empty($menu)){
			return array();
		}
		
		$menu = unserialize(base64_decode($menu));

		if (empty($menu)){
			return array();
		}
		return $menu;
	}

	private function get_shop_orders( $all_post_types, $key, $limit ){
		$post_content = $this->wpdb->get_results("select id,total_amount,currency from ".$this->wpdb->prefix."wc_orders where type = '".esc_attr($key)."' LIMIT ".$limit, ARRAY_A);
		if (!empty($post_content)) {
			foreach ($post_content as $order) {
				$post_temp = array();
				$post_temp['type'] = $key;
				$post_temp['category'] = 'Orders';
				$post_temp['title'] = 'Order #'.$order['id'];
				$post_temp['ID'] = $order['id']; 
				$post_temp['price'] = $order['currency'].' '.$order['total_amount'];
				$post_temp['url']= 'admin.php?page=wc-orders&action=edit&id='.$order['id'];
				array_push($all_post_types, $post_temp);
			}
		}
		return $all_post_types;
	}
}