<?php

namespace WYSC;

class RegistrationFormCaptcha {

	/**
	 * Construct
	 */
	public function __construct() {
		if ( ! is_user_logged_in() ) {
			add_action( 'register_form', [ $this, 'extend_fields' ], 99 );
			add_filter( 'registration_errors', [ $this, 'check_captcha' ], 10, 2 );

			add_action( 'lostpassword_form', [ $this, 'extend_fields' ], 99 );
			add_action( 'lostpassword_post', [ $this, 'check_captcha' ], 10, 2 );

			add_action( 'resetpass_form', [ $this, 'extend_fields' ], 99 );
			add_filter( 'validate_password_reset', [ $this, 'check_captcha' ], 10, 2 );
		}
	}

	/**
	 * Add captcha script and container
	 *
	 * @return void
	 */
	public function extend_fields() {
		$client_key = Plugin::getOption( 'client_token' );

		wp_enqueue_script( 'wysc_script' );
		echo '<div style="height: 98px; min-width: 200px; margin: 0 0 16px 0;"' .
		     ' id="captcha-container"' .
		     ' class="smart-captcha"' .
		     ' data-sitekey="' . esc_attr( $client_key ) . '"' .
		     '></div>';
	}

	/**
	 * @param \WP_Error $errors
	 * @param string $sanitized_user_login
	 *
	 * @return \WP_Error
	 */
	public function check_captcha( $errors, $sanitized_user_login ) {
		if ( isset( $_POST['smart-token'] ) ) {
			if ( wysc_check_smart_captcha( $_POST['smart-token'] ) ) {
				return $errors;
			}
		}

		$errors->add( 'captcha_error', esc_html__( 'Please complete the captcha.', 'smart-captcha-yandex' ) );

		return $errors;
	}
}
