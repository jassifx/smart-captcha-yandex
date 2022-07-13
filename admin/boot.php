<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once WYSC_PLUGIN_DIR . '/admin/includes/base/class.page-base.php';
//require_once WYSC_PLUGIN_DIR . '/admin/pages/class.page-settings.php';
//require_once WYSC_PLUGIN_DIR . '/admin/pages/class.page-main.php';

$pages_dir = WYSC_PLUGIN_DIR . '/admin/pages/';
foreach ( scandir( $pages_dir ) as $page ) {
	if ( '.' === $page || '..' === $page ) {
		continue;
	}

	require_once $pages_dir . $page;
}
