<?php
namespace CCDE;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main file
 */
class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	public $dashboard;
	public $db;

	public $code_query_var = '_cid';

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;

	}

	/**
	 * Register autoloader.
	 */
	private function register_autoloader() {
		require CCDE_PATH . 'includes/autoloader.php';
		Autoloader::run();
	}

	/**
	 * Initialize plugin parts
	 *
	 * @return void
	 */
	public function init_components() {

		if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
			return;
		}

		$this->props        = new Props();
		$this->db           = new DB();
		$this->code_factory = new Codes\Code_Factory();
		$this->edd_gateway  = new EDD_Gateway();

		$this->ajax = new Ajax\Factory( array(
			new Ajax\Get_Codes(),
			new Ajax\Delete_Code(),
			new Ajax\Save_Code(),
			new Ajax\Generate_Codes(),
			new Ajax\Export_Codes(),
		) );

		$this->dashboard = new Admin\Dashboard( array(
			new Admin\Pages\List_Codes(),
			new Admin\Pages\Single_Code(),
		) );

	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		$this->register_autoloader();
		$this->init_components();
	}

}

Plugin::instance();
