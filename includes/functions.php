<?php

use WYSC\Plugin;

/**
 * @param string $token Captcha token from form
 *
 * @return bool
 */
function wysc_check_smart_captcha( $token ) {
	$server_key = Plugin::getOption( 'server_token' );

	$args     = http_build_query( [
		'secret' => $server_key,
		'token'  => esc_attr( $token ),
		'ip'     => esc_attr( $_SERVER['REMOTE_ADDR'] ),
	] );
	$response = wp_remote_get( "https://captcha-api.yandex.ru/validate?{$args}" );

	if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
		$result = json_decode( $response['body'] ?? [], true );
		if ( 'ok' === $result['status'] ) {
			return true;
		}
	}

	return false;
}