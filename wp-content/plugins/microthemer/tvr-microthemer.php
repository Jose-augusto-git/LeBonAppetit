<?php
/*
Plugin Name: Microthemer
Plugin URI: https://themeover.com/microthemer
Text Domain: microthemer
Domain Path: /languages
Description: Microthemer is a feature-rich visual design plugin for customizing the appearance of ANY WordPress Theme or Plugin Content (single posts & pages, headers, footers, sidebars, contact forms, shopping carts etc.) down to the smallest detail. For CSS coders, Microthemer is a proficiency tool that allows them to rapidly restyle a WordPress site. For non-coders, Microthemer's intuitive point and click editing opens the door to advanced style customization.
Version: 7.2.4.4
Author: Themeover
Author URI: https://themeover.com
*/

// Copyright 2023 by Sebastian Webb @ Themeover

// Stop direct call
if ( !defined( 'ABSPATH') ) {
	exit;
}

// Include a simple autoload script
require dirname(__FILE__) . '/src/autoload.php';

// Plugin constants
if (!defined('MT_IS_ACTIVE')) {
	define('MT_IS_ACTIVE', true); // signal MT is active, for inactive code
} if (!defined('TVR_DEV_MODE')) {
	define('TVR_DEV_MODE', false); // signal dev mode
} if (!defined('TVR_DEBUG_DATA')) {
	define('TVR_DEBUG_DATA', false); // debug data
}

// Initiate Microthemer functionality once current_user_can() can be checked (after plugins_loaded hook runs)
function initiateMicrothemer() {

	// Admin dashboard
	if ( is_admin() ) {

		// Only run admin code for a logged in Administrator
		if (current_user_can('administrator')){
			new Microthemer\Admin();
		}
	}

	// Site frontend
	else {

		// logged in Administrator viewing the frontend - include editing assets
		if (current_user_can('administrator')){
			new Microthemer\AssetAuth('edit');
		}

		// Non-admin viewing the site - just load minimal CSS assets
		else {
			new Microthemer\AssetLoad();
		}

	}
}

add_action('plugins_loaded', 'initiateMicrothemer');




