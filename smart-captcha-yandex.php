<?php
/**
 * Plugin Name: Smart Captcha Yandex
 * Description: WordPress integration for Yandex Smart Captcha
 * Version:     1.0.0
 * Author:      Webtemyk <webtemyk@yandex.ru>
 * Author URI:  https://temyk.ru
 * Text Domain: smart-captcha-yandex
 * Domain Path: /languages/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = get_file_data( __FILE__, [ 'ver' => 'Version' ] );

define( 'WYSC_PLUGIN_DIR', __DIR__ );
define( 'WYSC_PLUGIN_SLUG', 'smart-captcha-yandex' );
define( 'WYSC_PLUGIN_VERSION', $data['ver'] );
define( 'WYSC_PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'WYSC_PLUGIN_URL', plugins_url( null, __FILE__ ) );
define( 'WYSC_PLUGIN_PREFIX', 'wysc' );

load_plugin_textdomain( WYSC_PLUGIN_SLUG, false, dirname( WYSC_PLUGIN_BASE ) );

require_once WYSC_PLUGIN_DIR . '/includes/boot.php';
if ( is_admin() ) {
	require_once WYSC_PLUGIN_DIR . '/admin/boot.php';
}

try {
	new \WYSC\Plugin();
} catch ( Exception $e ) {
	$wysc_plugin_error_func = function () use ( $e ) {
		$error = sprintf( __( 'The %1$s plugin has stopped. <b>Error:</b> %2$s Code: %3$s', 'smart-captcha-yandex' ), 'Yandex Smart Captcha', $e->getMessage(), $e->getCode() );
		echo wp_kses_post( '<div class="notice notice-error"><p>' . $error . '</p></div>' );
	};

	add_action( 'admin_notices', $wysc_plugin_error_func );
	add_action( 'network_admin_notices', $wysc_plugin_error_func );
}
