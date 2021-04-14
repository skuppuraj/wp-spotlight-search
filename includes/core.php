<?php

class WP_Spotlight_Core{

	public static function get_searchabel_post_types_checkbox(){
		$other_types[0] = array('type'=>'users', 'label' => 'Users');
		$other_types[1] = array('type'=>'comments', 'label' => 'Comments');
		$other_types[2] = array('type'=>'post_meta', 'label' => 'Post meta (E.g: Advanced Custom Fields)');
	    $searchabel_post_type = WP_Spotlight_Core::get_searchabel_post_types();
	    $response = '';
	    $wp_spotlight_settings = WP_Spotlight_Core::wp_spotlight_search_include_options();
	    $searchabel_post_type = array_merge($searchabel_post_type, $other_types);
	    foreach ($searchabel_post_type as $key => $value) {
	        $type = $value['type'];
	        $label = $value['label'];
	        $selected = '';
	        if ($wp_spotlight_settings != false && in_array($type, $wp_spotlight_settings)) {
	            $selected = 'checked';
	        }
	        $response .= "<label class='wp-spotlight-settings-checkbox'> <input type='checkbox' value='".$type."' name='search_include_options[]' $selected /> $label </label> <br>";
	    }

	    return $response;
	}

	public static function get_searchabel_post_types(){
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

	public static function wp_spotlight_get_settings(){
	    $wp_spotlight_setting = get_option('wp_spotlight_setting');
	    if (empty($wp_spotlight_setting)) {
	        return false;
	    }

	    return unserialize($wp_spotlight_setting);

	}

	public static function wp_spotlight_search_include_options(){
	    $wp_spotlight_setting = WP_Spotlight_Core::wp_spotlight_get_settings();
	    if(empty($wp_spotlight_setting['search_include_options'])) {
	        return false;
	    }
	    return $wp_spotlight_setting['search_include_options'];
	}

	public static function get_search_content(){
		global $submenu, $menu, $wp_admin_bar, $wpdb;
		$final_response = array();
		$all_post_types = WP_Spotlight_Core::get_all_searchable_post();
		$final_response = array_merge($final_response,$all_post_types);
		$searchabel_menu = WP_Spotlight_Core::get_searchable_menu();
		$users = WP_Spotlight_Core::get_all_users();
		$final_response = array_merge($final_response,$users);
		$comments = WP_Spotlight_Core::get_all_comments();
		$final_response = array_merge($final_response,$comments);
		$join = array_merge($final_response,$searchabel_menu);

		return $join;
	}
	public static function get_searchable_menu(){
		global $submenu, $menu;
		$full_array = array();
		$full_array = self::menu_structure($menu, $submenu);
		return $full_array;
	}

	public static function get_all_searchable_post(){
		global $wpdb;
		$all_post_types = array();
		$post_types = get_post_types('', 'object');
		$wp_spotlight_setting = WP_Spotlight_Core::wp_spotlight_search_include_options();
		if ($wp_spotlight_setting == false) {
			return array();
		}
		foreach ($post_types as $key => $post) {
		    if (!in_array($key, $wp_spotlight_setting) || $key == 'attachment' || ($post->show_in_menu == false && $post->public == false)) {
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
		        $post_temp['ID'] = $content['ID']; 
		        if ($key == 'shop_order') {
		            $post_temp['title'] = $content['post_title']; 
		            $meta = get_post_meta($content['ID']);
		            $_order_currency = $meta['_order_currency'][0];
		            $_order_total = $meta['_order_total'][0];
		            $post_temp['price'] = $_order_currency.' '.$_order_total;
		        }elseif($key == 'product'){
		            $post_temp['title'] = $content['post_title'];
		            $meta = get_post_meta($content['ID']);
		            $_price = $meta['_price'][0];
		            $currency = get_option('woocommerce_currency');
		            $post_temp['price'] = $currency.' '.$_price;

		        }else{
		        	$meta = self::get_post_meta( $content['ID'], $key, $wp_spotlight_setting );
		            $post_temp['title'] = $content['post_title'];
		            if ( $meta != '' ) {
		            	$post_temp['description'] = $meta;
		            }
		        }
		        $post_temp['url']= 'post.php?post='.$content['ID'].'&action=edit';
		        array_push($all_post_types, $post_temp);
		    }

		}

		return $all_post_types;
	}

	public static function get_post_meta( $id, $type, $wp_spotlight_setting ){
		$li_html = '';
		if ( !in_array( 'post_meta', $wp_spotlight_setting ) ) {
			return 	$li_html;
		}
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

	public static function get_all_users(){
		$user_results = array();
		$wp_spotlight_setting = WP_Spotlight_Core::wp_spotlight_search_include_options();
		if ($wp_spotlight_setting == false) {
			return array();
		}
		if (!in_array('users', $wp_spotlight_setting)) {
			return $user_results;
		}
		$users = get_users();
		foreach ($users as $key => $value) {
			$user_temp = array();
			$user_temp['ID'] = $value->data->ID;
			$user_temp['title'] = $value->data->user_login;
			$user_temp['price'] = $value->roles[0];
			$user_temp['category'] = 'Users';
			$user_temp['url'] = 'user-edit.php?user_id='.$value->data->ID.'&wp_http_referer=%2Fsftp%2Fw1%2Fwp-admin%2Fusers.php';
			array_push($user_results, $user_temp);
		}
		return $user_results;
	}

	public static function get_all_comments(){
		$comment_results = array();
		$wp_spotlight_setting = WP_Spotlight_Core::wp_spotlight_search_include_options();
		if ($wp_spotlight_setting == false) {
			return array();
		}
		if (!in_array('comments', $wp_spotlight_setting)) {
			return $comment_results;
		}
		$comments = get_comments();
		foreach ($comments as $key => $value) {
			$comment_temp = array();
			$comment_temp['ID'] = $value->comment_ID;
			$comment_temp['title'] = $value->comment_content;
			$comment_temp['price'] = $value->comment_author_email;
			$comment_temp['category'] = 'Comments';
			$comment_temp['url'] = 'comment.php?action=editcomment&c='.$value->comment_ID;
			array_push($comment_results, $comment_temp);
		}

		return $comment_results;
	}

	public static function wp_spotlight_save_settings($data){
	    if (empty($data['search_include_options']) && empty($data['submit'])) {
	        return false;
	    }
	    if (empty($data['search_include_options']) && !empty($data['submit'])) {
	        delete_option('wp_spotlight_setting');
	        return true;
	    }
	    $settings['search_include_options'] = $data['search_include_options'];
	    update_option('wp_spotlight_setting', serialize($settings));
	     ?>
	    <div class="notice notice-success is-dismissible">
	        <p><?php _e( 'WP Spotlight settings saved', WP_SPOTLIGHT_SEARCH_NAME ); ?></p>
	    </div>
	    <?php
	}

	public static function wp_spotlight_admin_notice(){
		return get_option('wp_spotlight_admin_notice');
	}

	public static function wp_spotlight_save_admin_notice(){
		 update_option('wp_spotlight_admin_notice', 1);
	}

	public static function wp_spotlight_update_notice(){
		return get_option('wp_spotlight_update_notice');
	}

	public static function wp_spotlight_save_update_notice(){
		 update_option('wp_spotlight_update_notice', 1);
	}

	private static function menu_structure( $menu, $submenu, $submenu_as_parent = true ) {
		$full_array = array();
		$first = true;
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
			$temp['title']= $title;
            $temp['category'] = $item[0];
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
					$temp['title']= $title;
		            $temp['category'] = $item[0];

					if ( ! empty( $menu_hook ) || ( ( 'index.php' != $sub_item[2] ) && file_exists( WP_PLUGIN_DIR . "/$sub_file" ) && ! file_exists( ABSPATH . "/wp-admin/$sub_file" ) ) ) {
						// If admin.php is the current page or if the parent exists as a file in the plugins or admin dir
						if ( ( ! $admin_is_parent && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! is_dir( WP_PLUGIN_DIR . "/{$item[2]}" ) ) || file_exists( $menu_file ) ) {
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

		return $full_array;
	}

}