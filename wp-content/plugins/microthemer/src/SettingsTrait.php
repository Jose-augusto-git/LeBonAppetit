<?php

namespace Microthemer;

trait SettingsTrait {

	function actionSaveInterface(){

		// remove slashes and custom escaping so that DB data is clean
		$this->serialised_post =
			$this->deep_unescape($_POST, 1, 1, 1);

		if (!empty($this->serialised_post['serialise'])){
			$this->serialised_post['tvr_mcth'] = $this->json('decode', $this->serialised_post['tvr_mcth']);
			//json_decode($this->serialised_post['tvr_mcth'], true);
			/*echo 'show_me from tvr_mcth: <pre> ';
					print_r($_POST);
					echo '</pre>';*/
		}

		// bail if no save data was successfully decoded
		if (empty($this->serialised_post['tvr_mcth'])) {
			return false;
		}

		// strange Kinsta error prompted this but might have been a fleeting issue
		$partial = !empty($this->serialised_post['partial_data'])
			? $this->serialised_post['partial_data']
			: false;
		$last_save_time = !empty($this->serialised_post['last_save_time'])
			? $this->serialised_post['last_save_time']
			: false;
		$new_select_option = '';


		/*$debug = true;
		if ($debug){
			echo 'show_me from ajax save (before): <pre>';
			echo print_r($this->serialised_post, 1);
			echo '</pre>';
		}*/

		// save settings in DB
		if (!$this->saveUiOptions2(
			$this->serialised_post['tvr_mcth'],
			$partial,
			$last_save_time
		)) {

			// save error
			$this->log(
				esc_html__('Settings failed to save', 'microthemer'),
				'<p>' . esc_html__('Saving your settings to the database failed.', 'microthemer') . '</p>'
			);
		}

		// save successful
		else {

			$saveOk = empty($this->preferences['auto_publish_mode'])
				? esc_html__('Draft saved', 'microthemer')
				: esc_html__('Settings saved and published', 'microthemer');

			$this->log(
				$saveOk,
				'<p>' . esc_html__('The UI interface settings were successfully saved.', 'microthemer') . '</p>',
				'notice'
			);



			// check if settings need to be exported to a design pack
			if (!empty($this->serialised_post['export_to_pack']) && $this->serialised_post['export_to_pack'] == 1) {

				$theme = htmlentities($this->serialised_post['export_pack_name']);
				$context = 'existing';
				$do_option_insert = false;

				if ($this->serialised_post['new_pack'] == 1){
					$context = 'new';
					$do_option_insert = true;
				}

				// function return sanitised theme name
				$theme = $this->update_json_file($theme, $context);

				// save new sanitised theme in span for updating select menu via jQuery
				if ($do_option_insert) {
					$meta_file = $this->micro_root_dir.$theme.'/meta.txt';
					if (file_exists($meta_file)){
						$meta_info = $this->read_meta_file($meta_file);
						$new_select_option = $meta_info['Name'];
					} else {
						$new_select_option = $this->readable_name($theme);
					}

				}
				//$user_action.= sprintf( esc_html__(' & Export to %s', 'microthemer'), '<i>'. $this->readable_name($theme). '</i>');
			}

			// else its a standard save of custom settings
			else {
				$theme = 'customised';
				//$user_action.= esc_html__(' (regular)', 'microthemer');
			}

			// update active-styles.css
			$this->update_assets($theme);

			// update the revisions DB field
			$user_action = !empty($this->serialised_post['user_action'])
				? json_encode($this->serialised_post['user_action'])
				: null;

			if (!$this->updateRevisions($this->options, $user_action)) {
				$this->log('','','error', 'revisions');
			}
		}



		//echo 'carrots!';
		//wp_die();

		// return the globalmessage and then kill the program - this action is always requested via ajax
		// also fullUIData as an interim way to keep JS ui data up to date (post V5 will have new system with less http)
		$html = '<div id="microthemer-notice">' . $this->display_log() . '</div>';

		/*<span id="outdated-tab-issue">'.$this->outdatedTabIssue.'</span>
							<span id="returned-save-time">'.$this->options['non_section']['last_save_time'].'</span>*/

		// we're returning a JSON obejct here, the HTML is added as a property of the object
		$response = array(
			//'prefs' => $this->preferences,
			'html'=> $html,
			'outdatedTab'=> $this->outdatedTabIssue,
			'outdatedTabDebug'=> $this->outdatedTabDebug,
			'returnedSaveTime'=> $this->options['non_section']['last_save_time'],
			'returnedRevisions' => $this->getRevisions(true),
			'exportName' => $new_select_option,
			'num_saves' => $this->preferences['num_saves'],
			'asset_loading' => $this->preferences['asset_loading'],
			'asset_loading_change' => $this->asset_loading_change,
			'adjusted_logic' => !empty($this->serialised_post['adjusted_logic']),
			'recent_logic' => $this->preferences['recent_logic'],
			'num_unpublished_saves' => $this->preferences['num_unpublished_saves']
			//'uiData'=> $this->options
			//'uiData'=> array()
		);

		echo json_encode($response); //$html;
	}

	function publishSettings(){

		$root = $this->micro_root_dir;
		$conditionalRoot = $root . 'mt/conditional/';
		$draftDir  = $conditionalRoot . 'draft/';
		$activeDir = $conditionalRoot . 'active/';
		$minifyCSS = !empty($this->preferences['minify_css']);
		$minifyJS = !empty($this->preferences['minify_js']);

		// get a list of the active files before copying over
		$origActiveFiles = $this->getDirectoryFileList($activeDir);

		// copy the global files
		$globals = array('styles.css', 'styles.scss', 'scripts.js');

		foreach ($globals as $file){

			$draft_file = $root . 'draft-' . $file;
			$published_file = $root . 'active-' . $file;
			$ext = $this->get_extension($file);

			// copy the draft file if it exists
			if (file_exists($draft_file)){

				$doMinify = $ext === 'css' && $minifyCSS || $ext === 'js' && $minifyJS;

				// Minify if user preference
				if ($doMinify){

					if (!$this->minify($draft_file, $ext, $published_file)){

						$this->log(
							esc_html__('File not minified', 'microthemer'),
							'<p>' . sprintf(
								esc_html__('Asset failed to minify and publish: %s', 'microthemer'),
								$this->root_rel($published_file)
							) . '</p>',
							'error'
						);
					}
				}

				// Simply copy the file
				else {

					if (!copy($draft_file, $published_file)){

						$this->log(
							esc_html__('File not published', 'microthemer'),
							'<p>' . sprintf(
								esc_html__('Asset failed to publish: %s', 'microthemer'),
								$this->root_rel($published_file)
							) . '</p>',
							'error'
						);
					}
				}


			}

			// if the draft file doesn't exist, but the published one does, clean the published file
			elseif (file_exists($published_file)){
				//echo 'we should del pub: ' . $published_file;
				unlink($published_file);
			}
		}

		// copy the conditional draft folder to the conditional active folder
		$this->copyFolder($draftDir, $activeDir, $minifyCSS, $minifyJS);

		// delete any lingering conditional files that are no longer needed in the published directory
		foreach ($origActiveFiles as $activeFile){
			if (!file_exists($draftDir . $activeFile)){
				unlink($activeDir . $activeFile);
			}
		}

		// update the published preferences
		$this->savePreferences(array(
			'num_unpublished_saves' => 0,
			'asset_loading_published' => $this->preferences['asset_loading'],
			'global_stylesheet_required_published' => $this->preferences['global_stylesheet_required'],
			'load_js_published' => $this->preferences['load_js'],
		));

		return json_encode(array(
			'num_unpublished_saves' => 0,
			//'html' => '<div id="microthemer-notice">' . $this->display_log() . '</div>' // todo show in UI
		));

	}



	// Resest the options.
	function resetUiOptions(){
		delete_option($this->optionsName);
		$this->getOptions(); // reset the defaults
		$pref_array = array();
		$pref_array['active_theme'] = 'customised';
		$pref_array['theme_in_focus'] = '';
		$pref_array['num_saves'] = 0;
		$pref_array['g_fonts_used'] = false;
		$pref_array['g_url'] = '';
		$pref_array['g_url_with_subsets'] = '';
		$pref_array['hover_inspect'] = 1;
		$this->savePreferences($pref_array);
		return true;
	}

	// Save the UI styles to the database - from full or partial save package
	function saveUiOptions2($savePackage, $partial = false, $last_save_time = false){

		// check last save time
		if (!$this->check_last_save_time($last_save_time)){
			return false;
		}

		// plain save if no save package
		if (!$partial){
			$this->options = $savePackage;
		}

		// loop through update items making adjustments to $this->options
		else {
			$this->apply_save_package($savePackage, $this->options);
		}

		// tag version the settings were saved at so e.g. css units can be imported correctly for legacy data
		$this->options['non_section']['mt_version'] = $this->version;

		// update DB
		update_option($this->optionsName, $this->options);

		return true;

	}

	// check the last save time
	function check_last_save_time($last_save_time){

		// if we have no last_save_time to compare, set it for future reference
		if (!isset($this->options['non_section']['last_save_time'])){
			$this->options['non_section']['last_save_time'] = time();
		}

		// else we do have a time in the DB and a passed save time to compare
		else if ($last_save_time){

			// do safety check to make sure newer settings haven't been applied in another tab
			// allow passed last save time to be 15 seconds out due to quirk of resave I haven't fully understood
			if ( intval($last_save_time + 10) < intval($this->options['non_section']['last_save_time']) ){

				$this->log(
					esc_html__('Multiple tabs/users issue', 'microthemer'),
					'<p>' . esc_html__('MT settings were updated more recently by another user or browser tab. Saving from this outdated tab could cause data loss. Please reload the page instead of saving from this tab (to get the latest changes).', 'microthemer') . '</p>'
				);

				$this->outdatedTabDebug = 'Last save time: '.intval($last_save_time). ", \n" .
				                          'Stored save time: '.intval($this->options['non_section']['last_save_time'])  . ", \n" .
				                          'Difference: ' . (intval($last_save_time) - intval($this->options['non_section']['last_save_time']));

				$this->outdatedTabIssue = 1;

				return false;
			}

			else {

				$this->outdatedTabDebug = 'Last save time: '.$last_save_time. ", \n" .
				                          'Stored save time: '.$this->options['non_section']['last_save_time'] . ", \n" .
				                          'Difference: ' . (intval($last_save_time) - intval($this->options['non_section']['last_save_time']));

				// update last save time
				$this->options['non_section']['last_save_time'] = time();



			}



		}

		return true;
	}

	// update the ui options using & reference to behave like JS object
	function apply_save_package($savePackage, &$data){

		$before_after = array('### Save Package Before and After ###');

		foreach($savePackage as $update){

			if ($update['action'] === 'debug'){
				if ($this->debug_save_package) {
					$before_after[] = $update['data'];
				}
				continue;
			} elseif ($update['action'] === 'no_new_data'){
				continue;
			}

			$before = false;
			if ($this->debug_save_package) {
				$before                 = $this->get_or_update_item($data, array_merge($update, array('action' => 'get')));
				$update[ 'callerFunc' ] = !empty($update[ 'callerFunc' ]) ? $update[ 'callerFunc' ] : '';
			}

			$data_item = &$this->get_or_update_item($data, $update, 0);

			if ($this->debug_save_package) {
				$before_after[] = array(
					'before '.$update['callerFunc'].' (' .$update['action'].')' => $before,
					'after '.$update['callerFunc'].' (' .$update['action'].')' => $data_item,
					'update_package '.$update['callerFunc'].' (' .$update['action'].')' => $update
				);
			}
		}

		if ($this->debug_save_package) {
			$before_after[] = array(
				'Full options:' => $this->options
			);
			$write_file = @fopen($this->debug_dir . 'save-package.txt', 'w');
			fwrite($write_file, print_r($before_after, true));
			fclose($write_file);
		}

	}


	// (optionally) update a multidimensional array item using array trail e.g. ['non_section', 'meta'].
	// Returns a reference to the target item. Note '&' must proceed function call for ref rather than copy.
	function &get_or_update_item(&$data, $config, $startIndex = 0){

		$item = &$data;
		$trail = !empty($config['trail']) ? $config['trail'] : array();
		$trail_length = count($trail);

		// to get round PHP error: Only variable references should be returned by reference
		$false = false;

		for ($x = $startIndex; $x < $trail_length; $x++) {
			$key = $trail[$x];

			// if item doesn't exist
			if (!isset($data[$key])){

				// bail if we're trying to get an item that doesn't exist
				if ($config['action'] === 'get'){

					/* $this->log(
								 esc_html__('Trail lead to undefined item: '.$key, 'microthemer'),
								 '<pre>parent: '  . print_r($data, true) . '</pre>'
								 //'notice'
							 );*/

					return $false;
				}

				// create trail is we're trying to perform an action on a non_existant item
				else {

					$data[$key] = array();

					/*$this->log(
								esc_html__('Previously undefined item added: '.$key, 'microthemer'),
								'<pre>parent: '  . print_r($data, true) . '</pre>'
								//'notice'
							);*/
				}

			}

			$item = &$data[$key];
			$next_index = $x+1;

			//$this->show_me.= '<pre>loop key: '.$key. ' $x: '.$x. ' $trail_length: '.$trail_length. ' $item: '.$item.'</pre>';

			if ($next_index < $trail_length){
				return $this->get_or_update_item($item, $config, $next_index);
			}
		}

		// optionally update item
		switch($config['action']){
			case 'get':
				return $item;
			case 'replace':

				/*$this->log(
							esc_html__('The replace item: ', 'microthemer'),
							'<pre>parent: '  . print_r($item, true) . '</pre>'
						//'notice'
						);*/

				$item = $config['data'];
				break;
			case 'delete':
				unset($item[$config['key']]);
				break;
			case 'rename':
				$this->order_item_properties($item, $config['order'], $config['key'], $config['new_key']);
				break;
			case 'reorder':
				$this->order_item_properties($item, $config['order']);
				break;
			case 'append':
				$item[$config['key']] = $config['data'];
				break;
			case 'array_merge':
				$item = array_merge($item, $config['data']);
				break;
			case 'array_merge_recursive_distinct':

				// tip for myself, this causes 500 error otherwise
				if (!is_array($config['data'])){
					$this->log(
						esc_html__('Merge data is not an array: ', 'microthemer'),
						'<pre>Update package: '  . print_r($config, true) . '</pre>'
					);
					return $false;
				}

				$item = $this->array_merge_recursive_distinct($item, $config['data']);
				break;
			/* unlikely to ned this
					 * array_merge_recursive is a bit weird http://php.net/manual/en/function.array-merge-recursive.php
                     * see explaination of diff with array_merge_recursive_distinct on above PHP page
					 * case 'array_merge_recursive':
						$item = array_merge_recursive($item, $config['data']);
						break;*/
		}

		// return the updated item
		return $item;
	}

	function order_item_properties(&$item, $order, $old_key = false, $new_key = false){
		$new_item = array();
		foreach ($order as $i => $key){

			// don't add undefined keys
			if (isset($item[$key])){
				$new_item[(($key == $old_key) ? $new_key : $key)] = $item[$key];
			} else {
				/* for debugging
						 * $this->log(
							esc_html__('Order key was undefined: '.$key, 'microthemer'),
							'<pre>parent: '  . print_r($item, true) . '</pre>'
						);*/
			}
		}
		$item = $new_item;
	}

	// circumvent max_input_vars by passing one serialised input that can be unpacked with this function
	function my_parse_str($string, &$result) {
		if($string==='') return false;
		$result = array();
		// find the pairs "name=value"
		$pairs = explode('&', $string);
		foreach ($pairs as $pair) {
			// use the original parse_str() on each element
			parse_str($pair, $params);
			$k=key($params);
			if(!isset($result[$k])) {
				$result+=$params;
			}
			else {
				if (is_array($result[$k])){
					//echo '<pre>key:'. $k . "\n";
					//echo 'params:';
					//print_r($params);
					//$result[$k]+=$params[$k];
					$result[$k] = $this->array_merge_recursive_distinct($result[$k], $params[$k]);
					// 'result:';
					//print_r($result);
					//echo '</pre>';
				}
			} //
			//else $result[$k]+=$params[$k];
		}
		return true;
	}

	// better recursive array merge function listed on the function's PHP page
	function array_merge_recursive_distinct ( array &$array1, array &$array2 ){
		$merged = $array1;
		foreach ( $array2 as $key => &$value )
		{
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
				$merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
				$merged [$key] = $value;
			}
		}

		return $merged;
	}

	// run data structure updates
	function maybe_do_data_conversions_for_update(){

		// a few minor data format changes were made for the speed version. This runs once.
		if (empty($this->preferences['speed_conversion_done'])){

			// create backup
			//$this->pre_upgrade_backup(); - this happens on every update

			$non_section = &$this->options['non_section'];
			$keys = array(
				'adv_wizard_focus', 'css_focus', 'device_focus', // just pref now
				'last_save_time' // move to meta
			);

			// remove keys that were hack for non-queued settings save
			foreach ($keys as $key){
				$item = &$this->get_or_update_item($non_section, array('trail' => array($key), 'action' => 'get'));
				//$this->show_me.= '<pre>key '.$key. ' $item: '.$item.'</pre>';
				if ($item){

					// move certain key values to meta
					if ($key === 'last_save_time'){
						$this->get_or_update_item($non_section, array(
							'action' => 'append',
							'trail' => array('meta'),
							'key' => $key,
							'data' => $item
						));
					}

					unset($non_section[$key]);
				}
			}

			// we don't need to track view state outside of regular sel
			if (!empty($non_section['view_state'])){
				unset($non_section['view_state']);
			}

			// we only use active_queries for import/revision restore now
			if (!empty($non_section['active_queries'])){
				unset($non_section['active_queries']);
			}

			// remove recent sug for background_image and list_style_image which will be basename - invalid
			$image_props = array('list_style_image', 'background_image', 'url');
			$types = array('recent', 'copiedSrc');
			foreach ($image_props as $image_prop){
				foreach ($types as $type){
					$this->get_or_update_item(
						$this->preferences['my_props']['sug_values'],
						array(
							'trail' => array($image_prop, $type),
							'action' => 'replace',
							'data' => array()
						)
					);
				}

			}

			// update preferences
			$this->savePreferences(
				array(
					'speed_conversion_done' => true,
					'my_props' =>  $this->preferences['my_props']
				)
			);

			// update DB
			update_option($this->optionsName, $this->options);

			//$this->show_me.= '<pre>modified non_section what '.$this->options['css_focus'].'</pre>';

		}

		// transition to more solid system for connecting MT tabs with page builder device views
		if (empty($this->preferences['builder_site_preview_width_conversion_done'])){

			$m_queries = $this->preferences['m_queries'];

			$map = array(
				//"bbxl" => "builder.FLBuilder.xl",
				"bb0" => "builder.FLBuilder.large",
				"bb1" => "builder.FLBuilder.medium",
				"bb2" => "builder.FLBuilder.small",
				"elem2" => "builder.elementor.tablet",
				"elem3" => "builder.elementor.mobile",
				"oxy_page_width" => "builder.oxygen.page-width",
				"oxy_tablet" => "builder.oxygen.tablet",
				"oxy_phone_landscape" => "builder.oxygen.phone-landscape",
				"oxy_phone_portrait" => "builder.oxygen.phone-portrait",
			);

			// remove keys that were hack for non-queued settings save
			foreach ($m_queries as $mq_key => $m_query){

				foreach ($map as $key_suffix => $site_preview_width){

					if ( preg_match('/'.$key_suffix.'$/', $mq_key) ){
						$m_queries[$mq_key]['site_preview_width'] = $site_preview_width;
					}
				}
			}

			/*wp_die('Old: <pre>$media_queries_list: '.print_r($this->preferences['m_queries'], true). '</pre>'
					. 'New: <pre>$media_queries_list: '.print_r($m_queries, true). '</pre>');*/

			// update preferences
			$this->savePreferences(
				array(
					'builder_site_preview_width_conversion_done' => true,
					'm_queries' => $m_queries
				)
			);

		}

		// there were some errors with recently viewed pages being badly formatted
		// including an Oxygen issue that could cause data loss, so reset custom_paths if not done already
		// custom paths also needed to be reset for the logic feature, so that logic values are included too
		if (empty($this->preferences['custom_paths_reset']) || $this->preferences['custom_paths_reset'] < 3){
			$this->savePreferences(
				array(
					'custom_paths_reset' => 3,
					'custom_paths' =>  array('/')
				)
			);
		}

		// we previously had dock_styles_left which did both styles and editor
		if (empty($this->preferences['dock_left_conversion_done'])){
			$this->savePreferences(
				array(
					'dock_editor_left' => 1,
					'dock_styles_left' => 1,
					'dock_left_conversion_done' =>  1
				)
			);
		}


		// min_widths for resizable panels are stored alongside user custom sizes
		// but this may need to be refined at various intervals
		$layout_adjust_version = 3;// increase this number when
		if (
			empty($this->preferences['layout_adjust_version'])
			|| $this->preferences['layout_adjust_version'] !== $layout_adjust_version
		){

			// set new value - note array_merge had strange affect, so be careful with that
			// simpler to just set new values
			$this->preferences['layout']['inspection_columns']['min_column_sizes'] = array(300, 310);

			// update minimum size for left columns
			$min_left = array(200, 200, 180);
			$this->preferences['layout']['left']['min_column_sizes'] = $min_left;
			foreach ($min_left as $index => $min_size){
				if ($this->preferences['layout']['left']['column_sizes'][$index] < $min_size){
					$this->preferences['layout']['left']['column_sizes'][$index] = $min_size;
				}
			}


			$this->savePreferences(
				array(
					'layout' => $this->preferences['layout'],
					'layout_adjust_version' =>  $layout_adjust_version
				)
			);
		}

		// ensure draft mode is always on, and wizard dock right setting is off
		if (empty($this->preferences['always_draft_conversion_done'])){
			$this->savePreferences(
				array(
					'draft_mode' => 1,
					'dock_wizard_right' => 0, // this is never docked right in 7.0 release (may be supported again)
					'always_draft_conversion_done' =>  1
				)
			);
		}

	}


	// update active-styles.css
	function update_assets($activated_from, $context = '') {

		// as an interim, MT will continue to load dependencies in the global stylesheet
		// e.g. key frames, GFonts, event JS - this will take some time but can be supported
		$globalStylesheetRequired = 0;

		// cache previous asset loading for change analysis
		$prev_asset_loading = $this->preferences['asset_loading'];

		// get path to active-styles.css
		//$act_styles = $this->micro_root_dir.'active-styles.css';

		// check for micro-themes folder and create if it doesn't exist
		$this->setup_micro_themes_dir();

		// bail if stylesheet isn't writable
		/*if (file_exists($act_styles) && !is_writable($act_styles) ) {
			$this->log(
				esc_html__('Write stylesheet error', 'microthemer'),
				'<p>' . esc_html__('WordPress does not have "write" permission for: ', 'microthemer')
				. '<span title="'.$act_styles.'">'. $this->root_rel($act_styles) . '</span>
						. '.$this->permissionshelp.'</p>'
			);
			return false;
		}*/

		// setup vars
		$asset = array(
			'global' => array(
				'data' => '',
				'scss_data' => '',
				'js_data' => ''
			),
			'conditional' => array(),

			// when the settings are saved, conditional folders are checked and only those with
			// styles are given stylesheets. This make this Server side script a good candidate as the
			// central source of truth building the asset_loading logic value (rather than with JS)
			'preference' => array(

				// log when an asset (global or conditional) has been added or removed
				// so that browser tab syncing can add/remove these assets on other tabs
				'global_css' => 0,
				'global_g_fonts' => 0,
				'conditional' => array(),

				// ordered folder logic
				'logic' => array()
			),
		);

		$title = '/*  MICROTHEMER STYLES  */' . "\n\n";

		// check if hand coded have been set - output before other css
		$scss_custom_code = '';
		$custom_code = '';
		if ( !empty($this->options['non_section']['hand_coded_css']) &&
		     !empty(trim($this->options['non_section']['hand_coded_css'])) ){

			$globalStylesheetRequired = 1;

			// format comment
			$name = esc_attr_x('Full Code Editor CSS', 'CSS comment', 'microthemer');
			$eq_str = $this->eq_str($name);
			$custom_code_comment = "/*= $name $eq_str */\n\n";

			// if the scss compiles in the browser
			if ($this->client_scss()){

				// log raw SCSS for writing to active-styles.scss
				$scss_custom_code.= $custom_code_comment . $this->options['non_section']['hand_coded_css'] ."\n";

				// include already compiled CSS
				if (!empty($this->options['non_section']['hand_coded_css_compiled'])){
					$custom_code.= $custom_code_comment . $this->options['non_section']['hand_coded_css_compiled'] ."\n";
				}
			}

			// server-side scss or no scss support
			else {
				$custom_code.= $custom_code_comment . $this->options['non_section']['hand_coded_css'] ."\n";
			}

		}

		// convert ui data to regular css output
		$this->convert_ui_data($this->options, $asset, 'regular');

		// convert ui data to media query css output
		if (!empty($this->options['non_section']['m_query']) and is_array($this->options['non_section']['m_query'])) {
			foreach ($this->preferences['m_queries'] as $key => $m_query) {
				// process media query if it has been in use at all
				if (!empty($this->options['non_section']['m_query'][$key]) and
				    is_array($this->options['non_section']['m_query'][$key])){
					$this->convert_ui_data($this->options['non_section']['m_query'][$key], $asset, 'mq', $key);
				}
			}
		}

		//$this->log('The total config', json_encode($asset['preference']['conditional']));

		// flag that some CSS will be output to the global stylesheet
		if ($asset['global']['data']){
			$globalStylesheetRequired = 1;
		}

		//wp_die('Styles: <pre>' . print_r($asset, 1) . '</pre>');

		// any animations have been found after iterating GUI options, include if necessary
		$anim_keyframes = '';

		if ( !empty($this->options['non_section']['meta']['animations']['names']) and
		     count($this->options['non_section']['meta']['animations']['names']) ){

			$globalStylesheetRequired = 1;

			// flag section with CSS comment
			$name = esc_attr_x('Animations', 'CSS comment', 'microthemer');
			$eq_str = $this->eq_str($name);
			$anim_keyframes.= "/*= $name $eq_str */\n\n";

			// get array of animation code
			$animations = array();
			include $this->thisplugindir . 'includes/animation/animation-code.inc.php';

			foreach ($this->options['non_section']['meta']['animations']['names'] as $animation_name => $one){

				// if we recognise the animation name, include the keyframe code
				if (!empty($animations[$animation_name])){
					$anim_keyframes.= $animations[$animation_name]['code'];
				}

			}
		}

		// join title, animations, custom code and GUI output in correct order
		$asset['global']['data'] = $title . $anim_keyframes . $custom_code . $asset['global']['data'];
		$asset['global']['scss_data'] = $title . $anim_keyframes . $scss_custom_code . $asset['global']['scss_data'];

		/** UPDATE PREFERENCES */

		// flag if global JS file should load
		$js_data = !empty($this->options['non_section']['js'])
			? trim($this->options['non_section']['js'])
			: '';
		$load_js = !empty($js_data);

		// save the google font values
		$g_fonts = $this->get_item(
			$this->options,
			array('non_section', 'meta', 'g_fonts')
		);

		// copy loading of global CSS/JS to asset_loading preference for ease of lookup
		$asset['preference']['global_g_fonts'] = !empty($g_fonts['g_fonts_used']) ? 1: 0;
		$asset['preference']['global_css'] = $globalStylesheetRequired ? 1: 0;
		$asset['preference']['global_js'] = $load_js ? 1: 0;
		$asset['global']['js_data'] = $js_data;
		$this->asset_loading_change = $this->checkAssetLoadingChange($asset['preference'], $prev_asset_loading);

		// core preference values
		$pref_array = array(
			'global_stylesheet_required' => $globalStylesheetRequired,
			'asset_loading' => $asset['preference'],
			'load_js' => $load_js,
			'active_events' => !empty($this->options['non_section']['active_events'])
				? $this->options['non_section']['active_events']
				: array(),
			'num_saves' => (++$this->preferences['num_saves'])
		);

		// google fonts
		$gf_keys = array('g_fonts_used', 'g_url', 'g_url_with_subsets', 'found_gf_subsets');
		foreach($gf_keys as $index => $key){
			$pref_array[$key] = !empty($g_fonts[$key]) ? $g_fonts[$key] : '';
		}

		// track number of unpublished saves
		if (empty($this->preferences['auto_publish_mode'])){
			$pref_array['num_unpublished_saves'] = ++$this->preferences['num_unpublished_saves'];
		}

		// update the recent logic array
		if (!empty($this->serialised_post['adjusted_logic']['update_recent_logic'])){

			// update recent logic data
			if (!empty($this->serialised_post['adjusted_logic']['expr'])){
				$pref_array['recent_logic'] = $this->updateRecentLogic(
					$this->serialised_post['adjusted_logic'],
					$this->preferences['recent_logic']
				);
			}
		}
		
		// set theme in focus (legacy)
		if ($activated_from != 'customised' and $context != __('Merge', 'microthemer')) {
			$pref_array['theme_in_focus'] = $activated_from;
			$pref_array['active_theme'] = $activated_from;
		}

		if ($context == __('Merge', 'microthemer') or $activated_from == 'customised') {
			$pref_array['active_theme'] = 'customised'; // a merge means a new custom configuration
		}

		if ($this->savePreferences($pref_array) and $activated_from != 'customised') {
			$this->log(
				esc_html__('Design pack activated', 'microthemer'),
				'<p>' . esc_html__('The design pack was successfully activated.', 'microthemer') . '</p>',
				'dev-notice'
			);
		}

		$this->updateAssetFiles($asset, $globalStylesheetRequired);

	}

	function updateRecentLogic($adjusted_logic, $prev_recent_logic){

		$existingLabelUpdated = false;
		$adjusted_expr = trim($adjusted_logic['expr']);
		$adjusted_label = trim($adjusted_logic['label']) ?: $adjusted_expr;
		$recent_logic = array();
		$max = 8; // let there be lots

		foreach ($prev_recent_logic as $i => $array){

			//echo 'compare: ' . trim($array['logic']) . ' with: ' . $adjusted_expr . "\n";

			// update existing logic label if expressions match
			if (trim($array['logic']) === $adjusted_expr && !empty($adjusted_logic['label'])){
				$array['label'] = $adjusted_label;
				$array['value'] = $adjusted_label;
				$existingLabelUpdated = true;
			}

			// ensure we don't exceed max and that labels are unique
			if ($i < $max && ($existingLabelUpdated || trim($array['label']) !== $adjusted_label)){
				$recent_logic[] = $array;
			}
		}

		// prepend the new logic if an existing item wasn't updated
		if (!$existingLabelUpdated){
			array_unshift($recent_logic, array(
				'logic' => $adjusted_expr,
				'label' => $adjusted_label,
				'value' => $adjusted_label
			));
		}
		
		return $recent_logic;
	}

	function checkAssetLoadingChange($asset_loading, $prev_asset_loading){

		$change = array();

		// log any change in the Google font loading
		if ($asset_loading['global_g_fonts'] !== $prev_asset_loading['global_g_fonts']){
			$change[] = array(
				'key' => 'global_g_fonts',
				'action' => ($asset_loading['global_g_fonts'] ? 'added' : 'removed'),
			);
		}

		// log any change in the global CSS loading
		if ($asset_loading['global_css'] !== $prev_asset_loading['global_css']){
			$change[] = array(
				'key' => 'global_css',
				'action' => ($asset_loading['global_css'] ? 'added' : 'removed'),
			);
		}

		// log newly added conditional folders
		foreach ($asset_loading['conditional'] as $key => $on){

			if (empty($prev_asset_loading['conditional'][$key])){
				$change[] = array(
					'key' => $key,
					'action' => 'added',
				);
			}

			// they are both on, remove from previous asset loading array so any leftover have been removed
			else {
				unset($prev_asset_loading['conditional'][$key]);
			}
		}

		// log removed conditional folders
		if (count($prev_asset_loading['conditional'])) {
			foreach ( $prev_asset_loading['conditional'] as $key => $on) {
				$change[] = array(
					'key'    => $key,
					'action' => 'removed'
				);
			}
		}

		return count($change) ? $change : false;
	}

	function updateAssetFiles($asset, $globalStylesheetRequired){

		$root = $this->micro_root_dir;
		$conditionalDir = $root . 'mt/conditional/draft/';
		$global_css =  $root . 'draft-styles.css';
		$global_scss =  $root . 'draft-styles.scss';
		$global_js =  $root . 'draft-scripts.js';

		// we only minified published assets, if that preferences is set (on by default)
		/*$minifyCSS = !empty($this->preferences['minify_css']);
		$minifyJS = !empty($this->preferences['minify_js']);*/
		$minifyCSS = false;
		$minifyJS = false;

		// write any global styles
		if ($globalStylesheetRequired){
			$this->write_file($global_css, $asset['global']['data'], $minifyCSS, 'css');
		} elseif (file_exists($global_css)) {
			unlink($global_css);
		}

		/* debug
		 * $this->log('An update', json_encode([
			'$globalStylesheetRequired' => $globalStylesheetRequired,
			'file exists' => file_exists($global_css),
			'file' => $global_css
		]));*/

		// if Sass is enabled, write to global .scss file which holds all Sass (which has a single scope)
		if ($this->preferences['allow_scss']){
			$this->write_file($global_scss, $asset['global']['scss_data']);
		}

		// Write to global JavaScript file, or delete it
		if ($asset['global']['js_data']){
			$this->write_file($global_js, $asset['global']['js_data'], $minifyJS, 'js');
		} elseif (file_exists($global_js)) {
			unlink($global_js);
		}

		// write any conditional styles
		if (count($asset['conditional'])){

			// list of existing files to compare with current files, and possibly cleaned
			$previousFiles = $this->getDirectoryFileList($conditionalDir);
			$currentFiles = array();

			foreach ($asset['conditional'] as $folderSlug => $condSty){

				if (empty($condSty['data'])){
					continue;
				}

				$name = $folderSlug . '.css';
				$file = $conditionalDir . $name;
				$currentFiles[] = $name;

				$this->write_file($file, $condSty['data'], $minifyCSS, 'css');
			}

			// clean up any files that are no longer conditional / renamed
			$redundantFiles = array_diff($previousFiles, $currentFiles);
			foreach ($redundantFiles as $fileName){
				unlink($conditionalDir . $fileName);
			}
		}

		// publish settings if auto-publish is on
		if (!empty($this->preferences['auto_publish_mode'])){
			$this->publishSettings();
		}

	}

	// transform MT form settings into stylesheet data
	function convert_ui_data($ui_data, &$asset, $con, $mq_key = '1') {

		$tab = $sec_breaks = $mq_line = "";
		$sassToo = $this->client_scss();

		if ($con == 'mq') {

			// don't output media query if no values inside
			if (!$this->ui_data_has_values($ui_data, false)){
				return false;
			}

			// reset tracker that opening MQ has been added
			$asset['global']['mq_opened'] = 0;

			$mq_label = $this->preferences['m_queries'][$mq_key]['label'];
			$mq_query = $this->preferences['m_queries'][$mq_key]['query'];
			$tab = "\t";
			$sec_breaks = "";
			$mq_line = "\n/*( $mq_label )*/\n$mq_query {\n";
		}

		// loop through the sections
		foreach ($ui_data as $section_name => $array) {

			// check if the folder is empty but don't skip straight away, we need to assess folder logic
			$emptyFolder = !$this->section_has_values($section_name, $array, false);

			//$this->log('Empty folder ('.$section_name.'): '.$emptyFolder, 'More info');

			//echo 'empty folder ('.$section_name.'): '.$emptyFolder . "\n";

			// skip non_section stuff or empty sections
			if ($section_name == 'non_section'){ // || !$this->section_has_values($section_name, $array, false)
				continue;
			}

			// Get folder name
			$display_section_name = $this->get_folder_name_inc_legacy($section_name, $array);

			// we either update the global styles data or conditional styles
			$sectionLoading = !empty($this->options[$section_name]['this']['logic']['expr'])
				? 'conditional'
				: 'global';

			// update conditional folder stylesheet
			if ($sectionLoading === 'conditional'){

				// ensure data store is set
				if (!isset($asset['conditional'][$section_name])){

					// update the asset_loading preference data
					$asset['preference']['conditional'][$section_name] = $emptyFolder ? 'empty' : 1;
					$asset['preference']['logic'][] = array_merge(
						$this->options[$section_name]['this']['logic'],
						array(
							'slug' => $section_name,
							//'isEmpty' => $emptyFolder
						)
					);

					// Prepare asset data keys if the folder is not empty
					if (!$emptyFolder){
						$asset['conditional'][$section_name] = array(
							'data' => '',
							'scss_data' => '',
							'js_data' => '',
							'mq_opened' => 0
						);
					}
				}

				$dataToUpdate = &$asset['conditional'][$section_name];

				// flag that the folder is conditional
				//$display_section_name.= ' (conditional folder)'; // make this a clickable link in the ACE editor
			}

			// update the global stylesheet
			else {
				$dataToUpdate = &$asset['global'];
			}

			// Now that a potentially conditional folder has been flagged, we can continue if it's empty
			if ($emptyFolder){
				continue;
			}

			// start the opening media query bracket if not created yet
			if ($con == 'mq' && empty($dataToUpdate['mq_opened'])) {

				$this->updateStyleData($sassToo, $asset, $dataToUpdate, $mq_line);

				$dataToUpdate['mq_opened'] = 1;
			}

			// flag if the folder is disabled and
			$sectionIsDisabled = !empty($this->options[$section_name]['this']['disabled']);
			if ($sectionIsDisabled){
				$display_section_name.= ' ('.$this->dis_text.')';
			}

			// make sections same width by adding extra = and accounting for char length
			$eq_str = $this->eq_str($display_section_name);
			$section_comment = $sec_breaks."\n$tab/*= $display_section_name $eq_str */\n\n";

			// Add the section folder name comment regardless of whether styles are omitted
			// not for media queries as that's too many unused folder comments
			if ($con !== 'mq') {
				$this->updateStyleData($sassToo, $asset, $dataToUpdate, $section_comment);
			}

			// if section disabled, continue
			if ($sectionIsDisabled) {
				continue;
			}

			// loop the CSS selectors - section_has_values() already tells us array is good
			foreach ( $array as $css_selector => $sub_array ) {

				// skip this or empty selectors
				if ($css_selector == 'this' or
				    !$this->selector_has_values($section_name, $css_selector, $sub_array, false)) {
					continue;
				}

				// sort out the css selector - need to get css label/code from regular ui array
				if ($con == 'mq') {
					$sub_array['label'] = $this->options[$section_name][$css_selector]['label'];
				}
				$label_array = explode('|', $sub_array['label']);
				$css_label = $label_array[0];
				$sel_disabled = false;
				if (!empty($sub_array['tab']['disabled']) ||
				    !empty($this->options[$section_name][$css_selector]['disabled'])) {
					$sel_disabled = true;
					$css_label.= ' ('.$this->dis_text.')';
				}

				// output sel comment
				$selector_comment = "$tab/** $display_section_name >> $css_label **/\n$tab";
				$this->updateStyleData($sassToo, $asset, $dataToUpdate, $selector_comment);

				// move on if sel disabled
				if ($sel_disabled) {
					continue;
				}

				// add selector data
				$this->updateStyleData(
					$sassToo,
					$asset,
					$dataToUpdate,
					$this->normalise_tabs(
						$this->normalise_line_breaks($sub_array['compiled_css']), $tab
					),
					($sassToo
						? $this->normalise_tabs(
							$this->normalise_line_breaks($sub_array['raw_scss']), $tab, true
						)
						: null
					)

				);

			}
		}

		// Close media query bracket
		if ($con == 'mq') {

			$close_mq = "}\n\n";

			// add for global stylesheet if opened
			if (!empty($asset['global']['mq_opened'])){
				$this->updateStyleData($sassToo, $asset, $asset['global'], $close_mq);
			}

			// add for any conditional stylesheets
			foreach ($asset['conditional'] as &$dataToUpdate){

				if (!empty($dataToUpdate['mq_opened'])){

					$this->updateStyleData($sassToo, $asset, $dataToUpdate, $close_mq);

					// reset for next MQ that calls this method
					$dataToUpdate['mq_opened'] = 0;
				}
			}

		}



		//return $asset;
	}

	// update the css and possible sass data strings
	function updateStyleData($sassToo, &$asset, &$dataToUpdate, $string, $string2 = false){

		$dataToUpdate['data'].= $string;

		// for now, we still add all SCSS to a single global file.
		// This kind of makes sense because the Sass is processed in a single global scope,
		// Even if the output CSS is distributed between separate files
		if ($sassToo){
			$asset['global']['scss_data'].= ($string2 ?: $string);
		}

	}


}