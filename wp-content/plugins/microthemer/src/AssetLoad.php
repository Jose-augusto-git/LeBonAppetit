<?php

namespace Microthemer;

// Stop direct call
if (!defined( 'ABSPATH')) {
	exit;
}

/*
 * AssetLoad
 *
 * Minimal functionality to add CSS, JS (user animation), body classes, Google Fonts, and the viewport meta tag.
 * This file is copied to /wp-content/micro-themes upon activation, to support deactivating Microthemer.
 * It can be manually included as a standalone file using require(ABSPATH . '/wp-content/micro-themes/AssetLoad.php');
 * Alternatively, it can be installed as a simple plugin: mt-inactive.zip
 */

if (!class_exists('\Microthemer\AssetLoad')){

	class AssetLoad {

		protected $context = 'load';
		protected $isFrontend = true;
		protected $isAdminArea = false;

		var $preferences = array();
		var $assetLoadingKey = 'asset_loading_published';
		var $globalStylesheetRequiredKey = "global_stylesheet_required_published";
		var $globalJSRequiredKey = "load_js_published";
		var $defaultActionHookOrder = 999999;
		var $asyncAssets = array(
			'css' => array(),
			'js' => array(),
		);
		var $folderLoading = array();
		var $mtv;
		var $mts;
		var $cacheParam;
		var $rootUrl;
		var $rootDir;
		var $fileStub = 'active';
		var $menuSlugs = array(); // for adding first/last classes to menus
		var $menuItemCount = 0;


		protected $hooks = array(
			'head' => 'wp_head',
			'footer' => 'wp_footer',
			'enqueue_scripts' => 'wp_enqueue_scripts'
		);

		function __construct($standalone = false){

			// bail if this class is being called as a standalone script, but Microthemer is active
			if ($standalone && defined('MT_IS_ACTIVE')){
				return;
			}

			// all good, initiate
			$this->init();
		}

		function init(){

			$p = $this->getPreferences();
			$this->checkAdminVsFront();

			// setup version and path vars
			$this->mtv = 'mtv=' . (!empty($p['version']) ? $p['version'] : 7);
			$this->mts = 'mts=' . (!empty($p['num_saves']) ? $p['num_saves'] : 0);
			$this->cacheParam = $this->getCacheParam();
			$this->getPaths();

			// hook asset loading into WordPress callback actions
			$this->hookCSS($p);

			if ($this->isFrontend){
				$this->hookJS($p);
				$this->hookViewportMeta($p);
				$this->hookClasses($p);
				$this->hookLegacy($p);
			}

		}

		// get the directory path for /wp-content/micro-themes/, accounting for multisite
		function getPaths(){

			$microDir = '/micro-themes/';
			$url = content_url();
			$dir = WP_CONTENT_DIR;

			// Not multisite
			if (!is_multisite()) {
				$this->rootUrl = $url . $microDir;
				$this->rootDir = $dir . $microDir;
				return;
			}

			// It is multisite, resolve the path
			$blog_id = get_current_blog_id();
			$primarySite = $blog_id === 1;
			$sitesPath = file_exists( $dir . "/blogs.dir/")
				? '/blogs.dir'
				: '/uploads/sites';
			$subPath = $primarySite
				? $microDir
				: '/' . $blog_id . $microDir;

			$this->rootUrl = $url . $sitesPath . $subPath;
			$this->rootDir = $dir . $sitesPath . $subPath;
		}

		function checkAdminVsFront(){

			if (is_admin()){
				$this->isFrontend = false;
				$this->isAdminArea = true;
				$this->hooks = array(
					'head' => 'admin_head',
					'footer' => 'admin_footer',
					'enqueue_scripts' => 'admin_enqueue_scripts'
				);
			}
		}

		function supportAdminAssets(){
			$p = $this->preferences;
			return !empty($p['admin_asset_loading']) || !empty($p['admin_asset_editing']);
		}

		function getCacheParam(){
			return $this->mts;
		}

		function getCSSActionHook($p){
			$logic_test = isset($_GET['test_logic']);

			return !empty($p['stylesheet_in_footer']) && !$logic_test
				? $this->hooks['footer']
				// with test_logic, we want logic checked before output buffer HTML in head screws up JSON
				: (!empty($p['stylesheet_order']) && !$logic_test
					? $this->hooks['head']
					: $this->hooks['enqueue_scripts']);
		}

		function getCSSActionOrder($p){
			return !empty($p['stylesheet_order']) && !isset($_GET['test_logic'])
				? intval($p['stylesheet_order'])
				: $this->defaultActionHookOrder;
		}

		function hookCSS($p){

			// do not load assets when a builder has replaced the frontend with a UI
			if ($this->isBricksUi()){
				return;
			}

			// determine which action to hook into
			$action_hook = $this->getCSSActionHook($p);

			// determine the action execution order
			$action_order = $this->getCSSActionOrder($p);

			// add CSS
			add_action($action_hook, array(&$this, 'addCSS'), $action_order);

			// Also add CSS to the login page unless specified otherwise
			if ($this->isFrontend && !empty($p['global_styles_on_login'])){
				add_action('login_enqueue_scripts', array(&$this, 'addCSS'), $action_order);
			}

			// allow style tag atts to be updated if they are async
			add_filter( 'style_loader_tag', array(&$this, 'asyncStyleTag'), 10, 4 );

		}

		function addInsteadOfEnqueue(){
			return !empty($this->preferences['stylesheet_order'])
			       || !empty($this->preferences['stylesheet_in_footer']);
		}

		function addGlobalGoogleFonts($p, $add){

			if (!empty($p['g_fonts_used'])){

				$google_url = !empty($p['g_url_with_subsets'])
					? $p['g_url_with_subsets']
					: (!empty($p['gfont_subset'])
						? $p['g_url'].$p['gfont_subset']
						: $p['g_url']);

				$this->enqueueOrAdd($add, 'microthemer_g_font', $google_url);
			}
		}

		function addGlobalStylesheet($p, $add){

			$path = $this->fileStub . '-styles.css';

			if (
				file_exists($this->rootDir . $path)
				&& !empty($p[$this->globalStylesheetRequiredKey]) || !isset($p[$this->globalStylesheetRequiredKey])
			){

				$this->enqueueOrAdd(
					$add,
					'microthemer',
					$this->rootUrl . $path . '?' . $this->cacheParam
				);
			}
		}

		function addCSS(){

			$p = $this->preferences;
			$asset_loading = !empty($p[$this->assetLoadingKey])
				? $p[$this->assetLoadingKey]
				: array();

			// if stylesheet order is set, we add to $wp_styles object rather than enqueuing
			$add = $this->addInsteadOfEnqueue();

			// if Auth, we add a placeholder to update conditional styles on synced other tabs
			$this->addMTPlaceholder();

			// Global CSS is just for the frontend
			if ($this->isFrontend){

				// enqueue any Google Fonts
				$this->addGlobalGoogleFonts($p, $add);

				// load the Microthemer stylesheet if new preference hasn't been set, or it has been set to true
				$this->addGlobalStylesheet($p, $add);
			}

			// Load conditional assets
			if (isset($asset_loading['logic']) && (count($asset_loading['logic']) || isset($_GET['test_logic']) )){
				$this->conditionalAssets($asset_loading['logic']);
			}

			// insert MT interface CSS here if AssetAuth child class is running
			$this->addMTCSS();
		}

		function supportLogicTest(){
			return false;
		}

		function doLogicTest($folders, $logic){}

		function loadConditionalAssets($folders, $logic){

			// bail if the user has not enabled admin assets
			if ($this->isAdminArea && !$this->supportAdminAssets()){
				return;
			}

			$subDir = 'mt/conditional/' . $this->fileStub . '/';
			$dir = $this->rootDir . $subDir;
			$url = $this->rootUrl . $subDir;
			$add = $this->addInsteadOfEnqueue();

			foreach ($folders as $folder){

				if (isset($folder['expr'])){

					$slug = $folder['slug'];
					$this->folderLoading[$slug] = 0;
					$result = $logic->result(trim($folder['expr']));
					$cssFileName = $slug . '.css';
					$cssFile = $dir . $slug . '.css';
					$fileExists = file_exists($dir . $cssFileName);

					$this->folderLoading[$slug] = $result
						? ($fileExists ? 1 : 'empty')
						: 0;

					// load content if true and the file exists
					if ($result && $fileExists){

						$inline = empty($folder['css_external']);
						$async = !empty($folder['css_async']);

						// load the CSS file
						$this->enqueueOrAdd(
							($add || $inline || $async),
							'microthemer-'.$slug, //.'-css',
							$url . $cssFileName . '?' . $this->cacheParam,
							array(
								'inline' => $inline,
								'async' => $async,
								'code' => $inline ? file_get_contents($cssFile) : false
							)
						);
					}
				}
			}

		}

		function asyncStyleTag($html, $handle, $href, $media){

			$noscript = $this->context === 'load' ? '<noscript>' . $html . '</noscript>' : '';

			if (!empty($this->asyncAssets['css'][$handle])){

				// The preload method - doesn't support IE11 & preload will prioritise resource
				/*$html = '<link rel="preload" href="' . $href . '" as="style" id="' . $handle . '-css"
						media="' . $media . '" onload="this.onload=null;this.rel=\'stylesheet\'">'
				       . $noscript;*/

				// The media print method - works in all browsers and doesn't change resource priority
				$html = '<link rel="stylesheet" href="'.$href.'" id="'.$handle.'-css" 
						media="print" onload="this.onload=null; this.media=\''.$media.'\';">'
				        . $noscript;
			}

			return $html;
		}

		function conditionalAssets($folders){

			if (!class_exists('Microthemer\Logic')){
				require_once dirname(__FILE__) . '/Logic.php';
			}

			$logic = new Logic();

			if ($this->supportLogicTest()){
				$this->doLogicTest($folders, $logic);
			}

			else {
				$this->loadConditionalAssets($folders, $logic);
			}

		}

		function hookViewportMeta($p){
			if (!empty($p['initial_scale'])) {
				add_action($this->hooks['head'], array(&$this, 'addViewportMeta'));
			}
		}

		function hookClasses($p){
			add_filter('body_class', array(&$this, 'addBodyClasses'));
		}

		// legacy functionality that is not enabled by default
		function hookLegacy($p){

			// insert dynamic classes to menus if preferred
			if (!function_exists('add_first_and_last')) {
				if (!empty($p['first_and_last'])) {
					add_filter('nav_menu_css_class', array(&$this, 'addMenuOrdinalClasses'), 10, 3);
				}
			}
		}

		// Add first and last classes to WordPress menus
		function addMenuOrdinalClasses($classes, $item, $args){

			// store menu item count if not done already
			if (empty($this->menuSlugs[$args->menu->slug])){
				$this->menuSlugs[$args->menu->slug] = $args->menu->count;
				$this->menuItemCount = 0;
			}

			// add first or last item
			if ( $this->menuItemCount === 0 ) {
				$classes[] = 'menu-item-first';
			} else if ($this->menuItemCount === $this->menuSlugs[$args->menu->slug]-1) {
				$classes[] = 'menu-item-last';
			}

			$this->menuItemCount++;
			
			return $classes;

		}

		function addViewportMeta($p){
			echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
		}

		// Microthemer updates a separate database entry with a handful of preferences that are needed on the frontend
		// and these preferences autoload, saving an extra DB request
		function getPreferences(){

			// fallback to the full preferences if frontend preferences haven't been set somehow
			// (or an empty array but that's just to address PHP 8.2 type warnings)
			$this->preferences = ( get_option('microthemer_autoload_preferences')
					?: get_option('preferences_themer_loader') )
						?: array();

			return $this->preferences;

		}

		function hookJS($p){

			add_action($this->hooks['enqueue_scripts'], array(&$this, 'addJS'), $this->defaultActionHookOrder);

			// allow login page to be editable unless disabled
			if ($this->isFrontend && !empty($p['global_styles_on_login'])){
				add_action('login_enqueue_scripts', array(&$this, 'addJS'), $this->defaultActionHookOrder);
			}

		}


		function addMTPlaceholder(){}
		function addMTCSS(){}
		function addMTJS(){}

		function addJS() {

			$p = $this->preferences;

			$deps = !empty($p['active_scripts_deps'])
				? preg_split("/[\s,]+/", $p['active_scripts_deps'])
				: array();

			// enqueue any script libraries
			if (!empty($p['enq_js']) and is_array($p['enq_js'])){
				foreach ($p['enq_js'] as $k => $arr){
					if (empty($arr['disabled'])){
						wp_enqueue_script($arr['display_name']);
						$deps[] = $arr['display_name'];
					}
				}
			}

			// insert MT JavaScript here if AssetAuth child class sf running
			// This needs to come before the user's JavaScript so that we can catch/log their JS errors
			$this->addMTJS();

			// enqueue any user JavaScript
			if (!empty($p[$this->globalJSRequiredKey]) ||
			    (!isset($p[$this->globalJSRequiredKey]) && !empty($p['load_js'])) // legacy config
			) {
				$h = 'mt_user_js';
				wp_register_script($h, $this->rootUrl . $this->fileStub . '-scripts.js?'.$this->cacheParam);
				wp_enqueue_script($h, false, $deps, null, !empty($p['active_scripts_footer']));
			}

			// enqueue JS animations if used
			if (!empty($p['active_events'])) {
				$h = 'mt_animation_events';
				wp_register_script($h, $this->rootUrl.'animation-events.js?'.$this->mtv, array('jquery'));
				wp_enqueue_script($h);
				wp_localize_script( $h, 'MT_Events_Data', json_decode($p['active_events'], true) );
			}
		}

		function enqueueOrAdd($add, $handle, $url, $config = array()){

			global $wp_styles;

			// add to $wp_styles
			if ($add){

				// add content as inline style
				if (!empty($config['inline'])){
					$wp_styles->add($handle, false);
					$wp_styles->enqueue(array($handle));
					$wp_styles->add_inline_style($handle, $config['code']);
				}

				// regular external stylesheet
				else {

					$wp_styles->add($handle, $url);

					$wp_styles->enqueue(array($handle));

					if (!empty($config['data_key'])){
						$wp_styles->add_data($handle, $config['data_key'], $config['data_val']);
					}

					if (!empty($config['async'])){
						$wp_styles->add_data($handle, 'async', '1');
						$this->asyncAssets['css'][$handle] = 1;
					}
				}

				// do_item not do_items, otherwise it renders other stylesheets in the queue before they might be ready
				$wp_styles->do_item($handle);
				$wp_styles->done[] = $handle;
			}

			// or enqueue normally
			else {
				wp_register_style($handle, $url, false);
				wp_enqueue_style($handle);
			}
		}

		function addBodyClasses($classes){
			
			global $post;

			$p = $this->preferences;
			
			if (isset($post)) {

				$pfx = !empty($p['page_class_prefix']) ? $p['page_class_prefix'] : 'mt';
				$classes[] = $pfx.'-'.$post->ID;
				$classes[] = $pfx.'-'.$post->post_type.'-'.$post->post_name;

				if (!empty($p['insert_custom_field_classes'])){

					$custom_classes = get_post_meta($post->ID, 'my_body_classes', true);

					if ($custom_classes){
						$classes = array_merge($classes, preg_split("/\s+/", trim($custom_classes)));
					}
				}
			}

			return $classes;
		}

		/* Integrations */

		function isBricksUi(){
			return !isset($_GET['brickspreview']) && isset($_GET['bricks']) && $_GET['bricks'] === 'run';
		}

	}



}