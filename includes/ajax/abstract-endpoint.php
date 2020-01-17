<?php
namespace CCDE\Ajax;

abstract class Abstract_Endpoint {

	/**
	 * Return AJAX hook name
	 *
	 * @return string
	 */
	abstract public function get_hook();

	/**
	 * Return AJAX hook name
	 *
	 * @return array
	 */
	abstract public function ajax_callback();

	/**
	 * Return AJAX hook name
	 *
	 * @return boolean
	 */
	abstract public function permission_callback();

	/**
	 * Return arguments
	 *
	 * @return [type] [description]
	 */
	public function args() {
		return array();
	}

	/**
	 * Setup ajax arguments
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		$result = array();

		foreach ( $this->args() as $key => $data ) {
			$default        = isset( $data['default'] ) ? $data['default'] : false;
			$result[ $key ] = isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $default;
		}

		return $result;

	}

	/**
	 * Check is private request or not
	 *
	 * @return [type] [description]
	 */
	public function nopriv() {
		return false;
	}

}
