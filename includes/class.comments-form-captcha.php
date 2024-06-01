<?php

namespace WYSC;

class CommentsFormCaptcha {

	/**
	 * Construct
	 */
	public function __construct() {
		add_filter( 'comment_form_after_fields', [ $this, 'extend_comment_fields' ] );

		if ( ! is_user_logged_in() ) {
			add_filter( 'preprocess_comment', [ $this, 'check_captcha' ] );
		}

	}

	/**
	 * Add captcha script and container
	 *
	 * @return void
	 */
	public function extend_comment_fields() {
		$client_key = Plugin::getOption( 'client_token' );

		wp_enqueue_script( 'wysc_script' );
		echo '<p class="comment-form-captcha"><div ' .
		     'style="height: 98px;" ' .
		     'id="captcha-container" ' .
		     'class="smart-captcha" ' .
		     'data-sitekey="' . esc_attr( $client_key ) . '"' .
		     '></div></p>';
	}

	/**
	 * @param array $comment_data Comment data
	 *
	 * @return array|void
	 */
	public function check_captcha( $comment_data ) {
		$comment_type = $comment_data['comment_type'] ?? null;

		if ( isset( $_POST['smart-token'] ) && 'pingback' !== $comment_type && 'trackback' !== $comment_type ) {
			if ( wysc_check_smart_captcha( $_POST['smart-token'] ) ) {
				return $comment_data;
			}
		}

		wp_die( esc_html__( 'Please complete the captcha.', 'smart-captcha-yandex' ) );
	}
}
