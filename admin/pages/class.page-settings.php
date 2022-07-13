<?php

namespace WYSC;

class Page_Settings extends PageBase {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * Settings constructor.
	 *
	 * @param $plugin Plugin object
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		$this->id                 = 'settings';
		$this->page_menu_position = 20;
		$this->page_title         = __( 'Yandex Smart Captcha Settings', 'yandex-smart-captcha' );
		$this->page_menu_title    = __( 'Smart Captcha Settings', 'yandex-smart-captcha' );

		$this->settings = $this->settings();

		add_action( 'admin_init', [ $this, 'init_settings' ] );
	}

	/**
	 * Array of the settings
	 *
	 * @return array
	 */
	public function settings(): array {
		$settings = [
				'settings_group' => [
						'sections' => [
								[
										'title'   => __( 'API settings', 'yandex-smart-captcha' ),
										'slug'    => 'section_general',
										'options' => [
												'text_option'  => [
														'title'             => __( 'Text option', 'yandex-smart-captcha' ),
														'render_callback'   => [ $this, 'fill_text_field' ],
														'sanitize_callback' => [ $this, 'sanitize_callback' ],
												],
												'check_option' => [
														'title'             => __( 'Checkbox', 'yandex-smart-captcha' ),
														'render_callback'   => [ $this, 'fill_checkbox_field' ],
														'sanitize_callback' => [ $this, 'sanitize_callback' ],
												],
										],
								],
						],
				],
		];

		return $settings;
	}

	public function add_page_to_menu() {
		add_options_page( $this->page_title, $this->page_menu_title, 'manage_options', WYSC_PLUGIN_PREFIX . '_' . $this->id, [
				$this,
				'page_action',
		], $this->page_menu_position );
	}

	public function page_action() {
		$this->plugin->render_template( 'admin/settings-page', [ 'settings' => $this->settings ] );
	}

	public function init_settings() {
		foreach ( $this->settings as $group_slug => $group ) {
			$group_slug = WYSC_PLUGIN_PREFIX . '_' . $group_slug;
			foreach ( $group['sections'] as $section ) {
				$section_slug = WYSC_PLUGIN_PREFIX . '_' . $section['slug'];
				foreach ( $section['options'] as $opt_name => $option ) {
					$opt_name = WYSC_PLUGIN_PREFIX . '_' . $opt_name;
					register_setting( $group_slug, $opt_name, [
							'sanitize_callback' => $option['sanitize_callback'],
							'show_in_rest'      => false,
					] );
					add_settings_field( $opt_name, $option['title'], $option['render_callback'], WYSC_PLUGIN_PREFIX . '_settings_page', $section_slug, $opt_name );
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
		<input type="text" name="<?php echo esc_attr( $option_name ); ?>" id="<?php echo esc_attr( $option_name ); ?>"
		       value="<?php echo esc_attr( $val ); ?>"/>
		<?php
	}

	/**
	 * @param string $option_name Option name
	 */
	public function fill_checkbox_field( string $option_name ) {
		$val   = get_option( $option_name );
		$val   = $val ? 1 : 0;
		$check = __( 'Check', 'yandex-smart-captcha' );
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
			return strip_tags( $value );
		}

		if ( is_numeric( $value ) ) {
			return intval( $value );
		}

		return $value;
	}
}
