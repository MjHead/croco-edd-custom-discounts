<?php
namespace CCDE\Ajax;

use CCDE\Plugin;
use CCDE\Codes\Exporter;

class Export_Codes extends Abstract_Endpoint {

	/**
	 * Return AJAX hook name
	 *
	 * @return string
	 */
	public function get_hook() {
		return 'export_codes';
	}

	/**
	 * Return AJAX hook name
	 *
	 * @return array
	 */
	public function ajax_callback() {

		$args    = $this->get_args();
		$hash    = $args['hash'];
		$columns = $args['columns'];

		if ( ! $hash ) {
			return array(
				'success' => false,
			);
		}

		$columns  = explode( ',', $columns );
		$exporter = new Exporter(
			$columns,
			Plugin::instance()->db->query( array( 'generator_hash' => $hash ) )
		);

		$exporter->send_file();

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
			'hash' => array(
				'default' => '',
			),
			'columns' => array(
				'default' => 'code',
			),
		);
	}

}
