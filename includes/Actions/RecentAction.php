<?php

namespace WP_SPOTLIGHT\Actions;
use WP_SPOTLIGHT\Core\ActionCore;

class RecentAction extends ActionCore {

	
	public function __construct( $id, $no_privilege, $action_prefix ) {
		parent::__construct( $id, $no_privilege, $action_prefix );
	}

	public function action() {
        $out = array();
        if (empty($_POST['item'])) {
            $this->out( $out );
            return;
        }
        $new_item = wp_spotlight_get_main()->recursive_sanitize_text_field($_POST['item']);
        $this->save_recent($new_item);
	}
    
    private function save_recent( $new_item ){
        $recent = array();
        $current_user_id = get_current_user_id();
        $existing_details = wp_spotlight_get_main()->get_recent_search();
        if (empty($existing_details)) {
            $new_item['recent_score'] = 1;
            $recent[] = $new_item;
        }else {
            $is_exists = false;
            foreach ($existing_details as $key => $value) {
                $item = $value['item'];
                foreach ($new_item["item"] as $ikey => $value) {
                    if (isset($item[$ikey]) && $item[$ikey] == $value) {
                        $is_exists = true;
                    }else{$is_exists = false;}
                }
                if ($is_exists == true) {
                   $existing_details[$key]['recent_score'] = $existing_details[$key]['recent_score']+1;
                }
            }
            $recent = $existing_details;
            if ($is_exists == false) {
                if (count($recent) >= WP_SPOTLIGHT_SEARCH_SEARCH_RECENT_SEARCH_LIMIT) {
                    array_shift($recent);
                }
                $new_item['recent_score'] = 1;
                $recent[] = $new_item;
            }
        }
        update_user_meta( $current_user_id, WP_SPOTLIGHT_SEARCH_SEARCH_RECENT_SEARCH_KEY, $recent );
        
    }
}
