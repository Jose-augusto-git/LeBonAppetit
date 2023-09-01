<?php
/**
* Plugin Name: Expire posts
* Plugin URI: https://wordpress.org/plugins/expire-posts/
* Description: Automatic post expirator for WordPress (with custom-post-type support). NO LONGER IN ACTIVE DEVELOPMENT
* Version: 1.0.5
* Author: Marcin Karpezo
* Author URI: http://sirmacik.net
* License: MIT License
*
**/
     add_action( 'admin_menu', 'epw_create_menu' );
    function epw_create_menu() {
        add_menu_page( 'Expire posts', 'Expire posts', 'manage_options', 'epw_settings_page', 'epw_settings', 'dashicons-calendar-alt');
        add_action( 'admin_init', 'epw_register_settings' );
    }

    function epw_register_settings() {
        register_setting('epw_options', 'epw_post_type');
        register_setting('epw_options', 'epw_frequency');
        register_setting('epw_options', 'epw_expiration_time');
        register_setting('epw_options', 'epw_setting_time');
        register_setting('epw_options', 'epw_expire');
    }

    function epw_settings() {
        ?>
        <div class="wrap">
            <h2>Expire posts settings</h2>
            <p>Use these settings to choose post type, frequency of execution and default expiration time for posts</p>
        <form method="POST" action="options.php">
        <?php settings_fields( 'epw_options' ); ?>
        <?php do_settings_sections( 'epw_options' ); ?>
            <table class="form-table">
               <tr valign="top">
                    <th scope="row">Current settings:</th>
                    <td>
                        Enabled? <?php echo get_option('epw_expire'); ?><br>
                        Post type: <?php echo get_option('epw_post_type'); ?><br>
                        Frequency: <?php echo get_option('epw_frequency'); ?><br>
                        Expiration time: <?php echo get_option('epw_expiration_time'); ?><br>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="epw_expire">Enabled:</label></th>
                    <td><select name="epw_expire">
                        <option value="false">No</option>
                        <option value="true">Yes</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="epw_post_type">Post type:</label></th>
                    <td>
                        <select name="epw_post_type">
                        <option value="post">post</option>
                        <option value="page">page</option>

                        <?php
                        // determine registered public custom post types
                        $args = array(
                                   'public'   => true,
                                   '_builtin' => false
                                );

                        $output = 'objects';
                        $operator = 'and';

                        $post_types = get_post_types( $args, $output, $operator );

                        foreach ( $post_types  as $post_type ) {
                           echo '<option value=' . $post_type->name . ' >' . $post_type->labels->name . '</option>';
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="epw_frequency">Frequency</label></th>
                    <td>
                        <select name="epw_frequency">
                        <option value="hourly">Hourly</option>
                        <option value="twicedaily">Twice Daily</option>
                        <option value="daily">Daily</option>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="epw_expiration_time">Expiration time (default)</label></th>
                    <td>
                        <input maxlength="45" type="text" size="25" name="epw_expiration_time" value="2 weeks" />
                        <input maxlength="45" type="hidden" size="25" name="epw_setting_time" value="<?php echo time() ?>" />
                        <br>
                        <p>You can use values like: 2 months, 22 weeks, 10 days, 9 hours</p>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <input type="submit" name="save" value="Save options"
                               class="button-primary" />
                    </td>
                </tr>
            </table>
        </form>
        </div>
    <?php
}



    $expire = get_option('epw_expire');
	$frequency = get_option('epw_frequency');
	$expiration_time = get_option('epw_expiration_time');
	$post_type = get_option('epw_post_type');
    $settingtime = get_option('epw_setting_time');

/* what happens with the time:
    - once you save options two time values get saved:
        expiration time (for ex. 2 weeks)
        current time as unix timestamp
    - to expire post expiration time gets converted into unix timestamp
    - time to determine how long is it (setting time - expiration time)
    - same goes for post time
    - if (curent time - post time) >= (setting time - expiration time) → expire post
*/

	function epw_expire() {
		global $post_type;
		global $daysmins;
		global $expiration_time;
        global $settingtime;

		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => '-1',
			);
		$query = new WP_Query( $args );


		foreach ($query->posts as $post) {

			$postid = $post->ID;
			$postdate = get_the_date( 'U', $postid );

			$curtime = time();

			$howlong = $curtime - $postdate;
            $expiration_time = $expiration_time - $settingtime;
			echo $howlong;
			if ($postdate >= $expiration_time ) {
				$info = array(
					'ID' => $postid,
					'post_status' => 'trash'
					);
				wp_update_post( $info );
			}


		}
	}

    if ($expire == "true") {
        wp_clear_scheduled_hook( 'expire_posts' );
        add_action( 'expire_posts', 'epw_expire' );

        if ( !wp_next_scheduled('expire_posts') ) {
            wp_schedule_event( current_time( 'timestamp' ), $frequency, 'expire_posts' );
        }
    } else {
        wp_clear_scheduled_hook( 'expire_posts' );
    }

/*
#### ToDo: ####
- make it WordPress Guidelines compilant for WP plugin repository
*/
