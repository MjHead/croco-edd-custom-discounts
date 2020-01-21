<?php
namespace CCDE\Codes;

class Exporter {

	private $columns;
	private $items;

	public function __construct( $columns = array(), $items = array() ) {
		$this->columns = $columns;
		$this->items   = $items;
	}

	/**
	 * Print headers
	 *
	 * @return [type] [description]
	 */
	public function print_headers() {
		echo implode( ',', $this->columns ) . PHP_EOL;
	}

	/**
	 * Print export file content
	 *
	 * @return [type] [description]
	 */
	public function print_content() {

		foreach ( $this->items as $item ) {

			$row = array();

			foreach ( $this->columns as $column ) {

				$content = isset( $item[ $column ] ) ? $item[ $column ] : '';

				if ( strpos( $content, ',' ) ) {
					$content = '"' . $content . '"';
				}

				$row[] = $content;

			}

			echo implode( ',', $row ) . PHP_EOL;

		}

	}

	/**
	 * Send download headers
	 *
	 * @return [type] [description]
	 */
	public function file_headers() {

		set_time_limit( 0 );

		@session_write_close();

		if( function_exists( 'apache_setenv' ) ) {
			@apache_setenv('no-gzip', 1);
		}

		@ini_set( 'zlib.output_compression', 'Off' );

		nocache_headers();

		$filename = 'discount-codes.csv';

		header( "Robots: none" );
		header( "Content-Type: text/csv" );
		header( "Content-Description: File Transfer" );
		header( "Content-Disposition: attachment; filename=\"" . $filename . "\";" );
		header( "Content-Transfer-Encoding: binary" );

		// Set the file size header
		//header( "Content-Length: " . @filesize( $filepath ) );

	}

	/**
	 * Create new code
	 *
	 * @return [type] [description]
	 */
	public function send_file() {

		$this->file_headers();

		$this->print_headers();
		$this->print_content();

		die();

	}

}
