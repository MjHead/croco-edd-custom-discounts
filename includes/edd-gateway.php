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

				$discount  = edd_get_discount_by_code( $discount_code );
				$amount    = edd_format_discount_rate( $code->get_prop( 'type' ), $code->get_prop( 'amount' ) );
				$discounts = edd_set_cart_discount( $discount_code );
				$total     = edd_get_cart_total( $discounts );

				$return = array(
					'msg'         => 'valid',
					'amount'      => $amount,
					'total_plain' => $total,
					'total'       => html_entity_decode( edd_currency_filter( edd_format_amount( $total ) ), ENT_COMPAT, 'UTF-8' ),
					'code'        => $discount_code,
					'html'        => edd_get_cart_discounts_html( $discounts )
				);

				return $code->is_valid();
			}

		} else {
			return $result;
		}

	}

}
