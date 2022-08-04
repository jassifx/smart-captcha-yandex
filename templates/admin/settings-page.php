<?php
/** @var array $args */

$tabs        = $args['tabs'] ?? '';
$current_tab = $args['current_tab'] ?? '';
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
		<?php
		foreach ( $tabs as $slug => $label ) {
			echo '<a href="' . esc_url_raw( admin_url( 'admin.php?page=' . WYSC_PLUGIN_PREFIX . '_settings&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
		}
		?>
	</nav>
	<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="POST">
		<?php
		settings_fields( WYSC_PLUGIN_PREFIX . '_settings_group' );
		do_settings_sections( WYSC_PLUGIN_PREFIX . '_settings_page' );
		submit_button();
		?>
	</form>
</div>
