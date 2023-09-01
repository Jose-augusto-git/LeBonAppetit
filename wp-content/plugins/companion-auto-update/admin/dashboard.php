<?php 

// Event schedules failed
if ( !wp_next_scheduled ( 'cau_set_schedule_mail' ) ) {
	echo '<div id="message" class="error"><p><b>'.__( 'Companion Auto Update was not able to set the event for sending you emails, please re-activate the plugin in order to set the event', 'companion-auto-update' ).'.</b></p></div>';
}

// Database requires an update
if ( cau_incorrectDatabaseVersion() ) {
        echo '<div id="message" class="error"><p><b>'.__( 'Companion Auto Update Database Update', 'companion-auto-update' ).' &ndash;</b>
        '.__( 'We need you to update to the latest database version', 'companion-auto-update' ).'. <a href="'.cau_url( 'status' ).'&run=db_update" class="button button-alt" style="background: #FFF;">'.__( 'Run updater now', 'companion-auto-update' ).'</a></p></div>';
}

// Update log DB is empty
if ( cau_updateLogDBisEmpty() ) {
        echo '<div id="message" class="error"><p><b>'.__( 'Companion Auto Update Database Update', 'companion-auto-update' ).' &ndash;</b>
        '.__( 'We need to add some information to your database', 'companion-auto-update' ).'. <a href="'.cau_url( 'status' ).'&run=db_info_update" class="button button-alt" style="background: #FFF;">'.__( 'Run updater now', 'companion-auto-update' ).'</a></p></div>';
}

// Save settings
if( isset( $_POST['submit'] ) ) {

	check_admin_referer( 'cau_save_settings' );

	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 

	// Auto updater
	$plugins 			= isset( $_POST['plugins'] ) ? sanitize_text_field( $_POST['plugins'] ) : '';
	$themes 			= isset( $_POST['themes'] ) ? sanitize_text_field( $_POST['themes'] ) : '';
	$minor 				= isset( $_POST['minor'] ) ? sanitize_text_field( $_POST['minor'] ) : '';
	$major 				= isset( $_POST['major'] ) ? sanitize_text_field( $_POST['major'] ) : '';
	$translations 		= isset( $_POST['translations'] ) ? sanitize_text_field( $_POST['translations'] ) : '';

	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'plugins'", $plugins ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'themes'", $themes ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'minor'", $minor ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'major'", $major ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'translations'", $translations ) );

	// Emails
	$send 				= isset( $_POST['cau_send'] ) ? sanitize_text_field( $_POST['cau_send'] ) : '';
	$sendupdate 		= isset( $_POST['cau_send_update'] ) ? sanitize_text_field( $_POST['cau_send_update'] ) : '';
	$sendoutdated 		= isset( $_POST['cau_send_outdated'] ) ? sanitize_text_field( $_POST['cau_send_outdated'] ) : '';
	$wpemails 			= isset( $_POST['wpemails'] ) ? sanitize_text_field( $_POST['wpemails'] ) : '';
	$email 				= isset( $_POST['cau_email'] ) ? sanitize_text_field( $_POST['cau_email'] ) : '';
	$html_or_text 		= isset( $_POST['html_or_text'] ) ? sanitize_text_field( $_POST['html_or_text'] ) : 'html';
	$dbupdateemails 	= isset( $_POST['dbupdateemails'] ) ? sanitize_text_field( $_POST['dbupdateemails'] ) : '';

	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'email'", $email ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'send'", $send ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'sendupdate'", $sendupdate ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'sendoutdated'", $sendoutdated ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'wpemails'", $wpemails ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'html_or_text'", $html_or_text ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'dbupdateemails'", $dbupdateemails ) );

	// Advanced
	$allow_editor 			= isset( $_POST['allow_editor'] ) ? sanitize_text_field( $_POST['allow_editor'] ) : '';
	$allow_author 			= isset( $_POST['allow_author'] ) ? sanitize_text_field( $_POST['allow_author'] ) : '';
	$advanced_info_emails 	= isset( $_POST['advanced_info_emails'] ) ? sanitize_text_field( $_POST['advanced_info_emails'] ) : '';
	$plugin_links_emails 	= isset( $_POST['plugin_links_emails'] ) ? sanitize_text_field( $_POST['plugin_links_emails'] ) : '';

	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'allow_editor'", $allow_editor ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'allow_author'", $allow_author ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'advanced_info_emails'", $advanced_info_emails ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'plugin_links_emails'", $plugin_links_emails ) );

	// Delay
	$update_delay 		= isset( $_POST['update_delay'] ) ? sanitize_text_field( $_POST['update_delay'] ) : '';
	$update_delay_days 	= isset( $_POST['update_delay_days'] ) ? sanitize_text_field( $_POST['update_delay_days'] ) : '';

	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'update_delay'", $update_delay ) );
	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'update_delay_days'", $update_delay_days ) );

	// Intervals

	// Set variables
	$plugin_sc 			= sanitize_text_field( $_POST['plugin_schedule'] );
	$theme_sc 			= sanitize_text_field( $_POST['theme_schedule'] );
	$core_sc 			= sanitize_text_field( $_POST['core_schedule'] );
	$schedule_mail 		= sanitize_text_field( $_POST['update_notifications'] );
	$outdated_notifier 	= sanitize_text_field( $_POST['outdated_notifier'] );

	// First clear schedules
	wp_clear_scheduled_hook( 'wp_update_plugins' );
	wp_clear_scheduled_hook( 'wp_update_themes' );
	wp_clear_scheduled_hook( 'wp_version_check' );
	wp_clear_scheduled_hook( 'cau_set_schedule_mail' );
	wp_clear_scheduled_hook( 'cau_custom_hooks_plugins' );
	wp_clear_scheduled_hook( 'cau_custom_hooks_themes' );
	wp_clear_scheduled_hook( 'cau_log_updater' );
	wp_clear_scheduled_hook( 'cau_outdated_notifier' );

	// Then set the new times

	// Plugins
	if( $plugin_sc == 'daily' ) {

		$date 			= date( 'Y-m-d' );
		$hours 			= sanitize_text_field( $_POST['plugin_schedule-sethour'] );
		$minutes 		= sanitize_text_field( $_POST['plugin_schedule-setminutes'] );
		$seconds 		= date( 's' );
		$fullDate 		= $date.' '.$hours.':'.$minutes.':'.$seconds;
		$pluginSetTime 		= strtotime( $fullDate );

		wp_schedule_event( $pluginSetTime, $plugin_sc, 'wp_update_plugins' );
		wp_schedule_event( $pluginSetTime, $plugin_sc, 'cau_custom_hooks_plugins' );
		wp_schedule_event( ( $pluginSetTime - 1800 ), $plugin_sc, 'cau_log_updater' );

	} else {
		wp_schedule_event( time(), $plugin_sc, 'wp_update_plugins' );
		wp_schedule_event( time(), $plugin_sc, 'cau_custom_hooks_plugins' );
		wp_schedule_event( ( time() - 1800 ), $plugin_sc, 'cau_log_updater' );
	}

	// Themes
	if( $theme_sc == 'daily' ) {

		$dateT 			= date( 'Y-m-d' );
		$hoursT 		= sanitize_text_field( $_POST['theme_schedule-sethour'] );
		$minutesT 		= sanitize_text_field( $_POST['theme_schedule-setminutes'] );
		$secondsT 		= date( 's' );
		$fullDateT 		= $dateT.' '.$hoursT.':'.$minutesT.':'.$secondsT;
		$themeSetTime 		= strtotime( $fullDateT );

		wp_schedule_event( $themeSetTime, $theme_sc, 'wp_update_themes' );
		wp_schedule_event( $themeSetTime, $theme_sc, 'cau_custom_hooks_themes' );

	} else {
		wp_schedule_event( time(), $theme_sc, 'wp_update_themes' );
		wp_schedule_event( time(), $theme_sc, 'cau_custom_hooks_themes' );
	}

	// Core
	if( $core_sc == 'daily' ) {

		$dateC 			= date( 'Y-m-d' );
		$hoursC 		= sanitize_text_field( $_POST['core_schedule-sethour'] );
		$minutesC 		= sanitize_text_field( $_POST['core_schedule-setminutes'] );
		$secondsC 		= date( 's' );
		$fullDateC 		= $dateC.' '.$hoursC.':'.$minutesC.':'.$secondsC;
		$coreSetTime 		= strtotime( $fullDateC );

		wp_schedule_event( $coreSetTime, $core_sc, 'wp_version_check' );

	} else {
		wp_schedule_event( time(), $core_sc, 'wp_version_check' );
	}

	// Update notifications
	if( $schedule_mail == 'daily' ) {

		$dateT 			= date( 'Y-m-d' );
		$hoursT 		= sanitize_text_field( $_POST['update_notifications-sethour'] );
		$minutesT 		= sanitize_text_field( $_POST['update_notifications-setminutes'] );
		$secondsT 		= date( 's' );
		$fullDateT 		= $dateT.' '.$hoursT.':'.$minutesT.':'.$secondsT;
		$emailSetTime 		= strtotime( $fullDateT );

		wp_schedule_event( $emailSetTime, $schedule_mail, 'cau_set_schedule_mail' );

	} else {
		wp_schedule_event( time(), $schedule_mail, 'cau_set_schedule_mail' );
	}

	// Outdated notifications
	if( $outdated_notifier == 'daily' ) {

		$dateT 			= date( 'Y-m-d' );
		$hoursT 		= sanitize_text_field( $_POST['outdated_notifier-sethour'] );
		$minutesT 		= sanitize_text_field( $_POST['outdated_notifier-setminutes'] );
		$secondsT 		= date( 's' );
		$fullDateT 		= $dateT.' '.$hoursT.':'.$minutesT.':'.$secondsT;
		$emailSetTime 		= strtotime( $fullDateT );

		wp_schedule_event( $emailSetTime, $outdated_notifier, 'cau_outdated_notifier' );

	} else {
		wp_schedule_event( time(), $outdated_notifier, 'cau_outdated_notifier' );
	}


	echo '<div id="message" class="updated"><p><b>'.__( 'Settings saved.' ).'</b></p></div>';

}

// Welcome screen for first time viewers
if( isset( $_GET['welcome'] ) ) {
	echo '<div class="welcome-to-cau welcome-bg" style="margin-bottom: 0px;">
		<div class="welcome-image">
		</div><div class="welcome-content">

		<h3>'.__( 'Welcome to Companion Auto Update', 'companion-auto-update' ).'</h3>
		<br />
		<p><strong>'.__( 'You\'re set and ready to go', 'companion-auto-update' ).'</strong></p>
		<p>'.__( 'The plugin is all set and ready to go with the recommended settings, but if you\'d like you can change them below.' ).'</p>
		<br />
		<p><strong>'.__( 'Get Started' ).': </strong> <a href="'.cau_url( 'pluginlist' ).'">'.__( 'Update filter', 'companion-auto-update' ).'</a> &nbsp; | &nbsp;
		<strong>'.__( 'More Actions' ).': </strong> <a href="http://codeermeneer.nl/cau_poll/" target="_blank">'.__('Give feedback', 'companion-auto-update').'</a> - <a href="https://translate.wordpress.org/projects/wp-plugins/companion-auto-update/" target="_blank">'.__( 'Help us translate', 'companion-auto-update' ).'</a></p>

		</div>
	</div>';
}

$cs_hooks_p = wp_get_schedule( 'cau_custom_hooks_plugins' );
$cs_hooks_t = wp_get_schedule( 'cau_custom_hooks_themes' );

?>

<div class="cau-dashboard cau-column-wide">
	
	<form method="POST">

		<div class="welcome-to-cau update-bg cau-dashboard-box">
			
			<h2 class="title"><?php _e('Auto Updater', 'companion-auto-update');?></h2>

			<table class="form-table">
				<tr>
					<td>
						<fieldset>

							<?php

							$plugins_on 		= ( cau_get_db_value( 'plugins' ) == 'on' ) ? "CHECKED" : "";
							$themes_on 			= ( cau_get_db_value( 'themes' ) == 'on' ) ? "CHECKED" : "";
							$minor_on 			= ( cau_get_db_value( 'minor' ) == 'on' ) ? "CHECKED" : "";
							$major_on 			= ( cau_get_db_value( 'major' ) == 'on' ) ? "CHECKED" : "";
							$translations_on 	= ( cau_get_db_value( 'translations' ) == 'on' ) ? "CHECKED" : "";

							echo "<p><input id='plugins' name='plugins' type='checkbox' {$plugins_on}/><label for='plugins'>".__( 'Auto update plugins?', 'companion-auto-update' )."</label></p>";
							echo "<p><input id='themes' name='themes' type='checkbox' {$themes_on}/><label for='themes'>".__( 'Auto update themes?', 'companion-auto-update' )."</label></p>";
							echo "<p><input id='minor' name='minor' type='checkbox' {$minor_on}/><label for='minor'>".__( 'Auto update minor core updates?', 'companion-auto-update' )."  <code class='majorMinorExplain'>6.0.0 > 6.0.1</code></label></p>";
							echo "<p><input id='major' name='major' type='checkbox' {$major_on}/><label for='major'>".__( 'Auto update major core updates?', 'companion-auto-update' )."  <code class='majorMinorExplain'>6.0.0 > 6.1.0</code></label></p>";
							echo "<p><input id='translations' name='translations' type='checkbox' {$translations_on}/><label for='translations'>".__( 'Auto update translation files?', 'companion-auto-update' )."</label></p>";

							?>

						</fieldset>
					</td>
				</tr>
			</table>

		</div>

		<div class="welcome-to-cau email-bg cau-dashboard-box">

			<h2 class="title"><?php _e( 'Email Notifications', 'companion-auto-update' );?></h2>

			<?php

			$db_email 	= cau_get_db_value( 'email' );
			$toemail 	= ( $db_email == '' ) ? get_option( 'admin_email' ) : $db_email;

			$hot 		= cau_get_db_value( 'html_or_text' );

			?>

			<table class="form-table">
				<tr>
					<th scope="row"><?php _e( 'Update notifications', 'companion-auto-update' );?></th>
					<td>
						<p>
							<input id="cau_send_update" name="cau_send_update" type="checkbox" <?php if( cau_get_db_value( 'sendupdate' ) == 'on' ) { echo 'checked'; } ?> />
							<label for="cau_send_update"><?php _e( 'Send me emails when something has been updated.', 'companion-auto-update' );?></label>
						</p>
						<p>
							<input id="cau_send" name="cau_send" type="checkbox" <?php if( cau_get_db_value( 'send' ) == 'on' ) { echo 'checked'; } ?> />
							<label for="cau_send"><?php _e( 'Send me emails when an update is available.', 'companion-auto-update' );?></label>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Check for outdated software', 'companion-auto-update' );?></th>
					<td>
						<p>
							<input id="cau_send_outdated" name="cau_send_outdated" type="checkbox" <?php if( cau_get_db_value( 'sendoutdated' ) == 'on' ) { echo 'checked'; } ?> />
							<label for="cau_send_outdated"><?php _e( 'Be notified of plugins that have not been tested with the 3 latest major versions of WordPress.', 'companion-auto-update' );?></label>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Email Address', 'companion-auto-update' );?></th>
					<td>
						<p>
							<label for="cau_email"><?php _e( 'To', 'companion-auto-update' ); ?>:</label>
							<input type="text" name="cau_email" id="cau_email" class="regular-text" placeholder="<?php echo get_option( 'admin_email' ); ?>" value="<?php echo esc_html( $toemail ); ?>" />
						</p>

						<p class="description"><?php _e('Seperate email addresses using commas.', 'companion-auto-update');?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Use HTML in emails?', 'companion-auto-update' );?></th>
					<td>
						<p>
							<select id='html_or_text' name='html_or_text'>
								<option value='html' <?php if( $hot == 'html' ) { echo "SELECTED"; } ?>><?php _e( 'Use HTML', 'companion-auto-update' ); ?></option>
								<option value='text' <?php if( $hot == 'text' ) { echo "SELECTED"; } ?>><?php _e( 'Use plain text', 'companion-auto-update' ); ?></option>
							</select>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Show more info in emails', 'companion-auto-update' );?></th>
					<td>
						<p>
							<label for="advanced_info_emails"><input name="advanced_info_emails" type="checkbox" id="advanced_info_emails" <?php if( cau_get_db_value( 'advanced_info_emails' ) == 'on' ) { echo "CHECKED"; } ?>> <?php _e( 'Show the time of the update', 'companion-auto-update' ); ?></label>
						</p>
						<p>
							<label for="plugin_links_emails"><input name="plugin_links_emails" type="checkbox" id="plugin_links_emails" <?php if( cau_get_db_value( 'plugin_links_emails' ) == 'on' ) { echo "CHECKED"; } ?>> <?php _e( 'Show links to WordPress.org pages', 'companion-auto-update' ); ?></label>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e( 'WordPress notifications', 'companion-auto-update' );?>
						<span class='cau_tooltip'><span class="dashicons dashicons-editor-help"></span>
							<span class='cau_tooltip_text'>
								<?php _e( 'Core notifications are handled by WordPress and not by this plugin. You can only disable them, changing your email address in the settings above will not affect these notifications.', 'companion-auto-update' );?>
							</span>
						</span>
					</th>
					<td>
						<p>
							<input id="wpemails" name="wpemails" type="checkbox" <?php if( cau_get_db_value( 'wpemails' ) == 'on' ) { echo 'checked'; } ?> />
							<label for="wpemails"><?php _e( 'By default WordPress sends an email when a core update has occurred. Uncheck this box to disable these emails.', 'companion-auto-update' ); ?></label>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Database update required', 'companion-auto-update' );?></th>
					<td>
						<p>
							<input id="dbupdateemails" name="dbupdateemails" type="checkbox" <?php if( cau_get_db_value( 'dbupdateemails' ) == 'on' ) { echo 'checked'; } ?> />
							<label for="dbupdateemails"><?php _e( 'Sometimes we\'ll need your help updating our database version to the latest version, check this box to allow us to send you an email about this.', 'companion-auto-update' ); ?></label>
						</p>
					</td>
				</tr>
			</table>

		</div>

		<div class="welcome-to-cau interval-bg cau-dashboard-box" style="overflow: hidden;">

			<h2 class="title"><?php _e( 'Intervals', 'companion-auto-update' );?></h2>

			<?php 

			function cau_show_interval_selection( $identiefier, $schedule ) {

				// Get the info
				$setValue 		= wp_get_schedule( $schedule );
				$setTime 		= wp_next_scheduled( $schedule );
				$setHour 		= date( 'H' , $setTime );
				$setMinutes 	= date( 'i' , $setTime ); 

				// Show interval selection
				echo "<p>";
					echo "<select name='$identiefier' id='$identiefier' class='schedule_interval wide interval_scheduler' data-timeblock='$identiefier'>";
						foreach ( cau_wp_get_schedules() as $key => $value ) {
							echo "<option "; if( $setValue == $key ) { echo "selected "; } echo "value='".$key."'>".$value."</option>"; 
						}
					echo "</select>";
				echo "</p>";

				// Set the time when daily is selected
				echo "<div class='timeblock-$identiefier' style='display: none;'>";

					echo "<div class='cau_schedule_input'>
						<input type='number' min='0' max='23' name='".$identiefier."-sethour' value='$setHour' maxlength='2' >
					</div><div class='cau_schedule_input_div'>
						:
					</div><div class='cau_schedule_input'>
						<input type='number' min='0' max='59' name='".$identiefier."-setminutes' value='$setMinutes' maxlength='2' > 
					</div><div class='cau_shedule_notation'>
						<span class='cau_tooltip'><span class='dashicons dashicons-editor-help'></span>
							<span class='cau_tooltip_text'>".__( 'At what time should the updater run? Only works when set to <u>daily</u>.', 'companion-auto-update' )." - ".__( 'Time notation: 24H', 'companion-auto-update' )."</span>
						</span>
					</div>";

				echo "</div>";

			}

			?>

			<div class="welcome-column">

				<h4><?php _e( 'Plugin update interval', 'companion-auto-update' );?></h4>
				<?php cau_show_interval_selection( 'plugin_schedule', 'wp_update_plugins' ); ?>

			</div>

			<div class="welcome-column">

				<h4><?php _e( 'Theme update interval', 'companion-auto-update' );?></h4>
				<?php cau_show_interval_selection( 'theme_schedule', 'wp_update_themes' ); ?>

			</div>

			<div class="welcome-column">

				<h4><?php _e( 'Core update interval', 'companion-auto-update' );?></h4>
				<?php cau_show_interval_selection( 'core_schedule', 'wp_version_check' ); ?>

			</div>

			<p></p>

			<div class="welcome-column">

				<h4><?php _e( 'Update notifications', 'companion-auto-update' );?></h4>
				<?php cau_show_interval_selection( 'update_notifications', 'cau_set_schedule_mail' ); ?>

			</div>

			<div class="welcome-column">

				<h4><?php _e( 'Outdated software', 'companion-auto-update' );?></h4>
				<?php cau_show_interval_selection( 'outdated_notifier', 'cau_outdated_notifier' ); ?>

			</div>

		</div>

		<div class="welcome-to-cau advanced-bg cau-dashboard-box">

			<h2 class="title"><?php _e( 'Advanced settings', 'companion-auto-update' ); ?></h2>

			<?php

			// Access
			$accessallowed 		= cau_allowed_user_rights_array();
			$has_editor 		= in_array( 'editor', $accessallowed ) ? true : false;
			$has_author 		= in_array( 'author', $accessallowed ) ? true : false;

			// Update delays
			$has_updatedelay 	= ( cau_get_db_value( 'update_delay' ) == 'on' ) ? true : false;

			?>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label><?php _e( 'Allow access to:', 'companion-auto-update' ); ?></label></th>
						<td>
							<p><label for="allow_administrator"><input name="allow_administrator" type="checkbox" id="allow_administrator" disabled="" checked=""><?php _e( 'Administrator', 'companion-auto-update' ); ?></label></p>
							<p><label for="allow_editor"><input name="allow_editor" type="checkbox" id="allow_editor" <?php if( $has_editor ) { echo "CHECKED"; } ?>><?php _e( 'Editor', 'companion-auto-update' ); ?></label></p>
							<p><label for="allow_author"><input name="allow_author" type="checkbox" id="allow_author" <?php if( $has_author ) { echo "CHECKED"; } ?>><?php _e( 'Author', 'companion-auto-update' ); ?></label></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Delay updates', 'companion-auto-update' ); ?></label></th>
						<td>
							<p><label for="update_delay"><input name="update_delay" type="checkbox" id="update_delay" <?php echo $has_updatedelay ? "CHECKED" : ""; ?> ><?php _e( 'Delay updates', 'companion-auto-update' ); ?></label></p>
						</td>
					</tr>
					<tr id='update_delay_days_block' <?php echo !$has_updatedelay ? "class='disabled_option'" : ""; ?>>
						<th scope="row"><label><?php _e( 'Number of days', 'companion-auto-update' ); ?></label></th>
						<td>
							<input type="number" min="0" max="31" name="update_delay_days" id="update_delay_days" class="regular-text" value="<?php echo cau_get_db_value( 'update_delay_days' ); ?>" />
							<p><?php _e( 'For how many days should updates be put on hold?', 'companion-auto-update' ); ?></p>
							<p><small><strong>Please note:</strong> Delaying updates does not work with WordPress updates yet.</small></p>
						</td>
					</tr>
				</tbody>
			</table>

		</div>

		<?php wp_nonce_field( 'cau_save_settings' ); ?>	
		
		<div class="cau_save_button">
			<?php submit_button(); ?>
		</div>
		<div class="cau_save_button__space"></div>

		<script>jQuery( '.cau-dashboard input, .cau-dashboard select, .cau-dashboard textarea' ).on( 'change', function() { jQuery('.cau_save_button').addClass( 'fixed_button' ); } );</script>

	</form>

</div><div class="cau-column-small">

	<div class="welcome-to-cau help-bg cau-dashboard-box">
		<div class="welcome-column welcome-column.welcome-column-half">
			<h3 class="support-sidebar-title"><?php _e( 'Help' ); ?></h3>
			<ul class="support-sidebar-list">
				<li><a href="https://codeermeneer.nl/stuffs/faq-auto-updater/" target="_blank"><?php _e( 'Frequently Asked Questions', 'companion-auto-update' ); ?></a></li>
				<li><a href="https://wordpress.org/support/plugin/companion-auto-update" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
			</ul>

			<h3 class="support-sidebar-title"><?php _e( 'Want to contribute?', 'companion-auto-update' ); ?></h3>
			<ul class="support-sidebar-list">
				<li><a href="http://codeermeneer.nl/cau_poll/" target="_blank"><?php _e( 'Give feedback', 'companion-auto-update' ); ?></a></li>
				<li><a href="https://codeermeneer.nl/blog/companion-auto-update-and-its-future/" target="_blank"><?php _e( 'Feature To-Do List', 'companion-auto-update' ); ?></a></li>
				<li><a href="https://translate.wordpress.org/projects/wp-plugins/companion-auto-update/" target="_blank"><?php _e( 'Help us translate', 'companion-auto-update' ); ?></a></li>
			</ul>
		</div>
		<div class="welcome-column welcome-column.welcome-column-half">
			<h3 class="support-sidebar-title"><?php _e( 'Developer?', 'companion-auto-update' ); ?></h3>
			<ul class="support-sidebar-list">
				<li><a href="https://codeermeneer.nl/documentation/auto-update/" target="_blank"><?php _e( 'Documentation' ); ?></a></li>
			</ul>
		</div>
	</div>

	<div class="welcome-to-cau support-bg cau-dashboard-box">
		<div class="welcome-column welcome-column">
			<h3><?php _e('Support', 'companion-auto-update');?></h3>
			<p><?php _e('Feel free to reach out to us if you have any questions or feedback.', 'companion-auto-update'); ?></p>
			<p><a href="https://codeermeneer.nl/contact/" target="_blank" class="button button-primary"><?php _e( 'Contact us', 'companion-auto-update' ); ?></a></p>
			<p><a href="https://codeermeneer.nl/plugins/" target="_blank" class="button button-alt"><?php _e('Check out our other plugins', 'companion-auto-update');?></a></p>
		</div>
	</div>

	<div class="welcome-to-cau love-bg cau-show-love cau-dashboard-box">
		<h3><?php _e( 'Like our plugin?', 'companion-auto-update' ); ?></h3>
		<p><?php _e('Companion Auto Update is free to use. It has required a great deal of time and effort to develop and you can help support this development by making a small donation.<br />You get useful software and we get to carry on making it better.', 'companion-auto-update'); ?></p>
		<a href="https://wordpress.org/support/plugin/companion-auto-update/reviews/#new-post" target="_blank" class="button button-alt button-hero">
			<?php _e('Rate us (5 stars?)', 'companion-auto-update'); ?>
		</a>
		<a href="<?php echo cau_donateUrl(); ?>" target="_blank" class="button button-primary button-hero">
			<?php _e('Donate to help development', 'companion-auto-update'); ?>
		</a>
		<p style="font-size: 12px; color: #BDBDBD;"><?php _e( 'Donations via PayPal. Amount can be changed.', 'companion-auto-update'); ?></p>
	</div>

	<div class="welcome-to-cau cau-dashboard-box">
		<h3><span style='background: #EBE3F7; color: #BCADD3; padding: 1px 5px; border-radius: 3px; font-size: .8em'>Plugin Promotion</span></h3>
		<h3>Keep your site fast with our Revision Manager</h3>
		<p>Post Revisions are great, but will also slow down your site. Take back control over revisions with Companion Revision Manager!</p>
		<a href="https://codeermeneer.nl/portfolio/plugin/companion-revision-manager/" target="_blank" class="button button-alt">Read more</a>
	</div>

</div>

<style>
.disabled_option {
	opacity: .5;
}
</style>

<script type="text/javascript">
	
	jQuery( '#update_delay' ).change( function() {
		jQuery( '#update_delay_days_block' ).toggleClass( 'disabled_option' );
	});
	
	jQuery( '.interval_scheduler' ).change( function() {

		var selected 	= jQuery(this).val(); // Selected value
		var timeblock 	= jQuery(this).data( 'timeblock' ); // Corresponding time block

		if( selected == 'daily' ) {
			jQuery( '.timeblock-'+timeblock ).show();
		} else {
			jQuery( '.timeblock-'+timeblock ).hide();
		}

	});
	
	jQuery( '.interval_scheduler' ).each( function() {

		var selected 	= jQuery(this).val(); // Selected value
		var timeblock 	= jQuery(this).data( 'timeblock' ); // Corresponding time block

		if( selected == 'daily' ) {
			jQuery( '.timeblock-'+timeblock ).show();
		} else {
			jQuery( '.timeblock-'+timeblock ).hide();
		}

	});

</script>