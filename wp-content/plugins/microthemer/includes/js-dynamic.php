<?php
/* This PHP file will be enqueued (indirectly) as if it were a regular javascript file.
It contains JS arrays/objects that change as the user updates their settings in Microthemer.
These include: media queries, default CSS units, suggested values, design packs...
*/

$data = '';

// Only run performance functions if dev_mode is enabled
$dev_mode = TVR_DEV_MODE ? 'true' : 'false';
$data.= 'TVR_DEV_MODE = ' . $dev_mode . ';' . "\n\n";

// program data needs to be added dynamically if non-english and server doesn't allow writing JS files
// upon activation
if ( !empty($this->preferences['inlineJsProgData']) ){
	$data.= $this->write_mt_version_specific_js(false, true);
}

// add the design pack directories to the TvrMT.data.prog.combo object already defined in the static version JS file
$directories = array();
foreach ($this->file_structure as $dir => $array) {
	$exclude_dirs = array('sass', 'scss');
	if (!empty($dir) && !in_array($dir, $exclude_dirs) && !$this->has_extension($dir)) {
		$directories[] = $this->readable_name($dir);
	}
}
$data.= 'TvrMT.data.prog.combo.directories = ' . json_encode($directories) . ';' . "\n\n";

// the last 20 custom site preview URLs the user enters are saved in DB
$data.= 'TvrMT.data.prog.combo.custom_paths = ' . json_encode($this->preferences['custom_paths']) . ';' . "\n\n";

// the last 30 page logic items
$data.= 'TvrMT.data.prog.combo.recent_logic = ' . json_encode($this->preferences['recent_logic']) . ';' . "\n\n";

// path to the icon font
$data.= 'TvrMT.data.dyn.icon_font_face_style = ' . json_encode( array('css' => $this->load_icon_font(true)) ) . ';' ."\n\n";
//'icon_font_face_style' => $this->load_icon_font(true),

// ready combo for MQ and CSS unit sets (dynamic so they can be translated and added to with integrations)
foreach ($this->mq_sets as $set => $junk){
	$mq_sets[] = $set;
}
$data.= 'TvrMT.data.prog.combo.mq_sets = ' . json_encode($mq_sets) . ';' . "\n\n";

// import css files for quick navigation
$data.= 'TvrMT.data.prog.combo.viewed_import_stylesheets = ' . json_encode($this->preferences['viewed_import_stylesheets']) . ';' . "\n\n";

// user's current media queries (combined with All Devices)
$data.= 'var TvrMQsCombined = ' . json_encode($this->combined_devices()) . ';' . "\n\n";

// available builder integrations
$data.= 'TvrMT.data.dyn.integrations = ' . json_encode($this->integrations) . ';' . "\n\n";

// the full ui options in JS form. Later, this will be used for speed optimisations
$data.= 'TvrMT.data.dyn.builder_breakpoints = ' . json_encode(
	array(
		'elementor' => $this->elementor_breakpoints,
		'oxygen' => $this->oxygen_breakpoints
	)
) . ';' . "\n\n";

// full preferences (can replace the above)
$data.= 'TvrMT.data.dyn.pref = ' . json_encode($this->preferences) . ';' . "\n\n";

// just sels (going for the full ui)
//$data.= 'window.TvrMT.sels = ' . json_encode($this->sel_lookup) . ';' . "\n\n";

// the full ui options in JS form. Later, this will be used for speed optimisations
$data.= 'TvrMT.data.dyn.ui = ' . json_encode($this->options) . ';' . "\n\n";

global $is_IE;

// the full ui options in JS form. Later, this will be used for speed optimisations
$data.= 'TvrMT.data.dyn.is_IE = ' . ($is_IE ? 1: 0) . ';' . "\n\n";

// if multi-site, store blog details (use path to correct multi-site relative path)
if (function_exists('get_blog_details')){
	$data.= 'TvrMT.data.dyn.blog_details = ' . json_encode(get_blog_details( $this->multisite_blog_id )) . ';' . "\n\n";
}

// client side SASS stuff
if ($this->client_scss()){

	// store file structure of micro-themes folder so client-side SCSS paths can be cross-checked
	// may also be helpful for page-logic feature
	$data.= 'TvrMT.data.dyn.micro_files = ' . json_encode($this->file_structure) . ';' . "\n\n";

	// replacement content for @import statements
	$data.= 'TvrMT.data.dyn.preloaded_sass_imports = ' . json_encode($this->get_sass_import_content()) . ';' . "\n\n";

	// log that client-side scss is in use
	$data.= 'TvrMT.data.dyn.client_scss = "1";' . "\n\n";
}

// the micro-themes dir and url paths
$data.= 'TvrMT.data.dyn.micro_root_url = "' . $this->micro_root_url . '";' . "\n\n";

// the default site pages list (posts and pages limited to 30, more results collected on search)
$data.= 'TvrMT.data.dyn.site_pages = ' . json_encode($this->get_site_pages()) . ';' . "\n\n";

// the default site pages list (posts and pages limited to 30, more results collected on search)
//$data.= 'TvrMT.data.dyn.site_pages = ' . json_encode($this->get_site_pages()) . ';' . "\n\n";

// URLs for placehold (light, dark, current)
//$data.= 'TvrMT.data.dyn.placeholderURLs = ' . json_encode($this->get_placeholder_urls()) . ';' . "\n\n";

// dynamic menus: enq_js, mqs, custom code, animation, preset

$data.= 'TvrMT.data.dyn.ui_config = ' . json_encode(array(
	'mt_nonlog_nonce' => wp_create_nonce('mt_nonlog_check'), // note this won't work with browser sync enabled
	'mt_builder_redirect_nonce' => wp_create_nonce('mt_builder_redirect_check'), // note this won't work with browser sync enabled
	'preview_item_id' => $this->get_preview_item_id(),
	'reporting' => $this->reporting,
	'errorsRequiringData' => $this->errorsRequiringData,
)) . ';' . "\n\n";




// output JS
echo $data;
