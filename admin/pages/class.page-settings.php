<?php

namespace WYSC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Page_Settings extends PageSettingsBase {

	/**
	 * @var array
	 */
	public array $tabs = [
		'api' => 'API',
	];

	/**
	 * Settings constructor.
	 *
	 * @param $plugin Plugin object
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		$this->id                 = 'settings';
		$this->page_menu_position = 20;
		$this->page_title         = __( 'Yandex Smart Captcha Settings', 'smart-captcha-yandex' );
		$this->page_menu_title    = __( 'Smart Captcha Settings', 'smart-captcha-yandex' );

		$this->settings = $this->settings();

		add_action( 'admin_init', [ $this, 'init_settings' ] );
	}

	/**
	 * Array of the settings
	 *
	 * @return array
	 */
	public function settings(): array {
		return [
			'settings_group' => [
				'sections' => [
					[
						'title'   => __( 'API Settings', 'smart-captcha-yandex' ),
						'slug'    => 'section_api',
						'options' => [
							'client_token' => [
								'title'   => __( 'Client Key', 'smart-captcha-yandex' ),
								'type'    => 'text',
								'default' => '',
							],
							'server_token' => [
								'title'   => __( 'Server Key', 'smart-captcha-yandex' ),
								'default' => '',
							],
							/*
							'check_option' => [
									'title'             => __( 'Checkbox', 'smart-captcha-yandex' ),
									'type'  			=> 'checkbox',
									'default'			=> '',
							],
							*/
						],
					],
				],
			],
		];
	}

	public function page_action() {
		$current_tab = ! empty( $_REQUEST['tab'] ) ? sanitize_title( $_REQUEST['tab'] ) : 'api';

		$this->plugin->render_template( 'admin/settings-page', [
			'settings'     => $this->settings,
			'tabs'         => $this->tabs,
			'current_tab' => $current_tab,
		] );
	}
}
