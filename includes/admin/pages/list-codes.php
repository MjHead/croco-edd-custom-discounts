<?php
namespace CCDE\Admin\Pages;

use CCDE\Plugin;
use CCDE\Admin\Helpers\Page_Config;

/**
 * All discount codes list
 */
class List_Codes extends Base {

	/**
	 * Page slug
	 *
	 * @return string
	 */
	public function slug() {
		return 'ccde-list-codes';
	}

	/**
	 * Page title
	 *
	 * @return string
	 */
	public function title() {
		return __( 'Custom Discounts', 'croco-edd-custom-discounts' );
	}

	/**
	 * Page render funciton
	 *
	 * @return void
	 */
	public function render() {
		?>
		<style type="text/css">
			.ccde-header {
				display: flex;
				align-items: center;
				margin-bottom: 20px;
			}
			.ccde-header .cx-vui-title {
				margin-right: 20px;
				padding-bottom: 10px;
			}
		</style>
		<div class="wrap"><div id="ccde-list-codes"></div></div>
		<?php
	}

	/**
	 * Return  page config object
	 *
	 * @return [type] [description]
	 */
	public function page_config() {
		return new Page_Config(
			$this->slug(),
			array(
				'ajax'       => Plugin::instance()->ajax->get_actions(),
				'nonce'      => Plugin::instance()->ajax->nonce(),
				'single_url' => Plugin::instance()->dashboard->page_url( 'ccde-single-code' ),
				'code_key'   => Plugin::instance()->code_query_var,
			)
		);
	}

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {
		$this->enqueue_script( $this->slug(), 'list-codes.js' );
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array(
			'list-codes',
		);
	}

}
