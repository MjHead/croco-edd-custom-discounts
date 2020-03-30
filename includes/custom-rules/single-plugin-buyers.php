<?php
namespace CCDE\Custom_Rules;

class Single_Plugin_Buyers extends Base {

	public function get_id() {
		return 'single-plugin-buyers';
	}

	public function get_name() {
		return 'Single Plugin Buyers';
	}

	public function apply_rule( $code ) {
		
		if ( ! is_user_logged_in() ) {
			$code->set_prop( 'status', 'inactive' );
			return;
		}

		$purchases = edd_get_users_purchases();

		if ( empty( $purchases ) ) {
			$code->set_prop( 'status', 'inactive' );
			return;
		}

		$has_plugin     = false;
		$has_membership = false;
		$amount         = 0;

		foreach ( $purchases as $purchase ) {
			$payment = new \EDD_Payment( $purchase->ID );
				
			if ( $payment->downloads ) {
				foreach ( $payment->downloads as $download ) {
					$d_id = absint( $download['id'] );
					if ( $d_id === crocoblock_core()->main_id() ) {
						$has_membership	= true;
					} else {

						$terms = wp_get_post_terms( $d_id, 'download_category' );

						if ( ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								if ( 'plugins' === $term->slug ) {
									$has_plugin = true;

									$price = edd_get_cart_item_price( $d_id, $download['options'] );

									if ( $price > $amount ) {
										$amount = $price;
									}

								}
							}
						}

					}
				}
			}

		}

		//$has_membership = false;

		if ( $has_membership || ! $has_plugin || ! $amount ) {
			$code->set_prop( 'status', 'inactive' );
			return;
		}

		$code->set_prop( 'amount', $amount );

	}

}
