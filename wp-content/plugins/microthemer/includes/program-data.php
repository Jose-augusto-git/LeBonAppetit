<?php

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

// central store of language strings - in future, have this is sep file and store escaped translates files in DB table rows
$this->lang = array(
	'css_unit_types' => array(
		'common' => esc_html__('Common', 'microthemer'),
		'other' => esc_html__('Other', 'microthemer'),
		'time' => esc_html__('Time', 'microthemer'),
		'grid' => esc_html__('Grid', 'microthemer'),
		'none' => esc_html__('No unit', 'microthemer'),
		'angle' => esc_html__('Angle', 'microthemer'),
	),
);

// save time for use with ensuring non-cached files
$this->time = time();

$this->permissionshelp = esc_html__('Please see this help article for changing directory and file permissions:', 'microthemer') . ' <a href="http://codex.wordpress.org/Changing_File_Permissions">http://codex.wordpress.org/Changing_File_Permissions</a>.' . esc_html__('Tip: you may want to jump to the "Using an FTP Client" section of the article. But bear in mind that if your web hosting runs windows it may not be possible to adjust permissions using an FTP program. You may need to log into your hosting control panel, or request that your host adjust the permissions for you.', 'microthemer');

// moved to constructor because __() can't be used on member declarations
$this->edge_mode = array(
	'available' => false,
	'edge_forum_url' => 'https://themeover.com/forum/topic/were-trialling-automatic-error-reporting/',
	'cta' => __("Enable automatic error reporting so we can make Microthemer error free!", 'microthemer'),
	'config' => array(
		'error_reporting_trial' => 1
	),
	'active' => false // evaluated and set at top of ui page (don't change this)
);

// define folders (translatable)
$this->set_default_folders();

// custom code
$this->custom_code = Microthemer\Common::get_custom_code();

// params to strip
$this->params_to_strip = Microthemer\Common::params_to_strip();

// flatten custom_code array (easier to work with)
/*foreach ($this->custom_code as $key => $arr){
	if ($key == 'ie_css'){
		foreach ($arr as $key => $arr){
			$flat[$key] = $arr;
		}
	} else {
		$flat[$key] = $arr;
	}
}*/

$this->custom_code_flat = $this->custom_code; // $flat;

// define possible CSS units
$unit_types = $this->lang['css_unit_types'];
$this->css_units = array();

$this->css_units[$unit_types['none']] = array(
	'none' => array(
		'type' => 'none',
		'value' => '',
		'desc' => esc_attr__('No unit', 'microthemer'),
	),
);

$this->css_units[$unit_types['grid']] = array(
	'fr' => array(
		'type' => 'fraction',
		'desc' => esc_attr__('A fraction of the available space', 'microthemer'),
	),
);

$this->css_units[$unit_types['common']] = array(
	'px'=> array(
		'type' => 'common',
		'desc' => esc_attr__('1px = 1/96th of 1in', 'microthemer')
	),
	'em'=> array(
		'type' => 'common',
		'desc' => esc_attr__('1em = the element\'s font-size', 'microthemer')
	),
	'rem'=> array(
		'type' => 'common',
		'desc' => esc_attr__('1rem = the html element\'s font-size', 'microthemer')
	),
	'%'=> array(
		'type' => 'common',
		'desc' => esc_attr__('percentage of parent element\'s size', 'microthemer')
	),

);

$this->css_units[$unit_types['other']] = array(

	'ch'=> array(
		'type' => 'other',
		'desc' => esc_attr__('width of a "0" (approx 0.5em)', 'microthemer')
	),
	'cm'=> array(
		'type' => 'other',
		'desc' => esc_attr__('centimeters', 'microthemer')
	),
	'ex'=> array(
		'type' => 'other',
		'desc' => esc_attr__('height of a lowercase "x" (approx 0.5em)', 'microthemer')
	),
	'in'=> array(
		'type' => 'other',
		'desc' => esc_attr__('inches', 'microthemer')
	),
	'mm'=> array(
		'type' => 'other',
		'desc' => esc_attr__('millimeters', 'microthemer')
	),
	'pt'=> array(
		'type' => 'other',
		'desc' => esc_attr__('point: 1pt = 1/72nd of 1in', 'microthemer')
	),
	'pc'=> array(
		'type' => 'other',
		'desc' => esc_attr__('pica: 1pc = 12pt', 'microthemer')
	),
	'vw' => array(
		'type' => 'other',
		'desc' => esc_attr__('&#37; of viewport width', 'microthemer'),
		'not_supported_in' => array('IE8')
	),
	'vh' => array(
		'type' => 'other',
		'desc' => esc_attr__('&#37; viewport height', 'microthemer'),
		'not_supported_in' => array('IE8')
	),
	'vmin' => array(
		'type' => 'other',
		'desc' => esc_attr__('&#37; of viewport\'s smaller dimension', 'microthemer'),
		'not_supported_in' => array('IE8')
		// NOTE: in IE9 it's called 'vm'
	),
	'vmax' => array(
		'type' => 'other',
		'desc' => esc_attr__('&#37; of viewport\'s larger dimension', 'microthemer'),
		'not_supported_in' => array('IE8', 'IE9', 'IE10', 'IE11')
	),



);

$this->css_units[$unit_types['time']] = array(
	's' => array(
		'type' => 'time',
		'desc' => esc_attr__('seconds', 'microthemer'),
	),
	'ms' => array(
		'type' => 'time',
		'desc' => esc_attr__('milliseconds', 'microthemer'),
	),
);

$this->css_units[$unit_types['angle']] = array(
	'deg' => array(
		'type' => 'angle',
		'desc' => esc_attr__('degree: circle = 360deg', 'microthemer'),
	),
	'grad' => array(
		'type' => 'angle',
		'desc' => esc_attr__('gradian: circle = 400grad', 'microthemer'),
	),
	'rad' => array(
		'type' => 'angle',
		'desc' => esc_attr__('radian: circle = 6.2832rad (approx)', 'microthemer'),
	),
	'turn' => array(
		'type' => 'angle',
		'desc' => esc_attr__('circle = 1turn, half circle = .5turn', 'microthemer'),
	),
);

$this->folder_item_types = array(
	'selector' => array(
		'label' => esc_html__('Selector', 'microthemer'),
		'label_plural' => esc_html__('Selectors', 'microthemer')
	),
	// for adding custom CSS or Sass (inc mixins / functions which don't have dedicated type)
	'snippet' => array(
		'label' => esc_html__('Code snippet', 'microthemer'),
		'label_plural' => esc_html__('Code snippets', 'microthemer')
	),
	// types to support later - for UI integration (Code | Fields tabs)
	/*'css_variables' => array(
		'label' => esc_html__('CSS variable', 'microthemer'),
		'label_plural' => esc_html__('CSS variables', 'microthemer')
	),
	'sass_variables' => array(
		'label' => esc_html__('Sass variable', 'microthemer'),
		'label_plural' => esc_html__('Sass variables', 'microthemer')
	),
	'keyframes' => array(
		'label' => esc_html__('Keyframe', 'microthemer'),
		'label_plural' => esc_html__('Keyframes', 'microthemer')
	),*/
);



// to_autocomplete_arr

// easy preview of common devices
$this->mob_preview = array(
	array('(P) Apple iPhone 4', 320, 480),
	array('(P) Apple iPhone 5', 320, 568),
	array('(P) BlackBerry Z30', 360, 640),
	array('(P) Google Nexus 5', 360, 640),
	array('(P) Nokia N9', 360, 640),
	array('(P) Samsung Gallaxy (All)', 360, 640),
	array('(P) Apple iPhone 6', 375, 667),
	array('(P) Google Nexus 4', 384, 640),
	array('(P) LG Optimus L70', 384, 640),
	array('(P) Apple iPhone 6 Plus', 414, 736),
	// landscape (with some exceptions)
	array('(L) Apple iPhone 4', 480, 320),
	array('(L) Nokia Lumia 520', 533, 320),
	array('(L) Apple iPhone 5', 568, 320),
	array('(P) Google Nexus 7', 600, 960),
	array('(P) BlackBerry PlayBook', 600, 1024),
	array('(L) BlackBerry Z30', 640, 360),
	array('(L) Google Nexus 5', 640, 360),
	array('(L) Nokia N9', 640, 360),
	array('(L) Samsung Gallaxy (All)', 640, 360),
	array('(L) Google Nexus 4', 640, 384),
	array('(L) LG Optimus L70', 640, 384),
	array('(L) Apple iPhone 6/7', 667, 375),
	array('(L) Apple iPhone 6+/7+', 736, 414),
	array('(P) Apple iPad', 768, 1024),
	array('(P) Google Nexus 10', 800, 1280),
	array('(L) Google Nexus 7', 960, 600),
	array('(L) BlackBerry PlayBook', 1024, 600),
	array('(L) Apple iPad', 1024, 768),
	array('(P) Apple iPad Pro', 1024, 1366),
	array('(L) Google Nexus 10', 1280, 800),
	array('(L) Apple iPad Pro', 1366, 1024),
);

// country codes
$this->country_codes = array(
	"ab", "aa", "af", "ak", "sq", "am", "ar", "an", "hy", "as", "av", "ae", "ay", "az", "bm", "ba", "eu", "be", "bn", "bh", "bi", "bs", "br", "bg", "my", "ca", "ch", "ce", "ny", "zh", "zh-Hans", "zh-Hant", "cv", "kw", "co", "cr", "hr", "cs", "da", "dv", "nl", "dz", "en", "eo", "et", "ee", "fo", "fj", "fi", "fr", "ff", "gl", "gd", "gv", "ka", "de", "el", "kl", "gn", "gu", "ht", "ha", "he", "hz", "hi", "ho", "hu", "is", "io", "ig", "id, in", "ia", "ie", "iu", "ik", "ga", "it", "ja", "jv", "kl", "kn", "kr", "ks", "kk", "km", "ki", "rw", "rn", "ky", "kv", "kg", "ko", "ku", "kj", "lo", "la", "lv", "li", "ln", "lt", "lu", "lg", "lb", "gv", "mk", "mg", "ms", "ml", "mt", "mi", "mr", "mh", "mo", "mn", "na", "nv", "ng", "nd", "ne", "no", "nb", "nn", "ii", "oc", "oj", "cu", "or", "om", "os", "pi", "ps", "fa", "pl", "pt", "pa", "qu", "rm", "ro", "ru", "se", "sm", "sg", "sa", "sr", "sh", "st", "tn", "sn", "ii", "sd", "si", "ss", "sk", "sl", "so", "nr", "es", "su", "sw", "ss", "sv", "tl", "ty", "tg", "ta", "tt", "te", "th", "bo", "ti", "to", "ts", "tr", "tk", "tw", "ug", "uk", "ur", "uz", "ve", "vi", "vo", "wa", "cy", "wo", "fy", "xh", "yi, ji", "yo", "za", "zu"
);

// nth-formulas
$this->nth_formulas = array(
	'1',
	'2',
	'3',
	'4',
	'5',
	'6',

	'n+2',
	'n+3',
	'n+4',
	'n+5',
	'n+6',

	'-n+1',
	'-n+2',
	'-n+3',
	'-n+4',
	'-n+5',
	'-n+6',

	'odd',
	'even',
	'3n',
	'4n',
	'5n',
	'6n',

	'2n+1',
	'3n+1',
	'4n+1',
	'5n+1',
	'6n+1',
);

// string for storing favourites
$this->fav_css_filters = '';

// pseudo selectors to show in selector wizard
$this->css_filters = array(

	// classes found in the body tag, this is built dynamically (MT will add some)
	'page_specific' => array(
		'short_label' => esc_html__('Page-specific', 'microthemer'),
		'label' => esc_html__('Page-specific', 'microthemer'),
		'items' => array(
			// note, if changing key text - must update js_i18n_overlay.cur_pid_filter
			'page-id' => array(
				'text' => esc_html__('page-id', 'microthemer'),
				'tip' => esc_attr__('Microthemer will add the page/post id, thus targeting only the current page.', 'microthemer'),
				'common' => 1
			),
			// note, if changing key text - must update js_i18n_overlay.cur_pid_filter
			'page-name' => array(
				'text' => esc_html__('page-name', 'microthemer'),
				'tip' => esc_attr__('Microthemer will add the page/post slug, an alternative way to target only the current page. But will need updating if you change the URL of the page.', 'microthemer'),
			),
			// allow custom prefix for selectors
			'custom-prefix' => array(
				'text' => esc_html__('custom-prefix', 'microthemer'),
				'tip' => esc_attr__('Prefix MT selectors with a custom class or id', 'microthemer'),
				'editable' => array(
					'str' => !empty($this->preferences['custom-prefix-extraValue'])
								? $this->preferences['custom-prefix-extraValue']
								: '',
					'combo' => 'custom_prefix',
					'justInputValue' => 1
				)
			),
		)
	),

	// show all pseudo elements
	'pseudo_elements' => array(
		'short_label' => esc_html__('Pseudo elements', 'microthemer'),
		'label' => esc_html__('Pseudo elements', 'microthemer'),
		'items' => array(
			"::after" => array(
				'tip' => esc_attr__('Insert a pseudo HTML element after any normal content the element may have', 'microthemer'),
				'strip' => '1',
			),
			"::before" => array(
				'tip' => esc_attr__('Insert a pseudo HTML element before any normal content the element may have', 'microthemer'),
				'strip' => '1',
			),
			"::first-letter" => array(
				'tip' => esc_attr__('Target the first letter of a block level element', 'microthemer'),
				'strip' => '1',
			),
			"::first-line" => array(
				'tip' => esc_attr__('Target the first line of a block level element', 'microthemer'),
				'strip' => '1',
			),
			"::marker" => array(
				'tip' => esc_attr__('Target bullets in a HTML list', 'microthemer'),
				'strip' => '1',
			),
			"::placeholder" => array(
				'tip' => esc_attr__('Target an input field\'s placeholder text', 'microthemer'),
				'strip' => '1',
			),
			"::selection" => array(
				'tip' => esc_attr__('Target the area of an element that is selected by the user', 'microthemer'),
				'strip' => '1',
				//'replace' => ':selected', I think this was a mistake
			),
		)
	),

	// there are lots of pseudo classes, maybe show some (0) as dyn controls like JS libraries
	'pseudo_classes' => array(
		'short_label' => esc_html__('Pseudo classes', 'microthemer'),
		'label' => esc_html__('Pseudo classes', 'microthemer'),
		'items' => array(
			":active" => array(
				'tip' => esc_attr__('Target elements in the "being clicked" state', 'microthemer'),
				'strip' => '1',
			),
			":checked" =>  array(
				'tip' => esc_attr__('Target "checked" form elements', 'microthemer'),
				//'strip' => '1',
				//'replace' => '[checked]'
			),
			":disabled" =>  array(
				'tip' => esc_attr__('Target "disabled" form elements', 'microthemer'),
				//'strip' => '1',
				//'replace' => '[disabled]'
			),
			":empty" =>  array(
				'tip' => esc_attr__('Target elements that have no children', 'microthemer'),
			),
			":enabled" =>  array(
				'tip' => esc_attr__('Target "enabled" form elements', 'microthemer'),
			),
			":first-child" =>  array(
				'tip' => esc_attr__('Target elements that are the first child of their parent', 'microthemer'),
			),
			":first-of-type" =>  array(
				'tip' => esc_attr__('Target elements that are the first child of their parent, of a certian type. For instance, the first <p>, of a parent <div>', 'microthemer'),
			),
			":focus" =>  array(
				'tip' => esc_attr__('Target elements that "have focus". For instance, a textarea being edited.', 'microthemer'),
				'strip' => '1',
				//'common' => 1
			),
			":hover" =>  array(
				'tip' => esc_attr__('Target elements in the "being hovered over" state', 'microthemer'),
				'strip' => '1',
				'common' => 1

			),
			":in-range" =>  array(
				'tip' => esc_attr__('Target <input type="number"> elements with values no less or more than their min/max attributes respectively.', 'microthemer'),
				'strip' => '1',
				'filter' => 1,
			),
			":invalid" =>  array(
				'tip' => esc_attr__('Target form elements that have an invalid value such as <input type="email"> fields with a malformed email address', 'microthemer'),
				'strip' => '1',
				'filter' => 1 // http://stackoverflow.com/questions/15820780/jquery-support-invalid-selector
			),
			":is(selector)" =>  array(
				'tip' => esc_attr__('More concise e.g. .long-selector:is(:hover, :focus) instead of .long-selector:hover, .long-selector:focus', 'microthemer'),
				'editable' => array(
					'str' => '(selector)',
					'combo' => 'is_options'
				)
			),
			":lang(language)" =>  array(
				'tip' => esc_attr__('Target elements that have a "lang" attribute set to a certain langauage code e.g. lang="en"', 'microthemer'),
				'editable' => array(
					'str' => '(language)',
					'combo' => 'lang_codes'
				)
			),
			":last-child" =>  array(
				'tip' => esc_attr__('Target elements that are the last child of their parent', 'microthemer'),
			),
			":last-of-type" =>  array(
				'tip' => esc_attr__('Target elements that are the last child of their parent, of a certian type. For instance, the last <p>, of a parent <div>', 'microthemer'),
			),
			":link" =>  array(
				'tip' => esc_attr__('Target unvisited links', 'microthemer'),
				'strip' => '1',
			),
			":not(selector)" =>  array(
				'tip' => esc_attr__('Use a selector in between the brackets to exclude elements from the selection.', 'microthemer'),
				'editable' => array(
					'str' => '(selector)',
				)
			),
			":nth-child(n)" =>  array(
				'tip' => esc_attr__('Target elements that are the nth child of their parent', 'microthemer'),
				'editable' => array(
					'str' => '(n)',
					'combo' => 'nth_formulas'
				)
			),
			":nth-last-child(n)" =>  array(
				'tip' => esc_attr__('Target elements that are the nth child of their parent, counting backwards from the last child', 'microthemer'),
				'editable' => array(
					'str' => '(n)',
					'combo' => 'nth_formulas'
				)
			),
			":nth-last-of-type(n)" =>  array(
				'tip' => esc_attr__('Target elements that are the nth child of their parent, of a certain type (e.g. <p>), counting backwards from the last child', 'microthemer'),
				'editable' => array(
					'str' => '(n)',
					'combo' => 'nth_formulas'
				)
			),
			":nth-of-type(n)" =>  array(
				'tip' => esc_attr__('Target elements that are the nth child of their parent, of a certain type (e.g. <p>)', 'microthemer'),
				'editable' => array(
					'str' => '(n)',
					'combo' => 'nth_formulas'
				)
			),
			":only-of-type" =>  array(
				'tip' => esc_attr__('Target elements that are the only child of their parent, of a certain type', 'microthemer'),
			),
			":only-child" =>  array(
				'tip' => esc_attr__('Target elements that are the only child of their parent', 'microthemer'),
			),
			":optional" =>  array(
				'tip' => esc_attr__('Target elements that do not have the "required" attribute set', 'microthemer'),
				//'strip' => '1',
				//'replace' => ':not([required])',
			),
			":out-of-range" =>  array(
				'tip' => esc_attr__('Target <input type="number"> elements with values outside their min/max attributes.', 'microthemer'),
				'strip' => '1',
				'filter' => 1,
			),
			":read-only" =>  array(
				'tip' => esc_attr__('Target elements that have the "readonly" attribute set', 'microthemer'),
				//'strip' => '1',
				//'replace' => '[readonly]',
			),
			":read-write" =>  array(
				'tip' => esc_attr__('Target elements that do not have the "readonly" attribute set', 'microthemer'),
				//'strip' => '1',
				//'replace' => ':not([readonly])',
			),
			":required" =>  array(
				'tip' => esc_attr__('Target elements that have the "required" attribute set', 'microthemer'),
				'strip' => '1',
				'replace' => '[required]', // this pseudo selector still needs a replacement (only one now)
			),
			":root" =>  array(
				'tip' => esc_attr__('Target the html element, using an alternative selector', 'microthemer'),
			),
			":target" =>  array(
				'tip' => esc_attr__('Links can target elements on the same page that have an id attribute. So <a href="#logo">click me</a> would target <img id="logo" /> when clicked. And the selector "#logo:target" would apply to the image when the link was clicked. ":target" is used to show/hide elements dynamically without using JavaScript.', 'microthemer'),
			),
			":valid" =>  array(
				'tip' => esc_attr__('Target form elements that have a valid value such as <input type="email"> fields with a correctly formatted email address', 'microthemer'),
				'strip' => '1',
				'filter' => 1
			),
			":visited" => array(
				'tip' => esc_attr__('Target links that have been visited', 'microthemer'),
				'strip' => '1',
			),
			":where(selector)" =>  array(
				'tip' => esc_attr__('Like :is but with zero CSS specificity', 'microthemer'),
				'editable' => array(
					'str' => '(selector)',
				)
			),
		)
	),


);

// populate the default media queries
$this->legacy_m_queries = array(
	$this->unq_base.'1' => array(
		"label" => __('Large Desktop', 'microthemer'),
		"query" => "@media (min-width: 1200px)",
	),
	$this->unq_base.'2' => array(
		"label" => __('Desktop & Tablet', 'microthemer'),
		"query" => "@media (min-width: 768px) and (max-width: 979px)",
	),
	$this->unq_base.'3' => array(
		"label" => __('Tablet & Phone', 'microthemer'),
		"query" => "@media (max-width: 767px)",
	),
	$this->unq_base.'4' => array(
		"label" => __('Phone', 'microthemer'),
		"query" => "@media (max-width: 480px)",
	)
);

// min and max width media queries
$this->min_and_max_mqs = array(
	$this->unq_base.'1' => array(
		"label" => __('< 1200', 'microthemer'),
		"query" => "@media (max-width: 1199.98px)",
	),
	$this->unq_base.'2' => array(
		"label" => __('< 980', 'microthemer'),
		"query" => "@media (max-width: 979.98px)",
	),
	$this->unq_base.'3' => array(
		"label" => __('< 768', 'microthemer'),
		"query" => "@media (max-width: 767.98px)",
	),
	$this->unq_base.'4' => array(
		"label" => __('< 480', 'microthemer'),
		"query" => "@media (max-width: 479.98px)",
	),
	$this->unq_base.'7' => array(
		"label" => __('768 >', 'microthemer'),
		"query" => "@media (min-width: 768px)",
	),
	$this->unq_base.'8' => array(
		"label" => __('980 >', 'microthemer'),
		"query" => "@media (min-width: 980px)",
	),
	$this->unq_base.'9' => array(
		"label" => __('1200 >', 'microthemer'),
		"query" => "@media (min-width: 1200px)",
	),
	$this->unq_base.'10' => array(
		"label" => __('1400 >', 'microthemer'),
		"query" => "@media (min-width: 1400px)",
	),
);

// Sample container queries
$this->container_queries = array(
	$this->unq_base.'c1' => array(
		"label" => __('< (c) 1100', 'microthemer'),
		"query" => "@container (max-width: 1100px)",
	),
	$this->unq_base.'c2' => array(
		"label" => __('< (c) 900', 'microthemer'),
		"query" => "@container (max-width: 900px)",
	),
	$this->unq_base.'c3' => array(
		"label" => __('< (c) 700', 'microthemer'),
		"query" => "@container (max-width: 700px)",
	),
	$this->unq_base.'4' => array(
		"label" => __('< (c) 500', 'microthemer'),
		"query" => "@container (max-width: 500px)",
	)
);

$this->default_mqs = array_slice($this->min_and_max_mqs, 0, 4);

$this->mq_sets[esc_html__('Legacy MQs', 'microthemer')] = $this->legacy_m_queries;
$this->mq_sets[esc_html__('Mobile-first MQs', 'microthemer')] = array_slice($this->min_and_max_mqs, 4, 4);
$this->mq_sets[esc_html__('Desktop-first MQs (default)', 'microthemer')] = $this->default_mqs;
$this->mq_sets[esc_html__('Min and max MQs', 'microthemer')] = $this->min_and_max_mqs;
$this->mq_sets[esc_html__('Container Queries', 'microthemer')] = $this->container_queries;


// default preferences for devs are a bit different
$this->default_dev_preferences = array(
	"css_important" => 0,
	//"selname_code_synced" => 1,
	"wizard_expanded" => 1,
);

$default_asset_loading_config = array(
	'global_css' => 1,
	'global_g_fonts' => 0,
	'conditional' => array(),
	'logic' => array(),
);

// Initial Setup Preference Options
$this->initial_preference_options = array(
	'css_important' => array(
		'label' => __('Always add !important to CSS styles', 'microthemer'),
		'label_no' => '(configure manually)',
		'explain' => __('Always add the "!important" CSS declaration to CSS styles. This largely solves the issue of having to understand how CSS specificity works. But if you prefer, you can disable this functionality and still apply "!important" on a per style basis by clicking the [i] icons that will appear to the right of every style input.', 'microthemer')
	),
	'allow_scss' => array(
		'label' => __('Enable Sass (at cost of syncing editor with UI fields)', 'microthemer'),
		'explain' => __('Enable this option if you want to write raw Sass code in the custom code editors or use Sass variables and mixins etc in the GUI fields. Disable this option if you want the CSS code you write to be interchangeably editable with the GUI fields' , 'microthemer')
	),
	'mt_dark_mode' => array(
		'label' => __('Use a dark theme for the Microthemer interface', 'microthemer'),
		'explain' => __('Microthemer defaults to your light vs dark PC system preference, but you can switch MT\'s UI theme', 'microthemer')
	)
);

// define the default preferences here (these can be reset & exported)
$this->default_preferences = array(
	"css_important" => 1,
	"selname_code_synced" => 0,
	"wizard_expanded" => 0,
	"show_code_editor" => 0,
	"code_manual_resize" => 0,
	"initial_scale" => 0,
	"abs_image_paths" => 0,
	"full_editor_instant_save" => 0,
	"inline_editor_instant_save" => 1,
	"dock_whole_gui_left" => 0,
	"dock_inline_editor_left" => 0,
	"dock_full_editor_left" => 0,
	"global_styles_on_login" => 1,
	"pseudo_base_styles" => 0,
	"active_scripts_footer" => 0,
	"hover_inspect" => 1, // this is hard set in $this->getPreferences()
	"prev_folder" => array(
		'global' => '',
		'conditional' => ''
	),
	"fold_threshold" => 1440, // a conservative fold threshold, but still below 4K screens - user can customise of course
	"hover_inspect_off_initially" => 0,
	"scroll_to_elements" => 1, // scroll to out of view elements in reposition_overlays
	"autofocus_editor" => 0,
	"wireframe_mode" => 0,
	"allow_scss" => 0, // if enabled by default, invalid css/scss will prevent stylesheet update.
	"sync_browser_tabs" => 0,
	"specificity_preference" => 1, // 1 = high, 0 = low
	"wp55_jquery_version" => 0,
	"bricks_container_hack" => 0, // no longer necessary since Bricks 1.4
	"remove_all_bricks_container_hacks" => 0,
	"insert_custom_field_classes" => 0,
	"autoload_oxygen" => 0,
	"autoload_elementor" => 0,
	"autoload_FLBuilder" => 0,
	"selector_auto_name" => 1, // always on by default
	"tape_measure_slider" => 1,
	"monitor_js_errors" => 1,
	"stylesheet_in_footer" => 0,
	"gzip" => 1, // try having this off by default - realised there was an issue with sessions now fixed - try having on again
	"hide_ie_tabs" => 1,
	"show_extra_actions" => 1, // have the icons showing by default (change)
	"grid_highlight" => 1,
	"expand_grid" => 0, // this doesn't get saved
	"minify_css" => 1, // because this only happens to published assets now
	"minify_js" => 1,
	"mt_dark_mode" => 0, // dark theme
	"draft_mode" => 1, // need to have draft_mode_conversion when upgrading people - on by default
	"auto_publish_mode" => 0, // maybe don't launch beta with this, but add if requested
	"color_as_hex" => 0,
	"admin_bar_preview" => 1, // because WP jumps out of iframe now
	"admin_asset_loading" => 0,
	"admin_asset_editing" => 0,
	"admin_bar_shortcut" => 1,
	"top_level_shortcut" => 1, // with auto referrer this is more useful and should be on by default
	"first_and_last" => 0,
	"all_devices_default_width" => '',
	"edge_mode" => 0,
	"tooltip_delay" => 500,
	"num_history_points" => 50,
	"suggested_layout" => 'M',
	"dock_folders_left" => 1,
	"dock_styles_left" => 1,
	"dock_editor_left" => 1,
	"full_height_left_sidebar" => 1,
	"sticky_styles_toolbar" => 1,
	"full_height_right_sidebar" => 0,
	"expand_device_tabs" => 0,
	"dock_wizard_right" => 0,
	"dock_settings_right" => 0,
	"detach_preview" => 0,
	"layout" => array(
		'preset' => 's', // maybe set this dynamically
		'left' => array(
			'items' => array(
				'folders' => array(
					'size' => 252, // 229
					'size_category' => 'sm',
				),
				'styles' => array(
					'size' => 252,
					'size_category' => 'sm'
				),
				'editor' => array(
					'size' => 252,
					'size_category' => 'sm'
				),
			),
			'min_column_sizes' => array(252, 252, 180),
			'column_sizes' => array(252, 252, 220),
			'num_items_docked' => 0,
			'num_columns' => 1,
			'effective_num_columns' => 1,
			'top_panel' => array(
				'current' => false,
				'previous' => false,
			),
		),
		'right' => array(
			'items' => array(
				'settings' => array(
					'size' => 205,
					'size_category' => 'sm'
				),
				'wizard' => array(
					'size' => 205,
					'size_category' => 'sm'
				),
			),
			'min_column_sizes' => array(205, 205),
			'column_sizes' => array(205, 205),
			'num_items_docked' => 0,
			'num_columns' => 1,
			'active_settings' => 'general',
			'effective_num_columns' => 0,
			'top_panel' => array(
				'current' => false,
				'previous' => false,
			),
			'expanded_settings' => array(
				'exit'
			),
		),
		'top' => array(
			'effective_num_rows' => 1,
		),
		'editor_height' => array(
			'size' => 160,
			'min_size' => 68,
		),
		'inspection_height' => array(
			'size' => 200,
			'min_size' => 30,
		),
		'inspection_columns' => array(
			'min_column_sizes' => array(300, 310), // 1fr for first column (350, 375)
			'column_sizes' => array(300, 360), // 1fr for first column
		),

	),
	"show_rulers" => 1,
	"show_text_labels" => 1,
	"show_sampled_variables" => 0,
	"show_sampled_values" => 0,
	"adv_wizard_tab" => 'refine-targeting',
	"program_settings_tab" => 'general',
	"overwrite_existing_mqs" => 1,
	"tooltip_en_prop" => 1,
	"css_imp_only_selected" => 0,
	"css_imp_mqs" => 1,
	"css_imp_sels" => 1,
	"css_imp_styles" => 1,
	"css_imp_friendly" => 1,
	"css_imp_adjust_paths" => 1,
	"css_imp_always_cus_code" => 0,
	"css_imp_copy_remote" => 1, // debating this
	"css_imp_max" => 0,
	"page_specific" => array(),
	"pseudo_classes" => array(),
	"pseudo_elements" => array(),
	"font_config" => array(
		'google' => false,
		'typekit' => false,
	),

);

$this->subscription_defaults = array(
	'member_id' => false,
	'renewal_check' => false,
	'eligible_version' => false,
	'capped_version' => false,
	'restriction_mode' => false,
);

$this->subscription_check_defaults = array(
	'stop_attempts' => false,
	'max' => 4,
	'num' => 0,
	'next_time' => false,
);

// if installing MT when a supported page builder is active, default to their MQs

if (count($this->elementor_mqs)){
	$arr['m_queries'] = $this->elementor_mqs;
} elseif (count($this->bb_mqs)){
	$arr['m_queries'] = $this->bb_mqs;
} elseif (count($this->oxygen_mqs)){
	$arr['m_queries'] = $this->oxygen_mqs;
} else {
	$arr['m_queries'] = $this->default_mqs;
}
/*wp_die('We got the queries: <pre>'.print_r(array(
	                                           'the one' => $arr,
	                                           'oxy' => $this->oxygen_mqs
                                           ), true).'</pre>' );*/

$this->stylesheet_order_options = array(
	array(
		'label' => 1,
	),
	array(
		'label' => 10,
	),
	array(
		'label' => 100,
	),
	array(
		'category' => esc_html__('Legacy order (MT6)', 'microthemer'),
		'label' => 999999,
	),
	array(
		'category' => esc_html__('After Oxygen Builder', 'microthemer'),
		'label' => 11000000
	),
	array(
		'category' => esc_html__('Latest possible', 'microthemer'),
		'label' => PHP_INT_MAX
	)
);

$this->page_class_prefix_options = array(
	array(
		'category' => esc_html__('Legacy prefix (MT6)', 'microthemer'),
		'label' => 'mt',
	),
	array(
		'category' => esc_html__('Default prefix', 'microthemer'),
		'label' => 'mtp',
	),
);

// preferences that should not be reset if user resets global preferences
$this->default_preferences_dont_reset_or_export = array(

	// Subscription contingent
	"buyer_email" => '',
	"buyer_validated" => false,
	'retro_sub_check_done' => false,
	"subscription" => $this->subscription_defaults,
	"subscription_checks" => $this->subscription_check_defaults,

	// Site contingent
	"version" => $this->version,
	"previous_version" => '',
	"reporting" => array(
		'anonymous_id' => mt_rand(1000000000, 9999999999),
		'data' => array(
			'last_sent' => null,
			'sends_today' => 0
		),
		'file' => array(
			'last_sent' => null,
			'sends_today' => 0,
			'unique_errors' => (object) array()
		),
		'permission' => array(
			'file' => 1,
			'data' => 1
		)
	),
	"clean_uninstall" => 0,
	"recent_logic" => array(),
	"recent_custom_prefixes" => array(),
	"preview_url" => $this->home_url,
	"preview_title" => false,
	"preview_item_id" => false,
	"inlineJsProgData" => 0,
	"draft_mode_uids" => array(),
	"custom_paths" => array('/'),
	"viewed_import_stylesheets" => array(
		// theme css is an obvious one
		get_stylesheet_directory_uri() . '/style.css',
		// and MT might be useful for authors converting custom code to GUI
		$this->micro_root_url . 'active-styles.css'
	),
	"num_saves" => 0, // keep track of saves for caching purposes
	"num_unpublished_saves" => 0,
	"code_tabs" => $this->custom_code,

	// UI options contingent
	"active_events" => '',
	'g_fonts_used' => false,
	'found_gf_subsets' => array(),
	'g_url' => '',
	'g_url_with_subsets' => '',
	'gfont_subset' => '',
	"asset_loading" => $default_asset_loading_config,
	"asset_loading_published" => $default_asset_loading_config,
	"global_stylesheet_required" => 1,
	"global_stylesheet_required_published" => 1,
	"load_js" => 0,
	"load_js_published" => 0,
	"custom-prefix-extraValue" => '',
	"mt_color_variables_css" => "",

	// UI state contingent
	"active_theme" => 'customised',
	"theme_in_focus" => '',
	"mq_device_focus" => 'all-devices',
	"css_focus" => 'all-browsers',
	"view_css_focus" => 'input',
	"pg_focus" => 'font',
	"grid_focus" => 'gridtemplate',
	"transform_focus" => 'transformscale',
	"generated_css_focus" => 0,
	"returned_ajax_msg" => '',
	"returned_ajax_msg_seen" => 1,

	// Data processing/conversion contingent
	"units_added_to_suggestions" => 0,
	"default_sug_values_set" => 0,
	"default_sug_variables_set" => 0,
	"manual_recompile_all_css" => 0,
	"show_setup_screen" => 1,
	"show_setup_screen_first_time" => 1,
);

// do export, don't reset
$this->default_preferences_exportable = array(

	// related but not dependent on UI data
	"m_queries" => $this->mq_min_max($arr),
	"active_scripts_deps" => 0,
	"enq_js" => array(),
	"my_props" => array(),

	// useful to keep track of watched videos across installs
	"external_videos" => array(
		'last_viewed' => 'introducing-microthemer-7',
		'watched' => array()
	),
);

// do reset, don't export
$this->default_preferences_resetable = array(
	"hide_interface" => 0, // so they can fix a missing UI
);


// some preferences should only be set once we've checked WP dependent variables (e.g. integrations)
if ($pd_context === 'setup_wp_dependent_vars'){

	global $wpdb;
	$revisions_table_name = $wpdb->prefix . "micro_revisions";
	$fresh_install = !$this->check_table_exists($revisions_table_name, true);

	//$this->show_me.= 'Prefs set 2: ' . $this->preferences['allow_scss'] . ', oxy:' . $this->preferences['stylesheet_order'];

	// if oxygen is active and it's a fresh install
	$default_preferences = array(

		// if oxygen is active and it's a fresh install
		//"after_oxy_css" => !empty($this->integrations['oxygen']) && $fresh_install ? 1 : 0,

		// only set stylesheet order on a fresh install
		"stylesheet_order" => ($fresh_install

			// default to highest possible on fresh installs (new)
			// NO - this will invoke $wp_styles->add_data, which is less well tested, so safer to keep original
			? '' //PHP_INT_MAX

			// on existing site upgrades
			: (
				!empty($this->preferences['after_oxy_css'])
					? 11000000 // keep things consistent with deprecated Oxygen setting
					: '' // keep things consistent with the legacy default using normal enqueue hook (999999)
			)
		),

		"page_class_prefix" => $fresh_install ? 'mtp' : 'mt',

		// this is mainly because pre 7.2.1.0 - anyone on a global folder will not get page-specific styles
		// because they will already be on a global folder, which will apply to the page, and not trigger new auto-folder
		"auto_folders" => $fresh_install ? 1 : 0,
		"auto_folders_page" => $fresh_install ? 1 : 0

		// todo remember to update exportable_preferences array merge values if adding new options here
	);

	$this->default_preferences = array_merge($this->default_preferences, $default_preferences);
}

// create micro-themes dir/blank active-styles.css, copy pie if doesn't exist
$this->setup_micro_themes_dir();

// get the file structure - and create micro_root dir if it doesn't exist
$this->file_structure = $this->dir_loop($this->micro_root_dir);

// get the styles from the DB
$this->getOptions();

// get the css props
$this->getPropertyOptions();

// get/set the preferences
$this->getPreferences(true, $pd_context);


//wp_die('<pre>'.print_r($this->file_structure, true).'</pre>');

/*wp_die('<pre>'.print_r($this->get_sass_import_paths("
@import 'breakpoint/context';
@import 'breakpoint/helpers';
@import 'breakpoint/parsers';


@import 'breakpoint/no-query';

@import 'breakpoint/respond-to';"), true).'</pre>');*/

//wp_die('<pre>'.print_r($this->strip_css_sass_comments($this->options['non_section']['hand_coded_css'], true), true).'</pre>');

/*if (!empty($this->options['non_section']['hand_coded_css'])){
	//wp_die('<pre>'.print_r($this->options['non_section']['hand_coded_css'], true).'</pre>');
	wp_die('<pre>'.print_r($this->get_sass_import_content(), true).'</pre>');
}*/

// animation/ transition events
$browser_events = array(

	'CSS Events' => array(
		':hover',
		':focus',
	),

	'JavaScript Events' => array(
		'inView',
		'inView (once)',
		//'toggle',
		'click',
		'mouseenter',
		//'mouseleave',
		'JS focus',
		//'blur'
	),

	/*'View Events' => array(
		'inView',
		'inView (once)',
	),
	'Mouse Events' => array(
		'click',
		'dblclick',
		'mouseenter',
		'mouseleave',
		'mouseover',
		'mouseout',
	),
	'Form Events' => array(
		'change',
		'blur',
		'js focus',
	),
	*/

);

$this->selector_variations = array(
	'normal' => array(
		'label' => esc_html__('normal', 'microthemer'),
		'slug' => 'normal'
	),
	':hover' => array(
		'label' => esc_html__(':hover', 'microthemer'),
		'slug' => 'hover'
	),
	':focus' => array(
		'label' => esc_html__(':focus', 'microthemer'),
		'slug' => 'focus'
	),
	'::before' => array(
		'label' => esc_html__('::before', 'microthemer'),
		'slug' => 'before'
	),
	'::after' => array(
		'label' => esc_html__('::after', 'microthemer'),
		'slug' => 'after'
	)
);

$this->browser_events = $this->to_autocomplete_arr($browser_events);
$this->browser_event_keys = $this->autocomplete_to_param_keys($this->browser_events);

// system fonts
$system_fonts = array(

	__('System fonts', 'microthemer') => array(
		'Arial',
		'"Book Antiqua"',
		'"Bookman Old Style"',
		'"Arial Black"',
		'Charcoal',
		'"Comic Sans MS"',
		'cursive',
		'Courier',
		'"Courier New"',
		'Gadget',
		'Garamond',
		'Geneva',
		'Georgia',
		'Helvetica',
		'Impact',
		'"Lucida Console"',
		'"Lucida Grande"',
		'"Lucida Sans Unicode"',
		'Monaco',
		'monospace',
		'"MS Sans Serif"',
		'"MS Serif"',
		'"New York"',
		'Palatino',
		'"Palatino Linotype"',
		'sans-serif',
		'serif',
		'Symbol',
		'Tahoma',
		'"Times New Roman"',
		'Times',
		'"Trebuchet MS"',
		'Verdana',
		'Webdings',
		'Wingdings',
		'"Zapf Dingbats"'
	),
);

$this->system_fonts = $this->to_autocomplete_arr($system_fonts);


// enq_js
$this->enq_js_structure = array( // structure
	'slug' => 'enq_js',
	'name' => esc_html__('Enqueued Script', 'microthemer'),
	'add_button' => esc_html__('Add Script', 'microthemer'),
	'combo_add' => 1,
	'combo_add_arrow' => 1,
	'name_stub' => 'tvr_preferences[enq_js]',
	'level' => 'script',
	'items' => array(
		'fields' => array(
			'display_name' => array(
				'type' => 'hidden',
				'label' => 0
			)
		),
		'icon' => array(
			'title' => esc_html__('Reorder Enqueued Script', 'microthemer'),
		),
		'actions' => array(
			'delete' => array(
				'class' => 'delete-item',
				'title' => esc_html__('Remove Enqueued Script', 'microthemer'),
			),
			'disabled' => array(
				'icon_control' => 1
			)
		),
		'edit_fields' => 0
	)
);

// media queries
$this->mq_structure = array( // structure
	'slug' => 'mqs',
	'name' => esc_html__('Media Query', 'microthemer'),
	'add_button' => esc_html__('Add Media Query', 'microthemer'),
	'input_placeholder' => 'Enter label',
	'name_stub' => 'tvr_preferences[m_queries]',
	'level' => 'mquery',
	'base_key' => $this->unq_base,
	'items' => array(
		'fields' => array(
			'label' => array(
				'type' => 'text',
				'label' => esc_html__('Label', 'microthemer'),
				'name_class' => 'edit-item',
				'label_title' => esc_html__('Give your media query a descriptive name', 'microthemer'),
			),
			'query' => array(
				'type' => 'textarea',
				'label' => esc_html__('Media Query', 'microthemer'),
				'label_title' => esc_html__('Set the media query condition', 'microthemer'),
			),
			'site_preview_width' => array(
				'type' => 'text',
				'field_class' => 'tvr-input-wrap',
				'input_class' => 'combobox has-arrows',
				'input_rel' => 'builder_sync_tabs',
				'input_arrows' => '<span class="combo-arrow combo-dots"></span>',
				'label' => esc_html__('Site preview width / builder sync (optional)', 'microthemer'),
				'label_title' => esc_html__('Specify the site preview width for this tab, or sync with a page builder responsive view.', 'microthemer'),
			),
			'hide' => array(
				'type' => 'checkbox',
				'field_class' => 'mq-checkbox-wrap',
				'label' => esc_html__('Hide tab in interface', 'microthemer'),
				'label_title' => esc_html__('Hide this tab in the interface if you don\'t need it right now', 'microthemer'),
				'label2' => esc_html__('Yes (no settings will be lost)', 'microthemer')
			),
		),
		'icon' => array(
			'title' => esc_html__('Reorder Media Query', 'microthemer'),
		),
		'actions' => array(
			'delete' => array(
				'class' => 'delete-item',
				'title' => esc_html__('Delete Media Query', 'microthemer'),
			),
			'edit' => array(
				'class' => 'edit-item',
				'title' => esc_html__('Edit Media Query', 'microthemer'),
			),
		)
	)
);

$this->suggested_screen_layouts = $this->suggested_screen_layouts();

// main options menu (should come after preferences have been got/set)
$default_path = $this->root_rel($this->preferences['preview_url'], false, true);
$default_path = $default_path ? $default_path : '/';
$this->menu = array(
	'general' => array(
		'name' => esc_html__('General', 'microthemer'),
		'icon_class' => 'general-program-settings',
		'sub' => array(

			'initial_setup' => array(
				'name' => esc_html__('Setup', 'microthemer'),
				'title' => esc_attr__('Microthemer Setup', 'microthemer'),
				'dialog' => 1,
				'class' => 'mt-initial-setup',
				'icon_name' => 'box-open'
			),

			// prompt to unlock
			/*'buyer_validated' => array(
				'name' => esc_html__('Unlock Microthemer', 'microthemer'),
				'title' => $this->preferences['buyer_validated'] ?
					esc_attr__('Validate license using a different license key', 'microthemer') :
					esc_attr__('Enter your code to unlock Microthemer (see My Downloads on themeover.com)', 'microthemer'),
				'dialog' => 1,
				'class' => 'unlock-microthemer',
				'icon_name' => 'unlock-alt'
			),*/

			'generated' => array(
				//'new_set' => 1,
				'name' => esc_html__('Generated code', 'microthemer'),
				'title' => esc_attr__('View the code Microthemer generates', 'microthemer'),
				'dialog' => 1,
				'class' => 'display-css-code',
				'icon_name' => 'view-code',
				'icon_title' => 'Ctrl + Alt + G',
			),

			'preferences' => array(

				'name' => esc_html__('Preferences', 'microthemer'),
				'title' => esc_attr__('Edit global preferences', 'microthemer'),
				'dialog' => 1,
				'class' => 'display-preferences',
				'icon_name' => 'cog'
			),

			'media_queries' => array(
				'name' => esc_html__('Media queries', 'microthemer'),
				'title' => esc_attr__('Edit media queries', 'microthemer'),
				'dialog' => 1,
				'class' => 'edit-media-queries',
				'icon_name' => 'devices'
			),

			'js_libraries' => array(
				'name' => esc_html__('JS Libraries', 'microthemer'),
				'title' => esc_attr__('Enqueue WordPress JavaScript libraries', 'microthemer'),
				'dialog' => 1,
				'class' => 'mt-enqueue-js',
				'icon_name' => 'js'
			),



			'auto_publish_mode' => array(
				//'new_set' => 1,
				'name' => esc_html__('Auto-publish', 'microthemer'),
				'title' => esc_attr__("Automatically publish changes rather than saving as draft first", 'microthemer'),
				'toggle' => $this->preferences['auto_publish_mode'],
				'class' => 'auto-publish-mode',
				'data-pos' => esc_attr__('Enable Auto-publish mode', 'microthemer'),
				'data-neg' => esc_attr__('Disable Auto-publish mode', 'microthemer'),
			),

		)
	),

	'preview_page' => array(

		'name' => esc_html__('Page', 'microthemer'),
		'sub' => array(
			'preview_url' => array(
				'icon_title' => 'Ctrl + Alt + N',
				'name' => esc_html__('', 'microthemer'),
				'title' => esc_html__('Go to a new page', 'microthemer'),
				'class' => 'switch-preview',
				'combo_data' => 'custom_paths',
				'input' => '', //$default_path,
				'input_id' => 'previewPath',
				'input_name' => 'set_preview_url',
				'input_placeholder' => esc_html__('Search site', 'microthemer'),
				'button' => array(
					'text' => esc_html__('Go', 'microthemer'),
					'class' => 'change-preview-url'
				),
				// todo support Auto-load Elementor toggle
				/*'checkboxes' => array(
					array(
						'name' => 'launch_builder',
						'label' => 'Launch <span class="available-builder-name">builder</span> on new page'
					)
				)*/
			),
			'mt_nonlog' => array(
				'name' => esc_html__('Logged out view', 'microthemer'),
				'title' => esc_attr__("View the page like a non-logged in site visitor", 'microthemer'),
				'class' => 'mt_non_logged',
				'toggle' => !empty($this->preferences['mt_nonlog']),
				'data-pos' => esc_attr__('Load preview as non-logged-in user', 'microthemer'),
				'data-neg' => esc_attr__('Load preview as logged-in user', 'microthemer'),
			),

		)

	),

	// page builders (and maybe other integrations)
	'integrations' => array(

		'name' => esc_html__('Integrations', 'microthemer'),
		'sub' => array(

			'sync_browser_tabs' => array(
				//'icon_title' => '',
				'name' => esc_html__('Sync browser tabs', 'microthemer'),
				'title' => esc_attr__("Auto-refresh Microthemer CSS in other browser tabs", 'microthemer'),
				'class' => 'sync-browser-tabs',
				'toggle' => !empty($this->preferences['sync_browser_tabs']),
				'data-pos' => esc_attr__('Enable synced browser tabs', 'microthemer'),
				'data-neg' => esc_attr__('Disable synced browser tabs', 'microthemer'),
			),


			// autoload feature should only autoload if the page has already been edited
			// set an option on preferences "Only autoload builder if previously launched manually" : "Yes"
			// uncomment options below when the logic for testing this has been implemented
			// Changelog: * "Autoload" option added to the integrations menu for supported builders: Elementor, Beaver Builder, Oxygen.

			'enable_beaver_builder' => array(
				'icon_title' => 'Ctrl + Alt + B',
				'name' => esc_html__('Enable BB', 'microthemer'),
				'title' => esc_attr__("Make page editable with Beaver Builder", 'microthemer'),
				'class' => 'toggle-beaver-builder',
				'toggle' => strpos($this->preferences['preview_url'], '?fl_builder'),
				'data-pos' => esc_attr__('Enable Beaver Builder', 'microthemer'),
				'data-neg' => esc_attr__('Publish & Exit Beaver Builder', 'microthemer'),
			),

			/*'autoload_FLBuilder' => array(
				'name' => esc_html__('Autoload Beaver Builder', 'microthemer'),
				'title' => esc_attr__("Automatically enable Beaver Builder on startup and page navigation", 'microthemer'),
				'class' => 'autoload-beaver_builder',
				'toggle' => !empty($this->preferences['autoload_FLBuilder']),
				'data-pos' => esc_attr__('Enable Beaver Builder autoload', 'microthemer'),
				'data-neg' => esc_attr__('Disable Beaver Builder autoload', 'microthemer'),
			),*/

			'activate_elementor' => array(
				'icon_title' => 'Ctrl + Alt + B',
				'name' => esc_html__('Enable Elementor', 'microthemer'),
				'title' => esc_attr__("Make page editable with Elementor", 'microthemer'),
				'class' => 'toggle-elementor',
				'toggle' => strpos($this->preferences['preview_url'], 'action=elementor'),
				'data-pos' => esc_attr__('Enable Elementor', 'microthemer'),
				'data-neg' => esc_attr__('Publish & Exit Elementor', 'microthemer'),
			),

			/*'autoload_elementor' => array(
				'name' => esc_html__('Autoload Elementor', 'microthemer'),
				'title' => esc_attr__("Automatically enable Elementor on startup and page navigation", 'microthemer'),
				'class' => 'autoload-elementor',
				'toggle' => !empty($this->preferences['autoload_elementor']),
				'data-pos' => esc_attr__('Enable Elementor autoload', 'microthemer'),
				'data-neg' => esc_attr__('Disable Elementor autoload', 'microthemer'),
			),*/

			'activate_oxygen' => array(
				//'new_set' => 1,
				'icon_title' => 'Ctrl + Alt + B',
				'name' => esc_html__('Enable Oxygen', 'microthemer'),
				'title' => esc_attr__("Make page editable with Oxygen", 'microthemer'),
				'class' => 'toggle-oxygen',
				'toggle' => strpos($this->preferences['preview_url'], 'oxygen_iframe=true'),
				'data-pos' => esc_attr__('Enable Oxygen', 'microthemer'),
				'data-neg' => esc_attr__('Save & Exit Oxygen', 'microthemer'),
			),
			/*
			'autoload_oxygen' => array(
				'name' => esc_html__('Autoload Oxygen', 'microthemer'),
				'title' => esc_attr__("Automatically enable Oxygen on startup and page navigation", 'microthemer'),
				'class' => 'autoload-oxygen',
				'toggle' => !empty($this->preferences['autoload_oxygen']),
				'data-pos' => esc_attr__('Enable Oxygen autoload', 'microthemer'),
				'data-neg' => esc_attr__('Disable Oxygen autoload', 'microthemer'),
			),*/

			
		)

	),


	'history' => array(
		'common_option' => 1,
		'icon_class' => 'change-history',
		'name' => esc_html__('History', 'microthemer'),
		'sub' => array(
			'revisions_summary' => array(
				'id' => 'revisions-summary',
				'class' => 'revisions-summary',
				'custom' => $this->getRevisions(true)
			),
			'restore_styles' => array(
				'name' => esc_html__('All revisions', 'microthemer'),
				'title' => esc_attr__("View full list of revisions", 'microthemer'),
				'class' => 'display-revisions',
				'icon_name' => 'undo',
				'dialog' => 1
			),
			'ui_reset' => array(
				'new_set' => '',
				'name' => esc_html__('Reset everything', 'microthemer'),
				'title' => esc_attr__("Reset the interface to the default empty folders", 'microthemer'),
				'class' => 'folder-reset',
				'icon_name' => 'reset-folders'
			),
		)
	),
	'view' => array(
		'common_option' => 1,
		'icon_class' => 'interface-view',
		'name' => esc_html__('View', 'microthemer'),
		'sub' => array(

			'mt_dark_mode' => array(
				//'new_set' => 1,
				'name' => esc_html__('Dark theme', 'microthemer'),
				'title' => esc_attr__("Set a darker theme for the interface", 'microthemer'),
				'toggle' => $this->preferences['mt_dark_mode'],
				'class' => 'mt_dark_mode-switch',
				'data-pos' => esc_attr__('Enable dark theme', 'microthemer'),
				'data-neg' => esc_attr__('Disable dark theme', 'microthemer'),
				'data-fhtml' => 1
			),

			'show_code_editor' => array(
				'name' => esc_html__('Full code editor', 'microthemer'),
				'title' => esc_attr__("Switch code/GUI view", 'microthemer')
				           . " (Ctrl + Alt + C)",
				'class' => 'edit-css-code',
				'toggle' => $this->preferences['show_code_editor'],
				'toggle_id' => 'toggle-full-code-editor',
				'data-pos' => esc_attr__('Show code editor', 'microthemer'),
				'data-neg' => esc_attr__('Show GUI', 'microthemer'),
			),

			// frontend view options
			'show_rulers' => array(
				//'new_set' => 1,
				'name' => esc_html__('Show rulers', 'microthemer'),
				'title' => esc_attr__("Toggle rulers", 'microthemer'),
				'class' => 'toggle-rulers',
				'toggle' => $this->preferences['show_rulers'],
				'data-pos' => esc_attr__('Show rulers', 'microthemer'),
				'data-neg' => esc_attr__('hide rulers', 'microthemer'),
			),

			'wireframe_mode' => array(
				//'new_set' => 1,
				'name' => esc_html__('Wireframe mode', 'microthemer'),
				'title' => esc_attr__("Toggle wireframe mode", 'microthemer'),
				'class' => 'toggle-rulers',
				'toggle' => !empty($this->preferences['wireframe_mode']),
				'data-pos' => esc_attr__('Enable wireframe mode', 'microthemer'),
				'data-neg' => esc_attr__('Disable wireframe mode', 'microthemer'),
				'data-no-save' => 1,
				'data-fhtml' => 1
			),

			// detach preview
			'detach_preview' => array(
				//'new_set' => 1,
				//'keyboard_shortcut' => 'Ctrl + Alt + D',
				'name' => esc_html__('Detach preview', 'microthemer'),
				'title' => esc_attr__("Detach site preview for separate screen", 'microthemer'),
				'class' => 'toggle-detached-preview',
				'toggle' => !empty($this->preferences['detach_preview']),
				'data-pos' => esc_attr__('Detach site preview', 'microthemer'),
				'data-neg' => esc_attr__('Attach site preview', 'microthemer'),
			),



			// full screen - see if anyone misses this
			/*'fullscreen' => array(
				//'new_set' => 1,
				'name' => esc_html__('Fullscreen', 'microthemer'),
				'title' => esc_attr__("Switch fullscreen mode", 'microthemer'),
				'class' => 'toggle-full-screen',
				'toggle' => 0,
				'data-pos' => esc_attr__('Enable fullscreen mode', 'microthemer'),
				'data-neg' => esc_attr__('Disable fullscreen mode', 'microthemer'),
			),*/

			// show
			/*'show_text_labels' => array(
				//'new_set' => 1,
				'name' => esc_html__('Property text labels', 'microthemer'),
				'title' => esc_attr__("Toggle text labels (Ctrl + Alt + L)", 'microthemer'),
				'class' => 'toggle-mt-property-labels',
				'toggle' => $this->preferences['show_text_labels'],
				'data-pos' => esc_attr__('Enable text labels', 'microthemer'),
				'data-neg' => esc_attr__('Disable text labels', 'microthemer'),
			),*/



			// suggested layout for screen size
			'suggested_layout' => array(
				'new_set' => '',
				'name' => esc_html__('Suggested layouts', 'microthemer'),
				'title' => esc_attr__("Suggested layout for screen size", 'microthemer'),
				'class' => 'suggested-screen-layout',
				'custom' => $this->screen_layout_options()
			),

			// suggested layout for screen size
			'advanced_layout_options' => array(
				'nested' => array(
					'name' => esc_html__('Fine tune layout', 'microthemer'),
					'sub' => array(

						// expand device tabs onto a new row
						'expand_device_tabs' => array(
							//'new_set' => 1,
							'name' => esc_html__('Expand device tabs', 'microthemer'),
							'title' => esc_attr__("Have a separate row for the responsive tabs", 'microthemer'),
							'class' => 'full-width-responsive-tabs',
							'toggle' => !empty($this->preferences['expand_device_tabs']),
							'data-pos' => esc_attr__('Enable full width', 'microthemer'),
							'data-neg' => esc_attr__('Disable full width', 'microthemer'),
							'data_attr' => array(
								'layout-preset' => 'view'
							)
						),

						// dock folders left
						'dock_folders_left' => array(
							'new_set' => esc_html__('Left sidebar', 'microthemer'),
							'name' => esc_html__('Dock folders left', 'microthemer'),
							'title' => esc_attr__("Dock the style options to the left of the screen", 'microthemer'),
							'class' => 'toggle-dock-folders-left',
							'toggle' => !empty($this->preferences['dock_folders_left']),
							'data-pos' => esc_attr__('Dock folders left', 'microthemer'),
							'data-neg' => esc_attr__('Undock folders', 'microthemer'),
							'data_attr' => array(
								'layout-preset' => 'view'
							)
						),

						// dock editor left
						'dock_editor_left' => array(
							'name' => esc_html__('Dock editor left', 'microthemer'),
							'title' => esc_attr__("Dock the code editor to the left of the site preview", 'microthemer'),
							'class' => 'toggle-dock-editor-left',
							'toggle' => !empty($this->preferences['dock_editor_left']),
							'data-pos' => esc_attr__('Dock editor left', 'microthemer'),
							'data-neg' => esc_attr__('Dock editor top', 'microthemer'),
							'data_attr' => array(
								'layout-preset' => 'view'
							)
						),

						// dock styles left
						'dock_styles_left' => array(

							'name' => esc_html__('Dock styles left', 'microthemer'),
							'title' => esc_attr__("Dock the style options to the left of the screen", 'microthemer'),
							'class' => 'toggle-dock-styles-left',
							'toggle' => !empty($this->preferences['dock_styles_left']),
							'data-pos' => esc_attr__('Dock styles left', 'microthemer'),
							'data-neg' => esc_attr__('Dock styles top', 'microthemer'),
							'data_attr' => array(
								'layout-preset' => 'view'
							)
						),

						// dock styles left
						'sticky_styles_toolbar' => array(
							'name' => esc_html__('Sticking styles toolbar', 'microthemer'),
							'title' => esc_attr__("Always display styles navigation toolbar", 'microthemer'),
							'class' => 'sticky-styles-toolbar',
							'toggle' => !empty($this->preferences['sticky_styles_toolbar']),
							'data-pos' => esc_attr__('Stick styles toolbar in place', 'microthemer'),
							'data-neg' => esc_attr__('Unstick styles toolbar', 'microthemer')
						),

						'full_height_left_sidebar' => array(
							'name' => esc_html__('Full height sidebar', 'microthemer'),
							'title' => esc_attr__("Make the left sidebar fill the height of the screen", 'microthemer'),
							'class' => 'full-height-left-sidebar',
							'toggle' => !empty($this->preferences['full_height_left_sidebar']),
							'data-pos' => esc_attr__('Enable full height', 'microthemer'),
							'data-neg' => esc_attr__('Disable full height', 'microthemer'),
							'data_attr' => array(
								'layout-preset' => 'view'
							)
						),

						// Num left columns
						'left_sidebar_columns' => array(
							'name' => esc_html__('Number of columns', 'microthemer'),
							'title' => esc_attr__("Display left docked content in one or more columns", 'microthemer'),
							'class' => 'left-sidebar-layout has-select-menu',
							'column_options' => 'left',
							'data_attr' => array(
								'layout-preset' => 'view',
								'aspect' => 'left_sidebar_columns'
							)
						),

						// RIGHT

						// dock_settings_right
						'dock_settings_right' => array(
							'new_set' => esc_html__('Right sidebar', 'microthemer'),
							'name' => esc_html__('Dock settings right', 'microthemer'),
							'title' => esc_attr__("Dock these program settings right", 'microthemer'),
							'class' => 'toggle-dock-program-settings-right',
							'toggle' => !empty($this->preferences['dock_settings_right']),
							'data-pos' => esc_attr__('Dock program settings right', 'microthemer'),
							'data-neg' => esc_attr__('Undock program settings', 'microthemer'),
							'data_attr' => array(
								'layout-preset' => 'view'
							)
						),

						// dock_settings_right
						/*'dock_wizard_right' => array(
							'name' => esc_html__('Dock inspection right', 'microthemer'),
							'title' => esc_attr__("Dock the inspection options to the right of the screen", 'microthemer'),
							'class' => 'toggle-dock-inspection-right',
							'toggle' => !empty($this->preferences['dock_wizard_right']),
							'data-pos' => esc_attr__('Dock right', 'microthemer'),
							'data-neg' => esc_attr__('Dock bottom', 'microthemer'),
						),*/

						 // Always have full height so settings panel never moves based on toggle change
						 'full_height_right_sidebar' => array(
							'name' => esc_html__('Full height sidebar', 'microthemer'),
							'title' => esc_attr__("Make the right sidebar fill the height of the screen", 'microthemer'),
							'class' => 'full-height-right-sidebar',
							'toggle' => !empty($this->preferences['full_height_right_sidebar']),
							'data-pos' => esc_attr__('Enable full height', 'microthemer'),
							'data-neg' => esc_attr__('Disable full height', 'microthemer'),
							'data_attr' => array(
								'layout-preset' => 'view'
							)
						),

						// Num right columns
						/*'right_sidebar_columns' => array(
							'name' => esc_html__('Number of columns', 'microthemer'),
							'title' => esc_attr__("Display right docked content in one or more columns", 'microthemer'),
							'class' => 'right-sidebar-layout has-select-menu',
							'column_options' => 'right',
							'data_attr' => array(
								'layout-preset' => 'view',
								'aspect' => 'right_sidebar_columns'
							)
						),*/
					)
				),
			),
		)
	),

	'packs' => array(
		'icon_class' => 'import-export-icon',
		'name' => esc_html__('Import / Export', 'microthemer'),
		'sub' => array(
			'manage' => array(
				'name' => esc_html__('Install & Manage', 'microthemer'),
				'title' => esc_attr__("Install & manage your design packages", 'microthemer'),
				'class' => 'manage-design-packs',
				'icon_name' => 'manage-packs',
				'dialog' => 1
			),
			'import' => array(
				'name' => esc_html__('Import CSS', 'microthemer'),
				'title' => esc_attr__("Import from a design pack or CSS stylesheet", 'microthemer'),
				'class' => 'import-from-pack',
				'icon_name' => 'import',
				'dialog' => 1
			),
			'export' => array(
				'name' => esc_html__('Export CSS', 'microthemer'),
				'title' => esc_attr__("Export your CSS to a design pack", 'microthemer'),
				'class' => 'export-to-pack',
				'icon_name' => 'export',
				'dialog' => 1
			),
		)
	),

	'shortcuts' => array(
		'name' => esc_html__('Keyboard shortcuts', 'microthemer'),
		'sub' => array(
			'keyboard_shortcuts' => array(
				'name' => esc_html__('Keyboard shortcuts', 'microthemer'),
				//'title' => esc_attr__("Suggested layout for screen size", 'microthemer'),
				'class' => 'keyboard-shortcuts',
				'custom' => $this->keyboard_shortcuts_list()
			),
		)
	),

	'support' => array(
		'name' => esc_html__('Help', 'microthemer'),
		'icon_class' => 'help-icon',
		'sub' => array(
			/*'start_tips' => array(
				'name' => esc_html__('Getting started tips', 'microthemer'),
				'title' => esc_attr__("Quick tips on how to use Microthemer", 'microthemer'),
				'class' => 'program-docs',
				'data_attr' => array(
					'docs-index' => 1
				),
				'dialog' => 1
			),*/
			'video' => array(
				'name' => esc_html__('Getting started video', 'microthemer'),
				'title' => esc_attr__("Quick start and in-depth video rolled into one",
					'microthemer'),
				'class' => 'demo-video',
				'icon_name' => 'play',
				'link_target' => '_blank',
				'item_link' => $this->demo_video
			),
			'changes_video' => array(
				'name' => esc_html__('V6 to V7 changes', 'microthemer'),
				'title' => esc_attr__("The 3 main changes for V6 users to adjust to",
					'microthemer'),
				'class' => 'demo-video',
				'icon_name' => 'play',
				'link_target' => '_blank',
				'item_link' => 'https://themeover.com/v6-to-v7-3-main-changes/'
			),
			'docs' => array(
				'name' => esc_html__('Documentation', 'microthemer'),
				'title' => esc_attr__("Learn how to use Microthemer", 'microthemer'),
				'class' => 'online-docs',
				'icon_name' => 'help',
				'link_target' => '_blank',
				'item_link' => 'https://themeover.com/install-and-setup/'
			),
			'reference' => array(
				'name' => esc_html__('CSS Reference', 'microthemer'),
				'title' => esc_attr__("Learn how to use each CSS property", 'microthemer'),
				'class' => 'program-docs',
				'icon_name' => 'docs',
				'data_attr' => array(
					'prop-group' => 'font',
					'prop' => 'font_family'
				),
				'dialog' => 1
			),
			// https://themeover.com/install-and-setup/
			/*'responsive' => array(
				'name' => esc_html__('Responsive tutorial', 'microthemer'),
				'title' => esc_attr__("Learn the basics and CSS layout and responsive design", 'microthemer'),
				'class' => 'responsive-tutorial',
				'link_target' => '_blank',
				'item_link' => 'https://themeover.com/html-css-responsive-design-wordpress-microthemer/'
			),*/
			'forum' => array(
				'name' => esc_html__('Forum', 'microthemer'),
				'title' => esc_attr__("Get help in the forum", 'microthemer'),
				'class' => 'support-forum',
				'icon_name' => 'forum',
				'link_target' => '_blank',
				'item_link' => 'https://themeover.com/forum/'
			),
		)
	),


	/*'dashboard' => array(
		'direct-action' => 1,
		'icon_class' => 'wp-dashboard-icon',
		'name' => esc_html__('WP Dashboard', 'microthemer'),
		'title' => esc_attr__("Go to your WordPress dashboard", 'microthemer'),
		'class' => 'back-to-wordpress',
		'item_link' => $this->wp_blog_admin_url
	),
	'frontend' => array(
		'direct-action' => 1,
		'icon_class' => 'site-frontend-icon',
		'name' => esc_html__('Site frontend', 'microthemer'),
		'title' => esc_attr__("Go to your website", 'microthemer'),
		'class' => 'back-to-frontend',
		'item_link' => $this->preferences['preview_url'],
		'link_id' => 'back-to-frontend'
	),*/

	'exit' => array(
		'custom_insert' => 1,
		'icon_class' => 'exit-to-wordpress',
		'name' => esc_html__('Exit', 'microthemer'),
		'sub' => array(
			'dashboard' => array(
				'name' => esc_html__('WordPress dashboard', 'microthemer'),
				'title' => esc_attr__("Go to your WordPress dashboard", 'microthemer'),
				'class' => 'back-to-wordpress',
				'icon_name' => 'wordpress',
				'item_link' => $this->wp_blog_admin_url
			),
			'frontend' => array(
				'name' => esc_html__('Site frontend', 'microthemer'),
				'title' => esc_attr__("Go to your website", 'microthemer'),
				'class' => 'back-to-frontend',
				'icon_name' => 'site-frontend',
				'item_link' => $this->preferences['preview_url'],
				'link_id' => 'back-to-frontend',
				'text_data_attr' => array(
					'draft-status' => esc_attr__('draft', 'microthemer'),
				),
			),
		)
	),

	// more menu




);

// have a dev menu solely for our testing
if (TVR_DEV_MODE){
	$this->menu['dev'] = array(
		'common_option' => 1,
		'icon_class' => 'mt-dev-only',
		'name' => esc_html__('Dev', 'microthemer'),
		'sub' => array(
			'show_time_concurrently' => array(
				'name' => esc_html__('Concurrent timers', 'microthemer'),
				'title' => esc_attr__("Output functions times in browser console as they happen", 'microthemer'),
				'toggle' => 0,
				'class' => 'show_time_concurrently',
				'data-pos' => esc_attr__('Enable', 'microthemer'),
				'data-neg' => esc_attr__('Disable', 'microthemer'),
			),
			'show_total_times' => array(
				'name' => esc_html__('Show total function times', 'microthemer'),
				'short_name' => esc_html__('Order by', 'microthemer'),
				'class' => 'show_total_times',
				'title' => esc_attr__("Show functions that have accrued time since page load and subsequent actions.", 'microthemer'),
				'text_class' => 'link menu-input-toggle',
				'combo_data' => 'show_total_times',
				'input' => 'avg_time',
				'button' => array(
					'text' => esc_html__('OK', 'microthemer'),
					'class' => 'change-show_total_times'
				)
			),
			'clear_logs' => array(
				'name' => esc_html__('Clear console logs', 'microthemer'),
				'title' => esc_attr__('Clear everything in the browser console', 'microthemer'),
				'class' => 'clear-console-logs'
			),
			/*
			'test_all_mt_actions' => array(
				'name' => esc_html__('Test every MT action', 'microthemer'),
				'title' => esc_attr__('Run every function in MT specified in auto-test.js', 'microthemer'),
				'class' => 'test_all_mt_actions'
			)*/
		)
	);

}


