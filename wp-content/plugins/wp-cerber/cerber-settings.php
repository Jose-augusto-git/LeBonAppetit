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

// Processed by WP Settings API
const CERBER_OPT = 'cerber-main';
const CERBER_OPT_H = 'cerber-hardening';
const CERBER_OPT_U = 'cerber-users';
const CERBER_OPT_A = 'cerber-antispam';
const CERBER_OPT_C = 'cerber-recaptcha';
const CERBER_OPT_N = 'cerber-notifications';
const CERBER_OPT_T = 'cerber-traffic';
const CERBER_OPT_S = 'cerber-scanner';
const CERBER_OPT_E = 'cerber-schedule';
const CERBER_OPT_P = 'cerber-policies';
const CERBER_OPT_US = 'cerber-user_shield';
const CERBER_OPT_OS = 'cerber-opt_shield';
const CERBER_OPT_SL = 'cerber-nexus-slave';
const CERBER_OPT_MA = 'cerber-nexus_master';

// Processed by Cerber
const CERBER_SETTINGS = 'cerber_settings'; // @deprecated since 9.3.4
const CERBER_GEO_RULES = 'geo_rule_set';

// @since 9.3.4 - a new, united settings
const CERBER_CONFIG = 'cerber_configuration';

// Processed (parsed) WP Cerber settings
const CERBER_COMPILED = 'cerber_compiled';

// Add-on settings
const CRB_ADDON_STS = 'crb_addon_settings';

// PRO settings
const CRB_PRO_SETS = array( CERBER_OPT_E, CERBER_OPT_P );
const CRB_PRO_SETTINGS = array(
	'nologinhint_msg',
	'nopasshint_msg',
	'reglimit',
	'reglimit_num',
	'reglimit_min',
	'regwhite',
	'regwhite_msg',
	'email_mask',
	'email_format',
	'notify_plugin_update_freq',
	'notify_plugin_update_brf',
	'notify_plugin_update_to',
	'use_smtp',
	'pbrate',
	'pbnotify',
	'pb_mask',
	'pb_format',
	'scan_media',
	'customcomm'
);

const CRB_PRO_POLICIES = array(
	'sess_limit'        => array( 2, 0 ),
	'sess_limit_policy' => array( 2, 0 ),
	'sess_limit_msg'    => array( 2, '' ),
	'app_pwd'           => array( 2, 0 ),
	'2fasmart'          => array( 1, 0 ),
	'2fanewcountry'     => array( 1, 0 ),
	'2fanewnet4'        => array( 1, 0 ),
	'2fanewip'          => array( 1, 0 ),
	'2fanewua'          => array( 1, 0 ),
	'2fasessions'       => array( 1, 0 ),
	'note2'             => array( 1, 0 ),
	'2fadays'           => array( 1, 0 ),
	'2falogins'         => array( 1, 0 ),
	'2faremember'       => array( 2, 0 ),
);

/**
 * A set of Cerber settings (WP options)
 *
 * @param bool $all
 * @return array
 */
function cerber_get_setting_list( $all = false ) {
	$ret = array( CERBER_SETTINGS, CERBER_OPT, CERBER_OPT_H, CERBER_OPT_U, CERBER_OPT_A, CERBER_OPT_C, CERBER_OPT_N, CERBER_OPT_T, CERBER_OPT_S, CERBER_OPT_E, CERBER_OPT_P, CERBER_OPT_SL, CERBER_OPT_MA, CERBER_OPT_US, CERBER_OPT_OS );

	if ( $all ) {
		$ret = array_merge( $ret, array( CERBER_GEO_RULES, CERBER_CONFIG ) );
	}

	return $ret;
}

/**
 * @param $name string HTML input name
 * @param $list array   List of elements
 * @param null $selected Index of selected element
 * @param string $class HTML class
 * @param string $id HTML ID
 * @param string $multiple
 *
 * @return string
 */
function cerber_select( $name, $list, $selected = null, $class = '', $id = '', $multiple = '', $placeholder = '', $data = array(), $atts = '' ) {
	$options = array();
	foreach ( $list as $key => $value ) {
		$s         = ( $selected == (string) $key ) ? 'selected' : '';
		$options[] = '<option value="' . $key . '" ' . $s . '>' . htmlspecialchars( $value ) . '</option>';
	}
	$p      = ( $placeholder ) ? ' data-placeholder="' . $placeholder . '" placeholder="' . $placeholder . '" ' : '';
	$m      = ( $multiple ) ? ' multiple="multiple" ' : '';
	$the_id = ( $id ) ? ' id="' . $id . '" ' : '';
	$d      = '';
	if ( $data ) {
		foreach ( $data as $att => $val ) {
			$d .= ' data-' . $att . '="' . $val . '"';
		}
	}

	return ' <select name="' . $name . '" ' . $the_id . ' class="crb-input-select ' . $class . '" ' . $m . $p . $d . ' ' . $atts . '>' . implode( "\n", $options ) . '</select>';
}

function crb_get_activity_dd( $first = '' ) {
	$all = $labels = cerber_get_labels( 'activity' );

	if ( ! class_exists( 'BP_Core' ) ) {
		unset( $labels[200] );
	}

	if ( ! nexus_is_client() ) {
		unset( $labels[300] );
	}

	unset( $labels[151] );
	unset( $labels[152] );

	// Not in use and replaced by statuses 532 - 534 since 8.9.4.
	unset( $labels[40] );
	unset( $labels[41] );
	unset( $labels[42] );

	asort( $labels );

	if ( ! $first ) {
		$first = __( 'Any activity', 'wp-cerber' );
	}

	$labels = array( 0 => __( $first, 'wp-cerber' ) ) + $labels + array( 151 => $all[151], 152 => $all[152] );

	$selected = crb_get_query_params( 'filter_activity', '\d+' );
	if ( ! $selected || is_array( $selected ) ) {
		$selected = 0;
	}

	return cerber_select( 'filter_activity', $labels, $selected, 'crb-filter-act' );
}

/**
 * Convert an array to text string by using a given delimiter
 *
 * @param array $array
 * @param string $delimiter
 *
 * @return array|string
 */
function cerber_array2text( $array = array(), $delimiter = '') {
	if ( empty( $array ) ) {
		return '';
	}

	if ( is_array( $array ) ) {
	    if ($delimiter == ',') $delimiter .= ' ';
		$ret = implode( $delimiter , $array );
	}
	else {
		$ret = $array;
    }

    return $ret;
}

/**
 * Convert string to an array by using a given delimiter, remove empty and duplicate elements
 * Optionally a callback function can be applied to the resulting array.
 * Optionally a REGEX filter can be applied to the resulting array.
 *
 * @param string $text
 * @param string $delimiter
 * @param string $callback
 * @param string $regex
 *
 * @return array
 */
function cerber_text2array( $text = '', $delimiter = '', $callback = '', $regex = '') {

	if ( empty( $text ) ) {
		return array();
	}

	if ( ! is_array( $text ) ) {
		if ( $delimiter[0] == '/' ) {
			$list = preg_split( $delimiter, $text );
		}
		else {
			$list = explode( $delimiter, $text );
		}
	}
	else {
		$list = $text;
	}

	$list = array_map( 'trim', $list );

	if ( $callback && is_callable( $callback ) ) {
		$list = array_map( $callback, $list );
	}

	if ( $regex ) {
		global $_regex;
		$_regex = $regex;
		$list = array_map( function ( $e ) {
			global $_regex;

			return mb_ereg_replace( $_regex, '', $e );
		}, $list );
	}

	$list = array_filter( $list );
	$list = array_unique( $list );

	return $list;
}

/*
 * 	Default settings.
 *  Returns a list split into setting pages.
 *
 */
function cerber_get_defaults( $setting = null, $dynamic = true ) {
	$all_defaults = array(
		CERBER_OPT    => array(
			'boot-mode'       => 0,
			'attempts'        => 5,
			'period'          => 30,
			'lockout'         => 60,
			'agperiod'        => 24,
			'aglocks'         => 2,
			'aglast'          => 4,
			'limitwhite'      => 0,
			'nologinhint'     => 0,
			'nologinhint_msg' => '',
			'nopasshint'      => 0,
			'nopasshint_msg'  => '',
			'nologinlang'     => 0,

			'proxy'      => 0,
			'cookiepref' => '',

			'subnet'           => 0,
			'nonusers'         => 0,
			'wplogin'          => 0,
			'noredirect'       => 0,
			'page404'          => 1,
			'page404_redirect' => '',
			'main_use_proxy'   => 0,
			'cerber_sw_repo'   => 1,
			'cerber_sw_auto'   => 0,

			'loginpath'     => '',
			'loginnowp'     => 0,
			'logindeferred' => 0,

			'citadel_on' => '1',
			'cilimit'    => 200,
			'ciperiod'   => 15,
			'ciduration' => 60,
			'cinotify'   => 1,

			'keeplog'        => 90,
			'keeplog_auth'   => 90,
			'ip_extra'       => 1,
			'cerberlab'      => 1,
			'cerberproto'    => 1,
			'usefile'        => 0,
			'dateformat'     => '',
			'plain_date'     => 0,
			'admin_lang'     => 0,
			'top_admin_menu' => 1,
			'no_white_my_ip' => 0,
			//'log_errors'   => 1

		),
		CERBER_OPT_H => array(
			'stopenum'            => 1,
			'stopenum_oembed'     => 1,
			'stopenum_sitemap'    => 0,
			'nouserpages_bylogin' => 0,
			'adminphp'            => 0,
			'phpnoupl'            => 0,
			'nophperr'            => 1,
			'xmlrpc'              => 0,
			'nofeeds'             => 0,
			'norestuser'          => 1,
			'norestuser_roles'    => array(),
			'norest'              => 0,
			'restauth'            => 1,
			'restroles'           => array( 'administrator' ),
			'restwhite'           => array( 'oembed', 'wp-site-health' ),
			'cleanhead'           => 1,
		),
		CERBER_OPT_U  => array(
			'authonly'       => 0,
			'authonlyacl'    => 0,
			'authonlymsg'    => '',
			'authonlyredir'  => '',
			'regwhite'       => 0,
			'regwhite_msg'   => '',
			'reglimit_num'   => 3,
			'reglimit_min'   => 60,
			'emrule'         => 0,
			'emlist'         => array(),
			'prohibited'     => array(),
			'app_pwd'        => 1,
			'auth_expire'    => '',
			'no_rememberme'  => 0,
			'usersort'       => 0,
			'pdata_erase'    => 0,
			'pdata_sessions' => 0,
			'pdata_export'   => 0,
			'pdata_act'      => 0,
			'pdata_trf'      => array(),
		),
		CERBER_OPT_A => array(
			'botscomm'    => 0,
			'botsreg'     => 0,
			'botsany'     => 0,
			'botssafe'    => 0,
			'botsnoauth'  => 1,
			'botsipwhite' => 1,
			'customcomm'  => 0,
			'botswhite'   => array(),

			'spamcomm'           => 0,
			'trashafter'         => 7,
			'trashafter-enabled' => 0,
		),
		CERBER_OPT_C => array(
			'sitekey'          => '',
			'secretkey'        => '',
			'invirecap'        => 0,
			'recaplogin'       => 0,
			'recaplost'        => 0,
			'recapreg'         => 0,
			'recapwoologin'    => 0,
			'recapwoolost'     => 0,
			'recapwooreg'      => 0,
			'recapcom'         => 0,
			'recapcomauth'     => 1,
			'recapipwhite'     => 0,
			'recaptcha-period' => 60,
			'recaptcha-number' => 3,
			'recaptcha-within' => 30,
		),
		CERBER_OPT_N => array(
			'notify'                    => 1,
			'above'                     => 5,
			'email'                     => array(),
			'emailrate'                 => 12,
			'notify-new-ver'            => 1,
			'notify_plugin_update'      => 1,
			'notify_plugin_update_freq' => 24,
			'notify_plugin_update_brf'  => 0,
			'notify_plugin_update_to'   => array(),
			'email_mask'                => 0,
			'email_format'              => 0,

			'use_smtp'       => 0,
			'smtp_host'      => '',
			'smtp_port'      => '587',
			'smtp_encr'      => 'tls',
			'smtp_pwd'       => '',
			'smtp_user'      => '',
			'smtp_from'      => '',
			'smtp_from_name' => 'WP Cerber',

			'pbtoken'          => '',
			'pbdevice'         => '',
			'pbrate'           => '',
			'pbnotify'         => 10,
			'pbnotify-enabled' => 1,
			'pb_mask'          => 0,
			'pb_format'        => 0,
			'wreports-day'     => 1,
			'wreports-time'    => 9,
			'wreports_7'       => 0,
			'email-report'     => array(),
			'enable-report'    => 1,

			'monthly_report' => 0,
			'monthly_on'     => array( 'day' => 1, 'hours' => 9 ),
			'monthly_30'     => 0,
			'email_report_one_month'  => array(),

		),
		CERBER_OPT_T => array(
			'tienabled'      => 1,
			'tiipwhite'      => 0,
			'tiwhite'        => array(),
			'tierrmon'       => 1,
			'tierrnoauth'    => 1,
			'timode'         => '3',
			'tilogrestapi'   => 0,
			'tilogxmlrpc'    => 0,
			'tinocrabs'      => 1,
			'tinolocs'       => array(),
			'tinoua'         => array(),
			'tifields'       => 0,
			'timask'         => array(),
			'tihdrs'         => 0,
			'tihdrs_sent'    => 0,
			'tisenv'         => 0,
			'ticandy'        => 0,
			'ticandy_sent'   => 0,
			'tiphperr'       => 0,
			'tithreshold'    => '',
			'tikeeprec'      => 30,
			'tikeeprec_auth' => 30,
		),
		CERBER_OPT_US => array(
			'ds_4acc'       => 0,
			'ds_regs_roles' => array(),
			'ds_add_acc'    => array( 'administrator' ),
			'ds_edit_acc'   => array( 'administrator' ),
			'ds_4acc_acl'   => 0,
			'ds_4roles'     => 0,
			'ds_add_role'   => array( 'administrator' ),
			'ds_edit_role'  => array( 'administrator' ),
			'ds_4roles_acl' => 0,
		),
		CERBER_OPT_OS => array(
			'ds_4opts'       => 0,
			'ds_4opts_roles' => array( 'administrator' ),
			'ds_4opts_list'  => array(),
			'ds_4opts_acl'   => 0,
		),
		CERBER_OPT_S  => array(
			'scan_cpt'      => array(),
			'scan_uext'     => array( 'tmp', 'temp', 'bak' ),
			'scan_exclude'  => array(),
			'scan_inew'     => 1,
			'scan_imod'     => 1,
			'scan_chmod'    => 0,
			'scan_tmp'      => 0,
			'scan_sess'     => 0,
			'scan_debug'    => 0,
			'scan_qcleanup' => '30',
		),
		CERBER_OPT_E  => array(
			'scan_aquick'        => 0,
			'scan_afull'         => '0' . rand( 1, 5 ) . ':00',
			'scan_afull-enabled' => 0,
			'scan_reinc'         => array( 3 => 1, CERBER_VULN => 1, CERBER_IMD => 1, 50 => 1, 51 => 1 ),
			'scan_relimit'       => 3,
			'scan_isize'         => 0,
			'scan_ierrors'       => 0,
			'email-scan'         => array()
		),
		CERBER_OPT_P  => array(
			'scan_delunatt'   => 0,
			'scan_delupl'     => array(),
			'scan_delunwant'  => 0,
			'scan_recover_wp' => 0,
			'scan_recover_pl' => 0,

			'scan_media'      => 0,
			'scan_skip_media' => array( 'css', 'jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tif', 'tiff', 'ico', 'woff', 'woff2', 'eot', 'ttf' ),
			'scan_del_media'  => array( 'php', 'js', 'htm', 'html', 'shtml' ),

			'scan_nodeltemp' => 0,
			'scan_nodelsess' => 0,
			'scan_delexdir'  => array(),
			'scan_delexext'  => array(),
		),
		CERBER_OPT_MA => array(
			'master_tolist'    => 1,
			'master_swshow'    => 1,
			'master_at_site'   => 1,
			'master_locale'    => 0,
			'cerber_hub_proxy' => 0,
			'master_dt'        => 0,
			'master_tz'        => 0,
			'master_diag'      => 0,
		),
		CERBER_OPT_SL => array(
			'slave_ips'    => '',
			'slave_access' => 2,
			'slave_diag'   => 0,
		),
		'other_settings' => array(
			'crb_role_policies'  => array(),
			CRB_ADDON_STS => array(),
		),
	);

	if ( $dynamic ) {
		$all_defaults[ CERBER_OPT_U ]['authonlymsg'] = __( 'Only registered and logged in users are allowed to view this website', 'wp-cerber' );
		$all_defaults[ CERBER_OPT_OS ]['ds_4opts_list'] = CRB_DS::get_settings_list( false );
	}

	if ( $setting ) {
		foreach ( $all_defaults as $section ) {
			if ( isset( $section[ $setting ] ) ) {
				return $section[ $setting ];
			}
		}

		return null;
	}

	return $all_defaults;
}

/**
 * Returns all default settings as a single-level associative array
 *
 * @return array
 *
 * @since 8.9.6.6
 */
function crb_get_default_values() {
	static $defs;

	if ( ! $defs ) {
		$defs = array();
		foreach ( cerber_get_defaults() as $fields ) {
			$defs = array_merge( $defs, $fields );
		}
	}

	return $defs;
}

/**
 * Returns default settings for PRO features only as a single-level associative array
 *
 * @return array
 *
 * @since 8.9.6.6
 */
function crb_get_default_pro() {
	static $pro;

	if ( ! $pro ) {
		$pro = array();

		// 1. Get page-level PRO settings
		$list = array_intersect_key( cerber_get_defaults(), array_flip( CRB_PRO_SETS ) );
		foreach ( $list as $fields ) {
			$pro = array_merge( $pro, $fields );
		}

		// 2. Get setting-level PRO settings
		$pro = array_merge( $pro, array_intersect_key( crb_get_default_values(), array_flip( CRB_PRO_SETTINGS ) ) );
	}

	return $pro;
}

/**
 * Upgrades WP Cerber settings from previous versions and formats
 *
 * @param string $ver The previous version we are upgrading from
 *
 * @return void
 */
function cerber_upgrade_settings( $ver = '' ) {

	if ( $ver && version_compare( '9.3.3', $ver, '>=' ) ) {
		$settings = _cerber_get_site_old_options();

		// Run it after all add-ons were loaded

		update_site_option( 'cerber_tmp_old_settings', $settings );
		add_action( 'plugins_loaded', '_cerber_upgrade_addon_settings' );
	}
	else {
		$settings = crb_get_settings();
	}

	if ( ! $settings ) {
		$settings = array();
	}

	$defs = crb_get_default_values();

	// Add new settings (fields) with their default values

	foreach ( $defs as $field_name => $default ) {
		if ( ! isset( $settings[ $field_name ] ) ) {
			$settings[ $field_name ] = $default;
		}
	}

	// Remove outdated fields

	$settings = array_intersect_key( $settings, $defs );

	// @since 9.3.4 all WP Cerber settings use a new format

	cerber_load_admin_code();

	cerber_settings_update( $settings, 'all' ); // @since 9.3.4

	// Remove orphans and deprecated stuff

	if ( ! $key = get_site_option( '_cerberkey_' ) ) {
		$key = cerber_get_site_option( '_cerberkey_' );
	}

	if ( $key ) {
		if ( cerber_update_set( '_cerberkey_', $key ) ) { // new
			delete_site_option( '_cerberkey_' ); // deprecated
		}
	}
}

/**
 * Returns WP Cerber settings.
 * The replacement for cerber_get_options()
 *
 * @param string $option
 * @param bool $purge_cache Purge static cache
 * @param bool $use_defaults Exclusively for plugin activation process
 *
 * @return array|bool|mixed
 */
function crb_get_settings( $option = '', $purge_cache = false, $use_defaults = true ) {
	global $wpdb;
	static $cache;

	/**
	 * For some hosting environments it can be faster, e.g. Redis enabled
	 */
	if ( defined( 'CERBER_WP_OPTIONS' ) && CERBER_WP_OPTIONS ) {
		$opts = get_site_option( CERBER_CONFIG );

		if ( $option ) {
			return $opts[ $option ] ?? false;
		}

		return $opts;
	}

	if ( $purge_cache ) {
		$cache = array();
	}

	if ( ! isset( $cache ) || $purge_cache ) {

		$cache  = array();

	    if ( is_multisite() ) {
		    $sql_new = 'SELECT meta_value FROM ' . $wpdb->sitemeta . ' WHERE meta_key = "' . CERBER_CONFIG . '"';
	    }
	    else {
		    $sql_new = 'SELECT option_value FROM ' . $wpdb->options . ' WHERE option_name = "' . CERBER_CONFIG . '"';
	    }

		$set_new = cerber_db_get_var( $sql_new );

		if ( $set_new ) {
			$cache = crb_unserialize( $set_new );
		}
		elseif ( $use_defaults ) {
			$cache = crb_get_default_values();
		}

		if ( ! lab_lab() && $use_defaults ) {
			$cache = array_merge( $cache, crb_get_default_pro() );
		}

		// Compatibility with Cloudflare add-on version 1.2 and older

		$addons = crb_array_get( $cache, CRB_ADDON_STS );
		if ( $cf = crb_array_get( $addons, 'cloudflare' ) ) {
			$cache = array_merge( $cache, $cf );
		}

    }

	if ( ! empty( $option ) ) {
		return $cache[ $option ] ?? false;
	}

	return $cache;
}

function crb_purge_settings_cache() {
	crb_get_settings( null, true );
}

/**
 * @param string $option Name of site option
 * @param boolean $unserialize If true the value of the option must be unserialized
 *
 * @return null|array|string
 * @since 5.8.7
 */
function cerber_get_site_option( $option = '', $unserialize = true ) {
	global $wpdb;
	static $values = array();

	if ( ! $option ) {
		return null;
	}

	/**
	 * For some hosting environments it might be faster, e.g. Redis enabled
	 */
	if ( defined( 'CERBER_WP_OPTIONS' ) && CERBER_WP_OPTIONS ) {
		return get_site_option( $option, null );
	}

	if ( isset( $values[ $option ] ) ) {
		return $values[ $option ];
	}

	if ( is_multisite() ) {
		$sql = 'SELECT meta_value FROM ' . $wpdb->sitemeta . ' WHERE meta_key = "' . $option . '"';
	}
	else {
		$sql = 'SELECT option_value FROM ' . $wpdb->options . ' WHERE option_name = "' . $option . '"';
	}

	$value = cerber_db_get_var( $sql );

	if ( $value ) {
		if ( $unserialize ) {
			$value = crb_unserialize( $value );
			if ( ! is_array( $value ) ) {
				$value = null;
			}
		}
	}
	else {
		$value = null;
	}

	$values[ $option ] = $value;

	return $value;
}

/**
 * Returns WP Cerber settings from the old format (pre v. 9.3.4)
 *
 * @return array
 *
 * @since 9.3.4
 */
function _cerber_get_site_old_options() {
	$list = cerber_get_setting_list();
	array_unshift( $list, CERBER_CONFIG );

	$settings = array();
	foreach ( $list as $old_option ) {
		//if ( $val = cerber_get_site_option( $old_option ) ) {
		if ( $val = get_site_option( $old_option ) ) {
			$settings = array_merge( $settings, $val );
		}
	}

	return $settings;
}

/*
	Load default settings, except Custom Login URL
*/
function cerber_load_defaults() {

	$save = crb_get_default_values();

	if ( $path = crb_get_settings( 'loginpath' ) ) {
		$save['loginpath'] = $path;
	}

	cerber_settings_update( $save, 'all' ); // @since 9.3.4
	update_site_option( CERBER_GEO_RULES, array() );
	cerber_remove_issues();
}

/**
 * Get a compiled Cerber setting
 *
 * @param string $setting
 * @param string $default
 * @param bool $reload
 *
 * @return false|mixed|null
 *
 * @since 8.8
 */
function crb_get_compiled( $setting, $default = '', $reload = false ) {
	static $cache;

	if ( ! isset( $cache ) || $reload ) {
		$cache = cerber_get_set( CERBER_COMPILED );
	}

	if ( ! is_array( $cache ) ) {
		$cache = array();
	}

	return crb_array_get( $cache, $setting, $default );
}

/**
 * Update a compiled Cerber setting
 *
 * @param string $setting
 * @param mixed $value
 *
 * @return bool
 *
 * @since 8.8
 */
function crb_update_compiled( $setting, $value ) {

	$data = cerber_get_set( CERBER_COMPILED );
	if ( ! is_array( $data ) ) {
		$data = array();
	}

	$data[ $setting ] = $value;

	if ( $ret = cerber_update_set( CERBER_COMPILED, $data ) ) {
		crb_get_compiled( 'anything', '', true );
	}

	return $ret;
}

/**
 * Returns the list of email addresses based on the given arguments
 *
 * @param string $type Type of notification email
 * @param array $args Optional arguments
 *
 * @return array Email address(es)
 */
function cerber_get_email( $type = '', $args = array() ) {

	if ( $list = $args['email_recipients'] ?? false ) {
		return $list;
	}

	$emails = array();

	if ( in_array( $type, array( 'report', 'scan' ) ) ) {
		if ( ( $args['report_id'] ?? '' ) == 'one_month' ) {
			// The scheme is 'email_'.$type.'_'.$args['report_id']
			$emails = (array) crb_get_settings( 'email_report_one_month' );
		}
		else {
			$emails = (array) crb_get_settings( 'email-' . $type );
		}
	}

	if ( isset( $args['recipients_setting'] ) ) {
		$emails = (array) crb_get_settings( $args['recipients_setting'] );
	}

	if ( $list = $args['user_list'] ?? false ) {
		foreach ( $list as $user_id ) {
			if ( $u = get_userdata( $user_id ) ) {
				$emails[] = $u->display_name . ' <' . $u->user_email . '>';
			}
		}
	}

	if ( ! $emails ) { // Fallback to notification email
		$emails = (array) crb_get_settings( 'email' );
	}

	if ( ! $emails ) {
		$emails = get_site_option( 'admin_email' );
		$emails = array( $emails );
	}

	if ( $type == 'activated' ) {
		if ( is_super_admin() ) {
			$user = wp_get_current_user();
			$emails[] = $user->user_email;
		}
	}

	return array_unique( $emails );
}

/**
 * Sync a set of scanner/uptime bots settings with the cloud
 *
 * @param $data
 *
 * @return bool
 */
function cerber_cloud_sync( $data = array() ) {
	if ( ! lab_lab() ) {
		return false;
	}

	if ( ! $data ) {
		$data = crb_get_settings();
	}

	$full  = ( empty( $data['scan_afull-enabled'] ) ) ? 0 : 1;
	$quick = absint( $data['scan_aquick'] );

	if ( $quick || $full ) {
		$set = array(
			$quick,
			$full,
			cerber_sec_from_time( $data['scan_afull'] ),
			cerber_get_email( 'scan' )
		);
		$scan_scheduling = array( // Is used for scheduled scans
			'client'     => $set,
			//'site_url'   => cerber_get_home_url(), // @since 9.5.7
			'site_url'   => cerber_get_site_url(),
			'gmt_offset' => (int) get_option( 'gmt_offset' ),
			'dtf'        => cerber_get_dt_format(),
		);
	}
	else {
		$scan_scheduling = array();
	}

	if ( lab_api_send_request( array(
		'scan_scheduling' => $scan_scheduling
	) ) ) {
		return true;
	}

	return false;
}

/**
 * Is a cloud based service enabled by the site owner
 *
 * @return bool False if nothing cloud related is enabled
 */
function cerber_is_cloud_enabled( $what = '' ) {
	$data = crb_get_settings();

	$s = array( 'quick' => 'scan_aquick', 'full' => 'scan_afull-enabled' );

	if ( $what ) {
		if ( ! empty( $data[ $s[ $what ] ] ) ) {
			return true;
		}

		return false;
	}

	foreach ( $s as $item ) {
		if ( ! empty( $data[ $item ] ) ) {
			return true;
		}
	}

	return false;
}

function cerber_get_role_policies( $role ) {
	if ( ! $conf = crb_get_settings( 'crb_role_policies' ) ) {
		return array();
	}

	$ret = crb_array_get( $conf, $role );

	if ( ! is_array( $ret ) ) {
		$ret = array();
	}

	if ( ! lab_lab() ) {
		$ret = array_merge( $ret, crb_get_default_pol_pro() );
	}

	return $ret;
}

/**
 * @param $policy string
 * @param $user integer | WP_User
 * @param $global string fallback if no role-based policy is configured
 *
 * @return bool|string
 */
function cerber_get_user_policy( $policy, $user = null, $global = '' ) {
	static $user_cache = array();

	if ( ! ( $user instanceof WP_User ) ) {
		if ( is_numeric( $user ) ) {
			if ( ! isset( $user_cache[ $user ] ) ) {
				$user_cache[ $user ] = get_user_by( 'id', $user );
			}
			$user = $user_cache[ $user ];
		}
		else {
			$user = wp_get_current_user();
		}
	}

	if ( ! $user ) {
		return false;
	}

	$ret = false;

	foreach ( $user->roles as $role ) {
		$policies = cerber_get_role_policies( $role );
		if ( ! empty( $policies[ $policy ] ) ) {
			$ret = $policies[ $policy ];
		}
	}

	if ( ! $ret && $global ) {
		$ret = crb_get_settings( $global );
	}

	return $ret;
}

/**
 * Returns default values of PRO role-based policies
 *
 * @return array
 *
 * @since 8.9.6.6
 */
function crb_get_default_pol_pro() {
	static $pol;

	if ( ! $pol ) {
		foreach ( CRB_PRO_POLICIES as $id => $conf ) {
			$pol[ $id ] = $conf[1];
		}
	}

	return $pol;
}
