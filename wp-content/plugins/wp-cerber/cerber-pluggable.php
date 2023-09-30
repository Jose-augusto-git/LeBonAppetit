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

/*
 *
 *  Replacement for WordPress pluggable functions without hooks
 *
 *
 */

if ( ! function_exists( 'wp_set_password' ) ) {
	function wp_set_password( $password, $user_id ) {
		global $wpdb;

		$hash = wp_hash_password( $password );
		$wpdb->update(
			$wpdb->users,
			array(
				'user_pass'           => $hash,
				'user_activation_key' => ''
			),
			array( 'ID' => $user_id ) );

		clean_user_cache( $user_id );

		do_action( 'crb_after_reset', null, $user_id );
	}
}

if ( ! function_exists( 'wp_logout' ) ) :
	/**
	 * Log the current user out.
	 *
	 * @since 8.9.4
	 */
	function wp_logout() {
		$user_id = get_current_user_id();

		CRB_Globals::$do_not_log[ CRB_EV_UST ] = true;

		wp_destroy_current_session();
		wp_clear_auth_cookie();
		wp_set_current_user( 0 );

		/**
		 * Fires after a user is logged out.
		 *
		 * @since 1.5.0
		 * @since 5.5.0 Added the `$user_id` parameter.
		 *
		 * @param int $user_id ID of the user that was logged out.
		 */
		do_action( 'wp_logout', $user_id );
	}
endif;


// Compatibility with old versions of WordPress

if ( ! function_exists( 'get_metadata_raw' ) ) :

	/**
	 * Retrieves raw metadata value for the specified object.
	 *
	 * @param string $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
	 *                          or any other object type with an associated meta table.
	 * @param int $object_id ID of the object metadata is for.
	 * @param string $meta_key Optional. Metadata key. If not specified, retrieve all metadata for
	 *                          the specified object. Default empty.
	 * @param bool $single Optional. If true, return only the first value of the specified `$meta_key`.
	 *                          This parameter has no effect if `$meta_key` is not specified. Default false.
	 *
	 * @return mixed An array of values if `$single` is false.
	 *               The value of the meta field if `$single` is true.
	 *               False for an invalid `$object_id` (non-numeric, zero, or negative value),
	 *               or if `$meta_type` is not specified.
	 *               Null if the value does not exist.
	 * @since 5.5.0
	 *
	 */
	function get_metadata_raw( $meta_type, $object_id, $meta_key = '', $single = false ) {
		if ( ! $meta_type || ! is_numeric( $object_id ) ) {
			return false;
		}

		$object_id = absint( $object_id );
		if ( ! $object_id ) {
			return false;
		}

		/**
		 * Short-circuits the return value of a meta field.
		 *
		 * The dynamic portion of the hook, `$meta_type`, refers to the meta object type
		 * (post, comment, term, user, or any other type with an associated meta table).
		 * Returning a non-null value will effectively short-circuit the function.
		 *
		 * Possible filter names include:
		 *
		 *  - `get_post_metadata`
		 *  - `get_comment_metadata`
		 *  - `get_term_metadata`
		 *  - `get_user_metadata`
		 *
		 * @param mixed $value The value to return, either a single metadata value or an array
		 *                          of values depending on the value of `$single`. Default null.
		 * @param int $object_id ID of the object metadata is for.
		 * @param string $meta_key Metadata key.
		 * @param bool $single Whether to return only the first value of the specified `$meta_key`.
		 * @param string $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
		 *                          or any other object type with an associated meta table.
		 *
		 * @since 5.5.0 Added the `$meta_type` parameter.
		 *
		 * @since 3.1.0
		 */
		$check = apply_filters( "get_{$meta_type}_metadata", null, $object_id, $meta_key, $single, $meta_type );
		if ( null !== $check ) {
			if ( $single && is_array( $check ) ) {
				return $check[0];
			}
			else {
				return $check;
			}
		}

		$meta_cache = wp_cache_get( $object_id, $meta_type . '_meta' );

		if ( ! $meta_cache ) {
			$meta_cache = update_meta_cache( $meta_type, array( $object_id ) );
			if ( isset( $meta_cache[ $object_id ] ) ) {
				$meta_cache = $meta_cache[ $object_id ];
			}
			else {
				$meta_cache = null;
			}
		}

		if ( ! $meta_key ) {
			return $meta_cache;
		}

		if ( isset( $meta_cache[ $meta_key ] ) ) {
			if ( $single ) {
				return maybe_unserialize( $meta_cache[ $meta_key ][0] );
			}
			else {
				return array_map( 'maybe_unserialize', $meta_cache[ $meta_key ] );
			}
		}

		return null;
	}

endif;