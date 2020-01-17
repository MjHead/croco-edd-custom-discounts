<?php
namespace CCDE\Admin\Helpers;

/**
 * Base dashboard page
 */
class Page_Config {

	private $handle = null;
	private $config = array();

	/**
	 * Setup props
	 */
	public function __construct( $handle = null, $config = array() ) {
		$this->handle = $handle;
		$this->config = apply_filters( 'ccde/admin/helpers/page-config/config', $config );
	}

	/**
	 * Check if config is not empty
	 *
	 * @return [type] [description]
	 */
	public function isset() {
		return ( ! empty( $this->handle ) && ! empty( $this->config ) );
	}

	/**
	 * Get cofig prop
	 *
	 * @return [type] [description]
	 */
	public function get( $prop ) {
		return isset( $this->$prop ) ? $this->$prop : false;
	}

	/**
	 * Add config to the page
	 *
	 * @param  [type] $object_name [description]
	 * @return [type]              [description]
	 */
	public function include( $object_name = null ) {
		if ( $this->isset() && $object_name ) {
			wp_localize_script( $this->get( 'handle' ), $object_name, $this->get( 'config' ) );
		}
	}

}
