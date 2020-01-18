<?php
namespace CCDE\Codes;

use CCDE\Plugin;

class Code extends Abstract_Code {

	private $props  = array();
	private $errors = array();

	/**
	 * Set code props
	 *
	 * @param array $props
	 */
	public function __construct( $props = array() ) {

		$this->props = $props;

		foreach ( $this->default_props() as $prop => $value ) {

			if ( 'meta' !== $prop ) {
				if ( ! isset( $this->props[ $prop ] ) ) {
					$this->props[ $prop ] = $value;
				}
			} else {

				if ( empty( $this->props['meta'] ) ) {
					$this->props['meta'] = array();
				} elseif ( ! is_array( $this->props['meta'] ) ) {
					$this->props['meta'] = maybe_unserialize( $this->props['meta'] );
				}

				foreach ( $value as $meta_prop => $meta_value ) {
					if ( ! isset( $this->props['meta'][ $meta_prop ] ) ) {
						$this->props['meta'][ $meta_prop ] = $meta_value;
					}
				}
			}
		}

	}

	/**
	 * Check id this code has property
	 *
	 * @param  [type]  $prop [description]
	 * @return boolean       [description]
	 */
	public function has_prop( $prop ) {
		return ! empty( $this->props[ $prop ] );
	}

	/**
	 * Check if is correctly defined code
	 *
	 * @return boolean true
	 */
	public function exists() {
		return $this->has_prop( 'ID' );
	}

	/**
	 * Returns all registered props and ensure required props is set
	 *
	 * @return [type] [description]
	 */
	public function get_props() {
		return $this->props;
	}

	/**
	 * Set single prop value
	 *
	 * @param [type] $prop  [description]
	 * @param [type] $value [description]
	 */
	public function set_prop( $prop, $value ) {
		$this->props[ $prop ] = $value;
	}

	/**
	 * Returns requested prop value
	 *
	 * @param  [type] $prop [description]
	 * @return [type]       [description]
	 */
	public function get_prop( $prop ) {
		return isset( $this->props[ $prop ] ) ? $this->props[ $prop ] : false;
	}

	/**
	 * Check reuired props
	 *
	 * @return [type] [description]
	 */
	public function check_required_props() {

		$required     = $this->get_required_props();
		$missed_props = array();

		foreach ( $required as $prop => $data ) {

			if ( $this->has_prop( $prop ) ) {
				continue;
			}

			if ( ! empty( $data['default_val'] ) ) {
				$this->set_prop( $prop, $data['default_val'] );
			} else {
				$missed_props[] = $prop;
			}

		}

		if ( empty( $missed_props ) ) {
			return true;
		} else {
			return $this->return_error( 'Required props not set: ' . implode( ', ', $missed_props ) );
		}

	}

	/**
	 * Adds sanitize error
	 */
	public function return_error( $message ) {

		if ( ! in_array( $message, $this->errors ) ) {
			$this->errors[] = $message;
		}

		return false;
	}

	/**
	 * Sanitize code for saving
	 *
	 * @return [type] [description]
	 */
	public function sanitize() {

		if ( ! $this->check_required_props() ) {
			return false;
		}

		if ( ! $this->sanitize_raw_code() ) {
			return false;
		}

		return true;
	}

	/**
	 * Sanitize code prop
	 * @return [type] [description]
	 */
	public function sanitize_raw_code() {

		$code = $this->get_prop( 'code' );

		// check if code with the same value already exists
		$code = Plugin::instance()->code_factory->get_code( array( 'code' => $this->props['code'] ) );

		if ( $code->exists() && ! $this->get_prop( 'ID' ) ) {
			return $this->return_error( 'The same code already exists' );
		} elseif ( $code->exists() && $this->get_prop( 'ID' ) !== $code->get_prop( 'ID' ) ) {
			return $this->return_error( 'The same code already exists' );
		}

		return true;

	}

	/**
	 * Convert dates-related props to timestamps
	 *
	 * @return [type] [description]
	 */
	public function dates_to_timestamp() {

		$start_date = $this->get_prop( 'start_date' );
		$end_date   = $this->get_prop( 'end_date' );

		if ( $start_date ) {
			$start_date = strtotime( $start_date );
			$this->set_prop( 'start_date', $start_date );
		}

		if ( $end_date ) {
			$end_date = strtotime( $end_date . ' 23:59:59' );
			$this->set_prop( 'end_date', $end_date );
		}

	}

	/**
	 * Convert dates-related props to timestamps
	 *
	 * @return [type] [description]
	 */
	public function timestamps_to_date( $format = 'Y-m-d' ) {

		$start_date = $this->get_prop( 'start_date' );
		$end_date   = $this->get_prop( 'end_date' );

		if ( $start_date ) {
			$start_date = date( $format, $start_date );
			$this->set_prop( 'start_date', $start_date );
		} else {
			$this->set_prop( 'start_date', false );
		}

		if ( $end_date ) {
			$end_date = date( $format, $end_date );
			$this->set_prop( 'end_date', $end_date );
		} else {
			$this->set_prop( 'end_date', false );
		}

	}

	/**
	 * Save code into DB
	 *
	 * @return [type] [description]
	 */
	public function save() {

		$props   = $this->get_props();
		$code_id = false;

		if ( ! empty( $props['ID'] ) ) {

			$code_id = $props['ID'];
			unset( $props['ID'] );

			Plugin::instance()->db->update( $props, array( 'ID' => $code_id ) );

		} else {
			$code_id = Plugin::instance()->db->insert( $props );
		}

		return $code_id;

	}

	/**
	 * Returns sanitizing errors
	 *
	 * @return [type] [description]
	 */
	public function sanitize_errors() {
		if ( ! empty( $this->errors ) ) {
			return implode( '; ', $this->errors );
		} else {
			return null;
		}
	}

}
