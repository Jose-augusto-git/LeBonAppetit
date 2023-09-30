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

if ( ! defined( 'WPINC' ) ) {
	define( 'WPINC', 'wp-includes' );
}

const MYSQL_FETCH_OBJECT = 5;
const MYSQL_FETCH_OBJECT_K = 6;
const CRB_IP_NET_RANGE = '/[^a-f\d\-\.\:\*\/]+/i';
const CRB_SANITIZE_ID = '[a-z\d\_\-\.\:\*\/]+';
const CRB_SANITIZE_KEY = '/[^a-z_\-\d.:\/]/i';
const CRB_GROOVE = 'cerber_groove';

// Critical: the order of these fields MUST NOT be changed

const CRB_ALERT_PARAMS = array(
	'filter_activity'    => 0, // 0
	'filter_user'        => 0, // 1
	'begin'              => 0, // 2 - IP
	'end'                => 0, // 3 - IP
	'filter_ip'          => 0, // 4 - IP
	'filter_login'       => 0, // 5
	'search_activity'    => 0, // 6
	'filter_role'        => 0, // 7
	'user_ids'           => 0, // 8

	// @since 8.9.6
	'search_url'         => 0, // 9
	'filter_status'      => 0, // 10

	// Non-query alert settings, see CRB_NON_ALERT_PARAMS
	'al_limit'           => 0, // 11
	'al_count'           => 0, // 12
	'al_expires'         => 0, // 13
	'al_ignore_rate'     => 0, // 14

	// @since 8.9.7
	'al_send_emails'     => 0, // 15
	'al_send_pushbullet' => 0, // 16
	// @since 9.4.2
	'al_send_me'         => 0, // 17
);

const CRB_NON_ALERT_PARAMS = array(
	'al_limit',
	'al_count',
	'al_expires',
	'al_ignore_rate',
	'al_send_emails',
	'al_send_pushbullet',
	'al_send_me',
);

const CRB_ALERTZ = '_cerber_subs';

// Known alert channels

const CRB_CHANNELS = array( 'email' => 1, 'pushbullet' => 1 );

// Events (activities)

const CRB_EV_LIN = 5;
const CRB_EV_LFL = 7;
const CRB_EV_PUR = 50;
const CRB_EV_LDN = 53;

const CRB_EV_PRS = 21;
const CRB_EV_UST = 22;
const CRB_EV_PRD = 25;

// Statuses

const CRB_STS_25 = 25;
const CRB_STS_29 = 29;
const CRB_STS_30 = 30;

const CRB_STS_11 = 11;
const CRB_STS_51 = 51;
const CRB_STS_52 = 52;
const CRB_STS_532 = 532;

// Denied by WP Cerber

const CRB_AC_DENIED = array( 12, 16, 17, 18, 19, CRB_EV_PRD, 41, 42, CRB_EV_PUR, 51, 52, CRB_EV_LDN, 54, 55, 56, 57, 70, 71, 72, 73, 74, 75, 76, 100 );

/**
 * Known WP scripts
 * @since 6.0
 *
 */
function cerber_get_wp_scripts() {
	$scripts = array( WP_LOGIN_SCRIPT, WP_REG_URI, WP_XMLRPC_SCRIPT, WP_TRACKBACK_SCRIPT, WP_PING_SCRIPT, WP_SIGNUP_SCRIPT );
	if ( ! cerber_is_custom_comment() ) {
		$scripts[] = WP_COMMENT_SCRIPT;
	}

	return array_map( function ( $e ) {
		return '/' . $e;
	}, $scripts );
}

/**
 * Returns WP Cerber nonce
 *
 * @param string $action
 *
 * @return false|string
 *
 * @since 8.9.5.3
 */
function crb_create_nonce( $action = 'control' ) {
	static $cache = array();

	if ( ! isset( $cache[ $action ] ) ) {
		crb_load_dependencies( 'wp_create_nonce', true );
		$cache[ $action ] = wp_create_nonce( $action );
	}

	return $cache[ $action ];
}

/**
 * Returns Site URL + /wp-admin/admin.php
 *
 * @return string
 *
 * @since 8.9.5.3
 */
function crb_get_admin_base(){
	static $adm_base;

	if ( ! isset( $adm_base ) ) {
		if ( nexus_is_valid_request() ) {
			$base = nexus_request_data()->base;
		}
		else {
			$base = ( ! is_multisite() ) ? admin_url() : network_admin_url();
		}

		$adm_base = rtrim( $base, '/' ) . '/admin.php';
	}

	return $adm_base;
}

/**
 * Return a link (full URL) to a Cerber admin page.
 * Add a particular tab and GET parameters if they are specified
 *
 * @param string $tab Tab on the page
 * @param array $args GET arguments to add to the URL
 * @param bool $add_nonce If true, adds the nonce
 * @param bool $encode
 *
 * @return string   Full URL
 */
function cerber_admin_link( $tab = '', $args = array(), $add_nonce = false, $encode = true ) {

	$page = 'cerber-security';

	if ( empty( $args['page'] ) ) {

		// TODO: look up the page in tabs config
		//$config = cerber_get_admin_page_config();

		if ( in_array( $tab, array( 'antispam', 'captcha' ) ) ) {
			$page = 'cerber-recaptcha';
		}
		elseif ( in_array( $tab, array( 'imex', 'diagnostic', 'license', 'diag-log', 'change-log' ) ) ) {
			$page = 'cerber-tools';
		}
		elseif ( in_array( $tab, array( 'traffic', 'ti_settings' ) ) ) {
			$page = 'cerber-traffic';
		}
		elseif ( in_array( $tab, array( 'user_shield', 'opt_shield' ) ) ) {
			$page = 'cerber-shield';
		}
		elseif ( in_array( $tab, array( 'geo' ) ) ) {
			$page = 'cerber-rules';
		}
		elseif ( in_array( $tab, array( 'role_policies', 'global_policies' ) ) ) {
			$page = 'cerber-users';
		}
		else {
			if ( list( $prefix ) = explode( '_', $tab, 2 ) ) {
				if ( $prefix == 'scan' ) {
					$page = 'cerber-integrity';
				}
				elseif ( $prefix == 'nexus' ) {
					$page = 'cerber-nexus';
				}
			}
		}
	}
	else {
		$page = $args['page'];
		unset( $args['page'] );
	}

	$link = crb_get_admin_base() . '?page=' . $page;

	$amp = ( $encode ) ? '&amp;' : '&';

	if ( $tab ) {
		$link .= $amp . 'tab=' . preg_replace( '/[^\w\-]+/', '', $tab );
	}

	if ( $args ) {
		foreach ( $args as $arg => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $val ) {
					$link .= $amp . $arg . '[]=' . urlencode( $val );
				}
			}
			else {
				$link .= $amp . $arg . '=' . urlencode( $value );
			}
		}
	}

	if ( $add_nonce ) {
		$link .= $amp . 'cerber_nonce=' . crb_create_nonce();
	}

	return $link;
}

/**
 * Return modified link to the currently displaying page
 *
 * @param array $args Arguments to add to the link to the currently displayed page
 * @param bool $preserve Save GET parameters of the current request other than 'page' and 'tab'
 * @param bool $add_nonce Add Cerber's nonce
 *
 * @return string
 */
function cerber_admin_link_add( $args = array(), $preserve = false, $add_nonce = true ) {

	$link = cerber_admin_link( crb_admin_get_tab(), array( 'page' => crb_admin_get_page() ), $add_nonce );

	if ( $preserve ) {
		$get = crb_get_query_params();
		unset( $get['page'], $get['tab'] );
	}
	else {
		$get = array();
	}

	if ( $args ) {
		$get = array_merge( $get, $args );
	}

	if ( $get ) {
		foreach ( $get as $arg => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $key => $val ) {
					$link .= '&amp;' . $arg . '[' . $key . ']=' . urlencode( $val );
				}
			}
			else {
				$link .= '&amp;' . $arg . '=' . urlencode( $value );
			}
		}
	}

	return esc_url( $link );
}

/**
 * @param array $act
 * @param int $status
 *
 * @return string
 */
function cerber_activity_link( $act = array(), $status = null ) {
	static $link;

	if ( ! $link ) {
		$link = cerber_admin_link( 'activity' );
	}

	$filter = '';

	$c = $act ? count( $act ) : 0;

	if ( 1 == $c ) {
		$filter .= '&amp;filter_activity=' . crb_absint( array_shift( $act ) );
	}
	elseif ( $c ) {
		foreach ( $act as $key => $item ) {
			$filter .= '&amp;filter_activity[' . $key . ']=' . crb_absint( $item );
		}
	}

	if ( $status ) {
		$filter .= '&amp;filter_status=' . crb_absint( $status );
	}

	return $link . $filter;
}

function cerber_traffic_link( $set = array(), $format = 1 ) {
	$ret = cerber_admin_link( 'traffic', $set );

	if ( $format ) {
		$class = ( $format == 1 ) ? 'class="crb-button-tiny"' : '';
		$ret = '<a ' . $class . ' href="' . $ret . '">' . __( 'Check for requests', 'wp-cerber' ) . '</a>';
	}

	return $ret;
}

function cerber_get_login_url() {
	$ret = '';

	if ( $path = crb_get_settings( 'loginpath' ) ) {
		$ret = cerber_get_home_url() . '/' . $path . '/';
	}

	return $ret;
}

/**
 * Extracts the site domain (or IP address) and the sub folder (path) if any
 *
 * @return array
 *
 * @since 8.6.6.1
 */
function crb_parse_site_url() {
	static $result;

	if ( isset( $result ) ) {
		return $result;
	}

	$site_url = cerber_get_site_url();

	$p1 = strpos( $site_url, '//' );
	$offset = ( $p1 !== false ) ? $p1 + 2 : 0;
	$sub_folder_pos = ( strlen( $site_url ) > $offset ) ? strpos( $site_url, '/', $offset ) : false;

	if ( $sub_folder_pos !== false ) {
		$site_root = substr( $site_url, 0, $sub_folder_pos );
		$sub_folder = substr( $site_url, $sub_folder_pos );
	}
	else {
		$site_root = $site_url;
		$sub_folder = '';
	}

	$result = array( $site_root, $sub_folder );

	return $result;
}

/**
 * Always includes the path to the current WP installation
 *
 * @return string
 * @since 7.9.4
 *
 */
function cerber_get_site_url() {
	static $url;

	if ( isset( $url ) ) {
		return $url;
	}

	$url = trim( get_site_url(), '/' );

	return $url;
}

/**
 * Might NOT include the path to the current WP installation in some cases
 * See: https://wordpress.org/support/article/giving-wordpress-its-own-directory/
 *
 * @return string
 * @since 7.9.4
 *
 */
function cerber_get_home_url() {
	static $url;

	if ( ! isset( $url ) ) {
		$url = trim( get_home_url(), '/' );
	}

	return $url;
}

/**
 * Makes a site label to include it in the file name of files generated by WP Cerber
 *
 * @return string
 *
 * @since 9.5.1
 */
function crb_site_label() {
	$home = cerber_get_site_url();

	return trim( preg_replace( '/[^\w\d]+/', '-', substr( $home, strpos( $home, '//' ) + 2 ) ), '-' );
}

/**
 * For non-HTML cases. Not suitable for HTML rendering.
 *
 * @return string
 */
function crb_get_blogname_decoded() {
	return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
}

/**
 * @param int $begin Unix timestamp
 * @param int $end Unix timestamp
 *
 * @return array[]
 */
function cerber_calculate_kpi( $begin, $end ) {

	// TODO: Add spam performance as percentage Denied / Allowed comments

	return array(
		array(
			__( 'Malicious activities mitigated', 'wp-cerber' ) . '</a>',
			cerber_count_log( crb_get_activity_set( 'malicious' ), $begin, $end )
		),
		array( __( 'Spam comments denied', 'wp-cerber' ), cerber_count_log( array( 16 ), $begin, $end ) ),
		array( __( 'Spam form submissions denied', 'wp-cerber' ), cerber_count_log( array( 17 ), $begin, $end ) ),
		array( __( 'Malicious IP addresses detected', 'wp-cerber' ), cerber_count_log( crb_get_activity_set( 'malicious' ), $begin, $end, 'ip', true ) ),
		array( __( 'Lockouts occurred', 'wp-cerber' ), cerber_count_log( array( 10, 11 ), $begin, $end ) ),
	);

}

/**
 * The list devices available to send notifications
 *
 * @param string $token
 *
 * @return array|false|WP_Error
 *
 */
function cerber_pb_get_devices( $token = '' ) {

	cerber_delete_set( 'pushbullet_name' );

	if ( ! $token ) {
		if ( ! $token = crb_get_settings( 'pbtoken' ) ) {
			return false;
		}
	}

	$ret = array();

	$curl = @curl_init();

	if ( ! $curl ) {
		return false;
	}

	$headers = array(
		'Authorization: Bearer ' . $token
	);

	crb_configure_curl( $curl, array(
		CURLOPT_URL               => 'https://api.pushbullet.com/v2/devices',
		CURLOPT_HTTPHEADER        => $headers,
		CURLOPT_RETURNTRANSFER    => true,
		CURLOPT_CONNECTTIMEOUT    => 2,
		CURLOPT_TIMEOUT           => 4, // including CURLOPT_CONNECTTIMEOUT
		CURLOPT_DNS_CACHE_TIMEOUT => 4 * 3600,
	) );

	$result = @curl_exec( $curl );
	$curl_error = curl_error( $curl );
	curl_close( $curl );

	$response = json_decode( $result, true );

	if ( JSON_ERROR_NONE == json_last_error()
	     && isset( $response['devices'] ) ) {
		foreach ( $response['devices'] as $device ) {
			if ( ! empty( $device['active'] )
			     && ( $iden = $device['iden'] ?? false ) ) {
				$nn = $device['nickname'] ?? 'Device ' . $iden;
				$ret[ $iden ] = $nn;
			}
		}
	}
	else {
		if ( $response['error'] ) {
			$e = 'Pushbullet ' . $response['error']['message'];
		}
		elseif ( $curl_error ) {
			$e = $curl_error;
		}
		else {
			$e = 'Unknown cURL error';
		}

		$ret = new WP_Error( 'cerber_pb_error', $e );
	}

	return $ret;
}

/**
 * Send push message via Pushbullet
 *
 * @param string $title
 * @param string $text
 * @param string $more Additional text (links)
 * @param string $footer
 *
 * @return bool|WP_Error True on success
 */
function cerber_pb_send( $title, $text, $more = '', $footer = '' ) {

	if ( ! $text ) {
		return false;
	}

	if ( ! $token = crb_get_settings( 'pbtoken' ) ) {
		return false;
	}

	$body = $text;

	if ( $format = crb_get_settings( 'pb_format' ) ) {
		if ( $format == 1 ) {
			$body .= $more;
		}
	}
	else {
		$body .= $more . $footer;
	}

	$params = array( 'type' => 'note', 'title' => $title, 'body' => $body, 'sender_name' => 'WP Cerber' );

	if ( $device = crb_get_settings( 'pbdevice' ) ) {
		if ( $device != 'all' && $device != 'N' ) {
			$params['device_iden'] = $device;
		}
	}

	$headers = array(
		'Access-Token: ' . $token,
		'Content-Type: application/json'
	);

	$curl = @curl_init();
	if ( ! $curl ) {
		return false;
	}

	crb_configure_curl( $curl, array(
		CURLOPT_URL               => 'https://api.pushbullet.com/v2/pushes',
		CURLOPT_POST              => true,
		CURLOPT_HTTPHEADER        => $headers,
		CURLOPT_POSTFIELDS        => json_encode( $params ),
		CURLOPT_RETURNTRANSFER    => true,
		CURLOPT_CONNECTTIMEOUT    => 2,
		CURLOPT_TIMEOUT           => 4, // including CURLOPT_CONNECTTIMEOUT
		CURLOPT_DNS_CACHE_TIMEOUT => 4 * 3600,
	) );

	$result = @curl_exec( $curl );

	if ( $curl_error = curl_error( $curl ) ) {
		$ret = new WP_Error( 'cerber_pb_error', $curl_error );
	}
	else {
		$ret = true;
	}

	curl_close( $curl );

	return $ret;
}

/**
 * Returns the name of the selected Pushbullet device
 *
 * @return string Sanitized name of the device retrieved from Pushbullet
 *
 * @since 8.9.5.3
 */
function cerber_pb_get_active() {

	if ( false !== $name = cerber_get_set( 'pushbullet_name', null, false ) ) {
		return $name;
	}

	// Updating the cache

	$device = crb_get_settings( 'pbdevice' );

	$name = '';

	if ( $device == 'all' ) {
		$name = 'all connected devices';
	}
	elseif ( $device && $list = cerber_pb_get_devices() ) {
		$name = htmlspecialchars( $list[ $device ] ) ?? '';
	}

	cerber_update_set( 'pushbullet_name', $name, null, false, time() + 3600 );

	return $name;
}

/**
 * Check the server environment and the WP Cerber configuration for possible issues
 *
 * @return void
 *
 * @since 9.5.1
 */
function cerber_issue_monitor() {
	static $checkers = array();

	if ( ! $checkers ) {
		$checkers = array(
			'cerber-security' => function () {
				$results = new WP_Error;

				if ( crb_get_settings( 'cerber_sw_repo' )
				     && ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL )
				     && defined( 'WP_ACCESSIBLE_HOSTS' )
				     && false === strpos( WP_ACCESSIBLE_HOSTS, 'downloads.wpcerber.com' ) ) {
					$results->add( 'norepo', 'To permit automatic updates for WP Cerber, add "downloads.wpcerber.com" to the WP_ACCESSIBLE_HOSTS constant' . ' ' . '[ <a href="https://wpcerber.com/cerber-sw-repository/" target="_blank">Know more</a> ]' );
				}

				if ( crb_get_settings( 'cerber_sw_auto' ) ) {
					crb_load_dependencies( 'wp_is_auto_update_enabled_for_type' );
					if ( ! wp_is_auto_update_enabled_for_type( 'plugin' ) ) {
						$results->add( 'noauto', 'WP Cerber does not get automatic updates because automatic updates for plugins on this website are disabled.' );
					}
				}

				if ( cerber_get_mode() != crb_get_settings( 'boot-mode' ) ) {
					$results->add( 'booterr', 'The plugin is initialized in a different mode that does not match the settings. Check the "Load security engine" setting.' );
				}

				if ( $ler = cerber_get_set( 'last_email_error' ) ) {
					if ( $ler[0] > ( time() - WEEK_IN_SECONDS ) ) {

						$txt = $ler[2] . ' Error #' . $ler[3];
						$txt .= ( $ler[4] ? '. SMTP server: ' . $ler[4] : '' );
						$txt .= ( $ler[5] ? '. SMTP username: ' . $ler[5] : '' );
						$txt .= ( $ler[6] ? '. Recipient(s): ' . implode( ',', $ler[6] ) : '' );
						$txt .= ( $ler[7] ? '. Subject: "' . $ler[7] . '"' : '' );
						$txt .= '. Date: ' . cerber_date( $ler[0] );

						$results->add( 'emailerr', 'An error occurred while sending email. ' . $txt );
					}

					cerber_delete_set( 'last_email_error' );
				}

				return $results;
			},
			'cerber-integrity' => function () {
				if ( defined( 'CERBER_FOLDER_PATH' ) ) {
					return cerber_get_my_folder();
				}

				return false;
			},
			'cerber-shield' => function () {
				return CRB_DS::check_errors();
			},
			'*' => function () {
				$results = new WP_Error;

				if ( ! crb_get_settings( 'tienabled' ) ) {
					$results->add( 'noti', 'Traffic Inspector is disabled' );
				}

				$ex_list = get_loaded_extensions();

				if ( ! in_array( 'mbstring', $ex_list ) || ! function_exists( 'mb_convert_encoding' ) ) {
					$results->add( 'nombstring', 'Required PHP extension <b>mbstring</b> is not enabled on this website. Some plugin features do work properly. Please enable the PHP mbstring extension (multibyte string support) in your hosting control panel.' );
				}

				if ( ! in_array( 'curl', $ex_list ) ) {
					$results->add( 'nocurl', 'cURL PHP library is not enabled on this website.' );
				}
				else {
					$curl = @curl_init();

					if ( ! $curl
					     && ( $err_msg = curl_error( $curl ) ) ) {
						$results->add( 'nocurl', $err_msg );
					}

					curl_close( $curl );
				}

				return $results;
			}
		);
	}

	$notices = array();
	$page = crb_admin_get_page();

	// Part 1. We periodically run all the checks

	if ( cerber_get_set( '_check_env', 0, false ) < ( time() - 120 ) ) {

		cerber_update_set( '_check_env', time(), 0, false );

		foreach ( $checkers as $page_id => $check ) {
			if ( ! is_callable( $check ) || $page == $page_id ) {
				continue;
			}

			if ( crb_is_wp_error( $test = call_user_func( $check ) )
			     && $codes = $test->get_error_codes() ) {
				foreach ( $codes as $err_code ) {
					$notices[ $err_code ] = $test->get_error_message( $err_code );
				}
			}
		}
	}

	// Part 2. Critical checks on a specific page (context)

	if ( ( $check = $checkers[ $page ] ?? false )
	     && is_callable( $check )
	     && crb_is_wp_error( $test = call_user_func( $check ) )
	     && $codes = $test->get_error_codes() ) {
		//$notices[ $page ] = $test->get_error_message();
		foreach ( $codes as $err_code ) {
			$notices[ $err_code ] = $test->get_error_message( $err_code );
		}
	}

	// Part 3. Critical things we monitor continuously

	if ( version_compare( CERBER_REQ_PHP, phpversion(), '>' ) ) {
		$notices['php'] = sprintf( __( 'WP Cerber requires PHP %s or higher. You are running %s.', 'wp-cerber' ), CERBER_REQ_PHP, phpversion() );
	}

	if ( ! crb_wp_version_compare( CERBER_REQ_WP ) ) {
		$notices['wordpress'] = sprintf( __( 'WP Cerber requires WordPress %s or higher. You are running %s.', 'wp-cerber' ), CERBER_REQ_WP, cerber_get_wp_version() );
	}

	if ( defined( 'CERBER_CLOUD_DEBUG' ) ) {
		$notices['cloud'] = 'Diagnostic logging of cloud requests is enabled (CERBER_CLOUD_DEBUG).';
	}

	if ( $notices ) {

		foreach ( $notices as $code => $notice ) {
			cerber_add_issue( $code, $notice );
		}

		$notices = array_map( function ( $e ) {
			return '<b>' . __( 'Warning!', 'wp-cerber' ) . '</b> ' . $e;
		}, $notices );

		cerber_admin_notice( $notices );
	}
}

/**
 * Register an issue
 *
 * @param string $code
 * @param string $text
 * @param array $data
 *
 * @since 9.3.4
 */
function cerber_add_issue( $code, $text, $data = array() ) {
	static $issues = null;

	return; // TODO: it was commented out until we implement the proper UI for viewing/clearing the list of issues
	// TODO: implement storing issues in a file as a fallback if no DB connection.

	if ( $issues === null ) {
		$issues = cerber_get_issues();
	}

	$section = (string) $data['section'] ?? 'generic';
	$code = (string) $code;

	if ( ! isset( $issues[ $section ][ $code ] ) ) {
		$details = $data['details'] ?? array();
		$setting = $data['setting'] ?? '';

		$issues[ $section ][ $code ] = array( time(), $text, $setting, $details );

		cerber_update_set( CRB_ISSUE_SET, $issues );
	}
}

/**
 * @param bool $plain
 *
 * @return array
 *
 * @since 9.3.4
 */
function cerber_get_issues( $plain = false ) {

	if ( ! $issues = cerber_get_set( CRB_ISSUE_SET ) ) {
		$issues = array();
	}

	if ( $issues && $plain ) {
		$ret = array();
		foreach ( $issues as $list ) {
			$ret = array_merge( $ret, array_column( $list, 0 ) );
		}

		return $ret;
	}

	return $issues;
}

/**
 * Deletes issues from the storage
 *
 * @param string $section
 *
 * @return void
 *
 * @since 9.3.4
 */
function cerber_remove_issues( $section = '' ) {
	if ( ! $section ) {
		cerber_delete_set( CRB_ISSUE_SET );

		return;
	}

	$issues = cerber_get_issues();

	if ( isset( $issues[ $section ] ) ) {
		unset( $issues[ $section ] );
		cerber_update_set( CRB_ISSUE_SET, $issues );
	}
}

/**
 * Health check-up and self-repairing for vital parts
 *
 */
function cerber_watchdog( $full = false ) {
	if ( $full ) {
		cerber_create_db( false );
		cerber_upgrade_db();

		return;
	}
	if ( ! cerber_is_table( CERBER_LOG_TABLE )
	     || ! cerber_is_table( CERBER_BLOCKS_TABLE )
	     || ! cerber_is_table( CERBER_LAB_IP_TABLE )
	) {
		cerber_create_db( false );
		cerber_upgrade_db();
	}
}

/**
 * Detect and return remote client IP address
 *
 * @return string Valid IP address
 * @since 6.0
 */
function cerber_get_remote_ip() {
	static $remote_ip;

	if ( isset( $remote_ip ) ) {
		return $remote_ip;
	}

	if ( defined( 'CERBER_IP_KEY' ) ) {
		$remote_ip = filter_var( $_SERVER[ CERBER_IP_KEY ] ?? '', FILTER_VALIDATE_IP );
	}
	elseif ( crb_get_settings( 'proxy' ) ) {
		$remote_ip = crb_extract_ip_from_headers();
	}
	else {
		$remote_ip = filter_var( $_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP );
	}

	if ( ! $remote_ip ) { // including WP-CLI, other way is: if defined('WP_CLI')
		$remote_ip = CERBER_NO_REMOTE_IP;
	}

	if ( cerber_is_ipv6( $remote_ip ) ) {
		$remote_ip = cerber_ipv6_short( $remote_ip );
	}

	return $remote_ip;
}

/**
 * Extracting the client remote IP address from HTTP headers
 *
 * @return string|false The valid IP address, false otherwise
 *
 * @since 9.4.2.4
 */
function crb_extract_ip_from_headers( $strict = false ) {

	$remote_ip = false;

	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {

		$list = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );

		// We have to start with the left-most IP address

		foreach ( $list as $maybe_ip ) {
			if ( $remote_ip = filter_var( trim( $maybe_ip ), FILTER_VALIDATE_IP ) ) {
				return $remote_ip;
			}
		}
	}

	if ( $strict ) {
		return $remote_ip;
	}

	// The last resort if no IP address in the $_SERVER['HTTP_X_FORWARDED_FOR']

	$remote_ip = filter_var( $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? '', FILTER_VALIDATE_IP );

	return $remote_ip;
}

/**
 * Get ip_id for IP.
 * The ip_id can be safely used for array indexes and in any HTML code
 *
 * @param $ip string IP address
 *
 * @return string ID for given IP
 * @since 2.2
 *
 */
function cerber_get_id_ip( $ip ) {
	$ip_id = str_replace( '.', '-', $ip, $count );
	$ip_id = str_replace( ':', '_', $ip_id );

	return $ip_id;
}

/**
 * Get IP from ip_id
 *
 * @param $ip_id string ID for an IP
 *
 * @return string IP address for given ID
 * @since 2.2
 *
 */
function cerber_get_ip_id( $ip_id ) {
	$ip = str_replace( '-', '.', $ip_id, $count );
	$ip = str_replace( '_', ':', $ip );

	return $ip;
}

/**
 * Check if given IP address is a valid single IP v4 address
 *
 * @param $ip
 *
 * @return bool
 */
function cerber_is_ipv4( $ip ) {
	return (bool) filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
}

function cerber_is_ipv6( $ip ) {
	return (bool) filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 );
}

/**
 * Check if a given IP address belongs to a private network (private IP).
 * Universal: support IPv6 and IPv4.
 *
 * @param $ip string An IP address to check
 *
 * @return bool True if IP is in the private range, false otherwise
 */
function is_ip_private( $ip ) {

	if ( ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE ) ) {
		return true;
	}
	elseif ( ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE ) ) {
		return true;
	}

	return false;
}

function cerber_is_ip( $ip ) {
	return filter_var( $ip, FILTER_VALIDATE_IP );
}

/**
 * Hostname validation: it's either a valid domain or a valid IP address
 *
 * @param string $str
 *
 * @return bool
 *
 * @since 9.5.1
 */
function crb_is_valid_hostname( $str ) {
	return ( filter_var( $str, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME )
	         || filter_var( $str, FILTER_VALIDATE_IP ) );
}

/**
 * Expands shortened IPv6 to full IPv6 address
 *
 * @param $ip string IPv6 address
 *
 * @return string Full IPv6 address
 */
function cerber_ipv6_expand( $ip ) {
	$full_hex = (string) bin2hex( inet_pton( $ip ) );

	return implode( ':', str_split( $full_hex, 4 ) );
}

/**
 * Compress full IPv6 to shortened
 *
 * @param $ip string IPv6 address
 *
 * @return string Full IPv6 address
 */
function cerber_ipv6_short( $ip ) {
	if ( ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
		return $ip;
	}

	return inet_ntop( inet_pton( $ip ) );
}

/**
 * Convert multilevel object or array of objects to associative array recursively
 *
 * @param $var object|array
 *
 * @return array result of conversion
 * @since 3.0
 */
function obj_to_arr_deep( $var ) {
	if ( is_object( $var ) ) {
		$var = get_object_vars( $var );
	}
	if ( is_array( $var ) ) {
		return array_map( __FUNCTION__, $var );
	}

	return $var;
}

/**
 * Search for a string key in a given multidimensional array
 *
 * @param array $array
 * @param string $needle
 *
 * @return bool
 */
function crb_multi_search_key( $array, $needle ) {
	foreach ( $array as $key => $value ) {
		if ( (string) $key == (string) $needle ) {
			return true;
		}
		if ( is_array( $value ) ) {
			$ret = crb_multi_search_key( $value, $needle );
			if ( $ret == true ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * array_column() implementation for PHP < 5.5
 *
 * @param array $arr Multidimensional array
 * @param string $column Column key
 *
 * @return array
 */
function crb_array_column( $arr = array(), $column = '' ) {
	global $x_column;
	$x_column = $column;

	$ret = array_map( function ( $element ) {
		global $x_column;

		return $element[ $x_column ];
	}, $arr );

	$ret = array_values( $ret );

	return $ret;
}

/**
 * @param $arr array
 * @param $key string|integer|array
 * @param $default mixed
 * @param $pattern string REGEX pattern for value validation, UTF is not supported
 *
 * @return mixed
 */
function crb_array_get( &$arr, $key, $default = false, $pattern = '' ) {
	if ( ! is_array( $arr ) || empty( $arr ) ) {
		return $default;
	}

	if ( is_array( $key ) ) {
		$ret = crb_array_get_deep( $arr, $key );
		if ( $ret === null ) {
			$ret = $default;
		}
	}
	else {
		$ret = ( isset( $arr[ $key ] ) ) ? $arr[ $key ] : $default;
	}

	if ( ! $pattern ) {
		return $ret;
	}

	if ( ! is_array( $ret ) ) {
		if ( @preg_match( '/^' . $pattern . '$/i', $ret ) ) {
			return $ret;
		}

		return $default;
	}

	global $cerber_temp;
	$cerber_temp = $pattern;

	array_walk( $ret, function ( &$item ) {
		global $cerber_temp;
		if ( ! @preg_match( '/^' . $cerber_temp . '$/i', $item ) ) {
			$item = '';
		}
	} );

	return array_filter( $ret );
}

/**
 * Retrieve element from multi-dimensional array
 *
 * @param array $arr
 * @param array $keys Keys (dimensions)
 *
 * @return mixed|null Value of the element if it's defined, null otherwise
 */
function crb_array_get_deep( &$arr, $keys ) {
	if ( ! is_array( $arr ) ) {
		return null;
	}

	$key = array_shift( $keys );
	if ( isset( $arr[ $key ] ) ) {
		if ( empty( $keys ) ) {
			return $arr[ $key ];
		}

		return crb_array_get_deep( $arr[ $key ], $keys );
	}

	return null;
}

/**
 * Compare two arrays by using keys: check if two arrays have different set of keys
 *
 * @param $arr1 array
 * @param $arr2 array
 *
 * @return bool true if arrays have different set of keys
 */
function crb_array_diff_keys( &$arr1, &$arr2 ) {
	if ( count( $arr1 ) != count( $arr2 ) ) {
		return true;
	}
	if ( array_diff_key( $arr1, $arr2 ) ) {
		return true;
	}
	if ( array_diff_key( $arr2, $arr1 ) ) {
		return true;
	}

	return false;
}

/**
 * Compares two elements of two arrays
 *
 * @param $arr1 array
 * @param $arr2 array
 * @param $key1 string|int
 * @param $key2 string|int
 *
 * @return bool True if elements are equal or absent in two arrays
 */
function crb_array_cmp_val( &$arr1, &$arr2, $key1, $key2 = null ) {
	if ( ! $key2 ) {
		$key2 = $key1;
	}

	if ( ( $set = isset( $arr1[ $key1 ] ) ) !== isset( $arr2[ $key2 ] ) ) {
		return false;
	}

	if ( ! $set ) {
		return true;
	}

	return ( $arr1[ $key1 ] === $arr2[ $key2 ] );
}

/**
 * Changes the case of all keys in an array.
 * Supports multi-dimensional arrays.
 *
 * @param array $arr
 * @param int $case CASE_LOWER | CASE_UPPER
 *
 * @return array
 */
function crb_array_change_key_case( $arr, $case = CASE_LOWER ) {
	return array_map( function ( $item ) use ( $case ) {
		if ( is_array( $item ) ) {
			$item = crb_array_change_key_case( $item, $case );
		}

		return $item;
	}, array_change_key_case( $arr, $case ) );
}

/**
 * @param string|array $val
 *
 * for objects see map_deep();
 */
function crb_trim_deep( &$val ) {
	if ( ! is_array( $val ) ) {
		$val = trim( $val );
	}

	array_walk_recursive( $val, function ( &$v ) {
		$v = trim( $v );
	} );
}

/**
 * - Checks for invalid UTF-8,
 * - Converts single `<` characters to entities
 * - Strips all tags
 * - Removes tabs, and extra whitespace
 * - Strips octets
 *
 * @param string|array $val
 *
 * Note: _sanitize_text_fields removes HTML tags
 *
 */
function crb_sanitize_deep( &$val ) {
	if ( ! is_array( $val ) ) {
		if ( $val && ! is_numeric( $val ) ) { // crucial since sanitize_text_fields() convert all values to strings
			$val = _sanitize_text_fields( $val, true );
		}
	}
	else {
		array_walk_recursive( $val, function ( &$v ) {
			if ( $v && ! is_numeric( $v ) ) { // crucial since sanitize_text_fields() convert all values to strings
				$v = _sanitize_text_fields( $v, true );
			}
		} );
	}
}

/**
 * Sanitizes integer values
 *
 * @param array|int $val Input value
 * @param bool $make_list If true, returns result as an array
 * @param bool $keep_empty If false, removes empty elements from the resulting array
 *
 * @return array|int
 *
 * @since 8.9.5.2
 */
function crb_sanitize_int( $val, $make_list = true, $keep_empty = false ) {
	if ( ! is_array( $val ) ) {
		if ( $make_list ) {
			$val = array( $val );
		}
		else {
			return crb_absint( $val );
		}
	}

	array_walk_recursive( $val, function ( &$v ) {
		$v = crb_absint( $v );
	} );

	if ( ! $keep_empty ) {
		$val = array_filter( $val );
	}

	return $val;
}

/**
 * Simple and quick "escaping/sanitizing" for manually coded plain ASCII alphanumeric values.
 * Should not be used for any external or untrusted data (user input, DB, network etc.)
 *
 * @param string $val
 *
 * @return string
 *
 * @since 9.3.4
 */
function crb_boring_escape( $val = '' ) {
	if ( ! $val
	     || is_numeric( $val ) ) {
		return $val;
	}

	return preg_replace( '/[^\w\-\[\].]/', '', (string) $val );
}

/**
 * Escaping HTML attributes and form fields
 *
 * @param array|string $value
 *
 * @return array|string Escaped array or string
 */
function crb_attr_escape( $value ) {
	if ( is_array( $value ) ) {
		array_walk_recursive( $value, function ( &$element ) {
			$element = crb_escape( $element );
		} );
	}
	else {
		$value = crb_escape( $value );
	}

	return $value;
}

/**
 * Escaping singular values
 *
 * @param string $val
 *
 * @return string Escaped string
 */
function crb_escape( $val ) {
	if ( ! $val
	     || is_numeric( $val ) ) {
		return $val;
	}

	// the same way as in esc_attr();
	return _wp_specialchars( $val, ENT_QUOTES );
}

/**
 * Return true if a REST API URL has been requested
 *
 * @return bool
 * @since 3.0
 */
function cerber_is_rest_url() {
	global $wp_rewrite;
	static $ret = null;

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return true;
	}

	if ( isset( $_REQUEST['rest_route'] ) ) {
		return true;
	}

	if ( ! $wp_rewrite ) { // see get_rest_url() in the multisite mode
		return false;
	}

	if ( isset( $ret ) ) {
		return $ret;
	}

	$ret = false;

	if ( crb_get_rest_path() ) {
		$ret = true;
	}

	return $ret;
}

/**
 * @return bool
 *
 * @since 8.8
 */
function cerber_is_api_request() {
	return ( ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) );
}

/**
 * Check if the current query is HTTP GET
 *
 * @return bool true if request method is GET
 */
function cerber_is_http_get() {
	if ( nexus_is_valid_request() ) {
		return ! nexus_request_data()->is_post;
	}
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
		return true;
	}

	return false;
}

/**
 * Check if the current query is HTTP POST
 *
 * @return bool true if request method is POST
 */
function cerber_is_http_post() {
	if ( nexus_is_valid_request() ) {
		return nexus_request_data()->is_post;
	}

	if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		return true;
	}

	return false;
}

/**
 * Checks if it's a wp cron request
 *
 * @return bool
 */
function cerber_is_wp_cron() {
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		return true;
	}
	if ( CRB_Request::is_script( '/wp-cron.php' ) ) {
		return true;
	}

	return false;
}

function cerber_is_wp_ajax( $use_filter = false ) {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return true;
	}

	// @since 8.1.3
	if ( $use_filter && function_exists( 'wp_doing_ajax' ) ) {
		return wp_doing_ajax();
	}

	return false;
}

/**
 * Checks whether the given variable is a WordPress Error
 *
 * @param mixed $thing
 * @param bool $add_issue If true, the error will be saved as an issue
 * @param string $issue_section Use this section when saving the issue
 *
 * @return bool True if the variable is an instance of WP_Error
 *
 * @since 9.0.4
 */
function crb_is_wp_error( $thing, $add_issue = false, $issue_section = '' ) {
	$ret = false;

	if ( $thing instanceof WP_Error ) {

		$ret = true;

		if ( $add_issue ) {
			cerber_add_issue( $thing->get_error_code(), $thing->get_error_message(), array( 'section' => $issue_section ) );
		}
	}

	return $ret;
}

/**
 * @return bool True if it's the user edit/profile WordPress admin page
 */
function is_admin_user_edit() {
	if ( ( defined( 'IS_PROFILE_PAGE' ) && IS_PROFILE_PAGE )
	     || CRB_Request::is_script( array( '/wp-admin/user-edit.php', '/wp-admin/profile.php' ) ) ) {
		return true;
	}

	return false;
}

/**
 * Returns a $_GET parameter with a given key
 *
 * @param $key string
 * @param $pattern string
 * @param $filter_var integer filter_var() filter ID
 *
 * @return bool|array|string
 */
function cerber_get_get( $key, $pattern = '', $filter_var = null ) {

	$ret = crb_array_get( $_GET, $key, false, $pattern );

	if ( $filter_var ) {
		return filter_var( $ret, FILTER_VALIDATE_IP );
	}

	return $ret;
}

/**
 *
 * @param $key string
 * @param $pattern string
 *
 * @return bool|array|string
 */
function cerber_get_post( $key, $pattern = '' ) {
	return crb_array_get( $_POST, $key, false, $pattern );
}

/**
 * Returns values of $_GET parameters (query string)
 *
 * @param string $key
 * @param string $pattern
 *
 * @return array|string|mixed
 */
function crb_get_query_params( $key = null, $pattern = '' ) {
	if ( nexus_is_valid_request() ) {
		if ( $key ) {
			return crb_array_get( nexus_request_data()->get_params, $key, false, $pattern );
		}

		return (array) nexus_request_data()->get_params;
	}

	// Local context

	if ( $key ) {
		return cerber_get_get( $key, $pattern );
	}

	return (array) $_GET;
}

function crb_get_post_fields( $key = null, $default = false, $pattern = '' ) {
	if ( nexus_is_valid_request() ) {
		if ( nexus_request_data()->is_post ) {
			return nexus_request_data()->get_post_fields( $key, $default, $pattern );
		}

		return array();
	}

	if ( $key ) {
		return crb_array_get( $_POST, $key, $default, $pattern );
	}

	return $_POST;
}

function crb_get_request_field( $field, $default = false ) {
	$fields = crb_get_request_fields();

	return crb_array_get( $fields, $field, $default );
}

function crb_get_request_fields() {
	if ( nexus_is_valid_request() ) {
		$ret = nexus_request_data()->get_params;
		if ( nexus_request_data()->is_post ) {
			$ret = array_merge( $ret, nexus_request_data()->get_post_fields() );
		}

		return $ret;
	}

	return $_REQUEST;
}

function cerber_is_rest_permitted() {

	$rest_path = crb_get_rest_path();

	// Exception: application passwords route @since WP Cerber 8.8 & WP 5.6 -> permissions are checked in the WP core
	if ( preg_match( '#^wp/v\d+/users/\d+/application-passwords#', $rest_path ) ) {

		return true;
	}

	$opt = crb_get_settings();

	if ( ! empty( $opt['norestuser'] ) ) {
		$path = explode( '/', $rest_path );
		if ( $path
		     && count( $path ) > 2
		     && $path[0] == 'wp'
		     && $path[2] == 'users' ) {

			if ( is_super_admin() ) {
				return true; // @since 8.3.4
			}
			if ( crb_wp_user_has_role( $opt['norestuser_roles'] ) ) {
				return true;
			}

			return false;
		}

	}

	if ( empty( $opt['norest'] ) ) {
		return true;
	}

	CRB_Globals::$user_id = get_current_user_id();

	if ( $opt['restauth'] && is_user_logged_in() ) {
		return true;
	}

	if ( ! empty( $opt['restwhite'] ) ) {
		$namespace = substr( $rest_path, 0, strpos( $rest_path, '/' ) );
		foreach ( $opt['restwhite'] as $allowed ) {
			if ( $allowed == $namespace ) {
				CRB_Globals::$req_status = 503;

				return true;
			}
		}

	}

	if ( crb_wp_user_has_role( $opt['restroles'] ) ) {
		return true;
	}

	return false;
}

/**
 * Requested REST API route without leading and trailing slashes
 *
 * @return string Empty string for any non-REST API request
 */
function crb_get_rest_path() {
	global $wp_rewrite;
	static $ret;

	if ( isset( $ret ) ) {
		return $ret;
	}

	$ret = '';

	if ( isset( $_REQUEST['rest_route'] ) ) {
		$ret = trim( $_REQUEST['rest_route'], '/' );
	}
	elseif ( cerber_is_permalink_enabled()
	         && $path = CRB_Request::get_relative_path() ) {

		$path = trim( $path, '/' );
		$prefix = rest_get_url_prefix() . '/';

		// See get_rest_url() ----------------

		if ( ( $wp_rewrite instanceof WP_Rewrite )
		     && $wp_rewrite->using_index_permalinks() ) {

			$prefix = $wp_rewrite->index . '/' . $prefix;
		}

		// -----------------------------------

		if ( 0 === strpos( $path, $prefix ) ) {
			$ret = substr( $path, strlen( $prefix ) );
		}
	}

	return $ret;
}

/**
 *
 * @return string Full URL including scheme, host, path and trailing slash
 *
 */
function crb_get_rest_url() {
	static $ret;

	if ( ! isset( $ret ) ) {
		$ret = get_rest_url();
	}

	return $ret;
}

/**
 * Check if a user has at least one role from the list
 *
 * @param array $roles
 * @param null $user_id
 *
 * @return bool
 */
function crb_wp_user_has_role( $roles = array(), $user_id = null ) {
	if ( ! $roles ) {
		return false;
	}

	if ( ! is_array( $roles ) ) {
		$roles = array( (string) $roles );
	}

	if ( ! $user_id ) {
		$user = wp_get_current_user();
	}
	else {
		$user = get_userdata( $user_id );
	}

	if ( ! $user || empty( $user->roles ) ) {
		return false;
	}

	if ( array_intersect( $user->roles, $roles ) ) {
		return true;
	}

	return false;
}

/**
 * Check if all user roles are in the list
 *
 * @param array|string $roles
 * @param int $user_id
 *
 * @return bool false if the user has role(s) other than listed in $roles
 */
function crb_user_has_role_strict( $roles, $user_id ) {
	if ( ! $user_id || ! $user = get_userdata( $user_id ) ) {
		return false;
	}

	if ( ! is_array( $roles ) ) {
		$roles = array( $roles );
	}

	$user_roles = ( is_array( $user->roles ) ) ? $user->roles : array();

	return ( ! array_diff( $user_roles, $roles ) );
}

/**
 * Returns metadata if the user is blocked by admin
 *
 * @param int $uid User ID
 *
 * @return false|array
 */
function crb_is_user_blocked( $uid ) {
	if ( $uid
	     && ( $m = get_user_meta( $uid, CERBER_BUKEY, 1 ) )
	     && ! empty( $m['blocked'] )
	     && $m[ 'u' . $uid ] == $uid ) {
		return $m;
	}

	return false;
}

/**
 * Returns textual info on who and when blocked a user
 *
 * @param array $meta
 *
 * @return string
 *
 * @since 9.0.2
 */
function crb_user_blocked_by( $meta ) {
	if ( empty( $meta['blocked_by'] ) ) {
		return '';
	}

	if ( $meta['blocked_by'] == get_current_user_id() ) {
		$who = __( 'You', 'wp-cerber' );
	}
	else {
		$user = get_userdata( $meta['blocked_by'] );
		$who = '<a href="' . get_edit_user_link( $user->ID ) . '" target="_blank">' . $user->display_name . '</a>';
	}

	return sprintf( _x( 'blocked by %s at %s', 'e.g. blocked by John at 11:00', 'wp-cerber' ), $who, cerber_date( $meta['blocked_time'] ) );
}

/**
 * @return bool
 *
 * @since 8.8
 *
 */
function crb_is_user_logged_in() {

	crb_load_dependencies( 'is_user_logged_in', true );

	return is_user_logged_in();
}

/**
 * Returns user session token.
 *
 * OMG: WordPress stores the same token in two different cookies.
 *
 * @return string
 *
 * @since 8.9.1
 */
function crb_get_session_token() {

	// First, trying the default cookie:  LOGGED_IN_COOKIE
	if ( ! $token = wp_get_session_token() ) {
		// Trying another, backup cookie: SECURE_AUTH_COOKIE or AUTH_COOKIE
		$cookie = wp_parse_auth_cookie();
		$token = crb_array_get( $cookie, 'token', '' );
	}

	return $token;
}

/**
 * Checks role-based user limits
 *
 * @param $user_id
 *
 * @return false|string Returns false if no restrictions, an error message otherwise.
 */
function crb_check_user_limits( $user_id ) {
	if ( ! $user_id ) {
		return false;
	}

	// Sessions

	if ( ! $limit = absint( cerber_get_user_policy( 'sess_limit', $user_id ) ) ) {
		return false;
	}

	$list = cerber_db_get_results( 'SELECT started, wp_session_token FROM ' . cerber_get_db_prefix() . CERBER_USS_TABLE . ' WHERE user_id = ' . absint( $user_id ) );
	if ( $list && ( count( $list ) >= $limit ) ) {
		if ( cerber_get_user_policy( 'sess_limit_policy', $user_id ) ) {
			if ( $msg = cerber_get_user_policy( 'sess_limit_msg', $user_id ) ) {
				return $msg;
			}

			return get_wp_cerber()->getErrorMsg();
		}
		else {
			$started = array_column( $list, 'started' );
			array_multisort( $started, SORT_ASC, SORT_NUMERIC, $list );
			CRB_Globals::$session_status = 38;
			crb_sessions_kill( $list[0]['wp_session_token'], $user_id, false );
		}
	}

	return false;
}

/**
 * Return the last element in the path of the requested URI.
 *
 * @return bool|string
 */
function cerber_last_uri() {
	static $ret;

	if ( isset( $ret ) ) {
		return $ret;
	}

	$ret = strtolower( $_SERVER['REQUEST_URI'] );

	if ( $pos = strpos( $ret, '?' ) ) {
		$ret = substr( $ret, 0, $pos );
	}

	if ( $pos = strpos( $ret, '#' ) ) {
		$ret = substr( $ret, 0, $pos ); // @since 8.1 - malformed request URI
	}

	$ret = rtrim( $ret, '/' );
	$ret = substr( strrchr( $ret, '/' ), 1 );

	return $ret;
}

/**
 * Return the name of an executable script in the requested URI if it's present
 *
 * @return bool|string The script name or false if executable script is not detected
 */
function cerber_get_uri_script() {
	static $ret;

	if ( isset( $ret ) ) {
		return $ret;
	}

	$last = cerber_last_uri();
	if ( cerber_detect_exec_extension( $last ) ) {
		$ret = $last;
	}
	else {
		$ret = false;
	}

	return $ret;
}

/**
 * Detects an executable extension in a filename.
 * Supports double and N fake extensions.
 *
 * @param $line string Filename
 * @param array $extra A list of additional extensions to detect
 *
 * @return bool|string An extension if it's found, false otherwise
 */
function cerber_detect_exec_extension( $line, $extra = array() ) {
	static $executable = array( 'php', 'phtm', 'phtml', 'phps', 'shtm', 'shtml', 'jsp', 'asp', 'aspx', 'axd', 'exe', 'com', 'cgi', 'pl', 'py', 'pyc', 'pyo' );
	static $not_exec = array( 'jpg', 'png', 'svg', 'css', 'txt' );

	if ( empty( $line ) || ! strrpos( $line, '.' ) ) {
		return false;
	}

	if ( $extra ) {
		$ex_list = array_merge( $executable, $extra );
	}
	else {
		$ex_list = $executable;
	}

	$line = trim( $line );
	$line = trim( $line, '/' );

	$parts = explode( '.', $line );
	array_shift( $parts );

	// First and last are critical for most server environments
	$first_ext = array_shift( $parts );
	$last_ext = array_pop( $parts );

	if ( $first_ext ) {
		$first_ext = strtolower( $first_ext );
		if ( ! in_array( $first_ext, $not_exec ) ) {
			if ( in_array( $first_ext, $ex_list ) ) {
				return $first_ext;
			}
			if ( preg_match( '/php\d+/', $first_ext ) ) {
				return $first_ext;
			}
		}
	}

	if ( $last_ext ) {
		$last_ext = strtolower( $last_ext );
		if ( in_array( $last_ext, $ex_list ) ) {
			return $last_ext;
		}
		if ( preg_match( '/php\d+/', $last_ext ) ) {
			return $last_ext;
		}
	}

	return false;
}

/**
 * Remove extra slashes \ / from a script file name
 *
 * @return string|bool
 */
function cerber_script_filename() {
	return preg_replace( '/[\/\\\\]+/', '/', $_SERVER['SCRIPT_FILENAME'] ); // Windows server
}

function cerber_script_exists( $uri ) {
	$script_filename = cerber_script_filename();
	if ( is_multisite() && ! is_subdomain_install() ) {
		$path = explode( '/', $uri );
		if ( count( $path ) > 1 ) {
			$last = array_pop( $path );
			$virtual_sub_folder = array_pop( $path );
			$uri = implode( '/', $path ) . '/' . $last;
		}
	}
	if ( false === strrpos( $script_filename, $uri ) ) {
		return false;
	}

	return true;
}

/**
 * Activity labels and statues
 *
 * @param string $type
 * @param int $id
 *
 * @return array|string
 */
function cerber_get_labels( $type = 'activity', $id = 0 ) {

	if ( ! $labels = cerber_cache_get( 'labels' ) ) {

		// Initialize it

		$labels = array(
			'activity'    => array(),
			'activity_by' => array(),
			'status'      => array(),
		);

		$act = &$labels['activity'];
		$act_by = &$labels['activity_by'];

		// User actions
		$act[1] = __( 'User created', 'wp-cerber' );
		/* translators: Here %s is the name of a website administrator who created the user. */
		$act_by[1] = __( 'User created by %s', 'wp-cerber' );
		$act[2] = __( 'User registered', 'wp-cerber' );
		$act[3] = __( 'User deleted', 'wp-cerber' );
		/* translators: Here %s is the name of a website administrator who deleted the user. */
		$act_by[3] = __( 'User deleted by %s', 'wp-cerber' );
		$act[ CRB_EV_LIN ] = __( 'Logged in', 'wp-cerber' );
		$act[6] = __( 'Logged out', 'wp-cerber' );
		$act[ CRB_EV_LFL ] = __( 'Login failed', 'wp-cerber' );

		// Cerber actions - IP specific - lockouts
		$act[10] = __( 'IP blocked', 'wp-cerber' );
		$act[11] = __( 'IP subnet blocked', 'wp-cerber' );

		// WP Cerber's actions - denied
		$act[12] = __( 'Citadel activated!', 'wp-cerber' );
		$act[16] = __( 'Spam comment denied', 'wp-cerber' );
		$act[17] = __( 'Spam form submission denied', 'wp-cerber' );
		$act[18] = __( 'Form submission denied', 'wp-cerber' );
		$act[19] = __( 'Comment denied', 'wp-cerber' );

		// Not in use anymore. Moved to status labels.
		//$act[13]=__('Locked out','wp-cerber');
		//$act[14]=__('IP blacklisted','wp-cerber');
		//$act[15]=__('Malicious activity detected','wp-cerber');
		// --------------------------------------------------------------

		// Other events
		$act[20] = __( 'Password changed', 'wp-cerber' );
		/* translators: Here %s is the name of a website administrator who changed the password. */
		$act_by[20] = __( 'Password changed by %s', 'wp-cerber' );
		$act[ CRB_EV_PRS ] = __( 'Password reset requested', 'wp-cerber' );
		$act[ CRB_EV_UST ] = __( 'User session terminated', 'wp-cerber' );
		/* translators: Here %s is the name of a website administrator who terminated the session. */
		$act_by[ CRB_EV_UST ] = __( 'User session terminated by %s', 'wp-cerber' );

		$act[ CRB_EV_PRD ] = __( 'Password reset request denied', 'wp-cerber' );

		// Not in use and replaced by statuses 532 - 534 since 8.9.4.
		$act[40] = __( 'reCAPTCHA verification failed', 'wp-cerber' );
		$act[41] = __( 'reCAPTCHA settings are incorrect', 'wp-cerber' );
		$act[42] = __( 'Request to the Google reCAPTCHA service failed', 'wp-cerber' );
		// --------------------------

		$act[CRB_EV_PUR] = __( 'Attempt to access prohibited URL', 'wp-cerber' );
		$act[51] = __( 'Attempt to log in with non-existing username', 'wp-cerber' );
		$act[52] = __( 'Attempt to log in with prohibited username', 'wp-cerber' );

		// WP Cerber's actions - denied
		$act[ CRB_EV_LDN ] = __( 'Attempt to log in denied', 'wp-cerber' );
		$act[54] = __( 'Attempt to register denied', 'wp-cerber' );
		$act[55] = __( 'Probing for vulnerable code', 'wp-cerber' );
		$act[56] = __( 'Attempt to upload malicious file denied', 'wp-cerber' );
		$act[57] = __( 'File upload denied', 'wp-cerber' );

		$act[70] = __( 'Request to REST API denied', 'wp-cerber' );
		$act[71] = __( 'Request to XML-RPC API denied', 'wp-cerber' );

		$act[72] = __( 'User creation denied', 'wp-cerber' );
		$act[73] = __( 'User row update denied', 'wp-cerber' );
		$act[74] = __( 'Role update denied', 'wp-cerber' );
		$act[75] = __( 'Setting update denied', 'wp-cerber' );
		$act[76] = __( 'User metadata update denied', 'wp-cerber' );

		$act[100] = __( 'Malicious request denied', 'wp-cerber' );

		// APIs
		$act[149] = __( 'User application password updated', 'wp-cerber' );
		$act[150] = __( 'User application password created', 'wp-cerber' );
		/* translators: Here %s is the name of a website administrator who created the password. */
		$act_by[150] = __( 'User application password created by %s', 'wp-cerber' );
		$act[151] = __( 'API request authorized', 'wp-cerber' );
		$act[152] = __( 'API request authorization failed', 'wp-cerber' );
		$act[153] = __( 'User application password deleted', 'wp-cerber' );
		/* translators: Here %s is the name of a website administrator who deleted the password. */
		$act_by[153] = __( 'User application password deleted by %s', 'wp-cerber' );

		// BuddyPress
		$act[200] = __( 'User activated', 'wp-cerber' );

		// Nexus (managed website)
		$act[300] = __( 'Invalid master credentials', 'wp-cerber' );

		$act[400] = 'Two-factor authentication enforced';

		// Statuses

		$sts = &$labels['status'];

		$sts[10] = __( 'Denied', 'wp-cerber' ); // @since 8.9.5.6
		$sts[ CRB_STS_11 ] = __( 'Bot detected', 'wp-cerber' );
		$sts[12] = __( 'Citadel mode is active', 'wp-cerber' );
		$sts[13] = __( 'IP address is locked out', 'wp-cerber' );
		$sts[14] = __( 'IP blacklisted', 'wp-cerber' );
		$sts[15] = __( 'Malicious activity detected', 'wp-cerber' );
		$sts[16] = __( 'Blocked by country rule', 'wp-cerber' );
		$sts[17] = __( 'Limit reached', 'wp-cerber' );
		$sts[18] = __( 'Multiple suspicious activities', 'wp-cerber' );
		$sts[19] = __( 'Denied', 'wp-cerber' ); // @since 6.7.5

		$sts[20] = __( 'Suspicious number of fields', 'wp-cerber' );
		$sts[21] = __( 'Suspicious number of nested values', 'wp-cerber' );
		$sts[22] = __( 'Malicious code detected', 'wp-cerber' );
		$sts[23] = __( 'Suspicious SQL code detected', 'wp-cerber' );
		$sts[24] = __( 'Suspicious JavaScript code detected', 'wp-cerber' );
		$sts[ CRB_STS_25 ] = __( 'Blocked by administrator', 'wp-cerber' );
		$sts[26] = __( 'Site policy enforcement', 'wp-cerber' );
		$sts[27] = __( '2FA code verified', 'wp-cerber' );
		$sts[28] = __( 'Initiated by the user', 'wp-cerber' );
		$sts[ CRB_STS_29 ] = __( 'User blocked by administrator', 'wp-cerber' );
		$sts[ CRB_STS_30 ] = __( 'Username is prohibited', 'wp-cerber' );
		$sts[31] = __( 'Email address is prohibited', 'wp-cerber' );
		$sts[32] = 'User role is prohibited';
		$sts[33] = __( 'Permission denied', 'wp-cerber' );
		$sts[34] = 'Unauthorized access denied';
		$sts[35] = __( 'Invalid user', 'wp-cerber' );
		$sts[36] = __( 'Incorrect password', 'wp-cerber' );
		$sts[37] = __( 'IP address is not allowed', 'wp-cerber' );
		$sts[38] = __( 'Limit on concurrent user sessions', 'wp-cerber' );
		$sts[39] = __( 'Invalid cookies', 'wp-cerber' );
		$sts[40] = __( 'Invalid cookies cleared', 'wp-cerber' );
		$sts[50] = __( 'Forbidden URL', 'wp-cerber' );
		$sts[ CRB_STS_51 ] = __( 'Executable file extension detected', 'wp-cerber' );
		$sts[ CRB_STS_52 ] = __( 'Filename is prohibited', 'wp-cerber' );

		// @since 8.6.4
		$sts[500] = __( 'IP whitelisted', 'wp-cerber' );
		$sts[501] = 'URL whitelisted';
		$sts[502] = 'Query whitelisted';
		$sts[503] = 'Namespace whitelisted';

		// IP is in the whitelist, but "use whitelist" setting is not enabled
		$sts[510] = $sts[500]; // TI
		$sts[511] = $sts[500]; // DS
		$sts[512] = $sts[500]; // DS

		// @since 8.9.4
		$sts[530] = __( 'Logged out everywhere', 'wp-cerber' );

		$sts[531] = __( 'reCAPTCHA verified', 'wp-cerber' );
		$sts[ CRB_STS_532 ] = __( 'reCAPTCHA verification failed', 'wp-cerber' );
		$sts[533] = __( 'reCAPTCHA settings are incorrect', 'wp-cerber' );
		$sts[534] = __( 'Request to the Google reCAPTCHA service failed', 'wp-cerber' );

		// @since 9.3.2
		$sts[540] = __( "User's IP address does not match the one used to log in", 'wp-cerber' );
		$sts[541] = __( "User's browser does not match the one used to log in", 'wp-cerber' );
		$sts[542] = __( 'Exceeded the limit on the number of attempts to enter 2FA code', 'wp-cerber' );

		// Note: 7xx is in use in cerber_get_reason()

		cerber_cache_set( 'labels', $labels );
	}

	if ( $id ) {

		if ( isset( $labels[ $type ][ $id ] ) ) {
			return $labels[ $type ][ $id ];
		}

		return __( 'Unknown label', 'wp-cerber' ) . ' (' . absint( $id ) . '/' . $type . ')';
	}

	return $labels[ $type ];
}

/**
 * Returns a label to be displayed in the logs
 *
 * @param int $activity
 * @param int $user
 * @param int $by_user
 * @param bool $link
 *
 * @return string
 *
 * @since 8.9.5.1
 */
function crb_get_label( $activity, $user = null, $by_user = null, $link = true ) {
	static $user_link = array();

	if ( $by_user
	     && $user != $by_user
	     && $user_data = crb_get_userdata( $by_user ) ) {

		if ( $link ) {
			if ( empty( $user_link[ $by_user ] ) ) {
				$user_link[ $by_user ] = get_edit_user_link( $by_user );
			}
			$user_name = '<a href="' . $user_link[ $by_user ] . '">' . $user_data->display_name . '</a>';
		}
		else {
			$user_name = $user_data->display_name;
		}

		return sprintf( cerber_get_labels( 'activity_by', $activity ), $user_name );
	}

	return cerber_get_labels( 'activity', $activity );
}

function crb_get_filter_set( $set_id ) {
	static $list = array( 1 => 'suspicious', 2 => 'login_issues' );

	if ( ! isset( $list[ $set_id ] ) ) {
		return array();
	}

	return crb_get_activity_set( $list[ $set_id ] );

}

function crb_get_activity_set( $slice = 'malicious', $implode = false ) {

	$ret = array();

	switch ( $slice ) {
		case 'malicious':
			$ret = array( 16, 17, CRB_EV_PRD, 40, 50, 51, 52, CRB_EV_LDN, 54, 55, 56, 100 );
			break;
		case 'black': // Like 'malicious' but will cause an IP lockout when hit the limit
			$ret = array( 16, 17, 40, 50, 51, 52, CRB_EV_LDN, 55, 56, 100, 300 );
			break;
		case 'suspicious': // Uses when an admin inspects logs with filter_set = 1
			$ret = array( 10, 11, 16, 17, CRB_EV_PRD, 40, 50, 51, 52, CRB_EV_LDN, 54, 55, 56, 57, 100, 70, 71, 72, 73, 74, 75, 76, 300 );
			break;
		case 'dashboard': // Important events for the plugin dashboard
			$ret = array( 1, 2, 3, CRB_EV_LIN, 12, 16, 17, 18, 19, CRB_EV_UST, 40, 41, 42, 50, 51, 52, CRB_EV_LDN, 54, 55, 56, 57, 72, 73, 74, 75, 76, 100, 149, 150, 200, 300, 400 );
			break;
		case 'login_issues':
			$ret = array( CRB_EV_LFL, CRB_EV_PRS, CRB_EV_PRD, 51, 52, CRB_EV_LDN, 152 );
			break;
		case 'blocked': // IP or subnet was blocked
			$ret = array( 10, 11 );
	}

	if ( $implode ) {
		return implode( ',', $ret );
	}

	return $ret;
}


function cerber_get_reason( $reason_id = null ) {

	if ( ! $labels = cerber_cache_get( 'reasons' ) ) {

		$labels = array();
		$labels[701] = __( 'Limit on login attempts is reached', 'wp-cerber' );
		$labels[702] = __( 'Attempt to access', 'wp-cerber' );
		$labels[702] = __( 'Attempt to access prohibited URL', 'wp-cerber' );
		$labels[703] = __( 'Attempt to log in with non-existing username', 'wp-cerber' );
		$labels[704] = __( 'Attempt to log in with prohibited username', 'wp-cerber' );
		$labels[705] = __( 'Limit on failed reCAPTCHA verifications is reached', 'wp-cerber' );
		$labels[706] = __( 'Bot activity is detected', 'wp-cerber' );
		$labels[707] = __( 'Multiple suspicious activities were detected', 'wp-cerber' );
		$labels[708] = __( 'Probing for vulnerable code', 'wp-cerber' );
		$labels[709] = __( 'Malicious code detected', 'wp-cerber' );
		$labels[710] = __( 'Attempt to upload a file with malicious code', 'wp-cerber' );

		$labels[711] = __( 'Multiple erroneous requests', 'wp-cerber' );
		$labels[712] = __( 'Multiple suspicious requests', 'wp-cerber' );
		//$labels[721] = 'Limit on 2FA verifications has reached';
		$labels[721] = __( 'Exceeded the limit on the number of attempts to enter 2FA code', 'wp-cerber' );

		cerber_cache_set( 'reasons', $labels );

	}

	if ( $reason_id ) {
		if ( isset( $labels[ $reason_id ] ) ) {
			return $labels[ $reason_id ];
		}

		return __( 'Unknown', 'wp-cerber' );
	}

	return $labels;

}

function cerber_db_error_log( $errors = array() ) {
	global $wpdb;

	if ( ! $errors ) {
		$errors = array();

		if ( ! empty( $wpdb->last_error ) ) {
			$errors = array( array( $wpdb->last_error, $wpdb->last_query, microtime( true ) ) );
		}

		if ( $others = cerber_db_get_errors( true, false ) ) {
			$errors = array_merge( $errors, $others );
		}
	}

	if ( ! $errors ) {
		return;
	}

	if ( ! $old = get_site_option( '_cerber_db_errors' ) ) {
		$old = array();
	}

	update_site_option( '_cerber_db_errors', array_merge( $old, $errors ) );
}

/**
 * Add red admin error message(s) to be displayed in the dashboard
 *
 * @param string|array $msg
 */
function cerber_admin_notice( $msg ) {
	crb_admin_add_msg( $msg, false, 'admin_notice' );
}

/**
 * Add green admin notification message(s)
 *
 * @param string|string[] $msg
 * @param bool $prepend If true, the given message(s) will be shown above existing ones
 *
 * @return void
 */
function cerber_admin_message( $msg, $prepend = false ) {
	crb_admin_add_msg( $msg, $prepend );
}

/**
 * Saves admin messages to be displayed in the WP Cerber dashboard
 *
 * @param string|string[] $messages
 * @param bool $prepend If true, the given message(s) will be shown above existing ones
 * @param string $type 'admin_message' | 'admin_notice'
 *
 * @return void
 */
function crb_admin_add_msg( $messages, $prepend = false, $type = 'admin_message' ) {

	if ( ! $messages || CRB_Globals::$doing_upgrade ) {
		return;
	}

	if ( ! is_array( $messages ) ) {
		$messages = array( $messages );
	}

	$set = cerber_get_set( $type );

	if ( ! $set || ! is_array( $set ) ) {
		$set = array();
	}

	if ( $set ) { // Preventing duplicate messages

		foreach ( $messages as $key => $str_1 ) {
			foreach ( $set as $str_2 ) {
				if ( sha1( $str_1 ) == sha1( $str_2 ) ) {
					unset( $messages[ $key ] );
				}
			}
		}

		if ( ! $messages ) {
			return;
		}
	}

	if ( $prepend ) {
		foreach ( $messages as $item ) {
			array_unshift( $set, $item );
		}
	}
	else {
		$set = array_merge( $set, $messages );
	}

	cerber_update_set( $type, $set );
}

/**
 * @param string $id
 *
 * @return void
 *
 * @since 9.5.1
 */
function crb_clear_admin_notice( $id = '' ) {
	$set = cerber_get_set( 'admin_notice' );

	if ( isset( $set[ $id ] ) ) {
		unset( $set[ $id ] );
		cerber_update_set( 'admin_notice', $set );
	}
}

function crb_clear_admin_msg() {
	cerber_update_set( 'admin_notice', array() );
	cerber_update_set( 'admin_message', array() );
	cerber_update_set( 'cerber_admin_wide', '' );
}

/**
 *
 * Check if the currently displayed page is a WP Cerber admin dashboard page.
 * Optionally checks a set of GET params.
 *
 * @param array $params Optional GET params to check
 * @param string $screen_base Optional WP admin screen
 *
 * @return bool True on a WP Cerber page
 */
function cerber_is_admin_page( $params = array(), $screen_base = '' ) {

	if ( ! is_admin()
	     && ! nexus_is_valid_request() ) {
		return false;
	}

	$get = crb_get_query_params();
	$ret = false;

	if ( isset( $get['page'] ) && false !== strpos( $get['page'], 'cerber-' ) ) {
		$ret = true;
		if ( $params ) {
			foreach ( $params as $param => $value ) {
				if ( ! isset( $get[ $param ] ) ) {
					$ret = false;
					break;
				}
				if ( ! is_array( $value ) ) {
					if ( $get[ $param ] != $value ) {
						$ret = false;
						break;
					}
				}
				elseif ( ! in_array( $get[ $param ], $value ) ) {
					$ret = false;
					break;
				}
			}
		}
	}
	if ( $ret || ! $screen_base ) {
		return $ret;
	}

	if ( ! function_exists( 'get_current_screen' ) || ! $screen = get_current_screen() ) {
		return false;
	}

	if ( $screen->base == $screen_base ) {
		return true;
	}

	/*
	if ($screen->parent_base == 'options-general') return true;
	if ($screen->parent_base == 'settings') return true;
	*/

	return false;
}

/**
 * Return human readable "ago" time
 *
 * @param $time integer Unix timestamp - time of an event
 *
 * @return string
 */
function cerber_ago_time( $time ) {
	$diff = abs( time() - (int) $time );
	if ( $diff < MINUTE_IN_SECONDS ) {
		$secs = ( $diff <= 1 ) ? 1 : $diff;
		/* translators: Time difference between two dates, in seconds. %s: number of seconds. */
		$dt = sprintf( _n( '%s sec', '%s secs', $secs, 'wp-cerber' ), $secs );
	}
	else {
		$dt = human_time_diff( $time );
	}

	// _x( 'at', 'preposition of time',
	return ( $time <= time() ) ? sprintf( __( '%s ago' ), $dt ) : sprintf( _x( 'in %s', 'Preposition of a period of time like: in 6 hours', 'wp-cerber' ), $dt );
}

function cerber_auto_date( $time, $purify = true ) {
	if ( ! $time ) {
		return __( 'Never', 'wp-cerber' );
	}

	return $time < ( time() - DAY_IN_SECONDS ) ? cerber_date( $time, $purify ) : cerber_ago_time( $time );
}

/**
 * Format date according to user settings and timezone
 *
 * @param $timestamp int Unix timestamp
 * @param $purify boolean If true adds html to have a better look on a web page
 *
 * @return string
 */
function cerber_date( $timestamp, $purify = true ) {
	static $gmt_offset;

	if ( $gmt_offset === null ) {
		$gmt_offset = get_option( 'gmt_offset' ) * 3600;
	}

	$timestamp = $gmt_offset + absint( $timestamp );

	// @since 8.6.4: snippet is taken from new date_i18n()
	if ( function_exists( 'wp_date' ) ) { // wp_date() introduced in WP 5.3
		$local_time = gmdate( 'Y-m-d H:i:s', $timestamp );
		$timezone = wp_timezone();
		$datetime = date_create( $local_time, $timezone );
		$date = wp_date( cerber_get_dt_format(), $datetime->getTimestamp(), $timezone );
	}
	else { // Older WP
		$date = date_i18n( cerber_get_dt_format(), $timestamp );
	}

	if ( $purify ) {
		$date = str_replace( array( ',', ' am', ' pm', ' AM', ' PM' ), array( ',<wbr>', '&nbsp;am', '&nbsp;pm', '&nbsp;AM', '&nbsp;PM' ), $date );
	}

	return $date;
}

function cerber_get_dt_format() {
	static $ret;

	if ( $ret !== null ) {
		return $ret;
	}

	if ( $ret = crb_get_settings( 'dateformat' ) ) {
		return $ret;
	}

	$ret = crb_get_default_dt_format();

	return $ret;

}

function crb_get_default_dt_format() {
	$tf = get_option( 'time_format' );
	$df = get_option( 'date_format' );

	return $df . ', ' . $tf;
}

function cerber_is_ampm() {
	if ( 'a' == strtolower( substr( trim( get_option( 'time_format' ) ), - 1 ) ) ) {
		return true;
	}

	return false;
}

function cerber_sec_from_time( $time ) {
	list( $h, $m ) = explode( ':', trim( $time ) );
	$h = absint( $h );
	$m = absint( $m );
	$ret = $h * 3600 + $m * 60;

	if ( strpos( strtolower( $time ), 'pm' ) ) {
		$ret += 12 * 3600;
	}

	return $ret;
}

function cerber_percent( $one, $two ) {
	if ( $one == 0 ) {
		if ( $two > 0 ) {
			$ret = '100';
		}
		else {
			$ret = '0';
		}
	}
	else {
		$ret = round( ( ( ( $two - $one ) / $one ) ) * 100 );
	}
	$style = '';
	if ( $ret < 0 ) {
		$style = 'color:#008000';
	}
	elseif ( $ret > 0 ) {
		$style = 'color:#FF0000';
	}
	if ( $ret > 0 ) {
		$ret = '+' . $ret;
	}

	return '<span style="' . $style . '">' . $ret . ' %</span>';
}

function crb_size_format( $fsize ) {
	$fsize = absint( $fsize );

	return ( $fsize < 1024 ) ? $fsize . '&nbsp;' . __( 'Bytes', 'wp-cerber' ) : size_format( $fsize );
}

/**
 * Return a user by login or email with automatic detection
 *
 * @param $login_email string login or email
 *
 * @return false|WP_User
 */
function cerber_get_user( $login_email ) {
	if ( is_email( $login_email ) ) {
		return get_user_by( 'email', $login_email );
	}

	return get_user_by( 'login', $login_email );
}

/**
 * @param int $user_id
 *
 * @return false|WP_User
 *
 * @since 8.9.5.1
 */
function crb_get_userdata( $user_id ) {
	static $users;

	if ( ! isset( $users[ $user_id ] ) ) {
		$users[ $user_id ] = get_user_by( 'id', $user_id );
	}

	return $users[ $user_id ];
}

/**
 * Check if a DB table exists
 *
 * @param $table
 *
 * @return bool true if table exists in the DB
 */
function cerber_is_table( $table ) {
	global $wpdb;
	if ( ! $wpdb->get_row( "SHOW TABLES LIKE '" . $table . "'" ) ) {
		return false;
	}

	return true;
}

/**
 * Check if a column is defined in a table
 *
 * @param $table string DB table name
 * @param $column string Field name
 *
 * @return bool true if field exists in a table
 */
function cerber_is_column( $table, $column ) {

	$table = preg_replace( '/[^\w\-]/', '', $table );
	$column = preg_replace( '/[^\w\-]/', '', $column );

	if ( cerber_db_get_results( 'SHOW FIELDS FROM ' . $table . ' WHERE FIELD = "' . $column . '"' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if a table has an index
 *
 * @param $table string DB table name
 * @param $key string Index name
 *
 * @return bool true if an index defined for a table
 */
function cerber_is_index( $table, $key ) {

	$table = preg_replace( '/[^\w\-]/', '', $table );
	$key = preg_replace( '/[^\w\-]/', '', $key );

	if ( cerber_db_get_results( 'SHOW INDEX FROM ' . $table . ' WHERE KEY_NAME = "' . $key . '"' ) ) {
		return true;
	}

	return false;
}

/**
 * Return reCAPTCHA language code for reCAPTCHA widget
 *
 * @return string
 */
function cerber_recaptcha_lang() {
	static $lang = '';
	if ( ! $lang ) {
		$lang = crb_get_bloginfo( 'language' );
		//$trans = array('en-US' => 'en', 'de-DE' => 'de');
		//if (isset($trans[$lang])) $lang = $trans[$lang];
		$lang = substr( $lang, 0, 2 );
	}

	return $lang;
}

/**
 * Checks for a new version of WP Cerber and generates messages if needed
 *
 * @param bool $check_only if true, no messages are generated / sent
 *
 * @return string A new version, empty string otherwise
 *
 */
function cerber_check_new_version( $check_only = true ) {
	if ( ! $updates = get_site_transient( 'update_plugins' ) ) {
		return '';
	}

	$result = false;
	$new_ver = '';
	$key = CERBER_PLUGIN_ID;

	if ( isset( $updates->checked[ $key ] )
	     && isset( $updates->response[ $key ] ) ) {

		$old_ver = $updates->checked[ $key ];
		$new_ver = crb_boring_escape( $updates->response[ $key ]->new_version );

		if ( 1 === version_compare( $new_ver, $old_ver ) ) {
			$result = true;
		}
		else {
			$new_ver = '';
		}
	}

	if ( $check_only || ! $result ) {
		return $new_ver;
	}

	$link = 'https://wpcerber.com/?plugin_version=' . $new_ver;
	$msg_one = __( 'A new version of WP Cerber Security is available. It is strongly advised to install it.', 'wp-cerber' );
	$msg_two = __( 'Know more details about this version here:', 'wp-cerber' );

	if ( ! cerber_get_set( '_cerber_message_new', null, false ) ) {
		// FIX stacking messages
		cerber_admin_message( array( $msg_one, $msg_two . ' ' . '<a href="' . $link . '" target="_blank">' . $link . '</a>' ) );
		cerber_update_set( '_cerber_message_new', 1, null, false, time() + HOUR_IN_SECONDS );
	}

	if ( ! crb_get_settings( 'notify-new-ver' ) ) {
		return $new_ver;
	}

	$history = get_site_option( '_cerber_notify_new' );

	if ( ! $history || ! is_array( $history ) ) {
		$history = array();
	}

	if ( in_array( $new_ver, $history ) ) {
		return $new_ver;
	}

	cerber_send_message( 'new_version', array(
		'subj' => sprintf( __( 'WP Cerber %s is available. Please update.', 'wp-cerber' ), $new_ver ),
		'text' => array( $msg_one, $msg_two . ' ' . $link )
	) );

	$history[] = $new_ver;
	update_site_option( '_cerber_notify_new', $history );

	return $new_ver;
}

/**
 * Is user agent string indicates bot (crawler)
 *
 * @param $ua
 *
 * @return integer 1 if ua string contains a bot definition, 0 otherwise
 * @since 6.0
 */
function cerber_is_crawler( $ua ) {
	if ( ! $ua ) {
		return 0;
	}
	$ua = strtolower( $ua );
	if ( preg_match( '/\(compatible\;(.+)\)/', $ua, $matches ) ) {
		$bot_info = explode( ';', $matches[1] );
		foreach ( $bot_info as $item ) {
			if ( strpos( $item, 'bot' )
			     || strpos( $item, 'crawler' )
			     || strpos( $item, 'spider' )
			     || strpos( $item, 'Yahoo! Slurp' )
			) {
				return 1;
			}
		}
	}
	elseif ( 0 === strpos( $ua, 'Wget/' ) ) {
		return 1;
	}

	return 0;
}

/**
 * Natively escape a string for use in an SQL statement
 * The reason: https://make.wordpress.org/core/2017/10/31/changed-behaviour-of-esc_sql-in-wordpress-4-8-3/
 *
 * @param string $str
 *
 * @return string
 * @since 6.0
 */
function cerber_real_escape( $str ) {

	$str = (string) $str;

	if ( empty( $str ) ) {
		if ( $str === '0' ) {
			return '0';
		}

		return '';
	}

	if ( $db = cerber_get_db() ) {
		return mysqli_real_escape_string( $db->dbh, $str );
	}

	return '';
}

/**
 * @param bool $erase
 * @param bool $flat If true returns an array of error messages, otherwise a multidimensional array
 *
 * @return array
 */
function cerber_db_get_errors( $erase = false, $flat = true ) {

	if ( ! isset( CRB_Globals::$db_errors ) ) {
		CRB_Globals::$db_errors = array();
	}

	$ret = CRB_Globals::$db_errors;

	if ( $erase ) {
		CRB_Globals::$db_errors = array();
	}

	if ( $ret && $flat ) {
		$ret = array_map( function ( $e ) {
			if ( is_array( $e ) ) {
				return implode( ' ', $e );
			}

			return $e;
		}, $ret );
	}

	return $ret;
}

/**
 * Execute a direct SQL query on the website database
 *
 * The reason: https://make.wordpress.org/core/2017/10/31/changed-behaviour-of-esc_sql-in-wordpress-4-8-3/
 *
 * @param $query string An SQL query
 * @param int $ignore_error A MySQL error code to ignore (e.g. 1062 Duplicate entry)
 *
 * @return bool|mysqli_result
 *
 * @since 6.0
 */
function cerber_db_query( $query, $ignore_error = null ) {
	global $wpdb;

	if ( ! $db = cerber_get_db() ) {

		CRB_Globals::$db_errors[] = 'No database connection. Query failed: ' . $query;

		return false;
	}

	if ( defined( 'CRB_SAVEQUERIES' ) && CRB_SAVEQUERIES ) {
		$started = microtime( true );
	}

	$error_msg = '';

	//$ret = mysqli_query( $db->dbh, $query, MYSQLI_USE_RESULT );
	if ( ! $ret = mysqli_query( $db->dbh, $query ) ) {
		$error_code = mysqli_errno( $db->dbh );
		$error_msg = mysqli_error( $db->dbh );
		if ( $error_msg && $error_code && $error_code != $ignore_error ) {
			CRB_Globals::$db_errors[] = array( 'ERROR ' . $error_code . ': ' . $error_msg, $query, microtime( true ) );
		}
	}

	// cerber_check_groove()
	if ( defined( 'CRB_SAVEQUERIES' ) && CRB_SAVEQUERIES && is_object( $wpdb ) ) {
		$elapsed = microtime( true ) - $started;

		$backtrace = '';
		if ( function_exists( 'wp_debug_backtrace_summary' ) ) {
			$backtrace = wp_debug_backtrace_summary();
		}

		$stat = array( $query, $elapsed, $backtrace, $started, array( $error_msg ) );
		CRB_Globals::$db_requests[] = $stat;

		$wpdb->queries[] = $stat;
	}

	return $ret;
}

/**
 * Returns the number of affected rows in a previous MySQL query via cerber_db_query()
 *
 * @return int
 *
 * @since 9.0.2
 */
function cerber_db_get_affected() {
	$aff = 0;

	if ( $db = cerber_get_db() ) {
		$aff = $db->dbh->affected_rows;
		if ( $aff < 0 ) { // DB Error
			$aff = 0;
		}
	}

	return  $aff;
}

function cerber_db_get_results( $query, $type = MYSQLI_ASSOC ) {

	if ( ! $result = cerber_db_query( $query ) ) {
		return array();
	}

	$ret = array();

	switch ( $type ) {
		case MYSQLI_ASSOC:
			while ( $row = mysqli_fetch_assoc( $result ) ) {
				$ret[] = $row;
			}
			//$ret = mysqli_fetch_all( $result, $type ); // Requires mysqlnd driver
			break;
		case MYSQL_FETCH_OBJECT:
			while ( $row = mysqli_fetch_object( $result ) ) {
				$ret[] = $row;
			}
			break;
		case MYSQL_FETCH_OBJECT_K:
			while ( $row = mysqli_fetch_object( $result ) ) {
				$vars = get_object_vars( $row );
				$key = array_shift( $vars );
				$ret[ $key ] = $row;
			}
			break;
		default:
			while ( $row = mysqli_fetch_row( $result ) ) {
				$ret[] = $row;
			}
	}

	mysqli_free_result( $result );

	return $ret;
}

/**
 * @param string $query
 * @param int $type
 *
 * @return false|array|object
 */
function cerber_db_get_row( $query, $type = MYSQLI_ASSOC ) {

	if ( ! $result = cerber_db_query( $query ) ) {
		return false;
	}

	if ( $type == MYSQL_FETCH_OBJECT ) {
		$ret = $result->fetch_object();
	}
	else {
		$ret = $result->fetch_array( $type );
	}

	$result->free();

	return $ret;
}

/**
 * @param string $query
 *
 * @return array
 */
function cerber_db_get_col( $query ) {

	if ( ! $result = cerber_db_query( $query ) ) {
		return array();
	}

	$ret = array();

	while ( $row = $result->fetch_row() ) {
		$ret[] = $row[0];
	}

	$result->free();

	return $ret;
}

/**
 * @param string $query
 *
 * @return bool|mixed
 */
function cerber_db_get_var( $query ) {

	if ( ! $result = cerber_db_query( $query ) ) {
		return false;
	}

	//$r = $result->fetch_row();
	$r = mysqli_fetch_row( $result );
	$result->free();

	if ( $r ) {
		return $r[0];
	}

	return false;
}

/**
 * @param string $table
 * @param array $values
 *
 * @return bool|mysqli_result
 */
function cerber_db_insert( $table, $values ) {
	return cerber_db_query( 'INSERT INTO ' . $table . ' (' . implode( ',', array_keys( $values ) ) . ') VALUES (' . implode( ',', $values ) . ')' );
}

/**
 * @param string $table
 * @param array $key_fields
 * @param array $data_fields
 *
 * @return bool|mysqli_result
 * @since 8.8.6.3
 */
function cerber_db_update( $table, $key_fields, $data_fields ) {
	$table = cerber_get_db_prefix() . $table;

	if ( ! $where = cerber_db_make_where( $table, $key_fields ) ) {
		return false;
	}

	$set = array();
	foreach ( $data_fields as $field => $value ) {
		$set[] = $field . '=' . cerber_db_prepare( $table, $field, $value );
	}
	$set = implode( ',', $set );

	return cerber_db_query( 'UPDATE ' . $table . ' SET ' . $set . ' WHERE ' . $where );
}

/**
 * @param string $table
 * @param array $key_fields
 *
 * @return string
 * @since 8.8.6.3
 */
function cerber_db_make_where( $table, $key_fields ) {

	$where = array();

	foreach ( $key_fields as $field => $value ) {
		$where [] = $field . '=' . cerber_db_prepare( $table, $field, $value );
	}

	return implode( ' AND ', $where );
}

/**
 * @param string $table
 * @param string $field
 * @param string|int|float $value
 *
 * @return int|string
 * @since 8.8.6.3
 */
function cerber_db_prepare( $table, $field, &$value ) {
	$type = '';

	if ( ! empty( CERBER_DB_TYPES[ $table ][ $field ] ) ) {
		$type = CERBER_DB_TYPES[ $table ][ $field ];
	}

	switch ( $type ) {
		case 'int':
			return (int) $value;
		default:
			return '"' . cerber_real_escape( $value ) . '"';
	}
}

/**
 * @return false|wpdb
 */
function cerber_get_db() {
	global $wpdb;
	static $db;

	if ( ! isset( CRB_Globals::$db_errors ) ) {
		CRB_Globals::$db_errors = array();
	}

	if ( ! $db
	     || empty( $db->dbh )
	     || ! $db->dbh instanceof MySQLi ) {

		if ( ! is_object( $wpdb )
		     || empty( $wpdb->dbh )
		     || ! $wpdb->dbh instanceof MySQLi ) {
			$db = cerber_db_connect();
		}
		else {
			$db = $wpdb;
		}
	}

	// Check if the attempt to connect has failed or the connection is lost

	if ( ! $db
	     || empty( $db->dbh )
	     || ! $db->dbh instanceof MySQLi ) {

		CRB_Globals::$db_errors[] = 'Unable to connect to the website database';

		return false;
	}

	return $db;
}

function cerber_get_db_prefix() {
	global $wpdb;
	static $prefix = null;
	if ( $prefix === null ) {
		$prefix = $wpdb->base_prefix;
	}

	return $prefix;
}

/**
 * Create a WP DB handler
 *
 * @return false|wpdb
 */
function cerber_db_connect() {
	if ( ! defined( 'CRB_ABSPATH' ) ) {
		define( 'CRB_ABSPATH', dirname( __FILE__, 4 ) );
	}

	$db_class = CRB_ABSPATH . '/' . WPINC . '/wp-db.php';

	$wp_config = CRB_ABSPATH . '/wp-config.php';
	if ( ! file_exists( $wp_config ) ) {
		$wp_config = dirname( CRB_ABSPATH ) . '/wp-config.php';
	}

	if ( file_exists( $db_class ) && $config = file_get_contents( $wp_config ) ) {
		$config = str_replace( '<?php', '', $config );
		$config = str_replace( '?>', '', $config );
		ob_start();
		@eval( $config ); // This eval is OK. Getting site DB connection parameters.
		ob_end_clean();
		if ( defined( 'DB_USER' ) && defined( 'DB_PASSWORD' ) && defined( 'DB_NAME' ) && defined( 'DB_HOST' ) ) {
			require_once( $db_class );

			return new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
		}
	}

	return false;
}

function crb_get_mysql_var( $var ) {
	static $cache;
	if ( ! isset( $cache[ $var ] ) ) {
		if ( $v = cerber_db_get_row( 'SHOW VARIABLES LIKE "' . $var . '"' ) ) {
			$cache[ $var ] = $v['Value'];
		}
		else {
			$cache[ $var ] = false;
		}
	}

	return $cache[ $var ];
}

/**
 * Retrieve a value from the key-value storage
 *
 * @param string $key
 * @param integer $id
 * @param bool $unserialize
 * @param bool $use_cache
 *
 * @return bool|array|string
 */
function cerber_get_set( $key, $id = null, $unserialize = true, $use_cache = null ) {
	if ( ! $key ) {
		return false;
	}

	$key = preg_replace( CRB_SANITIZE_KEY, '', $key );
	$cache_key = 'crb#' . $key . '#';

	$id = ( $id !== null ) ? absint( $id ) : 0;
	$cache_key .= $id;

	$ret = false;

	$use_cache = ( isset( $use_cache ) ) ? $use_cache : cerber_cache_is_enabled();

	if ( $use_cache ) {
		$cache_value = cerber_cache_get( $cache_key, null );
		if ( $cache_value !== null ) {
			return $cache_value;
		}
	}

	if ( $row = cerber_db_get_row( 'SELECT * FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE the_key = "' . $key . '" AND the_id = ' . $id ) ) {
		if ( $row['expires'] > 0 && $row['expires'] < time() ) {
			cerber_delete_set( $key, $id );
			if ( $use_cache ) {
				cerber_cache_delete( $cache_key );
			}

			return false;
		}
		if ( $unserialize ) {
			if ( ! empty( $row['the_value'] ) ) {
				$ret = crb_unserialize( $row['the_value'] );
			}
			else {
				$ret = array();
			}
		}
		else {
			$ret = $row['the_value'];
		}
	}

	if ( $use_cache ) {
		cerber_cache_set( $cache_key, $ret );
	}

	return $ret;
}

/**
 * Update/insert value to the key-value storage
 *
 * @param string $key A unique key for the data set. Max length is 255.
 * @param $value
 * @param integer $id An additional numerical key
 * @param bool $serialize
 * @param integer $expires Unix timestamp (UTC) when this element will be deleted
 * @param bool $use_cache
 *
 * @return bool
 */
function cerber_update_set( $key, $value, $id = null, $serialize = true, $expires = null, $use_cache = null ) {

	if ( ! $key ) {
		return false;
	}

	$key = preg_replace( CRB_SANITIZE_KEY, '', $key );
	$cache_key = 'crb#' . $key . '#';

	$expires = ( $expires !== null ) ? absint( $expires ) : 0;

	$id = ( $id !== null ) ? absint( $id ) : 0;
	$cache_key .= $id;

	$use_cache = ( isset( $use_cache ) ) ? $use_cache : cerber_cache_is_enabled();

	if ( $use_cache ) {
		cerber_cache_set( $cache_key, $value, $expires - time() );
	}

	if ( $serialize && ! is_string( $value ) ) {
		$value = serialize( $value );
	}

	$value = cerber_real_escape( $value );

	if ( false !== cerber_get_set( $key, $id, false, false ) ) {
		$sql = 'UPDATE ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' SET the_value = "' . $value . '", expires = ' . $expires . ' WHERE the_key = "' . $key . '" AND the_id = ' . $id;
	}
	else {
		$sql = 'INSERT INTO ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' (the_key, the_id, the_value, expires) VALUES ("' . $key . '",' . $id . ',"' . $value . '",' . $expires . ')';
	}

	unset( $value );

	if ( cerber_db_query( $sql ) ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Delete value from the storage
 *
 * @param string $key
 * @param integer $id
 *
 * @return bool
 */
function cerber_delete_set( $key, $id = null ) {

	$key = preg_replace( CRB_SANITIZE_KEY, '', $key );
	$cache_key = 'crb#' . $key . '#';

	$id = ( $id !== null ) ? absint( $id ) : 0;
	$cache_key .= $id;

	cerber_cache_delete( $cache_key );

	if ( cerber_db_query( 'DELETE FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE the_key = "' . $key . '" AND the_id = ' . $id ) ) {
		return true;
	}

	return false;
}

/**
 * Clean up all expired sets. Usually by cron.
 *
 * @param bool $all if true, deletes all sets that has expiration
 *
 * @return bool
 */
function cerber_delete_expired_set( $all = false ) {
	if ( ! $all ) {
		$where = 'expires > 0 AND expires < ' . time();
	}
	else {
		$where = 'expires > 0';
	}
	if ( cerber_db_query( 'DELETE FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE ' . $where ) ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Remove comments from a given piece of code
 *
 * @param string $string
 *
 * @return mixed
 */
function cerber_remove_comments( $string = '' ) {
	return preg_replace( '/#.*/', '', preg_replace( '#//.*#', '', preg_replace( '#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '', $string ) ) );
}

/**
 * Set Cerber Groove to logged in user browser
 *
 * @param $expire
 */
function cerber_set_groove( $expire ) {
	if ( ! headers_sent() ) {
		cerber_set_cookie( CRB_GROOVE, md5( cerber_get_groove() ), $expire + 1 );

		$groove_x = cerber_get_groove_x();
		cerber_set_cookie( CRB_GROOVE . '_x_' . $groove_x[0], $groove_x[1], $expire + 1 );
	}
}

function cerber_is_auth_cookie( $text ) {
	return ( 0 === strpos( $text, cerber_get_cookie_prefix() . CRB_GROOVE ) );
}

/*
	Get the special Cerber Sign for using with cookies
*/
function cerber_get_groove() {
	$groove = cerber_get_site_option( 'cerber-groove', false );

	if ( empty( $groove ) ) {
		$groove = crb_random_string( 16 );
		update_site_option( 'cerber-groove', $groove );
	}

	return $groove;
}

/*
	Check if the special Cerber Sign valid
*/
function cerber_check_groove( $hash = '' ) {
	if ( ! $hash ) {
		if ( ! $hash = cerber_get_cookie( CRB_GROOVE ) ) {
			return false;
		}
	}
	$groove = cerber_get_groove();
	if ( $hash == md5( $groove ) ) {
		return true;
	}

	return false;
}

/**
 * @since 7.0
 */
function cerber_check_groove_x() {
	$groove_x = cerber_get_groove_x();
	if ( cerber_get_cookie( CRB_GROOVE . '_x_' . $groove_x[0] ) == $groove_x[1] ) {
		return true;
	}

	return false;
}

/*
	Get the special public Cerber Sign for using with cookies
*/
function cerber_get_groove_x( $regenerate = false ) {
	$groove_x = array();

	if ( ! $regenerate ) {
		$groove_x = cerber_get_site_option( 'cerber-groove-x' );
	}

	if ( $regenerate || empty( $groove_x ) ) {
		$groove_0 = crb_random_string( 24, 32 );
		$groove_1 = crb_random_string( 24, 32 );
		$groove_x = array( $groove_0, $groove_1 );
		update_site_option( 'cerber-groove-x', $groove_x );
		crb_update_cookie_dependent();
	}

	return $groove_x;
}

function cerber_get_cookie_path() {
	if ( defined( 'COOKIEPATH' ) ) {
		return COOKIEPATH;
	}

	return '/';
}

/**
 * Sets WP Cerber's cookies
 *
 * @param string $name
 * @param string $value
 * @param int $expire
 * @param string $path
 * @param string $domain
 * @param bool $http
 *
 * @return bool
 */
function cerber_set_cookie( $name, $value, $expire = 0, $path = "", $domain = "", $http = false ) {
	global $wp_cerber_cookies;

	if ( cerber_is_wp_cron() ) {
		return false;
	}

	if ( ! $path ) {
		$path = cerber_get_cookie_path();
	}

	$expire = absint( $expire );
	$expire = ( $expire > 43009401600 ) ? 43009401600 : $expire;

	$ret = setcookie( cerber_get_cookie_prefix() . $name, $value, $expire, $path, $domain, is_ssl(), $http );
	// No rush here: PHP 7.3 only
	/*setcookie( cerber_get_cookie_prefix() . $name, $value, array(
		'expires ' => $expire,
		'path'     => $path,
		'domain'   => $domain,
		'secure'   => is_ssl(),
		'httponly' => false,
		'samesite' => 'Strict',
	) );*/

	if ( $ret ) {
		$wp_cerber_cookies[ cerber_get_cookie_prefix() . $name ] = array( $expire, $value );
	}

	return $ret;
}

/**
 * Retrieves WP Cerber's cookies
 *
 * @param string $name
 * @param bool $default
 *
 * @return string|boolean value of the cookie, false if the cookie is not set
 */
function cerber_get_cookie( $name, $default = false ) {
	return crb_array_get( $_COOKIE, cerber_get_cookie_prefix() . $name, $default );
}

function cerber_get_cookie_prefix() {

	if ( $p = (string) crb_get_settings( 'cookiepref' ) ) {
		return $p;
	}

	return '';
}

function crb_update_cookie_dependent() {
	static $done = false;

	if ( $done ) {
		return;
	}

	register_shutdown_function( function () {
		cerber_htaccess_sync( 'main' ); // keep the .htaccess rules up to date
	} );

	$done = true;
}

/**
 * Synchronize plugin settings with rules in the .htaccess file
 *
 * @param $file string
 * @param $settings array
 *
 * @return bool|string|WP_Error
 */
function cerber_htaccess_sync( $file, $settings = array() ) {

	if ( ! $settings ) {
		$settings = crb_get_settings();
	}

	if ( 'main' == $file ) {
		$rules = array();

		if ( ! empty( $settings['adminphp'] )
		     && ( ! defined( 'CONCATENATE_SCRIPTS' ) || ! CONCATENATE_SCRIPTS ) ) {
			// https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2018-6389
			if ( ! crb_is_apache_mod_loaded( 'mod_rewrite' ) ) {

				return new WP_Error( 'no_mod', 'The Apache mod_rewrite module is not enabled on your web server. Ask your server administrator for assistance.' );
			}
			$groove_x = cerber_get_groove_x();
			$cookie = cerber_get_cookie_prefix() . CRB_GROOVE . '_x_' . $groove_x[0];
			$rules [] = '# Protection of admin scripts is enabled (CVE-2018-6389)';
			$rules [] = '<IfModule mod_rewrite.c>';
			$rules [] = 'RewriteEngine On';
			$rules [] = 'RewriteBase /';
			$rules [] = 'RewriteCond %{REQUEST_URI} ^(.*)wp-admin/+load-scripts\.php$ [OR,NC]'; // @updated 8.1
			$rules [] = 'RewriteCond %{REQUEST_URI} ^(.*)wp-admin/+load-styles\.php$ [NC]'; // @updated 8.1
			$rules [] = 'RewriteCond %{HTTP_COOKIE} !' . $cookie . '=' . $groove_x[1];
			$rules [] = 'RewriteRule (.*) - [R=403,L]';
			$rules [] = '</IfModule>';
		}

		return cerber_update_htaccess( $file, $rules );
	}

	if ( 'media' == $file ) {
		/*if ( ! crb_is_php_mod() ) {
			return 'ERROR: The Apache PHP module mod_php is not active.';
		}*/
		$rules = array();

		if ( ! empty( $settings['phpnoupl'] ) ) {

			$rules [] = '<Files *>';
			$rules [] = 'SetHandler none';
			$rules [] = 'SetHandler default-handler';
			$rules [] = 'Options -ExecCGI';
			$rules [] = 'RemoveHandler .cgi .php .php3 .php4 .php5 .php7 .php8 .phtml .pl .py .pyc .pyo';
			$rules [] = '</Files>';

			$rules [] = '<IfModule mod_php.c>'; // PHP 8
			$rules [] = 'php_flag engine off';
			$rules [] = '</IfModule>';
			$rules [] = '<IfModule mod_php7.c>';
			$rules [] = 'php_flag engine off';
			$rules [] = '</IfModule>';
			$rules [] = '<IfModule mod_php5.c>';
			$rules [] = 'php_flag engine off';
			$rules [] = '</IfModule>';
		}

		return cerber_update_htaccess( $file, $rules );
	}

	return false;
}

/**
 * Remove Cerber rules from the .htaccess file
 *
 */
function cerber_htaccess_clean_up() {
	cerber_update_htaccess( 'main', array() );
	cerber_update_htaccess( 'media', array() );
}

/**
 * Update the .htaccess file
 *
 * @param $file
 * @param array $rules A set of rules (array of strings) for the section. If empty, the section will be cleaned.
 *
 * @return string|WP_Error  String on success, WP_Error on a failure
 */
function cerber_update_htaccess( $file, $rules = array() ) {
	if ( $file == 'main' ) {
		$htaccess = cerber_get_htaccess_file();
		$marker = CERBER_MARKER1;
	}
	elseif ( $file == 'media' ) {
		$htaccess = cerber_get_upload_dir() . '/.htaccess';
		$marker = CERBER_MARKER2;
	}
	else {
		return new WP_Error( 'htaccess-io', 'Unknown .htaccess file specified' );
	}

	if ( ! is_file( $htaccess ) ) {
		if ( ! touch( $htaccess ) ) {
			return new WP_Error( 'htaccess-io', 'ERROR: Unable to create the file ' . $htaccess );
		}
	}
	elseif ( ! is_writable( $htaccess ) ) {
		return new WP_Error( 'htaccess-io', 'ERROR: Unable to update the file ' . $htaccess );
	}

	$result = crb_insert_with_markers( $htaccess, $marker, $rules );

	if ( $result || $result === 0 ) {
		$result = 'The file ' . $htaccess . ' has been updated';
	}
	else {
		$result = new WP_Error( 'htaccess-io', 'ERROR: Unable to update the file ' . $htaccess );
	}

	return $result;
}

/**
 * Return .htaccess filename with full path
 *
 * @return bool|string full filename if the file can be written, false otherwise
 */
function cerber_get_htaccess_file() {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$home_path = get_home_path();

	return $home_path . '.htaccess';
}

/**
 * Check if the remote domain match mask
 *
 * @param $domain_mask array|string Mask(s) to check remote domain against
 *
 * @return bool True if hostname match at least one domain from the list
 */
function cerber_check_remote_domain( $domain_mask ) {

	$hostname = @gethostbyaddr( cerber_get_remote_ip() );

	if ( ! $hostname || filter_var( $hostname, FILTER_VALIDATE_IP ) ) {
		return false;
	}

	if ( ! is_array( $domain_mask ) ) {
		$domain_mask = array( $domain_mask );
	}

	foreach ( $domain_mask as $mask ) {

		if ( substr_count( $mask, '.' ) != substr_count( $hostname, '.' ) ) {
			continue;
		}

		$parts = array_reverse( explode( '.', $hostname ) );

		$ok = true;

		foreach ( array_reverse( explode( '.', $mask ) ) as $i => $item ) {
			if ( $item != '*' && $item != $parts[ $i ] ) {
				$ok = false;
				break;
			}
		}

		if ( $ok == true ) {
			return true;
		}

	}

	return false;
}

/**
 * Install and de-install WP Cerber files for different boot modes
 *
 * @param $mode int A plugin boot mode
 *
 * @return int|WP_Error
 *
 * @since 6.3
 */
function cerber_set_boot_mode( $mode = null ) {
	if ( $mode === null ) {
		$mode = crb_get_settings( 'boot-mode' );
	}

	$source = dirname( cerber_plugin_file() ) . '/modules/aaa-wp-cerber.php';
	$target = WPMU_PLUGIN_DIR . '/aaa-wp-cerber.php';

	if ( $mode == 1 ) {

		if ( file_exists( $target ) ) {
			if ( sha1_file( $source, true ) == sha1_file( $target, true ) ) {
				return 1;
			}
		}

		// Copying the file

		if ( ! is_dir( WPMU_PLUGIN_DIR ) ) {
			if ( ! mkdir( WPMU_PLUGIN_DIR, 0755, true ) ) {
				return new WP_Error( 'cerber-boot', __( 'Unable to create the directory', 'wp-cerber' ) . ' ' . WPMU_PLUGIN_DIR );
			}
		}

		if ( ! copy( $source, $target ) ) {
			if ( ! wp_is_writable( WPMU_PLUGIN_DIR ) ) {
				return new WP_Error( 'cerber-boot', __( 'Destination folder access denied', 'wp-cerber' ) . ' ' . WPMU_PLUGIN_DIR );
			}
			elseif ( ! file_exists( $source ) ) {
				return new WP_Error( 'cerber-boot', __( 'File not found', 'wp-cerber' ) . ' ' . $source );
			}

			return new WP_Error( 'cerber-boot', __( 'Unable to copy the file', 'wp-cerber' ) . ' ' . $source . ' to the folder ' . WPMU_PLUGIN_DIR );
		}
		else {
			return 2;
		}
	}

	if ( file_exists( $target ) ) {
		if ( ! unlink( $target ) ) {
			return new WP_Error( 'cerber-boot', __( 'Unable to delete the file', 'wp-cerber' ) . ' ' . $target );
		}
		else {
			cerber_diag_log( 'MU module deleted. IP:' . cerber_get_remote_ip() . ' METHOD: ' . $_SERVER['REQUEST_METHOD'] . ' ' . print_r( crb_get_post_fields(), 1 ) . ' UA: ' . $_SERVER['HTTP_USER_AGENT'] . ' REQ: ' . $_SERVER['REQUEST_URI'] );
			return 3;
		}
	}

	return 4;
}

/**
 * How WP Cerber was loaded (initialized)
 *
 * @return int
 *
 * @since 6.3
 */
function cerber_get_mode() {
	//if ( function_exists( 'cerber_mode' ) && defined( 'CERBER_MODE' ) ) {  // CERBER_MODE is not yet defined when Cerber.Hub renders admin pages on a managed website
	if ( function_exists( 'cerber_mode' ) ) {
		return cerber_mode();
	}

	return 0;
}

function cerber_is_permalink_enabled() {
	static $ret;

	if ( ! isset( $ret ) ) {
		$ret = (bool) get_option( 'permalink_structure' );
	}

	return $ret;
}

/**
 * @param string $dir Path to check
 * @param string $msg An error message
 * @param string $none An optional error message if the directory does not exist or is not within the allowed paths
 *
 * @return int 0 if the folder exists and writable
 *
 * @since 9.5.1
 */
function crb_check_dir( $dir, &$msg = '', $none = '' ) {
	$ret = 0;

	if ( ! is_dir( $dir ) ) {
		$ret = 1;
		$msg = $none ?: 'Required directory does not exist: ' . crb_escape( $dir );
	}
	elseif ( ! wp_is_writable( $dir ) ) {
		if ( ! chmod( $dir, 0755 ) ) {
			$ret = 2;
			$msg = 'Required directory is not writable. Please check write permissions and ownership of this directory: ' . crb_escape( $dir );
		}
	}

	return $ret;
}

/**
 * Implement basename() with multibyte support
 *
 * @param $file_name
 *
 * @return string
 */
function cerber_mb_basename( $file_name ) {
	$pos = mb_strrpos( $file_name, DIRECTORY_SEPARATOR );
	if ( $pos !== false ) {
		return mb_substr( $file_name, $pos + 1 );
	}

	return $file_name;
}

function cerber_get_extension( $file_name ) {
	$file_name = cerber_mb_basename( $file_name );
	$pos = mb_strpos( $file_name, '.' );
	if ( $pos !== false ) {
		if ( $ext = mb_substr( $file_name, $pos + 1 ) ) {
			return mb_strtolower( $ext );
		}
	}

	return  '';
}

/**
 * True if version of WP is equal or greater than specified one
 *
 * @param string $ver
 *
 * @return bool|int
 */
function crb_wp_version_compare( $ver, $comp = '>=' ) {
	return version_compare( cerber_get_wp_version(), (string) $ver, $comp );
}

/**
 * Returns an unaltered $wp_version variable
 *
 * @return string WordPress version
 */
function cerber_get_wp_version() {
	static $ver;

	if ( ! $ver ) {
		include( ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'version.php' );
		$ver = (string) $wp_version;
	}

	return $ver;
}

/**
 * Returns an unaltered $wp_local_package variable
 *
 * @return string WordPress locale
 * @since 8.8.7.2
 */
function cerber_get_wp_locale() {
	static $lc;

	if ( ! $lc ) {
		//global $wp_local_package;
		include( ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'version.php' );
		$lc = $wp_local_package ?? 'en_US';
	}

	return $lc;
}

function crb_get_themes() {

	static $theme_headers = array(
		'Name'        => 'Theme Name',
		'ThemeURI'    => 'Theme URI',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'Version'     => 'Version',
		'Template'    => 'Template',
		'Status'      => 'Status',
		'Tags'        => 'Tags',
		'TextDomain'  => 'Text Domain',
		'DomainPath'  => 'Domain Path',
	);

	$themes = array();

	if ( $list = search_theme_directories() ) {
		foreach ( $list as $key => $info ) {
			$css = $info['theme_root'] . '/' . $info['theme_file'];
			if ( is_readable( $css ) ) {
				$themes[ $key ] = get_file_data( $info['theme_root'] . '/' . $info['theme_file'], $theme_headers, 'theme' );
				$themes[ $key ]['theme_root'] = $info['theme_root'];
				$themes[ $key ]['theme_file'] = $info['theme_file'];
			}
		}
	}

	return $themes;
}

function cerber_is_base64_encoded( $val ) {
	$val = trim( $val );
	if ( empty( $val ) || is_numeric( $val ) || strlen( $val ) < 8 || preg_match( '/[^A-Z0-9\+\/=]/i', $val ) ) {
		return false;
	}
	if ( $val = @base64_decode( $val ) ) {
		if ( ! preg_match( '/[\x00-\x08\x0B-\x0C\x0E-\x1F]/', $val ) ) { // ASCII control characters must not be
			if ( preg_match( '/[A-Z]/i', $val ) ) { // Latin chars must be
				return $val;
			}
		}
	}


	return false;
}

function crb_is_alphanumeric( $str ) {
	return ! preg_match( '/[^\w\-]/', $str );
}

/**
 * @param array $arr
 * @param array $fields
 *
 * @return bool
 */
function crb_arrays_similar( &$arr, $fields ) {
	if ( crb_array_diff_keys( $arr, $fields ) ) {
		return false;
	}

	foreach ( $fields as $field => $pattern ) {
		if ( is_callable( $pattern ) ) {
			if ( ! call_user_func( $pattern, $arr[ $field ] ) ) {
				return false;
			}
		}
		else {
			if ( ! preg_match( $pattern, $arr[ $field ] ) ) {
				return false;
			}
		}
	}

	return true;
}

function cerber_get_html_label( $iid ) {
	//$css['scan-ilabel'] = 'color: #fff; margin-left: 6px; display: inline-block; line-height: 1em; padding: 3px 5px; font-size: 92%;';

	$c = ( $iid == 1 ) ? '#33be84' : '#dc2f34';

	return '<span style="background-color: ' . $c . '; color: #fff; margin-left: 6px; display: inline-block; line-height: 1em; padding: 3px 5px; font-size: 92%;">' . cerber_get_issue_label( $iid ) . '</span>';
}

function crb_getallheaders() {

	if ( function_exists( 'getallheaders' ) ) {
		return getallheaders();
	}

	// @since v. 7.7 for PHP-FPM

	$headers = array();
	foreach ( $_SERVER as $name => $value ) {
		if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
			$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value;
		}
	}

	return $headers;
}

/**
 * @param $msg
 * @param string $source
 */
function cerber_error_log( $msg, $source = '' ) {
	//if ( crb_get_settings( 'log_errors' ) ) {
	cerber_diag_log( $msg, $source, true );
	//}
}

/**
 * Write message to the diagnostic log
 *
 * @param string|array $msg
 * @param string $source
 * @param bool $error
 *
 * @return bool|int
 */
function cerber_diag_log( $msg, $source = '', $error = false ) {

	if ( $source == 'CLOUD' ) {
		if ( ! defined( 'CERBER_CLOUD_DEBUG' )
		     || ( ! defined( 'WP_ADMIN' ) && ! defined( 'WP_NETWORK_ADMIN' ) ) ) {
			return false;
		}
	}

	if ( ! $msg || ! $log = @fopen( cerber_get_diag_log(), 'a' ) ) {
		return false;
	}

	if ( $source ) {
		$source = '[' . $source . ']';
	}
	if ( $error ) {
		$source .= ' ERROR: ';
	}
	if ( ! is_array( $msg ) ) {
		$msg = array( $msg );
	}

	foreach ( $msg as $line ) {
		if ( is_array( $line ) ) {
			$line = print_r( $line, 1 ); // workaround for CRB_Globals::$db_errors
		}
		//$ret = @fwrite( $log, '[' .cerber_get_remote_ip(). '][' . cerber_date( time() ) . ']' . $source . ' ' . $line . PHP_EOL );
		$ret = @fwrite( $log, '[' . cerber_date( time(), false ) . ']' . $source . ' ' . $line . PHP_EOL );
	}

	@fclose( $log );

	return $ret;
}

function cerber_get_diag_log() {

	if ( defined( 'CERBER_DIAG_DIR' ) && is_dir( CERBER_DIAG_DIR ) ) {
		$dir = CERBER_DIAG_DIR;
	}
	else {
		if ( ! $dir = cerber_get_the_folder() ) {
			return false;
		}
	}

	return rtrim( $dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'cerber-debug.log';
}

function cerber_truncate_log( $bytes = 10000000 ) {

	update_user_meta( get_current_user_id(), 'clast_log_view', array() );

	$file = cerber_get_diag_log();

	if ( ! is_file( $file ) || filesize( $file ) <= $bytes ) {
		return;
	}
	if ( $bytes == 0 ) {
		$log = @fopen( $file, 'w' );
		@fclose( $log );

		return;
	}
	if ( $text = file_get_contents( $file ) ) {
		$text = substr( $text, 0 - $bytes );
		if ( ! $log = @fopen( $file, 'w' ) ) {
			return;
		}
		@fwrite( $log, $text );
		@fclose( $log );
	}
}

function crb_get_bloginfo( $what ) {
	static $info = array();
	if ( ! isset( $info[ $what ] ) ) {
		$info[ $what ] = get_bloginfo( $what );
	}

	return $info[ $what ];
}

function crb_is_php_mod() {
	require_once( ABSPATH . 'wp-admin/includes/misc.php' );
	if ( apache_mod_loaded( 'mod_php7' ) ) {
		return true;
	}
	if ( apache_mod_loaded( 'mod_php5' ) ) {
		return true;
	}

	return false;
}

/**
 * PHP implementation of fromCharCode
 *
 * @param $str
 *
 * @return string
 */
function cerber_fromcharcode( $str ) {
	$vals = explode( ',', $str );
	$vals = array_map( function ( $v ) {
		$v = trim( $v );
		if ( $v[0] == '0' ) {
			$v = ( $v[1] == 'x' || $v[1] == 'X' ) ? hexdec( $v ) : octdec( $v );
		}
		else {
			$v = intval( $v );
		}

		return '&#' . $v . ';';
	}, $vals );

	return mb_convert_encoding( implode( '', $vals ), 'UTF-8', 'HTML-ENTITIES' );
}

/**
 * @param $dir string Directory to empty with a trailing directory separator
 *
 * @return int|WP_Error
 */
function cerber_empty_dir( $dir ) {
	//$trd = rtrim( $dir, '/\\' );
	if ( ! @is_dir( $dir )
	     || 0 === strpos( $dir, ABSPATH ) ) { // Workaround for a non-legitimate use of this function
		return new WP_Error( 'no-dir', 'This directory cannot be emptied' );
	}

	$files = @scandir( $dir );
	if ( ! is_array( $files ) || empty( $files ) ) {
		return true;
	}

	$fs = cerber_init_wp_filesystem();
	if ( crb_is_wp_error( $fs ) ) {
		return $fs;
	}

	$ret = true;

	foreach ( $files as $file ) {
		$full = $dir . $file;
		if ( @is_file( $full ) ) {
			if ( ! @unlink( $full ) ) {
				$ret = false;
			}
		}
		elseif ( ! in_array( $file, array( '..', '.' ) ) && is_dir( $full ) ) {
			if ( ! $fs->rmdir( $full, true ) ) {
				$ret = false;
			}
		}
	}

	if ( ! $ret ) {
		return new WP_Error( 'file-deletion-error', 'Some files or subdirectories in this directory cannot be deleted: ' . $dir );
	}

	return $ret;
}

/**
 * Creates folder(s) if it (or the whole path) doesn't exist.
 * Set permissions.
 *
 * @param string $target_dir
 * @param int $permissions
 *
 * @return bool|WP_Error
 * @since 9.5.4.2
 */
function crb_create_folder( $target_dir, $permissions = 0755 ) {
	if ( file_exists( $target_dir ) ) {
		return true;
	}

	$err = '';

	if ( ! mkdir( $target_dir, $permissions, true ) ) {
		$err = 'Unable to create the folder <b>' . $target_dir . '</b>. Check permissions of parent folders.';
	}
	elseif ( ! chmod( $target_dir, $permissions ) ) {
		$err = 'Unable to set directory permissions for ' . $target_dir;
	}

	if ( $err ) {
		return new WP_Error( 'cerber-dir', $err );
	}

	return true;
}

/**
 * Tries to raise PHP limits
 *
 */
function crb_raise_limits( $mem = null ) {

	@ini_set( 'max_execution_time', 180 );

	if ( function_exists( 'set_time_limit' )
	     && false === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) ) {
		@set_time_limit( 0 );
	}

	if ( $mem ) {
		@ini_set( 'memory_limit', $mem );
	}
}

/**
 * Masks email address
 *
 * @param string $email
 *
 * @return string
 */
function cerber_mask_email( $email ) {
	list( $box, $host ) = explode( '@', $email );
	$box = str_pad( $box[0], strlen( $box ), '*' );
	$host = str_pad( substr( $host, strrpos( $host, '.' ) ), strlen( $host ), '*', STR_PAD_LEFT );

	return str_replace( '*', '&#8727;', $box . '@' . $host );
}

/**
 * Masks username (login)
 *
 * @param string $login
 *
 * @return string
 *
 * @since 8.9.6.4
 */
function crb_mask_login( $login ) {
	if ( is_email( $login ) ) {
		return cerber_mask_email( $login );
	}

	$strlen = mb_strlen( $login );

	return str_pad( mb_substr( $login, 0, intdiv( $strlen, 2 ) ), $strlen, '*' );
}

/**
 * Masks IP address
 *
 * @param string $ip
 *
 * @return string
 *
 * @since 8.9.6.4
 */
function crb_mask_ip( $ip = '' ) {
	if ( cerber_is_ipv6( $ip ) ) {
		// Look for the third colon
		$pos = strpos( $ip, ':', strpos( $ip, ':', strpos( $ip, ':' ) + 1 ) + 1 );
		$delimiter = ':';
	}
	else {
		// Look for the second dot
		$pos = strpos( $ip, '.', strpos( $ip, '.' ) + 1 );
		$delimiter = '.';
	}

	if ( ! $pos ) {
		return $ip;
	}

	$net = substr( $ip, 0, $pos );
	$sub = substr( $ip, $pos );

	return $net . preg_replace( '/[^' . $delimiter . ']/', '*', $sub );
}


/**
 * A modified clone of insert_with_markers() from wp-admin/includes/misc.php
 * Removed switch_to_locale() and related stuff that were introduced in WP 5.3. and cause problem if calling ite before 'init' hook.
 *
 * Inserts an array of strings into a file (.htaccess ), placing it between
 * BEGIN and END markers.
 *
 * Replaces existing marked info. Retains surrounding
 * data. Creates file if none exists.
 *
 * @param string $filename Filename to alter.
 * @param string $marker The marker to alter.
 * @param array|string $insertion The new content to insert.
 *
 * @return bool True on write success, false on failure.
 * @since 8.5.1
 *
 */
function crb_insert_with_markers( $filename, $marker, $insertion ) {
	if ( ! file_exists( $filename ) ) {
		if ( ! is_writable( dirname( $filename ) ) ) {
			return false;
		}
		if ( ! touch( $filename ) ) {
			return false;
		}
	}
	elseif ( ! is_writeable( $filename ) ) {
		return false;
	}

	if ( ! is_array( $insertion ) ) {
		$insertion = explode( "\n", $insertion );
	}

	$start_marker = "# BEGIN {$marker}";
	$end_marker = "# END {$marker}";

	$fp = fopen( $filename, 'r+' );
	if ( ! $fp ) {
		return false;
	}

	// Attempt to get a lock. If the filesystem supports locking, this will block until the lock is acquired.
	flock( $fp, LOCK_EX );

	$lines = array();
	while ( ! feof( $fp ) ) {
		$lines[] = rtrim( fgets( $fp ), "\r\n" );
	}

	// Split out the existing file into the preceding lines, and those that appear after the marker
	$pre_lines = array();
	$post_lines = array();
	$existing_lines = array();
	$found_marker = false;
	$found_end_marker = false;
	foreach ( $lines as $line ) {
		if ( ! $found_marker && false !== strpos( $line, $start_marker ) ) {
			$found_marker = true;
			continue;
		}
		elseif ( ! $found_end_marker && false !== strpos( $line, $end_marker ) ) {
			$found_end_marker = true;
			continue;
		}
		if ( ! $found_marker ) {
			$pre_lines[] = $line;
		}
		elseif ( $found_marker && $found_end_marker ) {
			$post_lines[] = $line;
		}
		else {
			$existing_lines[] = $line;
		}
	}

	// Check to see if there was a change
	if ( $existing_lines === $insertion ) {
		flock( $fp, LOCK_UN );
		fclose( $fp );

		return true;
	}

	// Generate the new file data
	$new_file_data = implode(
		"\n",
		array_merge(
			$pre_lines,
			array( $start_marker ),
			$insertion,
			array( $end_marker ),
			$post_lines
		)
	);

	// Write to the start of the file, and truncate it to that length
	fseek( $fp, 0 );
	$bytes = fwrite( $fp, $new_file_data );
	if ( $bytes ) {
		ftruncate( $fp, ftell( $fp ) );
	}
	fflush( $fp );
	flock( $fp, LOCK_UN );
	fclose( $fp );

	return (bool) $bytes;
}

/**
 * @return WP_Error|WP_Filesystem_Direct
 */
function cerber_init_wp_filesystem() {
	global $wp_filesystem;

	if ( $wp_filesystem instanceof WP_Filesystem_Direct ) { // @since 8.1.5
		return $wp_filesystem;
	}

	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	add_filter( 'filesystem_method', '__ret_direct' );

	if ( ! WP_Filesystem() ) {
		return new WP_Error( 'cerber-file', 'Unable to init WP_Filesystem' );
	}

	remove_filter( 'filesystem_method', '__ret_direct' );

	return $wp_filesystem;
}

function __ret_direct() {
	return 'direct';
}

/**
 * Returns a list of alert parameters for the currently displaying admin page in a specific order.
 * The keys are used to create an alert URL.
 * Values are used to calculate an alert hash.
 *
 * @return array The set of parameters
 */
function crb_get_alert_params() {

	// A set of alert parameters
	// A strictly particular order due to further using numeric array indexes.

	$params = CRB_ALERT_PARAMS;
	$get = crb_get_query_params();

	if ( ! array_intersect_key( $params, $get ) ) {
		return $params; // No parameters in the current query
	}

	// The IP field is processed differently than other fields

	if ( ! empty( $get['filter_ip'] ) ) {
		$begin = 0;
		$end = 0;
		$ip = cerber_any2range( $get['filter_ip'] );

		if ( is_array( $ip ) ) {
			$begin = $ip['begin'];
			$end = $ip['end'];
			$ip = 0;
		}
		elseif ( ! $ip ) {
			$ip = 0;
		}

		$params['begin'] = $begin;
		$params['end'] = $end;
		$params['filter_ip'] = $ip;
	}

	// Getting values of the request fields (used as alert parameters)

	$temp = $params;
	unset( $temp['begin'], $temp['end'], $temp['filter_ip'] );

	foreach ( array_keys( $temp ) as $key ) {
		if ( ! empty( $get[ $key ] ) ) {
			if ( is_array( $get[ $key ] ) ) {
				$params[ $key ] = array_map( 'trim', $get[ $key ] );
			}
			else {
				$params[ $key ] = trim( $get[ $key ] );
			}
		}
		else {
			$params[ $key ] = 0;
		}
	}

	// Preparing/sanitizing values of the alert parameters

	if ( ! empty( $params['al_expires'] ) ) {
		$time = 24 * 3600 + strtotime( 'midnight ' . $params['al_expires'] );
		$params['al_expires'] = $time - get_option( 'gmt_offset' ) * 3600;
	}

	$int_fields = array( 'al_limit', 'al_ignore_rate', 'al_send_emails', 'al_send_pushbullet', 'al_send_me', );

	foreach ( $int_fields as $f ) {
		$params[ $f ] = crb_absint( $params[ $f ] );
	}

	if ( ! is_array( $params['filter_activity'] ) ) {
		$params['filter_activity'] = array( $params['filter_activity'] );
	}
	$params['filter_activity'] = array_filter( $params['filter_activity'] );

	// Basic XSS sanitization

	array_walk_recursive( $params, function ( &$item ) {
		$item = str_replace( array( '<', '>', '[', ']', '"', "'" ), '', $item );
	} );

	return $params;
}

/**
 * @param array $params
 *
 * @return string
 *
 * @since 8.9.6
 */
function crb_get_alert_id( $params ) {
	return sha1( json_encode( array_values( array_diff_key( $params, array_flip( CRB_NON_ALERT_PARAMS ) ) ) ) );
}

/**
 * Generates a random string with the random length between $length_min and $length_max
 *
 * @param int $length_min Min length
 * @param int $length_max Max length
 * @param bool $inc_num Include numbers
 * @param bool $upper_case Include upper case
 * @param string $extra Additional characters (other than ASCII) to include
 *
 * @return string
 */
function crb_random_string( $length_min, $length_max = null, $inc_num = true, $upper_case = true, $extra = '' ) {
	static $alpha1 = 'abcdefghijklmnopqrstuvwxyz';
	static $alpha2 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	static $digits = '0123456789';

	if ( ! $length_max ) {
		$length_max = $length_min;
	}

	$str = $alpha1;

	if ( $inc_num ) {
		$str .= $digits;
	}

	if ( $upper_case ) {
		$str .= $alpha2;
	}

	if ( $extra ) {
		$str .= $extra;
	}

	$n = (int) ceil( $length_max / strlen( $str ) );

	if ( $n > 1 ) {
		$str = implode( '', array_fill( 0, $n, $str ) );
	}

	$length = ( $length_min != $length_max ) ? rand( $length_min, $length_max ) : $length_min;

	return substr( str_shuffle( $str ), 0, $length );
}

/**
 * Detects and decodes serialized or JSON encoded array
 *
 * @param $text string
 *
 * @return array
 *
 * @since 8.8
 */
function crb_auto_decode( &$text ) {
	if ( ! $text ) {
		return array();
	}

	if ( $text[0] == 'a' ) {
		return crb_unserialize( $text );
	}

	return @json_decode( $text, true );
}

/**
 * A safe version of unserialize()
 *
 * @param string $string
 *
 * @return mixed
 *
 */
function crb_unserialize( &$string ) {
	return @unserialize( $string, [ 'allowed_classes' => false ] ); // Requires PHP 7
}

function crb_get_review_url( $vendor = null ) {
	static $urls = array(
		'tpilot' => array( 'https://www.trustpilot.com/review/wpcerber.com', 'https://www.trustpilot.com/evaluate/wpcerber.com' ),
		'g2'     => array( 'https://www.g2.com/products/cerber-security-antispam-malware-scan/reviews/start' ),
		'wp'     => array( 'https://wordpress.org/support/plugin/wp-cerber/reviews/#new-post' ),
		'cap'    => array( 'https://reviews.capterra.com/new/187653' ),
	);

	$ret = $urls[ $vendor ];
	if ( $vendor == 'tpilot' ) {
		shuffle( $ret );
	}

	return $ret[0];
}

function crb_was_activated( $ago ) {
	static $actvd;

	if ( ! isset( $actvd ) ) {
		if ( ! $actvd = cerber_get_set( '_activated' ) ) {
			return true;
		}
	}

	return ( ( (int) crb_array_get( $actvd, 'time' ) + $ago ) <= time() );
}

/**
 * Return a "session verifier" to identify the current admin session among others admin sessions
 *
 * Copy of WP_Session_Tokens->hash_token();
 *
 * @param $token
 *
 * @return string
 */
function cerber_hash_token( $token ) {
	// If ext/hash is not present, use sha1() instead.
	if ( function_exists( 'hash' ) ) {
		return hash( 'sha256', $token );
	}
	else {
		return sha1( $token );
	}
}
/**
 * Convert a value to non-negative integer.
 *
 * @param mixed $val Data you wish to have converted to a non-negative integer.
 *
 * @return int A non-negative integer.
 *
 * @since 9.3.4
 */
function crb_absint( $val ) {
	return abs( (int) $val );
}

function crb_is_apache_mod_loaded( $mod = '' ) {
	static $loaded = array();

	if ( ! isset( $loaded[ $mod ] ) ) {
		$loaded[ $mod ] = apache_mod_loaded( 'mod_rewrite' );
	}

	return $loaded[ $mod ];
}

// The key-value cache

final class CRB_Cache {
	private static $cache = array();
	private static $stat = array();
	private static $wp_cache_group = 'wp_cerber';
	private static $wp_key_list = 'wp_cerber_list';

	static function set( $key, $value, $expire = 0 ) {
		$exp = 0;

		if ( $expire > 0 ) {
			$exp = time() + (int) $expire;
			if ( $exp < time() ) {
				return false;
			}
		}

		$element = array( $value, $exp );
		self::$cache[ $key ] = $element;

		if ( self::checker() ) {
			wp_cache_set( $key, $element, self::$wp_cache_group );

			$entries = wp_cache_get( self::$wp_key_list, self::$wp_key_list );
			if ( ! $entries ) {
				$entries = array();
			}
			$entries[ $key ] = $expire;
			wp_cache_set( self::$wp_key_list, $entries, self::$wp_key_list );
		}

		if ( ! isset( self::$stat[ $key ] ) ) {
			self::$stat[ $key ] = array( 0, 0 );
		}

		self::$stat[ $key ][0] ++;

		return true;
	}

	static function get( $key, $default = null ) {

		$element = crb_array_get( self::$cache, $key );

		if ( ! is_array( $element ) ) {
			if ( self::checker() ) {
				$element = wp_cache_get( $key, self::$wp_cache_group );
			}
		}

		if ( ! is_array( $element ) ) {
			return $default;
		}

		if ( ! empty( $element[1] ) && $element[1] < time() ) {
			self::delete( $key );

			return $default;
		}

		if ( ! isset( self::$stat[ $key ] ) ) {
			self::$stat[ $key ] = array( 0, 0 );
		}

		self::$stat[ $key ][1] ++;

		return $element[0];
	}

	static function delete( $key ) {
		if ( isset( self::$cache[ $key ] ) ) {
			unset( self::$cache[ $key ] );
		}
		if ( self::checker() ) {
			wp_cache_delete( $key, self::$wp_cache_group );
		}
	}

	static function reset() {
		self::$cache = array();

		if ( $entries = wp_cache_get( self::$wp_key_list, self::$wp_key_list ) ) {
			foreach ( $entries as $entry => $exp ) {
				wp_cache_delete( $entry, self::$wp_cache_group );
			}

			wp_cache_delete( self::$wp_key_list, self::$wp_key_list );
		}
	}

	static function get_stat( $recheck = false ) {
		$entries = wp_cache_get( self::$wp_key_list, self::$wp_key_list );

		if ( $recheck && $entries ) { // Make sure that our list of existing key doesn't contain wrong entries
			foreach ( $entries as $key => $exp ) {
				if ( ! $element = wp_cache_get( $key, self::$wp_cache_group ) ) {
					unset( $entries[ $key ] );
				}
			}

			wp_cache_set( self::$wp_key_list, $entries, self::$wp_key_list );
		}

		if ( empty( $entries ) ) {
			$entries = array();
		}

		return array( self::$stat, $entries );
	}

	static function checker() {

		$sid = get_wp_cerber()->getRequestID();
		$check = wp_cache_get( '__checker__', self::$wp_cache_group );

		if ( ! $check || ! isset( $check['t'] ) || ! isset( $check['s'] ) ) {
			wp_cache_set( '__checker__', array(
				't' => time(),
				's' => $sid
			), self::$wp_cache_group );

			return 0;
		}

		if ( $check['s'] == $sid ) {
			return 0;
		}

		return $check['t'];
	}
}

/**
 * @param $key string
 * @param $value mixed
 * @param $expire integer Element will expire in X seconds, 0 = never expires
 *
 * @return bool
 */
function cerber_cache_set( $key, $value, $expire = 0 ) {
	return CRB_Cache::set( $key, $value, $expire );
}

/**
 * @param $key string
 * @param $default mixed
 *
 * @return mixed|null
 */
function cerber_cache_get( $key, $default = null ) {
	return CRB_Cache::get( $key, $default );
}

function cerber_cache_delete( $key ) {
	CRB_Cache::delete( $key );
}

function cerber_cache_enable() {
	global $cerber_use_cache;
	$cerber_use_cache = true;
}

function cerber_cache_disable() {
	global $cerber_use_cache;
	$cerber_use_cache = false;
}

function cerber_cache_is_enabled() {
	global $cerber_use_cache;

	return ! empty( $cerber_use_cache );
}

/**
 * Retrieve and cache data from the DB. Make sense for heavy queries.
 *
 * @param array|string $sql One or more SQL queries with optional data format
 * @param string $table DB table we're caching data from
 * @param bool $cache_only
 * @param string[] $hash_fields Fields to calculate hash
 * @param int $order_by The key of the ORDER BY field in the $fieldset
 *
 * @return array|false
 *
 * @since 8.8.3.1
 */
function crb_q_cache_get( $sql, $table, $cache_only = false, $hash_fields = array( 'stamp', 'ip', 'session_id' ), $order_by = 0 ) {
	global $wp_cerber_q_cache;

	if ( is_string( $sql ) ) {
		$sql = array( array( $sql ) );
	}

	$single = ( count( $sql ) == 1 );

	$run = true;

	$cache_key = 'q_cache_' . sha1( implode( '|', array_column( $sql, 0 ) ) );
	$cache = cerber_get_set( $cache_key, 0, false, true );

	if ( $cache ) {
		$cache = json_decode( $cache );
		if ( $cache->hash == crb_get_table_hash( $table, $hash_fields, $order_by ) ) {
			$wp_cerber_q_cache = true;
			$run = false;
		}
	}

	if ( $run && $cache_only ) {
		return false;
	}

	if ( ! $run ) {
		$results = $cache->results;
	}
	else {

		$new_cache = array();

		$new_cache['hash'] = crb_get_table_hash( $table, $hash_fields, $order_by );

		$results = array();

		foreach ( $sql as $query ) {
			$results[] = cerber_db_get_results( $query[0], crb_array_get( $query, 1 ) );
		}

		$new_cache['results'] = $results;
		$new_cache = json_encode( $new_cache, JSON_UNESCAPED_UNICODE );

		cerber_update_set( $cache_key, $new_cache, 0, false, time() + 7200, true );
	}

	if ( $single ) {
		return $results[0];
	}

	return $results;
}

/**
 * Returns pseudo "hash" for a given log table to detect changes in the table
 *
 * @param string $table
 * @param string[] $hash_fields
 * @param int $order_by
 *
 * @return string
 * @since 8.8.3.1
 */
function crb_get_table_hash( $table, $hash_fields, $order_by ) {
	static $hashes;

	$fields = implode( ',', $hash_fields );
	$key = sha1( $table . '|' . $fields . '|' . $order_by );

	if ( ! isset( $hashes[ $key ] ) ) {
		if ( $data = cerber_db_get_row( 'SELECT ' . $fields . ' FROM ' . $table . ' ORDER BY ' . $hash_fields[ $order_by ] . ' DESC LIMIT 1' ) ) {
			$hashes[ $key ] = sha1( implode( '|', $data ) );
		}
		else {
			$hashes[ $key ] = '';
		}

	}

	return $hashes[ $key ];
}

add_filter( 'update_plugins_downloads.wpcerber.com', 'cerber_check_for_update', 10, 4 );

/**
 * @param $update
 * @param $plugin_data
 * @param $plugin_file
 * @param $locales
 *
 * @return mixed|null
 *
 * @since 9.1.2
 */
function cerber_check_for_update( $update, $plugin_data, $plugin_file, $locales ) {

	if ( ! crb_get_settings( 'cerber_sw_repo' )
	     || ! $uri = crb_array_get( $plugin_data, 'UpdateURI' ) ) {
		return false;
	}

	$response = wp_remote_get( $uri );

	if ( ! $body = crb_array_get( $response, 'body' ) ) {
		return false;
	}

	$package_data = json_decode( $body, true );

	$update = crb_array_get( $package_data, $plugin_file, $update );

	//$update['requires_php'] = CERBER_REQ_PHP;
	$update['tested'] = cerber_get_wp_version(); // The last version of WP Cerber is always tested with the last version of WP

	return $update;

}

add_filter( 'auto_update_plugin', function ( $update, $item ) {

	// $update = apply_filters( "auto_update_{$type}", $update, $item );

	if ( crb_get_settings( 'cerber_sw_auto' )
	     && $item->plugin == 'wp-cerber/wp-cerber.php' ) {
		return true;
	}

	return $update;

}, PHP_INT_MAX, 2 );


/**
 * Returns full last login info if it exists, false otherwise.
 * Updates last login information, if it's empty.
 *
 * @param $user_id
 *
 * @return false|array
 *
 * @since 9.4.1
 */
function crb_get_last_user_login( $user_id ) {
	$user_set = cerber_get_set( CRB_USER_SET, $user_id );

	if ( $user_set['last_login']['cn'] ?? false ) {
		return $user_set['last_login'];
	}

	if ( ! $row = cerber_get_last_login( $user_id ) ) {
		return false;
	}

	// Updating introduced in 9.4.1 elements

	$user_set['last_login']['ts'] = $row->stamp;
	$user_set['last_login']['cn'] = $row->country ?: lab_get_country( $row->ip, false );

	cerber_update_set( CRB_USER_SET, $user_set, $user_id );

	return $user_set['last_login'];
}

/**
 * Add global cURL parameters
 *
 * @param CurlHandle|resource $curl
 * @param array $params
 *
 * @return bool true if all options were successfully set. If an option could
 * not be successfully set, false is immediately returned, ignoring any
 * future options in the options array.
 *
 * @since 9.5.4.3
 *
 */
function crb_configure_curl( $curl, $params, $setting = 'main_use_proxy' ) {

	if ( crb_get_settings( $setting ) ) {
		if ( defined( 'WP_PROXY_HOST' ) && defined( 'WP_PROXY_PORT' ) ) {

			$params[ CURLOPT_PROXYTYPE ] = defined( 'CERBER_PROXY_TYPE' ) ? CERBER_PROXY_TYPE : CURLPROXY_HTTP;
			$params[ CURLOPT_PROXY ] = WP_PROXY_HOST;
			$params[ CURLOPT_PROXYPORT ] = WP_PROXY_PORT;

			if ( defined( 'WP_PROXY_USERNAME' ) && defined( 'WP_PROXY_PASSWORD' ) ) {
				$params[ CURLOPT_PROXYAUTH ] = CURLAUTH_ANY;
				$params[ CURLOPT_PROXYUSERPWD ] = WP_PROXY_USERNAME . ':' . WP_PROXY_PASSWORD;
			}
		}
	}

	return curl_setopt_array( $curl, $params );
}

/**
 * Load definitions of WordPress functions from its PHP files
 *
 * @param string $func
 * @param bool $load_cons
 *
 * @return bool
 *
 * @since 9.5.5.1
 */
function crb_load_dependencies( $func, $load_cons = false ) {

	if ( function_exists( $func ) ) {
		return false;
	}

	if ( $load_cons ) {
		cerber_load_wp_constants();
	}

	$ret = true;

	switch ( $func ) {
		case 'wp_mail':
		case 'wp_create_nonce':
		case 'is_user_logged_in':
			require_once( ABSPATH . WPINC . '/pluggable.php' );
			break;
		case 'wp_is_auto_update_enabled_for_type':
			require_once( ABSPATH . 'wp-admin/includes/update.php' );
			break;
		default:
			$ret = false;
	}

	return $ret;
}

/**
 * A replacement for global PHP variables. It doesn't make them good (less ugly), but it helps to trace their usage easily (within IDE).
 *
 * @since 8.9.4
 *
 */
class CRB_Globals {
	static $session_status;
	static $act_status;
	static $do_not_log = array();
	static $reset_pwd_msg;
	static $reset_pwd_denied = false;
	static $user_id;
	static $req_status = 0;
	private static $assets_url = '';
	static $ajax_loader = '';
	static $logged;
	static $blocked;
	static $db_requests = array();
	static $db_errors = array();
	static $bot_status = 0;
	static $htaccess_failure = array();
	static $php_errors = array();

	static $doing_upgrade;

	static function admin_init() {
	}

	/**
	 * Returns collected PHP errors if any
	 *
	 * @return array
	 */
	static function get_errors() {
		if ( ! is_array( self::$php_errors ) ) {
			self::$php_errors = array();
		}

		// Add an error from error_get_last() if it was not caught by our error handler

		if ( $last_err = error_get_last() ) {

			$add = true;

			if ( self::$php_errors) {

				// Avoiding duplicates

				foreach ( self::$php_errors as $err ) {
					if ( $last_err['type'] == $err[0]
					     && $last_err['line'] == $err[3]
					     && $last_err['file'] == $err[2] ) {
						$add = false;

						break;
					}
				}
			}

			if ( $add ) {
				self::$php_errors[] = array( $last_err['type'], $last_err['message'], $last_err['file'], $last_err['line'] );
			}
		}

		return self::$php_errors;
	}

	/**
	 * Returns collected PHP errors if any
	 *
	 * @param int|array $code See https://www.php.net/manual/en/errorfunc.constants.php
	 *
	 * @return bool
	 */
	static function has_errors( $code ) {
		if ( ! self::get_errors() ) {
			return false;
		}

		$error_codes = array_column( self::$php_errors, 0 );

		if ( is_array( $code ) ) {
			return (bool) array_intersect( $error_codes, $code );
		}

		return in_array( $code, $error_codes );
	}

	/**
	 * Returns a safe URL to the WP Cerber assets folder or a file in it.
	 *
	 * @param string $res A file in the assets folder
	 * @param bool $echo Echo the result if true
	 *
	 * @return string Safe for any HTML context
	 *
	 * @since 9.5.1
	 */
	static function assets_url( $res = '', $echo = false ) {

		if ( ! self::$assets_url ) {
			self::$assets_url = crb_escape( cerber_plugin_dir_url() ) . 'assets/';
		}

		$ret = self::$assets_url;

		if ( $res ) {
			$ret .= $res;
		}

		if ( $echo ) {
			echo $ret;
		}

		return $ret;
	}

	/**
	 * Alternate the asset URL
	 *
	 * @param string $url
	 *
	 * @return void
	 */
	static function set_assets_url( $url ) {
		self::$assets_url = crb_escape( $url );
	}

	/**
	 * @param integer $val
	 *
	 * @return void
	 */
	static function set_bot_status( $val ) {
		self::$bot_status = $val;
		self::$act_status = $val; // For backward compatibility
	}

	/**
	 * @param integer $val
	 *
	 * @return void
	 */
	static function set_act_status( $val ) {
		if ( ! self::$act_status ) {
			self::$act_status = $val;
		}
	}
}
