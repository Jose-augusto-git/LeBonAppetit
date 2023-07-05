<?php

namespace Microthemer;

class Common {

	public static function get_protocol() {
		$isSSL = is_ssl(); // (!empty($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on");
		return 'http' . ($isSSL ? 's' : '') . '://';
	}

	public static function get_custom_code() {
		return array(
			'hand_coded_css' => array (
				'tab-key' => 'all-browsers',
				'label' => esc_html__('CSS', 'microthemer'),
				//'label' => esc_html__('CSS', 'microthemer'),
				'type' => 'css'
			),
			'js' => array (
				'tab-key' => 'js',
				'label' => esc_html__('JS', 'microthemer'),
				'type' => 'javascript'
			),
		);
	}

	// add a param to an existing url if it doesn't exist, using the correct joining char
	public static function append_url_param($url, $param, $val = false){

		// bail if already present
		if (strpos($url, $param) !== false){
			return $url;
		}

		// we do need to add param, so determine joiner
		$joiner = strpos($url, '?') !== false ? '&': '?';

		// is there param val?
		$param = $val ? $param.'='.$val : $param;

		// return new url
		return $url . $joiner . $param;

	}

	// strip a single parameter from an url (adapted from JS function)
	public static function strip_url_param($url, $param, $withVal = true){

		$param = $withVal ? $param . '(?:=[a-z0-9]+)?' : $param;
		$pattern = '/(?:&|\?)' . $param . '/';
		$url = preg_replace($pattern, '', $url);

		// check we don't have an any params that start with & instead of ?
		if (strpos($url, '&') !== false && strpos($url, '?') === false){
			preg_replace('/&/', '?', $url, 1); // just replaces the first instance of & with ?
		}

		return $url;
	}

	// &preview= and ?preview= cause problems - strip everything after (more heavy handed than above function)
	public static function strip_preview_params($url){
		//$url = explode('preview=', $url); // which didn't support regex (for e.g. elementor)
		$url = preg_split('/(?:elementor-)?preview=/', $url, -1);
		$url = rtrim($url[0], '?&');
		return $url;
	}

	public static function params_to_strip(){
		return array(

			// wordpress params
			array(
				'param' => '_wpnonce',
				'withVal' => true,
			),
			array(
				'param' => 'ver',
				'withVal' => true,
			),

			array(
				'param' => 'mt_nonlog',
				'withVal' => false,
			),
			array(
				'param' => 'mto2_edit_link',
				'withVal' => true,
			),
			array(
				'param' => 'elementor-preview',
				'withVal' => true,
			),
			array(
				'param' => 'brizy-edit-iframe', // strip brizy
				'withVal' => false,
			),
			array(
				'param' => 'et_fb', // strip Divi param which causes iframe to break out of parent
				'withVal' => true,
			),
			array(
				'param' => 'fl_builder', // strip beaver builder
				'withVal' => false,
			),
			// oxygen params
			array(
				'param' => 'ct_builder',
				'withVal' => true,
				'unless' => array('ct_template') // ct_template also requires ct_builder to work
			),
			array(
				'param' => 'ct_inner',
				'withVal' => true,
			),
			/* Keep as necessary for showing specific content
			 * array(
				'param' => 'ct_template',
				'withVal' => true,
			),*/
			array(
				'param' => 'oxygen_iframe',
				'withVal' => true,
			),

			// elementor doesn't pass a parameter to the frontend it runs on the admin side

		);
	}

	// we don't strip params that are required when another param is present
	public static function has_excluded_param($url, $array){

		$unless = !empty($array['unless']) ? $array['unless'] : false;
		if ($unless){
			foreach ($unless as $i => $excl){
				if (strpos($url, $excl) !== false){
					return true;
				}
			}
		}

		return false;
	}

	// strip preview= and page builder parameters
	public static function strip_page_builder_and_other_params($url, $strip_preview = true){

		// strip preview params (regular and elementor)
		//$url = Common::strip_preview_params($url); // test what happens

		$other_params = Common::params_to_strip();

		foreach ($other_params as $key => $array){

			// we don't strip params that are required when another param is present
			if (Common::has_excluded_param($url, $array)){
				continue;
			}

			$url = Common::strip_url_param($url, $array['param'], $array['withVal']);
		}

		// strip brizy
		/*$url = Common::strip_url_param($url, 'brizy-edit-iframe', false);

		// strip Divi param which causes iframe to break out of parent
		$url = Common::strip_url_param($url, 'et_fb', true); // this has issue with divi builder

		// strip beaver builder - NO, we're currently checking fl_builder for JS logic.
		$url = Common::strip_url_param($url, 'fl_builder', false);*/

		return $url;

	}

	// we are adding user google fonts on admin side too so they can be shown in UI (todo)
	public static function add_user_google_fonts($p){

		// use g_url_with_subsets value generated when writing stylesheet
		$google_url = !empty($p['g_url_with_subsets'])
			? $p['g_url_with_subsets']

			// fallback to g_url if user has yet to save settings since g_url_with_subsets was added
			: (!empty($p['gfont_subset']) ? $p['g_url'].$p['gfont_subset'] : $p['g_url']);

		if (!empty($google_url)){
			Common::mt_enqueue_or_add(!empty($p['stylesheet_order']), 'microthemer_g_font', $google_url);
		}

	}

	public static function mt_enqueue_or_add($add, $handle, $url, $in_footer = false, $data_key = false, $data_val = false){

		global $wp_styles;

		// special case for loading CSS after Oxygen
		if ($add){

			$wp_styles->add($handle, $url);
			$wp_styles->enqueue(array($handle));

			if ($data_key){
				$wp_styles->add_data($handle, $data_key, $data_val);
			}

			// allow CSS to load in footer if O2 is active so MT comes after O2 even when O2 active without O2
			// Note this didn't work on my local install, but did on a customer who reported issue with Agency Tools
			// so better to use a more deliberate action hook e.g. wp_footer
			// Ideally, O2 would enqueue a placeholder stylesheet and replace rather than append to head
			/*if ( !defined( 'SHOW_CT_BUILDER' ) ) {
				$wp_styles->do_items($handle);
			}*/

			// (feels a bit risky, but can add if MT loading before O2 when active by itself causes issue for people)
			$wp_styles->do_items($handle);
		}

		else {
			wp_register_style($handle, $url, false);
			wp_enqueue_style($handle);
		}

	}

	// dequeue rougue styles or scripts loading on MT UI page that cause issues for it
	public static function dequeue_rogue_assets(){

		$conflict_styles = array(

			// admin 2020 plugin assets
			'uikitcss',
			'ma_admin_head_css',
			'ma_admin_editor_css',
			'ma_admin_menu_css',
			'ma_admin_mobile_css',
			'custom_wp_admin_css',
			'ma_admin_media_css',

			// for UIPress
			'uip-app',
			'uip-app-rtl',
			'uip-icons',
			'uip-font',

			// TK shortcodes
			'tk-shortcodes',
			'tk-shortcodes-fa'


		);

		// for UIPress
		/*if ( class_exists('uipress') ){
			$conflict_styles = array_merge($conflict_styles, array(

			));
		}*/

		foreach ($conflict_styles as $style_handle){
			wp_dequeue_style($style_handle);
		}
	}

}
