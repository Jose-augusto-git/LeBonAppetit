<?php
/**
 * The main plugin file.
 *
 * @link              https://wpsocio.com
 * @since             1.0.0
 * @package           WPTelegram\FormatText
 *
 * @wordpress-plugin
 * Plugin Name:       WP Telegram Format Text
 * Plugin URI:        https://github.com/wpsocio/wptelegram-format-text
 * Description:       ❌ DO NOT DELETE ❌ WP Loader for WP Telegram Format Text.
 * Version:           999.999.999
 * Author:            WP Socio
 * Author URI:        https://github.com/wpsocio
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wptelegram
 * Domain Path:       /languages
 */

// Namespace doesn't really matter here.
namespace WPTelegram\FormatText;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( __NAMESPACE__ . '\WPLoader_1_0_6', false ) ) {
	/**
	 * Handles checking for and loading the newest version of the library
	 *
	 * Inspired from CMB2 loading technique
	 * to ensure that only the latest version is loaded
	 *
	 * @see https://github.com/CMB2/CMB2/blob/v2.3.0/init.php
	 *
	 * @since  1.0.0
	 *
	 * @category  WordPress_Plugin Addon
	 * @package   WPTelegram\FormatText
	 * @author    WPTelegram team
	 * @license   GPL-3.0+
	 * @link      https://t.me/WPTelegram
	 */
	class WPLoader_1_0_6 {

		/**
		 * Current version number
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		const VERSION = '1.0.6';

		/**
		 * Current version hook priority.
		 * Will decrement with each release
		 *
		 * @var   int
		 * @since 1.0.0
		 */
		const PRIORITY = 9993;

		/**
		 * Single instance of the WPLoader_1_0_6 object
		 *
		 * @var WPLoader_1_0_6
		 */
		private static $instance = null;

		/**
		 * Creates/returns the single instance WPLoader_1_0_6 object
		 *
		 * @since  1.0.0
		 * @return WPLoader_1_0_6 Single instance object
		 */
		public static function initiate() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Starts the version checking process.
		 * Creates WPTELEGRAM_FORMAT_TEXT_LOADED definition for early detection by other scripts
		 *
		 * Hooks the library inclusion to the after_setup_theme hook on a high priority which decrements
		 * (increasing the priority) with each version release.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			/**
			 * Use after_setup_theme hook instead of init
			 * to make the library available during init
			 */
			add_action( 'after_setup_theme', [ $this, 'init' ], self::PRIORITY );
		}

		/**
		 * A final check if the library is already loaded before kicking off our loading.
		 * WPTELEGRAM_FORMAT_TEXT_VERSION constant is set at this point.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function init() {
			if ( defined( 'WPTELEGRAM_FORMAT_TEXT_LOADED' ) ) {
				return;
			}

			/**
			 * A constant you can use to check if the library is loaded
			 */
			define( 'WPTELEGRAM_FORMAT_TEXT_LOADED', self::PRIORITY );

			if ( ! defined( 'WPTELEGRAM_FORMAT_TEXT_VERSION' ) ) {
				define( 'WPTELEGRAM_FORMAT_TEXT_VERSION', self::VERSION );
			}

			if ( ! defined( 'WPTELEGRAM_FORMAT_TEXT_DIR' ) ) {
				define( 'WPTELEGRAM_FORMAT_TEXT_DIR', dirname( __FILE__ ) );
			}

			// Now kick off the class autoloader.
			spl_autoload_register( [ __CLASS__, 'autoload_classes' ] );
		}

		/**
		 * Autoloads files with WPTelegram\FormatText classes when needed
		 *
		 * @since  1.0.0
		 * @param  string $class_name Name of the class being requested.
		 *
		 * @return void
		 */
		public static function autoload_classes( string $class_name ) {
			$namespace = 'WPTelegram\FormatText';

			if ( 0 !== strpos( $class_name, $namespace ) ) {
				return;
			}

			$class_name = str_replace( $namespace, '', $class_name );
			$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );

			$path = WPTELEGRAM_FORMAT_TEXT_DIR . DIRECTORY_SEPARATOR . 'src' . $class_name . '.php';

			include_once $path;
		}
	}
	WPLoader_1_0_6::initiate();
}
