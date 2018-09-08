<?php

class WP_Spotlite_Core{

	public static function get_searchabel_post_types_checkbox(){
		$other_types[0] = array('type'=>'users', 'label' => 'Users');
		$other_types[0] = array('type'=>'comments', 'label' => 'Comments');
	    $searchabel_post_type = WP_Spotlite_Core::get_searchabel_post_types();
	    $response = '';
	    $wp_spotlite_settings = WP_Spotlite_Core::wp_spotlite_search_include_options();
	    $searchabel_post_type = array_merge($searchabel_post_type, $other_types);
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

	public static function wp_spotlite_get_settings(){
	    $wp_spotlite_setting = get_option('wp_spotlite_setting');
	    if (empty($wp_spotlite_setting)) {
	        return false;
	    }

	    return unserialize($wp_spotlite_setting);

	}

	public static function wp_spotlite_search_include_options(){
	    $wp_spotlite_setting = WP_Spotlite_Core::wp_spotlite_get_settings();
	    if(empty($wp_spotlite_setting['search_include_options'])) {
	        return false;
	    }
	    return $wp_spotlite_setting['search_include_options'];
	}

	public static function get_search_content(){
		global $submenu, $menu, $wp_admin_bar, $wpdb;
		$final_response = array();
		$all_post_types = WP_Spotlite_Core::get_all_searchable_post();
		$final_response = array_merge($final_response,$all_post_types);
		$searchabel_menu = WP_Spotlite_Core::get_searchable_menu();
		$users = WP_Spotlite_Core::get_all_users();
		$final_response = array_merge($final_response,$users);
		$comments = WP_Spotlite_Core::get_all_comments();
		$final_response = array_merge($final_response,$comments);
		$join = array_merge($final_response,$searchabel_menu);

		return $join;
	}
	public static function get_searchable_menu(){
		global $submenu, $menu;
		$full_array = array();
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
		            array_push($full_array, $temp);
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
		        array_push($full_array, $temp);
		    }
		
		}
		return $full_array;
	}

	public static function get_all_searchable_post(){
		global $wpdb;
		$all_post_types = array();
		$post_types = get_post_types('', 'object');
		$wp_spotlite_setting = WP_Spotlite_Core::wp_spotlite_search_include_options();
		foreach ($post_types as $key => $post) {
		    if (!in_array($key, $wp_spotlite_setting) || $key == 'attachment' || ($post->show_in_menu == false && $post->public == false)) {
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
		            $post_temp['title'] = $content['post_title'];
		        }
		        $post_temp['url']= 'post.php?post='.$content['ID'].'&action=edit';
		        array_push($all_post_types, $post_temp);
		    }

		}

		return $all_post_types;
	}

	public static function get_all_users(){
		$user_results = array();
		$wp_spotlite_setting = WP_Spotlite_Core::wp_spotlite_search_include_options();
		if (!in_array('users', $wp_spotlite_setting)) {
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
		$wp_spotlite_setting = WP_Spotlite_Core::wp_spotlite_search_include_options();
		if (!in_array('comments', $wp_spotlite_setting)) {
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

	public static function wp_spotlite_save_settings($data){
	    if (empty($data['search_include_options'])) {
	        return false;
	    }
	    $settings['search_include_options'] = $data['search_include_options'];
	    update_option('wp_spotlite_setting', serialize($settings));
	}

}