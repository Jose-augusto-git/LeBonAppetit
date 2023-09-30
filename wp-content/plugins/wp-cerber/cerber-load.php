<?php
/*
	Copyright (C) 2015-23 CERBER TECH INC., https://wpcerber.com

    Licenced under the GNU GPL.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/*

*========================================================================*
|                                                                        |
|	       ATTENTION!  Do not change or edit this file!                  |
|                                                                        |
*========================================================================*

*/

// If this file is called directly, abort executing.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

const CERBER_LOG_TABLE = 'cerber_log';
const CERBER_QMEM_TABLE = 'cerber_qmem';
const CERBER_TRAF_TABLE = 'cerber_traffic';
const CERBER_ACL_TABLE = 'cerber_acl';
const CERBER_BLOCKS_TABLE = 'cerber_blocks';
const CERBER_LAB_TABLE = 'cerber_lab';
const CERBER_LAB_IP_TABLE = 'cerber_lab_ip';
const CERBER_LAB_NET_TABLE = 'cerber_lab_net';
const CERBER_GEO_TABLE = 'cerber_countries';
const CERBER_SCAN_TABLE = 'cerber_files';

const CERBER_DB_TYPES = array(
	CERBER_SCAN_TABLE => array(
		'scan_id'     => 'int',
		'scan_type'   => 'int',
		'scan_mode'   => 'int',
		'scan_status' => 'int',
		'scan_step'   => 'int',
	),
);

const CERBER_SETS_TABLE = 'cerber_sets';
const CERBER_MS_TABLE = 'cerber_ms';
const CERBER_MS_LIST_TABLE = 'cerber_ms_lists';
const CERBER_USS_TABLE = 'cerber_uss';

const CERBER_BUKEY = '_crb_blocked';
const CERBER_PREFIX = '_cerber_';
const CERBER_MARKER1 = 'WP CERBER GROOVE';
const CERBER_MARKER2 = 'WP CERBER CLAMPS';
const CERBER_NO_REMOTE_IP = '0.0.0.0';

const WP_LOGIN_SCRIPT = 'wp-login.php';
const WP_REG_URI = 'wp-register.php';
const WP_SIGNUP_SCRIPT = 'wp-signup.php';
const WP_XMLRPC_SCRIPT = 'xmlrpc.php';
const WP_TRACKBACK_SCRIPT = 'wp-trackback.php';
const WP_PING_SCRIPT = 'wp-trackback.php';
const WP_COMMENT_SCRIPT = 'wp-comments-post.php';

const GOO_RECAPTCHA_URL = 'https://www.google.com/recaptcha/api/siteverify';

const CERBER_REQ_PHP = '7.0';
const CERBER_REQ_WP = '4.9';
const CERBER_TECH = 'https://cerber.tech/';

const CERBER_CIREC_LIMIT = 30; // Upper limit for allowed nested values during inspection for malware

const CRB_USER_SET = 'cerber_user';
const CRB_SITE_SET = 'cerber_site_meta';

const CRB_ISSUE_SET = 'cerber_issues';

const CRB_CNTX_SAFE = 1;
const CRB_CNTX_NEXUS = 2;

const CRB_ACT_PARAMS = array(
	'filter_activity' => 'activity',
	'filter_status'   => 'ac_status',
	'filter_set'      => false,
	'filter_ip'       => 'ip',
	'filter_login'    => 'user_login',
	'filter_user'     => 'user_id',
	'search_activity' => false,
	'filter_sid'      => 'session_id',
	'search_url'      => false,
	'filter_country'  => 'country',
);

// Note: values is not specified yet

const CRB_TRF_PARAMS = array(
	'filter_sid'            => 'to_be_specified',
	'filter_http_code'      => 'to_be_specified',
	'filter_http_code_mode' => 'to_be_specified',
	'filter_ip'             => 'to_be_specified',
	'filter_processing'     => 'to_be_specified',
	'filter_user'           => 'to_be_specified',
	'filter_user_alt'       => 'to_be_specified',
	'filter_user_mode'      => 'to_be_specified',
	'filter_wp_type'        => 'to_be_specified',
	'filter_wp_type_mode'   => 'to_be_specified',
	'search_traffic'        => 'to_be_specified',
	'filter_method'         => 'to_be_specified',
	'filter_activity'       => 'to_be_specified',
	'filter_set'            => 'to_be_specified',
	'filter_errors'         => 'to_be_specified',
);

require_once( __DIR__ . '/cerber-pluggable.php' );
require_once( __DIR__ . '/cerber-common.php' );
require_once( __DIR__ . '/cerber-settings.php' );
include_once( __DIR__ . '/cerber-request.php' );
require_once( __DIR__ . '/cerber-lab.php' );
require_once( __DIR__ . '/cerber-whois.php' );
require_once( __DIR__ . '/cerber-scanner.php' );
require_once( __DIR__ . '/cerber-2fa.php' );
require_once( __DIR__ . '/nexus/cerber-nexus.php' );
require_once( __DIR__ . '/cerber-ds.php' );
require_once( __DIR__ . '/cerber-addons.php' );

nexus_init();

if ( defined( 'WP_ADMIN' ) || defined( 'WP_NETWORK_ADMIN' ) ) {
	cerber_load_admin_code();
}

// =============================================================================================

class WP_Cerber {
	private $remote_ip;
	private $session_id;
	private $status = null;
	private $options;

	private $recaptcha = null; // Can recaptcha be verified with current request
	private $recaptcha_verified = null; // Is recaptcha successfully verified with current request
	public $recaptcha_here = null; // Is recaptcha widget enabled on the currently displayed page

	private $uri_prohibited = null;
	private $deny = null;
	private $acl = null;

	//private $boot_source_file = '';
	//private $boot_target_file = '';

	public $garbage = false; // Garbage has been deleted

	final function __construct() {

		$this->session_id = crb_random_string( 24 );

		$this->options = crb_get_settings();

		$this->remote_ip = cerber_get_remote_ip();

		$this->reCaptchaInit();

		$this->deleteGarbage();

		// Condition to check reCAPTCHA

		add_action( 'login_init', array( $this, 'reCaptchaNow' ) );

	}

	/**
	 * @since 6.3.3
	 */
	final public function isURIProhibited() {

		if ( isset( $this->uri_prohibited ) ) {
			return $this->uri_prohibited;
		}

		if ( crb_acl_is_white() ) {
			$this->uri_prohibited = false;

			return false;
		}

		$script = cerber_last_uri();
		$script = urldecode( $script ); // @since 8.1
		if ( substr( $script, - 4 ) != '.php' ) {
			$script .= '.php'; // Apache MultiViews enabled?
		}

		if ( $script ) {
			if ( $script == WP_LOGIN_SCRIPT
			     || $script == WP_SIGNUP_SCRIPT
			     || ( $script == WP_REG_URI && ! get_option( 'users_can_register' ) ) ) {
				if ( ! empty( $this->options['wplogin'] ) ) {
					CRB_Globals::$act_status = 19;
					cerber_log( CRB_EV_PUR );
					cerber_soft_block_add( $this->remote_ip, 702, $script );
					$this->uri_prohibited = true;

					return true;
				}
				if ( ( ! empty( $this->options['loginnowp'] ) && $this->options['loginnowp'] != 2 )
				     || $this->isDeny() ) {
					CRB_Globals::$act_status = 10; // @since 8.9.5.6
					cerber_log( CRB_EV_PUR );
					$this->uri_prohibited = true;

					return true;
				}
			}
            elseif ( $script == WP_XMLRPC_SCRIPT || $script == WP_TRACKBACK_SCRIPT ) {
				if ( ! empty( $this->options['xmlrpc'] )
				     || $this->isDeny() ) {
					cerber_log( 71 );
					$this->uri_prohibited = true;

					return true;
				}
				if ( ! cerber_geo_allowed( 'geo_xmlrpc' ) ) {
					CRB_Globals::$act_status = 16;
					cerber_log( 71 );
					$this->uri_prohibited = true;

					return true;
				}
			}
			// @since 8.8
            elseif ( $script == WP_COMMENT_SCRIPT && cerber_is_custom_comment() ) {
				cerber_log( CRB_EV_PUR );
				$this->uri_prohibited = true;

				return true;
			}
		}

		$this->uri_prohibited = false;

		return $this->uri_prohibited;
	}

	/**
	 * @since 6.3.3
	 */
	final public function CheckProhibitedURI(){
		if ($this->isURIProhibited()){
			if ( $this->options['page404'] ) {
				cerber_404_page();
			}

			return true;
		}

		return false;
	}

	/**
	 * @since 6.3.3
	 */
	final public function InspectRequest() {
		$deny = false;
		$act  = 18;

		if ( cerber_is_http_post() ) {
			if ( ! cerber_is_ip_allowed( null, CRB_CNTX_SAFE ) ) {
				$deny = true;
				$act  = 18;
			}
		}
        elseif ( cerber_get_non_wp_fields() ) {
			if ( ! cerber_is_ip_allowed( null, CRB_CNTX_SAFE ) ) {
				$deny = true;
				$act = 100;
			}
		}

		if ( ! $deny && ( $files = CRB_Request::get_files() ) ) {
			foreach ( $files as $item ) {
				if ( $reason = $this->isProhibitedFilename( $item['source_name'] ) ) {
					$deny = true;
					$act = $reason;
					break;
				}
			}
		}


		if ( $deny ) {
			cerber_log( $act );
			cerber_forbidden_page();
		}
	}

	/**
	 * @since 6.3.3
	 */
	final public function isProhibitedFilename( $file_name ) {

		$prohibited = array( '.htaccess' );
		if ( in_array( $file_name, $prohibited ) ) {
			CRB_Globals::$act_status = CRB_STS_52;
			return 57;
		}

		if ( cerber_detect_exec_extension( $file_name, array('js') ) ) {
			CRB_Globals::$act_status = CRB_STS_51;
			return 56;
		}

		return false;
	}

	/**
	 * @since 6.3.3
	 */
	final public function isDeny() {
		if ( isset( $this->deny ) ) {
			return $this->deny;
		}

		$this->acl = cerber_acl_check();

		if ( $this->acl == 'B' || ! cerber_is_ip_allowed() ) {
			$this->deny = true;
		}
		else {
			$this->deny = false;
		}

		return $this->deny;
	}

	final public function getRequestID() {
		return $this->session_id;
	}

	final public function getStatus() {
		if (isset($this->status)) return $this->status;

		$this->status = 0; // Default

		if ( cerber_is_citadel() ) {
			$this->status = 3;
		}
		else {
			//if ( ! cerber_is_allowed( $this->remote_ip ) ) {
			if ( cerber_block_check( $this->remote_ip ) ) {
				$this->status = 2;
			}
			else {
				$tag = cerber_acl_check( $this->remote_ip );
				if ( $tag == 'W' ) {
					//$this->status = 4;
				}
				elseif ( $tag == 'B' || lab_is_blocked($this->remote_ip, false)) {
					$this->status = 1;
				}
			}
		}

		return $this->status;
	}

	/*
		Return Error message in context
	*/
	final public function getErrorMsg() {
		$status = $this->getStatus();
		switch ( $status ) {
			case 1:
			case 3:
				return apply_filters( 'cerber_msg_blocked', __( 'You are not allowed to log in. Ask your administrator for assistance.', 'wp-cerber' ) , $status);
			case 2:
				$block = cerber_get_block();
				$min   = 1 + ( $block->block_until - time() ) / 60;

				return apply_filters( 'cerber_msg_reached',
					sprintf( __( 'You have exceeded the number of allowed login attempts. Please try again in %d minutes.', 'wp-cerber' ), $min ),
					$min );
				break;
			default:
				return __( 'You are not allowed to log in', 'wp-cerber' );
		}
	}

	/*
		Return Remain message in context
	*/
	final public function getRemainMsg() {
		$acl = ! $this->options['limitwhite'];
		$remain = cerber_get_remain_count( $this->remote_ip, $acl );
		if ( $remain < $this->options['attempts'] ) {
			if ( $remain == 0 ) {
				$remain = 1;  // with some settings or when lockout was manually removed, we need to have 1 attempt.
			}

			if ( $remain == 1 ) {
				$msg = __( 'You have only one login attempt remaining.', 'wp-cerber' );
			}
			else {
				$msg = sprintf( _n( 'You have %d login attempt remaining.', 'You have %d login attempts remaining.', $remain, 'wp-cerber' ), $remain );
			}

			return apply_filters( 'cerber_msg_remain', $msg, $remain );
		}

		return false;
	}

	final public function getSettings( $name = null ) {
		if ( ! empty( $name ) ) {
			if ( isset( $this->options[ $name ] ) ) {
				return $this->options[ $name ];
			} else {
				return false;
			}
		}

		return $this->options;
	}

	/**
	 * Adding reCAPTCHA widgets
	 *
	 */
	final public function reCaptchaInit(){

		if ( $this->status == 4
		     || empty( $this->options['sitekey'] )
		     || empty( $this->options['secretkey'] )
		     || ( crb_get_settings( 'recapipwhite' ) && crb_acl_is_white() ) ) {
			return;
		}

		// Native WP forms
		add_action( 'login_form', function () {
			get_wp_cerber()->reCaptcha( 'widget', 'recaplogin' );
		} );
		add_filter( 'login_form_middle', function ( $value ) {
			$value .= get_wp_cerber()->reCaptcha( 'widget', 'recaplogin', false );
			return $value;
		});
		add_action( 'lostpassword_form', function () {
			get_wp_cerber()->reCaptcha( 'widget', 'recaplost' );
		} );
		add_action( 'register_form', function () {
			if ( ! did_action( 'woocommerce_register_form_start' ) ) {
				get_wp_cerber()->reCaptcha( 'widget', 'recapreg' );
			}
		} );

		// Support for WooCommerce forms: @since 3.8

		add_action( 'woocommerce_login_form', function () {
			get_wp_cerber()->reCaptcha( 'widget', 'recapwoologin' );
		} );
		add_action( 'woocommerce_lostpassword_form', function () {
			get_wp_cerber()->reCaptcha( 'widget', 'recapwoolost' );
		} );
		add_action( 'woocommerce_register_form', function () {
			if ( ! did_action( 'woocommerce_register_form_start' ) ) {
				return;
			}
			get_wp_cerber()->reCaptcha( 'widget', 'recapwooreg' );
		} );
		add_filter( 'woocommerce_process_login_errors', function ( $validation_error ) {
			$wp_cerber = get_wp_cerber();
			//$wp_cerber->reCaptchaNow();
			if ( ! $wp_cerber->reCaptchaValidate( 'woologin', true ) ) {

				return new WP_Error( 'incorrect_recaptcha', $wp_cerber->reCaptchaMsg( 'woocommerce-login' ) );
			}

			return $validation_error;
		} );

		add_filter( 'allow_password_reset', function ( $var ) {
			static $done; // 'allow_password_reset' is fired in WooCommerce and WP (twice in different functions)

			if ( ! $done && crb_is_woo_reset() ) {
				$done = true;
				$wp_cerber = get_wp_cerber();
	            $login = crb_get_user_login_field();

                //$wp_cerber->reCaptchaNow();
				if ( ! $wp_cerber->reCaptchaValidate( 'woolost', true ) ) {

					cerber_log( CRB_EV_PRD, $login );

					return new WP_Error( 'incorrect_recaptcha', $wp_cerber->reCaptchaMsg( 'woocommerce-lost' ) );
				}

	            cerber_log( CRB_EV_PRS, $login );
			}

			return $var;
		}, PHP_INT_MAX );

		add_filter( 'woocommerce_process_registration_errors', function ( $validation_error ) {
			$wp_cerber = get_wp_cerber();
			//$wp_cerber->reCaptchaNow();
			if ( ! $wp_cerber->reCaptchaValidate( 'wooreg', true ) ) {

				cerber_log( 54 );

				return new WP_Error( 'incorrect_recaptcha', $wp_cerber->reCaptchaMsg( 'woocommerce-register' ) );
			}

			return $validation_error;
		} );

	}

	/**
	 * Generates reCAPTCHA HTML
	 *
	 * @param string $part  'style' or 'widget'
	 * @param null $option  what plugin setting must be set to show the reCAPTCHA
	 * @param bool $echo    if false, return the code, otherwise show it
	 *
	 * @return null|string
	 */
	final public function reCaptcha( $part = '', $option = null, $echo = true ) {
		if ( $this->status == 4 || empty( $this->options['sitekey'] ) || empty( $this->options['secretkey'] )
		     || ( $option && empty( $this->options[ $option ] ) )
		) {
			return null;
		}

		$sitekey = $this->options['sitekey'];
		$ret     = '';

		switch ( $part ) {
			case 'style': // for default login WP form only - fit it in width nicely.
				?>
				<style>
					#rc-imageselect, .g-recaptcha {
						transform: scale(0.9);
						-webkit-transform: scale(0.9);
						transform-origin: 0 0;
						-webkit-transform-origin: 0 0;
					}

					.g-recaptcha {
						margin: 16px 0 20px 0;
					}
				</style>
				<?php
				break;
			case 'widget':
				if ( ! empty( $this->options[ $option ] ) ) {
					$this->recaptcha_here = true;

					//if ($this->options['invirecap']) $ret = '<div data-size="invisible" class="g-recaptcha" data-sitekey="' . $sitekey . '" data-callback="now_submit_the_form" id="cerber-recaptcha" data-badge="bottomright"></div>';
					if ($this->options['invirecap']) {
						$ret = '<span class="cerber-form-marker"></span><div data-size="invisible" class="g-recaptcha" data-sitekey="' . $sitekey . '" data-callback="now_submit_the_form" id="cerber-recaptcha" data-badge="bottomright"></div>';
					}
					else $ret = '<span class="cerber-form-marker"></span><div class="g-recaptcha" data-sitekey="' . $sitekey . '" data-callback="form_button_enabler" id="cerber-recaptcha"></div>';

					//$ret = '<span class="cerber-form-marker g-recaptcha"></span>';

				}
				break;
		}
		if ( $echo ) {
			echo $ret;
			$ret = null;
		}

		return $ret;
		/*
			<script type="text/javascript">
				var onloadCallback = function() {
					//document.getElementById("wp-submit").disabled = true;
					grecaptcha.render("c-recaptcha", {"sitekey" : "<?php echo $sitekey; ?>" });
					//document.getElementById("wp-submit").disabled = false;
				};
			</script>
			<script src = "https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=<?php echo $lang; ?>" async defer></script>
			*/
	}

	/**
	 * Validate reCAPTCHA by calling Google service
     * Returns true on success or if validation is not needed (reCAPTCHA is not enabled for the given form)
	 *
	 * @param string $form  Form ID (slug)
	 * @param boolean $force Force validation without pre-checks
	 *
	 * @return bool true on success false on failure
	 */
	final public function reCaptchaValidate( $form = null, $force = false ) {

		if ( crb_get_settings( 'recapipwhite' ) && crb_acl_is_white() ) {
			return true;
		}

		if ( ! $force ) {
			if ( ! $this->recaptcha || $this->status == 4 ) {
				return true;
			}
		}

		if ( $this->recaptcha_verified != null ) {
			return $this->recaptcha_verified;
		}

		if ( $form == 'comment' && $this->options['recapcomauth'] && is_user_logged_in() ) {
			return true;
		}

		if ( ! $form ) {
			$form = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
		}

		$forms = array( // known pairs: form => specific plugin setting
			'lostpassword' => 'recaplost',
			'register'     => 'recapreg',
			'login'        => 'recaplogin',
			'comment'      => 'recapcom',
			'woologin'     => 'recapwoologin',
			'woolost'      => 'recapwoolost',
			'wooreg'       => 'recapwooreg',
		);

		if ( isset( $forms[ $form ] ) ) {
			if ( empty( $this->options[ $forms[ $form ] ] ) ) {
				return true; // no validation is required
			}
		}
		else {
			return true; // we don't know this form
		}

        // Generic status = reCAPTCHA verification failed
		CRB_Globals::set_bot_status( CRB_STS_532 );

		if ( empty( $_POST['g-recaptcha-response'] ) ) {
            // Among other issues it means invalid reCAPTCHA key or/and secret
			$this->reCaptchaFailed( $form );

			return false;
		}

		$result = $this->reCaptchaRequest( $_POST['g-recaptcha-response'] );
		if ( ! $result ) {
			//cerber_log( 42 );
			CRB_Globals::set_bot_status( 534 );

			return false;
		}

		if ( ! empty( $result['success'] ) ) {
			$this->recaptcha_verified = true;
			CRB_Globals::set_bot_status( 531 );

			return true;
		}

		$this->recaptcha_verified = false;

		if ( ! empty( $result['error-codes'] ) ) {
			if ( in_array( 'invalid-input-secret', (array) $result['error-codes'] ) ) {
				//cerber_log( 41 );
				CRB_Globals::set_bot_status( 533 );
			}
		}

		$this->reCaptchaFailed( $form );

		return false;
	}

	final function reCaptchaFailed( $context = '' ) {

		if ( $this->options['recaptcha-period']
             && $this->options['recaptcha-number']
             && $this->options['recaptcha-within'] ) {

			if ( crb_acl_is_white() ) {
				return;
			}

			$range = time() - absint( $this->options['recaptcha-within'] ) * 60;

			$num = cerber_db_get_var( 'SELECT count(ip) FROM ' . CERBER_LOG_TABLE . ' WHERE ip = "' . $this->remote_ip . '" AND ac_bot = ' . CRB_STS_532 . ' AND stamp > ' . $range );

			$num ++; // Current failed attempt

			if ( $num >= $this->options['recaptcha-number'] ) {
				cerber_block_add( $this->remote_ip, 705 );
			}

		}

	}

	/**
	 * A form with possible reCAPTCHA has been submitted.
	 * Allow to process reCAPTCHA by setting a global flag.
	 * Must be called before reCaptchaValidate();
	 *
	 */
	final public function reCaptchaNow() {
		if ( cerber_is_http_post() && $this->options['sitekey'] && $this->options['secretkey'] ) {
			$this->recaptcha = true;
		}
	}

	/**
	 * Make a request to the Google reCaptcha web service
	 *
	 * @param string $response Google specific field from the submitted form (widget)
	 *
	 * @return false|array Response of the Google service or false on failure
	 */
	final public function reCaptchaRequest( $response = '' ) {

		if ( ! $response ) {
			if ( ! $response = crb_array_get( $_POST, 'g-recaptcha-response' ) ) {
				return false;
			}
		}

		$curl = @curl_init(); // @since 4.32
		if ( ! $curl ) {
			cerber_admin_notice( __( 'ERROR:', 'wp-cerber' ) . ' Unable to initialize cURL' );

			return false;
		}

		$opt = crb_configure_curl( $curl, array(
			CURLOPT_URL            => GOO_RECAPTCHA_URL,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => array( 'secret' => $this->options['secretkey'], 'response' => $response ),
			CURLOPT_RETURNTRANSFER => true,
		) );

		if ( ! $opt ) {
			cerber_admin_notice( __( 'ERROR:', 'wp-cerber' ) . ' ' . curl_error( $curl ) );
			curl_close( $curl );

			return false;
		}

		$result = @curl_exec( $curl );
		if ( ! $result ) {
			cerber_admin_notice( __( 'ERROR:', 'wp-cerber' ) . ' ' . curl_error( $curl ) );
			$result = false;
		}

        curl_close( $curl );
		return json_decode( $result, true );

	}

	final public function reCaptchaMsg( $context = null ) {
		if ( crb_get_settings( 'invirecap' ) ) {
			$msg = __( 'Human verification failed.', 'wp-cerber' );
		}
		else {
			$msg = __( 'Human verification failed. Please click the square box in the reCAPTCHA block below.', 'wp-cerber' );
		}

		return apply_filters( 'cerber_msg_recaptcha', $msg, $context );
	}

	final public function deleteGarbage() {
		if ( $this->garbage ) {
			return;
		}

		$last = cerber_get_set( 'garbage_collector', null, false );

		if ( $last > ( time() - 60 ) ) { // We do this once a minute
			$this->garbage = true;

			return;
		}

		crb_del_expired_blocks();

		cerber_update_set( 'garbage_collector', time(), null, false );
		$this->garbage = true;
	}
}

function cerber_init() {
	static $done = false;

	if ( $done ) {
		return;
	}

	cerber_on_plugin_activation();

	cerber_error_control();

	if ( crb_get_settings( 'tiphperr' ) ) {
		set_error_handler( 'cerber_catch_error' );
	}

	cerber_upgrade_all();

	get_wp_cerber();

	cerber_beast();

	$antibot = cerber_antibot_gene();
	if ( $antibot && ! empty( $antibot[1] ) ) {
		foreach ( $antibot[1] as $item ) {
			cerber_set_cookie( $item[0], $item[1], time() + 3600 * 24 );
		}
	}

	// Redirection control: no default aliases for redirections
	if ( crb_block_admin_redirections() ) {
		remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
	}

	$hooks = apply_filters( 'cerber_antibot_hooks', array() );
	if ( ! empty( $hooks['login_register'] ) ) {
		foreach ( $hooks['login_register'] as $hook ) {
			add_action( $hook, 'cerber_login_register_stuff', 1000 );
		}
	}

	/*add_action( 'wp_upgrade', function () {
		lab_get_site_meta();
	} );*/

	$done = true;
}

/**
 * Returns correct WP_Cerber object
 *
 * @return WP_Cerber
 *
 * @since 6.0
 */
function get_wp_cerber(){

	static $the_wp_cerber = null;

	if ( ! isset( $the_wp_cerber ) ) {
		$the_wp_cerber = new WP_Cerber();
	}

	return $the_wp_cerber;
}

add_action( 'plugins_loaded', function () {

	cerber_error_control();

	get_wp_cerber();

	cerber_inspect_uploads(); // Uploads in the dashboard

	require_once( __DIR__ . '/jetflow.php' );

	if ( ! wp_next_scheduled( 'cerber_bg_launcher' ) ) {
		wp_schedule_event( time(), 'crb_five', 'cerber_bg_launcher' );
	}

}, 1000 );

function cerber_load_admin_code() {

	//cerber_cache_enable();

	require_once( ABSPATH . 'wp-admin/includes/class-wp-screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

	require_once( __DIR__ . '/admin/cerber-admin.php' );
	require_once( __DIR__ . '/admin/cerber-admin-settings.php' );
	require_once( __DIR__ . '/admin/cerber-users.php' );
	require_once( __DIR__ . '/admin/cerber-tools.php' );
	require_once( __DIR__ . '/admin/cerber-dashboard.php' );
	require_once( __DIR__ . '/cerber-toolbox.php' );
}

/**
 * If we need WP auth constants to be available.
 * It makes sense only in "Standard mode" and if WP Cerber executes its code before WP filters.
 *
 * @since 8.8
 */
function cerber_load_wp_constants() {

	require_once( ABSPATH . WPINC . '/default-constants.php' );

	if ( is_multisite() ) {
		ms_cookie_constants();
	}

	wp_cookie_constants();
}

/**
 * Some additional tasks...
 *
 */
function cerber_extra_vision() {

	// Multiple different malicious activities

	if ( empty( CRB_Globals::$logged ) ) {
		return false;
	}

	$ip = cerber_get_remote_ip();

	$black = crb_get_activity_set( 'black' );
	$black_logged = array_intersect( $black, CRB_Globals::$logged );
	if ( ! empty( $black_logged ) && cerber_is_ip_allowed() ) {
		$remain = cerber_get_remain_count( $ip, true, $black ); // @since 6.7.5
		if ( $remain < 1 ) {
			cerber_soft_block_add( $ip, 707 );
			CRB_Globals::$act_status = 18;

			return true;
		}
	}
    // TODO: there should be a matrix activity => limit per period
	$remain = cerber_get_remain_count( $ip, true, array( 400 ), 10, 30 );
	if ( $remain < 1 ) {
		cerber_block_add( $ip, 721 );
		CRB_Globals::$act_status = 18;

		return true;
	}

	return false;
}

/*
	Display WordPress login form if the Custom login URL is requested

*/
function cerber_wp_login_page() {
	if ( $path = crb_get_settings( 'loginpath' ) ) {
		if ( cerber_is_login_request() ) {
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', true );  // @since 5.7.6
			}
			@ini_set( 'display_startup_errors', 0 );
			@ini_set( 'display_errors', 0 );
			add_action( 'login_init', function () {
				@ini_set( 'display_startup_errors', 0 );
				@ini_set( 'display_errors', 0 );
			} );

			// Prevent getting PHP 8 "Undefined variable" error
			$user_login = '';
			$error = '';

			require( ABSPATH . WP_LOGIN_SCRIPT ); // load default wp-login.php form
			exit;
		}
	}
}

/**
 * Check if the current HTTP request is a login/register/lost password page request
 *
 * @return bool
 */
function cerber_is_login_request() {
	static $ret;

	if ( isset( $ret ) ) {
		return $ret;
	}

	$ret = false;

	if ( $path = crb_get_settings( 'loginpath' ) ) {

		$uri = $_SERVER['REQUEST_URI'];

		if ( $pos = strpos( $uri, '?' ) ) {
			$uri = substr( $uri, 0, $pos );
		}

		$components = explode( '/', rtrim( $uri, '/' ) );
		$last = end( $components );

		if ( $path === $last
             && ! cerber_is_rest_url() ) {
			$ret = true;
		}
	}
	elseif ( CRB_Request::is_script( '/' . WP_LOGIN_SCRIPT ) ) {
		$ret = true;
	}

	return $ret;
}

/**
 * Does the current location (URL) requires a user to be logged in to view
 *
 * @param $allowed_url string An URL that is allowed to view without authentication
 *
 * @return bool
 */
function cerber_auth_required( $allowed_url ) {
	if ( $allowed_url && CRB_Request::is_full_url_equal( $allowed_url ) ) {
		return false;
	}
	if ( cerber_is_login_request() ) {
		return false;
	}
	if ( CRB_Request::is_script( array( '/' . WP_LOGIN_SCRIPT, '/' . WP_SIGNUP_SCRIPT, '/wp-activate.php' ) ) ) {
		return false;
	}
	if ( CRB_Request::is_full_url_start_with( wp_login_url() ) ) {
		return false;
	}
	if ( class_exists( 'WooCommerce' ) ) {
		if ( CRB_Request::is_full_url_start_with( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ) {
			return false;
		}
	}

	return true;
}


// Authentication --------------------------------------------------------------------

remove_filter( 'authenticate', 'wp_authenticate_username_password', 20 );
remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );
remove_filter( 'authenticate', 'wp_authenticate_application_password', 20 );
add_filter( 'authenticate', function ( $user, $username, $password ) {
	return cerber_authenticate( $user, $username, $password );
}, PHP_INT_MAX, 3 ); // PHP_INT_MAX @since 8.8
/**
 * @param WP_User|WP_Error $user
 * @param string $username
 * @param string $password
 *
 * @return WP_User|WP_Error
 */
function cerber_authenticate( $user, $username, $password = '' ) {

	if ( $username
         && ( crb_get_settings( 'loginnowp' ) == 2 )
	     && ! crb_acl_is_white()
	     && CRB_Request::is_script( '/' . WP_LOGIN_SCRIPT ) ) {

	    return crb_login_error( $username, CRB_EV_LDN, 50 );
	}

	// reCAPTCHA
	if ( ! cerber_is_api_request()
	     && ! get_wp_cerber()->reCaptchaValidate() ) {

		cerber_log( CRB_EV_LDN, $username );

		return new WP_Error( 'incorrect_recaptcha',
			'<strong>' . __( 'ERROR:', 'wp-cerber' ) . ' </strong>' .
			get_wp_cerber()->reCaptchaMsg( 'login' ) );
	}

	// Prohibited usernames
	if ( $username && crb_is_username_prohibited( $username ) ) {
		$ret = crb_login_error( $username, 52 );
		cerber_block_add( null, 704, $username );

		return $ret;
	}

	$user = wp_authenticate_username_password( $user, $username, $password );
	$user = wp_authenticate_email_password( $user, $username, $password );

	// Application passwords
	$app_checked = false;
	$app = false;
	if ( ! ( $user instanceof WP_User )
	     && function_exists( 'wp_authenticate_application_password' ) ) {
		$app_checked = true;
		$user = wp_authenticate_application_password( $user, $username, $password );
		if ( $user instanceof WP_User ) {
			$app = true;
		}
	}

    // TODO: split the function into two parts:
    // 1. before user identification - do IP-based checks
    // 2. after user identification and password check - do user-based and role-based checks
	$user = cerber_restrict_auth( $user, $app );

	// Authentication failed or denied by cerber_restrict_auth()
	if ( ! ( $user instanceof WP_User ) || ! $user->ID ) {

		if ( crb_is_wp_error( $user ) ) {

			$err_code = $user->get_error_code();

			$ignore_codes = array( 'empty_username', 'empty_password', 'expired_session' );
			if ( ! in_array( $err_code, $ignore_codes ) ) {
				cerber_login_failed( $username );
			}

			if ( crb_get_settings( 'nologinhint' )
			     && ( $err_code == 'invalid_email' || $err_code == 'invalid_username' )
			     && ! crb_acl_is_white() ) {

				if ( ! $msg = crb_get_settings( 'nologinhint_msg' ) ) {
					return crb_login_error( $username );
				}

				return new WP_Error( 'cerber_login_error', sprintf( $msg, $username ) );
			}

		}

		return $user;
	}

	// Application passwords policies
	if ( cerber_is_api_request() ) {
		$app_pwd = cerber_get_user_policy( 'app_pwd', $user, 'app_pwd' );
		$deny = false;

		if ( ( 2 == $app_pwd && ! $app_checked )
		     || 3 == $app_pwd ) {
			$deny = true;
		}

		if ( $deny ) {
			cerber_log( 152, $username, 0, CRB_STS_25 );
			status_header( 403 );

			return new WP_Error( 'app_password_denied', 'Authentication failed' );
		}
	}

	// Shadowing
	if ( crb_get_settings( 'ds_4acc' ) && CRB_DS::is_ready( 1 ) ) {

		if ( ! CRB_DS::is_user_valid( $user->ID ) ) {
			return crb_login_error( $username, CRB_EV_LDN, 35 );
		}

		if ( ! $app_checked ) {
			$pwd = CRB_DS::get_user_pass( $user->ID );
			if ( ! $pwd || ( $password && ! wp_check_password( $password, $pwd, $user->ID ) ) ) {
				return crb_login_error( $username, CRB_EV_LDN, 36 );
			}
		}
	}

	// Authenticated via API
	if ( cerber_is_api_request() ) {
		if ( $app_checked ) {
			cerber_log( 151, $username, $user->ID );
		}
		else {
			cerber_log( CRB_EV_LIN, $username, $user->ID );
        }
	}

	CRB_Globals::$user_id = $user->ID;

	return $user;
}

add_filter( 'wp_is_application_passwords_available_for_user', 'cerber_is_app_passwords', PHP_INT_MAX, 2 );
function cerber_is_app_passwords( $var, $user ) {
	if ( $user instanceof WP_User ) {
		if ( 3 == cerber_get_user_policy( 'app_pwd', $user, 'app_pwd' ) ) {
			return false;
		}
	}

	return $var;
}

/**
 * Stops (restricts) authentication of a user once the user identified (existing users)
 *
 * @param WP_User $user
 * @param bool $app If true the user is authenticated with an application password
 *
 * @return WP_User|WP_Error
 */
function cerber_restrict_auth( $user, $app = false ) {

	if ( ! $user instanceof WP_User ) {
		return $user;
	}

	$deny = false;
	$user_msg = '';

    if ( $b = crb_is_user_blocked( $user->ID ) ) {
		$user_msg = $b['blocked_msg'];
	    CRB_Globals::$act_status = CRB_STS_29;
		$deny = true;
	}
    elseif ( ! $app && ( $b = crb_check_user_limits( $user->ID ) ) ) {
		$user_msg = $b;
	    CRB_Globals::$act_status = 38;
		$deny = true;
	}
    elseif ( crb_acl_is_white() ) { // TODO: Must be checked before user identification
		$deny = false;
	}
    elseif ( ! cerber_is_ip_allowed() ) { // TODO: Must be checked before user identification
		$deny = true;
    }
    elseif ( ! cerber_geo_allowed( 'geo_login', $user ) ) {
	    CRB_Globals::$act_status = 16;
		$deny = true;
	}
    elseif ( lab_is_blocked( cerber_get_remote_ip() ) ) {
	    CRB_Globals::$act_status = 15;
		$deny = true;
	}

	if ( $deny ) {
		status_header( 403 );
		$error = new WP_Error();
		if ( ! $user_msg ) {
			$user_msg = get_wp_cerber()->getErrorMsg();
		}
		$error->add( 'cerber_wp_error', $user_msg, array( 'user_id' => $user->ID ) );

		return $error;
	}

	return $user;
}

/**
 * Logs authentication errors, generates WP_Error object
 *
 * @param string $username
 * @param int $act
 * @param int $status
 *
 * @return WP_Error
 */
function crb_login_error( $username = '', $act = null, $status = null ) {

	CRB_Globals::$act_status = $status;

	if ( $act ) {
		cerber_log( $act, $username );
	}

	// Create with a message identical to the default WP

	if ( ! is_email( $username ) ) {
		return new WP_Error(
			'incorrect_password',
			sprintf(
			/* translators: Here %s is a user login. */
				__( '<strong>Error</strong>: The password you entered for the username %s is incorrect.' ),
				'<strong>' . $username . '</strong>'
			) .
			' <a href="' . wp_lostpassword_url() . '">' .
			__( 'Lost your password?' ) .
			'</a>' );
	}

	return new WP_Error(
		'incorrect_password',
		sprintf(
		/* translators: Here %s is an email address. */
			__( '<strong>Error</strong>: The password you entered for the email address %s is incorrect.' ),
			'<strong>' . $username . '</strong>'
		) .
		' <a href="' . wp_lostpassword_url() . '">' .
		__( 'Lost your password?' ) .
		'</a>' );
}

add_action( 'wp_login', function ( $login, $user ) {
	cerber_user_login( $login, $user );
}, 0, 2 );
/**
 * @param $login string
 * @param $user WP_User
 */
function cerber_user_login( $login, $user ) {

	CRB_Globals::$user_id = $user->ID;

	if ( ! empty( $_POST['log'] ) && ! empty( $_POST['pwd'] ) ) { // default WP login form
		$user_login = htmlspecialchars( $_POST['log'] );
	}
	else {
		$user_login = $login;
	}

	$fa = CRB_2FA::enforce( $user_login, $user );

	if ( crb_is_wp_error( $fa ) ) {
		cerber_error_log( $fa->get_error_message() . ' | RID: ' . get_wp_cerber()->getRequestID(), '2FA' );
	}

	cerber_login_history( $user->ID );

	cerber_log( CRB_EV_LIN, $user_login, $user->ID );

}

add_action( 'set_auth_cookie', function ( $auth_cookie, $expire, $expiration, $user_id, $scheme, $token ) {

	CRB_2FA::$token = $token;

	// Catching user switching and authentications without using a login form
	add_action( 'set_current_user', function () { // deferred to allow the possible 'wp_login' action to be logged first
		global $current_user;
		if ( $current_user instanceof WP_User ) {
			cerber_user_login( $current_user->user_login, $current_user );
		}
	} );

}, 10, 6 );

function cerber_login_history( $user_id, $reset = false ) {
	$cus = cerber_get_set( CRB_USER_SET, $user_id );
	if ( ! $cus || ! is_array( $cus ) ) {
		$cus = array();
	}

	$ip = cerber_get_remote_ip();

	$cus['last_login'] = array(
		// @since 8.3
		'ip' => $ip,
		'ua' => sha1( crb_array_get( $_SERVER, 'HTTP_USER_AGENT', '' ) ),
        // @since 9.4.1
		'ts' => time(),
		'cn' => lab_get_country( $ip )
	);

	if ( ! isset( $cus['2fa_history'] ) ) {
		$cus['2fa_history'] = array( 0, time() );
	}

	if ( $reset ) {
		$cus['2fa_history'] = array( 1, time() );
	}
	else {
		$cus['2fa_history'][0] ++;
	}

	cerber_update_set( CRB_USER_SET, $cus, $user_id );
}

/**
 *
 * Handler for failed login attempts
 *
 * @param string $user_login
 *
 */
function cerber_login_failed( $user_login ) {

	static $is_processed = false;

	if ( $is_processed ) {
		return;
	}

	$is_processed = true;

	$ip = cerber_get_remote_ip();
	$acl = cerber_acl_check( $ip );

	$no_user = ! cerber_get_user( $user_login );

	$act = CRB_EV_LFL; // Generic login failed (interactive), the default

	if ( cerber_is_api_request() ) {
		$act = 152;
	}
	else {

		// TODO this should be refactored together with cerber_restrict_auth() to make things clear in the log

		if ( $no_user ) {
			$act = 51;
		}
        elseif ( in_array( CRB_Globals::$act_status, array( 15, 16, CRB_STS_25, CRB_STS_29, 38 ) )
		         || ! cerber_is_ip_allowed( $ip ) ) {
			$act = CRB_EV_LDN;
		}
	}

	cerber_log( $act, $user_login );

	if ( $acl == 'W' && ! crb_get_settings( 'limitwhite' ) ) {
		return;
	}

	if ( crb_get_settings( 'usefile' ) ) {
		cerber_file_log( $user_login, $ip );
	}

	if ( ! cerber_is_wp_ajax() ) { // Needs additional researching and, maybe, refactoring
		status_header( 403 );
	}

	// Blacklisted? No more actions are needed.
	if ( $acl == 'B' ) {
		return;
	}

	// Must the Citadel mode be activated?
	if ( crb_get_settings( 'citadel_on' )
	     && ( $per = crb_get_settings( 'ciperiod' ) )
	     && ! cerber_is_citadel() ) {
		$range    = time() - $per * 60;
		$lockouts = cerber_db_get_var( 'SELECT count(ip) FROM ' . CERBER_LOG_TABLE . ' WHERE activity = '.CRB_EV_LFL.' AND stamp > ' . $range );
		if ( $lockouts >= crb_get_settings( 'cilimit' ) ) {
			cerber_enable_citadel();
		}
	}

	if ( $no_user && crb_get_settings( 'nonusers' ) ) {
		cerber_block_add( $ip, 703, $user_login);
	}
	elseif ( cerber_get_remain_count($ip, false) < 1 ) { //Limit on the number of login attempts is reached
		cerber_block_add( $ip, 701, '', null );
	}

}

// ------------ User Sessions

// do_action( "added_{$meta_type}_meta", $mid, $object_id, $meta_key, $_meta_value );
add_action( 'added_user_meta', function ( $meta_id, $user_id, $meta_key, $_meta_value ) {
	if ( $meta_key === 'session_tokens' ) {
		crb_sessions_update_user_data( $user_id, $_meta_value );
	}
}, 10, 4 );

add_action( 'update_user_meta', function ( $meta_id, $user_id, $meta_key, $_meta_value ) {

	if ( $meta_key !== 'session_tokens'
	     || CRB_Globals::$session_status !== null ) {
		return;
	}

	$old_value = get_metadata_raw( 'user', $user_id, $meta_key, true );

	if ( ! is_array( $old_value ) ) {
		return;
	}

	if ( ! is_array( $_meta_value ) ) {
		$_meta_value = array();
	}

	$new = count( $_meta_value );

	if ( count( $old_value ) > $new ) {
		if ( $new == 0 ) {
			CRB_Globals::$session_status = 530;
		}
		else {
			CRB_Globals::$session_status = 0;
		}
	}
	else {
		CRB_Globals::$session_status = null;
	}

}, 10, 4 );

// do_action( "updated_{$meta_type}_meta", $meta_id, $object_id, $meta_key, $_meta_value );
add_action( 'updated_user_meta', function ( $meta_id, $user_id, $meta_key, $_meta_value ) {

	if ( $meta_key === 'session_tokens' ) {
		crb_sessions_update_user_data( $user_id, $_meta_value );
		if ( CRB_Globals::$session_status !== null ) {
			cerber_log( CRB_EV_UST, '', $user_id, CRB_Globals::$session_status );
			CRB_Globals::$session_status = null;
		}
	}

}, 10, 4 );

// do_action( "deleted_{$meta_type}_meta", $meta_ids, $object_id, $meta_key, $_meta_value );
add_action( 'deleted_user_meta', function ( $meta_ids, $user_id, $meta_key, $_meta_value ) {
	if ( $meta_key === 'session_tokens' ) {
		$query = 'DELETE FROM ' . cerber_get_db_prefix() . CERBER_USS_TABLE;
		if ( $user_id ) {
			$query .= ' WHERE user_id = ' . $user_id;
			cerber_log( CRB_EV_UST, '', $user_id, 530 ); // Terminated by admin
			CRB_Globals::set_act_status( 530 );
		}
		cerber_db_query( $query );
	}
}, 10, 4 );

/**
 * Keep the sessions table up to date
 *
 * @param $user_id
 * @param array $wp_sessions List of user sessions from "session_tokens" user meta
 *
 * @return bool
 */
function crb_sessions_update_user_data( $user_id, $wp_sessions = null ) {
	global $wpdb;

	$crb_sessions = cerber_get_db_prefix() . CERBER_USS_TABLE;

	if ( $wp_sessions === null ) {
		$user_meta = cerber_db_get_var( 'SELECT um.* FROM ' . $wpdb->usermeta . ' um JOIN ' . $wpdb->users . ' us ON (um.user_id = us.ID) WHERE um.user_id = ' . $user_id . ' AND um.meta_key = "session_tokens"' );
		if ( $user_meta && ! empty( $user_meta['meta_value'] ) ) {
			$wp_sessions = crb_unserialize( $user_meta['meta_value'] );
		}
	}

	if ( ! $wp_sessions ) {
		cerber_db_query( 'DELETE FROM ' . $crb_sessions . ' WHERE user_id = ' . $user_id );

		return true;
	}

	$list = array_keys( $wp_sessions );
	cerber_db_query( 'DELETE FROM ' . $crb_sessions . ' WHERE user_id = ' . $user_id . ' AND wp_session_token NOT IN ("' . implode( '","', $list ) . '")' );

	$existing = cerber_db_get_col( 'SELECT wp_session_token FROM ' . $crb_sessions . ' WHERE user_id = ' . $user_id );

	if ( $existing ) {
		$new_entries = array_diff( $list, $existing );
	}
	else {
		$new_entries = $list;
	}

	foreach ( $new_entries as $id ) {
		$data = $wp_sessions[ $id ];
		$session_id = get_wp_cerber()->getRequestID();
		//$ip = $data['ip']; // On some servers behind a proxy WP core is unable to detect IP address correctly.
		$ip = cerber_get_remote_ip();
		$country = (string) lab_get_country( $ip );
		cerber_db_query( 'INSERT INTO ' . $crb_sessions . ' (user_id, ip, country, started, expires, session_id, wp_session_token) VALUES (' . $user_id . ',"' . $ip . '","' . $country . '","' . $data['login'] . '","' . $data['expiration'] . '","' . $session_id . '","' . $id . '")' );
	}

	return true;
}

/**
 * Synchronize all sessions in bulk
 *
 * @return bool
 */
function crb_sessions_sync_all() {
	global $wpdb;

	$table = cerber_get_db_prefix() . CERBER_USS_TABLE;

	cerber_db_query( 'DELETE FROM ' . $table );

	$query = 'SELECT um.* FROM ' . $wpdb->usermeta . ' um JOIN ' . $wpdb->users . ' us ON (um.user_id = us.ID) WHERE um.meta_key = "session_tokens"';
	if ( ! $metas = cerber_db_get_results( $query ) ) {
		return false;
	}

	foreach ( $metas as $user_meta ) {
		$sessions = crb_unserialize( $user_meta['meta_value'] );
		if ( empty( $sessions ) ) {
			continue;
		}
		foreach ( $sessions as $id => $data ) {
			if ( $data['expiration'] < time() ) {
				continue;
			}
			cerber_db_query( 'INSERT INTO ' . $table . ' (user_id, ip, started, expires, wp_session_token) VALUES (' . $user_meta['user_id'] . ',"' . $data['ip'] . '","' . $data['login'] . '","' . $data['expiration'] . '","' . $id . '")' );
		}
	}

	return true;
}

function crb_sessions_del_expired() {
	static $done;
	if ( $done ) {
		return;
	}

	cerber_db_query( 'DELETE FROM ' . cerber_get_db_prefix() . CERBER_USS_TABLE . ' WHERE expires < ' . time() );

	$done = true;
}

function crb_sessions_get_num( $user_id = null ) {
	$where = ( $user_id ) ? ' WHERE user_id = ' . absint( $user_id ) : '';

	return (int) cerber_db_get_var( 'SELECT COUNT(user_id) FROM ' . cerber_get_db_prefix() . CERBER_USS_TABLE . $where );
}

/**
 * Terminates specified user sessions by updating user meta directly in the DB
 *
 * @param array|string $tokens Session tokens to kill
 * @param int $user_id Users the sessions to kill belongs to
 * @param bool $admin If true, it is executing in the WP dashboard
 *
 * @return int
 */
function crb_sessions_kill( $tokens, $user_id = null, $admin = true ) {

	if ( ! is_array( $tokens ) ) {
		$tokens = array( $tokens );
	}

	if ( ! $user_id ) {
		$users = cerber_db_get_col( 'SELECT user_id FROM ' . cerber_get_db_prefix() . CERBER_USS_TABLE . ' WHERE wp_session_token IN ("' . implode( '","', $tokens ) . '")' );
	}
	else {
		$users = array( $user_id );
	}

	if ( ! $users || ! $tokens ) {
		return 0;
	}

	$kill = array_flip( $tokens );
	$total = 0;
	$errors = 0;

	// Prevent termination the current admin session
	if ( $token = crb_get_session_token() ) {
		unset( $kill[ cerber_hash_token( $token ) ] );
	}

	foreach ( $users as $user_id ) {
		$count = 0;

		$sessions = get_user_meta( $user_id, 'session_tokens', true );

		if ( empty( $sessions ) || ! is_array( $sessions ) ) {
			continue;
		}
		if ( ! $do_this = array_intersect_key( $kill, $sessions ) ) {
			continue;
		}

		foreach ( $do_this as $key => $nothing ) {
			unset( $sessions[ $key ] );
			unset( $kill[ $key ] );
			$count ++;
		}

		if ( $count ) {
			if ( update_user_meta( $user_id, 'session_tokens', $sessions ) ) {
				$total += $count;
			}
			else {
				$errors ++;
			}
		}
	}

	if ( $admin ) {
		if ( $errors ) {
			cerber_admin_notice( 'Error: Unable to update user meta data.' );
		}

		if ( $total ) {
			cerber_admin_message( sprintf( _n( 'User session has been terminated', '%s user sessions have been terminated', $total, 'wp-cerber' ), $total ) );
		}
		else {
			cerber_admin_notice( 'No user sessions found.' );
		}
	}

	return $total;
}

// Enforce restrictions for the current user

add_action( 'set_current_user', function () { // the normal way
	global $current_user;
	cerber_restrict_user( $current_user->ID );
}, 0 );

add_action( 'init', function () { // backup for 'set_current_user' hook which might not be invoked
	cerber_restrict_user( get_current_user_id() );
}, 0 );

function cerber_restrict_user( $user_id ) {
	static $done;

	if ( $done ) {
		return;
	}

	$done = true;

	CRB_2FA::check_for_errors( $user_id );

	if ( ! $user_id ) {
		return;
	}

	if ( ( $sts = ( crb_is_user_blocked( $user_id ) ? CRB_STS_29 : 0 ) )
	     || ( $sts = ( CRB_DS::is_user_valid( $user_id ) ? 0 : 35 ) )
	     || ( $sts = ( crb_acl_is_black() ? 14 : 0 ) ) // @since 8.2.4
	     || ( $sts = ( cerber_geo_allowed( 'geo_login', $user_id ) ? 0 : 16 ) ) ) { // @since 8.2.3

		cerber_user_logout( $sts );

		if ( is_admin() ) {
			wp_redirect( cerber_get_home_url() );
		}
		else {
			wp_safe_redirect( CRB_Request::full_url() );
		}

		exit;
	}

	CRB_2FA::restrict_and_verify( $user_id );

	if ( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX )
	     && is_admin()
	     && ! is_super_admin() ) {
		if ( cerber_get_user_policy( 'nodashboard', $user_id ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}

	if ( cerber_get_user_policy( 'notoolbar', $user_id ) ) {
		show_admin_bar( false );
	}

}

add_filter( 'login_redirect', function ( $redirect_to, $requested_redirect_to, $user ) {

	if ( $to = crb_redirect_by_policy( $user, 'rdr_login' ) ) {
		return $to;
	}

	return $redirect_to;
}, PHP_INT_MAX, 3 );

add_filter( 'logout_redirect', function ( $redirect_to, $requested_redirect_to, $user ) {

	if ( $to = crb_redirect_by_policy( $user, 'rdr_logout' ) ) {
		return $to;
	}

	if ( ( $path = crb_get_settings( 'loginpath' ) )
	     && empty( $requested_redirect_to )
	     && cerber_is_login_request() ) {
		$redirect_to = cerber_get_site_url() . '/' . $path . '/?loggedout=true'; // Replace the default WP logout redirection
	}

	return $redirect_to;
}, PHP_INT_MAX, 3 );

if ( crb_get_settings( 'loginpath' ) ) {

	add_filter( 'lostpassword_redirect', function ( $redirect_to ) {

		if ( ( $path = crb_get_settings( 'loginpath' ) )
		     && cerber_is_login_request() ) {
			$redirect_to = cerber_get_site_url() . '/' . $path . '/?checkemail=confirm'; // Replace the default WP logout redirection
		}

		return $redirect_to;
	}, PHP_INT_MAX );

	add_filter( 'registration_redirect', function ( $redirect_to ) {

		if ( ( $path = crb_get_settings( 'loginpath' ) )
		     && cerber_is_login_request() ) {
			$redirect_to = cerber_get_site_url() . '/' . $path . '/?checkemail=registered'; // Replace the default WP logout redirection
		}

		return $redirect_to;
	}, PHP_INT_MAX );

}

if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) == 'POST' ) {

	add_filter( 'lostpassword_errors', function ( $errors, $user_data ) {
		if ( $user_data || CRB_Globals::$reset_pwd_denied ) {
			return $errors;
		}

		cerber_log( CRB_EV_PRD, crb_get_user_login_field(), 0, 35 );

		if ( crb_get_settings( 'nopasshint' )
             && ! crb_acl_is_white() ) {

			// Mimic the default redirection, see "case 'retrievepassword':" in wp-login.php
			$redirect_to = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'wp-login.php?checkemail=confirm';
			wp_safe_redirect( $redirect_to );
			exit;
		}

		return $errors;

	}, PHP_INT_MAX, 2 );
}
elseif ( crb_get_settings( 'nopasshint' )
         && ! crb_acl_is_white() ) {

	add_filter( 'wp_login_errors', function ( $errors, $user_data ) {

		if ( $errors->get_error_code() == 'confirm'
		     && $errors->get_error_data() == 'message' ) {

			if ( ! $msg = crb_get_settings( 'nopasshint_msg' ) ) {
				$msg = __( 'If we have found your account, we have sent the confirmation link to the email address on the account.', 'wp-cerber' );
			}

			$errors = new WP_Error( 'confirm', $msg, 'message' ); // Do not change!
		}

		return $errors;

	}, PHP_INT_MAX, 2 );
}

function cerber_parse_redir( $url, $user ) {
	if ( strpos( $url, '{{' ) ) {
		$url = preg_replace( '/{{user_id}}/', $user->ID, $url );
	}

	return $url;
}

function crb_redirect_by_policy( $user, $policy ) {

	if ( $user
	     && ! crb_is_wp_error( $user )
	     && ( $to = cerber_get_user_policy( $policy, $user ) ) ) {

	    $force_redirect_to = cerber_parse_redir( $to, $user );
		if ( ! strpos( $force_redirect_to, '://' ) ) {
			$force_redirect_to = cerber_get_site_url() . '/' . ltrim( $force_redirect_to, '/' );
		}

		return $force_redirect_to;
	}

	return false;
}

function cerber_user_logout( $status = null ) {
	global $current_user, $userdata, $user_ID;

    CRB_Globals::$act_status = ( ! $status ) ? 26 : absint( $status );

	if ( $current_user instanceof WP_User ) {
		$uid = $current_user->ID;
	}
	else {
		$uid = get_current_user_id();
	}

	@wp_logout();

	CRB_2FA::delete_2fa( $uid );

	$current_user = null;
	$userdata     = null;
	$user_ID      = null;
}

// Registration -----------------------------------------------------------------------

function cerber_is_registration_prohibited( $user_login, $user_email = '' ) {

	$code = null;
	$msg = '';
	$ret_msg = '';
	$wp_cerber = get_wp_cerber();

	if ( crb_get_settings( 'regwhite' )
	     && ! crb_acl_is_white()
	     && lab_lab() ) {
		cerber_log( 54, '', 0, 37 );
		$code = 'ip_denied';
		if ( ! $ret_msg = crb_get_settings( 'regwhite_msg' ) ) {
			$msg = __( 'You are not allowed to register.', 'wp-cerber' );
		}
	}
    elseif ( crb_is_reg_limit_reached() ) {
	    cerber_log( 54, '', 0, 17 );
		$code = 'ip_denied';
		$msg = apply_filters( 'cerber_msg_denied', __( 'You are not allowed to register.', 'wp-cerber' ), 'register' );
	}
    elseif ( cerber_is_bot( 'botsreg' ) ) {
		cerber_log( 54 );
		$code = 'bot_detected';
		$msg = apply_filters( 'cerber_msg_denied', __( 'You are not allowed to register.', 'wp-cerber' ), 'register' );
	}
    elseif ( ! $wp_cerber->reCaptchaValidate() ) {
	    cerber_log( 54, '', 0 , CRB_STS_532 );
		$code = 'incorrect_recaptcha';
		$msg = $wp_cerber->reCaptchaMsg( 'register' );
	}
    elseif ( crb_is_username_prohibited( $user_login ) ) {
		cerber_log( 54, '', 0, CRB_STS_30 );
		$code = 'prohibited_login';
		$msg = apply_filters( 'cerber_msg_prohibited', __( 'Username is not allowed. Please choose another one.', 'wp-cerber' ), 'register' );
	}
    elseif ( ! cerber_is_email_permited( $user_email ) ) {
		cerber_log( 54, '', 0, 31 );
		$code = 'prohibited_email';
		$msg = apply_filters( 'cerber_msg_prohibited_email', __( 'Email address is not permitted.', 'wp-cerber' ) . ' ' . __( 'Please choose another one.', 'wp-cerber' ), 'register' );
	}
    elseif ( ! cerber_is_ip_allowed() || lab_is_blocked( cerber_get_remote_ip() ) ) {
		cerber_log( 54 );
		$code = 'ip_denied';
		$msg = apply_filters( 'cerber_msg_denied', __( 'You are not allowed to register.', 'wp-cerber' ), 'register' );
	}
    elseif ( ! cerber_geo_allowed( 'geo_register' ) ) {
		cerber_log( 54, '', 0, 16 );
		$code = 'country_denied';
		$msg = apply_filters( 'cerber_msg_denied', __( 'You are not allowed to register.', 'wp-cerber' ), 'register' );
	}

	if ( $code ) {
		if ( ! $ret_msg ) {
			$ret_msg = '<strong>' . __( 'ERROR:', 'wp-cerber' ) . ' </strong>' . $msg;
		}

		return array( $code, $ret_msg );
	}

	return false;
}

/**
 * Restrict email addresses
 *
 * @param $email string
 *
 * @return bool
 */
function cerber_is_email_permited( $email ) {

	if ( ! $email ) {
		return true;
	}

	if ( ( ! $rule = crb_get_settings( 'emrule' ) )
	     || ( ! $list = (array) crb_get_settings( 'emlist' ) ) ) {
		return true;
	}

	if ( $rule == 1 ) {
		$ret = false;
	}
    elseif ( $rule == 2 ) {
		$ret = true;
	}
	else {
		return true;
	}

	$email = strtolower( $email );

	foreach ( $list as $item ) {
		if ( $item[0] == '/' && substr( $item, - 1 ) == '/' ) {
			$pattern = $item . 'i'; // we permit to specify any REGEX
			if ( @preg_match( $pattern, $email ) ) {
				return $ret;
			}
		}
        elseif ( false !== strpos( $item, '*' ) ) {
	        $wildcard = '.+?';
			$pattern = '/^' . str_replace( array( '.', '*' ), array( '\.', $wildcard ), $item ) . '$/i';
			if ( @preg_match( $pattern, $email ) ) {
				return $ret;
			}
		}
        elseif ( $email === $item ) {
			return $ret;
		}
	}

	return ! $ret;
}

/**
 * Limit on user registrations per IP
 *
 * @return bool
 */
function crb_is_reg_limit_reached() {

	if ( ! lab_lab() ) {
		return false;
	}

	if ( ! crb_get_settings( 'reglimit_min' ) || ! crb_get_settings( 'reglimit_num' ) ) {
		return false;
	}

	if ( crb_acl_is_white() ) {
		return false;
	}

	$ip    = cerber_get_remote_ip();
	$stamp = absint( time() - 60 * crb_get_settings( 'reglimit_min' ) );
	$count = cerber_db_get_var( 'SELECT count(ip) FROM ' . CERBER_LOG_TABLE . ' WHERE ip = "' . $ip . '" AND activity = 2 AND stamp > ' . $stamp );
	if ( $count >= crb_get_settings( 'reglimit_num' ) ) {

		return true;
	}

	return false;
}

// The default WP registration form
add_filter( 'registration_errors', function ( $errors, $sanitized_user_login, $user_email ) {

	$result = cerber_is_registration_prohibited( $sanitized_user_login, $user_email );

	if ( $result ) {
		return new WP_Error( $result[0], $result[1] );
	}

	return $errors;
}, 10, 3 );

/**
 * Inserting users programmatically via wp_insert_user()
 *
 * @since 8.6.3.3
 */
add_filter( 'wp_pre_insert_user_data', function ( $data, $update, $user_id ) {
	/*if ( $update || is_admin() ) {
		return $data;
	}*/

	if ( ! $update && ! is_admin() ) {

		$user_login = crb_array_get( $data, 'user_login' );
		$user_email = crb_array_get( $data, 'user_email' );

		if ( cerber_is_registration_prohibited( $user_login, $user_email ) ) {
			return null;
		}
	}

	if ( $update ) {
		$old_user_data = get_userdata( $user_id );
		if ( $data['user_pass'] != $old_user_data->user_pass ) {
			crb_pass_reset( $old_user_data );
		}
	}

	return $data;
}, PHP_INT_MAX, 3 );

// Validation for MU and BuddyPress
add_filter( 'wpmu_validate_user_signup', function ( $signup_data ) {

	$sanitized_user_login = sanitize_user( $signup_data['user_name'], true );

	if ( $check = cerber_is_registration_prohibited( $sanitized_user_login, $signup_data['user_email'] ) ) {
		$signup_data['errors'] = new WP_Error( 'user_name', $check[1] );
	}

	return $signup_data;
}, PHP_INT_MAX );

// Filter out prohibited usernames
add_filter( 'illegal_user_logins', function ( $list ) {
	if ( ! is_admin_user_edit() ) {
		$list = (array) crb_get_settings( 'prohibited' );
	}

	return $list;
}, PHP_INT_MAX );

add_filter( 'option_users_can_register', function ( $value ) {
	//if ( ! cerber_is_allowed() || !cerber_geo_allowed( 'geo_register' )) {
	if ( ! cerber_is_ip_allowed() || crb_is_reg_limit_reached() ) {
		return false;
	}
	if ( crb_get_settings( 'regwhite' )
	     && ! crb_acl_is_white()
	     && lab_lab() ) {
		return false;
	}

	return $value;
}, PHP_INT_MAX );

// Comments (commenting) section ----------------------------------------------------------

if ( cerber_is_custom_comment() ) {
	add_filter( 'comment_form_defaults', function ( $defaults ) {
		$defaults['action'] = site_url( '/' . crb_get_compiled( 'custom_comm_slug' ) );

		return $defaults;
	} );
}

/**
 * Process comments submitted via the Custom comment URL
 *
 * @since 8.8
 */
function cerber_custom_comment_process() {
	if ( cerber_is_custom_comment() && CRB_Request::is_comment_sent() ) {
		require( ABSPATH . WP_COMMENT_SCRIPT ); // load the default wp-comments-post.php processor
		exit;
	}
}

/**
 * Is Custom comment URL is enabled?
 *
 * @return bool
 *
 * @since 8.8
 */
function cerber_is_custom_comment() {
	if ( crb_get_settings( 'customcomm' ) && cerber_is_permalink_enabled() ) {
		return true;
	}

	return false;
}


/**
 * If a comment must be marked as spam
 *
 */
add_filter( 'pre_comment_approved', function ( $approved, $commentdata ) {
	if ( 1 == crb_get_settings( 'spamcomm' ) && ! cerber_is_comment_allowed() ) {
		$approved = 'spam';
	}

	return $approved;
}, 10, 2 );

/**
 * If a comment must be denied
 *
 */
add_action( 'pre_comment_on_post', function ( $comment_post_ID ) {

	$deny = false;

	if ( 1 != crb_get_settings( 'spamcomm' ) && ! cerber_is_comment_allowed() ) {
		$deny = true;
	}
	elseif ( ! cerber_geo_allowed( 'geo_comment' ) ) {
		CRB_Globals::$act_status = 16;
		cerber_log(19);
		$deny = true;
	}

	if ( $deny ) {
		cerber_set_cookie( 'cerber_post_id', $comment_post_ID, time() + 60, '/' );
		$comments = get_comments( array( 'number' => '1', 'post_id' => $comment_post_ID ) );
		if ( $comments ) {
			$loc = get_comment_link( $comments[0]->comment_ID );
		} else {
			$loc = get_permalink( $comment_post_ID ) . '#cerber-recaptcha-msg';
		}
		wp_safe_redirect( $loc );
		exit;
	}

} );

/**
 * If submit comments via REST API is not allowed
 *
 */
add_filter( 'rest_allow_anonymous_comments', function ( $allowed, $request ) {

	if ( ! cerber_is_ip_allowed() ) {
		$allowed = false;
	}
	if ( ! cerber_geo_allowed( 'geo_comment' ) ) {
		cerber_log(19);
		CRB_Globals::$act_status = 16;
		$allowed = false;
	}
	elseif ( lab_is_blocked( cerber_get_remote_ip() ) ) {
		$allowed = false;
	}

	return $allowed;
}, 10, 2 );

/**
 * Check if a submitted comment is allowed
 *
 * @return bool
 */
function cerber_is_comment_allowed(){

	if ( is_admin() ) {
		return true;
	}

	$deny = null;
	$remain = 1;

	if ( ! cerber_is_ip_allowed() ) {
		$deny = 19;
	}
    elseif ( cerber_is_bot( 'botscomm' ) ) {
	    $deny = 16;
		$remain = cerber_get_remain_count( null, true, array( 16 ), 3, 60 );
	}
	elseif ( ! get_wp_cerber()->reCaptchaValidate( 'comment' , true ) ) {
		$deny = 16;
	}
	elseif ( lab_is_blocked( cerber_get_remote_ip() ) ) {
		$deny = 19;
	}

	if ( $deny ) {
		cerber_log( $deny );
		$ret = false;
	}
	else {
		$ret = true;
	}

	if ( $remain < 1 ) {
		cerber_block_add( null, 706, '', 60 );
	}

	return $ret;
}

/**
 * Showing reCAPTCHA widget.
 * Displaying error message on the comment form for a human.
 *
 */
add_filter( 'comment_form_submit_field', function ( $value ) {
	global $post;

	if ( cerber_get_cookie( 'cerber_post_id' ) == $post->ID ) {
		//echo '<div id="cerber-recaptcha-msg">' . __( 'ERROR:', 'wp-cerber' ) . ' ' . $wp_cerber->reCaptchaMsg( 'comment' ) . '</div>';
		echo '<div id="cerber-recaptcha-msg">' . __( 'ERROR:', 'wp-cerber' ) . ' ' . __( 'Sorry, human verification failed.', 'wp-cerber' ) . '</div>';
		$p = cerber_get_cookie_prefix();
		echo '<script type="text/javascript">document.cookie = "' . $p . 'cerber_post_id=0;path=/";</script>';
	}

	if ( ! crb_get_settings( 'recapcomauth' ) || ! is_user_logged_in() ) {
		get_wp_cerber()->reCaptcha( 'widget', 'recapcom' );
	}

	if ( cerber_is_custom_comment() ) {
		echo '<input type="hidden" name="' . crb_get_compiled( 'custom_comm_mark' ) . '" value="' . rand( 1, 100 ) . '">';
	}

	return $value;
} );


// Messages ----------------------------------------------------------------------

// Login page part 1
add_action( 'login_head', 'cerber_login_head' );
function cerber_login_head() {
	global $error; // This global WP variable is used at login_header() in wp-login.php

	if ( ! $allowed = cerber_is_ip_allowed() )  :
		?>
        <style>
            #loginform {
                display: none;
            }
        </style>
		<?php
	endif;

	if ( crb_get_settings( 'no_rememberme' ) || crb_get_settings( 'auth_expire' ) )  :
		?>
        <style>
            p.forgetmenot {
                display: none;
            }
        </style>
	<?php
	endif;

	$wp_cerber = get_wp_cerber();

	$wp_cerber->reCaptcha( 'style' );

	// Add an error message above the login form

	if ( ! cerber_is_http_get() ) {
		return;
	}

	if ( ! cerber_can_msg() ) {
		return;
	}

	if ( ! $allowed ) {
		$error = $wp_cerber->getErrorMsg();
	}
    elseif ( $msg = $wp_cerber->getRemainMsg() ) {
		$error = $msg;
	}
    elseif ( crb_get_settings( 'authonly' ) && ( $msg = crb_get_settings( 'authonlymsg' ) ) ) {
		$error = $msg;
	}
}

// Login page part 2, if credentials were wrong - after login form has been submitted (POST request)
add_filter( 'login_errors', 'cerber_login_form_msg' );
function cerber_login_form_msg( $errors ) {
	global $error; // This global WP variable is used at login_header() in wp-login.php

	if ( cerber_can_msg() ) {
		$wp_cerber = get_wp_cerber();
		if ( ! cerber_is_ip_allowed() ) {
			$errors = $wp_cerber->getErrorMsg(); // Replace any error messages
		}
        elseif ( ! $error && ( $msg = $wp_cerber->getRemainMsg() ) ) {
			$errors .= '<p>&nbsp;</p><p>' . $msg . '</p>';
		}
	}

	return $errors;
}

add_filter( 'shake_error_codes', 'cerber_login_failure_shake' ); // Shake it, baby!
function cerber_login_failure_shake( $shake_error_codes ) {
	$shake_error_codes[] = 'cerber_wp_error';

	return $shake_error_codes;
}

/*
	Replace default login/logout URL with Custom login page URL
*/
add_filter( 'site_url', 'cerber_login_logout', 9999, 4 );
add_filter( 'network_site_url', 'cerber_login_logout', 9999, 3 );
function cerber_login_logout( $url, $path, $scheme, $blog_id = 0 ) { // $blog_id only for 'site_url'

	if ( $login_path = crb_get_settings( 'loginpath' ) ) {
		$url = str_replace( WP_LOGIN_SCRIPT, $login_path . '/', $url );
	}

	return $url;
}

/*
	Replace default logout redirect URL with Custom login page URL
*/
add_filter( 'wp_redirect', 'cerber_login_redirect', 9999, 2 );
function cerber_login_redirect( $location, $status ) {

	if ( ( $path = crb_get_settings( 'loginpath' ) ) && ( 0 === strpos( $location, WP_LOGIN_SCRIPT . '?' ) ) ) {
		$loc      = explode( '?', $location );
		$location = cerber_get_home_url() . '/' . $path . '/?' . $loc[1];
	}

	return $location;
}

add_action( 'init', function () {

	cerber_cookie_bad_proc();

	if ( crb_get_settings( 'adminphp' ) ) {
		if ( defined( 'CONCATENATE_SCRIPTS' ) ) {

            define( 'CONCATENATE_SCRIPTS_BY_CRB', false );

			if ( CONCATENATE_SCRIPTS ) {
				if ( is_admin() || nexus_is_valid_request() ) {
					cerber_add_issue( 'conscripts', 'The PHP constant CONCATENATE_SCRIPTS is already defined somewhere else', array( 'setting' => 'adminphp' ) );
				}
			}
		}
        elseif ( ! cerber_check_groove_x() ) {
			define( 'CONCATENATE_SCRIPTS', false );
			define( 'CONCATENATE_SCRIPTS_BY_CRB', true );
		}
	}

	if ( ! is_admin()
	     && ! cerber_is_wp_cron() ) {
		cerber_access_control();
		cerber_auth_access();
	}

	cerber_custom_comment_process();

	cerber_post_control();

	// Load translations

	$use_eng = false;

	if ( is_admin() && crb_get_settings( 'admin_lang' ) ) {

		$use_eng = true;

		add_filter( 'override_load_textdomain', function ( $val, $domain, $mofile ) {
			if ( $domain == 'wp-cerber' ) {
				$val = true;
			}

			return $val;
		}, 100, 3 );
	}

	if ( ! $use_eng ) {
		load_plugin_textdomain( 'wp-cerber', false, 'wp-cerber/languages' );
	}

	if ( crb_get_settings( 'nologinlang' ) ) {
		add_filter( 'login_display_language_dropdown', '__return_false' );
	}

	if ( ! empty( $_POST['rememberme'] )
	     && ( crb_get_settings( 'no_rememberme' ) || crb_get_settings( 'auth_expire' ) ) ) {

        // See do_action( "login_form_{$action}" );
		add_action( 'login_form_login', function () {
			$_POST['rememberme'] = ''; // Ugly workaround
		}, 0 );

		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_login_credentials', function ( $creds ) {
				$creds['rememberme'] = false;

				return $creds;
			} );
		}
	}

	if ( ( ! defined( 'CERBER_OLD_LP' ) || ! CERBER_OLD_LP ) // This constant is deprecated
	     && ! crb_get_settings( 'logindeferred' ) ) {
		cerber_wp_login_page();
	}

	if ( class_exists( 'WooCommerce' )
	     && ( crb_get_settings( 'no_rememberme' ) || crb_get_settings( 'auth_expire' ) ) ) {
		add_action( 'wp_head', function () {
			?>
            <style>
                .woocommerce .woocommerce-form-login label.woocommerce-form-login__rememberme {
                    display: none;
                }
            </style>
			<?php
		} );
	}

}, 0 );

if ( ( defined( 'CERBER_OLD_LP' ) && CERBER_OLD_LP )
     || crb_get_settings( 'logindeferred' ) ) {
	add_action( 'init', 'cerber_wp_login_page', 20 );
}

/**
 * Restrict access to some vital parts of WP
 *
 */
function cerber_access_control() {

	if ( crb_acl_is_white() ) {
		return;
	}

	$wp_cerber = get_wp_cerber();

	if ( $wp_cerber->isURIProhibited() ) {
		cerber_404_page();
	}

	$opt = crb_get_settings();

	// REST API
	if ( $wp_cerber->isDeny() ) {
		cerber_block_rest_api();
	}
    elseif ( cerber_is_rest_url() ) {
		$rest_allowed = true;

		if ( ! cerber_is_rest_permitted() ) {
			$rest_allowed = false;
		}

		if ( $rest_allowed && ! cerber_geo_allowed( 'geo_restapi' ) ) {
			$rest_allowed  = false;
			CRB_Globals::$act_status = 16;
		}

		if ( ! $rest_allowed ) {
			CRB_Globals::$req_status = 0;
			cerber_block_rest_api();
		}
	}

	// Some XML-RPC stuff
	if ( $wp_cerber->isDeny() || ! empty( $opt['xmlrpc'] ) ) {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
		add_filter( 'bloginfo_url', 'cerber_pingback_url', 10, 2 );
		remove_action( 'wp_head', 'rsd_link', 10 );
		remove_action( 'wp_head', 'wlwmanifest_link', 10 );
	}

	// Feeds
	if ( $wp_cerber->isDeny() || ! empty( $opt['nofeeds'] ) ) {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );

		remove_action( 'do_feed_rdf', 'do_feed_rdf', 10 );
		remove_action( 'do_feed_rss', 'do_feed_rss', 10 );
		remove_action( 'do_feed_rss2', 'do_feed_rss2', 10 );
		remove_action( 'do_feed_atom', 'do_feed_atom', 10 );
		remove_action( 'do_pings', 'do_all_pings', 10 );

		add_action( 'do_feed_rdf', 'cerber_404_page', 1 );
		add_action( 'do_feed_rss', 'cerber_404_page', 1 );
		add_action( 'do_feed_rss2', 'cerber_404_page', 1 );
		add_action( 'do_feed_atom', 'cerber_404_page', 1 );
		add_action( 'do_feed_rss2_comments', 'cerber_404_page', 1 );
		add_action( 'do_feed_atom_comments', 'cerber_404_page', 1 );
	}

}

function cerber_auth_access() {

	$opt = crb_get_settings();

	if ( ! empty( $opt['authonlyacl'] )
	     && crb_acl_is_white() ) {
		return;
	}

	if ( ! empty( $opt['authonly'] )
	     && ! is_user_logged_in()
	     && cerber_auth_required( $opt['authonlyredir'] ) ) {
		if ( $opt['authonlyredir'] ) {
			$redirect = ( strpos( $_SERVER['REQUEST_URI'], '/options.php' ) && wp_get_referer() ) ? wp_get_referer() : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			wp_redirect( add_query_arg( 'redirect_to', $redirect, $opt['authonlyredir'] ) );
			exit;
		}
		auth_redirect();
	}
}

/**
 * Anti-spam & anti-bot engine
 *
 */
function cerber_post_control() {

	if ( ! cerber_is_http_post()
	     || ( crb_get_settings( 'botsipwhite' ) && crb_acl_is_white() ) ) {
		return;
	}

	if ( ! cerber_antibot_enabled( 'botsany' ) && ! cerber_get_geo_rules( 'geo_submit' ) ) {
		return;
	}

	// Exceptions -----------------------------------------------------------------------

	if ( cerber_is_antibot_exception() ) {
		return;
	}

	// Let's make the checks

	$deny = false;

	if ( ! cerber_is_ip_allowed(null, CRB_CNTX_SAFE) ) {
		$deny = true;
		cerber_log( 18 );
	}
	elseif ( cerber_is_bot( 'botsany' ) ) {
		$deny = true;
		cerber_log( 17 );
	}
    elseif ( ! cerber_geo_allowed( 'geo_submit' ) ) {
		$deny = true;
		CRB_Globals::$act_status = 16;
		cerber_log( 18 );
	}
    elseif ( lab_is_blocked( null, true ) ) {
		$deny = true;
	    CRB_Globals::$act_status = 18;
		cerber_log( 18 );
	}

	if ( $deny ) {
		cerber_forbidden_page();
	}

}

/**
 * Exception for POST request control
 *
 * @return bool
 */
function cerber_is_antibot_exception(){

	if ( cerber_is_wp_cron() ) {
		return true;
	}

	// Admin || AJAX requests by unauthorized users
	if ( is_admin() ) {
		if ( cerber_is_wp_ajax() ) {
			if ( is_user_logged_in() ) {
				return true;
			}
			if ( class_exists( 'WooCommerce' ) ) {
			    // Background processes launcher? P.S. wc_privacy_cleanup
				if ( crb_arrays_similar( $_GET, array(
						'nonce'  => 'crb_is_alphanumeric',
						'action' => 'crb_is_alphanumeric'
					) )
				     && ! preg_grep( '/[^\d]/', array_keys( $_POST ) ) ) { // If other than numeric keys in array
					return true;
				}
			}
		}
		else {
			return true;
		}
	}

	// Standard WordPress Comments
	if ( CRB_Request::is_comment_sent() ) {
		return true;
	}

	// XML-RPC
	if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
		return true;
	}

	// Trackback
	if ( is_trackback() ) {
		return true;
	}

	// Login page
	if ( cerber_is_login_request() ) {
		return true;
	}

	// REST API (except Contact Form 7 submission)
	if ( cerber_is_rest_url() ) {
		if ( false === strpos( $_SERVER['REQUEST_URI'], 'contact-form-7' ) ) {
			return true;
		}
	}

	if ( class_exists( 'WooCommerce' ) ) {
		if ( cerber_is_permalink_enabled() ) {
			if ( CRB_Request::is_full_url_start_with( cerber_get_home_url() . '/wc-api/' ) ) {
				return true;
			}
		}
		elseif ( ! empty( $_GET['wc-api'] ) ) {
			if ( cerber_check_remote_domain( array( '*.paypal.com', '*.stripe.com' ) ) ) {
				return true;
			}
		}
	}

	// Upgrading WP, see update-core.php
	if ( count( $_GET ) == 1
	     && count( $_POST ) == 0
	     && ( $p = cerber_get_get( 'step' ) )
	     && ( $p == 'upgrade_db' )
	     && substr( cerber_script_filename(), - 21 ) == '/wp-admin/upgrade.php' ) {
		return true;
	}

	// Cloud Scanner
	if ( cerber_is_cloud_request() ) {
		return true;
	}

	if ( nexus_is_valid_request() ) {
		return true;
	}

	return false;
}

/**
 * What anti-bot mode to use
 *
 * @return int 1 = Cookies + Fields, 2 = Cookies only
 */
function cerber_antibot_mode() {

	if ( current_user_can( 'manage_options' ) ) {
		return 2;
	}

	if ( cerber_is_wp_ajax() ) {
		if ( crb_get_settings( 'botssafe' ) ) {
			return 2;
		}
		if ( ! empty( $_POST['action'] ) ) {
			if ( $_POST['action'] == 'heartbeat' ) { // WP heartbeat
				//$nonce_state = wp_verify_nonce( $_POST['_nonce'], 'heartbeat-nonce' );
				return 2;
			}
		}

	}

	if ( cerber_get_uri_script() ) {
		return 1;
	}

	// Theme customizer by WP
	if ( isset( $_GET['customize_changeset_uuid'] )
	     && isset( $_GET['customize_theme'] )
	     && isset( $_POST['customize_changeset_uuid'] )
	     && isset( $_POST['wp_customize'] ) ) {
		if ( current_user_can( 'customize' ) ) {
			return 2;
		}
	}

	// Check for third-party exceptions

	if ( class_exists( 'WooCommerce' ) ) {

		if ( ! empty( $_GET['wc-ajax'] )
		     && count( $_GET ) == 1
		     && CRB_Request::is_root_request()
		     && in_array( $_GET['wc-ajax'], array(
				'get_refreshed_fragments',
				'apply_coupon',
				'remove_coupon',
				'update_shipping_method',
				'get_cart_totals',
				'update_order_review',
				'add_to_cart',
				'remove_from_cart',
				'checkout',
				'get_variation',
				'get_customer_location',
			) ) ) {

			return 2;
		}

		if ( cerber_is_permalink_enabled() ) {
			//if ( function_exists( 'wc_get_page_id' ) && 0 === strpos( cerber_get_site_root() . cerber_purify_uri(), get_permalink( wc_get_page_id( 'checkout' ) ) ) ) {
			if ( function_exists( 'wc_get_page_id' ) && CRB_Request::is_full_url_start_with( get_permalink( wc_get_page_id( 'checkout' ) ) ) ) {
				return 2;
			}
		}
		else {
			if ( ! empty( $_GET['order-received'] ) && ! empty( $_GET['key'] ) ) {
				return 2;
			}
		}
	}

	if ( class_exists( 'GFForms' ) ) {
		if ( count( $_GET ) == 2 &&
		     ! empty( $_GET['gf_page'] ) &&
		     ! empty( $_GET['id'] ) &&
		     is_user_logged_in()
		) {

			return 2;
		}
	}

	return 1;
}

/*
 * Disable pingback URL (hide from HEAD)
 */
function cerber_pingback_url( $output, $show ) {
	if ( $show == 'pingback_url' ) {
		$output = '';
	}

	return $output;
}

/**
 * Disable REST API
 *
 */
function cerber_block_rest_api() {
	// OLD WP
	add_filter( 'json_enabled', '__return_false' );
	add_filter( 'json_jsonp_enabled', '__return_false' );
	// @since WP 4.7
	add_filter( 'rest_jsonp_enabled', '__return_false' );
	// Links
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	// Default REST API hooks from default-filters.php
	remove_action( 'init', 'rest_api_init' );
	remove_action( 'rest_api_init', 'rest_api_default_filters', 10 );
	remove_action( 'rest_api_init', 'register_initial_settings', 10 );
	remove_action( 'rest_api_init', 'create_initial_rest_routes', 99 );
	remove_action( 'parse_request', 'rest_api_loaded' );

	if ( cerber_is_rest_url() ) {
		cerber_log( 70 );
		cerber_forbidden_page();
	}
}

/*
 * Redirection control: standard admin/login redirections
 *
 */
add_filter( 'wp_redirect', function ( $location ) {
	global $current_user;

	if ( ( ! $current_user || $current_user->ID == 0 )
	     && crb_block_admin_redirections() ) {
		$rdr = explode( 'redirect_to=', $location );
		if ( isset( $rdr[1] ) ) {
			$redirect_to = urldecode( $rdr[1] ); // a normal
			$redirect_to = urldecode( $redirect_to ); // @since 8.1 - may be twice encoded to bypass
			if ( false !== strpos( $redirect_to, '/wp-admin/' ) ) {
				cerber_404_page();
			}
		}
	}

	return $location;
}, 0 );

function crb_block_admin_redirections() {
	if ( crb_get_settings( 'noredirect' ) && ! cerber_check_groove_x() ) {
		return true;
	}

	return false;
}

// Stop user enumeration ---------------------------------------------------------

if ( crb_get_settings( 'stopenum' ) ) {
	add_action( 'template_redirect', function () {

	    if ( ! $a = crb_array_get( $_GET, 'author' ) ) {
			if ( ! $a = crb_array_get( $_POST, 'author' ) ) { // @since 8.1
				return;
			}
		}

		if ( preg_match( '/\d/', $a ) && ! is_admin() ) {
			cerber_404_page();
		}

	}, 0 );
}

if ( crb_get_settings( 'stopenum_oembed' ) ) {
	add_filter( 'oembed_response_data', function ( $data, $post, $width, $height ) {
		unset( $data['author_url'] );
		unset( $data['author_name'] );

		return $data;
	}, PHP_INT_MAX, 4 );
}

if ( crb_get_settings( 'stopenum_sitemap' ) ) {
	add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
		if ( $name == 'users' ) {
			$provider = false;
		}

		return $provider;
	}, PHP_INT_MAX, 2 );
}

if ( crb_get_settings( 'nouserpages_bylogin' ) ) {

	add_filter( 'author_link', function ( $link, $author_id ) {
        // TODO: Needs to be controlled by website admin to avoid phishing/scam links in case of user generated content
		/*if ( $author_id
		     && ( $d = get_userdata( $author_id ) )
		     && $d->user_url ) {

			return $d->user_url;
		}*/

		return cerber_get_site_url();

	}, PHP_INT_MAX, 2 );

    // Requests by user details are not allowed.
    // Unfortunately, in some WordPress environments, this approach doesn't work

	/*add_filter( 'query_vars', function ( $query_vars ) {

		if ( $key = array_search( 'author', $query_vars ) ) {
			unset( $query_vars[ $key ] );
		}

		if ( $key = array_search( 'author_name', $query_vars ) ) {
			unset( $query_vars[ $key ] );
		}

		return $query_vars;
	}, PHP_INT_MAX );*/

	add_action( 'template_redirect', function () {

		if ( is_author()
		     || ( ( cerber_get_get( 'author_name' )
		            || cerber_get_post( 'author_name' ) )
		          && ! is_admin() ) ) {
			cerber_404_page();
		}

	}, 0 );
}

/*
	Can login form message be shown?
*/
function cerber_can_msg() {
	if ( ! isset( $_REQUEST['action'] ) ) {
		return true;
	}
	if ( $_REQUEST['action'] == 'login' ) {
		return true;
	}

	return false;
	//if ( !in_array( $action, array( 'postpass', 'logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register', 'login' );
}


// Cookies ---------------------------------------------------------------------------------

add_action( 'auth_cookie_valid', 'cerber_cookie_one', 10, 2 );
function cerber_cookie_one( $cookie_elements = null, $user = null ) {
	if ( ! $user ) {
		$user = wp_get_current_user();
	}

	CRB_Globals::$user_id = $user->ID;

	// Mark user with Cerber Groove
	// TODO: remove filter, add IP address and user agent
	$expire = time() + apply_filters( 'auth_cookie_expiration', 14 * 24 * 3600, $user->ID, true ) + ( 24 * 3600 );
	cerber_set_groove( $expire );
}

/*
	Mark switched user with Cerber Groove
	@since 1.6
*/
add_action( 'set_logged_in_cookie', 'cerber_cookie2', 10, 5 );
function cerber_cookie2( $logged_in_cookie, $expire, $expiration, $user_id, $logged_in ) {
	cerber_set_groove( $expire );
}

/*
	Monitoring BAD auth cookies
*/
add_action( 'auth_cookie_bad_username', 'cerber_cookie_bad' );
add_action( 'auth_cookie_bad_hash', 'cerber_cookie_bad' );
add_action( 'auth_cookie_bad_session_token', 'cerber_cookie_bad' );
function cerber_cookie_bad( $cookie_elements ) {
	global $cerber_auth_cookie_bad;

	$cerber_auth_cookie_bad = array( 1, $cookie_elements['username'] );
}

/**
 * Bad (invalid) auth cookie handler
 *
 * @since 8.9.4
 *
 */
function cerber_cookie_bad_proc() {
	global $cerber_auth_cookie_bad;

	if ( empty( $cerber_auth_cookie_bad ) ) {
		return;
	}

	if ( ! headers_sent() ) {
		wp_clear_auth_cookie();
		CRB_Globals::$act_status = 40;
	}
	else {
		CRB_Globals::$act_status = 39;
	}

	cerber_login_failed( $cerber_auth_cookie_bad[1] );
}

/**
 * Is bot detection engine enabled in a given rule_id
 *
 * @param $location string|array  ID of the location
 *
 * @return bool true if enabled
 */
function cerber_antibot_enabled( $location ) {

	if ( crb_get_settings( 'botsipwhite' ) && crb_acl_is_white() ) {
		return false;
	}

	if ( crb_get_settings( 'botsnoauth' ) && is_user_logged_in() ) {
		return false;
	}

	if ( is_array( $location ) ) {
		foreach ( $location as $loc ) {
			if ( crb_get_settings( $loc ) ) {
				return true;
			}
		}
	}
	else {
		if ( crb_get_settings( $location ) ) {
			return true;
		}
	}

	return false;
}

/**
 *
 * @param $location string|array Location (setting)
 *
 */
function cerber_antibot_code( $location ) {

	if ( defined( 'CERBER_DISABLE_SPAM_FILTER' )
	     && is_singular() ) {
		$list = explode( ',', (string) CERBER_DISABLE_SPAM_FILTER );
		$pid = (int) get_queried_object_id();
		if ( in_array( $pid, $list ) ) {
			return;
		}
	}

	if ( ! cerber_antibot_enabled( $location ) ) {
		return;
	}

	$values = cerber_antibot_gene();

	if ( empty( $values ) || ! is_array( $values ) ) {
		return;
	}

	?>
    <script type="text/javascript">
        jQuery( function( $ ) {

            for (let i = 0; i < document.forms.length; ++i) {
                let form = document.forms[i];
				<?php
				foreach ( $values[0] as $value ) {
					echo 'if ($(form).attr("method") != "get") { $(form).append(\'<input type="hidden" name="' . $value[0] . '" value="' . $value[1] . '" />\'); }' . "\n";
				}
				?>
            }

            $(document).on('submit', 'form', function () {
				<?php
				foreach ( $values[0] as $value ) {
					echo 'if ($(this).attr("method") != "get") { $(this).append(\'<input type="hidden" name="' . $value[0] . '" value="' . $value[1] . '" />\'); }' . "\n";
				}
				?>
                return true;
            });

            jQuery.ajaxSetup({
                beforeSend: function (e, data) {

                    if (data.type !== 'POST') return;

                    if (typeof data.data === 'object' && data.data !== null) {
						<?php
						foreach ( $values[0] as $value ) {
							echo 'data.data.append("' . $value[0] . '", "' . $value[1] . '");' . "\n";
						}
						?>
                    }
                    else {
                        data.data = data.data + '<?php
							foreach ( $values[0] as $value ) {
								echo '&' . $value[0] . '=' . $value[1];
							}
							?>';
                    }
                }
            });

        });
    </script>
	<?php

}

/**
 * Generates and saves antibot markers
 *
 * @return array|bool
 */
function cerber_antibot_gene( $recreate = false ) {

	if ( ! crb_get_settings( 'botsany' )
         && ! crb_get_settings( 'botscomm' )
         && ! crb_get_settings( 'botsreg' ) ) {
		return false;
	}

	$ret = array();

	if ( ! $recreate ) {
		$ret = cerber_get_site_option( 'cerber-antibot' );
	}

	if ( $recreate || ! $ret ) {

		$ret = array();

		$max = rand( 2, 4 );
		for ( $i = 1; $i <= $max; $i ++ ) {
			$string1 = crb_random_string( 6, 16, false, true, '_-' );
			$string2 = crb_random_string( 6, 16, true, true, '.@*_[]' );
			$ret[0][] = array( $string1, $string2 );
		}

		$max = rand( 2, 4 );
		for ( $i = 1; $i <= $max; $i ++ ) {
			$string1 = crb_random_string( 6, 16, false, true, '_-' );
			$string2 = crb_random_string( 6, 16, true, true, '.@*_[]' );
			$ret[1][] = array( $string1, $string2 );
		}

		update_site_option( 'cerber-antibot', $ret );
	}

	return $ret;
}

/**
 * Is a POST request (a form) submitted by a bot?
 *
 * @param $location string identificator of a place where we are check for a bot
 *
 * @return bool
 */
function cerber_is_bot( $location = '' ) {

	static $ret = null;

	$remote_ip = cerber_get_remote_ip();

	if ( $remote_ip == '127.0.0.1' || $remote_ip == '::1' ) {
		return false; // v. 7.8.5
	}

	if ( isset( $ret ) ) {
		return $ret;
	}

	if ( ! $location || ! cerber_is_http_post() || cerber_is_wp_cron() ) {
		$ret = false;

		return $ret;
	}

	// Admin || AJAX requests by unauthorized users
	if ( is_admin() ) {
		if ( cerber_is_wp_ajax() ) {
			if ( is_user_logged_in() ) {
				$ret = false;
			}
			elseif ( ! empty( $_POST['action'] ) ) {
				if ( $_POST['action'] == 'heartbeat' ) { // WP heartbeat
					//$nonce_state = wp_verify_nonce( $_POST['_nonce'], 'heartbeat-nonce' );
					$ret = false;
				}
			}
		}
		else {
			$ret = false;
		}
	}

	if ($ret !== null) {
		return $ret;
	}

	if ( ! cerber_antibot_enabled( $location ) ) {
		$ret = false;

		return $ret;
	}

	// Antibot whitelist
	if ( ( $list = crb_get_settings( 'botswhite' ) ) && is_array( $list ) ) {
		$uri = '/' . trim( $_SERVER['REQUEST_URI'], '/' );
		$uri_slash = $uri . ( ( empty( $_GET ) ) ? '/' : '' ); // @since 8.8
		foreach ( $list as $item ) {
			if ( $item[0] == '{' && substr( $item, - 1 ) == '}' ) {
				$pattern = '/' . substr( $item, 1, - 1 ) . '/i';
				if ( @preg_match( $pattern, $uri ) ) {
					CRB_Globals::$req_status = 502;
					$ret = false;

					return $ret;
				}
			}
			else {
				$cmp = ( substr( $item, - 1 ) == '/' ) ? $uri_slash : $uri; // @since 8.8 Someone may specify trailing slash
				if ( false !== strpos( $cmp, $item ) ) {
					CRB_Globals::$req_status = 502;
					$ret = false;

					return $ret;
				}
			}
		}
	}

	$antibot = cerber_antibot_gene();

	$ret = false;

	if ( ! empty( $antibot ) ) {

		$mode = cerber_antibot_mode();

		if ( $mode == 1 ) {
			foreach ( $antibot[0] as $fields ) {
				if ( empty( $_POST[ $fields[0] ] ) || $_POST[ $fields[0] ] != $fields[1] ) {
					$ret = true;
					break;
				}
			}
		}

		if ( ! $ret ) {
			foreach ( $antibot[1] as $fields ) {
				if ( cerber_get_cookie( $fields[0] ) != $fields[1] ) {
					$ret = true;
					break;
				}
			}
		}

		if ( $ret ) {
			CRB_Globals::set_bot_status( CRB_STS_11 );
			lab_save_push( $remote_ip, 333 );
		}
	}

	return $ret;
}

function cerber_geo_allowed( $rule_id = '', $user = null ) {

	if ( ! $rule_id || cerber_is_wp_cron() || ! lab_lab() ) {
		return true;
	}

	if ( crb_acl_is_white() ) {
		return true;
	}

	if ( $user ) {

		if ( $user instanceof WP_User ) {
			$roles = $user->roles;
		}
		else {
			$user  = get_userdata( $user );
			$roles = $user->roles;
		}

		if ( $roles ) {
			foreach ( $roles as $role ) {
				$ret = cerber_check_geo( $rule_id . '_' . $role );
				if ( $ret !== 0 ) { // This rule exists and country was successfully checked
					return $ret;
				}
			}
		}
	}

	$ret = cerber_check_geo( $rule_id );

	if ( $ret === 0 ) {
		return true;
	}

	return $ret;
}

function cerber_check_geo( $rule_id ) {
	if ( ! $rule = cerber_get_geo_rules( $rule_id ) ) {
		return 0;
	}

	if ( ! $country = lab_get_country( cerber_get_remote_ip(), false ) ) {
		return 0;
	}

	if ( in_array( $country, $rule['list'] ) ) {
		if ( $rule['type'] == 'W' ) {
			return true;
		}

		return false;
	}

	if ( $rule['type'] == 'W' ) {
		return false;
	}

	return true;
}

/**
 * Retrieve and return GEO rule(s) from the DB
 *
 * @param string $rule_id ID of the rule
 *
 * @return bool|array False if no rule configured
 */
function cerber_get_geo_rules( $rule_id = '' ) {
    static $rules;
	global $wpdb;

	if ( ! isset( $rules ) || cerber_is_http_post() ) {
		if ( is_multisite() ) {
			$geo = cerber_db_get_var( 'SELECT meta_value FROM ' . $wpdb->sitemeta . ' WHERE meta_key = "' . CERBER_GEO_RULES . '"' );
		}
		else {
			$geo = cerber_db_get_var( 'SELECT option_value FROM ' . $wpdb->options . ' WHERE option_name = "' . CERBER_GEO_RULES . '"' );
		}

		if ( $geo ) {
			$rules = crb_unserialize( $geo );
		}
		else {
			$rules = false;

			return false;
		}
	}

	if ( $rule_id ) {
		$ret = ( ! empty( $rules[ $rule_id ] ) ) ? $rules[ $rule_id ] : false;
	}
	else {
		$ret = $rules;
	}

	return $ret;
}

/**
 * Set user session expiration
 *
 */
add_filter( 'auth_cookie_expiration', function ( $expire, $user_id, $remember ) {
	$time = cerber_get_user_policy( 'auth_expire', $user_id, 'auth_expire' );

	if ( $time ) {
		$expire = 60 * $time;
	}

	return $expire;
}, 10, 3 );

// add_action( 'wp_logout', function(){});
add_action( 'clear_auth_cookie', function () {

	$uid = get_current_user_id();
	if ( $uid ) {
		CRB_Globals::$user_id = $uid;
		cerber_log( 6, '', $uid, CRB_Globals::$act_status );
		CRB_2FA::delete_2fa( $uid );
	}

	cerber_set_cookie( 'cerber_nexus_id', 0, time(), '/' );
} );

// Lost passwords  --------------------------------------------------------------------

add_filter( 'allow_password_reset', 'crb_check_pwd_reset', PHP_INT_MAX, 2 );

/**
 * @param bool|WP_Error $allow
 * @param int $user_id
 *
 * @return bool|WP_Error
 * @since 8.9.5.3
 */
function crb_check_pwd_reset( $allow, $user_id ) {
	if ( ! $allow || ( $allow instanceof WP_Error && $allow->has_errors() ) ) {
		return $allow;
	}

	if ( $user_id && $user_data = crb_get_userdata( $user_id ) ) {

        if ( ( $b = crb_is_user_blocked( $user_data->ID ) )
		     || crb_is_username_prohibited( $user_data->user_login ) ) {

			if ( ! $allow instanceof WP_Error ) {
				$allow = new WP_Error;
			}

            $allow->add( 'cerber_pwd_reset_not_allowed', __( 'Sorry, password reset is not allowed for this user.', 'wp-cerber' ) );

			$status = ( $b ) ? CRB_STS_29 : CRB_STS_30;

			cerber_log( CRB_EV_PRD, crb_get_user_login_field( $user_data->user_login ), 0, $status );
			CRB_Globals::$reset_pwd_denied = true;
		}
	}

	return $allow;
}

/**
 * @return bool True if the WooCommerce reset form has been submitted
 */
function crb_is_woo_reset() {
	return ( isset( $_POST['wc_reset_password'], $_POST['user_login'] )
	         && class_exists( 'WooCommerce' ) );
}

/**
 * Returns login entered by a user on the standard WordPress and WooCommerce login forms
 *
 * @param string $default Default value
 *
 * @return string
 */
function crb_get_user_login_field( $default = '' ) {
	if ( ! empty( $_POST['user_login'] ) ) {
		return sanitize_user( stripslashes( $_POST['user_login'] ) );
	}

	return $default;
}

/**
 * Validate reCAPTCHA for the WordPress lost password form
 */
add_action( 'login_form_' . 'lostpassword', 'cerber_lost_pwd_captcha' );
function cerber_lost_pwd_captcha() {
	$wp_cerber = get_wp_cerber();
	if ( ! $wp_cerber->reCaptchaValidate() ) {

		// Abort password reset
		$_POST['user_login'] = null;

		cerber_log( CRB_EV_PRD, crb_get_user_login_field() );
		CRB_Globals::$reset_pwd_denied = true;
		CRB_Globals::$reset_pwd_msg = '<strong>' . __( 'ERROR:', 'wp-cerber' ) . ' </strong>' . $wp_cerber->reCaptchaMsg( 'lostpassword' );
	}
}

/**
 * Display message on the WordPress lost password form screen
 */
add_action( 'lostpassword_form', 'cerber_lost_show_msg' );
function cerber_lost_show_msg() {
	if ( ! CRB_Globals::$reset_pwd_msg ) {
		return;
	}
	?>
    <script type="text/javascript">
        //document.getElementById('login_error').style.visibility = "hidden";
        document.getElementById('login_error').innerHTML = "<?php echo CRB_Globals::$reset_pwd_msg; ?>";
    </script>
	<?php
}

add_action( 'lostpassword_post', function ( $errors ) {

	add_action( 'retrieve_password_key', function ( $login ) {

		cerber_log( CRB_EV_PRS, $login );

	} );

} );

add_action( 'password_reset', 'crb_pass_reset' );
add_action( 'crb_after_reset', 'crb_pass_reset', 10, 2);

function crb_pass_reset( $user, $user_id = null) {

	if ( ! $user && $user_id ) {
		$user = get_user_by( 'id', $user_id );
	}

	if ( ! $user ) {
		return;
	}

	cerber_log( 20, $user->user_login, $user->ID );

	// Do not log 'clear_auth_cookie' event (logout/login sequence) that occurs after password reset
	CRB_Globals::$do_not_log[ CRB_EV_LIN ] = true;
	CRB_Globals::$do_not_log[6] = true;
}

// Fires in wp_insert_user()
add_action( 'user_register', function ( $user_id ) { // @since 5.6
	$cid = get_current_user_id();
	if ($user = get_user_by( 'ID', $user_id )) {
		if ( $cid && $cid != $user_id ) {
			$ac = 1;
		}
		else {
			$ac = 2;
		}
		cerber_log( $ac, $user->user_login, $user_id );
		crb_log_user_ip( $user_id, $cid );
	}
});

// Fires after a new user has been created in WP dashboard.
add_action( 'edit_user_created_user', function ( $user_id, $notify = null ) {
	if ( $user_id && $user = get_user_by( 'ID', $user_id ) ) {
		cerber_log( 1, $user->user_login, $user_id );
		crb_log_user_ip( $user_id );
	}
}, 10, 2 );

// Log IP address of user registration independently
function crb_log_user_ip( $user_id, $by_user = null ) {
	if ( ! $user_id ) {
		return;
	}
	if ( ! $by_user ) {
		$by_user = get_current_user_id();
	}
	add_user_meta( $user_id, '_crb_reg_', array( 'IP' => cerber_get_remote_ip(), 'user' => $by_user ) );
}

if ( is_multisite() ) {
	add_action( 'wpmu_delete_user', 'crb_user_delete' );
}
else {
	add_action( 'delete_user', 'crb_user_delete' );
}
/**
 * @param $user_id
 * @since 8.6.3.4
 */
function crb_user_delete( $user_id ) {
	global $__deleted_user;
	if ( ! $__deleted_user = get_user_by( 'ID', $user_id ) ) {
		return;
	}
	add_action( 'deleted_user', function ( $user_id ) {
		global $__deleted_user;
		cerber_log( 3, '', $user_id );
		$user_data = array( 'display_name' => $__deleted_user->display_name, 'roles' => $__deleted_user->roles );
		cerber_update_set( 'user_deleted', $user_data, $user_id );
	} );
}

// Lockouts routines ---------------------------------------------------------------------

/**
 * Lock out IP address if it is an alien IP only (browser does not have valid Cerber groove)
 *
 * @param $ip string IP address to block
 * @param integer $reason_id ID of reason of blocking
 * @param string $details Reason of blocking
 * @param null $duration Duration of blocking
 *
 * @return bool|false|int
 */
function cerber_soft_block_add( $ip, $reason_id, $details = '', $duration = null ) {
	if ( cerber_check_groove() ) {
		return false;
	}

	return cerber_block_add( $ip, $reason_id, $details, $duration );
}

/**
 * Lock out IP address
 *
 * @param $ip_address string IP address to block
 * @param integer $reason_id ID of reason of blocking
 * @param string $details Reason of blocking
 * @param int $duration Duration of blocking
 *
 * @return bool
 */
function cerber_block_add( $ip_address = '', $reason_id = 1, $details = '', $duration = null ) {

	if ( cerber_is_cloud_request() ) {
		return false;
	}

	//$wp_cerber = get_wp_cerber();
	//$wp_cerber->setProcessed();

	if ( empty( $ip_address ) || ! filter_var( $ip_address, FILTER_VALIDATE_IP ) ) {
		$ip_address = cerber_get_remote_ip();
	}

	if ( cerber_acl_check( $ip_address ) ) {
		return false;
	}

	$reason_id = absint( $reason_id );
	$update = false;

	if ( $row = cerber_get_block( $ip_address ) ) {
		if ( $row->reason_id == $reason_id ) {
			return false;
		}

		$update = true;
	}

	if ( crb_get_settings( 'subnet' ) ) {
		$ip       = cerber_get_subnet_ipv4( $ip_address );
		$activity = 11;
	}
	else {
		$ip       = $ip_address;
		$activity = 10;
	}

	lab_save_push( $ip_address, $reason_id, $details );

	$reason = cerber_get_reason( $reason_id );

	if ( $details ) {
		$reason .= ': ' . $details;
	}

	$reason_escaped = cerber_real_escape( $reason );

	if ( ! $duration ) {
		$duration = cerber_calc_duration( $ip );
	}
	$until = time() + $duration;

	if ( ! $update ) {
		$result = cerber_db_query( 'INSERT INTO ' . CERBER_BLOCKS_TABLE . ' (ip,block_until,reason,reason_id) VALUES ("' . $ip . '",' . $until . ',"' . $reason_escaped . '",' . $reason_id . ')', 1062 );
	}
	else {
		$result = cerber_db_query( 'UPDATE ' . CERBER_BLOCKS_TABLE . ' SET block_until = ' . $until . ', reason = "' . $reason_escaped . '", reason_id = ' . $reason_id . ' WHERE ip = "' . $ip . '"' );
	}

	if ( $result ) {
		$result = true;
		CRB_Globals::$blocked = $reason_id;

		if ( ! $update ) {
			cerber_log( $activity, null, null, 0, $ip_address );
		}

		crb_event_handler( 'ip_event', array(
			'e_type'    => 'locked',
			'ip'        => $ip_address,
			'reason_id' => $reason_id,
			'reason'    => $reason,
			'update'    => $update
		) );

		if ( ! $update ) {
			do_action( 'cerber_ip_locked', array( 'IP' => $ip_address, 'reason' => $reason ) );
		}
	}
	else {
		$result = false;
		cerber_db_error_log();
	}

	if ( ! $update ) {
		crb_send_lockout( $ip_address );
	}

	return $result;
}

/**
 *
 * Check if an IP address is currently blocked. With C subnet also.
 *
 * @param string $ip an IP address
 *
 * @return bool true if IP is locked out
 */
function cerber_block_check( $ip = '' ) {
	static $cache = array();

	if ( ! isset( $cache[ $ip ] ) ) {
		$cache[ $ip ] = cerber_get_block( $ip );
	}

	return $cache[ $ip ];
}

/**
 *
 * Return the lockout row for an IP if it is blocked. With C subnet also.
 *
 * @param string $ip an IP address
 *
 * @return object|bool object if IP is locked out, false otherwise
 */
function cerber_get_block( $ip = '' ) {

	if ( ! $ip ) {
		$ip = cerber_get_remote_ip();
	}

	if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return false;
	}

	$where = ' WHERE ip = "' . $ip . '"';

	if ( cerber_is_ipv4( $ip ) ) {
		$subnet = cerber_get_subnet_ipv4( $ip );
		$where  .= ' OR ip = "' . $subnet . '"';
	}

	if ( $ret = cerber_db_get_row( 'SELECT * FROM ' . CERBER_BLOCKS_TABLE . $where, MYSQL_FETCH_OBJECT ) ) {
		return $ret;
	}

	return false;
}

/**
 * Returns the number of currently locked out IPs
 *
 * @return int
 *
 * @since 3.0
 */
function cerber_blocked_num() {
	return absint( cerber_db_get_var( 'SELECT count(ip) FROM ' . CERBER_BLOCKS_TABLE ) );
}

function crb_del_expired_blocks() {
    static $done;
	if ( $done ) {
		return;
	}

	$time = time();

	if ( $list = cerber_db_get_col( 'SELECT ip FROM ' . CERBER_BLOCKS_TABLE . ' WHERE block_until < ' . $time ) ) {
		$result = cerber_db_query( 'DELETE FROM ' . CERBER_BLOCKS_TABLE . ' WHERE block_until < ' . $time );
		crb_event_handler( 'ip_event', array(
			'e_type' => 'unlocked',
			'ip'     => $list,
			'result' => $result
		) );
	}

	$done = true;
}

/**
 * Calculate duration for a lockout of an IP address based on settings
 *
 * @param string $ip
 *
 * @return integer Duration in seconds
 */
function cerber_calc_duration( $ip ) {
	$range    = time() - crb_get_settings( 'aglast' ) * 3600;
	$lockouts = cerber_db_get_var( 'SELECT COUNT(ip) FROM ' . CERBER_LOG_TABLE . ' WHERE ip = "' . $ip . '" AND activity IN (10,11) AND stamp > ' . $range );

	if ( $lockouts >= crb_get_settings( 'aglocks' ) ) {
		$duration = crb_get_settings( 'agperiod' ) * 3600;
	}
	else {
		$duration = crb_get_settings( 'lockout' ) * 60;
	}

	$duration = absint( $duration );
	if ( $duration < 60 ) {
		$duration = 60;
	}

	return $duration;
}

/**
 * Calculation of remaining attempts
 *
 * @param $ip string an IP address
 * @param $check_acl bool if true will check the White IP ACL first
 * @param $activity array List of activity IDs to calculate for
 * @param $allowed int  Allowed attempts within $period
 * @param $period int  Period for count attempts in minutes
 *
 * @return int Allowed attempts for present moment
 */
function cerber_get_remain_count( $ip = '', $check_acl = true, $activity = array( CRB_EV_LFL, 152, 51, 52 ), $allowed = null, $period = null ) {

	if ( ! $ip ) {
		$ip = cerber_get_remote_ip();
	}
	else {
		if ( ! $ip = filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return 0;
		}
	}

	if ( ! $allowed ) {
		$allowed = absint( crb_get_settings( 'attempts' ) );
	}

	if ( $check_acl && crb_acl_is_white( $ip ) ) {
		return $allowed; // whitelist = infinity attempts
	}

	if ( ! $period ) {
		$period = absint( crb_get_settings( 'period' ) );
	}

	$range    = time() - $period * 60;
	$in       = implode( ',', array_filter( array_map( 'absint', $activity ) ) );
	$attempts = cerber_db_get_var( 'SELECT count(ip) FROM ' . CERBER_LOG_TABLE . ' WHERE ip = "' . $ip . '" AND activity IN (' . $in . ') AND stamp > ' . $range );

	if ( ! $attempts ) {
		return $allowed;
	}
	else {
		$ret = $allowed - $attempts;
	}
	$ret = $ret < 0 ? 0 : $ret;

	return $ret;
}

/**
 * Is a given IP is allowed to do restricted things?
 * Here Cerber makes its decision.
 *
 * @param $ip string IP address
 * @param $context int What context?
 *
 * @return bool
 */
function cerber_is_ip_allowed( $ip = '', $context = null ) {

	if ( ! $ip ) {
		$ip = cerber_get_remote_ip();
	}
	elseif ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return false;
	}

	$tag = cerber_acl_check( $ip );
	if ( $tag == 'W' ) {
		return true;
	}
	if ( $tag == 'B' ) {
		CRB_Globals::$act_status = 14;
		return false;
	}

	if ( $b = cerber_get_block( $ip ) ) {
		if ( ! in_array( $b->reason_id, crb_context_get_allowed( $context ) ) ) {
			CRB_Globals::$act_status = 13;
			return false;
		}
	}

    if ( $context != CRB_CNTX_NEXUS && cerber_is_citadel() ) {
	    CRB_Globals::$act_status = 19;
		return false;
	}

	if ( lab_is_blocked( $ip, false ) ) {
		CRB_Globals::$act_status = 15;
		return false;
	}

	return true;
}

/**
 * @param int $context_id
 *
 * @return array
 */
function crb_context_get_allowed( $context_id ) {
	$sets = array( CRB_CNTX_SAFE => array( 701, 703, 704, 721 ) );

	if ( $context_id && isset( $sets[ $context_id ] ) ) {
		return $sets[ $context_id ];
	}

	return array();
}

/**
 * Check if a given username is not permitted to log in or register
 *
 * @param $username string
 *
 * @return bool true if username is prohibited
 */
function crb_is_username_prohibited( $username ) {
	if ( ! $username ) {
		return false;
	}

	if ( $list = (array) crb_get_settings( 'prohibited' ) ) {

        $username_lower = strtolower( $username ); // since 'prohibited' gets lower case when settings are saved

		foreach ( $list as $item ) {
			if ( mb_substr( $item, 0, 1 ) == '/' && mb_substr( $item, - 1 ) == '/' ) {
				$pattern = trim( $item, '/' );
				if ( @mb_ereg_match( $pattern, $username, 'i' ) ) {
					return true;
				}
			}
            elseif ( $username_lower == $item ) {
				return true;
			}
		}
	}

	return false;
}

function cerber_get_status( $ip, $activity = null ) {

	if ( ! empty( CRB_Globals::$act_status ) ) {
		return absint( CRB_Globals::$act_status );
	}

	if ( cerber_block_check( $ip ) ) {
		return 13;
	}

	if ( $tag = cerber_acl_check( $ip ) ) {
		if ( $tag == 'W' ) {
			if ( in_array( $activity, array( 1, 2, CRB_EV_LIN, 20, CRB_EV_PRS ) ) ) {
				return 500;
			}
			if ( in_array( $activity, array( 72, 73, 75, 76 ) ) ) {
				return 511;
			}
			if ( $activity == 74 ) {
				return 512;
			}

			return 0;
		}
        elseif ( $tag == 'B' ) {
			return 14;
		}
	}

	if ( cerber_is_citadel() ) {
		return 12;
	}

	if ( lab_is_blocked( $ip, false ) ) {
		return 15;
	}


	return 0;
}

// Access lists (ACL) routines ------------------------------------------------

/**
 * Is an IP whitelisted?
 *
 * @param $ip string
 *
 * @return bool
 */
function crb_acl_is_white( $ip = null ){

	if ( cerber_acl_check( $ip, 'W' ) ) {
		return true;
	}

	return false;
}

/**
 * Is an IP blacklisted?
 *
 * @param $ip string
 *
 * @return bool
 */
function crb_acl_is_black( $ip = '' ) {
	$tag = cerber_acl_check( $ip );
	if ( $tag === 'W' ) {
		return false;
	}
	elseif ( $tag === 'B' ) {
		return true;
	}

	return false;
}

/**
 * Check ACLs for given IP. Some extra lines for performance reason.
 *
 * @param string $ip
 * @param string $tag
 * @param int $acl_slice
 * @param object|false $row If a given IP is in any ACL, it contains an appropriate DB row object:
 *                         for IPv4 all columns
 *                         for IPv6 comments column only
 *
 * @return bool|string If string, returns 'W' or 'B'
 */
function cerber_acl_check( $ip = null, $tag = '', $acl_slice = 0, &$row = false ) {
	static $cache, $row_cache;

	if ( ! $ip ) {
		$ip = cerber_get_remote_ip();
	}

	$key = cerber_get_id_ip( $ip ) . (string) $tag;

	if ( isset( $cache[ $key ] ) ) {
		$row = $row_cache[ $key ];
		return $cache[ $key ];
	}

	if ( cerber_is_ipv6( $ip ) ) {
		$ret = cerber_ipv6_acl_check( $ip, $tag, $acl_slice, $row );
		$cache[ $key ] = $ret;
		$row = $row ? (object) $row : false;
		$row_cache[ $key ] = $row;

		return $ret;
	}

	$long = ip2long( $ip );
	$acl_slice = absint( $acl_slice );

	if ( $tag ) {
		if ( $tag !== 'W' && $tag !== 'B' ) {
			$ret = false;
			$row = false;
		}
		elseif ( $row = cerber_db_get_row( 'SELECT * FROM ' . CERBER_ACL_TABLE . ' WHERE acl_slice = ' . $acl_slice . ' AND ver6 = 0 AND ip_long_begin <= ' . $long . ' AND ' . $long . ' <= ip_long_end AND tag = "' . $tag . '" LIMIT 1', MYSQL_FETCH_OBJECT ) ) {
			$ret = true;
		}
		else {
			$ret = false;
			$row = false;
		}

		$row_cache[ $key ] = $row;
		$cache[ $key ] = $ret;
		return $ret;
	}
	else {
		// We use two queries because of possible overlapping an IP and its network
		if ( $row = cerber_db_get_row( 'SELECT * FROM ' . CERBER_ACL_TABLE . ' WHERE acl_slice = ' . $acl_slice . ' AND ver6 = 0 AND ip_long_begin <= ' . $long . ' AND ' . $long . ' <= ip_long_end AND tag = "W" LIMIT 1', MYSQL_FETCH_OBJECT ) ) {
			$row_cache[ $key ] = $row;
			$cache[ $key ] = $row->tag;
			return $row->tag;
		}
		if ( $row = cerber_db_get_row( 'SELECT * FROM ' . CERBER_ACL_TABLE . ' WHERE acl_slice = ' . $acl_slice . ' AND ver6 = 0 AND ip_long_begin <= ' . $long . ' AND ' . $long . ' <= ip_long_end AND tag = "B" LIMIT 1', MYSQL_FETCH_OBJECT ) ) {
			$row_cache[ $key ] = $row;
			$cache[ $key ] = $row->tag;
			return $row->tag;
		}

		$row_cache[ $key ] = false;
		$cache[ $key ] = false;
		return false;
	}
}

/**
 * IPv6 version of cerber_acl_check() with ranges
 *
 * @param string $ip
 * @param string $tag
 * @param int $acl_slice
 * @param object|null $row @since 8.6.7
 *
 * @return bool|null|string
 */
function cerber_ipv6_acl_check( $ip, $tag = '', $acl_slice = 0, &$row = false ) {

	if ( ! $ip ) {
		$ip = cerber_get_remote_ip();
	}

	list ( $d0, $d1, $d2 ) = crb_ipv6_split( $ip );

	$acl_slice = absint( $acl_slice );

	if ( $tag ) {
		if ( $tag != 'W' && $tag != 'B' ) {
			return false;
		}

		$results = array();

		if ( empty( $row ) ) {
			if ( ! $list = cerber_db_get_col( 'SELECT v6range FROM ' . CERBER_ACL_TABLE . ' WHERE acl_slice = ' . $acl_slice . ' AND ver6 = 1 AND ip_long_begin <= ' . $d0 . ' AND ' . $d0 . ' <= ip_long_end AND tag = "' . $tag . '"' ) ) {
				return false;
			}
		}
		else {
			if ( ! $results = cerber_db_get_results( 'SELECT v6range,comments FROM ' . CERBER_ACL_TABLE . ' WHERE acl_slice = ' . $acl_slice . ' AND ver6 = 1 AND ip_long_begin <= ' . $d0 . ' AND ' . $d0 . ' <= ip_long_end AND tag = "' . $tag . '"' ) ) {
				return false;
			}
			$list = array_column( $results, 'v6range' );
		}

		if ( ! crb_ipv6_is_in_range_list( $d1, $d2, $list, $key ) ) {
			return false;
		}

		$row = crb_array_get( $results, $key );

		return true;
	}
	else {
		if ( ! $results = cerber_db_get_results( 'SELECT v6range,tag,comments FROM ' . CERBER_ACL_TABLE . ' WHERE acl_slice = ' . $acl_slice . ' AND ver6 = 1 AND ip_long_begin <= ' . $d0 . ' AND ' . $d0 . ' <= ip_long_end' ) ) {
			return false;
		}

		if ( $tag = crb_ipv6_get_tag( $d1, $d2, $results, $key ) ) {
			$row = crb_array_get( $results, $key );
		}

		return $tag;
	}
}

function crb_ipv6_is_in_range( $ip, $range ) {

	list ( $d0, $d1, $d2 ) = crb_ipv6_split( $ip );

	if ( $range['begin'] >= $d0 || $d0 >= $range['end'] ) {
		return false;
	}

	$list = array( $range['IPV6range'] );

	if ( crb_ipv6_is_in_range_list( $d1, $d2, $list ) ) {
		return true;
	}

	return false;
}

/**
 * @param int $d1
 * @param int $d2
 * @param array $list
 * @param int $key
 *
 * @return bool
 */
function crb_ipv6_is_in_range_list( $d1, $d2, &$list, &$key = null ) {

	foreach ( $list as $key => $v6range ) {
		list( $begin1, $begin2, $end1, $end2 ) = explode( '#', $v6range, 4 );
		if ( crb_compare_numbers( $d1, $d2, $begin1, $begin2 )
		     && crb_compare_numbers( $end1, $end2, $d1, $d2 ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Check to what ACL a given IP belongs with white list priority
 *
 * @param $d1
 * @param $d2
 * @param $v6rows
 * @param int $key
 *
 * @return bool|string false if IP is not in any list, 'B' if is in the black, 'W' if is in the white
 */
function crb_ipv6_get_tag( $d1, $d2, &$v6rows, &$key = null ) {
	$black = false;

	foreach ( $v6rows as $key => $row ) {
		if ( $black && ( $row['tag'] == 'B' ) ) {
			continue;
		}
		list( $begin1, $begin2, $end1, $end2 ) = explode( '#', $row['v6range'], 4 );
		if ( crb_compare_numbers( $d1, $d2, $begin1, $begin2 )
		     && crb_compare_numbers( $end1, $end2, $d1, $d2 ) ) {
			if ( $row['tag'] == 'W' ) {
				return 'W';
			}
			$black = true;
		}
	}

	if ( $black ) {
		return 'B';
	}

	return false;
}

/**
 * Returns true if the number $a1.$a2 is bigger than or equal the number $b1.$b2
 *
 * @param $a1 integer
 * @param $a2 integer
 * @param $b1 integer
 * @param $b2 integer
 *
 * @return bool
 */
function crb_compare_numbers( $a1, $a2, $b1, $b2 ) {
	if ( $a1 > $b1 ) {
		return true;
	}

	if ( $a1 < $b1 ) {
		return false;
	}

	if ( $a2 >= $b2 ) {
		return true;
	}

	return false;
}

/**
 * Split an IPv6 into 3 integer numbers that can be handled by PHP and MySQL
 * 15 bytes HEX number converted to integer is a maximum for PHP 7.X
 *
 * @param string $ip Valid IPv6
 *
 * @return array
 */
function crb_ipv6_split( $ip ) {
	$hex = (string) bin2hex( inet_pton( $ip ) );

	return array(
		hexdec( substr( $hex, 0, 15 ) ),
		hexdec( substr( $hex, 15, 15 ) ),
		hexdec( substr( $hex, 30, 2 ) )
	);
}

function crb_ipv6_prepare( $begin, $end ) {
	list ( $b0, $b1, $b2 ) = crb_ipv6_split( $begin );
	list ( $e0, $e1, $e2 ) = crb_ipv6_split( $end );

	return array( $b0, $e0, $b1 . '#' . $b2 . '#' . $e1 . '#' . $e2 );
}

/*
 * Logging directly to the file
 *
 * CERBER_FAIL_LOG optional, full path including filename to the log file
 * CERBER_LOG_FACILITY optional, use to specify what type of program is logging the messages
 *
 * */
function cerber_file_log( $user_login, $ip ) {
	if ( defined( 'CERBER_FAIL_LOG' ) ) {
		if ( $log = @fopen( CERBER_FAIL_LOG, 'a' ) ) {
			$pid = absint( @posix_getpid() );
			@fwrite( $log, date( 'M j H:i:s ' ) . $_SERVER['SERVER_NAME'] . ' Cerber(' . $_SERVER['HTTP_HOST'] . ')[' . $pid . ']: Authentication failure for ' . $user_login . ' from ' . $ip . "\n" );
			@fclose( $log );
		}
	} else {
		@openlog( 'Cerber(' . $_SERVER['HTTP_HOST'] . ')', LOG_NDELAY | LOG_PID, defined( 'CERBER_LOG_FACILITY' ) ? CERBER_LOG_FACILITY : LOG_AUTH );
		@syslog( LOG_NOTICE, 'Authentication failure for ' . $user_login . ' from ' . $ip );
		@closelog();
	}
}

/*
	Return wildcard - string like subnet Class C
*/
function cerber_get_subnet_ipv4( $ip ) {
	return preg_replace( '/\.\d{1,3}$/', '.*', $ip );
}

/*
	Check if given IP address or wildcard or CIDR is valid
*/
function cerber_is_ip_or_net( $ip ) {
	if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return true;
	}
	// WILDCARD: 192.168.1.*
	$ip = str_replace( '*', '0', $ip );
	//if ( @inet_pton( $ip ) ) {
	if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return true;
	}
	// CIDR: 192.168.1/24
	if ( strpos( $ip, '/' ) ) {
		$cidr = explode( '/', $ip );
		$net  = $cidr[0];
		$mask = absint( $cidr[1] );
		$dots = substr_count( $net, '.' );
		if ( $dots < 3 ) {
			if ( $dots == 1 ) {
				$net .= '.0.0';
			} elseif ( $dots == 2 ) {
				$net .= '.0';
			}
		}
		if ( ! cerber_is_ipv4( $net ) ) {
			return false;
		}
		if ( ! is_numeric( $mask ) ) {
			return false;
		}

		return true;
	}

	return false;
}

/**
 * Tries to recognize a valid IP range (with a dash) in a given string.
 * Supports IPv4 & IPv6
 *
 * @param string $string String to detect for an IP range
 *
 * @return array|bool|string Return IP range as an array for a valid range, string in case of a single IP, false otherwise
 */
function cerber_parse_ip_range( $string ) {

	if ( cerber_is_ip_or_net( $string ) ) {
		return $string;
	}

	$explode = explode( '-', $string, 2 );
	if ( ! is_array( $explode ) || 2 != count( $explode ) ) {
		return false;
	}

	$begin_ip = filter_var( trim( $explode[0] ), FILTER_VALIDATE_IP );
	$end_ip   = filter_var( trim( $explode[1] ), FILTER_VALIDATE_IP );

	if ( ! $begin_ip || ! $end_ip ) {
		return false;
	}

	if ( cerber_is_ipv4( $begin_ip ) && cerber_is_ipv4( $end_ip ) ) {
		$begin = ip2long( $begin_ip );
		$end   = ip2long( $end_ip );

		if ( $begin > $end ) {
			return false;
		}

		$ver6 = 0;
		$v6range = '';
	}
    elseif ( cerber_is_ipv6( $begin_ip ) && cerber_is_ipv6( $end_ip ) ) {
		$ver6 = 1;
		list( $begin, $end, $v6range ) = crb_ipv6_prepare( $begin_ip, $end_ip );

	    // @since 8.6.1 check for a valid IPv6 range: begin < end
	    if ( $begin > $end ) {
		    return false;
	    }
	    list( $begin1, $begin2, $end1, $end2 ) = explode( '#', $v6range, 4 );
	    if ( crb_compare_numbers( $begin1, $begin2, $end1, $end2 ) ) {
		    return false;
	    }
    }
	else {
		return false;
	}

	return array(
		'range'     => $begin_ip . ' - ' . $end_ip,
		'begin_ip'  => $begin_ip,
		'end_ip'    => $end_ip,
		'begin'     => $begin,
		'end'       => $end,
		'IPV6'      => $ver6,
		'IPV6range' => $v6range
	);
}

/**
 * Convert a network wildcard string like x.x.x.* to an IP v4 range
 *
 * @param $wildcard string
 *
 * @return array|bool|string False if no wildcard found, otherwise result of cerber_parse_ip()
 */
function cerber_wildcard2range( $wildcard ) {
	if ( false === strpos( $wildcard, '*' ) ) {
		return false;
	}

	if ( ! strpos( $wildcard, ':' ) ) {
		$begin = str_replace( '*', '0', $wildcard );
		$end   = str_replace( '*', '255', $wildcard );
		if ( ! cerber_is_ipv4( $begin ) || ! cerber_is_ipv4( $end ) ) {
			return false;
		}
	}
	else {
		$begin = str_replace( ':*', ':0000', $wildcard );
		$end   = str_replace( ':*', ':ffff', $wildcard );
		if ( ! cerber_is_ipv6( $begin ) || ! cerber_is_ipv6( $end ) ) {
			return false;
		}
	}

	return cerber_parse_ip_range( $begin . ' - ' . $end );
}

/**
 * Convert a CIDR to an IP v4 range
 *
 * @param $cidr string
 *
 * @return array|bool|string
 */
function cerber_cidr2range( $cidr = '' ) {
	if ( ! strpos( $cidr, '/' ) ) {
		return false;
	}
	$cidr = explode( '/', $cidr );
	$net  = $cidr[0];
	$mask = absint( $cidr[1] );
	$dots = substr_count( $net, '.' );
	if ( $dots < 3 ) { // not completed CIDR
		if ( $dots == 1 ) {
			$net .= '.0.0';
		} elseif ( $dots == 2 ) {
			$net .= '.0';
		}
	}
	if ( ! cerber_is_ipv4( $net ) ) {
		return false;
	}
	if ( ! is_numeric( $mask ) ) {
		return false;
	}

	if ( $mask == 32 ) {
		$begin_ip = $net;
		$end_ip = $net;
	}
	else {
		$begin_ip = long2ip( ( ip2long( $net ) ) & ( ( - 1 << ( 32 - (int) $mask ) ) ) );
		$end_ip = long2ip( ( ip2long( $net ) ) + pow( 2, ( 32 - (int) $mask ) ) - 1 );
	}

	return cerber_parse_ip_range( $begin_ip . ' - ' . $end_ip );
}

/**
 * Tries to recognize if a given string contains an IP range/CIDR/wildcard
 * Supports IPv4 & IPv6
 *
 * If returns false, there is no IP in the string in any form
 *
 * @param $string string Anything
 *
 * @return array|string Return an array if an IP range recognized, string with IP in case of a single IP, false otherwise
 */
function cerber_any2range( $string ) {
	if ( ! $string
	     || ! is_string( $string ) ) {
		return false;
	}

	$string = trim( $string );

	if ( filter_var( $string, FILTER_VALIDATE_IP ) ) {
		return $string;
	}

	// Do not change the order!
	$ret = cerber_wildcard2range( $string );
	if ( ! $ret ) {
		$ret = cerber_cidr2range( $string );
	}
	if ( ! $ret ) {
		$ret = crb_ipv6_cidr2range( $string );
	}
	if ( ! $ret ) {
		$ret = cerber_parse_ip_range( $string ); // must be last due to checking for cidr and wildcard
	}

	return $ret;
}

function crb_ipv6_cidr2range( $cidr ) {
	if ( ! strpos( $cidr, '/' ) ) {
		return false;
	}

	list( $net, $mask ) = explode( '/', $cidr );
	$mask = (int) $mask;
	if ( ! cerber_is_ipv6( $net ) || ! is_integer( $mask ) || $mask < 0 || $mask > 128 ) {
		return false;
	}

	$begin_hex = (string) bin2hex( inet_pton( $net ) );
	$begin_ip  = cerber_ipv6_expand( $net );

	// These are cases that PHP can't handle as integers

	$exceptions = array(
		65 => '7fffffffffffffff',
		1  => '7fffffffffffffffffffffffffffffff',
		0  => 'ffffffffffffffffffffffffffffffff'
	);

	if ( isset( $exceptions[ $mask ] ) ) {
		$add = $exceptions[ $mask ];
	}
    elseif ( $mask >= 66 ) {
		$add = (string) dechex( pow( 2, ( 128 - $mask ) ) - 1 );
	}
	else { // $mask <= 64
		$add = (string) dechex( pow( 2, ( 128 - $mask - 64 ) ) - 1 ) . 'ffffffffffffffff';
	}

	$end_hex = str_pad( crb_summ_hex( $begin_hex, $add ), 32, '0', STR_PAD_LEFT );

	$end_ip = implode( ':', str_split( $end_hex, 4 ) );

	return cerber_parse_ip_range( $begin_ip . ' - ' . $end_ip );
}

/**
 * Calculate the summ of two any HEX numbers
 *
 * @param string $hex1 Number with no 0x prefix
 * @param string $hex2 Number with no 0x prefix
 *
 * @return string
 */
function crb_summ_hex( $hex1, $hex2) {
	$hex1 = ltrim( $hex1, '0' );
	$hex2 = ltrim( $hex2, '0' );

	if ( strlen( $hex1 ) > strlen( $hex2 ) ) {
		$h1 = $hex1;
		$h2 = $hex2;
	}
	else {
		$h1 = $hex2;
		$h2 = $hex1;
	}

	$h1 = str_split( (string) $h1 );
	$h2 = str_split( (string) $h2 );

	$h1 = array_reverse( array_map( 'hexdec', $h1 ) );
	$h2 = array_reverse( array_map( 'hexdec', $h2 ) );

	$max1 = count( $h1 ) - 1;
	$max2 = count( $h2 ) - 1;
	$i      = 0;
	$r      = 0;
	$finish = false;

	while ( $i <= $max1 && ! $finish ) {
		if ( $i <= $max2 ) {
			$h1[ $i ] = $h1[ $i ] + $h2[ $i ] + $r;
		}
		else {
			if ( ! $r ) {
				$finish = true;
			}
			$h1[ $i ] += $r;
		}
		if ( $h1[ $i ] >= 16 ) {
			$r        = 1;
			$h1[ $i ] -= 16;
		}
		else {
			$r = 0;
		}
		$i ++;
	}

	if ( $r ) {
		$h1[] = 1;
	}

	$h1 = array_reverse( array_map( 'dechex', $h1 ) );

	return implode( '', $h1 );
}

/*
	Check for given IP address or subnet belong to this session.
*/
function cerber_is_myip( $ip ) {

	if ( ! is_string( $ip ) ) {
		return false;
	}

	$remote_ip = cerber_get_remote_ip();

	if ( $ip == $remote_ip ) {
		return true;
	}
	if ( $ip == cerber_get_subnet_ipv4( $remote_ip ) ) {
		return true;
	}

	return false;
}

/**
 * Supports IPv4 & IPv6 ranges
 *
 * @param array $range
 * @param string $ip
 *
 * @return bool
 */
function cerber_is_ip_in_range( $range, $ip ) {

	if ( ! is_array( $range ) ) {
		return false;
	}

	// $range = IPv6 range

	if ( $range['IPV6'] ) {
		if ( cerber_is_ipv4( $ip ) ) {
			return false;
		}

		return crb_ipv6_is_in_range( $ip, $range );
	}

	// $range = IPv4 range

	if ( cerber_is_ipv6( $ip ) ) {
		return false;
	}

	$long = ip2long( $ip );

	if ( $range['begin'] <= $long && $long <= $range['end'] ) {
		return true;
	}

	return false;
}

/**
 * Display 404 page to bump bots and bad guys
 *
 * @param bool $simple If true, force displaying basic 404 page
 */
function cerber_404_page( $simple = false ) {
	global $wp_query;

	header_remove( 'Link' ); // Avoid "promoting" REST API URL by WordPress in rest_output_link_header()

	$method = crb_get_settings( 'page404' );

	if ( $method == 2
	     && $url = crb_get_settings( 'page404_redirect' ) ) {
		if ( ! headers_sent() ) {
			header( 'Location: ' . $url, true, 302 );

			exit;
		}
	}
	elseif ( ! $simple ) {
		if ( function_exists( 'status_header' ) ) {
			status_header( '404' );
		}
		if ( isset( $wp_query ) && is_object( $wp_query ) ) {
			$wp_query->set_404();
		}
		if ( 0 == $method ) {
			$template = null;

			// Avoid the fatal error "Call to a member function is_block_editor() on null"
			remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

			if ( function_exists( 'get_404_template' ) ) {
				$template = get_404_template();
			}

			if ( function_exists( 'apply_filters' ) ) {
				$template = apply_filters( 'cerber_404_template', $template );
			}

			if ( $template
                 && @file_exists( $template ) ) {
				include( $template );

				exit;
			}
		}
	}

    // Showing a simple 404 page

	header( 'HTTP/1.0 404 Not Found', true, 404 );
	echo '<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL ' . esc_url( $_SERVER['REQUEST_URI'] ) . ' was not found on this server.</p></body></html>';
	cerber_traffic_log(); // do not remove!

	exit;
}

/*
	Display Forbidden page
*/
function cerber_forbidden_page() {
	$wp_cerber = get_wp_cerber();
	$sid       = strtoupper( $wp_cerber->getRequestID() );
	status_header( '403' );
	header( 'HTTP/1.0 403 Access Forbidden', true, 403 );
	?>
    <!DOCTYPE html>
    <html style="height: 100%;">
    <head>
        <meta charset="UTF-8">
        <title>403 Access Forbidden</title>
        <style>
            @media screen and (max-width: 800px) {
                body > div > div > div div {
                    display: block !important;
                    padding-right: 0 !important;
                }

                body {
                    text-align: center !important;
                }
            }
        </style>
    </head>
    <body style="height: 90%;">
    <div style="display: flex; align-items: center; justify-content: center; height: 90%;">
        <div style="background-color: #eee; width: 70%; border: solid 3px #ddd; padding: 1.5em 3em 3em 3em; font-family: Arial, Helvetica, sans-serif;">
            <div style="display: table-row;">
                <div style="display: table-cell; font-size: 150px; color: red; vertical-align: top; padding-right: 50px;">
                    &#9995;
                </div>
                <div style="display: table-cell; vertical-align: top;">
                    <h1 style="margin-top: 0;"><?php _e( "We're sorry, you are not allowed to proceed", 'wp-cerber' ); ?></h1>
                    <p><?php _e( 'Your request looks suspiciously similar to automated requests from spam posting software or it has been denied by a security policy configured by the website administrator.', 'wp-cerber' ); ?></p>
                    <p><?php _e( 'If you believe you should be able to perform this request, please let us know.', 'wp-cerber' ); ?></p>
                    <p style="margin-top: 2em;">
                    <pre style="color: #777">RID: <?php echo $sid; ?></pre>
                    </p>
                    <p style="margin-top: 2em; font-size: 80%">
                        Know more: <a href="https://wpcerber.com/rid-request-not-allowed-wordpress/" target="_blank" rel="noopener noreferrer">Documentation</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
	<?php
	cerber_traffic_log();  // do not remove!
	exit;
}

// Citadel mode -------------------------------------------------------------------------------------

function cerber_enable_citadel() {

	if ( ! crb_get_settings( 'citadel_on' ) ) {
		return;
	}

	if ( cerber_is_citadel() ) {
		return;
	}

	cerber_update_set( 'cerber_citadel', 1, null, false, time() + crb_get_settings( 'ciduration' ) * 60 );

    cerber_log( 12 );

	// Notify admin
	if ( crb_get_settings( 'cinotify' ) ) {
		cerber_send_message( 'citadel' );
	}
}

function cerber_disable_citadel() {
	cerber_delete_set( 'cerber_citadel' );
}

function cerber_is_citadel() {
	return (bool) cerber_get_set( 'cerber_citadel', null, false );
}

class CRB_Messaging {
	private $smpt_enabled = false;
	private $no_smtp = false; // If true, it temporarily disables using the WP Cerber's SMTP settings
	private $mailer; // Last used mailer
    private $email_args = array(); // Holds email parameters in case of postponed sending
	private $error = false; // If an error occurred while sending

	/**
	 *
	 * Send message via specified channels
	 *
	 * @param string $type Notification type
	 * @param array $msg_parts Additional message
	 * @param array $channels Channels to send the message
	 * @param bool $ignore Ignore global limits
	 * @param array $args Additional parameters
	 *
	 * @return array|false The list of recipients on success
	 *
	 * @since 9.5.5.1
	 */
	public function send_message( $type, $msg_parts = array(), $channels = array(), $ignore = false, $args = array() ) {

		$type = $type ?: 'generic';

		$sb = $msg_parts['subj'] ?? '';
		$msg = $msg_parts['text'] ?? '';
		$msg_masked = $msg_parts['text_masked'] ?? '';
		$more = $msg_parts['more'] ?? '';
		$ip = $msg_parts['ip'] ?? '';

		$channels = array_merge( CRB_CHANNELS, $channels );

		if ( ! array_filter( $channels ) ) {
			return false;
		}

		if ( ! $ignore
		     && ! is_admin()
		     && in_array( $type, array( 'lockout', 'send_alert' ) ) ) {

			$channels = $this->check_limits( $channels );

			if ( ! array_filter( $channels ) ) {
				return false;
			}
		}

		$html_mode = false;
		$blogname = crb_get_blogname_decoded();

		if ( ! in_array( $type, array( '2fa', 'new_version' ) ) ) {
			$subj = '[' . $blogname . '] WP Cerber: ' . $sb;
		}
		else {
			$subj = '[' . $blogname . '] ' . $sb;
		}

		$body = '';
		$body_masked = '';

		if ( is_array( $msg ) ) {
			$msg = implode( "\n\n", $msg ) . "\n\n";
		}

		if ( is_array( $msg_masked ) ) {
			$msg_masked = implode( "\n\n", $msg_masked ) . "\n\n";
		}

		$last = null;

		switch ( $type ) {
			case 'citadel':
				$max = cerber_db_get_var( 'SELECT MAX(stamp) FROM ' . CERBER_LOG_TABLE . ' WHERE  activity = ' . CRB_EV_LFL );
				if ( $max ) {
					$last_date = cerber_date( $max, false );
					//$last      = $wpdb->get_row( 'SELECT * FROM ' . CERBER_LOG_TABLE . ' WHERE stamp = ' . $max . ' AND activity = 7' );
					$last = cerber_db_get_row( 'SELECT * FROM ' . CERBER_LOG_TABLE . ' WHERE stamp = ' . $max . ' AND activity = ' . CRB_EV_LFL, MYSQL_FETCH_OBJECT );
				}

				if ( ! $last ) { // workaround for the empty log table
					$last = new stdClass();
					$last->ip = CERBER_NO_REMOTE_IP;
					$last->user_login = 'test';
				}

				$subj .= __( 'Citadel mode is active', 'wp-cerber' );

				$body = sprintf( __( 'Citadel mode has been activated after %d failed login attempts in %d minutes.', 'wp-cerber' ), crb_get_settings( 'cilimit' ), crb_get_settings( 'ciperiod' ) ) . "\n\n";
				$body .= sprintf( __( 'Last failed attempt was at %s from IP %s using username: %s.', 'wp-cerber' ), $last_date, $last->ip, $last->user_login ) . "\n\n";

				$more = __( 'View activity in the Dashboard', 'wp-cerber' ) . ': ' . cerber_admin_link( 'activity', array(), false, false ) . "\n\n";
				break;
			case 'lockout':
				$max = cerber_db_get_var( 'SELECT MAX(stamp) FROM ' . CERBER_LOG_TABLE . ' WHERE  activity IN (10,11)' );

				if ( $max ) {
					$last_date = cerber_date( $max, false );
					$last = cerber_db_get_row( 'SELECT * FROM ' . CERBER_LOG_TABLE . ' WHERE stamp = ' . $max . ' AND activity IN (10,11)', MYSQL_FETCH_OBJECT );
				}
				else {
					$last_date = '';

					// workaround for the empty log table
					$last = new stdClass();
					$last->ip = CERBER_NO_REMOTE_IP;
					$last->user_login = 'test';
				}

				$active = cerber_blocked_num();

				if ( $last->ip && ( $block = cerber_get_block( $last->ip ) ) ) {
					$reason = $block->reason;
				}
				else {
					$reason = __( 'unspecified', 'wp-cerber' );
				}

				$subj .= __( 'Number of lockouts is increasing', 'wp-cerber' ) . ' (' . $active . ')';

				$body = __( 'Number of active lockouts at the moment:', 'wp-cerber' ) . ' ' . $active . "\n\n";

				if ( $last_date ) {
					$body .= sprintf( __( 'Last security action: IP address %s was locked out on %s', 'wp-cerber' ), $last->ip . ' (' . @gethostbyaddr( $last->ip ) . ')', $last_date ) . "\n\n";
					$body .= __( 'Reason:', 'wp-cerber' ) . ' '.strip_tags( $reason ) . "\n\n";
				}

				$more = __( 'View activity for this IP:', 'wp-cerber' ) . ' ' . cerber_admin_link( 'activity', array(), false, false ) . '&filter_ip=' . $last->ip . "\n\n";
				$more .= __( 'View all lockouts:', 'wp-cerber' ) . ' ' . cerber_admin_link( 'lockouts', array(), false, false ) . "\n\n";

				$more .= __( 'Learn more about advanced alerting in WP Cerber:', 'wp-cerber' ) . ' ' . 'https://wpcerber.com/wordpress-notifications-made-easy/';
				break;
			case 'new_version':
				$body = $msg . "\n\n";
				$more = __( 'Website', 'wp-cerber' ) . ': ' . $blogname;
				break;
			case 'shutdown':
				$d = __( 'The WP Cerber Security plugin has been deactivated', 'wp-cerber' );
				$subj = '[' . $blogname . '] ' . $d;
				$body .= "\n" . $d . "\n\n";
				if ( ! is_user_logged_in() ) {
					$u = __( 'Unknown', 'wp-cerber' );
				}
				else {
					$user = wp_get_current_user();
					$u = $user->display_name;
				}
				$body .= __( 'Website', 'wp-cerber' ) . ': ' . $blogname . "\n";

				$more = __( 'By the user', 'wp-cerber' ) . ': ' . $u . "\n";
				$more .= __( 'From the IP address', 'wp-cerber' ) . ': ' . cerber_get_remote_ip() . "\n";
				$whois = cerber_ip_whois_info( cerber_get_remote_ip() );
				if ( ! empty( $whois['data']['country'] ) ) {
					$more .= __( 'From the country', 'wp-cerber' ) . ': ' . cerber_country_name( $whois['data']['country'] );
				}
				break;
			case 'activated':
				$subj = '[' . $blogname . '] ' . __( 'The WP Cerber Security plugin is now active', 'wp-cerber' );
				$body = "\n" . __( 'WP Cerber is now active and has started protecting your site', 'wp-cerber' ) . "\n\n";
				$body .= __( 'Getting Started Guide', 'wp-cerber' ) . "\n\n";
				$body .= 'https://wpcerber.com/getting-started/' . "\n\n";
				$body .= 'Is your website under Cloudflare? You have to enable a crucial WP Cerber setting.' . "\n\n";
				$body .= 'https://wpcerber.com/cloudflare-and-wordpress-cerber/' . "\n\n";
				$body .= 'Be in touch with the developer.' . "\n\n";
				$body .= 'Follow Cerber on X: https://twitter.com/wpcerber' . "\n\n";
				$body .= "Subscribe to Cerber's newsletter: https://wpcerber.com/subscribe-newsletter/" . "\n\n";
				break;
			case 'newlurl':
				$subj .= __( 'New Custom login URL', 'wp-cerber' );
				$body .= $msg;
				break;
			case 'send_alert':
				$body = __( 'A new activity has occurred', 'wp-cerber' ) . "\n\n";
				$body_masked = $body;
				$body .= $msg;
				$body_masked .= $msg_masked;
				break;
			case 'report':
				list ( $title, $body ) = cerber_generate_email_report( $args );
				$subj .= $title;
				$link = cerber_admin_link( 'notifications', array(), false, false );
				$body .= '<br/>' . __( 'To change reporting settings visit', 'wp-cerber' ) . ' <a href="' . $link . '">' . $link . '</a>';
				$body .= $msg;
				$html_mode = true;
				break;
			case 'scan':
				$subj .= __( 'Scanner Report', 'wp-cerber' );
				$body = $msg;

                $link = cerber_admin_link( 'scan_main', array(), false, false );
				$body .= '<p>' . __( 'To view full report, navigate to:', 'wp-cerber' ) . ' <a href="' . $link . '">' . $link . '</a></p>';

                $link = cerber_admin_link( 'scan_schedule', array(), false, false );
				$body .= '<br/>' . __( 'To modify your reporting settings, navigate to:', 'wp-cerber' ) . ' <a href="' . $link . '">' . $link . '</a>';

                $html_mode = true;
				break;
			case 'generic':
			case '2fa':
			default:
				$body = $msg;
				break;
		}

		$to_list = array();
		$to = '';

		if ( $channels['email'] ) {
			$to_list = cerber_get_email( $type, $args );
			$to = implode( ', ', $to_list );
		}

		$body_filtered = apply_filters( 'cerber_notify_body', $body, array(
			'type'    => $type,
			'IP'      => $ip,
			'to'      => $to,
			'subject' => $subj
		) );

		if ( $body_filtered && is_string( $body_filtered ) ) {
			$body = $body_filtered;
		}

		if ( ! $body ) {
			//return new WP_Error( 'notifications', 'No text of the message provided.' );
			return false;
		}

		$footer = '';
		$mf = crb_get_settings( 'email_format' );

		if ( ( 1 > $mf )
		     && ! in_array( $type, array( 'shutdown', 'generic', '2fa' ) )
		     && $lolink = cerber_get_login_url() ) {

			$lourl = urldecode( $lolink );

			if ( $html_mode ) {
				$lourl = '<a href="' . $lolink . '">' . $lourl . '</a>';
			}

			$footer .= "\n\n" . __( 'Your login page:', 'wp-cerber' ) . ' ' . $lourl;

		}

		if ( ( 1 > $mf )
		     && $type == 'report'
		     && $date = lab_lab( 1 ) ) {
			$footer .= "\n\n" . __( 'Your license is valid until', 'wp-cerber' ) . ' ' . $date;
		}

		if ( $type != '2fa' ) {
			$footer .= "\n\n\n" . __( 'This message was created by', 'wp-cerber' ) . ' WP Cerber Security ' . ( lab_lab() ? 'Professional ' : '' ) . ( 1 > $mf ? CERBER_VER : '' );
			$footer .= "\n" . __( 'Date:', 'wp-cerber' ) . ' ' . cerber_date( time(), false );
			$footer .= "\n" . 'https://wpcerber.com';
		}

		// Everything is prepared, let's send it out

		$results = array();
		$recipients = array();
		$success = false;

		$go = 'pushbullet';
		if ( $channels[ $go ] && ! $html_mode ) {
			$body_go = ( $type == 'send_alert' && crb_get_settings( 'pb_mask' ) ) ? $body_masked : $body;
			$res = cerber_pb_send( $subj, $body_go, $more, $footer );
			if ( $res && ! crb_is_wp_error( $res ) ) {
				$results[ $go ] = true;
				$recipients[ $go ] = cerber_pb_get_active();
				$success = true;
			}
		}

		$go = 'email';
		if ( $channels[ $go ] ) {
			$body_go = ( $type == 'send_alert' && crb_get_settings( 'email_mask' ) ) ? $body_masked : $body;
			if ( $results[ $go ] = $this->send_email( $type, $html_mode, $to_list, $subj, $body_go, $more, $footer, $ip ) ) {
				$recipients[ $go ] = $to;
				$success = true;
			}
		}

		if ( ! $success ) {

			return false;
		}

		$sent = cerber_get_set( '_cerber_last_send' );

		if ( ! is_array( $sent ) ) {
			$sent = array();
		}

		foreach ( $results as $channel_id => $result ) {
			$sent[ $channel_id ] = ( $result ) ? time() : 0;
		}

		cerber_update_set( '_cerber_last_send', $sent );

		return $recipients;
	}

	/**
	 * Sends emails. Acts as a go_send_email() wrapper.
	 *
	 * @param string $type
	 * @param bool $html_mode
	 * @param array $to_list
	 * @param string $subj
	 * @param string $body
	 * @param string $more
	 * @param string $footer
	 * @param string $ip
	 *
	 * @return bool
	 *
	 * @since 9.5.5.1
	 */
	private function send_email( $type, $html_mode, $to_list, $subj, $body, $more, $footer, $ip ) {

		if ( function_exists( 'wp_mail' ) ) {
			return $this->go_send_email( $type, $html_mode, $to_list, $subj, $body, $more, $footer, $ip );
		}

		// Here wp_mail() is not yet defined
        // We postpone sending to the moment when we expect it is defined

		$this->email_args[] = func_get_args();

		add_action( 'plugins_loaded', array( $this, 'launch_send_email' ) );

        // If 'plugins_loaded' is not invoked - e.g. HTTP redirection occurred before
		register_shutdown_function( [ $this, 'launch_send_email' ] );

        // We have to return the result of sending, but at this pont, we have no idea how the postponed sending will end
        // We hope it will be OK.
        return true;
	}

	/**
	 * Sends emails if sending was postponed due to wp_mail() is not defined
	 *
	 * @return void
     *
     * @since 9.5.5.1
	 */
	public function launch_send_email() {

		if ( ! $this->email_args ) {
			return;
		}

		foreach ( $this->email_args as $item ) {
			$this->go_send_email( ...$item );
		}

		$this->email_args = array();
	}

	/**
     * Sends email
     *
	 * @param string $type
	 * @param bool $html_mode
	 * @param array $to_list
	 * @param string $subj
	 * @param string $body
	 * @param string $more
	 * @param string $footer
	 * @param string $ip
	 *
	 * @return bool
	 *
	 * @since 8.9.6.1
	 */
	private function go_send_email( $type, $html_mode, $to_list, $subj, $body, $more, $footer, $ip ) {

        $this->error = false;
		add_action( 'wp_mail_failed', array( $this, 'process_email_errors' ) );

		if ( $html_mode ) {
			add_filter( 'wp_mail_content_type', 'cerber_enable_html' );
			$footer = str_replace( "\n", '<br/>', $footer );
		}

		if ( crb_get_settings( 'email_format' ) < 2 ) {
			$body .= $more;
		}

		$this->enable_smtp();

		$result = false;
		$to = implode( ', ', $to_list );

		if ( $to_list && $subj && $body ) {

			$lang = crb_get_bloginfo( 'language' );

			if ( $type == 'report') {

				$result = true;

				foreach ( $to_list as $email ) {

					$lastus = '';

					if ( ( $user = get_user_by( 'email', $email ) )
					     && $last = crb_get_last_user_login( $user->ID ) ) {

						$last_ip = crb_get_settings( 'email_mask' ) ? crb_mask_ip( $last['ip'] ) : $last['ip'];

						$lastus = sprintf( __( 'Your last sign-in was %s from %s', 'wp-cerber' ), cerber_date( $last['ts'], false ), $last_ip );

						if ( $country = cerber_country_name( $last['cn'] ) ) {
							$lastus .= ' (' . $country . ')';
						}

						if ( $html_mode ) {
							$lastus = '<br/><br/>' . $lastus;
						}
						else {
							$lastus = "\n\n" . $lastus;
						}
					}

					$body = '<html lang="' . $lang . '">' . $body . $lastus . $footer . '</html>';

					if ( ! $this->transmit_email( $email, $subj, $body ) ) {
						$result = false;
					}
				}
			}
			else {

				$result = true;
				$body = $body . $footer;

				if ( $html_mode ) {
					$body = '<html lang="' . $lang . '">' . $body . '</html>';
				}

				foreach ( $to_list as $email ) {
					if ( ! $this->transmit_email( $email, $subj, $body ) ) {
						$result = false;
					}
				}
			}
		}

		$this->disable_smtp();

		remove_filter('wp_mail_content_type', 'cerber_enable_html');

		remove_action( 'wp_mail_failed', array( $this, 'process_email_errors' ) );

		$params = array( 'type' => $type, 'IP' => $ip, 'to' => $to, 'subject' => $subj );

		if ( $result ) {
			do_action( 'cerber_notify_sent', $body, $params );

            if ( ! $this->error ) { // Delete error occurred during a previous sending only
				cerber_delete_set( 'last_email_error' );
			}
		}
		else {
			do_action( 'cerber_notify_fail', $body, $params );
		}

		return $result;
	}

	/**
     * Send out one email message.
     * If SMTP sending has failed, use generic wp_mail() without SMTP.
     *
	 * @param string|string[] $email
	 * @param string $subj
	 * @param string $body
	 *
	 * @return bool
     *
     * @since 9.5.5.1
	 */
	private function transmit_email( $email, $subj, $body ) {

		crb_load_dependencies( 'wp_mail' );  // It's crucial in some configurations

		if ( ( ! $result = wp_mail( $email, $subj, $body ) )
		     && $this->smpt_enabled ) {

            $this->no_smtp = true;

			if ( ! $result = wp_mail( $email, $subj, $body ) ) {
				$this->no_smtp = false;
			}
		}

        return $result;
	}

	/**
	 * @param PHPMailer $pm
	 *
	 * @return void
	 *
	 * @since 8.9.6.3
	 */
	public function set_smtp_credentials( $pm ) {
		if ( ! lab_lab()
		     || $this->no_smtp ) {
			return;
		}

		$config = crb_get_settings();

		$pm->isSMTP();
		$pm->SMTPAuth = true;
		$pm->Timeout = 5;
		$pm->Host = $config['smtp_host'];
		$pm->Port = $config['smtp_port'];
		$pm->Username = $config['smtp_user'];
		$pm->Password = $config['smtp_pwd'];

		// For better deliverability we use "smtp_user"
		$pm->From = ( $config['smtp_from'] ) ?: $config['smtp_user'];

		if ( $config['smtp_from_name'] ) {
			$pm->FromName = $config['smtp_from_name'];
		}

		if ( $config['smtp_encr'] ) {
			$pm->SMTPSecure = $config['smtp_encr'];
		}

		$this->save_mailer( array( &$pm ) );

	}

	/**
     * Set up hooks to configure PHPMailer if SMTP configured
     *
	 * @return void
     *
     * @since 9.5.5.1
	 */
	private function enable_smtp() {
		if ( ! $this->smpt_enabled = crb_get_settings( 'use_smtp' ) ) {
			return;
		}

		add_action( 'phpmailer_init', array( $this, 'set_smtp_credentials' ) );
	}

	/**
     * Remove our hooks
     *
	 * @return void
     *
     * @since 9.5.5.1
	 */
	private function disable_smtp() {
		if ( ! $this->smpt_enabled ) {
			return;
		}

		remove_action( 'phpmailer_init', array( $this, 'set_smtp_credentials' ) );

		// Warn the website admin if the wp_mail() function is redefined somewhere or/and our SMTP settings were not used

		if ( is_admin() && crb_get_query_params( 'cerber_admin_do' ) ) {
			$mailer = $this->mailer;

			if ( ! ( $mailer instanceof PHPMailer\PHPMailer\PHPMailer )
			     || $mailer->Host != crb_get_settings( 'smtp_host' )
			     || $mailer->Port != crb_get_settings( 'smtp_port' )
			     || $mailer->Username != crb_get_settings( 'smtp_user' ) ) {
				cerber_admin_notice( "Warning: The WP Cerber SMTP settings were not used while sending email. They can be altered by another plugin." );
				$alien = true;
			}
			else {
				$alien = false;
			}

			if ( $alien && $mailer->Host ) {
				cerber_admin_notice( 'Warning: The email was sent using the host ' . $mailer->Host . ' and the user ' . $mailer->Username );
			}
		}
	}

	/**
	 * Saves and provides access to last used PHPMailer configuration
	 *
	 * @param PHPMailer $pm
	 *
	 * @return null|PHPMailer
	 *
	 * @since 8.9.6.4
	 */
	private function save_mailer( $pm = null ) {

		if ( isset( $pm[0] ) ) {
			$this->mailer = $pm[0];
		}

		return $this->mailer;
	}

	/**
     * A wp_mail() error handler
     *
	 * @param WP_Error $error
	 *
	 * @return void
     *
     * @since 9.5.5.1
	 */
	public function process_email_errors( $error ) {

        $this->error = true;
        $data = $error->get_error_data();

		if ( is_admin()
		     && crb_get_query_params( 'cerber_admin_do' ) ) {
			cerber_admin_notice( strip_tags( $error->get_error_message() ) . ' (' . ( $data['phpmailer_exception_code'] ?? '' ) . ')' );
		}
		else {

			$save = array(
				time(),
				cerber_get_remote_ip(),
				strip_tags( $error->get_error_message() ),
				$data['phpmailer_exception_code'] ?? '',
				isset( $this->mailer->Host ) ? $this->mailer->Host : '',
				isset( $this->mailer->Username ) ? $this->mailer->Username : '',
                $data['to'] ?? '',
				$data['subject'] ?? '',
			);

			cerber_update_set( 'last_email_error', $save );
		}
	}

	/**
	 * Check sending limits and disable channels if it's needed
	 *
	 * @param array $channels
	 *
	 * @return array
	 *
	 * @since 8.9.6.1
	 *
	 */
	private function check_limits( $channels ) {
		static $ref = array( 'email' => 'emailrate', 'pushbullet' => 'pbrate' );

		if ( ! lab_lab() ) {
			$ref ['pushbullet'] = 'emailrate';
		}

		$limits = array_filter( array_intersect_key( crb_get_settings(), array_flip( $ref ) ) );

		if ( ! $limits ) {
			return $channels;
		}

		$sent = cerber_get_set( '_cerber_last_send' );

		if ( empty( $sent ) ) {
			return $channels;
		}

		foreach ( $ref as $channel_id => $key ) {
			$rate = absint( $limits[ $key ] ?? 0 );
			if ( $rate && ( $sent[ $channel_id ] ?? 0 ) > ( time() - 3600 / $rate ) ) {
				$channels[ $channel_id ] = 0; // Do not send
			}
		}

		return $channels;
	}
}

/**
 *
 * Send message via specified channels
 *
 * @param string $type Notification type
 * @param array $msg_parts Additional message
 * @param array $channels Channels to send the message
 * @param bool $ignore Ignore global limits
 * @param array $args Additional parameters
 *
 * @return array|false The list of recipients on success
 *
 * @since 8.9.6.1
 */
function cerber_send_message( $type, $msg_parts = array(), $channels = array(), $ignore = false, $args = array() ) {
    static $messenger;

	if ( ! $messenger ) {
		$messenger = new CRB_Messaging();
	}

	return $messenger->send_message( $type, $msg_parts, $channels, $ignore, $args );
}

/**
 * @param string $ip_address
 *
 * @return array|bool
 *
 * @since 8.9.6.1
 */
function crb_send_lockout( $ip_address = '' ) {
	$em = crb_get_settings( 'notify' );
	$pb = crb_get_settings( 'pbnotify-enabled' );

	if ( ! $em && ! $pb ) {
		return false;
	}

	$count = cerber_blocked_num();
	$send_email = ( $em && ( $count > crb_get_settings( 'above' ) ) );

	$channels = array(
		'email' => $send_email,
	);

	if ( lab_lab() ) {
		$channels['pushbullet'] = ( $pb && ( $count > crb_get_settings( 'pbnotify' ) ) );
	}
	else {
		$channels['pushbullet'] = $send_email;
	}

	if ( array_filter( $channels ) ) {
		return cerber_send_message( 'lockout', array( 'ip' => $ip_address ), $channels );
	}

    return false;
}

function cerber_enable_html() {
	return 'text/html';
}

/**
 * Generates a performance report
 *
 * @param array $args
 *
 * @return array
 */
function cerber_generate_email_report( $args ) {
	global $wpdb;

	$period = $args['report_id'] ?? 'one_week';

	if ( $period == 'one_week' ) {
		if ( crb_get_settings( 'wreports_7' ) ) {
			$end = strtotime( 'midnight' ) - 1;
			$begin = $end - 7 * 24 * 3600 + 1;
		}
		else {
			$end = strtotime( 'last sunday' ) + 24 * 3600 - 1;
			$begin = $end - 7 * 24 * 3600 + 1;
		}
		$title = __( 'Weekly Report', 'wp-cerber' );
	}
    elseif ( $period == 'one_month' ) {
		if ( crb_get_settings( 'monthly_30' ) ) {
			$end = strtotime( 'midnight' ) - 1;
			$begin = $end - 30 * 24 * 3600 + 1;
		}
		else {
			$date = getdate( strtotime( 'first day of previous month' ) );
			$begin = mktime( 0, 0, 0, $date['mon'], $date['mday'], $date['year'] );
			$date = getdate( strtotime( 'last day of previous month' ) );
			$end = mktime( 23, 59, 59, $date['mon'], $date['mday'], $date['year'] );
		}
		$title = __( 'Monthly Report', 'wp-cerber' );
	}
	else {
		return array( 'Unsupported period of time', 'Unsupported period of time' );
	}

	$report = '';

    $kpi_rows = array();
	$base_url = cerber_admin_link( 'activity', array(), false, false );
	$css_table = 'width: 95%; max-width: 1000px; margin:0 auto; margin-bottom: 10px; background-color: #f5f5f5; text-align: center; font-family: Arial, Helvetica, sans-serif;';
	$css_td = 'padding: 0.5em 0.5em 0.5em 1em; text-align: left;';
	$css_border = 'border-bottom: solid 2px #f9f9f9;';

	$site_name = ( is_multisite() ) ? get_site_option( 'site_name' ) : get_option( 'blogname' );

    $df = get_option( 'date_format' );

	$report .= '<div style="' . $css_table . '"><div style="margin:0 auto; text-align: center;"><p style="font-size: 130%; padding-top: 0.5em;">' . $site_name . '</p><p>' . $title . '</p><p style="padding-bottom: 1em;">' . date( $df, $begin ) . ' - ' . date( $df, $end ) . '</p></div></div>';

	$activites = $wpdb->get_results( 'SELECT activity, COUNT(activity) cnt FROM ' . CERBER_LOG_TABLE . ' WHERE stamp BETWEEN ' . $begin . ' AND ' . $end . ' GROUP by activity ORDER BY cnt DESC' );

	if ( $activites ) {

		// KPI
		$kpi_list = cerber_calculate_kpi( $begin, $end );

		foreach ( $kpi_list as $kpi ) {
			$kpi_rows[] = '<td style="' . $css_td . ' text-align: right;">' . $kpi[1] . '</td><td style="padding: 0.5em; text-align: left;">' . $kpi[0] . '</td>';
		}

		$report .= '<div style="text-align: center; ' . $css_table . '"><table style="font-size: 130%; margin:0 auto;"><tr>' . implode( '</tr><tr>', $kpi_rows ) . '</tr></table></div>';

		// Activities breakdown

		$rows = array();
		$rows[] = '<td style="' . $css_td . $css_border . '" colspan="2"><p style="line-height: 1.5em; font-weight: bold;">' . __( 'Activity details', 'wp-cerber' ) . '</p></td>';

		$lables = cerber_get_labels();

		foreach ( $activites as $a ) {
			$rows[] = '<td style="' . $css_border . $css_td . '">' . $lables[ $a->activity ] . '</td><td style="padding: 0.5em; text-align: center; width:10%;' . $css_border . '"><a href="' . $base_url . '&filter_activity=' . $a->activity . '">' . $a->cnt . '</a></td>';
		}

		$report .= '<table style="border-collapse: collapse; ' . $css_table . '"><tr>' . implode( '</tr><tr>', $rows ) . '</tr></table>';

		// Attempts to log in with non-existing usernames
		$attempts = $wpdb->get_results( 'SELECT user_login, COUNT(user_login) cnt FROM ' . CERBER_LOG_TABLE . ' WHERE activity = 51 AND (stamp BETWEEN ' . $begin . ' AND ' . $end . ') GROUP by user_login ORDER BY cnt DESC LIMIT 10' );

		if ( $attempts ) {
			$rows = array();
			$rows[] = '<td style="' . $css_td . $css_border . '" colspan="2"><p style="line-height: 1.5em; font-weight: bold;">' . __( 'Attempts to log in with non-existing usernames', 'wp-cerber' ) . '</p></td>';
			foreach ( $attempts as $a ) {
				$rows[] = '<td style="' . $css_border . $css_td . '">' . htmlspecialchars( $a->user_login ) . '</td><td style="padding: 0.5em; text-align: center; width:10%;' . $css_border . '"><a href="' . $base_url . '&filter_login=' . $a->user_login . '">' . $a->cnt . '</a></td>';
			}
			$report .= '<table style="border-collapse: collapse; ' . $css_table . '"><tr>' . implode( '</tr><tr>', $rows ) . '</tr></table>';
		}
	}
    else {
	    $report .= '<div style="text-align: center; ' . $css_table . '"><p style="padding: 1em;">No data logged within the specified date range to generate the report</p></div>';
    }

	$report = '<div style="width:100%; padding: 1em; text-align: center; background-color: #f9f9f9;">' . $report . '</div>';

	return array( $title, $report );
}


// Maintenance routines ----------------------------------------------------------------

add_filter( 'cron_schedules', function ( $schedules ) {
	$schedules['crb_five'] = array(
		'interval' => 300,
		'display'  => 'Every 5 Minutes',
	);

	return $schedules;
} );

add_action( 'cerber_hourly_1', 'cerber_do_hourly_1' );
function cerber_do_hourly_1( $force = false ) {

	$t = 'cerber_hourly_1';
	$start = time();
	if ( ( $last = get_site_transient( $t ) ) && date( 'G', $last[0] ) == date( 'G' ) ) {
		return;
	}

	set_site_transient( $t, array( $start ), 2 * 3600 );

	if ( is_multisite() ) {
		if ( ! $force && get_site_transient( 'cerber_multisite' ) ) {
			return;
		}
		set_site_transient( 'cerber_multisite', 'executed', 3600 );
	}

	require_once( __DIR__ . '/cerber-toolbox.php' );

    // Process obsolete things
	crb_log_maintainer();

	// Upgrading data to new formats
	crb_once_upgrade_log();
	crb_once_upgrade_cbla();

	// Keep the size of the log file small
	cerber_truncate_log();

	crb_plugin_update_notifier();

    // Delete expired alerts

	if ( $alerts = get_site_option( CRB_ALERTZ ) ) {
		$delete = array();

		foreach ( $alerts as $hash => $alert ) {
			if ( crb_is_alert_expired( $alert ) ) {
				$delete[] = $hash;
			}
		}

		if ( $delete ) {
			foreach ( $delete as $hash ) {
				unset( $alerts[ $hash ] );
			}
			if ( ! update_site_option( CRB_ALERTZ, $alerts ) ) {
				cerber_error_log( 'Unable to update the list of alerts', 'ALERTS' );
			}
		}
	}

	if ( cerber_get_mode() != crb_get_settings( 'boot-mode' ) ) {
		cerber_set_boot_mode();
	}

	set_site_transient( $t, array( $start, time() ), 2 * 3600 );
}

add_action( 'cerber_hourly_2', 'cerber_do_hourly_2');
function cerber_do_hourly_2() {
	$t = 'cerber_hourly_2';
	$start = time();
	if ( ( $last = get_site_transient( $t ) ) && date( 'G', $last[0] ) == date( 'G' ) ) {
		return;
	}

	set_site_transient( $t, array( $start ), 2 * 3600 );

	// Ensure the integrity of the WP Cerber database tables

	cerber_watchdog( true );

    // Reporting

	$now = time() + get_option( 'gmt_offset' ) * 3600;

	if ( crb_get_settings( 'enable-report' )
	     && date( 'w', $now ) == crb_get_settings( 'wreports-day' )
	     && date( 'G', $now ) == crb_get_settings( 'wreports-time' ) ) {

        $result = cerber_send_message( 'report' );

		if ( ! $sent = get_site_option( '_cerber_report' ) ) {
			$sent = array();
		}
		$sent['period_week'] = array( time(), $result );
		update_site_option( '_cerber_report', $sent );
	}

	if ( crb_get_settings( 'monthly_report' )
	     && $on = crb_get_settings( 'monthly_on' ) ) {

		$last_day = date( 'j', strtotime( 'last day of this month' ) );
		$send_day = ( $on['day'] > $last_day ) ? $last_day : $on['day'];

        if ( date( 'j', $now ) == $send_day
		     && date( 'G', $now ) == $on['hours'] ) {

			$result = cerber_send_message( 'report', array(), array(), false, array( 'report_id' => 'one_month' ) );

	        if ( ! $sent = get_site_option( '_cerber_report' ) ) {
		        $sent = array();
	        }
	        $sent['one_month'] = array( time(), $result );
	        update_site_option( '_cerber_report', $sent );
		}
	}

    // --------------------------------------

	cerber_delete_expired_set();

	if ( crb_get_settings( 'cerberlab' ) || lab_lab() ) {
		lab_check_nodes( true, true );
	}

	cerber_push_lab();

	cerber_cloud_sync();

	cerber_get_the_folder(); // Simply keep folder locked

	cerber_db_query( 'DELETE FROM ' . CERBER_QMEM_TABLE . ' WHERE stamp < ' . ( time() - 30 * 60 ) );

	set_site_transient( $t, array( $start, time() ), 2 * 3600 );
}

add_action( 'cerber_daily', 'cerber_daily_run' );
function cerber_daily_run() {
	$t = 'cerber_daily_1';
	$start = time();
	if ( ( $last = get_site_transient( $t ) ) && date( 'j', $last[0] ) == date( 'j' ) ) {
		return;
	}

	set_site_transient( $t, array( $start ), 48 * 3600 );

	cerber_do_hourly_1( true );

	$time = time();

	lab_validate_lic();

	cerber_db_query( 'DELETE FROM ' . CERBER_LAB_NET_TABLE . ' WHERE expires < ' . $time );
	cerber_db_query( 'DELETE FROM ' . CERBER_LAB_TABLE . ' WHERE stamp < ' . ( $time - 3600 ) ); // workaround for weird/misconfigured hostings

	// Delete sets if log entries for a user were deleted completely
    // @since 8.6.3.4
	$sql = 'SELECT the_id FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' sets LEFT JOIN ' . CERBER_LOG_TABLE . ' log
		    ON log.user_id = sets.the_id
		    WHERE  sets.the_key = "user_deleted" AND log.user_id IS NULL';
	$user_ids1 = cerber_db_get_col( $sql );
	$sql = 'SELECT the_id FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' sets LEFT JOIN ' . CERBER_TRAF_TABLE . ' log
		    ON log.user_id = sets.the_id
		    WHERE  sets.the_key = "user_deleted" AND log.user_id IS NULL';
	$user_ids2 = cerber_db_get_col( $sql );
	if ( $delete = array_intersect( $user_ids1, $user_ids2 ) ) {
		cerber_db_query( 'DELETE FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE the_key = "user_deleted" AND the_id IN (' . implode( ',', $delete ) . ')' );
	}

	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_LOG_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_QMEM_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_TRAF_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_ACL_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_BLOCKS_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_LAB_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_LAB_IP_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . CERBER_LAB_NET_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . cerber_get_db_prefix() . CERBER_SCAN_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . cerber_get_db_prefix() . CERBER_SETS_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . cerber_get_db_prefix() . CERBER_MS_LIST_TABLE );
	cerber_db_query( 'OPTIMIZE TABLE ' . cerber_get_db_prefix() . CERBER_USS_TABLE );

	cerber_check_new_version( false );

	if ( nexus_is_main() ) {
		if ( ( $ups = get_site_transient( 'update_plugins' ) ) && ! empty( $ups->response ) ) {
			nexus_update_updates( obj_to_arr_deep( $ups->response ) );
		}

		nexus_delete_unused( 'nexus_servers', 'server_id' );
		nexus_delete_unused( 'nexus_countries', 'server_country' );
	}

	CRB_Cache::reset();

	// Cleanup the quarantine folder
	if ( $dirs = glob( cerber_get_the_folder() . 'quarantine' . '/*', GLOB_ONLYDIR ) ) {
		$sync = false;
		foreach ( $dirs as $dir ) {
			$d = basename( $dir );
			if ( is_numeric( $d ) ) {
				if ( $d < ( time() - DAY_IN_SECONDS * crb_get_settings( 'scan_qcleanup' ) ) ) {
					$fs = cerber_init_wp_filesystem();
					if ( ! crb_is_wp_error( $fs ) ) {
						$fs->delete( $dir, true );
						$sync = true;
					}
				}
			}
		}
		if ( $sync ) {
			_crb_qr_total_sync();
		}
	}
    else {
	    _crb_qr_total_sync( 0 );
    }

	cerber_upgrade_deferred();

	// TODO: implement holding previous values for a while
	// cerber_antibot_gene();

	set_site_transient( $t, array( $start, time() ), 48 * 3600 );
}

/**
 * Master CRON task scheduler
 *
 */
add_action( 'cerber_bg_launcher', function () {

    $next_hour = intval( floor( ( time() + 3600 ) / 3600 ) * 3600 );

	if ( ! wp_next_scheduled( 'cerber_hourly_1' ) ) {
		wp_schedule_event( $next_hour, 'hourly', 'cerber_hourly_1' );
	}

	if ( ! wp_next_scheduled( 'cerber_hourly_2' ) ) {
		wp_schedule_event( $next_hour + 600 , 'hourly', 'cerber_hourly_2' );
	}

	if ( ! wp_next_scheduled( 'cerber_daily' ) ) {
		if ( ! $when = strtotime( 'midnight' ) + 24 * 3600 ) {
			$when = $next_hour;
		}
		wp_schedule_event( $when + 2 * 3600 + 1200, 'daily', 'cerber_daily' );
	}

	define( 'CRB_DOING_BG_TASK', 1 );

	@ignore_user_abort( true );
	crb_raise_limits();

	if ( nexus_is_main() ) {
		nexus_schedule_refresh();
	}

	cerber_bg_task_launcher();

    // Run a postponed tasks

	require_once( __DIR__ . '/cerber-toolbox.php' );

	if ( cerber_get_set( 'event_wp_found_updates', null, false ) ) {
		crb_plugin_update_notifier( true );
		cerber_delete_set( 'event_wp_found_updates' );
	}

} );

function cerber_bg_task_launcher( $filter = null ) {
	$ret = array();

	if ( ! $task_list = cerber_bg_task_get_all() ) {
		return $ret;
	}

	if ( $filter ) {
		//$exec_it = array_intersect_key( $task_list, array_flip( crb_array_get( $_REQUEST, 'tasks', array() ) ) );
		$exec_it = array_intersect_key( $task_list, $filter );
	}
	else {
		$exec_it = $task_list;
	}

	if ( empty( $exec_it ) ) {
		return $ret;
	}

	$safe_func = array(
		'nexus_send',
		'nexus_do_upgrade',
		'_crb_ds_background',
		'nexus_refresh_slave_srv',
		'_crb_qr_total_sync',
		'crb_sessions_sync_all',
		'cerber_upgrade_deferred',
		'cerber_daily_run',
		'cerber_do_hourly_2'
	);

	foreach ( $exec_it as $task_id => $task ) {

		$func = crb_array_get( $task, 'func' );
		if ( ! in_array( $func, $safe_func ) ) {
			cerber_error_log( 'Function ' . $func . ' is not in the safe list', 'BG TASK' );
			cerber_bg_task_delete( $task_id );
			continue;
		}
		if ( ! is_callable( $func ) ) {
			cerber_error_log( 'Function ' . $func . ' is not available (not defined)', 'BG TASK' );
			cerber_bg_task_delete( $task_id );
			continue;
		}
		if ( ! isset( $task['exec_until'] ) ) {
			cerber_bg_task_delete( $task_id );
		}

		// Ready to lunch the task

		$args = crb_array_get( $task, 'args', array() );

		nexus_diag_log( 'Launching bg task: ' . $func );

		ob_start();
		$result = call_user_func_array( $func, $args );
		$echo   = ob_get_clean();

		if ( isset( $task['exec_until'] ) ) {
			if ( $task['exec_until'] === $result ) {
				cerber_bg_task_delete( $task_id );
			}
		}

		if ( empty( $task['return'] ) ) {
			$echo   = ( $echo ) ? ' there was an output ' . strlen( $echo ) . ' bytes length' : 'no output';
			$result = 1;
		}

		$ret[ $task_id ] = array( $result, crb_array_get( $task, 'run_js' ), $echo );
	}

	return $ret;
}

function cerber_bg_task_get_all() {
	$list = cerber_get_set( '_background_tasks' );

	if ( ! $list ) {
		$list = array();
	}

	return $list;
}

/**
 * @param callable $func Function must be in the safe list in cerber_bg_task_launcher().
 * @param array $config
 * @param bool $priority
 * @param int $limit
 *
 * @return bool
 *
 * @since 8.6.4
 *
 */
function cerber_bg_task_add( $func, $config = array(), $priority = false, $limit = 60 ) {

	if ( ! is_callable( $func ) ) {
		cerber_error_log( 'Function ' . $func . ' is not callable', 'BG TASK' );

		return false;
	}

	$list = cerber_bg_task_get_all();

	$config['func'] = $func;

	$id = sha1( serialize( $config ) );

	if ( isset( $list[ $id ] ) ) {
		return false;
	}

	if ( $priority ) {
		$list = array( $id => $config ) + $list;
	}
	else {
		$list[ $id ] = $config;
	}

	return cerber_update_set( '_background_tasks', $list );
}

function cerber_bg_task_delete( $task_id ) {

	if ( ! $list = cerber_bg_task_get_all() ) {
		return false;
	}

	if ( ! isset( $list[ $task_id ] ) ) {
		return false;
	}

	unset( $list[ $task_id ] );

	return cerber_update_set( '_background_tasks', $list );
}

/**
 * Log activity
 *
 * @param int $activity Activity ID
 * @param string $login Login used or any additional information
 * @param int $user_id  User ID
 * @param int $status
 * @param null $ip IP Address
 *
 * @return bool
 * @since 3.0
 */
function cerber_log( $activity, $login = '', $user_id = 0, $status = 0, $ip = null ) {
	global $user_ID;
	static $logged = array();

	$wp_cerber = get_wp_cerber();

	$activity = absint( $activity );

	if ( empty( $user_id ) ) {
		$user_id = ( $user_ID ) ?: 0;
	}

	$user_id = absint( $user_id );

	$key = $activity . '-' . $user_id;

	if ( ( isset( $logged[ $key ] )
	       || isset( CRB_Globals::$do_not_log[ $activity ] ) )
	     && ! defined( 'CRB_ALLOW_MULTIPLE' ) ) {
		return false;
	}

	$logged[ $key ] = true;

	CRB_Globals::$logged[ $activity ] = $activity;

	//$wp_cerber->setProcessed();

	if ( empty( $ip ) ) {
		$ip = cerber_get_remote_ip();
	}
	elseif ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return false;
	}

	if ( cerber_is_ipv4( $ip ) ) {
		$ip_long = ip2long( $ip );
	}
	else {
		$ip_long = 1;
	}

	$stamp = microtime( true );

	$pos  = strpos( $_SERVER['REQUEST_URI'], '?' );
	$path = ( $pos ) ? substr( $_SERVER['REQUEST_URI'], 0, $pos ) : $_SERVER['REQUEST_URI'];
	$url  = strip_tags( $_SERVER['HTTP_HOST'] . $path );

	if ( ! $status ) {
		if ( $activity != 10 && $activity != 11 ) {
			$status = cerber_get_status( $ip, $activity );
		}
        elseif ( CRB_Globals::$blocked ) {
			$status = CRB_Globals::$blocked;
		}
	}

	$ac_bot = absint( CRB_Globals::$bot_status );
	$ac_by_user = absint( CRB_Globals::$user_id );

	$status = absint( $status );

	$details = $status . '|0|0|0|' . $url; // Note: @since 8.9.4 $status is stored in a separate "ac_status" row

	$country = lab_get_country( $ip );

	$login   = cerber_real_escape( $login );
	$details = cerber_real_escape( $details );
	$ret     = cerber_db_query( 'INSERT INTO ' . CERBER_LOG_TABLE . ' (ip, ip_long, user_login, user_id, stamp, activity, session_id, country, details, ac_status, ac_bot, ac_by_user) 
	    VALUES ("' . $ip . '",' . $ip_long . ',"' . $login . '",' . $user_id . ',"' . $stamp . '",' . $activity . ',"' . $wp_cerber->getRequestID() . '","' . $country . '","' . $details . '", ' . $status . ', ' . $ac_bot . ',' . $ac_by_user . ')' );

	if ( ! $ret ) {
		cerber_watchdog();
		$ret = false;
	}
    else {
	    $ret = true;
    }

	// Alerts for admin ---------------------------------------------------

	$alert_list = cerber_get_site_option( CRB_ALERTZ );

	if ( ! empty( $alert_list ) ) {

		$update_alerts = false;

		foreach ( $alert_list as $hash => $alert ) {

			$updated = false;

			// Check if all alert parameters match

			if ( ! empty( $alert[10] )
			     && $alert[10] != $status ) {
				continue;
			}

			if ( ! empty( $alert[1] )
			     && $alert[1] != $user_id
			     && $alert[1] != $ac_by_user ) {
				continue;
			}

			if ( ! empty( $alert[13] )
			     && ( $expires = absint( $alert[13] ) )
			     && $expires < time() ) {
				continue;
			}

			if ( ! empty( $alert[11] ) ) {
				if ( $alert[11] <= $alert[12] ) {
					continue;
				}

				$alert[12] ++;
				$updated = true;
			}

			if ( ! empty( $alert[0] ) ) {
				if ( ! in_array( $activity, $alert[0] ) ) {
					continue;
				}
			}

			if ( ! empty( $alert[3] )
                 && ( $ip_long < $alert[2] || $alert[3] < $ip_long ) ) {
				continue;
			}

			if ( ! empty( $alert[4] )
                 && $alert[4] != $ip ) {
				continue;
			}

			if ( ! empty( $alert[5] )
                 && $alert[5] != $login ) {
				continue;
			}

			if ( ! empty( $alert[9] )
                 && false === strpos( $url, $alert[9] ) ) {
				continue;
			}

			if ( ! empty( $alert[6] ) ) {
				$none = true;
				if ( false !== strpos( $ip, $alert[6] ) ) {
					$none = false;
				}
                elseif ( false !== mb_stripos( $login, $alert[6] ) ) {
					$none = false;
				}
                elseif ( $user_id ) {
					if ( ! $user = wp_get_current_user() ) {
						$user = get_userdata( $user_id );
					}
					if ( false !== mb_stripos( $user->user_firstname, $alert[6] )
					     || false !== mb_stripos( $user->user_lastname, $alert[6] )
					     || false !== mb_stripos( $user->nickname, $alert[6] ) ) {
						$none = false;
					}
				}
				/*elseif ( $user_id && in_array( $user_id, $sub[8] ) ) {
					$none = false;
				}*/

				// No alert parameters match, continue to the next alert

                if ( $none ) {
					continue;
				}
			}

			// Alert parameters match, prepare and send an alert email

			$ac_lbl = crb_get_label( $activity, $user_id, $ac_by_user, false );

            $status_lbl = '';

            if ( $status ) {
				$status_list = cerber_get_labels( 'status' ) + cerber_get_reason();
				if ( $status_lbl = $status_list[ $status ] ?? '' ) {
					$status_lbl = ' (' . $status_lbl . ')';
				}
			}

			$msg = array();

			$msg[] = __( 'Activity', 'wp-cerber' ) . ': ' . $ac_lbl . $status_lbl;
			$msg_masked = $msg;

			$coname = $country ? ' (' . cerber_country_name( $country ) . ')' : '';

			$msg[] = __( 'IP address', 'wp-cerber' ) . ': ' . $ip . $coname;
			$msg_masked[] = __( 'IP address', 'wp-cerber' ) . ': ' . crb_mask_ip( $ip ) . $coname;

			if ( $user_id && function_exists( 'get_userdata' ) ) {
				$u = get_userdata( $user_id );
				$msg[] = __( 'User', 'wp-cerber' ) . ': ' . $u->display_name;
				$msg_masked[] = __( 'User', 'wp-cerber' ) . ': ' . $u->display_name;
			}

			if ( $login ) {
				$msg[] = __( 'Username used', 'wp-cerber' ) . ': ' . $login;
				$msg_masked[] = __( 'Username used', 'wp-cerber' ) . ': ' . crb_mask_login( $login );
			}

			if ( ! empty( $alert['6'] ) ) {
				$msg[] = __( 'Search string', 'wp-cerber' ) . ': ' . $alert['6'];
				$msg_masked[] = __( 'Search string', 'wp-cerber' ) . ': ' . $alert['6'];
			}

			// Make a link to the Activity log page

			$args = array_intersect_key( crb_get_alert_params(), CRB_ACT_PARAMS );

			$base_url = cerber_admin_link( 'activity', array(), false, false );
			$i = 0;
			$link_params = '';

			foreach ( $args as $arg => $val ) {
				if ( is_array( $alert[ $i ] ) ) {
					foreach ( $alert[ $i ] as $item ) {
						$link_params .= '&' . $arg . '[]=' . $item;
					}
				}
				else {
					$link_params .= '&' . $arg . '=' . $alert[ $i ];
				}
				$i ++;
			}

			$more = __( 'View activity in the Dashboard', 'wp-cerber' ) . ': ' . $base_url . $link_params;
			$more .= "\n\n" . __( 'To delete the alert, click here', 'wp-cerber' ) . ': ' . $base_url . '&unsubscribeme=' . $hash;

			$ignore = $alert[14] ?? false;
			$use_email = ! empty( $alert[15] ) || ! empty( $alert[17] );
			$extra = array();

            if ( ! empty( $alert[17] ) ) {
	            $extra = array( 'user_list' => array( $alert[17] ) );
			}

			$channels = array( 'email' => $use_email, 'pushbullet' => ! empty( $alert[16] ) );

			// Crucial for old alerts (no channels at all)

			if ( ! array_filter( $channels ) ) {
				$channels = array(); // All channels are in use
			}

			$sent = cerber_send_message( 'send_alert', array(
				'subj'        => $ac_lbl,
				'text'        => $msg,
				'text_masked' => $msg_masked,
				'more'        => $more,
				'ip'          => $ip
			), $channels, $ignore, $extra );

			if ( $sent && $updated ) {
				$update_alerts = true;
				$alert_list[ $hash ] = $alert;
			}

			break; // Just one notification letter per an HTTP request is allowed
		}

		if ( $update_alerts ) {
			if ( ! update_site_option( CRB_ALERTZ, $alert_list ) ) {
				cerber_error_log( 'Unable to update the list of alerts', 'ALERTS' );
			}
		}

	}

    // Lab --------------------------------------------------------------------

	if ( in_array( $activity, array( 16, 17, 40, CRB_EV_PUR, CRB_EV_LDN, 55, 56, 71 ) ) ) {
		lab_save_push( $ip, $activity );
	}

	return $ret;
}

/**
 * Get records from the log
 *
 * @param array $activity
 * @param array $user
 * @param array $order
 * @param string $limit
 *
 * @return object[]|null
 */
function cerber_get_log( $activity = array(), $user = array(), $order = array(), $limit = '' ) {

	$where = array();

	if ( $activity ) {
		$activity = array_map( 'absint', $activity );
		$where[]  = 'activity IN (' . implode( ', ', $activity ) . ')';
	}

	if ( ! empty( $user['email'] ) ) {
		if ( ! $user = get_user_by( 'email', $user['email'] ) ) {
			return null;
		}
		$where[] = 'user_id = ' . absint( $user->ID );
	}
    elseif ( ! empty( $user['id'] ) ) {
		$where[] = 'user_id = ' . absint( $user['id'] );
	}

	$where_sql = '';
	if ( $where ) {
		$where_sql = ' WHERE ' . implode( ' AND ', $where );
	}

	$order_sql = '';
	if ( $order ) {
		if ( ! empty( $order['DESC'] ) ) {
			$order_sql = ' ORDER BY ' . preg_replace( '/[^\w]/', '', $order['DESC'] ) . ' DESC ';
		}
        elseif ( ! empty( $order['ASC'] ) ) {
			$order_sql = ' ORDER BY ' . preg_replace( '/[^\w]/', '', $order['ASC'] ) . ' ASC ';
		}
	}

	$limit_sql = '';
	if ( $limit ) {
		$limit_sql = ' LIMIT ' . preg_replace( '/[^0-9.,]/', '', $limit );
	}

	return cerber_db_get_results( 'SELECT * FROM ' . CERBER_LOG_TABLE . ' ' . $where_sql . $order_sql . $limit_sql, MYSQL_FETCH_OBJECT );
	//return $wpdb->get_results( 'SELECT * FROM ' . CERBER_LOG_TABLE . ' ' . $where_sql . $order_sql . $limit_sql );

}

/**
 * Return the last login record for a given user
 *
 * @param $user_id int|null
 * @param $user_email string
 *
 * @return false|object
 */
function cerber_get_last_login( $user_id, $user_email = '' ) {
	if ( $user_id ) {
		$u = array( 'id' => $user_id );
	}
	elseif ( $user_email ) {
		$u = array( 'email' => $user_email );
	}
    else {
	    return false;
    }

	if ( $recs = cerber_get_log( array( CRB_EV_LIN ), $u, array( 'DESC' => 'stamp' ), 1 ) ) {
		return $recs[0];
	}

	return false;
}

/**
 * Finds the last failed/denied attempt to log in. Uses user login and email.
 * Returns an activity log entry.
 *
 * @param string $login
 * @param string $email
 * @param bool $denied
 *
 * @return false|object
 *
 * @since 9.0.2
 */
function crb_get_last_failed( $login, $email, $denied = false ) {
	$act = ( $denied ) ? 53 : CRB_EV_LFL;

	return cerber_db_get_row( 'SELECT * FROM ' . CERBER_LOG_TABLE . ' WHERE ( user_login = "' . $login . '" OR user_login = "' . $email . '" ) AND activity = ' . $act . ' ORDER BY stamp DESC LIMIT 1', MYSQL_FETCH_OBJECT );
}

/**
 * @param array $activity
 * @param int $begin
 * @param int $end
 * @param string $column
 * @param false $distinct
 *
 * @return int|mixed
 */
function cerber_count_log( $activity, $begin, $end, $column = 'ip', $distinct = false ) {

	$begin = ( (int) floor( (int) $begin / 90 ) ) * 90; // every 90 seconds - let SQL query be cached
	$end = ( (int) floor( (int) $end / 90 ) ) * 90; // every 90 seconds - let SQL query be cached

	$column = ( ( $distinct ) ? ' DISTINCT ' : '' ) . $column;
	$result = crb_q_cache_get( 'SELECT COUNT( ' . $column . ' ) FROM ' . CERBER_LOG_TABLE . ' WHERE activity IN (' . implode( ',', $activity ) . ') AND (stamp BETWEEN ' . $begin . ' AND ' . $end . ')', CERBER_LOG_TABLE );
	if ( empty( $result ) ) {
		return 0;
	}

	return $result[0][0];
}

/**
 * Load must have settings before the rest of the stuff during the plugin activation
 *
 * @since 7.8.3
 *
 */
function cerber_on_plugin_activation() {

	// The only way to get it done earlier

	if ( cerber_get_get( 'action' ) === 'activate'
	     && cerber_get_get( 'plugin' ) === CERBER_PLUGIN_ID ) {
		// The plugin is activated in wp-admin
		if ( ! crb_get_settings( '', true, false ) ) {

			if ( ! defined( 'CRB_JUST_MARRIED' ) ) {
				define( 'CRB_JUST_MARRIED', 1 );
			}

            cerber_load_defaults();
		}
	}

	// A backup way

	add_action( 'activated_plugin', function ( $plugin ) {

        if ( $plugin !== CERBER_PLUGIN_ID ) {
			return;
		}

		if ( ! crb_get_settings( '', true, false ) ) {

			if ( ! defined( 'CRB_JUST_MARRIED' ) ) {
				define( 'CRB_JUST_MARRIED', 1 );
			}

            cerber_load_defaults();
		}
	} );
}

/**
 * Post plugin activation stuff
 *
 */
register_activation_hook( cerber_plugin_file(), function () {

	load_plugin_textdomain( 'wp-cerber', false, 'wp-cerber/languages' );

	if ( version_compare( CERBER_REQ_PHP, phpversion(), '>' ) ) {
		cerber_stop_activating( '<h3>' . sprintf( __( 'WP Cerber requires PHP %s or higher. You are running %s.', 'wp-cerber' ), CERBER_REQ_PHP, phpversion() ) . '</h3>' );
	}

	if ( ! crb_wp_version_compare( CERBER_REQ_WP ) ) {
		cerber_stop_activating( '<h3>' . sprintf( __( 'WP Cerber requires WordPress %s or higher. You are running %s.', 'wp-cerber' ), CERBER_REQ_WP, cerber_get_wp_version() ) . '</h3>' );
	}

	$db_errors = cerber_create_db();

	if ( $db_errors ) {
		$e = '';
		foreach ( $db_errors as $db_error ) {
			$e .= '<p>' . implode( '</p><p>', $db_error ) . '</p>';
		}
		cerber_stop_activating( '<h3>' . __( "Can't activate WP Cerber due to a database error.", 'wp-cerber' ) . '</h3>'.$e);
	}

	lab_get_key( true );

	cerber_upgrade_all( true );

	cerber_cookie_one();

	cerber_load_admin_code();

	cerber_bg_task_add( 'crb_sessions_sync_all' );

	$whited = '';

	if ( is_user_logged_in() ) {  // Not for remote plugin installation/activation

		$ip = cerber_get_remote_ip();

		if ( cerber_get_block( $ip ) ) {
			if ( ! cerber_block_delete( $ip ) ) {
				$sub = cerber_get_subnet_ipv4( $ip );
				cerber_block_delete( $sub );
			}
		}

		if ( ! crb_get_settings( 'no_white_my_ip' ) ) {
			cerber_add_white( $ip, 'My IP address (' . cerber_date( time(), false ) . ')' ); // Protection for non-experienced users
			$whited = ' <p>' . sprintf( __( 'Your IP address %s has been added to the White IP Access List', 'wp-cerber' ), cerber_get_remote_ip() );
		}

		cerber_disable_citadel();
	}

	cerber_htaccess_sync( 'main' );
	cerber_htaccess_sync( 'media' );

	cerber_set_boot_mode();

	crb_x_update_add_on_list();

	$msg =
		'<h2>' . __( 'WP Cerber is now active and has started protecting your site', 'wp-cerber' ) . '</h2>'

		. $whited .

		'<p style="font-size:130%;"><a href="https://wpcerber.com/getting-started/" target="_blank">' . __( 'Getting Started Guide', 'wp-cerber' ) . '</a></p>' .

		'<div id="crb-activation-msg"><p>' .
		//    <i class="crb-icon crb-icon-bx-slider"></i> <a href="' . cerber_admin_link( 'main' ) . '">' . __( 'Main Settings', 'wp-cerber' ) . '</a>' .
		//' <i class="crb-icon crb-icon-bx-radar"></i> <a href="' . cerber_admin_link( 'scan_main' ) . '">' . __( 'Security Scanner', 'wp-cerber' ) . '</a>' .
		//' <i class="crb-icon crb-icon-bx-lock"></i> <a href="' . cerber_admin_link( 'acl' ) . '">' . __( 'Access Lists', 'wp-cerber' ) . '</a>' .
		//' <i class="crb-icon crb-icon-bxs-shield"></i> <a href="' . cerber_admin_link( 'antispam' ) . '">' . __( 'Anti-spam', 'wp-cerber' ) . '</a>' .
		//' <i class="crb-icon crb-icon-bx-shield-alt"></i> <a href="' . cerber_admin_link( 'hardening' ) . '">' . __( 'Hardening', 'wp-cerber' ) . '</a>' .
		//' <i class="crb-icon crb-icon-bx-bell"></i> <a href="' . cerber_admin_link( 'notifications' ) . '">' . __( 'Notifications', 'wp-cerber' ) . '</a>' .
		' <i class="crb-icon crb-icon-bx-layer"></i> <a href="' . cerber_admin_link( 'imex' ) . '">' . __( 'Import settings', 'wp-cerber' ) . '</a>' .
		' <i class="crb-icon dashicons-before dashicons-twitter"></i> <a target="_blank" href="https://twitter.com/wpcerber">Follow Cerber on X</a>' .
		' <i class="crb-icon dashicons-before dashicons-email-alt"></i> <a target="_blank" href="https://wpcerber.com/subscribe-newsletter/">Subscribe to Cerber\'s newsletter</a>' .
		'</p></div>';

	cerber_update_set( 'cerber_admin_wide', $msg );

	$pi = array();
	$pi['Version'] = CERBER_VER;
	$pi['time'] = time();
	$pi['user'] = get_current_user_id();
	// @since 9.4.2
	$pi['ip'] = cerber_get_remote_ip();
	$pi['ua'] = crb_array_get( $_SERVER, 'HTTP_USER_AGENT', '' );

    cerber_update_set( '_cerber_on', $pi ); // @since 9.4.2

	if ( ! defined( 'CRB_JUST_MARRIED' ) || ! CRB_JUST_MARRIED ) {
		return;
	}

	cerber_send_message( 'activated' );

    if ( ! cerber_get_set( '_activated' ) ) {
		cerber_update_set( '_activated', $pi );
	}

});

/*
	Abort activating plugin!
*/
function cerber_stop_activating( $msg ) {
	deactivate_plugins( CERBER_PLUGIN_ID );
	wp_die( $msg );
}

// Closure can't be used
register_uninstall_hook( cerber_plugin_file(), 'cerber_finito' );
function cerber_finito() {
	if ( ! is_super_admin() ) {
		return;
	}

	$dir = cerber_get_the_folder();
	if ( $dir && file_exists( $dir ) ) {
		$fs = cerber_init_wp_filesystem();
		if ( ! crb_is_wp_error( $fs ) ) {
			$fs->rmdir( $dir, true );
		}
	}

	$list = array(
		CRB_ALERTZ,
		'_cerber_up',
		'_cerber_report',
		'cerber_tmp_old_settings',
		'cerber-groove',
		'cerber-groove-x',
		'_cerberkey_',
		'cerber-antibot',
		'cerber_admin_info',
		'_cerber_db_errors',
        '_cerber_notify_new'
	);

    $list = array_merge( $list, cerber_get_setting_list( true ) );
	foreach ( $list as $opt ) {
		delete_site_option( $opt );
	}

	// Must be executed last
	cerber_db_query( 'DROP TABLE IF EXISTS ' . implode( ',', cerber_get_tables() ) );
}

/**
 * Upgrade database tables, data and plugin settings
 *
 * @since 3.0
 *
 */
function cerber_upgrade_all( $force = false ) {

	$ver = get_site_option( '_cerber_up' );

	if ( ! $force
         && crb_array_get( $ver, 'v' ) == CERBER_VER ) {
		return;
	}

	$d = @ini_get( 'display_errors' );
	@ini_set( 'display_errors', 0 );

	@ignore_user_abort( true );

	crb_raise_limits();

	CRB_Globals::$doing_upgrade = true;
	@define( 'CRB_DOING_UPGRADE', 1 );

	crb_clear_admin_msg();
	cerber_remove_issues();
	cerber_create_db();

	if ( $errors = cerber_upgrade_db() ) {

	}

	cerber_antibot_gene( true );
	cerber_upgrade_settings( crb_array_get( $ver, 'v' ) );
	cerber_htaccess_sync( 'main' );

	cerber_bg_task_add( 'cerber_upgrade_deferred' );
	delete_site_option( '_cerber_report' );

	update_site_option( '_cerber_up', array( 'v' => CERBER_VER, 't' => time() ) );

	cerber_push_the_news();
	cerber_delete_expired_set( true );
	CRB_Cache::reset();
	if ( wp_next_scheduled( 'cerber_hourly' ) ) {
		wp_clear_scheduled_hook( 'cerber_hourly' ); // not in use since v. 5.8.
	}

    // @since 8.9.5.5 Adding new parameters to existing alerts and updating their hashes

	if ( $alerts = get_site_option( CRB_ALERTZ ) ) {
		$test = current( $alerts );
		if ( ! isset( $test[9] ) ) { // Old structure
			$new = array();
			foreach ( $alerts as $alert ) {
				$alert[9] = 0;
				$alert[10] = 0;
				$new[ crb_get_alert_id( $alert ) ] = $alert;
			}
			if ( ! update_site_option( CRB_ALERTZ, $new ) ) {
				cerber_error_log( 'Unable to update the list of alerts', 'ALERTS' );
			}
		}
	}

	// ----------------------------------------------------

	// Delete orphaned WP Cerber files

	$orphans = array( '/nexus/cerber-nexus-master.php', '/nexus/cerber-nexus-slave.php', '/nexus/cerber-slave-list.php' );

	foreach ( $orphans as $file ) {
		$delete = cerber_plugin_dir() . $file;
		if ( is_file( $delete ) ) {
			unlink( $delete );
		}
	}

	// ----------------------------------------------------

	lab_get_key( true );

	CRB_Globals::$doing_upgrade = false;
	delete_site_transient( 'update_plugins' );

	@ini_set( 'display_errors', $d );
}

/**
 * Creates DB tables if they don't exist
 *
 * @param bool $recreate  If true, recreate some tables completely (with data lost)
 *
 * @return array    Errors
 */
function cerber_create_db($recreate = true) {
	global $wpdb;

	$wpdb->hide_errors();
	$db_errors = array();
	$sql       = array();

	if ( ! cerber_is_table( CERBER_LOG_TABLE ) ) {
		$sql[] = '
        	CREATE TABLE IF NOT EXISTS ' . CERBER_LOG_TABLE . ' (
            ip varchar(39) CHARACTER SET ascii NOT NULL,
            user_login varchar(60) NOT NULL,
            user_id bigint(20) unsigned NOT NULL DEFAULT "0",
            stamp bigint(20) unsigned NOT NULL,
            activity int(10) unsigned NOT NULL DEFAULT "0",
            KEY ip (ip)
	        ) DEFAULT CHARSET=utf8;
				';
	}

	if ( ! cerber_is_table( CERBER_ACL_TABLE ) ) {
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . CERBER_ACL_TABLE . ' (
            ip varchar(39) CHARACTER SET ascii NOT NULL,
            tag char(1) NOT NULL,
            comments varchar(250) NOT NULL
	        ) DEFAULT CHARSET=utf8;
				';
	}

	if ( ! cerber_is_table( CERBER_BLOCKS_TABLE ) ) {
		$sql[] = '
	        CREATE TABLE IF NOT EXISTS ' . CERBER_BLOCKS_TABLE . ' (
		    ip varchar(39) CHARACTER SET ascii NOT NULL,
		    block_until bigint(20) unsigned NOT NULL,
		    reason varchar(250) NOT NULL,
		    reason_id int(11) unsigned NOT NULL DEFAULT "0",
		    UNIQUE KEY ip (ip)
			) DEFAULT CHARSET=utf8;			
				';
	}

	if ( ! cerber_is_table( CERBER_LAB_TABLE ) ) {
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . CERBER_LAB_TABLE . ' (
            ip varchar(39) CHARACTER SET ascii NOT NULL,
            reason_id int(11) unsigned NOT NULL DEFAULT "0",
            stamp bigint(20) unsigned NOT NULL,
            details text NOT NULL
			) DEFAULT CHARSET=utf8;
				';
	}


	if ( $recreate || ! cerber_is_table( CERBER_LAB_IP_TABLE ) ) {
		if ( $recreate && cerber_is_table( CERBER_LAB_IP_TABLE ) ) {
			$sql[] = 'DROP TABLE IF EXISTS ' . CERBER_LAB_IP_TABLE;
		}
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . CERBER_LAB_IP_TABLE . ' ( 
            ip varchar(39) CHARACTER SET ascii NOT NULL,
            reputation INT(11) UNSIGNED NOT NULL,
            expires INT(11) UNSIGNED NOT NULL, 
            PRIMARY KEY (ip)
			) DEFAULT CHARSET=utf8;
				';
	}

	if ( $recreate || ! cerber_is_table( CERBER_LAB_NET_TABLE ) ) {
		if ( $recreate && cerber_is_table( CERBER_LAB_NET_TABLE ) ) {
			$sql[] = 'DROP TABLE IF EXISTS ' . CERBER_LAB_NET_TABLE;
		}
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . CERBER_LAB_NET_TABLE . ' (
            ip varchar(39) CHARACTER SET ascii NOT NULL DEFAULT "",             
            ip_long_begin BIGINT UNSIGNED NOT NULL DEFAULT "0",
            ip_long_end BIGINT UNSIGNED NOT NULL DEFAULT "0",
            country CHAR(3) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT "",
            expires INT(11) UNSIGNED NOT NULL DEFAULT "0", 
            PRIMARY KEY (ip),
            UNIQUE KEY begin_end (ip_long_begin, ip_long_end)
			) DEFAULT CHARSET=utf8;
				';
	}

	if ( ! cerber_is_table( CERBER_GEO_TABLE ) ) {
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . CERBER_GEO_TABLE . ' (
            country CHAR(3) NOT NULL DEFAULT "" COMMENT "Country code",
            locale CHAR(10) NOT NULL DEFAULT "" COMMENT "Locale i18n",
            country_name VARCHAR(250) NOT NULL DEFAULT "",
            PRIMARY KEY (country, locale)
			) DEFAULT CHARSET=utf8;
				';
	}

	if ( ! cerber_is_table( CERBER_TRAF_TABLE ) ) {
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . CERBER_TRAF_TABLE . ' (            
            ip varchar(39) CHARACTER SET ascii NOT NULL,
            ip_long BIGINT UNSIGNED NOT NULL DEFAULT "0", 
            hostname varchar(250) NOT NULL DEFAULT "",
            uri text NOT NULL,
            request_fields MEDIUMTEXT NOT NULL,
            request_details MEDIUMTEXT NOT NULL,
            session_id char(32) CHARACTER SET ascii NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
            stamp decimal(14,4) NOT NULL,
            processing int(10) NOT NULL DEFAULT 0,
            country char(3) CHARACTER SET ascii NOT NULL DEFAULT "",
            request_method char(8) CHARACTER SET ascii NOT NULL,
            http_code int(10) UNSIGNED NOT NULL,
            wp_id bigint(20) UNSIGNED NOT NULL DEFAULT 0,
            wp_type int(10) UNSIGNED NOT NULL DEFAULT 0,
            is_bot int(10) UNSIGNED NOT NULL DEFAULT 0,
            blog_id int(10) UNSIGNED NOT NULL DEFAULT 0,
            KEY stamp (stamp)
            ) DEFAULT CHARSET=utf8;
			';
	}

	if ( ! cerber_is_table( cerber_get_db_prefix() . CERBER_SCAN_TABLE ) ) {
		$sql[] = '
		    CREATE TABLE IF NOT EXISTS ' . cerber_get_db_prefix(). CERBER_SCAN_TABLE . ' (
            scan_id INT(10) UNSIGNED NOT NULL,
            scan_type INT(10) UNSIGNED NOT NULL DEFAULT 1,
            scan_mode INT(10) UNSIGNED NOT NULL DEFAULT 0,
            scan_status INT(10) UNSIGNED NOT NULL DEFAULT 0,
            file_name_hash VARCHAR(255) CHARACTER SET ascii NOT NULL DEFAULT "",
            file_name TEXT NOT NULL,
            file_type INT(10) UNSIGNED NOT NULL DEFAULT 0,
            file_hash VARCHAR(255) CHARACTER SET ascii NOT NULL DEFAULT "",
            file_md5 VARCHAR(255) CHARACTER SET ascii NOT NULL DEFAULT "",
            file_hash_repo VARCHAR(255) CHARACTER SET ascii NOT NULL DEFAULT "",
            hash_match INT(10) UNSIGNED NOT NULL DEFAULT 0,
            file_size BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            file_perms INT(11) NOT NULL DEFAULT 0,
            file_writable INT(10) UNSIGNED NOT NULL DEFAULT 0,
            file_mtime INT(10) UNSIGNED NOT NULL DEFAULT 0,
            extra TEXT NOT NULL,
            PRIMARY KEY (scan_id, file_name_hash)
            ) DEFAULT CHARSET=utf8;
        ';
	}

	if ( ! cerber_is_table( cerber_get_db_prefix() . CERBER_SETS_TABLE ) ) {
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' (          
            the_key VARCHAR(255) CHARACTER SET ascii NOT NULL,
            the_id BIGINT(20) NOT NULL DEFAULT 0,
            the_value LONGTEXT NOT NULL,
            expires BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (the_key, the_id)
			) DEFAULT CHARSET=utf8;
        ';
	}

	if ( ! cerber_is_table( CERBER_QMEM_TABLE ) ) {
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . CERBER_QMEM_TABLE . ' (            
            ip varchar(39) CHARACTER SET ascii NOT NULL,
            http_code int(10) UNSIGNED NOT NULL,
            stamp int(10) UNSIGNED NOT NULL,
            KEY ip_stamp (ip, stamp)
			) DEFAULT CHARSET=utf8;
    			';
	}

	if ( ! cerber_is_table( cerber_get_db_prefix() . CERBER_USS_TABLE ) ) {
		$sql[] = '
            CREATE TABLE IF NOT EXISTS ' . cerber_get_db_prefix() . CERBER_USS_TABLE . ' (
            user_id bigint(20) UNSIGNED NOT NULL,
            ip varchar(39) CHARACTER SET ascii NOT NULL,
            country char(3) CHARACTER SET ascii NOT NULL DEFAULT "",
            started int(10) UNSIGNED NOT NULL,
            expires int(10) UNSIGNED NOT NULL,
            session_id char(32) CHARACTER SET ascii NOT NULL DEFAULT "",
            wp_session_token varchar(250) CHARACTER SET ascii NOT NULL,             
            KEY user_id (user_id)
			) DEFAULT CHARSET=utf8;
    			';
	}

	foreach ( $sql as $query ) {
		$query = str_replace( '"', '\'', $query );
		if ( ! $wpdb->query( $query ) && $wpdb->last_error ) {
			$db_errors[] = array( $wpdb->last_error, $wpdb->last_query );
		}
	}

	return $db_errors;
}

/**
 * Upgrade structure of existing DB tables
 *
 * @return array Errors occurred during upgrading database tables
 *
 * @since 3.0
 */
function cerber_upgrade_db( $force = false ) {

	$sql = array();

	// @since 3.0
	$sql[] = 'ALTER TABLE ' . CERBER_LOG_TABLE . ' CHANGE stamp stamp DECIMAL(14,4) NOT NULL';

	// @since 3.1
	if ( $force || ! cerber_is_column( CERBER_LOG_TABLE, 'ip_long' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_LOG_TABLE . ' ADD ip_long BIGINT UNSIGNED NOT NULL DEFAULT "0" AFTER ip, ADD INDEX (ip_long)';
	}
	if ( $force || ! cerber_is_column( CERBER_ACL_TABLE, 'ip_long_begin' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' ADD ip_long_begin BIGINT UNSIGNED NOT NULL DEFAULT "0" AFTER ip, ADD ip_long_end BIGINT UNSIGNED NOT NULL DEFAULT "0" AFTER ip_long_begin';
	}
	/*if ( $force || !cerber_is_index( CERBER_ACL_TABLE, 'ip_begin_end' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' ADD UNIQUE ip_begin_end (ip, ip_long_begin, ip_long_end)';
	}*/
	if ( $force || cerber_is_index( CERBER_ACL_TABLE, 'ip' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' DROP INDEX ip';
	}

	// @since 4.8.2
	if ( $force || cerber_is_index( CERBER_ACL_TABLE, 'begin_end' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' DROP INDEX begin_end';
	}
	/*if ( $force || !cerber_is_index( CERBER_ACL_TABLE, 'begin_end_tag' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' ADD INDEX begin_end_tag (ip_long_begin, ip_long_end, tag)';
	}*/

	// @since 4.9
	if ( $force || ! cerber_is_column( CERBER_LOG_TABLE, 'session_id' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_LOG_TABLE . ' 
        ADD session_id CHAR(32) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT "",
        ADD country CHAR(3) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT "",
        ADD details VARCHAR(250) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT "";
      ';
	}

	// @since 6.1
	if ( $force || ! cerber_is_index( CERBER_LOG_TABLE, 'session_index' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_LOG_TABLE . ' ADD INDEX session_index (session_id)';
	}

	// @since 7.0.3
	$sql[] = 'DROP TABLE IF EXISTS ' . CERBER_SCAN_TABLE;

	// @since 7.1
	if ( $force || ! cerber_is_column( cerber_get_db_prefix() . CERBER_SCAN_TABLE, 'file_status' ) ) {
		$sql[] = 'ALTER TABLE ' . cerber_get_db_prefix() . CERBER_SCAN_TABLE . " ADD file_status INT UNSIGNED NOT NULL DEFAULT '0' AFTER scan_status";
	}

	// @since 7.5.2
	if ( $force || ! cerber_is_column( CERBER_BLOCKS_TABLE, 'reason_id' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_BLOCKS_TABLE . ' ADD reason_id int(11) unsigned NOT NULL DEFAULT "0"';
	}

	// @since 7.8.6
	if ( $force || ! cerber_is_column( CERBER_TRAF_TABLE, 'php_errors' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_TRAF_TABLE . ' ADD php_errors TEXT NOT NULL AFTER blog_id';
	}

	// @since 8.5.4
	if ( $force || ! cerber_is_column( CERBER_ACL_TABLE, 'ver6' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . '
		ADD acl_slice SMALLINT UNSIGNED NOT NULL DEFAULT 0, 
		ADD ver6 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
		ADD v6range VARCHAR(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT "",
		ADD req_uri VARCHAR(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT "",
		MODIFY COLUMN ip VARCHAR(81) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL
		';
	}
	if ( $force || ! cerber_is_index( CERBER_ACL_TABLE, 'main_for_selects' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' ADD INDEX main_for_selects (acl_slice, ver6, ip_long_begin, ip_long_end, tag)';
	}
	if ( $force || cerber_is_index( CERBER_ACL_TABLE, 'begin_end_tag' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' DROP INDEX begin_end_tag';
	}
	if ( $force || cerber_is_index( CERBER_ACL_TABLE, 'ip_begin_end' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_ACL_TABLE . ' DROP INDEX ip_begin_end';
	}

    // @since 8.6.4
	if ( $force || ! cerber_is_column( cerber_get_db_prefix() . CERBER_SCAN_TABLE, 'file_ext' ) ) {
		$sql[] = 'ALTER TABLE ' . cerber_get_db_prefix() . CERBER_SCAN_TABLE . '
		ADD file_ext VARCHAR(255) NOT NULL DEFAULT "" AFTER file_mtime
		';
	}
	if ( $force || ! cerber_is_column( CERBER_TRAF_TABLE, 'req_status' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_TRAF_TABLE . ' ADD req_status int(10) UNSIGNED NOT NULL DEFAULT 0';
	}

	// @since 8.8.6.2
	if ( $force || ! cerber_is_column( cerber_get_db_prefix() . CERBER_SCAN_TABLE, 'scan_step' ) ) {
		$sql[] = 'ALTER TABLE ' . cerber_get_db_prefix() . CERBER_SCAN_TABLE . '
		ADD scan_step INT UNSIGNED NOT NULL DEFAULT 0 AFTER scan_mode
		';
	}

	// @since 8.9.4
	if ( $force || ! cerber_is_column( CERBER_LOG_TABLE, 'ac_status' ) ) {
		$sql[] = 'ALTER TABLE ' . CERBER_LOG_TABLE . '
		ADD ac_bot int(10) UNSIGNED NOT NULL DEFAULT 0,
		ADD ac_status int(10) UNSIGNED NOT NULL DEFAULT 0,
		ADD ac_by_user bigint(20) UNSIGNED NOT NULL DEFAULT 0';
	}

	// @since 9.4.2
    // Add maintenance fields
	if ( $force || ! cerber_is_column( cerber_get_db_prefix() . CERBER_SETS_TABLE, 'argo' ) ) {
		$sql[] = 'ALTER TABLE ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' ADD argo int(10) UNSIGNED NOT NULL DEFAULT 0';
	}

	if ( ! empty( $sql ) ) {
		foreach ( $sql as $query ) {
			$query = str_replace( '"', '\'', $query );
			cerber_db_query( $query );
		}
	}

	cerber_acl_fixer();

	$db_errors = cerber_db_get_errors();

	if ( ! $force && $db_errors ) {
		cerber_add_issue( __FUNCTION__, 'Database errors occured while upgrading WP Cerber database tables.', array( 'section' => 'upgrading_db', 'details' => $db_errors ) );
		cerber_db_error_log( cerber_db_get_errors( true, false ) );
	}

	return $db_errors;
}

/**
 * All upgrade procedures that can be executed via a background or scheduled task
 *
 * @since 8.8
 *
 */
function cerber_upgrade_deferred() {

    // @since 8.8
	list ( $charset, $collate ) = cerber_db_detect_collate();
	if ( $charset == 'utf8mb4' ) {
	    // We can upgrade columns to utf8mb4_unicode_ci
		$col_name = 'request_fields';
		if ( ( $col_info = cerber_db_get_columns( CERBER_TRAF_TABLE, $col_name ) )
		     && 'utf8mb4_unicode_ci' != $col_info['collation'] ) {
			cerber_db_query( 'ALTER TABLE ' . CERBER_TRAF_TABLE . ' MODIFY COLUMN ' . $col_name . ' ' . $col_info['type'] . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;' );
		}
	}
}

function cerber_db_get_columns( $table, $column = '' ) {

	$where = '';
	if ( $column ) {
		$column = preg_replace( '/[^a-z_]/i', '', $column );
		$where = ' WHERE field = "' . $column . '"';
	}

	$table = preg_replace( '/[^a-z_]/i', '', $table );

	if ( $data = cerber_db_get_results( "SHOW FULL COLUMNS FROM " . $table . $where, MYSQL_FETCH_OBJECT_K ) ) {
		if ( $column ) {
			$data = current( $data );
		}

		$data = obj_to_arr_deep( $data );

		return crb_array_change_key_case( $data, CASE_LOWER );
	}

	return false;
}

function cerber_db_detect_collate() {
	global $wpdb;

	if ( ! $wpdb->charset ) {
		$wpdb->init_charset();
	}

	if ( 'utf8mb4' === $wpdb->charset || ( ! $wpdb->charset && $wpdb->has_cap( 'utf8mb4' ) ) ) {
		$charset = 'utf8mb4';
		$collate = 'utf8mb4_unicode_ci';
	}
	else {
		$charset = 'utf8';
		$collate = 'utf8_general_ci';
	}

	return array( $charset, $collate );
}

function cerber_get_tables() {
	return array(
		CERBER_LOG_TABLE,
		CERBER_QMEM_TABLE,
		CERBER_TRAF_TABLE,
		CERBER_ACL_TABLE,
		CERBER_BLOCKS_TABLE,
		CERBER_LAB_TABLE,
		CERBER_LAB_IP_TABLE,
		CERBER_LAB_NET_TABLE,
		CERBER_GEO_TABLE,
		cerber_get_db_prefix() . CERBER_SCAN_TABLE,
		cerber_get_db_prefix() . CERBER_SETS_TABLE,
		cerber_get_db_prefix() . CERBER_MS_TABLE,
		cerber_get_db_prefix() . CERBER_MS_LIST_TABLE,
		cerber_get_db_prefix() . CERBER_USS_TABLE,
	);
}

/**
 * Upgrade outdated / corrupted rows in ACL
 *
 */
function cerber_acl_fixer() {

	$ips = cerber_db_get_col( 'SELECT ip FROM ' . CERBER_ACL_TABLE . ' WHERE ip_long_begin = 0 OR ip_long_end = 0 OR ip_long_begin = 7777777777' );

	if ( ! $ips ) {
		return;
	}

	foreach ( $ips as $ip ) {

	    // Code from cerber_acl_add()

		$v6range = '';
		$ver6 = 0;

		if ( cerber_is_ipv4( $ip ) ) {
			$begin = ip2long( $ip );
			$end   = ip2long( $ip );
		}
        elseif ( cerber_is_ipv6( $ip ) ) {
			$ip = cerber_ipv6_short( $ip );
			list( $begin, $end, $v6range ) = crb_ipv6_prepare( $ip, $ip );
			$ver6 = 1;
		}
        elseif ( ( $range = cerber_any2range( $ip ) )
		         && is_array( $range ) ) {
			$ver6    = $range['IPV6'];
			$begin   = $range['begin'];
			$end     = $range['end'];
			$v6range = $range['IPV6range'];
		}
		else {
			continue;
		}

		$set = 'ip_long_begin = ' . $begin . ', ip_long_end = ' . $end . ', ver6 = ' . $ver6 . ', v6range = "' . $v6range . '"  WHERE ip = "' . $ip . '"';

		cerber_db_query( 'UPDATE ' . CERBER_ACL_TABLE . ' SET ' . $set );
	}
}

add_action( 'deac' . 'tivate_' . CERBER_PLUGIN_ID, function ( $ip ) {
    wp_clear_scheduled_hook( 'cerber_bg_launcher' );
	wp_clear_scheduled_hook( 'cerber_hourly_1' );
	wp_clear_scheduled_hook( 'cerber_hourly_2' );
	wp_clear_scheduled_hook( 'cerber_daily' );
	wp_clear_scheduled_hook( 'cerber_scheduled_hash' );

	cerber_htaccess_clean_up();
	cerber_set_boot_mode( 0 );
	cerber_delete_expired_set( true );
	cerber_delete_set( 'plugins_done' );
	cerber_delete_set( '_background_tasks' );

	$pi = array();
	$pi['Version'] = CERBER_VER;
	$pi['v'] = time();
	$pi['u'] = get_current_user_id();
    // @since 9.4.2
	$pi['ip'] = cerber_get_remote_ip();
	$pi['ua'] = crb_array_get( $_SERVER, 'HTTP_USER_AGENT', '' );

	cerber_update_set( '_cerber_o' . 'ff', $pi );
	$f = 'cerb' . 'er_se' . 'nd_mess' . 'age';
	$f( 'sh' . 'utd' . 'own' );

	CRB_Cache::reset();

	crb_event_handler( 'deactivated', array() );
} );

/**
 * Fixing an issue with the empty user_id field in the WordPress comments table.
 * We use it to count comments for the Users page.
 *
 */
add_filter( 'preprocess_comment', 'cerber_add_uid' );
function cerber_add_uid( $commentdata ) {
	$current_user           = wp_get_current_user();
	$commentdata['user_ID'] = $current_user->ID;

	return $commentdata;
}

/**
 * Load jQuery on the page
 *
 */
add_action( 'login_enqueue_scripts', 'cerber_login_scripts' );
function cerber_login_scripts() {
	if ( cerber_antibot_enabled( array('botsreg', 'botsany') ) ) {
		wp_enqueue_script( 'jquery' );
	}
}

add_action( 'wp_enqueue_scripts', 'cerber_scripts' );
function cerber_scripts() {
	if ( ( ( is_singular() || is_archive() ) && cerber_antibot_enabled( array( 'botscomm', 'botsany' ) ) )
	     || ( crb_get_settings( 'sitekey' ) && crb_get_settings( 'secretkey' ) )
	) {
		wp_enqueue_script( 'jquery' );
	}
}

/**
 * Footer stuff like JS code
 * Explicit rendering reCAPTCHA
 *
 */
add_action( 'login_footer', 'cerber_login_register_stuff', 1000 );
function cerber_login_register_stuff() {

	cerber_antibot_code( array( 'botsreg', 'botsany' ) );

	if ( ! get_wp_cerber()->recaptcha_here ) {
		return;
	}

	// Universal JS

	if ( ! crb_get_settings( 'invirecap' ) ) {
		// Classic version (visible reCAPTCHA)
		echo '<script src = https://www.google.com/recaptcha/api.js?hl=' . cerber_recaptcha_lang() . ' async defer></script>';
	}
	else {
		// Pure JS version with explicit rendering
		?>

        <script src="https://www.google.com/recaptcha/api.js?onload=init_recaptcha_widgets&render=explicit&hl=<?php echo cerber_recaptcha_lang(); ?>" async defer></script>

        <script type='text/javascript'>

            document.getElementById("cerber-recaptcha").remove();

            var init_recaptcha_widgets = function () {
                for (var i = 0; i < document.forms.length; ++i) {
                    var form = document.forms[i];
                    var place = form.querySelector('.cerber-form-marker');
                    if (null !== place) render_recaptcha_widget(form, place);
                }
            };

            function render_recaptcha_widget(form, place) {
                var place_id = grecaptcha.render(place, {
                    'callback': function (g_recaptcha_response) {
                        HTMLFormElement.prototype.submit.call(form);
                    },
                    'sitekey': '<?php echo crb_get_settings('sitekey'); ?>',
                    'size': 'invisible',
                    'badge': 'bottomright'
                });

                form.onsubmit = function (event) {
                    event.preventDefault();
                    grecaptcha.execute(place_id);
                };

            }
        </script>
		<?php
	}
}

/**
 * Add Cerber's JS to the footer on the public pages
 *
 */
add_action( 'wp_footer', 'cerber_wp_footer', PHP_INT_MAX );
function cerber_wp_footer() {

	if ( is_singular() || is_archive() ) {
		cerber_antibot_code( array( 'botscomm', 'botsany' ) );
	}

	if ( ! get_wp_cerber()->recaptcha_here ) {
		return;
	}

	// jQuery version with support visible and invisible reCAPTCHA

	?>
	<script type="text/javascript">

        jQuery( function( $ ) {

            let recaptcha_ok = false;
            let the_recaptcha_widget = $("#cerber-recaptcha");
            let is_recaptcha_visible = ($(the_recaptcha_widget).data('size') !== 'invisible');

            let the_form = $(the_recaptcha_widget).closest("form");
            let the_button = $(the_form).find('input[type="submit"]');
            if (!the_button.length) {
                the_button = $(the_form).find(':button');
            }

            // visible
            if (the_button.length && is_recaptcha_visible) {
                the_button.prop("disabled", true);
                the_button.css("opacity", 0.5);
            }

            window.form_button_enabler = function () {
                if (!the_button.length) return;
                the_button.prop("disabled", false);
                the_button.css( "opacity", 1 );
            };

            // invisible
            if (!is_recaptcha_visible) {
                $(the_button).on('click', function (event) {
                    if (recaptcha_ok) return;
                    event.preventDefault();
                    grecaptcha.execute();
                });
            }

            window.now_submit_the_form = function () {
                recaptcha_ok = true;
                //$(the_button).click(); // this is only way to submit a form that contains "submit" inputs
                $(the_button).trigger('click'); // this is only way to submit a form that contains "submit" inputs
            };
        });
	</script>
	<script src = "https://www.google.com/recaptcha/api.js?hl=<?php echo cerber_recaptcha_lang(); ?>" async defer></script>
	<?php
}

register_shutdown_function( function () {

	cerber_extra_vision();

	// Error monitoring
	if ( 400 <= http_response_code()
	     && ! cerber_is_wp_cron()
	     && ! ( cerber_is_wp_ajax() && ( 400 == http_response_code() ) )
	     && ( $mode = crb_get_settings( 'tierrmon' ) ) ) {
		cerber_error_shield( $mode );
	}

	cerber_push_lab();
	cerber_traffic_log();
} );

function cerber_error_shield( $mode = 1 ) {

	if ( ! $mode || ( crb_get_settings( 'tierrnoauth' ) && crb_is_user_logged_in() ) ) {
		return;
	}

	if ( $mode == 1 ) { // safe mode
		$time = 120;
		$limit = 10;
		$codes = array( 404, 500 );
	}
	else {
		$time = 600;
		$limit = 5;
		$codes = array();
	}

	$code = http_response_code();
	if ( $code < 400 || ( $codes && ! in_array( $code, $codes ) ) ) {
		return;
	}

	$go = false;
	if ( cerber_is_http_post() ) {
		$go = true;
	}

	if ( ! $go && cerber_get_uri_script() ) {
		$go = true;
	}

	if ( ! $go ) {
		if ( $mode == 1 ) {
			if ( cerber_get_non_wp_fields() ) {
				$go = true;
			}
		}
		else {
			if ( ! empty( $_GET ) ) {
				$go = true;
			}
		}
	}

	if ( ! $go && cerber_is_rest_url() ) {
		$go = true;
	}

	if ( ! $go ) {
		return;
	}

	$ip = cerber_get_remote_ip();
	cerber_db_query( 'INSERT INTO ' . CERBER_QMEM_TABLE . ' (ip, http_code, stamp) 
	        VALUES ("' . $ip . '",' . intval( http_response_code() ) . ',' . time() . ')' );

	if ( ! CRB_Globals::$blocked ) {
		$t = time() - $time;
		$c = cerber_db_get_var( 'SELECT COUNT(ip) FROM ' . CERBER_QMEM_TABLE . ' WHERE  ip = "' . $ip . '" AND stamp > ' . $t );
		if ( $c >= $limit ) {
			cerber_soft_block_add( $ip, 711 );
			CRB_Globals::$act_status = 18;
		}
	}

}

/**
 * Error handler collects PHP errors for logging
 *
 * @param $errno
 * @param $errstr
 * @param $errfile
 * @param $errline
 *
 * @return false
 */
function cerber_catch_error( $errno, $errstr = null, $errfile = null, $errline = null ) {

    if ( ! $errno ) {
		return false;
	}

	if ( ! is_array( CRB_Globals::$php_errors ) ) {
		CRB_Globals::$php_errors = array();
	}

	CRB_Globals::$php_errors[] = array( $errno, $errstr, $errfile, $errline );

	return false;

}

function cerber_traffic_log(){
	global $wp_query, $wp_cerber_start_stamp, $blog_id;
	static $done = false;

	if ( $done || cerber_is_cloud_request() ) {
		return;
	}

	$wp_cerber = get_wp_cerber();

	$wp_type = 700;

	if ( cerber_is_wp_ajax() ) {
		/*
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'heartbeat' ) {
			return;
		}*/
		$wp_type = 500;
	}
	elseif ( is_admin() ) {
		$wp_type = 501;
	}
	elseif ( cerber_is_wp_cron() ) {
		$wp_type = 502;
	}
	elseif ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
		$wp_type = 515;
	}
	elseif ( cerber_is_rest_url() ) {
		$wp_type = 520;
	}
	// Public part starts with 600
	elseif ( $wp_query && is_object( $wp_query ) ) {
		$wp_type = 600;
		if ( $wp_query->is_singular ) {
			$wp_type = 601;
		}
		elseif ( $wp_query->is_tag ) {
			$wp_type = 603;
		}
		elseif ( $wp_query->is_category ) {
			$wp_type = 604;
		}
		elseif ( $wp_query->is_search ) {
			$wp_type = 605;
		}
	}

	if ( function_exists( 'http_response_code' ) ) {
		$http_code = http_response_code();
	}
	else {
		$http_code = 200;
		if ( $wp_type > 600 ) {
			if ( $wp_query->is_404 ) {
				$http_code = 404;
			}
		}
	}

	$user_id = 0;
	if ( function_exists( 'get_current_user_id' ) ) {
		$user_id = get_current_user_id();
	}
	if ( ! $user_id && CRB_Globals::$user_id ) {
		$user_id = absint( CRB_Globals::$user_id );
	}

	if ( ! cerber_to_log( $wp_type, $http_code, $user_id ) ) {
		return;
	}

	$done = true;

	if ( cerber_log_exceptions() ) {
		return;
	}

	if ( $ua = crb_array_get( $_SERVER, 'HTTP_USER_AGENT', '' ) ) {
		$ua = substr( $ua, 0, 1000 );
	}

	$bot = cerber_is_crawler( $ua );
	if ( $bot && crb_get_settings( 'tinocrabs' ) ) {
		return;
	}

	$ip = cerber_get_remote_ip();
	$ip_long = 0;
	if ( cerber_is_ipv4( $ip ) ) {
		$ip_long = ip2long( $ip );
	}

	$wp_id = 0;
	if ( $wp_query && is_object( $wp_query ) ) {
		$wp_id = absint( $wp_query->get_queried_object_id() );
	}

	$session_id = $wp_cerber->getRequestID();
	if ( is_ssl() ) {
		$scheme = 'https';
	}
	else {
		$scheme = 'http';
	}
	$uri = $scheme . '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$method = preg_replace( '/[^\w]/', '', $_SERVER['REQUEST_METHOD'] );

	// Request fields

	$fields = '';
	if ( crb_get_settings( 'tifields' ) ) {

        $fields = array();

		if ( ! empty( $_POST ) ) {
			$fields[1] = cerber_prepare_fields( cerber_mask_fields( (array) $_POST ) );
		}

		if ( ! empty( $_GET ) ) {
			$fields[2] = cerber_prepare_fields( (array) $_GET );
		}

		if ( ! empty( $_FILES ) ) {
			$fields[3] = $_FILES;
		}

		if ( ( 'application/json' == strtolower( $_SERVER['CONTENT_TYPE'] ?? '' ) )
		     && $input = file_get_contents( 'php://input' ) ) {
			$json = json_decode( $input, true );
			if ( JSON_ERROR_NONE == json_last_error() ) {
				$fields[4] = cerber_prepare_fields( cerber_mask_fields( $json ) );
			}
		}

		if ( ! empty( $fields ) ) {
			$fields = json_encode( $fields, JSON_UNESCAPED_UNICODE );
		}
		else {
			$fields = '';
		}

	}

	// Extra request details

	$details = array();
	$details[1] = $ua;

	if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
		//$ref = mb_substr( $_SERVER['HTTP_REFERER'], 0, 1048576 ); // 1 Mb for ASCII
		$details[2] = filter_var( $_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL );
	}

	if ( $wp_type == 605 && ! empty( $_GET['s'] ) ) {
		$details[4] = $_GET['s'];
	}
	if ( $wp_type == 515 ) {
		// TODO: add a setting to enable it because there is a user/password in the php://input
		//$details[5] = file_get_contents('php://input');
	}
	if ( crb_get_settings( 'tihdrs' ) ) {
		$hds = crb_getallheaders();
		unset( $hds['Cookie'] );
		unset( $hds['cookie'] );
		ksort( $hds );
		$details[6] = $hds;
	}
	if ( crb_get_settings( 'tisenv' ) ) {
		$srv = $_SERVER;
		unset( $srv['HTTP_COOKIE'] );
		ksort( $srv );
		$details[7] = $srv;
	}
	if ( crb_get_settings( 'ticandy' ) && ! empty( $_COOKIE ) ) {
		$details[8] = $_COOKIE;
		ksort( $details[8] );
	}

	$cs = crb_get_settings( 'ticandy_sent' );
	$hs = crb_get_settings( 'tihdrs_sent' );
	if ( ( $cs || $hs ) && ( $hl = headers_list() ) ) {
		$d1 = array();
		$d2 = array();
		foreach ( $hl as $h ) {
			if ( 0 === strpos( $h, 'Set-Cookie:' ) ) {
				$d1[] = $h;
			}
			else {
				$d2[] = $h;
			}
		}
		if ( $cs ) {
			$details[9] = $d1;
		}
		if ( $hs ) {
			$details[10] = $d2;
		}
	}

	if ( !empty( $details ) ) {
		$details = cerber_prepare_fields( $details );
		$details = json_encode( $details, JSON_UNESCAPED_UNICODE );
	}
	else {
		$details = '';
	}

	// Software errors

    $php_err = '';

	if ( crb_get_settings( 'tiphperr' )
	     && $php_errors = CRB_Globals::get_errors() ) {

		foreach ( $php_errors as $key => $err ) {
			// A special case for is_readable() in class-phpass.php
			if ( $err[0] == E_WARNING ) {
				if ( '/wp-includes/class-phpass.php' == substr( $err[2], - 29 )
				     && strpos( $err[1], '/dev/urandom' ) ) {
					unset( $php_errors[ $key ] );
				}
			}
		}

		// Preparing errors for saving

		if ( $php_errors ) {
			$php_errors = array_values( $php_errors );
			$php_errors = array_slice( $php_errors, 0, 25 );
			$php_err = json_encode( $php_errors, JSON_UNESCAPED_UNICODE );
		}
	}

	// Timestamps
	if ( ! empty( $wp_cerber_start_stamp ) && is_numeric( $wp_cerber_start_stamp ) ) {
		$start = (float) $wp_cerber_start_stamp; // define this variable: $wp_cerber_start_stamp = microtime( true ); in wp-config.php
	}
	else {
		$start = cerber_request_time();
	}

	$processing = (int) ( 1000 * ( microtime( true ) - $start ) );

	$uri = cerber_real_escape( $uri );
	$details = cerber_real_escape( $details );
	$fields = ( $fields ) ? cerber_real_escape( $fields ) : '';
	$php_err = ( $php_err ) ? cerber_real_escape( $php_err ) : '';

	if ( ! $req_status = absint( CRB_Globals::$req_status ) ) {
		if ( crb_acl_is_white() ) {
			$req_status = 510;
		}
	}

	$query = 'INSERT INTO ' . CERBER_TRAF_TABLE . ' 
	(ip, ip_long, uri, request_fields , request_details, session_id, user_id, stamp, processing, request_method, http_code, wp_id, wp_type, is_bot, blog_id, php_errors, req_status ) 
	VALUES ("' . $ip . '", ' . $ip_long . ',"' . $uri . '","' . $fields . '","' . $details . '", "' . $session_id . '", ' . $user_id . ', ' . $start . ',' . $processing . ', "' . $method . '", ' . $http_code . ',' . $wp_id . ', ' . $wp_type . ', ' . $bot . ', ' . absint( $blog_id ) . ',"' . $php_err . '",' . $req_status . ')';

	$ret = cerber_db_query( $query );

	if ( ! $ret ) {
		//cerber_diag_log( print_r( cerber_db_get_errors(), 1 ) );

		// mysqli_error($wpdb->dbh);

		// TODO: Daily software error report
		/*
		echo mysqli_sqlstate($wpdb->dbh);
		echo $wpdb->last_error;
		echo "<p>\n";
		echo $uri;
		echo "<p>\n";
		echo '<p>ERR '.$query.$wpdb->last_error;
		echo '<p>'.$wpdb->_real_escape( $uri );
		*/
	}

}

/**
 * To log or not to log current request?
 *
 * @param $wp_type integer
 * @param $http_code integer
 * @param $user_id integer
 *
 * @return bool
 * @since 6.0
 */
function cerber_to_log( $wp_type, $http_code, $user_id ) {

	if ( nexus_is_valid_request() ) {
		return false;
	}

	$mode = crb_get_settings( 'timode' );

	if ( $mode == 0 ) {
		return false;
	}

	if ( $wp_type == 520 && crb_get_settings( 'tilogrestapi' ) ) {
		return true;
	}

	if ( $wp_type == 515 && crb_get_settings( 'tilogxmlrpc' ) ) {
		return true;
	}

    // All traffic

	if ( $mode == 2 ) {
		if ( $wp_type < 515 ) { // Pure admin requests
			if ( $wp_type < 502 && ! $user_id ) { // @since 6.3
				return true;
			}
			//if ( $wp_type == 500 && 'admin-ajax.php' != cerber_get_uri_script() ) { // @since 7.8
			if ( $wp_type == 500 && ! CRB_Request::is_script( '/wp-admin/admin-ajax.php' ) ) { // @since 7.9.1
				return true;
			}

			return false;
		}

		return true;
	}

    // Minimal

	if ( $mode == 3 ) {

		if ( CRB_Globals::$logged
		     || CRB_Globals::has_errors( E_ERROR ) ) {
			return true;
		}

		return false;
	}

	// Smart mode ---------------------------------------------------------

	if ( ! empty( CRB_Globals::$req_status ) ) {
		return true;
	}

	if ( CRB_Globals::has_errors( array( E_ERROR, E_WARNING ) ) ) {
		return true;
	}

	if ( ! empty( CRB_Globals::$logged ) ) {
		$tmp = CRB_Globals::$logged;
		unset( $tmp[ CRB_EV_LFL ], $tmp[51], $tmp[52] );
		if ( ! empty( $tmp ) ) {
			return true;
		}
	}

	if ( CRB_Globals::$blocked ) {
		return true;
	}

	if ( $wp_type < 515 ) {
		if ( $wp_type < 502 && ! $user_id ) { // @since 6.3
			if ( ! empty( $_GET ) || ! empty( $_POST ) || ! empty( $_FILES ) ) {
				return true;
			}
		}
		if ( $wp_type == 500 && ! CRB_Request::is_script( '/wp-admin/admin-ajax.php' ) ) { // @since 7.8
			return true;
		}

		return false;
	}

	if ( $http_code >= 400 ||
	     $wp_type < 600 ||
	     $user_id ||
	     ! empty( $_POST ) ||
	     ! empty( $_FILES ) ||
	     CRB_Request::is_search()
	     || cerber_get_non_wp_fields() ) {
		return true;
	}

	if ( cerber_is_http_post()
	     || cerber_get_uri_script() ) {
		return true;
	}

	return false;
}

/**
 * @since 8.6.5.2
 */
function cerber_log_exceptions() {

	if ( $ua_list = (array) crb_get_settings( 'tinoua' ) ) {
		if ( $ua = crb_array_get( $_SERVER, 'HTTP_USER_AGENT', '' ) ) {
			$ua = substr( $ua, 0, 1000 );
			foreach ( $ua_list as $item ) {
				if ( false !== stripos( $ua, $item ) ) {
					return true;
				}
			}
		}
	}

	if ( $paths = (array) crb_get_settings( 'tinolocs' ) ) {
		foreach ( $paths as $item ) {
			if ( $item[0] == '{' && substr( $item, - 1 ) == '}' ) {
				$pattern = '/' . substr( $item, 1, - 1 ) . '/i';
				if ( @preg_match( $pattern, $_SERVER['REQUEST_URI'] ) ) {
					return true;
				}
			}
			else {
				$uri = substr( $_SERVER['REQUEST_URI'], 0, strlen( $item ) );
				if ( 0 === stripos( $uri, $item ) ) {
					return true;
				}
			}
		}
	}

	return false;
}

/**
 * Mask sensitive request fields before saving in DB (avoid information leaks)
 *
 * @param $fields array
 *
 * @return array
 *
 * @since 6.0
 */
function cerber_mask_fields( $fields ) {

    $to_mask = array( 'pwd', 'pass', 'password', 'password_1', 'password_2', 'post_password', 'cerber-cloud-key' );

    if ( $list = (array) crb_get_settings( 'timask' ) ) {
		$to_mask = array_merge( $to_mask, $list );
	}

	foreach ( $to_mask as $mask_field ) {
		if ( ! empty( $fields[ $mask_field ] ) ) {
			$fields[ $mask_field ] = str_pad( '', mb_strlen( $fields[ $mask_field ] ), '*' );
		}
	}

	return $fields;
}

/**
 * Recursive prepare values in array for inserting into DB
 *
 * @param $list
 *
 * @return array
 *
 * @since 6.0
 */
function cerber_prepare_fields( $list ) {

    foreach ( $list as &$field ) {
		if ( is_array( $field ) ) {
			$field = cerber_prepare_fields( $field );
		}
		else {
			if ( ! $field ) {
				$field = '';
			}
			else {
				$field = mb_substr( $field, 0, 1048576 );  // 1 Mb for ASCII
			}
		}
	}

	$list = stripslashes_deep( $list );

	return $list;
}

/**
 * Return non WordPress public query $_GET fields (parameters)
 *
 * @return array
 * @since 6.0
 */
function cerber_get_non_wp_fields() {
	global $wp_query;
	static $result;

	if ( isset( $result ) ) {
		return $result;
	}

	$get_keys = array_keys( $_GET );

	if ( empty( $get_keys ) ) {
		$result = array();

		return $result;
	}

	if ( is_object( $wp_query ) ) {
		$keys = $wp_query->fill_query_vars( array() );
	}
	elseif ( class_exists( 'WP_Query' ) ) {
		$tmp  = new WP_Query();
		$keys = $tmp->fill_query_vars( array() );
	}
	else {
		$keys = array();
	}

	$wp_keys = array_keys( $keys );  // WordPress GET fields for frontend

	// Some well-known fields
	$wp_keys[] = 'redirect_to';
	$wp_keys[] = 'reauth';
	$wp_keys[] = 'action';
	$wp_keys[] = '_wpnonce';
	$wp_keys[] = 'loggedout';
	$wp_keys[] = 'doing_wp_cron';

	// WP Customizer fields
	$wp_keys = array_merge( $wp_keys, array(
		'nonce',
		'_method',
		'wp_customize',
		'changeset_uuid',
		'customize_changeset_uuid',
		'customize_theme',
		'theme',
		'customize_messenger_channel',
		'customize_autosaved'
	) );

	$result = array_diff( $get_keys, $wp_keys );

	if ( ! $result ) {
		$result = array();
	}

	return $result;

}


/**
 *
 * @since 6.0
 */
function cerber_beast() {

	if ( is_admin()
	     || cerber_is_wp_cron()
	     || ( defined( 'WP_CLI' ) && WP_CLI )
	) {
		return;
	}

	$wp_cerber = get_wp_cerber();

	$wp_cerber->CheckProhibitedURI();

	// TI --------------------------------------------------------------------

	if ( ! $ti_mode = crb_get_settings( 'tienabled' ) ) {
		return;
	}

	// White list by IP
	if ( crb_get_settings( 'tiipwhite' ) && crb_acl_is_white() ) {
		CRB_Globals::$req_status = 500;
		return;
	}

	// White list by URI
	//$uri = cerber_purify_uri();
	$uri = CRB_Request::URI();
	$uri_slash = $uri . '/';
	if ( $tiwhite = crb_get_settings( 'tiwhite' ) ) {
		foreach ( (array) $tiwhite as $item ) {
			if ( $item[0] == '{' && substr( $item, - 1 ) == '}' ) {
				$pattern = '/' . substr( $item, 1, - 1 ) . '/i';
				if ( @preg_match( $pattern, $uri ) ) {
					CRB_Globals::$req_status = 501;

					return;
				}
			}
			else {
				$cmp = ( substr( $item, - 1 ) == '/' ) ? $uri_slash : $uri; // Someone may specify trailing slash
				if ( $item == $cmp ) {
					CRB_Globals::$req_status = 501;

					return;
				}
			}
		}
	}

	// Step one
	$wp_cerber->InspectRequest();

	// Step two
	//$uri_script = cerber_get_uri_script();
	$uri_script = CRB_Request::script();

	//if ( $uri_script && $script_filename = cerber_script_filename() ) {
	if ( $uri_script && $script_filename = cerber_script_filename() ) { // @since 8.6.3.4
		// Scanning for executable scripts?
		if ( ! cerber_script_exists( $uri ) && ! cerber_is_login_request() ) {
			CRB_Globals::$act_status = 19;
			cerber_log( 55 );
			if ( $ti_mode > 1 ) {
				cerber_soft_block_add( null, 708 );
			}
			cerber_forbidden_page();
		}
		// Direct access to a PHP script
		$deny = false;
		if ( crb_acl_is_black() ) {
			$deny = true;
			CRB_Globals::$act_status = 14;
		}
		//elseif ( ! in_array( $uri_script, cerber_get_wp_scripts() ) ) {
		elseif ( ! CRB_Request::is_script( cerber_get_wp_scripts() ) ) {
			if ( ! cerber_is_ip_allowed() ) {
				$deny = true;
				CRB_Globals::$act_status = 13;
			}
			elseif ( lab_is_blocked( null, true ) ) {
				$deny = true;
				CRB_Globals::$act_status = 15;
			}
		}
		if ( $deny ) {
			cerber_log( CRB_EV_PUR );
			cerber_forbidden_page();
		}
	}

	// Step three
	cerber_screen_request_fields();

	// Step four
	cerber_inspect_uploads();
}

/**
 * Inspects POST & GET fields
 *
 */
function cerber_screen_request_fields(){
	global $cerber_in_context;

	$white = array();
	$found = false;

	if ( ! empty( $_GET ) ) {
		$cerber_in_context = 1;
		$found = cerber_inspect_array( $_GET, array( 's' ) );
	}

	if ( ! empty( $_POST ) && ! $found ) {
		//if ( CRB_Request::is_script( '/' . WP_COMMENT_SCRIPT ) ) {
		if ( CRB_Request::is_comment_sent() ) {
			$white = array( 'comment' );
		}
		$cerber_in_context = 2;
		$found = cerber_inspect_array( $_POST, $white );
	}

	if ( $found ) {
		cerber_log( $found );
		cerber_soft_block_add( null, 709);
		cerber_forbidden_page();
	}
}

/**
 * Recursively inspects values in a given multi-dimensional array
 *
 * @param array $array
 * @param array $white A list of elements to skip
 *
 * @return bool|int
 */
function cerber_inspect_array( &$array, $white = array() ) {

	static $rec_limit = null;

	if ( ! $array ) {
		return false;
	}

	if ( $rec_limit === null ) {
		$rec_limit = CERBER_CIREC_LIMIT;
	}
	else {
		$rec_limit --;
		if ( $rec_limit <= 0 ) {
			$rec_limit   = null;
			CRB_Globals::$act_status = 20;

			return 100;
		}
	}

	foreach ( $array as $key => $value ) {
		if ( in_array( $key, $white ) ) {
			continue;
		}
		if ( is_array( $value ) ) {
			$found = cerber_inspect_array( $value );
		}
		else {
			$found = cerber_inspect_value( $value, true );
		}
		if ( $found ) {
			return $found;
		}
	}

	$rec_limit ++;

	return false;
}

function cerber_inspect_value( &$value = '', $reset = false ) {

	static $rec_limit = null; // Real recursion limit

	if ( ! $value || is_numeric( $value ) ) {
		return false;
	}

	if ( $reset ) {
		$rec_limit = null;
	}

	if ( $rec_limit === null ) {
		$rec_limit = CERBER_CIREC_LIMIT;
	}
	else {
		$rec_limit --;
		if ( $rec_limit <= 0 ) {
			$rec_limit     = null;
			CRB_Globals::$act_status = 21;

			return 100;
		}
	}

	$found = false;

	if ( $varbyref = cerber_is_base64_encoded( $value ) ) {
		$found = cerber_inspect_value( $varbyref );
	}
	else {
		$parsed = cerber_detect_php_code( $value );
		if ( ! empty( $parsed[0] ) ) {
			CRB_Globals::$act_status = 22;
			$found = 100;
		}
		elseif ( ! empty( $parsed[1] ) ) {
			foreach ( $parsed[1] as $string ) {
				$found = cerber_inspect_value( $string );
				if ( $found ) {
					break;
				}
			}
		}
		if ( ! $found && cerber_detect_other_code( $value ) ) {
			CRB_Globals::$act_status = 23;
			$found         = 100;
		}
		if ( ! $found && cerber_detect_js_code( $value ) ) {
			CRB_Globals::$act_status = 24;
			$found         = 100;
		}
	}

	$rec_limit ++;

	return $found;
}

/**
 * @param $value string
 *
 * @return array A list of suspicious code patterns
 */
function cerber_detect_php_code( &$value ) {
	static $list;
	if ( ! $list ) {
		$list = cerber_get_php_unsafe();
	}
	$ret = array( array(), array() );
	$code_tokens = array( T_STRING, T_EVAL );

	$clean = preg_replace( "/[\r\n\s]+/", ' ', cerber_remove_comments( $value ) );

	if ( false === strpos( $clean, '<?php' ) ) {
		$clean = '<?php ' . $clean;
	}

	if ( ! $tokens = @token_get_all( $clean ) ) {
		return $ret;
	}

	foreach ( $tokens as $token ) {
		if ( ! is_array( $token ) ) {
			continue;
		}

		if ( in_array( $token[0], $code_tokens ) && isset( $list[ $token[1] ] ) ) {
			if ( preg_match( '/' . $token[1] . '\((?!\)).+\)/i', $clean ) ) {
				$ret[0] = array( $token[0], $list[ $token[1] ] );
				break;
			}
		}
        elseif ( $token[0] == T_CONSTANT_ENCAPSED_STRING ) {
			$string = trim( $token[1], '\'"' );
			if ( ! $string || is_numeric( $string ) ) {
				continue;
			}
			$ret[1][] = $string;
		}
	}

	return $ret;
}

function cerber_detect_other_code( &$value ) {
	global $cerber_in_context;
	//static $sql = array( 'information_schema.', 'xp_cmdshell', 'FROM_BASE64', '@@' );
	$co    = ( isset( $cerber_in_context ) ) ? $cerber_in_context : 0;
	$score = 0;
	$str = $value;
	if ( $co > 0 ) {
		$str = preg_replace( '#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '', $str ); // Remove comments
		if ( $co == 1 ) {
			if ( strlen( $value ) != strlen( $str ) ) {
				$score ++;
			}
		}
	}
	if ( preg_match( '/\b(?:SELECT|INSERT|UPDATE|DELETE)\b/i', $str ) ) { // SQL?
		$score ++;
		$p = stripos( $str, 'UNION' );
		if ( $p !== false ) {
			$score ++;
			if ( $co == 1 ) {
				return true;
			}
		}
		if ( preg_match( '/\b(?:information_schema|FROM_BASE64|wp_users|xp_cmdshell|LOAD_FILE)\b/i', $value ) ) {
			return true;
		}
		if ( $co < 1 ) {
			return false;
		}
		// $_GET & $_POST
		if ( preg_match( '/\b(?:name_const|unhex)\b/i', $value ) ) {
			$score ++;
		}
		if ( $score > 3 ) {
			return true;
		}
		$char = substr_count( strtoupper( $value ), 'CHAR' );
		if ( $char > 1 ) {
			return true;
		}
		$score += $char;
		if ( $score > 3 ) {
			return true;
		}
	}

	return false;
}

/**
 * Detects ob/fus/cated JS
 *
 * @param $val
 *
 * @return bool
 */
function cerber_detect_js_code( $val ) {
	$val = trim( $val );
	if ( empty( $val ) || is_numeric( $val ) ) {
		return false;
	}
	$val = preg_replace( "/[\s]+/", '', $val );
	if ( strlen( $val ) < 32 ) {
		return false;
	}
	// HEX
	if ( preg_match_all( '/(["\'])(\\\\x[0-9a-fA-F]{2})+?\1/m', $val, $matches ) ) {
		$found   = array_map( function ( $v ) {
			return trim( $v, '\'"' );
		}, $matches[0] );
		$found   = str_replace( '\x', '', $found );

		// -- V2
		/*
		$pieces = array();
		foreach ( $found as $str) {
			echo $str.'-';
			$hexs = str_split( $str, 2 );
			$pieces[] = implode( '', array_map( function ( $v ) {
				return chr( hexdec( $v ) );
			}, $hexs ) );
		}*/

		// V1
		$hexs    = str_split( implode( '', $found ), 2 );
		$decoded = implode( '', array_map( function ( $hex ) {
			return chr( hexdec( $hex ) );
		}, $hexs ) );

		if ( preg_match( '/(fromCharCode|createElement|appendChild|script|eval|unescape|getElement|querySelector|XMLHttpRequest|FileSystemObject)/i', $decoded ) ) {
			return true;
		}

	}

	if ( preg_match_all( '/((?:\d|0x)[\da-fA-F]{1,4})\s*(?:,|\))/m', $val, $matches ) ) {
		$decoded = cerber_fromcharcode( implode( ',', $matches[1] ) );
		list ( $xdata, $severity ) = cerber_process_patterns( $decoded, 'js' );
		if ( $xdata ) {
			return true;
		}
	}

	return false;
}

/**
 * @param string $str Text to process
 * @param string $type Signature type to use
 *
 * @return array[]|false
 */
function cerber_process_patterns( $str, $type ) {
    static $patterns;

	if ( ! isset( $patterns[ $type ] ) ) {
		switch ( $type ) {
			case 'php':
				$patterns[ $type ] = cerber_get_php_patterns();
				break;
			case 'js':
				$patterns[ $type ] = cerber_get_js_patterns();
				break;
			case 'htaccess':
				$patterns[ $type ] = cerber_get_ht_patterns();
				break;
			default:
				return false;
		}
	}

	$xdata = array();
	$severity = array();

	foreach ( $patterns[ $type ] as $pa ) {
		if ($pa[1] == 2) { // 2 = REGEX

			if ( ! empty( $pa['not_regex'] ) ) {
				if ( preg_match( '/' . $pa['not_regex'] . '/i', $str ) ) {
					continue;
				}
			}

			$matches = array();

			if ( preg_match_all( '/' . $pa[2] . '/i', $str, $matches, PREG_OFFSET_CAPTURE ) ) {

				if ( ! empty( $pa['not_func'] ) && is_callable( $pa['not_func'] ) ) {
					foreach ( $matches[0] as $key => $match ) {
						if ( call_user_func( $pa['not_func'], $match[0], $str ) ) {
							unset( $matches[0][ $key ] );
						}
					}
				}

				if ( ! empty( $pa['func'] ) && is_callable( $pa['func'] ) ) {
					foreach ( $matches[0] as $key => $match ) {
						if ( ! call_user_func( $pa['func'], $match[0], $str ) ) {
							unset( $matches[0][ $key ] );
						}
					}
				}

				if ( ! empty( $matches[0] ) ) {
					$xdata[]    = array( 2, $pa[0], array_values( $matches[0] ) );
					$severity[] = $pa[3];
				}
			}
		}
		else {
			if ( false !== stripos( $str, $pa[2] ) ) {
				$xdata[]    = array( 2, $pa[0], array( array( $pa[2] ) ) );
				$severity[] = $pa[3];
			}
		}
	}

	return array( $xdata, $severity );
}

function cerber_inspect_uploads() {
	static $found = null;

	if ( $found !== null ) {
		return $found; // avoid double inspection
	}

	if ( empty( $_FILES ) ) {
		return false;
	}

	global $crb_uploaded_files;
	$crb_uploaded_files = array();
	array_walk_recursive( $_FILES, function ( $file_name ) {
		global $crb_uploaded_files;
		if ( $file_name
             && is_string( $file_name )
             && is_file( $file_name ) ) {
			$crb_uploaded_files[] = $file_name;
		}
	} );

	if ( empty( $crb_uploaded_files ) ) {
		return false;
	}

	$found = false;

	foreach ( $crb_uploaded_files as $file_name ) {
		if ( $f = @fopen( $file_name, 'r' ) ) {
			$str = @fread( $f, 100000 );
			@fclose( $f );
			if ( cerber_inspect_value( $str, true ) ) {
				$found = 56;
				if ( ! @unlink( $file_name ) ) {
					// if a system doesn't permit us to delete the file in the tmp uploads folder
					$target = cerber_get_the_folder() . 'must_be_deleted.tmp';
					@move_uploaded_file( $file_name, $target );
					@unlink( $target );
				}
			}
		}
	}

	if ( $found ) {
		cerber_log( $found );
		cerber_soft_block_add( null, 710);
	}

	return $found;
}

function cerber_error_control() {
	if ( crb_get_settings( 'nophperr' ) ) {
		@ini_set( 'display_startup_errors', 0 );
		@ini_set( 'display_errors', 0 );
	}
}

/**
 * @param $alert
 *
 * @return bool
 *
 * @since 8.9.5.4
 */
function crb_is_alert_expired( $alert ) {
	if ( ! empty( $alert[11] ) && ! empty( $alert[12] )
	     && $alert[11] <= $alert[12] ) {
		return true;
	}

	if ( ! empty( $alert[13] ) && $alert[13] < time() ) {
		return true;
	}

	return false;
}

// Menu routines ---------------------------------------------------------------

// Hide/show menu items in public
add_filter( 'wp_get_nav_menu_items', function ( $items, $menu, $args ) {
	if ( is_admin() ) {
		return $items;
	}
	$logged = is_user_logged_in();
	foreach ( $items as $key => $item ) {
		// For *MENU*CERBER* See cerber_nav_menu_box() !!!
		if ( 0 === strpos( $item->attr_title, '*MENU*CERBER*' ) ) {
			$menu_id = explode( '|', $item->attr_title );
			switch ( $menu_id[1] ) {
				case 'wp-cerber-login-url':
					if ( $logged ) {
						unset( $items[ $key ] );
					}
					break;
				case 'wp-cerber-logout-url':
					if ( ! $logged ) {
						unset( $items[ $key ] );
					}
					break;
				case 'wp-cerber-reg-url':
					if ( $logged ) {
						unset( $items[ $key ] );
					}
					break;
				case 'wp-cerber-wc-login-url':
					if ( $logged ) {
						unset( $items[ $key ] );
					}
					break;
				case 'wp-cerber-wc-logout-url':
					if ( ! $logged ) {
						unset( $items[ $key ] );
					}
					break;
			}
		}
	}

	return $items;
}, 10, 3 );

// Set actual URL for a menu item based on a special value in title attribute
add_filter( 'nav_menu_link_attributes', function ( $atts ) {

	// For *MENU*CERBER* See cerber_nav_menu_box() !!!
	if ( 0 === strpos( $atts['title'], '*MENU*CERBER*' ) ) {
		$title         = explode( '|', $atts['title'] );
		$atts['title'] = '';

		$url = '#';
		// See cerber_nav_menu_items() !!!
		switch ( $title[1] ) {
			case 'wp-cerber-login-url':
				$url = wp_login_url();
				break;
			case 'wp-cerber-logout-url':
				$url = wp_logout_url();
				break;
			case 'wp-cerber-reg-url':
				if ( get_option( 'users_can_register' ) ) {
					$url = wp_registration_url();
				}
				break;
			case 'wp-cerber-wc-login-url':
				if ( class_exists( 'WooCommerce' ) ) {
					$url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
				}
				break;
			case 'wp-cerber-wc-logout-url':
				if ( class_exists( 'WooCommerce' ) ) {
					$url = wc_logout_url();
				}
				break;
		}

		$atts['href'] = $url;
	}

	return $atts;
}, 1 );

function cerber_push_the_news() {

	if ( ! $news = cerber_parse_change_log( true ) ) {
		return;
	}

	$news = array_slice( $news, 0, 7 );

	$text = '<h1>Highlights from WP Cerber Security ' . CERBER_VER . '</h1>';

	$text .= '<ul><li>' . implode( '</li><li>', $news ) . '</li></ul>';

	$text .= '<p style="margin-top: 18px; line-height: 1.3;"><span class="dashicons-before dashicons-info-outline"></span>  &nbsp; <a href="https://wpcerber.com/?plugin_version=' . CERBER_VER . '" target="_blank">Read more on wpcerber.com</a></p>';
	//$text .= '<p style="margin-top: 18px; font-weight: 600;"><a href="' . cerber_admin_link( 'change-log' ) . '">See the whole history in the changelog</a></p>';

	if ( ! defined( 'CRB_JUST_MARRIED' )
	     && crb_was_activated( 2 * WEEK_IN_SECONDS )
	     && ! lab_lab() ) {
		$text .= '  <h2 style="margin-top: 28px;">' . __( "We need your support to keep moving forward", 'wp-cerber' ) . '</h2>
                    <table style="margin-top: 20px;"><tr><td></td><td style="padding-top: 0;">' .
		         __( 'By sharing your unique opinion on WP Cerber, you help the engineers behind the plugin make greater progress and help other professionals find the right software. You can leave your review on one of the following websites. Feel free to use your native language. Thanks!', 'wp-cerber' )
		         . '
                    </td></tr></table>
                       
                    <p><a href="' . crb_get_review_url( 'tpilot' ) . '" target="_blank">Leave review on Trustpilot</a>
                    &nbsp;|&nbsp; 
                    <a href="' . crb_get_review_url( 'g2' ) . '" target="_blank">Leave review on G2.COM</a>';
		//&nbsp;|&nbsp;
		//<a href="' . crb_get_review_url( 'cap' ) . '" target="_blank">Capterra</a></p>

	}
	else {

		$text .= '	<p style="margin-top: 24px;"><span class="dashicons-before dashicons-email-alt"></span> &nbsp; <a href="https://wpcerber.com/subscribe-newsletter/">Subscribe to Cerber\'s newsletter</a></p>
					<p><span class="dashicons-before dashicons-twitter"></span> &nbsp; <a href="https://twitter.com/wpcerber">Follow Cerber on X</a></p>
					<p><span class="dashicons-before dashicons-facebook"></span> &nbsp; <a href="https://www.facebook.com/wpcerber/">Follow Cerber on Facebook</a></p>
				';
	}

	$text .= '<p style="text-align:right; padding-right: 20px;">
    		    <input type="button" class="button button-primary cerber-dismiss" value=" &nbsp; ' . __( 'Awesome!', 'wp-cerber' ) . ' &nbsp; "/></p>';

	update_site_option( 'cerber_admin_info', $text );
}

function cerber_parse_change_log( $last_only = false ) {
	if ( ! $text = file( cerber_get_plugins_dir() . '/wp-cerber/changelog.txt' ) ) {
		return false;
	}

	$ret = array();
	$abort = 0;
	$ver = '';

	foreach ( $text as $line ) {
		$line = ltrim( $line, '* ' );
		$line = trim( $line );

		if ( ! $line ) {
			continue;
		}

		$line = htmlspecialchars( $line );

		if ( preg_match_all( '/(\[.+?])(\(.+?\))/', $line, $m ) ) {
			$anchors = $m[1];
			$links = $m[2];
			$replace = array();
			foreach ( $anchors as $i => $anchor ) {
				$replace[] = '<a href="' . trim( $links[ $i ], '()' ) . '" target="_blank">' . trim( $anchor, '[]' ) . '</a>';
			}
			$line = str_replace( $anchors, $replace, $line );
			$line = str_replace( $links, '', $line );
		}
        elseif ( preg_match( '/=([.\d\s]+?)=/', $line, $m ) ) {
			if ( ! $last_only ) {
				if ( $ver ) {
					$ret[] = '<p><a href="https://wpcerber.com/wp-cerber-security-' . str_replace( array( '.', ' ' ), array( '-', '' ), $ver ) . '/" target="_blank">Read release note on wpcerber.com</a></p>';
				}
				$ver = $m[1];
				$line = str_replace( $m[0], '<span class="crb-version">' . $m[1] . '</span>', $line );
			}
			else {
				$line = '';
				$abort ++;
			}
		}

		$ret[] = $line;

		if ( $abort > 1 ) {
			$ret = array_filter( $ret );
			$ret = preg_replace( '/^\*\s*/', '', $ret, 1 );
			break;
		}
	}

	return  $ret;
}

add_shortcode( 'wp_cerber_cookies', 'cerber_show_cookies' );
function cerber_show_cookies( $attr ) {
    global $wp_cerber_cookies;

	$html_atts = '';

	if ( isset( $attr['id'] ) ) {
		$html_atts .= ' id="' . esc_attr( $attr['id'] ) . '"';
	}

	if ( isset( $attr['style'] ) ) {
		$html_atts .= ' style="' . esc_attr( $attr['style'] ) . '"';
	}

	/*if ( ! $cookies = cerber_get_set( 'cerber_sweets' ) ) {
		return '';
	}*/

	$cookies = $wp_cerber_cookies;

	if ( ! is_user_logged_in() ) {
		foreach ( $cookies as $cookie => $data ) {
			if ( cerber_is_auth_cookie( $cookie ) ) {
				unset( $cookies[ $cookie ] );
			}
		}
	}

	$ret = '';

	if ( isset( $attr['text'] ) ) {
		$ret .= $attr['text'];
	}

	$type = crb_array_get( $attr, 'type' );

	switch ( $type ) {
		case 'comma':
			$ret .= implode( ', ', array_keys( $cookies ) );
			break;
		case 'table':
			$items = '';
			foreach ( $cookies as $cookie => $data ) {
				$items .= '<tr><td>' . $cookie . '</td></tr>';
			}

			$ret .= '<table ' . $html_atts . '><tbody>' . $items . '</tbody></table>';
			break;
		case 'list':
		default:
			$items = '';
			foreach ( $cookies as $cookie => $data ) {
				$items .= '<li>' . $cookie . '</li>';
			}

			$ret .= '<ul ' . $html_atts . '>' . $items . '</ul>';
	}

	return $ret;
}

add_action( 'wp_create_application_password', function ( $user_id, $new_item, $new_password, $args ) {
	cerber_log( 150, '', $user_id );
}, 0, 4 );

add_action( 'wp_update_application_password', function ( $user_id, $item, $update ) {
	cerber_log( 149, '', $user_id );
}, 0, 3 );

add_action( 'wp_delete_application_password', function ( $user_id, $item ) {
	cerber_log( 153, '', $user_id );
}, 0, 3 );

/**
 * Check if the current user is the website admin (can manage website)
 * @since 8.6.9
 *
 * @return bool
 */
function cerber_user_can_manage() {
	if ( is_multisite() ) {
		$cap = 'manage_network';
	}
	else {
		$cap = 'manage_options';
	}

	return current_user_can( $cap );
}