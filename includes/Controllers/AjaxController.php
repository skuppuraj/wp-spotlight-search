<?php

namespace WP_SPOTLIGHT\Controllers;

class AjaxController {

	protected $ajax_modules;

	public function __construct( $ajax_modules ) {
		$this->ajax_modules = $ajax_modules;
	}

	public function load_all() {
		foreach ( $this->ajax_modules as $ajax ) {
			$ajax->load();
		}
	}

	public function get_ajax_modules() {
		return $this->ajax_modules;
	}
}
