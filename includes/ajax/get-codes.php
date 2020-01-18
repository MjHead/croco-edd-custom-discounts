<?php
namespace CCDE\Ajax;

use CCDE\Plugin;

class Get_Codes extends Abstract_Endpoint {

	/**
	 * Return AJAX hook name
	 *
	 * @return string
	 */
	public function get_hook() {
		return 'get_codes';
	}

	/**
	 * Return AJAX hook name
	 *
	 * @return array
	 */
	public function ajax_callback() {

		$args   = $this->get_args();
		$limit  = $args['per_page'];
		$offset = $args['offset'];

		return array(
			'success' => true,
			'items'   => $this->prepare_results( Plugin::instance()->db->query( array(), $limit, $offset ) ),
		);
	}

	/**
	 * Prepare results to send to front
	 *
	 * @return [type] [description]
	 */
	public function prepare_results( $raw ) {

		if ( empty( $raw ) ) {
			return $raw;
		}

		$result = array();

		foreach ( $raw as $code_props ) {
			$code = Plugin::instance()->code_factory->get_code( $code_props );
			$code->timestamps_to_date();
			$result[] = $code->get_props();
		}

		return $result;

	}

	/**
	 * Return AJAX hook name
	 *
	 * @return boolean
	 */
	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Returns arguments list
	 *
	 * @return [type] [description]
	 */
	public function args() {
		return array(
			'offset' => array(
				'default' => 0,
			),
			'per_page' => array(
				'default' => 50,
			),
			'query' => array(
				'default' => array(),
			),
		);
	}

}
