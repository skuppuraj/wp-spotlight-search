<?php

namespace WP_SPOTLIGHT\Actions;
use WP_SPOTLIGHT\Core\ActionCore;
use WP_SPOTLIGHT\Core\SearchCore;

class SearchAction extends ActionCore {

	
	public function __construct( $id, $no_privilege, $action_prefix ) {
		parent::__construct( $id, $no_privilege, $action_prefix );
	}

	public function action() {
		$out = array();
        if (empty($_POST['search'])) {
            $this->out( $out );
            return;
        }
        $request = wp_spotlight_get_main()->recursive_sanitize_text_field($_POST);

		$this->startSearch($request);
		
	}

	private function startSearch($request){
		
	}
}
