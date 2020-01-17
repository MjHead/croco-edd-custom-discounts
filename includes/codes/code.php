<?php
namespace CCDE\Codes;

class Code extends Abstract_Code {

	private $props = array();

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
	 * Sanitize code for saving
	 *
	 * @return [type] [description]
	 */
	public function sanitize() {

	}

	/**
	 * Save code into DB
	 *
	 * @return [type] [description]
	 */
	public function save() {

	}

	/**
	 * Returns sanitizing errors
	 *
	 * @return [type] [description]
	 */
	public function sanitize_erros() {

	}

}
