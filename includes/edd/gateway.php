<?php
namespace CCDE\EDD;

use CCDE\Plugin;

/**
 * Edd gateway
 */
class Gateway {

	public $id_offset = 99999;
	public $hits      = array();
	public $misses    = array();

	public function __construct() {

		add_filter( 'edd_custom_discount', function( $result, $_id_or_code_or_name, $by_code, $by_name ) {

			$code   = Plugin::instance()->code_factory->get_code();
			$offset = (string) $this->id_offset;

			if ( in_array( $_id_or_code_or_name, $this->misses ) ) {
				return $result;
			}

			if ( $by_code ) {
				$code = Plugin::instance()->code_factory->get_code( array( 'code' => $_id_or_code_or_name ) );
			} elseif ( ! $by_code && ! $by_name && false !== strpos( $_id_or_code_or_name, $offset ) ) {
				Plugin::instance()->code_factory->set_from_cache();
				$_id_or_code_or_name = str_replace( $this->id_offset, '', $_id_or_code_or_name );
				$code = Plugin::instance()->code_factory->get_code( array( 'ID' => $_id_or_code_or_name ) );
			}

			if ( ! $code->exists() ) {
				$this->misses[] = $_id_or_code_or_name;
				return $result;
			}

			do_action( 'ccde/edd/gateway/code-found', $code );

			$this->hits[] = $_id_or_code_or_name;;

			return new Bridge( $code );

		}, 10, 4 );

	}

}
