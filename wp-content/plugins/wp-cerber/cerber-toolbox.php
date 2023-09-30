<?php
/**
 *
 * Periodically and occasionally used routines
 *
 */

/**
 * Send email notification if  plugin is available
 *
 * @param bool $no_check_freq If true, ignore the frequency setting
 * @param bool $no_check_history If true, do not check sending history. Use for testing.
 * @param bool $result The results of sending
 * @param array $info Error messages if any
 *
 * @return integer|false false if there is no information about updates, otherwise the number of messages sent
 *
 * @since 9.4.3
 */
function crb_plugin_update_notifier( $no_check_freq = false, $no_check_history = false, &$result = false, &$info = array() ) {

	if ( ! crb_get_settings( 'notify_plugin_update' ) ) {
		return false;
	}

	$updates = get_site_transient( 'update_plugins' );
	$interval = ( ! lab_lab() ) ? 24 : (int) crb_get_settings( 'notify_plugin_update_freq' );
	$interval = HOUR_IN_SECONDS * ( ( $interval < 1 ) ? 1 : $interval );

	$prev = cerber_get_set( 'plugin_update_alerting_status' );

	if ( ! $no_check_freq
	     && isset( $prev[0] )
	     && $prev[0] > ( time() - $interval ) ) {
		return false;
	}

	if ( ! $updates
	     || empty( $updates->last_checked )
	     || empty( $updates->response )
	     || ( $updates->last_checked < ( time() - $interval ) ) ) {

		delete_site_transient( 'update_plugins' );
		wp_update_plugins();

		$updates = get_site_transient( 'update_plugins' );
	}

	$errors = 0;
	$sent = 0;

	if ( empty( $updates->response ) ) {
		cerber_update_set( 'plugin_update_alerting_status',
			array(
				time(),
				( $updates->last_checked ?? 0 ),
				( $updates->checked ?? 0 ),
				$errors,
				$sent
			) );

		$info[] = __( 'No updates found.', 'wp-cerber' );

		if ( empty( $updates->checked ) ) {
			$info[] = __( 'It seems outgoing Internet connections are not allowed on your website.', 'wp-cerber' );
		}

		return false;
	}

	$history = cerber_get_set( 'plugin_update_alerting' );

	if ( ! is_array( $history ) ) {
		$history = array();
	}

	$brief = ( ! lab_lab() ) ? 0 : crb_get_settings( 'notify_plugin_update_brf' );
	$active_plugins = get_option( 'active_plugins' );
	$result = false;

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // get_plugin_data()

	foreach ( $updates->response as $plugin => $new_data ) {
		if ( ! $no_check_history && isset( $history[ $plugin ][ $new_data->new_version ] ) ) {
			continue;
		}

		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

		$name = htmlspecialchars_decode( $plugin_data['Name'] );

		$notes = array();

		if ( ! empty( $new_data->requires )
		     && ! crb_wp_version_compare( $new_data->requires ) ) {
			/* translators: Here %s is a version number like 6.1 */
			$notes[] = '[!] ' . sprintf( __( 'This update requires WordPress version %s or higher, you have %s', 'wp-cerber' ), $new_data->requires, ( $brief ? '*' : cerber_get_wp_version() ) );
		}

		if ( ! empty( $new_data->requires_php )
		     && version_compare( $new_data->requires_php, phpversion(), '>' ) ) {
			/* translators: Here %s is a version number like 6.1 */
			$notes[] = '[!] ' . sprintf( __( 'This update requires PHP version %s or higher, you have %s', 'wp-cerber' ), $new_data->requires_php, ( $brief ? '*' : phpversion() ) );
		}

		if ( ! empty( $new_data->tested )
		     && crb_wp_version_compare( $new_data->tested, '>' ) ) {
			$notes[] = '[!] ' . __( 'This update has not been tested with your version of WordPress', 'wp-cerber' );
		}

		$msg = array(
			__( 'There is a new version of a plugin installed on your website.', 'wp-cerber' ),
		);

		if ( $notes ) {
			$msg = array_merge( $msg, $notes );
		}

		$active = ( in_array( $plugin, $active_plugins ) ) ? __( 'Yes', 'wp-cerber' ) : __( 'No', 'wp-cerber' );

		$msg = array_merge( $msg, array(
			__( 'Website:', 'wp-cerber' ) . ' ' . crb_get_blogname_decoded(),
			__( 'Plugin:', 'wp-cerber' ) . ' ' . $name,
			__( 'Active:', 'wp-cerber' ) . ' ' . $active,
			__( 'Installed version:', 'wp-cerber' ) . ' ' . ( $brief ? '*' : crb_boring_escape( $plugin_data['Version'] ) ),
			__( 'New version:', 'wp-cerber' ) . ' ' . $new_data->new_version,
		) );

		if ( ! empty( $new_data->tested ) ) {
			$msg[] = __( 'Tested up to:', 'wp-cerber' ) . ' WordPress ' . $new_data->tested;
		}

		$msg[] = __( 'Plugin page:', 'wp-cerber' ) . ' ' . $new_data->url;

		if ( ! $brief ) {
			$msg[] = __( 'Manage plugins on your website:', 'wp-cerber' ) . ' ' . admin_url( 'plugins.php' );
		}

		$args = ( ! lab_lab() ) ? array() : array( 'recipients_setting' => 'notify_plugin_update_to' );

		$result = cerber_send_message( 'generic', array(
			/* translators: Here %s is a name of software package (module). */
			'subj' => sprintf( __( 'A new version of %s is available', 'wp-cerber' ), $name ),
			'text' => $msg
		), array( 'email' => 1, 'pushbullet' => 0 ), true, $args );

		if ( $result ) {
			$sent ++;
			$history[ $plugin ][ $new_data->new_version ] = time();
			if ( ! $no_check_history ) {
				cerber_update_set( 'plugin_update_alerting', $history );
			}
		}
		else {
			$errors ++;
			cerber_add_issue( __FUNCTION__, 'Unable to send a notification email. Please check the notification settings.' );
		}
	}

	cerber_update_set( 'plugin_update_alerting_status',
		array(
			time(),
			( $updates->last_checked ?? 0 ),
			( $updates->checked ?? 0 ),
			$errors,
			$sent,
			( is_array( $result ) ? $result : 0 )
		) );

	return $sent;
}

/**
 * If WordPress core find an update earlier than WP Cerber,
 * notify admin (ASAP) using postponed tasks
 *
 */
add_action( 'set_site_transient_update_plugins', function () {
	cerber_update_set( 'event_wp_found_updates', 1, null, false );
} );

/**
 * @return void
 *
 * @since 9.4.2.4
 */
function crb_log_maintainer() {

	// Get non-cached settings since they can be filled with default values in case of a DB error

	if ( ! $settings = crb_get_settings( '', true, false ) ) {
		cerber_add_issue( __FUNCTION__,	'Log processing aborted. Unable to load WP Cerber settings from the website database.', array( 'details' => cerber_db_get_errors() ) );

		return;
	}

	// Settings are OK

	$time = time();

	$days = absint( $settings['keeplog'] ) ?: cerber_get_defaults( 'keeplog' );  // @since 8.5.6
	$days_auth = absint( $settings['keeplog_auth'] ?? false ) ?: $days; // It may be not configured by the admin yet, since it's introduced in 8.5.6

	if ( $days == $days_auth ) {
		cerber_db_query( 'DELETE FROM ' . CERBER_LOG_TABLE . ' WHERE stamp < ' . ( $time - $days * 24 * 3600 ) );
	}
	else {
		cerber_db_query( 'DELETE FROM ' . CERBER_LOG_TABLE . ' WHERE user_id =0 AND stamp < ' . ( $time - $days * 24 * 3600 ) );
		cerber_db_query( 'DELETE FROM ' . CERBER_LOG_TABLE . ' WHERE user_id !=0 AND stamp < ' . ( $time - $days_auth * 24 * 3600 ) );
	}

	$days = absint( $settings['tikeeprec'] ) ?: cerber_get_defaults( 'tikeeprec' );  // @since 8.5.6
	$days_auth = absint( $settings['tikeeprec_auth'] ?? false ) ?: $days; // It may be not configured by the admin yet, since it's introduced in 8.5.6

	if ( $days == $days_auth ) {
		cerber_db_query( 'DELETE FROM ' . CERBER_TRAF_TABLE . ' WHERE stamp < ' . ( $time - $days * 24 * 3600 ) );
	}
	else {
		cerber_db_query( 'DELETE FROM ' . CERBER_TRAF_TABLE . ' WHERE user_id =0 AND stamp < ' . ( $time - $days * 24 * 3600 ) );
		cerber_db_query( 'DELETE FROM ' . CERBER_TRAF_TABLE . ' WHERE user_id !=0 AND stamp < ' . ( $time - $days_auth * 24 * 3600 ) );
	}

	// Other, non-log stuff

	cerber_db_query( 'DELETE FROM ' . CERBER_LAB_IP_TABLE . ' WHERE expires < ' . $time );

	if ( ( $settings['trashafter-enabled'] ?? 0 )
	     && $after = absint( crb_get_settings( 'trashafter' ) ) ) {

		$time = time() - DAY_IN_SECONDS * $after;

		if ( $list = get_comments( array( 'status' => 'spam' ) ) ) {
			foreach ( $list as $item ) {
				if ( $time > strtotime( $item->comment_date_gmt ) ) {
					wp_trash_comment( $item->comment_ID );
				}
			}
		}
	}
}

/**
 * Updating old activity log records to the new row format (introduced in v 3.1)
 *
 * @since 4.0
 */
function crb_once_upgrade_log() {

	if ( ! $ips = cerber_db_get_col( 'SELECT DISTINCT ip FROM ' . CERBER_LOG_TABLE . ' WHERE ip_long = 0 LIMIT 50' ) ) {
		return;
	}

	foreach ( $ips as $ip ) {
		$ip_long = cerber_is_ipv4( $ip ) ? ip2long( $ip ) : 1;
		cerber_db_query( 'UPDATE ' . CERBER_LOG_TABLE . ' SET ip_long = ' . $ip_long . ' WHERE ip = "' . $ip .'" AND ip_long = 0');
	}
}

/**
 * Copying last login data to the user sets in bulk
 *
 * @return void
 *
 * @since 9.4.2
 */
function crb_once_upgrade_cbla() {
	$status = cerber_get_set( 'cerber_db_status' ) ?: array();
	$lal = $status['lal'] ?? false;

	if ( 'done' == $lal ) {
		return;
	}

	$table = cerber_get_db_prefix() . CERBER_SETS_TABLE;

	if ( 'progress' != $lal ) {
		if ( ! cerber_db_query( 'UPDATE ' . $table . ' SET argo = 1 WHERE the_key = "' . CRB_USER_SET . '"' ) ) {
			$status['lal'] = 'done';
			cerber_update_set( 'cerber_db_status', $status );

			return;
		}
		$status['lal'] = 'progress';
		cerber_update_set( 'cerber_db_status', $status );
	}
	elseif ( ! cerber_db_get_var( 'SELECT the_key FROM ' . $table . ' WHERE the_key = "' . CRB_USER_SET . '" AND argo = 1 LIMIT 1' ) ) {
		$status['lal'] = 'done';
		cerber_update_set( 'cerber_db_status', $status );

		return;
	}

	if ( ! $users = cerber_db_get_col( 'SELECT the_id FROM ' . $table . ' WHERE the_key = "' . CRB_USER_SET . '" AND argo = 1 LIMIT 1000' ) ) {

		return;
	}

	cerber_cache_disable();

	foreach ( $users as $user_id ) {
		crb_get_last_user_login( $user_id );
		cerber_db_query( 'UPDATE ' . $table . ' SET argo = 0 WHERE the_key = "' . CRB_USER_SET . '" AND the_id = ' . $user_id );
	}

	if ( $db_errors = cerber_db_get_errors() ) {
		$db_errors = array_slice( $db_errors, 0, 10 );
		cerber_admin_notice( 'Database errors occurred while upgrading user sets to a new format.' );
		cerber_admin_notice( $db_errors );
	}
}