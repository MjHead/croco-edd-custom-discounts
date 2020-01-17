<?php
namespace CCDE\Admin\Pages;

use CCDE\Admin\Helpers\Page_Config;
use CCDE\Plugin;

/**
 * Base dashboard page
 */
class Single_Code extends Base {

	/**
	 * Page slug
	 * @return string
	 */
	public function slug() {
		return 'ccde-single-code';
	}

	/**
	 * Page title
	 * @return string
	 */
	public function title() {
		if ( $this->is_edit_page() ) {
			return __( 'Edit Code', 'croco-edd-custom-discounts' );
		} else {
			return __( 'Add Code', 'croco-edd-custom-discounts' );
		}
	}

	/**
	 * Page render funciton
	 * @return void
	 */
	public function render() {
		?>
		<style type="text/css">
			.ccde-single-wrap {
				display: flex;
			}
			.ccde-single-fields {
				flex: 0 0 900px;
				max-width: 900px;
			}
			.ccde-single-actions {
				padding: 0 0 0 40px;
			}
			.code-single-actions-content {
				position: sticky;
				top: 40px;
			}
		</style>
		<div class="wrap"><div id="ccde-single-code"></div></div>
		<?php
	}

	/**
	 * Returns ID from request
	 *
	 * @return [type] [description]
	 */
	public function get_id_from_request() {
		return absint( $_GET[ Plugin::instance()->code_query_var ] );
	}

	/**
	 * Return  page config object
	 *
	 * @return [type] [description]
	 */
	public function page_config() {

		$not_found = false;

		if ( $this->is_edit_page() ) {

			$code = Plugin::instance()->code_factory->get_code( array( 'id' => $this->get_id_from_request() ) );

			if ( ! $code->exists() ) {
				$not_found = true;
			}

		} else {
			$code = Plugin::instance()->code_factory->get_code();
		}

		return new Page_Config(
			$this->slug(),
			array(
				'is_edit'        => $this->is_edit_page(),
				'code'           => $code->get_props(),
				'not_found'      => $not_found,
				'ajax'           => Plugin::instance()->ajax->get_actions(),
				'nonce'          => Plugin::instance()->ajax->nonce(),
				'single_url'     => Plugin::instance()->dashboard->page_url( $this->slug() ),
				'code_key'       => Plugin::instance()->code_query_var,
				'downloads_list' => Plugin::instance()->db->query_posts_for_js( 'download' ),
			)
		);
	}

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {

		$this->enqueue_script( $this->slug(), 'single-code.js' );

	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array(
			'single-code',
		);
	}

	/**
	 * Check if is adeit or is add new code page
	 *
	 * @return boolean [description]
	 */
	public function is_edit_page() {
		return ( ! empty( $_GET[ Plugin::instance()->code_query_var ] ) );
	}

}
