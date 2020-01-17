<?php
/**
 * Plugin Name: Crocoblock Custom Discounts for EDD
 * Plugin URI:
 * Description: Crocoblock Custom Discounts for EDD
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:
 * Text Domain: croco-edd-custom-discounts
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'plugins_loaded', 'croco_cde_init' );

function croco_cde_init() {

	define( 'CCDE_VERSION', '1.0.0' );

	define( 'CCDE__FILE__', __FILE__ );
	define( 'CCDE_PLUGIN_BASE', plugin_basename( CCDE__FILE__ ) );
	define( 'CCDE_PATH', plugin_dir_path( CCDE__FILE__ ) );
	define( 'CCDE_URL', plugins_url( '/', CCDE__FILE__ ) );

	require CCDE_PATH . 'includes/plugin.php';

}

function ccde() {
	return CCDE\Plugin::instance();
}
