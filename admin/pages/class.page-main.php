<?php

namespace WYSC;

class Page_Main extends PageBase {

	/**
	 * Settings constructor.
	 *
	 * @param $plugin Plugin Plugin class
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		$this->id                 = 'main';
		$this->page_menu_dashicon = 'dashicons-superhero-alt';
		$this->page_menu_position = 20;
		$this->page_title         = __( 'My Plugin Page', 'smart-captcha-yandex' );
		$this->page_menu_title    = __( 'My Plugin Page', 'smart-captcha-yandex' );

		add_action( 'admin_init', array( $this, 'init_settings' ) );
	}

	public function page_action() {
		$this->plugin->render_template( 'my-plugin-page', array( 'key' => 'value' ) );
	}
}
