<?php
namespace CCDE\Custom_Rules;

class Manager {
	
	private $_rules = array();

	public function __construct() {
		$this->init_rules();
	}

	public function init_rules() {

		$this->register_rule( new Single_Plugin_Buyers() );

		do_action( 'ccde/custom-rules/register-rule', $this );

	}

	public function register_rule( $rule ) {
		$this->_rules[ $rule->get_id() ] = $rule;
	}

	public function get_rules_for_options() {

		$result = array(
			array(
				'value' => '',
				'label' => 'Select custom rule...',
			),
		);

		foreach ( $this->_rules as $rule_id => $rule ) {
			$result[] = array(
				'value' => $rule_id,
				'label' => $rule->get_name(),
			);
		}

		return $result;

	}

}
