<?php

namespace WP_SPOTLIGHT\Actions;
use WP_SPOTLIGHT\Core\ActionCore;

class SaveCategoryAction extends ActionCore {

	
	public function __construct( $id, $no_privilege, $action_prefix ) {
		parent::__construct( $id, $no_privilege, $action_prefix );
	}

	public function action() {
        $category = $_POST['activeCategories'];
        if (empty($category)) {
            $category = array();
        }
        $activeCategories = wp_spotlight_get_main()->recursive_sanitize_text_field($category);
        $this->save_category($activeCategories);
	}
    
    private function save_category( $activeCategories ){
        $current_user_id = get_current_user_id();
        update_user_meta( $current_user_id, WP_SPOTLIGHT_SEARCH_SAVED_CATEGORY_KEY, serialize( $activeCategories ) );
        
    }
}
