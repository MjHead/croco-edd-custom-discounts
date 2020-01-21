<?php
namespace CCDE;

/**
 * Props helper class
 */
class Props {

	public function props_map() {
		return array(
			'ID' => array(
				'sql'         => 'bigint(20) NOT NULL AUTO_INCREMENT',
				'default_sql' => false,
				'default_val' => false,
				'required'    => false,
			),
			'name' => array(
				'sql'         => 'text',
				'default_sql' => false,
				'default_val' => false,
				'required'    => true,
			),
			'code'    => array(
				'sql'         => 'text',
				'default_sql' => false,
				'default_val' => false,
				'required'    => true,
			),
			'type'   => array(
				'sql'         => 'text',
				'default_sql' => 'percentage',
				'default_val' => 'percentage',
				'required'    => true,
			),
			'amount' => array(
				'sql'         => 'int',
				'default_sql' => false,
				'default_val' => 0,
				'required'    => true,
			),
			'start_date'       => array(
				'sql'         => 'bigint(20) NOT NULL',
				'default_sql' => false,
				'default_val' => false,
				'required'    => false,
			),
			'end_date'       => array(
				'sql'         => 'bigint(20) NOT NULL',
				'default_sql' => false,
				'default_val' => false,
				'required'    => false,
			),
			'max_uses'   => array(
				'sql'         => 'int',
				'default_sql' => 0,
				'default_val' => 0,
				'required'    => false,
			),
			'used'   => array(
				'sql'         => 'int',
				'default_sql' => 0,
				'default_val' => 0,
				'required'    => false,
			),
			'status'   => array(
				'sql'         => 'text',
				'default_sql' => 'active',
				'default_val' => 'active',
				'required'    => false,
			),
			'meta'   => array(
				'sql'         => 'longtext',
				'default_sql' => '',
				'default_val' => array(
					'required_downloads' => array(),
				),
				'required'    => false,
			),
			'generator_hash' => array(
				'sql'         => 'text',
				'default_sql' => false,
				'default_val' => false,
				'required'    => false,
			),
		);
	}

	/**
	 * Returns props list for JS
	 *
	 * @return [type] [description]
	 */
	public function get_props_for_js() {

		$props = array_keys( $this->props_map() );
		$res   = array();

		foreach ( $props as $prop ) {
			$res[] = array(
				'value' => $prop,
				'label' => $prop,
			);
		}

		return $res;

	}

	/**
	 * Returns required props
	 *
	 * @return [type] [description]
	 */
	public function get_required_props() {
		return array_filter( $this->props_map(), function( $prop ) {
			return ! empty( $prop['required'] );
		} );
	}

}
