<?php

use WYSC\Plugin;

/**
 * @param string $token Captcha token from form
 *
 * @return bool
 */
function wysc_check_smart_captcha( string $token ): bool {
	$server_key = Plugin::getOption( 'server_token' );

	$args     = http_build_query( [
		'secret' => $server_key,
		'token'  => sanitize_text_field( $token ),
		'ip'     => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ),
	] );
	$response = wp_safe_remote_get( "https://captcha-api.yandex.ru/validate?{$args}" );

	if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
		return false;
	}

	$result = json_decode( wp_remote_retrieve_body( $response ), true );

	return ( isset( $result['status'] ) && $result['status'] === 'ok' );
}
