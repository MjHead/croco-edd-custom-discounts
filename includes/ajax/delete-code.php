<?php
namespace CCDE\Ajax;

use CCDE\Plugin;

class Delete_Code extends Abstract_Endpoint {

	/**
	 * Return AJAX hook name
	 *
	 * @return string
	 */
	public function get_hook() {
		return 'delete_code';
	}

	/**
	 * Return AJAX hook name
	 *
	 * @return array
	 */
	public function ajax_callback() {

		$args = $this->get_args();
		$code = $args['code'];

		if ( ! $code ) {
			return array(
				'success' => false,
				'data'    => array( 'message' => 'Code ID not found in request' ),
			);
		}

		Plugin::instance()->db->delete( array( 'ID' => $code ) );

		return array( 'success' => true );

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
			'code' => array(
				'default' => 0,
			),
		);
	}

}
