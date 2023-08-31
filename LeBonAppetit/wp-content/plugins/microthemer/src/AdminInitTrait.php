<?php

namespace Microthemer;

trait AdminInitTrait {

	var $activation_function_ran;
	var $all_pages = array();
	var $user_memory_limit;
	
	function init(){

		// bail if server doesn't meet the minimum requirements
		if (!$this->checkMinimumRequirements()){
			return;
		}

		// we use a session to hold unsaved draft folder logic across frontend page navigation
		// that way, the folder loading status remains accurate until it is saved
		//$this->supportSessions();

		// setup text domain
		$this->loadTextDomain();

		// get the directory paths
		include dirname(__FILE__) .'/../get-dir-paths.inc.php';

		// for media queries
		$this->unq_base = uniqid();

		// Setup essential vars
		$page = isset($_GET['page']) ? $_GET['page'] : false;
		$this->preferences = get_option($this->preferencesName);

		// hook minimal functionality on all admin pages
		$this->hookAjax();
		$this->hookAdminMenu();
		$this->hookAdminBarShortcut();
		$this->hookPluginUpdate();
		$this->hookActivation();


		// Load additional functionality on MT admin pages
		if (in_array($page, $this->getAllPluginPages()) ) {
			$this->initMicrothemerPage($page);
		}

		// Non-MT page
		// Support loading MT assets in the admin area (without the user setting a preference)
		else {

			// Pass in the context of loading assets or actually being able to edit the admin area with point and click
			$context = !empty($this->preferences['admin_asset_editing']) ? 'edit' : 'respond';

			new AssetAuth($context);
		}

	}

	function initMicrothemerPage($page){

		$this->new_version = (empty($this->preferences['version']) || $this->preferences['version'] != $this->version);

		// if it's a new version, run the activation/upgrade function (if not done at activation hook)
		// this will update the translations in the JS cached HTML
		// and ensures the pre-update settings are saved in the history table
		if ($this->new_version){
			//$this->microthemer_activated_or_updated();
			add_action('admin_init', array(&$this, 'microthemer_activated_or_updated'));
		}

		// get lang for non-english exceptions (e.g. showing English property labels too)
		$this->locale = get_locale();

		$this->dis_text = __('DISABLED', 'microthemer');
		$this->level_map = array(
			'section' => __('folder', 'microthemer'),
			'selector' => __('selector', 'microthemer'),
			'tab' => __('tab', 'microthemer'),
			'tab-input' => __('tab', 'microthemer'),
			'group' => __('group', 'microthemer'),
			'pgtab' => __('styles', 'microthemer'),
			'subgroup' => __('styles', 'microthemer'),
			'property' => __('property', 'microthemer'),
			'script' => __('Enqueued Script', 'microthemer')
		);


		// check if integratable plugins are active
		add_action( 'admin_init', array(&$this, 'check_integrations'));

		// setup vars that depend on WP being fully loaded
		add_action( 'admin_init', array(&$this, 'setup_wp_dependent_vars'));

		// we don't want the WP admin bar on any Microthemer pages
		add_filter('show_admin_bar', '__return_false');

		// loading URL for iframe
		//$this->placeholderURLs = $this->get_placeholder_urls();

		/* this may need work, ocassionally breaks: http://stackoverflow.com/questions/5441784/why-does-ob-startob-gzhandler-break-this-website
				 * $this->show_me = 'zlib.output_compression config: ('
					. ini_get('zlib.output_compression')
					. ') gzipping HTTP_ACCEPT_ENCODING: (' . $_SERVER['HTTP_ACCEPT_ENCODING']
					. ') substr_count: ' . substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');*/
		// only microthemer needs custom jQuery and gzipping

		// enable gzipping on UI page if defined
		if ( $_GET['page'] == basename(__FILE__) and $this->preferences['gzip'] == 1) {
			if (session_id() === null &&
			    !empty($_SERVER['HTTP_ACCEPT_ENCODING']) &&
			    substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
				ob_start("ob_gzhandler");
			} else {
				ob_start();
			}
		}

		// add scripts and styles
		// Not necessary if this is an ajax call. XDEBUG_PROFILE showed add_js was adding sig time.
		if ( empty($_GET['action']) or $_GET['action'] != 'mtui'){

			add_action('admin_init', array(&$this, 'actionCookieUpdates') );
			add_action('admin_init', array(&$this, 'add_no_cache_headers'), 1);
			add_action('admin_enqueue_scripts', array(&$this, 'add_css'), PHP_INT_MAX);
			add_action('admin_head', array(&$this, 'add_dyn_inline_css'));
			add_action('admin_head', array(&$this, 'load_icon_font')); // icon font and layout
			add_action('admin_enqueue_scripts', array(&$this, 'add_js'), PHP_INT_MAX);
			//add_action( 'wp_body_open', array(&$this, 'add_svg_sprite') );

			// fix compatibility issues due to a plugin loading scripts or styles on MT interface pages
			add_action('admin_enqueue_scripts', array('Microthemer\Common', 'dequeue_rogue_assets'), 1000);
			add_action('wp_enqueue_media', array('Microthemer\Common', 'dequeue_rogue_assets'), 1000);

		} else {
			//echo 'it is an ajax request';
		}

	}

	// Stop the plugin if below requirements
	function checkMinimumRequirements(){
		return $this->required_version() && $this->check_user_memory_limit() && !defined('TVR_MICROBOTH');
	}

	// Hook into WordPress ajax action (for saving settings)
	function hookAjax(){
		add_action('wp_ajax_mtui', array(&$this, 'microthemer_ajax_actions'));

		//echo '<pre>' . print_r($GLOBALS, 1 ) . '</pre>';
	}

	// add menu links (all WP admin pages need this)
	function hookAdminMenu(){
		add_action("admin_menu", array(&$this, "microthemer_dedicated_menu"));
	}

	// add shortcut to Microthemer if preference
	function hookAdminBarShortcut(){

		if ( !empty($this->preferences['admin_bar_shortcut']) ) {
			add_action( 'admin_bar_menu', array(&$this, 'custom_toolbar_link'), 999999);
		}
	}

	// plugin update stuff
	function hookPluginUpdate(){
		add_filter( 'site_transient_update_plugins', array( $this, 'site_transient_update_plugins' ) );
		add_filter( 'plugins_api_result', array( &$this, 'plugins_api_result' ), 99, 3 );
		add_action( 'in_plugin_update_message-microthemer/' . $this->microthemeruipage,
			array( &$this, 'plugin_update_message' ), 1, 2
		);
	}

	// activation hook for setting initial preferences (so e.g. Microthemer link appears in top toolbar)
	function hookActivation(){

		// just flag that Microthemer has been activated on code than runs before activation redirect
		// we can't hook into all WordPress actions prior to the admin redirect
		register_activation_hook( __FILE__, array(&$this, 'flag_microthemer_activation') );

		// check if Microthemer has been activated and run any code that CAN tap into all WP functionality
		add_action( 'admin_init', array(&$this, 'check_microthemer_activation_flag') );
	}

	function actionCookieUpdates(){

		if (isset($_GET['page']) && $_GET['page'] === $this->microthemeruipage){
			if (isset($_COOKIE['microthemer_draft_folder'])){
				$this->deleteCookie('microthemer_draft_folder');
			}
		}
	}



	// user's subscription has expired and they are capped at a version
	function is_capped_version(){
		return !empty($this->preferences['buyer_validated']) and !empty($this->preferences['subscription']['capped_version']);
	}

	function getAllPluginPages(){

		$this->all_pages = array(
			$this->microthemeruipage,
			$this->microthemespage,
			$this->managesinglepage,
			$this->docspage,
			$this->fontspage,
			$this->preferencespage,
			$this->detachedpreviewpage
		);

		return $this->all_pages;
	}

	// @taken from ngg gallery: http://wordpress.org/extend/plugins/nextgen-gallery/
	function required_version() {

		global $wp_version;

		// if requirements not met
		if ( !version_compare($wp_version, $this->minimum_wordpress, '>=') ) {

			add_action(
				'admin_notices',
				function(){
					echo '<div id="message" class="error"><p><strong>' .
					     sprintf(esc_html__('Sorry, Microthemer only runs on WordPress version %s or above. Deactivate Microthemer to remove this message.', 'microthemer'), $this->minimum_wordpress) .
					     '</strong></p></div>';
				}
			);

			return false;
		}

		return true;
	}

	// check the user has a minimal amount of memory
	function check_user_memory_limit() {

		// get memory limit including unit
		$subject = ini_get('memory_limit'); // e.g. 256M
		$pattern = '/([\-0-9]+)/';
		preg_match($pattern, $subject, $matches);
		$this->user_memory_limit = $matches[0];
		$unit = str_replace($matches[0], '', $subject);

		// cautious memory check that will only throw error if memory is given in MB.
		// Too many variables to safely accommodate all e.g. 0, -1, (int) 268435456, 3GB etc
		if (($unit == 'M' || $unit == 'MB') && $this->user_memory_limit < 16){
			// we don't have enough
			add_action(
				'admin_notices',
				function() {
					echo '<div id="message" class="error"><p><strong>' .
					     sprintf( esc_html__( 'Sorry, Microthemer has a memory requirement of 16MB or higher to run. Your allocated memory is less than this (%sMB). Deactivate Microthemer to remove this message. Or increase your memory limit.', 'microthemer' ), $this->user_memory_limit) .
					     '</strong></p></div>';
				}
			);

			return false;
		}

		return true;
	}

	// Microthemer dedicated menu
	function microthemer_dedicated_menu() {

		// for draft mode and preventing two users overwriting each other's edits
		// get_current_user_id() needs to be here (hooked function)
		$this->current_user_id = get_current_user_id();

		add_menu_page(__('Microthemer UI', 'microthemer'), 'Microthemer', 'administrator', $this->microthemeruipage, array(&$this,'microthemer_ui_page'));
		add_submenu_page('options.php',
			__('Manage Design Packs', 'microthemer'),
			__('Manage Packs', 'microthemer'),
			'administrator', $this->microthemespage, array(&$this,'manage_micro_themes_page'));
		add_submenu_page('options.php',
			__('Manage Single Design Pack', 'microthemer'),
			__('Manage Single Pack', 'microthemer'),
			'administrator', $this->managesinglepage, array(&$this,'manage_single_page'));
		add_submenu_page('options.php',
			__('Microthemer Docs', 'microthemer'),
			__('Documentation', 'microthemer'),
			'administrator', $this->docspage, array(&$this,'microthemer_docs_page'));
		add_submenu_page('options.php',
			__('Microthemer Fonts', 'microthemer'),
			__('Fonts', 'microthemer'),
			'administrator', $this->fontspage, array(&$this,'microthemer_fonts_page'));
		add_submenu_page('options.php',
			__('Microthemer Detached Preview', 'microthemer'),
			__('Detached Preview', 'microthemer'),
			'administrator', $this->detachedpreviewpage, array(&$this,'microthemer_detached_preview_page'));
		add_submenu_page($this->microthemeruipage,
			__('Microthemer Preferences', 'microthemer'),
			__('Preferences', 'microthemer'),
			'administrator', $this->preferencespage, array(&$this,'microthemer_preferences_page'));
	}

	// add a link to the WP Toolbar (this was copied from frontend class - use better method later)
	function custom_toolbar_link($wp_admin_bar) {

		if (!current_user_can('administrator')){
			return false;
		}

		if (!empty($this->preferences['top_level_shortcut'])){
			$parent = false;
		} else {
			$parent = 'site-name';
		}

		// root URL to MT UI
		$href = $this->wp_blog_admin_url . 'admin.php?page=' . $this->microthemeruipage;

		// if admin edit post or page - MT should load that page
		$front = $this->get_url_from_edit_screen();

		if ($front){

			$href.= '&mt_preview_url=' . rawurlencode($front['url'])
			        . '&mt_item_id=' . rawurlencode($front['postID'])
			        . '&mt_path_label=' . rawurlencode($front['title'])
			        .' &_wpnonce=' . wp_create_nonce( 'mt-preview-nonce' );

			// not sure how to make a post
			if ($front['post_status'] === 'auto-draft'){
				$href.= '&auto_save_draft='.$front['postID'];
			}

			//wp_die('<pre>'.print_r($front, true).'</pre>');
		}

		$args = array(
			'id' => 'wp-mcr-shortcut',
			'title' => 'Microthemer',
			'parent' => $parent,
			'href' => $href,
			'meta' => array(
				'class' => 'wp-mcr-shortcut',
				'title' => __('Jump to the Microthemer interface', 'microthemer')
			)
		);

		$wp_admin_bar->add_node($args);
	}

	function get_url_from_edit_screen(){

		global $post;

		$url = false;

		if ($post && function_exists('get_current_screen')) {

			$current_screen = get_current_screen();
			$post_type = $current_screen->post_type;
			$isPostOrPage = ($post_type === 'post' || $post_type === 'page');
			$isEditScreen = $isPostOrPage && isset($_GET['action'])
			                && $_GET['action'] === 'edit'
			                && !empty($_GET['post']);
			$isAddScreen = $isPostOrPage && $current_screen->action === 'add';

			//wp_die('<pre>'.print_r($post, true).'</pre>');

			// if add new or saved draft use preview URL
			if ($isAddScreen || $post->post_status !== 'publish'){
				$url = get_preview_post_link($post->ID);
			}

			// get link for published post
			else if ($isEditScreen){
				$url = get_permalink( intval($_GET['post']) );
			}

			if ($url){
				return array(
					'url' => $url,
					'post_status' => $post->post_status,
					'title' => $post->post_title,
					'postID' => $post->ID
				);
			}

			//wp_die('<pre>'.print_r(get_current_screen()->id, true).'</pre>');
		}

		return false;
	}

	// When a WP plugin is activated, we can't hook into all WordPress functionality
	// so the workaround they advise is to flag that activation happened, so when WP
	// redirects to another admin page, the activation functionality can run on the admin_init hook
	// https://developer.wordpress.org/reference/functions/register_activation_hook/
	function flag_microthemer_activation(){
		add_option('microthemer_activation', '1');
	}

	function check_microthemer_activation_flag(){

		if ( get_option( 'microthemer_activation' ) == '1' ) {

			// delete the flag
			delete_option( 'microthemer_activation' );

			// run the activation code
			$this->microthemer_activated_or_updated();
		}
	}

	// ensure preferences are set upon activation
	function microthemer_activated_or_updated(){

		if (!$this->activation_function_ran){

			$pd_context = 'microthemer_activated_or_updated';

			// we need to check_integrations here, so that the default MQs are set for a builder, if active
			//$this->check_integrations(); // is causes an error - is_plugin_active is not defined - need to come back to this
			/*wp_die('check_integrations result on activation: <pre>'.print_r(array(
							'integrations' => $this->integrations,
							'mqs' => $this->mq_sets
						), true).'</pre>' );*/

			// setup program data arrays
			// calls getPreferences() which also sets if nothing to get yet
			// and creates a backup of the settings and preferences if a new version
			include dirname(__FILE__) .'/../includes/program-data.php';

			// if non-english, we need to write to program-data.js in current language
			// log success of overwrite
			// (this didn't work properly on some servers, maybe @fopen suppress would work, but this is safer)
			$pref_array = array(
				'inlineJsProgData' => ( strpos($this->locale, 'en_') === false ) //!$this->write_mt_version_specific_js('../js-min')
			);

			$this->savePreferences($pref_array);

			// Ensure that all new preferences have been set, so the frontend can use new preferences
			$this->getPreferences();


			// todo save all lang strings in DB at this point to save CPU later, start with property-options.inc.php

			// ensure micro-themes dir is created animation-events.js and stock.zip extracted
			$this->setup_micro_themes_dir(true);

			$this->activation_function_ran = true;

		}



	}

	// maybe sets 'Automatic update is unavailable for this plugin'
	function site_transient_update_plugins($transient){

		if ($this->is_capped_version()){
			global $pagenow;
			$plugin = 'microthemer/' . $this->microthemeruipage;
			if ( ('plugins.php' == $pagenow && is_multisite()) or empty($transient->response[$plugin]) ) {
				return $transient;
			}
			$transient->response[$plugin]->package = false;
			$transient->response[$plugin]->upgrade_notice = 'UPDATE UNAVAILABLE. Please renew your subscription to enable updates.';
		}

		return $transient;
	}

	// maybe removes download button from plugin details popup
	function plugins_api_result($res, $action, $args){
		if ($this->is_capped_version()){
			$res->download_link = false;
		}
		return $res;
	}

	// prompts renewal & unlock if version is capped
	// thanks to Beaver Builder for lighting the way here: https://www.wpbeaverbuilder.com/
	function plugin_update_message($plugin_data, $response){

		if ( empty( $response->package ) ) {

			$message  = '<span style="display:block;padding:10px 20px;margin:10px 0; background: #d54e21; color: #fff;">';
			$message .= '<strong>' . __( 'UPDATE UNAVAILABLE!', 'microthemer' ) . '</strong>';
			$message .= '&nbsp;&nbsp;&nbsp;';
			$message .= 'Please renew your subscription to enable updates.';
			$message .= ' ' . sprintf( '<a href="%s" target="_blank" style="color: #fff; text-decoration: underline;">%s </a>', admin_url( '/admin.php?page='.$this->microthemeruipage.'&launch_unlock=1'), __( 'Renew subscription', 'microthemer' ) );
			$message .= '</span>';

			echo  $message;
		}
	}

	function microthemer_ajax_actions(){

		if ( !current_user_can('administrator') ){
			wp_die( 'Access denied' );
		}

		// simple ajax operations that can be executed from any page, pointing to ui page
		if (isset($_GET['mcth_simple_ajax'])) {

			check_ajax_referer( 'mcth_simple_ajax', '_wpnonce' );

			// workspace preferences
			if (isset($_POST['tvr_preferences_form'])) {
				$this->process_preferences_form();
				wp_die();
			}

			// if it's an options save request
			if (isset($_GET['mt_action']) and $_GET['mt_action'] === 'mt_save_interface') {
				$this->actionSaveInterface();
				wp_die();
			}

			// if it's a silent save request for updating ui options (e.g. last viewed selector)
			if (isset($_GET['mt_action']) and $_GET['mt_action'] == 'mt_silent_save_interface') {
				$savePackage = $this->deep_unescape($_POST['savePackage'], 1, 1, 1);
				/*echo 'show_me from ajax save (before): <pre> ';
						print_r($savePackage);
						echo '</pre>';
						return false;*/
				$this->apply_save_package($savePackage, $this->options);
				update_option($this->optionsName, $this->options);
				wp_die();
			}


			// $this->get_site_pages();
			if (isset($_GET['get_site_pages'])) {

				// MT posts search should only check title or slug so we get precise results (that appear in top 10 limit)
				// And because MT will filter out results with no title match on JS side anyway
				add_filter( 'posts_search', array(&$this, 'search_by_title_or_slug'), 10, 2 );

				$searchTerm = isset($_GET['search_term'])
					? htmlentities($_GET['search_term'])
					: null;

				echo json_encode($this->get_site_pages($searchTerm));

				wp_die();
			}

			// ajax - load selectors and/or selector options
			/*if ( isset($_GET['mt_action']) and $_GET['mt_action'] == 'tvr_microthemer_ui_load_styles') {
						//check_admin_referer('tvr_microthemer_ui_load_styles');
						$section_name = strip_tags($_GET['tvr_load_section']);
						$css_selector = strip_tags($_GET['tvr_load_selector']);
						$array = $this->options[$section_name][$css_selector];
						echo '<div id="tmp-wrap">';
						echo $this->all_option_groups_html($section_name, $css_selector, $array);
						echo '</div>';
						// output pulled data to debug file
						if ($this->debug_pulled_data){
							$debug_file = $this->debug_dir . 'debug-pulled-data.txt';
							$write_file = @fopen($debug_file, 'w');
							$data = '';
							$data.= esc_html__('Custom debug output', 'microthemer') . "\n\n";
							$data.= $this->debug_custom;
							$data.= "\n\n" . esc_html__('Last pulled data', 'microthemer') . "\n\n";
							$data.= print_r($this->options[$section_name][$css_selector], true);
							fwrite($write_file, $data);
							fclose($write_file);
						}
						// kill the program - this action is always requested via ajax. no message necessary
						wp_die();
					}*/

			// ajax - toggle draft mode
			if (isset($_GET['draft_mode'])) {

				$pref_array['draft_mode'] = intval($_GET['draft_mode']);

				// ned to get current user id again as $this->current_user_id won't be set in ajax request
				$current_user_id = get_current_user_id();

				// save current user in array
				if ($pref_array['draft_mode']){
					$pref_array['draft_mode_uids'][$current_user_id] = $current_user_id;
				} else {
					// reset if draft mode is off
					$pref_array['draft_mode_uids'] = array();
				}
				$this->savePreferences($pref_array);
				wp_die();
			}

			if (isset($_GET['mt_publish_settings'])) {
				echo $this->publishSettings();
				wp_die();
			}

			// selname_code_synced
			if (isset($_GET['load_sass_import'])) {

				$path = htmlentities(rawurldecode($_GET['load_sass_import']));
				$imports = $this->get_sass_import_paths('@import "'.$path.'";', '');
				$content = false;

				if ($imports){
					$content = $this->recursively_scan_import_files(
						array(
							'import' => $imports[0]
						)
					);
				}

				$response = array(
					'error' => !$content,
					'content' => $content
				);

				echo json_encode($response);
				wp_die();
			}

			// selname_code_synced
			if (isset($_GET['selname_code_synced'])) {
				$pref_array['selname_code_synced'] = intval($_GET['selname_code_synced']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// code_manual_resize
			if (isset($_GET['code_manual_resize'])) {
				$pref_array['code_manual_resize'] = intval($_GET['code_manual_resize']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// ace full page html
			if (isset($_GET['wizard_expanded'])) {
				$pref_array['wizard_expanded'] = intval($_GET['wizard_expanded']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// remember the state of the extra icons in the selectors menu
			if (isset($_GET['show_extra_actions'])) {
				$pref_array['show_extra_actions'] = intval($_GET['show_extra_actions']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// remember the grid highlight status
			if (isset($_GET['grid_highlight'])) {
				$pref_array['grid_highlight'] = intval($_GET['grid_highlight']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// remember show_sampled_values
			if (isset($_GET['show_sampled_values'])) {
				$pref_array['show_sampled_values'] = intval($_GET['show_sampled_values']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// remember show_sampled_variables
			if (isset($_GET['show_sampled_variables'])) {
				$pref_array['show_sampled_variables'] = intval($_GET['show_sampled_variables']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// mt_color_variables_css
			if (isset($_POST['mt_color_variables_css'])) {
				$pref_array['mt_color_variables_css'] = strip_tags($_POST['mt_color_variables_css']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// wizard footer/right dock
			if (isset($_GET['dock_wizard_right'])) {
				$pref_array['dock_wizard_right'] = intval($_GET['dock_wizard_right']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// wizard footer/right dock
			if (isset($_GET['dock_settings_right'])) {
				$pref_array['dock_settings_right'] = intval($_GET['dock_settings_right']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// instant hover inspection
			if (isset($_GET['hover_inspect'])) {
				$pref_array['hover_inspect'] = intval($_GET['hover_inspect']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// auto folders
			if (isset($_GET['auto_folders'])) {
				$pref_array['auto_folders'] = intval($_GET['auto_folders']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// auto folders
			if (isset($_GET['auto_folders_page'])) {
				$pref_array['auto_folders_page'] = intval($_GET['auto_folders_page']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// ajax - update preview url after page navigation
			if (isset($_GET['mt_preview_url'])) {
				$this->maybe_set_preview_url();
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// ajax - update preview url after page navigation
			if (isset($_GET['import_css_url'])) {
				// update view_import_stylesheets list with possible new stylesheet
				$this->update_css_import_urls(strip_tags(rawurldecode($_GET['import_css_url'])));
				wp_die();
			}

			// code editor focus
			if (isset($_GET['show_code_editor'])) {
				$pref_array = array();
				$pref_array['show_code_editor'] = intval($_GET['show_code_editor']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// ruler show/hide
			if (isset($_GET['show_rulers'])) {
				$pref_array = array();
				$pref_array['show_rulers'] = intval($_GET['show_rulers']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// dark theme
			if (isset($_GET['mt_dark_mode'])) {
				$pref_array = array();
				$pref_array['mt_dark_mode'] = intval($_GET['mt_dark_mode']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// dark theme
			if (isset($_GET['sync_browser_tabs'])) {
				$pref_array = array();
				$pref_array['sync_browser_tabs'] = intval($_GET['sync_browser_tabs']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// auto-load Elementor, BB, Oxygen
			if (isset($_GET['autoload_elementor'])) {
				$this->savePreferences(array(
					'autoload_elementor' => intval($_GET['autoload_elementor'])
				));
				wp_die();
			} if (isset($_GET['autoload_FLBuilder'])) {
				$this->savePreferences(array(
					'autoload_FLBuilder' => intval($_GET['autoload_FLBuilder'])
				));
				wp_die();
			} if (isset($_GET['autoload_oxygen'])) {
				$this->savePreferences(array(
					'autoload_oxygen' => intval($_GET['autoload_oxygen'])
				));
				wp_die();
			}

			// Auto-publish mode
			if (isset($_GET['auto_publish_mode'])) {
				$pref_array = array();
				$pref_array['auto_publish_mode'] = intval($_GET['auto_publish_mode']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			if (isset($_GET['sticky_styles_toolbar'])) {
				$this->savePreferences(array(
					'sticky_styles_toolbar' => intval($_GET['sticky_styles_toolbar'])
				));
				wp_die();
			}

			if (isset($_GET['pseudo_base_styles'])){
				$this->savePreferences(array(
					'pseudo_base_styles' => intval($_GET['pseudo_base_styles'])
				));
				wp_die();
			}

			// specificity preference
			if (isset($_GET['specificity_preference'])) {
				$pref_array = array();
				$pref_array['specificity_preference'] = intval($_GET['specificity_preference']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// sidebar size
			/* if (isset($_GET['sidebar_size'])) {
						 $pref_array = array();
						 $pref_array['sidebar_size'] = intval($_GET['sidebar_size']);
						 $pref_array['sidebar_size_category'] = htmlentities($_GET['sidebar_size']);
						 $this->savePreferences($pref_array);
						 // kill the program - this action is always requested via ajax. no message necessary
						 wp_die();
					 }*/

			// save new MT layout
			if (isset($_GET['update_mt_layout'])) {
				$data = json_decode( stripslashes($_POST['tvr_serialized_data']), true );
				$this->savePreferences(array(
					'layout' => $data
				));
				wp_die();
			}

			// dock folders left
			if (isset($_GET['dock_folders_left'])) {
				$pref_array = array();
				$pref_array['dock_folders_left'] = intval($_GET['dock_folders_left']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// dock styles left
			if (isset($_GET['dock_styles_left'])) {
				$pref_array = array();
				$pref_array['dock_styles_left'] = intval($_GET['dock_styles_left']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// dock editor left
			if (isset($_GET['dock_editor_left'])) {
				$pref_array = array();
				$pref_array['dock_editor_left'] = intval($_GET['dock_editor_left']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// full_height_left_sidebar
			if (isset($_GET['full_height_left_sidebar'])) {
				$pref_array = array();
				$pref_array['full_height_left_sidebar'] = intval($_GET['full_height_left_sidebar']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// expand_device_tabs
			if (isset($_GET['expand_device_tabs'])) {
				$pref_array = array();
				$pref_array['expand_device_tabs'] = intval($_GET['expand_device_tabs']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// update multiple preferences in one go
			if (isset($_GET['save_multiple_preferences'])) {

				$pref_array = array();

				foreach($_POST['pref_array'] as $key => $value){
					$pref_array[$key] = is_numeric($value) ? floatval($value) : $value;
				}

				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// if it's an email error report request
			elseif (isset($_GET['send_error_report'])){

				$manual = (bool) intval($_POST['manual']);

				$response = $this->maybeSendReport($_POST, $manual);

				if ($manual || TVR_DEV_MODE){
					echo json_encode(array(
						//'html'=> '<div id="microthemer-notice">'. $this->display_log() . '</div>',
						'response' => $response,
						'success' => !!$response
					));
				}

				wp_die();
			}


			// dock ALL options left
			/* if (isset($_GET['dock_styles_left'])) {
						 $pref_array = array();
						 $pref_array['dock_styles_left'] = intval($_GET['dock_styles_left']);
						 $this->savePreferences($pref_array);
						 // kill the program - this action is always requested via ajax. no message necessary
						 wp_die();
					 }*/

			// detach preview -
			if (isset($_GET['detach_preview'])) {
				$pref_array = array();
				$pref_array['detach_preview'] = intval($_GET['detach_preview']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// ruler show/hide
			if (isset($_GET['show_text_labels'])) {
				$pref_array = array();
				$pref_array['show_text_labels'] = intval($_GET['show_text_labels']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// show/hide whole interface
			if (isset($_GET['hide_interface'])) {
				$pref_array = array();
				$pref_array['hide_interface'] = intval($_GET['hide_interface']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// active MQ tab
			if (isset($_GET['manual_recompile_all_css'])) {
				$pref_array = array();
				$pref_array['manual_recompile_all_css'] = htmlentities($_GET['manual_recompile_all_css']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// remove_all_bricks_container_hacks
			if (isset($_GET['remove_all_bricks_container_hacks'])) {
				$pref_array = array();
				$pref_array['remove_all_bricks_container_hacks'] = intval($_GET['remove_all_bricks_container_hacks']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// initial_view_set
			if (isset($_GET['initial_view_set'])) {
				$pref_array = array();
				$pref_array['initial_view_set'] = intval($_GET['initial_view_set']);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// active MQ tab
			if (isset($_GET['mq_device_focus'])) {
				$pref_array = array();
				$pref_array['mq_device_focus'] = htmlentities($_GET['mq_device_focus']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// active MQ tab
			if (isset($_GET['rev_save_status'])) {
				$this->updateRevisionSaveStatus(
					intval($_GET['rev_id']),
					intval($_GET['rev_save_status'])
				);
				wp_die();
			}

			// active CSS tab
			if (isset($_GET['css_focus'])) {
				$pref_array = array();
				$pref_array['css_focus'] = htmlentities($_GET['css_focus']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// update_default_unit
			if (isset($_GET['update_default_unit'])) {
				$data = json_decode( stripslashes($_POST['tvr_serialized_data']), true );
				$this->preferences['my_props'][$data['group']]['pg_props'][$data['prop']]['default_unit'] = $data['unit'];
				$pref_array['my_props'] = $this->preferences['my_props'];
				$this->savePreferences($pref_array);

				wp_die();
			}

			// MT may update custom paths array via JS (e.g. path clear) and then post full array to replace current
			if (isset($_GET['update_custom_paths'])) {
				$pref_array['custom_paths'] = json_decode(
					stripslashes($_POST['tvr_serialized_data']),
					true
				);
				$this->savePreferences($pref_array);
				wp_die();
			}

			// update draft folder session
			if (isset($_GET['update_draft_folder_session'])) {
				//add_action( 'init', array(&$this, 'updateDraftFolderCookie') );
				$this->updateDraftFolderCookie();
			}

			// update_recent_custom_prefixes
			if (isset($_GET['update_recent_custom_prefixes'])) {
				$pref_array['recent_custom_prefixes'] = json_decode( stripslashes($_POST['tvr_serialized_data']), true );
				$this->savePreferences($pref_array);
				wp_die();
			}

			// update suggested values
			if (isset($_GET['update_sug_values'])) {

				$pref_array = array();
				$root_cat = $_GET['update_sug_values'];

				// tap into WordPress native JSON functions
				/*if( !class_exists('Moxiecode_JSON') ) {
							require_once($this->thisplugindir . 'includes/class-json.php');
						}

						$json_object = new Moxiecode_JSON();*/

				$data = json_decode( stripslashes($_POST['tvr_serialized_data']), true );

				// if we're setting suggested values for all properties
				if ($root_cat == 'all'){
					$this->preferences['my_props']['sug_values'] = $data['sug_values'];
					$this->preferences['my_props']['sug_variables'] = $data['sug_variables'];
				}  elseif ($root_cat == 'synced_set') {
					// a set of fields in one go e.g. padding
					$this->preferences['my_props']['sug_values'] =
						array_merge($this->preferences['my_props']['sug_values'], $data['synced_set']);
				} else {
					// just setting suggestions for a type of property e.g. site_colors

					if (!empty($data['specific'])){
						$this->preferences['my_props']['sug_values'][$root_cat] = $data['specific'];
					}
				}

				// update variable if passed
				if (isset($data['sug_variables'])){
					$this->preferences['my_props']['sug_variables'] = $data['sug_variables'];
					$pref_array['default_sug_variables_set'] = 1;
				}

				$pref_array['default_sug_values_set'] = 1;
				$pref_array['my_props'] = $this->preferences['my_props'];
				$this->savePreferences($pref_array);

				//echo '<pre>posted array: '.print_r($data, true).'</pre>';

				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// save google/typekit fonts config
			if (isset($_GET['save_font_config'])) {

				// tap into WordPress native JSON functions
				/*if( !class_exists('Moxiecode_JSON') ) {
							require_once($this->thisplugindir . 'includes/class-json.php');
						}

						$json_object = new Moxiecode_JSON();*/

				$data = json_decode( stripslashes($_POST['tvr_serialized_data']), true );
				$pref_array = array();
				$key = $_GET['save_font_config'] == 'google' ? 'google' : 'typekit';
				$pref_array['font_config'][$key] = $data;

				$this->savePreferences($pref_array);

				//echo '<pre>posted array: '.print_r($data, true).'</pre>';

				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// active property group
			if (isset($_GET['pg_focus'])) {
				$pref_array = array();
				$pref_array['pg_focus'] = htmlentities($_GET['pg_focus']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// active generated_css_focus
			/*if (isset($_GET['generated_css_focus'])) {
				$pref_array = array();
				$pref_array['generated_css_focus'] = intval($_GET['generated_css_focus']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}*/

			// remember selector wizard tab
			if (isset($_GET['adv_wizard_tab'])) {
				$pref_array = array();
				$pref_array['adv_wizard_tab'] = htmlentities($_GET['adv_wizard_tab']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// remember the program settings tab
			if (isset($_GET['program_settings_tab'])) {
				$pref_array = array();
				$pref_array['program_settings_tab'] = htmlentities($_GET['program_settings_tab']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// remember selector wizard tab
			if (isset($_GET['grid_focus'])) {
				$pref_array = array();
				$pref_array['grid_focus'] = htmlentities($_GET['grid_focus']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// remember transform tab
			if (isset($_GET['transform_focus'])) {
				$pref_array = array();
				$pref_array['transform_focus'] = htmlentities($_GET['transform_focus']);
				$this->savePreferences($pref_array);
				// kill the program - this action is always requested via ajax. no message necessary
				wp_die();
			}

			// last viewed selector
			/*if (isset($_GET['last_viewed_selector'])) {
						$pref_array = array();
						$pref_array['last_viewed_selector'] = htmlentities($_GET['last_viewed_selector']);
						$this->savePreferences($pref_array);
						// kill the program - this action is always requested via ajax. no message necessary
						wp_die();
					}*/

			// download pack
			if (!empty($_GET['mt_action']) and
			    $_GET['mt_action'] == 'tvr_download_pack') {
				if (!empty($_GET['dir_name'])) {
					// first of all, copy any images from the media library
					$pack = $_GET['dir_name'];
					$dir = $this->micro_root_dir . $pack;
					$json_config_file = $dir . '/config.json';
					if ($library_images = $this->get_linked_library_images($json_config_file)){
						foreach($library_images as $key => $path){
							// strip site_url rather than home_url in this case coz using with ABSPATH
							$root_rel_path = $this->root_rel($path, false, true, true);
							$basename = basename($root_rel_path);
							$orig = rtrim(ABSPATH,"/"). $root_rel_path;
							$img_paths[] = $new = $dir . '/' . $basename;
							$replacements[$path] = $this->root_rel(
								$this->micro_root_url . $pack . '/' . $basename, false, true
							);
							if (!copy($orig, $new)){
								$this->log(
									esc_html__('Library image not downloaded', 'microthemer'),
									'<p>' . sprintf(esc_html__('%s could not be copied to the zip download file', 'microthemer'), $root_rel_path) . '</p>',
									'warning'
								);
								$download_status = 0;
							}
						}
						// cache original config file data
						$orig_json_data = $this->get_file_data($json_config_file);

						// update image paths in config.json for zip only (we'll restore shortly)
						$this->replace_json_paths($json_config_file, $replacements, $orig_json_data);
					}

					// now zip the contents
					if (
						$this->create_zip(
							$this->micro_root_dir,
							$pack,
							$this->thisplugindir.'zip-exports/')
					){
						$download_status = 1;
					} else {
						$download_status = 0;
					}
				}
				else {
					$download_status = 0;
				}
				// delete any media library images temporarily copied to the directory
				if ($library_images){
					// restore orgin config.json paths
					$this->write_file($json_config_file, $orig_json_data);
					// delete images
					foreach ($img_paths as $key => $path){
						if (!unlink($path)){
							$this->log(
								esc_html__('Temporary image could not be deleted.', 'microthemer'),
								'<p>' . sprintf( esc_html__('%s was temporarily copied to your theme pack before download but could not be deleted after the download operation finished.', 'microthemer'), $this->root_rel($root_rel_path) ) . '</p>',
								'warning'
							);
						}
					}
				}
				echo '
							<div id="microthemer-notice">'
				     . $this->display_log() . '
								<span id="download-status" rel="'.$download_status.'"></span>
							</div>';
				wp_die();
			}

			// delete pack
			if (!empty($_GET['mt_action']) and
			    $_GET['mt_action'] == 'tvr_delete_micro_theme') {
				if (!empty($_GET['dir_name']) and $this->tvr_delete_micro_theme($_GET['dir_name'])){
					$delete_status = 1;
				} else {
					$delete_status = 0;
				}
				echo '
							<div id="microthemer-notice">'
				     . $this->display_log() . '
								<span id="delete-status" rel="'.$delete_status.'"></span>
							</div>';
				wp_die();
			}

			// download remote css file
			if (!empty($_GET['mt_action']) and
			    $_GET['mt_action'] == 'tvr_get_remote_css') {
				$config['allowed_ext'] = array('css');
				$r = $this->get_safe_url(rawurldecode($_GET['url']), $config);
				echo '
							<div id="microthemer-notice">'
				     . $this->display_log() . '
								<div id="remote-css">'.(!empty($r['content']) ? $r['content'] : 0).'</div>
							</div>';
				wp_die();
			}

			// if it's an import request
			if ( !empty($_POST['import_pack_or_css']) ){

				// if importing raw CSS
				if (!empty($_POST['stylesheet_import_json'])){

					$context = esc_attr__('Raw CSS', 'microthemer');
					$json_str = stripslashes($_POST['stylesheet_import_json']);
					$p = $_POST['tvr_preferences'];

					// checkbox values must be explicitly evaluated
					$p['css_imp_only_selected'] = !empty($p['css_imp_only_selected']) ? 1 : 0;

					// handle remote image import. See plugins that do this:
					// https://premium.wpmudev.org/blog/download-remote-images-into-wordpress/
					if (!empty($_POST['get_remote_images'])){

						$r_images = explode('|', $_POST['get_remote_images']);
						$do_copy = false;
						$remote_images = array();
						$all_r = array();
						foreach ($r_images as $i => $both){
							$tmp = explode(',', $both);
							$path_in_data = $tmp[0];
							$full_url = $tmp[1];
							// save to temp dir first
							$r = $this->get_safe_url($full_url, array(
								'allowed_ext' => array('jpg', 'jpeg', 'gif', 'png', 'svg'),
								'tmp_file' => 1
							));

							if ($r){
								$remote_images[$path_in_data] = $r['tmp_file'];
								$do_copy = true;
								//$all_r[++$i] = $r;
							}

						}

						// do image copy function
						if ($do_copy){

							$updated_json_str = $this->import_pack_images_to_library(
								false,
								'custom',
								$json_str,
								$remote_images
							);

							$json_str = $updated_json_str ? $updated_json_str : $json_str;
						}

					}

					// load the json file
					$this->load_json_file(false, 'custom', $context, $json_str);

					// save the import preferences
					$this->savePreferences($p);
				}

				// if importing an MT design pack
				else {


					$theme_name = sanitize_file_name(sanitize_title(htmlentities($_POST['import_from_pack_name'])));


					$json_file = $this->micro_root_dir . $theme_name . '/config.json';

					$context = $_POST['tvr_import_method'];

					// import any background images that may need moving to the media library and update json
					$this->import_pack_images_to_library($json_file, $theme_name);

					// load the json file
					$this->load_json_file($json_file, $theme_name, $context);

				}

				// signal that all selectors should be recompiled (to ensure latest data structure)
				$this->update_preference('manual_recompile_all_css', 1);

				// update the revisions DB field
				if (!$this->updateRevisions($this->options, $this->json_format_ua(
					'import-from-pack lg-icon',
					esc_html__('Import', 'microthemer') . ' ('.$context.'):&nbsp;',
					$this->readable_name($theme_name)
				))) {
					$this->log('','','error', 'revisions');
				}

				// save last message in database so that it can be displayed on page reload (just once)
				$this->cache_global_msg();
				wp_die();
			}



			// if it's a reset request
			elseif( isset($_GET['mt_action']) and $_GET['mt_action'] == 'tvr_ui_reset'){
				if ($this->resetUiOptions()) {
					$this->update_assets('customised');
					$item = esc_html__('Folders were reset', 'microthemer');
					$this->log(
						$item,
						'<p>' . esc_html__('The default empty folders have been reset.', 'microthemer') . '</p>',
						'notice'
					);
					// update the revisions DB field
					if (!$this->updateRevisions($this->options, $this->json_format_ua(
						'folder-reset lg-icon',
						$item
					))) {
						$this->log(
							esc_html__('Revision failed to save', 'microthemer'),
							'<p>' . esc_html__('The revisions table could not be updated.', 'microthemer') . '</p>',
							'notice'
						);
					}
				}
				// save last message in database so that it can be displayed on page reload (just once)
				$this->cache_global_msg();
				wp_die();
			}

			// if it's a restore revision request
			if(isset($_GET['mt_action']) and $_GET['mt_action'] == 'restore_rev'){
				$rev_key = $_GET['tvr_rev'];
				if ($this->restoreRevision($rev_key)) {
					$item = esc_html__('Previous settings restored', 'microthemer');
					$this->log(
						$item,
						'<p>' . esc_html__('Your settings were successfully restored from a previous save.', 'microthemer') . '</p>',
						'notice'
					);
					$this->update_assets('customised');
					// update the revisions DB field
					if (!$this->updateRevisions($this->options, $this->json_format_ua(
						'display-revisions lg-icon',
						$item
					))) {
						$this->log('','','error', 'revisions');
					}
				}
				else {
					$this->log(
						esc_html__('Settings restore failed', 'microthemer'),
						'<p>' . esc_html__('Data could not be restored from a previous save.', 'microthemer') . '</p>'
					);
				}
				// save last message in database so that it can be displayed on page reload (just once)
				$this->cache_global_msg();
				wp_die();
			}

			// if it's a get revision ajax request
			elseif(isset($_GET['mt_action']) and $_GET['mt_action'] == 'get_revisions'){
				echo '<div id="tmp-wrap">' . $this->getRevisions() . '</div>'; // outputs table
				wp_die();
			}


			/* PREFERENCES FUNCTIONS MOVED TO MAIN UI */

			// update the MQs
			if (isset($_POST['tvr_media_queries_submit'])){

				$orig_media_queries = $this->preferences['m_queries'];

				// remove backslashes from $_POST
				$_POST = $this->deep_unescape($_POST, 0, 1, 1);
				// get the initial scale and default width for the "All Devices" tab
				$pref_array['initial_scale'] = $_POST['tvr_preferences']['initial_scale'];
				$pref_array['all_devices_default_width'] = $_POST['tvr_preferences']['all_devices_default_width'];
				// reset default media queries if all empty
				$action = '';
				if (empty($_POST['tvr_preferences']['m_queries'])) {
					$pref_array['m_queries'] = $this->default_mqs;
					$action = 'reset';
				} else {
					$pref_array['m_queries'] = $_POST['tvr_preferences']['m_queries'];
					$action = 'update';
				}

				// are we merging/overwriting with a new media query set
				if (!empty($_POST['tvr_preferences']['load_mq_set'])){
					//print_r($this->mq_sets);
					$action = 'load_set';
					$new_set = $_POST['tvr_preferences']['load_mq_set'];
					$new_mq_set = $this->mq_sets[$new_set];
					$pref_array['overwrite_existing_mqs'] = $_POST['tvr_preferences']['overwrite_existing_mqs'];
					if (!empty($pref_array['overwrite_existing_mqs'])){
						$pref_array['m_queries'] = $new_mq_set;
						$load_action = esc_html__('replaced', 'microthemer');
					} else {
						$pref_array['m_queries'] = array_merge($pref_array['m_queries'], $new_mq_set);
						$load_action = esc_html__('was merged with', 'microthemer');
					}
				}

				// format media query min/max width (height later) and units
				$pref_array['m_queries'] = $this->mq_min_max($pref_array);

				// save and preset message
				$pref_array['num_saves'] = ++$this->preferences['num_saves'];

				if (empty($this->preferences['auto_publish_mode'])){
					$pref_array['num_unpublished_saves'] = ++$this->preferences['num_unpublished_saves'];
				}

				if ($this->savePreferences($pref_array)) {

					switch ($action) {
						case 'reset':
							$this->log(
								esc_html__('Media queries reset', 'microthemer'),
								'<p>' . esc_html__('The default media queries were successfully reset.', 'microthemer') . '</p>',
								'notice'
							);
							break;
						case 'update':
							$this->log(
								esc_html__('Media queries updated', 'microthemer'),
								'<p>' . esc_html__('Your media queries were successfully updated.', 'microthemer') . '</p>',
								'notice'
							);
							break;
						case 'load_set':
							$this->log(
								esc_html__('Media query set loaded', 'microthemer'),
								'<p>' . sprintf( esc_html__('A new media query set %s your existing media queries: %s', 'microthemer'), $load_action, htmlentities($_POST['tvr_preferences']['load_mq_set']) ) . '</p>',
								'notice'
							);
							break;
					}

					// if the user deleted a media query, ensure data is cleaned from the ui data
					$this->clean_deleted_media_queries($orig_media_queries, $pref_array['m_queries']);

				}
				// save last message in database so that it can be displayed on page reload (just once)
				$this->cache_global_msg();
				wp_die();
			}

			// update the enqueued JS files
			if (isset($_POST['mt_enqueue_js_submit'])){
				// remove backslashes from $_POST
				$_POST = $this->deep_unescape($_POST, 0, 1, 1);
				$pref_array['enq_js'] = $_POST['tvr_preferences']['enq_js'];
				$pref_array['num_saves'] = ++$this->preferences['num_saves'];

				if (empty($this->preferences['auto_publish_mode'])){
					$pref_array['num_unpublished_saves'] = ++$this->preferences['num_unpublished_saves'];
				}

				// save and present message
				if ($this->savePreferences($pref_array)) {
					$this->log(
						esc_html__('Enqueued scripts were updated', 'microthemer'),
						'<p>' . esc_html__('Your enqueued scripts were successfully updated.', 'microthemer') . '</p>',
						'notice'
					);
				}

				// save last message in database so that it can be displayed on page reload (just once)
				$this->cache_global_msg();
				wp_die();
			}

			// reset default preferences
			if (isset($_POST['tvr_preferences_reset'])) {
				check_admin_referer('tvr_preferences_reset');
				$pref_array = $this->default_preferences;
				if ($this->savePreferences($pref_array)) {
					$this->log(
						esc_html__('Preferences were reset', 'microthemer'),
						'<p>' . esc_html__('The default program preferences were reset.', 'microthemer') . '</p>',
						'notice'
					);
				}
			}

			// save the conditional logic configuration
			/*if (isset($_GET['update_asset_loading'])) {
				$pref_array['asset_loading'] = json_decode(
					stripslashes($_POST['tvr_serialized_data']),
					true
				);
				$this->savePreferences($pref_array);
				wp_die();
			}*/



			// css filter configs
			$filter_types = array('page_specific', 'pseudo_classes', 'pseudo_elements');
			foreach ($filter_types as $type){
				if (isset($_GET[$type])) {
					$pref_sub_key = $_GET['pref_sub_key'];
					//$value = $_GET['pref_sub_key'] === 'custom-prefix' ? $_GET[$type] : intval($_GET[$type]);
					$this->preferences[$type][$pref_sub_key] = intval($_GET[$type]);
					$pref_array[$type] = $this->preferences[$type];

					if (isset($_GET['extraValue'])){
						$pref_array[$pref_sub_key.'-extraValue'] = $_GET['extraValue'];
					}

					$this->savePreferences( $pref_array );
					//echo '<pre>'. print_r($this->preferences[$type], true).'</pre>';
					wp_die();
				}
			}

			// if we got to hear, the ajax request didn't work as intended, so warn
			echo 'Yo! The Ajax call failed to trigger any function. Sort it out.';
			wp_die();

		}

	}

	function updateDraftFolderCookie(){

		$key = 'microthemer_draft_folder';

		if (isset($_GET['delete_value'])){
			$this->deleteCookie($key);
		} else {
			$this->pluginCookie(
				$key,
				stripslashes($_POST['tvr_serialized_data'])
			);
		}

		wp_die();
	}

}
