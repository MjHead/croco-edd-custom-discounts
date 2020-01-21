<?php
namespace CCDE\Codes;

use CCDE\Plugin;

class Code_Factory {

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
			return new Code( $args );
		}

		$code = Plugin::instance()->db->get_item( $args );

		if ( ! empty( $code ) ) {
			return new Code( $code );
		} else {
			return new Empty_Code();
		}

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
