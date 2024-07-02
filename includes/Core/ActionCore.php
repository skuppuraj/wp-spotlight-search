<?php

namespace WP_SPOTLIGHT\Core;

abstract class ActionCore extends Tracked {

	private $no_privilege;

	private $action_prefix;

	public function __construct( $id, $no_privilege = true, $action_prefix = 'wp_ajax_' ) {
		$this->no_privilege  = $no_privilege;
		$this->action_prefix = $action_prefix;

		parent::__construct( $id );
	}

	public function load() {
		remove_all_actions( "{$this->action_prefix}{$this->get_id()}" );
		add_action( "{$this->action_prefix}{$this->get_id()}", array( $this, 'execute' ) );

		if ( $this->no_privilege === true ) {
			remove_all_actions( "{$this->action_prefix}nopriv_{$this->get_id()}" );
			add_action( "{$this->action_prefix}nopriv_{$this->get_id()}", array( $this, 'execute' ) );
		}
	}

	public function execute() {
		if ( ! defined('WP_SPOTLIGHT_SEARCH_BOOSTER_NO_BUFFER') ) {
			// Try to prevent errors and errata from leaking into AJAX responses
			// This output buffer is discarded on out();
			@ob_end_clean();
			ob_start();
		}

		$this->action();
	}

	protected function out( $out ) {
		if ( ! defined('WP_SPOTLIGHT_SEARCH_BOOSTER_NO_BUFFER') ) {
			@ob_end_clean();
		}

		echo json_encode( $out );
		wp_die();
	}

	abstract public function action();
}
