<?php
namespace CCDE\Codes;

use CCDE\Plugin;

abstract class Abstract_Code {

	/**
	 * Returns true if this code is exists in DB, false if not
	 *
	 * @return [type] [description]
	 */
	abstract public function exists();

	/**
	 * Returns all registered props and ensure required props is set
	 *
	 * @return [type] [description]
	 */
	abstract public function get_props();

	/**
	 * Returns required props list
	 *
	 * @return [type] [description]
	 */
	public function get_required_props() {
		return Plugin::instance()->props->get_required_props();
	}

	/**
	 * Returns default props of any code
	 *
	 * @return [type] [description]
	 */
	public function default_props() {

		$props_map = Plugin::instance()->props->props_map();
		$result    = array();

		foreach ( $props_map as $prop_name => $prop_data ) {
			if ( ! empty( $prop_data['default_val'] ) ) {
				$result[ $prop_name ] = $prop_data['default_val'];
			}
		}

		return $result;
	}

}
