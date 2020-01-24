<?php
namespace CCDE\EDD;

use CCDE\Plugin;

class Bridge {

	private $code;

	public function __construct( $code ) {
		$this->code = $code;
	}

	/**
	 * Retrieve the ID of the WP_Post object.
	 *
	 * @since 2.7
	 *
	 * @return int Discount ID.
	 */
	public function get_ID() {
		return Plugin::instance()->edd_gateway->id_offset . $this->code->get_prop( 'ID' );
	}

	/**
	 * Retrieve the name of the discount.
	 *
	 * @since 2.7
	 *
	 * @return string Name of the discount.
	 */
	public function get_name() {
		return $this->code->get_prop( 'name' );
	}

	/**
	 * Retrieve the code used to apply the discount.
	 *
	 * @since 2.7
	 *
	 * @return string Discount code.
	 */
	public function get_code() {
		return $this->code->get_prop( 'code' );
	}

	/**
	 * Retrieve the status of the discount
	 *
	 * @since 2.7
	 *
	 * @return string Discount code status (active/inactive).
	 */
	public function get_status() {
		return $this->code->get_prop( 'status' );
	}

	/**
	 * Retrieves the status label of the discount.
	 *
	 * @since 2.9
	 *
	 * @return string Status label for the current discount.
	 */
	public function get_status_label() {
		return $this->code->get_prop( 'status' );
	}

	/**
	 * Retrieve the type of discount.
	 *
	 * @since 2.7
	 *
	 * @return string Discount type (percent or flat amount).
	 */
	public function get_type() {
		return $this->code->get_prop( 'type' );
	}

	/**
	 * Retrieve the discount amount.
	 *
	 * @since 2.7
	 *
	 * @return mixed float Discount amount.
	 */
	public function get_amount() {
		return $this->code->get_prop( 'amount' );
	}

	/**
	 * Retrieve the discount requirements for the discount to be satisfied.
	 *
	 * @since 2.7
	 *
	 * @return array IDs of required downloads.
	 */
	public function get_product_reqs() {
		return $this->code->get_meta( 'required_downloads' );
	}

	/**
	 * Retrieve the downloads that are excluded from having this discount code applied.
	 *
	 * @since 2.7
	 *
	 * @return array IDs of excluded downloads.
	 */
	public function get_excluded_products() {
		return array();
	}

	/**
	 * Retrieve the start date.
	 *
	 * @since 2.7
	 *
	 * @return string Start date.
	 */
	public function get_start() {
		$start_date = $this->code->get_prop( 'start_date' );

		if ( $start_date ) {
			return date( 'm/d/Y', $this->code->get_prop( 'start_date' ) );
		} else {
			return false;
		}

	}

	/**
	 * Retrieve the end date.
	 *
	 * @since 2.7
	 *
	 * @return string End date.
	 */
	public function get_expiration() {

		$start_date = $this->code->get_prop( 'end_date' );

		if ( $start_date ) {
			return date( 'm/d/Y', $this->code->get_prop( 'end_date' ) );
		} else {
			return false;
		}

	}

	/**
	 * Retrieve the uses for the discount code.
	 *
	 * @since 2.7
	 *
	 * @return int Uses.
	 */
	public function get_uses() {
		return $this->code->get_prop( 'used' );
	}

	/**
	 * Retrieve the maximum uses for the discount code.
	 *
	 * @since 2.7
	 *
	 * @return int Maximum uses.
	 */
	public function get_max_uses() {
		return $this->code->get_prop( 'max_uses' );
	}

	/**
	 * Retrieve the minimum spend required for the discount to be satisfied.
	 *
	 * @since 2.7
	 *
	 * @return mixed float Minimum spend.
	 */
	public function get_min_price() {
		return 0;
	}

	/**
	 * Retrieve the usage limit per limit (if the discount can only be used once per customer).
	 *
	 * @since 2.7
	 *
	 * @return bool Once use per customer?
	 */
	public function get_is_single_use() {
		return false;
	}

	/**
	 * Retrieve the property determining if a discount is not global.
	 *
	 * @since 2.7
	 *
	 * @return bool Whether or not the discount code is global.
	 */
	public function get_is_not_global() {
		return false;
	}

	/**
	 * Retrieve the product condition.
	 *
	 * @since 2.7
	 *
	 * @return string Product condition
	 */
	public function get_product_condition() {
		return false;
	}

	/**
	 * Increase discount usage
	 *
	 * @return [type] [description]
	 */
	public function increase_usage() {

		$used     = $this->code->get_prop( 'used' );
		$max_uses = $this->code->get_prop( 'max_uses' );

		if ( $used ) {
			$used++;
		} else {
			$used = 1;
		}

		$this->code->set_prop( 'used', $used );

		if ( $max_uses == $used ) {
			$this->code->set_prop( 'status', 'inactive' );
		}

		$this->code->save();

		return $used;

	}

	/**
	 * Increase discount usage
	 *
	 * @return [type] [description]
	 */
	public function decrease_usage() {

		$used     = $this->code->get_prop( 'used' );
		$max_uses = $this->code->get_prop( 'max_uses' );

		if ( $used ) {
			$used--;
		}

		if ( $used < 0 ) {
			$used = 0;
		}

		$this->code->set_prop( 'used', $used );

		if ( $max_uses > $this->used ) {
			$this->code->set_prop( 'status', 'active' );
		}

		$this->code->save();

		return $used;

	}

	public function update( $args = array() ) {

		foreach ( $args as $key => $value ) {
			if ( $this->code->prop_exists( $key ) ) {
				$this->code->set_prop( $key, $value );
			} else {
				$this->code->set_meta( $key, $value );
			}
		}

		$this->code->save();

	}

	/**
	 * Update discount status
	 *
	 * @return [type] [description]
	 */
	public function update_status( $new_status ) {

		$old_status = $this->code->get_prop( 'used' );

		if ( $old_status !== $new_status ) {
			$this->code->set_prop( 'status', $new_status );
			$this->code->save();
		}

	}

	public function get_meta( $key ) {

		if ( $this->code->prop_exists( $key ) ) {
			return $this->code->get_prop( $key );
		} else {
			return $this->code->get_meta( $key );
		}

	}

	/**
	 * Update code data
	 *
	 * @return [type] [description]
	 */
	public function update_meta( $key, $value ) {

		if ( $this->code->prop_exists( $key ) ) {
			$this->code->set_prop( $key, $value );
		} else {
			$this->code->set_meta( $key, $value );
		}

		$this->code->save();

	}

}
