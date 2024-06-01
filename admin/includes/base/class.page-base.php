<?php

namespace WYSC;

abstract class PageBase {

	/**
	 * @var string
	 */
	public string $id = '';

	/**
	 * @var string
	 */
	public string $page_menu_dashicon = '';

	/**
	 * @var int
	 */
	public int $page_menu_position = 20;

	/**
	 * @var Plugin
	 */
	protected Plugin $plugin;

	/**
	 * @var string
	 */
	protected string $page_title = '';

	/**
	 * @var string
	 */
	protected string $page_menu_title = '';

	/**
	 * PageBase constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		add_action( 'admin_menu', [ $this, 'add_page_to_menu' ] );
	}

	public function add_page_to_menu() {
		add_options_page( $this->page_title, $this->page_menu_title, 'manage_options', WYSC_PLUGIN_PREFIX . '_' . $this->id, [
			$this,
			'page_action',
		], $this->page_menu_position );
	}

	abstract public function page_action();
}
