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
if ( ! defined( 'WPINC' ) ) { exit; }

const WHOIS_ERR_EXPIRE = 300;
const WHOIS_OK_EXPIRE = 4 * 3600;
const WHOIS_IO_TIMEOUT = 3;

require_once( __DIR__ . '/cerber-ripe.php' );

/**
 * Get WHOIS info about a given IP
 *
 * @param string $ip
 *
 * @return array
 *
 * @since 2.7
 *
 */
function cerber_ip_whois_info( $ip ) {
	$ret = array();

	$whois_server = cerber_get_whois_server( $ip );
	if ( is_array( $whois_server ) ) {
		return $whois_server;
	}

	if ( $whois_server == 'whois.ripe.net' ) {
		return ripe_readable_info( $ip );
	}

	$whois_info = cerber_get_whois( $ip );
	if ( is_array( $whois_info ) ) {
		return $whois_info;
	}

	$data = cerber_parse_whois_data( $whois_info );

	// Special case - network was transferred to RIPE
	if ( isset( $data['ReferralServer'] )
	     && $data['ReferralServer'] == 'whois://whois.ripe.net' ) {
		return ripe_readable_info( $ip );
	}

	$data = crb_attr_escape( $data );

	$table1 = '';

	if ( ! empty( $data ) ) {
		$table1 = '<table class="whois-object"><tr><td colspan="2"><b>FILTERED WHOIS INFO</b></td></tr>';
		foreach ( $data as $key => $value ) {
			if ( is_email( $value ) ) {
				$value = '<a href="mailto:' . $value . '">' . $value . '</a>';
			}
			elseif ( strtolower( $key ) == 'country' ) {
				$value = cerber_get_flag_html( $value, '<b>' . cerber_country_name( $value ) . ' (' . $value . ')</b>' );
				$ret['country'] = $value;
			}

			$table1 .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
		}
		$table1 .= '</table>';
	}

	$table2 = '<table class="whois-object crb-raw-data"><tr><td><b>RAW WHOIS INFO</b></td></tr>';
	$table2 .= '<tr><td><pre>' . crb_escape( $whois_info ) . "\n WHOIS server: " . $whois_server . '</pre></td></tr>';
	$table2 .= '</table>';

	$info = $table1 . $table2;

	// Other possible fields with abuse email address
	if ( empty( $data['abuse-mailbox'] ) ) {
		$data['abuse-mailbox'] = $data['OrgAbuseEmail'] ?? '';
	}

	if ( empty( $data['abuse-mailbox'] ) ) {
		foreach ( $data as $field ) {
			$maybe_email = trim( $field );
			if ( false !== strpos( $maybe_email, 'abuse' )
			     && is_email( $maybe_email ) ) {
				$data['abuse-mailbox'] = $maybe_email;
				break;
			}
		}
	}

	// Network
	$data['network'] = $data['inetnum'] ?? $data['NetRange'] ?? '';

	$ret['data'] = $data;
	$ret['whois'] = $info;
	return $ret;
}
/**
 * Get WHOIS info for a given IP
 *
 * @param string $ip IP address
 *
 * @return array|string Array on error
 *
 * @since 2.7
 */
function cerber_get_whois( $ip ) {
	$key = 'WHS-' . cerber_get_id_ip( $ip );

	if ( ! $info = cerber_get_set( $key, null, false ) ) {
		$whois_server = cerber_get_whois_server( $ip );

		if ( is_array( $whois_server ) ) {
			return $whois_server;
		}

		$info = make_whois_request( $whois_server, $ip );

		if ( is_array( $info ) ) {
			return $info;
		}

		cerber_update_set( $key, $info, null, false, time() + WHOIS_OK_EXPIRE );
	}

	return $info;
}
/**
 * Find out what server stores WHOIS info for a given IP
 *
 * @param string $ip IP Address
 *
 * @return mixed|string[] Array on error, hostname on success
 */
function cerber_get_whois_server( $ip ) {
	$key = 'SRV-' . cerber_get_id_ip( $ip );

	if ( ! $server = cerber_get_set( $key, null, false ) ) {
		$w = make_whois_request( 'whois.iana.org', $ip );

		if ( is_array( $w ) ) {
			return $w;
		}

		preg_match( '/^whois\:\s+([\w\.\-]{3,})/m', $w, $data );

		if ( empty( $data[1] )
		     || ! crb_is_valid_hostname( $data[1] ) ) {
			return array( 'error' => 'No valid WHOIS server was found for IP ' . $ip );
		}

		$server = $data[1];

		cerber_update_set( $key, $server, null, false, time() + WHOIS_OK_EXPIRE );
	}

	return $server;
}
/**
 * Attempts to parse textual WHOIS response to associative array
 *
 * @param string $text
 *
 * @return array
 *
 * @since 2.7
 */
function cerber_parse_whois_data( $text ) {
	$lines = explode( "\n", $text );
	$lines = array_filter( $lines );
	$ret = array();

	foreach ( $lines as $line ) {
		if ( preg_match( '/^([\w\-]+)\:\s+(.+)/', trim( $line ), $data ) ) {
			$ret[ $data[1] ] = $data[2];
		}
	}

	return $ret;
}
/**
 * Retrieve RAW text information about an IP address by using WHOIS protocol
 *
 * @param string $hostname WHOIS server
 * @param string $ip IP address
 *
 * @return string|string[] Array on error
 *
 * @since 2.7
 */
function make_whois_request( $hostname, $ip ) {
	if ( ! $socket = @fsockopen( $hostname, 43, $errno, $errstr, WHOIS_IO_TIMEOUT ) ) {
		return array( 'error' => 'Network error: ' . $errstr . ' (WHOIS server: ' . $hostname . ').' );
	}

	#Set the timeout for answering
	if ( ! stream_set_timeout( $socket, WHOIS_IO_TIMEOUT ) ) {
		return array( 'error' => 'WHOIS: Unable to set IO timeout.' );
	}

	#Send the IP address to the whois server
	if ( false === fwrite( $socket, "$ip\r\n" ) ) {
		return array( 'error' => 'WHOIS: Unable to send request to remote WHOIS server (' . $hostname . ').' );
	}

	//Set the timeout limit for reading again
	if ( ! stream_set_timeout( $socket, WHOIS_IO_TIMEOUT ) ) {
		return array( 'error' => 'WHOIS: Unable to set IO timeout.' );
	}

	//Set socket in non-blocking mode
	if ( ! stream_set_blocking( $socket, 0 ) ) {
		return array( 'error' => 'WHOIS: Unable to set IO non-blocking mode.' );
	}

	//If connection is still valid
	if ( $socket ) {
		$data = '';
		while ( ! feof( $socket ) ) {
			$data .= fread( $socket, 256 );
		}
	}
	else {
		return array( 'error' => 'Unable to get WHOIS response.' );
	}

	if ( ! $data ) {
		return array( 'error' => 'Remote WHOIS server return empty response (' . $hostname . ').' );
	}

	return $data;
}

/**
 * HTML for displaying a national flag
 *
 * @param $code string Country code
 *
 * @return string   HTML code
 *
 */
function cerber_get_flag_html( $code, $txt = '' ) {

	if ( ! $code ) {
		return '';
	}

	return '<div class="crb-country-label"><img alt="' . $code . '" class="crb-country-flag" src="' . CRB_Globals::assets_url( 'flags/' ) . strtolower( $code ) . '.png">' . $txt . '</div>';
}
/*
 *
 * Country name from two letter code
 * ISO 3166-1 alpha-2
 * @since 2.7
 *
 */
function cerber_country_name( $code ) {
	static $cache, $locale;

	if ( ! $code ) {
		return __( 'Unknown', 'wp-cerber' );
	}

	if ( isset( $cache[ $code ] ) ) {
		return $cache[ $code ];
	}

	$code = strtoupper( $code );
	$ret = '';

	if (!isset($locale)) {
		$locale = crb_get_bloginfo( 'language' );
		if ( $locale != 'pt-BR' && $locale != 'zh-CN' ) {
			$locale = substr( $locale, 0, 2 );
			if ( ! in_array( $locale, array( 'de', 'en', 'es', 'fr', 'ja', 'ru' ) ) ) {
				$locale = 'en';
			}
		}
	}

	$ret = cerber_db_get_var( 'SELECT country_name FROM ' . CERBER_GEO_TABLE . ' WHERE country = "'.$code.'" AND locale = "'.$locale.'"' );

	if ($ret) {
		$cache[ $code ] = $ret;
		return $ret;
	}

	$ret = CERBER_COUNTRY_NAMES[ $code ] ?? __( 'Unknown', 'wp-cerber' );

	$cache[ $code ] = $ret;
	return $ret;
}

function cerber_get_country_list() {

	$ret = array();
	foreach ( CERBER_COUNTRY_NAMES as $code => $name ) {
		$ret[ $code ] = cerber_country_name( $code );
	}

	// Remove non-countries

	unset( $ret['EU'] );
	unset( $ret['EZ'] );

	return $ret;
}

const CERBER_COUNTRY_NAMES = array(
	'AF' => 'Afghanistan',
	'AL' => 'Albania',
	'AX' => 'Aland Islands',
	'DZ' => 'Algeria',
	'AS' => 'American Samoa',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antarctica',
	'AG' => 'Antigua and Barbuda',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
	'AU' => 'Australia',
	'AT' => 'Austria',
	'AZ' => 'Azerbaijan',
	'BS' => 'Bahamas',
	'BH' => 'Bahrain',
	'BD' => 'Bangladesh',
	'BB' => 'Barbados',
	'BY' => 'Belarus',
	'BE' => 'Belgium',
	'BZ' => 'Belize',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BT' => 'Bhutan',
	'BO' => 'Bolivia',
	'BQ' => 'Caribbean Netherlands',
	'BA' => 'Bosnia and Herzegovina',
	'BW' => 'Botswana',
	'BV' => 'Bouvet Island',
	'BR' => 'Brazil',
	'IO' => 'British Indian Ocean Territory',
	'BN' => 'Brunei Darussalam',
	'BG' => 'Bulgaria',
	'BF' => 'Burkina Faso',
	'BI' => 'Burundi',
	'KH' => 'Cambodia',
	'CM' => 'Cameroon',
	'CA' => 'Canada',
	'CV' => 'Cape Verde',
	'KY' => 'Cayman Islands',
	'CF' => 'Central African Republic',
	'TD' => 'Chad',
	'CL' => 'Chile',
	'CN' => 'China',
	'CX' => 'Christmas Island',
	'CC' => 'Cocos (Keeling) Islands',
	'CO' => 'Colombia',
	'KM' => 'Comoros',
	'CG' => 'Congo',
	'CD' => 'Democratic Republic of the Congo',
	'CK' => 'Cook Islands',
	'CR' => 'Costa Rica',
	'CI' => 'Cote Divoire',
	'HR' => 'Croatia',
	'CU' => 'Cuba',
	'CW' => 'Curacao',
	'CY' => 'Cyprus',
	'CZ' => 'Czech Republic',
	'DK' => 'Denmark',
	'DJ' => 'Djibouti',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'EC' => 'Ecuador',
	'EG' => 'Egypt',
	'SV' => 'El Salvador',
	'GQ' => 'Equatorial Guinea',
	'ER' => 'Eritrea',
	'EE' => 'Estonia',
	'ET' => 'Ethiopia',
	'EU' => 'European Union',
	'EZ' => 'Eurozone',
	'FK' => 'Falkland Islands',
	'FO' => 'Faroe Islands',
	'FJ' => 'Fiji',
	'FI' => 'Finland',
	'FR' => 'France',
	'GF' => 'French Guiana',
	'PF' => 'French Polynesia',
	'TF' => 'French Southern Territories',
	'GA' => 'Gabon',
	'GM' => 'Gambia',
	'GE' => 'Georgia',
	'DE' => 'Germany',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GR' => 'Greece',
	'GL' => 'Greenland',
	'GD' => 'Grenada',
	'GP' => 'Guadeloupe',
	'GU' => 'Guam',
	'GT' => 'Guatemala',
	'GG' => 'Guernsey',
	'GN' => 'Guinea',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HT' => 'Haiti',
	'HM' => 'Heard Island And McDonald Islands',
	'VA' => 'Holy See',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong',
	'HU' => 'Hungary',
	'IS' => 'Iceland',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IR' => 'Iran',
	'IQ' => 'Iraq',
	'IE' => 'Ireland',
	'IM' => 'Isle of Man',
	'IL' => 'Israel',
	'IT' => 'Italy',
	'JM' => 'Jamaica',
	'JP' => 'Japan',
	'JE' => 'Jersey',
	'JO' => 'Jordan',
	'KZ' => 'Kazakhstan',
	'KE' => 'Kenya',
	'KI' => 'Kiribati',
	'KP' => 'North Korea',
	'KR' => 'South Korea',
	'KW' => 'Kuwait',
	'KG' => 'Kyrgyzstan',
	'LA' => 'Laos',
	'LV' => 'Latvia',
	'LB' => 'Lebanon',
	'LS' => 'Lesotho',
	'LR' => 'Liberia',
	'LY' => 'Libya',
	'LI' => 'Liechtenstein',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'MO' => 'Macao',
	'MK' => 'Macedonia',
	'MG' => 'Madagascar',
	'MW' => 'Malawi',
	'MY' => 'Malaysia',
	'MV' => 'Maldives',
	'ML' => 'Mali',
	'MT' => 'Malta',
	'MH' => 'Marshall Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MU' => 'Mauritius',
	'YT' => 'Mayotte',
	'MX' => 'Mexico',
	'FM' => 'Micronesia',
	'MD' => 'Moldova',
	'MC' => 'Monaco',
	'MN' => 'Mongolia',
	'ME' => 'Montenegro',
	'MS' => 'Montserrat',
	'MA' => 'Morocco',
	'MZ' => 'Mozambique',
	'MM' => 'Myanmar',
	'NA' => 'Namibia',
	'NR' => 'Nauru',
	'NP' => 'Nepal',
	'NL' => 'Netherlands',
	'NC' => 'New Caledonia',
	'NZ' => 'New Zealand',
	'NI' => 'Nicaragua',
	'NE' => 'Niger',
	'NG' => 'Nigeria',
	'NU' => 'Niue',
	'NF' => 'Norfolk Island',
	'MP' => 'Northern Mariana Islands',
	'NO' => 'Norway',
	'OM' => 'Oman',
	'PK' => 'Pakistan',
	'PW' => 'Palau',
	'PS' => 'Palestine',
	'PA' => 'Panama',
	'PG' => 'Papua New Guinea',
	'PY' => 'Paraguay',
	'PE' => 'Peru',
	'PH' => 'Philippines',
	'PN' => 'Pitcairn',
	'PL' => 'Poland',
	'PT' => 'Portugal',
	'PR' => 'Puerto Rico',
	'QA' => 'Qatar',
	'RE' => 'Reunion',
	'RO' => 'Romania',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'BL' => 'Saint Barthelemy',
	'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
	'KN' => 'Saint Kitts and Nevis',
	'LC' => 'Saint Lucia',
	'MF' => 'Saint Martin (French part)',
	'PM' => 'Saint Pierre and Miquelon',
	'VC' => 'Saint Vincent and the Grenadines',
	'WS' => 'Samoa',
	'SM' => 'San Marino',
	'ST' => 'Sao Tome and Principe',
	'SA' => 'Saudi Arabia',
	'SN' => 'Senegal',
	'RS' => 'Serbia',
	'SC' => 'Seychelles',
	'SL' => 'Sierra Leone',
	'SG' => 'Singapore',
	'SX' => 'Sint Maarten (Dutch part)',
	'SK' => 'Slovakia',
	'SI' => 'Slovenia',
	'SB' => 'Solomon Islands',
	'SO' => 'Somalia',
	'ZA' => 'South Africa',
	'GS' => 'South Georgia and the South Sandwich Islands',
	'SS' => 'South Sudan',
	'ES' => 'Spain',
	'LK' => 'Sri Lanka',
	'SD' => 'Sudan',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard and Jan Mayen',
	'SZ' => 'Swaziland',
	'SE' => 'Sweden',
	'CH' => 'Switzerland',
	'SY' => 'Syrian Arab Republic',
	'TW' => 'Taiwan, Province of China',
	'TJ' => 'Tajikistan',
	'TZ' => 'Tanzania',
	'TH' => 'Thailand',
	'TL' => 'Timor-Leste',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad and Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turkey',
	'TM' => 'Turkmenistan',
	'TC' => 'Turks And Caicos Islands',
	'TV' => 'Tuvalu',
	'UG' => 'Uganda',
	'UA' => 'Ukraine',
	'AE' => 'United Arab Emirates',
	'GB' => 'United Kingdom',
	'US' => 'United States',
	'UM' => 'United States Minor Outlying Islands',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VE' => 'Venezuela',
	'VN' => 'Viet Nam',
	'VG' => 'British Virgin Islands',
	'VI' => 'United States Virgin Islands',
	'WF' => 'Wallis and Futuna',
	'EH' => 'Western Sahara',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe'
);
