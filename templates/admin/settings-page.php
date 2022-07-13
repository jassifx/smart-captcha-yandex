<?php
/** @var array $args */
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="POST">
		<?php
		settings_fields( WYSC_PLUGIN_PREFIX . '_settings_group' );
		do_settings_sections( WYSC_PLUGIN_PREFIX . '_settings_page' );
		submit_button();
		?>
	</form>
</div>
