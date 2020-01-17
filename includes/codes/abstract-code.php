<?php
namespace CCDE\Codes;

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
	 * Returns default props of any code
	 *
	 * @return [type] [description]
	 */
	public function default_props() {
		return array(
			'type' => 'percentage',
			'meta' => array(
				'required_downloads' => array(),
			),
		);
	}

}
