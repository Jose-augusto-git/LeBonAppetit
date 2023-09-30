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

if ( ! defined( 'WPINC' ) || ! defined( 'CERBER_VER' ) ) {
	exit;
}

/**
 * WP Cerber settings form in the WP dashboard
 *
 * @since 8.5.9.1
 */
const CRB_SETTINGS_GROUP = 'cerber_settings_group';
const CRB_FIELD_PREFIX = 'crb-input-';

function cerber_settings_config( $args = array() ) {
	if ( $args && ! is_array( $args ) ) {
		return false;
	}

	// WP setting is: 'cerber-'.$screen_id
	$screens = array(
		'main'          => array( 'boot', 'liloa', 'stspec', 'proactive', 'custom', 'citadel', 'activity', 'prefs' ),
		'users'         => array( 'us', 'us_reg', 'us_misc', 'pdata' ),
		'hardening'     => array( 'hwp', 'rapi' ),
		'notifications' => array( 'notify', 'smtp', 'pushit', 'reports' ),
		'traffic'       => array( 'tmain', 'tierrs', 'tlog' ),
		'scanner'       => array( 'smain', 'smisc' ),
		'schedule'      => array( 's1', 's2' ),
		//'policies'      => array( 'scanpls', 'suploads', 'scanrecover', 'scanexcl' ),
		'policies'      => array( 'scanpls', 'scanrecover', 'scanexcl' ),
		'antispam'      => array( 'antibot', 'antibot_more', 'commproc' ),
		'recaptcha'     => array( 'recap' ),
		'user_shield'   => array( 'acc_protect', 'role_protect' ),
		'opt_shield'    => array( 'opt_protect' ),
		'nexus-slave'   => array( 'slave_settings' ),
		'nexus_master'  => array( 'master_settings' ),
	);

	$add = crb_addon_settings_config( $args );

	if ( ! empty( $add['screens'] ) ) {
		$screens = array_merge( $screens, $add['screens'] );
	}

	// Pushbullet devices
	$pb_set = array();
	if ( cerber_is_admin_page( array( 'tab' => 'notifications' ) ) ) {
		$pb_set = cerber_pb_get_devices();
		if ( is_array( $pb_set ) ) {
			if ( ! empty( $pb_set ) ) {
				$pb_set = array( 'all' => __( 'All connected devices', 'wp-cerber' ) ) + $pb_set;
			}
			else {
				$pb_set = array( 'N' => __( 'No devices found', 'wp-cerber' ) );
			}
		}
		else {
			$pb_set = array( 'N' => __( 'Not available', 'wp-cerber' ) );
		}
	}

	// Descriptions
	if ( ! cerber_is_permalink_enabled() ) {
		$custom = '<span style="color:#DF0000;">' . __( 'Please enable Permalinks to use this feature. Set Permalink Settings to something other than Default.', 'wp-cerber' ) . '</span>';
	}
	else {
		$custom = __( 'Be careful about enabling these options.', 'wp-cerber' ) . ' ' . __( 'If you forget your Custom login URL, you will be unable to log in.', 'wp-cerber' );
	}

	$no_wcl = __( 'These restrictions do not apply to IP addresses in the White IP Access List', 'wp-cerber' );

	$sections = array(
		'boot'      => array(
			'name'   => __( 'Initialization Mode', 'wp-cerber' ),
			'desc'   => __( 'How WP Cerber loads its core and security mechanisms', 'wp-cerber' ),
			'fields' => array(
				'boot-mode' => array(
					'title' => __( 'Load security engine', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						__( 'Legacy mode', 'wp-cerber' ),
						__( 'Standard mode', 'wp-cerber' )
					)
				),
			),
		),
		'liloa'     => array(
			//'name'   => __( 'User Authentication', 'wp-cerber' ),
			'name'    => __( 'Login Security', 'wp-cerber' ),
			'desc'    => __( 'Brute-force attack mitigation and user authentication settings', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/wordpress-login-security/',
			'fields'  => array(
				'attempts'    => array(
					'title' => __( 'Limit login attempts', 'wp-cerber' ),
					'type'  => 'attempts',
				),
				'lockout' => array(
					'type'    => 'digits',
					'min_val' => 1,
					'title'   => __( 'Block IP address for', 'wp-cerber' ),
					'label'   => __( 'minutes', 'wp-cerber' ),
				),
				'aggressive'  => array(
					'title' => __( 'Mitigate aggressive attempts', 'wp-cerber' ),
					'type'  => 'aggressive',
				),
				'limitwhite'  => array(
					'title' => __( 'Use White IP Access List', 'wp-cerber' ),
					'label' => __( 'Apply limit login rules to IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'loginnowp' => array(
					'title'        => __( 'Processing wp-login.php authentication requests', 'wp-cerber' ),
					'type'         => 'select',
					'set'          => array(
						__( 'Default processing', 'wp-cerber' ),
						__( 'Block access to wp-login.php', 'wp-cerber' ),
						__( 'Deny authentication through wp-login.php', 'wp-cerber' )
					),
					'act_relation' => array(
						array( array( 2 ), array( 'filter_activity' => CRB_EV_LDN, 'filter_status' => 50 ), __( 'View violations in the log', 'wp-cerber' ) ),
						array( array( 1 ), array( 'filter_activity' => CRB_EV_PUR, 'filter_status' => array( 0, 10 ), 'search_url' => '/wp-login.php' ), __( 'View violations in the log', 'wp-cerber' ) )
					),
				),
				'nologinhint'     => array(
					'title' => __( 'Disable the default login error message', 'wp-cerber' ),
					'label' => __( 'Do not reveal non-existing usernames and emails in the failed login attempt message', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'nologinhint_msg' => array(
					'title'   => __( 'Custom login error message', 'wp-cerber' ),
					'label'   => __( 'An optional error message to be displayed when attempting to log in with a non-existing username or a non-existing email', 'wp-cerber' ),
					'type'    => 'textarea',
					'enabler' => array( 'nologinhint' ),
				),
				'nopasshint'      => array(
					'title'       => __( 'Disable the default reset password error message', 'wp-cerber' ),
					'label'       => __( 'Do not reveal non-existing usernames and emails in the reset password error message', 'wp-cerber' ),
					'type'        => 'checkbox',
					'requires_wp' => '5.5'
				),
				'nopasshint_msg'  => array(
					'title'       => __( 'Custom password reset error message', 'wp-cerber' ),
					'label'       => __( 'An optional error message to be displayed when attempting to reset password for a non-existing username or non-existing email address', 'wp-cerber' ),
					'type'        => 'textarea',
					'enabler'     => array( 'nopasshint' ),
					'requires_wp' => '5.5'
				),
				'nologinlang'    => array(
					'title'         => __( 'Disable login language switcher', 'wp-cerber' ),
					'type'          => 'checkbox',
					'requires_wp'   => '5.9',
					'requires_true' => function () {
						return (bool) get_available_languages();
					},
				),
			),
		),
		'custom'    => array(
			'name'    => __( 'Custom login page', 'wp-cerber' ),
			'desc'    => $custom,
			'doclink' => 'https://wpcerber.com/how-to-rename-wp-login-php/',
			'fields'  => array(
				'loginpath' => array(
					'type'      => 'prefixed',
					'prefix'    => cerber_get_site_url() . '/',
					'title'     => __( 'Custom login URL', 'wp-cerber' ),
					'label'     => __( 'A unique string that does not overlap with slugs of the existing pages or posts', 'wp-cerber' ),
					'label_pos' => 'below',
					'attr'      => array( 'title' => __( 'Custom login URL may contain Latin alphanumeric characters, dashes and underscores only', 'wp-cerber' ) ),
					'size'      => 30,
					'pattern'   => '[a-zA-Z0-9\-_]{1,100}',
				),
				'logindeferred' => array(
					'title' => __( 'Deferred rendering', 'wp-cerber' ),
					'label' => __( 'Defer rendering the custom login page', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
			),
		),
		'proactive' => array(
			'name'   => __( 'Proactive security rules', 'wp-cerber' ),
			'desc'   => __( 'Make your protection smarter!', 'wp-cerber' ),
			'fields' => array(
				'noredirect' => array(
					'title' => __( 'Disable dashboard redirection', 'wp-cerber' ),
					'label' => __( 'Disable automatic redirection to the login page when /wp-admin/ is requested by an unauthorized request', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'nonusers'   => array(
					'title' => __( 'Non-existing users are strictly prohibited', 'wp-cerber' ),
					'label' => __( 'Immediately block IP address when attempting to log in with a non-existing username', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'wplogin'    => array(
					'title' => __( 'Requests to wp-login.php are strictly prohibited', 'wp-cerber' ),
					'label' => __( 'Immediately block IP address after any request to wp-login.php', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'subnet'     => array(
					'title' => __( 'Block subnet', 'wp-cerber' ),
					'label' => __( 'Always block entire subnet Class C of intruders IP', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
			),
		),
		'stspec' => array(
			'name'   => __( 'Site-specific settings', 'wp-cerber' ),
			'fields' => array(
				'proxy'            => array(
					'title'      => __( 'Site connection', 'wp-cerber' ),
					'label'      => __( 'My site is behind a reverse proxy', 'wp-cerber' ),
					'type'       => 'checkbox',
					'doclink'    => 'https://wpcerber.com/wordpress-ip-address-detection/',
					'pre_update' => function ( $val, $old_val ) {
						if ( $val ) {
							if ( ! $ip = crb_extract_ip_from_headers( true ) ) {
								cerber_admin_notice( array(
									__( 'The reverse proxy mode has not been enabled. No valid proxy headers found.', 'wp-cerber' ),
									'<a href="https://wpcerber.com/wordpress-ip-address-detection/" target="_blank">' . __( 'Documentation', 'wp-cerber' ) . '</a>',
								) );

								$val = '';
							}
                            elseif ( ! $old_val ) {
								cerber_admin_message( sprintf( __( 'Your IP address is detected as %s. Make sure it is equal to your IP address on this page: %s.', 'wp-cerber' ), $ip, '<a href="https://wpcerber.com/what-is-my-ip/" target="_blank">What Is My IP Address</a>' ) );
							}
						}

						return $val;
					},
				),
				'cookiepref'       => array(
					'title'       => __( 'Prefix for plugin cookies', 'wp-cerber' ),
					'attr'        => array( 'title' => __( 'Prefix may contain only Latin alphanumeric characters and underscores', 'wp-cerber' ) ),
					'placeholder' => 'Latin alphanumeric characters or underscores',
					'size'        => 24,
					'pattern'     => '[a-zA-Z0-9_]{1,24}',
					'on_change'   => function () {
						if ( crb_get_settings( 'adminphp' ) ) {
							crb_htaccess_admin( 'main' );
						}
					},
					'rollback'    => function () {
						return ! empty( CRB_Globals::$htaccess_failure['main'] );
					},
				),
				'page404'          => array(
					'title' => __( 'Access to prohibited locations', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						__( 'Use 404 template from the active theme', 'wp-cerber' ),
						__( 'Display simple 404 page', 'wp-cerber' ),
						__( 'Redirect to the specified URL', 'wp-cerber' ),
					)
				),
				'page404_redirect' => array(
					'title'     => __( 'Redirection URL', 'wp-cerber' ),
					'type'      => 'url',
					'default'   => '',
					'maxlength' => 1000,
					'enabler'   => array( 'page404', 2 ),
				),
				'main_use_proxy'   => array(
					'title'         => __( 'Use WordPress proxy settings', 'wp-cerber' ),
					'label'         => __( 'Use proxy server for outgoing network connections', 'wp-cerber' ),
					'type'          => 'checkbox',
					'requires_true' => function () {
						return ( defined( 'WP_PROXY_HOST' ) && defined( 'WP_PROXY_PORT' ) );
					},
				),
				'cerber_sw_repo'   => array(
					'title'     => __( "Use WP Cerber's plugin repository", 'wp-cerber' ),
					'label'     => __( 'Allow updating WP Cerber from its official website', 'wp-cerber' ),
					'type'      => 'checkbox',
					'doclink'   => 'https://wpcerber.com/cerber-sw-repository/',
					'on_change' => function () {
						delete_site_transient( 'update_plugins' );
					},
				),
				'cerber_sw_auto'   => array(
					'title'     => __( 'Automatically update WP Cerber', 'wp-cerber' ),
					'label'     => __( 'Automatically install new versions of WP Cerber when they are available', 'wp-cerber' ),
					'type'      => 'checkbox',
					'doclink'   => 'https://wpcerber.com/cerber-sw-repository/',
				),
			),
		),
		'citadel'   => array(
			'name'   => __( 'Citadel mode', 'wp-cerber' ),
			'desc'   => __( 'In the Citadel mode nobody is able to log in except IPs from the White IP Access List. Active user sessions will not be affected.', 'wp-cerber' ),
			'fields' => array(
				'citadel_on' => array(
					'title'   => __( 'Enable authentication log monitoring', 'wp-cerber' ),
					//'doclink' => 'https://wpcerber.com/only-logged-in-wordpress-users/',
					'type'    => 'checkbox',
					'default' => 0,
				),
				'citadel'    => array(
					'title'   => __( 'Citadel mode threshold', 'wp-cerber' ),
					'type'    => 'citadel',
					'enabler' => array( 'citadel_on' ),
				),
				'ciduration' => array(
					'title'   => __( 'Citadel mode duration', 'wp-cerber' ),
					'label'   => __( 'minutes', 'wp-cerber' ),
					'type'    => 'digits',
					'min_val' => 1,
					'enabler' => array( 'citadel_on' ),
				),
				'cinotify' => array(
					'title'   => __( 'Notify admin', 'wp-cerber' ),
					'type'    => 'checkbox',
					'label'   => __( 'Send email notification to the admin', 'wp-cerber' ) . crb_test_notify_link( array( 'type' => 'citadel' ) ),
					'enabler' => array( 'citadel_on' ),
				),
			),
		),
		'activity'  => array(
			'name'   => __( 'Activity', 'wp-cerber' ),
			'fields' => array(
				'keeplog' => array(
					'title'   => __( 'Keep log records of not logged in visitors for', 'wp-cerber' ),
					'label'   => __( 'days', 'wp-cerber' ),
					//'label'  => __( 'days, not logged in visitors', 'wp-cerber' ),
					'type'    => 'digits',
					'min_val' => 1
				),
				'keeplog_auth' => array(
					'title' => __( 'Keep log records of logged in users for', 'wp-cerber' ),
					'label' => __( 'days', 'wp-cerber' ),
					//'label'  => __( 'days, logged in users', 'wp-cerber' ),
					'type'  => 'digits',
					'min_val' => 1
				),
				'cerberlab'    => array(
					'title'   => __( 'Cerber Lab connection', 'wp-cerber' ),
					'label'   => __( 'Send malicious IP addresses to the Cerber Lab', 'wp-cerber' ),
					'type'    => 'checkbox',
					'doclink' => 'https://wpcerber.com/cerber-laboratory/'
				),
				'cerberproto'  => array(
					'title'   => __( 'Cerber Lab protocol', 'wp-cerber' ),
					'type'    => 'select',
					'set'     => array(
						'HTTP',
						'HTTPS'
					),
				),
				'usefile'      => array(
					'title' => __( 'Use file', 'wp-cerber' ),
					'label' => __( 'Write failed login attempts to the file', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
			),
		),
		'prefs'     => array(
			'name'   => __( 'Personal Preferences', 'wp-cerber' ),
			'fields' => array(
				'ip_extra'       => array(
					'title' => __( 'Show IP WHOIS data', 'wp-cerber' ),
					'label' => __( 'Retrieve IP address WHOIS information when viewing the logs', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'dateformat'     => array(
					'title'     => __( 'Date format', 'wp-cerber' ),
					'label'     => sprintf( __( 'if empty, the default format %s will be used', 'wp-cerber' ), '<b>' . date( crb_get_default_dt_format(), time() ) . '</b>' ),
					'doclink'   => 'https://wpcerber.com/date-format-setting/',
					'label_pos' => 'below',
					'size'      => 16,
				),
				'plain_date'     => array(
					'title' => __( 'Date format for CSV export', 'wp-cerber' ),
					'label' => __( 'Use ISO 8601 date format for CSV export files', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'admin_lang'     => array(
					'title' => 'Use English',
					'label' => 'Use English for the plugin admin pages',
					'type'  => 'checkbox',
				),
				'top_admin_menu' => array(
					'title' => __( 'Shift admin menu', 'wp-cerber' ),
					'label' => __( 'Shift the WP Cerber admin menu to the top when navigating through WP Cerber admin pages', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'no_white_my_ip' => array(
					'title' => __( 'My IP address', 'wp-cerber' ),
					'label' => __( 'Do not add my IP address to the White IP Access List upon plugin activation', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				/*'log_errors' => array(
					'title' => __( 'Log critical errors', 'wp-cerber' ),
					'type'  => 'checkbox',
				),*/
			),
		),

		'hwp'  => array(
			'name'   => __( 'Hardening WordPress', 'wp-cerber' ),
			'desc'   => $no_wcl,
			'fields' => array(
				'stopenum'         => array(
					'title' => __( 'Stop user enumeration', 'wp-cerber' ),
					'label' => __( 'Block access to user pages like /?author=n', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'stopenum_oembed'  => array(
					'title' => __( 'Prevent username discovery', 'wp-cerber' ),
					'label' => __( 'Prevent username discovery via oEmbed', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'stopenum_sitemap' => array(
					'title' => __( 'Prevent username discovery', 'wp-cerber' ),
					'label' => __( 'Prevent username discovery via user XML sitemaps', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'nouserpages_bylogin' => array(
					'title' => __( 'Stop exposing user details', 'wp-cerber' ),
					'label' => __( 'Block access to user pages via their usernames', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'adminphp'         => array(
					'title' => __( 'Protect admin scripts', 'wp-cerber' ),
					'label' => __( 'Block unauthorized access to load-scripts.php and load-styles.php', 'wp-cerber' ),
					'type'  => 'checkbox',
					'on_change'   => function () {
						crb_htaccess_admin( 'main' );
					},
					'rollback'    => function () {
						return ! empty( CRB_Globals::$htaccess_failure['main'] );
					},
					'pre_render'  => function ( &$val, &$att ) {
						$att['disabled'] = crb_is_apache_mod_loaded( 'mod_rewrite' ) ? 0 : 1;
					},
                    // TODO: must be united with the function above in case of disabled inputs
					'row_attr'    => function ( &$att ) {
						$att['classes'][] = crb_is_apache_mod_loaded( 'mod_rewrite' ) ? '' : 'crb-disabled-colors';
					}
				),
				'phpnoupl' => array(
					'title'     => __( 'Disable PHP in uploads', 'wp-cerber' ),
					'label'     => __( 'Block execution of PHP scripts in the WordPress media folder', 'wp-cerber' ),
					'type'      => 'checkbox',
					'on_change' => function () {
						crb_htaccess_admin( 'media' );
					},
					'rollback'  => function () {
						return ! empty( CRB_Globals::$htaccess_failure['media'] );
					},
				),
				'nophperr'         => array(
					'title' => __( 'Disable PHP error displaying', 'wp-cerber' ),
					'label' => __( 'Do not show PHP errors on my website', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'xmlrpc'           => array(
					'title' => __( 'Disable XML-RPC', 'wp-cerber' ),
					'label' => __( 'Block access to the XML-RPC server (including Pingbacks and Trackbacks)', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'nofeeds'          => array(
					'title' => __( 'Disable feeds', 'wp-cerber' ),
					'label' => __( 'Block access to the RSS, Atom and RDF feeds', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
			),
		),
		'rapi' => array(
			'name'    => __( 'Access to WordPress REST API', 'wp-cerber' ),
			'desc'    => __( 'Restrict or completely block access to the WordPress REST API according to your needs', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/restrict-access-to-wordpress-rest-api/',
			'fields'  => array(
				'norestuser' => array(
					'title' => __( 'Stop user enumeration', 'wp-cerber' ),
					'label' => __( "Block access to users' data via REST API", 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'norestuser_roles'  => array(
					'title'   => __( "Allow access to users' data via REST API for these roles", 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'norestuser' ),
				),
				'norest'     => array(
					'title' => __( 'Disable REST API', 'wp-cerber' ),
					'label' => __( 'Block access to WordPress REST API except any of the following', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'restauth'   => array(
					'title'   => __( 'Logged-in users', 'wp-cerber' ),
					'label'   => __( 'Allow access to REST API for logged-in users', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'norest' ),
				),
				'restroles'  => array(
					'title'   => __( 'Allow REST API for these roles', 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'norest' ),
				),
				'restwhite'  => array(
					'title'     => __( 'Allow these namespaces', 'wp-cerber' ),
					'type'      => 'textarea',
					'delimiter' => "\n",
					'list'      => true,
					'label'     => __( 'Specify REST API namespaces to be allowed if REST API is disabled. One string per line.', 'wp-cerber' ),
					'doclink'   => 'https://wpcerber.com/restrict-access-to-wordpress-rest-api/',
					'enabler'   => array( 'norest' ),
					'callback_under' => function () {
						return '[ <a href="' . cerber_admin_link( 'traffic', array( 'filter_wp_type' => 520 ) ) . '">' . __( 'View all REST API requests', 'wp-cerber' ) . '</a> ] [ <a href="' . cerber_admin_link( 'activity', array( 'filter_activity' => 70 ) ) . '">' . __( 'View denied REST API requests', 'wp-cerber' ) . '</a>]';
					},
					'pre_update' => function ( $val ) {
						return cerber_text2array( $val, "\n", function ( $v ) {
							$v = preg_replace( '/[^a-z_\-\d\/]/i', '', $v );

							return trim( $v, '/' );
						} );
					},
				),
			),
		),

		'acc_protect'  => array(
			'name'   => __( 'Protect user accounts', 'wp-cerber' ),
			//'desc'   => 'These policies prevent site takeover (admin dashboard hijacking) by creating accounts with administrator privileges',
			'desc'   => 'These security measures prevent site takeover by preventing bad actors from creating additional administrator accounts or user privilege escalation',
			'fields' => array(
				'ds_4acc' => array(
					'label'     => __( 'Restrict user account creation and user management with the following policies', 'wp-cerber' ),
					//'doclink' => 'https://wpcerber.com/only-logged-in-wordpress-users/',
					'type'      => 'checkbox',
					'on_change' => function ( $new ) {
						if ( ! empty( $new ) ) {
							CRB_DS::enable_shadowing( 1 );
						}
						else {
							CRB_DS::disable_shadowing( 1 );
						}
					},
				),
				'ds_regs_roles' => array(
					'label'   => __( 'User registrations are limited to these roles', 'wp-cerber' ),
					//'title'   => __( 'Roles restricted to new user registrations', 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'ds_4acc' ),
				),
				'ds_add_acc'    => array(
					'label'   => __( 'Users with these roles are permitted to create new accounts', 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'ds_4acc' ),
				),
				'ds_edit_acc'   => array(
					'label'   => __( 'Users with these roles are permitted to change sensitive user data', 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'ds_4acc' ),
				),
				'ds_4acc_acl'   => array(
					'label'   => __( 'Do not apply these policies to the IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'    => 'checkbox',
					'default' => 0,
					'enabler' => array( 'ds_4acc' ),
				),
			),
		),
		'role_protect' => array(
			'name'   => __( 'Protect user roles', 'wp-cerber' ),
			'desc'   => 'These security measures prevent site takeover by preventing bad actors from creating new roles or role capabilities escalation',
			'fields' => array(
				'ds_4roles' => array(
					'label'     => __( "Restrict roles and capabilities management with the following policies", 'wp-cerber' ),
					//'doclink' => 'https://wpcerber.com/only-logged-in-wordpress-users/',
					'type'      => 'checkbox',
					'default'   => 0,
					'on_change' => function ( $new ) {
						if ( ! empty( $new ) ) {
							CRB_DS::enable_shadowing( 2 );
						}
						else {
							CRB_DS::disable_shadowing( 2 );
						}
					},
				),
				'ds_add_role'   => array(
					'label'   => __( 'Users with these roles are permitted to add new roles', 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'ds_4roles' ),
				),
				'ds_edit_role'  => array(
					'label'   => __( "Users with these roles are permitted to change role capabilities", 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'ds_4roles' ),
				),
				'ds_4roles_acl' => array(
					'label'   => __( 'Do not apply these policies to the IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'    => 'checkbox',
					'default' => 0,
					'enabler' => array( 'ds_4roles' ),
				),
			),
		),
		'opt_protect'  => array(
			'name'   => __( 'Protect site settings', 'wp-cerber' ),
			'desc'   => 'These security measures prevent malware injection by preventing bad actors from altering vital site settings',
			'fields' => array(
				'ds_4opts'       => array(
					'label'     => __( "Restrict updating site settings with the following policies", 'wp-cerber' ),
					//'doclink' => 'https://wpcerber.com/only-logged-in-wordpress-users/',
					'type'      => 'checkbox',
					'default'   => 0,
					'on_change' => function ( $new ) {
						if ( ! empty( $new ) ) {
							CRB_DS::enable_shadowing( 3 );
						}
						else {
							CRB_DS::disable_shadowing( 3 );
						}
					},
				),
				'ds_4opts_roles' => array(
					'label'   => __( 'Users with these roles are permitted to change protected settings', 'wp-cerber' ),
					'type'    => 'role_select',
					'enabler' => array( 'ds_4opts' ),
				),
				'ds_4opts_list'  => array(
					'label'   => __( 'Protected settings', 'wp-cerber' ),
					'type'    => 'checkbox_set',
					'set'     => CRB_DS::get_settings_list(),
					'enabler' => array( 'ds_4opts' ),
				),
				'ds_4opts_acl'   => array(
					'label'   => __( 'Do not apply these policies to the IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'    => 'checkbox',
					'default' => 0,
					'enabler' => array( 'ds_4opts' ),
				),
			),
		),

		'us_reg' => array(
			'name'   => __( 'User registration', 'wp-cerber' ),
			'desc'   => __( 'Restrict new user registrations by the following conditions', 'wp-cerber' ),
			'fields' => array(
				'reglimit'     => array(
					'title'   => __( 'Registration limit', 'wp-cerber' ),
					'type'    => 'reglimit',
					'default' => array( 3, 60 ),
				),
				'emrule'       => array(
					'title' => __( 'Restrict email addresses', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						__( 'No restrictions', 'wp-cerber' ),
						__( 'Deny all email addresses that match the following', 'wp-cerber' ),
						__( 'Permit only email addresses that match the following', 'wp-cerber' ),
					)
				),
				'emlist'       => array(
					'title'     => '',
					'label'     => __( 'Specify email addresses, wildcards or REGEX patterns. Use comma to separate items.', 'wp-cerber' ) . ' ' . __( 'To specify a REGEX pattern wrap a pattern in two forward slashes.', 'wp-cerber' ),
					'type'      => 'textarea',
					'list'      => true,
					'delimiter' => '/(?<!{\d),(?!\d*}.*?\/)/',
					'delimiter_show' => ',',
					'apply'     => 'strtolower',
					'default'   => array(),
					'enabler'   => array( 'emrule', '[1,2]' ),
				),
				'regwhite'     => array(
					'title' => __( 'Use White IP Access List', 'wp-cerber' ),
					'label' => __( 'Only users from IP addresses in the White IP Access List may register on the website', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'regwhite_msg' => array(
					'title'       => __( 'User message', 'wp-cerber' ),
					'placeholder' => __( "This message is displayed to a user if the IP address of the user's computer is not whitelisted", 'wp-cerber' ),
					'type'        => 'textarea',
					'enabler'     => array( 'regwhite' ),
				),
			)
		),

		'us' => array(
			'name'    => __( 'Authorized Access', 'wp-cerber' ),
			'desc'    => __( 'Grant access to the website to logged-in users only', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/only-logged-in-wordpress-users/',
			'fields'  => array(
				'authonly'      => array(
					'title'   => __( 'Authorized users only', 'wp-cerber' ),
					'label'   => __( 'Only registered and logged in website users have access to the website', 'wp-cerber' ),
					'type'    => 'checkbox',
					'default' => 0,
				),
				'authonlyacl'   => array(
					'title'   => __( 'Use White IP Access List', 'wp-cerber' ),
					'label'   => __( 'Do not apply these policy to the IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'    => 'checkbox',
					'default' => 0,
					'enabler' => array( 'authonly' ),
				),
				'authonlymsg'   => array(
					'title'       => __( 'User Message', 'wp-cerber' ),
					'placeholder' => __( 'An optional login form message', 'wp-cerber' ),
					'type'        => 'textarea',
					'apply'       => 'strip_tags',
					'enabler'     => array( 'authonly' ),
				),
				'authonlyredir' => array(
					'title'       => __( 'Redirect to URL', 'wp-cerber' ),
					//'label'       => __( 'if empty, visitors are redirected to the login page', 'wp-cerber' )
					'placeholder' => 'https://',
					'type'        => 'url',
					'default'     => '',
					'maxlength'   => 1000,
					'enabler'     => array( 'authonly' ),
				),
			)
		),

		'us_misc' => array(
			'name'   => __( 'Miscellaneous Settings', 'wp-cerber' ),
			'fields' => array(
				'prohibited'  => array(
					'title'     => __( 'Prohibited usernames', 'wp-cerber' ),
					'label'     => __( 'Usernames from this list are not allowed to log in or register. Any IP address, have tried to use any of these usernames, will be immediately blocked. Use comma to separate logins.', 'wp-cerber' ) . ' ' . __( 'To specify a REGEX pattern wrap a pattern in two forward slashes.', 'wp-cerber' ),
					'type'      => 'textarea',
					'list'      => true,
					'delimiter' => '/(?<!{\d),(?!\d*}.*?\/)/',
					'delimiter_show' => ',',
					'apply'     => 'strtolower',
					'default'   => array(),
				),
				'app_pwd'     => array(
					'title' => __( 'Application Passwords', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						1 => __( 'Enabled, access to API using standard user passwords is allowed', 'wp-cerber' ),
						2 => __( 'Enabled, no access to API using standard user passwords', 'wp-cerber' ),
						3 => __( 'Disabled', 'wp-cerber' ),
					)
				),
				'auth_expire' => array(
					'title'     => __( 'User session expiration time', 'wp-cerber' ),
					'label'     => __( 'minutes (leave empty to use the default WordPress value)', 'wp-cerber' ),
					'size'      => 6,
					'type'      => 'digits',
					'min_val'   => 1,
					'empty_val' => true, // Empty values allowed
				),
				'no_rememberme'    => array(
					'title'   => __( 'Disable "Remember Me" on the login form', 'wp-cerber' ),
					'type'    => 'checkbox',
				),
				'usersort'    => array(
					'title'   => __( 'Sort users in the Dashboard', 'wp-cerber' ),
					'label'   => __( 'by date of registration', 'wp-cerber' ),
					'type'    => 'checkbox',
				),
			)
		),

		'pdata' => array(
			'name'    => __( 'Personal Data', 'wp-cerber' ),
			//'desc'   => __( 'These features help your organization to be in compliance with data privacy laws', 'wp-cerber' ),
			'desc'    => __( 'These features help your organization to be in compliance with personal data protection laws', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/wordpress/gdpr/',
			'fields'  => array(
				'pdata_erase'    => array(
					'title'   => __( 'Enable data erase', 'wp-cerber' ),
					//'label'   => __( 'Only registered and logged in website users have access to the website', 'wp-cerber' ),
					//'doclink' => 'https://wpcerber.com/only-logged-in-wordpress-users/',
					'type'    => 'checkbox',
					'default' => 0,
				),
				'pdata_sessions' => array(
					'title'   => __( 'Terminate user sessions', 'wp-cerber' ),
					'label'   => __( 'Delete user sessions data when user data is erased', 'wp-cerber' ),
					'type'    => 'checkbox',
					'default' => 0,
					'enabler' => array( 'pdata_erase' ),
				),
				'pdata_export'   => array(
					'title'   => __( 'Enable data export', 'wp-cerber' ),
					//'label'   => __( 'Only registered and logged in website users have access to the website', 'wp-cerber' ),
					//'doclink' => 'https://wpcerber.com/only-logged-in-wordpress-users/',
					'type'    => 'checkbox',
					'default' => 0,
				),
				'pdata_act'      => array(
					'title'   => __( 'Include activity log events', 'wp-cerber' ),
					'type'    => 'checkbox',
					'default' => 0,
					'enabler' => array( 'pdata_export' ),
				),
				'pdata_trf'      => array(
					'title'   => __( 'Include traffic log entries', 'wp-cerber' ),
					'type'    => 'checkbox_set',
					'set'     => array(
						1 => __( 'Request URL', 'wp-cerber' ),
						2 => __( 'Form fields data', 'wp-cerber' ),
						3 => __( 'Cookies', 'wp-cerber' )
					),
					'enabler' => array( 'pdata_export' ),
				),
			),
		),

		'notify' => array(
			'name'    => __( 'Email notifications', 'wp-cerber' ),
			'desc'    => __( 'Configure email parameters for notifications, reports, and alerts', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/wordpress-notifications-made-easy/',
			'fields'  => array(
				'email'          => array(
					'title'       => __( 'Where to send emails generated by WP Cerber', 'wp-cerber' ),
					'placeholder' => __( 'Use comma to specify multiple values', 'wp-cerber' ),
					'delimiter'   => ',',
					'list'        => true,
					'maxlength'   => 1000,
					'label'       => sprintf( __( 'if empty, the website administrator email %s will be used', 'wp-cerber' ), '<b>' . get_site_option( 'admin_email' ) . '</b>' )
				),
				'emailrate'      => array(
					'title' => __( 'Limit on the allowed number of alerts', 'wp-cerber' ),
					'label' => __( 'notifications are allowed per hour (0 means unlimited)', 'wp-cerber' ),
					'type'  => 'digits',
				),
				'email_mask'     => array(
					'title' => __( 'Mask sensitive data', 'wp-cerber' ),
					'label' => __( 'Mask usernames and IP addresses in notifications and alerts', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'email_format' => array(
					'title'   => __( 'Message format', 'wp-cerber' ),
					'type'    => 'select',
					'set'     => array(
						2 => __( 'Plain', 'wp-cerber' ),
						1 => __( 'Brief', 'wp-cerber' ),
						0 => __( 'Verbose', 'wp-cerber' )
					),
				),
				'notify'         => array(
					'title' => __( 'Lockout notification', 'wp-cerber' ),
					'type'  => 'notify',
				),
				'notify-new-ver' => array(
					'title' => __( 'New version of WP Cerber is available', 'wp-cerber' ),
					'label' => __( 'Send notification when a new version of WP Cerber is available', 'wp-cerber' ),
					'type'  => 'checkbox'
				),
				'notify_plugin_update'      => array(
					'title' => __( 'Plugin update is available', 'wp-cerber' ),
					'label' => __( 'Send notification when a new version of a plugin is available', 'wp-cerber' ) . crb_test_notify_link( array( 'type'  => 'plugin_updates',
					                                                                                                                             'title' => __( 'Click to send now', 'wp-cerber' )
						), 'notify_plugin_update' ),
					'type'  => 'checkbox'
				),
				'notify_plugin_update_freq' => array(
					'type'    => 'digits',
					'min_val' => 1,
					'title'   => __( 'Checking frequency', 'wp-cerber' ),
					'label'   => __( 'hours interval check', 'wp-cerber' ),
					'enabler' => array( 'notify_plugin_update' ),
				),
				'notify_plugin_update_brf' => array(
					'title'   => __( 'Brief notification format', 'wp-cerber' ),
					'label'   => __( 'Hide software versions and website URLs in notifications', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'notify_plugin_update' ),
				),
				'notify_plugin_update_to'   => array(
					'title'       => __( 'Where to email notifications', 'wp-cerber' ),
					'label'       => __( 'if empty, the email addresses from the notification settings will be used', 'wp-cerber' ),
					'placeholder' => implode( ', ', cerber_get_email() ),
					'delimiter'   => ',',
					'list'        => true,
					'maxlength'   => 1000,
					'enabler'     => array( 'notify_plugin_update' ),
				),
			),
		),

		'smtp' => array(
			'name'   => __( 'Mail Transport', 'wp-cerber' ),
			'desc'   => __( 'Email server for sending emails generated by WP Cerber', 'wp-cerber' ),
			//'doclink' => 'https://wpcerber.com/wordpress-notifications-made-easy/',
			'fields' => array(
				'use_smtp'       => array(
					'title' => __( 'Use SMTP', 'wp-cerber' ),
					'label' => __( 'Use SMTP server to send emails', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'smtp_host'      => array(
					'title'     => __( 'SMTP host', 'wp-cerber' ),
					'size'      => 32,
					'maxlength' => 64,
					'enabler'   => array( 'use_smtp' ),
					'pattern'   => '[.-_\d\w]+',
					'attr'      => array( 'title' => 'Valid hostname or IP address' ),
					'validate'  => array( 'required' => 1 )
				),
				'smtp_port'      => array(
					'title'    => __( 'SMTP port', 'wp-cerber' ),
					'size'     => 32,
					'enabler'  => array( 'use_smtp' ),
					'pattern'  => '\d{1,5}',
					'attr'     => array( 'title' => 'Number' ),
					'validate' => array( 'required' => 1 )
				),
				'smtp_encr'      => array(
					'title'   => __( 'SMTP encryption', 'wp-cerber' ),
					'type'    => 'select',
					'set'     => array(
						0     => __( 'None', 'wp-cerber' ),
						'tls' => 'TLS',
						'ssl' => 'SSL',
					),
					'enabler' => array( 'use_smtp' ),
				),
				'smtp_pwd'       => array(
					'title'     => __( 'SMTP password', 'wp-cerber' ),
					'size'      => 32,
					'maxlength' => 64,
					'enabler'   => array( 'use_smtp' ),
					'validate'  => array( 'required' => 1 )
				),
				'smtp_user'      => array(
					'title'     => __( 'SMTP username', 'wp-cerber' ),
					'size'      => 32,
					'maxlength' => 64,
					'enabler'   => array( 'use_smtp' ),
					'validate'  => array( 'required' => 1 )
				),
				'smtp_from'      => array(
					'title'       => __( 'SMTP From email', 'wp-cerber' ),
					'placeholder' => __( 'If empty, the SMTP username is used', 'wp-cerber' ),
					'size'        => 32,
					'maxlength'   => 64,
					'enabler'     => array( 'use_smtp' ),
					'validate'    => array( 'satisfy' => 'is_email' )
				),
				'smtp_from_name' => array(
					'title'     => __( 'SMTP From name', 'wp-cerber' ),
					'size'      => 32,
					'maxlength' => 64,
					'enabler'   => array( 'use_smtp' ),
				),
				/*'smtp_backup'    => array(
					'title'   => __( 'Backup transport', 'wp-cerber' ),
					'type'    => 'select',
					'set'     => array(
						__( 'Do not use', 'wp-cerber' ),
						__( 'Default WordPress mailer', 'wp-cerber' ),
						__( 'Mobile messaging', 'wp-cerber' ),
					),
					'enabler' => array( 'use_smtp' ),
				),*/
			),
		),

        'pushit' => array(
			'name'    => __( 'Push notifications', 'wp-cerber' ),
			'desc'    => __( 'Get notified instantly with mobile and desktop notifications', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/wordpress-mobile-and-browser-notifications-pushbullet/',
			'fields'  => array(
				'pbtoken'  => array(
					'title' => __( 'Pushbullet access token', 'wp-cerber' ),
				),
				'pbdevice' => array(
					'title'   => __( 'Pushbullet device', 'wp-cerber' ),
					'type'    => 'select',
					'set'     => $pb_set,
					'enabler' => array( 'pbtoken' ),
				),
				'pbrate'   => array(
					'title'   => __( 'Limit on the allowed number of alerts', 'wp-cerber' ),
					'label'   => __( 'notifications are allowed per hour (0 means unlimited)', 'wp-cerber' ),
					'type'    => 'digits',
					'enabler' => array( 'pbtoken' ),
				),
				'pb_mask' => array(
					'title'   => __( 'Mask sensitive data', 'wp-cerber' ),
					'label'   => __( 'Mask usernames and IP addresses in notifications and alerts', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'pbtoken' ),
				),
				'pb_format' => array(
					'title'   => __( 'Message format', 'wp-cerber' ),
					'type'    => 'select',
					'enabler' => array( 'pbtoken' ),
					'set'     => array(
						2 => __( 'Plain', 'wp-cerber' ),
						1 => __( 'Brief', 'wp-cerber' ),
						0 => __( 'Verbose', 'wp-cerber' )
					),
				),
				'pbnotify' => array(
					'title'          => __( 'Lockout notification', 'wp-cerber' ),
					'field_switcher' => __( 'Send notification if the number of active lockouts above', 'wp-cerber' ),
					'label'          =>  crb_test_notify_link( array( 'channel' => 'pushbullet' ) ),
					'enabler'        => array( 'pbtoken' ),
					'type'           => 'digits',
				),
			),
		),
		'reports' => array(
			'name'   => __( 'Activity Reports', 'wp-cerber' ),
			'desc'   => __( 'Activity report is a summary of all logged activities and suspicious events occurred during the selected period of time', 'wp-cerber' ),
			'fields' => array(
				'enable-report' => array(
					'title' => __( 'Enable weekly reporting', 'wp-cerber' ),
					'type'  => 'checkbox'
				),
				'wreports'      => array(
					'title'   => __( 'Send weekly reports on', 'wp-cerber' ),
					'type'    => 'reptime',
					'enabler' => array( 'enable-report' ),
				),
				'wreports_7'    => array(
					'title'   => __( 'Generate 7-day reports', 'wp-cerber' ),
					'label'   => __( 'Generate report for the last 7 days instead of the previous calendar week', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'enable-report' ),
				),
				'email-report'   => array(
					'title'       => __( 'Where to email reports', 'wp-cerber' ),
					'label'       => __( 'if empty, the email addresses from the notification settings will be used', 'wp-cerber' ),
					//'placeholder' => __( 'Use comma to specify multiple values', 'wp-cerber' ),
					'placeholder' => implode( ', ', cerber_get_email() ),
					'delimiter'   => ',',
					'list'        => true,
					'maxlength'   => 1000,
					'enabler'     => array( 'enable-report' ),
				),
				'monthly_report' => array(
					'title'      => __( 'Enable monthly reporting', 'wp-cerber' ),
					'type'       => 'checkbox',
					'pre_update' => function ( $val, $old_val, &$settings ) {
						if ( $val ) {
							$chaged = false;

							if ( ! empty( $settings['monthly_30'] ) ) {
								$min = 31;
							}
							else {
								$min = 31 + absint( $settings['monthly_on']['day'] );
							}

							if ( $settings['keeplog'] < $min ) {
								$settings['keeplog'] = $min;
								$chaged = true;
							}
							if ( $settings['keeplog_auth'] < $min ) {
								$settings['keeplog_auth'] = $min;
								$chaged = true;
							}

							if ( $chaged ) {
								cerber_admin_message( sprintf( __( 'To generate complete reports, activity log entries will be kept for %d days.', 'wp-cerber' ), $min ) );
							}
						}

                        return $val;
					},
				),
				'monthly_on'     => array(
					'title'   => __( 'Send monthly reports on the nth day of month', 'wp-cerber' ),
					'type'    => 'day_time_picker',
					'period'  => 'one_month',
					'enabler' => array( 'monthly_report' ),
				),
				'monthly_30'     => array(
					'title'   => __( 'Generate 30-day reports', 'wp-cerber' ),
					'label'   => __( 'Generate report for the last 30 days instead of the previous calendar month', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'monthly_report' ),
				),
				'email_report_one_month'  => array(
					'title'       => __( 'Where to email reports', 'wp-cerber' ),
					'label'       => __( 'if empty, the email addresses from the notification settings will be used', 'wp-cerber' ),
					//'placeholder' => __( 'Use comma to specify multiple values', 'wp-cerber' ),
					'placeholder' => implode( ', ', cerber_get_email() ),
					'delimiter'   => ',',
					'list'        => true,
					'maxlength'   => 1000,
					'enabler'     => array( 'monthly_report' ),
				),
			),
		),

		'tmain'  => array(
			'name'    => __( 'Traffic Inspection', 'wp-cerber' ),
			'desc'    => __( 'Traffic Inspector is a context-aware web application firewall (WAF) that protects your website by recognizing and denying malicious HTTP requests', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/traffic-inspector-in-a-nutshell/',
			'fields'  => array(
				'tienabled' => array(
					'title' => __( 'Traffic inspection mode', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						__( 'Disabled', 'wp-cerber' ),
						__( 'Maximum compatibility', 'wp-cerber' ),
						__( 'Maximum security', 'wp-cerber' )
					),
				),
				'tiipwhite' => array(
					'title'   => __( 'Use White IP Access List', 'wp-cerber' ),
					'label' => __( 'Use less restrictive security filters for IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'tienabled', '[1,2]' ),
				),
				'tiwhite' => array(
					'title'      => __( 'Request whitelist', 'wp-cerber' ),
					'type'       => 'textarea',
					'delimiter'  => "\n",
					'list'       => true,
					'label'      => __( 'Enter a request URI to exclude the request from inspection. One item per line.', 'wp-cerber' ) . ' ' . __( 'To specify a REGEX pattern, enclose a whole line in two braces.', 'wp-cerber' ),
					'doclink'    => 'https://wpcerber.com/wordpress-probing-for-vulnerable-php-code/',
					'enabler'    => array( 'tienabled', '[1,2]' ),
					'pre_update' => function ( $val ) {
						$val = cerber_text2array( $val, "\n" );

                        foreach ( $val as $item ) {
							if ( strrpos( $item, '?' ) ) {
								cerber_admin_notice( 'You may not specify the query string with a question mark: ' . htmlspecialchars( $item, ENT_SUBSTITUTE ) );
							}
							if ( strrpos( $item, '://' ) ) {
								cerber_admin_notice( 'You may not specify the full URL: ' . htmlspecialchars( $item, ENT_SUBSTITUTE ) );
							}
						}

                        return $val;
					},
				),
			),
		),
		'tierrs' => array(
			'name'   => __( 'Erroneous Request Shielding', 'wp-cerber' ),
			//'desc'   => 'Block IP addresses that generate excessive HTTP 404 requests.',
			'desc'   => __( 'Block IP addresses that send excessive requests for non-existing pages or scan website for security breaches', 'wp-cerber' ),
			'fields' => array(
				'tierrmon'    => array(
					'title' => __( 'Error shielding mode', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						__( 'Disabled', 'wp-cerber' ),
						__( 'Maximum compatibility', 'wp-cerber' ),
						__( 'Maximum security', 'wp-cerber' )
					)
				),
				'tierrnoauth' => array(
					'title'   => __( 'Ignore logged-in users', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'tierrmon', '[1,2]' ),
				),
			),
		),
		'tlog'   => array(
			'name'    => __( 'Traffic Logging', 'wp-cerber' ),
			'desc'    => __( 'Enable optional traffic logging if you need to monitor suspicious and malicious activity or solve security issues', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/wordpress-traffic-logging/',
			'fields'  => array(
				'timode'         => array(
					'title' => __( 'Logging mode', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						0 => __( 'Logging disabled', 'wp-cerber' ),
						3 => __( 'Minimal', 'wp-cerber' ),
						1 => __( 'Smart', 'wp-cerber' ),
						2 => __( 'All traffic', 'wp-cerber' )
					),
				),
				'tilogrestapi'   => array(
					'title'   => __( 'Log all REST API requests', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', 3 ),
				),
				'tilogxmlrpc'    => array(
					'title'   => __( 'Log all XML-RPC requests', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', 3 ),
				),
				'tinocrabs'      => array(
					'title'   => __( 'Do not log known crawlers', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'tinolocs'       => array(
					'title'     => __( 'Do not log these locations', 'wp-cerber' ),
					'type'      => 'textarea',
					'list'      => true,
					'delimiter' => "\n",
					'label'     => __( 'Specify URL paths to exclude requests from logging. One item per line.', 'wp-cerber' ) . ' ' . __( 'To specify a REGEX pattern, enclose a whole line in two braces.', 'wp-cerber' ),
					'enabler'   => array( 'timode', '[1,2,3]' ),
				),
				'tinoua'         => array(
					'title'     => __( 'Do not log these User-Agents', 'wp-cerber' ),
					'type'      => 'textarea',
					'list'      => true,
					'delimiter' => "\n",
					'label'     => __( 'Specify User-Agents to exclude requests from logging. One item per line.', 'wp-cerber' ),
					'enabler'   => array( 'timode', '[1,2,3]' ),
				),
				'tifields'       => array(
					'title'   => __( 'Save request fields', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'timask'         => array(
					'title'       => __( 'Mask these form fields', 'wp-cerber' ),
					'maxlength'   => 1000,
					'placeholder' => __( 'Use comma to specify multiple values', 'wp-cerber' ),
					'list'        => true,
					'delimiter'   => ',',
					'enabler'     => array( 'timode', '[1,2,3]' ),
				),
				'tihdrs'         => array(
					'title'   => __( 'Save request headers', 'wp-cerber' ),
					'label'   => __( '', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'tihdrs_sent'    => array(
					'title'   => __( 'Save response headers', 'wp-cerber' ),
					'label'   => __( '', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'ticandy'        => array(
					'title'   => __( 'Save request cookies', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'ticandy_sent'   => array(
					'title'   => __( 'Save response cookies', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'tisenv'         => array(
					'title'   => __( 'Save $_SERVER', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'tiphperr'       => array(
					'title'   => __( 'Save software errors', 'wp-cerber' ),
					'type'    => 'checkbox',
					'enabler' => array( 'timode', '[1,2,3]' ),
				),
				'tithreshold' => array(
					'title'   => __( 'Page generation time threshold', 'wp-cerber' ),
					'label'   => __( 'milliseconds', 'wp-cerber' ),
					'type'    => 'digits',
					'size'    => 4,
					'enabler' => array( 'timode', '[1,2,3]' ),
					'pre_update' => function ( $val ) {
						if ( $val ) {
							$val = crb_absint( $val );
						}

                        return $val;
					},
				),
				'tikeeprec' => array(
					'title'      => __( 'Keep log records of not logged in visitors for', 'wp-cerber' ),
					'label'      => __( 'days', 'wp-cerber' ),
					'type'       => 'digits',
					'size'       => 4,
					'min_val'    => 1,
					/*'pre_update' => function ( $val ) {
						$val = crb_absint( $val );
						if ( $val == 0 ) {
							$val = 1;
							cerber_admin_notice( 'You may not set <b>Keep records for</b> to 0 days. To completely disable logging, set <b>Logging mode</b> to Logging disabled.' );
						}

						return $val;
					},*/
				),
				'tikeeprec_auth' => array(
					'title'   => __( 'Keep log records of logged in users for', 'wp-cerber' ),
					'label'   => __( 'days', 'wp-cerber' ),
					'type'    => 'digits',
					'size'    => 4,
					'min_val' => 1,
				),
			),
		),

		'smain' => array(
			'name'    => __( 'Scanner settings', 'wp-cerber' ),
			'desc'    => __( 'The scanner monitors file changes, verifies the integrity of WordPress, plugins, and themes, and detects malware', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/wordpress-security-scanner/',
			'fields'  => array(
				'scan_inew'    => array(
					'title' => __( 'Monitor new files', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						0 => __( 'Disabled', 'wp-cerber' ),
						1 => __( 'Executable files', 'wp-cerber' ),
						2 => __( 'All files', 'wp-cerber' ),
					)
				),
				'scan_imod'    => array(
					'title' => __( 'Monitor modified files', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						0 => __( 'Disabled', 'wp-cerber' ),
						1 => __( 'Executable files', 'wp-cerber' ),
						2 => __( 'All files', 'wp-cerber' ),
					)
				),
				'scan_tmp'     => array(
					'title' => __( "Scan web server's temporary directories", 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_sess'    => array(
					'title' => __( 'Scan the sessions directory', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_uext'    => array(
					'title'        => __( 'Unwanted file extensions', 'wp-cerber' ),
					'list'         => true,
					'delimiter'    => ',',
					'regex_filter' => '[".?*/\'\\\\]',
					'apply'        => 'strtolower',
					'deny_filter'  => array( 'php', 'js', 'css', 'txt', 'po', 'mo', 'pot' ),
					'label'        => __( 'Specify file extensions to search for. Full scan only. Use comma to separate items.', 'wp-cerber' )
				),
				'scan_cpt'     => array(
					'title'     => __( 'Custom signatures', 'wp-cerber' ),
					'type'      => 'textarea',
					'list'      => true,
					'delimiter' => "\n",
					'label'     => __( 'Specify custom PHP code signatures. One item per line. To specify a REGEX pattern, enclose a whole line in two braces.', 'wp-cerber' ) . ' <a target="_blank" href="https://wpcerber.com/malware-scanner-settings/">Read more</a>'
				),
				'scan_exclude' => array(
					'title'      => __( 'Directories to exclude', 'wp-cerber' ),
					'type'       => 'textarea',
					'delimiter'  => "\n",
					'list'       => true,
					'label'      => __( 'Specify directories to exclude from scanning. One directory per line.', 'wp-cerber' ),
					'pre_update' => function ( $val ) {
						return cerber_normal_dirs( $val );
					},
					'on_change' => function () {
						cerber_admin_message( __( 'Please run a new scan to get consistent and accurate results.', 'wp-cerber' ) );
					},
				),
			),
		),
		'smisc' => array(
			'name'   => __( 'Miscellaneous Settings', 'wp-cerber' ),
			'fields' => array(
				'scan_chmod'    => array(
					'title' => __( 'Change filesystem permissions', 'wp-cerber' ),
					'label' => __( 'Change file and directory permissions if it is required to delete files', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_debug'    => array(
					'title'    => __( 'Enable diagnostic logging', 'wp-cerber' ),
					'label'    => sprintf( __( 'Once enabled, the log is available here: %s', 'wp-cerber' ), ' <a target="_blank" href="' . cerber_admin_link( 'diag-log' ) . '">' . __( 'Diagnostic Log', 'wp-cerber' ) . '</a>' ),
					'type'     => 'checkbox',
					'diag_log' => 'Logging of the scan operations',
				),
				'scan_qcleanup' => array(
					'title'   => __( 'Delete quarantined files after', 'wp-cerber' ),
					'type'    => 'digits',
					'min_val' => 1,
					'label'   => __( 'days', 'wp-cerber' ),
				),
			),
		),

		's1' => array(
			'name'    => __( 'Automated recurring scan schedule', 'wp-cerber' ),
			'desc'    => __( 'The scanner automatically scans the website, removes malware and sends email reports with the results of a scan', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/automated-recurring-malware-scans/',
			'fields'  => array(
				'scan_aquick' => array(
					'title' => __( 'Launch Quick Scan', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => cerber_get_qs(),
				),
				'scan_afull'  => array(
					'title'          => __( 'Launch Full Scan', 'wp-cerber' ),
					'type'           => 'timepicker',
					'field_switcher' => __( 'once a day at', 'wp-cerber' ),
				),
			),
		),
		's2' => array(
			'name'    => __( 'Scan results reporting', 'wp-cerber' ),
			'desc'    => __( 'Configure what issues to include in the email report and the condition for sending reports', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/automated-recurring-malware-scans/',
			'fields'  => array(
				'scan_reinc'   => array(
					'title' => __( 'Report an issue if any of the following is true', 'wp-cerber' ),
					'type'  => 'checkbox_set',
					'set'   => array(
						           1 => __( 'Low severity', 'wp-cerber' ),
						           2 => __( 'Medium severity', 'wp-cerber' ),
						           3 => __( 'High severity', 'wp-cerber' )
					           ) + cerber_get_issue_label( array( CERBER_IMD, CERBER_UXT, 50, 51, CERBER_VULN ) ),
				),
				'scan_relimit' => array(
					'title' => __( 'Send email report', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array(
						1 => __( 'After every scan', 'wp-cerber' ),
						3 => __( 'If any changes in scan results occurred', 'wp-cerber' ),
						5 => __( 'If new issues found', 'wp-cerber' ),
					)
				),
				'scan_isize'   => array(
					'title' => __( 'Include file sizes', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_ierrors' => array(
					'title' => __( 'Include scan errors', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'email-scan'   => array(
					'title'       => __( 'Email Address', 'wp-cerber' ),
					'label'       => __( 'if empty, the email addresses from the notification settings will be used', 'wp-cerber' ),
					//'placeholder' => __( 'Use comma to specify multiple values', 'wp-cerber' ),
					'placeholder' => implode( ', ', cerber_get_email() ),
					'delimiter'   => ',',
					'list'        => true,
					'maxlength'   => 1000,
				),
			),
		),

		'scanpls'     => array(
			'name'    => __( 'Automatic cleanup of malware and suspicious files', 'wp-cerber' ),
			'desc'    => __( 'These policies are automatically enforced at the end of every scan based on its results. All affected files are moved to the quarantine.', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/automatic-malware-removal-wordpress/',
			'fields'  => array(
				'scan_delunatt'  => array(
					'title' => __( 'Delete unattended files', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_delupl'    => array(
					'title' => __( 'Delete files in the WordPress uploads directory', 'wp-cerber' ),
					'type'  => 'checkbox_set',
					'set'   => array(
						1 => __( 'Low severity', 'wp-cerber' ),
						2 => __( 'Medium severity', 'wp-cerber' ),
						3 => __( 'High severity', 'wp-cerber' ),
					),
				),
				'scan_delunwant' => array(
					'title' => __( 'Delete files with unwanted extensions', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
			),
		),
		'suploads'    => array(
			'name'   => __( 'WordPress uploads analysis', 'wp-cerber' ),
			'desc'   => __( 'Keep the WordPress uploads directory clean and secure. Detect injected files with public web access, report them, and remove malicious ones.', 'wp-cerber' ),
			//'doclink' => 'https://wpcerber.com/wordpress-security-scanner/',
			//'pro_section'    => 1,
			'fields' => array(
				'scan_media'      => array(
					'title' => __( 'Analyze the uploads directory', 'wp-cerber' ),
					'label' => __( 'Analyze the WordPress uploads directory to detect injected files', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_skip_media' => array(
					'title'        => __( 'Skip files with these extensions', 'wp-cerber' ),
					//'label'        => __( 'List of file extensions to ignore', 'wp-cerber' ),
					'label'        => __( 'Ignore files with these extensions', 'wp-cerber' ),
					'placeholder'  => __( 'Use comma to separate multiple extensions', 'wp-cerber' ),
					'list'         => true,
					'delimiter'    => ',',
					'regex_filter' => '[".?*/\'\\\\]',
					'apply'        => 'strtolower',
					'maxlength'    => 1000,
					'enabler'      => array( 'scan_media' ),
				),
				'scan_del_media'  => array(
					'title'        => __( 'Prohibited extensions', 'wp-cerber' ),
					//'label'        => __( 'List of file extensions allowed to be deleted', 'wp-cerber' ),
					'label'        => __( 'Delete publicly accessible files with these extensions', 'wp-cerber' ),
					'placeholder'  => __( 'Use comma to separate multiple extensions', 'wp-cerber' ),
					'list'         => true,
					'delimiter'    => ',',
					'regex_filter' => '[".?*/\'\\\\]',
					'apply'        => 'strtolower',
					'maxlength'    => 1000,
					'enabler'      => array( 'scan_media' ),
				),
			),
		),
		'scanrecover' => array(
			'name'   => __( 'Automatic recovery of modified and infected files', 'wp-cerber' ),
			'fields' => array(
				'scan_recover_wp' => array(
					'title' => __( 'Recover WordPress files', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_recover_pl' => array(
					'title' => __( "Recover plugins' files", 'wp-cerber' ),
					'type'  => 'checkbox',
				),
			),
		),
		'scanexcl'    => array(
			'name'   => __( 'Global Exclusions', 'wp-cerber' ),
			'desc' => __( 'These files will never be deleted during automatic cleanup.', 'wp-cerber' ) . ' ' . __( 'Be careful about configuring these settings. Improper configuration may lead to failure to delete malicious files.', 'wp-cerber' ),
			'fields' => array(
				'scan_delexdir'  => array(
					'title'     => __( 'Files in these directories', 'wp-cerber' ),
					'type'      => 'textarea',
					'delimiter' => "\n",
					'list'      => true,
					'label'     => __( 'Use absolute or relative to the home directory paths. One directory per line.', 'wp-cerber' ),
					'pre_update' => function ( $val ) {
						return cerber_normal_dirs( $val );
					},
				),
				'scan_delexext'  => array(
					'title'        => __( 'Files with these extensions', 'wp-cerber' ),
					'type'         => 'textarea',
					'list'         => true,
					'delimiter'    => ',',
					'regex_filter' => '[".?*/\'\\\\]',
					'apply'        => 'strtolower',
					'label'        => __( 'Use comma to separate items.', 'wp-cerber' )
				),
				'scan_nodeltemp' => array(
					'title' => __( 'Files in temporary directories', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'scan_nodelsess' => array(
					'title' => __( 'Files in the sessions directory', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
			),
		),


		'antibot'      => array(
			'name'    => __( 'Cerber anti-spam engine', 'wp-cerber' ),
			'desc'    => __( 'Spam protection for registration, comment, and other forms on the website', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/antispam-for-wordpress-contact-forms/',
			'seclinks' => array(
				array(
					__( 'View bot events', 'wp-cerber' ),
					cerber_admin_link( 'activity', array( 'filter_status' => CRB_STS_11 ) )
				)
			),
			'fields' => array(
				'botsreg'    => array(
					'title' => __( 'Registration form', 'wp-cerber' ),
					'label' => __( 'Protect registration form with bot detection engine', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'botscomm'   => array(
					'title' => __( 'Comment form', 'wp-cerber' ),
					'label' => __( 'Protect comment form with bot detection engine', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'customcomm' => array(
					'title' => __( 'Custom comment URL', 'wp-cerber' ),
					'label' => __( 'Use custom URL for the WordPress comment form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'botsany'    => array(
					'title' => __( 'Other forms', 'wp-cerber' ),
					'label' => __( 'Protect all forms on the website with bot detection engine', 'wp-cerber' ),
					'type'  => 'checkbox',
					'callback_under' => function () {
						if ( ! defined( 'CERBER_DISABLE_SPAM_FILTER' ) ) {
							return '';
						}

						$list = explode( ',', (string) CERBER_DISABLE_SPAM_FILTER );
						$titles = array();
						$home = cerber_get_site_url();

						foreach ( $list as $pid ) {
							if ( $t = get_the_title( $pid ) ) {
								$titles [] = '<a href="' . $home . '/?p=' . (int) $pid . '" target="_blank">' . $t . '</a> (ID ' . $pid . ')';
							}
						}

						if ( $titles ) {
							$ret = '<p>Forms on the following pages are not analyzed: form submissions will be denied by the anti-spam engine.</p>';
							$ret .= '<ul style="margin-bottom: 0;"><li>' . implode( '</li><li>', $titles ) . '</li></ul>';
						}
						else {
							$ret = 'Note: you have specified the CERBER_DISABLE_SPAM_FILTER constant, but no pages with given IDs found.';
						}

						return $ret;
					}
				),
			)
		),
		'antibot_more' => array(
			'name'   => __( 'Adjust anti-spam engine', 'wp-cerber' ),
			'desc'   => __( 'These settings enable you to fine-tune the behavior of anti-spam algorithms and avoid false positives', 'wp-cerber' ),
			'fields' => array(
				'botssafe'   => array(
					'title' => __( 'Safe mode', 'wp-cerber' ),
					'label' => __( 'Use less restrictive policies (allow AJAX)', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'botsnoauth' => array(
					'title' => __( 'Logged-in users', 'wp-cerber' ),
					'label' => __( 'Disable bot detection engine for logged-in users', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'botsipwhite' => array(
					'title' => __( 'Use White IP Access List', 'wp-cerber' ),
					'label' => __( 'Disable bot detection engine for IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'botswhite'  => array(
					'title'     => __( 'Query whitelist', 'wp-cerber' ),
					'label'     => __( 'Enter a part of query string or query path to exclude a request from inspection by the engine. One item per line.', 'wp-cerber' ),
					'type'      => 'textarea',
					'list'      => true,
					'delimiter' => "\n",
					'doclink'   => 'https://wpcerber.com/antispam-exception-for-specific-http-request/',
				),
			)
		),
		'commproc'     => array(
			'name'   => __( 'Comment processing', 'wp-cerber' ),
			'desc'   => __( 'How the plugin processes comments submitted through the standard comment form', 'wp-cerber' ),
			'fields' => array(
				'spamcomm'   => array(
					'title' => __( 'If a spam comment detected', 'wp-cerber' ),
					'type'  => 'select',
					'set'   => array( __( 'Deny it completely', 'wp-cerber' ), __( 'Mark it as spam', 'wp-cerber' ) )
				),
				'trashafter' => array(
					'title'          => __( 'Trash spam comments', 'wp-cerber' ),
					'type'           => 'digits',
					'field_switcher' => __( 'Move spam comments to trash after', 'wp-cerber' ),
					'label'          => __( 'days', 'wp-cerber' ),
				),
			)
		),

		'recap' => array(
			'name'    => __( 'reCAPTCHA settings', 'wp-cerber' ),
			'desc'    => __( 'Before you can start using reCAPTCHA, you have to obtain Site key and Secret key on the Google website', 'wp-cerber' ),
			'doclink' => 'https://wpcerber.com/how-to-setup-recaptcha/',
			'seclinks' => array(
				array(
					__( 'View reCAPTCHA events', 'wp-cerber' ),
					cerber_admin_link( 'activity', array( 'filter_status' => array( 531, CRB_STS_532, 533, 534 ) ) )
				)
			),
			'fields'  => array(
				'sitekey'       => array(
					'title' => __( 'Site key', 'wp-cerber' ),
					'type'  => 'text',
				),
				'secretkey'     => array(
					'title' => __( 'Secret key', 'wp-cerber' ),
					'type'  => 'text',
				),
				'invirecap'     => array(
					'title' => __( 'Invisible reCAPTCHA', 'wp-cerber' ),
					'label' => __( 'Enable invisible reCAPTCHA', 'wp-cerber' ) . ' ' . __( '(do not enable it unless you get and enter the Site and Secret keys for the invisible version)', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recapreg'      => array(
					'title' => __( 'Registration form', 'wp-cerber' ),
					'label' => __( 'Enable reCAPTCHA for WordPress registration form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recapwooreg'   => array(
					'title' => '',
					'label' => __( 'Enable reCAPTCHA for WooCommerce registration form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recaplost'     => array(
					'title' => __( 'Lost password form', 'wp-cerber' ),
					'label' => __( 'Enable reCAPTCHA for WordPress lost password form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recapwoolost'  => array(
					'title' => '',
					'label' => __( 'Enable reCAPTCHA for WooCommerce lost password form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recaplogin'    => array(
					'title' => __( 'Login form', 'wp-cerber' ),
					'label' => __( 'Enable reCAPTCHA for WordPress login form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recapwoologin' => array(
					'title' => '',
					'label' => __( 'Enable reCAPTCHA for WooCommerce login form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recapcom'      => array(
					'title' => __( 'Comment form', 'wp-cerber' ),
					'label' => __( 'Enable reCAPTCHA for WordPress comment form', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recapcomauth' => array(
					'title'   => '',
					'label'   => __( 'Disable reCAPTCHA for logged-in users', 'wp-cerber' ),
					'enabler' => array( 'recapcom' ),
					'type'    => 'checkbox',
				),
				'recapipwhite'   => array(
					'title' => __( 'Use White IP Access List', 'wp-cerber' ),
					'label' => __( 'Disable reCAPTCHA for IP addresses in the White IP Access List', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'recaplimit'    => array(
					'title' => __( 'Limit attempts', 'wp-cerber' ),
					'label' => __( 'Lock out IP address for %s minutes after %s failed attempts within %s minutes', 'wp-cerber' ),
					'type'  => 'limitz',
				),
			)
		),

		'master_settings' => array(
			'name'   => __( 'Main website settings', 'wp-cerber' ),
			'fields' => array(
				/*('master_cache'    => array(
					'title' => __( 'Cache Time', 'wp-cerber' ),
					'type'  => 'text',
				),*/
				'master_tolist'  => array(
					'title' => __( 'Return to the website list', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'master_swshow'  => array(
					'title' => __( 'Show "Switched to" notification', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'master_at_site' => array(
					'title' => __( 'Add @ site to the page title', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'master_locale'  => array(
					'title' => __( 'Use my language', 'wp-cerber' ),
					'label' => __( 'Display admin pages of remote websites using my language', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'cerber_hub_proxy' => array(
					'title' => __( 'Use WordPress proxy settings', 'wp-cerber' ),
					'label' => __( 'Use proxy server to connect to managed websites', 'wp-cerber' ),
					'type'  => 'checkbox',
					'requires_true' => function () {
						return ( defined( 'WP_PROXY_HOST' ) && defined( 'WP_PROXY_PORT' ) );
					},
				),
				/*
				'master_dt'      => array(
					'title' => __( 'Use master datetime format', 'wp-cerber' ),
					'type'  => 'checkbox',
				),
				'master_tz'      => array(
					'title' => __( 'Use master timezone', 'wp-cerber' ),
					'type'  => 'checkbox',
				),*/
				'master_diag' => array(
					'title'    => __( 'Enable diagnostic logging', 'wp-cerber' ),
					'label'    => sprintf( __( 'Once enabled, the log is available here: %s', 'wp-cerber' ), ' <a target="_blank" href="' . cerber_admin_link( 'diag-log' ) . '">' . __( 'Diagnostic Log', 'wp-cerber' ) . '</a>' ),
					'type'     => 'checkbox',
					'diag_log' => 'Logging of the main website operations',
				),
			)
		),
		'slave_settings'  => array(
			'name'   => '',
			//'info'   => __( 'User related settings', 'wp-cerber' ),
			'fields' => array(
				'slave_ips'    => array(
					'title' => __( 'Limit access by IP address', 'wp-cerber' ),
					//'placeholder' => 'The IP address of the main website',
					'type'  => 'text',
				),
				'slave_access' => array(
					'title'     => __( 'Access to this website', 'wp-cerber' ),
					'type'      => 'select',
					'set'       => array(
						2 => __( 'Full access mode', 'wp-cerber' ),
						4 => __( 'Read-only mode', 'wp-cerber' ),
						8 => __( 'Disabled', 'wp-cerber' )
					),
					'label_pos' => 'below',
					'default'   => 2,
				),
				'slave_diag' => array(
					'title'    => __( 'Enable diagnostic logging', 'wp-cerber' ),
					'label'    => sprintf( __( 'Once enabled, the log is available here: %s', 'wp-cerber' ), ' <a target="_blank" href="' . cerber_admin_link( 'diag-log' ) . '">' . __( 'Diagnostic Log', 'wp-cerber' ) . '</a>' ),
					'default'  => 0,
					'type'     => 'checkbox',
					'diag_log' => 'Logging of the operations initiated by the main website',
				),
			)
		)
	);

	if ( ! empty( $add['sections'] ) ) {
		$sections = array_merge( $sections, $add['sections'] );
	}

	if ( ! lab_lab() ) {
		$sections['slave_settings']['fields']['slave_access']['label'] = '<a href="https://wpcerber.com/pro/" target="_blank">' . __( 'The full access mode requires the PRO version of WP Cerber', 'wp-cerber' ) . '</a>';
		$sections['smtp']['desc'] .= ' [ <a href="https://wpcerber.com/pro/" target="_blank">' . __( 'Available in the professional version of WP Cerber', 'wp-cerber' ) . '</a> ]';
	}

	if ( $screen_id = crb_array_get( $args, 'screen_id' ) ) {
		if ( empty( $screens[ $screen_id ] ) ) {
			return false;
		}

		return array_intersect_key( $sections, array_flip( $screens[ $screen_id ] ) );
	}

	if ( $setting = crb_array_get( $args, 'setting' ) ) {
		foreach ( $sections as $s ) {
			if ( isset( $s['fields'][ $setting ] ) ) {
				return $s['fields'][ $setting ];
			}
		}

		return false;

	}

	return $sections;
}

function crb_settings_processor() {

	if ( ! cerber_is_admin_page()
	     && ! strpos( $_SERVER['REQUEST_URI'], '/options.php' )
	     && ! nexus_is_valid_request() ) {
		return;
	}

	if ( crb_get_settings( 'top_admin_menu' ) ) {
		add_filter( 'admin_body_class', function ( $classes ) {
			return $classes . ' crb-top_admin_menu_enabled ';
		} );
	}

	cerber_wp_settings_setup( cerber_get_setting_id() );

	if ( cerber_is_http_post()
	     && ! nexus_get_context() ) { // it's crucial

		cerber_process_settings_form();
	}

}

/**
 * Configure WP Settings API stuff for a given admin page
 *
 * @since 7.9.7
 *
 * @param $screen_id string
 * @param $sections array
 */
function cerber_wp_settings_setup( $screen_id, $sections = array() ) {
	if ( ! $sections && ! $sections = cerber_settings_config( array( 'screen_id' => $screen_id ) ) ) {
		return;
	}

	$option = 'cerber-' . $screen_id;
	register_setting( 'cerberus-' . $screen_id, $option );

	global $tmp;
	foreach ( $sections as $section_id => $section_config ) {

		$desc = crb_array_get( $section_config, 'desc' );

		if ( $links = crb_array_get( $section_config, 'seclinks' ) ) {
			foreach ( $links as $link ) {
				$desc .= '<span class="crb-insetting-link">[ <a target="_blank" href="' . $link[1] . '">' . $link[0] . '</a> ]</span>';
			}
		}

		if ( $doclink = crb_array_get( $section_config, 'doclink' ) ) {
			$desc .= '<span class="crb-insetting-link">[ <a class="" target="_blank" href="' . $doclink . '">' . __( 'Documentation', 'wp-cerber' ) . '</a> ]</span>';
		}

		$tmp[ $section_id ] = '<span class="crb-section-desc">' . $desc . '</span>';

		add_settings_section( $section_id, crb_array_get( $section_config, 'name', '' ), function ( $sec ) {
			global $tmp;
			if ( $tmp[ $sec['id'] ] ) {
				echo $tmp[ $sec['id'] ];
			}
		}, $option );

		foreach ( $section_config['fields'] as $field => $config ) {

			if ( ( $req_wp = $config['requires_wp'] ?? false )
			     && ! crb_wp_version_compare( $req_wp ) ) {
				continue;
			}

			if ( ( $cb = $config['requires_true'] ?? false )
			     && is_callable( $cb )
			     && ! $cb() ) {
				continue;
			}

			if ( in_array( $field, CRB_PRO_SETTINGS ) && ! lab_lab() ) {
				continue;
			}

			$config['setting'] = $field;
			$config['group'] = $screen_id;

			$config['type'] = $config['type'] ?? 'text';

			$class = array( 'crb-setting-row' ); // Setting row (tr) class, to specify the input class use 'input_class'
    		$attrs = array();

			if ( ( $row_attr = ( $config['row_attr'] ?? false ) )
			     && is_callable( $row_attr ) ) {

				call_user_func_array( $row_attr, array( &$attrs ) );
			}

			if ( $class_list = $attrs['classes'] ?? false ) {
				$class = array_merge( $class, $class_list );
			}

			if ( isset( $config['enabler'] ) ) {
				$class[] = 'crb-font-normal';
			}

			if ( $config['type'] == 'hidden' ) {
				$class[] = 'crb-display-none';
			}

			if ( isset( $config['enabler'] ) ) {
				$class[] = crb_check_enabler( $config, crb_get_settings( $config['enabler'][0] ) );
			}

			$config['class'] = implode( ' ', $class );

			add_settings_field( $field, crb_array_get( $config, 'title', '' ), 'cerber_field_show', $option, $section_id, $config );
		}
	}
}

function cerber_get_setting_id( $tab = null ) {
	$id = ( ! $tab ) ? cerber_get_get( 'tab', CRB_SANITIZE_ID ) : $tab;
	if ( ! $id ) {
		$id = cerber_get_wp_option_id();
	}
	if ( ! $id ) {
		$id = crb_admin_get_page();
	}
	// Mapping: some tab names (or page id) doesn't match WP setting names
	// tab => settings id
	$map = array(
		'scan_settings'    => 'scanner', // define('CERBER_OPT_S','cerber-scanner');
		'scan_schedule'    => 'schedule', // define('CERBER_OPT_E','cerber-schedule');
		'scan_policy'      => 'policies',
		'ti_settings'      => 'traffic',
		'captcha'          => 'recaptcha',
		'cerber-recaptcha' => 'antispam',
		'global_policies'  => 'users',
		'cerber-shield'    => 'user_shield',

		'cerber-nexus' => 'nexus-slave',
		'nexus_slave'  => 'nexus-slave',
	);

	crb_addon_settings_mapper( $map );

	if ( isset( $map[ $id ] ) ) {
		return $map[ $id ];
	}

	return $id;
}

/**
 * Works when updating WP options
 *
 * @return bool|string
 */
function cerber_get_wp_option_id( $option_page = null ) {

	if ( ! $option_page ) {
		$option_page = crb_array_get( $_POST, 'option_page' );
	}
	if ( $option_page && ( 0 === strpos( $option_page, 'cerberus-' ) ) ) {
		return substr( $option_page, 9 ); // 8 = length of 'cerberus-'
	}

	return false;
}

/**
 * Generates and displays WP Cerber settings form
 *
 * @param string $group WP settings API group
 * @param array $hidden Optional hidden fields
 *
 * @return void
 */
function cerber_show_settings_form( $group = '', $hidden = array() ) {

	if ( is_multisite()
	     || nexus_is_valid_request() ) {
		$action = '';  // WP settings API doesn't work in multisite
	}
	else {
		$action = 'options.php'; // Standard way
	}

	?>
	<div class="crb-admin-form">
        <form id="crb-form-<?php echo crb_boring_escape( $group ); ?>" class="crb-settings" method="post" action="<?php echo $action; ?>">

			<?php

			$hidden[ CRB_SETTINGS_GROUP ] = $group;

			if ( $hidden ) {
				foreach ( $hidden as $name => $value ) {
					echo '<input type="hidden" name="' . crb_boring_escape( $name ) . '" value="' . crb_boring_escape( $value ) . '">';
				}
			}

			cerber_nonce_field( 'control', true );

			settings_fields( 'cerberus-' . $group ); // option group name, the same as used in register_setting().
			do_settings_sections( 'cerber-' . $group ); // the same as used in add_settings_section()	$page

			echo '<div style="padding-left: 220px">';

			echo crb_admin_submit_button();

			echo '</div>';

            ?>

		</form>
	</div>
	<?php
}

/**
 * Generates HTML for a single input field on the admin settings page.
 * Prepares setting value to display.
 *
 * @param array $config Setting field config
 */
function cerber_field_show( $config ) {

	$settings = crb_get_settings();

	$attrs = array();

	$label = $config['label'] ?? '';

	if ( ! empty( $config['doclink'] ) ) {
		$label .= '<span class="crb-insetting-link">[ <a class="crb-no-wrap" target="_blank" href="' . $config['doclink'] . '">' . __( 'Know more', 'wp-cerber' ) . '</a> ]</span>';
	}

	// Unconditionally required
	$attrs['required'] = $config['required'] ?? false ? 1 : 0;

    // Conditionally (if enabled) required
	if ( $config['validate']['required'] ?? false ) {
		$attrs['data-input_required'] = '1';
	}

	if ( $placeholder = $config['placeholder'] ?? '' ) {
		$attrs['placeholder'] = $placeholder;
	}

	$attrs['disabled'] = $config['disabled'] ?? false ? 1 : 0;

	$value = $config['value'] ?? '';

	$setting = $config['setting'] ?? '';

	if ( $setting ) {
		if ( ! $value && isset( $settings[ $setting ] ) ) {
			$value = $settings[ $setting ];
		}
		if ( ( $setting == 'loginnowp' || $setting == 'loginpath' )
             && ! cerber_is_permalink_enabled() ) {
			$attrs['disabled'] = 1;
		}
		if ( $setting == 'loginpath' ) {
			$value = urldecode( $value );
		}
	}

	$value = crb_attr_escape( $value );

	$value = crb_format_field_value( $value, $config );

	$name_prefix = 'cerber-' . $config['group'];
	$name = $name_prefix . '[' . $setting . ']';

	$id = $config['id'] ?? CRB_FIELD_PREFIX . $setting;

	$data_atts = '';
	$ena_atts = array();
	if ( isset( $config['enabler'] ) ) {
		$ena_atts['input_enabler'] = CRB_FIELD_PREFIX . $config['enabler'][0];
		if ( isset( $config['enabler'][1] ) ) {
			$ena_atts['input_enabler_value'] = $config['enabler'][1];
		}
		foreach ( $ena_atts as $att => $val ) {
			$data_atts .= ' data-' . $att . '="' . $val . '"';
		}
	}

	$type = $config['type'] ?? 'text';

	$class = 'crb-input-' . $type;
	$class .= ' ' . ( $config['input_class'] ?? '' );

	if ( ( $pre_render = $config['pre_render'] ?? false )
	     && is_callable( $pre_render ) ) {

		call_user_func_array( $pre_render, array( &$value, &$attrs ) );
	}

    // Remove empty attributes including binary ones
	$attrs = array_filter( $attrs );

	$input_atts = '';

	if ( $attrs ) {
		foreach ( $attrs as $at => $val ) {
			$input_atts .= $at . '="' . crb_attr_escape( $val ) . '"';
		}
	}

	$html = '';
	$html_second = '';

	switch ( $type ) {

		case 'limitz':
			$s1 = $config['group'] . '-period';
			$s2 = $config['group'] . '-number';
			$s3 = $config['group'] . '-within';

			$html = sprintf( $label,
				cerber_digi_field( $name_prefix . '[' . $s1 . ']', $settings[ $s1 ], 'crb-first-field' ),
				cerber_digi_field( $name_prefix . '[' . $s2 . ']', $settings[ $s2 ] ),
				cerber_digi_field( $name_prefix . '[' . $s3 . ']', $settings[ $s3 ] ) );
			break;

		case 'attempts':
			$html = sprintf( __( '%s retries are allowed within %s minutes', 'wp-cerber' ),
				cerber_digi_field( $name_prefix . '[attempts]', $settings['attempts'], 'crb-first-field' ),
				cerber_digi_field( $name_prefix . '[period]', $settings['period'] ) );
			break;

		case 'reglimit':
			$html = sprintf( __( '%s registrations are allowed within %s minutes from one IP address', 'wp-cerber' ),
				cerber_digi_field( $name_prefix . '[reglimit_num]', $settings['reglimit_num'], 'crb-first-field' ),
				cerber_digi_field( $name_prefix . '[reglimit_min]', $settings['reglimit_min'], '', array( 'size' => 4, 'maxln' => 4, ) ) );
			break;

		case 'aggressive':
			$html = sprintf( __( 'Increase lockout duration to %s hours after %s lockouts in the last %s hours', 'wp-cerber' ),
				cerber_digi_field( $name_prefix . '[agperiod]', $settings['agperiod'] ),
				cerber_digi_field( $name_prefix . '[aglocks]', $settings['aglocks'] ),
				cerber_digi_field( $name_prefix . '[aglast]', $settings['aglast'] ) );
			break;

		case 'notify':
			$html = '<label class="crb-switch"><input class="screen-reader-text" type="checkbox" id="' . $id . '" name="cerber-' . $config['group'] . '[' . $setting . ']" value="1" ' . checked( 1, $value, false ) . $input_atts . ' /><span class="crb-slider round"></span></label>';
			$html_second .= '<label for="' . $id . '">' . __( 'Send notification if the number of active lockouts above', 'wp-cerber' ) . '</label> ' .
			                cerber_digi_field( $name_prefix . '[above]', $settings['above'] ) .
			                crb_test_notify_link( array( 'channel' => 'email' ) );
			break;

		case 'citadel':
			$html = sprintf( __( 'Enable after %s failed login attempts in the last %s minutes', 'wp-cerber' ),
				cerber_digi_field( $name_prefix . '[cilimit]', $settings['cilimit'] ),
				cerber_digi_field( $name_prefix . '[ciperiod]', $settings['ciperiod'] )
                . '<i ' . $data_atts . '></i>' );
			break;

		case 'checkbox':
			$html = '<label class="crb-switch"><input class="screen-reader-text" type="checkbox" id="' . $id . '" name="' . $name . '" value="1" ' . checked( 1, $value, false ) . $input_atts . ' /><span class="crb-slider round"></span></label>';

            if ( $label ) {
				$html_second .= '<label for="' . $id . '">' . $label . '</label>';
			}

            if ( $data_atts ) {
	            $html_second .= '<i ' . $data_atts . '></i>';
			}

            break;

		case 'textarea':
			$html = '<textarea class="large-text crb-monospace" id="' . $id . '" name="' . $name . '" ' . $input_atts . $data_atts . '>' . $value . '</textarea>';
			if ( $label ) {
				$html .= '<br/><label class="crb-below" for="' . $setting . '">' . $label . '</label>';
			}
			break;

		case 'select':
			$html = cerber_select( $name, $config['set'], $value, $class, $id, '', $placeholder, $ena_atts );
			if ( $label ) {
				$html .= '<br/><label class="crb-below">' . $label . '</label>';
			}
			break;

		case 'role_select':
			if ( $label ) {
				$label = '<p class="crb-label-above"><label for="' . $name . '">' . $label . '</label></p>';
			}
			$html = $label . '<div class="crb-select2-multi">' . cerber_role_select( $name . '[]', $value, '', true, '' ) . '<i ' . $data_atts . '></i></div>';
			break;

		case 'checkbox_set':
			$label = $label ? '<p class="crb-label-above">' . $label . '</p>' : '';
			$html = '<div class="crb-checkbox-set" style="line-height: 2em;" ' . $data_atts . '>' . $label;
			foreach ( $config['set'] as $key => $item ) {
				$v = ( ! empty( $value[ $key ] ) ) ? $value[ $key ] : 0;
                $box_name = $name . '[' . $key . ']';
				$html .= '<input type="checkbox" id="' . $box_name . '" value="1" name="' . $box_name . '" ' . checked( 1, $v, false ) . $input_atts . '/><label for="' . $box_name . '">' . $item . '</label><br />';
			}
			$html .= '</div>';
			break;

		case 'reptime':
			$html = cerber_time_select( $config, $settings ) . '<i ' . $data_atts . '></i>';
			break;

		case 'day_time_picker':
			$html = cerber_time_picker( $config, $value ) . '<i ' . $data_atts . '></i>';
			break;

		case 'timepicker':
			$html = '<input class="crb-tpicker" type="text" size="7" id="' . $setting . '" name="' . $name . '" value="' . $value . '"' . $input_atts . '/>';
			$html .= ' <label for="' . $setting . '">' . $label . '</label>';
			break;

		case 'hidden':
			$html = '<input type="hidden" id="' . $setting . '" class="crb-hidden-field" name="' . $name . '" value="' . $value . '" />';
			break;

		case 'text':
		case 'digits':
		default:

			if ( in_array( $type, array( 'url', 'number', 'email' ) ) ) {
				$input_type = $type;
			}
			else {
				$input_type = 'text';
			}

            if ( $prefix = $config['prefix'] ?? '' ) {
	            $before = '<div class="crb-prefixed-input"><span class="crb-input-prefix">' . $prefix . '</span>';
                $after = '</div>';
            }
            else {
                $before = '';
                $after = '';
            }

			$size = $config['size'] ?? '';

			if ( ! $size && $type == 'digits' ) {
				$size = '3';
			}

			$maxlength = $config['maxlength'] ?? $size;

			if ( $maxlength ) {
				$maxlength = ' maxlength="' . $maxlength . '" ';
			}

			if ( $size ) {
				$size = ' size="' . $size . '"';
			}
			else {
				$class .= ' crb-wide';
			}

			$pattern = $config['pattern'] ?? '';

			if ( ! $pattern && $type == 'digits' ) {
				$pattern = '\d+';
			}

			if ( $pattern ) {
				$input_atts .= ' pattern="' . $pattern . '"';
			}

			if ( isset( $config['attr'] ) ) {
				foreach ( $config['attr'] as $at_name => $at_value ) {
					$input_atts .= ' ' . $at_name . ' ="' . $at_value . '" ';
				}
			}
			else {
				if ( isset( $config['title'] ) ) {
					$input_atts .= ' title="' . $config['title'] . '"';
				}
			}

		    $html = $before . '<input type="' . $input_type . '" id="' . $setting . '" name="' . $name . '" value="' . $value . '"' . ' class="' . $class . ' crb-first-field" ' . $size . $maxlength . $input_atts . $data_atts . ' />' . $after;

			if ( $label ) {
				if ( ! $size || crb_array_get( $config, 'label_pos' ) == 'below' ) {
					$html .= '<label class="crb-below" for="' . $setting . '">' . $label . '</label>';
				}
				else {
					$html_second = '<label for="' . $setting . '">' . $label . '</label>';
				}
			}

			break;
	}

	if ( $loh = $config['act_relation'] ?? false ) {
		foreach ( $loh as $item ) {
			if ( in_array( $value, $item[0] ) ) {
				$html .= '<span class="crb-insetting-link">[ <a href="' . cerber_admin_link( 'activity', $item[1] ) . '" target="_blank">' . $item[2] . '</a> ]</span>';
			}
		}
	}

	if ( ! empty( $config['field_switcher'] ) ) {
		$name = 'cerber-' . $config['group'] . '[' . $setting . '-enabled]';
		$value = $settings[ $setting . '-enabled' ] ?? 0;
		$checkbox = '<label class="crb-switch"><input class="screen-reader-text" type="checkbox" id="' . $id . '" name="' . $name . '" value="1" ' . checked( 1, $value, false ) . ' /><span class="crb-slider round"></span></label>';
		$html_second = '<label for="' . $id . '">' . $config['field_switcher'] . '</label>' . $html . $html_second;
		$html = $checkbox;
	}

	$setting_class = $html_second ? 'crb_setting_twin' : 'crb_setting_single';
	$html_second = $html_second ? '<div>' . $html_second . '</div>' : '';

	echo '<div class="crb-settings-field crb_setting_' . $type . ' ' . $setting_class . '">';
	echo '<div>' . $html;

	echo '</div>' . $html_second . "</div>\n";

	if ( ( $under = $config['callback_under'] ?? false )
	     && is_callable( $under )
	     && $content = call_user_func( $under ) ) {

		echo '<div class="crb-settings-under">';
		echo $content;
		echo '</div>';
	}
}

function cerber_role_select( $name = 'cerber-roles', $selected = array(), $class = '', $multiple = '', $placeholder = '', $width = '75%' ) {

	if ( ! is_array( $selected ) ) {
		$selected = array( $selected );
	}
	if ( ! $placeholder ) {
		$placeholder = __( 'Select one or more roles', 'wp-cerber' );
	}
	$roles = wp_roles();
	$options = array();
	foreach ( $roles->get_names() as $key => $title ) {
		$s         = ( in_array( $key, $selected ) ) ? 'selected' : '';
		$options[] = '<option value="' . $key . '" ' . $s . '>' . $title . '</option>';
	}

	$m = ( $multiple ) ? 'multiple="multiple"' : '';

	// Setting width via class is not working
	$style = '';
	if ( $width ) {
		$style = 'width: ' . $width.';';
	}

	return ' <select style="' . $style . '" name="' . $name . '" class="crb-select2 ' . $class . '" ' . $m . ' data-placeholder="' . $placeholder . '" data-allow-clear="true">' . implode( "\n", $options ) . '</select>';
}

function cerber_time_select($args, $settings){

	// Week
	$php_week = array(
		__( 'Sunday' ),
		__( 'Monday' ),
		__( 'Tuesday' ),
		__( 'Wednesday' ),
		__( 'Thursday' ),
		__( 'Friday' ),
		__( 'Saturday' ),
	);
	$field = $args['setting'].'-day';
	$selected = $settings[ $field ] ?? '';
	$ret = cerber_select( 'cerber-' . $args['group'] . '[' . $field . ']', $php_week, $selected );
	$ret .= ' &nbsp; ' . _x( 'at', 'preposition of time like: at 11:00', 'wp-cerber' ) . ' &nbsp; ';

	// Hours
	$hours = array();
	for ( $i = 0; $i <= 23; $i ++ ) {
		$hours[] = str_pad( $i, 2, '0', STR_PAD_LEFT ) . ':00';
	}
	$field = $args['setting'] . '-time';
	$selected = $settings[ $field ] ?? '';
	$ret .= cerber_select( 'cerber-' . $args['group'] . '[' . $field . ']', $hours, $selected );

	return $ret . crb_test_notify_link( array( 'type' => 'report', 'title' => __( 'Click to send now', 'wp-cerber' ) ) );
}

/**
 * Generates day and time picker.
 * Replacement for cerber_time_select()
 *
 * @param array $conf Setting field config
 * @param mixed $val Value of the saved setting
 *
 * @return string HTML code of the picker
 * @since 9.3.5
 */
function cerber_time_picker( $conf, $val ) {

	// Week
	if ( $conf['period'] == 'one_week' ) {
		$period = array(
			__( 'Sunday' ),
			__( 'Monday' ),
			__( 'Tuesday' ),
			__( 'Wednesday' ),
			__( 'Thursday' ),
			__( 'Friday' ),
			__( 'Saturday' ),
		);
	}
	elseif ( $conf['period'] == 'one_month' ) {
		$period = range( 0, 31 );
		unset( $period[0] );
    }
    else {
        return 'Not supported period specified';
    }

	$selected = $val['day'] ?? '';
	$picker = cerber_select( 'cerber-' . $conf['group'] . '[' . $conf['setting'] . '][day]', $period, $selected );
	$picker .= ' &nbsp; ' . _x( 'at', 'preposition of time like: at 11:00', 'wp-cerber' ) . ' &nbsp; ';

	// Hours
	$hours = array();
	for ( $i = 0; $i <= 23; $i ++ ) {
		$hours[] = str_pad( $i, 2, '0', STR_PAD_LEFT ) . ':00';
	}
	$selected = $val['hours'] ?? '';
	$picker .= cerber_select( 'cerber-' . $conf['group'] . '[' . $conf['setting'] . '][hours]', $hours, $selected );

	return $picker . crb_test_notify_link( array( 'type' => 'report', 'test_period' => $conf['period'], 'title' => __( 'Click to send now', 'wp-cerber' ) ) );
}


function cerber_checkbox( $name, $value, $label = '', $id = '', $atts = '' ) {
	if ( ! $id ) {
		$id = CRB_FIELD_PREFIX . $name;
	}

	return '<div style="display: table-cell;"><label class="crb-switch"><input class="screen-reader-text" type="checkbox" id="' . $id . '" name="' . $name . '" value="1" ' . checked( 1, $value, false ) . $atts . ' /><span class="crb-slider round"></span></label></div>
	<div style="display: table-cell;"><label for="' . $id . '">' . $label . '</label></div>';
}

function cerber_digi_field( $name, $value = '', $class = '', $args = array() ) {
	return cerber_txt_field( $name, $value, '', $args['size'] ?? '3', $args['maxln'] ?? '3', '\d+', $class . ' crb-input-digits' );
}

function cerber_txt_field( $name, $value = '', $id = '', $size = '', $maxlength = '', $pattern = '', $class = '' ) {
	$atts = '';

	$atts .= $id ? ' id="' . $id . '" ' : '';
	$atts .= $class ? ' class="' . $class . '" ' : '';
	$atts .= $size ? ' size="' . $size . '" ' : '';
	$atts .= $maxlength ? ' maxlength="' . $maxlength . '" ' : '';
	$atts .= $pattern ? ' pattern="' . $pattern . '" ' : '';

	return '<input type="text" name="' . $name . '" value="' . $value . '" ' . $atts . ' />';
}

function cerber_nonce_field( $action = 'control', $echo = false ) {
	$sf = '';
	if ( nexus_is_valid_request() ) {
		$sf = '<input type="hidden" name="cerber_nexus_seal" value="' . nexus_request_data()->seal . '">';
	}
	$nf = wp_nonce_field( $action, 'cerber_nonce', false, false );
	if ( ! $echo ) {
		return $nf . $sf;
	}

	echo $nf . $sf;
}

function crb_admin_submit_button( $text = '', $echo = false ) {
	if ( ! $text ) {
		$text = __( 'Save Changes' );
	}

	$d    = '';
	$hint = '';
	if ( nexus_is_valid_request() && ! nexus_is_granted( 'submit' ) ) {
		$d    = 'disabled="disabled"';
		$hint = ' not available in the read-only mode';
	}

	$html = '<p class="submit"><input ' . $d . ' type="submit" name="submit" id="submit" class="button button-primary" value="' . $text . '"  /> ' . $hint . '</p>';
	if ( $echo ) {
		echo $echo;
	}

	return $html;
}

/*
	Sanitizing users input for Main Settings
*/
function crb_pre_update_main( $new, $old, $option ) {
	if ( isset( $new['boot-mode'] ) ) {
		$ret = cerber_set_boot_mode( $new['boot-mode'] );

		if ( crb_is_wp_error( $ret ) ) {
			cerber_admin_notice( __( 'ERROR:', 'wp-cerber' ) . ' ' . $ret->get_error_message() );
			cerber_admin_notice( __( 'Plugin initialization mode has not been changed', 'wp-cerber' ) );
			$new['boot-mode'] = $old['boot-mode'];
		}
        elseif ( $ret == 2 ) {
			cerber_admin_message( __( 'A must-use WP Cerber boot file has been copied to the WordPress must-use directory', 'wp-cerber' ) );
		}
	}

	$new['attempts'] = absint( $new['attempts'] );
	$new['period']   = absint( $new['period'] );
	$new['lockout']  = absint( $new['lockout'] );

	$new['agperiod'] = absint( $new['agperiod'] );
	$new['aglocks']  = absint( $new['aglocks'] );
	$new['aglast']   = absint( $new['aglast'] );

	if ( cerber_is_permalink_enabled() ) {
		$new['loginpath'] = urlencode( str_replace( '/', '', $new['loginpath'] ) );
		$new['loginpath'] = sanitize_text_field( $new['loginpath'] );

		if ( $new['loginpath'] ) {
			if ( $new['loginpath'] == 'wp-admin'
			     || preg_match( '/[#|.!?:\s]/', $new['loginpath'] ) ) {
				cerber_admin_notice( __( 'ERROR:', 'wp-cerber' ) . ' You may not set this value for Custom login URL. Please specify another one.' );
				$new['loginpath'] = $old['loginpath'];
			}
			elseif ( $new['loginpath'] != $old['loginpath'] ) {
				$href    = cerber_get_home_url() . '/' . $new['loginpath'] . '/';
				$url     = urldecode( $href );
				$msg     = array();
				$msg_e   = array();
				$msg[]   = __( 'Attention! You have changed the login URL! The new login URL is', 'wp-cerber' ) . ': <a href="' . $href . '">' . $url . '</a>';
				$msg_e[] = __( 'Attention! You have changed the login URL! The new login URL is', 'wp-cerber' ) . ': ' . $url;
				$msg[]   = __( 'If you use a caching plugin, you have to add your new login URL to the list of pages not to cache.', 'wp-cerber' );
				$msg_e[] = __( 'If you use a caching plugin, you have to add your new login URL to the list of pages not to cache.', 'wp-cerber' );
				cerber_admin_notice( $msg );
				cerber_send_message( 'newlurl', array( 'text' => $msg_e ) );
			}
		}
	}
	else {
		$new['loginpath'] = '';
		$new['loginnowp'] = 0;
	}

	if ( $new['loginnowp'] && empty( $new['loginpath'] ) && ! class_exists( 'WooCommerce' ) ) {
		cerber_admin_notice( array(
			'<b>' . __( 'Heads up!' ) . '</b>',
			__( 'You have disabled the default login page. Ensure that you have configured an alternative login page. Otherwise, you will not be able to log in.', 'wp-cerber' )
		) );
	}

	$new['ciduration'] = absint( $new['ciduration'] );
	$new['cilimit']    = absint( $new['cilimit'] );
	$new['cilimit']    = $new['cilimit'] == 0 ? '' : $new['cilimit'];
	$new['ciperiod']   = absint( $new['ciperiod'] );
	$new['ciperiod']   = $new['ciperiod'] == 0 ? '' : $new['ciperiod'];
	if ( ! $new['cilimit'] ) {
		$new['ciperiod'] = '';
	}
	if ( ! $new['ciperiod'] ) {
		$new['cilimit'] = '';
	}

	return $new;
}

/*
	Sanitizing/checking user input for anti-spam tab settings
*/
function crb_pre_update_antispam( $new, $old, $option ) {

	if ( empty( $new['botsany'] ) && empty( $new['botscomm'] ) && empty( $new['botsreg'] ) ) {
		update_site_option( 'cerber-antibot', '' );
	}

	$warn = false;

	if ( ! empty( $new['botsany'] )
         && crb_array_get( $new, 'botsany' ) != crb_array_get( $old, 'botsany' ) ) {
		$warn = true;
	}

	if ( ! empty( $new['botscomm'] )
         && crb_array_get( $new, 'botscomm' ) != crb_array_get( $old, 'botscomm' ) ) {
		$warn = true;
	}

	if ( ! empty( $new['customcomm'] ) ) {
		if ( ! crb_get_compiled( 'custom_comm_slug' ) ) {
			crb_update_compiled( 'custom_comm_slug', crb_random_string( 20, 30 ) );
			crb_update_compiled( 'custom_comm_mark', crb_random_string( 20, 30 ) );
			$warn = true;
		}
	}
	else {
		if ( crb_get_compiled( 'custom_comm_slug' ) ) {
			crb_update_compiled( 'custom_comm_slug', '' );
			$warn = true;
		}
	}

	if ( $warn ) {
		cerber_admin_notice( array(
			'<b>' . __( 'Important note if you have a caching plugin in place', 'wp-cerber' ) . '</b>',
			__( 'To avoid false positives and get better anti-spam performance, please clear the plugin cache.', 'wp-cerber' )
		) );
	}

	return $new;
}
/*
	Sanitizing/checking user input for reCAPTCHA tab settings
*/
function crb_pre_update_recaptcha( $new, $old, $option ) {

	// Check ability to make external HTTP requests
	if ( ! empty( $new['sitekey'] ) && ! empty( $new['secretkey'] ) ) {
		if ( ( ! $goo = get_wp_cerber()->reCaptchaRequest( '1' ) )
		     || ! isset( $goo['success'] ) ) {
			cerber_admin_notice( __( 'ERROR:', 'wp-cerber' ) . ' ' . cerber_get_labels( 'status', 534 ) );
		}
	}

	$new['recaptcha-period'] = absint( $new['recaptcha-period'] );
	$new['recaptcha-number'] = absint( $new['recaptcha-number'] );
	$new['recaptcha-within'] = absint( $new['recaptcha-within'] );

	return $new;
}
/*
	Sanitizing/checking user input for Notifications tab settings
*/
function crb_pre_update_notifications( $new, $old, $option ) {

	$new['email'] = crb_email_purify( $new['email'] );
	$new['email-report'] = crb_email_purify( $new['email-report'] );
	$new['email_report_one_month'] = crb_email_purify( $new['email_report_one_month'] );

	$new['emailrate'] = absint( $new['emailrate'] );

	// When we install a new token, we set proper default value for the device setting

	if ( $new['pbtoken'] != $old['pbtoken'] ) {

		if ( ! $new['pbtoken'] ) {
			$new['pbdevice'] = '';
		}
		else {

			$list = cerber_pb_get_devices( $new['pbtoken'] );

			if ( is_array( $list ) && ! empty( $list ) ) {
				$new['pbdevice'] = 'all';
				cerber_admin_message( __( 'The Pushbullet token is valid. You can select devices to receive notifications.', 'wp-cerber' ) );
			}
			else {
				$new['pbdevice'] = '';
				if ( crb_is_wp_error( $list ) ) {
					cerber_admin_notice( __( 'ERROR:', 'wp-cerber' ) . ' ' . crb_escape( $list->get_error_message() ) );
				}
			}
		}
	}

	return $new;
}

/*
    Sanitizing/checking user input for Scanner Schedule settings
*/
function crb_pre_update_schedule( $new, $old, $option ) {
	$new['scan_aquick'] = absint( $new['scan_aquick'] );
	$new['scan_afull-enabled'] = ( empty( $new['scan_afull-enabled'] ) ) ? 0 : 1;

	$sec = cerber_sec_from_time( $new['scan_afull'] );
	if ( ! $sec || ! ( $sec >= 0 && $sec <= 86400 ) ) {
		$new['scan_afull'] = '01:00';
	}

	$new['email-scan'] = crb_email_purify( $new['email-scan'] );

	if ( lab_lab() ) {
		if ( cerber_cloud_sync( $new ) ) {
			cerber_admin_message( __( 'The scanner schedule has been updated', 'wp-cerber' ) );
		}
		else {
			cerber_admin_message( __( 'Unable to update the scanner schedule', 'wp-cerber' ) );
		}
	}

	return $new;
}

function cerber_normal_dirs( $list = array() ) {
	if ( ! is_array( $list ) ) {
		$list = cerber_text2array( $list, "\n" );
	}
	$ready = array();

	foreach ( $list as $item ) {
		$item = rtrim( cerber_normal_path( $item ), '/\\' ) . DIRECTORY_SEPARATOR;
		if ( ! @is_dir( $item ) ) {
			$dir = cerber_get_abspath() . ltrim( $item, DIRECTORY_SEPARATOR );
			if ( ! @is_dir( $dir ) ) {
				cerber_admin_notice( 'Directory does not exist: ' . htmlspecialchars( $item, ENT_SUBSTITUTE ) );
				continue;
			}
			$item = $dir;
		}
		$ready[] = $item;
	}

	return $ready;
}

/**
 * @param string $emails
 *
 * @return array
 * @since 9.3.5
 */
function crb_email_purify( $emails ) {

	$list = array();

	if ( $emails = cerber_text2array( $emails, ',' ) ) {

		foreach ( $emails as $item ) {
			if ( is_email( $item ) ) {
				$list[] = $item;
			}
			else {
				cerber_admin_notice( __( '<strong>ERROR</strong>: please enter a valid email address.' ) );
			}
		}
	}

    return $list;
}

/**
 * An intermediate level for update_site_option() for Cerber's settings.
 * Goal: have a more granular control over processing settings.
 *
 * @since 8.5.9.1
 *
 * @param string $option_name
 * @param $value
 *
 * @return bool
 */
function cerber_update_site_option( $option_name, $value ) {

	$result = update_site_option( $option_name, $value );

	cerber_process_settings_form();

	crb_purge_settings_cache();

	return $result;
}

/**
 * Process WP Cerber's settings forms in a new way
 *
 * @since 8.6
 *
 */
function cerber_process_settings_form() {

	if ( ! cerber_is_http_post()
	     || ! $group = crb_get_post_fields( CRB_SETTINGS_GROUP ) ) {
		return;
	}

	// We do not process some specific cases - not a real settings form
	if ( defined( 'CRB_NX_MANAGED' ) && $group == CRB_NX_MANAGED ) {
		return;
	}

	if ( ! nexus_is_valid_request() ) {
		if ( ! cerber_user_can_manage() ) {
			return;
		}

		// See wp_nonce_field() in the settings_fields() function
		check_admin_referer( $_POST['option_page'] . '-options' );
	}

	$post_fields = crb_get_post_fields( 'cerber-' . $group, array() );
	crb_trim_deep( $post_fields );
	$post_fields = stripslashes_deep( $post_fields );

    $msg = '';

	if ( 'addon-settings' == crb_get_post_fields( 'page_type' ) ) {
		if ( CRB_Addons::update_settings( $post_fields, crb_get_post_fields( 'addon_id' ) ) ) {
			$msg = __( 'Add-on settings updated', 'wp-cerber' );
		}
	}
	else {
		// Fields extracted from the settings config
		$fields_one = array_fill_keys( array_keys( crb_get_settings_fields( $group ) ), '' );

        // Fields extracted from from default values
		$defs = cerber_get_defaults();
		$fields_two = array_fill_keys( array_keys( $defs[ 'cerber-' . $group ] ), '' );

        // For best coverage we combine them
		$fields = array_merge( $fields_one, $fields_two );

		$new_settings = array_merge( $fields, $post_fields );

		if ( cerber_settings_update( $new_settings, $group ) ) {
			$msg = __( 'Plugin settings updated', 'wp-cerber' );
		}
	}

	if ( $msg ) {
		cerber_admin_message( $msg, true );
	}

}

/**
 * Updates WP Cerber's settings in the database in the new format
 *
 * @param array $new_settings Array of settings (id => value) to update
 * @param string $sanitizing_group Settings group (setting form)
 *
 * @since 9.3.4
 */
function cerber_settings_update( $new_settings, $sanitizing_group = '' ) {

	if ( ( ! $old_settings = get_site_option( CERBER_CONFIG ) )
	     || ! is_array( $old_settings ) ) {
		$old_settings = array();
	}

    // Ensure that all settings keys are in place in $old_settings

	$all_settings = array_fill_keys( array_keys( crb_get_default_values() ), '' );
	$old_settings = array_merge( $all_settings, $old_settings );

    // Preserve PRO settings if the license is expired

	if ( ! lab_lab()
	     && $pro = array_intersect_key( $new_settings, array_flip( CRB_PRO_SETTINGS ) ) ) {
		$new_settings = array_merge( $new_settings, array_intersect_key( $old_settings, $pro ) );
	}

	// Pre-process in the old way @before 9.3.4

	$new_settings = cerber_settings_pre_update( $old_settings, $new_settings, $sanitizing_group );

	$save = array_merge( $old_settings, $new_settings );

    // Pre-process in the new way @since 9.3.4

	$defs = crb_get_settings_fields();
	$defs = array_intersect_key( $defs, $new_settings );

	foreach ( $defs as $id => $config ) {

        if ( ( $pre_update = crb_array_get( $config, 'pre_update' ) )
		     && is_callable( $pre_update ) ) {

	        $save[ $id ] = call_user_func_array( $pre_update, array( $save[ $id ], $old_settings[ $id ], &$save, $old_settings, $new_settings ) );
		}
	}

    // Check if any changes in the settings

	$changed = array();

	foreach ( $save as $key => $val ) {

		if ( ! isset( $old_settings[ $key ] ) ) {
			/*if ( ! empty( $val ) ) {
                // Absence of the old setting is equal to a new, empty one
                $changed[] = $key;
			}*/

			continue;
		}

		$old = $old_settings[ $key ];

		// We compare only non-empty values, for WP Cerber settings '', 0, false, and empty array are equal

		if ( empty( $old ) && empty( $val ) ) {

			continue;
		}

		if ( ! is_array( $val ) && ! is_array( $old ) ) {
			if ( (string) $old != (string) $val ) {
				$changed[] = $key;
			}
		}
        elseif ( is_array( $val ) && is_array( $old ) ) {
			if ( json_encode( $old ) !== json_encode( $val ) ) {
				$changed[] = $key;
			}
		}
		else {
			$changed[] = $key;
		}

	}

    $equal = empty( $changed );

    // We are ready to save settings

    $result = null;

	if ( ! $equal ) {
		if ( ! $result = update_site_option( CERBER_CONFIG, $save ) ) {
			cerber_admin_notice( 'Critical I/O error #77 occurred while updating WP Cerber settings.' );
		}
	}

	$diag_log = array();

	if ( $result ) {

		crb_purge_settings_cache(); // Delete outdated values from the static cache

		$back = array();

		foreach ( $defs as $id => $config ) {

            if ( ! in_array( $id, $changed ) ) {
				continue;
			}

			if ( $msg = crb_array_get( $config, 'diag_log' ) ) {
				$diag_log[ $id ] = $msg;
			}

            // Post-processing if any needed

			if ( ( $on_change = crb_array_get( $config, 'on_change' ) )
			     && is_callable( $on_change ) ) {

				call_user_func( $on_change, $save[ $id ], $save, $old_settings );
			}

			// Rolling back value if a rollback returns true

			if ( ( $rollback = crb_array_get( $config, 'rollback' ) )
			     && is_callable( $rollback ) ) {

				if ( call_user_func( $rollback, $save, $old_settings ) ) {
					$back[ $id ] = crb_array_get( $old_settings, $id, '' );
				}
			}
		}

		if ( $back ) {

			// Rolling back values

			$save = array_merge( $save, $back );

			if ( ! update_site_option( CERBER_CONFIG, $save ) ) {
				cerber_admin_notice( 'Critical I/O error #78 occurred while updating WP Cerber settings.' );
			}
			else {
				$changed = array_flip( array_diff_key( array_flip( $changed ), $back ) );
			}
		}

	}

	$data = array(
		'group'      => (string) $sanitizing_group,
		'equal'      => $equal,
		'result'     => $result,
		'changed'    => $changed,
		'new_values' => $new_settings,
		'old_values' => $old_settings,
	);

	if ( $result && $changed && $diag_log ) {
		crb_journaling( $data, $diag_log );
	}

	crb_event_handler( 'update_settings', $data );

    return $result;
}

/**
 * Sanitizing and processing setting values before saving to the DB.
 * Calls functions that were initially created for WP settings API.
 *
 * @param array $old
 * @param array $new
 * @param string $setting_group
 *
 * @return array
 *
 * @since 9.3.4
 */
function cerber_settings_pre_update( $old, $new, $setting_group = 'all' ) {

	if ( $setting_group === 'all' ) {
		$new = crb_pre_update_main( $new, $old, '' );
		$new = crb_pre_update_antispam( $new, $old, '' );
		$new = crb_pre_update_recaptcha( $new, $old, '' );
		$new = crb_pre_update_notifications( $new, $old, '' );
		$new = crb_pre_update_schedule( $new, $old, '' );
	}
    elseif ( $setting_group ) {

		// Screen specific sanitizing and processing

		$option_id = 'cerber-' . $setting_group;

		switch ( $option_id ) {
			case CERBER_OPT:
				$new = crb_pre_update_main( $new, $old, '' );
				break;
			case CERBER_OPT_A:
				$new = crb_pre_update_antispam( $new, $old, '' );
				break;
			case CERBER_OPT_C:
				$new = crb_pre_update_recaptcha( $new, $old, '' );
				break;
			case CERBER_OPT_N:
				$new = crb_pre_update_notifications( $new, $old, '' );
				break;
			case CERBER_OPT_E:
				$new = crb_pre_update_schedule( $new, $old, '' );
				break;
		}
	}

    // Global sanitizing and processing

	return cerber_grand_sanitizing( $new );
}

/**
 *  Global sanitizing, processing, formatting
 *
 * @param array $settings
 *
 * @return array
 */
function cerber_grand_sanitizing( $settings ) {

	$pre_sanitize = $settings;
	$set_minimum = array();
	$defs = crb_get_settings_fields();

	// Parsing settings, applying formatting, etc.

	foreach ( $settings as $setting_id => &$setting_val ) {

		if ( ! $conf = crb_array_get( $defs, $setting_id ) ) {
			continue;
		}

		if ( $enabler = $conf['enabler'] ?? '' ) {
			if ( crb_check_enabler( $conf, $settings[ $enabler[0] ] ) ) {
				continue;
			}
		}

		$callback = crb_array_get( $conf, 'apply' );
		$regex = crb_array_get( $conf, 'regex_filter' ); // Filtering out not allowed chars
		$validate = crb_array_get( $conf, 'validate' );

		if ( isset( $conf['list'] ) ) {
			// Process the values
			$setting_val = cerber_text2array( $setting_val, $conf['delimiter'], $callback, $regex );

			// Remove not allowed values
			global $_deny;
			if ( $_deny = crb_array_get( $conf, 'deny_filter' ) ) {
				$setting_val = array_filter( $setting_val, function ( $e ) {
					global $_deny;

					return ! in_array( $e, $_deny );
				} );
			}
		}
		else {
			// Process the value
			if ( $callback && is_callable( $callback ) ) {
				$setting_val = call_user_func( $callback, $setting_val );
			}
			if ( $regex ) {
				$setting_val = mb_ereg_replace( $regex, '', $setting_val );
			}

            // Validating the value
			if ( $validate ) {

				$field_name = $conf['title'] ?? $conf['label'] ?? 'Unknown field';
				$error_msg = '';

				if ( ! empty( $validate['required'] )
				     && ! $setting_val ) {
					$error_msg = sprintf( __( 'Field %s may not be empty', 'wp-cerber' ), '<b>' . $field_name . '</b>' );
				}
                elseif ( $setting_val
				         && ( $sat = $validate['satisfy'] ?? false )
				         && is_callable( $sat )
				         && ! call_user_func( $sat, $setting_val ) ) {
					$error_msg = sprintf( __( 'Field %s contains an invalid value', 'wp-cerber' ), '<b>' . $field_name . '</b>' );
				}

				if ( $error_msg ) {
					cerber_admin_notice( '<b>' . __( 'ERROR:' ) . '</b> ' . $error_msg );
				}
			}

			// Limits for numeric values

			if ( isset( $conf['min_val'] )
			     && ! ( ( $conf['empty_val'] ?? false ) && $setting_val === '' ) // Is empty values allowed
			     && $setting_val < $conf['min_val'] ) {
				$setting_val = $conf['min_val'];
				$set_minimum[] = $setting_id;
			}
		}
	}

	crb_sanitize_deep( $settings );

	// Warn the user if we have altered some values the user has entered

	if ( $set_minimum ) {
		$list = array_intersect_key( $defs, array_flip( $set_minimum ) );
		$msg = '<div style="font-weight: 600;"><p>' . implode( '</p><p>', array_column( $list, 'title' ) ) . '</p></div>';
		cerber_admin_notice( __( 'The following settings have been set to their minimum acceptable values.', 'wp-cerber' ) . $msg );
	}

	$changed = array();

	foreach ( $pre_sanitize as $setting_id => $pre_val ) {
		if ( empty( $pre_val ) ) {
			continue;
		}

		if ( is_array( $pre_val ) ) { // Usually checkboxes
			if ( json_encode( $pre_val ) !== json_encode( $settings[ $setting_id ] ) ) {
				$changed[] = $setting_id;
			}
		}
        elseif ( is_array( $settings[ $setting_id ] ) ) {
			$pre_val = preg_replace( '/\s+/', '', $pre_val );
			$san_val = preg_replace( '/\s+/', '', crb_format_field_value( $settings[ $setting_id ], crb_array_get( $defs, $setting_id ) ) );
			if ( $pre_val != $san_val ) {
				$changed[] = $setting_id;
			}
		}
        elseif ( $pre_val != $settings[ $setting_id ] ) {
			$changed[] = $setting_id;
		}
	}

	if ( $changed ) {
		$list = array_intersect_key( $defs, array_flip( $changed ) );
		$msg = '<div style="font-weight: 600;"><p>' . implode( '</p><p>', array_column( $list, 'title' ) ) . '</p></div>';
		cerber_admin_notice( __( 'For safety reasons, prohibited symbols and invalid values have been removed from the following settings. Please check their values.', 'wp-cerber' ) . $msg );
	}

	return $settings;
}

/**
 * Logs changes of the plugin settings to the diagnostic log.
 * To enable logging, a setting has to have a 'diag_log' field in the setting config.
 *
 * @param array $data Information about updated settings.
 * @param array $list The list of settings that have to be logged to the diagnostic log.
 *
 * @return void
 *
 * @since 9.0.1
 */
function crb_journaling( $data, $list ) {
	if ( $data['equal'] ) {
		return;
	}

	if ( ! $changed = array_intersect( array_keys( $list ), $data['changed'] ) ) {
		return;
	}

	foreach ( $changed as $key ) {

		$what = $list[ $key ];

		if ( empty( $data['new_values'][ $key ] ) ) {
			$msg = 'Disabled: ' . $what;
		}
		else {
			$msg = 'Enabled: ' . $what;
		}

		cerber_diag_log( $msg, '*' );
	}

}

/**
 * @param string $screen_id An optional setting screen (group)
 *
 * @return array The list of all WP Cerber settings
 *
 * @since 9.1.5
 */
function crb_get_settings_fields( $screen_id = '' ) {
    static $all;

	if ( ! $screen_id ) {
		if ( ! $all ) {
			$all = cerber_settings_config();
		}
		$sections = $all;
	}
	else {
		$sections = cerber_settings_config( array( 'screen_id' => $screen_id ) );
	}

	$ret = array();

	foreach ( $sections as $sec ) {
		if ( $fields = crb_array_get( $sec, 'fields' ) ) {
			$ret = array_merge( $ret, $fields );
		}
	}

	return $ret;
}

/**
 * Check setting field enabler and returns conditional inputs CSS class
 *
 * @param array $config The config of a setting field
 * @param mixed $enab_val The value of the enabler field
 *
 * @return string CSS class to be used
 */
function crb_check_enabler( $config, $enab_val ) {
	if ( ! isset( $config['enabler'] ) ) {
		return '';
	}

	$enabled = true;

	if ( isset( $config['enabler'][1] ) ) {
		$target_val = $config['enabler'][1];
		if ( 0 === strpos( $target_val, '[' ) ) {
			$list = json_decode( $target_val );
			if ( ! in_array( $enab_val, $list ) ) {
				$enabled = false;
			}
		}
		else {
			if ( $enab_val != $target_val ) {
				$enabled = false;
			}
		}
	}
	else {
		if ( empty( $enab_val ) ) {
			$enabled = false;
		}
	}

	return ( ! $enabled ) ? ' crb-disable-this' : '';
}

/**
 * @param mixed $value
 * @param array $config
 *
 * @return string
 *
 * @since 9.1.5
 */
function crb_format_field_value( $value, $config ) {
	if ( isset( $config['list'] ) ) {
		$dlt = $config['delimiter_show'] ?? $config['delimiter'];
		$value = cerber_array2text( $value, $dlt );
	}

	return $value;
}

/**
 * @param string $file
 *
 * @return false False if .htaccess was not updated
 */
function crb_htaccess_admin( $file ) {

	$result = cerber_htaccess_sync( $file );

	if ( crb_is_wp_error( $result ) ) {
		CRB_Globals::$htaccess_failure[ $file ] = true;
		cerber_admin_notice( $result->get_error_message() );

		return false;
	}
    elseif ( $result ) {
		cerber_admin_message( $result );
	}

	return true;
}