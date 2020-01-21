<?php
namespace CCDE\Ajax;

use CCDE\Plugin;
use CCDE\Codes\Generator;

class Generate_Codes extends Abstract_Endpoint {

	/**
	 * Return AJAX hook name
	 *
	 * @return string
	 */
	public function get_hook() {
		return 'generate_codes';
	}

	/**
	 * Return AJAX hook name
	 *
	 * @return array
	 */
	public function ajax_callback() {

		$args    = $this->get_args();
		$props   = $args['code'];
		$number  = $args['number'];
		$offset  = $args['offset'];
		$number  = $number + $offset;

		$generator = new Generator( $props['name'] );
		$hash      = Plugin::instance()->code_factory->create_hash( $props );

		for ( $i = $offset; $i < $number; $i++ ) {

			$code = Plugin::instance()->code_factory->get_code( $props, true );
			$name = $code->get_prop( 'name' );
			$num  = $i + 1;
			$name .= ' #' . $num;

			$code->set_prop( 'generator_hash', $hash );
			$code->set_prop( 'name', $name );
			$code->set_prop( 'code', $generator->create_code( $i ) );

			$code->save();

		}

		return array(
			'success' => true,
			'number'  => $number,
			'hash'    => $hash,
		);

	}

	/**
	 * Return AJAX hook name
	 *
	 * @return boolean
	 */
	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Returns arguments list
	 *
	 * @return [type] [description]
	 */
	public function args() {
		return array(
			'code' => array(
				'default' => array(),
			),
			'number' => array(
				'default' => 100,
			),
			'offset' => array(
				'default' => 0,
			),
		);
	}

}
