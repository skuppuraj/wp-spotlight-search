<?php

namespace WP_SPOTLIGHT\Admin;
use WP_SPOTLIGHT\Admin\Settings;
class Admin {
    private $settings_obj;
    public function __construct() {
		$this->init();
	}

    private function init(){
        $this->create_objects();
		$this->add_action_hooks();
	}

    private function create_objects(){
        $this->settings_obj = new Settings();
    }

    private function add_action_hooks(){
        // add_action( 'admin_menu', array($this, 'wp_spotlight_menu'));
    }

    public function wp_spotlight_menu(){
        add_menu_page('WP Spotlight Setting', 'WP Spotlight', 'manage_options', 'wp_spotlight_menu', array($this->settings_obj, 'wp_spotlight_menu_page'));
    }
}