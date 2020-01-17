<?php
namespace CCDE\Ajax;

use CCDE\Plugin;

class Save_Code extends Abstract_Endpoint {

	/**
	 * Return AJAX hook name
	 *
	 * @return string
	 */
	public function get_hook() {
		return 'save_code';
	}

	/**
	 * Return AJAX hook name
	 *
	 * @return array
	 */
	public function ajax_callback() {

		$args  = $this->get_args();
		$props = $args['code'];

		if ( ! empty( $props['start_date'] ) ) {
			$props['start_date'] = strtotime( $props['start_date'] );
		}

		if ( ! empty( $props['end_date'] ) ) {
			$props['end_date'] = strtotime( $props['end_date'] );
		}

		$code = Plugin::instance()->code_factory->get_code( $props, true );

		if ( $code->sanitize() ) {
			$code->save();
		} else {
			return array(
				'success' => false,
				'data'    => array( 'message' => $code->sanitize_errors() ),
			);
		}

		return array(
			'success' => true,
			'items'   => Plugin::instance()->db->query( array(), $limit, $offset ),
		);
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
				'default' => array(),
			),
		);
	}

}
