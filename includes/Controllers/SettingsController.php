<?php
namespace WP_SPOTLIGHT\Controllers;

require_once WP_SPOTLIGHT_SEARCH_PATH . 'includes/Base/wordpress-simple-settings.php';

class SettingsController extends \WP_Spotlight_Search_WordPress_SimpleSettings {

	var $prefix = '_wp_spotlight_search_';
	var $suffix = '_settings';

	/**
	 * SettingsManager constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( $prefix = "" , $suffix = "" ) {
		if ( !empty($prefix) ) $this->prefix = $prefix;
		if ( !empty($suffix) ) $this->suffix = $suffix;
		parent::__construct();

		// Silence is golden
	}

	public function add_setting( $setting = false, $value, $keys = array() ) {
		if ( $setting === false ) {
			return false;
		}

		$suffix = '';

		if ( ! empty( $keys ) ) {
			asort( $keys );

			$suffix = '_' . join( '', $keys );
		}

		if ( ! isset( $this->settings[ $setting . $suffix ] ) ) {
			return $this->update_setting( $setting . $suffix, $value );
		} else {
			return false;
		}
	}

	public function update_setting( $setting = false, $value, $keys = array() ) {
		$suffix = '';

		if ( ! empty( $keys ) ) {
			asort( $keys );

			$suffix = '_' . join( '', $keys );
		}

		return parent::update_setting( $setting . $suffix, $value );
	}

	public function delete_setting( $setting = false, $keys = array() ) {
		$suffix = '';
		if ( ! empty( $keys ) ) {
			asort( $keys );

			$suffix = '_' . join( '', $keys );
		}

		return parent::delete_setting( $setting . $suffix );
	}

	function get_setting( $setting = false, $keys = array() ) {
		$suffix = '';

		if ( ! empty( $keys ) ) {
			asort( $keys );

			$suffix = '_' . join( '', $keys );
		}

		return parent::get_setting( $setting . $suffix, 'string' );
	}

	public function get_field_name( $setting, $keys = array() ) {
		$suffix = '';

		if ( ! empty( $keys ) ) {
			asort( $keys );

			$suffix = '_' . join( '', $keys );
		}

		return parent::get_field_name( $setting . $suffix, 'string' );
	}
}
