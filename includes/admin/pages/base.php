<?php
namespace CCDE\Admin\Pages;

/**
 * Base dashboard page
 */
abstract class Base {

	/**
	 * Page slug
	 * @return string
	 */
	abstract public function slug();

	/**
	 * Page title
	 * @return string
	 */
	abstract public function title();

	/**
	 * Page render funciton
	 * @return void
	 */
	abstract public function render();

	/**
	 * Return page config array
	 *
	 * @return [type] [description]
	 */
	abstract public function page_config();

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {
	}

	/**
	 * Enqueue all page assets
	 *
	 * @return [type] [description]
	 */
	public function enqueue_assets() {
		$this->assets();
		$config = $this->page_config();
		$config->include( 'CCDEConfig' );
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array();
	}

	/**
	 * Render vue templates
	 *
	 * @return [type] [description]
	 */
	public function render_vue_templates() {
		foreach ( $this->vue_templates() as $template ) {
			if ( is_array( $template ) ) {
				$this->render_vue_template( $template['file'], $template['dir'] );
			} else {
				$this->render_vue_template( $template );
			}
		}
	}

	/**
	 * Render vue template
	 *
	 * @return [type] [description]
	 */
	public function render_vue_template( $template, $path = null ) {

		if ( ! $path ) {
			$path = $this->slug();
		}

		$file = CCDE_PATH . 'templates/' . $path . '/' . $template . '.php';

		if ( ! is_readable( $file ) ) {
			return;
		}

		ob_start();
		include $file;
		$content = ob_get_clean();

		printf(
			'<script type="text/x-template" id="ccde-%1$s-template">%2$s</script>',
			$template,
			$content
		);

	}

	/**
	 * Enqueue script
	 *
	 * @param  [type] $handle    [description]
	 * @param  [type] $file_path [description]
	 * @return [type]            [description]
	 */
	public function enqueue_script( $handle = null, $file_path = null ) {

		wp_enqueue_script(
			$handle,
			CCDE_URL . 'assets/js/' . $file_path,
			array( 'jquery' ),
			CCDE_VERSION . time(),
			true
		);

	}

	/**
	 * Enqueue style
	 *
	 * @param  [type] $handle    [description]
	 * @param  [type] $file_path [description]
	 * @return [type]            [description]
	 */
	public function enqueue_style( $handle = null, $file_path = null ) {

		wp_enqueue_style(
			$handle,
			CCDE_URL . 'assets/css/' . $file_path,
			array(),
			CCDE_VERSION . time()
		);

	}

	/**
	 * Set to true to hide page from admin menu
	 * @return boolean [description]
	 */
	public function is_hidden() {
		return false;
	}

	/**
	 * Returns current page url
	 *
	 * @return [type] [description]
	 */
	public function get_url() {
		return add_query_arg(
			array( 'page' => $this->slug() ),
			esc_url( admin_url( 'admin.php' ) )
		);
	}

}
