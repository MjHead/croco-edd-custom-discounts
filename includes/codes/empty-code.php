<?php
namespace CCDE\Codes;

class Empty_Code extends Abstract_Code {

	/**
	 * Returns false beacuae is empty code
	 *
	 * @return boolean false
	 */
	public function exists() {
		return false;
	}

	public function get_props() {
		return $this->default_props();
	}

}
