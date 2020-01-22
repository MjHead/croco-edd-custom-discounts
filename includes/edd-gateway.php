<?php
namespace CCDE;

/**
 * Edd gateway
 */
class EDD_Gateway {

	public function __construct() {
		add_filter( 'edd_ajax_discount_response', array( $this, 'check_discount' ) );
		add_filter( 'edd_get_cart_item_discounted_amount', array( $this, 'apply_discount' ), 10, 4 );
		add_filter( 'edd_get_cart_discount_html', array( $this, 'cart_discount_html' ), 10, 4 );
	}

	public function cart_discount_html( $discount_html, $discount, $rate, $remove_url ) {
		$code = Plugin::instance()->code_factory->get_code( array( 'code' => $discount ) );

		if ( $code->exists() ) {
			return str_replace(
				$rate,
				edd_format_discount_rate( $code->get_prop( 'type' ), $code->get_prop( 'amount' ) ),
				$discount_html
			);
		} else {
			return $discount_html;
		}
	}

	/**
	 * Apply discount
	 *
	 * @return [type] [description]
	 */
	public function apply_discount( $discounted_price, $discounts, $item, $price ) {

		$price = floatval( $price );

		if ( empty( $discounts ) || 0 >= $price ) {
			return $discounted_price;
		}

		foreach ( $discounts as $discount ) {

			$code = Plugin::instance()->code_factory->get_code( array( 'code' => $discount ) );

			// Check discount exists
			if( ! $code->exists() ) {
				continue;
			}

			$reqs              = $code->get_required_products();
			$excluded_products = array();

			// Make sure requirements are set and that this discount shouldn't apply to the whole cart
			if ( ! empty( $reqs ) ) {
				// This is a product(s) specific discount
				foreach ( $reqs as $download_id ) {
					if ( $download_id == $item['id'] && ! in_array( $item['id'], $excluded_products ) ) {
						$discounted_price -= $price - $code->get_discounted_amount( $price );
					}
				}
			} else {
				// This is a global cart discount
				if( ! in_array( $item['id'], $excluded_products ) ) {
					if ( 'flat' === $code->get_prop( 'type' ) ) {

						/* *
						 * In order to correctly record individual item amounts, global flat rate discounts
						 * are distributed across all cart items. The discount amount is divided by the number
						 * of items in the cart and then a portion is evenly applied to each cart item
						 */
						$items_subtotal    = 0.00;
						$cart_items        = edd_get_cart_contents();

						foreach ( $cart_items as $cart_item ) {
							if ( ! in_array( $cart_item['id'], $excluded_products ) ) {
								$item_price      = edd_get_cart_item_price( $cart_item['id'], $cart_item['options'] );
								$items_subtotal += $item_price * $cart_item['quantity'];
							}
						}

						$subtotal_percent  = ( ( $price * $item['quantity'] ) / $items_subtotal );
						$code_amount       = $code->get_prop( 'amount' );
						$discounted_amount = $code_amount * $subtotal_percent;
						$discounted_price -= $discounted_amount;

						$edd_flat_discount_total += round( $discounted_amount, edd_currency_decimal_filter() );

						if ( $edd_is_last_cart_item && $edd_flat_discount_total < $code_amount ) {
							$adjustment = $code_amount - $edd_flat_discount_total;
							$discounted_price -= $adjustment;
						}
					} else {
						$discounted_price -= $price - $code->get_discounted_amount( $discount, $price );
					}
				}
			}

			if ( $discounted_price < 0 ) {
				$discounted_price = 0;
			}
		}

		return $discounted_price;

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
			} else if ( ! $code->is_valid( get_current_user_id() ) ) {
				return $result;
			} else {

				$amount        = edd_format_discount_rate( $code->get_prop( 'type' ), $code->get_prop( 'amount' ) );
				$discount_code = $code->get_prop( 'code' );
				$discounts     = edd_set_cart_discount( $discount_code );
				$total         = edd_get_cart_total( $discounts );

				return array(
					'msg'         => 'valid',
					'amount'      => $amount,
					'total_plain' => $total,
					'total'       => html_entity_decode( edd_currency_filter( edd_format_amount( $total ) ), ENT_COMPAT, 'UTF-8' ),
					'code'        => $discount_code,
					'html'        => edd_get_cart_discounts_html( $discounts )
				);

			}

		} else {
			return $result;
		}

	}

}
