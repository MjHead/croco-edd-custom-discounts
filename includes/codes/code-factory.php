<?php
namespace CCDE\Codes;

use CCDE\Plugin;

class Code_Factory {

	private $found_codes = array();
	private $from_cache  = false;

	/**
	 * Set from_cache falg to true
	 */
	public function set_from_cache() {
		$this->from_cache = true;
	}

	/**
	 * Returns new code by args
	 *
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public function get_code( $args = array(), $by_props = false ) {

		if ( empty( $args ) ) {
			return new Empty_Code();
		}

		if ( $by_props ) {
			$code_instance = new Code( $args );
			return $code_instance;
		}

		if ( isset( $args['code'] ) && ! empty( $this->found_codes[ $args['code'] ] ) ) {
			return $this->found_codes[ $args['code'] ];
		}

		if ( $this->from_cache && ! empty( $args['ID'] ) ) {
			$cached = $this->find_in_cache( $args['ID'] );

			if ( $cached ) {
				return $cached;
			}

		}

		$code = Plugin::instance()->db->get_item( $args );

		if ( ! empty( $code ) ) {
			$code_instance = new Code( $code );
			$this->found_codes[ $code_instance->get_prop( 'code' ) ] = $code_instance;
			return new Code( $code );
		} else {
			return new Empty_Code();
		}

	}

	/**
	 * Find code in codes cache by ID
	 *
	 * @param  [type] $code_id [description]
	 * @return [type]          [description]
	 */
	public function find_in_cache( $code_id ) {

		foreach ( $this->found_codes as $code ) {
			if ( absint( $code->get_prop( 'ID' ) ) === absint( $code_id ) ) {
				return $code;
			}
		}

		return false;
	}

	/**
	 * Create props hash
	 *
	 * @return [type] [description]
	 */
	public function create_hash( $props = array() ) {

		$result = '';

		foreach ( $props as $prop => $value ) {
			if ( ! is_array( $value ) ) {
				$result .= $value;
			} else {
				$result .= $this->create_hash( $value );
			}
		}

		return md5( $result );

	}

}
