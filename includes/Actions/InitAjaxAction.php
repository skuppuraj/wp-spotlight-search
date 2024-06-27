<?php

namespace WP_SPOTLIGHT\Actions;
use WP_SPOTLIGHT\Core\ActionCore;

class InitAjaxAction extends ActionCore {

	
	public function __construct( $id, $no_privilege, $action_prefix ) {
		parent::__construct( $id, $no_privilege, $action_prefix );
	}

	public function action() {
        $out = array();
        $out = wp_spotlight_get_main()->get_core()->init_search();
        $this->out( $out );
	}
}
