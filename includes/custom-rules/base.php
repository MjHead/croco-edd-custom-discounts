<?php
namespace CCDE\Custom_Rules;

abstract class Base {

	public function __construct() {
		add_action( 'ccde/edd/gateway/code-found', array( $this, 'init_rule' ) );
	}

	public function init_rule( $code ) {
		
		$meta = $code->get_prop( 'meta' );
		$rule = ! empty( $meta['custom_rule'] ) ? $meta['custom_rule'] : '';

		if ( $this->get_id() !== $rule ) {
			return $code;
		}

		$this->apply_rule( $code );

		return $code;

	}

	abstract public function get_id();

	abstract public function get_name();

	abstract public function apply_rule( $code );

}
