<?php
/**
 * WPChill licence checker class . Handles checking licence expiry date for WPChill plugins.
 *
 * @package wpchill
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wpchill_License_Checker' ) ) {

	/**
	 * Main class for license checker
	 */
	class Wpchill_License_Checker {
		/**
		 * Plugin file
		 *
		 * @var string
		 */
		private $plugin_file;

		/**
		 * Plugin slug
		 *
		 * @var string
		 */
		private $plugin_slug;

		/**
		 * Plugin nice name
		 *
		 * @var string
		 */
		private $plugin_nicename;

		/**
		 * Store url
		 *
		 * @var string
		 */
		private $store_url;

		/**
		 * Item id
		 *
		 * @var int
		 */
		private $item_id;
		/**
		 * Users license.
		 *
		 * @var string
		 */
		private $license = '';

		/**
		 * License status
		 *
		 * @var string
		 */
		private $license_status;

		/**
		 * License data trans
		 *
		 * @var array
		 */
		private $license_data_trans;

		/**
		 * The instance of the class
		 *
		 * @var
		 */
		public static $instance = array();

		/**
		 * Define the core functionality of the class.
		 *
		 * @param array $args args to init the class.
		 */
		public function __construct( $args ) {//@phpcs:ignore

			// If the arguments don't check out we return, means the class can't be initialized correctly
			if ( ! $this->check_args( $args ) ) {
				return;
			}

			// We se out class variables here
			$this->set_class_variables( $args );

			// We set the hooks after we set the variables because inside the hooks we need the variables
			$this->set_hooks();

		}

		/**
		 * Get our class instance
		 *
		 * @param       $slug
		 * @param array $options
		 *
		 * @return mixed|Wpchill_License_Checker|null
		 */
		public static function get_instance( $slug, $options = array() ) {

			if ( ! isset( self::$instance[ $slug ] ) && ! empty( $options ) ) {
				self::$instance[ $slug ] = new Wpchill_License_Checker( $options );
			}

			if ( ! isset( $instance[ $slug ] ) ) {
				return self::$instance[ $slug ];
			} else {
				return null;
			}
		}

		/**
		 * Check the arguments given when creating the instance of the class
		 *
		 * @param $args
		 *
		 * @return bool
		 */
		public function check_args( $args ) {

			if ( ! isset( $args['plugin_slug'] ) || ! isset( $args['plugin_nicename'] ) || ! isset( $args['store_url'] ) || ! isset( $args['license'] ) || ! isset( $args['plugin_file'] ) || ! isset( $args['item_id'] ) || ! isset( $args['license_status'] ) ) {

				return false;
			}

			return true;

		}

		/**
		 * Set our class variables
		 *
		 * @param $args
		 */
		public function set_class_variables( $args ) {

			$this->plugin_file     = $args['plugin_file'];
			$this->plugin_slug     = $args['plugin_slug'];
			$this->plugin_nicename = $args['plugin_nicename'];
			$this->store_url       = $args['store_url'];
			$this->item_id         = $args['item_id'];
			$this->license         = $args['license'];
			$this->license_status  = $args['license_status'];
		}

		/**
		 * Set our hooks
		 */
		public function set_hooks() {

			register_activation_hook( $this->plugin_file, array( $this, 'schedule_tracking' ) );

			add_action( 'admin_init', array( $this, 'init' ) );

			// Modula plugin
			add_filter( 'modula_uninstall_transients', array( $this, 'unintall_transients' ) );
			add_action( 'modula_after_license_save', array( $this, 'delete_transients' ) );
			add_action( 'modula_after_license_deactivated', array( $this, 'delete_transients' ) );

			// Strong Testimonials plugin
			add_filter( 'st_uninstall_transients', array( $this, 'unintall_transients' ) );

			// Set the weekly interval.
			add_filter( 'cron_schedules', array( $this, 'set_weekly_cron_schedule' ) );

			// Hook our check_license_valability function to the weekly action.
			add_action( 'wpchill_st_weekly_license', array( $this, 'check_license_valability' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Already extended action
			add_action( 'wp_ajax_wpchill_check_license_valability', array( $this, 'recheck_availability' ) );

			// Check if slug is from Strong Testimonials as this one has a different approach on admin notices
			// Once everyone will follow same approach this will become more generalized
			if ( 'strong-testimonials' == $this->plugin_slug ) {

				// Set the notice
				add_action( 'admin_notices', array( $this, 'set_notice' ), 5 );

				// Add the HTML used for our notice
				add_filter( 'wpmtst_license-expire_notice', array( $this, 'st_expiry_notice' ) );
			}

		}

		/**
		 * Initialize function.
		 */
		public function init() {

			// Strong Testimonials, for the moment, has a different approach on notices
			if ( 'strong-testimonials' !== $this->plugin_slug ) {
				$this->license_data_trans = get_transient( "wpchill_{$this->plugin_slug}_license_data" );

				if ( $this->license_data_trans && $this->license_data_trans['notice_time'] && 'lifetime' !== $this->license_data_trans['expires'] ) {

					add_action( 'admin_notices', array( $this, 'expiry_notice' ) );
				}
			}

		}

		/**
		 * When the plugin is activated
		 * Create scheduled event
		 * And check if tracking is enabled - perhaps the plugin has been reactivated
		 */
		public function schedule_tracking() {

			if ( ! wp_next_scheduled( 'wpchill_st_weekly_license' ) ) {

				wp_schedule_event( time(), 'weekly', 'wpchill_st_weekly_license' );
			}

			$this->check_license_valability();
		}

		/**
		 * Check license valability function
		 */
		public function check_license_valability() {

			$this->license = $this->get_license();

			// Return if there is no license
			if ( false === $this->license || empty( $this->license ) ) {
				return;
			}

			// data to send in our API request.
			$api_params = array(
					'edd_action' => 'check_license',
					'license'    => $this->license,
					'item_id'    => $this->item_id,
					'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
					$this->store_url,
					array(
							'timeout'   => 15,
							'sslverify' => false,
							'body'      => $api_params,
					)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = esc_html__( 'An error occurred, please try again.', $this->plugin_slug ); //@phpcs:ignore
				}

				wp_send_json_error( $message );
				die();
			}

			// Decode license data and check status and expiry .
			// Add `false` to assicuative - we retrieve an object
			$license_data = json_decode( wp_remote_retrieve_body( $response ), false );

			// Stop the function if license is invalid.
			if ( 'invalid' === $license_data->license || 'lifetime' === $license_data->expires ) {
				return false;
			}

			$license_args = array(
					'expires'     => $license_data->expires,
					'notice_time' => strtotime( $license_data->expires ) < strtotime( '+1 week' ),
			);

			// Check if the license is expired . If it is then set its status to expired.
			if ( strtotime( $license_data->expires ) < strtotime( 'now' ) ) {

				$license_status = get_option( $this->license_status );

				$license_status->license = 'expired';
				update_option( $this->license_status, $license_status );

			}

			// Set the transient that holds the necessary information and expires in a week , when the next time to check is.
			set_transient( "wpchill_{$this->plugin_slug}_license_data", $license_args, 30 * DAY_IN_SECONDS );

			return true;

		}

		/**
		 * Recheck the license availability once user extended the license and the notice is still showing
		 */
		public function recheck_availability() {

			check_admin_referer( 'wpchill-license-checker', 'nonce' );

			// Trigger the action that has all the attached checks
			// We need to trigger this and not do the check_license_valability() method because AJAX is used and we do not know which
			// of the classes present in our plugins is used
			do_action( 'wpchill_st_weekly_license' );

		}

		/**
		 * Enqueue our license checker script
		 */
		public function enqueue_scripts() {

			wp_register_script( 'wpchill-license-checker', plugin_dir_url( __FILE__ ) . 'assets/wpchill.license.checker.js', array( 'jquery' ), false, true );
			wp_register_style( 'wpchill-license-checker', plugin_dir_url( __FILE__ ) . 'assets/wpchill.license.checker.css' );
			wp_enqueue_script( 'wpchill-license-checker' );
			wp_enqueue_style( 'wpchill-license-checker' );
			wp_localize_script( 'wpchill-license-checker', 'WPChill', array( 'nonce' => wp_create_nonce( 'wpchill-license-checker' ) ) );
		}

		/**
		 * Set weekly cron schedule
		 *
		 * @param array $schedules list of recurrences ( daily , hourly , twice daily by default).
		 */
		public function set_weekly_cron_schedule( $schedules ) {

			$schedules['weekly'] = array(
					'interval' => 604800,
					'display'  => __( 'Weekly' ),
			);

			return $schedules;
		}

		/**
		 * Display expiry notice
		 */
		public function expiry_notice() {

			// If notice was dismissed don't show it again
			// Also, if user doesn't have the right capabilities don't show the notice
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$this->notice_html();
		}

		/**
		 * Set our notice using ST's admin notice system. Will look into the possibility to remake the notice system
		 *
		 */
		public function set_notice() {

			$this->license_data_trans = get_transient( "wpchill_{$this->plugin_slug}_license_data" );

			if ( $this->license_data_trans && $this->license_data_trans['notice_time'] && 'lifetime' !== $this->license_data_trans['expires'] ) {
				wpmtst_add_admin_notice( 'license-expire' );
			}
		}

		/**
		 * Display expiry notice for Srong Testimonials
		 *
		 */
		public function st_expiry_notice( $html ) {
			// If notice was dismissed don't show it again
			if ( ! $this->license_data_trans ) {
				return $html;
			}

			ob_start();

			$this->notice_html();

			return ob_get_clean();
		}

		/**
		 * Output the already paid and dismiss buttons
		 *
		 * @return string
		 */
		public function action_buttons() {


			if ( 'modula-best-grid-gallery' === $this->plugin_slug ) {
				$url = admin_url( 'edit.php?post_type=modula-gallery&page=modula' );
			} else {
				$url = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=licenses' );
			}

			return '<div class="wpchill-license-buttons"><a href="' . esc_url( $this->store_url . '/checkout/?edd_action=apply_license_renewal&edd_license_key=' . $this->get_license() ) . '" class="button button-primary" target="_blank">Renew license</a><a href="#" class="wpchill-already-extended button button-secondary" >I already renewed!</a></div>';
		}

		/**
		 * Helper function to retrieve the license
		 */
		public function get_license() {

			return trim( get_option( $this->license ) );
		}

		/**
		 * Add our transients to the uninstall process
		 *
		 * @param $transients
		 *
		 * @return mixed
		 *
		 */
		public function unintall_transients( $transients ) {

			if ( ! isset( $transients["wpchill_{$this->plugin_slug}_license_data"] ) ) {

				$transients[] = "wpchill_{$this->plugin_slug}_license_data";
			}

			return $transients;
		}

		/**
		 * Add our notice HTML
		 *
		 */
		public function notice_html() {

			$date         = $this->license_data_trans['expires'];
			$create_date  = new DateTime( $date );
			$date_no_time = $create_date->format( 'Y-m-d' );
			$license_expiration_text = ( strtotime( $date ) > time() ) ? ' license is about to expire on' : ' license has expired on';
			?>
			<div class='wpchill-license-notice notice notice-warning'>
				<div class="wpchill-license-text">
					<p> Your <?php echo esc_html( $this->plugin_nicename . $license_expiration_text ); ?>  <strong
								style="color:#bd1919"> <?php echo esc_html( $date_no_time ); ?> </strong>
					</p>
				</div>
				<?php echo $this->action_buttons(); ?>
			</div>
			<?php
		}

		/**
		 * Delete transients function
		 *
		 * @return void
		 */
		public function delete_transients(){

			delete_transient( "wpchill_{$this->plugin_slug}_license_data" );
		}

	}
}