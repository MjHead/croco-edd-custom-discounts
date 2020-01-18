<?php
namespace CCDE;

/**
 * Edd gateway
 */
class EDD_Gateway {

	public function __construct() {
		add_filter( 'edd_ajax_discount_response', array( $this, 'check_discount' ) );
	}

	/**
	 * Check discount code
	 *
	 * @return [type] [description]
	 */
	public function check_discount( $result ) {

		if ( isset( $result['code'] ) ) {

			$code = Plugin::instance()->code_factory->get_code( array( 'code' => $result['code'] ) );

			if ( ! $code->exists() ) {
				return $result;
			} else {
				return $code->is_valid();
			}

		} else {
			return $result;
		}

	}

}
