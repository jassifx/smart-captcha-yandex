<?php

namespace WYSC;

use Exception;

/**
 * Base plugin class
 */

abstract class Plugin_Base {

	/**
	 * Admin pages
	 *
	 * @var array
	 */
	private $pages = [];

	/**
	 * @see self::app()
	 * @var self
	 */
	private static $_instance; // @codingStandardsIgnoreLine

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		self::$_instance = $this;
		add_action( 'wp_enqueue_scripts', [ $this, 'front_enqueue_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_assets' ] );

		$this->global_code();

		if ( is_admin() ) {
			$this->admin_code();
		} else {
			$this->front_code();
		}
	}

	/**
	 * Static method for quick access to the plugin interface.
	 * Allows to globally access an instance of the plugin class anywhere of the plugin
	 *
	 * @return self
	 */
	public static function instance() {
		if ( ! isset( static::$_instance ) ) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

	/**
	 * Add assets to front
	 */
	abstract public function front_enqueue_assets();

	/**
	 * Add assets to admin pages
	 */
	abstract public function admin_enqueue_assets();

	/**
	 * Admin code
	 */
	protected function admin_code() { }

	/**
	 * Front code
	 */
	protected function front_code() { }

	/**
	 * Global code
	 */
	protected function global_code() { }

	/**
	 *
	 * @param string $class_name Class Name.
	 *
	 * @throws Exception Class not found.
	 */
	public function register_page( string $class_name ) {
		if ( ! class_exists( $class_name ) ) {
			$class_name = __NAMESPACE__ . '\\' . $class_name;
			if ( ! class_exists( $class_name ) ) {
				throw new Exception( 'A class with this name {' . $class_name . '} does not exist.' );
			}
		}

		new $class_name( $this );
	}

	/**
	 * Get settings option
	 *
	 * @param string $option_name
	 * @param mixed $default_value
	 *
	 * @return false|mixed|void
	 */
	public static function getOption( string $option_name, $default_value = '' ) {
		$option = get_option( WYSC_PLUGIN_PREFIX . '_' . $option_name );

		return false !== $option ? $option : $default_value;
	}

	/**
	 * Add settings option
	 *
	 * @param string $option_name
	 * @param mixed $option_value
	 *
	 * @return bool
	 */
	public static function addOption( string $option_name, $option_value ): bool {
		return add_option( WYSC_PLUGIN_PREFIX . '_' . $option_name, $option_value );
	}

	/**
	 * Update settings option
	 *
	 * @param string $option_name
	 * @param mixed $option_value
	 *
	 * @return bool
	 */
	public static function updateOption( string $option_name, $option_value ): bool {
		return update_option( WYSC_PLUGIN_PREFIX . '_' . $option_name, $option_value );
	}

	/**
	 * Delete settings option
	 *
	 * @param string $option_name
	 *
	 * @return bool
	 */
	public static function deleteOption( string $option_name ): bool {
		return delete_option( WYSC_PLUGIN_PREFIX . '_' . $option_name );
	}

	/**
	 * Method renders layout template
	 *
	 * @param string $template_name Template name without ".php"
	 * @param array $args Template arguments
	 */
	public function render_template( $template_name, $args = [] ) {
		$template_name = apply_filters( WYSC_PLUGIN_PREFIX . '/template/name', $template_name, $args );

		$path = WYSC_PLUGIN_DIR . "/templates/$template_name.php";
		if ( file_exists( $path ) ) {
			ob_start();
			include $path;

			echo apply_filters( WYSC_PLUGIN_PREFIX . '/content/template', ob_get_clean(), $template_name, $args ); // @codingStandardsIgnoreLine
		} else {
			echo apply_filters( WYSC_PLUGIN_PREFIX . '/message/template_not_found', __( 'This template does not exist!', 'smart-captcha-yandex' ) ); // @codingStandardsIgnoreLine
		}
	}
}
