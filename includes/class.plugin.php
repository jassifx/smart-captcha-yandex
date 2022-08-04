<?php

namespace WYSC;

/**
 * Main plugin class
 */
class Plugin extends Plugin_Base {

	/**
	 * Settings class
	 *
	 * @var Page_Settings
	 */
	public $settings;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		parent::__construct();

		if ( ! is_admin() ) { // without this check it is not possible to add comment in admin section
			add_action( 'wp_head', function () {
				$comments = new CommentsFormCaptcha();
			} );
			add_action( 'init', function () {
				$auth     = new AuthFormCaptcha();
				$register = new RegistrationFormCaptcha();
			} );
		}
	}

	public function front_enqueue_assets() {
		//wp_enqueue_script( WYSC_PLUGIN_PREFIX . '_js', WYSC_PLUGIN_URL . '/assets/script.js', [ 'jquery' ], WYSC_PLUGIN_VERSION, true );
		wp_register_script( 'wysc_script', 'https://captcha-api.yandex.ru/captcha.js', [], WYSC_PLUGIN_VERSION, true );
	}

	public function admin_enqueue_assets() {
	}

	/**
	 * Admin code
	 *
	 * @throws \Exception Page class not found.
	 */
	public function admin_code() {
		$this->register_page( 'Page_Settings' );
		//$this->register_page( 'Page_Main' );
	}


}
