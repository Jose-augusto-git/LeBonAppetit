<?php

// Get selected filter type
if( isset( $_GET['filter'] ) ) {
	$filter = sanitize_key( $_GET['filter'] );
} else {
	$filter = 'plugins';
}

// Select correct database row
switch ( $filter ) {
	case 'themes':
		$db_table 		= 'notUpdateListTh';
		$filter_name 	= __( 'Themes', 'companion-auto-update' );
		$filterFunction = wp_get_themes();
		break;
	case 'plugins':
		$db_table 		= 'notUpdateList';
		$filter_name 	= __( 'Plugins', 'companion-auto-update' );
		$filterFunction = get_plugins();
		break;
	default:
		$db_table 		= 'notUpdateList';
		$filter_name 	= __( 'Plugins', 'companion-auto-update' );
		$filterFunction = get_plugins();
		break;
}

?>

<ul class="subsubsub">
	<li><a <?php if( $filter == 'plugins' ) { echo "class='current'"; } ?> href='<?php echo cau_url( 'pluginlist&filter=plugins' ); ?>'><?php _e( 'Plugins', 'companion-auto-update' ); ?></a></li> |
	<li><a <?php if( $filter == 'themes' ) { echo "class='current'"; } ?> href='<?php echo cau_url( 'pluginlist&filter=themes' ); ?>'><?php _e( 'Themes', 'companion-auto-update' ); ?></a></li>
</ul>

<div style='clear: both;'></div>

<?php if( $filter == 'themes' ) { ?>
	<div id="message" class="cau">
		We've had to (temporarily) disable the theme filter because it was causing issues on some installations. We'll try to get it working again in a future update.
	</div>
<?php } ?>

<p><?php echo sprintf( esc_html__( 'Prevent certain %s from updating automatically. %s that you select here will be skipped by Companion Auto Update and will require manual updating.', 'companion-auto-update' ), strtolower( $filter_name ), $filter_name ); ?></p>

<?php 

global $wpdb;
$table_name = $wpdb->prefix."auto_updates"; 

// Save list
if( isset( $_POST['submit'] ) ) {

	check_admin_referer( 'cau_save_pluginlist' );

	$noUpdateList 	= '';
	$i 				= 0;
	$noUpdateCount 	= 0;

	if( isset( $_POST['post'] ) ) {
		$noUpdateCount 	= count( $_POST['post'] );
	}

	if( $noUpdateCount > 0 ) {
		foreach ( $_POST['post'] as $key ) {
			$noUpdateList .= sanitize_text_field( $key );
			$i++;
			if( $i != $noUpdateCount ) $noUpdateList .= ', ';
		}
	}

	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = '%s' WHERE name = '%s'", $noUpdateList, $db_table ) );
	echo '<div id="message" class="updated"><p><b>'.__( 'Succes', 'companion-auto-update' ).' &ndash;</b> '.sprintf( esc_html__( '%1$s %2$s have been added to the no-update-list', 'companion-auto-update' ), $noUpdateCount, strtolower( $filter_name ) ).'.</p></div>';
}


// Reset list
if( isset( $_POST['reset'] ) ) {

	check_admin_referer( 'cau_save_pluginlist' );

	$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = '%s' WHERE name = %s", "", $db_table ) );
	echo '<div id="message" class="updated"><p><b>'.__( 'Succes', 'companion-auto-update' ).' &ndash;</b> '.sprintf( esc_html__( 'The no-update-list has been reset, all %s will be auto-updated from now on', 'companion-auto-update' ), strtolower( $filter_name ) ).'.</p></div>';
}


?>

<form method="POST">

	<div class='pluginListButtons'>
		<?php submit_button(); ?>
		<input type='submit' name='reset' id='reset' class='button button-alt' value='<?php _e( "Reset list", "companion-auto-update" ); ?>'>
	</div>

	<table class="wp-list-table widefat autoupdate striped">
		<thead>
			<tr>
				<td>&nbsp;</td>
				<th class="head-plugin"><strong><?php _e( 'Name', 'companion-auto-update' ); ?></strong></th>
				<th class="head-status"><strong><?php _e( 'Status', 'companion-auto-update' ); ?></strong></th>
				<th class="head-description"><strong><?php _e( 'Description' ); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">

		<?php 

		foreach ( $filterFunction as $key => $value ) {

			$slug 			= $key;
			$explosion 		= explode( '/', $slug );
			$actualSlug 	= array_shift( $explosion );
			$slug_hash 		= md5( $slug[0] );

			if( $filter == 'themes' ) {

				$theme 			= wp_get_theme( $actualSlug );
				$name 			= $theme->get( 'Name' );
				$description 	= $theme->get( 'Description' );

			} else {

				foreach ( $value as $k => $v ) {

					if( $k == "Name" ) $name = $v;
					if( $k == "Description" ) $description = $v;

				}

			}

			if( in_array( $actualSlug, donotupdatelist( $filter ) ) ) {

				$class 		= 'inactive';
				$checked 	= 'CHECKED';
				$statusicon = 'no';
				$statusName = 'disabled';

			} else {
				
				$class 		= 'active';
				$checked 	= '';
				$statusicon = 'yes';
				$statusName = 'enabled';
			}

			echo '<tr id="post-'.$slug_hash.'" class="'.$class.'">

				<th class="check-column">			
					<label class="screen-reader-text" for="cb-select-'.$slug_hash.'">Select '. $name .'</label>
					<input id="cb-select-'.$slug_hash.'" type="checkbox" name="post[]" value="'.$actualSlug.'" '.$checked.' ><label></label>
					<div class="locked-indicator"></div>
				</th>

				<td class="column-name">
					<p style="margin-bottom: 0px;"><strong>'. $name .'</strong></p>
					<small class="description" style="opacity: 0.5; margin-bottom: 3px;">'.$actualSlug.'</small>
				</td>

				<td class="cau_hide_on_mobile column-status">
					<p><span class="nowrap">'.__( 'Auto Updater', 'companion-auto-update' ).': <span class="cau_'.$statusName.'"><span class="dashicons dashicons-'.$statusicon.'"></span></span></span></p>
				</td>

				<td class="cau_hide_on_mobile column-description">
					<p>'.$description.'</p>
				</td>

			</tr>';

		}
		?>

		</tbody>
	</table>

	<?php wp_nonce_field( 'cau_save_pluginlist' ); ?>

	<div class='pluginListButtons'>
		<?php submit_button(); ?>
		<input type='submit' name='reset' id='reset' class='button button-alt' value='<?php _e( "Reset list", "companion-auto-update" ); ?>'>
	</div>

</form>