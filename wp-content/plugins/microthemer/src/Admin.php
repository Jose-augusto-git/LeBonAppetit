<?php

namespace Microthemer;

class Admin {

    // Use traits to organise this god class into related functions
    // Then, convert the traits into classes one by one over time
    use PluginTrait,
        LogTrait,
	    AdminInitTrait,
        PreferencesTrait,
	    SettingsTrait,
	    FileTrait;

	var $locale = ''; // current language
	var $lang = array(); // lang strings
	var $time = 0;

	// set this to true if version saved in DB is different, other actions may follow if new v
	var $new_version = false;

	var $optimisation_test = false;
	var $optionsName = 'microthemer_ui_settings';

	var $micro_ver_name = 'micro_revisions_version';
	var $globalmessage = array();
	var $outdatedTabIssue = 0;
	var $outdatedTabDebug = '';
	var $innoFirewall = false;
	var $ei = 0; // error index
	var $permissionshelp;


	//var $placeholderpage = 'tvr-placeholder.php';
	var $demo_video = 'https://themeover.com/videos/?name=gettingStarted';
	var $targeting_video = 'https://themeover.com/videos/?name=targeting';
	var $mt_admin_nonce = '';
	var $wp_ajax_url = '';
	var $total_sections = 0;
	var $total_selectors = 0;
	var $sel_loop_count;
	var $sel_count = 0;
	var $sel_option_count = 0;
	var $group_spacer_count = 0;
	//var $sel_lookup = array();
	var $trial = true;
	var $initial_options_html = array();
	var $imported_images = array();

	var $integrations = array();
	var $integrationsChecked = false;

	var $version_is_capped = false;

	// @var array $pages Stores all the plugin pages in an array

	// @var array $css_units Stores all the possible CSS units
	var $css_units = array();
	var $folder_item_types = array();
	//var $css_unit_sets = array();
	// @var array $options Stores the ui options for this plugin
	var $options = array();
	var $preferences = array();
    var $asset_loading_change = false;
	//var $placeholderURLs = array();
	var $serialised_post = array();
	var $propertyoptions = array();
	var $en_propertyoptions = array();
	var $property_option_groups = array();
	var $animatable = array();
	var $shorthand = array();
	var $auto_convert_map = array();
	var $legacy_groups = array();
	var $mob_preview = array();
	var $propAliases = array();
	var $cssFuncAliases = array();
	var $input_wrap_templates = array();
	var $suggested_screen_layouts = array();
	var $selector_variations = array();
	var $stylesheet_order_options = array();
	var $page_class_prefix_options = array();
	// @var array $options Stores the "to be merged" options in
	var $to_be_merged = array();
	var $dis_text = '';

	var $pre_update_preferences = array();
	// @var array $file_structure Stores the micro theme dir file structure
	var $file_structure = array();

	// temporarily keep track of the tabs that are available for the property group.
	// This saves additional processing at various stages
	var $current_pg_group_tabs = array();
	var $subgroup = '';
	// default preferences set in constructor
	var $default_preferences = array();
	var $default_preferences_exportable = array();
	var $default_preferences_resetable = array();
	var $default_preferences_dont_reset_or_export = array();
	// edge mode fixed settings
	var $edge_mode = array();

	// default media queries
	var $unq_base = '';
	var $builder_sync_tabs = array();
	var $default_folders = array();
	var $legacy_m_queries = array();
	var $default_mqs = array();
	var $bb_mqs = array();
	var $elementor_mqs = array();
	var $elementor_breakpoints = false;
	var $oxygen_mqs = array();
	var $oxygen_breakpoints = false;
	var $mq_sets = array();
	var $comb_devs = array(); // for storing all-devs + MQs in one array
	// set default custom code options (todo make use of this array throughout the program)
	var $custom_code = array();
	var $custom_code_flat = array();
	var $params_to_strip = array();
    var $level_map = '';
	var $actionHookOrder = 999999;

    // Previously deprecated
    var $longhand;

	// control debug output here

	var $debug_custom = '';
	var $debug_pulled_data = TVR_DEBUG_DATA;
	var $debug_current = TVR_DEBUG_DATA;
	var $debug_import = TVR_DEBUG_DATA;
	var $debug_merge = TVR_DEBUG_DATA;
	var $debug_save = TVR_DEBUG_DATA;
	var $debug_save_package = TVR_DEBUG_DATA;
	var $debug_selective_export = TVR_DEBUG_DATA;
	var $show_me = ''; // for quickly printing vars in the top toolbar
    var $setupError;
    var $maxUploadPrefSize = (1024 * 1024 * 10); // 10MB
    var $initial_preference_options;
    var $autoloadPreferencesList = array(
        'version',
        'num_saves',
	    'initial_scale',
	    'first_and_last',
	    'page_class_prefix',
	    'insert_custom_field_classes',
	    'g_fonts_used',
        'g_url_with_subsets',
        'gfont_subset',
	    'g_url',
	    'stylesheet_in_footer',
	    'stylesheet_order',
	    'global_styles_on_login',
	    'admin_asset_loading',
	    'admin_asset_editing',
	    'asset_loading',
	    'asset_loading_published',
	    'global_stylesheet_required',
	    'global_stylesheet_required_published',
	    'load_js',
	    'load_js_published',
	    'active_events',
	    'enq_js',
	    'active_scripts_deps',
	    'active_scripts_footer',
        'draft_mode',
        'draft_mode_uids',
	    'admin_bar_preview',
        'top_level_shortcut',
        'sync_browser_tabs',
        'admin_bar_shortcut',
	    'top_level_shortcut'
    );

	private $reporting = array(
		'max' => array(
			'fileSends' => 10,
			'dataSends' => 1,
            'auto' => array(
                'bytes' => (1024 * 1024 * 1.5), // 1.5MB
                'timeout' => 15
            ),
            'manual' => array(
				'bytes' => null, // we'll check max_post_size at point of send
				'timeout' => 120
			),
		)
	);

    private $errorsRequiringData = array(
		//'frontend.js|306|7|Uncaught ReferenceError: consol is not defined' => 1 // paste error key(s) here
	);

	function __construct(){
		$this->init();
	}

	function check_table_exists($table_name, $also_populated = false){

		global $wpdb;

		$exists = !empty(
		$wpdb->get_var(
			$wpdb->prepare("SHOW TABLES LIKE %s", $table_name)
		)
		);

		if (!$exists || !$also_populated){
			return $exists;
		}

		$wpdb->query("SELECT id FROM $table_name");

		return $exists && $wpdb->num_rows > 0;
	}











	function log_subscription_check(){

		$s = $this->preferences['subscription'];
		$checks = $this->preferences['subscription_checks'];
		$pref_array['subscription_checks'] = $checks;
		$pref_array['subscription_checks']['num']++;

		// last try, max attempts reached
		// could add a condition for max 3 days after renewal date, but holding off for now
		if ($pref_array['subscription_checks']['num'] >= $pref_array['subscription_checks']['max']){
			$pref_array['subscription_checks']['stop_attempts'] = true;
			$this->savePreferences($pref_array);
			return 'subscription check failed';
		}

		// add some time before next check
		else {
			$extra_seconds = 12 * 60 * 60; // 12 hours
			//$extra_seconds = 10;

			$inital_time = !empty($checks['next_time']) ? $checks['next_time'] : $this->time;
			$pref_array['subscription_checks']['next_time'] =
				$inital_time + ($pref_array['subscription_checks']['num'] * $extra_seconds);
			$this->savePreferences($pref_array);
			return 'defer';
		}

	}

	// check subscription if past renewal_check date
	function maybe_check_subscription(){

		$p = $this->preferences;
		$s = $p['subscription'];
		$checks = $p['subscription_checks'];

		// Note: renewal_check is 2 days after their subscription expires (to safely allow for timezone diffs)
		$renewal_time = !empty($checks['next_time']) ? $checks['next_time'] : strtotime($s['renewal_check']);

		// remote check conditions
		$after_renewal_check = (!empty($s['renewal_check']) and $this->time > $renewal_time);
		$higher_than_capped = (!empty($s['capped_version']) and
		                       version_compare($s['capped_version'], $this->version) < 0);
		$retro_check_needed = empty($p['retro_sub_check_done']);

		// if subscription check needed
		if (
			($after_renewal_check or $higher_than_capped or $retro_check_needed) and
			!empty($p['buyer_email']) and
			!empty($p['buyer_validated']) and
			empty($checks['stop_attempts'])
		){
			//$this->show_me.= 'doing auto remote check';
			// check if subscription is still active
			$this->get_validation_response($p['buyer_email'], 'scheduled');
		} else {
			//$this->show_me.= 'NOT doing auto remote check';
		}
	}



	function themeover_connection_url($email, $proxy = false){

		$test_domain = false;

		$domain =  $test_domain ? $test_domain : $this->home_url;

		$base_url = ($proxy) //|| 1 // to force proxy
			? 'https://validate.themeover.com/'
			: 'https://themeover.com/wp-content/tvr-auto-update/validate.php';

		$params = 'email='.rawurlencode($email)
		          .'&domain='.$domain
		          .'&mt_version='.$this->version;

		 //Get local URL
        //$this->show_me = 'https://tvrdev.themeover.com/wp-content/tvr-auto-update/validate.php'.'?'.$params;
        //return 'https://tvrdev.themeover.com/wp-content/tvr-auto-update/validate.php'.'?'.$params;

		return $base_url.'?'.$params;



	}


	/**
	 * Connect to themeover directly or via proxy fallback
	 *
	 * @param      $url
	 * @param $email
	 * @param bool $proxy
	 *
	 * @return array
	 */
	function connect_to_themeover($url, $email, $proxy = false){

		//$url = $this->themeover_connection_url($email, $proxy);
		//$responseString = wp_remote_fopen($url);
		$result = $this->wp_remote_fopen($url);
		$responseString = $result['body'];
		$responseCode = $result['code'];
		$response = json_decode($responseString, true);

		//$this->show_me.= 'The response from '. $url . ': '. $responseString;

		// if we have a valid result, or we have already tried the fallback proxy script, return result
		if (!empty($response['message']) or $proxy){
			return array(
				'responseString' => $responseString,
				'url' => $url,
				'code' => $responseCode
			);
		}

		// the initial connection was unsuccessful, possibly due to firewall rules, attempt proxy connection
		else {
			return $this->connect_to_themeover(
				$this->themeover_connection_url($email, true), $email, true
			);
		}

	}

	// check user can unlock / continue using MT
	function get_validation_response($email, $context = 'unlock'){

		$pref_array = array(
			'buyer_email' => $email
		);
		$was_capped_version = $this->is_capped_version();
		$response = false;
		//$url = $this->themeover_connection_url($email);
		//$responseString = $this->connect_to_themeover($url, $email);

		$connection_details = $this->connect_to_themeover(
			$this->themeover_connection_url($email),
			$email
		);

		//wp_die('<pre>$connection_details: ' . print_r($connection_details, true) . '</pre>');

		$responseString = $connection_details['responseString'];
		$response_code = $connection_details['code'];
		$url = $connection_details['url'];

		//$this->show_me.= '<pre>$connection_details: ' . print_r($connection_details, true) . '</pre>';

		/*$this->innoFirewall = array_merge($connection_details, array(
					'debug' => array(
						'responseString' => $responseString,
						'decodedResponse' => json_decode($responseString, true),
						'altDecoded' => $this->json('decode', $responseString)
					)
				));*/

		// accommodate new json response format
		if ( strpos($responseString, '{') !== false ){
			$response = json_decode($responseString, true);
			$validation = !empty($response['unlock']) ? $response['unlock'] : false;
		} else {
			// old response format - ha! this will never happen, only older versions of MT get old response format
			$validation = $responseString && strlen($responseString) < 2;
		}

		/* Trigger firewall notification screen
		 * $response['message'] = ''; // sebtest
        $connection_details['url'] = 'https://validate.themeover.com/';
		*/

		// if no valid response, check for http issue
		if (empty($response['message'])){

			//$response_code = wp_remote_retrieve_response_code( wp_remote_get($url) ); // now collected in first request

			if ($response_code != 200){
				$response['message'] = 'connection error';
				if (empty($response_code) && !empty($responseString)){
					$response_code  = esc_html($responseString);
				}
			}

			// we may have got a HTML captcha page response, display this
			else {
				$response['message'] = 'possible firewall issue';
				$this->innoFirewall = array_merge($connection_details, array(
					'debug' => array(
						'responseString' => $responseString,
						'decodedResponse' => $response,
						//'altDecoded' => $this->json('decode', $responseString)
					)
				));
			}

			$response['code'] = $response_code;

			// if scheduled subscription check, log num tries and bail if deferring
			if ($context == 'scheduled'){
				$response['message'] = $this->log_subscription_check();
				if ($response['message'] == 'defer'){
					return false;
				}
			}


		}

		// valid response format
		else {

			// save subscription response from server (includes renewal_check date)
			$pref_array['subscription'] = $response;

			// reset subscription checks if manual unlock attempted
			if ($context == 'unlock'){
				$pref_array['subscription_checks'] = $this->subscription_check_defaults;
			}

		}

		$this->change_unlock_status($context, $validation, $pref_array, $response, $was_capped_version);

        return $validation;
	}


	function change_unlock_status($context, $validation, $pref_array, $response, $was_capped_version){

		// regardless of unlock/lock no further need for retrospectively checking their subscription renewal
		$pref_array['retro_sub_check_done'] = 1;

		/* validation success */
		if ($validation) {

			$pref_array['buyer_validated'] = 1;

			if ($context == 'unlock'){
				if (!$this->preferences['buyer_validated']) { // not already validated
					$this->log(
						esc_html__('Full program unlocked!', 'microthemer'),
						'<p>' . esc_html__('Your license has been successfully validated. Microthemer\'s full program features have been unlocked!', 'microthemer') . '</p>',
						'notice'
					);
				} else {

					if ($was_capped_version){
						if (empty($response['capped_version'])){
							$this->log(
								esc_html__('Updates enabled', 'microthemer'),
								'<p>' . esc_html__('You can now update Microthemer to the latest version.', 'microthemer') . '</p>',
								'notice'
							);
						} else {
							$this->log(
								esc_html__('Version is still limited ', 'microthemer'),
								'<p>' . esc_html__('Your subscription must be renewed on themeover.com to enable Microthemer updates.', 'microthemer') . '</p>',
								'warning'
							);
						}
					}

					else {
						$this->log(
							esc_html__('Already validated', 'microthemer'),
							'<p>' . esc_html__('Your license has already been validated. The full program is currently active.', 'microthemer') . '</p>',
							'notice'
						);
					}


				}


			}

		}


		/* validation fail */
		else {

			// do checks on why validation failed here and report to user
			$pref_array['buyer_validated'] = 0;

			// prevent future subscription checks as we're already in free trial mode
			$pref_array['subscription']['renewal_check'] = false;

			$explain = '';
			$title_prefix = ($context == 'unlock') ? 'Unlock failed' : 'Notice';

			// check for returned message to give clue about problem
			if (!empty($response['message'])){

				$title = $title_prefix . ' - ' . $response['message'];

				switch ($response['message']) {

					case "missing info":
						$explain = "<p>The required unlock credentials were not provided.</p>";
						break;

					case "invalid credentials":
						$explain = '<p>The unlock credentials were invalid. Make sure you are entering 
                                        the license key shown in 
                                        <a target="_blank" href="https://themeover.com/my-account/">My Downloads</a></p>';
						break;

					case "subscription expired":
						$explain = '<p>Your subscription has expired. This means you can only  
                                        use Microthemer in free trial mode. To continue using Microthemer in 
                                        full capacity please renew or upgrade via  
                                        <a target="_blank" href="https://themeover.com/my-account/">My Downloads</a></p>';
						break;

					case "incorrect version":
						$explain = '<p>Your expired subscription does not allow you to use this version 
                                        ('.$this->version.') of Microthemer. You are eligible to use version '
						           .$response['capped_version'].', which you can download from  
                                        <a target="_blank" href="https://themeover.com/my-account/">My Downloads</a>. 
                                        You can also renew or upgrade your subscription from there if you want to 
                                        use this version of Microthemer.</p>';
						break;

					case "please upgrade":

						$limit = intval($response['limit']);
						$limit_lang = $limit === 1 ? 'domain' : 'domains';

						$explain = '<p>Domain limit ('.intval($response['limit']).') reached. Your license permits 
                                        installing Microthemer on '.intval($response['limit']).' '.$limit_lang.' in total, not '.intval($response['limit']).' '.$limit_lang.' at any one time.</p>';

						// extra text if they have already reached their limit
						/*if (count($response['domains']) > 3){
									$explain.= '<p>We started enforcing this restriction after learning that a
                                            few people have been unclear about the terms
                                            of the standard license. No worries if this includes you.</p>';
								}*/

						$explain.= '<p><a class="tvr-button" target="_blank" 
                                        href="https://themeover.com/my-account/">Please upgrade 
                                        to use Microthemer on this domain</a></p>
                                        
                                        <h3>Domains you have installed Microthemer on</h3>';

						// display domains
						$domains = '';
						foreach ($response['domains'] as $key => $arr){
							$domains.= '
                                            <li>
                                                <span class="domain-name">' . $arr['domain'] . '</span>
                                                <span class="install-date">' . $arr['date'] . '</span>
                                            </li>';
						}

						$explain.= '<ol>' . $domains . '</ol>';

						break;


					case "connection error":
					case "proxy connection error":
					case "subscription check failed":
						$code_message = !empty($response_code) ? 'HTTP response code: '.$response_code : '';
						$explain = '<p>The connection to themeover.com was unsuccessful. 
                                '.$code_message.'</p>
                            
                                <p>The connection to Themeover\'s server may have failed due to an 
                                intermittent network error. Please ensure you are connected to the internet, 
                                if working from localhost. <span class="link show-dialog" 
                                rel="unlock-microthemer">Resubmitting your email one 
                                more time</span> may do the trick</p>
                                
                                <p>Or try <b>disabling any security plugins</b> that may be 
                                blocking Microthemer\'s outbound connection. You can re-enable them after you 
                                unlock Microthemer</p>
                                
                                <p>Finally, security settings on your server may block all outgoing PHP 
                                connections to domains not on a trusted whitelist (e.g. sites that are not 
                                wordpress.org). Ask your web host about temporarily unblocking themeover.com.</p>';
						break;

				}

			}

			// unknown error
			else {
				$title = $title_prefix;
				$explain = '<p>Your license could not be validated. Make sure you are entering 
                                 the unlock code shown in <a target="_blank" href="https://themeover.com/my-account/">
                                 My Downloads</a>. If you are still stuck,  
                                <a target="_blank" href="https://themeover.com/support/contact/">please contact 
                                support for assistance</a></p>';
			}

			$this->log($title, $explain);
		}


		if (!$this->savePreferences($pref_array)) {
			$this->log(
				esc_html__('Unlock status not saved', 'microthemer'),
				'<p>' . esc_html__('Your validation status could not be saved. The program may need to be unlocked again.', 'microthemer') . '</p>'
			);
		}

		return $pref_array['subscription'];
	}

	function invalidLic(){

		$invalid = array(
			'c9f79e3d1874f6cc044513ab5357c63d'
		);

		$current = md5($this->preferences['buyer_email']);

		if ( in_array($current, $invalid) ){

			$pref_array = array(
				'buyer_email' => '',
				'buyer_validated' => 0,
				'used_invalid' => 1,
				'subscription' => array(
					'unlock' => false,
					'renewal_check' => false,
					'message' => ''
				)
			);

			$this->savePreferences($pref_array);

			return true;
		}

		return false;
	}

	// set defaults for user's property preferences (this runs on every page load)
	function maybe_set_my_props_defaults(){

		$log = array(
			'update2' => false
		);

		// for resetting during development
		/*unset($this->preferences['layout']);
				$this->preferences['my_props'] = array();
				$this->preferences['default_sug_values_set'] = 0;*/

		foreach ($this->propertyoptions as $prop_group => $array){

			foreach ($array as $prop => $meta) {

				// we're only interested in props with default units or suggested values
				if ( !isset($meta['default_unit']) and empty($meta['sug_values']) ){
					continue;
				}

				// ensure that the default unit is set, this will cater for new props too
				if (isset($meta['default_unit']) and
				    (!isset($this->preferences['my_props'][$prop_group]['pg_props'][$prop]['default_unit']) or
				     $this->preferences['my_props'][$prop_group]['pg_props'][$prop]['default_unit'] === 'px (implicit)') // legacy system default
				){
					$log['update2'] = true;
					$default_unit = $meta['default_unit']; //$this->is_time_prop($prop) ? 's' : 'px (implicit)';
					$this->preferences['my_props'][$prop_group]['pg_props'][$prop]['default_unit'] = $default_unit;
				}

				// ensure that the suggested values array is set, this will cater for new props too
				$log = $this->prepare_sug_values($log, $meta, $prop, '');
				$log = $this->prepare_sug_values($log, $meta, $prop, '_extra');
			}
		}

		// Save if changes were made to my_props
		if ($log['update2']){

			$this->savePreferences(
				array(
					'my_props' =>  $this->preferences['my_props'],
					'units_added_to_suggestions' => $this->preferences['units_added_to_suggestions']
				)
			);

			return true;
		}

		return false;
	}

	function unitCouldBeAdded($value, $unit){

		$isTimeUnit = ($unit === 's' || $unit === 'ms');

		return ( $isTimeUnit || ($value != 0 && $value !== '0') ) && is_numeric($value);
	}

	// Apply the default unit as set in MT to the suggestions (before we mostly had pixels and no unit)
	function ensureSuggestionsHaveUnits(&$suggestions, $meta){

		// if property supports units
		if (isset($meta['default_unit'])){

			$factoryDefaultUnit = $meta['default_unit'];

			foreach ($suggestions as $i => $value){

				if ( $this->unitCouldBeAdded($value, $factoryDefaultUnit) ){
					$suggestions[$i] = $value . $factoryDefaultUnit;
				}
			}
		}
	}

	function prepare_sug_values($log, $meta, $prop, $extra = ''){

		if ( !empty($meta['sug_values'.$extra]) ){

			// empty arrays by default
			$recent = $sampled = $saved = array();

			// copy MT default suggestions to sug_values key
			$sug_by_default = true; // do show suggestions by default
			$copiedSrc = ($sug_by_default and !empty($meta['select_options'.$extra]))
				? $meta['select_options'.$extra]
				: array();
			$this->ensureSuggestionsHaveUnits($copiedSrc, $meta);

			// note, this system allows EITHER root cat (only used for color) or prop
			$root_cat = !empty($meta['sug_values'.$extra]['root_cat'])
				? $meta['sug_values'.$extra]['root_cat']
				: $prop;

			/* New structure conversion - if root_cat is simple numerical array  */
			if (isset($this->preferences['my_props']['sug_values'][$root_cat]) and
			    (!count($this->preferences['my_props']['sug_values'][$root_cat]) or
			     is_int(key($this->preferences['my_props']['sug_values'][$root_cat])))
			){

				if ($root_cat == 'color') {

					// we only need to grab color once
					if (!empty($log['color_done'])){
						return $log;
					}

					$recent = $this->preferences['my_props']['sug_values'][$root_cat];
					if ( isset($this->preferences['my_props']['sug_values']['site_colors']) ){
						$sampled = $this->preferences['my_props']['sug_values']['site_colors'];
						unset($this->preferences['my_props']['sug_values']['site_colors']);
					} if ( isset($this->preferences['my_props']['sug_values']['saved_colors']) ){
						$saved = $this->preferences['my_props']['sug_values']['saved_colors'];
						unset($this->preferences['my_props']['sug_values']['saved_colors']);
					}

					$log['color_done'] = true;

				}

				// prepare arrays
				$this->preferences['my_props']['sug_values'][$root_cat] = array(
					'recent' => $recent,
					'sampled' => $sampled,
					'saved' => $saved,
					'copiedSrc' => $copiedSrc
				);

				$log['update2'] = true;
			}
			/* End new structure conversion*/

			// no conversion necessary
			else {

				// set root array if not already
				if (!isset($this->preferences['my_props']['sug_values'][$root_cat]) ){

					$this->preferences['my_props']['sug_values'][$root_cat] = array(
						'recent' => $recent,
						'sampled' => $sampled,
						'saved' => $saved,
						'copiedSrc' => $copiedSrc
					);

					// color will be set now, and doesn't need conversion true above
					if ($root_cat === 'color'){
						$log['color_done'] = true;
					}

					$log['update2'] = true;
				}

				// the root array is set
				else {

					// set copiedSrc if not already
					if (!isset($this->preferences['my_props']['sug_values'][$root_cat]['copiedSrc']) ){
						$this->preferences['my_props']['sug_values'][$root_cat]['copiedSrc'] = $copiedSrc;
						$log['update2'] = true;
					}

					// ensure units have been explicitly added (this came later)
					if ( empty($this->preferences['units_added_to_suggestions']) ){

						$sug_values = $this->preferences['my_props']['sug_values'][$root_cat];

						foreach($sug_values as $sug_key => $suggestions){
							if ($sug_key !== 'sampled' and count($suggestions)){

								$this->ensureSuggestionsHaveUnits(
									$this->preferences['my_props']['sug_values'][$root_cat][$sug_key], $meta
								);
							}
						}

						$this->preferences['units_added_to_suggestions'] = 1;
						$log['update2'] = true;
					}
				}
			}
		}

		return $log;
	}


	// check if an url is valid
	function is_valid_url( $url ) {
		if ( '' != $url ) {
			/* Using a HEAD request, we'll be able to know if the URL actually exists.
					 * the reason we're not using a GET request is because it might take (much) longer. */
			$response = wp_remote_head( $url, array( 'timeout' => 3 ) );
			/* We'll match these status codes against the HTTP response. */
			$accepted_status_codes = array( 200, 301, 302 );

			/* If no error occured and the status code matches one of the above, go on... */
			if ( ! is_wp_error( $response ) &&
			     in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
				/* Target URL exists. Let's return the (working) URL */
				return $url;
			}
			/* If we have reached this point, it means that either the HEAD request didn't work or that the URL
					 * doesn't exist. This is a fallback so we don't show the malformed URL */
			return '';
		}
		return $url;
	}




	// load full set of suggested CSS units
	function update_my_prop_default_units($new_css_units){

		$first_in_group_val = '';

		foreach ($this->preferences['my_props'] as $prop_group => $array){

			if ($prop_group == 'sug_values' ||
			    empty($this->preferences['my_props'][$prop_group]['pg_props'])
			) {
				continue;
			}

			foreach ($this->preferences['my_props'][$prop_group]['pg_props'] as $prop => $arr){

				// skip props with no default unit
				if (!isset($this->propertyoptions[$prop_group][$prop]['default_unit'])
				    or $this->is_non_length_unit($this->propertyoptions[$prop_group][$prop]['default_unit'], $prop)
				){
					continue;
				}

				// get unit
				$new_unit = isset($new_css_units[$prop_group][$prop])
					? $new_css_units[$prop_group][$prop]
					: '';

				// correct for line-height
				if ($new_unit == 'none'){
					$new_unit = '';
				}

				// set all related the same
				$box_model_rel = false;

				if (!empty($this->propertyoptions[$prop_group][$prop]['rel'])){
					$box_model_rel = $this->propertyoptions[$prop_group][$prop]['rel'];
				} elseif (!empty($this->propertyoptions[$prop_group][$prop]['unit_rel'])){
					$box_model_rel = $this->propertyoptions[$prop_group][$prop]['unit_rel'];
				}

				/*if (!empty($this->propertyoptions[$prop_group][$prop]['sub_label'])){
							$first_in_group_val = $new_unit;
						}*/
				if (!empty($this->propertyoptions[$prop_group][$prop]['unit_sub_label'])){
					$first_in_group_val = $new_unit;
				} elseif (!empty($this->propertyoptions[$prop_group][$prop]['sub_label'])){
					$first_in_group_val = $new_unit;
				}

				if ($box_model_rel){
					$new_unit = $first_in_group_val;
				}

				$this->preferences['my_props'][$prop_group]['pg_props'][$prop]['default_unit'] = $new_unit;

			}
		}

		return $this->preferences['my_props'];
	}

	// ensure all preferences are defined
	function ensure_defined_preferences($full_preferences, $pd_context){

		//$this->preferences = array(); // for testing with fresh

		// copy previous preferences for history backup
		$this->pre_update_preferences = $this->preferences;

		// backup previous version settings as special history entry if new version
		if ($this->new_version && $pd_context == 'microthemer_activated_or_updated'){
			$this->pre_upgrade_backup();
		}

		// check if all preference are defined
		//unset($this->preferences['layout']); // for debugging
		$pref_array = array();
		$update = false;
		foreach ($full_preferences as $key => $value){
			if (!isset($this->preferences[$key])){

                // load_js_published is a new preference, but the default value depends on the value for 'load_js'
                if ($key === 'load_js_published' && isset($this->preferences['load_js'])){
                    $value = $this->preferences['load_js'];
                }

				$pref_array[$key] = $value;

				$update = true;
			}
		}

		// maybe reset the reporting stats for the day
        $today = date('Y-m-d');
		$pref_array['reporting'] = isset($this->preferences['reporting'])
            ? $this->preferences['reporting']
            : $full_preferences['reporting'];

        // fix missing property added later
        if (!isset($pref_array['reporting']['file']['unique_errors'])){
	        $pref_array['reporting']['file']['unique_errors'] = (object) array();
	        $update = true;
        }

        foreach(array('file', 'data') as $reportType){
	        $last_sent = $pref_array['reporting'][$reportType]['last_sent'];
            if ($last_sent != $today){

	            $pref_array['reporting'][$reportType] = array(
                   'last_sent' => $today,
                   'sends_today' => 0,
                );

                if ($reportType === 'file'){
	                $pref_array['reporting'][$reportType]['unique_errors'] = (object) array();
                }

	            $update = true;
            }
        }

		// save new preference definitions if found
		if ($update) {
			$this->savePreferences($pref_array);
		}

		// new CSS props will be added over time and the default unit etc must be assigned.
		$this->maybe_set_my_props_defaults();

		// convert user's non_section config to modern format with meta holding little values
		// meta always gets sent in ajax save
		$this->maybe_do_data_conversions_for_update();

		// ensure view_import_stylesheets list has current theme style.css (saves preferences too)
		$this->update_css_import_urls(get_stylesheet_directory_uri() . '/style.css', 'ensure');
	}

	// create a backup of the user's settings in history and as a design pack
	function pre_upgrade_backup(){

		// no need to backup if no settings have been saved
		global $wpdb;
		$this->maybeCreateOrUpdateRevsTable(); // only creates table if doesn't exist or needs updating
		$wpdb->get_results("select id from ".$wpdb->prefix . "micro_revisions");

		$previous_version = !empty($this->preferences['previous_version'])
			? $this->preferences['previous_version']
			: 'Previous version';

		if ($wpdb->num_rows > 0){

			// add settings before update to revision table, include preferences in this special revision.
			if (!$this->updateRevisions(
				$this->options,
				$this->json_format_ua(
					'display-preferences lg-icon',
					esc_html__($previous_version.' settings (before updating to '.$this->version.')',
						'microthemer')
				),
				true, // otherwise error on new installs
				$this->preferences, //$backup_preferences,
				true
			)) {
				$this->log('','','error', 'revisions');
			}

			// clean any pre-update packs MT created when it was using that system
			$this->clean_pre_upgrade_backup_packs();

			// export the old settings too, to ensure history doesn't get wiped
			// no, history will suffice as pre_upgrade only gets cleared when another upgrade happens
			// also this was creating mulitple packs as $alt_name was preventing overwrite
			//$this->update_json_file('Pre-upgrade backup settings', 'new', true, $this->preferences);
		}

	}

	// clean any pre-update packs MT created when it using that system
	function clean_pre_upgrade_backup_packs(){

		$pattern = '/pre-upgrade-backup-settings(?:-\d)?/';

		// loop packs
		foreach ($this->file_structure as $dir => $array) {

			// delete matches
			if (preg_match($pattern, $dir)) {
				$this->tvr_delete_micro_theme($dir);
			}
		}
	}




	// manually override user preferences here after code changes
	function maybe_manually_override_preferences(){

		$update_prefs = false;
		if (!empty($this->preferences['pseudo_classes']) and count($this->preferences['pseudo_classes'])){
			$this->preferences['pseudo_classes'] = array();
			$update_prefs = true;
		} if (!empty($this->preferences['pseudo_elements']) and count($this->preferences['pseudo_elements'])){
			$this->preferences['pseudo_elements'] = array();
			$update_prefs = true;
		}

		if ($update_prefs){
			$this->savePreferences($this->preferences);
		}

		// Version 7 has targeting enabled by default, but this used to be hard set to 0.
		// So now we hard set to 'on' for initial load unless the user wants it off by default;
		$this->preferences['hover_inspect'] = !empty($this->preferences['hover_inspect_off_initially']) ? 0 : 1;


	}

	// update viewed_import_stylesheets list array
	function update_css_import_urls($url, $context = 'make top'){

		// if url is already in the array, ensure it's at the top
		$curKey = array_search($url, $this->preferences['viewed_import_stylesheets']);
		if ($context == 'make top'){
			if ($curKey !== false){
				array_splice( $this->preferences['viewed_import_stylesheets'], $curKey, 1 );
			}
			array_unshift( $this->preferences['viewed_import_stylesheets'], $url );
		}

		// unless we're just ensuring e.g. the theme's style.css is in the list
		elseif ($context == 'ensure'){
			if ( !in_array($url, $this->preferences['viewed_import_stylesheets']) ){
				$this->preferences['viewed_import_stylesheets'][] = $url;
			}
		}

		// ensure only 20 items
		$i = 0;
		$pref_array['viewed_import_stylesheets'] = array();
		foreach ($this->preferences['viewed_import_stylesheets'] as $key => $css_url){
			if (++$i > 20) break;
			$pref_array['viewed_import_stylesheets'][] = $css_url;
		}

		//$pref_array['viewed_import_stylesheets'] = array();
		$this->savePreferences($pref_array);
	}




	// add js
	function add_js() {

		if (!$this->optimisation_test){
			wp_enqueue_media(); // adds over 1000 lines of code to footer
		}

		// Run pre-wordPress 5.6 jQuery and jQuery UI (temp fix for users with sites that still have issues)
		$runLegacyJquery = !empty($this->preferences['wp55_jquery_version']);

		// jQuery UI scripts
		$jqueryUIScripts = array(
			'core',
			'widget',
			'mouse',
			'sortable',
			'position',
			'slider',
			'menu',
			'autocomplete',
			'button',
			'tooltip',
			'draggable',
			'resizable',
		);

		// core jQuery and migrate script
		$jquery_scripts = array(
			array(
				'h' => 'jquery',
				'alwaysInc' => 1,
				'dequeue' => $runLegacyJquery,
				'f' => $runLegacyJquery
					? '../js-min/legacy-jquery/jquery.js'
					: false,
				//'footer' => true
			),
			array(
				'h' => 'jquery-migrate',
				'dep' => 'jquery',
				'alwaysInc' => 1,
				'dequeue' => $runLegacyJquery,
				'f' => ($runLegacyJquery)
					? '../js-min/legacy-jquery/jquery-migrate-1.4.1-wp.js'
					: false,
				//'footer' => true
			)
		);

		$prevScript = false;
		foreach ($jqueryUIScripts as $jqi){

			$jqueryUIDeps = array('jquery', 'jquery-migrate');
			if (!empty($prevScript)){
				$jqueryUIDeps[] = 'jquery-ui-core';
				$jqueryUIDeps[] = 'jquery-ui-'.$prevScript;
			}

			$jquery_scripts[] = array(
				'h' => 'jquery-ui-'.$jqi,
				'dep' => $jqueryUIDeps,
				'alwaysInc' => 1,
				'dequeue' => $runLegacyJquery,
				'f' => $runLegacyJquery
					? '../js-min/legacy-jquery/jquery-ui/'.$jqi.'.min.js'
					: false,
				//'footer' => false

			);

			$prevScript = $jqi;
		}

		// script map
		$mt_scripts = array(

			//array('h' => 'jquery', 'alwaysInc' => 1),

			// WP 5.5 removed the migrate helper, which caused some issues for the autocomplete menu
			// e.g. clearing a single suggestion also selected the cleared item, thus returning it to the suggestions
			//array('h' => 'mt-jquery-migrate', 'alwaysInc' => 1, 'f' => '../js-min/jquery-migrate-1.4.1-wp.js'),

			// jquery/ui
			/*array('h' => 'jquery-ui-core', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-position', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-sortable', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-slider', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-menu', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-autocomplete', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-button', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-tooltip', 'dep' => 'jquery', 'alwaysInc' => 1),

					// essential for gridstack
					array('h' => 'jquery-ui-widget', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-mouse', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-draggable', 'dep' => 'jquery', 'alwaysInc' => 1),
					array('h' => 'jquery-ui-resizable', 'dep' => 'jquery', 'alwaysInc' => 1),*/


			// mt core namespace
			array('h' => 'tvr_core', 'f' => 'mt-core.js'),
			array('h' => 'tvr_mcth_cssprops', 'f' => 'data/program-data.js'), // this will be dyn soon

			// js libraries (prefix name with mt- if I've edited the library)
			// use ace2, ace4 and have /ace as sub dir for easy globs in gulp file
			array('h' => 'tvr_ace', 'f' => 'lib/ace4/ace/ace.js'),
			array('h' => 'tvr_ace_lang', 'f' => 'lib/ace4/ace/ext-language_tools.js'),
			array('h' => 'tvr_ace_search', 'f' => 'lib/ace4/ace/ext-searchbox.js'),

			// this must be loaded in head so we can extend it
			//array('h' => 'tvr_ace_html', 'f' => 'lib/ace4/ace/mode-html.js'),

			// conditionally load emmet in build version as it's quite big - come back to this
			//array('h' => 'tvr_emmet', 'f' => 'lib/emmet/emmet.js'),
			//array('h' => 'tvr_ace_emmet', 'f' => 'lib/ace4/ace/ext-emmet.js'),

			array('h' => 'tvr_gsap', 'f' => 'lib/gsap/gsap.min.js'),
			/*array('h' => 'tvr_widget', 'f' => '../src/js/mt-widget.js'),
					array('h' => 'tvr_transform', 'f' => '../src/js/mt-transform.js'),*/
			array('h' => 'tvr_gridstack', 'f' => 'lib/gridstack/gridstack.js'),
			array('h' => 'tvr_gridstack_ui', 'f' => 'lib/gridstack/gridstack.jQueryUI.js'),
			array('h' => 'tvr_extend_regexp', 'f' => 'lib/extend-native-regexp.js'),
			//array('h' => 'tvr_mcth_colorbox', 'f' => 'lib/colorbox/1.6.4/jquery.colorbox-min.js'),
			array('h' => 'tvr_spectrum', 'f' => 'lib/colorpicker/mt-spectrum.js', 'dep' => array( 'jquery' )),

			// https://github.com/beautify-web/js-beautify
			array('h' => 'tvr_html_beautify', 'f' => 'worker/beautify-html.min.js'),
			array('h' => 'tvr_css_beautify', 'f' => 'lib/js-beautify/beautify-css.js'),
			array('h' => 'tvr_sprintf', 'f' => 'lib/sprintf/sprintf.min.js'),
			array('h' => 'tvr_parser', 'f' => 'lib/parser.js'),
			//array('h' => 'tvr_ast_query', 'f' => 'lib/query-ast.js'), // doesn't play well with gonz
			array('h' => 'tvr_scss_parser', 'f' => 'lib/gonzales.js'),
			array('h' => 'tvr_cssutilities', 'f' => 'lib/mt-cssutilities.js'),
			//array('h' => 'tvr_cssutilities', 'f' => 'lib/CSSUtilities.js'), // for comparing customised

			// try out a new sortable library as jquery seems buggy when there are lots of selectors
			//array('h' => 'tvr_sortable', 'f' => 'lib/sortable/mt-sortable.js'),
			array('h' => 'tvr_sortable', 'f' => 'lib/sortable/mt-sortable-1.13.js'),

			// custom modules
			array('h' => 'tvr_main_and_worker', 'f' => '../js/worker/mt-main-and-worker.js'),
			array('h' => 'tvr_utilities', 'f' => 'mod/mt-utilities.js'),
			array('h' => 'tvr_widget', 'f' => 'mod/mt-widget.js'),
			array('h' => 'tvr_layout', 'f' => 'mod/mt-layout.js', 'page' => array(
				$this->microthemeruipage
			)),
			//array('h' => 'tvr_transform', 'f' => '../src/js/mt-transform.js'),
			array('h' => 'tvr_init', 'f' => 'mod/mt-init.js'),
			array('h' => 'tvr_mod_ace', 'f' => 'mod/mt-ace.js'),
			array('h' => 'tvr_mod_integrate', 'f' => 'mod/mt-integrate.js'),
			array('h' => 'tvr_mod_loop', 'f' => 'mod/mt-loop.js'),
			array('h' => 'tvr_mod_dd', 'f' => 'mod/mt-dom-data.js'),
			array('h' => 'tvr_mod_save', 'f' => 'mod/mt-save.js'),
			array('h' => 'tvr_mod_sass', 'f' => 'mod/mt-sass.js'),
			array('h' => 'tvr_mod_grid', 'f' => 'mod/mt-grid.js'),
			array('h' => 'tvr_mod_folder', 'f' => 'mod/mt-folder.js'),
			array('h' => 'tvr_mod_local', 'f' => 'mod/mt-local.js',
			      'page' => array(
				      $this->microthemeruipage,
				      $this->detachedpreviewpage
			      )),

			// page specific (non-min)
			array('h' => 'tvr_main_ui', 'f' => 'page/microthemer.js', 'page' => array($this->microthemeruipage)),
			array('h' => 'tvr_broadcast', 'f' => 'mod/mt-broadcast.js', 'page' => array($this->microthemeruipage)),
			array('h' => 'tvr_man', 'f' => 'page/packs.js', 'page' => 'other'),
			array('h' => 'tvr_fonts', 'f' => 'page/fonts.js', 'page' => array($this->fontspage)),
			array('h' => 'tvr_detached', 'f' => 'page/detached-preview.js', 'page' => array($this->detachedpreviewpage)),

			// min (deps.js combines all libraries and includes
			// apart from ace that didn't concat well with it's web workers for some reason
			array('h' => 'tvr_ace', 'f' => '../js-min/ace/ace.js', 'min' => 1),
			array('h' => 'tvr_ace_lang', 'f' => '../js-min/ace/ext-language_tools.js', 'min' => 1),
			array('h' => 'tvr_ace_search', 'f' => '../js-min/ace/ext-searchbox.js', 'min' => 1),

			// this must be loaded in head so we can extend it
			//array('h' => 'tvr_ace_html', 'f' => '../js-min/ace/mode-html.js', 'min' => 1),

			// page specific (min)
			array('h' => 'tvr_sassjs', 'f' => '../js-min/sass/sass.js', 'alwaysInc' => 1, 'ifSASS'),
			array('h' => 'tvr_deps', 'f' => '../js-min/deps.js', 'min' => 1),
			array('h' => 'tvr_mcth_cssprops', 'f' => '../js-min/program-data.js', 'min' => 1,
			      'skipScript' => !empty($this->preferences['inlineJsProgData']) ),
			array('h' => 'tvr_main_ui', 'f' => '../js-min/microthemer.js', 'min' => 1, 'page' => array($this->microthemeruipage)),
			array('h' => 'tvr_broadcast', 'f' => '../js-min/mt-broadcast.js', 'min' => 1, 'page' => array($this->microthemeruipage)),
			array('h' => 'tvr_man', 'f' => '../js-min/packs.js', 'min' => 1, 'page' => 'other'),
			array('h' => 'tvr_fonts', 'f' => '../js-min/fonts.js', 'min' => 1, 'page' => array($this->fontspage)),
			array('h' => 'tvr_detached', 'f' => '../js-min/detached-preview.js', 'min' => 1, 'page' => array($this->detachedpreviewpage)),

		);

		// combine jQuery and other scripts
		$scripts = array_merge($jquery_scripts, $mt_scripts);
		//$scripts = $mt_scripts;

		//wp_die('<pre>'.print_r($scripts, true).'</pre>');

		// output scripts based on various conditions
		$js_path = $this->thispluginurl.'js/';
		$v = '?v='.$this->version;
		foreach ($scripts as $key => $arr){
			$dep = !empty($arr['dep']) ? $arr['dep'] : false;
			$do_script = true;

			// filter out page specific scripts
			if (!empty($arr['page'])){
				if ( is_array($arr['page']) and !in_array($_GET['page'], $arr['page'])){
					$do_script = false;
				}
				if ($arr['page'] == 'other' and
				    ($_GET['page'] == $this->microthemeruipage or
				     $_GET['page'] == $this->fontspage or
				     $_GET['page'] == $this->detachedpreviewpage
				    )){
					$do_script = false;
				}
			}

			// only show correct script for dev/production
			if ( empty($arr['alwaysInc']) ) {
				if ((TVR_DEV_MODE and !empty($arr['min']))
				    or (!TVR_DEV_MODE and empty($arr['min']))
				    or !empty($arr['skipScript'])
				){
					$do_script = false;
				}
			}

			// always inc - check condition
			else {

				// skip sass.js if not enabled
				if ( $arr['alwaysInc'] === 'ifSASS' && !$this->preferences['allow_scss']){
					$do_script = false;
				}
			}

			// register/enqueue
			if ($do_script){
				if (!empty($arr['dequeue'])){
					//wp_dequeue_script( $arr['h'] );
					wp_scripts()->remove( $arr['h'] );
				}
				if (!empty($arr['f'])){
					wp_register_script( $arr['h'], $js_path . $arr['f']. $v, $dep );
				}
				wp_enqueue_script( $arr['h'], '', $dep, $v, !empty($arr['footer']));
			}
		}


		/*// for article
				$jqueryUIScripts = array(
					'core',
					'widget',
					'mouse',
					'sortable',
					'menu',
					'autocomplete',
				);

				$prevScript = false;

				foreach ($jqueryUIScripts as $scriptName){

					$jqueryUIDeps = array('jquery', 'jquery-migrate');

					if (!empty($prevScript)){
						$jqueryUIDeps[] = 'jquery-ui-core';
						$jqueryUIDeps[] = 'jquery-ui-'.$prevScript;
					}

					wp_scripts()->remove( $scriptName );

					wp_enqueue_script(
						$scriptName, '/wp-content/themes/my-theme/js/temp-fix/'.$scriptName.'.js',
						$jqueryUIDeps
					);

					$prevScript = $scriptName;
				}*/


		// load js strings for translation
		include_once $this->thisplugindir . 'includes/js-i18n.inc.php';

	}

	// initiate vars that are wp dependent
	function setup_wp_dependent_vars(){

		// ajax url - requires wp_create_nonce()
		$this->wp_ajax_url = $this->wp_blog_admin_url . 'admin-ajax.php' . '?action=mtui&mcth_simple_ajax=1&page='.$this->microthemeruipage.'&_wpnonce='.wp_create_nonce('mcth_simple_ajax');

		$pd_context = 'setup_wp_dependent_vars';

		// setup program data arrays (program data default MQs are dependent on which builder is active)
		include dirname(__FILE__) .'/../includes/program-data.php';

		// Write Microthemer version specific array data to JS file (can be static for each version).
		// This can be done in dev mode only (also, some servers don't like creating JS files)
		if (TVR_DEV_MODE){ // temp disable and false
			$this->write_mt_version_specific_js();
		}

		//$this->get_site_pages(); // for debug

	}

	// check_integrations (on the admin side)
	function check_integrations(){

		if (!$this->integrationsChecked){
			$check = array(
				'FLBuilder' => 'bb-plugin/fl-builder.php',
				'FLBuilder_lite' => 'beaver-builder-lite-version/fl-builder.php',
				'elementor' => 'elementor/elementor.php',
				'oxygen' => 'oxygen/functions.php'
			);

			// set config
			foreach ($check as $key => $plugin){
				if ( is_plugin_active( $plugin ) ) {

					// two versions of BB, try using same key
					$key = ($key === 'FLBuilder_lite') ? 'FLBuilder' : $key;

					$this->integrations[$key] = 1;
				}
			}

			// if BB, provide way to load a BB breakpoint set
			if ( !empty($this->integrations['FLBuilder']) ){

				$bb_global = get_option('_fl_builder_settings');
				$small = !empty($bb_global->responsive_breakpoint)
					? $bb_global->responsive_breakpoint : 768;
				$medium = !empty($bb_global->medium_breakpoint)
					? $bb_global->medium_breakpoint : 992;
				$large = !empty($bb_global->large_breakpoint)
					? $bb_global->large_breakpoint : 1200;
				$xl = $large + 1;

				//wp_die('<pre>'.print_r($bb_global, true).'</pre>');

				// append BB media query option to
				$this->bb_mqs = array(
					/*$this->unq_base.'bbxl' => array(
							    "label" => __('BB XL Only', 'microthemer'),
							    "query" => "@media (min-width: {$xl}px)",
							    "site_preview_width" => "builder.FLBuilder.xl"
						    ),*/
					$this->unq_base.'bb0' => array(
						"label" => __('BB Large', 'microthemer'),
						"query" => "@media (max-width: {$large}px)",
						"site_preview_width" => "builder.FLBuilder.large"
					),
					$this->unq_base.'bb1' => array(
						"label" => __('BB Medium', 'microthemer'),
						"query" => "@media (max-width: {$medium}px)",
						"site_preview_width" => "builder.FLBuilder.medium"
					),
					$this->unq_base.'bb2' => array(
						"label" => __('BB Small', 'microthemer'),
						"query" => "@media (max-width: {$small}px)",
						"site_preview_width" => "builder.FLBuilder.small"
					),
				);

				$this->concat_builder_sync_options($this->bb_mqs);

				$this->mq_sets[esc_html__('Beaver Builder MQs', 'microthemer')] = $this->bb_mqs;
			}

			// if Elementor, provide way to load an Elementorbreakpoint set
			if ( !empty($this->integrations['elementor']) ){

				if (method_exists('\Elementor\Core\Responsive\Responsive', 'get_breakpoints')){

					$this->elementor_breakpoints = \Elementor\Core\Responsive\Responsive::get_breakpoints();
					// todo the above is Class/method is deprecated, need to switch to the below code (or something else) but formatting needs to be adjusted - haven't spent time on it yet.
                    // $this->elementor_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

					// elementor breakpoints to not match the preview screen. They are calculated from lg and md minus 1.
					$tablet_max = $this->elementor_breakpoints['lg'] - 1;
					$mobile_max = $this->elementor_breakpoints['md'] - 1;

					// Elementor media query option
					$this->elementor_mqs = array(
						/*// Not using Desktop label as Elmentor has that for full width. Yet 1025 is a custom setting.
								$this->unq_base.'elem1' => array(
								   "label" => __('Max: '.$breakpoints['lg'], 'microthemer'),
								   "query" => "@media (max-width: {$breakpoints['lg']}px)",
							   ),*/
						$this->unq_base.'elem2' => array(
							"label" => __('Elementor Tablet', 'microthemer'),
							"query" => "@media (max-width: {$tablet_max}px)",
							"site_preview_width" => "builder.elementor.tablet"
						),
						$this->unq_base.'elem3' => array(
							"label" => __('Elementor Mobile', 'microthemer'),
							"query" => "@media (max-width: {$mobile_max}px)",
							"site_preview_width" => "builder.elementor.mobile"
						),

					);

					$this->concat_builder_sync_options($this->elementor_mqs);

					$this->mq_sets[esc_html__('Elementor MQs', 'microthemer')] = $this->elementor_mqs;
				}


			}

			// get oxygen breakpoints $media_queries_list (global)
			/*global $media_queries_list_above, $media_queries_list;
	$this->show_me = '<pre>$media_queries_list: '.print_r($media_queries_list, true). '</pre>' .
					 '<pre>$media_queries_list_above: '.print_r($media_queries_list_above, true). '</pre>';*/

			// if Oxygen, provide way to load an Oxygen breakpoint set
			if ( !empty($this->integrations['oxygen']) ){

				global $media_queries_list, $media_queries_list_above;

				// get a copy of Oxygen global media query array so we don't update global instance
				// when setting maxSize on page-width key using $global_page_width
				// this is unset because oxygen allows different page widths
				// but MT doesn't support dynamic MQ tabs
				$mq_copy = $this->array_clone($media_queries_list);
				$mq_above_copy = $this->array_clone($media_queries_list_above);

				if ( isset($mq_copy) && function_exists('oxygen_vsb_get_page_width') ){

					$global_page_width = oxygen_vsb_get_page_width(true);
					$mq_copy['page-width']['maxSize'] = $global_page_width.'px';
					$tablet_max = $mq_copy['tablet']['maxSize'];
					$phone_landscape_max = $mq_copy['phone-landscape']['maxSize'];
					$phone_portrait_max = $mq_copy['phone-portrait']['maxSize'];
					$page_container_label = __('Page container', 'microthemer');
					$and_below_label = __('and below', 'microthemer');

					// Oxygen media query option
					$this->oxygen_mqs = array(

						$this->unq_base.'_oxy_page_width' => array(
							"label" => $page_container_label, //.' ('.$global_page_width.'px) '.$and_below_label,
							"query" => "@media (max-width: {$global_page_width}px)",
							"site_preview_width" => "builder.oxygen.page-width"
						),
						$this->unq_base.'_oxy_tablet' => array(
							"label" => $mq_copy['tablet']['title'],
							"query" => "@media (max-width: {$tablet_max})",
							"site_preview_width" => "builder.oxygen.tablet"
						),
						$this->unq_base.'_oxy_phone_landscape' => array(
							"label" => $mq_copy['phone-landscape']['title'],
							"query" => "@media (max-width: {$phone_landscape_max})",
							"site_preview_width" => "builder.oxygen.phone-landscape"
						),
						$this->unq_base.'_oxy_phone_portrait' => array(
							"label" => $mq_copy['phone-portrait']['title'],
							"query" => "@media (max-width: {$phone_portrait_max})",
							"site_preview_width" => "builder.oxygen.phone-portrait"
						),

					);

					$this->concat_builder_sync_options($this->oxygen_mqs);

					// make MQ set available
					$this->mq_sets[esc_html__('Oxygen MQs', 'microthemer')] = $this->oxygen_mqs;

					// save breakpoint data we might need later
					$this->oxygen_breakpoints = $mq_copy;

					//$this->show_me = '';
				}


			}

			// so it doesn't run twice when activating/updating
			$this->integrationsChecked = true;

		}

	}

	function concat_builder_sync_options($mqs){

		foreach ($mqs as $array){
			$this->builder_sync_tabs[] = $array['site_preview_width'];
		}
	}

	// clone an array - useful if we want to edit global array only locally
	function array_clone($array) {
		return array_map(function($element) {
			return ((is_array($element))
				? $this->array_clone($element)
				: ((is_object($element))
					? clone $element
					: $element
				)
			);
		}, $array);
	}

	// output meta tags to prevent browser back/forward cache from generating
	// false positive multiple tabs warning
	function add_no_cache_headers(){
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}

	// color variables and customisable panel sizes for left and right sidebars
	function add_dyn_inline_css(){

        //wp_die('layout <pre>' . print_r($this->preferences['layout'], 1) . '</pre>');

		$layout = $this->preferences['layout'];
		$inspCols = $layout['inspection_columns']['column_sizes'];

		// MT side columns are customisable, default to saved widths on load
		// Note, left and right columns are have position absolutes so resizing isn't jerky
		$column_sizes = $layout['left']['column_sizes'];
        $effective_num_columns = $layout['left']['effective_num_columns'];
		/*$min_column_sizes = $this->preferences['layout']['left']['min_column_sizes'];
				minmax('.$min_column_sizes[$i].'px, */
		$gridColumns = '';
		$gridColumnsFooter = '';
		foreach ($column_sizes as $i => $size) {

			$mainSize = $size.'px ';
			$footerSize = $mainSize;
            $columnNumber = $i+1;

			if ($columnNumber > intval($effective_num_columns)){
	            $mainSize = '0 ';
                if ($columnNumber > 1){
	                $footerSize = '0 ';;
                }
            }

			$gridColumns.= $mainSize;
			$gridColumnsFooter.= $footerSize;
		}

        // Right columns
		$rightColumn = $layout['right']['column_sizes'][1].'px';
        $effectiveRightColumn = $layout['right']['effective_num_columns'] > 0 ? $rightColumn : 0;
        $mainAndPlaceholder = '1fr 0 ';
		$gridColumns.= $mainAndPlaceholder . $effectiveRightColumn;
		$gridColumnsFooter.= $mainAndPlaceholder . $rightColumn;

		// color variables
		$colorVariablesRuleSet = !empty($this->preferences['mt_color_variables_css'])
			? '.sp-container .tile-color, .tvr-input-wrap .var-color-box { '.strip_tags($this->preferences['mt_color_variables_css']).' }'
			: '';

		echo '
                <style id="mt_color_variables">'.$colorVariablesRuleSet.' </style>
                <style id="main-interface-columns">
                    .visual-view {
                        grid-template-columns: '.$gridColumns.';
                    }
                </style>
                <style id="mt-footer-columns">
                    #advanced-wizard {
                        grid-template-columns: '.$gridColumnsFooter.';
                    }
                </style>
                <style id="inspection-columns">
                    .wizard-panes { 
                        grid-template-columns: 1fr minmax(auto, '.$inspCols[0].'px) minmax(auto, '.$inspCols[1].'px);
                    }
                </style>';

	}

	/* function add_svg_sprite(){
				 echo $this->svg('sprite', array('id' => 'mt-svg-sprite', 'style' => 'display: none'), false);
			 }*/

	// add css
	function add_css() {

		// Google fonts
		//wp_register_style('tvrGFonts', '//fonts.googleapis.com/css2?family=Fira+Code:wght@600;700&family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,700&display=swap');
		wp_register_style('tvrGFonts', $this->thispluginurl.'local-google-font/load-mt-gf.css?v='.$this->version);
		wp_enqueue_style( 'tvrGFonts');

		// if dev mode, enqueue css libraries separately
		if (TVR_DEV_MODE){

			// color picker, colorbox, jquery ui styling
			wp_enqueue_style( 'spectrum-colorpicker',
				$this->thispluginurl . 'js/lib/colorpicker/spectrum.css?v=' . $this->version );
			/*wp_register_style( 'tvr_mcth_colorbox_styles',
						$this->thispluginurl.'css/colorbox.css?v='.$this->version );
					wp_enqueue_style( 'tvr_mcth_colorbox_styles' );*/
			wp_register_style( 'tvr_jqui_styles', $this->thispluginurl.'css/jquery-ui1.11.4.css?v='.$this->version );
			wp_enqueue_style( 'tvr_jqui_styles' );

			$main_css_file = $this->thispluginurl.'css/styles.css';
			$fonts_css_file = 'fonts.css';

		} else {

			//wp_register_style( 'tvr_mcth_colorbox_styles',
			//$this->thispluginurl.'js/lib/colorbox/1.3.19/colorbox.css?v='.$this->version );
			//wp_enqueue_style( 'tvr_mcth_colorbox_styles' );

			// in production, all css will be minified and concatenated to concat-styles.min.css
			$main_css_file = $this->thispluginurl.'css/concat-styles.min.css';
			$fonts_css_file = 'fonts.min.css';
		}

		// enqueue main stylesheet
		wp_register_style( 'tvr_mcth_styles', $main_css_file.'?v='.$this->version );
		wp_enqueue_style( 'tvr_mcth_styles' );

		// extra styles for fonts page
		if ($_GET['page'] === $this->fontspage){
			wp_register_style( 'tvr_font_styles', $this->thispluginurl.'css/'.$fonts_css_file.'?v='.$this->version );
			wp_enqueue_style( 'tvr_font_styles' );
		}

		// preferences page doesn't want toolbar hack, so add to stylesheet conditionally
		if ($_GET['page'] != $this->preferencespage){
			$custom_css = "
						html, html.wp-toolbar {
							padding-top:0
						}";
			wp_add_inline_style( 'tvr_mcth_styles', $custom_css );
		}

	}

	// preload icon font and have @font-face inline to ensure $this->version preloads correct file
	function load_icon_font($return = false){

		//$dir = $this->root_rel($this->thispluginurl, false, true). 'images/icon-font/';
		//$dir = $this->thispluginurl . 'images/icon-font/';

		$rel_path = 'images/icon-font/';

		// we use root rel so that this works with browser sync
		// and actual path = true so it works on local sub-dir install e.g. /wp-versions/really-fresh/
		//$dir = $this->root_rel($this->thispluginurl, false, true, true).$rel_path;

		// but if the user has a strange setup, stick to simple path
		/*$test_url = $this->site_url . $dir. 'microthemer.woff2';
				if (filter_var($test_url, FILTER_VALIDATE_URL) === FALSE) {
					$dir = $this->thispluginurl . $rel_path;
					//die('Not a valid URL ' . $dir. 'microthemer.woff2');
				}*/


		// the above options caused issues - and it seems to work with browser sync...
		$dir = $this->thispluginurl . $rel_path;

		$preload = '<link rel="preload" as="font" crossorigin="anonymous" href="'.$dir.'microthemer.woff2?v='.$this->version.'" />';
		$fontFace = "
                <style>
                     @font-face {
                      font-family: 'microthemer';
                      src: url(".$dir . 'microthemer.woff2?v='.$this->version.") format('woff2'),
                           url(".$dir . 'microthemer.woff?v='.$this->version.") format('woff');
                      font-weight: normal;
                      font-style: normal;
                    }   
                </style>";

		if ($return){
			return $fontFace;
		} else {
			echo $preload . "\n" . $fontFace;
		}
	}

	// build array for property/value input fields
	function getPropertyOptions() {
		$propertyOptions = array();
		$legacy_groups = array();
		$this->animatable = array(

			array(
				'label' => 'all',
				'category' => ''
			),
			array(
				'label' => 'none',
				'category' => ''
			)
		);
		include $this->thisplugindir . 'includes/property-options.inc.php';
		$this->propertyoptions = $propertyOptions;

		// populate $property_option_groups, $auto_convert_map, and animatable array
		foreach ($propertyOptions as $prop_group => $array){
			foreach ($array as $prop => $meta) {
				// pg group
				if ( !empty($meta['pg_label']) ){
					$pg_label = $meta['pg_label'];
					$this->property_option_groups[$prop_group] = $pg_label;
				}
				// auto convert
				if ( !empty($meta['auto']) ){
					$this->auto_convert_map[$prop] = $meta['auto'];
				}
				// animatable properties
				if ( !empty($meta['animatable']) ){
					$cssf = str_replace('_', '-', $prop);

					// adjust for shorthand
					if ($cssf == 'text-shadow-x'){
						$cssf = 'text-shadow';
					} else if ($cssf == 'box-shadow-x'){
						$cssf = 'box-shadow';
					}

					// include extra shorthand
					else if ($cssf == 'padding-top'){
						$this->animatable[] = array(
							'label' => 'padding',
							'category' => $pg_label
						);
					} else if ($cssf == 'margin-top'){
						$this->animatable[] = array(
							'label' => 'margin',
							'category' => $pg_label
						);
					} else if ($cssf == 'border-top-color'){
						$this->animatable[] = array(
							'label' => 'border-color',
							'category' => $pg_label
						);
					} else if ($cssf == 'border-top-width'){
						$this->animatable[] = array(
							'label' => 'border-width',
							'category' => $pg_label
						);
					} else if ($cssf == 'flex-grow'){
						$this->animatable[] = array(
							'label' => 'flex',
							'category' => $pg_label
						);
					}

					$this->animatable[] = array(
						'label' => $cssf,
						'category' => $pg_label
					);
				}
			}
		}
		$this->legacy_groups = $legacy_groups;

	}

	// update shorthand map array
	function update_shorthand_map($shorthand, $cssf, $prop_group, $prop, $propArr, $sh) {
		//$this->show_me.= print_r($sh[0], true);
		$shorthand[$sh[0]][$cssf] = array(
			'group' => $prop_group,
			'prop' => $prop,
			'index' => $sh[1],
			'config' => !empty($sh[2]) ? $sh[2] : array()
		);
		// signal if prop is !important carrier
		!empty($propArr['important_carrier']) ? $shorthand[$sh[0]][$cssf]['important_carrier'] = 1 : 0;
		// signal if prop can have multiple
		!empty($propArr['multiple']) ? $shorthand[$sh[0]][$cssf]['multiple'] = 1 : 0;
		// and if MT supports this
		!empty($propArr['multiple_sup']) ? $shorthand[$sh[0]][$cssf]['multiple_sup'] = 1 : 0;

		return $shorthand;
	}

	// update the array for checking match criteria when gathering interesting css values from site stylesheets
	function update_gc_css_match($gc_css_match, $type, $val){
		$gc_css_match[] = array(
			'type' => $type,
			'val' => $val
		);
		return $gc_css_match;
	}

	// create static JS file for property options etc that relate to the current version of MT
	function write_mt_version_specific_js($dir = 'js/data', $inline = false) {

		$write_file = '';

		if (!$inline){

			// Create new file if it doesn't already exist
			$js_file = $this->thisplugindir . $dir . '/program-data.js';
			$write_file = @fopen($js_file, 'w');

			if (!$write_file) {
				$this->log(
					esc_html__('Permission Error', 'microthemer'),
					'<p>' . sprintf( esc_html__('WordPress does not have permission to update: %s', 'microthemer'), $this->root_rel($js_file) . '. '.$this->permissionshelp ) . '</p>'
				);

				return 0;
			}
		}

		// some CSS properties need adjustment for jQuery .css() call
		// include any prop that needs special treatment for one reason or another
		$exceptions = array(
			'display' => array('display-flex'),
			'font-family' => 'google-font',
			'grid-template-areas' => 'grid-template-areas-add',
			'list-style-image' => 'list-style-image',
			'text-shadow' => array(
				'text-shadow-x',
				'text-shadow-y',
				'text-shadow-blur',
				'text-shadow-color'),
			'box-shadow' => array(
				'box-shadow-x',
				'box-shadow-y',
				'box-shadow-blur',
				'box-shadow-spread',
				'box-shadow-color',
				'box-shadow-inset'),
			'background-img-full' => array(
				'background-image',
				'gradient-angle',
				'gradient-a',
				'gradient-b',
				'gradient-b-pos',
				'gradient-c'
			),
			'background-position' => 'background-position',
			'background-position-custom' => array(
				'background-position-x',
				'background-position-y'
			),
			'background-repeat' => 'background-repeat',
			'background-attachment' => 'background-attachment',
			'background-size' => 'background-size',
			'background-clip' => 'background-clip',
			'border-top-left-radius' => 'radius-top-left',
			'border-top-right-radius' => 'radius-top-right',
			'border-bottom-right-radius' => 'radius-bottom-right',
			'border-bottom-left-radius' =>'radius-bottom-left',

			'keys' => array(
				'background-position-x' => array(
					'0%' => 'left',
					'100%' => 'right',
					'50%' => 'center'
				),
				'background-position-y' => array(
					'0%' => 'top',
					'100%' => 'bottom',
					'50%' => 'center'
				),
				'gradient-angle' => array(
					'180deg' => 'top to bottom',
					'0deg' => 'bottom to top',
					'90deg' => 'left to right',
					'-90deg' => 'right to left',
					'135deg' => 'top left to bottom right',
					'-45deg' => 'bottom right to top left',
					'-135deg' => 'top right to bottom left',
					'45deg' => 'bottom left to top right'
				),
				// webkit has a different interpretation of the degrees - doh!
				'webkit-gradient-angle' => array(
					'-90deg' => 'top to bottom',
					'90deg' => 'bottom to top',
					'0deg' => 'left to right',
					'180deg' => 'right to left',
					'-45deg' => 'top left to bottom right',
					'135deg' => 'bottom right to top left',
					'-135deg' => 'top right to bottom left',
					'45deg' => 'bottom left to top right'
				)
			)
		);


		$this->propAliases = array(

			'display-flex' => 'display',
			'column-gap-flex' => 'column-gap',
			'row-gap-flex' => 'row-gap',

			// properties that should go to grid prop in format cssf-grid
			'display-grid' => 'display',
			'justify-items-grid' => 'justify-items',
			'justify-content-grid' => 'justify-content',
			//'justify-self-grid' => 'justify-self', // use grid as default group for this
			'align-items-grid' => 'align-items',
			'align-content-grid' => 'align-content',
			'align-self-grid' => 'align-self',
			'order-grid' => 'order',
			'z-index-grid' => 'z-index',

			// caution order an z-index appear twice here!
			// this has implications for resolve_repeated_property_group (OK for now)

			// properties that should go to grid all fields
			'width-gridall' => 'width',
			'height-gridall' => 'height',
			'grid-area-gridall' => 'grid-area',
			'order-gridall' => 'order',
			'z-index-gridall' => 'z-index'

		);


		$this->cssFuncAliases = array(

			// transform css functions
			'rotate' => 'rotatez', // rotate does the same thing as rotateZ

			// filter css functions
			'opacity-function' => 'opacity',
		);

		// var for storing then writing json data to JS file
		$data = '';

		// shorthand properties in this array (like padding, font etc) also have longhand single props.
		// At the JS end, these single props can be got from tapping the browser's comp CSS
		// unlike only shorthand props in the $exceptions array above.
		$shorthand = array();

		// I should have left space for this in the $shorthand array, I will never learn
		$shorthand_prefixes = array();

		// longhand for checking against regular css properties
		// Also a general deposit for property data we want JavaScript to have access to
		$longhand = array();

		// And object for storing the subgroup keys e.g. just padding, rather than padding and margin
		$sub_group_keys = array();

		// also need to map subgroups to groups so pg_disabed[padding] will load group options
		$sub_group_to_group = array();

		// temporary reference map/storage for style values from site's stylesheets e.g. color palette
		// certain styles are saved to my_props
		$gathered_css = array(
			'eligable' => array(),
			'store' => array(
				//'site_colors' => array(),
				//'saved_colors' => array(),
			),
			'root_cat_keys' => array(),
		);

		// combo array for storing data for comboboxes
		$combo = array();

		// css props for passing jQuery.css() to get computed CSS
		$css_props = array();

		// we need a collection of props for pg tabs
		$pg_tab_props = array();

		// for mapping a pgtab control to a group
		$pg_tab_map = array();

		$unsupported_css_func_map = array(
			'matrix' => 'transform', // unsupported shorthand matrices that cannot be reliably converted to longhand functions
			'matrix3d' => 'transform',
			'rotate3d' => 'transform'
		);

		// for mapping css functions to the shorthand prop
		$css_func_map = array(
			'rotate' => 'transform', // alias for rotateZ
		);

		$sub_slug = '';

		// loop through property options, creating various JS key map arrays
		foreach ($this->propertyoptions as $prop_group => $array) {

			foreach ($array as $prop => $propArr) {

				// new sub group, update and save reference to array
				if (!empty($propArr['sub_slug'])){
					$sub_slug = $propArr['sub_slug'];
					$sub_group_keys[$sub_slug] = array();
					$sub_group_array = &$sub_group_keys[$sub_slug];

					$sub_group_to_group[$sub_slug] = $prop_group;
				}

				// store prop in sub_array
				$sub_group_array[] = $prop;

				// we loop the group props in a specified order so the CSS props are in the same place, so we need group too
				if ($sub_slug !== $prop_group){
					$sub_group_keys[$prop_group][] = $prop;
				}

				// this could be replaced with hardcoded values in property-options.inc.php
				$cssf = str_replace('_', '-', $prop);
				$css_props[$prop_group][] = array(
					'prop' => $prop,
					'cssf' => $cssf
				);

				// update tab control array and grid items map
				if (!empty($propArr['tab_control'])){

					// we're setting the valid syntax here, I think that makes sense as property group is included
					$valid_syntax = !empty($this->propAliases[$cssf])
						? $this->propAliases[$cssf]
						: $cssf;

					$pg_tab_props[$prop_group][$propArr['tab_control']][$prop] = $valid_syntax;
					$pg_tab_map[$propArr['tab_control']] = $prop_group;
				}

				// update shorthand map
				if (!empty($propArr['sh'])){
					// like with border, border-top, and border-color shorthands affecting 1 prop
					if (is_array($propArr['sh'][0])){
						foreach($propArr['sh'] as $n => $sub_sh){

							$shorthand = $this->update_shorthand_map($shorthand, $cssf, $prop_group,
								$prop, $propArr, $propArr['sh'][$n]);

							$shorthand_property = $propArr['sh'][$n][0];

							// also update $gathered_css while we're here
							if (!empty($propArr['sug_values'])){
								$gathered_css['eligable'][$shorthand_property] = 1;
							}

							if (isset($propArr['css_func'])){
								$css_func_map[$shorthand_property] = $prop_group;
							}

						}
					} else {
						// prop with just one shorthand available
						$shorthand = $this->update_shorthand_map($shorthand, $cssf, $prop_group,
							$prop, $propArr, $propArr['sh']);

						$shorthand_property = $propArr['sh'][0];

						// also update $gathered_css while we're here
						if (!empty($propArr['sug_values'])){
							$gathered_css['eligable'][$shorthand_property] = 1;
						}

						if (isset($propArr['css_func'])){
							$css_func_map[$shorthand_property] = $prop_group;
						}

						// update any shorthand prefixes
						if (!empty($propArr['sh'][2]['prefixes'])){
							$shorthand_prefixes[$shorthand_property]['prefixes'] = $propArr['sh'][2]['prefixes'];
						}

					}
				}

				// update longhand map (even with onlyShort)
				//if (empty($propArr['sh'][2]['onlyShort'])){
				$longhand[$cssf] = array(
					'group' => $prop_group,
					'prop' => $prop,
					//'multiple' => !empty($propArr['multiple']) ? 1 : 0,
				);

				// include any vendor prefixes for property
				!empty($propArr['prefixes'])
					? $longhand[$cssf]['prefixes'] = $propArr['prefixes']
					: false;

				// signal if prop can have multiple
				!empty($propArr['multiple']) ? $longhand[$cssf]['multiple'] = 1 : false;

				// signal MT factory default unit as unitless suggestions are based on that
				// the factory default can be used to convert suggested values based on the user's unit choice
				isset($propArr['default_unit'])
					? $longhand[$cssf]['fdu'] = $propArr['default_unit']
					: false;

				// signal if property has special units
				!empty($propArr['special_units'])
					? $longhand[$cssf]['special_units'] = $propArr['special_units']
					: false;

				// css function like rotateX or rotate3d for transform or filter
				if (isset($propArr['css_func'])){
					$longhand[$cssf]['css_func'] = $propArr['css_func'];
					$css_func_map[$prop] = $prop_group;
				}

				// and if MT supports multiple
				!empty($propArr['multiple_sup']) ? $longhand[$cssf]['multiple_sup'] = 1 : 0;

				// attach tab_control data to prop
				!empty($propArr['tab_control'])
					? $longhand[$cssf]['tab_control'] = $propArr['tab_control']
					: false;

				// and attach shorthand so we can check this when resampling page for suggested styles
				if (!empty($propArr['sh'])){
					$longhand[$cssf]['sh'] = $propArr['sh'];
					// also put shorthand prefixes in longhand
				}


				// get sub_slug for checking disabled via JS (among things perhaps)
				$longhand[$cssf]['sub_slug'] = $sub_slug;

				// get combobox type for edge_mode (temp)
				//!empty($propArr['type']) ? $longhand[$cssf]['type'] = $propArr['type'] : 0;

				// get sug_values config for forcing recent / suggestions etc
				!empty($propArr['sug_values']) ? $longhand[$cssf]['sug_values'] = $propArr['sug_values'] : 0;

				//}

				// update the $gathered_css map
				if (!empty($propArr['sug_values'])){

					// straight property match e.g. font-size
					if (!empty($propArr['sug_values']['this'])){
						$gathered_css['eligable'][$cssf] = 1;
					}

					// populate $gathered_css keys and storage arrays ready for getting vals with JS
					$gc_root_cat = !empty($propArr['sug_values']['root_cat'])
						? $propArr['sug_values']['root_cat']
						: $prop;

					$gathered_css['root_cat_keys'][$prop] = $gc_root_cat;
					$gathered_css['store'][$gc_root_cat] = array();

					// create store for e.g. grid line names too
					if (!empty($propArr['sug_values_extra'])){
						$gc_root_cat_extra = $propArr['sug_values_extra']['root_cat'];
						$gathered_css['root_cat_keys'][$prop.'_extra'] = $gc_root_cat_extra;
						$gathered_css['store'][$gc_root_cat_extra] = array();
					}

				}

				// populate combobox array
				if (!empty($array[$prop]['select_options'])){
					$combo[$prop] = $array[$prop]['select_options'];
				} if (!empty($array[$prop]['select_options_extra'])){
					$combo[$prop.'_extra'] = $array[$prop]['select_options_extra'];
				}

				// exceptions for more complicated select items with categories
				else {

					// event options
					if ($prop == 'font_family'){
						$combo[$prop] = $this->system_fonts;
					}

					// get preset animations from include file
					elseif ($prop == 'animation_name'){
						$animation_names = array();
						include $this->thisplugindir . 'includes/animation/animation-code.inc.php';
						$combo[$prop] = $animation_names;
					}

					// read stock background-image paths from json file created with svgstock gulp build
					elseif ($prop == 'background_image'){

						$background_image_json = file_get_contents(
							$this->thisplugindir . 'stock/json/'.$cssf.'.json'
						);

						if ($background_image_json){
							$combo[$prop] = json_decode($background_image_json, true);
						}

					}

					// read stock mask-image paths from json file created with svgstock gulp build
					elseif ($prop == 'mask_image'){

						$mask_image_json = file_get_contents(
							$this->thisplugindir . 'stock/json/'.$cssf.'.json'
						);
						$mask_images = $mask_image_json ? json_decode($mask_image_json, true) : false;

						// add the gradients too
						$angles = $exceptions['keys']['gradient-angle'];
						$linear_gradients = array();
						foreach ($angles as $deg => $label){
							$linear_gradients[] = array(
								'label' => '['.$label.']',
								'value' => 'linear-gradient('.$deg.', black 0%, rgba(0,0,0,0) 100%)'
							);
						}

						$css_gradients = $this->to_autocomplete_arr(array(
							__('Linear gradients', 'microthemer') => $linear_gradients,
							__('Radial & conic gradients', 'microthemer') => array(
								array(
									'label' => '[conic]',
									'value' => 'conic-gradient(from 0deg at 50% 50%, black 0% 25%, rgba(0,0,0,0) 50%, black 75% 100%)'
								),
								array(
									'label' => '[conic light ray]',
									'value' => 'conic-gradient(from 15deg at 0% 0%, black 0% 16%, rgba(0,0,0,0.2) 25%, black 75% 100%)'
								),

								array(
									'label' => '[conic checkerboard]',
									'value' => 'repeating-conic-gradient(rgba(0,0,0,0) 90deg, #000 0 180deg, rgba(0,0,0,0) 0 270deg, #000 0)'
								),
								array(
									'label' => '[radial fade out]',
									'value' => 'radial-gradient(at 50% 50%, black 0%, rgba(0,0,0,0) 100%)'
								),
								array(
									'label' => '[radial fade in]',
									'value' => 'radial-gradient(at 50% 50%, rgba(0,0,0,0) 0%, black 100%)'
								),
								array(
									'label' => '[radial rings]',
									'value' => 'repeating-radial-gradient(black, rgba(0,0,0,.7) 20%)'
								),

							)
						));


						if ($mask_image_json){
							$combo[$prop] = array_merge(
								$css_gradients,
								$mask_images
							);
						}

					}

					// get preset animations from include file
					elseif ($prop == 'clip_path'){
						$combo[$prop] = $this->to_autocomplete_arr(array(

							__('Angular sections', 'microthemer') => array(

								array(
									'label' => '[bottom center point]',
									'value' => 'polygon(0 0, 100% 0%, 100% 80%, 50% 100%, 0 80%)'
								),
								array(
									'label' => '[bottom ascend]',
									'value' => 'polygon(0 0, 100% 0, 100% 80%, 0% 100%)'
								),
								array(
									'label' => '[bottom descend]',
									'value' => 'polygon(0 0, 100% 0, 100% 100%, 0 80%)'
								),
								array(
									'label' => '[top ascend]',
									'value' => 'polygon(0 20%, 100% 0, 100% 100%, 0 100%)'
								),
								array(
									'label' => '[top descend]',
									'value' => 'polygon(0 0, 100% 20%, 100% 100%, 0 100%)'
								),
								array(
									'label' => '[top and bottom ascend]',
									'value' => 'polygon(0 20%, 100% 0, 100% 80%, 0 100%)'
								),
								array(
									'label' => '[top and bottom descend]',
									'value' => 'polygon(0 0, 100% 20%, 100% 100%, 0 79%)'
								),
								array(
									'label' => '[top ascend, bottom descend]',
									'value' => 'polygon(0 20%, 100% 0, 100% 100%, 0 79%)'
								),
								array(
									'label' => '[top descend, bottom ascend]',
									'value' => 'polygon(0 0, 100% 20%, 100% 80%, 0 100%)'
								),

							),



							__('Clippy shapes', 'microthemer') => array(

								array(
									'label' => '[arrow left]',
									'value' => 'polygon(40% 0%, 40% 20%, 100% 20%, 100% 80%, 40% 80%, 40% 100%, 0% 50%)'
								),
								array(
									'label' => '[arrow right ]',
									'value' => 'polygon(0% 20%, 60% 20%, 60% 0%, 100% 50%, 60% 100%, 60% 80%, 0% 80%)'
								),

								array(
									'label' => '[bevel]',
									'value' => 'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)'
								),

								array(
									'label' => '[chevron left ]',
									'value' => 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)'
								),
								array(
									'label' => '[chevron right]',
									'value' => 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)'
								),

								array(
									'label' => '[circle]',
									'value' => 'circle(50% at 50% 50%)'
								),

								array(
									'label' => '[close]',
									'value' => 'polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%)'
								),

								array(
									'label' => '[cross]',
									'value' => 'polygon(10% 25%, 35% 25%, 35% 0%, 65% 0%, 65% 25%, 90% 25%, 90% 50%, 65% 50%, 65% 100%, 35% 100%, 35% 50%, 10% 50%)'
								),

								array(
									'label' => '[custom]',
									'value' => 'polygon(18% 10%, 93% 37%, 77% 90%, 16% 89%, 83% 17%, 20% 42%)'
								),

								array(
									'label' => '[decagon]',
									'value' => 'polygon(50% 0%, 80% 10%, 100% 35%, 100% 70%, 80% 90%, 50% 100%, 20% 90%, 0% 70%, 0% 35%, 20% 10%)'
								),

								array(
									'label' => '[ellipse]',
									'value' => 'ellipse(50% 33% at 50% 50%)'
								),

								array(
									'label' => '[frame]',
									'value' => 'polygon(0% 0%, 0% 100%, 25% 100%, 25% 25%, 75% 25%, 75% 75%, 25% 75%, 25% 100%, 100% 100%, 100% 0%)'
								),

								array(
									'label' => '[heptagon]',
									'value' => 'polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%)'
								),

								array(
									'label' => '[hexagon]',
									'value' => 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)'
								),

								array(
									'label' => '[message]',
									'value' => 'polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 75% 100%, 50% 75%, 0% 75%)'
								),

								array(
									'label' => '[nonagon]',
									'value' => 'polygon(50% 0%, 83% 12%, 100% 43%, 94% 78%, 68% 100%, 32% 100%, 6% 78%, 0% 43%, 17% 12%)'
								),

								array(
									'label' => '[octagon]',
									'value' => 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)'
								),

								array(
									'label' => '[parallelogram]',
									'value' => 'polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%)'
								),

								array(
									'label' => '[pentagon]',
									'value' => 'polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)'
								),

								array(
									'label' => '[point left]',
									'value' => 'polygon(25% 0%, 100% 0%, 100% 100%, 25% 100%, 0% 50%)'
								),
								array(
									'label' => '[point right]',
									'value' => 'polygon(0% 0%, 75% 0%, 100% 50%, 75% 100%, 0% 100%)'
								),

								array(
									'label' => '[rabbet]',
									'value' => 'polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%)'
								),

								array(
									'label' => '[rectangle]',
									'value' => 'inset(0 0 0 0)'
								),

								array(
									'label' => '[rhombus]',
									'value' => 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)'
								),

								array(
									'label' => '[trapezoid]',
									'value' => 'polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%)'
								),

								array(
									'label' => '[triangle]',
									'value' => 'polygon(50% 0%, 0% 100%, 100% 100%)'
								),
								array(
									'label' => '[star]',
									'value' => 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)'
								),
								array(
									'label' => '[wide star]',
									'value' => 'polygon(50% 0%, 70% 30%, 98% 35%, 80% 57%, 79% 91%, 50% 80%, 19% 92%, 20% 59%, 2% 35%, 30% 30%)'
								),


							),

							__('Valid syntax examples', 'microthemer') => array(
								'margin-box',
								'border-box',
								'padding-box',
								'content-box',
								'none',
								'inset(100px 50px)',
								'circle(50px at 0 100px)',
								'ellipse(50px 60px at 0 10% 20%)',
								'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
								// heart
								'path("M15,45 A30,30,0,0,1,75,45 A30,30,0,0,1,135,45 Q135,90,75,130 Q15,90,15,45 Z")',
								'url("#inlineSvgClipPathIDHere")',
							),

						));
					}

					// event options
					elseif ($prop == 'event'){
						$combo[$prop] = $this->browser_events;
					}

					// animatable properties
					elseif ($prop == 'transition_property'){
						$combo[$prop] = $this->animatable;
					}

				}


			}
		}

		//$this->show_me.= print_r($combo, true);

		$this->shorthand = $shorthand;
		$this->longhand = $longhand;

		// text/box-shadow need to be called as one
		$css_props['shadow'][] = array(
			'prop' => 'text_shadow',
			'cssf' => 'text-shadow'
		);
		$css_props['shadow'][] = array(
			'prop' => 'box_shadow',
			'cssf' => 'box-shadow'
		);
		$css_props['background'][] = array(
			'prop' => 'background_img_full',
			'cssf' => 'background-img-full'
		);
		// for storing full string (inc gradient)
		$css_props['background'][] = array(
			'prop' => 'extracted_gradient',
			'cssf' => 'extracted-gradient'
		);
		// for storing just gradient (for mixed-comp check)
		$css_props['gradient'][] = array(
			'prop' => 'background_image',
			'cssf' => 'background-image'
		);
		// gradient group needs this
		$css_props['gradient'][] = array(
			'prop' => 'background_img_full',
			'cssf' => 'background-img-full'
		);
		// for storing full string (inc gradient)
		$css_props['gradient'][] = array(
			'prop' => 'extracted_gradient',
			'cssf' => 'extracted-gradient'
		);

		// dev option for showing function times
		$combo['show_total_times'] = array('avg_time', 'total_time', 'calls');

		// set options for :lang(language) pseudo class
		$combo['lang_codes'] = $this->country_codes;

		// suggest some handy nth formulas
		$combo['nth_formulas'] = $this->nth_formulas;

		// ready combo for css_units
		$length_units = array();
		$unit_types = $this->lang['css_unit_types'];
		$length_units[$unit_types['none']] = $this->css_units[$unit_types['none']];
		$length_units[$unit_types['common']] = $this->css_units[$unit_types['common']];
		$length_units[$unit_types['other']] = $this->css_units[$unit_types['other']];
		$combo['css_length_units'] = $this->to_autocomplete_arr($length_units);

		// empty array for all sel suggestions
		$combo['all_sel_suggestions'] = array();

		// num history saves
		$combo['num_history_points'] = array(
			'50', '75', '100', '150', '200', '300'
		);

		// example below fold thresholds
		$combo['fold_threshold'] = array(
			'600', '900', '1200', '1440', '2160',
		);

		$combo['builder_sync_tabs'] = array_merge(
			array(
				'360', '568', '800'
			),
			$this->builder_sync_tabs
		);

		// options for choosing the  stylesheet loading order
		$combo['stylesheet_order_options'] = $this->stylesheet_order_options;

		// customisable page prefix
		$combo['page_class_prefix_options'] = $this->page_class_prefix_options;

		// example scripts for enqueuing
		$combo['enq_js'] = array( 'jquery', 'jquery-form', 'jquery-color', 'jquery-masonry', 'masonry', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-accordion', 'jquery-ui-autocomplete', 'jquery-ui-button', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-menu', 'jquery-ui-mouse', 'jquery-ui-position', 'jquery-ui-progressbar', 'jquery-ui-selectable', 'jquery-ui-resizable', 'jquery-ui-selectmenu', 'jquery-ui-sortable', 'jquery-ui-slider', 'jquery-ui-spinner', 'jquery-ui-tooltip', 'jquery-ui-tabs', 'jquery-effects-core', 'jquery-effects-blind', 'jquery-effects-bounce', 'jquery-effects-clip', 'jquery-effects-drop', 'jquery-effects-explode', 'jquery-effects-fade', 'jquery-effects-fold', 'jquery-effects-highlight', 'jquery-effects-pulsate', 'jquery-effects-scale', 'jquery-effects-shake', 'jquery-effects-slide', 'jquery-effects-transfer', 'wp-mediaelement', 'schedule', 'suggest', 'thickbox', 'hoverIntent', 'jquery-hotkeys', 'sack', 'quicktags', 'iris', 'json2', 'plupload', 'plupload-all', 'plupload-html4', 'plupload-html5', 'plupload-flash', 'plupload-silverlight', 'underscore', 'backbone' );
		sort($combo['enq_js']);


		// the full program data in one array
		$prog = array(
			'CSSUnits' =>  $this->css_units,
			'browser_event_keys' =>  $this->browser_event_keys,
			'selector_variations' => $this->selector_variations,
			'propExc' =>  $exceptions,
			'propAliases' =>  $this->propAliases,
			'cssFuncAliases' =>  $this->cssFuncAliases,
			'subGroupKeys' =>  $sub_group_keys,
			'subToGroup' =>  $sub_group_to_group,
			'sh' =>  $shorthand,
			'sh_prefixes' =>  $shorthand_prefixes,
			'lh' =>  $longhand,
			'gatheredCSS' =>  $gathered_css,
			'CSSProps' =>  $css_props,
			'PGs' =>  $this->property_option_groups,
			'autoMap' =>  $this->auto_convert_map,
			'combo' =>  $combo,
			'mobPreview' =>  $this->mob_preview,
			'CSSFilters' =>  $this->css_filters,
			'custom_code_flat' =>  $this->custom_code_flat,
			'pg_tab_props' =>  $pg_tab_props,
			'pg_tab_map' =>  $pg_tab_map,
			'css_func_map' =>  $css_func_map,
			'unsupported_css_func_map' =>  $unsupported_css_func_map,
			'params_to_strip' =>  $this->params_to_strip,
			'suggested_screen_layouts' => $this->suggested_screen_layouts,
            'exportable_preferences' => array_merge(
	            array_keys($this->default_preferences),
	            array_keys($this->default_preferences_exportable),
                // and some dynamically set preferences that should also copy over
                array(
                    "stylesheet_order",
                    "page_class_prefix",
                    "auto_folders",
                    "auto_folders_page"
                )
            )
		);

		$data.= 'TvrMT.data.prog = ' . json_encode($prog) . ';' . "\n\n";
		$data.= 'TvrMT.data.templates = ' . json_encode($this->html_templates()) . ';' . "\n\n";

		// if the server can't overwrite an external JS file, add it inline with other dynamic JS
		if ($inline){
			return $data;
		}

		else {
			fwrite($write_file, $data);
			fclose($write_file);
		}

		return 1;

	}

	// HTML TEMPLATES
	function html_templates(){

		// full set of templates
		$templates = array(

			'notice' =>
				'<div id="notice-template" class="tvr-message tvr-template-notice tvr-warning test-class">
                            <span class="mt-notice-icon"></span>
                            <span class="mt-notice-text"></span>
                         </div>',

			'loaders' => $this->loaders_template(),

			'log_item' => $this->log_item_template(),

			'enq_js_menu_item' => $this->dyn_item($this->enq_js_structure, 'item', array('display_name' => 'item')),

			'mqs_menu_item' => $this->dyn_item($this->mq_structure, 'item', array('label' => 'item')),

			'sectionM' => $this->menu_section_html('selector_section', 'section_label'),

			'section' => $this->section_html('selector_section', array()),

			'selectorM' => $this->menu_selector_html('selector_section', 'selector_css', array('selector_code', 'selector_label'), 1),

			'selector' => $this->single_selector_html('selector_section', 'selector_css', '', true),

			'property_groups' => $this->property_group_templates(),

			'icon_inputs' => $this->icon_inputs(),

			'history_shortcut' => $this->iconFont('undo', array(
				'title' => esc_attr__('View history', 'microthemer'),
				'class' => 'short-status-icon display-revisions show-dialog',
				'rel' => 'display-revisions'
			)),

			'add_selector_form' => '',

			'edit_selector_form' => '',

			'input_wraps' => $this->input_wrap_templates, // var

			'frontend_markup' => $this->frontend_markup_template(),

			'frontend_overlay_template' => $this->frontend_overlay_template(),

			'icon_classes' => $this->iconFont('iconName', array('onlyClass' => 1)),

			'new_sel_radio' => $this->targeting_radio(),

			'existing_sel_radio' => $this->targeting_radio(true),

			'variation_radio' => $this->variation_radio(),

			'key_computed_info' => $this->key_computed_info(true),

			'svg_chev_right' => $this->svg_chev_right(),

			'toggle_switch' => $this->toggle('toggle_aspect', array(
				'toggle' => 0,
				'toggle_id' => 'toggle_id',
				'data-pos' => esc_attr__('enable_toggle_tooltip', 'microthemer'),
				'data-neg' => esc_attr__('disable_toggle_tooltip', 'microthemer'),
			))

			//'primary_context_menu_trigger' => $this->element_meta_template('primary'),

			//'oncanvas_feedback_template' =>$this->oncanvas_feedback_template()


		);

		return $templates; //$this->strip_tabs_and_line_breaks($templates); // not working but could be fixed maybe?

	}

	function targeting_radio($existing = false){

		$title = esc_html__('Choose suggestion', 'microthemer');
		$extra_class = '';

		if ($existing){
			$title = esc_html__('Choose selector', 'microthemer');
			$extra_class = ' existsel-radio';
		}

		return '
                 <span class="sugsel-radio targeting-sel-radio'. $extra_class . ' '.
		       $this->iconFont('radio-btn-unchecked', array('onlyClass' => 1)).'" 
                        title="'.$title.'">
                 </span>';

	}


	function variation_radio(){

		//  <input name="sel_variation" type="radio" value="1" />

		return '
				
                 <span class="alt-radio sel-variation-radio mt-variation-option '.
		       $this->iconFont('radio-btn-unchecked', array('onlyClass' => 1)).'" 
                        title="'.esc_html__('Choose variation', 'microthemer').'">
                 </span>';
	}

	function svg_chev_right(){
		return '<svg data-icon="BreadcrumbPartRight" aria-hidden="true" focusable="false" width="7" height="28" viewBox="0 0 7 28" class="bem-Svg left notch" style="display: block; color: rgb(235, 235, 235);"><path fill="currentColor" d="M6.5 14L.5 0H0v28h.5z"></path><path fill="#858585" d="M1 0H0l6 14-6 14h1l6-14z"></path></svg>';
	}

	function key_computed_info(){

		// define layers from outside going in
		$layers = array('margin', 'border-width', 'padding', 'content');

		// define side in order or grid-auto flow to make CSS simple
		$sides = array('top', 'left', 'right', 'bottom');

		// return the template which gets updated by JS in one DOM write
		return
			'<div id="box-model-layers">
                    '.$this->box_model_layers(0, 'margin', $layers, $sides).'
                </div>'
			.
			$this->key_computed_other();

	}

	function key_computed_other(){

		$info = array(
			'display' => array(
				'label' => 'display',
				'value' => '{display}'
			),
			'position' => array(
				'label' => 'position',
				'value' => '{position}'
			),
			'color' => array(
				'label' => 'color',
				'value' => '{color}'
			),
			'background-color' => array(
				'label' => 'background-color',
				'value' => '{background-color}'
			),
			'font-size' => array(
				'label' => 'font-size',
				'value' => '{font-size}'
			),
			'line-height' => array(
				'label' => 'line-height',
				'value' => '{line-height}'
			),
			'font-family' => array(
				'label' =>'font-family',
				'value' => '{font-family}'
			),
			// position
		);

		$html = '
	            <div id="key-element-info">
	                <div class="key-info-heading mt-expandable-heading expanded">'.esc_html__('Key information', 'microthemer').'</div>
                    <div class="key-element-info-inner mt-expandable-panel">';

		foreach ($info as $key => $array){
			$html.=
				'<span class="info-label '.$key.'-info-label">'.$array['label'].': </span>
                         <span class="info-value '.$key.'-info-value">'.$array['value'].'</span>';
		}

		$html.= '
                    </div>
                </div>';

		return $html;
	}

	function box_model_layers($i, $layer, $layers, $sides){

		$item = '<div class="mt-bm-layer mt-'.$layer.'-layer">';

		// inside content
		if ($layer === 'content'){
			$item.= '{width} x {height}';
		}

		// outer layer
		else {

			// color mask
			$item.= '<div class="tvr-mask"></div>';

			// label
			$layer_label = $layer === 'border-width' ? 'border' : $layer;
			$item.= '<div class="layer-label">'.$layer_label.'</div>';

			foreach($sides as $side) {

				$cssf = $layer === 'border-width'
					? 'border-' . $side . '-width'
					: $layer . '-' . $side;

				$item .= '<div class="mt-bm-cell mt-bm-' . $side . '-cell">{' . $cssf . '}</div>';


				// recursively call layer function if we have a next layer inside
				if ($side === 'left') {

					$i++;
					$next_layer = !empty($layers[$i]) ? $layers[$i] : false;

					if ($next_layer){
						$item.= $this->box_model_layers($i, $next_layer, $layers, $sides);
					}

				}

			}
		}

		$item.= '</div>';

		return $item;
	}

	function strip_tabs_and_line_breaks($string){
		// this does not work - not sure why.
		//preg_replace('/[\\t\\r\\n\n\t\r]+/g', "", $string);
		return $string;
	}

	function icon_inputs(){

		return array(
			'section' => array(
				'disabled'  => $this->icon_control(true, 'disabled', true, 'section', 'selector_section')
			),
			'selector' => array(
				'disabled'  => $this->icon_control(true, 'disabled', true, 'selector', 'selector_section',
					'selector_css')
			),
			'tab-input' => array(
				'tab_disabled'  => $this->icon_control(true, 'disabled', true, 'tab-input', 'selector_section',
					'selector_css', 'all-devices')
			),
			'group' => array(
				'pgtab_disabled'  => $this->icon_control(true, 'disabled', true,
					'group', 'selector_section', 'selector_css',
					'all-devices', 'group_slug', 'subgroup_slug', 'property_slug',
					'tvr_mcth', 'pgtab_slug'),

				'disabled'  => $this->icon_control(true, 'disabled', true, 'group', 'selector_section',
					'selector_css', 'all-devices', 'group_slug', 'subgroup_slug'),

				'flexitem'  => $this->icon_control(true, 'flexitem', true, 'group',
					'selector_section','selector_css', 'all-devices', 'group_slug'),

				'griditem'  => $this->icon_control(true, 'griditem', true, 'group',
					'selector_section','selector_css', 'all-devices', 'group_slug'),

				'nth_option' => '<li class="nth-item-option nth-item-option-0" data-nth="0">
                            <input class="nth-item-radio" type="radio" name="name_placeholder" value="0" />
                            '.$this->iconFont('radio-btn-unchecked', array('class' => 'fake-radio nth-radio-control')).'
                            <span class="nth-item-label">0</span>
                        </li>'
			),
			'subgroup' => array(
				'chained'  => $this->icon_control(true, 'chained', true, 'subgroup', 'selector_section',
					'selector_css', 'all-devices', 'group_slug', 'subgroup_slug')
			),
			'property' => array(
				'important'  => $this->icon_control(true, 'important', true, 'group', 'selector_section',
					'selector_css', 'all-devices', 'group_slug', 'subgroup_slug', 'property_slug'),
				'css_unit' => $this->icon_control(true, 'css_unit', true, 'property', 'selector_section',
					'selector_css', 'all-devices', 'group_slug', 'subgroup_slug', 'property_slug')
			)

		);


	}

	function loaders_template(){

		// loading gif common atts
		$loader_com = 'class="ajax-loader small" src="'.$this->thispluginurl.'/images/';

		return array(
			'default' => '<img id="loading-gif-template" '.$loader_com.'ajax-loader-green.gif" />',
			'wbg' => '<img id="loading-gif-template-wbg" '.$loader_com.'ajax-loader-wbg.gif" />',
			'mgbg' => '<img id="loading-gif-template-mgbg" '.$loader_com.'ajax-loader-mgbg.gif" />',
			'sec' => '<img id="loading-gif-template-sec" '.$loader_com.'sec-ajax-loader-green.gif" />',
			'sel' => '<img id="loading-gif-template-sel" '.$loader_com.'sel-ajax-loader-green.gif" />',
		);

	}

	function log_item_template(){

		// template for displaying save error and error report option
		$short = __('Error saving settings', 'microthemer');
		$long =
			'<p>' . sprintf(
				esc_html__('Please %s. The error report sends us information about your current Microthemer settings, server and browser information, and your WP admin email address. We use this information purely for replicating your issue and then contacting you with a solution.', 'microthemer'),
				'<span id="email-error" class="link">' . __('click this link to email an error report to Themeover', 'microthemer') . '</span>'
			) . '</p>
				<p>' . wp_kses(
				__('<b>Note:</b> reloading the page is normally a quick fix for now. However, unsaved changes will need to be redone.', 'microthemer'),
				array( 'b' => array() )
			). '</p>';


		return $this->display_log_item('error',
			array(
				'short'=> $short,
				'long'=> $long
			),
			0,
			'id="log-item-template"'
		);
	}

	function property_group_templates(){

		$pg_templates = array();

		// add property group templates
		foreach ($this->propertyoptions as $property_group_name => $property_group_array) {

			// we want root keys only for $property_group_array, to match propertyOptions format
			$array_keys = array_keys($property_group_array);
			$property_group_array_root = array();
			foreach($array_keys as $prop_slug){
				$property_group_array_root[$prop_slug] = '';
			}

			$pg_templates[$property_group_name] = $this->single_option_fields(
				'selector_section',
				'selector_css',
				array(),
				$property_group_array_root,
				$property_group_name,
				'',
				true
			);
		}

		return $pg_templates;
	}

	// frontend templates

	function frontend_markup_template(){

		// highlighting elements live on the frontend because we want them to be constrained by the frontend HTML boundary
		// but context menus live in the main UI window so avoid CSS conflicts and allow for overlap with the toolbars

		return '
				<div id="mt-stuff-ignore">
                   
                    <div id="mt-grid-highlight">
                        '.$this->oncanvas_grid().'
                    </div>
              
                    <div id="over-cont-hover">'.$this->element_meta_template('hover').'</div>
                        
                    <div id="mt-el-overlays"></div>'.

		       $this->frontend_overlay_template('primary').'
				       
				    <div id="mt-pos-layer"></div>  
				    <div id="mt-placeholder-layer"></div> 
                    
                </div>';
	}

	// the breadcrumb label and options for triggering the context menu are in thr iframe dom
	// so they move when the user scrolls. The actual context menu displays in the parent frame.
	function element_meta_template($forElementSelection){

		$suggestionsTrigger = $this->ui_toggle(
			'show_suggestions_menu',
			esc_attr__('Show targeting options', 'microthemer'),
			esc_attr__('Close targeting options', 'microthemer'),
			false,
			'show-suggestions-icon mt-ui-element mtif',
			false,
			array(
				'dataAtts' => array(
					'fhtml' => 1,
					'no-save' => 1,
					'forpopup' => 'contextMenu',
				),
				'inlineSVG' => 1
			)
		);

		//$suggestionsTrigger = $this->icon('');

		$onCanvasTrigger = $this->ui_toggle(
			'show_visual_controls_menu',
			esc_attr__('Enable visual controls', 'microthemer'),
			esc_attr__('Disable visual controls', 'microthemer'),
			true, // visual controls are on by default - but can be customised
			'show-visual-controls-icon mt-ui-element mtif',
			false,
			array(
				'dataAtts' => array(
					'fhtml' => 1,
					'no-save' => 1
				)
			)
		);

		$breadcrumbLabel = '';

		if ($forElementSelection === 'hover'){
			$breadcrumbLabel = '<span class="mt-breadcrumb-label mt-ui-element"></span>';
		}

		else if ($forElementSelection === 'primary'){
			$breadcrumbLabel = '<div id="mt-nested-els" class="mt-breadcrumb-nav mt-ui-element"></div>';
		}


		return '
				<div id="'.$forElementSelection.'-element-meta" class="mt-element-metas mt-ui-element">
			        '. $suggestionsTrigger . $onCanvasTrigger . $breadcrumbLabel .'
			    </div>';
	}

	function oncanvas_grid(){

		$gridlines = '';

		for ($x = 0; $x <= 24; $x++) {
			$gridlines.= '<div class="mtoc-line"></div>';
		}

		return '<div class="mt-oncanvas-grid">'.$gridlines.'</div>';
	}

	function oncanvas_feedback_template(){

		return '
				<div id="tvr-oncanvas-feedback">
                    <div class="tvr-adjusted-property">
                        <span class="tvr-adjusted-property-label"></span>
                        <span class="tvr-adjusted-property-value"></span>
                    </div>
                </div>';
	}

	function frontend_overlay_template($forElementSelection = false){

		// for normal overlays
		$id = 'over-cont-%s';
		$class = 'tvr-overlay tvr-container-overlay';
		$style = 'style="%s"';
		$elementMeta = '';
		$onCanvasControls = '';

		// inner padding/margin/border divs
		$inner = $this->frontend_overlay_template_inner($forElementSelection, $style);

		// for the fixed trigger el overlay with context menu and on-canvas controls
		if ($forElementSelection === 'primary'){
			$id = 'over-cont-'.$forElementSelection;
			$class = '';
			$elementMeta = $this->element_meta_template($forElementSelection);
			$inner = '<div id="mt-bm-shading">'.$inner.'</div>';

			// todo add controls for size and spacing, transform, position (grid adapt canvas, not tied to selected element),
			$onCanvasControls = $this->oncanvas_feedback_template($forElementSelection);
		}



		return
			'<div id="'.$id.'" class="'.$class.'">'.
			$inner . $onCanvasControls . $elementMeta
			.'</div>';
	}

	function frontend_overlay_template_inner($forElementSelection, $style){
		return '
			    <div class="tvr-overlay tvr-margin-overlay" '.$style.'>
                    <div class="tvr-mask"></div>
                </div>
                <div class="tvr-overlay tvr-border-overlay" '.$style.'>
                    <div class="tvr-mask"></div>
                </div>
                <div class="tvr-overlay tvr-padding-overlay" '.$style.'>
                    <div class="tvr-mask"></div>
                </div>
                <div class="tvr-overlay tvr-content-overlay" '.$style.'>
                    <div class="tvr-mask"></div>
                </div>';
	}

	// display the css filters
	function display_css_filters(){
		/*$html = '
				<div class="quick-opts first-quick-opts">
					<div class="quick-opts-inner">';*/

		$html = '';

		$i = 0;
		foreach ($this->css_filters as $key => $arr){
			if ($i === 0){
				$extra = $this->css_filter_list(
					$this->css_filters['pseudo_elements']['items'],
					'pseudo_elements',
					$this->css_filters['pseudo_elements']['label']
				);
			} elseif ($i === 1){
				++$i;
				continue;
			} else {
				$extra = '';
			}
			$html.= '
							<div class="mt-col mt-col'.(++$i).'">'
			        . $this->css_filter_list(
					$arr['items'],
					$key,
					$arr['label']
				). $extra . '
							</div>';
		}


		$html.= $this->iconFont('eraser', array(
			'class' => 'clear-css-filters',
			'innerHTML' => esc_html__('Clear all', 'microthemer'),
			'title' => esc_attr__('Clear all modifiers', 'microthemer'),
		));


		/*$clear_text = esc_html__('Clear all', 'microthemer');

				$html.= '
						<span class="clear-filters-wrap"
							  title="'.esc_html__('Clear all selector adjustments', 'microthemer').'">
							<span class="clear-icon clear-css-filters clear-css-filters-icon"></span>
							<span class="clear-css-filters clear-css-filters-text">'.$clear_text.'</span>
						</span>

					';*/

		return $html;

	}

	// display the targeting suggestions menu
	function targeting_suggestions($context){

		ob_start();
		?>

		<div id="mt-suggestions-<?php echo $context; ?>" class="scrollable-area mt-suggestions radio-set-container">

			<div class="mt-panel-header existing-selector-heading">
				<div class="mt-panel-title"><?php echo esc_html__('Resume an existing selector', 'microthemer'); ?></div>
				<span class="mtif mtif-times-circle-regular close-context-menu"></span>
			</div>

			<div class="mt-existing-selectors"></div>


			<div class="mt-panel-header create-new-selector-heading">
				<div class="mt-panel-title">

                            <span class="new-selector-text">
                                 <?php echo esc_html__('Create a new selector', 'microthemer'); ?>
                            </span>

					<span class="edit-selector-targeting-text">
                                <?php echo esc_html__('Edit selector targeting', 'microthemer'); ?>
                            </span>

				</div>
				<span class="mtif mtif-times-circle-regular close-context-menu"></span>
			</div>

			<div class="mt-panel-header additional-selector-heading">
				<div class="mt-ui-toggle uit-par mt-panel-title additional-selector-toggle"
				     data-aspect="create_additional_selector" data-no-save="1">
					<?php echo esc_html__('Create an additional selector', 'microthemer'); ?>
				</div>
			</div>

			<div class="new-selector-suggestions">

				<div class="mt-selector-adjustments">

					<ul class="fav-filters css-filter-list">
						<?php echo $this->fav_css_filters; ?>
					</ul>

					<?php
					$specificity_high = !empty($this->preferences['specificity_preference']) ? ' on' : '';
					?>
					<div class="mt-ui-toggle uit-par specificity-preference<?php echo $specificity_high; ?>" data-aspect="specificity_preference">
                                    <span class="specificity-label"
                                          title="<?php esc_attr_e('Sort by CSS specificity', 'microthemer'); ?>">
                                        <?php esc_html_e('Specificity', 'microthemer'); ?>:
                                    </span>
						<span class="specificity-low mt-ui-toggle"
						      title="<?php esc_attr_e('low CSS specificity (favor classes)', 'microthemer'); ?>">
                                        <?php esc_html_e('low', 'microthemer'); ?>
                                    </span>
						<span class="specificity-high mt-ui-toggle"
						      title="<?php esc_attr_e('high CSS specificity (favor ids)', 'microthemer'); ?>">
                                        <?php esc_html_e('high', 'microthemer'); ?>
                                    </span>
					</div>
				</div>

				<div class="code-suggestions mt-selector-suggestions"></div>

				<div class="code-suggestions-cta">

					<span><?php esc_html_e('Apply a new style change or', 'microthemer'); ?></span>
					<span class="tvr-button save-draft-selector"><?php esc_html_e('Save selector', 'microthemer'); ?></span>
					<div class="wizard-update-cur tvr-button">
						<?php esc_html_e('Update selector', 'microthemer'); ?>
					</div>
				</div>

			</div>

		</div>

		<?php

		return ob_get_clean();

	}

	// output a list of css filters (pseudo classes, elements, page-specific)
	function css_filter_list($filters, $type, $heading) {
		$html = '
				<div class="filter-heading">'.$heading.'</div>';
		$num_items = count($filters);
		$index = 0;

		// there are lots of pseudo, split into 3 columns
		if (($num_items >= 14)){
			$break = $num_items/2.5;
			$j = -1;
			foreach($filters as $k => $v){
				if (++$j > $break){
					++$index;
					$j = 0;
				}
				$split_filters[$index][$k] = $v;
			}
		} else {
			$split_filters[0] = $filters;
		}

		// loop through normalised $filters
		foreach ($split_filters as $i => $f){
			$html.= '
					<ul class="css-filter-list flist-'.$type.' cssfl-index-'.$i.'">';
			foreach($f as $key => $arr){
				$text = !empty($arr['text']) ? $arr['text'] : $key;
				$edClass = !empty($arr['editable']) ? ' filter-editable' : '';
				$li =
					$this->ui_toggle(
						$type,
						$arr['tip'],
						$arr['tip'],
						// left over enabled
						!empty($this->preferences[$type][$key]),
						'css-filter-item filter-'.$this->pseudo_class_format($text) . $edClass,
						false,
						array(
							'tag' => 'li',
							'dataAtts' => array(
								'filter' => $key,
								'type' => $type,
								'no-save' => $type === 'page_specific' ? 0 : 1
							),
							'text' => $text,
							'inner_icon' => $this->iconFont('tick-box-unchecked', array(
								'class' => 'alt-checkbox mt-ui-toggle'
							)),
							'pref_sub_key' => $text,
							'css_filter' => $arr
						)
					);
				$html.= $li;
				// save for favs list if required
				if (!empty($arr['common'])){
					// the title is a bit annoying on favourites.
					$this->fav_css_filters.= preg_replace('/title=\"([^"]*)\"/i', '', $li, 1);
				}
			}

			// provide an option to remember the choice
			//$html.= '<li class="filter-choice">'.esc_html__('More', 'microthemer').'</li>';

			$html.= '</ul>';
		}

		return $html;
	}

	// @return array - Retrieve the plugin options from the database.
	function getOptions() {
		// default options (html layout sections only - no default selectors)
		if (!$theOptions = get_option($this->optionsName)) {
			$theOptions = $this->default_folders;
			$theOptions['non_section']['hand_coded_css'] = '';
			// add_option rather than update_option (so autoload can be set to no)
			add_option($this->optionsName, $theOptions, '', 'no');
		}
		$this->options = $theOptions;
	}

	function pseudo_class_format($pseudo){
		return str_replace(array( ':', '(', ')' ), '', $pseudo);
	}

    function copyToAutoloadValues($fullPreferences){

        $autoloadPreferences = array();

        foreach($this->autoloadPreferencesList as $key){

            // Some dynamic default prefs might not be set when this first runs
            if (isset($fullPreferences[$key])){
	            $autoloadPreferences[$key] = $fullPreferences[$key];
            }
        }

        return $autoloadPreferences;
    }

	// @return array - Retrieve the plugin preferences from the database.
	function getPreferences($special_checks = false, $pd_context = false) {

		$full_preferences = array_merge(
            $this->default_preferences,
            $this->default_preferences_dont_reset_or_export,
            $this->default_preferences_exportable,
            $this->default_preferences_resetable
        );

		// default preferences
		if (!$thePreferences = get_option($this->preferencesName)) {

			$thePreferences = $full_preferences;

			// add_option rather than update_option (so autoload can be set to no)
			add_option($this->preferencesName, $thePreferences, '', 'no');
		}

		// hard set this as version 7 is always in draft mode with manual publish
		// more formal conversion in DB pending
		$thePreferences['draft_mode'] = 1;

		// hard set this off as we're trialing not supporting this
		$thePreferences['dock_wizard_right'] = 0;

		$this->preferences = $thePreferences;

		// checks we only need to do once when this function is first called
		if ($special_checks){

			/*wp_die('the special '. !empty($this->preferences['version']) . ' '.$this->preferences['version']. ' '.$this->version. ' '.($this->preferences['version'] != $this->version));*/

			// check if this is a new version of Microthemer
			if ($this->new_version){ // empty($this->preferences['version']) || $this->preferences['version'] != $this->version){

				//$this->new_version = true;

				// maybe update revisions table
				$this->maybeCreateOrUpdateRevsTable();

				// signal that all selectors should be recompiled (to ensure latest data structure)
				$this->update_preference('manual_recompile_all_css', 1);
			}

			// ensure preferences are defined (for when I add new preferences that upgrading users won't have)
			$this->ensure_defined_preferences($full_preferences, $pd_context);

			// manually override user preferences after code changes
			$this->maybe_manually_override_preferences();
		}

		// Setup autoload preferences if necessary too
		if (!$autoloadPreferences = get_option($this->autoloadPreferencesName)) {
            add_option($this->autoloadPreferencesName, $this->copyToAutoloadValues($thePreferences)); // autoload = yes
		}

	}

	// Save the preferences


	function preferences_grid_items($opts){

        $html = '';
		// labels
		$yes_label = __('Yes', 'microthemer' );
		$no_label = __('No', 'microthemer' );

		foreach ($opts as $key => $array) {

			// skip edge mode if not available
			if ($key == 'edge_mode' and !$this->edge_mode['available']){
				continue;
			}

			// common
			$input_name = 'tvr_preferences['.$key.']';
			$array['link'] = ( !empty($array['link']) ) ? $array['link'] : '';

			// if radio
			if (empty($array['is_text'])){

				// ensure various vars are defined
				$li_class = 'fake-radio-parent';
				$array['label_no'] = ( !empty($array['label_no']) ) ? $array['label_no'] : '';
				$yes_val = ($key == 'draft_mode') ? $this->current_user_id : 1;
				$no_val = 0;

				if (!empty($this->preferences[$key])) {
					$yes_checked = 'checked="checked"';
					$yes_on = 'on';
					$no_checked = $no_on = '';
				} else {
					$no_checked = 'checked="checked"';
					$no_on = 'on';
					$yes_checked = $yes_on = '';
				}

				$form_options = '
                <span class="yes-wrap p-option-wrap">
                    <input type="radio" autocomplete="off" class="radio"
                       name="'.$input_name.'" value="'.$yes_val.'" '.$yes_checked.' />
                       '.$this->iconFont('radio-btn-unchecked', array('class' => 'fake-radio '.$yes_on)).'
                 
                    <span class="ef-label">'.$yes_label.'</span>
                </span>
                <span class="no-wrap p-option-wrap">
                    <input type="radio" autocomplete="off" class="radio"
                       name="'.$input_name.'" value="'.$no_val.'" '.$no_checked.' />
                     '.$this->iconFont('radio-btn-unchecked', array('class' => 'fake-radio '.$no_on)).'
                    <span class="ef-label">'.$no_label.'</span>
                </span>';

			}

			// else if input
			else {
				$li_class = 'mt-text-option';

				if (!empty($array['one_line'])){
					$li_class.= ' one-line';
				}

				$input_id = $input_class = $arrow_class = $class = $rel = $arrow = '';
				$input_value = ( !empty($this->preferences[$key]) ) ? $this->preferences[$key] : '';
				$extra_info = '';

				// does it need a custom id?
				if (!empty($array['input_id'])){
					$input_id = $array['input_id'];
				}
				// does it need a custom input class?
				if (!empty($array['input_class'])){
					$input_class = $array['input_class'];
				}
				// does it need a custom arrow class?
				if (!empty($array['arrow_class'])){
					$arrow_class = $array['arrow_class'];
				}
				// does it need a custom input name?
				if (!empty($array['input_name'])){
					$input_name = $array['input_name'];
				}
				// does it need a custom input value?
				if (!empty($array['input_value'])){
					$input_value = $array['input_value'];
				}
				// do we want to add a data attribute (quick and dirty way to support one att)
				if (!empty($array['extra_info'])){
					$extra_info = ' data-info="'. $array['extra_info'].'"';
				}

				if (!empty($array['prop'])){
					$extra_info.= ' data-prop="'. $array['prop'].'"';
				}

				// exception for css unit set (keep blank)
				if ($input_id == 'css_unit_set'){
					$input_value = '';
				}

				// is it a combobox?
				if (!empty($array['combobox'])){
					$class = 'combobox has-arrows';
					$rel = 'rel="'.$array['combobox'].'"';
					$arrow = '<span class="combo-arrow '.$arrow_class.'"></span>';
				}

				if (!empty($input_id)){
					$input_id = 'id="'.$input_id.'"';
				}

				$form_options = '
                <span class="tvr-input-wrap">
                    <input '.$input_id.' type="text" autocomplete="off" name="'.$input_name.'"  
                    class="'.$class . ' ' . $input_class.'" '.$rel . $extra_info.'
                    value="'. esc_attr($input_value).'" />'
                .$arrow .
                '</span>';
			}

			// sometimes we use empty cell after
			if (!empty($array['empty_before'])){
				$html.= '<li class="empty-cell empty-before-'.$key.'"></li>';
			}

			// the option
			$html.= '
            <li class="'.$li_class.'">
                <label>
                    <span title="'.esc_attr($array['explain']).'">
                        '.esc_html($array['label']) . ' ' . $array['link'].'
                    </span>
                </label>
                '.$form_options.'
            </li>';

			// sometimes we use empty cell after
			if (!empty($array['empty_after'])){
				$html.= '<li class="empty-cell empty-after-'.$key.'"></li>';
			}
		}

        return $html;

	}

    // common function for outputting yes/no radios
	function preferences_grid($pref_cats, $settings_class){

		// ensure CSS recompile is off by default
		$this->preferences['manual_recompile_all_css'] = 0;

		// generate the HTML
		$html = '
        <ul id="'.$settings_class.'" class="mt-form-settings '.$settings_class.'">';

		foreach ($pref_cats as $cat_key => $cat_array){

			$html.= '
            <li class="empty-cell empty-before-'.$cat_key.'"></li>
            <li class="preference-category pref-cat-'.$cat_key.'">'.$cat_array['label'].'</li>';

			$html.= $this->preferences_grid_items($cat_array['items']);
		}

		$html.= '
        </ul>';

		return $html;
	}

	// common function for outputting yes/no radios
	function output_radio_input_lis($opts, $hidden = ''){

		foreach ($opts as $key => $array) {

			// ensure various vars are defined
			$array['label_no'] = ( !empty($array['label_no']) ) ? $array['label_no'] : '';
			$array['default'] = ( !empty($array['default']) ) ? $array['default'] : '';
			$array['link'] = ( !empty($array['link']) ) ? $array['link'] : '';
			$yes_val = ($key == 'draft_mode') ? $this->current_user_id : 1;

			// skip edge mode if not available
			if ($key == 'edge_mode' and !$this->edge_mode['available']){
				continue;
			}

			// ensure this setting is off by default
			$this->preferences['manual_recompile_all_css'] = 0;

			?>
			<li class="fake-radio-parent <?php echo $hidden; ?>" xmlns="http://www.w3.org/1999/html">
				<label>
							<span title="<?php echo esc_attr($array['explain']); ?>">
								<?php echo esc_html($array['label']) . $array['link']; ?>
							</span>
				</label>

				<span class="yes-wrap p-option-wrap">
							<input type='radio' autocomplete="off" class='radio'
							       name='tvr_preferences[<?php echo $key; ?>]' value='<?php echo $yes_val; ?>'
								<?php
								if ( !empty($this->preferences[$key])) {
									echo 'checked="checked"';
									$on = 'on';
								} else {
									$on = '';
								}
								?>
                            />
                            <?php
                            echo $this->iconFont('radio-btn-unchecked', array('class' => 'fake-radio '.$on))
                            ?>

							<span class="ef-label"><?php esc_html_e('Yes', 'microthemer'); ?></span>
						</span>
				<span class="no-wrap p-option-wrap">
							<input type='radio' autocomplete="off" class='radio' name='tvr_preferences[<?php echo $key; ?>]' value='0'
								<?php
								if ( (empty($this->preferences[$key]))
									// exception for mq set overwrite as this isn't stored as a global preference
									//and $key != 'overwrite_existing_mqs'
								) {
									echo 'checked="checked"';
									$on = 'on';
								} else {
									$on = '';
								}
								?>
                            />
                             <?php
                             echo $this->iconFont('radio-btn-unchecked', array('class' => 'fake-radio '.$on))
                             ?>
							<span class="ef-label"><?php esc_html_e('No', 'microthemer'); ?></span>
						</span>
			</li>
			<?php
		}
	}

	// common function for text inputs/combos
	function output_text_combo_lis($opts, $hidden = ''){
		foreach ($opts as $key => $array) {
			$input_id = $input_class = $arrow_class = $class = $rel = $arrow = '';
			$input_name = 'tvr_preferences['.$key.']';
			$input_value = ( !empty($this->preferences[$key]) ) ? $this->preferences[$key] : '';
			$extra_info = '';

			// does it need a custom id?
			if (!empty($array['input_id'])){
				$input_id = $array['input_id'];
			}
			// does it need a custom input class?
			if (!empty($array['input_class'])){
				$input_class = $array['input_class'];
			}
			// does it need a custom arrow class?
			if (!empty($array['arrow_class'])){
				$arrow_class = $array['arrow_class'];
			}
			// does it need a custom input name?
			if (!empty($array['input_name'])){
				$input_name = $array['input_name'];
			}
			// does it need a custom input value?
			if (!empty($array['input_value'])){
				$input_value = $array['input_value'];
			}
			// do we want to add a data attribute (quick and dirty way to support one att)
			if (!empty($array['extra_info'])){
				$extra_info = ' data-info="'. $array['extra_info'].'"';
			}

			// exception for css unit set (keep blank)
			if ($input_id == 'css_unit_set'){
				$input_value = '';
			}

			// is it a combobox?
			if (!empty($array['combobox'])){
				$class = 'combobox has-arrows';
				$rel = 'rel="'.$array['combobox'].'"';
				$arrow = '<span class="combo-arrow '.$arrow_class.'"></span>';
			}

			if (!empty($input_id)){
				$input_id = 'id="'.$input_id.'"';
			}

			?>
			<li class="tvr-input-wrap <?php echo $hidden; ?>">
				<label class="text-label">
							<span title="<?php echo esc_attr($array['explain']); ?>">
								<?php echo esc_html($array['label']); ?>
							</span>
				</label>
				<input type='text' autocomplete="off" name='<?php echo esc_attr($input_name); ?>'
					<?php echo $input_id; ?>
					   class="<?php echo $class . ' ' . $input_class; ?>" <?php echo $rel . $extra_info; ?>
					   value='<?php echo esc_attr($input_value); ?>' />
				<?php echo $arrow; ?>
			</li>
			<?php
		}
	}

	// create revisions table if it doesn't exist
	function maybeCreateOrUpdateRevsTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . "micro_revisions";
		$micro_ver_num = get_option($this->micro_ver_name);

		/*wp_die('the table should update' . ' ' . ($micro_ver_num > $this->db_chg_in_ver) . ' '  .$micro_ver_num . '  '.$this->db_chg_in_ver);*/

		// only execut following code if table doesn't exist.
		// dbDelta function wouldn't overwrite table,
		// But table version num shouldn't be updated with current plugin version if it already exists
		if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name or
		    $micro_ver_num < $this->db_chg_in_ver) {


			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";


			$sql = "CREATE TABLE $table_name (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						user_action TEXT DEFAULT '' NOT NULL,
						data_size VARCHAR(10) DEFAULT '' NOT NULL,
						settings longtext NOT NULL,
						preferences longtext DEFAULT NULL,
						saved BOOLEAN DEFAULT 0,
						upgrade_backup BOOLEAN DEFAULT 0,
						UNIQUE KEY id (id)
						) $charset_collate;";

			//wp_die('the table should update' . $sql);

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			// store the table version in the wp_options table (useful for upgrading the DB)
			add_option($this->micro_ver_name, $this->version);

			// todo dbDelta doesn't overwrite but condition always returns true. Have proper check and add first entry (see below)
			//echo '$wpdb->get_var( "SHOW TABLES LIKE $table_name" ) != $table_name' .$wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name;
			//echo '$micro_ver_num < $this->db_chg_in_ver' .$micro_ver_num < $this->db_chg_in_ver;
			// set the first entry to be the state MT is in during initial install (empty default folders)
			//$initial_install = esc_html__('Initial install', 'microthemer');
			// Note: set $tryCreate to false to prevent circular ref
			//$this->updateRevisions( $this->options, $initial_install, false);

			return true;
		}
		else {
			return false;
		}
	}


	// format user action in same json format - see JS function: TvrUi.user_action() - this isn't very DRY. JS templating would be better.
	function json_format_ua($icon, $item, $val = false){
		$json = '{"items":["'.$item.'"],"val":"'.$val.'","icon":"'.$icon.'","main_class":"",';
		$json.= '"icon_html":"<span class=\"h-i no-click '.$icon.'\" ></span>",';
		$json.= '"html":"<span class=\"history-item history_'.$this->to_param($item).'\"><span class=\"his-items\"><span>'.$item.'</span></span>';
		if ($val){
			//$json.= '<span class=\"his-val\">'.htmlentities($val).'</span>'; // escHistory
			$json.= '<span class=\"his-val\">'.$val.'</span>';
		}
		$json.= '</span>"}';
		return $json;
	}

	// Update the Revisions Table
	function updateRevisions(
		$save_data, $user_action = '', $tryCreate = true, $preferences = false, $upgrade_backup = false
	) {

		//debug_print_backtrace();
		//wp_die('user_action_debug: '. $user_action);

		// sometimes we don't want to log an action e.g. if editing a selector's code via the editor
		// the change will be shown in the code editor change history entry
		// and we don't want them to restore one without the other (selector code and editor content)
		if (is_null($user_action) or $user_action === 'null' or $user_action === 'false' or $user_action === false){
			return true; // false would generate an error message
		}

		$user_action = html_entity_decode($user_action);

		// create/update revisions table if it doesn't already exist or is out of date
		if ($tryCreate){
			$this->maybeCreateOrUpdateRevsTable();
		}

		// include the user's current media queries for restoring back
        if (!empty($save_data)){
	        $save_data['non_section']['active_queries'] = $this->preferences['m_queries'];
        }

		// add the revision to the table
		global $wpdb;
		$table_name = $wpdb->prefix . "micro_revisions";
		$serialized_data = $save_data ? serialize($save_data) : '';
        $serialized_preferences = ($preferences ? serialize($preferences) : '');
        $dataToSize = $serialized_data . $serialized_preferences;
		$data_size = round(strlen($dataToSize)/1000).'KB';
		// $wpdb->insert (columns and data should not be SQL escaped): https://developer.wordpress.org/reference/classes/wpdb/insert/
		$rows_affected = $wpdb->insert( $table_name, array(
			'time' => current_time('mysql', false), // use blogs local time - doesn't work on Nelson's site
			//'time' => $this->adjust_unix_timestamp_for_local(time(), 'mysql'), // nor does this
			//'time' => date_i18n('Y-m-d H:i:s'), // or this

			'user_action' => $user_action,
			'data_size' => $data_size,
			'settings' => $serialized_data,

			// pass in preferences when a revision should revert to workspace settings
			// adding this so users can rollback to a pre-speed version of MT in case of an upgrade issue
			'preferences' => $serialized_preferences,
			'upgrade_backup' => $upgrade_backup,
		));

		/*$this->log(
					esc_html__('$rows_affected: '.$rows_affected, 'microthemer'),
					'<pre>Hello' . print_r($user_action, true) . '</pre>'
				);*/

		//$this->show_me.= '<pre>$rows_affected:  '.$rows_affected.'</pre>';

		$default_num_revs = 50;
		$max_revisions = !empty($this->preferences['num_history_points'])
			? intval($this->preferences['num_history_points'])
			: $default_num_revs;

		// cap lowest and highest number of revisions
		if ($max_revisions > 300){
			$max_revisions = 300;
		} if ($max_revisions < 1){
			$max_revisions = 1;
		}

		$maybe_exclude_backups = !$upgrade_backup ? 'and upgrade_backup != 1' : '';

		// check if an old revision needs to be deleted
		$wpdb->get_results("select id from $table_name 
				where saved != 1 $maybe_exclude_backups order by id asc");

		// this will not delete saved or backups for regular saves. And wont delete saved backups ever.
		if ($wpdb->num_rows > $max_revisions) {
			$excess_rows = ($wpdb->num_rows - $max_revisions);
			$sql = "delete from $table_name 
                    where saved != 1 $maybe_exclude_backups order by id asc limit $excess_rows";
			$wpdb->query($sql);
		}

		return true;
	}

	function updateRevisionSaveStatus($rev_id, $rev_save_status){
		global $wpdb;
		$table_name = $wpdb->prefix . "micro_revisions";
		$wpdb->query(
			$wpdb->prepare(
				"update $table_name set saved = %d where id = %d",
				$rev_save_status, $rev_id
			)
		);
	}

	// adjust unix time stamp for local time
	function adjust_unix_timestamp_for_local($unix_timestamp, $format = 'timestamp'){
		$mysql_format = get_date_from_gmt( date( 'Y-m-d H:i:s', $unix_timestamp ) );
		return $format === 'timesptamp'
			? strtotime($mysql_format)
			: $mysql_format;
		//return strtotime( get_date_from_gmt( date( 'Y-m-d H:i:s', $unix_timestamp ) ));
	}

	// custom function for time diff as we want seconds
	function human_time_diff( $from, $to = '' ) {

		if ( empty( $to ) ) {
			$to = current_time( 'timestamp', false); // use blogs local time
		}

		$diff = (int) abs( $to - $from );

		if ( $diff < 60 ) {
			//$since = $diff . ' secs';
			$since = sprintf( _n( '%s sec', '%s secs', $diff ), $diff );
		} elseif ( $diff < HOUR_IN_SECONDS ) {
			$mins = round( $diff / MINUTE_IN_SECONDS );
			if ( $mins <= 1 )
				$mins = 1;
			/* translators: min=minute */
			$since = sprintf( _n( '%s min', '%s mins', $mins ), $mins );
		} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
			$hours = round( $diff / HOUR_IN_SECONDS );
			if ( $hours <= 1 )
				$hours = 1;
			$since = sprintf( _n( '%s hour', '%s hours', $hours ), $hours );
		} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
			$days = round( $diff / DAY_IN_SECONDS );
			if ( $days <= 1 )
				$days = 1;
			$since = sprintf( _n( '%s day', '%s days', $days ), $days );
		} elseif ( $diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
			$weeks = round( $diff / WEEK_IN_SECONDS );
			if ( $weeks <= 1 )
				$weeks = 1;
			$since = sprintf( _n( '%s week', '%s weeks', $weeks ), $weeks );
		} elseif ( $diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS ) {
			$months = round( $diff / MONTH_IN_SECONDS );
			if ( $months <= 1 )
				$months = 1;
			$since = sprintf( _n( '%s month', '%s months', $months ), $months );
		} elseif ( $diff >= YEAR_IN_SECONDS ) {
			$years = round( $diff / YEAR_IN_SECONDS );
			if ( $years <= 1 )
				$years = 1;
			$since = sprintf( _n( '%s year', '%s years', $years ), $years );
		}
		return $since;
	}

	// Get Revisions for displaying in table
	function getRevisions($summary = false) {

		$rev_table = '';

		// create/update revisions table if it doesn't already exist or is out of date
		$this->maybeCreateOrUpdateRevsTable();

		// get the full array of revisions

		global $wpdb;
		$table_name = $wpdb->prefix . "micro_revisions";
		$limit = $summary ? ' limit 10' : '';
		//$revs = $wpdb->get_results("select id, user_action, data_size, date_format(time, '%D %b %Y %H:%i') as datetime
		/*$revs = $wpdb->get_results("select id, user_action, data_size, unix_timestamp(time) as unix_timestamp
				from $table_name order by id desc");*/
		$revs = $wpdb->get_results("select id, user_action, data_size, time, saved from $table_name order by id desc".$limit);
		$total_rows = $wpdb->num_rows;
		// if no revisions, explain
		if ($total_rows == 0) {
			return '<span class="no-revisions-table">' .
			       esc_html__('No revisions have been created.', 'microthemer') .
			       '</span>';
		}

		$summary_class = '';

		if ($summary){
			$summary_class = ' summary-revisions-table';
			$rev_table.= '
                    <div class="lastest-revisions-heading new-set-heading">'
			             .esc_html__('Latest revisions', 'microthemer')
			             .'</div>';
		}


		$rev_table.=
			'
				<table class="revisions-table'.$summary_class.'">
				<thead>
				<tr>
					<th class="rev-size">' . esc_html__('Size', 'microthemer') . '</th>
					<th class="rev-time">' . esc_html__('Time', 'microthemer') . '</th>
					<th class="rev-action" colspan="2">' . esc_html__('User Action', 'microthemer') . '</th>
					<th class="rev-restore">' . esc_html__('Restore', 'microthemer') . '</th>
					<th class="rev-save">' . esc_html__('Save', 'microthemer') . '</th>
				
				</tr>
				</thead>';

		$i = 0;
		foreach ($revs as $rev) {

			// adjust unix timestamp for blog's GMT timezone offset - no this doesn't make sense
			//$local_timestamp = $this->adjust_unix_timestamp_for_local($rev->unix_timestamp);

			//$local_timestamp = $rev->unix_timestamp;

			$local_timestamp = strtotime($rev->time);
			$time_ago = $this->human_time_diff($local_timestamp);

			//$time_ago = $this->getTimeSince($rev->timestamp);
			// get traditional save or new history which will be in json obj
			$user_action = $rev->user_action;
			$rev_icon = $main_class = '';
			$legacy_new_class = 'legacy-hi';

			/*if ($user_action){
						wp_die('string chars', json_encode($user_action));
					}*/

			// fix a bug with line-breaks getting into the history json, making it invalid
			if (preg_match("/[\n\r]+\t+/", $user_action)){
				$user_action = preg_replace("/[\n\r]+\t+/", '', $user_action);
			}

			// (escHistory)
			//wp_die('history rev <pre>' . print_r($this->json('decode', $rev->user_action), true) . '</pre>');
			//wp_die('history rev <pre>' . print_r(json_decode($rev->user_action, true), true) . '</pre>');
			//$this->log('user action', $rev->user_action, 'warning', false);

			if (strpos($user_action, '{"') !== false){

				//wp_die('history rev <pre>' . print_r(json_decode($rev->user_action, true), true) . '</pre>');

				$ua = $this->json('decode', $user_action); //  json_decode($rev->user_action, true);

				//wp_die('history rev <pre>' . print_r($ua, true) . '</pre>');

				$legacy_new_class = 'new-hi';
				$user_action = $this->unescape_cus_quotes($ua['html'], true);
				$rev_icon = $ua['icon_html'];
				$main_class = $ua['main_class'];
			}

			// saved lock icon
			$rev_is_saved = !empty($rev->saved);
			$save_rev_pos = esc_html__('Permanently save restore point', 'microthemer');
			$save_rev_neg = esc_html__('Unsave restore point', 'microthemer');
			$rev_is_saved_class = $rev_is_saved ? ' revision-is-saved' : '';
			$rev_save_title = $rev_is_saved ? $save_rev_neg : $save_rev_pos;
			$saved_icon = '<span class="save-revision'.$rev_is_saved_class.'" 
					data-pos="'.$save_rev_pos.'" data-neg="'.$save_rev_neg.'" title="'.$rev_save_title.'"
					rel="'.$rev->id.'"></span>';

			$niceDate = date('l jS \of F Y H:i:s', $local_timestamp);
			$timeOutput = $summary
				? $niceDate
				: sprintf(esc_html__('%s ago', 'microthemer'), $time_ago);

			// todo need to have live JS time ago

			//$timeOutput = sprintf(esc_html__('%s ago', 'microthemer'), $time_ago);

			// remove any HTML from history val, which should be CSS, unless it's a colored box (escHistory)
			preg_match('/<span class="his-val">(.+?)<\/span>/', $user_action, $history_val_match);
			if ($history_val_match && !preg_match('/^<span class="colored-box"/', $history_val_match[1]) ){
				//wp_die('history_val <pre>' . print_r($history_val_match, true) . '</pre>');
				$user_action = str_replace(
					$history_val_match[1],
					htmlentities($history_val_match[1]),
					$user_action
				);
			}


			//<td class="rev-num">'.$total_rows.'</td>
			//<td class="rev-size">'.$rev->data_size.'</td>
			$rev_table.= '
					<tr class="'.$legacy_new_class.$rev_is_saved_class.'">
						<td class="rev-size">'.$rev->data_size.'</td>
						<td class="rev-time tvr-help" title="'.$niceDate.'">'.$timeOutput.'</td>
						<td class="rev-icon '.$main_class.'" title="'.$timeOutput.'">'.$rev_icon.'</td>
						<td class="rev-action '.$main_class.'">'.$user_action.'</td>
						<td class="rev-restore">';

			// current item
			if ($i == 0) {
				$restoreActionText = esc_html__('Current', 'microthemer');
				$rev_table.= $summary
					? $this->iconFont('check-circle', array(
						'class' => 'current-revision-tick mt-fixed-color',
						'title' => $restoreActionText

					))
					: $restoreActionText;
			}

			// restorable item
			else {

				$restoreClass = 'restore-link';
				$restoreRel = 'mt_action=restore_rev&tvr_rev='.$rev->id;
				$restoreText = esc_html__('Restore', 'microthemer');

				if ($summary){
					$rev_table.= $this->iconFont('undo', array(
						'class' => $restoreClass,
						'title' => $restoreText,
						'rel' => $restoreRel
					));
				} else {
					$rev_table.='<span class="'.$restoreClass.' link" rel="'.$restoreRel.'">'.$restoreText.'</span>';
				}
			}

			$rev_table.='
					    </td>
                         <td class="rev-save">'.$saved_icon.'</td>
					</tr>';
			--$total_rows;
			++$i;
		}
		$rev_table.= '</table>';

		/*if ($summary){
					$rev_table.= '<div class="show-dialog view-older-revisions link" rel="display-revisions">'.esc_html__('Older revisions', 'microthemer').'</div>';
				}*/

		return $rev_table;
	}

	// update a single preference
	function update_preference($key, $value){
		$pref_array = array();
		$pref_array[$key] = $value;
		$this->savePreferences($pref_array);
	}

	// Restore a revision
	function restoreRevision($rev_key) {

        // get the revision
		global $wpdb;
		$table_name = $wpdb->prefix . "micro_revisions";
		$rev = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $rev_key) );

		// signal that all selectors should be recompiled (to ensure latest data structure)
		$this->update_preference('manual_recompile_all_css', 1);

        // if revision has preferences, restore those
		if (!empty($rev->preferences)){
			update_option($this->preferencesName, unserialize($rev->preferences));
		}

		// restore to options DB field - unless this is purely a revisions history
        if (!empty($rev->settings)){
	        $rev->settings = unserialize($rev->settings);
	        // add css units, mq keys (extra tabs) etc to settings display correctly
	        $filtered_json = $this->filter_incoming_data('restore', $rev->settings);
	        update_option($this->optionsName, $filtered_json);
	        $this->options = get_option($this->optionsName); // this DB interaction doesn't seem necessary...
        }

		return true;
	}

	function css_sass_comment_regex(){
		$commentSingle      = '\/\/';
		$commentMultiLeft   = '\/\*';
		$commentMultiRight  = '\*\/';
		$commentPattern = $commentMultiLeft . '.*?' . $commentMultiRight;
		return '/' . $commentSingle . '[^\n]*\s*|(' . $commentPattern . ')/isu';
	}

	function css_sass_import_regex(){
		return '/@import\s+(?:"|\')([\w\-.\/ ]+)(?:"|\');/';
	}

	function get_css_sass_comments($content){

		preg_match_all(
			$this->css_sass_comment_regex(),
			$content,
			$matches,
			PREG_PATTERN_ORDER
		);

		return $matches ? $matches[0] : false;
	}

	function strip_css_sass_comments($content, $onlyImportComments = false){

		if ($matches = $this->get_css_sass_comments($content)){

			foreach ($matches as $comment){

				if (!$onlyImportComments || preg_match($this->css_sass_import_regex(), $comment) ){
					$content = str_replace($comment, '', $content);
				}

			}
		}

		return $content;
	}

	// generate sass content for replacing @import rules with then compiling sass on the frontend
	function get_sass_import_paths($content, $cur_path){

		$content = $this->strip_css_sass_comments($content, true);

		preg_match_all(
			$this->css_sass_import_regex(),
			$content,
			$matches,
			PREG_PATTERN_ORDER
		);

		if (!$matches){
			return false;
		}

		$formatted = array();

		// sometimes root path has ./ when it should be a blank string
		$cur_path = preg_replace('/^\.\//', '', $cur_path);

		foreach ($matches[0] as $i => $rule){

			$path = $matches[1][$i];
			$resolved_path = $this->normalize_path($cur_path . $path);
			$valid_path = $this->check_sass_file_exists($resolved_path);

			// only get list valid files that do not have .css extension explicitly declared
			if ($valid_path && $this->get_extension($path) !== 'css'){
				$formatted[$i] = array(
					'rule' => $rule,
					'path' => $path,
					'cur_path' => $cur_path,
					'resolved_path' => $valid_path
				);
			}


		}

		return $formatted;
	}

	// convert import file and sub imports into long strings
	function recursively_scan_import_files($config, $cur_path = ''){

		$rule = $config['import']['rule'];
		$file_content = $rule; // default to rule as it will not be possible to replace if file path is wrong
		$resolved_path = $config['import']['resolved_path']; // relative path from root with extension
		$abs_path = $this->micro_root_dir . $resolved_path;

		if (file_exists($abs_path)){

			// get file content
			$file_content = file_get_contents($abs_path);

			// strip commented out @import paths
			$file_content = $this->strip_css_sass_comments($file_content, true);

			// check for sub @imports
			if ($imports = $this->get_sass_import_paths($file_content, $this->get_real_dirname($resolved_path))){

				//$file_content.= '<pre>'.print_r($imports, true).'</pre>';
				//return $file_content;

				foreach ($imports as $i => $arr){

					$sub_content = $this->recursively_scan_import_files(
						array(
							'import' => $arr
						),
						$this->get_real_dirname($arr['resolved_path']) // new current path
					);

					$file_content = str_replace($imports[$i]['rule'], $sub_content, $file_content);

					// debug
					//$file_content.= 'RUULE: '.$rule . 'SUUB: '.$sub_content;
					//$file_content.= 'Prev resolved path: '.$this->get_real_dirname($resolved_path). '<br />';
					//$file_content.= '$imports: <pre>'.print_r($imports, true).'</pre>';

				}
			}
		}

		return $file_content;
	}

	// convert e.g. /path/piece/section/../file.txt to /path/piece/file.txt
	function normalize_path($str){
		$N = 0;
		$A = explode("/",preg_replace("/\/\.\//",'/',$str));  // remove current_location
		$B = array();
		for($i = sizeof($A)-1;$i>=0;--$i){
			if(trim($A[$i]) ===".."){
				$N++;
			}else{
				if($N>0){
					$N--;
				}
				else{
					$B[] = $A[$i];
				}
			}
		}
		return implode("/",array_reverse($B));
	}

	// convert e.g. /path/piece/section/../file.txt to /path/piece/
	function get_real_dirname($path){
		$pathinfo = pathinfo($this->normalize_path($path));
		return !empty($pathinfo['dirname']) ? trailingslashit($pathinfo['dirname']) : '';
	}

	// generate sass content for replacing @import rules with then compiling sass on the frontend
	function get_sass_import_content(){

		$preloaded_sass = array();

		// if there are import paths defined
		if (!empty($this->options['non_section']['hand_coded_css']) &&
		    $imports = $this->get_sass_import_paths($this->options['non_section']['hand_coded_css'], '')
		){

			// recursively import the content so there is one long string of SASS for each @import
			foreach ($imports as $i => $arr){
				$preloaded_sass[$arr['resolved_path']] = $this->recursively_scan_import_files(
					array(
						'import' => $arr
					)
				);
			}
		}

		return $preloaded_sass;
	}

	function client_scss(){
		return !empty($this->preferences['allow_scss']);
	}

	// check if a file exists in /micro-themes dir (various different paths are valid with SASS @imports)
	function check_sass_file_exists($path){

		$files = $this->file_structure;
		$parts = explode('/', $path);
		$partsFinalIndex = count($parts)-1;

		// check for user specified file extension
		$path_parts = pathinfo($path);
		$definedExt = !empty($path_parts['extension']) ? $path_parts['extension'] : '';
		$noExt =  ltrim($path_parts['filename'], '_');

		// variation dimensions
		$extensions = $definedExt ? array($definedExt) : ['scss', 'sass', 'css'];
		$names = array($noExt, '_'.$noExt);

		// create variations
		$variations = array();
		foreach ($extensions as $e) {
			foreach ($names as $n) {
				$partsClone = $parts;
				$partsClone[$partsFinalIndex] = $n.'.'.$e;
				$variations[] = $partsClone;
			}
		}

		// loop through variations
		foreach ($variations as $variation) {

			if ($this->get_item(
				$files,
				$variation
			)){
				return implode('/', $variation); // bingo
			}
		}

		return false;
	}

	// simple wrapper getting data // todo test it works
	function &get_item(&$data, $trail, $startIndex = 0){

		$item = &$this->get_or_update_item(
			$data, array(
			'action' => 'get',
			'trail' => $trail,
		), $startIndex
		);

		return $item;
	}


	// if global !important preference changes MT needs to do full recompile
	function preference_settings_changed($keys, $orig, $new){

		foreach($keys as $key){
			if (intval($orig[$key]) !== intval($new[$key])){
				return true;
			}
		}

		return false;
	}

	// process preferences form
	function process_preferences_form(){

		$pref_array = $this->deep_unescape($_POST['tvr_preferences'], 0, 1, 1);
		$pref_array['num_saves'] = ++$this->preferences['num_saves'];

		if (empty($this->preferences['auto_publish_mode'])){
			$pref_array['num_unpublished_saves'] = ++$this->preferences['num_unpublished_saves'];
		}

		// ensure that changing the default hover inspect setting takes effect immediately
		//wp_die('the post 2'. '<pre>'.print_r($_POST['tvr_preferences'], true).'</pre>');
		$pref_array['hover_inspect'] = !empty($pref_array['hover_inspect_off_initially']) ? 0 : 1;

		// CSS units need saving in a different way (as my_props is more than just css units)
		$pref_array = $this->update_default_css_units($pref_array);

		// update g_url_with_subsets as manual subset param may have changed
		$pref_array['g_url_with_subsets'] =
			$this->g_url_with_subsets(false, false, $pref_array['gfont_subset']);

		// if they changed !important or SCSS settings do full recompile
		if ($this->preference_settings_changed(['css_important', 'allow_scss'],
			$this->preferences, $_POST['tvr_preferences'])){
			$pref_array['manual_recompile_all_css'] = 1;
		}

		if ($this->savePreferences($pref_array)) {

			$this->log(
				esc_html__('Preferences saved', 'microthemer'),
				'<p>' . esc_html__('Your Microthemer preferences have been successfully updated.', 'microthemer') . '</p>',
				'notice'
			);

			// the admin bar shortcut needs to be applied here else it will only show on next page load
			if (!empty($this->preferences['admin_bar_shortcut'])) {
				add_action( 'admin_bar_menu', array(&$this, 'custom_toolbar_link'), 999999);
			} else {
				remove_action( 'admin_bar_menu', array(&$this, 'custom_toolbar_link'), 999999 );
			}
		}

		// save last message in database so that it can be displayed on page reload (just once)
		$this->cache_global_msg();
	}

	// update the preferences array with the new units when the user saves the preferences
	function update_default_css_units($pref_array){
		// cache the posted css units
		$new_css_units = $pref_array['new_css_units'];
		// then discard as junk
		unset($pref_array['new_css_units']);
		// update the existing my_props array
		$pref_array['my_props'] = $this->update_my_prop_default_units($new_css_units);
		return $pref_array;
	}

	// process posted zip file (do this on manage and single hence wrapped in a funciton )
	function process_uploaded_zip() {
		if ($_FILES['upload_micro']['error'] == 0) {
			$this->handle_zip_package();
		}
		// there was an error - save in global message
		else {
			$this->log_file_upload_error($_FILES['upload_micro']['error']);
		}
	}

	/*// &preview= and ?preview= cause problems - strip
			function strip_preview_params($url){
				//$url = explode('preview=', $url); // which didn't support regex (for e.g. elementor)
				$url = preg_split('/(?:elementor-)?preview=/', $url, -1);
				$url = rtrim($url[0], '?&');
				return $url;
			}*/

	// prevent errors when admin or frontend doesn't use SSL, but the other does
	function ensure_iframe_protocol_matches_admin(){

		$preview_url = $this->preferences['preview_url'];
		$preview_plain = strpos($preview_url,'http:') !== false;
		$admin_ssl = Common::get_protocol() === 'https://';

		$update = false;

		// SSL alteration
		if ($admin_ssl and $preview_plain){
			$preview_url = str_replace('http:', 'https:', $preview_url);
			$update = true;
		}

		// maybe strip Oxygen template URL


		if ($update){
			$this->savePreferences(array('preview_url' => $preview_url));
		}

	}

	// we need to know the preview URL post/page id, which should be saved in the preference "preview_item_id"
	// when the preview URL changes either by using a toolbar option or navigating around in MT
	// but there is also a function WP provides for obtaining from the URL we can use as a fallback
	function get_preview_item_id(){
		return !empty($this->preferences['preview_item_id'])
			? $this->preferences['preview_item_id']
			: url_to_postid($this->preferences['preview_url']);
	}

	// update the iframe preview url
	function maybe_set_preview_url($nonce_key = false){

		// update preview url in DB and get title and post/page ID
		$pref_array = array();
		$url = strip_tags(rawurldecode($_GET['mt_preview_url']));
		$label = isset($_GET['mt_path_label'])
			? strip_tags(rawurldecode($_GET['mt_path_label']))
			: false;
		$item_id = isset($_GET['mt_item_id'])
			? strip_tags(rawurldecode($_GET['mt_item_id']))
			: false;

		$pref_array['preview_url'] = Common::strip_page_builder_and_other_params($url);
		$pref_array['preview_title'] = $label;
		$pref_array['preview_item_id'] = $item_id;

		// path won't be set if this is triggered after user clicked WP Toolbar MT link
		if (!empty($_GET['mt_preview_path'])){

			// get path and strip builder and other params
			$path = strip_tags(rawurldecode($_GET['mt_preview_path']));
			$path = Common::strip_page_builder_and_other_params($path);

			//wp_die('$path: '.$_GET['mt_preview_path']);



			// if the preview URL is 1, we should use the site_url with the path
			// this is used on the live demo
			if (intval($url) === 1){
				$pref_array['preview_url'] = untrailingslashit($this->site_url).$path;
			}

			// remove from array if already exists (as we be prepended at start)
			$existingKey = $this->in_array_column($path, $this->preferences['custom_paths'], 'value');
			if ($existingKey){
				array_splice($this->preferences['custom_paths'], $existingKey,1);
			}

			// sometimes the page title might have a different URL e.g. /?p=1, /, /home-page/
			// so we should remove any with the same name,
			// as the user probably just wants to most recent URL e.g. /home-page/ for the title
			for ($i = count($this->preferences['custom_paths'])-1; $i  >= 0; $i --) {

				if (!isset($this->preferences['custom_paths'][$i]['label'])
				    || $this->preferences['custom_paths'][$i]['label'] === $label){
					unset($this->preferences['custom_paths'][$i]);
				}

			}

			// insert url at start of custom_paths array
			array_unshift($this->preferences['custom_paths'], array(
				'value' => $path,
				'label' => $label,
				'item_id' => $item_id
			));

			// ensure only x items, and that paths are unique
			$i = 0;
			$paths_done = array();
			foreach ($this->preferences['custom_paths'] as $key => $pathOrObj){

				$custom_path = is_array($pathOrObj) ? $pathOrObj['value'] : $pathOrObj;

				// add custom path if unique
				if (empty($paths_done[$custom_path])){
					$pref_array['custom_paths'][] = $pathOrObj;
					$paths_done[$custom_path] = 1;
					++$i;
				}

				if ($i >= 8) {
					break;
				}
			}
		}

		$this->savePreferences($pref_array);

	}

	// check an array based on nested property with option to search base array for value
	function in_array_column($item, $array, $column = false, $checkFlat = false){

		if ( $foundWithColumn = array_search( $item, array_column($array, $column) ) ){
			return $foundWithColumn;
		}

		if ($checkFlat){
			return array_search($item, $array);
		}

		return false;
	}

	// check if we're on the demo site
	function is_demo_site(){
		return preg_match(
			'/^https?:\/\/(livedemo|beta)\.themeover\.(com|local)/',
			$this->site_url
		);
	}

	// Microthemer UI page
	function microthemer_ui_page() {

		// only run code if it's the ui page
		if ( isset($_GET['page']) and $_GET['page'] == $this->microthemeruipage ) {

			if (!current_user_can('administrator')){
				wp_die('Access denied');
			}

			// initial microthemer setup
			if (isset($_POST['mt_initial_setup_submit'])) {

				check_admin_referer( 'mt_initial_setup_form' );

                $error = false;
                $imported = false;
				$pref_array = array();

                // if they provided an import file, load that
                if (!empty($_FILES['preferences_file']['name'])){

	                $filePath = $_FILES['preferences_file']['tmp_name'];
	                $fileSize = filesize($filePath);
	                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
	                $fileType = finfo_file($fileInfo, $filePath);
                    $ext = $this->get_extension($_FILES['preferences_file']['name']);
                    $max = ' (max '.(floor($this->maxUploadPrefSize / 1000000)).'MB)';

                    // validate
                    if (!is_uploaded_file($filePath)){
	                    $error = 'Incorrect file'; // only shows if user up to no good
                    } elseif ($fileSize > $this->maxUploadPrefSize){
	                    $error = 'File size too big' . $max;
	                } elseif ($fileType !== 'application/json' || $ext !== 'json'){
		                $error = 'Incorrect file type. You must upload a .json file';
	                } elseif ($_FILES['preferences_file']['error']){
	                    $phpFileUploadErrors = array(
		                    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		                    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form' . $max,
		                    3 => 'The uploaded file was only partially uploaded',
		                    4 => 'No file was uploaded',
		                    6 => 'Missing a temporary folder',
		                    7 => 'Failed to write file to disk.',
		                    8 => 'A PHP extension stopped the file upload.',
	                    );

                        // allow for PHP to add more error codes without failing
                        if (isset($phpFileUploadErrors[ $_FILES['preferences_file']['error'] ])){
	                        $error = $phpFileUploadErrors[ $_FILES['preferences_file']['error'] ];
                        } else {
	                        $error = 'Unknown error';
                        }
	                }

                    // validation passed
                    else {

                        if (!$json = file_get_contents($filePath)){
	                        $error = 'The file had no content';
                        } elseif (!$pref_array = $this->json('decode', $json)){
	                        $error = 'The JSON file could not be read';
                        } else {
                            // maybe remove optional preferences
	                        $optional = array('my_props', 'm_queries', 'enq_js', 'active_scripts_deps');
	                        foreach ($optional as $key){
		                        $check_key = $key === 'active_scripts_deps' ? 'enq_js' : $key; // one checkbox for both
		                        if (empty($_POST['tvr_optional_preferences'][$check_key])){
			                        unset($pref_array[$key]);
		                        }
	                        }
	                        //wp_die('The final prefs: <pre>' . print_r($pref_array, 1) . '</pre>');
	                        $this->savePreferences($pref_array);

	                        $imported = true;
                        }
                    }

                    // display the type of error to the user
                    if ($error){
	                    $this->log(
		                    esc_html__('File upload error', 'microthemer'),
		                    '<p>' . $error . '</p>',
		                    'error'
	                    );
                    }
                }

                // else save the handful of preferences
                else {

                    foreach ($_POST['tvr_preferences'] as $key => $value){
                        switch($key){
                            case 'buyer_email':
	                            $pref_array[$key] = strip_tags($value);
                                break;
                            default:
	                            $pref_array[$key] = intval($value);
                                break;
                        }
                    }

                    $this->savePreferences($pref_array);

                    //wp_die('filtered prefs <pre>' . print_r($_POST, 1) . '</pre>');
                }

                // update the error reporting preferences (might be done in combo with file upload
                // so keep separate from save preferences code above
                $pref_array['reporting'] = $this->preferences['reporting'];
				$pref_array['reporting']['permission'] = array();
                if (isset($_POST['reporting_permission']) && is_array($_POST['reporting_permission'])){
	                foreach ($_POST['reporting_permission'] as $key => $value){
		                $pref_array['reporting']['permission'][$key] = intval($value);
	                }
                }
				$this->savePreferences($pref_array);

				// try to unlock if they supply a new license key
                if (!empty($_POST['tvr_preferences']['buyer_email'])){
	                $response = $this->get_validation_response($_POST['tvr_preferences']['buyer_email']);
                    if (!$response){
                        $error = 'Unlock fail';
                    }
                }

				// add to revision table
				$this->updateRevisions(
					'',
					$this->json_format_ua(
						'mtif-cog',
						($imported
                            ? esc_html__('Preferences imported', 'microthemer')
                            : esc_html__('Preferences updated', 'microthemer')
                        )
					),
					true,
					$this->preferences
				);

                // if we have an error, show the setup screen again
                if ($error){
                    $this->savePreferences(array(
                            'show_setup_screen' => 1
                    ));
                    $this->setupError = $error;
                }

			}

			// validate email todo make this an ajax request, with user feedback
			if (isset($_POST['tvr_ui_validate_submit'])) {

				check_ajax_referer( 'tvr_validate_form', 'validate_nonce' );

				// tvr_validate_form
				$this->get_validation_response($_POST['tvr_preferences']['buyer_email']);
			}

            // make it possible to set an invalid license key
			$this->invalidLic();

			// if user navigates from front to MT via toolbar, set previous front page in preview
			if (isset($_GET['mt_preview_url'])) {

				if (!$this->is_demo_site() && !wp_verify_nonce($_REQUEST['_wpnonce'], 'mt-preview-nonce')) {
					die( 'Security check failed' );
				}

				$this->maybe_set_preview_url();
			}

			// if draft mode is on, but user accessing MT GUI isn't in draft_mode_uids array,
			// add them so they see latest draft changes
			if ($this->preferences['draft_mode'] and
			    !in_array($this->current_user_id, $this->preferences['draft_mode_uids'])){
				$pref_array['draft_mode_uids'] = $this->preferences['draft_mode_uids'];
				$pref_array['draft_mode_uids'][$this->current_user_id] = $this->current_user_id;
				$this->savePreferences($pref_array);
			}

			// ensure Preview URL matches HTTPS in admin
			$this->ensure_iframe_protocol_matches_admin();

			// maybe check valid subscription
			$this->maybe_check_subscription();

			// Display user interface
			include $this->thisplugindir . 'includes/tvr-microthemer-ui.php';

		}
	}

	// Documentation page
	function microthemer_docs_page(){

		// only run code on docs page
		if ($_GET['page'] == $this->docspage) {

			if (!current_user_can('administrator')){
				wp_die('Access denied');
			}

			include $this->thisplugindir . 'includes/internal-docs.php';
		}
	}

	// fonts page
	function microthemer_fonts_page(){

		// only run code on docs page
		if ($_GET['page'] == $this->fontspage) {

			if (!current_user_can('administrator')){
				wp_die('Access denied');
			}

			include $this->thisplugindir . 'includes/fonts.php';
		}
	}

	// Documentation menu
	function docs_menu($propertyOptions, $cur_prop_group, $cur_property){
		?>
		<div id="docs-menu">
			<ul class="docs-menu">
				<li class="doc-item css-ref-side">
					<?php $this->show_css_index($propertyOptions, $cur_prop_group, $cur_property); ?>
				</li>
			</ul>
		</div>
		<?php
	}

	// function for showing all CSS properties
	function show_css_index($propertyOptions, $cur_prop_group, $cur_property) {
		// output all help snippets
		$i = 1;
		foreach ($propertyOptions as $property_group_name => $prop_array) {
			$ul_class = $arrow_cls = '';
			if ($i&1) { $ul_class.= 'odd'; }
			if ($property_group_name == $cur_prop_group) { $ul_class.= ' show-content'; $arrow_cls = 'on'; }
			//if ($property_group_name == 'code') continue;
			?>
			<ul id="<?php echo $property_group_name; ?>"
			    class="css-index <?php echo $ul_class; ?> accordion-menu">

				<li class="css-group-heading accordion-heading">

					<?php
					echo $this->iconFont(str_replace('_', '-', $property_group_name), array(
						'class' => 'no-click pg-icon',
					));
					echo $this->iconFont('chevron-right', array(
						'class' => 'menu-arrow accordion-menu-arrow '.$arrow_cls,
						//'title' => 'Open/close group'
					));
					?>

					<span class="text-for-group"><?php echo $this->property_option_groups[$property_group_name]; ?></span>

					<?php

					?>
				</li>

				<?php
				foreach ($prop_array as $property_id => $array) {
					$li_class = '';
					$cssf = str_replace('_', '-', $property_id);
					$icon_name = !empty($array['icon-name']) ? $array['icon-name'] : $cssf;
					if ($property_id == $cur_property) { $li_class.= 'current'; }
					//if (!empty($array['field-class'])) { $li_class.= ' '.$array['field-class']; }
					?>
				<li class="property-item accordion-item <?php echo $li_class; ?>">
					<a href="<?php echo 'admin.php?page=' . $this->docspage; ?>&prop=<?php echo $property_id; ?>&prop_group=<?php echo $property_group_name; ?>">

						<span class="option-text"><?php echo $array['label']; ?></span>
						<?php
						echo $this->iconFont($icon_name, array(
							'class' => 'no-click'
						));
						?>
					</a>

					</li><?php
				}
				++$i;
				?>
			</ul>
			<?php
		}
	}



	// Manage Micro Themes page
	function manage_micro_themes_page() {

		// only run code if it's the manage themes page
		if ( $_GET['page'] == $this->microthemespage ) {

			if (!current_user_can('administrator')){
				wp_die('Access denied');
			}

			// handle zip upload
			if (isset($_POST['tvr_upload_micro_submit'])) {
				check_admin_referer('tvr_upload_micro_submit');
				$this->process_uploaded_zip();
			}


			// notify that design pack was successfully deleted (operation done via ajax on single pack page)
			if (!empty($_GET['mt_action']) and $_GET['mt_action'] == 'tvr_delete_ok') {
				check_admin_referer('tvr_delete_ok');
				$this->log(
					esc_html__('Design pack deleted', 'microthemer'),
					'<p>' . esc_html__('The design pack was successfully deleted.', 'microthemer') . '</p>',
					'notice'
				);
			}

			// handle edit micro selection
			if (isset($_POST['tvr_edit_micro_submit'])) {
				check_admin_referer('tvr_edit_micro_submit');
				$pref_array = array();
				$pref_array['theme_in_focus'] = $_POST['preferences']['theme_in_focus'];
				$this->savePreferences($pref_array);
			}

			// activate theme
			if (
				!empty($_GET['mt_action']) and
				$_GET['mt_action'] == 'tvr_activate_micro_theme') {
				check_admin_referer('tvr_activate_micro_theme');
				$theme_name = $this->preferences['theme_in_focus'];
				$json_file = $this->micro_root_dir . $theme_name . '/config.json';
				$this->load_json_file($json_file, $theme_name);
				// update the revisions DB field
				$user_action = sprintf(
					esc_html__('%s Activated', 'microthemer'),
					'<i>' . $this->readable_name($theme_name) . '</i>'
				);
				if (!$this->updateRevisions($this->options, $user_action)) {
					$this->log('', '', 'error', 'revisions');
				}
			}
			// deactivate theme
			if (
				!empty($_GET['mt_action']) and
				$_GET['mt_action'] == 'tvr_deactivate_micro_theme') {
				check_admin_referer('tvr_deactivate_micro_theme');
				$pref_array = array();
				$pref_array['active_theme'] = '';
				if ($this->savePreferences($pref_array)) {
					$this->log(
						esc_html__('Item deactivated', 'microthemer'),
						'<p>' .
						sprintf(
							esc_html__('%s was deactivated.', 'microthemer'),
							'<i>'.$this->readable_name($this->preferences['theme_in_focus']).'</i>' )
						. '</p>',
						'notice'
					);
				}
			}

			// include manage micro interface (both loader and themer plugins need this)
			include $this->thisplugindir . 'includes/tvr-manage-micro-themes.php';
		}
	}

	// Manage single page
	function manage_single_page() {
		// only run code on preferences page
		if( $_GET['page'] == $this->managesinglepage ) {

			if (!current_user_can('administrator')){
				wp_die('Access denied');
			}

			// handle zip upload
			if (isset($_POST['tvr_upload_micro_submit'])) {
				check_admin_referer('tvr_upload_micro_submit');
				$this->process_uploaded_zip();
			}

			// update meta.txt
			if (isset($_POST['tvr_edit_meta_submit'])) {
				check_admin_referer('tvr_edit_meta_submit');
				$this->update_meta_file($this->micro_root_dir . $this->preferences['theme_in_focus'] . '/meta.txt');
			}

			// update readme.txt
			if (isset($_POST['tvr_edit_readme_submit'])) {
				check_admin_referer('tvr_edit_readme_submit');
				$this->update_readme_file($this->micro_root_dir . $this->preferences['theme_in_focus'] . '/readme.txt');
			}

			// upload a file
			if (isset($_POST['tvr_upload_file_submit'])) {
				check_admin_referer('tvr_upload_file_submit');
				$this->handle_file_upload();
			}

			// delete a file
			if (
				!empty($_GET['mt_action']) and
				$_GET['mt_action'] == 'tvr_delete_micro_file') {
				check_admin_referer('tvr_delete_micro_file');
				// strip site_url rather than home_url in this case coz using with ABSPATH
				$root_rel_path = $this->root_rel($_GET['file'], false, true, true);
				$delete_ok = true;
				// remove the file from the media library
				if ($_GET['location'] == 'library'){
					global $wpdb;
					$img_path = $_GET['file'];
					// We need to get the images meta ID.
					/*$query = "SELECT ID FROM wp_posts where guid = '" . esc_url($img_path)
								. "' AND post_type = 'attachment'";*/
					$query = $wpdb->prepare("SELECT ID FROM wp_posts where guid = '%s' AND post_type = 'attachment'", esc_url($img_path));
					$results = $wpdb->get_results($query);
					// And delete it
					foreach ( $results as $row ) {
						//delete the image and also delete the attachment from the Media Library.
						if ( false === wp_delete_attachment( $row->ID )) {
							$delete_ok = false;
						}
					}
				}
				// regular delete of pack file
				else {
					if ( !unlink(ABSPATH . $root_rel_path) ) {
						$delete_ok = false;
					} else {
						// remove from file_structure array
						$file = basename($root_rel_path);
						if (!$this->is_screenshot($file)){
							$key = $file;
						} else {
							$key = 'screenshot';
							// delete the screenshot-small too
							$thumb = str_replace('screenshot', 'screenshot-small', $root_rel_path);
							if (is_file(ABSPATH . $thumb)){
								unlink(ABSPATH . $thumb);
								unset($this->file_structure[$this->preferences['theme_in_focus']][basename($thumb)]);
							}
						}
						unset($this->file_structure[$this->preferences['theme_in_focus']][$key]);
					}
				}
				if ($delete_ok){
					$this->log(
						esc_html__('File deleted', 'microthemer'),
						'<p>' . sprintf( esc_html__('%s was successfully deleted.', 'microthemer'), htmlentities($root_rel_path) ) . '</p>',
						'notice'
					);
					// update paths in json file
					$json_config_file = $this->micro_root_dir . $this->preferences['theme_in_focus'] . '/config.json';
					$this->replace_json_paths($json_config_file, array($root_rel_path => ''));
				} else {
					$this->log(
						esc_html__('File delete failed', 'microthemer'),
						'<p>' . sprintf( esc_html__('%s was not deleted.', 'microthemer'), htmlentities($root_rel_path) ) . '</p>'
					);
				}
			}


			// include manage file
			include $this->thisplugindir . 'includes/tvr-manage-single.php';

		}
	}

	// Preferences page
	function microthemer_preferences_page() {

		// only run code on preferences page
		if( $_GET['page'] == $this->preferencespage ) {

			if (!current_user_can('administrator')){
				wp_die('Access denied');
			}

			// this is a separate include because it needs to have separate page for changing gzip
			$page_context = $this->preferencespage;

			$ui_class = '';
			require_once($this->thisplugindir . 'includes/common-inline-assets.php');

			echo '
                    <div id="tvr" class="wrap tvr-wrap">
                        <span id="ajaxUrl" rel="' . $this->wp_ajax_url.'"></span>
                        <span id="returnUrl" rel="admin.php?page=' . $this->preferencespage.'"></span>
                        <div id="pref-standalone">
                            <div id="full-logs">
                                '.$this->display_log().'
                            </div>';
			include $this->thisplugindir . 'includes/tvr-microthemer-preferences.php';
			echo '
                        </div>';

			//$this->hidden_ajax_loaders();

			echo '
                    </div>';

		}
	}

	// Detached preview page
	function microthemer_detached_preview_page() {

		// only run code on preferences page
		if( $_GET['page'] == $this->detachedpreviewpage ) {

			if (!current_user_can('administrator')){
				wp_die('Access denied');
			}

			// this is a separate include because it needs to have separate page for changing gzip
			$page_context = $this->detachedpreviewpage;

			// set the css filters here so that favourites get updated regardless of display order of main filters
			// we just want the favourites (hover/page-id) to display in the context menu
			$css_filters =  $this->display_css_filters();

			$ui_class = '';
			require_once($this->thisplugindir . 'includes/common-inline-assets.php');

			echo '
                    <div id="tvr" class="wrap tvr-wrap '.$ui_class.'">
                        <span id="ajaxUrl" rel="'.$this->wp_ajax_url.'"></span>
                        <span id="returnUrl" rel="admin.php?page=' . $this->preferencespage.'"></span>
                        <div id="preview-standalone">';

			include $this->thisplugindir . 'includes/tvr-microthemer-preview-wrap.php';

			// Detach certain UI elements from the main window
			// and move them to to the detached when active
			echo '
                            <div id="mt-context-menu" 
                            class="detached-alt-controls detached-mt-context-menu" 
                            data-popupName="contextMenu">'.

			     // selector modifier options (must come before targeting options for fav filters)
			     $this->context_menu_content(array(
				     'base_key' => 'suggestions',
				     'title' => esc_html__('Targeting options', 'microthemer'),
				     'sections' => array(
					     $this->targeting_suggestions('menu')
				     )
			     )).

			     '</div>
                        
                 <div id="advanced-wizard" class="detached-alt-controls detached-advanced-wizard"></div>
                            
                 <div id="style-components"></div>';

                 echo '
                </div>
            </div>';

		}
	}

	/* add run if admin page condition...? */

	/***
	Generic Functions
	 ***/

	// get min/max media query screen size
	function get_screen_size($q, $minmax) {
		$pattern = "/$minmax-width:\s*([0-9.]+)\s*(px|em|rem)/";
		if (preg_match($pattern, $q, $matches)) {
			//echo print_r($matches);
			return $matches;
		} else {
			return 0;
		}
	}


	// show need help videos
	function need_help_notice() {
		if ($this->preferences['need_help'] == '1') {
			?>
			<p class='need-help'><b><?php esc_html_e('Need Help?', 'microthemer'); ?></b>
				<?php echo wp_kses(
					sprintf(
						__('Browse Our <span %s>Video Guides</span> and <span %s>Tutorials</span> or <span %s>Search Our Forum</span>', 'microthemer'),
						'class="help-trigger" rel="' . $this->thispluginurl.'includes/help-videos.php',
						'class="help-trigger" rel="' . $this->thispluginurl.'includes/tutorials.php',
						'class="help-trigger" rel="' . $this->thispluginurl.'includes/search-forum.php'
					),
					array( 'span' => array() )
				); ?></p>
			<?php
		}
	}

	/* Simple function to check for the browser
			For checking chrome faster notice and FF bug if $.browser is deprecated soon
			// may not be 100% reliable, initially only using to correct sub-pixel alignment of frontend context menu
			// in Chrome and Edge - haven't tested Safari yet
			http://php.net/manual/en/function.get-browser.php */
	function check_browser(){
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$ub = $u_agent; //'unknown-browser';

		//return $ub;
		if(preg_match('/(MSIE|Trident)/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
			$ub = "MSIE";
		}
		elseif(preg_match('/Opera/i',$u_agent) || preg_match('/OPR/',$u_agent)){
			$ub = "Opera";
		}
		elseif(preg_match('/Edg/i',$u_agent)){
			$ub = "Edge";
		}
		elseif(preg_match('/Firefox/i',$u_agent)){
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent)){
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent)){
			$ub = "Safari";
		}
		elseif(preg_match('/Netscape/i',$u_agent)){
			$ub = "Netscape";
		}
		return $ub;
	}

	// ie notice
	/*function ie_notice() {
				// display ie message unless disabled
				//global $is_IE;
				if ($this->preferences['ie_notice'] == '1' and $this->check_browser() != 'Chrome') {
					$this->log(
						esc_html__('Chrome Is Faster', 'microthemer'),
						'<p>' .
						sprintf(
							esc_html__('We\'ve noticed that Microthemer runs considerably faster in Chrome than other browsers. Actions like switching tabs, property groups, and accessing preferences are instant in Chrome but can incur a half second delay on other browsers. Speed improvements will be a major focus in our next phase of development. But for now, you can avoid these issues simply by using Microthemer with %s. Internet Explorer 9 and below isn\'t supported at all.', 'microthemer'),
							'<a target="_blank" href="http://www.google.com/intl/' . esc_attr_x('en-US', 'Chrome URL slug: https://www.google.com/intl/en-US/chrome/browser/welcome.html', 'microthemer') . '/chrome/browser/welcome.html">Google Chrome</a>'
						)
						. '</p><p>' .
						wp_kses(__('<b>Note</b>: Web browsers do not conflict with each other, you can install as many as you want on your computer at any one time. But if you love your current browser you can turn this message off on the preferences page.', 'microthemer'), array( 'b' => array() ))
						. '</p>',
						'warning'
					);
				}
			}*/



	/*// show server info
			function server_info() {
				global $wpdb;
				// get MySQL version
				$sql_version = $wpdb->get_var("SELECT VERSION() AS version");
				// evaluate PHP safe mode
				if(ini_get('safe_mode')) {
					$safe_mode = 'On';
				}
				else {
					$safe_mode = 'Off';
				}
				?>
				&nbsp;Operating System:<br />&nbsp;<b><?php echo PHP_OS; ?> (<?php echo (PHP_INT_SIZE * 8) ?> Bit)</b><br />

				&nbsp;MySQL Version:<br />&nbsp;<b><?php echo $sql_version; ?></b><br />
				&nbsp;PHP Version:<br />&nbsp;<b><?php echo PHP_VERSION; ?></b><br />
				&nbsp;PHP Safe Mode:<br />&nbsp;<b><?php echo $safe_mode; ?></b><br />
			<?php
			}
			*/

	// get all-devs and the MQS into a single simple array
	function combined_devices(){
		$comb_devs['all-devices'] = array(
			'label' => esc_html__('All Devices', 'microthemer'),
			'query' => esc_html__('CSS that applies to all devices', 'microthemer'),
			'min' => 0,
			'max' => 0
		);
		foreach ($this->preferences['m_queries'] as $key => $m_query) {
			$comb_devs[$key] = $m_query;
		}
		return $comb_devs;
	}





	// some versions of php don't like empty(trim($input)) so this is workaround
	function trimmedEmpty($input){

		if (empty($input)){
			return true;
		}

		$input = trim($input);

		if (empty($input)){
			return true;
		}

		return false;
	}


	// get micro-theme dir file structure
	/*function dir_loop_old($dir_name) {

				if (empty($this->file_structure)) {
					$this->file_structure = array();
				}

				// check for micro-themes folder, create if doesn't already exist
				if ( !is_dir($dir_name) ) {
					if ( !wp_mkdir_p($dir_name) ) {
						$this->log(
							esc_html__('/micro-themes folder error', 'microthemer'),
							'<p>' .
							sprintf(
								esc_html__('WordPress was not able to create the %s directory.', 'microthemer'),
								$this->root_rel($dir_name)
							) . $this->permissionshelp . '</p>'
						);
						return false;
					}
				}

				// loop over the directory
				if ($handle = opendir($dir_name)) {

					$count = 0;

					while (false !== ($file = readdir($handle))) {
						if ($file != '.' and $file != '..' and $file != '_debug') {
							$file = htmlentities($file); // just in case
							if ($this->is_acceptable($file) or !preg_match('/\./',$file)) {

								// if it's a directory
								if (!preg_match('/\./',$file) ) {
									$this->file_structure[$file] = array();
									$next_dir = $dir_name . $file . '/';
									// loop through the contents of the micro theme
									$this->dir_loop($next_dir);
								}

								// it's a normal file
								else {
									$just_dir = str_replace($this->micro_root_dir, '', $dir_name);
									$just_dir = str_replace('/', '', $just_dir);
									if ($this->is_screenshot($file)){
										$this->file_structure[$just_dir]['screenshot'] = $file;
									} else {
										$this->file_structure[$just_dir][$file] = $file;
									}

									++$count;
								}
							}
						}
					}
					closedir($handle);
				}

				if (is_array($this->file_structure)) {
					ksort($this->file_structure);
				}

				return $this->file_structure;
			}*/


	// display abs file path relative to the root dir (for notifications)
	function root_rel($path, $markup = true, $url = false, $actual_path = false) {

		// normalise \/ slashes
		$abspath_fs = untrailingslashit(str_replace('\\', '/', ABSPATH));
		$path = str_replace('\\', '/', $path);

		if ($markup == true) {
			$rel_path = '<b><i>/' . str_replace($abspath_fs, '', $path) . '</i></b>';
		}
		else {
			$rel_path = str_replace($abspath_fs, '', $path);
		}

		// root relative url (works on mixed ssl sites and if WP is in a subfolder of the doc root - getenv())
		if ($url){

			// WP is sometimes installed in a sub-dir, but pages are served as if from the root.
			// $this->home_url = root, $this->site_url = path to sub-dir
			// We normally want to strip $this->home_url, unless using root rel with ABSPATH (which incs subdir)
			// See https://premium.wpmudev.org/blog/install-wordpress-subdirectory/
			$path_to_strip = $actual_path ? $this->site_url : $this->home_url;

			// we're making an url FILE path root relative. The url WILL contain any subdir, so strip site_url stub
			if ($actual_path){

				// get the path from the www root to the website root. Often this will be the same.
				// But on localhost it might be e.g. /personal/themeover.com/wp-versions/really-fresh/
				// this happens when using sub-dirs without special case above.
				$script_name = getenv("SCRIPT_NAME");

				// $script_name could be be either admin-ajax.php (24) or admin.php (19), which will affect the offset
				$str_offset = strpos($script_name, 'admin-ajax.php') !== false ? -24 : -19;

				// we always strip whole site_url because $script_name will include any sub-dir, we don't want it twice
				$rel_path = substr($script_name, 0, $str_offset) . str_replace($this->site_url, '', $path);
			}

			// we're making an URL path root relative. The URL will NOT contain any subdir, so strip home_url stub
			else {
				$rel_path = str_replace($this->home_url, '', $path);
			}

			/*/*$script_name = getenv("SCRIPT_NAME");
					$rel_path = substr($script_name, 0, -(strlen($script_name))) . str_replace($this->site_url, '', $path);
					if (true){
						$this->show_me.= '(New) $script_name: '. $script_name.'<br />';
						$this->show_me.= '$path: '. $path.'<br />';
						$this->show_me.= 'str_replace($this->site_url, $path): '
							. str_replace($this->site_url, '', $path).'<br />';
						$this->show_me.= 'substr($script_name, 0, $str_offset) : '
							. substr($script_name, 0, $str_offset) .'<br />';
						$this->show_me.= '$this->site_url: '. $this->site_url.'<br />';
						$this->show_me.= '$this->home_url: '. $this->home_url.'<br />';
						$this->show_me.= '$rel_path: '. $rel_path.'<br />';
						$this->log(
							'active-styles debug',
							'<p>' . $this->show_me . '</p>'
						);
					}
					*/


		}

		return $rel_path;
	}

	// extract valid HTML attributes from a config array
	function array_to_html_atts($config){

		$atts_string = '';

		foreach ($config as $key => $value) {

			// to make adding atts and non-atts easier when calling function
			// compare atts against a white list (rather than atts =>)
			$attPattern = '/^(id|class|style|title|href|target|data-.+?|rel|preserveAspectRatio)$/';
			if (!is_null($value) && preg_match($attPattern, $key)){
				$atts_string.= $key . '="'.$value.'" ';
			}
		}

		return $atts_string;
	}

	// output an inline svg file, svg symbol, or a font icon
	function icon($name, $config = array()){

		// default icon type is "f" (font) unless svg or sym is set
		$type = !empty($config['type']) ? $config['type'] : 'f'; // for font
		$dir = isset($config['dir']) ? $config['dir'] : 'svg-min/';

		// base class for icon for settings global and type based defaults,
		// as well as the individual icon class
		//$base_class = 'mt-icon mt-'.$type.'-icon mt-icon-'.$name.' mt-'.$type.'-'.$name;
		$base_class = 'mti'.$type.' mti'.$type.'-'.$name;

		// merge base class with any custom classes
		$config['class'] = !empty($config['class'])
			? $base_class.' '.$config['class']
			: $base_class;

		// generate string of attributes
		$atts_string = $this->array_to_html_atts($config);

		// generate the HTML for the icon, svg, or symbol
		$icon = '';
		switch ($type) {

			// output an icon font or just the classes / atts
			case 'f':
				$tag = !empty($config['tag']) ? $config['tag'] : 'span';
				if (!empty($config['onlyClass'])){
					$icon = $config['class'];
				} else if (!empty($config['onlyAtts'])){
					$icon = $atts_string;
				} else {
					$innerHTML = !empty($config['innerHTML']) ? $config['innerHTML'] : '';
					$icon = '<'.$tag.' '.$atts_string.'>'.$innerHTML.'</'.$tag.'>';
				}
				break;

			// if symbol or svg
			case 'svg':
			case 'sym':

				// attempt to get svg file (silently fail if file not found)
				$icon = @file_get_contents(
					$this->thisplugindir . 'images/'.$dir.$name.'.svg'
				);

				if ($type === 'sym'){

					// if we successfully loaded the SVG file, copy its viewBox attribute
					// that will allow the symbol to scale proportionally like a regular svg
					// but keeps the HTML markup small for better dynamic HTML performance
					if ($icon){
						if (preg_match('/viewBox="[^"]+"/', $icon, $matches)){
							$atts_string.= ' '.$matches[0];
						}
					}

					$icon = '
                            <svg '.$atts_string.'>
                              <use href="#'.$name.'"></use>
                            </svg>';

				}

				// use full SVG file from file-system, adding in custom HTML attributes
				elseif ($type === 'svg'){
					$icon = preg_replace(
						'/<svg /',
						'<svg '.$atts_string,
						$icon,
						1
					);
				}

				break;
		}

		// maybe add some text alongside the icon
		if (!empty($config['adjacentText'])){
			$text_atts_string = $this->array_to_html_atts($config['adjacentText']);
			$icon.= '<span '.$text_atts_string.'>'.$config['adjacentText']['text'].'</span>';
		}

		// maybe wrap icon with a div
		if (isset($config['wrap'])){
			$wrap_atts_string = $this->array_to_html_atts($config['wrap']);
			//$wrapClass = !empty($config['wrapClass']) ? ' '.$config['wrapClass'] : '';
			$icon = '
	                <div '.$wrap_atts_string.'>'
			        . $icon .
			        '</div>';
		}

		return $icon;
	}

	function symbol($name, $config = array()){

		$config['type'] = 'sym';

		return $this->icon($name, $config);
	}

	function svg($name, $config = array()){

		$config['type'] = 'svg';

		return $this->icon($name, $config);
	}

	function iconFont($name, $config = array()){

		$config['type'] = 'f';

		return $this->icon($name, $config);
	}

	// get extension
	function get_extension($file) {
		$tmp = explode('?', $file);
		$file = $tmp[0];
		$ext = strtolower(substr($file, strrpos($file, '.') + 1));
		return $ext;
	}

	// use wp_remote_fopen with some validation checks
	function get_safe_url($uri, $config = array(), $msg_type = 'warning') {

		$r = array();

		// bail if not an URL
		if (!preg_match('/^(https?:)?\/\//i', $uri)){
			$this->log(
				esc_html__('Invalid URL', 'microthemer'),
				'<p>'.esc_html($uri).'</p>',
				'error'
			);
			return false;
		}

		// bail if not correct extension
		if (!empty($config['allowed_ext']) && !in_array($this->get_extension($uri), $config['allowed_ext'])) {
			$this->log(
				esc_html__('Disallowed file extension', 'microthemer') . ':' . $this->get_extension($uri),
				'<p>Please enter an URL with one of the following extensions: '.
				implode(', ', $config['allowed_ext']). '</p>',
				'error'
			);
			return false;
		}

		// check if file exists
		if (!$this->url_exists($uri)){
			return false;
		}

		// it seems ok so get contents of file into string
		if (!$r['content'] = wp_remote_fopen($uri)){
			$this->log(
				esc_html__('File is empty', 'microthemer'),
				'<p>'.esc_html($uri).'</p>',
				$msg_type
			);
			return false;
		}

		// do we need to save as file in tmp dir?
		if (!empty($config['tmp_file'])){
			$r['tmp_file'] = $this->thistmpdir . basename($uri);
			$this->write_file($r['tmp_file'], $r['content']);
		}

		return $r;
	}

	// check if an URL exists (WP can return 404 custom page giving illusion file exists)
	function url_exists($url) {
		$response = wp_remote_get( esc_url_raw($url) );
		// my half-done ssl on localhost fails here, so warn others
		if ( is_wp_error( $response ) ) {
			$str = '';
			foreach ($response->errors as $key => $err_arr){
				$str.= '<p>'.$key.': '.implode(', ', $err_arr).'</p>';
			}
			$this->log(
				esc_html__('Could not get file', 'microthemer'),
				'<p>'.esc_html($url). '</p>'
				.$str
			);
			return false;
		}
		if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
			$this->log(
				esc_html__('File does not exist', 'microthemer'),
				'<p>'.esc_html($url).'</p>'
			);
			return false;

		}
		return true;
	}

	// is english
	function is_en() {
		if ($this->locale == 'en_GB' or $this->locale == 'en_US') return true;
		return false;
	}

	// get first item in an associative array
	function get_first_item($array) {
		$item = false;
		foreach ($array as $key => $value){
			$item = $array[$key];
			break;
		}
		return $item;
	}

	// convert one array format to autocomplete with categories format
	function to_autocomplete_arr(
		$orig_array,
		$new_array = array(),
		$config = array()
	){
		foreach ($orig_array as $category => $array){
			foreach ($array as $i => $value){

				// array may be an array of arrays with the value as the key
				if (is_array($value)){
					$data = array_merge(array(
						'label' => $i,
						'category' => $category
					), $value);
				}

				// simple numeric array with single values
				else {
					$data = array(
						'label' => $value,
						'value' => $value,
						'category' => $category
					);
				}

				$new_array[] = $data;
			}
		}
		return $new_array;
	}

	function autocomplete_to_param_keys($autocomplete_array){

		$new_array = array();

		foreach ($autocomplete_array as $i => $array){
			$new_array[$this->to_param($array['label'])] = $array['label'];
		}

		return $new_array;
	}

	// WordPress normalises magic_quotes, if magic_quotes are enabled.
	// Even though deprecated: http://wordpress.stackexchange.com/questions/21693/wordpress-and-magic-quotes
	// Useful WP functions: stripslashes_deep() and add_magic_quotes() (both recursive)
	// $do is for easy dev experimenting.
	function stripslashes($val, $do = false){
		return $do ? stripslashes_deep($val): $val;
	}

	function addslashes($val, $do){
		return $do ? add_magic_quotes($val): $val;
	}

	function normalise_line_breaks($value, $trailing = "\n\n", $leading = ''){
		return $leading.trim($value, "\n\r").$trailing;
	}

	function normalise_tabs($string, $cur_tab_indent, $isSass = false){

		if (!$isSass){
			return $string;
		}

		$string = preg_replace("/(?<!^)(\n)/", "\n{$cur_tab_indent}\\2", $string);

		return str_replace("\t}", $cur_tab_indent."}", $string);

		/*// strip all leading white space
				$string = preg_replace("/^[ \t]+/m", "", $string);

				// replace line breaks inside with tab (not starting line break or final before })
				$string = preg_replace("/(?<!^)(\n)([^}])/", "\n{$cur_tab_indent}\t\\2", $string);

				// add back start tab and tab before }
				return str_replace("}", $cur_tab_indent."}", $string);*/

	}

	// WP magic_quotes hack doesn't escape \ properly, so this is my workaround
	function unescape_cus_slashes($val){
		return str_replace('&#92;', '\\', $val);
	}

	// Another workaround I came up with along time ago without fully understanding wp magic_quotes, but works too.
	function unescape_cus_quotes($val, $forAttr = false){

		$single = $forAttr ? '&#039;' : "'";
		$double = $forAttr ? '&quot;' : '"';

		$val = str_replace('cus-#039;', $single, $val);
		$val = str_replace('cus-quot;', $double, $val);

		return $val;
	}

	// Unescape slashes and cus quotes recursively
	function deep_unescape($array, $cus_quotes = false, $slashes = false, $cus_slashes = false){
		foreach ( (array) $array as $k => $v ) {
			if (is_array($v)) {
				$array[$k] = $this->deep_unescape($v, $cus_quotes, $slashes, $cus_slashes);
			} else {
				if ($cus_quotes and $k !== 'user_action'){ // user action had issue with quotes inside title attribute not being distinguishable from regualr quotes.
					$array[$k] = $this->unescape_cus_quotes($v);
				}
				if ($slashes){
					$array[$k] = stripslashes($array[$k]);
				}
				if ($cus_slashes){
					$array[$k] = $this->unescape_cus_slashes($array[$k]);
				}
			}
		}
		return $array;
	}

	// make server folder readable
	function readable_name($name) {
		$readable_name = str_replace('_', ' ', $name);
		$readable_name = ucwords(str_replace('-', ' ', $readable_name));
		return $readable_name;
	}

	// convert text to param (same as JS function)
	// todo this doesn't convert non-alpha to unicode like the convert_to_param JS function
	// doesn't seem to do anything to user data right now, so focussing on other things
	function to_param($str) {
		$str = str_replace(' ', '_', $str);
		$str = strtolower(preg_replace("/[^A-Za-z0-9_]/", '', $str));
		return $str;
	}

	// populate the default folders global array with translated strings
	function set_default_folders() {

		//$homePage = $this->homePageLogicLabel();

        $folders = array(
			'general' => __('General', 'microthemer'), // Auto-create General 2, 3 etc when +25 selectors
			'header' => __('Header', 'microthemer'),
			'main_menu' => __('Main Menu', 'microthemer'),
			'content' => __('Content', 'microthemer'),
			'sidebar' => __('Sidebar', 'microthemer'),
			'footer' => __('Footer', 'microthemer'),
			//'home' => $homePage['title'],

		);

		$initial_index = 0;
		foreach ($folders as $en_slug => $label){

            $translated_slug = $this->to_param($label);

			$this->default_folders[$translated_slug]['this'] = array(
				'label' => $label,
				'index' => $initial_index
			);

			$initial_index+= 100;

            // We now have automatic page-specific folders by default,
            // so include a page-specific folder for the home page, which MT defaults to
            // this will allow new conditional folders for each new page - also need a placeholder selector
            /*if ($en_slug === 'home'){

                // add logic for home page
                $this->default_folders[$translated_slug]['this']['logic'] = array(
                    'expr' => $homePage['logic']
                );

                // add placeholder selector
                $exampleSelLabel = __('Heading 1', 'microthemer');
	            $exampleSelSlug = $this->to_param($exampleSelLabel);
	            $this->default_folders[$translated_slug][$exampleSelSlug] = array(
                        'label' => $exampleSelLabel.'|h1',
                );
            }*/

		}

		// add non_section stuff that would get set by JS otherwise, causing a save request
		// which bumps up num_saves and produces the wrong initial notification and publish button state
		$this->default_folders['non_section'] = array(
			'meta' => array(),
			'active_events' => '',
		);
	}

	// check if the file is an image
	function is_image($file) {
		$ext = $this->get_extension($file);
		if ($ext == 'jpg' or
		    $ext == 'jpeg' or
		    $ext == 'png' or
		    $ext == 'gif'
		) {
			return true;
		}
		else {
			return false;
		}
	}

	// check if it's a screenshot image
	function is_screenshot($file) {
		if(strpos($file, 'screenshot.', 0) !== false) {
			return true;
		}
		else {
			return false;
		}
	}

	// check a multidimentional array for a value
	function in_2dim_array($str, $array, $target_key){
		foreach ($array as $key => $arr2) {
			if ($arr2[$target_key] == $str) {
				return $key;
			}
		}
		return false;
	}

	//check if the file is acceptable
	function is_acceptable($file, $ext = false) {
		$ext = $ext ? $ext : $this->get_extension($file);
		if ($ext == 'jpg' or
		    $ext == 'jpeg' or
		    $ext == 'png' or
		    $ext == 'gif' or
		    $ext == 'txt' or
		    $ext == 'json' or
		    $ext == 'sass' or
		    $ext == 'scss' or
		    $ext == 'css' or
		    $ext == 'psd' or
		    $ext == 'ai'
		) {
			return true;
		}
		else {
			return false;
		}
	}


	// get list of acceptable file types
	function get_acceptable() {
		$acceptable = array (
			'jpg',
			'jpeg',
			'png',
			'gif',
			'txt',
			'json',
			'sass',
			'scss',
			'css',
			'psd',
			'ai');
		return $acceptable;
	}

	// rename dir if dir with same name exists in same location
	function rename_if_required($dir_path, $name) {
		if ( is_dir($dir_path . $name ) ) {
			$suffix = 1;
			do {
				$alt_name  = substr ($name, 0, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
				$dir_check = is_dir($dir_path . $alt_name);
				$suffix++;
			} while ( $dir_check );
			return $alt_name;
		}
		return false;
	}

	// rename the to-be-merged section
	function get_alt_section_name($section_name, $orig_settings, $new_settings) {
		$suffix = 2;
		do {
			$alt_name = substr ($section_name, 0, 200 - ( strlen( $suffix ) + 1 ) ) . "_$suffix";
			$context = 'alt-check';
			$conflict = $this->is_name_conflict($alt_name, $orig_settings, $new_settings, $context);
			$suffix++;
		} while ( $conflict );
		// also need to have index value by itself so return array.
		$alt = array(
			'name' => $alt_name,
			'index' => $suffix-1
		);
		return $alt;
		//return $alt_name;
	}

	// check if the section name exists in the orig_settings or the new_settings (possible after name modification)
	function is_name_conflict($alt_name, $orig_settings, $new_settings, $context='') {
		if ( ( isset($orig_settings[$alt_name]) // conflicts with orig settings or
		       or ($context == 'alt-check' and isset($new_settings[$alt_name]) )) // conflicts with new settings (and is an alt name)
		     and $alt_name != 'non_section' // and is a section
		) {
			return true; // conflict
		}
		else {
			return false; // no name conflict
		}
	}

	/***
	Microthemer UI Functions
	 ***/

	// ui dialog html (start)
	function start_dialog($id, $heading, $class = '', $tabs = array() ) {
		$content_class = '';
		// set dialog specific classes
		if ($id != 'manage-design-packs' and $id != 'program-docs' and $id != 'display-css-code'){
			$content_class.= 'scrollable-area';
		}
		if ($id == 'display-css-code' or $id == 'import-from-pack'){
			$content_class.= ' tvr-editor-area';
		}

		if ( !empty( $tabs ) ) {
			$class.= ' has-mt-tabs';
		}

		$icons = array( // todo
			'mt-initial-setup' => 'box-open',
			'unlock-microthemer' => 'unlock-alt',
			'display-preferences' => 'cog',
			'edit-media-queries' => 'devices',
			'mt-enqueue-js' => 'js',
			'inspect-stylesheet' => 'view-code',
			'import-from-pack' => 'import',
			'export-to-pack' => 'export',
			'display-css-code' => 'view-code',
			'display-revisions' => 'undo',
			'manage-design-packs' => 'manage-packs',
			'google-fonts' => 'google-font',
			'program-docs' => 'docs',
			'view-pack-file' => 'view-code'
		);

		$html = '<div id="'.$id.'-dialog" class="tvr-dialog '.$class.'">
				<div class="dialog-main">
					<div class="dialog-inner">
						<div class="dialog-header">
						    '.$this->iconFont($icons[$id], array(
				'class' => 'dialog-icon'
			)).'
							<span class="text">'.$heading.'</span>
							<span class="dialog-status"></span>
							 '.$this->iconFont('times-circle-regular', array(
				'class' => 'close-dialog'
			)).'
						</div>';

		// If there are any tabs, the content is preceded by a tab menu
		if ( !empty( $tabs ) ) {

			$dialog_tab_param = str_replace('-', '_', $id);

			$active_tab = (!empty($this->preferences['generated_css_focus']) && $dialog_tab_param === 'display_css_code')
				? $this->preferences['generated_css_focus']
				: '0';

			$html.='
					<div class="dialog-tabs query-tabs">
						<input class="dialog-focus" type="hidden"
						name="tvr_mcth[non_section]['.$dialog_tab_param.']"
						value="'.$active_tab.'" />';

			// maybe add functionality to remember pref tab at a later date.
			for ($i = 0; $i < count($tabs); $i++) {
				$tab_name = $tabs[$i];
				$tab_class = strtolower(str_replace(' ', '-', $tab_name));
				/*$mode = $tab_class;

				if ($mode === 'previous-scss-compile'){
					$mode = 'scss';
				}

				$mode = str_replace('-(min)', '', $mode);

				data-mode="'.$mode.'"
				*/

				$html .= '
							<span class="' . ($i == $active_tab ? 'active' : '' )
				         . ' mt-tab dialog-tab dialog-tab-'.$i.' dialog-tab-'.$tab_class.'" rel="'.$i.'">
								' . $tab_name . '
							</span>';
			}
			$html .= '
					</div>';
		}
		$html .= '<div class="dialog-content '.$content_class.'">';
		return $html;
	}

	function dialog_button($button_text, $type, $class, $title = ''){
		$tAttr = $title ? 'title="'.$title.'"' : '';
		if ($type == 'span'){
			$button = '<span class="tvr-button dialog-button '.$class.'" '.$tAttr.'>'.$button_text.'</span>';
		} else {
			$button = '<input name="tvr_'.strtolower(str_replace('-', '_', $class)).'_submit"
					type="submit" value="'. esc_attr($button_text) .'"
					class="tvr-button dialog-button" '.$tAttr.' />';
		}

		return $button;
	}

	// ui dialog html (end)
	function end_dialog($button_text, $type = 'span', $class = '', $title = '') {
		$button = $this->dialog_button($button_text, $type, $class, $title);
		$html = '

							</div>
							<div class="dialog-footer">
							'.$this->settings_menu('dialog-footer'). $button. '
							</div>
						</div>
					</div>
				</div>';
		return $html;
	}

	// output clear icon for section, selector, tab, or pg
	function clear_icon($level, $extra = false){

		$title = esc_attr__('Clear', 'microthemer') .  ' ' . $this->level_map[$level];

		$data_att = $extra ? 'data-'.$extra['key'].'="'.$extra['value'].'"' : '';

		return '<span class="'.$this->iconFont('eraser', array('onlyClass' => 1)).' clear-icon" title="'.$title.'" data-input-level="'.$level.'" '.$data_att.'></span>';
	}



	function extra_actions_icon($id = false){

		return $this->ui_toggle(
			'show_extra_actions',
			!$id ? 'conditional' : esc_attr__('Show more actions', 'microthemer'),
			!$id ? 'conditional' : esc_attr__('Show less actions', 'microthemer'),
			!$id ? false : $this->preferences['show_extra_actions'],
			'extra-actions-toggle',
			$id,
			array(
				'dataAtts' => array(
					//'no-save' => 1,
					'dyn-tt-root' => $id ? false : 'show_extra_actions'
				)
			)
		);
	}

	// hover inspect button
	function hover_inspect_button($id = false, $text = false){

		$tip = esc_attr__("(Ctrl+Alt+T)", 'microthemer');

		return $this->ui_toggle(
			'hover_inspect',
			!$id ? 'conditional' : esc_attr__('Target page', 'microthemer')." ".$tip,
			!$id ? 'conditional' : esc_attr__('View page', 'microthemer')." ".$tip,
			!$id ? false : $this->preferences['hover_inspect'],
			'hover-inspect-toggle',
			$id,
			array(
				//'text' => $text ? $text : esc_html__('Target', 'microthemer'),
				//'text' => 'conditional', // this proved tricky to maintain
				// - see css .hover-inspect-toggle position:fixed hack.
				'dataAtts' => array(
					'no-save' => 1,
					'dyn-tt-root' => $id ? false : 'hover-inspect-toggle',
				),
				'innerHTML' => '
                            <span class="mt-ui-toggle mt-icon-switch mt-icon-switch-on edit-mode-icon"></span>
                            <span class="mt-ui-toggle mt-icon-switch mt-icon-switch-off view-mode-icon"></span>
                        '
			)
		);
	}

	// alias for switching to code view ($id will always be false come to think of it)
	function code_view_icon($id = false){

		return $this->ui_toggle(
			'show_code_editor',
			!$id ? 'conditional' : esc_attr__('Code view', 'microthemer'),
			!$id ? 'conditional' : esc_attr__('GUI view', 'microthemer'),
			!$id ? false : $this->preferences['show_code_editor'],
			'toggle-full-code-editor',
			$id,
			array(
				'text' => esc_html__('Code', 'microthemer'),
				'dataAtts' => array(
					'dyn-tt-root' => $id ? false : 'toggle-full-code-editor',


					// would need to dynamically update the aliases text if using this
					//'text-pos' => esc_html__('Code', 'microthemer'),
					//'text-neg' => esc_html__('GUI', 'microthemer')
				)
			)
		);
	}

	/*function manual_resize_icon(){
				return $this->ui_toggle(
					'code_manual_resize',
					'conditional',
					'conditional',
					false,
					'code-manual-resize',
					false,
					// instruct tooltip to get content dynamically
					array('dataAtts' => array(
						'dyn-tt-root' => 'code_manual_resize',
						//'editor-type'=> $editorType
					))
				);
			}*/

	// output feather icon for section, selector, tab, or pg
	function feather_icon($level){
		return '<span class="feather-icon '.$level.'-feather" data-input-level="'.$level.'"></span>';
	}

	// output icon for toggling full interface feature e.g. dock right/bottom
	function ui_toggle($aspect, $pos, $neg, $on, $class, $id = false, $config = array()){

		if ($on){
			$title = $neg;
			$class.= ' on';
		} else {
			$title = $pos;
		}

		$id = $id ? 'id="'.$id.'"' : '';

		// determine tagname
		$tag = !empty($config['tag']) ? $config['tag'] : 'span';

		// css_filter needs to pass
		$pref_sub_key = !empty($config['pref_sub_key']) ? 'data-pref_sub_key="'.$config['pref_sub_key'].'"' : '';

		// e.g. css modifier has a checkbox
		$inner_icon = !empty($config['inner_icon']) ? $config['inner_icon'] : '';

		// output arbitrary data atts
		$dataAtts = '';
		if (!empty($config['dataAtts'])){
			foreach ($config['dataAtts'] as $dat => $dat_val){

				if ($dat_val !== ''){
					$dataAtts.= 'data-'.$dat.'="'.$dat_val.'" ';
				}
			}
		}

		// Note: uit-par may need to be var
		$html = '
				<'.$tag.' '.$id.' class="mt-ui-toggle uit-par '.$class.'" title="'.$title.'"
					  data-pos="'.$pos.'"
					  data-neg="'.$neg.'"
					  '.$dataAtts.'
					  '.$pref_sub_key.'
					  data-aspect="'.$aspect.'">';

		// add text if not an icon
		if (!empty($config['text'])){

			$text = $config['text'];

			// show/hide advanced wizard options uses conditional text, as would most text toggles
			if ($text === 'conditional'){
				$text = $on ? $config['dataAtts']['text-neg'] : $config['dataAtts']['text-pos'];
			}

			// check if an input needs to be added
			if (!empty($config['css_filter']['editable'])){

				$ed = $config['css_filter']['editable'];
				$rel = !empty($ed['combo']) ? 'rel="'.$ed['combo'].'"' : '';
				$combo = '<span class="tvr-input-wrap">'.
				         '<input type="text" class="combobox cssfilter-combo" name="'.$ed['str'].'" '.$rel.'
						value="'.esc_attr(trim($ed['str'], "()")).'" />
						</span>';

				// if it's an input value - for e.g. custom prefix just append
				if (!empty($config['css_filter']['editable']['justInputValue'])){
					$text.= $combo;
				}

				// the replace str has brackets to ensure we get the right (n) in e.g. nth-child(n)
				else {
					$text = '<span class="pre-edfil mt-ui-toggle">' .
					        str_replace($ed['str'], '(</span>'.$combo.'<span class="post-edfil mt-ui-toggle">)</span>', $text);
				}

			}



			$html.= $inner_icon . $text;
		}

		// some custom HTML in between
		if (!empty($config['innerHTML'])){
			$html.= $config['innerHTML'];
		}

		$html.= '</'.$tag.'>';
		return $html;
	}

	// feather, chain, important, pie, disable icons
	function icon_control(
		$justInput,
		$con,
		$on,
		$level,
		$section_name = '',
		$css_selector = '',
		$key = '',
		$group = '',
		$subgroup = '',
		$prop = '',
		$mq_stem = 'tvr_mcth',
		$tabGroup = ''){

		// common atts
		$icon_id = '';
		$input = '';
		$tracker_class = $con.'-tracker';
		$icon_class = $con.'-toggle input-icon-toggle';
		$icon_inside = '';
		$data_atts_arr = array();
		$pos_title = $neg_title = '';


		// set MQ stub for tab and pg inputs
		$imp_key = '';
		if ($level == 'tab-input' or $level == 'subgroup' or $level == 'property'){
			if ($mq_stem == 'tvr_mcth' and $key != 'all-devices'){
				$mq_stem.= '[non_section][m_query]['.$key.']';
				$imp_key = '[m_query]['.$key.']';
			}
		}

		// icon specific
		if ($con == 'disabled'){
			$icon_class.= ' '.$this->iconFont('disable', array('onlyClass' => 1)).' '.$level.'-disabled';
			$pos_title = esc_attr__('Disable', 'microthemer') .  ' ' . $this->level_map[$level];
			$neg_title = esc_attr__('Enable', 'microthemer') .  ' ' . $this->level_map[$level];
			if ($level === 'pgtab'){
				$data_atts_arr['tab-group'] = $tabGroup;
			}
		} elseif ($con == 'chained') {
			$icon_class.= ' '.$this->iconFont('chain', array('onlyClass' => 1)).' '.$subgroup.'-chained';
			$pos_title = esc_attr__('Link fields', 'microthemer');
			$neg_title = esc_attr__('Unlink fields', 'microthemer');
		} elseif ($con == 'important') {
			$pos_title = esc_attr__('Add !important', 'microthemer');
			$neg_title = esc_attr__('Remove !important', 'microthemer');
			$icon_inside = '';
			$icon_class.= ' ' . $this->iconFont('warning-square', array('onlyClass' => 1));
		}

		elseif ($con == 'flexitem' || $con == 'griditem') {
			$icon_class.= ' dynamic-fields-toggle';
			$pos_title = esc_attr__('Show item fields', 'microthemer');
			$neg_title = esc_attr__('Show container fields', 'microthemer');
			// default icon text is 'container' overridden below if toggle is on
			//$icon_inside = esc_html__('Container', 'microthemer');
			// if flex item/container toggle
			$data_atts_arr['text-pos'] = esc_attr__('Item', 'microthemer');
			$data_atts_arr['text-neg'] = esc_attr__('Container', 'microthemer');

		} elseif ($con == 'gradient') {
			$icon_class.= ' dynamic-fields-toggle';
			$pos_title = esc_attr__('Show gradient fields', 'microthemer');
			$neg_title = esc_attr__('Show background-image fields', 'microthemer');
		}

		// generate input if item is on
		$title = $pos_title; // do what toggle is there for
		if (!empty($on)){
			$title = $neg_title; // undo what toggle is there for
			switch ($level) {
				case "section":
					$name = $mq_stem . '['.$section_name.'][this]';
					break;
				case "selector":
					$name = $mq_stem . '['.$section_name.']['.$css_selector.']';
					break;
				case "tab-input":
					$tracker_class.= '-'.$key;
					$name = $mq_stem . '['.$section_name.']['.$css_selector.'][tab]';
					break;
				case "group":
					$name = $mq_stem . '['.$section_name.']['.$css_selector.'][pg_'.$con.']';
					break;
				case "pgtab":
					$name = $mq_stem . '['.$section_name.']['.$css_selector.'][pgtab_'.$con.']['.$tabGroup.']';
					break;
				case "subgroup":
					$name = $mq_stem . '['.$section_name.']['.$css_selector.'][pg_'.$con.']['.$subgroup.']';
					break;
				case "property":
					$name = $mq_stem . '['.$section_name.']['.$css_selector.'][styles]['.$group.']['.$prop.']';
					break;
				case "script":
					$name = 'tvr_preferences[enq_js]['.$section_name.']';
					break;
				default:
					$name = '';
			}
			$name.= '['.$con.']';

			// important is only for props, and has different structure
			if ($con == 'important'){
				$name = 'tvr_mcth[non_section][important]'.$imp_key.'['.$section_name.']['.$css_selector.']['.$group.']['.$prop.']';
			}

			$input = '<input class="input-toggle-tracker '.$tracker_class.'" type="hidden" name="'.$name.'" value="1" />';
			$icon_class.= ' active';
		}

		// output arbitrary data atts
		$dataAtts = '';
		//$test = 'yes' . implode($data_atts_arr);
		if (!empty($data_atts_arr)){
			foreach ($data_atts_arr as $dat => $dat_val){
				$dataAtts.= 'data-'.$dat.'="'.$dat_val.'" ';
			}
			//$test = 'person';
			;				}

		// generate icon
		$icon = '<span '.$icon_id.' class="'.$icon_class.'" title="'.$title.'" data-pos="'.$pos_title.'"
				data-neg="'.$neg_title.'"  data-input-type="'.$con.'" '.$dataAtts.' 
							data-input-level="'.$level.'">'.$icon_inside.'</span>';

		// with feather on tabs, icon and input are output separately
		if ($con == 'disabled'){
			if ($level == 'tab'){
				$input = '';
			} elseif ($level == 'tab-input'){
				$icon = '';
			}
		}

		// with important, a placeholder is used for css3 options that only need one 'i'
		if (!empty($this->propertyoptions[$group][$prop]['hide imp'])) {
			$icon = '<span class="imp-placeholder">i</span>';
		}

		// return control
		if ($justInput){
			return $input;
		}

		return $input . $icon;
	}

	// output header
	function manage_packs_header($page){
		?>
		<ul class="pack-manage-options">
			<li class="upload">
				<form name='upload_micro_form' id="upload-micro-form" method="post" enctype="multipart/form-data"
				      action="<?php echo 'admin.php?page='. $page;?>" >
					<?php wp_nonce_field('tvr_upload_micro_submit'); ?>
					<input id="upload_pack_input" type="file" name="upload_micro" />
					<input class="tvr-button upload-pack" type="submit" name="tvr_upload_micro_submit"
					       value="<?php esc_attr_e('Upload design pack', 'microthemer'); ?>" title="<?php esc_attr_e('Upload a new design pack', 'microthemer'); ?>" />
				</form>
			</li>
			<!--<li>
						<a class="tvr-button" target="_blank" title="Submit one of your design packs for sale/downlaod on themeover.com"
							href="https://themeover.com/sell-micro-themes/submit-micro-theme/">Submit To Marketplace</a>
					</li>
					<li>
						<a class="tvr-button" target="_blank" title="Browse Themeover's marketplace of design packs for various WordPress themes and plugins"
							href="http://themeover.com/theme-packs/">Browse Marketplace</a>
					</li>-->
		</ul>
		<?php
	}

	function get_design_packs($packs){
		$count = 0;
		$valid_packs = array();
		$exclude = array('sass', 'scss');
		foreach($packs as $name => $item){
			if (is_array($item) && !in_array($name, $exclude)){
				++$count;
				$valid_packs[$name] = $item;
			}
		}
		return array(
			'count' => $count,
			'directories' => $valid_packs
		);
	}

	// output meta spans and logs tmpl for manage pages // todo -  use JS object rather than spans
	function manage_packs_meta(){
		?>
		<span id="ajaxUrl" rel="<?php echo $this->wp_ajax_url; ?>"></span>
		<span id="delete-ok" rel='admin.php?page=<?php echo $this->microthemespage;?>&mt_action=tvr_delete_ok&_wpnonce=<?php echo wp_create_nonce('tvr_delete_ok'); ?>'></span>
		<span id="zip-folder" rel="<?php echo $this->thispluginurl.'zip-exports/'; ?>"></span>
		<?php

		//echo $this->display_log_item('error', array('short'=> '', 'long'=> ''), 0, 'id="log-item-template"');
	}

	function pack_pagination($page, $total_pages, $total_packs, $start, $end) {
		?>
		<ul class="tvr-pagination">
			<?php
			$i = $total_pages;
			while ($i >= 1){
				echo '
						<li class="page-item">';
				if ($i == $page) {
					echo '<span>'.$i.'</span>';
				} else {
					echo '<a href="admin.php?page='. $this->microthemespage . '&packs_page='.$i.'">'.$i.'</a>';
				}
				echo '
						</li>';
				--$i;
			}

			if ($end < 1) {
				$start = 0;
			}

			echo '<li class="displaying-x">' .
			     sprintf(esc_html__('Displaying %s - %s of %s', 'microthemer'), $start, $end, $total_packs) . '</li>';

			if (!empty($this->preferences['theme_in_focus']) and $total_packs > 0){
				$url = 'admin.php?page=' . $this->managesinglepage . '&design_pack=' . $this->preferences['theme_in_focus'];
				$name = $this->readable_name($this->preferences['theme_in_focus']);
				?>
				<li class="last-modified" rel="<?php echo $this->preferences['theme_in_focus']; ?>">
					<?php esc_html_e('Last modified design pack: ', 'microthemer'); ?><a title="<?php printf(esc_attr__('Edit %s', 'microthemer'), $name); ?>"
					                                                                     href="<?php echo $url; ?>"><?php echo esc_html($name); ?>
					</a>
				</li>
				<?php
			}
			?>
		</ul>
		<?php
	}

	/*
			function display_left_menu_icons() {

				if ($this->preferences['buyer_validated']){
					$unlock_class = 'unlocked';
					$unlock_title = esc_attr__('Validate license using a different email address', 'microthemer');
				} else {
					$unlock_class = '';
					$unlock_title = esc_attr__('Enter your PayPal email (or the email listed in My Downloads) to unlock Microthemer', 'microthemer');
				}

				// set 'on' buttons
				$code_editor_class = $this->preferences['show_code_editor'] ? ' on' : '';
				$ruler_class = $this->preferences['show_rulers'] ? ' on' : '';


				//
				$html = '
					<div class="unlock-microthemer '.$unlock_class.' v-left-button show-dialog popup-show" rel="unlock-microthemer" title="'.$unlock_title.'"></div>

					<div id="save-interface" class="save-interface v-left-button" title="' . esc_attr__('Manually save UI settings (Ctrl+S)', 'microthemer') . '"></div>

					<div id="toggle-highlighting" class="v-left-button"
					title="'. esc_attr__('Toggle highlighting', 'microthemer') .'"></div>

					<div id="toggle-rulers" class="toggle-rulers v-left-button '.$ruler_class.'"
						title="'. esc_attr__('Toggle rulers on/off', 'microthemer') .'"></div>

					<div class="ruler-tools v-left-button tvr-popright-wrap">

						<div class="tvr-popright">
							<div class="popright-sub">
								<div id="remove-guides" class="remove-guides v-left-button"
						title="'. esc_attr__('Remove all guides', 'microthemer') .'"></div>
							</div>
						</div>
					</div>


					<div class="code-tools v-left-button tvr-popright-wrap popup-show">

						<div id="edit-css-code" class="edit-css-code v-left-button new-icon-group '.$code_editor_class.'"
						title="'. esc_attr__('Code editor view', 'microthemer') .'"></div>

						<div class="tvr-popright">
							<div class="popright-sub">
								<div id="display-css-code" class="display-css-code v-left-button show-dialog popup-show" rel="display-css-code" title="' . esc_attr__('View the CSS code Microthemer generates', 'microthemer') . '"></div>
							</div>
						</div>
					</div>


					<div class="preferences-tools v-left-button tvr-popright-wrap popup-show">

						<div class="display-preferences v-left-button show-dialog popup-show" rel="display-preferences" title="' . esc_attr__('Set your global Microthemer preferences', 'microthemer') . '"></div>

						<div class="tvr-popright">
							<div class="popright-sub">

								<div class="edit-media-queries v-left-button show-dialog popup-show" rel="edit-media-queries"
					title="' . esc_attr__('Edit Media Queries', 'microthemer') . '"></div>

							</div>
						</div>
					</div>


					<div class="pack-tools v-left-button tvr-popright-wrap popup-show">

						<div class="manage-design-packs v-left-button show-dialog new-icon-group popup-show" rel="manage-design-packs" title="' . esc_attr__('Install & Manage your design packages', 'microthemer') . '"></div>

						<div class="tvr-popright">
							<div class="popright-sub">

								<div class="import-from-pack v-left-button show-dialog popup-show" rel="import-from-pack" title="' . esc_attr__('Import settings from a design pack', 'microthemer') . '"></div>

					<div class="export-to-pack v-left-button show-dialog popup-show" rel="export-to-pack" title="' . esc_attr__('Export your work as a design pack', 'microthemer') . '"></div>

							</div>
						</div>
					</div>


					<!--<div class="display-share v-left-button show-dialog" rel="display-share" title="' . esc_attr__('Spread the word about Microthemer', 'microthemer') . '"></div>-->




					<div class="reset-tools v-left-button tvr-popright-wrap popup-show">

						<div class="display-revisions v-left-button show-dialog new-icon-group popup-show" rel="display-revisions" title="' . esc_attr__("Restore settings from a previous save point", 'microthemer') . '"></div>

						<div class="tvr-popright">
							<div class="popright-sub">
								<div id="ui-reset" class="v-left-button folder-reset"
								title="' . esc_attr__("Reset the interface to the default empty folders", 'microthemer') . '"></div>
								<div id="clear-styles" class="v-left-button styles-reset"
								title="' . esc_attr__("Clear all styles, but leave folders and selectors intact", 'microthemer') . '"></div>
							</div>
						</div>
					</div>


					<div data-docs-index="1" class="program-docs v-left-button show-dialog new-icon-group popup-show" rel="program-docs"
					title="' . esc_attr__("Learn how to use Microthemer", 'microthemer') . '"></div>

					<div class="toggle-full-screen v-left-button" rel="toggle-full-screen"
					title="' . esc_attr__("Full screen mode", 'microthemer') . '"></div>

					<a class="back-to-wordpress v-left-button" title="' . esc_attr__("Return to WordPress dashboard", 'microthemer') . '"
					href="'.$this->wp_blog_admin_url.'"></a>';
				return $html;

			}
			*/


	// display the main menu
	function settings_menu($subset = false){

		//$html = '<ul class="mt-options-tabs">';
		$html = '';
		$dialog_footer_html = '<div class="dialog-footer-menu">';

		// menu groups
		foreach ($this->menu as $group_key => $arr){

			// e.g. the exit menu is added to the bottom row with the breadcrumbs
			if (isset($arr['custom_insert'])){
				continue;
			}

			$panel_set = $this->menu_panel_set(false, $group_key, $arr, $subset, $html, $dialog_footer_html);
			$html = $panel_set['main'];
			$dialog_footer_html = $panel_set['footer'];
		}

		//$html.= '</ul>';
		$dialog_footer_html.= '</div>';

		switch ($subset){
			case false:
				return $html;
			case 'dialog-footer':
				return $dialog_footer_html;
		}

		//return $html;
	}

	function menu_panel_set($is_nested, $group_key, $arr, $subset, $html, $dialog_footer_html){

		//$tabs_html = '<div class="mt-options-tabs">';
		$group_li = '';
		$areas_html = '';
		$is_expanded = in_array($group_key, $this->preferences['layout']['right']['expanded_settings'] );
		$field_class = '';
		//$this->preferences['program_settings_tab'] === $group_key;

		// do we have an icon for the set?
		$group_icon = '';
		/*if (!empty($arr['icon_class'])){
					$group_icon.= '<span class="mt-menu-group-icon '.$arr['icon_class'].'"></span>';
				}*/

		// should a link wrap the icon and text?
		$link_start = $link_end = $tab_class = '';
		if (!empty($arr['item_link'])){
			$link_id = !empty($arr['link_id']) ? 'id="'.$arr['link_id'].'"' : '';
			$link_target = !empty($arr['link_target']) ? 'target="'.$arr['link_target'].'"' : '';
			$link_start = '<a class="item-link" '.$link_id.' '.$link_target.' href="'.$arr['item_link'].'">';
			$link_end = '</a>';
			$tab_class = ' direct-action';
		}

		if ($is_expanded){
			$tab_class.= ' expanded';
		}

		if ($is_nested){
			$tab_class.= ' nested-panel-heading';
			$field_class.= ' nested-panel';
		}

		//  $group_icon .
		$group_li.= '
                <div class="mt-expandable-heading mt-'.$group_key . '-heading'. $tab_class.'" rel="'.$group_key.'">
                   '. $link_start . $arr['name'] . $link_end . ' 
                </div>';

		if (!empty($arr['sub'])){
			$panel_area = $this->menu_panel_sub(
				$group_key, $arr, $subset, $areas_html, $field_class, $dialog_footer_html
			);
			$areas_html = $panel_area['areas_html'];
			$dialog_footer_html = $panel_area['footer'];
		}

		// full menu set
		$html.= $group_li . $areas_html;

		return array(
			'main' => $html,
			'footer' => $dialog_footer_html
		);

	}

	function menu_panel_sub($group_key, $arr, $subset, $areas_html = '', $field_class = '', $dialog_footer_html = ''){

		if ($subset){
			$field_class.= ' panel-subset-'.$subset;
		}

		$areas_html.= '<ul class="mt-expandable-panel mt-'.$group_key . '-panel ' . $field_class.'">';

		// menu items
		foreach ($arr['sub'] as $item_key => $arr2){

			// dialog footer only needs a subset of options
			if ($subset === 'dialog-footer' and empty($arr2['dialog'])) continue;

			// support nested panels (which we're not currently indenting)
			if (!empty($arr2['nested'])){
				$panel_set = $this->menu_panel_set(
					true, $item_key, $arr2['nested'], $subset, $areas_html, $dialog_footer_html
				);
				$areas_html = $panel_set['main'];
				//$dialog_footer_html = $panel_set['footer'];
				continue;
			}

			// format the data attributes
			$data_attr = '';
			if ( !empty($arr2['data_attr']) ){
				foreach($arr2['data_attr'] as $da_key => $da_value){
					$data_attr.= 'data-'.$da_key.'="'.$da_value.'"';
				}
			}

			$common_class = !empty($arr2['class']) ? $arr2['class'] : '';
			$show_dialog_class = (!empty($arr2['dialog'])) ? ' show-dialog' : '';
			$class = 'item-' . $common_class;
			$class.= (isset($arr2['toggle'])) ? ' mt-toggle' : '';
			$class.= (isset($arr2['item_link'])) ? ' item-link' : '';
			//$class.= (!empty($arr2['new_set'])) ? ' new-set' : '';
			$class.= $show_dialog_class;

			// format rel, class, id, data
			$rel = !empty($arr2['dialog']) ? 'rel="'.$common_class.'"' : '';
			$id = !empty($arr2['id']) ? 'id="'.$arr2['id'].'"' : '';
			$link_id = !empty($arr2['link_id']) ? 'id="'.$arr2['link_id'].'"' : '';
			$link_target = !empty($arr2['link_target']) ? 'target="'.$arr2['link_target'].'"' : '';


			// format icon atts
			$item_icon = !empty($arr2['icon_name'])
				? $this->iconFont($arr2['icon_name'], array(
					'class' => 'mt-menu-icon '.$common_class.$show_dialog_class,
					'id' => !empty($arr2['icon_id']) ? $arr2['icon_id'] : null,
					'rel' => !empty($arr2['dialog']) ? $common_class : null,
					'title' => !empty($arr2['icon_title']) ? $arr2['icon_title'] : null,
				))
				: '';

			$text_class = !empty($arr2['text_class']) ? $arr2['text_class'] : '';
			$text_attr = !empty($arr2['short_name']) ?
				'data-sl="'.$arr2['short_name'].'" data-ll="'.$arr2['name'].'"' : '';

			//$show_dialog = $class.= (!empty($arr2['dialog'])) ? ' show-dialog' : '';
			//$icon_title = !empty($arr2['icon_title']) ? 'title="'.$arr2['icon_title'].'"' : '';
			$sup_checkboxes = !empty($arr2['checkboxes']) ? $arr2['checkboxes'] : false;

			// output heading or nothing if we just want a line
			if (isset($arr2['new_set'])){
				$areas_html.= '<li class="new-set-heading">'.$arr2['new_set'].'</li>';
			}

			// item
			$areas_html.= '<li '.$id.' '.$data_attr.' '.$rel.' class="mt-item '.$item_key.' '.$class.'">';

			// custom HTML
			if (!empty($arr2['custom'])){
				$areas_html.= $arr2['custom'];
			}

			// regular content
			else {
				// should a link wrap the icon and text?
				if (!empty($arr2['item_link'])){
					$areas_html.= '<a '.$link_id.' '.$link_target.' href="'.$arr2['item_link'].'">';
				}

				//$item_icon =  '<span '.$icon_id.' '.$rel.' class="mt-menu-icon mtif-'.$icon_class.' '.$show_dialog.'" '.$icon_title.'></span>';

				// add title to icons
				$item_icon = str_replace(
					'<span', '<span title="'.$arr2['name'].'"', $item_icon
				);

				// make docs option default to font-size
				if ($common_class === 'program-docs'){
					$item_icon = str_replace(
						'<span', '<span data-prop-group="font" data-prop="font_family"', $item_icon
					);
				}

				// append icon to dialog footer
				$dialog_footer_html.= $item_icon;


				// icon
				$areas_html.= $item_icon;

				if (!empty($arr2['text_data_attr'])){
					$text_attr = $this->format_data_attr_array($arr2['text_data_attr'], $text_attr);
				}

				// text label
				$colon = ''; // isset($arr2['toggle']) & ($item_key!= 'highlighting') ? ':' : '';
				$areas_html.= '<span class="mt-menu-text '.$common_class.$show_dialog_class.' '.$text_class.'"
							title="'.$arr2['title'].'" '.$text_attr.'>'
				              .$arr2['name'].$colon.'</span>';

				// do we need toggle?
				if (isset($arr2['toggle'])){
					$areas_html.= $this->toggle($item_key, $arr2);
				}

				// column options
				if (isset($arr2['column_options'])){
					$areas_html.= $this->column_options($arr2['column_options']);
				}

				// do we display keyboard shortcut
				if (isset($arr2['keyboard_shortcut'])){
					$areas_html.= '<span class="keyboard-sh">'.$arr2['keyboard_shortcut'].'</span>';
				}

				// do we need input?
				if (isset($arr2['input'])){
					$input_id = !empty($arr2['input_id']) ? 'id="'.$arr2['input_id'].'"' : '';
					$input_name = !empty($arr2['input_name']) ? $arr2['input_name'] : '';
					$input_placeholder = !empty($arr2['input_placeholder']) ? $arr2['input_placeholder'] : '';
					$areas_html.= '
								<div class="combobox-wrap tvr-input-wrap">
		
									<input type="text" name="'.$input_name.'" 
									placeholder="'.$input_placeholder.'"
									data-appto="#style-components"
									'.$input_id.' class="combobox has-arrows"
									rel="'.$arr2['combo_data'].'"
									value="'.$arr2['input'].'" />
									<span class="mt-clear-field"></span>
									<span class="combo-arrow"></span>
									<span class="tvr-button '.$arr2['button']['class'].'">
								    '.$arr2['button']['text'].'
								    </span>
								    
								</div>
								';

					$areas_html.= $this->maybe_output_supplementary_checkboxes($sup_checkboxes);
				}

				// custom display value
				if (isset($arr2['display_value'])){
					$areas_html.= $arr2['display_value'];
				}

				if (!empty($arr2['item_link'])){
					$areas_html.= '</a>';
				}
			}

			$areas_html.= '</li>';
		}

		$areas_html.= '</ul>';

		return array(
			'areas_html' => $areas_html,
			'footer' => $dialog_footer_html
		);
	}

	function format_data_attr_array($array, $data_attr = ''){

		if (!empty($array) && is_array($array)){
			foreach($array as $da_key => $da_value){
				$data_attr.= ' data-'.$da_key.'="'.$da_value.'"';
			}
		}

		return $data_attr;
	}

	function suggested_screen_layouts(){

		$sm = array(
			//'expand_device_tabs' => 1,
			'left_sidebar_columns' => 1,
		);
		$md = array(
			'full_height_left_sidebar' => 1, // this is now the default for CSS icons
			'dock_folders_left' => 1,
			'dock_styles_left' => 1,
			'dock_editor_left' => 1,
			'left_sidebar_columns' => 1,
		);
		$lg = array_merge($md, array(

			'dock_settings_right' => 1,
			'wizard_expanded' => 1
		));
		$xl = array_merge($lg, array(
			//'wizard_expanded' => 1,
			'left_sidebar_columns' => 3,
			'dock_styles_left' => 0,
			'full_height_right_sidebar' => 1,
		));
		$xl2 = array_merge($xl, array(
			'left_sidebar_columns' => 2,
			'dock_styles_left' => 1,
		));

		return array(
			'S' => array(
				'size' => 1366,
				'title' => esc_attr__('Options top', 'microthemer'),
				'on' => $sm
			),
			'M' => array(
				'size' => 1920,
				'title' => esc_attr__('Options left', 'microthemer'),
				'on' => $md
			),
			'L' => array(
				'size' => 2160,
				'title' => esc_attr__('Inspection and settings', 'microthemer'),
				'on' => $lg
			),
			'XL' => array(
				'size' => 2560,
				'title' => esc_attr__('Multi-column', 'microthemer'),
				'on' => $xl
			),
			'XL2' => array(
				'size' => 2560,
				'title' => esc_attr__('Alt Multi-column', 'microthemer'),
				'on' => $xl2
			),
		);
	}

	function screen_layout_options(){

		$html = '<div class="layout-presets-heading">'.esc_html__('Screen size layout presets', 'microthemer').'</div>'.
		        '<div id="screen-layout-options" class="screen-layout-options fake-radio-parent">';

		foreach ($this->suggested_screen_layouts as $key => $array){

			$on_class = '';
			$checked = '';

			if ($key === $this->preferences['suggested_layout']){
				$on_class = ' on';
				$checked = 'checked="checked"';
			}

			$html.= '
                    <div>
                        <input type="radio" autocomplete="off" class="radio"
                        name="suggested_layout" value="'.$key.'" '.$checked.' />
                        '.$this->iconFont('radio-btn-unchecked', array(
					'class' => 'fake-radio switch-layout-preset '.$on_class,
					'data-preset' => $key,
					'title' => $array['title']
				)).'
                        <span class="radio-label">'.$key.'</span>
                    </div>';
		}

		$html.= '</div>';

		return $html;
	}

	// keyboard shortcuts
	function keyboard_shortcuts_list(){

		$shortcuts = array(
			array(
				'action' => 'Toggle page builder', // (Elementor, Beaver Builder, or Oxygen)
				'win' => 'Ctrl+Alt+B',
			),
			array(
				'action' => 'Toggle full code editor',
				'win' => 'Ctrl+Alt+C',
			),
			array(
				'action' => 'Detach site preview', // in separate window
				'win' => 'Ctrl+Alt+D',
			),
			/*array(
						'action' => 'Search folders', // support this if time
						'win' => 'Ctrl+Alt+F',
					),*/
			array(
				'win' => 'Ctrl+Alt+G',
				'action' => 'View the generated code'
			),
			array(
				'win' => 'Ctrl+Alt+H',
				'action' => 'Toggle selector highlighting'
			),
			array(
				'action' => 'Jump to UI field for editor line', // Jump between code editor and UI property field
				'win' => 'Ctrl+Alt+J',
			),
			/*array(
						'action' => 'Toggle CSS property text labels',
						'win' => 'Ctrl+Alt+L',
					),*/
			array(
				'action' => 'Toggle page navigator menu',
				'win' => 'Ctrl+Alt+N',
			),
			array(
				'action' => 'Beautify code editor CSS',
				'win' => 'Ctrl+Alt+O',
			),
			array(
				'action' => 'Do full Sass compile', // If Sass is enabled, recompiles all MT selectors
				'win' => 'Ctrl+Alt+P',
			),
			array(
				'action' => 'Return focus to editor',
				'win' => 'Ctrl+Alt+R',
			),
			array(
				'win' => 'Ctrl+S',
				'action' => 'Save settings (for JS editor)' // Save settings. This is only needed when typing code in the JavaScript code editor. GUI settings and CSS code edits auto-save.
			),
			array(
				'action' => 'Toggle targeting mode',
				'win' => 'Ctrl+Alt+T',
			),
			array(
				'action' => 'Navigate to previous selector',
				'win' => 'Ctrl+Alt+,',

			),
			array(
				'action' => 'Navigate to next selector',
				'win' => 'Ctrl+Alt+.',

			),

		);

		// keyboard shortcuts alt / shift
		/*
				 * - select element when hovering ALT
				 * -  x 10 increment for property adjust
				 * - ALT + Shift (for left docked view)
				 * */

		$html = '<div class="keyboard-shortcuts-list">';

		// context dependent Ctrl key
		$html.= '
                
                <span class="shortcut-b shortcut-last shortcut-span3">Ctrl</span>
                <ul class="shortcut-span3">
                    <li>'.esc_html__('Press when clicking links to load a new page, even if Targeting mode is enabled', 'microthemer').'</li>
                </ul>';

		// context dependent Alt key
		$html.= '
                
                <span class="shortcut-b shortcut-last shortcut-span3">Alt</span>
                <ul class="shortcut-span3">
                    <li>'.esc_html__('Select an element (instead of clicking it)', 'microthemer').'</li>
                    <li>'.esc_html__('Enable mousewheel adjust', 'microthemer').'</li>
                </ul>';

		// context dependent Shift key
		$html.= '
          
                <span class="shortcut-b shortcut-last shortcut-span3">Shift</span>
                <ul class="shortcut-span3">
                    <li>'.esc_html__('Select multiple elements when clicking', 'microthemer').'</li>
                    <li>'.esc_html__('Increment style value by 10 when using:', 'microthemer').'
                        <ul>
                             <li>'.esc_html__('Increment buttons', 'microthemer').'</li>
                             <li>'.esc_html__('Mousewheel', 'microthemer').'</li>
                             <li>'.esc_html__('Up & down on keyboard', 'microthemer').'</li>
                        </ul>
                    </li>
                </ul>
                
                '; // <span class="new-shortcut-section"></span>


		foreach ($shortcuts as $i => $array){

			$shortcut = $array['win'];
			$shortcut_pieces = explode('+', $shortcut);
			$buttons = '';
			$count = count($shortcut_pieces);

			foreach ($shortcut_pieces as $j => $buttonText){
				$nth = $j+1;
				$last_class = ($nth === $count) ? ' shortcut-last' : '';
				$span_two_class = ($nth === 1 && $count === 2) ? ' shortcut-span2' : '';
				$buttons.= '<span class="shortcut-b shortcut-b'.($nth).$last_class.$span_two_class.'">'.$buttonText.'</span>';
			}

			$html.= '<span class="shortcut-heading">'.$array['action'].'</span>'
			        .$buttons;
		}





		$html.= '</div>';

		return $html;

	}

	function maybe_output_supplementary_checkboxes($checkboxes){

		if (!$checkboxes){
			return '';
		}

		$html = '';

		if (!empty($checkboxes)){
			foreach ($checkboxes as $item){
				$html.= '<div class="menu-supplementary-checkbox tvr-clearfix">
                            <input type="checkbox" name="'.$item['name'].'"> 
                            '.$this->iconFont('tick-box-unchecked', array(
						'class' => 'fake-checkbox'
					)).'
                            <span class="ef-label">'.$item['label'].'</span>
                        </div>';
			}
		}

		return $html;

	}

	// output left/right column options select menu
	function column_options($side = 'left'){

		$totalItems = count($this->preferences['layout'][$side]['items']);
		$optionHTML = '';

		for ($x = 1; $x <= $totalItems; $x++) {
			$selected = intval($this->preferences['layout'][$side]['num_columns']) === $x
				? ' selected="selected"'
				: '';
			$optionHTML.= '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
		}

		return '<select id="'.$side.'-column-options" class="settings-select-menu" data-side="'.$side.'">'.$optionHTML.'</select>';
	}

	function toggle($item_key, $arr){
		$on = $arr['toggle'] ? 'on' : '';
		$id = !empty($arr['toggle_id']) ? 'id="'.$arr['toggle_id'].'"' : '';
		// set dynamic title (adding this feature slowly)
		$pos_neg = $title = '';
		if( !empty($arr['data-pos']) ){
			$pos_neg = 'data-pos="'.$arr['data-pos'].'" data-neg="'.$arr['data-neg'].'" ';
			$title = !$on ? $arr['data-pos'] : $arr['data-neg'];
			$title = 'title="'.$title.'"';
		}


		$fhtml = !empty($arr['data-fhtml']) ? ' data-fhtml="'.$arr['data-fhtml'].'" ' : '';
		$noSave = !empty($arr['data-no-save']) ? ' data-no-save="'.$arr['data-no-save'].'" ' : '';


		$html = '';
		//$html.= print_r($on, true);
		$html.= '
				<div '.$id.' class="mtonoffswitch mt-ui-toggle uit-par '.$on.'"
				data-aspect="'.$item_key.'" '.$pos_neg.' '.$title.$fhtml.$noSave.'>
					<input type="checkbox" name="mtonoffswitch" class="mtonoffswitch-checkbox"
					id="mymtonoffswitch-'.$item_key.'">
					<label class="mtonoffswitch-label mt-ui-toggle" for="mymtonoffswitch-'.$item_key.'">
						<span class="mtonoffswitch-inner mt-ui-toggle"></span>
						<span class="mtonoffswitch-switch mt-ui-toggle"></span>
					</label>
				</div>';
		return $html;
	}

	// Resolve property/value input fields
	function resolve_input_fields(
		$section_name,
		$css_selector,
		$array,
		$property_group_array,
		$property_group_name,
		$property,
		$value,
		$con = 'reg',
		$key = 1,
		$sel_code = 'selector_code') {
		$html = '';

		// get value object, array, and string value
		$valueArray = false;
		$valueObject = $value;
		$value = is_array($valueObject) && !empty($valueObject['value'])
			? $valueObject['value']
			: ''; // should this be

		if (is_array($value)){
			$valueArray = $value;
			$value = !empty($valueArray[0]) ? $valueArray[0] : '';
		}

		// don't display legacy properties or the image display field
		if (!$this->is_legacy_prop($property_group_name, $property) and !strpos($property, 'img_display') ){
			include $this->thisplugindir . 'includes/resolve-input-fields.inc.php';
		}
		return $html;
	}

	// search posts by title or slug so we get precise search results
	function search_by_title_or_slug( $search, $wp_query ) {

		if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {

			global $wpdb;

			$q = $wp_query->query_vars;
			$n = ! empty( $q['exact'] ) ? '' : '%';
			$search = array();

			foreach ( ( array ) $q['search_terms'] as $term ) {

				$sql_term = $n . $wpdb->esc_like( $term ) . $n;

				$search[] = $wpdb->prepare(
					" ($wpdb->posts.post_title LIKE %s or 
                            $wpdb->posts.post_name LIKE %s) 
                           
                            ",
					// or $wpdb->posts.post_type LIKE %s
					// AND post_status != 'auto-draft' - this caused issues on some sites
					// the above line made search less precise for content
					// e.g. "P" would bring in all pages instead of a page called "Product tour"
					//$sql_term,
					$sql_term,
					$sql_term
				);
			}

			if ( ! is_user_logged_in() ){
				$search[] = "$wpdb->posts.post_password = ''";
			}

			$search = ' AND ' . implode( ' AND ', $search );
		}

		//echo 'mysearch: ' . $search;

		return $search;
	}

	function get_custom_post_types(){

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$output = 'objects'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'

		return get_post_types( $args, $output, $operator );
	}

	function format_posts_of_type(
		$post_type, $category, $permalink_structure, $common_config, &$urls
	){

		$isCustomPosts = ($post_type !== 'page' && $post_type !== 'post');
		$customPostsPathPrefix = $isCustomPosts
			? '/'.$post_type
			: '';

		$items = get_posts(
			array_merge($common_config, array('post_type'=> $post_type))
		);

		foreach($items as $item){

			// I noticed a strange bug whereby "kit" should have got an elementor template post in 'My Templates'
			// category, but also got the search result in all other post categories inc regular page/post
			if ($item->post_type !== $post_type){
				//continue;
			}

			$label = $item->post_title;

			if ($item->post_status === 'draft'){
				$label.= ' — Draft';
			}


			$path = $customPostsPathPrefix.'/'.$item->post_name.'/';
			//$url = rtrim($root_home_url, '/') . $path;

			// if non-standard permalink structure, we have to use the DB method of getting the URL
			if ($permalink_structure !== '/%postname%/' or
			    $item->post_type === 'ct_template' or
			    $item->post_status === 'draft'){

				// format URL as draft preview
				if ($item->post_status === 'draft'){
					$url = $item->guid.'&preview=true'; // maybe build this using id and pos_type
				}

				// it seems to be a quirk of Oxygen that the template admin screen must be loaded first
				elseif ($item->post_type === 'ct_template'){
					$url = $this->wp_blog_admin_url . 'post.php?post=' . $item->ID.'&action=edit';
					//$url = get_permalink($item).'&ct_builder=true';
					// enable this after fixing forever loading issue (maybe due to post locking...)
				}

				// non-standard permalink structure
				else {
					$url = get_permalink($item);
				}

				//$url = get_permalink($item);

				$path = $this->root_rel($url, false, true);
			}

			// exception for Oxygen - template pages produce PHP error if loaded on frontend
			// without ct_builder parameter
			/*if ($item->post_type === 'ct_template'){
						$path = Common::append_url_param($path, 'ct_builder', 'true');
					}*/

            $item_id = !empty($item->ID) ? $item->ID : false;
            $item_slug = !empty($item->post_name) ? $item->post_name : $item_id;

			$urls[$post_type][$label] = array(
				'label' => $label,
				'value' => $path,
				'category' => $category,
				'item_id' => $item_id,
                'logic' => $post_type === 'page'
                    ? 'is_page("'.$item_slug.'")'
                    : ($post_type === 'bricks_template'
                        ? '\Microthemer\has_template("bricks", '.$item_id.', "'.$item->post_title.'")'
                        : 'is_single("'.$item_slug.'")'),
				//'all' => $item, // debug
				//'config' => array_merge($common_config, array('post_type'=> $post_type))
			);
		}

		// sort alphabetically within category - actually no, have recently modified near top
		// ksort($urls[$post_type]);

	}

	// get example category, author, archive, 404
	function get_resource_example(){
		// todo
	}

	/*function get_placeholder_urls(){
				$light = $this->thispluginurl . "includes/place-holder.php";
			    $dark = $light . "?mt_dark_mode=1";
			    return array(
			            'light' => $light,
			            'dark' => $dark,
			            'current' => !empty($this->preferences['mt_dark_mode']) ? $dark : $light
			    );
			}*/

	function get_site_pages($searchTerm = null){

		// get URL vars
		/*$blog_details = function_exists('get_blog_details')
					? get_blog_details( $this->multisite_blog_id )
					: false;*/
		/*$root_home_url = $blog_details
                    ? rtrim($blog_details->path, '/')
                    : $this->home_url;*/

		// get_the_title( get_option('page_on_front') )
		$permalink_structure = get_option('permalink_structure');
		$users_can_register = get_option('users_can_register');
		$common_config = array(
			'post_status' => array('publish', 'draft'), // we want user to be able to access drafts
			'numberposts' => 8,
			'suppress_filters' => false,
			'orderby' => 'modified',
			'order' => 'DESC',
			's' => $searchTerm
		);

		// Get data
		$urls = array();
		$formatted_urls = array();
		$urlTypes = array(
			'global' => esc_html__('Global', 'microthemer'),
			'home' => esc_html__('Home page', 'microthemer'),
			'page' => esc_html__('Recently edited pages', 'microthemer'),
			'post' => esc_html__('Recently edited posts', 'microthemer'),
			'types' => esc_html__('Content types', 'microthemer'),
			'has' => esc_html__('Has attribute', 'microthemer'),
			'wordpress' => esc_html__('WordPress', 'microthemer'),
			'custom_posts' => esc_html__('Custom posts', 'microthemer'),
			'general' => esc_html__('General', 'microthemer'),
		);
		$custom_post_types = $this->get_custom_post_types();

		foreach ($urlTypes as $key => $category){

			// regular post or page
			if ($key === 'page' || $key === 'post'){
				$post_type = $key;
				$this->format_posts_of_type(
					$post_type, $category, $permalink_structure, $common_config, $urls
				);
			}

			// custom posts
			elseif ($key === 'custom_posts'){

				//$urls[$key] = $custom_post_types;
				foreach ($custom_post_types as $index => $custom_post_type){
					$category = $custom_post_type->label;
					$post_type = $custom_post_type->name;
					$this->format_posts_of_type(
						$post_type, $category, $permalink_structure, $common_config, $urls
					);
				}
			}

			// general / wordpress
			elseif ($key === 'global' || $key === 'home' || $key === 'types' || $key === 'has' || $key === 'general' || $key === 'wordpress'){

				$custom_links = array();

				if ($key === 'global'){

					$custom_links = array(
						array(
							'label' => esc_html__('Is frontend', 'microthemer'),
							'logic' => '\Microthemer\is_public()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is admin area', 'microthemer'),
							'logic' => 'is_admin()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is frontend or admin', 'microthemer'),
							'logic' => '\Microthemer\is_public_or_admin()',
							'logic_only' => 1
						)
					);
				}

				// Home
				if ($key === 'home'){

					/*$homeTitle = ('posts' === get_option( 'show_on_front' ))
						? esc_html__('Blog home', 'microthemer')
						: get_the_title( get_option('page_on_front') );*/

                    $homePage = $this->homePageLogicLabel();

					$custom_links[] = array(
						'label' => $homePage['title'], // esc_html__('Home page', 'microthemer'),
						'value' => '/',
                        'logic' => $homePage['logic']
					);
				}

				// Content types
				if ($key === 'types'){

					$custom_links = array(
						array(
							'label' => esc_html__('Is page', 'microthemer'),
							'logic' => 'is_page()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is single post', 'microthemer'),
							'logic' => 'is_single()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is blog home', 'microthemer'),
							'logic' => 'is_home()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is archive', 'microthemer'),
							'logic' => 'is_archive()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is search', 'microthemer'),
							'logic' => 'is_search()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is 404 page', 'microthemer'),
							'logic' => 'is_404()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is author archive', 'microthemer'),
							'logic' => 'is_author()',
							'logic_only' => 1
						),
                        array(
							'label' => esc_html__('Is category archive', 'microthemer'),
							'logic' => 'is_category()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Is date archive', 'microthemer'),
							'logic' => 'is_date()',
							'logic_only' => 1
						),
                        array(
							'label' => esc_html__('Is tag archive', 'microthemer'),
							'logic' => 'is_tag()',
							'logic_only' => 1
						),

                    );

					foreach ($custom_post_types as $custom_post_type){
						$customPostLabel = 'Is ' .  $custom_post_type->label;
						$custom_links[] = array(
							'label' => $customPostLabel,
							'logic' => 'get_post_type() === "'.$custom_post_type->name.'"',
							'logic_only' => 1
						);

					}

				}

				// Has attribute
				if ($key === 'has'){

					$custom_links = array(
						array(
							'label' => esc_html__('Has action', 'microthemer'),
							'logic' => 'has_action()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Has block', 'microthemer'),
							'logic' => 'has_block()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Has category', 'microthemer'),
							'logic' => 'has_category()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Has filter', 'microthemer'),
							'logic' => 'has_filter()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Has meta', 'microthemer'),
							'logic' => 'has_meta()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Has post format', 'microthemer'),
							'logic' => 'has_post_format()',
							'logic_only' => 1
						),
						array(
							'label' => esc_html__('Has tag', 'microthemer'),
							'logic' => 'has_tag()',
							'logic_only' => 1
						),
						/*array(
							'label' => esc_html__('Has term', 'microthemer'),
							'logic' => 'has_term()',
							'logic_only' => 1
						),*/
					);

					foreach ($custom_post_types as $custom_post_type){
						$customPostLabel = 'Is ' .  $custom_post_type->label;
						$custom_links[] = array(
							'label' => $customPostLabel,
							'logic' => 'get_post_type() === "'.$custom_post_type->name.'"',
							'logic_only' => 1
						);

					}

				}

				// WordPress auth pages
				if ($key === 'wordpress'){

                    $allLoginLabel = esc_html__('Login and Forgot Password', 'microthemer');

					$custom_links[] = array(
						'label' => esc_html__('Login page', 'microthemer'),
						'value' => $this->root_rel(wp_login_url(), false, true),
                        'logic' => 'is_login() and !isset($_GET["action"])',
                        'exclude_logic' => '!is_login() or isset($_GET["action"])',
					);

					$custom_links[] = array(
						'label' => esc_html__('Lost password page', 'microthemer'),
						'value' => $this->root_rel(wp_lostpassword_url(), false, true),
						'logic' => 'is_login() and $_GET["action"] === "lostpassword"',
						'exclude_logic' => '$_GET["action"] !== "lostpassword"',
					);

					// if registration is supported
					if ($users_can_register){

                        $custom_links[] = array(
							'label' => esc_html__('Registration page', 'microthemer'),
							'value' => $this->root_rel(wp_registration_url(), false, true),
							'logic' => 'is_login() and $_GET["action"] === "register"',
                            'exclude_logic' => '$_GET["action"] !== "register"',
						);

						$allLoginLabel.= esc_html__(' and Registration', 'microthemer');
					}

                    // When assigning conditional CSS it may be useful to target all WP login pages at once
					$custom_links[] = array(
						'label' => $allLoginLabel,
						'logic' => 'is_login()',
						'logic_only' => 1
					);

                    // WordPress admin pages
					$custom_links[] = array(
						'label' => 'Is admin area',
						'logic' => 'is_admin()',
						'logic_only' => 1
					);

					// Custom MT function - user is a subscriber
					$custom_links[] = array(
						'label' => 'Is a subscriber',
						'logic' => '\Microthemer\user_has_role("subscriber")',
						'logic_only' => 1
					);

					// Custom MT function - user is a subscriber
					$custom_links[] = array(
						'label' => 'Is an administrator',
						'logic' => '\Microthemer\user_has_role("administrator")',
						'logic_only' => 1
					);

					// Custom MT function - is gutenberg editor screen
					$custom_links[] = array(
						'label' => 'Is Gutenberg block editor',
						'logic' => '\Microthemer\query_admin_screen("is_block_editor", true)',
						'logic_only' => 1
					);


				}

				// General types of page - finish later
				/*elseif ($key === 'general'){

							$custom_links = array(
								array(
									'label' => esc_html__('Home page', 'microthemer'),
									'value' => '/',
								),
								array(
									'label' => esc_html__('Search page', 'microthemer'),
									'value' => '/?s=test',
								)
							);
						}*/

				// add the category and merge with urls array
				foreach ($custom_links as $j => $custom_links_array){
					$urls[$key][] = array_merge($custom_links_array, array('category' => $category));
				}

			}

		}

		// add category urls to flat array
		foreach ($urls as $key => $array){
			if (!empty($urls[$key])){
				$formatted_urls = array_merge($formatted_urls, array_values($urls[$key]));
			}

		}

		//wp_die('<pre>'.print_r($urls, true).'</pre>');

		//return $urls;

		return $formatted_urls;

	}

	// Global system for creating dynamic menus (data, structure, config)
	// Note: passing array/objs into PHP/JS functions over lots of params should become standard practice
	function dyn_menu($d, $s, $c) {

		$base_key = !empty($s['base_key']) ? 'data-base-key="'.$s['base_key'].'"' : '';
		$html = '<div id="dyn-wrap-'.$s['slug'].'" class="dyn-wrap"
				data-slug="'.$s['slug'].'" '.$base_key.'>';

		// add controls if required
		if (!empty($c['controls'])){
			$input_class = !empty($s['add_button']) ? 'combobox' : '';
			$input_placeholder = !empty($s['input_placeholder']) ? 'placeholder="'.$s['input_placeholder'].'"' : '';
			$combo_arrow = '';
			if (!empty($s['combo_add_arrow'])) {
				$combo_arrow = '<span class="combo-arrow"></span>';
				$input_class.= ' has-arrows';
			}
			$html.= '
					<div class="tvr-new-item">
						<span class="tvr-input-wrap">
							<input type="text" class="new-item-input '.$input_class.'" '.$input_placeholder.' 
							name="new_item[name]" rel="'.$s['slug'].'">
							'.$combo_arrow.'
						</span>
						<span class="new-item-add tvr-button" title="'.$s['add_button'].'">'.$s['add_button'].'</span>
					</div>';
		}


		// loop through data (maybe try to make this a recursive function)
		$html.= '
				<ul id="'.$s['slug'].'-dyn-menu" class="tvr-dyn-menu">';

		foreach ($d as $k => $arr){

			$html.= $this->dyn_item($s, $k, $arr);
		}

		$html.= '
				</ul>
				</div>'; // dyn-wrap

		return $html;
	}

	function dyn_item($s, $k, $arr){

		$fields = $s['items']['fields'];

		// resolve display name, class etc
		$display_name = !empty($arr['display_name']) ? $arr['display_name'] : $arr['label'];
		$name_class = !empty($fields['label']['name_class']) ? ' '.$fields['label']['name_class'] : '';

		$icons = array(
			'mqs' => 'devices',
			'enq_js' => 'js',
			'edit' => 'edit',
			'delete' => 'bin'
		);

		$sortableIcon = $this->iconFont($icons[$s['slug']], array(
			'class' => 'sortable-icon',
			'title' => $s['items']['icon']['title']
		));

		$html = '';
		// li item
		$html.= '
				<li id="'.$s['slug'].'-'.$k.'" class="dyn-item '.$s['level'].'-tag '.$s['slug'].' '.$s['slug'].'-'.$s['level'].'">';

		// row with sortable icon, name
		$dis_class = !empty($this->preferences['enq_js'][$k]['disabled']) ? 'item-disabled' : '';
		$html.= '
				<div class="'.$s['level'].'-row item-row '.$dis_class.'">
					'.$sortableIcon.'
					<span class="name-text '.$s['level'].'-name'.$name_class.'">'.esc_html($display_name).'</span>';

		$html.= '
				<span class="manage-'.$s['level'].'-icons folders-menu-actions">';

		// do action icons
		foreach ($s['items']['actions'] as $action => $a_arr){

			// output icon control e.g. disabled
			if (!empty($a_arr['icon_control'])){
				$html.= $this->icon_control(
					false,
					$action,
					!empty($this->preferences['enq_js'][$k]['disabled']),
					$s['level'],
					$k,
					'',
					'all-devices', // just to skip mq stuff
					'',
					'',
					$s['name_stub']
				);
			} else {
				// regular icon
				$a_class = !empty($a_arr['class']) ? $a_arr['class'] : '';

				$html.= $this->iconFont($icons[$action], array(
					'class' => $a_class. ' '.$action.'-'.$s['level'],
					'title' => $a_arr['title']
				));

			}

		}

		// end action icons and row
		$html.= '
					</span>
				</div>';

		// edit fields (for enq_js just hidden input so no need to have edit icon)
		$html.= '
				<div class="edit-item-form float-form hidden">';

		// editing or hidden input fields
		foreach ($s['items']['fields'] as $input_name => $f_arr){
			$input_type = $f_arr['type'];
			$val = !empty($arr[$input_name]) ? $arr[$input_name] : '';
			$input_class = !empty($f_arr['input_class']) ? ' '.$f_arr['input_class'] : '';
			$input_rel = !empty($f_arr['input_rel']) ? ' rel="'.$f_arr['input_rel'].'"' : '';
			$input = '<input type="'.$input_type.'" class="'.$s['level'].'-'.$input_name.$input_class.'"
								'.$input_rel.' name="'.$s['name_stub'].'['.$k.']['.$input_name.']" value="'.esc_html($val).'">';

			if (!empty($f_arr['input_arrows'])){
				$input.= $f_arr['input_arrows'];
			}

			// just input if hidden
			if ($input_type == 'hidden'){
				$html.= $input;
			} else {
				// form fields
				$f_class = !empty($f_arr['field_class']) ? ' '.$f_arr['field_class'] : '';
				//$f_class = $input_type == 'checkbox' ? 'mq-checkbox-wrap' : '';
				$html.= '
						<p class="'.$f_class.'">
							<label title="'.$f_arr['label_title'].'">'.$f_arr['label'].':</label>';
				// regular text input, checkbox
				if ($input_type != 'textarea'){
					$html.= $input;
					if ($input_type == 'checkbox'){
						$html.=
							$this->iconFont('tick-box-unchecked', array(
								'class' => 'fake-checkbox'
							))
							.'<span class="ef-label">'.$f_arr['label2'].'</span>';
					}
				} else {
					// text area
					$html.= '<textarea class="'.$s['level'].'-'.$input_name.'"
								name="'.$s['name_stub'].'['.$k.']['.$input_name.']">'.esc_html($val).'</textarea>';
				}
				$html.= '
						</p>';
			}
		}

		// maybe add recursive functionality for sub items here if needed/possible

		$html.= '
				</li>';

		return $html;
	}

	// menu section html
	function menu_section_html($section_name, $array) {

		$section_name = esc_attr($section_name); //=esc

		// get folder display name
		$display_section_name = $this->get_folder_name_inc_legacy($section_name, $array);

		$selector_count_state = $this->selector_count_state($array);

		// generate html code for sections in this loop to save having to do a 2nd loop later
		//$this->initial_options_html[$this->total_sections] = $this->section_html($section_name, $array);

		$sec_class = '';

		// user disabled
		$disabled = false;
		if (!empty($array['this']['disabled'])){
			$disabled = true;
			$sec_class.= ' item-disabled';
		}

        // folder is conditional
        if (!empty($array['this']['logic']['expr'])
            && !$this->hasGlobalCondition($array['this']['logic']['expr'])
        ){
			$sec_class.= ' item-conditional';
		}

		// should feather be displayed?
		if ($selector_count_state > 0 ) {
			// need deep search of values in selectors
			if ($this->section_has_values($section_name, $array, true)){
				$sec_class.= ' hasValues';
			}
		}
		$folder_title = esc_attr__("Reorder folder", 'microthemer');

		ob_start();
		?>
		<li id="<?php echo 'strk-'.$section_name; ?>" class="section-tag strk strk-sec <?php echo $sec_class; ?>">

            <?php
            // hidden input fields for storing folder values
            $hiddenInputs = array(
	            '[label]' => array(
                        'class' => 'label',
                        'value' => $display_section_name
                ),
                '[logic][exclude]' => array(
	                'class' => 'logic-exclude section-logic-input',
	                'value' => isset($array['this']['logic']['exclude']) ? $array['this']['logic']['exclude'] : ''
                ),
	            /*'[logic][label]' => array(
		            'class' => 'logic-label section-logic-input',
		            'value' => isset($array['this']['logic']['label']) ? $array['this']['logic']['label'] : ''
	            ),*/
	            '[logic][expr]' => array(
		            'class' => 'logic-expr section-logic-input',
		            'value' => isset($array['this']['logic']['expr']) ? $array['this']['logic']['expr'] : ''
	            ),
	            '[logic][css_external]' => array(
		            'class' => 'logic-css_external section-logic-input',
		            'value' => isset($array['this']['logic']['css_external']) ? $array['this']['logic']['css_external'] : ''
	            ),
                '[logic][css_async]' => array(
	                'class' => 'logic-css_async section-logic-input',
	                'value' => isset($array['this']['logic']['css_async']) ? $array['this']['logic']['css_async'] : ''
                ),

            );

            foreach ($hiddenInputs as $nameExtension => $inputData){

                echo '<input type="hidden" class="section-data-'.$inputData['class'].'"
			       name="tvr_mcth['.$section_name.'][this]'.$nameExtension.'"
			       value="'.esc_attr($inputData['value']).'" />';
            }
            ?>

			<div class="sec-row item-row">

                        <span class="item-icon folder-item-icon">
                            <?php
                            echo $this->iconFont('folder-filled', array(
	                            'class' => 'folder-icon sortable-icon',
	                            'title' => $folder_title,
	                            'data-title' => $folder_title
                            ));
                            ?>
                        </span>

				<span class="section-name item-name">
						    <span class="name-text selector-count-state"
						          rel="<?php echo $selector_count_state; ?>"><?php echo $display_section_name; ?></span><?php
					if ($selector_count_state > 0) {
						echo '<span class="folder-count-wrap count-wrap"> (<span class="folder-state-count state-count">'.$selector_count_state.'</span>)</span>';
					}
					// update global $total_selectors count
					$this->total_selectors = $this->total_selectors + $selector_count_state;
					?>
						</span>

				<?php
				echo $this->item_manage_icons('section', $section_name, array(
					'disabled' => $disabled,
					'context' => 'folders-menu',
				));
				?>

			</div>

			<ul class="selector-sub"></ul>
		</li>
		<?php

		return ob_get_clean();
	}




    function hasGlobalCondition($expr){
	    return !$expr
		    ? false
		    : preg_match(
                    '/^(!?)\\s*(is_admin|\\\\Microthemer\\\\is_public(?:_or_admin)?)\\(\\s*\\)$/',
                    $expr
            );
    }

	// MT supports multiple types of item added to folders now (e.g. selectors, code snippets, etc)
	function add_folder_item_tabs(){

		$html = '';

		foreach ($this->folder_item_types as $key => $array){
			$active = $key === 'selector' ? ' active' : '';
			$html.= '
				    <span class="mt-tab fit-tab fit-tab-'.$key.$active.'" rel="'.$key.'">
                        '.$array['label'].'
                    </span>';
		}

		return '<div class="query-tabs">'.$html.'</div>';
	}

	// folder and folder item icons
	function item_manage_icons($level, $slug, $config){

		$context = $config['context'];
		$sub_context = !empty($config['sub_context']) ? $config['sub_context'] : '';

		$labels = array(
			'section' => array(
				'disable' => esc_html__("Disable folder", 'microthemer'),
				'clear' => esc_html__("Clear folder styles", 'microthemer'),
				'delete' => esc_html__("Delete folder", 'microthemer'),
				'duplicate' => esc_html__("Duplicate folder", 'microthemer'),
				'more' => esc_html__("Folder options", 'microthemer'),

			),
			'selector' => array(
				'disable' => esc_html__("Disable selector", 'microthemer'),
				'clear' => esc_html__("Clear selector styles", 'microthemer'),
				'delete' => esc_html__("Delete selector", 'microthemer'),
				'duplicate' => esc_html__("Duplicate selector", 'microthemer'),
				//'variation' => esc_html__("Hover state and more", 'microthemer'),

				'retarget' => esc_html__("Re-target current selector", 'microthemer'),
				'more' => esc_html__("Edit selector", 'microthemer'),
				//'extra' => esc_html__("More options", 'microthemer'),
			)
		);

		$icons = array(
			'disable' => $this->icon_control(false, 'disabled', !empty($config['disabled']), $level, $slug),
			'clear' => $this->clear_icon($level),
			'delete' => $this->iconFont('bin', array(
				'class' => 'delete-'.$level.' mt-icon-divider',
				'title' => $labels[$level]['delete']
			)),
			'duplicate' => $this->iconFont('copy', array(
				'class' => 'copy-'.$level,
				'title' => $labels[$level]['duplicate'],
			)),
			'more' => $this->iconFont('dots-vertical', array(
				'class' => 'toggle-folder-more-options',
				'title' => $labels[$level]['more'],
				'data-level' => $level,
				'data-forpopup' => 'contextMenu'
			)),
			/*'extra' => $this->iconFont('dots-vertical', array(
						'class' => 'toggle-cur-extra',
						'title' => $labels[$level]['extra'],
						'data-level' => $level,
						'data-forpopup' => 'contextMenu'
					)),*/
		);

		if ($level === 'selector'){

			$icons = array_merge($icons, array(
				// selector only
				/*'variation' => $this->iconFont('hand-pointer', array(
							'class' => 'selector-variation',
							'title' => $labels[$level]['variation'],
							'data-forpopup' => 'contextMenu'
						)),*/

				'spotlight' => $this->ui_toggle(
					'mt_highlight',
					'conditional',
					'conditional',
					0, // on state is styled by HTML class mt_highlight
					'current-item-spotlight ' . $this->iconFont('spotlight', array(
						'onlyClass' => 1
					)),
					false,
					// instruct tooltip to get content dynamically
					array('dataAtts' =>
						      array(
							      'dyn-tt-root' => 'toggle-highlighting',
							      'highlight-context' => 'current-selector'
						      )
					)
				),
				'retarget' => $this->iconFont('retarget', array(
					'class' => 'retarget-'.$level,
					'title' => $labels[$level]['retarget'],
				))
			));
		}

		$html = '';

		if (isset($config['html_before'])){
			$html.= $config['html_before'];
		}

		foreach ($icons as $key => $icon){

			if (
				($key === 'more' && ($context !== 'folders-menu' && $context !== 'current-item')) ||

				// no duplicate in current item as rarely needed
				//($key === 'duplicate' && $context === 'current-item') ||

				// current item only

				// current main
				($key === 'spotlight' && ($context !== 'current-item' || $sub_context !== 'main')) ||
				($key === 'retarget' && ($context !== 'current-item' || $sub_context !== 'main')) ||
				($key === 'extra' && ($context !== 'current-item' || $sub_context !== 'main')) ||

				// current more
				($key === 'disable' && ($context === 'current-item' && $sub_context !== 'extra')) ||
				($key === 'clear' && ($context === 'current-item' && $sub_context !== 'extra')) ||
				($key === 'delete' && ($context === 'current-item' && $sub_context !== 'extra')) ||
				($key === 'duplicate' && ($context === 'current-item' && $sub_context !== 'extra'))
				//($key === 'variation' && ($context !== 'current-item' || $sub_context !== 'extra'))
			){
				continue;
			}

			$html.= $icon;
		}

		if (isset($config['html_after'])){
			$html.= $config['html_after'];
		}

		return  '
			    <div class="manage-'.$level.'-icons mt-icon-line '.$context.'-actions" 
			        data-context="'.$context.'" data-level="'.$level.'">
			        '.$html.'
			    </div>';

	}

	function add_edit_section_form($context){

		if ($context === 'add'){
			$title = esc_html__("Add folder", 'microthemer');
			$button_text = esc_html__("Add", 'microthemer');
			$icons = '';
		}

		else {
			$title = esc_html__("Edit folder", 'microthemer');
			$button_text = esc_html__("Update", 'microthemer');
			$icons = $this->item_manage_icons('section', 'selector_section', array(
				'context' => 'folder-popup',
			));
		}

		// popup sections
		$sections = array(

			// edit folder name form
			$this->context_menu_form($context.'-section', array(
				'wrap' => 1,
				'wrapClass' => 'mt-folder-form '.$context.'-section-form',
				'fields' => array(
					'label' => array(
						'label' => esc_html__("Folder name", 'microthemer'),
						'type' => 'input',
					),
					'filler' => array(
						'custom' => '<span></span>' // filler
					),
					'icon-line' => array(
						'custom' => $icons,
					),

				),
				'button' => array(
					'text' => $button_text,
				)
			))
		);

		// add extra fields for adding a selector if editing a folder
		if ($context === 'edit'){

			$sections = array_merge($sections, array(

                //  conditional logic
				$this->context_menu_heading(
					esc_html__("Load folder on specific pages", 'microthemer')
				),
				$this->conditional_folder_options(),

                // add selector
				$this->context_menu_heading(
					esc_html__("Add selector to folder", 'microthemer')
				),
				$this->add_folder_item_forms()
			));
		}

		return $this->context_menu_content(
			array(
				'base_key' => $context.'-folder-options',
				'title' => $title,
				'sections' => $sections
			)
		);

	}

    function switchAutoFolder(){

        return '
        <div class="move-current-folder-item">

            <input type="checkbox" name="auto_folders[move_current]" value="1" />

            '.$this->iconFont('tick-box-unchecked', array(
                'class' => 'fake-checkbox toggle-move-current-item',
            )).'

            <span>'.esc_html__('Move current selector to folder', 'microthemer').'</span>

        </div>

        <div class="folder-options-list scrollable-area"></div>';

    }

    function homePageLogicLabel(){

        // Blog posts on home
        if (('posts' === get_option( 'show_on_front' ))){
	        $title = esc_html__('Blog home', 'microthemer');
            $logic = 'is_home()';
        }

        // Static page on home
        else {
            $homePageId = get_option('page_on_front');
            $post = get_post($homePageId);
	        $title = $post->post_title;
	        $logic = 'is_page("'.$post->post_name.'")';
        }

        return array(
            'title' => $title,
            'logic' => $logic
        );

    }

	function add_edit_selector_form($context){

		if ($context === 'add'){
			$button_text = esc_html__("Add", 'microthemer');
			$icons = '';
		}

		else {
			$button_text = esc_html__("Update", 'microthemer');
			$icons = $this->item_manage_icons('selector', 'selector_css', array(
				'context' => 'folder-popup',
			));
		}

		$form = $this->context_menu_form($context.'-selector', array(
			'wrap' => 1,
			'wrapClass' => $context.'-selector-form modify-selector-form mt-folder-form 
				    '.$context.'-folder-item-form show',
			'fields' => array(
				'label' => array(
					'label' => esc_html__("Selector name", 'microthemer'),
					'type' => 'input',
					'inputClass' => 'selector-name-input linkable-fields'
				),

				'linked-fields-toggle' => array(
					'custom' => $this->ui_toggle(
						'selname_code_synced',
						'conditional', // only wizard toggle has title/on class (easier than maintaining dynamically)
						'conditional',
						false,
						'code-chained-icon selname-code-sync popup-options-sync ' . $this->iconFont('chain', array(
							'onlyClass' => 1
						)),
						false,
						// instruct tooltip to get content dynamically
						array('dataAtts' => array(
							'dyn-tt-root' => 'selname_code_synced'
						))
					)       ),
				'code' => array(
					'label' => esc_html__("Selector code", 'microthemer'),
					'type' => 'input',
					'inputClass' => 'selector-css-textarea linkable-fields'
				),
				'filler' => array(
					'custom' => '<span></span>' // filler
				),
				'icons' => array(
					'custom' => $icons
				),
			),
			'button' => array(
				'text' => $button_text,
				'class' => $context.'-selector',
			)
		));

		// selector variation if edit form
		if ($context === 'edit'){
			$form.=
				$this->context_menu_heading(
					esc_html__("Create selector variation", 'microthemer')
				)
				.
				'<div id="mt-selector-state-options"></div>';
		}

		return $form;
	}

	function add_folder_item_forms(){

		return $this->add_edit_selector_form('add');

	}

	// menu single selector html
	function menu_selector_html($section_name, $css_selector, $array, $sel_loop_count) {

		ob_start();

		$sel_class = '';

		$style_count_state = $this->selector_has_values($section_name, $css_selector, $array, true);

		// trial disabled (all sels will be editable even in free trial in future)
		if (!$this->preferences['buyer_validated'] and $this->sel_count > 15 ) {
			$sel_class.= 'trial-disabled'; // visually signals disabled and, prevents editing
		}

		// user disabled
		$disabled = false;
		if (!empty($array['disabled'])){
			$disabled = true;
			$sel_class.= ' item-disabled';
		}

		// should feather be displayed?
		if ($style_count_state > 0) {
			$sel_class.= ' hasValues';
		}

		// can't recall why I went down this route of storing label and code in piped single value.
		if (is_array($array) and !empty($array['label'])){
			$labelCss = explode('|', $array['label']);
			// convert my custom quote escaping in recognised html encoded single/double quotes
			$selector_title = esc_attr(str_replace('cus-', '&', $labelCss[1]));
		} else {
			$labelCss = array('', '');
			$array['label'] = '';
			$selector_title = '';
		}

		?>
		<li id="<?php echo 'strk-'.$section_name.'-'.$css_selector; ?>" class="selector-tag strk strk-sel <?php echo $sel_class; ?>">

			<input type='hidden' class='selector-label' name='tvr_mcth[<?php echo $section_name; ?>][<?php echo $css_selector; ?>][label]' value='<?php echo $array['label']; ?>' />

			<div class="sel-row item-row">

                        <span class="item-icon">

                            <?php
                            echo $this->iconFont('spotlight', array(
	                            'class' => 'highlight-preview',
	                            'title' => esc_attr__("Highlight selector", 'microthemer')
                            ));

                            echo $this->iconFont('target', array(
	                            'class' => 'selector-sortable-icon sortable-icon',
	                            'title' => esc_attr__("Reorder selector", 'microthemer')
                            ));
                            ?>
                        </span>

				<span class="selector-name item-name change-selector" title="<?php echo $selector_title; ?>">
						    <span class="name-text style-count-state change-selector"
						          rel="<?php echo $style_count_state; ?>"><?php echo esc_html($labelCss[0]); ?>
                            </span>
						</span>

				<span class="disabled-responsive-tabs"></span>

				<?php

				echo $this->item_manage_icons('selector', $css_selector, array(
					'disabled' => $disabled,
					'context' => 'folders-menu',
				));
				?>

			</div>
		</li>
		<?php

		return ob_get_clean();

	}

    function add_logic_options(){
        return '
        <div id="addLogic" class="popdown-content add-logic-popdown" title="">

            <div class="include-or-exclude mt-binary-buttons" data-run="toggle_include_exclude">
                <span class="binary-button-option" rel="0">Include</span>
                <input type="checkbox" name="logic[exclude]" value="1" class="mt_exclude" />
                <span class="binary-button-option" rel="1">Exclude</span>
            </div>
            
            <div class="tvr-input-wrap mt-search-logic-wrap">
                <input id="logic-search" placeholder="Search conditions" type="text" rel="conditional_logic" class="mt-search-logic combobox has-arrows" name="logic[search]" autocomplete="off">
                <span class="mt-clear-field"></span>
                <span class="combo-arrow"></span>
            </div>
            
            <input id="logic-to-add" type="hidden" name="logic_to_add" value="" />
            
            <div class="tvr-button add-logic-button add-first-logic" data-type="first">Add</div>
            <div class="tvr-button add-logic-button append-logic-button add-with-or" data-type="or" title="Add with \'or\'">or</div>
            <div class="tvr-button add-logic-button append-logic-button add-with-and" data-type="and" title="Add with \'and\'">and</div>
               
        </div>
        ';
    }

    function conditional_folder_options(){

        $logic = new Logic();
        $allowedPHPSyntax = $logic->getAllowedPHPSyntax();
        $functionsList = '';
	    $superglobalsList = '';
        $docPost = 'https://themeover.com/load-wordpress-css-on-specific-pages/';
        $MTdivider = false;
        $PHPdivider = false;

        foreach ($allowedPHPSyntax['functions'] as $functionName){

            $isMTFunction = strpos($functionName, '\Microthemer') === 0;
	        $isPHPFunction = $functionName === 'isset';
	        $functionDisplayName = $functionName;
            $url = 'https://developer.wordpress.org/reference/functions/'.$functionName.'/';
            $extra_class = '';

            // Custom Microthemer function
	        if ($isMTFunction){
		        $functionID = str_replace('\Microthemer\\', '', $functionName);
		        $functionDisplayName = $functionName; // '[MT] ' . $functionID;
		        $url = $docPost.'#'.$functionID;
		        $extra_class.= ' is-mt-function';
                if (!$MTdivider){
	                $functionsList.= '<div class="list-divider"></div>';
	                $MTdivider = true;
                }
	        }

	        // native PHP function
	        if ($isPHPFunction){
		        //$functionDisplayName = '[PHP] ' . $functionName;
		        $url = 'https://www.php.net/manual/en/function.'.$functionName.'.php';
		        $extra_class.= ' is-native-php';
		        if (!$PHPdivider){
			        $functionsList.= '<div class="list-divider"></div>';
			        $PHPdivider = true;
		        }
	        }

	        $functionsList.= '<a href="'.$url.'" target="_blank" class="supported-item'.$extra_class.'">'.$functionDisplayName.'()</a>';

	        /*$functionsList.= '<a href="'.$url.'" target="_blank" class="supported-item">
	            <span class="display-function">'.$functionDisplayName.'()</span>
	            <span class="mt-copy-function"></span>
	        </a>';*/
        }

	    foreach ($allowedPHPSyntax['superglobals'] as $globalName){
		    //$superglobalsList.= '<li class="supported-item">'.$globalName.'</li>';
            $slug = strtolower(str_replace('$_', '', $globalName));
            $url = 'https://www.php.net/manual/en/reserved.variables.'.$slug.'.php';
		    $functionsList.= '
            <a href="'.$url.'" target="_blank" class="supported-item is-native-php">
                '.$globalName.'
            </a>';
	    }

        // show which characters are supported
	    $functionsList.= '
        <li class="supported-item is-allowed-characters">
            ( ) , > < = ! \' " || && or and true false
        </li>';

        /*
         *   <div class="heading">'.count($allowedPHPSyntax['superglobals']).' superglobal </div>
                        '.$superglobalsList.'

         */

        $html = '
        <div class="mt-cm-form mt-folder-form conditional-logic-form">
           
            <div class="conditional-label">
            
                <span class="toggle-popdown-content toggle-php-syntax" data-forpopup="phpSyntax">
                    Condition 
                     <ul id="phpSyntax" class="popdown-content supported-functions-list">
                       <div class="heading">
                           Supported WordPress and Microthemer PHP code 
                           <a href="'.$docPost.'" target="_blank">Watch video</a>
                       </div>
                        '.$functionsList.'
                        
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" id="condition-clipboard-icon" class="condition-clipboard-icon" title="Copy text"><path d="M280 64h40c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128C0 92.7 28.7 64 64 64h40 9.6C121 27.5 153.3 0 192 0s71 27.5 78.4 64H280zM64 112c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320c8.8 0 16-7.2 16-16V128c0-8.8-7.2-16-16-16H304v24c0 13.3-10.7 24-24 24H192 104c-13.3 0-24-10.7-24-24V112H64zm128-8a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"/></svg>

                     </ul>
                </span>
 
                '.$this->iconFont('add', array(
                    'class' => 'toggle-add-logic toggle-popdown-content',
                    'title' => esc_attr__('Add a condition', 'Microthemer'),
                    'innerHTML' => $this->add_logic_options(),
                    'data-forpopup' => "addLogic"
                )).'
            </div>
            
            <textarea id="mt-logic-expressions" name="logic[expr]" spellcheck="false"></textarea>
            
            <!--<label>Label (optional)</label>
            
            <input id="logic-label" type="text" name="logic[label]" autocomplete="off" />-->
            
            <label>Load CSS</label>
            
            <div class="mt-asset-loading-options">
                <div class="inline-or-stylesheet mt-binary-buttons">
                    <span class="load-inline binary-button-option" rel="0">Inline tag</span>
                    <input type="checkbox" name="logic[css_external]" value="1" />
                    <span class="load-stylesheet binary-button-option" rel="1">
                        <span>External file</span>
                        
                        <span class="async-stylesheet">
                            <input type="checkbox" name="logic[css_async]" value="1" />    
                            '.$this->iconFont('tick-box-unchecked', array(
                                'class' => 'fake-checkbox mt-async-toggle',
                            )).'
                            
                            <span class="mt-async-label">Async</span>
                        </span>
                    </span>
                </div>
            </div>
            
            <div id="logic-testing-wrap">
                
                <div id="logic-testing">
                
                    <span class="mtif mtif-times-circle-regular close-logic-testing"></span>
                    
                    <div class="mt-table-heading">Test page</div>
                    <div class="mt-table-heading">Load</div>
                    <div class="mt-table-heading">Analysis</div>
                    
                    <div class="mt-table-result" id="logic-test-page">
                        <div class="combobox-wrap tvr-input-wrap">
        
                            <input type="text" name="set_test_url" placeholder="Set test page" data-appto="#style-components" id="testPreviewPath" class="combobox has-arrows" rel="custom_paths" value="" autocomplete="off">
                            <span class="mt-clear-field"></span>
                            <span class="combo-arrow"></span>
                            <span class="tvr-button view-test-url">View</span>

                        </div>
                    </div>
                    <div class="mt-table-result mt-test-result" id="logic-load-result"></div>
                    <div class="mt-table-result" id="logic-statement-analysis">
   
                    </div>
                </div>
            </div>
            
            <div class="conditions-menu">
                
                <span class="tvr-button tvr-gray clear-folder-logic" title="'.esc_attr__('Clear folder logic', 'microthemer').'">Clear logic</span>
                
                <span class="tvr-button test-folder-logic" title="'.esc_attr__('Save and test folder logic', 'microthemer').'">Save & Test</span>

            </div>
   
        </div>';

        return $html;
    }

	function context_menu_form($base_key, $form){

		$html = '';
		$fields = '';

		// output the fields
		foreach ($form['fields'] as $key => $item){

			// allow for inserting custom HTML
			if (isset($item['custom'])){
				$fields.= $item['custom'];
				continue;
			}

			$class = $base_key.'-'.$key;
			$name = str_replace('-', '_', $class);
			$input_class = !empty($item['inputClass']) ? ' '.$item['inputClass'] : '';
			$input_rel = !empty($item['rel']) ? 'rel="'.$item['rel'].'"' : '';
			$label_class = !empty($item['labelClass']) ? ' class="'.$item['labelClass'].'" ' : '';

			$fields.= '<label'.$label_class.'>'.$item['label'].'</label>';

			if ($item['type'] === 'input'){

                $inputField = '<input type="text" '.$input_rel.' class="'.$class.'-input'.$input_class.'" name="'.$name.'" />';

                if (!empty($item['combo'])){
	                $fields.= '<div class="tvr-input-wrap">' .$inputField
                                  . '<span class="mt-clear-field"></span>'
                                  . '<span class="combo-arrow"></span>'
                              .'</div>';
                } else {
	                $fields.= $inputField;
                }



			}

		}

		// add the button
		$button_class = !empty($form['button']['class']) ? ' '.$form['button']['class'] : '';
		$fields.= '<span class="tvr-button '.$base_key.'-button '.$button_class.'">'.$form['button']['text'].'</span>';

		// wrap if required
		if (!empty($form['wrap'])){
			$wrap_class = !empty($form['wrapClass']) ? ' '.$form['wrapClass'] : '';
			$html.= '<div class="mt-cm-form '.$base_key.'-form'.$wrap_class.'">'.$fields.'</div>';
		} else {
			$html.= $fields;
		}

		return $html;
	}

	function context_menu_actions($base_key, $config){

		$icon_html = '';
		$html = '';

		// loop actions
		foreach ($config['actions'] as $key => $item){

			// allow for inserting custom HTML
			if (!empty($item['custom'])){
				$icon_html.= $item['custom'];
				continue;
			}

			$icon_html.= $this->iconFont($key, $item);
		}

		// wrap if required
		if (!empty($config['wrap'])){
			$wrap_class = !empty($config['wrapClass']) ? ' '.$config['wrapClass'] : '';
			$html.= '<div class="mt-cm-actions '.$base_key.'-actions'.$wrap_class.'">'.$icon_html.'</div>';
		} else {
			$html.= $icon_html;
		}

		return $html;
	}

	// Output context menu content in a structured way
	function context_menu_content($config){

		$base_key = $config['base_key'];
		$title = !empty($config['title']) ? $config['title'] : esc_html__('Options', 'microthemer');
		$class = !empty($config['class']) ? ' '.$config['class'] : '';
		$id = !empty($config['id']) ? $config['id'] : 'cm-'.$base_key;
		$html = '';

		// loop through the sections
		if (!empty($config['sections']) && is_array($config['sections'])){
			foreach ($config['sections'] as $i => $section){
				$html.= $section;
			}
		}

		return '
			    <div id="'.$id.'" class="cm-'.$base_key.$class.'" data-title="'.$title.'">
			        '. $html .'
                </div>';

	}

	function context_menu_heading($heading = '', $config = array()){

		$title_class = !empty($config['title_class'])
			? ' '.$config['title_class']
			: '';

		return '
			    <div class="mt-panel-header">
                    <div class="mt-panel-title'.$title_class.'">'.$heading.'</div>
                    '.( !empty($config['close']) ?  $this->iconFont('times-circle-regular', array('class' => 'close-context-menu')) : '').'                                   
                </div>';
	}

	function layout_element_height($side, $abort = false){

		if ($abort){
			return '';
		}

		return ' style="height: ' . $this->preferences['layout'][$side]['size'] . 'px"';
	}

	function panel_resizers($config){
		$d = $config['dimension'];
		$grid_axis =  $d === 'width' ? 'column' : 'height';
		$context = $config['context'];
		$html = '';
		for ($x = 1; $x <= $config['total']; $x++) {
			$side = $x <= $config['side_division'] ? $config['side_1'] : $config['side_2'];
			$html.= '<div class="mt-panel-resizer mt-'.$d.'-resizer-'.$x.' mt-'.$d.'-resizer '
			        .$side.'-panel-resizer mt-'.$context.'-resizer-'.$x.'" 
                 data-template-'.$grid_axis.'="'.$x.'" data-side="'.$side.'" data-context="'.$context.'"></div>';
		}
		return $html;
	}

	// section html
	function section_html($section_name, $array) {
		$html = '';
		$html.= '
				<li id="opts-'.$section_name.'" class="section-wrap section-tag">
					'.$this->all_selectors_html($section_name, $array).'
				</li>';
		return $html;
	}

	function all_selectors_html($section_name, $array, $force_load = 0) {
		$html = '';
		$html.= '<ul class="selector-sub">';
		// loop the CSS selectors if they exist
		/*if (!$this->optimisation_test){
					if ( is_array($array) ) {
						$this->sel_loop_count = 0; // reset count of selector in section
						foreach ($array as $css_selector => $sel_array) {
							if ($css_selector == 'this') continue;
							++$this->sel_loop_count;
							$html .= $this->single_selector_html($section_name, $css_selector, $sel_array, $force_load);
						}
					}
				}*/
		$html.= '</ul>';
		return $html;
	}

	// selector html
	function single_selector_html($section_name, $css_selector, $array, $force_load = 0) {
		++$this->sel_option_count;
		$html = '';
		$css_selector = esc_attr($css_selector); //=esc
		// disable sections locked by trial
		if (!$this->preferences['buyer_validated'] and $this->sel_option_count > 15) {
			$trial_disabled = 'trial-disabled';
		} else {
			$trial_disabled = '';
		}
		$html.= '<li id="opts-'.$section_name.'-'.$css_selector.'"
				class="selector-tag selector-wrap '.$trial_disabled.'">';

		// only load style options if we need to force load
		if ($force_load == 1) {
			$html.= $this->all_option_groups_html($section_name, $css_selector, $array);
		}
		$html.= '</li>';
		return $html;
	}

	// determine the number of selectors in the array
	function selector_count_state($array) {

		// with empty folder, might not be an array
		if (!is_array($array)){
			return 0;
		}

		$selector_count_state = count($array);
		$selector_count_recursive = count($array, COUNT_RECURSIVE);

		// if the 2 values are the same, the $selector_count_state variable refers to an empty value
		if ($selector_count_state == $selector_count_recursive) {
			$selector_count_state = 0;
		}
		// [this] will be counted as extra selector. Fix.
		if ($selector_count_state > 0 and array_key_exists('this', $array)){
			--$selector_count_state;
		}
		return $selector_count_state;
	}

	function css_group_icons(){

		// display pg icons
		$html = '
				<ul class="styling-option-icons scrollable-area">
				    <li class="mt-panel-column-heading" data-short-text="'.esc_attr__('CSS', 'microthemer').'" data-long-text="'.esc_attr__('CSS Properties', 'microthemer').'"></li>';

		// display the pg icons
		$i = -1;
		$done = array();
		foreach ($this->propertyoptions as $property_group_name => $junk) {

			$i++;
			$class = '';

			// check if we are starting a new property group category
			$first_item = $this->get_first_item( $this->propertyoptions[$property_group_name] );
			$new_pg_cat = (!empty($first_item['new_pg_cat']) and empty($done[$property_group_name]))
				? $first_item['new_pg_cat']
				: false;
			$close_pg_cat_li = $i === 0 ? '' : '</div></li>';

			// if new cat, close previous and start new
			if ($new_pg_cat){
				$html.= $close_pg_cat_li . '
                        <li class="new-pg-cat new-pg-cat-'.$property_group_name.'">
                            <div class="pg-cat-sub">
                                <div class="new-pg-cat-label">'.$new_pg_cat.'</div>';
				$done[$property_group_name] = true;
			}

			// icon
			$icon_name = str_replace('_', '-', $property_group_name);
			$icon_type = 'f';
			$icon_dir = 'svg-min/';

			if ($property_group_name === 'gradient'){
				$icon_type = 'svg';
			}

			$label = $this->property_option_groups[$property_group_name];

			$html.= $this->icon($icon_name, array(
				'type' => $icon_type,
				'dir' => $icon_dir,
				'class' => 'pg-icon pg-icon-'.$property_group_name,
				'rel' => $property_group_name,
				'title' => $label,
				'adjacentText' => array(
					'text' => $label,
					'class' => 'mti-text pg-icon-text-label'
				),
				'wrap' => array(
					'class' => 'mti-wrap pg-icon-wrap pg-icon-wrap-' . $property_group_name,
					'rel' => $property_group_name,
					//'title' => $label,
				),
				// 'wrapClass' => $property_group_name. '-wrap',

			));

		}

		// close new-pg-cat item and list
		$html.='
                    </div></li>
				</ul>';

		return $html;
	}


	// display property group icons and options
	function all_option_groups_html($section_name, $css_selector, $array){

		// get the last viewed property group
		$pg_focus = ( !empty($array['pg_focus']) ) ? $array['pg_focus'] : '';

		// display actual fields
		$html ='
				<ul class="styling-options">';

		// do all-device and MQ fields
		foreach ($this->propertyoptions as $property_group_name => $junk) {
			$html.= $this->single_option_group_html(
				$section_name,
				$css_selector,
				$array,
				$property_group_name,
				$pg_focus);
		}

		$html.=

			// whole property group
			$this->mt_hor_scroll_buttons('style', 'li') .

			// single template/auto column
			$this->mt_hor_scroll_buttons('gridcolumns', 'li') .

			// single template/auto row
			$this->mt_hor_scroll_buttons('gridrows', 'li') .

			'</ul>';

		return $html;
	}

	function mt_hor_scroll_buttons($type, $el = 'li'){
		return '
                    <'.$el.' class="scroll-lr-buttons scroll-lr-buttons-'.$type.'">
                        <span class="mt-scroll-row mt-scroll-left mt-scroll-'.$type.'" data-type="'.$type.'"></span>
                        <span class="mt-scroll-row mt-scroll-right mt-scroll-'.$type.'" data-type="'.$type.'"></span>
                    </'.$el.'>';
	}

	// if a pg group has loaded but no values were added we don't want to load it into the dom
	function pg_has_values($array){
		if (empty($array) or !is_array($array)){
			return false;
		}
		$no_values = true;
		foreach ($array as $key => $value){
			// must allow zero values!
			if ( !empty($value) or $value === 0 or $value === '0'){
				$no_values = false;
				break;
			}
		}
		return $no_values ? false : true;
	}

	// are legacy values present for pg group?
	function has_legacy_values($styles, $property_group_name){
		$legacy_values = false;
		if (!empty($this->legacy_groups[$property_group_name]) and is_array($this->legacy_groups[$property_group_name])){
			foreach ($this->legacy_groups[$property_group_name] as $leg_group => $array){
				// check if the pg has values and they are specifically ones have have moved to this pg
				if ( !empty($styles[$leg_group]) and
				     $this->pg_has_values($styles[$leg_group]) and
				     $this->props_moved_to_this_pg($styles[$leg_group], $array)){
					$legacy_values = $styles[$leg_group];
					break;
				}
			}
		}
		return $legacy_values;
	}

	function pg_has_values_inc_legacy($array, $property_group_name){
		$styles_found = false;
		if (!empty($array['styles'][$property_group_name]) and $this->pg_has_values($array['styles'][$property_group_name])) {
			$styles_found['cur_leg'] = 'current';
		} elseif (!empty($array['styles']) and $this->has_legacy_values($array['styles'], $property_group_name)){
			$styles_found['cur_leg'] = 'legacy';
		}
		return $styles_found;
	}

	// look for any values in property group, including legacy values - and optionally, media query values
	function pg_has_values_inc_legacy_inc_mq($section_name, $css_selector, $array, $property_group_name){

		// first just look for values in all devices (most likely)
		if ($styles_found = $this->pg_has_values_inc_legacy($array, $property_group_name)) {
			$styles_found['dev_mq'] = 'all-devices';
			return $styles_found;
		} else {
			// look for media query tabs with values too
			// - use preferences mqs for loop because any mq keys in options not in there will not be output
			// also active_queries doesn't currently get updated after deleting an MQ tab via popup
			if (is_array($this->preferences['m_queries'])) {
				foreach ($this->preferences['m_queries'] as $mq_key => $junk) {
					// now check $options
					if (!empty($this->options['non_section']['m_query'][$mq_key][$section_name][$css_selector])){
						$array = $this->options['non_section']['m_query'][$mq_key][$section_name][$css_selector];
						if ($styles_found = $this->pg_has_values_inc_legacy($array, $property_group_name)) {
							$styles_found['dev_mq'] = 'mq';
							break;
						}
					}
				}
			}

			/*
					if (!empty($this->options['non_section']['active_queries']) and
						is_array($this->options['non_section']['active_queries'])) {
						foreach ($this->options['non_section']['active_queries'] as $mq_key => $junk) { // here
							if (!empty($this->options['non_section']['m_query'][$mq_key][$section_name][$css_selector])){
								$array = $this->options['non_section']['m_query'][$mq_key][$section_name][$css_selector];
								if ($styles_found = $this->pg_has_values_inc_legacy($array, $property_group_name)) {
									$styles_found['dev_mq'] = 'mq';
									break;
								}
							}
						}
					}*/
			return $styles_found;
		}
	}

	// does the selector contain any styles?
	function selector_has_values($section_name, $css_selector, $array, $deep){

		return !empty($array['compiled_css']);

		/*$style_count_state = 0;
				foreach ($this->propertyoptions as $property_group_name => $junk) {
					// ui menus need deep analysis of settings, but stylesheet only looks at mq/regular arrays one at a time
					// and legacy values will have already been dealt with
					if ($deep){
						if ($this->pg_has_values_inc_legacy_inc_mq($section_name, $css_selector, $array, $property_group_name)) {
							++$style_count_state;
						}
					} else {
						if (!empty($array['styles'][$property_group_name]) and
						    $this->pg_has_values($array['styles'][$property_group_name])) {
							++$style_count_state;
						}
					}
				}
				return $style_count_state;*/
	}

	// does the folder contain any styles?
	function section_has_values($section_name, $array, $deep){
		$style_count_state = 0;
		if ( is_array($array) ) {
			foreach ($array as $css_selector => $sel_array) {
				if ($this->selector_has_values($section_name, $css_selector, $sel_array, $deep)){
					++$style_count_state;
				}
			}
		}
		return $style_count_state;
	}

	// does the $ui_data array have values?
	function ui_data_has_values($ui_data, $deep){
		$style_count_state = 0;
		if (!empty($ui_data) and is_array($ui_data)){
			foreach ($ui_data as $section_name => $array){
				if ($this->section_has_values($section_name, $array, $deep)){
					++$style_count_state;
				}
			}
		}
		return $style_count_state;
	}

	// ensure that specific legacy props have moved to this pg
	function props_moved_to_this_pg($leg_group_styles, $array){
		// loop through legacy props to see if style values exist
		if (is_array($array)){
			foreach ($array as $legacy_prop => $legacy_prop_legend_key){
				if (!empty($leg_group_styles[$legacy_prop])){
					return true;
				}
			}
		}
		return false;
	}

	// determine if options property is legacy or not
	function is_legacy_prop($property_group_name, $property){
		$legacy = false;
		foreach ($this->legacy_groups as $new_group => $array){
			foreach ($array as $leg_group => $arr2){
				foreach ($arr2 as $leg_prop => $legacy_prop_legend_key) {
					if ($property_group_name == $leg_group and $property == $leg_prop) {
						$legacy = $new_group; // return new group for legacy property
						break;
					}
				}
			}
		}
		return $legacy;
	}

	// function to get legacy value (inc !important) if it exists
	function populate_from_legacy_if_exists($styles, $sel_imp_array, $prop){
		$target_leg_prop = false;
		$legacy_adjusted['value'] = false;
		$legacy_adjusted['imp'] = '';
		foreach ($this->legacy_groups as $pg => $leg_group_array){
			foreach ($leg_group_array as $leg_group => $leg_prop_array){
				// look for prop in value: 1 = same as key
				foreach ($leg_prop_array as $leg_prop => $legend_key){
					// prop may have legacy values
					if ( ($prop == $leg_prop and $legend_key) == 1 or $prop == $legend_key){
						$target_leg_prop = $leg_prop;

					} elseif (is_array($legend_key)){
						// loop through array
						if (in_array($prop, $legend_key)){
							$target_leg_prop = $leg_prop;
						}
					}
					// if the property had a previous location, check for a value
					if ($target_leg_prop){
						if (!empty($styles[$leg_group][$target_leg_prop])){
							$legacy_adjusted['value'] = $styles[$leg_group][$target_leg_prop];
							if (!empty($sel_imp_array[$leg_group][$target_leg_prop])){
								$legacy_adjusted['imp'] = $sel_imp_array[$leg_group][$target_leg_prop];
							}
							break 3; // break out of all loops
						}
					}
				}
			}
		}
		return $legacy_adjusted;
	}

	// new system that doesn't restrict section name format
	function get_folder_name_inc_legacy($section_name, $array){
		// legacy 1
		$display_section_name = ucwords(str_replace('_', ' ', $section_name));
		// legacy 2 (abandoned because I don't like having this stored in non_section)
		if (!empty($this->options['non_section']['display_name'][$section_name])) {
			$display_section_name = $this->options['non_section']['display_name'][$section_name];
		}
		// current
		if (!empty($this->options[$section_name]['this']['label'])) {
			$display_section_name = $this->options[$section_name]['this']['label'];
		}
		return $display_section_name;
	}


	// output all the options for a given property group
	function single_option_group_html(
		$section_name,
		$css_selector,
		$array,
		$property_group_name,
		$pg_focus){

		// check if the property group should be "active" (in focus)
		$pg_show_class = ( $property_group_name == $pg_focus ) ? 'show' : '';

		// main pg wrapper
		$html ='
				<li id="opts-'.$section_name.'-'.$css_selector.'-'.$property_group_name.'"
						 class="group-tag group-tag-'.$property_group_name.' '.$pg_show_class.'">';

		// output all devices and MQ fields
		$html.= $this->single_device_fields(
			$section_name,
			$css_selector,
			$array,
			$property_group_name,
			$pg_show_class);

		$html.= '
				</li>';

		return $html;
	}

	// function for outputting all devices and MQs without repeating code
	function single_device_fields(
		$section_name,
		$css_selector,
		$array,
		$property_group_name,
		$pg_show_class){

		$html = '';

		// output all fields
		foreach ($this->combined_devices() as $key => $m_query){

			$property_group_array = false;
			$con = 'reg';

			// get array val if MQ
			if ($key != 'all-devices'){
				$con = 'mq';
				$array = false;
				if (!empty($this->options['non_section']['m_query'][$key][$section_name][$css_selector])) {
					$array = $this->options['non_section']['m_query'][$key][$section_name][$css_selector];
				}
			}

			// need to check for existing styles (inc legacy)
			if ( $array and $styles_found = $this->pg_has_values_inc_legacy(
					$array,
					$property_group_name) ) {

				// if there are current styles for the all devices tab, retrieve them
				if ($styles_found['cur_leg'] == 'current'){
					$property_group_array = $array['styles'][$property_group_name];
				}

				// if only legacy values exist set empty array so inputs are displayed
				else {
					$property_group_array = array();
				}
			}

			// show fields even if no values if tab is current
			if ( !$property_group_array and $this->preferences['mq_device_focus'] == $key and $pg_show_class){
				$property_group_array = array();
			}

			/*$this->debug_custom.= $section_name.'> '.$css_selector .'> '
						. $m_query['label'] .' ('.$key .') > '. $property_group_name .'> '
						. print_r( $property_group_array, true ). 'Arr: ' . is_array($array) . "\n\n";*/

			// output fields if needed
			if ( is_array( $property_group_array ) ) {

				// visible if tab is active
				$show_class = ( $this->preferences['mq_device_focus'] == $key ) ? 'show' : '';

				// pass current CSS selector
				$sel_code = '';
				if (!empty($array['label'])){ // not sure why this would be - troubleshoot
					$sel_meta = explode('|', $array['label']);
					$sel_code = !empty($sel_meta[1]) ? $sel_meta[1] : '';
				}

				// this is contained in a separate function because the li always needs to exist
				// as a wrapper for the tmpl div
				$html.= $this->single_option_fields(
					$section_name,
					$css_selector,
					$array,
					$property_group_array,
					$property_group_name,
					$show_class,
					false,
					$key,
					$con,
					$sel_code);

			}
		}

		return $html;
	}

	function back_to_properties($pg_slug){
		return '
                <div class="back-to-properties">
                    '.$this->iconFont('arrow-alt-circle-left', array(
				'class' => 'back-to-properties-icon',
				'adjacentText' => array(
					'text' => $this->property_option_groups[$pg_slug],
					'class' => 'mti-text back-to-properties-text'
				)
			)).'
                </div>';
	}

	// the options fields part of the property group (which can be added as templates)
	function single_option_fields(
		$section_name,
		$css_selector,
		$array,
		$property_group_array,
		$property_group_name,
		$show_class,
		$template = false,
		$key = 'all-devices',
		$con = 'reg',
		$sel_code = 'selector_code'){

		// is this template HTML?
		$id = ( $template ) ? 'id="option-group-template-'.$property_group_name. '"' : '';

		// add certain classes based on property values
		$conditional_classes = '';

		if (!$template){

			// rotation of flex icons
			$special_flex_direction = $this->array_matches(
				$property_group_array,
				'flex_direction',
				'contains',
				array('column', 'row-reverse')
			);

			if ($special_flex_direction){
				$conditional_classes.= ' flex-direction-'.$special_flex_direction;
			}

			// show container or item fields (flex and grid)
			$contItem = array('flexitem', 'griditem');
			foreach ($contItem as $item_type){
				if ($this->array_matches( $array,'pg_'.$item_type)){
					$conditional_classes.= ' show-'.$item_type;
				}
			}
		}

		// do all-devices fields
		$html = '
				<div '.$id.' class="property-fields property-'.$property_group_name.'
				property-fields-'. $key . ' ' . $conditional_classes. ' ' . $show_class.'">
				<div class="pg-inner">
					';


		// option to go back to properties
		$html.= $this->back_to_properties($property_group_name);

		// line divider for horizontal tabbed grid fields
		if ($property_group_name === 'grid'){
			$html.= '<div class="grid-divider-or-spacer"></div>';
		}

		// merge to allow for new properties added to property-options.inc.php (array with values must come 2nd)
		$property_group_array = array_merge($this->propertyoptions[$property_group_name], $property_group_array);

		$this->group_spacer_count = 0;

		// output individual property fields
		foreach ($property_group_array as $property => $value) {

			// filter prop
			$property = esc_attr($property);

			/* if a new CSS property has been added with array_merge(), $value will be something like:
					Array ( [label] => Left [default_unit] => px [icon] => position_left )
					- so just set to nothing if it's an array
					*/
			//$value = ( !is_array($value) ) ? esc_attr($value) : ''; todo I hope this is OK...

			// format input fields
			$html.= $this->resolve_input_fields(
				$section_name,
				$css_selector,
				$array,
				$property_group_array,
				$property_group_name,
				$property,
				$value,
				$con,
				$key,
				$sel_code
			);
		}

		$html.= '
				</div></div>';

		return $html;
	}

	// check if an array key/value matches
	function array_matches($array, $key, $logic = 'isset', $value = null) {

		// false if not set
		if ( !isset($array[$key]) ) return false;

		// true if only checking if set
		if ($logic == 'isset') return true;

		// compare values
		$arr_val = $array[$key];
		switch ($logic) {
			case 'contains':
				if (is_array($value)){
					foreach ($value as $v){
						if (strpos($arr_val, $v) !== false){
							return $arr_val;
						}
					}
				} else {
					if ( strpos($arr_val, $value) !== false) {
						return $arr_val;
					}
				}
			case 'is':
				if ( $arr_val == $value ) {
					return $arr_val;
				}
			case 'isnot':
				if ( $arr_val != $value ) {
					return $arr_val;
				}
				break;
		}

		return false;
	}



	// format media query min/max width (height later) and units
	function mq_min_max($pref_array){
		// check the media query min/max values
		foreach($pref_array['m_queries'] as $key => $mq_array) {

            $m_conditions = array('min', 'max');
            $q = $mq_array['query'];

            foreach ($m_conditions as $condition){
				$matches = $this->get_screen_size($q, $condition);
				$pref_array['m_queries'][$key][$condition] = 0;
				if ($matches){
					$pref_array['m_queries'][$key][$condition] = intval($matches[1]);
					$pref_array['m_queries'][$key][$condition.'_unit'] = $matches[2];
				}
			}

            // flag if it's a container query
			if (preg_match('/@container/', $q, $matches)) {
				//echo print_r($matches);
				$pref_array['m_queries'][$key]['container'] = 1;
			}

		}
		return $pref_array['m_queries'];
	}

	// compare the original set of media queries with a new config to detect deleted mqs
	function deleted_media_queries($orig_media_queries, $new_media_queries){

		$deleted = false;

		foreach($orig_media_queries as $key => $array){
			if (empty($new_media_queries[$key])){
				$deleted[] = $key;
			}
		}

		return $deleted;
	}

	// compare the original set of media queries with a new config to detect deleted mqs
	function clean_deleted_media_queries($orig_media_queries, $new_media_queries){

		if ($deleted = $this->deleted_media_queries($orig_media_queries, $new_media_queries)){

			$non_section = &$this->options['non_section'];

			foreach($deleted as $i => $key){
				if (!empty($non_section['m_query'][$key])){
					unset($non_section['m_query'][$key]);
				}
				if (!empty($non_section['important']['m_query'][$key])){
					unset($non_section['important']['m_query'][$key]);
				}
			}

			// save
			update_option($this->optionsName, $this->options);

			/*$this->log(
						esc_html__('Deleted media query cleaned', 'microthemer'),
						'<pre>' . print_r($deleted, true) . print_r($this->options['non_section']['m_query'], true). '</pre>',
						'notice'
					);*/

		}

	}

	// The new UI always shows the MQ tabs.
	// This happens even when no selectors are showing, so a different approach is needed
	function global_media_query_tabs(){

		/*'.$this->icon('border-white').'
				                           <span class="font-awesome-icon-test"></span>
				                                                                  '.$this->icon('padding-margin', array('type' => 'sym')).'
                     '.$this->icon('gradient', array('type' => 'svg')).'
                     '.$this->icon('check-circle', array('type' => 'svg')).'*/

		$html = '
                <div class="query-tabs-wrap pending-overflow-check">
                
                    <div class="query-tabs menu-style-tabs">
                    
                    ';

		// display tabs
		foreach ($this->combined_devices() as $key => $m_query){

			// don't show if hidden by the user
			if ( isset($m_query['hide']) ) continue;

			// should the tab be active? - let JS handle this
			//$class = ($this->preferences['mq_device_focus'] == $key) ? 'active' : '';

			$menu_trigger = $this->iconFont('dots-horizontal', array(
				'class' => 'item-context-menu devices-context-menu-trigger',
				'data-forpopup' => "contextMenu"
			));

			$html.= '
                            <span class="mt-tab mq-tab mq-tab-'.$key.'" rel="'.$key.'" title="' . esc_attr($m_query['query']). '">' .
			        $menu_trigger .
			        '<span class="mt-tab-txt mq-tab-txt">' . $m_query['label']. '</span>
                            </span>';
		}


		$html.= '


                </div>
                   
				</div>' . $this->mt_hor_scroll_buttons('responsive', 'div');

		return $html;
	}

	// check for 2 values on border-radius corner
	function check_two_radius($radius, $c2){
		$check = explode(' ', $radius);
		// if there are more than two values
		if (!empty($check[1])){
			$radius = $check[0];
			$c2[] = $check[1];
		} else {
			// if ANY 2nd corners have been found so far, but not on this occasion, default to the existing radius
			if ($c2){
				$c2[] = $radius;
			}
		}
		$corner = array($radius, $c2);
		return $corner;
	}

	// check if e.g. box-sahdow-x has none/inherit/initial
	function is_single_keyword($val) {
		$keywords = array('none', 'initial', 'inherit');
		// $strict is needed to prevent 0 returning true
		// https://stackoverflow.com/questions/16787762/in-array-returns-true-if-needle-is-0
		return in_array($val, $keywords, true);
	}

	function is_time_prop($property){
		return strpos($property, 'duration') !== false || strpos($property, 'delay') !== false;
	}

	function is_non_length_unit($factoryUnit, $prop){
		return $factoryUnit === 's' || $factoryUnit === 'deg' || ($factoryUnit === ''); //&& $prop !== 'line_height' -
		// we removed line-height too as this is just for setting units globally,
		// and we don't want to change line-height from default non-unit when using 'ALL Units' option
	}





	function eq_str($name){
		$eq_signs = 25-strlen($name);
		$eq_signs = $eq_signs > -1 ? $eq_signs : 0;
		$eq_str = '';
		for ($x = $eq_signs; $x >= 0; $x--) {
			$eq_str.= '=';
		}
		return $eq_str;
	}




	function g_url_with_subsets($g_url = false, $found_gf_subsets = false, $gfont_subset = false){

		$g_url = $g_url !== false ? $g_url : $this->preferences['g_url'];

		if (empty($g_url)){
			return '';
		}

		$found_gf_subsets = $found_gf_subsets !== false ? $found_gf_subsets : $this->preferences['found_gf_subsets'];
		$gfont_subset = $gfont_subset !== false ? $gfont_subset : $this->preferences['gfont_subset'];
		$subsets = array();

		// add custom fonts subset url param if defined in preferences
		if (!empty($gfont_subset)) {
			preg_match('/subset=(.+)/', $gfont_subset, $matches);
			if (!empty($matches[1])){
				$subsets[] = $matches[1];
			}
		}

		// combine with subsets found in MT settings
		if (!empty($found_gf_subsets) and is_array($found_gf_subsets) and count($found_gf_subsets)){
			$subsets = array_merge($subsets, $found_gf_subsets);
			$subsets = array_unique($subsets);
		}

		if (count($subsets)){
			$g_url.= '&subset=' . implode(',', $subsets);
		}

		return $g_url;
	}



	// update ie specific stylesheets
	/*function update_ie_sheets() {
				if ( !empty($this->options['non_section']['ie_css']) and
				     is_array($this->options['non_section']['ie_css']) ) {
					foreach ($this->options['non_section']['ie_css'] as $key => $val) {
						// if has custom styles
						$trim_val = trim($val);
						if (!empty($trim_val)) {
							$pref_array['ie_css'][$key] = $this->custom_code['ie_css'][$key]['cond'];
						} else {
							// no value for stylesheet specified
							$pref_array['ie_css'][$key] = 0;
						}
						// always update file otherwise CSS can't be cleared
						$file_stub = $this->preferences['draft_mode'] ? 'draft-' : '';
						$stylesheet = $this->micro_root_dir.$file_stub.'ie-'.$key.'.css';
						$this->write_file($stylesheet, $val);
					}
					// update the preferences so that the stylesheets are called in the <head>
					$this->savePreferences($pref_array);
				}
			}*/


	// write settings to .json file
	function update_json_file($theme, $context = '', $export_full = false, $preferences = false) {

		$theme = sanitize_file_name(sanitize_title($theme));

		// create micro theme of 'new' has been requested
		if ($context == 'new') {
			// Check for micro theme with same name
			if ($alt_name = $this->rename_if_required($this->micro_root_dir, $theme)) {
				$theme = $alt_name; // $alt_name is false if no rename was required
			}
			if (!$this->create_micro_theme($theme, 'export', ''))
				return false;
		}

        // json file
		$json_file = $this->micro_root_dir.$theme.'/config.json';
        $task = file_exists($json_file) ? 'updated' : 'created';

        // simple test - the json file was not being overwritten for one user, so delete if it exists
        if ($task === 'updated'){
            unlink($json_file);
        }

		// Create new file if it doesn't already exist
		if (!file_exists($json_file)) {

            // create directory if it doesn't exist - not doing so caused fopen issues after pack delete,
            // unless page refreshed
			$dir = dirname($json_file);
            if (!is_dir($dir)){
	            if ( !wp_mkdir_p($dir) ) {
		            $this->log(
			            esc_html__('/micro-themes/'.$theme.' create directory error', 'microthemer'),
			            '<p>' . sprintf(
				            esc_html__('WordPress was not able to create the directory: %s', 'microthemer'),
				            $this->root_rel($dir)
			            ) . $this->permissionshelp . '</p>'
		            );
		            return false;
	            }
            }

			if (!$write_file = @fopen($json_file, 'w')) { // this creates a blank file for writing
				$this->log(
					esc_html__('Create json error', 'microthemer'),
					'<p>' . esc_html__('WordPress does not have permission to create: ', 'microthemer')
					. $this->root_rel($json_file) . $this->permissionshelp.'</p>'
				);
				return false;
			}
		}

		// check if json file is writable
		if (!is_writable($json_file)){
			$this->log(
				esc_html__('Write json error', 'microthemer'),
				'<p>' . esc_html__('WordPress does not have "write" permission for: ', 'microthemer')
				. $this->root_rel($json_file) . '. '.$this->permissionshelp.'</p>'
			);
			return false;
		}

		// tap into WordPress native JSON functions
		/*if( !class_exists('Moxiecode_JSON') ) {
					require_once($this->thisplugindir . 'includes/class-json.php');
				}
				$json_object = new Moxiecode_JSON();*/

		// copy full options to var for filtering
		$json_data = $this->options;

		// include the user's current media queries form importing back
		$json_data['non_section']['active_queries'] = $this->preferences['m_queries'];

		// unless full, loop through full options - removing sections
		if (!$export_full){

			foreach ($this->options as $section_name => $array) {

				// if the section wasn't selected, remove it from json data var (along with the view_state var)
				if ( empty($this->serialised_post['export_sections'])
				     or (!array_key_exists($section_name, $this->serialised_post['export_sections']) )
				        and $section_name != 'non_section') {

					// remove the regular section data and view states
					unset($json_data[$section_name]);
					unset($json_data['non_section']['view_state'][$section_name]);

					// need to remove all media query settings for unchecked sections too
					if (!empty($json_data['non_section']['m_query']) and
					    is_array($json_data['non_section']['m_query'])) {
						foreach ($json_data['non_section']['m_query'] as $m_key => $array) {
							unset($json_data['non_section']['m_query'][$m_key][$section_name]);
						}
					}

					// and all of the important values
					if (!empty($json_data['non_section']['important']['m_query']) and
					    is_array($json_data['non_section']['important']['m_query'])) {
						foreach ($json_data['non_section']['important']['m_query'] as $m_key => $array) {
							unset($json_data['non_section']['important']['m_query'][$m_key][$section_name]);
						}
					}
				}
			}
		}

		// include preferences in export if passed in
		if ($preferences){
			$json_data['non_section']['exported_preferences'] = $preferences;
		}

		// set hand-coded css to nothing if not marked for export
		if ( empty($this->serialised_post['export_sections']['hand_coded_css'])) {
			$json_data['non_section']['hand_coded_css'] = '';
		}

		// set js to nothing if not marked for export
		if ( empty($this->serialised_post['export_sections']['js'])) {
			$json_data['non_section']['js'] = '';
		}

		// ie too
		/*foreach ($this->preferences['ie_css'] as $key => $value) {
					if ( empty($this->serialised_post['export_sections']['ie_css'][$key])) {
						$json_data['non_section']['ie_css'][$key] = '';
					}
				}*/

		// create debug selective export file if specified at top of script
		if ($this->debug_selective_export) {
			$data = '';
			$debug_file = $this->debug_dir . 'debug-selective-export.txt';
			$write_file = @fopen($debug_file, 'w');
			$data.= esc_html__('The Selectively Exported Options', 'microthemer') . "\n\n";
			$data.= print_r($json_data, true);
			$data.= "\n\n" . esc_html__('The Full Options', 'microthemer') . "\n\n";
			$data.= print_r($this->options, true);
			fwrite($write_file, $data);
			fclose($write_file);
		}

		// write data to json file
		if ($data = json_encode($json_data)) {
			// the file will be created if it doesn't exist. otherwise it is overwritten.
			$write_file = @fopen($json_file, 'w');
			fwrite($write_file, $data);
			fclose($write_file);
			// report
			if ($task == 'updated'){
				$this->log(
					esc_html__('Settings exported', 'microthemer'),
					'<p>' . esc_html__('Your settings were successfully exported to: ',
						'microthemer') . '<b>'.$theme.'</b></p>',
					'notice'
				);
			}
		}
		else {
			$this->log(
				esc_html__('Encode json error', 'microthemer'),
				'<p>' . esc_html__('WordPress failed to convert your settings into json.', 'microthemer') . '</p>'
			);
		}

		return $theme; // sanitised theme name
	}

	// pre-process import or restore data
	function filter_incoming_data($con, $data){

		$filtered_json = $data;

		$active_enq_js = !empty($filtered_json['non_section']['active_enq_js'])
			? $filtered_json['non_section']['active_enq_js']
			: false;

		// compare media queries in import/restore to existing
		$mq_analysis = $this->analyse_mqs(
			$filtered_json['non_section']['active_queries'],
			$this->preferences['m_queries']
		);

		// check if enq_js needs to be added
		if ($this->new_enq_js(
			$this->preferences['enq_js'],
			$active_enq_js
		)){
			$pref_array['enq_js'] = array_merge(
				$this->preferences['enq_js'],
				$active_enq_js
			);
			if ($this->savePreferences($pref_array)) {
				$this->log(
					esc_html__('JS libraries added', 'microthemer'),
					'<p>' . esc_html__('The settings you added depend on JavaScript libraries that are different from your current setup. These have been imported to ensure correct functioning.',
						'microthemer') . '</p>',
					'warning'
				);
			}
		}

		// check if the import/restore contains the same media queries but with different keys
		// if so, set the keys the same.
		// new queries also trigger this because new queries get assigned fresh keys
		if ($mq_analysis['replacements_needed']){
			foreach ($mq_analysis['replacements'] as $student_key => $role_model_key){
				$filtered_json = $this->replace_mq_keys($student_key, $role_model_key, $filtered_json);
			}
		}

		// check for new media queries in the import
		if ($mq_analysis['new']) {

			// merge the new queries with the current workspace mqs
			$pref_array['m_queries'] = array_merge(
				$this->preferences['m_queries'],
				$mq_analysis['new']
			);

			// format media query min/max width (height later) and units
			$pref_array['m_queries'] = $this->mq_min_max($pref_array);

			// save the new queries
			if ($this->savePreferences($pref_array)) {
				$this->log(
					esc_html__('Media queries added', 'microthemer'),
					'<p>' . esc_html__('The settings you added contain media queries that are different from the ones in your current setup. In order for all styles to display correctly, these additional media queries have been imported into your workspace.',
						'microthemer') . '</p>
								<p>' . wp_kses(
						sprintf(
							__('Please <span %s>review (and possibly rename) the imported media queries</span>. Note: they are marked with "(imp)", which you can remove from the label name once you\'ve reviewed them.', 'microthemer'),
							'class="link show-dialog" rel="edit-media-queries"' ),
						array( 'span' => array() )
					) . ' </p>',
					'warning'
				);
			}
		}

		// active_queries are just used for import now. Unset as they have served their purpose
		unset($filtered_json['non_section']['active_queries']);

		// just for debugging
		if ($this->debug_import) {

			// get this before modifying in any way
			//$debug_mqs['incoming_active_queries'] = $data['non_section']['active_queries'];
			$debug_mqs['orig'] = $this->preferences['m_queries'];
			$debug_mqs['new'] = $mq_analysis['new'];
			$debug_mqs['merged'] = $debug_mqs['new'] ? $pref_array['m_queries'] : false;
			$debug_mqs['mq_analysis'] = $mq_analysis;

			$debug_file = $this->debug_dir . 'debug-'.$con.'.txt';
			$write_file = @fopen($debug_file, 'w');
			$data = '';
			$data.= "\n\n### 1. Key Debug Analysis \n\n";
			$data.= print_r($debug_mqs, true);
			$data.= "\n\n### 2. The UNMODIFIED incoming data\n\n";
			$data.= print_r($data, true);
			$data.= "\n\n### 3. The (potentially) MODIFIED incoming data\n\n";
			$data.= print_r($filtered_json, true);
			fwrite($write_file, $data);
			fclose($write_file);
		}

		// return the filtered data and mq analysis
		return $filtered_json;
	}

	// load .json file - or json data if already got
	function load_json_file($json_file, $theme_name, $context = '', $data = false) {

		// if json data wasn't passed in to function, get it
		if ( !$data ){

			// bail if file is missing or cannot read
			if ( !$data = $this->get_file_data( $json_file ) ) {
				return false;
			}
		}

		// tap into WordPress native JSON functions
		/*if( !class_exists('Moxiecode_JSON') ) {
					require_once($this->thisplugindir . 'includes/class-json.php');
				}
				$json_object = new Moxiecode_JSON();*/

		// convert to array
		if (!$json_array = $this->json('decode', $data)) { // json_decode($data, true)
			//$this->log('', '', 'error', 'json-decode', array('json_file', $json_file));
			return false;
		}

		// json decode was successful

		// if the export included workspace settings, save preferences and remove from data
		// this is insurance agaist upgrade problems
		if (!empty($json_array['non_section']['exported_preferences'])){
			update_option($this->preferencesName, $json_array['non_section']['exported_preferences']);
			unset($json_array['non_section']['exported_preferences']);
		}

		// replace mq keys, add new to the UI, add css units if necessary.
		$filtered_json = $this->filter_incoming_data('import', $json_array);

		// merge the arrays if merge (must come after key analysis/replacements)
		if ($context == __('Merge', 'microthemer') or $context == esc_attr__('Raw CSS', 'microthemer')) {
			$filtered_json = $this->merge($this->options, $filtered_json);
		} else {
			// Only update theme_in_focus if it's not a merge
			$pref_array['theme_in_focus'] = $theme_name;
			$this->savePreferences($pref_array);
		}

		// updates options var, save settings, and update stylesheet
		$this->options = $filtered_json;
		$this->saveUiOptions2($this->options);
		$this->update_assets($theme_name, $context);

		// import success
		$this->log(
			esc_html__('Settings were imported', 'microthemer'),
			'<p>' . esc_html__('The design pack settings were successfully imported.', 'microthemer') . '</p>',
			'notice'
		);

	}

	// ensure mq keys in pref array and options match
	//- NOTE A SIMPLER SOLUTION WOULD BE TO CONVERT ARRAY INTO STRING AND THEN DO str_replace (may have side effects though)
	function replace_mq_keys($student_key, $role_model_key, $options) {
		$old_new_mq_map[$student_key] = $role_model_key;
		// replace the relevant array keys - unset() doesn't work on $this-> so slightly convoluted solution used
		$cons = array('active_queries', 'm_query');
		$updated_array = array();
		foreach ($cons as $stub => $context) {
			unset($updated_array);
			if (is_array($options['non_section'][$context])) {
				foreach ($options['non_section'][$context] as $cur_key => $array) {
					if ($cur_key == $student_key) {
						$key = $role_model_key;
					} else {
						$key = $cur_key;
					}
					$updated_array[$key] = $array;
				}
				$options['non_section'][$context] = $updated_array; // reassign main array with updated keys array
			}
		}
		// and also the !important media query keys
		$updated_array = array();
		if (!empty($options['non_section']['important']['m_query']) and
		    is_array($options['non_section']['important']['m_query'])) {
			foreach ($options['non_section']['important']['m_query'] as $cur_key => $array) {
				if ($cur_key == $student_key) {
					$key = $role_model_key;
				} else {
					$key = $cur_key;
				}
				$updated_array[$key] = $array;
			}
			$options['non_section']['important']['m_query'] = $updated_array; // reassign main array with updated keys array
		}
		// annoyingly, I also need to do a replace on device_focus key values for all selectors
		/*foreach($options as $section_name => $array) {
					if ($section_name == 'non_section') { continue; }
					// loop through the selectors
					if (is_array($array)) {
						foreach ($array as $css_selector => $sub_array) {
							if (is_array($sub_array['device_focus'])) {
								foreach ( $sub_array['device_focus'] as $prop_group => $value) {
									// replace the value if it is an old key
									if (!empty($old_new_mq_map[$value])) {
										$options[$section_name][$css_selector]['device_focus'][$prop_group] = $old_new_mq_map[$value];
									}
								}
							}
						}
					}
				}*/
		return $options;
	}


	// merge the new settings with the current settings
	function merge($orig_settings, $new_settings) {
		// create debug merge file if set at top of script
		if ($this->debug_merge) {
			$debug_file = $this->debug_dir . 'debug-merge.txt';
			$write_file = @fopen($debug_file, 'w');
			$data = '';
			$data.= "\n\n" . __('### The to existing options (before merge)', 'microthemer') . "\n\n";
			$data.= print_r($orig_settings, true);

			$data .= "\n\n" . esc_html__('### The imported options (before any folder renaming)', 'microthemer') . "\n\n";
			$data .= print_r($new_settings, true);

		}
		if (is_array($new_settings)) {
			// check if search needs to be done on important and m_query arrays
			$mq_arr = $imp_arr = false;
			if (!empty($new_settings['non_section']['m_query']) and is_array($new_settings['non_section']['m_query'])){
				$mq_arr = $new_settings['non_section']['m_query'];
			}
			if (!empty($new_settings['non_section']['important']) and is_array($new_settings['non_section']['important'])){
				$imp_arr = $new_settings['non_section']['important'];
			}

			// loop through new sections to check for section name conflicts
			foreach($new_settings as $section_name => $array) {
				// if a name conflict exists
				if ( $this->is_name_conflict($section_name, $orig_settings, $new_settings, 'first-check') ) {
					// create a non-conflicting new name
					$alt = $this->get_alt_section_name($section_name, $orig_settings, $new_settings);
					$alt_name = $alt['name'];
					$alt_index = $alt['index'];
					// rename the to-be-merged section and the corresponding non_section extras
					$new_settings[$alt_name] = $new_settings[$section_name];
					$new_settings[$alt_name]['this']['label'] = $new_settings[$alt_name]['this']['label'].' '.$alt_index;
					$new_settings['non_section']['view_state'][$alt_name] = $new_settings['non_section']['view_state'][$section_name];
					unset($new_settings[$section_name]);
					unset($new_settings['non_section']['view_state'][$section_name]);
					// also rename all the corresponding [m_query] folder names (ouch)
					if ($mq_arr){
						foreach ($mq_arr as $mq_key => $arr){
							foreach ($arr as $orig_sec => $arr){
								// if the folder name exists in the m_query array, replace
								if ($section_name == $orig_sec){
									$new_settings['non_section']['m_query'][$mq_key][$alt_name] = $new_settings['non_section']['m_query'][$mq_key][$section_name];
									unset($new_settings['non_section']['m_query'][$mq_key][$section_name]);
								}
							}
						}
					}
					// and the [important] folder names (double ouch)
					if ($imp_arr){
						foreach ($imp_arr as $orig_sec => $arr){
							// if it's MQ important values
							if ($orig_sec == 'm_query'){
								foreach ($imp_arr['m_query'] as $mq_key => $arr){
									foreach ($arr as $orig_sec => $arr){
										// if the folder name exists in the m_query array, replace
										if ($section_name == $orig_sec){
											$new_settings['non_section']['important']['m_query'][$mq_key][$alt_name] = $new_settings['non_section']['important']['m_query'][$mq_key][$section_name];
											unset($new_settings['non_section']['important']['m_query'][$mq_key][$section_name]);
										}
									}
								}
							} else {
								// regular important value
								$new_settings['non_section']['important'][$alt_name] = $new_settings['non_section']['important'][$section_name];
								unset($new_settings['non_section']['important'][$section_name]);
							}
						}
					}
				}
			}


			if ($this->debug_merge) {
				$data .= "\n\n" . esc_html__('### The imported options (after folder renaming)', 'microthemer') . "\n\n";
				$data .= print_r($new_settings, true);
			}

			// now that we've checked for and corrected possible name conflicts
			// merge the arrays (recursively to avoid overwriting)
			$merged_settings = $this->array_merge_recursive_distinct($orig_settings, $new_settings);

			// the hand-coded CSS of the imported settings needs to be appended to the original
			foreach ($this->custom_code as $key => $arr){


				$new_code = trim($new_settings['non_section'][$key]);
				if (!empty($new_code)) {
					$merged_settings['non_section'][$key] =
						$orig_settings['non_section'][$key]
						. "\n\n/* " . esc_html_x('Imported CSS', 'CSS comment', 'microthemer') . " */\n"
						. $new_settings['non_section'][$key];
				} else {
					// the imported pack has no custom code so keep the original
					$merged_settings['non_section'][$key] = $orig_settings['non_section'][$key];
				}

				// if regular main custom css or JS
				/*if ($key == 'hand_coded_css' or $key == 'js'){

						}*/

			}
		}
		// maybe do some more processing here

		if ($this->debug_merge) {
			$data.= "\n\n" . __('### The Merged options', 'microthemer') . "\n\n";
			$data.= print_r($merged_settings, true);
			fwrite($write_file, $data);
			fclose($write_file);
		}
		return $merged_settings;
	}

	// add js deps in import if not
	function new_enq_js($cur_enq_js, $imp_enq_js){
		if ($imp_enq_js && is_array($imp_enq_js)){
			foreach ($imp_enq_js as $k => $arr){
				if (empty($cur_enq_js[$k])) return true;
			}
		}
		return false;
	}

	// get an array of current mq keys paired with replacements -
	// compare against 'role model' to base current array on
	function analyse_mqs($student_mqs, $role_model_mqs){
		$mq_analysis['new'] = false;
		$mq_analysis['replacements_needed'] = false;
		$i = 0;
		if (!empty($student_mqs) and is_array($student_mqs)) {
			foreach ($student_mqs as $student_key => $student_array){
				$replacement_key = $this->in_2dim_array($student_array['query'], $role_model_mqs, 'query');
				// if new media query
				if ( !$replacement_key ) {
					// ensure key is unique by using unique base from last page load
					// otherwise previously exported keys could overwrite rather add to existing MQs (if the query was changed after exporting)
					$new_key = $this->unq_base.++$i;
					$mq_analysis['new'][$new_key]['label'] = $student_array['label']. esc_html_x(' (imp)', '(imported media query)', 'microthemer');
					$mq_analysis['new'][$new_key]['query'] = $student_array['query'];
					// as we're defining new keys, the ui data keys will need replacing too
					$mq_analysis['replacements_needed'] = true;
					$mq_analysis['replacements'][$student_key] = $new_key;
				}
				// else store replacement key
				else {
					if ($replacement_key != $student_key){
						$mq_analysis['replacements_needed'] = true;
						$mq_analysis['replacements'][$student_key] = $replacement_key;
					}
				}
			}
		}
		return $mq_analysis;
	}


	/***
	Manage Micro Theme Functions
	 ***/











	// copy pie files so Microthemer styles can still be used following uninstall
	/*function maybe_copy_pie(){
				$pie_files = array('PIE.php', 'PIE.htc');
				foreach($pie_files as $file){
					$orig = $this->thisplugindir . '/pie/' . $file;
					$new = $this->micro_root_dir . $file;
					if (file_exists($new)){
						continue;
					}
					if (!copy($orig, $new)){
						$this->log(
							esc_html__('CSS3 PIE not copied', 'microthemer'),
							'<p>' . sprintf(
								esc_html__('CSS3 PIE (%s) could not be copied to correct location. This is needed to support gradients, rounded corners and box-shadow in old versions of Internet Explorer.', 'microthemer'),
								$file
							) . '</p>',
							'error'
						);
						return false;
					}
				}
				return true;
			}*/

	// create micro theme
	function create_micro_theme($micro_name, $action, $temp_zipfile) {
		// sanitize dir name
		$name = sanitize_file_name( $micro_name );
		$error = false;
		// extra bit need for zip uploads (removes .zip)
		if ($action == 'unzip') {
			$name = substr($name, 0, -4);
		}
		// check for micro-themes folder and create if doesn't exist
		$error = !$this->setup_micro_themes_dir() ? true : false;

		// check if the micro-themes folder is writable
		if ( !is_writeable( $this->micro_root_dir ) ) {
			$this->log(
				esc_html__('/micro-themes write error', 'microthemer'),
				'<p>' . sprintf(
					esc_html__('The directory %s is not writable.', 'microthemer'),
					$this->root_rel($this->micro_root_dir)
				) . $this->permissionshelp . '</p>'
			);
			$error = true;
		}
		// Check for micro theme with same name
		if ($alt_name = $this->rename_if_required($this->micro_root_dir, $name)) {
			$name = $alt_name; // $alt_name is false if no rename was required
		}
		// abs path
		$this_micro_abs = $this->micro_root_dir . $name;
		// Create new micro theme folder
		if ( !wp_mkdir_p ( $this_micro_abs ) ) {
			$this->log(
				esc_html__('design pack create error', 'microthemer'),
				'<p>' . sprintf(
					esc_html__('WordPress was not able to create the %s directory.', 'microthemer'), $this->root_rel($this_micro_abs)
				). '</p>'
			);
			$error = true;
		}
		// Check folder permission
		if ( !is_writeable( $this_micro_abs ) ) {
			$this->log(
				esc_html__('design pack write error', 'microthemer'),
				'<p>' . sprintf(
					esc_html__('The directory %s is not writable.', 'microthemer'), $this->root_rel($this_micro_abs)
				) . $this->permissionshelp . '</p>'
			);
			$error = true;
		}

		/*if (SAFE_MODE and $this->preferences['safe_mode_notice'] == '1') {
					$this->log(
						esc_html__('Safe-mode is on', 'microthemer'),
						'<p>' . esc_html__('The PHP server setting "Safe-Mode" is on.', 'microthemer')
						. '</p><p>' . wp_kses(
							sprintf(
								__('<b>This isn\'t necessarily a problem. But if the design pack "%s" hasn\'t been created</b>, please create the directory %s manually and give it permission code 777.', 'microthemer'),
								$this->readable_name($name), $this->root_rel($this_micro_abs)
							),
							array( 'b' => array() )
						) . $this->permissionshelp
						. '</p>',
						'warning'
					);
					$error = true;
				}
				*/

		// unzip if required
		if ($action == 'unzip') {
			// extract the files
			$this->extract_files($this_micro_abs, $temp_zipfile);
			// get the final name of the design pack from the meta file
			$name = $this->rename_from_meta($this_micro_abs . '/meta.txt', $name);
			if ($name){
				// import bg images to media library and update paths if any are found
				$json_config_file = $this->micro_root_dir . $name . '/config.json';
				$this->import_pack_images_to_library($json_config_file, $name);
			}
			// add the dir to the file structure array
			$this->file_structure[$name] = $this->dir_loop($this->micro_root_dir . $name);
			ksort($this->file_structure);
			//$this->dir_loop($this->micro_root_dir . $name);


		}

		// if creating blank shell or exporting UI settings, need to create meta.txt and readme.txt
		if ($action == 'export') {
			// set the theme name value
			$_POST['theme_meta']['Name'] = $this->readable_name($name);
			$this->update_meta_file($this_micro_abs . '/meta.txt');
			$this->update_readme_file($this_micro_abs . '/readme.txt');

		}
		// update the theme_in_focus value in the preferences table
		$this->savePreferences(
			array(
				'theme_in_focus' => $name,
			)
		);

		// if still no error, the action worked
		if ($error != true) {
			if ($action == 'create') {
				$this->log(
					esc_html__('Design pack created', 'microthemer'),
					'<p>' . esc_html__('The design pack directory was successfully created on the server.', 'microthemer') . '</p>',
					'notice'
				);
			}
			if ($action == 'unzip') {
				$this->log(
					esc_html__('Design pack installed', 'microthemer'),
					'<p>' . esc_html__('The design pack was successfully uploaded and extracted. You can import it into your Microthemer workspace any time using the') .
					' <span class="show-parent-dialog link" rel="import-from-pack">' . esc_html__('import option', 'microthemer') . '</span>'.
					'<span id="update-packs-list" rel="' . $this->readable_name($name) . '"></span>.</p>',
					'notice'
				);
			}
			if ($action == 'export') {
				$this->log(
					esc_html__('Settings exported', 'microthemer'),
					'<p>' . esc_html__('Your settings were successfully exported as a design pack directory on the server.', 'microthemer') . '</p>',
					'notice'
				);
			}
		}
		return true;
	}

	// rename zip form meta.txt name value
	function rename_from_meta($meta_file, $name){
		$orig_name = $name;
		if (is_file($meta_file) and is_readable($meta_file)) {
			$meta_info = $this->read_meta_file($meta_file);
			$name = strtolower(sanitize_file_name( $meta_info['Name'] ));
			// rename the directory if it doesn't already have the correct name
			if ($orig_name != $name){
				if ($alt_name = $this->rename_if_required($this->micro_root_dir, $name)) {
					$name = $alt_name; // $alt_name is false if no rename was required
				}
				rename($this->micro_root_dir . $orig_name, $this->micro_root_dir . $name);
			}
			return $name;
		} else {
			// no meta file error
			$this->log(
				esc_html__('Missing meta file', 'microthemer'),
				'<p>' . sprintf(
					esc_html__('The zip file doesn\'t contain a necessary %s file or it could not be read.', 'microthemer'),
					$this->root_rel($meta_file)
				) . '</p>'
			);
			return false;
		}
	}

	// read the data from a file into a string
	function get_file_data($file){
		if (!is_file($file)){
			$this->log(
				esc_html__('File doesn\'t exist', 'microthemer'),
				'<p>' . sprintf(
					esc_html__('%s does not exist on the server.', 'microthemer'),
					$this->root_rel($file)
				) . '</p>'
			);
			return false;
		}
		if (!is_readable($file)){
			$this->log(
				esc_html__('File not readable', 'microthemer'),
				'<p>' . sprintf(
					esc_html__(' %s could not be read.', 'microthemer'),
					$this->root_rel($file)
				) . '</p>'
				. $this->permissionshelp
			);
			return false;
		}
		$fh = @fopen($file, 'r');
		$data = fread($fh, filesize($file));
		fclose($fh);
		return $data;
	}

	// get image paths from the config.json file
	function get_image_paths($data){

		$img_array = array();

		// look for images
		preg_match_all('/"(background_image|list_style_image|border_image_src|mask_image)":"([^none][A-Za-z0-9 _\-\.\\/&\(\)\[\]!\{\}\?:=]+)"/',
			$data,
			$img_array,
			PREG_PATTERN_ORDER);

		// ensure $img_array only contains unique images
		foreach ($img_array[2] as $key => $config_img_path) {

			// if it's not unique, remove
			if (!empty($already_got[$config_img_path])){
				unset($img_array[2][$key]);
			}
			$already_got[$config_img_path] = 1;
		}

		if (count($img_array[2]) > 0) {
			return $img_array;
		} else {
			return false;
		}
	}

	// get media library images linked to from the config.json file
	function get_linked_library_images($json_config_file){

		// get config data
		if (!$data = $this->get_file_data($json_config_file)) {
			return false;
		}

		// get images from the config file that should be imported
		if (!$img_array = $this->get_image_paths($data)) {
			return false;
		}

		// loop through the image array, remove any images not in the media library
		foreach ($img_array[2] as $key => $config_img_path) {
			// has uploads path and doesn't also exist in pack dir (yet to be moved) - may be an unnecessary check
			if (strpos($config_img_path, '/uploads/')!== false and !is_file($this->micro_root_dir . $config_img_path)){
				$library_images[] = $config_img_path;
			}
		}
		if (is_array($library_images)){
			return $library_images;
		} else {
			return false;
		}

	}

	// encode or decode json todo replace other $json_object actions with this function (and test)
	function json($action, $data, $json_file = ''){

		// convert to array
		if ($action == 'decode'){

			// if we can't decode using native PHP function
			if (!$json_array = json_decode($data, true)) {

				//wp_die('$json_array failed: <pre>' . $data . '</pre>');

				// MT may be trying to decode data encoded by an older custom JSON class, rather than PHP native
				// so attempt to decode using legacy class
				if( !class_exists('Moxiecode_JSON') ) {
					require_once($this->thisplugindir . 'includes/class-json.php');
				}
				$json_object = new \Moxiecode_JSON();

				// we still can't decode the data
				if (!$json_array = $json_object->decode($data)) {
					$this->log('', '', 'error', 'json-decode', array('json_file', $json_file));
					return false;
				}

				/*$this->log(
							esc_html__('Legacy format data successfully decoded', 'microthemer'),
							'<p>' . esc_html__('Please contact themeover.com for help', 'microthemer') . '</p>',
						   'info'
						);*/

			}
			return $json_array;
		}

		// convert to json string
		elseif ($action == 'encode'){
			if (!$json_str = json_encode($data)) {
				$this->log(
					esc_html__('Encode json error', 'microthemer'),
					'<p>' . esc_html__('WordPress failed to convert your settings into json.', 'microthemer') . '</p>'
				);
				return false;
			}
			return $json_str;
		}
	}

	// import images in a design pack to the media library and update image paths in config.json
	function import_pack_images_to_library($json_config_file, $name, $data = false, $remote_images = false){

		// reset imported images
		$this->imported_images = array();

		// get config data if not passed in
		if (!$data) {
			if (!$data = $this->get_file_data($json_config_file)) {
				return false;
			}
		}

		// get images from the config file if not passed in
		if (!$remote_images) {
			if (!$img_array = $this->get_image_paths($data)) {
				return false;
			}
			$img_array = $img_array[2];
		} else {
			$img_array = $remote_images;
		}


		// loop through the image array
		foreach ($img_array as $key => $img_path) {

			$just_image_name = basename($img_path);

			// if remote image found in stylesheet downloaded to /tmp dir
			if ($remote_images){
				$tmp_image = $img_path; // C:/
				$orig_config_path = $key; // url
			} else {
				// else pack image found in zip
				$tmp_image = $this->micro_root_dir . $name . '/' . $just_image_name; // C:/
				$orig_config_path = $img_path; // url
			}

			// import the file to the media library if it exists
			if (file_exists($tmp_image)) {
				$this->imported_images[$just_image_name]['orig_config_path'] = $orig_config_path;

				// note import_image_to_library() updates 'success' and 'new_config_path'
				$id = $this->import_image_to_library($tmp_image, $just_image_name);

				// report wp error if problem
				if ( $id === 0 or is_wp_error($id) ) {
					if (is_wp_error($id)){
						$wp_error = '<p>'. $id->get_error_message() . '</p>';
					} else {
						$wp_error = '';
					}
					$this->log(
						esc_html__('Move to media library failed', 'microthemer'),
						'<p>' . sprintf(
							esc_html__('%s was not imported due to an error.', 'microthemer'),
							$this->root_rel($tmp_image)
						) . '</p>'
						. $wp_error
					);
				}
			}
		}

		// first report successfully moved images
		$moved_list =
			'<ul>';
		$moved = false;
		foreach ($this->imported_images as $just_image_name => $array){
			if (!empty($array['success'])){
				$moved_list.= '
						<li>
							'.$just_image_name.'
						</li>';
				$moved = true;
				// also update the json data string
				$replacements[$array['orig_config_path']] = $array['new_config_path'];
			}
		}
		$moved_list.=
			'</ul>';

		// move was successful, update paths
		if ($moved){
			$this->log(
				esc_html__('Images transferred to media library', 'microthemer'),
				'<p>' . esc_html__('The following images were transferred from the design pack to your WordPress media library:', 'microthemer') . '</p>'
				. $moved_list,
				'notice'
			);
			// update paths in json file
			return $this->replace_json_paths($json_config_file, $replacements, $data, $remote_images);
		}
	}

	// update paths in json file
	function replace_json_paths($json_config_file, $replacements, $data = false, $remote_images = false){

		if (!$data){
			if (!$data = $this->get_file_data($json_config_file)) {
				return false;
			}
		}

		// replace paths in string
		$replacement_occurred = false;
		foreach ($replacements as $orig => $new){
			if (strpos($data, $orig) !== false){
				$replacement_occurred = true;
				$data = str_replace($orig, $new, $data);
			}
		}
		if (!$replacement_occurred){
			return false;
		}

		// just return updated json data if loading css stylesheet
		if ($remote_images){
			$this->log(
				esc_html__('Image paths updated', 'microthemer'),
				'<p>' . esc_html__('Images paths were successfully updated to reflect the new location or deletion of an image(s).', 'microthemer') . '</p>',
				'notice'
			);
			return $data;
		}

		// update the config.json image paths for images successfully moved to the library
		if (is_writable($json_config_file)) {
			if ($write_file = @fopen($json_config_file, 'w')) {
				if (fwrite($write_file, $data)) {
					fclose($write_file);
					$this->log(
						esc_html__('Images paths updated', 'microthemer'),
						'<p>' . esc_html__('Images paths were successfully updated to reflect the new location or deletion of an image(s).', 'microthemer') . '</p>',
						'notice'
					);
					return true;
				}
				else {
					$this->log(
						esc_html__('Image paths failed to update.', 'microthemer'),
						'<p>' . esc_html__('Images paths could not be updated to reflect the new location of the images transferred to your media library. This happened because Microthemer could not rewrite the config.json file.', 'microthemer') . '</p>' . $this->permissionshelp
					);
					return false;
				}
			}
		}
	}

	// Unitless css values need to be auto-adjusted to explicit pixels if the user's preference
	// for the prop is not 'px (implicit)' and the value is a unitless number
	// Conversely, px values need to be removed if implicit pixels is set (and not custom code value)
	// Note: we can't do e.g. em conversion here as we don't know the DOM context
	/*function filter_json_css_units($data, $context = 'reg'){

			    $filtered_json = $data;
				$possible_units = array_merge(array_keys($this->css_units), $this->special_css_units);
				$before_units_change = empty($filtered_json['non_section']['mt_version']);

				foreach ($filtered_json as $section_name => $array){
					if ($section_name == 'non_section') {
						continue;
					}
					if (is_array($array)) {
						foreach ($array as $css_selector => $arr) {
							if ( is_array( $arr['styles'] ) ) {
								foreach ($arr['styles'] as $prop_group => $arr2) {
									if (is_array($arr2)) {
										foreach ($arr2 as $prop => $value) {

										    // data structure was updated
										    $value = !isset($value['value']) ? $value : $value['value'] ;

										    // if the property has a default unit
											if (isset($this->preferences['my_props'][$prop_group]['pg_props'][$prop]['default_unit'])){

											    $default_unit = $this->preferences['my_props'][$prop_group]['pg_props'][$prop]['default_unit'];

											    // if the unit is included in the value, remove it and add to unit key
                                                // todo limit to single values with css unit
												preg_match('/('.implode('|', $possible_units).')\s*$/', $value, $unit_match);

												if ($unit_match){
												    $extracted_css_unit = $unit_match[1];
                                                    $unitless_value = preg_replace(
                                                            '/'.$extracted_css_unit.'\s*$/', '', $value
                                                    );
													$filtered_json[$section_name][$css_selector]['styles'][$prop_group][$prop]['value'] = $unitless_value;
													$filtered_json[$section_name][$css_selector]['styles'][$prop_group][$prop]['unit'] = $extracted_css_unit;
												}

												// if the value is a unitless number from before MT updated the units system,
                                                // apply px so user's current default_unit setting doesn't change things to pixels
												else if ($before_units_change && $prop !== 'line_height' && is_numeric($value) && $value != 0){ //
													$filtered_json[$section_name][$css_selector]['styles'][$prop_group][$prop]['unit'] = 'px';
												}

											}
										}
									}
								}
							}
						}
					}
				}
				return $filtered_json;
			}*/

	//Handle an individual file import.
	function import_image_to_library($file, $just_image_name, $post_id = 0, $import_date = false) {
		set_time_limit(60);
		// Initially, Base it on the -current- time.
		$time = current_time('mysql', 1);
		// A writable uploads dir will pass this test. Again, there's no point overriding this one.
		if ( ! ( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] ) ) {
			$this->log(
				esc_html__('Uploads folder error', 'microthemer'),
				$uploads['error']
			);
			return 0;
		}

		$wp_filetype = wp_check_filetype( $file, null );
		$type = $ext = false;
		extract( $wp_filetype );
		if ( ( !$type || !$ext ) && !current_user_can( 'unfiltered_upload' ) ) {
			$this->log(
				esc_html__('Wrong file type', 'microthemer'),
				'<p>' . esc_html__('Sorry, this file type is not permitted for security reasons.', 'microthemer') . '</p>'
			);
			return 0;
		}

		//Is the file already in the uploads folder?
		if ( preg_match('|^' . preg_quote(str_replace('\\', '/', $uploads['basedir'])) . '(.*)$|i', $file, $mat) ) {
			$filename = basename($file);
			$new_file = $file;

			$url = $uploads['baseurl'] . $mat[1];

			$attachment = get_posts(array( 'post_type' => 'attachment', 'meta_key' => '_wp_attached_file', 'meta_value' => ltrim($mat[1], '/') ));
			if ( !empty($attachment) ) {
				$this->log(
					esc_html__('Image already in library', 'microthemer'),
					'<p>' . sprintf(
						esc_html__('%s already exists in the WordPress media library and was therefore not moved', 'microthemer'),
						$filename
					) . '</p>',
					'warning'
				);
				return 0;
			}
			//OK, Its in the uploads folder, But NOT in WordPress's media library.
		} else {
			$filename = wp_unique_filename( $uploads['path'], basename($file));

			// copy the file to the uploads dir
			$new_file = $uploads['path'] . '/' . $filename;
			if ( false === @rename( $file, $new_file ) ) {
				$this->log(
					esc_html__('Move to library failed', 'microthemer'),
					'<p>' . sprintf(
						esc_html__('%s could not be moved to %s', 'microthemer'),
						$filename,
						$uploads['path']
					) . '</p>',
					'warning'
				);
				return 0;
			}


			// Set correct file permissions
			$stat = stat( dirname( $new_file ));
			$perms = $stat['mode'] & 0000666;
			@ chmod( $new_file, $perms );
			// Compute the URL
			$url = $uploads['url'] . '/' . $filename;
		}

		//Apply upload filters
		$return = apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ) );
		$new_file = $return['file'];
		$url = $return['url'];
		$type = $return['type'];

		$title = preg_replace('!\.[^.]+$!', '', basename($new_file));
		$content = '';

		// update the array for replacing paths in config.json
		$this->imported_images[$just_image_name]['success'] = true;
		$this->imported_images[$just_image_name]['new_config_path'] = $this->root_rel($url, false, true);

		// use image exif/iptc data for title and caption defaults if possible
		if ( $image_meta = @wp_read_image_metadata($new_file) ) {
			//if ( '' != trim($image_meta['title']) )
			//$title = trim($image_meta['title']);
			if ( '' != trim($image_meta['caption']) )
				$content = trim($image_meta['caption']);
		}

		//=sebcus the title should reflect a possible file rename e.g. image1 - happens above ^
		//$title = str_replace('.'.$ext, '', $filename);

		if ( $time ) {
			$post_date_gmt = $time;
			$post_date = $time;
		} else {
			$post_date = current_time('mysql');
			$post_date_gmt = current_time('mysql', 1);
		}

		// Construct the attachment array
		$attachment = array(
			'post_mime_type' => $type,
			'guid' => $url,
			'post_parent' => $post_id,
			'post_title' => $title,
			'post_name' => $title,
			'post_content' => $content,
			'post_date' => $post_date,
			'post_date_gmt' => $post_date_gmt
		);

		$attachment = apply_filters('afs-import_details', $attachment, $file, $post_id, $import_date);

		//Win32 fix:
		$new_file = str_replace( strtolower(str_replace('\\', '/', $uploads['basedir'])), $uploads['basedir'], $new_file);

		// Save the data
		$id = wp_insert_attachment($attachment, $new_file, $post_id);
		if ( !is_wp_error($id) ) {
			$data = wp_generate_attachment_metadata( $id, $new_file );
			wp_update_attachment_metadata( $id, $data );
		}
		//update_post_meta( $id, '_wp_attached_file', $uploads['subdir'] . '/' . $filename );

		return $id;
	}

	// handle zip package
	function handle_zip_package() {
		$temp_zipfile = $_FILES['upload_micro']['tmp_name'];
		$filename = $_FILES['upload_micro']['name']; // it won't be this name for long
		// Chrome return a empty content-type : http://code.google.com/p/chromium/issues/detail?id=6800
		if ( !preg_match('/chrome/i', $_SERVER['HTTP_USER_AGENT']) ) {
			// check if file is a zip file
			if ( !preg_match('/(zip|download|octet-stream)/i', $_FILES['upload_micro']['type']) ) {
				@unlink($temp_zipfile); // del temp file
				$this->log(
					esc_html__('Faulty zip file', 'microthemer'),
					'<p>' . esc_html__('The uploaded file was faulty or was not a zip file.', 'microthemer') . '</p>
						<p>' . esc_html__('The server recognised this file type: ', 'microthemer') . $_FILES['upload_micro']['type'].'</p>'
				);
				return false;
			}
		}
		$this->create_micro_theme($filename, 'unzip', $temp_zipfile);
	}


	// read meta data from file
	function read_meta_file($meta_file) {
		// create default meta.txt file if it doesn't exist
		if (!is_file($meta_file)) {
			$_POST['theme_meta']['Name'] = $this->readable_name($this->preferences['theme_in_focus']);
			$this->update_meta_file($this->micro_root_dir . $this->preferences['theme_in_focus'].'/meta.txt');
		}
		if (is_file($meta_file)) {
			// check if it's readable
			if ( is_readable($meta_file) ) {
				//disable wptexturize
				remove_filter('get_theme_data', 'wptexturize');
				return $this->flx_get_theme_data( $meta_file );
			}
			else {
				$abs_meta_path = $this->micro_root_dir . $this->preferences['theme_in_focus'].'/meta.txt';

				$this->log(
					esc_html__('Read meta.txt error', 'microthemer'),
					'<p>' . esc_html__('WordPress does not have permission to read: ', 'microthemer') .
					$this->root_rel($abs_meta_path) . '. '.$this->permissionshelp.'</p>'
				);
				return false;
			}
		}
	}

	// read readme.txt data from file
	function read_readme_file($readme_file) {
		// create default readme file if it doesn't exist
		if (!is_file($readme_file)) {
			$this->update_readme_file($this->micro_root_dir . $this->preferences['theme_in_focus'].'/readme.txt');
		}
		if (is_file($readme_file)) {
			// check if it's readable
			if ( is_readable($readme_file) ) {
				$fh = @fopen($readme_file, 'r');
				$length = filesize($readme_file);
				if ($length == 0) {
					$length = 1;
				}
				$data = fread($fh, $length);
				fclose($fh);
				return $data;
			}
			else {
				$abs_readme_path = $this->micro_root_dir . $this->preferences['theme_in_focus'].'/readme.txt';
				$this->log(
					esc_html__('Read readme.txt error', 'microthemer'),
					'<p>' . esc_html__('WordPress does not have permission to read: ', 'microthemer'),
					$this->root_rel($abs_readme_path) . '. '.$this->permissionshelp.'</p>'
				);
				return false;
			}
		}
	}

	// adapted WordPress function for reading and formattings a template file
	function flx_get_theme_data( $theme_file ) {
		$default_headers = array(
			'Name' => 'Theme Name',
			'PackType' => 'Pack Type',
			'URI' => 'Theme URI',
			'Description' => 'Description',
			'Author' => 'Author',
			'AuthorURI' => 'Author URI',
			'Version' => 'Version',
			'Template' => 'Template',
			'Status' => 'Status',
			'Tags' => 'Tags'
		);
		// define allowed tags
		$themes_allowed_tags = array(
			'a' => array(
				'href' => array(),'title' => array()
			),
			'abbr' => array(
				'title' => array()
			),
			'acronym' => array(
				'title' => array()
			),
			'code' => array(),
			'em' => array(),
			'strong' => array()
		);
		// get_file_data() - WP 2.8 compatibility function created for this
		$theme_data = get_file_data( $theme_file, $default_headers, 'theme' );
		$theme_data['Name'] = $theme_data['Title'] = wp_kses( $theme_data['Name'], $themes_allowed_tags );
		$theme_data['PackType'] = wp_kses( $theme_data['PackType'], $themes_allowed_tags );
		$theme_data['URI'] = esc_url( $theme_data['URI'] );
		$theme_data['Description'] = wp_kses( $theme_data['Description'], $themes_allowed_tags );
		$theme_data['AuthorURI'] = esc_url( $theme_data['AuthorURI'] );
		$theme_data['Template'] = wp_kses( $theme_data['Template'], $themes_allowed_tags );
		$theme_data['Version'] = wp_kses( $theme_data['Version'], $themes_allowed_tags );
		if ( empty($theme_data['Status']) )
			$theme_data['Status'] = 'publish';
		else
			$theme_data['Status'] = wp_kses( $theme_data['Status'], $themes_allowed_tags );

		if ( empty($theme_data['Tags']) )
			$theme_data['Tags'] = array();
		else
			$theme_data['Tags'] = array_map( 'trim', explode( ',', wp_kses( $theme_data['Tags'], array() ) ) );

		if ( empty($theme_data['Author']) ) {
			$theme_data['Author'] = $theme_data['AuthorName'] = __('Anonymous');
		} else {
			$theme_data['AuthorName'] = wp_kses( $theme_data['Author'], $themes_allowed_tags );
			if ( empty( $theme_data['AuthorURI'] ) ) {
				$theme_data['Author'] = $theme_data['AuthorName'];
			} else {
				$theme_data['Author'] = sprintf( '<a href="%s" title="%s">%s</a>', $theme_data['AuthorURI'], esc_html__( 'Visit author homepage' ), $theme_data['AuthorName'] );
			}
		}
		return $theme_data;
	}

	// delete theme
	function tvr_delete_micro_theme($dir_name) {
		$error = false;
		// loop through files if they exist
		if (is_array($this->file_structure[$dir_name])) {
			foreach ($this->file_structure[$dir_name] as $file => $oneOrFileName) {

                // there is an odd inconsistency with screenshot key referring to a filename
                // rather than the key being the file name
				$file = $oneOrFileName == 1 ? $file : $oneOrFileName;

				if (!unlink($this->micro_root_dir . $dir_name.'/'.$file)) {
					$this->log(
						esc_html__('File delete error', 'microthemer'),
						'<p>' . esc_html__('Unable to delete: ', 'microthemer') .
						$this->root_rel($this->micro_root_dir .
						                $dir_name.'/'.$file) . print_r($this->file_structure, true). '</p>'
					);
					$error = true;
				}
			}
		}
		if ($error != true) {
			$this->log(
				'Files successfully deleted',
				'<p>' . sprintf(
					esc_html__('All files within %s were successfully deleted.', 'microthemer'),
					$this->readable_name($dir_name)
				) . '</p>',
				'dev-notice'
			);
			// attempt to delete empty directory
			if (!rmdir($this->micro_root_dir . $dir_name)) {
				$this->log(
					esc_html__('Delete directory error', 'microthemer'),
					'<p>' . sprintf(
						esc_html__('The empty directory: %s could not be deleted.', 'microthemer'),
						$this->readable_name($dir_name)
					) . '</p>'
				);
				$error = true;
			}
			else {
				$this->log(
					esc_html__('Directory successfully deleted', 'microthemer'),
					'<p>' . sprintf(
						esc_html__('%s was successfully deleted.', 'microthemer'),
						$this->readable_name($dir_name)
					) . '</p>',
					'notice'
				);

				// reset the theme_in_focus value in the preferences table
				$pref_array['theme_in_focus'] = '';
				if (!$this->savePreferences($pref_array)) {
					// not much cause for a message
				}


				if ($error){
					return false;
				} else {
					return true;
				}
			}
		}
	}

	// update the meta file
	function update_meta_file($meta_file) {
		// check if the micro theme dir needs to be renamed
		if (isset($_POST['prev_micro_name']) and ($_POST['prev_micro_name'] != $_POST['theme_meta']['Name'])) {
			$orig_name = $this->micro_root_dir . $this->preferences['theme_in_focus'];
			$new_theme_in_focus = sanitize_file_name(sanitize_title($_POST['theme_meta']['Name']));
			// need to do unique dir check here too
			// Check for micro theme with same name
			if ($alt_name = $this->rename_if_required($this->micro_root_dir, $new_theme_in_focus)) {
				$new_theme_in_focus = $alt_name;
				// The dir had to be automatically renamed so update the visible name
				$_POST['theme_meta']['Name'] = $this->readable_name($new_theme_in_focus);
			}
			$new_name = $this->micro_root_dir . $new_theme_in_focus;
			// if the directory is writable
			if (is_writable($orig_name)) {
				if (rename($orig_name, $new_name)) {
					// if rename is successful...

					// the meta file will have a different location now
					$meta_file = str_replace($this->preferences['theme_in_focus'], $new_theme_in_focus, $meta_file);

					// update the files array directory key
					$cache = $this->file_structure[$this->preferences['theme_in_focus']];
					$this->file_structure[$new_theme_in_focus] = $cache;
					unset($this->file_structure[$this->preferences['theme_in_focus']]);

					// update the value in the preferences table
					$pref_array = array();
					$pref_array['theme_in_focus'] = $new_theme_in_focus;
					if ($this->savePreferences($pref_array)) {
						$this->log(
							esc_html__('Design pack renamed', 'microthemer'),
							'<p>' . esc_html__('The design pack directory was successfully renamed on the server.', 'microthemer') . '</p>',
							'notice'
						);
					}
				}
				else {
					$this->log(
						esc_html__('Directory rename error', 'microthemer'),
						'<p>' . sprintf(
							esc_html__('The directory %s could not be renamed for some reason.', 'microthemer'),
							$this->root_rel($orig_name)
						) . '</p>'
					);
				}
			}
			else {
				$this->log(
					esc_html__('Directory rename error', 'microthemer'),
					'<p>' . sprintf(
						esc_html__('WordPress does not have permission to rename the directory %s to match your new theme name "%s".', 'microthemer'),
						$this->root_rel($orig_name),
						htmlentities($this->readable_name($_POST['theme_meta']['Name']))
					) . $this->permissionshelp.'.</p>'
				);
			}
		}


		// Create new file if it doesn't already exist
		if (!file_exists($meta_file)) {
			if (!$write_file = @fopen($meta_file, 'w')) {
				$this->log(
					sprintf( esc_html__('Create %s error', 'microthemer'), 'meta.txt' ),
					'<p>' . sprintf(esc_html__('WordPress does not have permission to create: %s', 'microthemer'), $this->root_rel($meta_file) . '. '.$this->permissionshelp ) . '</p>'
				);
			}
			else {
				fclose($write_file);
			}
			$task = 'created';
			// set post variables if undefined (might be following initial export)

			if (!isset($_POST['theme_meta']['Description'])) {

				$current_user = wp_get_current_user();

				//global $user_identity;
				//get_currentuserinfo();
				/* get the user's website (fallback on site_url() if null)
						$user_info = get_userdata($user_ID);
						if ($user_info->user_url != '') {
							$author_uri = $user_info->user_url;
						}
						else {
							$author_uri = site_url();
						}*/
				// get parent theme name and version
				//$theme_data = wp_get_theme(get_stylesheet_uri());
				// $template = $theme_data['Name'] . ' ' . $theme_data['Version'];
				//$template = $theme_data['Name'];
				$_POST['theme_meta']['Description'] = "";
				$_POST['theme_meta']['PackType'] ='';
				$_POST['theme_meta']['Author'] = $current_user->display_name;
				$_POST['theme_meta']['AuthorURI'] = '';
				// $_POST['theme_meta']['Template'] = get_current_theme();
				$_POST['theme_meta']['Template'] = '';
				$_POST['theme_meta']['Version'] = '1.0';
				$_POST['theme_meta']['Tags'] = '';

			}
		}
		else {
			$task = 'updated';
		}



		// check if it's writable - // need to remove carriage returns
		if ( is_writable($meta_file) ) {

			/*
					note: if DateCreated is missing the pack was made before june 12.
					This may or may not be useful information.
					*/

			//removed Theme URI: '.strip_tags(stripslashes($_POST['theme_meta']['URI'])).'

			$Name = !empty($_POST['theme_meta']['Name']) ? $_POST['theme_meta']['Name'] : '';
			$PackType = !empty($_POST['theme_meta']['PackType']) ? $_POST['theme_meta']['PackType'] : '';
			$Description = !empty($_POST['theme_meta']['Description']) ? $_POST['theme_meta']['Description'] : '';
			$Author = !empty($_POST['theme_meta']['Author']) ? $_POST['theme_meta']['Author'] : '';
			$AuthorURI = !empty($_POST['theme_meta']['AuthorURI']) ? $_POST['theme_meta']['AuthorURI'] : '';
			$Template = !empty($_POST['theme_meta']['Template']) ? $_POST['theme_meta']['Template'] : '';
			$Version = !empty($_POST['theme_meta']['Version']) ? $_POST['theme_meta']['Version'] : '';
			$Tags = !empty($_POST['theme_meta']['Tags']) ? $_POST['theme_meta']['Tags'] : '';

			$data = '/*
Theme Name: '.strip_tags(stripslashes($Name)).'
Pack Type: '.strip_tags(stripslashes($PackType)).'
Description: '.strip_tags(stripslashes(str_replace(array("\n", "\r"), array(" ", ""), $Description))).'
Author: '.strip_tags(stripslashes($Author)).'
Author URI: '.strip_tags(stripslashes($AuthorURI)).'
Template: '.strip_tags(stripslashes($Template)).'
Version: '.strip_tags(stripslashes($Version)).'
Tags: '.strip_tags(stripslashes($Tags)).'
DateCreated: '.date('Y-m-d').'
*/';

			// the file will be created if it doesn't exist. otherwise it is overwritten.
			$write_file = @fopen($meta_file, 'w');
			fwrite($write_file, $data);
			fclose($write_file);
			// success message
			$this->log(
				'meta.txt '.$task,
				'<p>' . sprintf( esc_html__('The %s file for the design pack was %s', 'microthemer'), 'meta.txt', $task ) . '</p>',
				'dev-notice'
			);
		}
		else {
			$this->log(
				sprintf( esc_html__('Write %s error', 'microthemer'), 'meta.txt'),
				'<p>' . esc_html__('WordPress does not have "write" permission for: ', 'microthemer') .
				$this->root_rel($meta_file) . '. '.$this->permissionshelp.'</p>'
			);
		}

	}

	// update the readme file
	function update_readme_file($readme_file) {
		// Create new file if it doesn't already exist
		if (!file_exists($readme_file)) {
			if (!$write_file = @fopen($readme_file, 'w')) {
				$this->log(
					sprintf( esc_html__('Create %s error', 'microthemer'), 'readme.txt'),
					'<p>' . sprintf(
						esc_html__('WordPress does not have permission to create: %s', 'microthemer'),
						$this->root_rel($readme_file) . '. '.$this->permissionshelp
					) . '</p>'
				);
			}
			else {
				fclose($write_file);
			}
			$task = 'created';
			// set post variable if undefined (might be defined if theme dir has been
			// created manually and then user is submitting readme info for the first time)
			if (!isset($_POST['tvr_theme_readme'])) {
				$_POST['tvr_theme_readme'] = '';
			}
		}
		else {
			$task = 'updated';
		}
		// check if it's writable
		if ( is_writable($readme_file) ) {
			$data = stripslashes($_POST['tvr_theme_readme']); // don't use striptags so html code can be added
			// the file will be created if it doesn't exist. otherwise it is overwritten.
			$write_file = @fopen($readme_file, 'w');
			fwrite($write_file, $data);
			fclose($write_file);
			// success message
			$this->log(
				'readme.txt '.$task,
				'<p>' . sprintf(
					esc_html__('The %s file for the design pack was %s', 'microthemer'),
					'readme.txt', $task
				) . '</p>',
				'dev-notice'
			);
		}
		else {
			$this->log(
				sprintf( esc_html__('Write %s error', 'microthemer'), 'readme.txt'),
				'<p>' . esc_html__('WordPress does not have "write" permission for: ', 'microthemer') .
				$this->root_rel($readme_file) . '. '.$this->permissionshelp.'</p>'
			);
		}
	}

	// handle file upload
	function handle_file_upload() {
		// if no error
		if ($_FILES['upload_file']['error'] == 0) {
			$file = $_FILES['upload_file']['name'];
			// check if the file has a valid extension
			if ($this->is_acceptable($file)) {
				$dest_dir = $this->micro_root_dir . $this->preferences['theme_in_focus'].'/';
				// check if the directory is writable
				if (is_writeable($dest_dir) ) {
					// copy file if safe
					if (is_uploaded_file($_FILES['upload_file']['tmp_name'])
					    and copy($_FILES['upload_file']['tmp_name'], $dest_dir . $file)) {
						$this->log(
							esc_html__('File successfully uploaded', 'microthemer'),
							'<p>' . wp_kses(
								sprintf(
									__('<b>%s</b> was successfully uploaded.', 'microthemer'),
									htmlentities($file)
								),
								array( 'b' => array() )
							) . '</p>',
							'notice'
						);
						// update the file_structure array
						$this->file_structure[$this->preferences['theme_in_focus']][$file] = 1;

						// resize file if it's a screeshot
						if ($this->is_screenshot($file)) {
							$img_full_path = $dest_dir . $file;
							// get the screenshot size, resize if too big
							list($width, $height) = getimagesize($img_full_path);
							if ($width > 896 or $height > 513){
								$this->wp_resize(
									$img_full_path,
									896,
									513,
									$img_full_path);
							}
							// now do thumbnail
							$thumbnail = $dest_dir . 'screenshot-small.'. $this->get_extension($file);
							$root_rel_thumb = $this->root_rel($thumbnail);
							if (!$final_dimensions = $this->wp_resize(
								$img_full_path,
								145,
								83,
								$thumbnail)) {
								$this->log(
									esc_html__('Screenshot thumbnail error', 'microthemer'),
									'<p>' . wp_kses(
										sprintf(
											__('Could not resize <b>%s</b> to thumbnail proportions.', 'microthemer'),
											$root_rel_thumb
										),
										array( 'b' => array() )
									) . $img_full_path .
									esc_html__(' thumb: ', 'microthemer') .$thumbnail.'</p>'
								);
							}
							else {
								// update the file_structure array
								$file = basename($thumbnail);
								$this->file_structure[$this->preferences['theme_in_focus']][$file] = 1;
								$this->log(
									esc_html__('Screenshot thumbnail successfully created', 'microthemer'),
									'<p>' . sprintf(
										esc_html__('%s was successfully created.', 'microthemer'),
										$root_rel_thumb
									) . '</p>',
									'notice'
								);
							}
						}


					}
				}
				// it's not writable
				else {
					$this->log(
						esc_html__('Write to directory error', 'microthemer'),
						'<p>'. esc_html__('WordPress does not have "Write" permission to the directory: ', 'microthemer') .
						$this->root_rel($dest_dir) . '. '.$this->permissionshelp.'.</p>'
					);
				}
			}
			else {
				$this->log(
					esc_html__('Invalid file type', 'microthemer'),
					'<p>' . esc_html__('You have uploaded a file type that is not allowed.', 'microthemer') . '</p>'
				);

			}
		}
		// there was an error - save in global message
		else {
			$this->log_file_upload_error($_FILES['upload_file']['error']);
		}
	}

	// log file upload problem
	function log_file_upload_error($error){
		switch ($error) {
			case 1:
				$this->log(
					esc_html__('File upload limit reached', 'microthemer'),
					'<p>' . esc_html__('The file you uploaded reached your "upload_max_filesize" limit. This is a PHP setting on your server.', 'microthemer') . '</p>'
				);
				break;
			case 2:
				$this->log(
					esc_html__('File size too big', 'microthemer'),
					'<p>' . esc_html__('The file you uploaded reached your "max_file_size" limit. This is a PHP setting on your server.', 'microthemer') . '</p>'
				);
				break;
			case 3:
				$this->log(
					esc_html__('Partial upload', 'microthemer'),
					'<p>' . esc_html__('The file you uploaded only partially uploaded.', 'microthemer') . '</p>'
				);
				break;
			case 4:
				$this->log(
					esc_html__('No file uploaded', 'microthemer'),
					'<p>' . esc_html__('No file was detected for upload.', 'microthemer') . '</p>'
				);
				break;
		}
	}

	// slightly modified version of wp_remote_fopen where we also get the response code
	function wp_remote_fopen( $uri ) {

		$parsed_url = parse_url( $uri );

		if ( ! $parsed_url || ! is_array( $parsed_url ) ) {
			return false;
		}

		$options            = array();
		$options['timeout'] = 10;

		$response = wp_safe_remote_get( $uri, $options );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		return array(
			'body' => wp_remote_retrieve_body( $response ),
			'code' => wp_remote_retrieve_response_code( $response )
		);
	}

	// resize image using wordpress functions
	function wp_resize($path, $w, $h, $dest, $crop = true){
		$image = wp_get_image_editor( $path );
		if ( ! is_wp_error( $image ) ) {
			$image->resize( $w, $h, $crop );
			$image->save( $dest );
			return true;
		} else {
			return false;
		}
	}

	// resize image
	function resize($img, $max_width, $max_height, $newfilename) {
		//Check if GD extension is loaded
		if (!extension_loaded('gd') && !extension_loaded('gd2')) {
			$this->log(
				esc_html__('GD not loaded', 'microthemer'),
				'<p>' . esc_html__('The PHP extension GD is not loaded.', 'microthemer') . '</p>'
			);
			return false;
		}
		//Get Image size info
		$imgInfo = getimagesize($img);
		switch ($imgInfo[2]) {
			case 1: $im = imagecreatefromgif($img); break;
			case 2: $im = imagecreatefromjpeg($img); break;
			case 3: $im = imagecreatefrompng($img); break;
			default:
				$this->log(
					esc_html__('File type error', 'microthemer'),
					'<p>' . esc_html__('Unsuported file type. Are you sure you uploaded an image?', 'microthemer') . '</p>'
				);

				return false; break;
		}
		// orig dimensions
		$width = $imgInfo[0];
		$height = $imgInfo[1];
		// set proportional max_width and max_height if one or the other isn't specified
		if ( empty($max_width)) {
			$max_width = round($width/($height/$max_height));
		}
		if ( empty($max_height)) {
			$max_height = round($height/($width/$max_width));
		}
		// abort if user tries to enlarge a pic
		if (($max_width > $width) or ($max_height > $height)) {
			$this->log(
				esc_html__('Dimensions too big', 'microthemer'),
				'<p>' . sprintf(
					esc_html__('The resize dimensions you specified (%s x %s) are bigger than the original image (%s x %s). This is not allowed.', 'microthemer'),
					$max_width, $max_height, $width, $height
				) . '</p>'
			);
			return false;
		}

		// proportional resizing
		$x_ratio = $max_width / $width;
		$y_ratio = $max_height / $height;
		if (($width <= $max_width) && ($height <= $max_height)) {
			$tn_width = $width;
			$tn_height = $height;
		}
		else if (($x_ratio * $height) < $max_height) {
			$tn_height = ceil($x_ratio * $height);
			$tn_width = $max_width;
		}
		else {
			$tn_width = ceil($y_ratio * $width);
			$tn_height = $max_height;
		}
		// for compatibility
		$nWidth = $tn_width;
		$nHeight = $tn_height;
		$final_dimensions['w'] = $nWidth;
		$final_dimensions['h'] = $nHeight;
		$newImg = imagecreatetruecolor($nWidth, $nHeight);
		/* Check if this image is PNG or GIF, then set if Transparent*/
		if(($imgInfo[2] == 1) or ($imgInfo[2]==3)) {
			imagealphablending($newImg, false);
			imagesavealpha($newImg,true);
			$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
			imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
		}
		imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
		// Generate the file, and rename it to $newfilename
		switch ($imgInfo[2]) {
			case 1: imagegif($newImg,$newfilename); break;
			case 2: imagejpeg($newImg,$newfilename); break;
			case 3: imagepng($newImg,$newfilename); break;
			default:
				$this->log(
					esc_html__('Image resize failed', 'microthemer'),
					'<p>' . esc_html__('Your image could not be resized.', 'microthemer') . '</p>'
				);
				return false;
				break;
		}
		return $final_dimensions;
	}
	// next function


}


// The PCL zip class requires a pre-defined callback function external to the class
if (!function_exists('\Microthemer\tvr_microthemer_getOnlyValid')) {

	function tvr_microthemer_getOnlyValid($p_event, &$p_header) {

		// avoid null byte hack (THX to Dominic Szablewski)
		if ( strpos($p_header['filename'], chr(0) ) !== false ){
			$p_header['filename'] = substr ( $p_header['filename'], 0, strpos($p_header['filename'], chr(0) ));
		}

		$info = pathinfo($p_header['filename']);

		// check for extension
		$ext = array('jpeg', 'jpg', 'png', 'gif', 'txt', 'json', 'psd', 'ai');
		$check_ext = strtolower($info['extension']);
		if ( in_array($check_ext, $ext) ) {

			// For MAC skip the ".image" files
			if ($info['basename'][0] == '.' ){
				return 0;
			}

			else {
				return 1;
			}

		}

		// ----- all other files are skipped
		else {
			return 0;
		}
	}
}


