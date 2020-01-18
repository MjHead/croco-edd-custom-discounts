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

		$args    = $this->get_args();
		$props   = $args['code'];
		$code    = Plugin::instance()->code_factory->get_code( $props, true );
		$code_id = false;

		$code->dates_to_timestamp();

		if ( $code->sanitize() ) {
			$code_id = $code->save();
		} else {
			return array(
				'success' => false,
				'data'    => array( 'message' => $code->sanitize_errors() ),
			);
		}

		if ( ! $code_id ) {
			return array(
				'success' => false,
				'data'    => array( 'message' => 'Can`t create the code. Please try again later.' ),
			);
		}

		if ( ! empty( $props['ID'] ) ) {
			$redirect = false;
		} else {
			$redirect = add_query_arg(
				array( Plugin::instance()->code_query_var => $code_id ),
				Plugin::instance()->dashboard->page_url( 'single-code' )
			);
		}

		return array(
			'success'  => true,
			'redirect' => $redirect,
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
