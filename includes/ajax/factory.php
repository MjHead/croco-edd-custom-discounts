<?php
namespace CCDE\Ajax;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Factory {

	private $_endpoints       = array();
	private $current_endpoint = null;
	private $nonce_action     = 'ccde-nonce';

	/**
	 * Constructor for the class
	 *
	 * @param array $endpoints [description]
	 */
	public function __construct( $endpoints = array() ) {
		$this->set_endpoints( $endpoints );
		$this->register_endpoint_actions();
	}

	/**
	 * Setup endpoints list
	 *
	 * @param array $endpoints [description]
	 */
	public function set_endpoints( $endpoints = array() ) {

		foreach ( $endpoints as $endpoint ) {
			$this->register_endpoint( $endpoint );
		}

		do_action( 'ccde/ajax/endpoints', $this );

	}

	/**
	 * Register endpoint
	 *
	 * @param  [type] $endpoint [description]
	 * @return [type]           [description]
	 */
	public function register_endpoint( $endpoint ) {
		$this->_endpoints[ $endpoint->get_hook() ] = $endpoint;
	}

	/**
	 * Register required endpoint actions
	 *
	 * @return [type] [description]
	 */
	public function register_endpoint_actions() {

		if ( empty( $_REQUEST['action'] ) ) {
			return;
		}

		$action = $_REQUEST['action'];

		if ( ! isset( $this->_endpoints[ $action ] ) ) {
			return;
		}

		$endpoint = $this->_endpoints[ $action ];

		add_action( 'wp_ajax_' . $endpoint->get_hook(), array( $this, 'do_ajax_callback' ) );

		if ( $endpoint->nopriv() ) {
			add_action( 'wp_ajax_nopriv_' . $endpoint->get_hook(), array( $this, 'do_ajax_callback' ) );
		}

		$this->current_endpoint = $endpoint;

	}

	/**
	 * Do ajax callback
	 *
	 * @return [type] [description]
	 */
	public function do_ajax_callback() {

		$nonce = ! empty( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : false;

		if ( ! $nonce || ! $this->verify_nonce( $nonce ) ) {
			wp_send_json_error( array( 'message' => 'Nonce verification failed' ) );
		}

		if ( ! $this->current_endpoint->permission_callback() ) {
			wp_send_json_error( array( 'message' => 'Access Denied' ) );
		}

		wp_send_json( $this->current_endpoint->ajax_callback() );

	}

	/**
	 * Returns all registered ajax actions
	 * @return [type] [description]
	 */
	public function get_actions() {
		$endpoints = array_keys( $this->_endpoints );
		return array_combine( $endpoints, $endpoints );
	}

	/**
	 * Returns nonce
	 *
	 * @return [type] [description]
	 */
	public function nonce() {
		return wp_create_nonce( $this->nonce_action );
	}

	/**
	 * Verify nonce
	 *
	 * @return [type] [description]
	 */
	public function verify_nonce( $nonce ) {
		return wp_verify_nonce( $nonce, $this->nonce_action );
	}

}
