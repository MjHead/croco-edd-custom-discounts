<?php
namespace CCDE\Codes;

class Generator {

	private $secret;

	public function __construct( $secret = null ) {
		$this->secret = $secret;
	}

	/**
	 * Create new code
	 *
	 * @return [type] [description]
	 */
	public function create_code( $index = 0, $size = 7 ) {

		$suffix = md5( microtime() . $this->secret );
		$bytes  = openssl_random_pseudo_bytes( $size );
		$code   = bin2hex( $bytes );

		return $code . substr( $suffix, 5, 10 );

	}

}
