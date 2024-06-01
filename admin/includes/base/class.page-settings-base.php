<?php

namespace WYSC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PageSettingsBase extends PageBase {

	/**
	 * @var array
	 */
	protected array $settings;

	/**
	 * PageSettingsBase constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );
	}

	public function init_settings() {
		foreach ( $this->settings as $group_slug => $group ) {
			$group_slug = WYSC_PLUGIN_PREFIX . '_' . $group_slug;
			foreach ( $group['sections'] as $section ) {
				$section_slug = WYSC_PLUGIN_PREFIX . '_' . $section['slug'];
				foreach ( $section['options'] as $opt_name => $option ) {
					$opt_name          = WYSC_PLUGIN_PREFIX . '_' . $opt_name;
					$opt_type          = $option['type'] ?? 'text';
					$render_function   = $option['render_callback'] ?? array( $this, "fill_{$opt_type}_field" );
					$sanitize_function = $option['sanitize_callback'] ?? array( $this, 'sanitize_callback' );

					register_setting( $group_slug, $opt_name, array(
						'sanitize_callback' => $sanitize_function,
						'default'           => $option['default'],
						'show_in_rest'      => false,
					) );
					add_settings_field( $opt_name, $option['title'], $render_function, WYSC_PLUGIN_PREFIX . '_settings_page', $section_slug, $opt_name );
				}
				add_settings_section( $section_slug, $section['title'], '', WYSC_PLUGIN_PREFIX . '_settings_page' );
			}
		}
	}

	/**
	 * @param string $option_name Option name
	 */
	public function fill_text_field( string $option_name ) {
		$val = get_option( $option_name );
		$val = $val ? $val : '';
		?>
		<input type="text" size="50" name="<?php echo esc_attr( $option_name ); ?>" id="<?php echo esc_attr( $option_name ); ?>"
		       value="<?php echo esc_attr( $val ); ?>"/>
		<?php
	}

	/**
	 * @param string $option_name Option name
	 */
	public function fill_checkbox_field( string $option_name ) {
		$val   = get_option( $option_name );
		$val   = $val ? 1 : 0;
		$check = __( 'Select', 'smart-captcha-yandex' );
		?>
		<label for="<?php echo esc_attr( $option_name ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $option_name ); ?>"
			       id="<?php echo esc_attr( $option_name ); ?>"
			       value="<?php echo esc_attr( $val ); ?>" <?php checked( 1, $val ); ?> />
			<?php echo esc_html( $check ); ?>
		</label>
		<?php
	}

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function sanitize_callback( $value ) {
		if ( is_string( $value ) ) {
			return sanitize_text_field( $value );
		}

		if ( is_numeric( $value ) ) {
			return intval( $value );
		}

		return $value;
	}
}
