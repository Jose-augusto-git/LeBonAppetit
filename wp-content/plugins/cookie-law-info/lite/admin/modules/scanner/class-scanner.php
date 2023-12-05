<?php
/**
 * Class Scanner file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Scanner;

use CookieYes\Lite\Includes\Modules;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Scanner
 * @version     3.0.0
 * @package     CookieYes
 */
class Scanner extends Modules {

	/**
	 * Constructor.
	 */
	public function init() {
		$controller = new \CookieYes\Lite\Admin\Modules\Scanner\Includes\Controller();
		$this->load_apis( $controller );
		add_filter( 'cky_admin_scripts_scanner_config', array( $controller, 'load_scanner_config' ) );
	}

	/**
	 * Load API classes
	 *
	 * @param object $controller Controller object.
	 * @return void
	 */
	public function load_apis( $controller ) {
		$api = new \CookieYes\Lite\Admin\Modules\Scanner\Api\Api( $controller );
	}

	/**
	 * Add admin sub menus
	 *
	 * @return void
	 */
	public function admin_menu() {

	}

	/**
	 * Main menu template
	 *
	 * @return void
	 */
	public function menu_page_template() {
		echo '<div id="cky-app"></div>';
	}
}
