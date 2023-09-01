<?php
	
	// Define globals
	global $wpdb;

	// Define variables
	$dateFormat 	= get_option( 'date_format' );
	$dateFormat 	.= ' '.get_option( 'time_format' );
	$table_name 	= $wpdb->prefix . "auto_updates"; 
	$schedules 		= wp_get_schedules();
	$interval_names = cau_wp_get_schedules();

	// Update the database
	if( isset( $_GET['run'] ) && $_GET['run'] == 'db_update' ) {
		cau_manual_update();
		echo '<div id="message" class="updated"><p><b>'.__( 'Database update completed' ).'</b></p></div>';
	}

	if( isset( $_GET['run'] ) && $_GET['run'] == 'db_info_update' ) {
		cau_savePluginInformation();
		echo '<div id="message" class="updated"><p><b>'.__( 'Database information update completed' ).'</b></p></div>';
	}

	if( isset( $_GET['ignore_report'] ) ) {

		$report_to_ignore 	= sanitize_text_field( $_GET['ignore_report'] );
		$allowedValues 		= array( 'seo', 'cron' );

		if( !in_array( $report_to_ignore, $allowedValues ) ) {

			wp_die( 'Trying to cheat eh?' );

		} else {

			$table_name = $wpdb->prefix . "auto_updates"; 
			$wpdb->query( $wpdb->prepare( "UPDATE {$table_name} SET onoroff = %s WHERE name = 'ignore_$report_to_ignore'", 'yes' ) );
			$__ignored 	= __( 'This report will now be ignored', 'companion-auto-update' );
			echo "<div id='message' class='updated'><p><b>$__ignored</b></p></div>";

		}

	}

?>



<div class="cau_status_page">

	<?php 

	$events = array(
		0 => array( 
			'name' 		=> __( 'Events', 'companion-auto-update' ),
			'fields' 	=> array(
				'plugins' 		=> __( 'Plugins', 'companion-auto-update' ),
				'themes' 		=> __( 'Themes', 'companion-auto-update' ),
				'minor' 		=> __( 'Core (Minor)', 'companion-auto-update' ),
				'major' 		=> __( 'Core (Major)', 'companion-auto-update' ),
				'send' 			=> __( 'Update available', 'companion-auto-update' ),
				'sendupdate' 	=> __( 'Successful update', 'companion-auto-update' ),
				'wpemails' 		=> __( 'Core notifications', 'companion-auto-update' ),
				'update_delay'	=> __( 'Log updater', 'companion-auto-update' ),
			),
			'values'	=> array(
				'plugins' 		=> 'wp_update_plugins',
				'themes' 		=> 'wp_update_themes',
				'minor' 		=> 'wp_version_check',
				'major' 		=> 'wp_version_check',
				'send' 			=> 'cau_set_schedule_mail',
				'sendupdate' 	=> 'cau_set_schedule_mail',
				'wpemails' 		=> 'cau_set_schedule_mail',
				'update_delay'	=> 'cau_log_updater',
			),
			'explain'	=> array(
				'plugins' 		=> __('Auto update plugins?', 'companion-auto-update'),
				'themes' 		=> __('Auto update themes?', 'companion-auto-update'),
				'minor' 		=> __('Auto update minor core updates?', 'companion-auto-update'),
				'major' 		=> __('Auto update major core updates?', 'companion-auto-update'),
				'send' 			=> __( 'Will notify you of available updates.', 'companion-auto-update' ),
				'sendupdate' 	=> __( 'Will notify you after successful updates.', 'companion-auto-update' ),
				'wpemails' 		=> __( 'The default WordPress notifications.', 'companion-auto-update' ),
				'update_delay'	=> __( 'Will keep track of the update log and make sure updates are delayed when needed.', 'companion-auto-update' ),
			)
		),
	);

	$__sta 	= __( 'Status', 'companion-auto-update' );
	$__int 	= __( 'Interval', 'companion-auto-update' );
	$__nxt 	= __( 'Next', 'companion-auto-update' );

	foreach( $events as $event => $info ) {

		echo "<table class='cau_status_list widefat striped'>

			<thead>
				<tr>
					<th class='cau_status_name' colspan='2'><strong>{$info['name']}</strong></th>
					<th class='cau_status_active_state'><strong>{$__sta}</strong></th>
					<th class='cau_status_interval'><strong>{$__int}</strong></th>
					<th class='cau_status_next'><strong>{$__nxt}</strong></th>
				</tr>
			</thead>

			<tbody id='the-list'>";

				foreach ( $info['fields'] as $key => $value ) {

					$is_on 			= ( cau_get_db_value( $key ) == 'on' && wp_get_schedule( $info['values'][$key] ) ) ? true : false;
					$__status  		= $is_on ? 'enabled' : 'warning';
					$__icon  		= $is_on ? 'yes-alt' : 'marker';
					$__text 		= $is_on ? __( 'Enabled', 'companion-auto-update' ) : __( 'Disabled', 'companion-auto-update' );
					$__interval 	= $is_on ? $interval_names[wp_get_schedule( $info['values'][$key] )] : '&dash;';
					$__next 		= $is_on ? date_i18n( $dateFormat, wp_next_scheduled( $info['values'][$key] ) ) : '&dash;';
					$__exp 			= !empty( $info['explain'][$key] ) ? '<br /><small>'.$info['explain'][$key].'</small>' : '';
					$__nxt 			= __( 'Next', 'companion-auto-update' );

					echo "<tr>
						<td class='cau_status_icon'><span class='dashicons dashicons-$__icon cau_$__status'></span></td>
						<td class='cau_status_name'><strong>$value</strong>$__exp</td>
						<td class='cau_status_active_state'><span class='cau_$__status'>$__text</span></td>
						<td class='cau_status_interval'>$__interval</td>
						<td class='cau_status_next'><span class='cau_mobile_prefix'>$__nxt: </span>$__next</td>
					</tr>";

				} 

			echo "</tbody>

		</table>";

	}

	?>

	<table class="cau_status_list widefat striped cau_status_warnings">

		<thead>
			<tr>
				<th class="cau_plugin_issue_name" colspan="5"><strong><?php _e( 'Status' ); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">

			<!-- checkAutomaticUpdaterDisabled -->
			<tr>	
				<td class='cau_status_icon'><span class="dashicons dashicons-update"></span></td>
				<td><?php _e( 'Auto updates', 'companion-auto-update' ); ?></td>
				<?php if ( checkAutomaticUpdaterDisabled() ) { ?>
					<td class="cau_status_active_state"><span class='cau_disabled'><span class="dashicons dashicons-no"></span> <?php _e( 'All automatic updates are disabled', 'companion-auto-update' ); ?></span></td>
					<td>
						<form method="POST">
							<?php wp_nonce_field( 'cau_fixit' ); ?>
							<button type="submit" name="fixit" class="button button-primary"><?php _e( 'Fix it', 'companion-auto-update' ); ?></button>
							<a href="https://codeermeneer.nl/documentation/known-issues-fixes/#updates_disabled" target="_blank" class="button"><?php _e( 'How to fix this', 'companion-auto-update' ); ?></a>
						</form>
					</td>
				<?php } else { ?>
					<td class="cau_status_active_state"><span class='cau_enabled'><span class="dashicons dashicons-yes-alt"></span> <?php _e( 'No issues detected', 'companion-auto-update' ); ?></span></td>
					<td></td>
				<?php } ?>
				<td></td>
			</tr>

			<!-- Connection with WP.org -->
			<tr>	
				<td class='cau_status_icon'><span class="dashicons dashicons-wordpress"></span></td>
				<td><?php _e( 'Connection with WordPress.org', 'companion-auto-update' ); ?></td>
				<?php if( wp_http_supports( array( 'ssl' ) ) == '1' ) {
					$__text		= __( 'No issues detected', 'companion-auto-update' );
					echo "<td colspan='3' class='cau_status_active_state'><span class='cau_enabled'><span class='dashicons dashicons-yes-alt'></span> $__text</span></td>";
				} else {
					$__text		= __( 'Disabled', 'companion-auto-update' );
					echo "<td colspan='3' class='cau_status_active_state'><span class='cau_disabled'><span class='dashicons dashicons-no'></span> $__text</span></td>";
				} 
				?>
			</tr>

			<!-- ignore_seo check -->
			<tr <?php if( cau_get_db_value( 'ignore_seo' ) == 'yes' ) { echo "class='report_hidden'"; } ?> >
				<td class='cau_status_icon'><span class="dashicons dashicons-search"></span></td>
				<td><?php _e( 'Search Engine Visibility', 'companion-auto-update' ); ?></td>
				<?php if( get_option( 'blog_public' ) == 0 ) { ?>
					<td colspan="2" class="cau_status_active_state">
						<span class='cau_warning'><span class="dashicons dashicons-warning"></span></span>
						<?php _e( 'You’ve chosen to discourage Search Engines from indexing your site. Auto-updating works best on sites with more traffic, consider enabling indexing for your site.', 'companion-auto-update' ); ?>
					</td>
					<td>
						<a href="<?php echo admin_url( 'options-reading.php' ); ?>" class="button"><?php _e( 'Fix it', 'companion-auto-update' ); ?></a>
						<a href="<?php echo cau_url( 'status' ); ?>&ignore_report=seo" class="button button-alt"><?php _e( 'Ignore this report', 'companion-auto-update' ); ?></a>
					</td>
				<?php } else { ?>
					<td colspan="3" class="cau_status_active_state"><span class='cau_enabled'><span class="dashicons dashicons-yes-alt"></span> <?php _e( 'No issues detected', 'companion-auto-update' ); ?></span></td>
				<?php } ?>
			</tr>

			<!-- ignore_cron check -->
			<tr <?php if( cau_get_db_value( 'ignore_cron' ) == 'yes' ) { echo "class='report_hidden'"; } ?> >
				<td class='cau_status_icon'><span class="dashicons dashicons-admin-generic"></span></td>
				<td><?php _e( 'Cronjobs', 'companion-auto-update' ); ?></td>
				<?php if( checkCronjobsDisabled() ) { ?>
					<td class="cau_status_active_state"><span class='cau_warning'><span class="dashicons dashicons-warning"></span> <?php _e( 'Disabled', 'companion-auto-update' ); ?></span></td>
					<td><code>DISABLE_WP_CRON true</code></td>
					<td>
						<a href="https://codeermeneer.nl/contact/" class="button"><?php _e( 'Contact for support', 'companion-auto-update' ); ?></a>
						<a href="<?php echo cau_url( 'status' ); ?>&ignore_report=cron" class="button button-alt"><?php _e( 'Ignore this report', 'companion-auto-update' ); ?></a>
					</td>
				<?php } else { ?>
					<td colspan="3" class="cau_status_active_state"><span class='cau_enabled'><span class="dashicons dashicons-yes-alt"></span> <?php _e( 'No issues detected', 'companion-auto-update' ); ?></span></td>
				<?php } ?>
			</tr>

			<!-- wp_version_check -->
			<tr>
				<td class='cau_status_icon'><span class="dashicons dashicons-wordpress-alt"></span></td>
				<td>wp_version_check</td>
				<?php if ( !has_filter( 'wp_version_check', 'wp_version_check' ) ) { ?>
					<td colspan="2" class="cau_status_active_state"><span class='cau_disabled'><span class="dashicons dashicons-no"></span> <?php _e( 'A plugin has prevented updates by disabling wp_version_check', 'companion-auto-update' ); ?></span></td>
					<td><a href="https://codeermeneer.nl/contact/" class="button"><?php _e( 'Contact for support', 'companion-auto-update' ); ?></a></td>
				<?php } else { ?>
					<td colspan="3" class="cau_status_active_state"><span class='cau_enabled'><span class="dashicons dashicons-yes-alt"></span> <?php _e( 'No issues detected' , 'companion-auto-update' ); ?></span></td>
				<?php } ?>
			</tr>

			<!-- VCD -->
			<tr>
				<td class='cau_status_icon'><span class="dashicons dashicons-open-folder"></span></td>
				<td>VCS</td>
				<td colspan="3" class="cau_status_active_state"><span class='cau_<?php echo cau_test_is_vcs_checkout( ABSPATH )['status']; ?>'><span class="dashicons dashicons-<?php echo cau_test_is_vcs_checkout( ABSPATH )['icon']; ?>"></span> <?php echo cau_test_is_vcs_checkout( ABSPATH )['description']; ?></span></td>
			</tr>

		</tbody>

	</table>

	<table class="autoupdate cau_status_list widefat striped cau_status_warnings">
		<thead>
			<tr>
				<th colspan="5"><strong><?php _e( 'Systeminfo', 'companion-auto-update' ); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">

			<tr>
				<td class='cau_status_icon'><span class="dashicons dashicons-wordpress"></span></td>
				<td>WordPress</td>
				<td><?php echo get_bloginfo( 'version' ); ?></td>
				<td></td>
				<td></td>
			</tr>

			<tr <?php if( version_compare( PHP_VERSION, '5.1.0', '<' ) ) { echo "class='inactive'"; } ?>>
				<td class='cau_status_icon'><span class="dashicons dashicons-media-code"></span></td>
				<td>PHP</td>
				<td><?php echo phpversion(); ?> <code>(Required: 5.1.0 or up)</code></td>
				<td></td>
				<td></td>
			</tr>

			<tr <?php if( cau_incorrectDatabaseVersion() ) { echo "class='inactive'"; } ?>>
				<td class='cau_status_icon'><span class="dashicons dashicons-database"></span></td>
				<td>Database</td>
				<td><?php echo get_option( "cau_db_version" ); ?> <code>(Latest: <?php echo cau_db_version(); ?>)</code></td>
				<td></td>
				<td></td>
			</tr>

			<tr>
				<td class='cau_status_icon'><span class="dashicons dashicons-calendar"></span></td>
				<td class="cau_status_name"><?php _e( 'Timezone' ); ?></td>
				<td class="cau_status_active_state"><?php echo cau_get_proper_timezone(); ?> (GMT <?php echo get_option('gmt_offset'); ?>) - <?php echo date_default_timezone_get(); ?></td>
				<td></td>
				<td></td>
			</tr>

		</tbody>

	</table>

	<?php 

	// If has incomptable plugins
	if( cau_incompatiblePlugins() ) { ?>

		<table class="cau_status_list no_column_width widefat striped cau_status_warnings">
			<thead>
				<tr>
					<th class="cau_plugin_issue_name" colspan="4"><strong><?php _e( 'Possible plugin issues', 'companion-auto-update' ); ?></strong></th>
				</tr>
			</thead>

			<tbody id="the-list">
				<?php

				foreach ( cau_incompatiblePluginlist() as $key => $value ) {
					if( is_plugin_active( $key ) ) {
						echo '<tr>
							<td class="cau_plugin_issue_name"><strong>'.$key.'</strong></td>
							<td colspan="2" class="cau_plugin_issue_explain">'.$value.'</td>
							<td class="cau_plugin_issue_fixit"><a href="https://codeermeneer.nl/documentation/known-issues-fixes/#plugins" target="_blank" class="button">'.__( 'How to fix this', 'companion-auto-update' ).'</a></td>
						</tr>';
					}
				}

				?>
			</tbody>

		</table>

	<?php } ?>

	<!-- Advanced info -->
	<table class="autoupdate cau_status_list widefat striped cau_status_warnings">

		<thead>
			<tr>
				<th><strong><?php _e( 'Advanced info', 'companion-auto-update' ); ?></strong> &dash; <?php _e( 'For when you need our help fixing an issue.', 'companion-auto-update' ); ?></th>
			</tr>
		</thead>

		<tbody id="the-list">
			<tr>
				<td>
					<div class='button button-primary toggle_advanced_button'><?php _e( 'Toggle', 'companion-auto-update' ); ?></div>
					<div class='toggle_advanced_content' style='display: none;'>
						<?php 
						$cau_configs 	= $wpdb->get_results( "SELECT * FROM $table_name" ); 
						array_push( $cau_configs, "WordPress: ".get_bloginfo( 'version' ) );
						array_push( $cau_configs, "PHP: ".phpversion() );
						array_push( $cau_configs, "DB: ".get_option( "cau_db_version" ).' / '.cau_db_version() );
						echo "<textarea style='width: 100%; height: 750px;'>";
							print_r( $cau_configs );
						echo "</textarea>";
						?>
					</div>
				</td>
			</tr>
		</tbody>

	</table>

	<script>jQuery( '.toggle_advanced_button' ).click( function() { jQuery( '.toggle_advanced_content' ).toggle(); });</script>

	<!-- Delay updates -->
	<table class="autoupdate cau_status_list widefat striped cau_status_warnings">

		<thead>
			<tr>
				<th><strong><?php _e( 'Delay updates', 'companion-auto-update' ); ?></strong> &dash; <?php echo ( cau_get_db_value( 'update_delay' ) == 'on' ) ? __( 'Enabled', 'companion-auto-update' ).' ('.sprintf( esc_html__( '%s days', 'companion-auto-update' ).')', cau_get_db_value( 'update_delay_days' ) ) : __( 'Disabled', 'companion-auto-update' ); ?></th>
				<th><?php _e( 'Till', 'companion-auto-update' ); ?></th>
			</tr>
		</thead>

		<tbody id="the-list">
			<?php 

			$updateLog 		= "{$wpdb->prefix}update_log"; 
			$put_on_hold 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$updateLog} WHERE put_on_hold <> '%s'", '0' ) );

			foreach ( $put_on_hold as $plugin ) {

				$__name 		= $plugin->slug;
				$__poh 			= $plugin->put_on_hold;
				$__udd 			= ( cau_get_db_value( 'update_delay_days' ) != '' ) ? cau_get_db_value( 'update_delay_days' ) : '2';
				$__date 		= date_i18n( $dateFormat, strtotime( "+".$__udd." days", $__poh ) );

				echo "<tr>
					<td>{$__name}</td>
					<td>{$__date}</td>
				</tr>";
			}

			echo empty( $put_on_hold ) ? "<tr><td>".__( 'No plugins have been put on hold.', 'companion-auto-update' )."</td></tr>" : "";

			?>
		</tbody>

	</table>

</div>

<?php 
// Remove the line
if( isset( $_POST['fixit'] ) ) {
	check_admin_referer( 'cau_fixit' );
	cau_removeErrorLine();
}

// Get wp-config location
function cau_configFile() {

	// Config file
	if ( file_exists( ABSPATH . 'wp-config.php') ) {
		$conFile = ABSPATH . 'wp-config.php';
	} else {
		$conFile = dirname(ABSPATH) . '/wp-config.php';
	}

	return $conFile;

}

// Change the AUTOMATIC_UPDATER_DISABLED line
function cau_removeErrorLine() {

	// Config file
	$conFile = cau_configFile();

	// Lines to check and replace
	$revLine 		= "define('AUTOMATIC_UPDATER_DISABLED', false);"; // We could just remove the line, but replacing it will be safer
	$posibleLines 	= array( "define( 'AUTOMATIC_UPDATER_DISABLED', true );", "define( 'AUTOMATIC_UPDATER_DISABLED', minor );" ); // The two base options
	foreach ( $posibleLines as $value ) array_push( $posibleLines, strtolower( $value ) ); // Support lowercase variants
	foreach ( $posibleLines as $value ) array_push( $posibleLines, str_replace( ' ', '', $value ) ); // For variants without spaces

	$melding 	= __( "We couldn't fix the error for you. Please contact us for further support", 'companion-auto-update' ).'.';
	$meldingS 	= 'error';

	// Check for each string if it exists
	foreach ( $posibleLines as $key => $string ) {

		if( strpos( file_get_contents( $conFile ), $string ) !== false) {
	        $contents = file_get_contents( $conFile );
			$contents = str_replace( $string, $revLine, $contents );
			file_put_contents( $conFile, $contents );
			$melding 	= __( "We've fixed the error for you", 'companion-auto-update' ).' :)';
			$meldingS 	= 'updated';
	    }

	}

	echo "<div id='message' class='$meldingS'><p><strong>$melding</strong></p></div>";

}
