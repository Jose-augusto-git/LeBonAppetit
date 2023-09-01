<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Recursively delete a directory and it's contents
function mt_destroy_dir($dir) {
    if (!is_dir($dir) || is_link($dir)) return @unlink($dir); // error suppressed
    foreach (scandir($dir) as $file) {
        if ($file == '.' || $file == '..') continue;
        if (!mt_destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) {
            chmod($dir . DIRECTORY_SEPARATOR . $file, 0777);
            if (!mt_destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) return false;
        };
    }
    return rmdir($dir);
}

/*
Reliably getting the dir path for the wp content folder isn't straight forward e.g. content_dir().
This is my best attempt so far (inspired by get-dir-paths.inc.php).
For the micro themes dir, we need to check for old and new multi-site directory structure.
*/
function micro_themes_dir_best_guess(){
    $wp_content_dir = str_replace(site_url(), ABSPATH, content_url());
    // if it still has http, use fallback dir method
    if (strpos($wp_content_dir, 'http') !== false){
        $wp_content_dir = WP_CONTENT_DIR;
    }
    // now for the micro-themes dir
    $micro_root_dir = $wp_content_dir . '/micro-themes/';
    // exception for multisite
    global $wp_version;
    global $blog_id;
    if ($wp_version >= 3 and is_multisite()) {
        $filename = $wp_content_dir . "/blogs.dir/";
        if(file_exists($filename)){
            if ($blog_id == '1') {
                $micro_root_dir = $wp_content_dir . '/blogs.dir/micro-themes/';
            } else {
                $micro_root_dir = $wp_content_dir . '/blogs.dir/' . $blog_id . '/micro-themes/';
            }
        } else {
            if ($blog_id == '1') {
                $micro_root_dir = $wp_content_dir . '/uploads/sites/micro-themes/';
            } else {
                $micro_root_dir = $wp_content_dir . '/uploads/sites/' . $blog_id . '/micro-themes/';
            }
        }
    }
    return $micro_root_dir;
}

// Run the cleanup code - if user sets this option
$p_name = 'preferences_themer_loader';
$options_name = 'microthemer_ui_settings';
$p = get_option($p_name);
if (!empty($p['clean_uninstall'])){
    delete_option($p_name);
    delete_option($options_name);
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . 'micro_revisions');
    mt_destroy_dir( micro_themes_dir_best_guess() );
}