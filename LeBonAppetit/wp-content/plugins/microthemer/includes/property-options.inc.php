<?php
// Stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

$colorvars_sug_config = array(
	'this' => 1,
	'root_cat' => 'color_extra'
);

// tips that repeat
$height_percentage_tip = esc_html__('Tip: setting height, min-height, or max-height as a percentage won\'t work unless the parent element has an explicit value for height.', 'microthemer');
$border_radius_unconvertable = esc_html__('There is no direct equivalent between pixels and percent values for border radius.', 'microthemer');

$padding_lengths = array(
	'0',
	'4',
	'6',
	'8',
	'12',
	'16',
	'24',
	'32',
	'48',
	'64',
);
$margin_lengths = array_merge($padding_lengths, array(
	'auto',
	'-8',
	'-16'
));

// brightness
$brightness = array();
for ($x = 0; $x < 2.1; $x+=.25) {
	$brightness[] = round($x, 2);
}

// grayscale
$opacity = array();
for ($x = 0; $x < 1.05; $x+=.1) {
	$opacity[] = round($x, 1);
}

$grayscale = $opacity;
$invert = $opacity;


// decimals for transform functions
$decimals = array();
for ($x = 0; $x <= 2; $x+=0.25) {
	$decimals[] = round($x, 2);
}

// degrees for transform functions
$degrees = array(-45, 45);
for ($x = -270; $x < 360; $x+=90) {
	$degrees[] = round($x, 0);
}

$perspective = array(
	'0',
	'50',
	'100',
	'200',
	'300',
	'400',
	'500'
);

// example lengths
$translate = array_merge($perspective, array(
	'-100',
	'-50%',
));

$position_lengths = array(
	'auto',
	'0',
	'50%',
	'75%',
	'100%'
);

$z_index = array(
	'-1',
	'0',
	'1',
	'2',
	'3',
	'4',
	'10',
	'9999',
	'auto',
);

$background_position_values = array(
	'left top',
	'left center',
	'left bottom',
	'right top',
	'right center',
	'right bottom',
	'center top',
	'center center',
	'center bottom',
	'0px 0px',
	'50% 50%',
);

$background_size_values = array(
	'auto',
	'cover',
	'contain',
	'100% auto',
	'auto 100%',
	'50% 50%',
);

$background_repeat_values = array(
	'repeat',
	'repeat-x',
	'repeat-y',
	'no-repeat'
);

$geometry_box_values = array(
	'margin-box',
	'border-box',
	'padding-box',
	'content-box',
	// not widely supported yet (March 2022)
	//'fill-box',
	//'stroke-box',
	//'view-box',
);

$display_ref_values = array(
	"block" => "Displays an element as if it were a block level element like a paragraph or heading.",
	"flex" => "Displays an element as a block-level flex container.",
	"grid" => "Displays an element as a block-level grid container.",
	"inline" => "Displays an element if it were an inline element like a link or an image.",
	"inline-block" => "Displays an element with all the properties of a block-level element apart from displaying inline with other content (rather than having a row of it's own).",
	"inline-flex" => "Displays an element as an inline-level flex container.",
	"inline-grid" => "Displays an element as an inline-level grid container.",
	"inline-table" => "The element is displayed as an inline-level table.",
	"list-item" => "The element behaves like a list item element.",
	"run-in" => "Displays an element as either block or inline, depending on context.",
	"table" => "The element behaves like a table element.",
	"table-caption" => "The element behaves like a caption element.",
	"table-column-group" => "The element behaves like a column group element.",
	"table-header-group" => "The element behaves like a table head element.",
	"table-footer-group" => "The element behaves like a table foot element.",
	"table-row-group" => "The element behaves like a tbody element.",
	"table-cell" => "The element behaves like a td element (useful if you want vertical align to work as expected).",
	"table-column" => "The element behaves like a table column element.",
	"table-row" => "The element behaves like a table row element.",
	"none" => "The element doesn't appear on the page at all. This is different from setting the visibility property to 'hidden' whereby the hidden element still takes up space on the page (it's just invisible)."
);


// special time units
$time_units = array(
	'type' => 'time',
	'exclusive' => 1, // no other units are valid
	'units' => array('s', 'ms')
);

// special grid units
$fraction_units = array(
	'type' => 'fraction',
	'units' => array('fr'),
);

// special angle units
$angle_units = array(
	'type' => 'angle',
	'exclusive' => 1,
	'units' => array('deg', 'grad', 'rad', 'turn')
);

// when only percent or no unit is OK
$percent_none = array(
	'type' => 'percent_none',
	'exclusive' => 1,
	'units' => array('', '%')
);

// when unit is optional (just line-height I think)
$unitless_too = array(
	'type' => 'unitless_too',
	'units' => array('')
);

// flex data

$flex_grow_shrink_order = array(
	'0',
	'1',
	'2',
	'3',
	'4',
	'5',
	'6',
	'7',
	'8'
);

// prefixes for display: flex/inline-flex (display property appears twice)
$flex_display_prefixes = array(
	'values' => array(
		'flex' => array('-webkit-box', '-moz-box', '-ms-flexbox', '-webkit-flex'),
		'inline-flex' => array('-webkit-inline-flexbox', '-moz-inline-flexbox', '-ms-inline-flexbox')
	)
);



// grid data

$grid_special = array(
	'minmax(100px, 1fr)',
	'fit-content(50%)',
	'min-content',
	'max-content',
);

// sizes that are valid for both auto and template
$grid_sizes = array_merge($grid_special, array('auto', '1fr', '2fr',  '100px', '200px',  '25%', '50%'));

// add in options that are only valid for grid-template
$grid_template_sizes = array_merge($grid_sizes, array(
	'none',
	'repeat(2, 1fr)',
	'repeat(auto-fit, minmax(320px, 1fr))'
));

$line_names = array(
	'[head-start]',
	'[head-end]',
	'[head-end main-start]',
	'[main-start]',
	'[main-end]',
);

$template_areas_add = array(
	'head',
	'main',
	'side',
	'foot',
);


// these should populate dynamically - using parsed comp values
$template_areas = array(
	'auto'
);

$line_sug_config = array(
	'this' => 1,
	'root_cat' => 'line_names'
);

$ja_items = array(
	'normal',
	'stretch',
	'center',
	'start',
	'end',
	//'self-start',
	//'self-end',
	//'left',
	//'right',
	'baseline',
	//'first baseline',
	//'last baseline',
	//'safe center',
	//'unsafe center',
);

$ja_content = array(
	'center',
	'start',
	'end',
	//'left',
	//'right',
	'normal',
	'space-between',
	'space-around',
	'space-evenly',
	'stretch',
	//'safe center',
	//'unsafe center',
);

$gap_sizes = $padding_lengths; //array_merge($percent_sizes, $padding_lengths);
$grid_lines = array(
	'auto',
	'1',
	'2',
	'span 1',
	'span 2',
);
$grid_lines_end = array_merge($grid_lines, array('-1', '-2'));

$grid_tutorials = array(
	array(
		'url' => 'https://gridbyexample.com/video/',
		'title' => 'Learn Grid Layout Video Series',
	),
	array(
		'url' => 'https://learncssgrid.com/',
		'title' => 'Learn CSS Grid',
	),
	array(
		'url' => 'https://css-tricks.com/snippets/css/complete-guide-grid/',
		'title' => 'A Complete Guide to Grid',
	),
);

$grid_area = array(
	'short_label' => esc_html_x('Grid area', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid area', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Area', 'microthemer'),
	'sub_slug' => 'itemarea',
	'tab_control' => 'griditems',
	'select_options' => $template_areas,
	'sug_values' => array(
		'this' => 1,
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '14, 8, B',
	'icon-name' => 'grid-area-gridall',
	// ref
	'ref_desc' => "<p>Assign a grid item to a named grid area. Or a shorthand to set grid-row-start, grid-column-start, grid-row-end and grid-column-end using forward slashes (/) to separate values.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-area',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-area.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-area.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

$grid_order = array(
	'animatable' => 1,
	'short_label' => esc_html_x('Order', 'noun', 'microthemer'),
	'label' => esc_attr_x('Order (grid)', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Other', 'microthemer'),
	'sub_slug' => 'itemother',
	'tab_control' => 'griditems',
	'icon' => '11, 1, B',
	'icon-name' => 'order',
	'select_options' => $flex_grow_shrink_order,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'order',
		'unitless_slider' => 1,
		'min' => '',
		'increment' => 1
	),
	// ref
	'ref_desc' => "<p>Specifies the order of a grid item relative to other items. The default value for this property is 0, which means '-1' must be used to place an item first.</p>",
	'ref_values' => array(
		"(unitless number)" => "E.g. -1, 1, 2, 3 etc",
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/order',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_order.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_order.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

$grid_z_index = array(
	'short_label' => esc_html_x('Z-index', 'noun', 'microthemer'),
	'label' => esc_attr_x('Z-index (grid)', 'noun', 'microthemer'),

	'tab_control' => 'griditems',
	'select_options' => $z_index,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'z_index',
		'unitless_slider' => 1,
		'min' => '',
		'increment' => 1
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '21, 14',
	'icon-name' => 'z-index',
	// ref
	'ref_desc' => "<p>Specifies the stack order of an element. An element with greater stack order is always in front of an element with a lower stack order.</p>",
	'ref_values' => array(
		"(unitless number)" => "E.g. -1, 1, 2, 3 etc",
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/z-index',
		'quackit' => 'http://www.quackit.com/css/properties/css_z-index.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_z-index.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// general


// the column/row-gap config is practically the same for grid and flexbox
$column_gap_config = array(
	'sh' => array('gap', 1, array(
		'initial' => 0,
		'prefixes' => array(
			'property' => array(
				'grid-gap',
			)
		),
	)),
	'short_label' => esc_html_x('Column gap', 'noun', 'microthemer'),
	'label' => esc_attr_x('Column gap', 'noun', 'microthemer'),
	'field-class' => 'perma-show',
	'input-class' => 'size-4',
	'spacer_before' => 1,
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $gap_sizes,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing'
	),
	'unit_rel' => 1,
	'unit_sub_label' => esc_html__('Gap', 'microthemer'),
	'prefixes' => array(
		'property' => array(
			'grid-column-gap',
		)
	),
	'icon' => '3, 8, B',
	// ref
	'ref_desc' => "<p>Set the gap between columns.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/column-gap',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-column-gap.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-column-gap.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

$row_gap_config = array(
	'sh' => array('gap', 0, array(
		'initial' => 0,
	)),
	'short_label' => esc_html_x('Row gap', 'noun', 'microthemer'),
	'label' => esc_attr_x('Row gap', 'noun', 'microthemer'),
	'field-class' => 'perma-show',
	'input-class' => 'size-4',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'select_options' => $gap_sizes,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing'
	),
	'unit_rel' => 1,
	'prefixes' => array(
		'property' => array(
			'grid-row-gap',
		)
	),
	'icon' => '4, 8, B',
	// ref
	'ref_desc' => "<p>Set the gap between rows.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/row-gap',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-row-gap.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-row-gap.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);


// the property options
$propertyOptions = array();

// Custom code
$propertyOptions['code']['css'] = array(
	'short_label' => esc_html__('Editor', 'microthemer'),
	'label' => esc_attr__('Editor', 'microthemer'),
	'pg_label' => esc_attr__('Code editor', 'microthemer'),
	'sub_label' => esc_html__('Code editor', 'microthemer'),
	'field-class' => 'icon-size-3',
	'input-class' => 'tvr-editor-hidden',
	'sub_slug' => 'code',
	'new_pg_cat' => esc_attr__('Code', 'microthemer'), // for delimiting property group categories
	'type' => 'editor',
	'icon' => '15, 1',
	'icon-name' => 'code',
	// ref
	'ref_desc' => "<p>Enter CSS properties by hand instead/as well as using the GUI options. This is useful if Microthemer doesn't support a particular CSS property, or if you just prefer writing CSS styles by hand.</p>
<p>This hybrid method of using the GUI with a code editor may be preferable over using just the custom code editor for many. Using this hybrid method, you can leverage the time saving selector wizard, as well as the repetition saving responsive tabs in the GUI view. You only need to update the CSS selector code in one place - the quick edit option in the top toolbar. The CSS selector will automatically update for all media queries.</p>",
	'ref_values' => array(
		"property:value;" => "Enter one or more css properties and values e.g. transform: rotate(7deg);",
		".my-selector { @include my-mixin(); }" => "You can enter Sass code here too e.g. &:hover { @include animate-button-background(); color:\$button_hover_text_color; }"
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.w3schools.com/cssref/',
			'title' => 'W3Schools CSS Reference',
		),
		array(
			'url' => 'http://sass-lang.com/',
			'title' => 'SASS documentation',
		),
	)
);

// font
// ------------------------------------------------------------
$propertyOptions['font']['font_family'] = array(
	'sh' => array('font', 5, array('initial' => 'initial')), // depends on user agent
	'short_label' => esc_html_x('Font Family', 'noun: Font Family', 'microthemer'),
	'label' => esc_attr__('Font Family', 'microthemer'),
	'pg_label' => esc_attr__('Font', 'microthemer'),
	'sub_label' => esc_html__('Font', 'microthemer'),
	'sub_slug' => 'font',
	'new_pg_cat' => esc_attr__('Text', 'microthemer'), // for delimiting property group categories
	// reference map for getting property values from stylesheet
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'font_family'
		// 'sh' => is checked too
	),
	'type' => 'combobox',
	'field-class' => 'has-category-combo grid-large',
	'input-class' => 'tvr-font-select size-big',
	'icon' => '33, 14',
	// ref
	'ref_desc' => "<p>The font-family property specifies the type of font for an element. You can specify just one font such as Arial, but it's also commonplace to specify multiple fonts separated by commas e.g. \"Book Antiqua\", Garamond, serif (hence the name font-family). The browser will try to load the first font, in this case \"Book Antiqua\". If the user doesn't have that font installed on their computer the second font will be loaded (e.g. Garamond) or the third - and so on. Note that if a font has spaces in the name in needs to be wrapped in quotes as has been done with \"Book Antiqua\".</p>",
	'ref_values' => array(
		"font name" => "The name of the font e.g. 'Arial'"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/font-family/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/font-family',
		'quackit' => 'http://www.quackit.com/css/properties/css_font-family.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_font_font-family.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://www.smashingmagazine.com/2010/12/what-font-should-i-use-five-principles-for-choosing-and-using-typefaces/',
			'title' => '“What Font Should I Use?”: Five Principles for Choosing and Using Typefaces',
		),
		array(
			'url' => 'http://webdesign.tutsplus.com/articles/choosing-the-right-font-a-practical-guide-to-typography-on-the-web--webdesign-15',
			'title' => 'Choosing the Right Font: A Practical Guide to Typography on the Web',
		),
		array(
			'url' => 'http://learn.shayhowe.com/html-css/working-with-typography/',
			'title' => 'Working with Typography',
		),
		array(
			'url' => 'http://ilovetypography.com/2008/04/04/on-choosing-type/',
			'title' => 'On Choosing Type',
		),
	)
);

$propertyOptions['font']['google_font'] = array(
	'short_label' => esc_html__('Google Font', 'microthemer'),
	'label' => esc_attr__('Google Font Family', 'microthemer'),
	'field-class' => 'tvr-font-custom',
	'input-class' => 'size-big mt-google-font',
	'type' => 'combobox',
	// set empty array for this, we're not currently getting this from site scan, as it will show up in font-family
	// but my_props will update with user inserting google fonts
	'sug_values' => array(
		//'non_gathered' => 1,
		'this' => 1,
		//'root_cat' => 'font_family' // no, it won't work with e.g. Raleway (700) and isn't guranteed to load
	),
	'icon' => '30',
	// ref
	'ref_desc' => "<p>Choose from hundreds of free, open-source fonts optimized for the web. Google Fonts is a service Google offers free of charge that allows web designers to use a wide variety of openly licensed fonts on their web pages. Prior to font services like Google Web Fonts, web designers were limited to using a small selection of 'web safe' fonts that were very likely to be installed on any computer. Now we can use Google fonts that may not be installed on a user's computer (because they are downloaded from Google). Microthemer makes use of Google's Web Fonts API to make adding Google Fonts really easy. Just select 'Google Fonts...' from the font-family menu, and then click the 'Use This Font' link next to the name of the font. For efficiency, Microthemer only loads the Google Fonts you've specified in your Microthemer workspace settings. This automation makes experimenting with different Google fonts hassle free.</p>",
	'ref_values' => array(
		"font name" => "The name of the font e.g. 'Oswald'. Note Microthemer includes the variation e.g. (normal) or (italic 700) in brackets so that Microthemer can download the correct font file (and only that font file) from Google."),
	'tutorials' => array(
		array(
			'url' => 'http://www.google.com/fonts/',
			'title' =>'Browse All Google Fonts',
			'tool' => 1,
		),
		array(
			'url' => 'http://designshack.net/articles/css/a-beginners-guide-to-using-google-web-fonts/',
			'title' =>'A Beginner’s Guide to Using Google Web Fonts',
		),
		array(
			'url' => 'https://developers.google.com/fonts/docs/getting_started',
			'title' =>'Get Started with the Google Fonts API',
		),
	)
);

$propertyOptions['font']['color'] = array(
	'animatable' => 1,
	'short_label' => esc_html_x('Color', 'noun', 'microthemer'),
	'label' => esc_attr_x('Color', 'noun', 'microthemer'),
	'field-class' => 'is-picker',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'color'
	),
	'sug_values_extra' => $colorvars_sug_config,
	'icon' => '26',
	// ref
	'ref_desc' => "<p>The color property specifies the color of text.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the Color text field to reveal the color picker."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=css3-colors',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/c/color/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/color',
		'quackit' => 'http://www.quackit.com/css/properties/css_color.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_color.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://practicaltypography.com/color.html',
			'title' => 'Color: Black is still best',
		),
		array(
			'url' => 'http://paletton.com/',
			'title' => 'Paletton: The Color Scheme Designer',
			'tool' => 1,
		),
		array(
			'url' => 'https://color.adobe.com',
			'title' => 'Adobe Color CC',
			'tool' => 1,
		),
		array(
			'url' => 'http://www.colourlovers.com/palettes',
			'title' => 'COLOURlovers is a creative community where people from around the world create
			and share colors, palettes and patterns',
			'tool' => 1,
		),
		array(
			'url' => 'http://www.pictaculous.com/',
			'title' => 'Pictaculous: upload your image, get a color palette',
			'tool' => 1,
		),
	)
);

$propertyOptions['font']['font_size'] = array(
	'sh' => array('font', 3, array(
		'initial' => 'medium'
	)),
	'animatable' => 1,
	'short_label' => esc_html_x('Font Size', 'noun', 'microthemer'),
	'label' => esc_attr__('Font Size', 'microthemer'),
	'field-class' => 'icon-size-0',
	'input-class' => 'size-0',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'font-size'
		)
	),
	'select_options' => array(
		'14',
		'16',
		'18',
		'24',
		'28',
		'32',
		'48',
		'64',
	),
	'default_unit' => 'px',
	'sug_values' => array(
		'this' => 1
	),
	'icon' => '21',
	// ref
	'ref_desc' => "<p>The font-size property sets the font-size of text.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '12' would set the font size to 12 pixels. Other commonly used units include 'em', 'rem', and '%'. So you could enter '1.2em' in the Font Size field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/font-size/',
		'mozilla' => 'https://developer.mozilla.org/en/docs/Web/CSS/font-size',
		'quackit' => 'http://www.quackit.com/css/properties/html_font_size_code.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_font_font-size.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.sitepoint.com/css-font-sizing-tutorial/',
			'title' => 'CSS Font-Sizing: a Definitive Guide',
		),
		array(
			'url' => 'https://www.smashingmagazine.com/2014/09/balancing-line-length-font-size-responsive-web-design/',
			'title' => 'Size Matters: Balancing Line Length And Font Size In Responsive Web Design',
		),
		array(
			'url' => 'http://www.css3-tutorial.net/text-font/font-size/',
			'title' => 'Font size',
		),
		array(
			'url' => 'A Visual Type Scale',
			'title' => 'http://type-scale.com/',
			'tool' => 1,
		),
	)
);

$propertyOptions['font']['line_height'] = array(
	'sh' => array('font', 4, array(
		'initial' => 'normal',
		'optional' => 1
	)),
	'animatable' => 1,
	'short_label' => esc_html__('Line Height', 'microthemer'),
	'label' => esc_attr__('Line Height', 'microthemer'),
	'input-class' => 'size-0',
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => 'font-size'
		)
	),
	'select_options' => array(
		'1',
		'1.2',
		'1.4',
		'1.6',
		'1.8',
		'2',
		'normal'
	),
	'default_unit' => '', // empty by default, but user can set
	'special_units' => $unitless_too,
	'sug_values' => array(
		'this' => 1
	),
	'icon' => '22',
	// ref
	'ref_desc' => "<p>The line-height property specifies the line height of an element. A line of text will be vertically centered within the specified height.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '1.5' would set the line height to 18 pixels if the font-size for the element was set to 12px (12 x 1.5).
		Note that with line-height it is valid and often advisable not to specify a unit value, thus allowing the browser to calculate line-height based on font-size. Microthemer therefore does not automatically add the 'px' unit if no unit is specified."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/l/line-height/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/line-height',
		'quackit' => 'http://www.quackit.com/css/properties/css_line-height.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_dim_line-height.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://practicaltypography.com/line-spacing.html',
			'title' => 'Line Spacing: 120–145% of the point size',
		),
		array(
			'url' => 'http://www.webteacher.ws/2009/09/30/improve-readability-with-line-height/',
			'title' => 'Improve readability with line-height',
		),
		array(
			'url' => 'http://kingdesk.com/articles/optimal-line-height/',
			'title' => 'Optimal Line Height',
		),
	)
);

$propertyOptions['font']['font_weight'] = array(
	'sh' => array('font', 2, array(
		'initial' => 'normal',
		'optional' => 1
	)),
	'animatable' => 1,
	'short_label' => esc_html_x('Font Weight', 'noun: Font Weight', 'microthemer'),
	'label' => esc_attr__('Font Weight', 'microthemer'),
	'field-class' => 'icon-size-0a',
	'input-class' => 'size-2',
	'type' => 'combobox',
	'select_options' => array(
		"normal",
		"bold",
		"bolder",
		"lighter",
		"100",
		"200",
		"300",
		"400",
		"500",
		"600",
		"700",
		"800",
		"900"
	),
	'icon' => '27',
	// ref
	'ref_desc' => "<p>The font-weight property sets the thickness of text characters.</p>",
	'ref_values' => array(
		"normal" => "Defines normal characters. This is default",
		"bold" => "Defines thick characters",
		"bolder" => "Defines thicker characters",
		"lighter" => "Defines lighter characters",
		"100" => "a numeric definition of thickness",
		"200" => "a numeric definition of thickness",
		"300" => "a numeric definition of thickness",
		"400" => "a numeric definition of thickness (equivalent to the 'normal' keyword)",
		"500" => "a numeric definition of thickness",
		"600" => "a numeric definition of thickness",
		"700" => "a numeric definition of thickness (equivalent to the 'bold' keyword)",
		"800" => "a numeric definition of thickness",
		"900" => "a numeric definition of thickness"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/font-weight/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/font-weight',
		'quackit' => 'http://www.quackit.com/css/properties/css_font-weight.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_font_weight.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.css3-tutorial.net/text-font/font-weight/',
			'title' => 'Font weight',
		),
		array(
			'url' => 'http://practicaltypography.com/bold-or-italic.html',
			'title' => 'Bold or italic: One or the other, as little as possible',
		),
	)
);

$propertyOptions['font']['font_style'] = array(
	'sh' => array('font', 0, array(
		'initial' => 'normal',
		'optional' => 1
	)),
	'short_label' => esc_html_x('Font Style', 'noun: Font Style', 'microthemer'),
	'label' => esc_attr__('Font Style', 'microthemer'),
	'field-class' => 'icon-size-0a',
	'input-class' => 'size-2a',
	'type' => 'combobox',
	'select_options' => array(
		"normal",
		"italic",
		"oblique"
	),
	'icon' => '28',
	// ref
	'ref_desc' => "<p>The font-style property specifies the font style for text. Generally used for setting italic text.</p>",
	'ref_values' => array(
		"normal" => "The browser displays a normal font style. This is default",
		"italic" => "The browser displays an italic font style",
		"oblique" => "The browser displays an oblique font style"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/font-style/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/font-style',
		'quackit' => 'http://www.quackit.com/css/properties/css_font-style.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_font_font-style.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://docstore.mik.ua/orelly/web2/css/ch05_04.htm',
			'title' => 'Styles and Variants',
		),
		array(
			'url' => 'http://www.css3-tutorial.net/text-font/font-style/',
			'title' => 'The font-style property',
		),
	)
);

$propertyOptions['font']['font_variant'] = array(
	'sh' => array('font', 1, array(
		'initial' => 'normal',
		'optional' => 1
	)),
	'short_label' => esc_html__('Font Variant', 'microthemer'),
	'label' => esc_attr__('Font Variant', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-4',
	// It would have been better to structure the data with [padding_margin][padding][padding_left] but it's too late now.
	// last_in_sub is a dirty work around for closing the subgroup-tag divs properly
	// I'm not sure we used it in the end...
	'last_in_sub' => 1,
	'type' => 'combobox',
	'select_options' => array(
		"normal",
		"small-caps"
	),
	'icon' => '29',
	// ref
	'ref_desc' => "<p>The font-variant property specifies whether or not text should
be displayed in a small-caps font. And that is the only option!</p>",
	'ref_values' => array(
		"normal" => "The browser displays a normal font. This is default",
		"small-caps" => "The browser displays a small-caps font"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/font-variant/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/font-variant',
		'quackit' => 'http://www.quackit.com/css/properties/css_font-variant.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_font_font-variant.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://ilovetypography.com/2008/02/20/small-caps/',
			'title' => 'Small caps',
		),
		array(
			'url' => 'http://practicaltypography.com/small-caps.html',
			'title' => 'Use real small caps; avoid fakes',
		),
	)
);

$propertyOptions['font']['text_decoration'] = array(
	'short_label' => esc_html__('Text Decoration', 'microthemer'),
	'label' => esc_attr__('Text Decoration', 'microthemer'),
	'field-class' => 'icon-size-0a grid-large',
	'input-class' => 'size-5a',
	'type' => 'combobox',
	'select_options' => array(
		"underline",
		"overline",
		"line-through",
		"none"
	),
	'icon' => '31',
	// ref
	'ref_desc' => "<p>The text-decoration property specifies the line decoration below, above, or through text.</p>",
	'ref_values' => array(
		"underline" => "Defines a line below the text",
		"overline" => "Defines a line above the text",
		"line-through" => "Defines a line through the text",
		"none" => "	Defines normal text. This is default"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/t/text-decoration/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/text-decoration',
		'quackit' => 'http://www.quackit.com/css/properties/css_text-decoration.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_text-decoration.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://www.1keydata.com/css-tutorial/text-decoration.php',
			'title' => 'Examples of text-decoration property values',
		),
	)
);

// text
// ------------------------------------------------------------

$propertyOptions['text']['text_align'] = array(
	'short_label' => esc_html__('Align', 'microthemer'),
	'label' => esc_attr__('Text Align', 'microthemer'),
	'pg_label' => esc_attr__('Text', 'microthemer'),
	'sub_label' => esc_html__('Text', 'microthemer'),
	'sub_slug' => 'text',
	'input-class' => 'size-1',
	'type' => 'combobox',
	'select_options' => array(
		"left",
		"right",
		"center",
		"justify"
	),
	'icon' => '38, 14',
	// ref
	'ref_desc' => "<p>The text-align property specifies the horizontal alignment of text in an element.</p>",
	'ref_values' => array(
		"left" => "Aligns the text to the left. This is default",
		"right" => "Aligns the text to the right",
		"center" => "Centers the text",
		"justify" => "Stretches the lines so that each line has equal width (like in newspapers and magazines)"),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/t/text-align/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/text-align',
		'quackit' => 'http://www.quackit.com/css/properties/css_text-align.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_text-align.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://css-tricks.com/centering-css-complete-guide/',
			'title' => 'Centering in CSS: A Complete Guide',
		),
	)
);

$propertyOptions['text']['text_indent'] = array(
	'animatable' => 1,
	'short_label' => esc_html_x('Indent', 'noun', 'microthemer'),
	'label' => esc_attr__('Text Indent', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-0b',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => array(
		"0",
		"32",
		"48",
		"64",
		"-999"
	),
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '23, 4',
	// ref
	'ref_desc' => "<p>The text-indent property specifies the indentation of the first line in a text-block. You might choose to set this property on dense articles of text to improve readability where paragraphs have no bottom margin.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '80' would set the text indent to 80 pixels. Other commonly used units include 'em' and '%'. So you could enter '15%' in the Text Indent field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/t/text-indent/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/text-indent',
		'quackit' => 'http://www.quackit.com/css/properties/css_text-indent.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_text-indent.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.goer.org/HTML/intermediate/align_and_indent/',
			'title' => 'Align and Indent',
		),
		array(
			'url' => 'http://www.thesitewizard.com/css/hanging-indents.shtml',
			'title' => 'How to Create Hanging Indents in HTML and CSS',
		),
	)
);

$propertyOptions['text']['text_transform'] = array(
	'short_label' => esc_html__('Transform', 'noun', 'microthemer'),
	'label' => esc_attr__('Text Transform', 'microthemer'),
	'input-class' => 'size-4',
	'type' => 'combobox',
	'select_options' => array(
		"capitalize",
		"uppercase",
		"lowercase",
		"none"
	),
	'field-class' => 'last',
	'icon' => '32',
	// ref
	'ref_desc' => "<p>The text-transform property controls the capitalization of text.</p>",
	'ref_values' => array(
		"capitalise" => "Transforms the first character of each word to uppercase",
		"uppercase" => "Transforms all characters to uppercase",
		"lowercase" => "Transforms all characters to lowercase",
		"none" => "No capitalization. The text renders as it is. This is default"),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/t/text-transform/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/text-transform',
		'quackit' => 'http://www.quackit.com/css/properties/css_text-transform.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_text-transform.asp',
	),
	'tutorials' => array()
);

$propertyOptions['text']['word_spacing'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Word Spacing', 'microthemer'),
	'field-class' => 'icon-size-4',
	'input-class' => 'size-0b',
	'auto' => array(
		'%' => false
	),
	'select_options' => array(
		'normal',
		'0',
		'2',
		'4',
		'6',
		'8'
	),
	'label' => esc_attr__('Word Spacing', 'microthemer'),
	'default_unit' => 'px',
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '22, 4',
	// ref
	'ref_desc' => "<p>The word-spacing property increases or decreases the white space between words.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '10' would set the font size to 10 pixels. Other commonly used units include 'em' and '%'. So you could enter '1em' in the Word Spacing field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/w/word-spacing/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/word-spacing',
		'quackit' => 'http://www.quackit.com/css/properties/css_word-spacing.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_word-spacing.asp',
	),
	'tutorials' => array()
);

$propertyOptions['text']['letter_spacing'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Letter Spacing', 'microthemer'),
	'label' => esc_attr__('Letter Spacing', 'microthemer'),
	'input-class' => 'size-2',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => array(
		'normal',
		'1',
		'2',
		'3',
		'4',
	),
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '24',
	// ref
	'ref_desc' => "<p>The letter-spacing property increases or decreases the space between characters in text.<p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '2' would set the letter spacing to 2 pixels."),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/l/letter-spacing/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/letter-spacing',
		'quackit' => 'http://www.quackit.com/css/properties/css_letter-spacing.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_letter-spacing.asp',
	),
	'tutorials' => array()
);

$propertyOptions['text']['word_wrap'] = array(
	'short_label' => esc_html__('Word Wrap', 'microthemer'),
	'label' => esc_attr__('Word Wrap', 'microthemer'),
	'input-class' => 'size-4a',
	'type' => 'combobox',
	'select_options' => array(
		"normal",
		"break-word"
	),
	'icon' => '41, 14',
	// ref
	'ref_desc' => "<p>The word-wrap property determines if whole words can be broken over two lines.<p>",
	'ref_values' => array(
		"normal" => "Words will only split in allowed places, like when a hyphen is used.",
		"break-word" => "Words will break at any point to ensure that they stay within their container element.",
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=wordwrap',
		'css_tricks' => '', // is not there
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/letter-spacing',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_word-wrap.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_word-wrap.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.impressivewebs.com/word-wrap-css3/',
			'title' => 'Word-Wrap: A CSS3 Property That Works in Every Browser',
		),
	)
);

$propertyOptions['text']['word_wrap'] = array(
	'short_label' => esc_html__('Word Wrap', 'microthemer'),
	'label' => esc_attr__('Word Wrap', 'microthemer'),
	'input-class' => 'size-4a',
	'type' => 'combobox',
	'select_options' => array(
		"normal",
		"break-word"
	),
	'icon' => '41, 14',
	// ref
	'ref_desc' => "<p>The word-wrap property determines if whole words can be broken over two lines.<p>",
	'ref_values' => array(
		"normal" => "Words will only split in allowed places, like when a hyphen is used.",
		"break-word" => "Words will break at any point to ensure that they stay within their container element.",
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=wordwrap',
		'css_tricks' => '', // is not there
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/letter-spacing',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_word-wrap.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_word-wrap.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.impressivewebs.com/word-wrap-css3/',
			'title' => 'Word-Wrap: A CSS3 Property That Works in Every Browser',
		),
	)
);

$propertyOptions['text']['white_space'] = array(
	'short_label' => esc_html__('White Space', 'microthemer'),
	'label' => esc_attr__('White Space', 'microthemer'),
	'input-class' => 'size-3a',
	'type' => 'combobox',
	'select_options' => array(
		'',
		"normal",
		"nowrap",
		"pre",
		"pre-line",
		"pre-wrap"
	),
	'icon' => '43, 14',
	// ref
	'ref_desc' => "<p>The white-space property determines how white spaces characters like spaces, tabs, and returns are handled. It also controls how text should wrap.<p>",
	'ref_values' => array(
		"normal" => "Multiple whitespace characters will be treated as one, text will wrap when necessary (default).",
		"nowrap" => "Multiple whitespace characters will be treated as one, text will never wrap to the next line until a br tag is used.",
		"pre" => "Whitespace characters are honoured. Text only wraps on line breaks - acts like the pre tag in HTML",
		"pre-line" => "Multiple whitespace characters will be treated as one, text will wrap when necessaryand on line breaks",
		"pre-wrap" => "Whitespace characters are honoured, text will wrap when necessary, and on line breaks"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/w/whitespace/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/white-space',
		'quackit' => 'http://www.quackit.com/css/properties/css_white-space.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_white-space.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://perishablepress.com/wrapping-content/',
			'title' => 'Wrapping Long URLs and Text Content with CSS',
		),
	)
);

// todo uncomment when icon is ready
/*$propertyOptions['text']['word_break'] = array(
	'short_label' => esc_html__('Word Break', 'microthemer'),
	'label' => esc_attr__('Word Break', 'microthemer'),
	//'input-class' => 'size-3a',
	'type' => 'combobox',
	'select_options' => array(
		"normal",
		"break-all",
		"keep-all",
		"break-word",
	),
	'icon' => '', // needed
	// ref
	'ref_desc' => "<p>The word-break CSS property sets whether line breaks appear wherever the text would otherwise overflow its content box. <p>",
	'ref_values' => array(
		"normal" => "Default value. Uses default line break rules",
		"break-all" => "To prevent overflow, word may be broken at any character",
		"keep-all" => 'Word breaks should not be used for Chinese/Japanese/Korean (CJK) text. Non-CJK text behavior is the same as value "normal"',
		"break-word" => "To prevent overflow, word may be broken at arbitrary points",
	),
	'ref_links' => array(
		'css_tricks' => 'https://css-tricks.com/almanac/properties/w/word-break/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/word-break',
		'quackit' => 'https://qhmit.com/css/css3/properties/css_word-break.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_word-break.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://www.bitdegree.org/learn/css-word-break',
			'title' => 'CSS Word Break',
		),
	)
);*/

$propertyOptions['text']['direction'] = array(
	'short_label' => esc_html__('Direction', 'microthemer'),
	'label' => esc_attr__('Text Direction', 'microthemer'),
	'input-class' => '',
	'type' => 'combobox',
	'select_options' => array(
		"ltr",
		"rtl"
	),
	'icon' => '42, 14',
	// ref
	'ref_desc' => "<p>The text-direction property specifies the direction of the text. Arabic sites would set this to 'right', as they read from right to left. Western sites are not likely to use this property as the default is 'left'.<p>",
	'ref_values' => array(
		"lte" => "The writing direction is left to right (default).",
		"rtl" => "The writing direction is right to left."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/d/direction/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/direction',
		'quackit' => 'http://www.quackit.com/css/properties/css_direction.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_text_direction.asp',
	),
	'tutorials' => array()
);

$propertyOptions['text']['vertical_align'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Vertical Align', 'microthemer'),
	'label' => esc_attr__('Vertical Align', 'microthemer'),
	'field-class' => 'grid-large',
	'input-class' => 'size-5',
	'type' => 'combobox',
	'select_options' => array(
		'baseline',
		'bottom',
		'middle',
		'sub',
		'super',
		'text-bottom',
		'text-top',
		'top'
	),
	'icon' => '31, 14',
	// ref
	'ref_desc' => "<p>The vertical-align property sets the vertical alignment of an element. Elements with a 'display' value of 'block' ignore the vertical-align property, but their inline children (if any) will inherit the vertical-align value. Tip: if you set the 'display' property to 'table-cell' you may find that vertical-align works in the way you expect (the same goes for applying vertical align on actual table cells). This <a target='_blank' href='http://phrogz.net/css/vertical-align/'>tutorial on vertical-align</a> is worth reading.<p>",
	'ref_values' => array(
		"baseline" => "Align the baseline of the element with the baseline of the parent element. This is default",
		"length" => "Raises or lower an element by the specified length. Negative values are allowed",
		"%" => "Raises or lower an element in a percent of the 'line-height' property. Negative values are allowed",
		"sub" => "Aligns the element as if it was subscript",
		"super" => "Aligns the element as if it was superscript",
		"top" => "The top of the element is aligned with the top of the tallest element on the line",
		"text-top" => "The top of the element is aligned with the top of the parent element's font",
		"middle" => "The element is placed in the middle of the parent element",
		"bottom" => "The bottom of the element is aligned with the lowest element on the line",
		"text-bottom" => "The bottom of the element is aligned with the bottom of the parent element's font"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=vertical-align',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/v/vertical-align/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/vertical-align',
		'quackit' => 'http://www.quackit.com/css/properties/css_vertical-align.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_vertical-align.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://christopheraue.net/2014/03/05/vertical-align/',
			'title' => 'Vertical-Align: All You Need To Know',
		),
		array(
			'url' => 'http://createdineden.com/blog/2014/september/24/a-professional-guide-how-to-vertically-align-elements-using-css/',
			'title' => 'A Professional Guide: How to Vertically Align Elements Using CSS',
		),
		array(
			'url' => 'http://www.hongkiat.com/blog/css-tricks-vertical-align-content/',
			'title' => '6 CSS Tricks to Align Content Vertically',
		),
	)
);

// list
// ------------------------------------------------------------

$propertyOptions['list']['list_style_type'] = array(
	'sh' => array('list-style', 0, array(
		'initial' => 'disc',
		'optional' => 1
	)),
	'short_label' => esc_html_x('List Style Type', 'noun: List Style Type', 'microthemer'),
	'label' => esc_attr__('List Style Type', 'microthemer'),
	'pg_label' => esc_attr__('List', 'microthemer'),
	'sub_label' => esc_html__('List', 'microthemer'),
	'sub_slug' => 'list',
	'last_in_sub' => 1,
	'field-class' => 'grid-large last',
	'input-class' => 'size-big',
	'type' => 'combobox',
	'select_options' => array(
		'circle',
		'disc',
		'square',
		'armenian',
		'decimal',
		'decimal-leading-zero',
		'georgian',
		'lower-alpha',
		'lower-greek',
		'lower-latin',
		'lower-roman',
		'upper-alpha',
		'upper-latin',
		'upper-roman',
		'cjk-ideographic',
		'hebrew',
		'hiragana',
		'hiragana-iroha',
		'katakana',
		'katakana-iroha',
		'none'
	),
	'icon' => '23',
	// ref
	'ref_desc' => "<p>The list-style-type specifies the type of list-item marker in a list. Circle, disc and square can be applied to unordered lists (&#60;ul&#62;), commonly referred to as bulleted lists. All the rest can be applied to ordered lists (&#60;ol&#62;), such as those with numbers at the start.</p>",
	'ref_values' => array(

		// we shouldn't be repeating this, flatten array and use this as central source of knowledge

		"circle" => "The marker is a circle",
		"disc" => "The marker is a filled circle. This is default",
		"square" => "The marker is a square",
		"armenian" => "The marker is traditional Armenian numbering",
		"decimal" => "	The marker is a number",
		"decimal-leading-zero" => "	The marker is a number padded by initial zeros (01, 02, 03, etc.)",
		"georgian" => "The marker is traditional Georgian numbering (an, ban, gan, etc.)",
		"lower-alpha" => "The marker is lower-alpha (a, b, c, d, e, etc.)",
		"lower-greek" => "The marker is lower-greek (alpha, beta, gamma, etc.)",
		"lower-latin" => "The marker is lower-latin (a, b, c, d, e, etc.)",
		"lower-roman" => "The marker is lower-roman (i, ii, iii, iv, v, etc.)",
		"upper-alpha" => "The marker is upper-alpha (A, B, C, D, E, etc.)",
		"upper-latin" => "upper-latin	The marker is upper-latin (A, B, C, D, E, etc.)",
		"upper-roman" => "The marker is upper-roman (I, II, III, IV, V, etc.)",

		"cjk-ideographic" => "The marker is plain ideographic numbers",
		"hebrew" => "The marker is traditional Hebrew numbering",
		"hiragana" => "The marker is traditional Hiragana numbering",
		"hiragana-iroha" => "The marker is traditional Hiragana iroha numbering",
		"katakana" => "The marker is traditional Katakana numbering",
		"katakana-iroha" => "The marker is traditional Katakana iroha numbering",

		"none" => ""
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/l/list-style/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/list-style-type',
		'quackit' => 'http://www.quackit.com/css/properties/css_list-style-type.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_list-style-type.asp'
	),
	'tutorials' => array(
		array(
			'url' => 'http://alistapart.com/article/taminglists',
			'title' => 'CSS Design: Taming Lists',
		),
		array(
			'url' => 'http://www.tizag.com/cssT/list.php',
			'title' => 'CSS lists',
		),
		array(
			'url' => 'http://css.maxdesign.com.au/listutorial/index.htm',
			'title' => 'Listutorial: simple tutorials on CSS based lists',
		),
		array(
			'url' => 'http://learn.shayhowe.com/html-css/creating-lists/',
			'title' => 'Creating Lists',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['list']['list_style_image'] = array(
	'sh' => array('list-style', 1, array(
		'initial' => 'none',
		'optional' => 1
	)),
	'short_label' => esc_html_x('List Style Image', 'noun', 'microthemer'),
	'label' => esc_attr__('List Style Image', 'microthemer'),
	//'type' => 'combobox',
	'field-class' => 'last span-3 icon-size-3 grid-large size-very-big',
	'input-class' => 'bg-image-select',
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	// we have slider for adjusting css gradient values
	'default_unit' => '',
	/*'select_options' => array(
		'none'
	),*/
	'icon' => '28, 14',
	// ref
	'ref_desc' => "<p>The list-style-image property sets an image to use for each item in the list (instead of a regular bullet point or number).<p>",
	'ref_values' => array(
		"none" => "No image will be used, the value for list-style-type will be used (default).",
		"url" => "The path to an image. You can use Microthemer's image browser to set an image you've uploaded to your WordPress media library."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/l/list-style/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/list-style-image',
		'quackit' => 'http://www.quackit.com/css/properties/css_list-style-image.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_list-style-image.asp',
	),
	'tutorials' => $propertyOptions['list']['list_style_type']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['list']['list_style_position'] = array(
	'sh' => array('list-style', 2, array(
		'initial' => 'outside',
		'optional' => 1
	)),
	'short_label' => esc_html__('List Style Position', 'microthemer'),
	'label' => esc_attr__('List Style Position', 'microthemer'),
	'field-class' => 'icon-size-2 grid-large',
	'input-class' => 'size-3',
	'type' => 'combobox',
	'select_options' => array(
		"inside",
		"outside"
	),
	'icon' => '27, 14',
	// ref
	'ref_desc' => "<p>The list-style-position property determines if the list item marker should appear inside or outside the flow of the content.<p>",
	'ref_values' => array(
		"outside" => "Keeps the marker to the left of the text (default).",
		"inside" => "Indents the item marker."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/l/list-style/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/list-style-position',
		'quackit' => 'http://www.quackit.com/css/properties/css_list-style-position.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_list-style-position.asp',
	),
	'tutorials' => $propertyOptions['list']['list_style_type']['tutorials'],
	'group_tutorials' => 1
);

// text shadow
// ------------------------------------------------------------

// suggested shadow lengths
$text_shadow_lengths = array(
	'0', '1', '2', '-2', '-1',
);
$box_shadow_lengths = array(
	'0', '1', '2', '3', '4', '5', '-3', '-2', '-1',
);
$shadow_blur = array(
	'0', '1', '2', '3', '4', '5', '6', '7', '8'
);

$propertyOptions['shadow']['text_shadow_x'] = array(
	'sh' => array('text-shadow', 0, array(
		'onlyShort' => 1,
		'initial' => 0
		// required value
	)),
	'multiple' => 1,
	'animatable' => 1, // text-shadow shorthand is, no longhand (so use first)
	'short_label' => esc_html__('X', 'microthemer'),
	'label' => esc_attr__('Text Shadow X-offset', 'microthemer'),
	'pg_label' => esc_attr__('Shadow', 'microthemer'),
	'sub_label' => esc_html__('Text Shadow', 'microthemer'),
	'sub_slug' => 'text_shadow',
	'new_pg_cat' => esc_attr__('Decorate', 'microthemer'), // for delimiting property group categories
	'input-class' => 'size-0b',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $text_shadow_lengths,
	'sug_values' => array(
		'this' => 1,
		 'root_cat' => 'text_shadow',
		'min' => ''
	),
	'unit_rel' => 1,
	'hide imp' => 1,
	'icon' => '39',
	// ref
	'ref_desc' => "<p>The position of the horizontal shadow. Negative values are allowed</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '1' would create a 1 pixel shadow to the right of the text."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=css-textshadow',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/t/text-shadow/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/text-shadow',
		'quackit' => 'http://www.quackit.com/css/properties/css_text-shadow.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_text-shadow.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.css3-tutorial.net/text-font/text-shadow/',
			'title' => 'Text shadow',
		),
		array(
			'url' => 'http://xahlee.info/js/css_text_shadow.html',
			'title' => 'CSS: Text Shadow, Glow/Outline Effects',
		),
	),
	'related_tutorials' => esc_attr__('Shadow', 'microthemer')
);

$propertyOptions['shadow']['text_shadow_y'] = array(
	'sh' => array('text-shadow', 1, array(
		'onlyShort' => 1,
		'initial' => 0
		// required value
	)),
	'short_label' => esc_html__('Y', 'microthemer'),
	'label' => esc_attr__('Text Shadow Y-offset', 'microthemer'),
	'input-class' => 'size-0b',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $text_shadow_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'text_shadow',
		'min' => ''
	),
	'unit_rel' => 1,
	'hide imp' => 1,
	'icon' => '40',
	// ref
	'ref_desc' => "<p>The position of the vertical shadow. Negative values are allowed</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '-1' would create a 1 pixel shadow above the element."
	),
	'ref_links' => $propertyOptions['shadow']['text_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['text_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['text_shadow_x']['sub_label']
);

$propertyOptions['shadow']['text_shadow_blur'] = array(
	'sh' => array('text-shadow', 2, array(
		'onlyShort' => 1,
		'initial' => 0,
		'optional' => 1
	)),
	'short_label' => esc_html_x('Blur', 'noun', 'microthemer'),
	'label' => esc_attr__('Text Shadow Blur', 'microthemer'),
	'input-class' => 'size-0b',
	'last_in_sub' => 1,
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $shadow_blur,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'text_shadow'
	),
	'unit_rel' => 1,
	'hide imp' => 1,
	'icon' => '42',
	// ref
	'ref_desc' => "<p>The blur distance. If you define a black text shadow and a blur of 3px, their will be a 3 pixel blur area where the shadow fades evenly from black to transparent.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '4' would create a 4 pixel text blur."
	),
	'ref_links' => $propertyOptions['shadow']['text_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['text_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['text_shadow_x']['sub_label']
);

$propertyOptions['shadow']['text_shadow_color'] = array(
	'sh' => array('text-shadow', 3, array(
		'onlyShort' => 1,
		'initial' => '',
		'optional' => 1
		// optional, but only shorthand so no initial needed
	)),
	'short_label' => esc_html_x('Color', 'noun', 'microthemer'),
	'label' => esc_attr__('Text Shadow Color', 'microthemer'),
	'field-class' => 'is-picker last icon-size-2',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'root_cat' => 'color'
	),
	'last_in_sub' => 1,
	'important_carrier' => 1,
	'icon' => '41',
	// ref
	'ref_desc' => "<p>The color of the text shadow.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the 'Text Shadow Color' text field to reveal the color picker."
	),
	'ref_links' => $propertyOptions['shadow']['text_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['text_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['text_shadow_x']['sub_label']
);

$propertyOptions['shadow']['box_shadow_x'] = array(
	'sh' => array('box-shadow', 0, array(
		'onlyShort' => 1,
		'initial' => 0
		// required
	)),
	'multiple' => 1,
	'animatable' => 1, // only shorthand, box-shadow (use first)
	'short_label' => esc_html__('X', 'microthemer'),
	'label' => esc_attr__('Box Shadow x-offset', 'microthemer'),
	'sub_label' => esc_html__('Box Shadow', 'microthemer'),
	'sub_slug' => 'box_shadow',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $box_shadow_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'box_shadow',
		'min' => ''
	),
	'unit_rel' => 1,
	'field-class' => 'icon-size-2',
	'input-class' => 'size-0b',
	'hide imp' => 1,
	'icon' => '34',
	// ref
	'ref_desc' => "<p>The position of the horizontal shadow. Negative values are allowed</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would create a 15 pixel shadow to the right of the element. Other commonly used units include 'em' and '%'. So you could enter '2%'."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=css-boxshadow',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/box-shadow/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/box-shadow',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_box-shadow.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_box-shadow.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://paulund.co.uk/creating-different-css3-box-shadows-effects',
			'title' => 'CSS Box Shadows Effects',
		),
		array(
			'url' => 'http://red-team-design.com/how-to-create-slick-effects-with-css3-box-shadow/',
			'title' => 'How to create slick effects with CSS3 box-shadow',
		),
		array(
			'url' => 'http://xahlee.info/js/css_box_shadow.html',
			'title' => 'CSS: Box Shadow Examples',
		),
		array(
			'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Box_Model/Box-shadow_generator',
			'title' => 'Box-shadow generator',
			'tool' => 1,
		),
		array(
			'url' => 'http://www.cssmatic.com/box-shadow',
			'title' => 'CSSmatic Box Shadow',
			'tool' => 1,
		),
	),
	'related_tutorials' => esc_html__('Box Shadow', 'microthemer')
);
$propertyOptions['shadow']['box_shadow_y'] = array(
	'sh' => array('box-shadow', 1, array(
		'onlyShort' => 1,
		'initial' => 0
		// required
	)),
	'short_label' => esc_html__('Y', 'microthemer'),
	'label' => esc_attr__('Box Shadow y-offset', 'microthemer'),
	'field-class' => 'icon-size-3',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $box_shadow_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'box_shadow',
		'min' => ''
	),
	'unit_rel' => 1,
	'input-class' => 'size-0b',
	'hide imp' => 1,
	'icon' => '35',
	// ref
	'ref_desc' => "<p>The position of the vertical shadow. Negative values are allowed.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '-10' would create a 10 pixel shadow above the element. Other commonly used units include 'em' and '%'. So you could enter '-2%'."
	),
	'ref_links' => $propertyOptions['shadow']['box_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['box_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['box_shadow_x']['sub_label']
);

$propertyOptions['shadow']['box_shadow_blur'] = array(
	'sh' => array('box-shadow', 2, array(
		'onlyShort' => 1,
		//'optional' => 1, // required (for MT) otherwise spread can be interpreted as blur - todo check this is true
		'initial' => 0
	)),
	'short_label' => esc_html_x('Blur', 'noun', 'microthemer'),
	'label' => esc_attr__('Box Shadow Blur', 'microthemer'),
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $shadow_blur,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'box_shadow'
	),
	'unit_rel' => 1,
	'field-class' => 'icon-size-2',
	'input-class' => 'size-0b',
	'hide imp' => 1,
	'icon' => '37',
	// ref
	'ref_desc' => "<p>The blur distance. If you defined a black shadow and a blur of 10px, their would be a 10 pixel blur area where the shadow faded evenly from black to transparent at every extremity.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '10' would create a 10 pixel blur at the edge of the element's shadow (Microthemer automatically adds the 'px' unit if a unit isn't specified. Other commonly used units include 'em' and '%'. So you could enter '5%'.)"
	),
	'ref_links' => $propertyOptions['shadow']['box_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['box_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['box_shadow_x']['sub_label']
);

$propertyOptions['shadow']['box_shadow_spread'] = array(
	'sh' => array('box-shadow', 3, array(
		'onlyShort' => 1,
		//'optional' => 1,
		'initial' => 0
		// optional, but onlyShort so no initial needed
	)),
	'short_label' => esc_html_x('Spread', 'noun', 'microthemer'),
	'label' => esc_attr__('Box Shadow Spread', 'microthemer'),
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $box_shadow_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'box_shadow',
		'min' => ''
	),
	'unit_rel' => 1,
	'input-class' => 'size-0b',
	'icon' => '31,4',
	'hide imp' => 1,
	// ref
	'ref_desc' => "<p>The size of the shadow. If X and Y offsets are set to 0, setting a positive spread will result in an even shadow on all sides. Negative values are also permitted.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '20' would create a 20 pixel shadow surrounding the element."
	),
	'ref_links' => $propertyOptions['shadow']['box_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['box_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['box_shadow_x']['sub_label']
);

$propertyOptions['shadow']['box_shadow_color'] = array(
	'sh' => array('box-shadow', 4, array(
		'onlyShort' => 1,
		'optional' => 1,
		'initial' => ''
		// optional, but onlyShort so no initial needed
	)),
	'short_label' => esc_html_x('Color', 'noun', 'microthemer'),
	'label' => esc_attr__('Box Shadow Color', 'microthemer'),
	'field-class' => 'icon-size-4 is-picker',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'root_cat' => 'color'
	),
	'hide imp' => 1,
	'icon' => '36',
	// ref
	'ref_desc' => "<p>The color of the shadow. Choosing a darker version of the parent element's background color produces the most natural effect.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the Color text field to reveal the color picker."
	),
	'ref_links' => $propertyOptions['shadow']['box_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['box_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['box_shadow_x']['sub_label']
);

$propertyOptions['shadow']['box_shadow_inset'] = array(
	'sh' => array('box-shadow', 5, array(
		'onlyShort' => 1,
		'initial' => '',
		//'binary' => 1,
		'optional' => 1 // take care with this when converting longhand to shorthand
		// optional, but onlyShort so no initial needed
	)),
	'short_label' => esc_html_x('Inset', 'noun', 'microthemer'),
	'label' => esc_attr__('Box Shadow Inset', 'microthemer'),
	//'input-class' => 'size-0b',
	'field-class' => 'last',
	'last_in_sub' => 1,
	'important_carrier' => 1,
	'type' => 'combobox',
	// ref
	'select_options' => array(
		" ",
		"inset"
	),
	'icon' => '30, 4',
	// ref
	'ref_desc' => "<p>If inset is defined, the box-shadow will be an inner shadow rather than an outer shadow.</p>",
	'ref_values' => array(
		"outset" => "Defines an outer box-shadow. This is default.",
		"inset" => "Defines an inner box-shadow."
	),
	'ref_links' => $propertyOptions['shadow']['box_shadow_x']['ref_links'],
	'tutorials' => $propertyOptions['shadow']['box_shadow_x']['tutorials'],
	'related_tutorials' => $propertyOptions['shadow']['box_shadow_x']['sub_label']
);

// background
// ------------------------------------------------------------

$propertyOptions['background']['background_color'] = array(
	'sh' => array('background', 0, array(
		'initial' => 'transparent',
		'optional' => 1
		//'wl' => '[-a-z%:#,\'"\\/\\.\\d\\(\\)]+' // background props white list (matches any bg prop value)
	)),
	'animatable' => 1,
	'short_label' => esc_html__('Color', 'noun', 'microthemer'),
	'label' => esc_attr__('Background Color', 'microthemer'),
	'pg_label' => esc_attr__('Background', 'microthemer'),
	'sub_label' => esc_html__('Background', 'microthemer'),
	'sub_slug' => 'background',
	'field-class' => 'is-picker',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'color'
	),
	'icon' => '25',
	// ref
	'ref_desc' => "<p>The background-color property sets the background color of an element.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the Color text field to reveal the color picker."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/background-color/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/background-color',
		'quackit' => 'http://www.quackit.com/css/properties/css_background-color.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_background-color.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.cssbasics.com/chapter_12_css_backgrounds.html',
			'title' => 'CSS Backgrounds',
		),
		array(
			'url' => 'http://tutorials.jenkov.com/css/background-image.html',
			'title' => 'CSS Background Images',
		),
		array(
			'url' => 'http://learn.shayhowe.com/html-css/setting-backgrounds-and-gradients/#background-color',
			'title' => 'Adding a background color',
		),
		array(
			'url' => 'http://www.quackit.com/css/tutorial/css_background_code.cfm',
			'title' => 'CSS Background Code',
		),
		array(
			'url' => 'https://css-tricks.com/perfect-full-page-background-image/',
			'title' => 'Perfect Full Page Background Image',
		),
		array(
			'url' => 'http://www.css3.info/preview/background-origin-and-background-clip/',
			'title' => 'CSS3: background-origin and background-clip',
		),
		array(
			'url' => 'http://www.css3.info/preview/background-size/',
			'title' => 'Background-size',
		),
		array(
			'url' => 'http://www.css3.info/preview/multiple-backgrounds/',
			'title' => 'Multiple Backgrounds with CSS3',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['background']['background_image'] = array(
	'sh' => array('background', 1, array(
		'initial' => 'none',
		'optional' => 1
	)),
	'multiple' => 1,
	'short_label' => esc_html_x('Image', 'noun', 'microthemer'),
	'label' => esc_attr__('Background Image', 'microthemer'),
	//'type' => 'combobox',
	'field-class' => 'last span-3 grid-large size-very-big',
	'input-class' => 'bg-image-select',
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	// we have slider for adjusting css gradient values
	//'default_unit' => '', // enable this if we provide some starter gradients
	'potential_gradient' => 1,
	/*'select_options' => array(
		'none'
	),*/
	'icon' => '39, 14',
	// ref
	'ref_desc' => "<p>The background-image property sets the background image for an element.</p>",
	'ref_values' => array(
		"(image)" => "Microthemer lists all the images contained within micro themes in a dropdown menu. You can also click the 'view images' link at the top right of the images menu to browse the images visually. Tip: the image slidehow will start from the image selected in the menu and will iterate through in the same order."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/background-image/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/background-image',
		'quackit' => 'http://www.quackit.com/css/properties/css_background-image.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_background-image.asp',
	),
	'tutorials' => $propertyOptions['background']['background_color']['tutorials'],
	'group_tutorials' => 1
);


$propertyOptions['background']['background_position'] = array(
	'sh' => array('background', 4, array(
		'initial' => '0% 0%', // todo pattern match 0|0 0|0% 0%
		'optional' => 1
	)),
	'multiple' => 1,
	'animatable' => 1,
	'short_label' => esc_html_x('Position', 'noun', 'microthemer'),
	'label' => esc_attr__('Background Position', 'microthemer'),
	//'type' => 'combobox',
	'field-class' => 'grid-large',
	'input-class' => 'bg-position-select size-6a',
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => array('width', 'height')
		)
	),
	'default_unit' => 'px',
	'sug_values' => array(
		'this' => 1,
		'min' => ''
		//'no_slider' => 1 // until we have posX/posY
		//'forceSug' => 1
	),
	'select_options' => $background_position_values,
	'icon' => '3, 14',
	// ref
	'ref_desc' => "<p>The background-position property sets the starting position of a background image.</p>
<p>You can enter two values separated by a space. The first value will determine the horizontal (x-axis) position of the background image. The second value wil determine the vertical (y-axis) position of the background image. Microthemer will default to 'px' if no unit is specified, you can also use '%' or 'em' though.</p>",
	'ref_values' => array(
		"left top" => "The left and top edges of the background image are flush against the left and top edges of the element. This is default",
		"left center" => "The left edge of the background image is flush against the left edge of the element and is vertically centered",
		"left bottom" => "The left and bottom edges of the background image are flush against the left and bottom edges of the element",
		"right top" => "The right and top edges of the background image are flush against the right and top edges of the element",
		"right center" => "The right edge of the background image is flush against the right edge of the element and is vertically centered",
		"right bottom" => "The right and bottom edges of the background image are flush against the right and bottom edges of the element",
		"center top" => "The top edge of the background image is flush against the top edge of the element and is horizontally centered",
		"center center" => "The center of the background image is aligned with the center of the element",
		"center bottom" => "The bottom edge of the background image is flush against the bottom edge of the element and is horizontally centered"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/background-position/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/background-position',
		'quackit' => 'http://www.quackit.com/css/properties/css_background-position.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_background-position.asp',
	),
	'tutorials' => $propertyOptions['background']['background_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['background']['background_repeat'] = array(
	'sh' => array('background', 2, array(
		'initial' => 'repeat',
		'optional' => 1
	)),
	'multiple' => 1,
	'short_label' => esc_html_x('Repeat', 'noun', 'microthemer'),
	'label' => esc_attr__('Background Repeat', 'microthemer'),
	'input-class' => 'size-3a',
	'type' => 'combobox',
	'select_options' => $background_repeat_values,
	'icon' => '1, 14',
	// ref
	'ref_desc' => "<p>The background-repeat property sets if/how a background image will be repeated.</p>",
	'ref_values' => array(
		"repeat" => "The background image will be repeated both vertically and horizontally. This is default",
		"repeat-x" => "The background image will be repeated only horizontally",
		"repeat-y" => "The background image will be repeated only vertically",
		"no-repeat" => "The background-image will not be repeated"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/background-repeat/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/',
		'quackit' => 'http://www.quackit.com/css/properties/css_background-repeat.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_background-repeat.asp',
	),
	'tutorials' => $propertyOptions['background']['background_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['background']['background_attachment'] = array(
	'sh' => array('background', 3, array(
		'initial' => 'scroll',
		'optional' => 1
	)),
	'multiple' => 1,
	'short_label' => esc_html__('Attachment', 'microthemer'),
	'label' => esc_attr__('Background Attachment', 'microthemer'),
	'type' => 'combobox',
	'input-class' => 'size-0',
	'select_options' => array(
		'scroll',
		'fixed'
	), 'icon' => '2, 14',
	// ref
	'ref_desc' => "<p>The background-attachment property sets whether a background image is
fixed or scrolls with the rest of the page.</p>",
	'ref_values' => array(
		"scroll" => "The background image scrolls with the rest of the page. This is default",
		"fixed" => "The background image is fixed"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=background-img-opts',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/background-attachment/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/background-attachment',
		'quackit' => 'http://www.quackit.com/css/properties/css_background-attachment.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_background-attachment.asp',
	),
	'tutorials' => $propertyOptions['background']['background_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['background']['background_size'] = array(
	'sh' => array('background', 5, array(
		'initial' => 'auto',
		'optional' => 1
	)),
	'multiple' => 1,
	'animatable' => 1,
	'short_label' => esc_html_x('Size', 'noun', 'microthemer'),
	'label' => esc_attr__('Background Size', 'microthemer'),
	//'type' => 'combobox',
	'input-class' => 'size-3a',
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => array('width', 'height')
		)
	),
	'default_unit' => 'px',
	'sug_values' => array(
		'this' => 1,
		//'no_slider' => 1
		//'forceSug' => 1
	),
	'select_options' => $background_size_values,
	'field-class' => 'last',
	'icon' => '25, 14',
	// ref
	'ref_desc' => "<p>The background-size property controls the size of the background image.<p>",
	'ref_values' => array(
		"auto" => "The background image is actual size (default).",
		"cover" => "Set the background image to cover the element's entire area. The image retains it's proportions and so is cropped if necessary.",
		"contain" => "Scale the image to the largest size such that both its width and its height can fit inside the content area.",
		"numeric" => "Specify two values separated by a space. The first is the width, the second is the height. px and ems etc can be used for the units. If you specify percentage units, this is calculated based on the width/height of the element.",
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/background-size/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/background-size',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_background-size.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_background-size.asp',
	),
	'tutorials' => $propertyOptions['background']['background_color']['tutorials'],
	'group_tutorials' => 1
);

// recently introduced
$propertyOptions['background']['background_origin'] = array(
	'sh' => array('background', 6, array(
		'initial' => 'padding-box',
		'optional' => 1
	)),
	'multiple' => 1,
	'short_label' => esc_html_x('Origin', 'noun', 'microthemer'),
	'label' => esc_attr__('Background Origin', 'microthemer'),
	'type' => 'combobox',
	'field-class' => 'grid-large',
	'input-class' => 'size-5',
	'select_options' => array(
		'border-box',
		'padding-box',
		'content-box'
	),
	'icon' => '26, 17',
	// ref
	'ref_desc' => "<p>The background-origin property specifies where the background image is positioned.<p>",
	'ref_values' => array(
		"padding-box" => "The default. The background image starts from the upper left corner of the padding edge.",
		"border-box" => "The background image starts from the upper left corner of the border.",
		"content-box" => "The background image starts from the upper left corner of the content."
	),
	'tutorials' => $propertyOptions['background']['background_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['background']['background_clip'] = array(
	'sh' => array('background', 7, array(
		'initial' => 'border-box',
		'optional' => 1
	)),
	'multiple' => 1,
	'short_label' => esc_html_x('Clip', 'noun', 'microthemer'),
	'label' => esc_attr__('Background Clip', 'microthemer'),
	'type' => 'combobox',
	'field-class' => 'grid-large',
	'input-class' => 'size-5',
	'last_in_sub' => 1,
	'select_options' => array(
		'border-box',
		'padding-box',
		'content-box',
		'text'
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-background-clip',
		)
	),
	'icon' => '26, 14',
	// ref
	'ref_desc' => "<p>The background-clip property controls the painting area of the background.<p>",
	'ref_values' => array(
		"border-box" => "The background is clipped at the outer edge of the border (default). The extent of the fill to the outer edge of the border is only evident if the border isn't solid e.g. dashed",
		"padding-box" => "The background is clipped at the outer edge of the padding (it doesn't show behind the border).",
		"content-box" => "The background is clipped at the outer edge of the content (it doesn't show behind the padding)."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=background-img-opts',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/background-clip/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/background-clip',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_background-clip.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_background-clip.asp',
	),
	'tutorials' => $propertyOptions['background']['background_color']['tutorials'],
	'group_tutorials' => 1
);

/* - include this once I've set up the a horizontal scroll system like with the breadcrumbs - use HTML DnD API for smoothness
$propertyOptions['background']['background_origin'] = array(
	'short_label' => 'Origin',
	'label' => 'Background Origin',
	'type' => 'combobox',
	'select_options' => array(
		'border-box',
		'padding-box',
		'content-box'
	));
*/

// gradient
// ------------------------------------------------------------

$propertyOptions['gradient']['gradient_angle'] = array(
	'sh' => array('linear-gradient', 0, array(
		'initial' => '180deg',
		'onlyShort' => 1,
		'optional' => 1
	)),
	//'sh' => array('gradient', 0),
	'short_label' => esc_html__('Gradient Angle', 'microthemer'),
	'label' => esc_attr__('Gradient Angle', 'microthemer'),
	'pg_label' => esc_attr__('Gradient', 'microthemer'),
	'sub_label' => esc_html__('Gradient', 'microthemer'),
	'sub_slug' => 'gradient',
	'field-class' => 'icon-size-2 last grid-large',
	'input-class' => 'size-big',
	'type' => 'combobox',
	"select_options" => array(
		"top to bottom",
		"bottom to top",
		"left to right",
		"right to left",
		"top left to bottom right",
		"bottom right to top left",
		"top right to bottom left",
		"bottom left to top right"
	),
	'hide imp' => 1,
	'icon' => '28, 1',
	// ref
	'ref_desc' => "<p>The angle of the gradient. You have 8 options which cover all possible horizontal, vertical and perfectly diagonal variations. Note: old versions of Safari and Chrome (< Chrome 10 and < Safari 5.1) don't properly support diagonal gradients.<p>",
	'ref_values' => array(
		"top to bottom" => "Gradient A blending from top to bottom into Gradient B or C.",
		"bottom to top" => "Gradient A blending from bottom to top into Gradient B or C.",
		"left to right" => "Gradient A blending from left to right into Gradient B or C.",
		"right to left" => "Gradient A blending from right to left into Gradient B or C.",
		"top left to bottom right" => "Gradient A blending from top left to bottom right into Gradient B or C.",
		"bottom right to top left" => "Gradient A blending from bottom right to top left into Gradient B or C.",
		"top right to bottom left" => "Gradient A blending from top right to bottom left into Gradient B or C.",
		"bottom left to top right" => "Gradient A blending from bottom left to top right into Gradient B or C."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=css-gradients',
		'css_tricks' => 'https://css-tricks.com/css3-gradients/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/linear-gradient',
		'quackit' => 'http://www.quackit.com/css/css3/gradients/linear_gradients.cfm',
		'w3s' => 'http://www.w3schools.com/css/css3_gradients.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.cssmatic.com/gradient-generator',
			'title' => 'CSSmatic Gradient Generator',
			'tool' => 1
		),
		array(
			'url' => 'http://www.colorzilla.com/gradient-editor/',
			'title' => 'Ultimate CSS Gradient Generator',
			'tool' => 1
		),
		array(
			'url' => 'http://xahlee.info/js/css_gradient.html',
			'title' => 'CSS Linear Gradient Tutorial',
		),
		array(
			'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Images/Using_CSS_gradients',
			'title' => 'Using CSS gradients',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['gradient']['gradient_a'] = array(
	'sh' => array('linear-gradient', 1, array(
		'initial' => 'transparent',
		'onlyShort' => 1,
	)),
	'short_label' => 'A color',
	'label' => esc_attr__('Gradient A', 'microthemer'),
	'field-class' => 'is-picker',
	'input-class' => 'color mt-color-picker',
	// note, may need to do some sug_values stuff here if just parsing 'background' isn't the best way to get gradient colors
	'hide imp' => 1,
	'icon' => '25',
	'icon-name' => 'background-color',
	// ref
	'ref_desc' => "<p>One of the color stops in the linear gradient. The color you specify here will gradually blend into the color you specify for Gradient B if you specify one. If you don't specify a color for Gradient B, Gradient A will blend into Gradient C.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the 'Gradient A' text field to reveal the color picker."
	),
	'tutorials' => $propertyOptions['gradient']['gradient_angle']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['gradient']['gradient_b'] = array(
	'sh' => array('linear-gradient', 2, array(
		'initial' => 'transparent',
		//'optional' => 1,
		'onlyShort' => 1,
	)),
	'short_label' => 'B color',
	'label' => esc_attr__('Gradient B', 'microthemer'),
	'field-class' => 'is-picker',
	'input-class' => 'color mt-color-picker',
	'hide imp' => 1,
	'icon' => '25',
	'icon-name' => 'background-color',
	// ref
	'ref_desc' => "<p>One of the color stops in the linear gradient. The color you specify here will gradually blend into Gradient A and C. <b>Gradient B is optional</b>. If you just want to blend 2 colors, you only need to specify a value for Grandient A and C. If you do use it, there is an additional option for Gradient B: 'B Position (optional)'. Click the label for this option for details.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the 'Gradient B' text field to reveal the color picker."
	),
	'tutorials' => $propertyOptions['gradient']['gradient_angle']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['gradient']['gradient_b_pos'] = array(
	'sh' => array('linear-gradient', 3, array(
		'initial' => '50%',
		'onlyShort' => 1,
		'optional' => 1
	)),
	'short_label' => esc_html__('B Position', 'microthemer'),
	'label' => esc_attr__('Gradient B Position', 'microthemer'),
	'field-class' => 'icon-size-0b icon-size-2',
	'select_options' => array(
		'10%',
		'25%',
		'50%',
		'75%',
	),
	'auto' => array(
		'%' => 'Pass, this one is tricky.'
	),
	'default_unit' => 'px',
	'sug_values' => array(
		'this' => 1, // just quick fix for making menu wide enough for slider
	),
	'hide imp' => 1,
	'icon' => '40, 14',
	'icon-name' => 'position',
	// ref
	'ref_desc' => "<p>The position of Gradient B in relation to Gradient A. Gradient B is the middle color of the gradient, but it doesn't have to appear exactly in between Gradient A and C. If you were to specify a 'B Position' of 10% (for instance) Gradient B would begin almost immediately after Gradient A. If you were to specify a 'B Position' of 90%, Gradient B wouldn't begin until just before Gradient C. If you leave this setting blank, or specify a value of 50% Gradient B, will be placed exactly between Gradient A and C.</p>",
	'ref_values' => array(
		"(numeric)" => "For consistency, the default unit is pixels. So if you enter 25 it will default to 25px. But it's more common to specify gradient positions in percentages. So you might put 15% (specifying the unit so it doesn't default to pixels)"
	),
	'tutorials' => $propertyOptions['gradient']['gradient_angle']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['gradient']['gradient_c'] = array(
	'sh' => array('linear-gradient', 4, array(
		'initial' => 'transparent',
		'onlyShort' => 1,
		'optional' => 1
	)),
	'short_label' => 'C color',
	'label' => esc_attr__('Gradient C', 'microthemer'),
	'field-class' => 'is-picker',
	'input-class' => 'color mt-color-picker',
	'icon' => '25',
	'icon-name' => 'background-color',
	'important_carrier' => 1,
	// ref
	'ref_desc' => "<p>One of the color stops in the linear gradient. The color you specify here will gradually blend into the color you specify for Gradient B if you specify one. If you don't specify a color for Gradient B, Gradient C will blend into Gradient A.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the 'Gradient C' text field to reveal the color picker."
	),
	'tutorials' => $propertyOptions['gradient']['gradient_angle']['tutorials'],
	'group_tutorials' => 1
);


// Masks
// ------------------------------------------------------------

$propertyOptions['mask']['mask_image'] = array(
	'short_label' => esc_html__('Image', 'microthemer'),
	'label' => esc_attr__('Mask Image', 'microthemer'),
	'pg_label' => esc_attr__('Shapes & Masks', 'microthemer'),
	'sub_label' => esc_html__('Mask', 'microthemer'),
	'sub_slug' => 'mask',
	'field-class' => 'grid-large mt-size-quite-big', // size-very-big
	'input-class' => 'bg-image-select mask-image-select',
	'multiple' => 1,
	// select options are generated with build script reading stock-min folder
	//'type' => 'combobox',
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	// we have slider for adjusting css gradient values
	'default_unit' => '',
	'special_units' => $unitless_too,
	'prefixes' => array(
		'property' => array(
			'-webkit-mask-image',
		)
	),
	// ref
	'ref_desc' => "<p>The mask-image property allows you to cut shapes out of an element and/or apply fades. Microthemer provides some stock SVG masks that you can select from the dropdown menu.</p>",
	'ref_values' => array(
		"none" => "No mask is applied. This is the defualt.",
		"[mask source]" => "E.g. url(my-mask.svg) or a CSS image such as a linear-gradient",
		"[image]" => "E.g. image(url(mask.png), skyblue), linear-gradient(rgba(0, 0, 0, 1.0), transparent)."
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=mask-image',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/mask-image/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/mask-image',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_mask-image.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://css-tricks.com/clipping-masking-css/',
			'title' => 'Clipping and Masking in CSS',
		),
		array(
			'url' => 'https://web.dev/css-masking/',
			'title' => 'Apply effects to images with CSS\'s mask-image property',
		),
		array(
			'url' => 'https://www.digitalocean.com/community/tutorials/css-masking-with-mask-image',
			'title' => 'Masking Images in CSS Using the mask-image Property',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['mask']['mask_position'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Position', 'microthemer'),
	'label' => esc_attr__('Mask Position', 'microthemer'),
	//'field-class' => 'grid-large position-very-big',
	//'input-class' => 'bg-image-select',
	'select_options' => $background_position_values,
	'multiple' => 1,
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => array('width', 'height')
		)
	),
	'default_unit' => 'px',
	'sug_values' => array(
		'root_cat' => 'background_position',
		'min' => ''
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-mask-position',
		)
	),
	// ref
	'ref_desc' => "<p>The mask-position CSS property sets the initial position, relative to the mask position layer set by mask-origin, for each defined mask image.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=mask-position',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/mask-position/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/mask-position',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_mask-position.asp',
	),
	'group_tutorials' => 1
);

$propertyOptions['mask']['mask_repeat'] = array(
	'short_label' => esc_html__('Repeat', 'microthemer'),
	'label' => esc_attr__('Mask Repeat', 'microthemer'),
	'type' => 'combobox',
	'input-class' => 'size-5',
	'multiple' => 1,
	'select_options' => array_merge($background_repeat_values, array(
		'space',
		'round',
		'repeat space',
		'no-repeat round',
	)),
	'prefixes' => array(
		'property' => array(
			'-webkit-mask-repeat',
		)
	),
	// ref
	'ref_desc' => "<p>The mask-repeat CSS property sets how mask images are repeated. A mask image can be repeated along the horizontal axis, the vertical axis, both axes, or not repeated at all.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=mask-repeat',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/mask-repeat/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/mask-repeat',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_mask-repeat.asp',
	),
	'group_tutorials' => 1
);

$propertyOptions['mask']['mask_size'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Size', 'microthemer'),
	'label' => esc_attr__('Mask Size', 'microthemer'),
	//'field-class' => 'grid-large size-very-big',
	//'input-class' => 'bg-image-select',
	'select_options' => $background_size_values,
	'multiple' => 1,
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => array('width', 'height')
		)
	),
	'default_unit' => 'px',
	'sug_values' => array(
		'this' => 1,
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-mask-size',
		)
	),
	// ref
	'ref_desc' => "<p>The mask-size CSS property specifies the sizes of the mask images. The size of the image can be fully or partially constrained in order to preserve its intrinsic ratio.</p>",
	'ref_values' => array(
		"auto" => "The mask image maintains its intrinsic proportion.",
		"contain" => "The mask image is letterboxed within the container. ",
		"cover" => "The mask image covers the entire width or height of the container and is clipped if necessary",
		"numeric" => "Specify two values separated by a space. The first is the width, the second is the height.",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=mask-size',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/mask-size/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/mask-size',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_mask-size.asp',
	),
	'group_tutorials' => 1
);

$propertyOptions['mask']['mask_origin'] = array(
	'short_label' => esc_html__('Origin', 'microthemer'),
	'label' => esc_attr__('Mask Origin', 'microthemer'),
	'type' => 'combobox',
	'field-class' => 'mt-size-medium',
	'input-class' => 'size-5',
	'multiple' => 1,
	'select_options' => $geometry_box_values,
	'prefixes' => array(
		'property' => array(
			'-webkit-mask-origin',
		)
	),
	// ref
	'ref_desc' => "<p>The mask-origin CSS property sets where the mask should start. This can be at the top left edge of the border, padding, or content.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=mask-origin',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/mask-origin/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/mask-origin',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_mask-origin.asp',
	),
	'group_tutorials' => 1
);

$propertyOptions['mask']['mask_clip'] = array(
	'short_label' => esc_html__('Clip', 'microthemer'),
	'label' => esc_attr__('Mask Clip', 'microthemer'),
	'type' => 'combobox',
	'field-class' => 'mt-size-medium',
	'multiple' => 1,
	'select_options' => array_merge($geometry_box_values, array(
		'no-clip'
	)),
	'prefixes' => array(
		'property' => array(
			'-webkit-mask-clip',
		)
	),
	// ref
	'ref_desc' => "<p>The mask-clip CSS property sets where the mask should be clipped. This can be at the top left edge of the border, padding, or content, or not at all.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=mask-clip',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/mask-clip/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/mask-clip',
		//'w3s' => 'https://www.w3schools.com/cssref/css3_pr_mask-clip.asp', // not done yet
	),
	'group_tutorials' => 1
);

$propertyOptions['mask']['clip_path'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Clip Path', 'microthemer'),
	'label' => esc_attr__('Clip Path', 'microthemer'),
	'sub_label' => esc_html__('Shape', 'microthemer'),
	'sub_slug' => 'shape',
	'field-class' => 'grid-large mt-size-quite-big',
	'last_in_sub' => 1,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'default_unit' => '',
	'special_units' => $unitless_too,
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => array('width', 'height')
		)
	),

	'icon' => '32, 14',
	// ref
	'ref_desc' => "<p>The clip-path CSS property creates a clipping region that sets what part of an element should be shown. Parts that are inside the region are shown, while those outside are hidden.</p>",
	
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=clip-path',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/c/clip-path/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/clip-path',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_clip-path.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://ishadeed.com/article/clip-path/',
			'title' => 'Understanding Clip Path in CSS',
		),
		array(
			'url' => 'https://bennettfeely.com/clippy/',
			'title' => 'Clippy - CSS clip-path generator',
		),
		array(
			'url' => 'https://css-tricks.com/animating-with-clip-path/',
			'title' => 'Animating with Clip-Path',
		),
		array(
			'url' => 'https://firefox-source-docs.mozilla.org/devtools-user/page_inspector/how_to/edit_css_shapes/index.html',
			'title' => 'How to use Firefox\'s Shape Path Editor',
		),

		//
	)
);

$propertyOptions['mask']['shape_outside'] = array(
	'short_label' => esc_html__('Shape Outside', 'microthemer'),
	'label' => esc_attr__('Shape Outside', 'microthemer'),
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => array('width', 'height')
		)
	),
	'default_unit' => '',
	'special_units' => $unitless_too,
	'sug_values' => array(
		'root_cat' => 'clip_path',
		'min' => ''
	),
	'icon' => '32, 14',
	// ref
	'ref_desc' => "<p>The shape-outside CSS property defines a shape, which may be non-rectangular, around which adjacent inline content should wrap. By default, inline content wraps around its margin box; shape-outside provides a way to customize this wrapping, making it possible to wrap text around complex objects rather than simple boxes. Note, shape-outside only works when an element is floated next to text.</p>",

	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=shape-outside',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/s/shape-outside/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/shape-outside',
	),
	'tutorials' => array(
		array(
			'url' => 'https://www.html5rocks.com/en/tutorials/shapes/getting-started/',
			'title' => 'Getting Started with CSS Shapes',
		),
		array(
			'url' => 'https://www.smashingmagazine.com/2018/09/css-shapes/',
			'title' => 'Take A New Look At CSS Shapes',
		),
		array(
			'url' => 'https://bennettfeely.com/clippy/',
			'title' => 'Clippy - CSS clip-path generator',
		),
		array(
			'url' => 'https://firefox-source-docs.mozilla.org/devtools-user/page_inspector/how_to/edit_css_shapes/index.html',
			'title' => 'How to use Firefox\'s Shape Path Editor',
		),
	)
);

//wp_die('$margin_lengths: <pre>' . print_r($margin_lengths, true) . '</pre>');

$propertyOptions['mask']['shape_margin'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Shape Margin', 'microthemer'),
	'label' => esc_attr__('Shape Margin', 'microthemer'),
	'select_options' => $margin_lengths,
	'sug_values' => array(
		//'this' => 1,
		'root_cat' => 'spacing',
		'min' => ''
	),
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'icon' => '32, 14',
	// ref
	'ref_desc' => "<p>The shape-margin CSS property sets a margin for a CSS shape created using shape-outside. The margin lets you adjust the distance between the edges of the shape (the <b>float</b> element) and the surrounding content. Note, you will often want to use the regular margin property instead of this.</p>",

	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=shape-margin',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/s/shape-margin/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/shape-margin',
	),

);

$propertyOptions['mask']['shape_image_threshold'] = array(
	'short_label' => esc_html__('Image Threshold', 'microthemer'),
	'label' => esc_attr__('Shape Image Threshold', 'microthemer'),
	'select_options' => $opacity,
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
		//'root_cat' => 'clip_path',
		'max' => array(
			'' => 1,
			'%' => 100
		)
	),
	'icon' => '32, 14',
	// ref
	'ref_desc' => "<p>The shape-image-threshold CSS property sets the alpha channel threshold used to extract the shape using an image as the value for shape-outside. Any pixels whose alpha component's value is greater than the threshold are considered to be part of the shape for the purposes of determining its boundaries. For example, a value of 0.5 means that the shape will enclose all the pixels that are more than 50% opaque.</p>",

	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=shape-image-threshold',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/s/shape-image-threshold/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/shape-image-threshold',
	),

);


// Filters
// ------------------------------------------------------------

$blur = $padding_lengths;

$filter_tutorials = array(
	array(
		'url' => 'https://css-tricks.com/almanac/properties/f/filter/',
		'title' => 'CSS Tricks: Filters',
	),
	array(
		'url' => 'https://www.html5rocks.com/en/tutorials/filters/understanding-css/',
		'title' => 'Understanding CSS Filter Effects',
	),
	array(
		'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter',
		'title' => 'Mozilla: Filters',
	),
	array(
		'url' => 'https://www.w3schools.com/cssref/css3_pr_filter.asp',
		'title' => 'W3Schools: Filters',
	),
	array(
		'url' => 'https://caniuse.com/#feat=css-filters',
		'title' => 'Can I use: CSS Filter Effects',
	),
);

// blur
$propertyOptions['filter']['blur_function'] = array(
	'short_label' => esc_html__('Blur', 'microthemer'),
	'label' => esc_attr__('Blur', 'microthemer'),
	'pg_label' => esc_attr__('Filter', 'microthemer'),
	'sub_label' => esc_html__('Filter', 'microthemer'),
	'last_in_sub' => 1,
	'css_func' => 0,
	'default_unit' => 'px',
	'sub_slug' => 'filter',
	'field-class' => '',
	'input-class' => 'size-0',
	'sug_values' => array(
		'this' => 1,
	),
	'select_options' => $blur,
	'icon' => '14, 4, B',
	// ref
	'ref_desc' => "<p>The blur() CSS function applies a Gaussian blur to the input image</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/blur',
		'quackit' => 'https://qhmit.com/css/functions/css_blur_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// brightness
$propertyOptions['filter']['brightness_function'] = array(
	'short_label' => esc_html__('Brightness', 'microthemer'),
	'label' => esc_html__('Brightness', 'microthemer'),
	'css_func' => 1,
	'field-class' => '',
	'input-class' => 'size-0b',
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
		//'unitless_slider' => 1
	),
	'select_options' => $brightness,
	'icon' => '15, 4, B',
	// ref
	'ref_desc' => "<p>The brightness() CSS function applies a linear multiplier to the input image, making it appear brighter or darker.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/brightness',
		'quackit' => 'https://qhmit.com/css/functions/css_brightness_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// contrast
$propertyOptions['filter']['contrast_function'] = array(
	'short_label' => esc_html__('Contrast', 'microthemer'),
	'label' => esc_html__('Contrast', 'microthemer'),
	'css_func' => 1,
	'field-class' => '',
	'input-class' => 'size-0b',
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
		//'unitless_slider' => 1
	),
	'select_options' => $brightness,
	'icon' => '16, 4, B',
	// ref
	'ref_desc' => "<p>The contrast() CSS function adjusts the contrast of the input image.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/contrast',
		'quackit' => 'https://qhmit.com/css/functions/css_contrast_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// drop_shadow
$propertyOptions['filter']['drop_shadow_function'] = array(
	'short_label' => esc_html__('Drop shadow', 'microthemer'),
	'label' => esc_html__('Drop shadow', 'microthemer'),
	'css_func' => '0 0',
	'field-class' => 'icon-size-4',
	'input-class' => 'size-big',
	'default_unit' => 'px',
	'sug_values' => array(
		'this' => 1,
		//'no_slider' => 1
	),
	'select_options' => array(
		'0 0 4px rgba(0,0,0,.3)',
		'0 0 20px rgba(0,0,0,.3)',
		'30px 30px 30px rgba(0,0,0,.3)'
	),
	'icon' => '37, 11',
	'icon-name' => 'shadow',
	// ref
	'ref_desc' => "<p>Unlike the box-shadow property, which creates a rectangular shadow behind an element's entire box, the drop-shadow() filter function creates a shadow that conforms to the shape (alpha channel) of the image itself</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/drop-shadow',
		'quackit' => 'https://qhmit.com/css/functions/css_drop-shadow_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// grayscale
$propertyOptions['filter']['grayscale_function'] = array(
	'short_label' => esc_html__('Grayscale', 'microthemer'),
	'label' => esc_html__('Grayscale', 'microthemer'),
	'css_func' => 0,
	'field-class' => '',
	'input-class' => 'size-0b',
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
		'max' => array(
			'' => 1,
			'%' => 100
		)
	),
	'select_options' => $grayscale,
	'icon' => '17, 4, B',
	// ref
	'ref_desc' => "<p>The grayscale() CSS function adjusts the grayscale of the input image.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/grayscale',
		'quackit' => 'https://qhmit.com/css/functions/css_grayscale_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// hue-rotate
$propertyOptions['filter']['hue_rotate_function'] = array(
	'short_label' => esc_html__('Hue Rotate', 'microthemer'),
	'label' => esc_html__('Hue Rotate', 'microthemer'),
	'css_func' => '0deg',
	'field-class' => '',
	'input-class' => 'size-1',
	'default_unit' => 'deg',
	'special_units' => $angle_units,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'select_options' => $degrees,
	'icon' => '18, 4, B',
	// ref
	'ref_desc' => "<p>Applies a hue rotation on the image. The value defines the number of degrees around the color circle the image samples will be adjusted. 0deg is default, and represents the original image.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/hue-rotate',
		'quackit' => 'https://qhmit.com/css/functions/css_hue-rotate_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// invert
$propertyOptions['filter']['invert_function'] = array(
	'short_label' => esc_html__('Invert', 'microthemer'),
	'label' => esc_html__('Invert', 'microthemer'),
	'css_func' => 0,
	'field-class' => '',
	'input-class' => 'size-0b',
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
		'max' => array(
			'' => 1,
			'%' => 100
		)
	),
	'select_options' => $invert,
	'icon' => '19, 4, B',
	// ref
	'ref_desc' => "<p>The invert() CSS function inverts the color samples in the input image</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/invert',
		'quackit' => 'https://qhmit.com/css/functions/css_invert_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// opacity
$propertyOptions['filter']['opacity_function'] = array(
	'short_label' => esc_html__('Opacity', 'microthemer'),
	'label' => esc_html__('Opacity', 'microthemer'),
	'css_func' => 1,
	'field-class' => '',
	'input-class' => 'size-0b',
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
		'max' => array(
			'' => 1,
			'%' => 100
		)
	),
	'select_options' => $opacity,
	'icon' => '20, 4, B',
	// ref
	'ref_desc' => "<p>The opacity() CSS function applies transparency to the samples in the input image.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/opacity',
		'quackit' => 'https://qhmit.com/css/functions/css_opacity_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// saturate
$propertyOptions['filter']['saturate_function'] = array(
	'short_label' => esc_html__('Saturate', 'microthemer'),
	'label' => esc_html__('Saturate', 'microthemer'),
	'css_func' => 1,
	'field-class' => '',
	'input-class' => 'size-0b',
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
	),
	'select_options' => $brightness,
	'icon' => '21, 4, B',
	// ref
	'ref_desc' => "<p>The saturate() CSS function super-saturates or desaturates the input image.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/saturate',
		'quackit' => 'https://qhmit.com/css/functions/css_saturate_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// sepia
$propertyOptions['filter']['sepia_function'] = array(
	'short_label' => esc_html__('Sepia', 'microthemer'),
	'label' => esc_html__('Sepia', 'microthemer'),
	'css_func' => 0,
	'field-class' => '',
	'input-class' => 'size-0b',
	'default_unit' => '',
	'special_units' => $percent_none,
	'sug_values' => array(
		'this' => 1,
		'max' => array(
			'' => 1,
			'%' => 100
		)
	),
	'select_options' => $opacity,
	'icon' => '16, 8, B',
	// ref
	'ref_desc' => "<p>The sepia() CSS function converts the input image to sepia, giving it a warmer, more yellow/brown appearance.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/sepia',
		'quackit' => 'https://qhmit.com/css/functions/css_sepia_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);

// url
$propertyOptions['filter']['url_function'] = array(
	'short_label' => esc_html__('Url', 'microthemer'),
	'label' => esc_html__('Url', 'microthemer'),
	'css_func' => 'none',
	'type' => 'combobox',
	'field-class' => 'size-very-big',
	'input-class' => 'bg-image-select',
	'sug_values' => array(
		'this' => 1,
	),
	'select_options' => array(
		'/wp-content/plugins/microthemer/images/examples/filter.svg#blur',
	),
	'icon' => '39, 14',
	'icon-name' => 'background-image',
	// ref
	'ref_desc' => "<p>The url() CSS function adjusts the url of the input image.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/filter-function/url',
		'quackit' => 'https://qhmit.com/css/functions/css_url_function.cfm',
	),
	'tutorials' => $filter_tutorials,
	'group_tutorials' => 1
);



// dimensions
// ------------------------------------------------------------

/*$rem_sizes = array(
	'.5rem',
	'1rem',
	'1.5rem',
	'2rem',
	'2.5rem',
	'3rem'
);*/
$percent_sizes = array(
	'25%',
	'33.33%',
	'50%',
	'66.66%',
	'75%',
	'100%'
);

$propertyOptions['dimensions']['width'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Width', 'microthemer'),
	'label' => esc_attr__('Width', 'microthemer'),
	'pg_label' => esc_attr__('Dimensions', 'microthemer'),
	'sub_label' => esc_html__('Width', 'microthemer'),
	'sub_slug' => 'width',
	'new_pg_cat' => esc_attr__('Layout', 'microthemer'), // for delimiting property group categories
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'input-class' => 'size-1',
	'default_unit' => 'px',
	'select_options' => array_merge($percent_sizes, array('auto')),
	'sug_values' => array(
		'this' => 1,
		//'pattern' => '^(?:(?:min|max)\-)?width',
		'root_cat' => 'width' // common key shared by all width properties, for storing all width values in on array
	),
	'icon' => '9',
	// ref
	'ref_desc' => "<p>The width property sets the width of an element. If 'box-sizing' is set to 'content-box' (default) the total width of an element is
	width + padding + borders (and in terms of the space it takes up on the page + margin too). However, if the width hasn't been given a numeric or percentage value (or has been explicitly set to 'auto') it will have a value of 'auto'. Applying padding, margin and border values when width is 'auto' causes the browser to decrease the value it calculates for width. Otherwise the element would be too big for it's parent element - which is what happens if you enter a value of '100%' for width and then add margins, padding or borders.</p>
	<p>If 'box-sizing' is set to 'border-box' padding and border values are not added to the defined width. The defined width specifies the total width. So if you set width to '100' and border-left to '20' the total width would be '100' as opposed to '120' (the padding forces the width down to '80').</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '400' would set the width to 400 pixels. Other commonly used units include 'em' and '%'. So you could enter '50%' in the Width field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/w/width/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/width',
		'quackit' => 'http://www.quackit.com/css/properties/css_width.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_dim_width.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.impressivewebs.com/min-max-width-height-css/',
			'title' => 'Using Min/Max Width and Height in CSS',
		),
		array(
			'url' => 'http://www.quackit.com/css/tutorial/css_height_width.cfm',
			'title' => 'CSS Height and Width',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['dimensions']['min_width'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Min', 'microthemer'),
	'label' => esc_attr__('Min Width', 'microthemer'),
	'input-class' => 'size-1',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => array(
		'0',
		'50%',
		'100%'
	),
	'sug_values' => array(
		'this' => 1,
		//'pattern' => '^(?:(?:min|max)\-)?width',
		'root_cat' => 'width'
	),
	'icon' => '36, 14',
	// ref
	'ref_desc' => "<p>The min-width property sets the minimum width of an element. Note: The min-width property does not include padding, borders, or margins.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '400' would set the minimun width to 400 pixels. Other commonly used units include 'em' and '%'. So you could enter '50%' in the Min Width field."),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=minmaxwh',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/min-width/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/min-width',
		'quackit' => 'http://www.quackit.com/css/properties/css_min-width.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_dim_min-width.asp',
	),
	'tutorials' => $propertyOptions['dimensions']['width']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['dimensions']['max_width'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Max', 'microthemer'),
	'label' => esc_attr__('Max Width', 'microthemer'),
	'input-class' => 'size-1',
	'last_in_sub' => 1,
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => array(
		'none',
		'0',
		'50%',
		'100%',
	),
	'sug_values' => array(
		'this' => 1,
		//'pattern' => '^(?:(?:min|max)\-)?width',
		'root_cat' => 'width'
	),
	'icon' => '34, 14',
	// ref
	'ref_desc' => "<p>The max-width property sets the maximum width of an element. Note: The max-width property does not include padding, borders, or margins.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '400' would set the maximun width to 400 pixels. Other commonly used units include 'em' and '%'. So you could enter '50%' in the Max Width field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=minmaxwh',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/max-width/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/max-width',
		'quackit' => 'http://www.quackit.com/css/properties/css_max-width.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_dim_max-width.asp',
	),
	'tutorials' => $propertyOptions['dimensions']['width']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['dimensions']['height'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Height', 'microthemer'),
	'label' => esc_attr__('Height', 'microthemer'),
	'field-class' => 'icon-size-0b',
	'sub_label' => esc_html__('Height', 'microthemer'),
	'sub_slug' => 'height',
	'input-class' => 'size-1',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height',
			'tip' => $height_percentage_tip
		)
	),
	'default_unit' => 'px',
	'select_options' => array(
		'auto',
		'100%'
	),
	// we don't want line-height, so use restricted pattern
	'sug_values' => array(
		'this' => 1,
		//'pattern' => '^(?:(?:min|max)\-)?height',
		'root_cat' => 'height'
	),
	'icon' => '10',
	// ref
	'ref_desc' => "<p>The height property sets the height of an element. <b>Note:</b> The total height of an element is
	height + padding + borders (+ margins, if you consider the total space the element takes up on the page). However, if box-sizing is set to border-box padding and border are incorporated in the value set for height.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '200' would set the height to 400 pixels. Other commonly used units include 'em' and '%'. So you could enter '10em' in the Height field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=minmaxwh',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/h/height/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/',
		'quackit' => 'http://www.quackit.com/css/properties/css_height.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_dim_height.asp',
	),
	'tutorials' => $propertyOptions['dimensions']['width']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['dimensions']['min_height'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Min', 'microthemer'),
	'label' => esc_attr__('Min Height', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-1',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height',
			'tip' => $height_percentage_tip
		)
	),
	'default_unit' => 'px',
	'select_options' => array(
		'0',
		'100%'
	),
	'sug_values' => array(
		'this' => 1,
		//'pattern' => '^(?:(?:min|max)\-)?height',
		'root_cat' => 'height'
	),
	'icon' => '37, 14',
	// ref
	'ref_desc' => "<p>The min-height property sets the minimum height of an element. Note: The min-height property does not include padding, borders, or margins</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '400' would set the minimun height to 400 pixels."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=minmaxwh',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/min-height/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/min-height',
		'quackit' => 'http://www.quackit.com/css/properties/css_min-height.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_dim_min-height.asp',
	),
	'tutorials' => $propertyOptions['dimensions']['width']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['dimensions']['max_height'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Max', 'microthemer'),
	'label' => esc_attr__('Max Height', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-1',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height',
			'tip' => $height_percentage_tip
		)
	),
	'default_unit' => 'px',
	'select_options' => array(
		'none',
		'0',
		'100%'
	),
	'sug_values' => array(
		'this' => 1,
		//'pattern' => '^(?:(?:min|max)\-)?height',
		'root_cat' => 'height'
	),
	'icon' => '35, 14',
	// ref
	'ref_desc' => "<p>The max-height property sets the maximum height of an element. Note: The max-height property does not include padding, borders, or margins.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '400' would set the maximun height to 400 pixels."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=minmaxwh',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/max-height/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/max-height',
		'quackit' => 'http://www.quackit.com/css/properties/css_max-height.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_dim_max-height.asp',
	),
	'tutorials' => $propertyOptions['dimensions']['width']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['dimensions']['box_sizing'] = array(
	'short_label' => esc_html__('Box Sizing', 'microthemer'),
	'label' => esc_attr__('Box Sizing', 'microthemer'),
	'sub_label' => esc_html__('Other', 'microthemer'),
	'sub_slug' => 'dimensionsother',
	'input-class' => 'size-5',
	'type' => 'combobox',
	'last_in_sub' => 1,
	'select_options' => array(
		'content-box',
		'border-box'
	),
	'field-class' => 'last',
	'icon' => '32, 14',
	// ref
	'ref_desc' => "<p>The box-sizing property determines if padding and border should be <b>added to the value</b> set for width/height or <b>form part of the value</b> set for width/height (including min/max). Although the default value for box-sizing is content-box, many view this to be a mistake as elements can be easier to layout when padding and border are included in the width (border-box).</p>",
	'ref_values' => array(
		"content-box" => "Padding and border values are added to the value given for width/height. For instance, total width = width + padding + border. This is the default.",
		"border-box" => "Padding and border values form part of the value given for width/height. For instance, total width = width."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=css3-boxsizing',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/box-sizing/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/box-sizing',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_box-sizing.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_box-sizing.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://css-tricks.com/box-sizing/',
			'title' => 'Box Sizing',
		),
		array(
			'url' => 'http://blog.teamtreehouse.com/box-sizing-secret-simple-css-layouts',
			'title' => 'Box-Sizing: The Secret to Simple CSS Layouts',
		),
		array(
			'url' => 'http://blog.teamtreehouse.com/take-control-of-the-box-model-with-box-sizing',
			'title' => 'Take Control of the Box Model with box-sizing',
		),
		array(
			'url' => 'http://learn.shayhowe.com/html-css/opening-the-box-model/#box-model',
			'title' => 'Working with the Box Model',
		),
	)
);

$propertyOptions['dimensions']['object_fit'] = array(
	'short_label' => esc_html__('Object Fit', 'microthemer'),
	'label' => esc_attr__('Object Fit', 'microthemer'),
	//'input-class' => 'size-5',
	'type' => 'combobox',
	'last_in_sub' => 1,
	'select_options' => array(
		'contain',
		'cover',
		'fill',
		'none',
		'scale-down',
	),
	'icon' => '32, 14',
	// ref
	'ref_desc' => "<p>The object-fit CSS property sets how the content of a replaced element, such as an img or video, should be resized to fit its container.</p>",
	'ref_values' => array(
		"contain" => "The object may be 'letter-boxed' to maintain its aspect ratio, while displaying entirely within the element's content box.",
		"cover" => "The object may be clipped to maintain its aspect ratio, while ensuring it covers the whole element's content box.",
		"fill" => "Like cover, but the object may be stretched, rather than clipped, to ensure it fills the whole element's content box. This is the default.",
		"none" => "The object will not be resized.",
		"scale-down" => "The content is sized as if 'none' or 'contain' were specified, whichever would result in a smaller concrete object size."
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=object-fit',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/o/object-fit/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/object-fit',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_object-fit.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_object-fit.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://www.digitalocean.com/community/tutorials/css-cropping-images-object-fit',
			'title' => 'How To Scale and Crop Images with CSS object-fit',
		),
		array(
			'url' => 'https://www.youtube.com/watch?v=6yAAV-uP0po',
			'title' => 'How to use CSS object-fit to control your images',
		),
		array(
			'url' => 'https://www.smashingmagazine.com/2021/10/object-fit-background-size-css/',
			'title' => 'A Deep Dive Into object-fit And background-size In CSS',
		),
	)
);

$propertyOptions['dimensions']['object_position'] = array(
	'short_label' => esc_html__('Object Position', 'microthemer'),
	'label' => esc_attr__('Object Position', 'microthemer'),
	//'input-class' => 'size-5',
	//'type' => 'combobox',
	'last_in_sub' => 1,
	//'multiple' => 1,
	'select_options' => array_merge($background_position_values, array(
		'50% 50%',
		//'right bottom 20px'
	)),
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => array('width', 'height')
		)
	),
	'default_unit' => 'px',
	'sug_values' => array(
		'root_cat' => 'background_position',
		'min' => ''
	),
	'icon' => '32, 14',
	// ref
	'ref_desc' => "<p>The object-position CSS property specifies the alignment of the object within the element's box. The default is centered.</p>",
	'ref_values' => '',
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=object-position',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/o/object-position/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/object-position',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_object-position.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_object-position.asp',
	),
);


// padding
// ------------------------------------------------------------

$propertyOptions['padding_margin']['padding_top'] = array(
	'sh' => array('padding', 0),
	'animatable' => 1,
	'short_label' => esc_html__('Top', 'microthemer'),
	'label' => esc_attr__('Padding Top', 'microthemer'),
	'pg_label' => esc_attr__('Spacing', 'microthemer'),
	'sub_label' => esc_html__('Padding', 'microthemer'),
	'sub_slug' => 'padding',
	'field-class' => 'quad-top',
	'input-class' => 'size-2',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'select_options' => $padding_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing', // I considered padding_margin here, but there will be lots of P&M values
	),
	'rel' => 'padding',
	'icon' => '3',
	// ref
	'ref_desc' => "<p>The padding-top property sets the top padding (space) of an element. The space is created <i>inside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the top padding for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Top Padding field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/p/padding/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/padding-top',
		'quackit' => 'http://www.quackit.com/css/properties/css_padding-top.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_padding-top.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.xul.fr/en/css/margin-padding.php',
			'title' => 'Padding and margin',
		),
		array(
			'url' => 'http://www.goer.org/HTML/intermediate/margins_and_padding/',
			'title' => 'Box Model: Margins and Padding',
		),
		array(
			'url' => 'http://xahlee.info/js/css_margin_vs_padding.html',
			'title' => 'CSS: Margin vs Padding',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['padding_margin']['padding_right'] = array(
	'sh' => array('padding', 1),
	'animatable' => 1,
	'short_label' => esc_html__('Right', 'microthemer'),
	'label' => esc_attr__('Padding Right', 'microthemer'),
	'field-class' => 'quad-right',
	'input-class' => 'size-2',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $padding_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing' // I considered padding_margin here, but there will be lots of P&M values
	),
	'rel' => 'padding',
	'icon' => '2',
	// ref
	'ref_desc' => "<p>The padding-right property sets the right padding (space) of an element. The space is created <i>inside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the right padding for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Right Padding field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/p/padding/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/padding-right',
		'quackit' => 'http://www.quackit.com/css/properties/css_padding-right.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_padding-right.asp',
	),
	'tutorials' => $propertyOptions['padding_margin']['padding_top']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['padding_margin']['padding_bottom'] = array(
	'sh' => array('padding', 2),
	'animatable' => 1,
	'short_label' => esc_html__('Bottom', 'microthemer'),
	'label' => esc_attr__('Padding Bottom', 'microthemer'),
	'field-class' => 'quad-bottom',
	'input-class' => 'size-2',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'select_options' => $padding_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing' // I considered padding_margin here, but there will be lots of P&M values
	),
	'rel' => 'padding',
	'icon' => '4',
	// ref
	'ref_desc' => "<p>The padding-bottom property sets the bottom padding (space) of an element. The space is created <i>inside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the bottom padding for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Bottom Padding field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/p/padding/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/padding-bottom',
		'quackit' => 'http://www.quackit.com/css/properties/css_padding-bottom.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_padding-bottom.asp',
	),
	'tutorials' => $propertyOptions['padding_margin']['padding_top']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['padding_margin']['padding_left'] = array(
	'sh' => array('padding', 3),
	'animatable' => 1,
	'short_label' => esc_html__('Left', 'microthemer'),
	'label' => esc_attr__('Padding Left', 'microthemer'),
	'field-class' => 'quad-left',
	'input-class' => 'size-2',
	'last_in_sub' => 1,
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $padding_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing' // I considered padding_margin here, but there will be lots of P&M values
	),
	'rel' => 'padding',
	'field-class' => 'last',
	'icon' => '1',
	// ref
	'ref_desc' => "<p>The padding-left property sets the left padding (space) of an element. The space is created <i>inside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the left padding for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Left Padding field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/p/padding/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/padding-left',
		'quackit' => 'http://www.quackit.com/css/properties/css_padding-left.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_padding-left.asp',
	),
	'tutorials' => $propertyOptions['padding_margin']['padding_top']['tutorials'],
	'group_tutorials' => 1
);

// margin
// ------------------------------------------------------------

$propertyOptions['padding_margin']['margin_top'] = array(
	'sh' => array('margin', 0),
	'animatable' => 1,
	'short_label' => esc_html__('Top', 'microthemer'),
	'label' => esc_attr__('Margin Top', 'microthemer'),
	'sub_label' => esc_html__('Margin', 'microthemer'),
	'sub_slug' => 'margin',
	'field-class' => 'quad-top',
	'input-class' => 'size-2',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'select_options' => $margin_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing',
		'min' => ''
	),
	'rel' => 'margin',
	'icon' => '7',
	// ref
	'ref_desc' => "<p>The margin-top property sets the top margin (space) of an element.
The space is created <i>outside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the top margin for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Top Margin field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/margin/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/margin-top',
		'quackit' => 'http://www.quackit.com/css/properties/css_margin-top.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_margin-top.asp',
	),
	'tutorials' => $propertyOptions['padding_margin']['padding_top']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['padding_margin']['margin_right'] = array(
	'sh' => array('margin', 1),
	'animatable' => 1,
	'short_label' => esc_html__('Right', 'microthemer'),
	'label' => esc_attr__('Margin Right', 'microthemer'),
	'field-class' => 'icon-size-2 quad-right',
	'input-class' => 'size-2',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $margin_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing',
		'min' => ''
	),
	'rel' => 'margin',
	'icon' => '6',
	// ref
	'ref_desc' => "<p>The margin-right property sets the right margin (space) of an element. The space is created <i>outside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the right margin for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Right Margin field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/margin/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/margin-right',
		'quackit' => 'http://www.quackit.com/css/properties/css_margin-right.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_margin-right.asp',
	),
	'tutorials' => $propertyOptions['padding_margin']['padding_top']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['padding_margin']['margin_bottom'] = array(
	'sh' => array('margin', 2),
	'animatable' => 1,
	'short_label' => esc_html__('Bottom', 'microthemer'),
	'label' => esc_attr__('Margin Bottom', 'microthemer'),
	'field-class' => 'quad-bottom',
	'input-class' => 'size-2',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'rel' => 'margin',
	'select_options' => $margin_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing',
		'min' => ''
	),
	'icon' => '8',
	// ref
	'ref_desc' => "<p>The margin-bottom property sets the bottom margin (space) of an element. The space is created <i>outside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the bottom margin for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Bottom Margin field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/margin/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/margin-bottom',
		'quackit' => 'http://www.quackit.com/css/properties/css_margin-bottom.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_margin-bottom.asp',
	),
	'tutorials' => $propertyOptions['padding_margin']['padding_top']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['padding_margin']['margin_left'] = array(
	'sh' => array('margin', 3),
	'animatable' => 1,
	'short_label' => esc_html__('Left', 'microthemer'),
	'label' => esc_attr__('Margin Left', 'microthemer'),
	'field-class' => 'icon-size-3 last quad-left',
	'input-class' => 'size-2',
	'last_in_sub' => 1,
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $margin_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'spacing',
		'min' => ''
	),
	'rel' => 'margin',
	'icon' => '5',
	// ref
	'ref_desc' => "<p>The margin-left property sets the left margin (space) of an element. The space is created <i>outside</i> the element's border.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the left margin for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Left Margin field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/m/margin/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/margin-left',
		'quackit' => 'http://www.quackit.com/css/properties/css_margin-left.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_margin-left.asp',
	),
	'tutorials' => $propertyOptions['padding_margin']['padding_top']['tutorials'],
	'group_tutorials' => 1
);

// border
// ------------------------------------------------------------
$border_width_lengths = array(
	'0',
	'1',
	'2',
	'3',
	'4',
	'5'
);
$border_radius_lengths = array(
	'0',
	'2',
	'3',
	'4',
	'6',
	'8',
	'10',
	'12',
	'50%'
);


$propertyOptions['border']['border_top_color'] = array(
	'sh' => array(
		array('border', 8, array('initial' => '')),
		array('border-top', 2, array('initial' => '')),
		array('border-color', 0, array('initial' => ''))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Top', 'microthemer'),
	'label' => esc_attr__('Border Top Color', 'microthemer'),
	'pg_label' => esc_attr__('Border', 'microthemer'),
	'sub_label' => esc_html__('Border Color', 'microthemer'),
	'sub_slug' => 'border_color',
	'rel' => 'border_color',
	'field-class' => 'is-picker quad-top',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'color'
	),
	'icon' => '10, 14',
	// ref
	'ref_desc' => "<p>The border-top-color property sets the top border color of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex code or RGB/A values. Just click your mouse in the Top Border Color text field to reveal the color picker."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-top-color',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-top-color.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-top_color.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://code.tutsplus.com/tutorials/css-refreshers-borders--net-24655',
			'title' => 'CSS Refreshers: Borders',
		),
		array(
			'url' => 'http://www.entheosweb.com/tutorials/css/borders.asp',
			'title' => 'CSS Borders Tutorial',
		),
		array(
			'url' => 'http://www.tizag.com/cssT/border.php',
			'title' => 'CSS border',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['border']['border_right_color'] = array(
	'sh' => array(
		array('border', 9, array('initial' => '')),
		array('border-right', 2, array('initial' => '')),
		array('border-color', 1, array('initial' => ''))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Right', 'microthemer'),
	'label' => esc_attr__('Border Right Color', 'microthemer'),
	'rel' => 'border_color',
	'field-class' => 'is-picker quad-right',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'color'
	),
	'icon' => '13, 14',
	// ref
	'ref_desc' => "<p>The border-right-color property sets the right border color of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the Right Border Color text field to reveal the color picker."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-right-color',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-right-color.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-right_color.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_bottom_color'] = array(
	'sh' => array(
		array('border', 10, array('initial' => '')),
		array('border-bottom', 2, array('initial' => '')),
		array('border-color', 2, array('initial' => ''))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Bottom', 'microthemer'),
	'label' => esc_attr__('Border Bottom Color', 'microthemer'),
	'rel' => 'border_color',
	'field-class' => 'is-picker quad-bottom',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'color'
	),
	'icon' => '11, 14',
	// ref
	'ref_desc' => "<p>The border-bottom-color property sets the bottom border color of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the Bottom Border Color text field to reveal the color picker."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-bottom-color',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-bottom-color.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-bottom_color.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_left_color'] = array(
	'sh' => array(
		array('border', 11, array('initial' => '')),
		array('border-left', 2, array('initial' => '')),
		array('border-color', 3, array('initial' => ''))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Left', 'microthemer'),
	'label' => esc_attr__('Border Left Color', 'microthemer'),
	'rel' => 'border_color',
	'field-class' => 'last is-picker quad-left',
	'input-class' => 'color mt-color-picker',
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'color'
	),
	'last_in_sub' => 1,
	'icon' => '12, 14',
	// ref
	'ref_desc' => "<p>The border-left-color property sets the left border color of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(hex code or RGB/A)" => "Microthemer provides a color picker for specifying color without having to remember hex codes or RGB/A values. Just click your mouse in the Left Border Color text field to reveal the color picker."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-left-color',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-left-color.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-left_color.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_top_width'] = array(
	'sh' => array(
		array('border', 0, array('initial' => 'medium')),
		array('border-top', 0, array('initial' => 'medium')),
		array('border-width', 0, array('initial' => 'medium'))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Top', 'microthemer'),
	'label' => esc_attr__('Border Top Width', 'microthemer'),
	'sub_label' => esc_html__('Border Width', 'microthemer'),
	'sub_slug' => 'border_width',
	'field-class' => 'quad-top',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $border_width_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_width'
	),
	'rel' => 'border_width',
	'icon' => '6, 14',
	// ref
	'ref_desc' => "<p>The border-top-width property sets the top border width of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the top border width for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Top Border Width field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-top-width',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-top-width.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-top_width.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_right_width'] = array(
	'sh' => array(
		array('border', 1, array('initial' => 'medium')),
		array('border-right', 0, array('initial' => 'medium')),
		array('border-width', 1, array('initial' => 'medium'))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Right', 'microthemer'),
	'label' => esc_attr__('Border Right Width', 'microthemer'),
	'field-class' => 'quad-right',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $border_width_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_width'
	),
	'rel' => 'border_width',
	'icon' => '9, 14',
	// ref
	'ref_desc' => "<p>The border-right-width property sets the right border width of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the right border for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Right Border Width field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-right-width',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-right-width.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-right_width.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_bottom_width'] = array(
	'sh' => array(
		array('border', 2, array('initial' => 'medium')),
		array('border-bottom', 0, array('initial' => 'medium')),
		array('border-width', 2, array('initial' => 'medium'))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Bottom', 'microthemer'),
	'label' => esc_attr__('Border Bottom Width', 'microthemer'),
	'field-class' => 'quad-bottom',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $border_width_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_width'
	),
	'rel' => 'border_width',
	'icon' => '7, 14',
	// ref
	'ref_desc' => "<p>The border-bottom-width property sets the bottom border width of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the bottom border for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Bottom Border Width field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-bottom-width',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-bottom-width.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-bottom_width.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_left_width'] = array(
	'sh' => array(
		array('border', 3, array('initial' => 'medium')),
		array('border-left', 0, array('initial' => 'medium')),
		array('border-width', 3, array('initial' => 'medium'))
	),
	'animatable' => 1,
	'short_label' => esc_html__('Left', 'microthemer'),
	'label' => esc_attr__('Border Left Width', 'microthemer'),
	'field-class' => 'quad-left last',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $border_width_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_width'
	),
	'rel' => 'border_width',
	'last_in_sub' => 1,
	'icon' => '8, 14',
	'linebreak' => 1,
	// ref
	'ref_desc' => "<p>The border-left-width property sets the left border width of an element. <b>Note</b>: the Border Style property must be set for any of the other border properties to work.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '15' would set the left border for an element to 15 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%' in the Left Border Width field."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-left-width',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-left-width.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-left_width.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$border_style_options = array(
	'hidden',
	'dotted',
	'dashed',
	'solid',
	'double',
	'groove',
	'ridge',
	'inset',
	'outset',
	'none'
);

$propertyOptions['border']['border_top_style'] = array(
	'sh' => array(
		array('border', 4, array('initial' => 'none')),
		array('border-top', 1, array('initial' => 'none')),
		array('border-style', 0, array('initial' => 'none'))
	),
	'short_label' => esc_html__('Top', 'microthemer'),
	'label' => esc_attr__('Border Top Style', 'microthemer'),
	'sub_label' => esc_html__('Border Style', 'microthemer'),
	'sub_slug' => 'border_style',
	'field-class' => 'quad-top',
	'type' => 'combobox',
	'rel' => 'border_style',
	'select_options' => $border_style_options,
	'icon' => '14, 14',
	// ref
	'ref_desc' => "<p>The border-top-style property sets the style of an element's top border.</p>",
	'ref_values' => array(
		"hidden" => "The same as 'none', except in border conflict resolution for table elements",
		"dotted" => "Specifies a dotted border",
		"dashed" => "Specifies a dashed border",
		"solid" => "Specifies a solid border",
		"double" => "Specifies a double border",
		"groove" => "Specifies a 3D grooved border. The effect depends on the border-color value",
		"ridge" => "Specifies a 3D ridged border. The effect depends on the border-color value!",
		"inset" => "Specifies a 3D inset border. The effect depends on the border-color value",
		"outset" => "Specifies a 3D outset border. The effect depends on the border-color value",
		"none" => "Specifies no border"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-top-style',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-top-style.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-top_style.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_right_style'] = array(
	'sh' => array(
		array('border', 5, array('initial' => 'none')),
		array('border-right', 1, array('initial' => 'none')),
		array('border-style', 1, array('initial' => 'none'))
	),
	'short_label' => esc_html__('Right', 'microthemer'),
	'label' => esc_attr__('Border Right Style', 'microthemer'),
	'field-class' => 'quad-right',
	'type' => 'combobox',
	'rel' => 'border_style',
	'select_options' => $border_style_options,
	'icon' => '17, 14',
	// ref
	'ref_desc' => "<p>The border-right-style property sets the style of an element's right border.</p>",
	'ref_values' => array(
		"hidden" => "The same as 'none', except in border conflict resolution for table elements",
		"dotted" => "Specifies a dotted border",
		"dashed" => "Specifies a dashed border",
		"solid" => "Specifies a solid border",
		"double" => "Specifies a double border",
		"groove" => "Specifies a 3D grooved border. The effect depends on the border-color value",
		"ridge" => "Specifies a 3D ridged border. The effect depends on the border-color value!",
		"inset" => "Specifies a 3D inset border. The effect depends on the border-color value",
		"outset" => "Specifies a 3D outset border. The effect depends on the border-color value",
		"none" => "Specifies no border"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-right-style',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-right-style.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-right_style.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_bottom_style'] = array(
	'sh' => array(
		array('border', 6, array('initial' => 'none')),
		array('border-bottom', 1, array('initial' => 'none')),
		array('border-style', 2, array('initial' => 'none'))
	),
	'short_label' => esc_html__('Bottom', 'microthemer'),
	'label' => esc_attr__('Border Bottom Style', 'microthemer'),
	'field-class' => 'quad-bottom',
	'type' => 'combobox',
	'rel' => 'border_style',
	'select_options' => $border_style_options,
	'icon' => '15, 14',
	// ref
	'ref_desc' => "<p>The border-bottom-style property sets the style of an element's bottom border.</p>",
	'ref_values' => array(
		"hidden" => "The same as 'none', except in border conflict resolution for table elements",
		"dotted" => "Specifies a dotted border",
		"dashed" => "Specifies a dashed border",
		"solid" => "Specifies a solid border",
		"double" => "Specifies a double border",
		"groove" => "Specifies a 3D grooved border. The effect depends on the border-color value",
		"ridge" => "Specifies a 3D ridged border. The effect depends on the border-color value!",
		"inset" => "Specifies a 3D inset border. The effect depends on the border-color value",
		"outset" => "Specifies a 3D outset border. The effect depends on the border-color value",
		"none" => "Specifies no border"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-bottom-style',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-bottom-style.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-right_style.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['border']['border_left_style'] = array(
	'sh' => array(
		array('border', 7, array('initial' => 'none')),
		array('border-left', 1, array('initial' => 'none')),
		array('border-style', 3, array('initial' => 'none'))
	),
	'short_label' => esc_html__('Left', 'microthemer'),
	'label' => esc_attr__('Border Left Style', 'microthemer'),
	'type' => 'combobox',
	'field-class' => 'last quad-left',
	'last_in_sub' => 1,
	'rel' => 'border_style',
	'select_options' => $border_style_options,
	'icon' => '16, 14',
	// ref
	'ref_desc' => "<p>The border-left-style property sets the style of an element's left border.</p>",
	'ref_values' => array(
		"hidden" => "The same as 'none', except in border conflict resolution for table elements",
		"dotted" => "Specifies a dotted border",
		"dashed" => "Specifies a dashed border",
		"solid" => "Specifies a solid border",
		"double" => "Specifies a double border",
		"groove" => "Specifies a 3D grooved border. The effect depends on the border-color value",
		"ridge" => "Specifies a 3D ridged border. The effect depends on the border-color value!",
		"inset" => "Specifies a 3D inset border. The effect depends on the border-color value",
		"outset" => "Specifies a 3D outset border. The effect depends on the border-color value",
		"none" => "Specifies no border"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-left-style',
		'quackit' => 'http://www.quackit.com/css/properties/css_border-left-style.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_border-left_style.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_color']['tutorials'],
	'group_tutorials' => 1
);

// border radius
// ------------------------------------------------------------

$propertyOptions['border']['border_top_left_radius'] = array(
	'sh' => array('border-radius', 0),
	'short_label' => esc_html__('Top Left', 'microthemer'),
	'label' => esc_attr__('Top Left Border Radius', 'microthemer'),
	'sub_label' => esc_html__('Border Radius', 'microthemer'),
	'sub_slug' => 'border_radius',
	/*'auto' => array(
		'%' => $border_radius_unconvertable
	),*/
	'default_unit' => 'px',
	'select_options' => $border_radius_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_radius'
	),
	'icon' => '17',
	'rel' => 'border_radius',
	//'hide imp' => 1,
	// ref
	'ref_desc' => "<p>The top left radius property defines the shape of the border of the top-left corner. A higher value creates a more rounded curve.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '5' would set the top left border radius for an element to 5 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%'."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=border-radius',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border-radius/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-top-left-radius',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_border-top-left-radius.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_border-top-left-radius.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://xahlee.info/js/css_round_corners.html',
			'title' => 'CSS: Round Corners: border-radius',
		),
		array(
			'url' => 'http://www.css3.info/preview/rounded-border/',
			'title' => 'Border-radius: create rounded corners with CSS!',
		),
		array(
			'url' => 'http://www.cssmatic.com/border-radius',
			'title' => 'CSSmatic Border Radius',
			'tool' => 1
		),
	),
	'related_tutorials' => esc_html__('Border Radius', 'microthemer')
);

$propertyOptions['border']['border_top_right_radius'] = array(
	'sh' => array('border-radius', 1),
	'short_label' => esc_html__('Top Right', 'microthemer'),
	'label' => esc_attr__('Top Right Border Radius', 'microthemer'),
	'auto' => array(
		'%' => $border_radius_unconvertable
	),
	'default_unit' => 'px',
	'select_options' => $border_radius_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_radius'
	),
	'icon' => '18',
	'rel' => 'border_radius',
	//'hide imp' => 1,
	//ref
	'ref_desc' => "<p>The top right radius property defines the shape of the border of the top-right corner. A higher value creates a more rounded curve.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '5' would set the top right border radius for an element to 5 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%'."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=border-radius',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border-radius/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-top-right-radius',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_border-top-right-radius.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_border-top-right-radius.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_left_radius']['tutorials']
);

$propertyOptions['border']['border_bottom_right_radius'] = array(
	'sh' => array('border-radius', 2),
	'short_label' => esc_html__('Bottom Right', 'microthemer'),
	'label' => esc_attr__('Bottom Right Border Radius', 'microthemer'),
	'auto' => array(
		'%' => $border_radius_unconvertable
	),
	'default_unit' => 'px',
	'select_options' => $border_radius_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_radius'
	),
	'icon' => '20',
	'rel' => 'border_radius',
	//'hide imp' => 1,
	// ref
	'ref_desc' => "<p>The bottom left radius property defines the shape of the border of the bottom-left corner. A higher value creates a more rounded curve.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '5' would set the bottom left border radius for an element to 5 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%'."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=border-radius',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border-radius/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-bottom-right-radius',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_border-bottom-right-radius.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_border-bottom-right-radius.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_left_radius']['tutorials'],
	'related_tutorials' => $propertyOptions['border']['border_top_left_radius']['sub_label']
);

$propertyOptions['border']['border_bottom_left_radius'] = array(
	'sh' => array('border-radius', 3),
	'short_label' => esc_html__('Bottom Left', 'microthemer'),
	'label' => esc_attr__('Bottom Left Border Radius', 'microthemer'),
	'auto' => array(
		'%' => $border_radius_unconvertable
	),
	'default_unit' => 'px',
	'select_options' => $border_radius_lengths,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'border_radius'
	),
	'icon' => '19',
	'rel' => 'border_radius',
	'field-class' => 'last',
	'last_in_sub' => 1,
	'important_carrier' => 1,
	// ref
	'ref_desc' => "<p>The bottom right radius property defines the shape of the border of the bottom-right corner. A higher value creates a more rounded curve.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '5' would set the bottom right border radius for an element to 5 pixels. Other commonly used units include 'em' and '%'. So you could enter '5%'."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=border-radius',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/border-radius/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/border-bottom-left-radius',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_border-bottom-left-radius.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_border-bottom-left-radius.asp',
	),
	'tutorials' => $propertyOptions['border']['border_top_left_radius']['tutorials'],
	'related_tutorials' => $propertyOptions['border']['border_top_left_radius']['sub_label']
);


// Flexbox (container)
// -------
//-----------------------------------------------------

$flex_basis = $propertyOptions['dimensions']['width']['select_options'];



// duplicate display property so display:flex can be set more easily
$propertyOptions['flexbox']['display_flex'] = array(
	'short_label' => esc_html_x('Display', 'noun', 'microthemer'),
	'label' => esc_attr_x('Display (flex)', 'noun', 'microthemer'),
	'pg_label' => esc_attr__('Flexbox', 'microthemer'),
	'sub_label' => esc_html__('Flex', 'microthemer'),
	'sub_slug' => 'flexcontainer',
	// configure toggle for container / items fields
	'dynamic_fields' => 'flexitem',
	'input-class' => 'size-4',
	'type' => 'combobox',
	'select_options' => array(
		'flex',
		'inline-flex',
	),
	'prefixes' => $flex_display_prefixes,
	'icon' => '23, 14',
	// ref
	'ref_desc' => "<p>The display property specifies the type of box an element should generate. Microthemer includes the 'display' property in the Flexbox group AS WELL as the Behaviour group for convenience. As it is necessary to set 'display' to 'flex' or 'inline-flex' in order for the other flexbox properties to work.</p>",
	'ref_values' => array(
		"flex" => "Displays an element as a block-level flex container. New in CSS3",
		"inline-flex" => "Displays an element as an inline-level flex container. New in CSS3.",
	),
	'tutorials' => array(
		array(
			'url' => 'https://css-tricks.com/snippets/css/a-guide-to-flexbox/',
			'title' => 'CSS-Tricks: A Complete Guide to Flexbox',
		),
		array(
			'url' => 'https://scotch.io/tutorials/a-visual-guide-to-css3-flexbox-properties',
			'title' => 'A Visual Guide to CSS3 Flexbox Properties',
		),
		array(
			'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Using_CSS_flexible_boxes',
			'title' => 'Mozilla: Using CSS Flexible Boxes',
		),
		array(
			'url' => 'https://internetingishard.com/html-and-css/flexbox/',
			'title' => 'Flexbox - No. 8. of HTML & CSS Is Hard',
		),
		array(
			'url' => 'http://flexboxfroggy.com/',
			'title' => 'A game for learning Flexbox',
		),

	),
	'group_tutorials' => 1
);

// flex direction (order of items as rows or columns)
$propertyOptions['flexbox']['flex_direction'] = array(
	'sh' => array('flex-flow', 0, array(
		'initial' => 'row',
		// we only need to add the shorthand prefixes to first item
		'prefixes' => array(
			'property' => array(
				'-webkit-flex-flow',
				'-moz-flex-flow',
				'-ms-flex-flow'
			)
		),
	)),
	'short_label' => esc_html__('Flex Direction', 'microthemer'),
	'label' => esc_attr__('Flex Direction', 'microthemer'),
	//'field-class' => 'icon-size-2',
	'input-class' => 'size-7',
	'type' => 'combobox',
	'select_options' => array(
		'row',
		'row-reverse',
		'column',
		'column-reverse',
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-flex-direction',
			'-moz-flex-direction',
			'-ms-flex-direction'
		)
	),
	//'sprite_type' => 'B',
	'icon' => '4, 1, B',
	// ref
	'ref_desc' => "<p>The direction the flex items are placed.</p>",
	'ref_values' => array(
		"row" => "Default. Items are placed from left to right",
		"row-reverse" => "Items are placed from right to left",
		"column" => "Items are placed from top to bottom",
		"column-reverse" => "Items are placed from bottom to top",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=flex-direction',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/flex-direction/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/flex-direction',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_flex-direction.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_flex-direction.asp',
	),
	'group_tutorials' => 1
);

// flex wrap (if rows should wrap onto further lines if dimensions exceed container)
$propertyOptions['flexbox']['flex_wrap'] = array(
	'sh' => array('flex-flow', 1, array('initial' => 'nowrap')),
	'short_label' => esc_html__('Flex Wrap', 'microthemer'),
	'label' => esc_attr__('Flex Wrap', 'microthemer'),
	'input-class' => 'size-6 last',
	'type' => 'combobox',
	'select_options' => array(
		'nowrap',
		'wrap',
		'wrap-reverse',
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-flex-wrap',
			'-moz-flex-wrap',
			'-ms-flex-wrap'
		)
	),
	//'sprite_type' => 'B',
	'icon' => '5, 1, B',
	// ref
	'ref_desc' => "<p>If flex items should wrap over multiple lines or not.</p>",
	'ref_values' => array(
		"nowrap" => "Default. Items will not wrap over multiple lines, even if their total lengths are larger than the container.",
		"wrap" => "Items will wrap over multiple lines, if their total lengths are larger than the container.",
		"wrap-reverse" => "Items will wrap over multiple lines as above, but in reverse order, and aligned from the end inwards.",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=flex-wrap',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/flex-wrap/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/flex-wrap',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_flex-wrap.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_flex-wrap.asp',
	),
	'group_tutorials' => 1
);

// justify content (main-axis)
$propertyOptions['flexbox']['justify_content'] = array(
	'short_label' => esc_html__('Justify Content', 'microthemer'),
	'label' => esc_attr__('Justify Content', 'microthemer'),
	'input-class' => 'size-7',
	//'field-class' => 'icon-size-4',
	'type' => 'combobox',
	'select_options' => array(
		'flex-start',
		'center',
		'flex-end',
		'space-between',
		'space-around',
		//'space-evenly', // not widely supported
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-justify-content',
		)
	),
	//'sprite_type' => 'B',
	'icon' => '12, 1, B',
	// ref
	'ref_desc' => "<p>Items are aligned along the 'main-axis', which means:</p>
	<p>Horizontal alignment if flex-direction is set to 'row' (default) or 'row-reverse'.<br />
	Vertical alignment if flex-direction is set to 'column' or 'column-reverse'.</p>",
	'ref_values' => array(
		"flex-start" => "Default. Items are left aligned if flex-direction is set to 'row'. Or top aligned if flex-direction is set to 'column'.",
		"center" => "Items are centered inside the container",
		"flex-end" => "Items are right aligned if flex-direction is set to 'row'. Or bottom aligned if flex-direction is is set to 'column'.",
		"space-between" => "Items are spaced, but flush against the edges of the container.",
		"space-around" => "Items are spaced, and there is space between the items and the edges of the container.",
		//"space-evenly" => "Items are horizontally spaced, with even spacing between items and the left and right edges of the container.", // not widely supported

	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=justify-content',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/j/justify-content/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/justify-content',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_justify-content.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_justify-content.asp',
	),
	'group_tutorials' => 1
);

// align items (cross-axis)
$propertyOptions['flexbox']['align_items'] = array(
	'short_label' => esc_html__('Align Items', 'microthemer'),
	'label' => esc_attr__('Align Items', 'microthemer'),
	'input-class' => 'size-3a',
	'field-class' => 'icon-size-3',
	'type' => 'combobox',
	'select_options' => array(
		'stretch',
		'flex-start',
		'center',
		'flex-end',
		'baseline'
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-align-items',
		)
	),
	//'sprite_type' => 'B',
	'icon' => '2, 1, B',

	// ref
	'ref_desc' => "<p>Items are aligned along the 'cross-axis', which means:</p>
<p>Vertical alignment if flex-direction is set to 'row' (default) or 'row-reverse'.<br >Horizontal alignment if flex-direction is set to 'column' or 'column-reverse'.</p>",

	'ref_values' => array(
		"stretch" => "Default. Items are stretched to fit the container (vertically if flex-direction is set to 'row' (default), horizontally if flex-direction is set to 'column')",
		"flex-start" => "Items are top aligned if flex-direction is set to 'row'. Or left aligned if flex-direction is set to 'column'",
		"center" => "Items are centered inside the container",

		"flex-end" => "Items are bottom aligned if flex-direction is set to 'row'. Or right aligned if flex-direction is is set to 'column'.",
		"baseline" => "Items are aligned according to the text baseline. This only becomes distinguishable from flex-start if the font-size of flex items varies. Here is a good demonstration of <a href='https://stackoverflow.com/questions/34606879/whats-the-difference-between-flex-start-and-baseline' target='_blank'>align-items: baseline</a>."

	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=align-items',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/a/align-items/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/align-items',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_align-items.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_align-items.asp',
	),
	'group_tutorials' => 1
);

// align content (multi-line cross-axis)
$propertyOptions['flexbox']['align_content'] = array(
	'short_label' => esc_html__('Align Content', 'microthemer'),
	'label' => esc_attr__('Align Content', 'microthemer'),
	//'linebreak' => 1,
	//'last_in_sub' => 1,
	'input-class' => 'size-7',
	'field-class' => 'icon-size-2',
	'type' => 'combobox',
	'select_options' => array(
		'stretch',
		'flex-start',
		'center',
		'flex-end',
		'space-between',
		'space-around'
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-align-content',
		)
	),
	//'sprite_type' => 'B',
	'icon' => '3, 1, B',
	// ref
	'ref_desc' => "<p>Align <b>multiple lines</b> of flex items along the 'cross-axis'. This property is to be used in conjunction with the flex-wrap property. It only takes effect when rows or columns of flex items wrap over multiple (horizontal or vertical) lines. And so align-content will not take effect if flex-wrap is set to 'nowrap'.</p>",
	'ref_values' => array(
		"stretch" => "Default value. Lines stretch to take up the remaining space",
		"flex-start" => "Lines bunch toward the center of the flex container",
		"center" => "Lines bunch toward the start of the flex container",
		"flex-end" => "	Lines bunch toward the end of the flex container",
		"space-between" => "Lines are spaced, but flush against the edges of the container.",
		"space-around" => "Lines are spaced, and there is half-size space between the edges of the container",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=align-content',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/a/align-content/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/align-content',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_align-content.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_align-content.asp',
	),
	'group_tutorials' => 1
);

// column-gap
$propertyOptions['flexbox']['column_gap_flex'] = array_merge($column_gap_config, array(
	'field-class' => '',
	'spacer_before' => '',
	'unit_sub_label' => '',
	'tutorials' => '',
	'icon-name' => 'column-gap',
));

// row-gap
$propertyOptions['flexbox']['row_gap_flex'] = array_merge($row_gap_config, array(
	'field-class' => '',
	'spacer_before' => '',
	'unit_sub_label' => '',
	'tutorials' => '',
	'icon-name' => 'row-gap',
));


// Flexbox (item) ***/

// flex grow (ratio numbers)
$propertyOptions['flexbox']['flex_grow'] = array(
	'sh' => array('flex', 0, array(
		'initial' => 'row',
		// we only need to add the shorthand prefixes to first item
		'prefixes' => array(
			'property' => array(
				'-webkit-flex',
			)
		),
	)),
	'animatable' => 1,
	'short_label' => esc_html_x('Flex Grow', 'noun', 'microthemer'),
	'label' => esc_attr_x('Flex Grow', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Flex Item', 'microthemer'),
	'sub_slug' => 'flexitem',
	'field-class' => 'icon-size-2',
	'input-class' => 'size-0b',
	'icon' => '7, 1, B',
	'select_options' => $flex_grow_shrink_order,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1,
		'increment' => .1
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-flex-grow',
		)
	),
	// ref
	'ref_desc' => "<p>When flex items don't fill the container fully, it is said that there is 'positive space' inside the container. The flex-grow property controls what ratio of this positive space is distributed between the flex items. It will NOT set the size of flex items relative to each other. Although this can sometimes happen as a result of distributing the available space. To find out why, <a target='_blank' href='https://css-tricks.com/flex-grow-is-weird/'>read this article</a>.</p>",
	'ref_values' => array(
		"(unitless number)" => "E.g. 1, 2, 3, 4, 5 etc (default value is 1)",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=flex-grow',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/flex-grow/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/flex-grow',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_flex-grow.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_flex-grow.asp',
	),
	'group_tutorials' => 1
);

// flex shrink
$propertyOptions['flexbox']['flex_shrink'] = array(
	'sh' => array('flex', 1, array('initial' => '1')),
	'animatable' => 1,
	'short_label' => esc_html_x('Flex Shrink', 'noun', 'microthemer'),
	'label' => esc_attr_x('Flex Shrink', 'noun', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-0b',
	'icon' => '8, 1, B',
	'select_options' => $flex_grow_shrink_order,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1,
		'increment' => .1
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-flex-shrink',
		)
	),
	// ref
	'ref_desc' => "<p>When flex items are too big for the container element, they auto-shrink down instead of overflowing. It is said that there is 'negative space' outside of the container. When flex-shrink is set to '1', all flex items have an even amount of space removed from them to ensure no overflow. Setting a higher flex-shrink value on a subset of flex-items will increase the amount of space removed from them in order to accommodate the negative space. The amount of negative space will be higher or lower depending on how much the flex items would overflow the container, were they allowed to do so.</p>
<p>Flex-shrink does nothing if flex-wrap is not set to 'nowrap' because items wrap onto new lines instead of overflowing the flex line or shrinking to fit.</p>",
	'ref_values' => array(
		"(unitless number)" => "E.g. 1, 2, 3, 4, 5 etc (default value is 1)",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=flex-shrink',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/flex-shrink/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/flex-shrink',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_flex-shrink.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_flex-shrink.asp',
	),
	'group_tutorials' => 1
);

// flex basis
$propertyOptions['flexbox']['flex_basis'] = array(
	'sh' => array('flex', 2, array('initial' => 'auto')),
	'animatable' => 1,
	'short_label' => esc_html_x('Flex Basis', 'noun', 'microthemer'),
	'label' => esc_attr_x('Flex Basis', 'noun', 'microthemer'),
	'field-class' => 'icon-size-2',
	//'input-class' => 'size-0b',
	'default_unit' => 'px',
	'icon' => '9, 1, B',
	//'type' => 'combobox',
	'select_options' => $flex_basis,
	'sug_values' => array(
		'this' => 1,
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-flex-basis',
		)
	),
	// ref
	'ref_desc' => "<p>Specifies the initial length of a flexible item. This will override any width value, unless flex-basis is set to auto (see below).</p>",
	'ref_values' => array(
		"auto" => "Default. The existing width of the flexible item is honoured. If the item has no explicit width set, the width will be according to its content.",
		"(numeric)" => "E.g. '400' would set the flex-basis to 400 pixels. Other commonly used units include 'em' and '%'. So you could enter '50%'",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=flex-basis',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/flex-basis/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/flex-basis',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_flex-basis.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_flex-basis.asp',
	),
	'group_tutorials' => 1
);

$align_self = array(
	'auto',
	'stretch',
	'flex-start',
	'center',
	'flex-end',
	'baseline'
);

// align self (vertical align)
$propertyOptions['flexbox']['align_self'] = array(
	'short_label' => esc_html__('Align Self', 'microthemer'),
	'label' => esc_attr__('Align Self', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-3',
	'type' => 'combobox',
	'select_options' => $align_self,
	'prefixes' => array(
		'property' => array(
			'-webkit-align-self',
		)
	),
	'icon' => '10, 1, B',
	// ref
	'ref_desc' => "<p>Vertical alignment. This overrides the align-items property, on a per-item basis using.</p>",
	'ref_values' => array(
		"auto" => "Default. The item inherits its parent container's 'align-items' property, or 'stretch' if it has no parent container",
		"stretch" => "The item is vertically stretched to fit the container",
		"center" => "The item is vertically centered inside the container",
		"flex-start" => "The item is top aligned",
		"flex-end" => "The item is bottom aligned",
		"baseline" => "The item is positioned at the baseline of the container."
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=align-self',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/a/align-self/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/align-self',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_align-self.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_align-self.asp',
	),
	'group_tutorials' => 1
);

// order (flex)
$propertyOptions['flexbox']['order'] = array(
	'animatable' => 1,
	'short_label' => esc_html_x('Order', 'noun', 'microthemer'),
	'label' => esc_attr_x('Order', 'noun', 'microthemer'),
	'field-class' => 'icon-size-2',
	'icon' => '11, 1, B',
	'select_options' => $flex_grow_shrink_order,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1,
		'min' => '',
		'increment' => 1
	),
	// ref
	'ref_desc' => "<p>Specifies the order of a flexible item relative to other flexible items in the container. The order property is only effective for flexible items inside a container that has the 'display' property set to 'flex' or 'inline-flex'. The default value for this property is 0, which means the natural order of flexible items in the HTML code will be honoured.</p>",
	'ref_values' => array(
		"(unitless number)" => "E.g. 1, 2, 3, 4, 5 etc (default value is 0)",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=flex-shrink',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/o/order/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/order',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_order.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_order.asp',
	),
	'group_tutorials' => 1
);


// Grid
// ------------------------------------------------------------


// duplicate display property so display:flex can be set more easily
$propertyOptions['grid']['display_grid'] = array(
	'short_label' => esc_html_x('Display', 'noun', 'microthemer'),
	'label' => esc_attr_x('Display (grid)', 'noun', 'microthemer'),
	'pg_label' => esc_attr__('Grid', 'microthemer'),
	'sub_label' => esc_html__('Grid', 'microthemer'),
	'sub_slug' => 'generalgrid',
	// configure toggle for container / items fields
	'dynamic_fields' => 'griditem',
	'field-class' => 'perma-show',
	'input-class' => 'size-4',
	'type' => 'combobox',
	'select_options' => array(
		'grid',
		'inline-grid',
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '23, 14',
	'icon-name' => 'display',
	// ref
	'ref_desc' => "<p>Set the display value to either 'grid' or 'inline-grid' to make an element a grid container. The direct children of the container element will behave as grid items.</p>",
	'ref_values' => array(
		"grid" => "Displays an element as a block-level grid container.",
		"inline-grid" => "Displays an element as an inline-level grid container."
	),
	'tutorials' => array(
		array(
			'url' => 'https://gridbyexample.com/video/',
			'title' => 'Learn Grid Layout Video Series',
		),
		array(
			'url' => 'https://learncssgrid.com/',
			'title' => 'Learn CSS Grid',
		),
		array(
			'url' => 'https://css-tricks.com/snippets/css/complete-guide-grid/',
			'title' => 'A Complete Guide to Grid',
		),
	),
	'group_tutorials' => 1
);

// grid-auto-flow
$propertyOptions['grid']['grid_auto_flow'] = array(
	'sh' => array(
		array('grid', 5, array('initial' => '', 'binary' => 1)),
	),
	'short_label' => esc_html_x('Auto flow', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid auto flow', 'noun', 'microthemer'),
	'field-class' => 'perma-show',
	'input-class' => 'size-4',
	//'highlight_grid_toggle' => 1,
	'text_before' => '<p>Position the current element as a <b>grid item</b></p>
<p>Note: the parent element must display as a grid</p>
<p><a class="learn-css-grid" href="https://gridbyexample.com/video/" target="_blank">Learn CSS grid - video series</a></p>',
	'type' => 'combobox',
	'select_options' => array(
		'row',
		'row dense',
		'column',
		'column dense',
		//'dense'
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '5, 8, B',
	// ref
	'ref_desc' => "<p>Define if the auto-flow axis should be row or column. And how items should pack together (dense).</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-auto-flow',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-auto-flow.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-auto-flow.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// column-gap
$propertyOptions['grid']['column_gap'] = $column_gap_config;

// row-gap
$propertyOptions['grid']['row_gap'] = $row_gap_config;

// explicit

// grid-template-columns
$propertyOptions['grid']['grid_template_columns'] = array(
	'sh' => array(
		array('grid', 1, array('initial' => 'none')),
		array('grid-template', 1, array('initial' => 'none')),
	),
	'short_label' => esc_html_x('Template columns', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid template columns', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Columns', 'microthemer'),
	'sub_slug' => 'templatecolumns',
	'variable_line' => 1,
	'array_values' => 1,
	'tab_control' => 'gridtemplate',
	'field-class' => 'gridcolumns icon-size-2',
	'tabs_before' => array(
		'gridtemplate' => esc_html__('Template', 'microthemer'),
		'gridauto' => esc_html__('Auto', 'microthemer'),
		'gridalign' => esc_html__('Align', 'microthemer'),
		'gridareas' => esc_html__('Areas', 'microthemer'),
		'allgriditems' => esc_html__('All', 'microthemer'),
		'griditems' => esc_html__('Item', 'microthemer')
		               . '<span class="mt-tab-txt grid-tab-txt pg-tab-txt grid-item-num"></span>',

	),
	'tabs_has_options' => 1,

	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => 'width',
			'plus' => array(
				'border-left-width',
				'border-right-width'
			)
		)
	),
	'default_unit' => 'px',
	'special_units' => $fraction_units,
	'select_options' => $grid_template_sizes,
	'select_options_extra' => $line_names,
	'sug_values' => array(
		'this' => 1,
	),
	'unit_rel' => 1,
	'unit_sub_label' => esc_html__('Template columns', 'microthemer'),
	'sug_values_extra' => $line_sug_config,
	'rel' => 'grid_template_columns',
	//'prefixes' => $flex_display_prefixes,
	'icon' => '1, 8, B',
	// ref
	'ref_desc' => "<p>Set the width of grid columns, and optional line names.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-template-columns',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-template-columns.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-template-columns.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid-template-rows
$propertyOptions['grid']['grid_template_rows'] = array(
	'sh' => array(
		array('grid', 0, array('initial' => 'none')),
		array('grid-template', 0, array('initial' => 'none')),

	),
	'short_label' => esc_html_x('Template rows', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid template rows', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Rows', 'microthemer'),
	'sub_slug' => 'templaterows',
	'variable_line' => 1,
	'array_values' => 1,
	'tab_control' => 'gridtemplate icon-size-2',
	'field-class' => 'gridrows',
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => 'height',
			'plus' => array(
				'border-top-width',
				'border-bottom-width'
			)
		)
	),
	'default_unit' => 'px',
	'special_units' => $fraction_units,
	'select_options' => $grid_template_sizes,
	'select_options_extra' => $line_names,
	'sug_values' => array(
		'this' => 1,
	),
	'unit_sub_label' => esc_html__('Template rows', 'microthemer'),
	'unit_rel' => 1,
	'sug_values_extra' => $line_sug_config,
	'rel' => 'grid_template_rows',
	//'prefixes' => $flex_display_prefixes,
	'icon' => '2, 8, B',
	// ref
	'ref_desc' => "<p>Set the height of grid rows, and optional line names.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-template-rows',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-template-rows.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-template-rows.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// implicit

// grid-auto-columns
$propertyOptions['grid']['grid_auto_columns'] = array(
	'sh' => array(
		array('grid', 4, array('initial' => 'auto')),
	),
	'short_label' => esc_html_x('Auto columns', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid auto columns', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Columns', 'microthemer'),
	'sub_slug' => 'autocolumns',
	'variable_line' => 1,
	'array_values' => 1,
	'tab_control' => 'gridauto',
	'field-class' => 'gridcolumns icon-size-2',
	'input-class' => 'size-4',
	'default_unit' => 'px',
	'special_units' => $fraction_units,
	'select_options' => $grid_sizes,
	'sug_values' => array(
		'this' => 1,
	),
	'unit_rel' => 1,
	'unit_sub_label' => esc_html__('Auto columns', 'microthemer'),
	'rel' => 'grid_auto_columns',
	//'prefixes' => $flex_display_prefixes,
	'icon' => '6, 8, B',
	// ref
	'ref_desc' => "<p>Set the width of <i>implicit</i> grid columns - those that are automatically 
generated when content flows outside of the <i>explicitly</i> defined grid-template-columns.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-auto-columns',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-auto-columns.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-auto-columns.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid-auto-rows
$propertyOptions['grid']['grid_auto_rows'] = array(
	'sh' => array(
		array('grid', 3, array('initial' => 'auto')),
	),
	'short_label' => esc_html_x('Auto rows', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid auto rows', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Rows', 'microthemer'),
	'sub_slug' => 'autorows',
	'variable_line' => 1,
	'array_values' => 1,
	'tab_control' => 'gridauto',
	'field-class' => 'gridrows icon-size-2',
	'input-class' => 'size-4',
	'default_unit' => 'px',
	'special_units' => $fraction_units,
	'select_options' => $grid_sizes,
	'sug_values' => array(
		'this' => 1,
	),
	'unit_rel' => 1,
	'unit_sub_label' => esc_html__('Auto rows', 'microthemer'),
	'rel' => 'grid_auto_rows',
	//'prefixes' => $flex_display_prefixes,
	'icon' => '7, 8, B',
	// ref
	'ref_desc' => "<p>Set the height of <i>implicit</i> grid rows - those that are automatically 
generated when content flows outside of the <i>explicitly</i> defined grid-template-rows.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-auto-rows',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-auto-rows.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-auto-rows.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// align

// justify items (x-axis)
$propertyOptions['grid']['justify_items_grid'] = array(
	'short_label' => esc_html__('Justify Items', 'microthemer'),
	'label' => esc_attr__('Justify Items', 'microthemer'),
	'sub_label' => esc_html__('Justify', 'microthemer'),
	'sub_slug' => 'gridjustify',
	'tab_control' => 'gridalign',
	//'input-class' => 'size-7',
	'field-class' => 'icon-size-2',
	'type' => 'combobox',
	'select_options' => array_merge($ja_items, array('auto')),
	'prefixes' => array(
		'property' => array(
			'-webkit-justify-content',
		)
	),
	//'sprite_type' => 'B',
	'icon' => '1, 1, B',
	// ref
	'ref_desc' => "<p>Align items inside their grid areas along the horizontal axis</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/justify-items',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_justify-items.cfm',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// justify content (main-axis)
$propertyOptions['grid']['justify_content_grid'] = array(
	'short_label' => esc_html__('Justify Content', 'microthemer'),
	'label' => esc_attr__('Justify Content (grid)', 'microthemer'),
	//'sub_slug' => 'justify',
	//'input-class' => 'size-7',
	'field-class' => 'icon-size-2',
	'tab_control' => 'gridalign',
	'type' => 'combobox',
	'select_options' => $ja_content,
	'prefixes' => array(
		'property' => array(
			'-webkit-justify-content',
		)
	),
	//'sprite_type' => 'B',
	'icon' => '12, 1, B',
	'icon-name' => 'justify-content',
	// ref
	'ref_desc' => "<p>Align multiple lines of content along the horizontal axis. Note, this effect will only be apparent if the content doesn't fill all of the available space (and so there is room for margins).</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/justify-content',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_justify-content.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_justify-content.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// align items (y-axis)
$propertyOptions['grid']['align_items_grid'] = array(
	'short_label' => esc_html__('Align Items', 'microthemer'),
	'label' => esc_attr__('Align Items (grid)', 'microthemer'),
	'sub_label' => esc_html__('Align', 'microthemer'),
	'sub_slug' => 'aligngrid',
	'tab_control' => 'gridalign',
	'input-class' => 'size-3',
	'field-class' => 'icon-size-2',
	'type' => 'combobox',
	'select_options' => $ja_items,
	'prefixes' => array(
		'property' => array(
			'-webkit-align-items',
		)
	),
	//'sprite_type' => 'B',
	'icon' => '2, 1, B',
	'icon-name' => 'align-items',
	// ref
	'ref_desc' => "<p>Align items inside their grid areas along the vertical axis.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/align-items',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_align-items.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_align-items.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// align content (multi-line cross-axis)
$propertyOptions['grid']['align_content_grid'] = array(
	'short_label' => esc_html__('Align Content', 'microthemer'),
	'label' => esc_attr__('Align Content (grid)', 'microthemer'),
	'tab_control' => 'gridalign',
	//'sub_slug' => 'align',
	//'linebreak' => 1,
	//'last_in_sub' => 1,
	//'input-class' => 'size-7',
	'field-class' => 'icon-size-2',
	'type' => 'combobox',
	'select_options' => $ja_content,
	'prefixes' => array(
		'property' => array(
			'-webkit-align-content',
		)
	),
	//'sprite_type' => 'B',
	'icon' => '3, 1, B',
	'icon-name' => 'align-content',
	// ref
	'ref_desc' => "<p>Align multiple lines of content along the vertical axis. Note, this effect will only be apparent if the content doesn't fill all of the available space (and so there is room for margins).</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/align-content',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_align-content.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_align-content.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// areas

// grid-template-areas
$propertyOptions['grid']['grid_template_areas'] = array(
	'sh' => array(
		array('grid', 2, array('initial' => 'none')),
		array('grid-template', 2, array('initial' => 'none')),
	),
	'short_label' => esc_html_x('Template areas', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid tempate areas', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Template Areas', 'microthemer'),
	'sub_slug' => 'areas',
	'grid_area_controls' => 1,
	'tab_control' => 'gridareas',
	'input-class' => 'size-4',
	'type' => 'textarea',
	'select_options' => array(
		'head',
		'main',
		'side',
		'foot',
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '8, 8, B',
	// ref
	'ref_desc' => "<p>Define named grid areas that grid items can be assigned to.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-template-areas',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-template-areas.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-template-areas.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// field for adding new template areas
$propertyOptions['grid']['grid_template_areas_add'] = array(
	'short_label' => esc_html_x('Add Template area', 'noun', 'microthemer'),
	'label' => esc_attr_x('Add Template area', 'noun', 'microthemer'),
	'tab_control' => 'gridareas',
	'input-class' => 'grid-area-add',
	//'type' => 'textarea',
	'select_options' => $template_areas_add,
	'icon' => '2, 4',
	'icon-name' => 'add',
	// ref
	'ref_desc' => "<p>Custom Microthemer option. Enter the name of a grid-area, then use the grid control canvas to define its size and position. Microthemer will then generate valid grid-template-areas ASCII art for you.</p>
<p>The grid-template-areas property is most useful when writing CSS grid code by hand - to visually see the structure of the grid. But Microthemer's grid control serves that function, so most users will not need to set grid-template-areas.</p>",
	'ref_values' => array(
		'name' => "A single word with no spaces.",
	)
);

// all items - for overriding flexbox or float layouts easily

$width_height_reset = array(
	'auto',
	'100%'
);

// grid_width
$propertyOptions['grid']['width_gridall'] = array(
	'short_label' => $propertyOptions['dimensions']['width']['short_label'],
	'label' => esc_html__('Width (all items)',  'microthemer'),
	'sub_label' => esc_html__('Size', 'microthemer'),
	'sub_slug' => 'allitemssize',
	'tab_control' => 'allgriditems',
	//'type' => 'combobox',
	'auto' => $propertyOptions['dimensions']['width']['auto'],
	'default_unit' => 'px',
	'select_options' => $width_height_reset,
	'sug_values' => $propertyOptions['dimensions']['width']['sug_values'],
	'icon' => '9',
	'icon-name' => 'width',
	// ref
	'ref_desc' => "<p>Set the width property of all grid items. Setting a value of '100%' or 'auto' makes grid items fill the full width of the grid tracks you define using grid-template-columns. This is useful when converting existing flexbox or float layouts to grid.</p>",
	'ref_links' => $propertyOptions['dimensions']['width']['ref_links'],
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid_height
$propertyOptions['grid']['height_gridall'] = array(
	'short_label' => $propertyOptions['dimensions']['height']['short_label'],
	'label' => esc_html__('height (all items)', 'microthemer'),
	'tab_control' => 'allgriditems',
	//'type' => 'combobox',
	'auto' => $propertyOptions['dimensions']['height']['auto'],
	'default_unit' => 'px',
	'select_options' => $width_height_reset,
	'sug_values' => $propertyOptions['dimensions']['height']['sug_values'],
	'icon' => '10',
	'icon-name' => 'height',
	// ref
	'ref_desc' => "<p>Set the height property of all grid items. Setting a value of '100%' or 'auto' makes grid items fill the full height of the grid tracks you define using grid-template-rows.</p>",
	'ref_links' => $propertyOptions['dimensions']['height']['ref_links'],
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid padding - note there is no other padding property so no _grid suffix (otherwise we get issues)
$propertyOptions['grid']['padding'] = array(
	'short_label' => $propertyOptions['padding_margin']['padding_top']['sub_label'],
	'label' => esc_html__('Padding (all items)',  'microthemer'),
	'sub_label' => esc_html__('Spacing', 'microthemer'),
	'sub_slug' => 'allitemsspacing',
	'tab_control' => 'allgriditems',
	//'type' => 'combobox',
	'auto' => $propertyOptions['padding_margin']['padding_top']['auto'],
	'default_unit' => 'px',
	'select_options' => $padding_lengths,
	'sug_values' => $propertyOptions['padding_margin']['padding_top']['sug_values'],
	'icon' => $propertyOptions['padding_margin']['padding_top']['icon'],
	'icon-name' => 'padding-top',
	// ref
	'ref_desc' => "<p>Set the padding shorthand property for all grid items.</p>",
	'ref_links' => $propertyOptions['padding_margin']['padding_top']['ref_links'],
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid margin - note there is no other margin property so no _grid suffix (otherwise we get issues)
$propertyOptions['grid']['margin'] = array(
	'short_label' => $propertyOptions['padding_margin']['margin_top']['sub_label'],
	'label' => esc_html__('Margin (all items)',  'microthemer'),
	'tab_control' => 'allgriditems',
	//'type' => 'combobox',
	'auto' => $propertyOptions['padding_margin']['margin_top']['auto'],
	'default_unit' => 'px',
	'select_options' => $margin_lengths,
	'sug_values' => $propertyOptions['padding_margin']['margin_top']['sug_values'],
	'icon' => $propertyOptions['padding_margin']['margin_top']['icon'],
	'icon-name' => 'margin-top',
	// ref
	'ref_desc' => "<p>Set the margin shorthand property for all grid items. Setting this to zero may sometimes be necessary when converting float layouts to grid, as float layouts typically create column and row gaps using margin.</p>",
	'ref_links' => $propertyOptions['padding_margin']['padding_top']['ref_links'],
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

$propertyOptions['grid']['grid_area_gridall'] = array(
	'short_label' => $grid_area['short_label'],
	'label' => esc_html__('Grid area (all items)',  'microthemer'),
	'sub_label' => $grid_area['sub_label'],
	'sub_slug' => 'allitemsarea',
	'tab_control' => 'allgriditems',
	'type' => 'combobox',
	'select_options' => array('auto'),
	'icon' => '14, 8, B',
	// ref
	'ref_desc' => "<p>Reset the grid-area of all grid items to 'auto'. This is useful for resetting grid positions on mobile.</p>",
	'ref_links' => $grid_area['ref_links'],
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

$propertyOptions['grid']['order_gridall'] = array(
	'short_label' => $grid_order['short_label'],
	'label' => esc_html__('Order (all items)',  'microthemer'),
	'sub_label' => $grid_order['sub_label'],
	'sub_slug' => 'allitemsother',
	'tab_control' => 'allgriditems',
	'type' => 'combobox',
	'select_options' => array('0'),
	'icon' => $grid_order['icon'],
	'icon-name' => $grid_order['icon-name'],
	// ref
	'ref_desc' => "<p>Reset the order of all grid items to '0'. This is useful for resetting the grid on mobile.</p>",
	'ref_links' => $grid_order['ref_links'],
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

$propertyOptions['grid']['z_index_gridall'] =  array(
	'short_label' => $grid_z_index['short_label'],
	'label' => esc_html__('Z-index (all items)',  'microthemer'),
	'tab_control' => 'allgriditems',
	'type' => 'combobox',
	'select_options' => array('auto'),
	'icon' => $grid_z_index['icon'],
	'icon-name' => $grid_z_index['icon-name'],
	// ref
	'ref_desc' => "<p>Reset the z-index of all grid items to 'auto'. This is useful for resetting the grid on mobile.</p>",
	'ref_links' => $grid_z_index['ref_links'],
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);;

// item

// grid-column-start
$propertyOptions['grid']['grid_column_start'] = array(
	'sh' => array(
		array('grid-area', 1, array('initial' => 'auto')),
		array('grid-column', 0, array('initial' => 'auto')),
	),
	'short_label' => esc_html_x('Start', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid column start', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Column', 'microthemer'),
	'sub_slug' => 'itemcolumn',
	//'nth_item_radios' => 1,
	'tab_control' => 'griditems',
	'select_options' => $grid_lines,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'grid_positions'
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '10, 8, B',
	// ref
	'ref_desc' => "<p>Set the column position a grid item should start at.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-column-start',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-column-start.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-column-start.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid-column-end
$propertyOptions['grid']['grid_column_end'] = array(
	'sh' => array(
		array('grid-area', 3, array('initial' => 'auto')),
		array('grid-column', 1, array('initial' => 'auto')),
	),
	'short_label' => esc_html_x('End', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid column end', 'noun', 'microthemer'),
	'tab_control' => 'griditems',
	'select_options' => $grid_lines_end,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'grid_positions'
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '11, 8, B',
	// ref
	'ref_desc' => "<p>Set the column position a grid item should end at.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-column-end',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-column-end.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-column-end.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid-row-start
$propertyOptions['grid']['grid_row_start'] = array(
	'sh' => array(
		array('grid-area', 0, array('initial' => 'auto')),
		array('grid-row', 0, array('initial' => 'auto')),
	),
	'short_label' => esc_html_x('Start', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid row start', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Row', 'microthemer'),
	'sub_slug' => 'itemrow',
	'tab_control' => 'griditems',
	//'spacer_before' => 1,

	'select_options' => $grid_lines,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'grid_positions'
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '12, 8, B',
	// ref
	'ref_desc' => "<p>Set the row position a grid item should start at.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-row-start',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-row-start.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-row-start.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid-row-end
$propertyOptions['grid']['grid_row_end'] = array(
	'sh' => array(
		array('grid-area', 2, array('initial' => 'auto')),
		array('grid-row', 1, array('initial' => 'auto')),
	),
	'short_label' => esc_html_x('End', 'noun', 'microthemer'),
	'label' => esc_attr_x('Grid row end', 'noun', 'microthemer'),
	'tab_control' => 'griditems',
	'select_options' => $grid_lines_end,
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'grid_positions'
	),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '13, 8, B',
	// ref
	'ref_desc' => "<p>Set the row position a grid item should end at.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/grid-row-end',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_grid-row-end.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/pr_grid-row-end.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);


// item align

// justify self
$propertyOptions['grid']['justify_self'] = array(
	'short_label' => esc_html_x('Justify self', 'noun', 'microthemer'),
	'label' => esc_attr_x('Justify self', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Justify', 'microthemer'),
	'sub_slug' => 'itemjustify',
	//'spacer_before' => 1,
	'type' => 'combobox',
	'tab_control' => 'griditems',
	'field-class' => 'icon-size-2',
	'select_options' => array_merge($ja_items, array('auto')),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '13, 1, B',
	// ref
	'ref_desc' => "<p>Align a single grid item inside its grid area along the horizontal axis</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/justify-self',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_justify-self.cfm',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// align self
$propertyOptions['grid']['align_self_grid'] = array(
	'short_label' => esc_html_x('Align self', 'noun', 'microthemer'),
	'label' => esc_attr_x('Align self (grid)', 'noun', 'microthemer'),
	'sub_label' => esc_html__('Align', 'microthemer'),
	'sub_slug' => 'itemalign',
	'type' => 'combobox',
	'tab_control' => 'griditems',
	'field-class' => 'icon-size-2',
	'select_options' => array_merge($ja_items, array('auto')),
	//'prefixes' => $flex_display_prefixes,
	'icon' => '10, 1, B',
	'icon-name' => 'align-self',
	// ref
	'ref_desc' => "<p>Align a single grid item inside its grid area along the vertical axis</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/align-self',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_align-self.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_align-self.asp',
	),
	'tutorials' => $grid_tutorials,
	'group_tutorials' => 1
);

// grid-area
$propertyOptions['grid']['grid_area'] = $grid_area;

// item other

// order (grid)
$propertyOptions['grid']['order_grid'] = $grid_order;

// grid z-index
$propertyOptions['grid']['z_index_grid'] = $grid_z_index;


// position
// ------------------------------------------------------------


$propertyOptions['position']['position'] = array(
	'short_label' => esc_html__('Position', 'microthemer'),
	'label' => esc_attr__('Position', 'microthemer'),
	'pg_label' => esc_attr__('Position', 'microthemer'),
	'sub_label' => esc_html__('Position', 'microthemer'),
	'sub_slug' => 'position',
	'new_pg_cat' => esc_attr__('Position', 'microthemer'), // for delimiting property group categories
	'input-class' => 'size-3',
	'type' => 'combobox',
	'select_options' => array(
		'absolute',
		'relative',
		'fixed',
		'static',
		'sticky'
	),
	'prefixes' => array(
		'values' => array(
			'sticky' => array('-webkit-sticky'),
		)
	),
	'icon' => '40, 14',
	// ref
	'ref_desc' => "<p>The position property is used to position an element.</p>",
	'ref_values' => array(
		"absolute" => "Generates an absolutely positioned element, positioned relative to the first parent element that has a position other than static. The element's position is specified with the 'left', 'top', 'right', and 'bottom' properties",
		"relative" => "	Generates a relatively positioned element, positioned relative to its normal position. The element's position is specified with the 'left', 'top', 'right', and 'bottom' properties",
		"fixed" => "Generates an absolutely positioned element, positioned relative to the browser window. The element's position is specified with the 'left', 'top', 'right', and 'bottom' properties",
		"static" => "Default. No position, the element occurs in the normal flow (ignores any top, bottom, left, right, or z-index declarations)",
		"sticky" => "<p>A stickily positioned element is an element whose computed position value is sticky. It's treated as relatively positioned until its containing block crosses a specified threshold (such as setting top to value other than auto) within its flow root (or the container it scrolls within), at which point it is treated as \"stuck\" until meeting the opposite edge of its containing block.</p>
<p>Getting sticky to work can be tricky sometimes. Certain CSS settings can break it, like an overflow setting of hidden, scroll, or auto on a parent element. Also you must set top, bottom, left, or right to something for it to work. See a discussion of sticky including <a href='https://themeover.com/forum/topic/cant-make-an-element-sticky/#post-11039' target='_blank'>6 things that can break it here</a>.</p>"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/p/position/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/position',
		'quackit' => 'http://www.quackit.com/css/properties/css_position.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_class_position.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://learnlayout.com/position.html',
			'title' => 'Learn CSS Layout: position',
		),
		array(
			'url' => 'http://www.elated.com/articles/css-positioning/',
			'title' => 'CSS Positioning',
		),
		array(
			'url' => 'http://learn.shayhowe.com/html-css/positioning-content/#uniquely-positioning-elements',
			'title' => 'Uniquely Positioning Elements',
		),
		array(
			'url' => 'http://www.barelyfitz.com/screencast/html-training/css/positioning/',
			'title' => 'Learn CSS Positioning in Ten Steps',
		),
	),
	'group_tutorials' => 1
);

$propertyOptions['position']['top'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Top', 'microthemer'),
	'label' => esc_attr__('Top (Position)', 'microthemer'),
	'field-class' => 'quad-top',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'select_options' => $position_lengths,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '11',
	// ref
	'ref_desc' => "<p>For absolutely positioned elements, the top property sets the top edge of an element to a unit above/below the top edge of its containing element. For relatively positioned elements, the top property sets the top edge of an element to a unit above/below its normal position. Negative values are allowed.</p>
	<p><b>Note</b>: the way an element moves on screen when you apply a positive 'top' value may seem counterintuitive. It moves down on the screen when given a positive value because the browser increases the distance between the top of the element and some reference point. If in doubt, just look at the direction of the property icon. The icon depicts the direction the element will move on the page as you increase the value for 'top'.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '75' would move an element 75 pixels below the top edge of its parent element (if the element is absolutely positioned), or 75px below it's normal position (if the element is relatively positioned)"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/t/top/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/top',
		'quackit' => 'http://www.quackit.com/css/properties/css_top.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_top.asp',
	),
	'tutorials' => $propertyOptions['position']['position']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['position']['bottom'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Bottom', 'microthemer'),
	'label' => esc_attr__('Bottom (Position)', 'microthemer'),
	'field-class' => 'quad-bottom',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'select_options' => $position_lengths,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '12',
	// ref
	'ref_desc' => "<p>For absolutely positioned elements, the bottom property sets the bottom edge of an element to a unit above/below the bottom edge of its containing element. For relatively positioned elements, the bottom property sets the bottom edge of an element to a unit above/below its normal position. Negative values are allowed.</p>
	<p><b>Note</b>: the way an element moves on screen when you apply a positive 'bottom' value may seem counterintuitive. It moves up on the screen when given a positive value because the browser increases the distance between the bottom of the element and some reference point. If in doubt, just look at the direction of the property icon. The icon depicts the direction the element will move on the page as you increase the value for 'bottom'.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '75' would move an element 75 pixels below the bottom edge of its parent element (if the element is absolutely positioned), or 75px below it's normal position (if the element is relatively positioned)"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/b/bottom/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/bottom',
		'quackit' => 'http://www.quackit.com/css/properties/css_bottom.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_bottom.asp',
	),
	'tutorials' => $propertyOptions['position']['position']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['position']['left'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Left', 'microthemer'),
	'label' => esc_attr__('Left (Position)', 'microthemer'),
	'field-class' => 'quad-left icon-size-2',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $position_lengths,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '13',
	// ref
	'ref_desc' => "<p>For absolutely positioned elements, the left property sets the left edge of an element to a unit to the
	left or right of the left edge of its containing element. For relatively positioned elements, the left property sets the left edge of an element to a unit to the left or right of its normal position. Negative values are allowed.</p>
	<p><b>Note</b>: the way an element moves on screen when you apply a positive 'left' value may seem counterintuitive. It moves right on the screen when given a positive value because the browser increases the distance between the left of the element and some reference point. If in doubt, just look at the direction of the icon. The icon depicts the direction the element will move on the page as you increase the value for 'left'.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '20' would move an element 20 pixels to the right of the left edge of its parent element (if the element is absolutely positioned), or 20px to the right of it's normal position (if the element is relatively positioned)"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/l/left/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/left',
		'quackit' => 'http://www.quackit.com/css/properties/css_left.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_left.asp',
	),
	'tutorials' => $propertyOptions['position']['position']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['position']['right'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Right', 'microthemer'),
	'label' => esc_attr__('Right (Position)', 'microthemer'),
	'field-class' => 'quad-right last',
	'auto' => array(
		'%' => array(
			'node' => 'parent',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $position_lengths,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '14',
	// ref
	'ref_desc' => "<p>For absolutely positioned elements, the right property sets the right edge of an element to a unit to the left or right of the right edge of its containing element. For relatively positioned elements, the right property sets the right edge of an element to a unit to the left or right of its normal position. Negative values are allowed.</p>
	<p><b>Note</b>: the way an element moves on screen when you apply a positive 'right' value may seem counterintuitive. It moves left on the screen when given a positive value because the browser increases the distance between the right of the element and some reference point. If in doubt, just look at the direction of the icon. The icon depicts the direction the element will move on the page as you increase the value for 'right'.</p>",
	'ref_values' => array(
		"(numeric)" => "e.g. '20' would move an element 20 pixels to the left of the right edge of its parent element (if the element is absolutely positioned), or 20px to the left of it's normal position (if the element is relatively positioned)"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/r/right/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/right',
		'quackit' => 'http://www.quackit.com/css/properties/css_right.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_right.asp',
	),
	'tutorials' => $propertyOptions['position']['position']['tutorials'],
	'group_tutorials' => 1
);

$propertyOptions['position']['z_index'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Z-index', 'microthemer'),
	'label' => esc_attr__('Z-index', 'microthemer'),
	'icon' => '21, 14',
	'select_options' => $z_index,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1,
		'min' => '',
		'increment' => 1
	),
	// ref
	'ref_desc' => "<p>The z-index property specifies the stack order of an element. An element with greater stack order is always in front of an element with a lower stack order. Note: z-index only works on positioned elements (position:absolute, position:relative, or position:fixed) and grid items.</p>",
	'ref_values' => array(
		"(numeric)" => "If you had 2 absolutely positioned elements that overlapped and you gave element A a z-index value of 5 and element B a z-index value of 10, element B would show in front of element A."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/z/z-index/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/z-index',
		'quackit' => 'http://www.quackit.com/css/properties/css_z-index.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_z-index.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.quackit.com/css/tutorial/css_layers.cfm',
			'title' => 'CSS Layers',
		),
		array(
			'url' => 'http://html.net/tutorials/css/lesson15.php',
			'title' => 'Layer on layer with z-index',
		),
		array(
			'url' => 'https://www.smashingmagazine.com/2009/09/the-z-index-css-property-a-comprehensive-look/',
			'title' => 'The Z-Index CSS Property: A Comprehensive Look',
		),
		array(
			'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Positioning/Understanding_z_index',
			'title' => 'Understanding CSS z-index',
		),
	)
);

$propertyOptions['position']['float'] = array(
	'short_label' => esc_html__('Float', 'microthemer'),
	'label' => esc_attr__('Float', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-0',
	'type' => 'combobox',
	'select_options' => array(
		'left',
		'right',
		'none'
	),
	'icon' => '18, 14',
	// ref
	'ref_desc' => "<p>The float property specifies whether or not an element should float. An element can either float left or right.</p>",
	'ref_values' => array(
		"left" => "The element floats to the left",
		"right" => "The element floats to the right",
		"none" => "The element is not floated. This is the default."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/f/float/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/float',
		'quackit' => 'http://www.quackit.com/css/properties/css_float.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_class_float.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://phrogz.net/css/understandingfloats.html',
			'title' => 'Understanding Floats. A mini-tutorial on how the CSS float and clear properties work',
		),
		array(
			'url' => 'https://css-tricks.com/all-about-floats/',
			'title' => 'All About Floats',
		),
		array(
			'url' => 'http://www.hongkiat.com/blog/css-floats/',
			'title' => 'CSS Floats Explained in 5 Questions',
		),
		array(
			'url' => 'https://www.smashingmagazine.com/2007/05/css-float-theory-things-you-should-know/',
			'title' => 'CSS Float Theory: Things You Should Know',
		),
		array(
			'url' => 'http://css.maxdesign.com.au/floatutorial/',
			'title' => 'Floatutorial: simple tutorials on CSS floats',
		),
		array(
			'url' => 'http://learn.shayhowe.com/html-css/positioning-content/#floats',
			'title' => 'Positioning with Floats',
		),
	)
);

$propertyOptions['position']['clear'] = array(
	'short_label' => esc_html__('Clear', 'microthemer'),
	'label' => esc_attr__('Clear', 'microthemer'),
	'field-class' => 'icon-size-2',
	'last_in_sub' => 1,
	'input-class' => 'size-0',
	'type' => 'combobox',
	'select_options' => array(
		'left',
		'right',
		'both',
		'none'
	),
	'icon' => '19, 14',
	// ref
	'ref_desc' => "<p>The clear property specifies which sides of an element where other floating elements are not allowed.</p>",
	'ref_values' => array(
		"left" => "No floating elements allowed on the left side",
		"right" => "No floating elements allowed on the right side",
		"both" => "No floating elements allowed on the left or the right side",
		"none" => "Default. Allows floating elements on both sides"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/c/clear/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/clear',
		'quackit' => 'http://www.quackit.com/css/properties/css_clear.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_class_clear.asp',
	),
	'tutorials' => $propertyOptions['position']['float']['tutorials'],
	'related_tutorials' => esc_html__('Float', 'microthemer')
);


// Transform
// ------------------------------------------------------------

$transform_tutorials = array(
	array(
		'url' => 'https://css-tricks.com/almanac/properties/t/transform/',
		'title' => 'CSS Tricks: Transform',
	),
	array(
		'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Transforms/Using_CSS_transforms',
		'title' => 'Mozilla: Transform',
	),
	array(
		'url' => 'https://3dtransforms.desandro.com/',
		'title' => '3D Transforms',
	)
);

// transform origin
$propertyOptions['transform']['transform_origin'] = array(
	'short_label' => esc_html__('Transform Origin', 'microthemer'),
	'label' => esc_attr__('Transform Origin', 'microthemer'),
	'pg_label' => esc_attr__('Transform', 'microthemer'),
	'sub_label' => esc_html__('General', 'microthemer'),
	'tab_control' => 'transformmisc',
	'sub_slug' => 'generaltransform',
	'field-class' => 'perma-show icon-size-2',
	'input-class' => 'size-7',
	'type' => 'combobox',
	'sug_values' => array(
		'this' => 1,
	),
	'select_options' => array_merge($background_position_values, array(
		'top left 10%',
		'right bottom 20px'
	)),
	'prefixes' => array(
		'property' => array(
			'-webkit-transform-origin',
		)
	),
	'icon' => '18, 12, B',
	// ref
	'ref_desc' => "<p>The transformation origin is the point around which a transformation is applied. For example, the transformation origin of the rotate() function is the center of rotation.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=transform-origin',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-origin',
		'quackit' => 'https://qhmit.com/css/css3/properties/css_transform-origin.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_transform-origin.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// transform style
$propertyOptions['transform']['transform_style'] = array(
	'short_label' => esc_html__('Transform Style', 'microthemer'),
	'label' => esc_html__('Transform Style', 'microthemer'),
	'tab_control' => 'transformmisc',
	'field-class' => 'perma-show',
	'input-class' => 'size-5',
	'type' => 'combobox',
	'select_options' => array(
		'flat',
		'preserve-3d'
	),
	'icon' => '19, 12, B',
	// ref
	'ref_desc' => "<p>The transform-style CSS property sets whether children of an element are positioned in the 3D space or are flattened in the plane of the element</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=transform-style',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-style',
		'quackit' => 'https://qhmit.com/css/css3/properties/css_transform-style.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_transform-style.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// backface visibility
$propertyOptions['transform']['backface_visibility'] = array(
	'short_label' => esc_html__('Backface Visibility', 'microthemer'),
	'label' => esc_html__('Backface Visibility', 'microthemer'),
	'tab_control' => 'transformmisc',
	'field-class' => 'perma-show icon-size-2',
	'input-class' => 'size-2',
	'type' => 'combobox',
	'select_options' => array(
		'visible',
		'hidden'
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-backface-visibility',
		)
	),
	'icon' => '20, 12, B',
	// ref
	'ref_desc' => "<p>The backface-visibility CSS property sets whether the back face of an element is visible when turned towards the user.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=backface-visibility',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/backface-visibility',
		'quackit' => 'https://qhmit.com/css/css3/properties/css_backface-visibility.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_backface-visibility.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// perspective origin
$propertyOptions['transform']['perspective_origin'] = array(
	'short_label' => esc_html__('Perspective Origin', 'microthemer'),
	'label' => esc_attr__('Perspective Origin', 'microthemer'),
	//'sub_label' => esc_html__('Perspective', 'microthemer'),
	'tab_control' => 'transformmisc',
	//'sub_slug' => 'perspective',
	'field-class' => 'icon-size-2',
	'input-class' => 'size-7',
	'type' => 'combobox',
	'sug_values' => array(
		'this' => 1,
	),
	'select_options' => $background_position_values,
	'prefixes' => array(
		'property' => array(
			'-webkit-perspective-origin',
		)
	),
	'icon' => '21, 12, B',
	// ref
	'ref_desc' => "<p>The perspective-origin CSS property determines the position at which the viewer is looking.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=perspective-origin',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/perspective-origin',
		'quackit' => 'https://qhmit.com/css/css3/properties/css_perspective-origin.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_perspective-origin.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// perspective
$propertyOptions['transform']['perspective'] = array(
	'short_label' => esc_html_x('Perspective (property)', 'microthemer'),
	'label' => esc_attr_x('Perspective (property)', 'microthemer'),
	'default_unit' => 'px',
	'field-class' => 'icon-size-2',
	'tab_control' => 'transformmisc',
	'select_options' => $perspective,
	'sug_values' => array(
		'this' => 1,
	),
	'unit_rel' => 1,
	'unit_sub_label' => esc_html__('Perspective', 'microthemer'),
	'icon' => '1, 12, B',
	// ref
	'ref_desc' => "<p>The perspective CSS <i>property</i> determines the distance between the z=0 plane and the user. Unlike the perspective <i>function</i>, the perspective property only acts on child elements, rather than the element it is set on. To apply a shared perspective to both parent and child elements, it is best to use the perspective function and set <i>transform-style</i> to <i>preserve-3d</i>.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/perspective',
		'quackit' => 'https://qhmit.com/css/css3/properties/css_perspective.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_perspective.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// perspective _function
$propertyOptions['transform']['perspective_function'] = array(
	'short_label' => esc_html_x('Perspective (function)', 'microthemer'),
	'label' => esc_attr_x('Perspective (function)', 'microthemer'),
	'css_func' => 0,
	'default_unit' => 'px',
	'field-class' => 'css-func-prop icon-size-2',
	'tab_control' => 'transformmisc',
	'select_options' => $perspective,
	'sug_values' => array(
		'this' => 1,
	),
	'unit_rel' => 1,
	'icon' => '1, 15, B',
	// ref
	'ref_desc' => "<p>The perspective CSS <i>function</i> determines the distance between the z=0 plane and the user. Unlike the perspective <i>property</i> (which <i>only</i> applies to children), it acts on the element itself. To extend to perspective to child elements, set <i>transform-style</i> to <i>preserve-3d</i>.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/perspective',
		'quackit' => 'https://qhmit.com/css/functions/css_perspective_function.cfm',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

/*// transform box - still marked as experimental
$propertyOptions['transform']['transform_box'] = array(
	'short_label' => esc_html__('Transform Box', 'microthemer'),
	'label' => esc_html__('Transform Box', 'microthemer'),
   'tab_control' => 'transformmisc',
	'field-class' => 'perma-show',
	'input-class' => 'size-3',
	'type' => 'combobox',
	'select_options' => array(
		'content-box',
		'border-box',
		'fill-box',
		'stroke-box',
		'view-box',
	),
	'icon' => '1, 4, B',
	// ref
	'ref_desc' => "<p>The transform-box CSS property defines the layout box to which the transform and transform-origin properties relate.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#search=transform-box',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-box',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);*/

/* TRANSLATE */

// translateX
$propertyOptions['transform']['translatex_function'] = array(
	'sh' => array(
		array('translate3d', 0, array('initial' => '0')),
		array('translate', 0, array('initial' => '0')),

	),
	'short_label' => esc_html_x('translateX', 'microthemer'),
	'label' => esc_attr_x('translateX', 'microthemer'),
	'sub_label' => esc_html__('Translate', 'microthemer'),
	'sub_slug' => 'translate',
	'tab_control' => 'transformtranslate',
	'css_func' => 0,
	'field-class' => 'css-func-prop icon-size-2',
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => 'width'
		)
	),
	'default_unit' => 'px',
	'select_options' => $translate,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'unit_rel' => 1,
	'unit_sub_label' => esc_html__('Translate', 'microthemer'),
	'icon' => '15, 12, B',
	// ref
	'ref_desc' => "<p>The translateX() CSS function repositions an element horizontally on the 2D plane.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/translatex',
		'quackit' => 'https://qhmit.com/css/functions/css_translatex_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// translateY
$propertyOptions['transform']['translatey_function'] = array(
	'sh' => array(
		array('translate3d', 1, array('initial' => '0')),
		array('translate', 1, array('initial' => '0')),

	),
	'short_label' => esc_html_x('translateY', 'microthemer'),
	'label' => esc_attr_x('translateY', 'microthemer'),
	'tab_control' => 'transformtranslate',
	'css_func' => 0,
	'field-class' => 'css-func-prop icon-size-2',
	'auto' => array(
		'%' => array(
			'node' => 'element',
			'prop' => 'height'
		)
	),
	'default_unit' => 'px',
	'select_options' => $translate,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'unit_rel' => 1,
	'icon' => '16, 12, B',
	// ref
	'ref_desc' => "<p>The translateY() CSS function repositions an element vertically on the 2D plane.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/translatey',
		'quackit' => 'https://qhmit.com/css/functions/css_translatey_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $translate,
	'group_tutorials' => 1
);

// translateZ
$propertyOptions['transform']['translatez_function'] = array(
	'sh' => array(
		array('translate3d', 2, array('initial' => '1')),
	),
	'short_label' => esc_html_x('translateZ', 'microthemer'),
	'label' => esc_attr_x('translateZ', 'microthemer'),
	'tab_control' => 'transformtranslate',
	'css_func' => 0,
	'field-class' => 'css-func-prop icon-size-2',
	'auto' => array(
		'%' => false
	),
	'default_unit' => 'px',
	'select_options' => $translate,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'unit_rel' => 1,
	'icon' => '17, 12, B',
	// ref
	'ref_desc' => "<p>The translateZ() CSS function repositions an element along the z-axis in 3D space, i.e., closer to or farther away from the viewer</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/translatez',
		'quackit' => 'https://qhmit.com/css/functions/css_translatez_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);


/* SCALE */

// scaleX
$propertyOptions['transform']['scalex_function'] = array(
	'sh' => array(
		array('scale3d', 0, array('initial' => '1')),
		array('scale', 0, array('initial' => '1')),

	),
	'short_label' => esc_html_x('scaleX', 'microthemer'),
	'label' => esc_attr_x('scaleX', 'microthemer'),
	'sub_label' => esc_html__('Scale', 'microthemer'),
	'sub_slug' => 'scale',
	'tab_control' => 'transformscale',
	'css_func' => 1,
	'field-class' => 'css-func-prop icon-size-2',
	'tabs_label' => esc_html__('Transform options', 'microthemer'),
	'tabs_before' => array(
		'transformmisc' => esc_html__('General', 'microthemer'),
		'transformtranslate' => esc_html__('Translate', 'microthemer'),
		'transformrotate' => esc_html__('Rotate', 'microthemer'),
		'transformskew' => esc_html__('Skew', 'microthemer'),
		'transformscale' => esc_html__('Scale', 'microthemer'),
		//'transformperspective' => esc_html__('Perspective', 'microthemer'),
	),
	'select_options' => $decimals,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1
	),
	'rel' => 'scale',
	'icon' => '11, 12, B',
	// ref
	'ref_desc' => "<p>The scaleX() CSS function defines a transformation that resizes an element along the x-axis (horizontally).</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/scalex',
		'quackit' => 'https://qhmit.com/css/functions/css_scalex_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// scaleY
$propertyOptions['transform']['scaley_function'] = array(
	'sh' => array(
		array('scale3d', 1, array('initial' => '1')),
		array('scale', 1, array('initial' => '1')),

	),
	'short_label' => esc_html_x('scaleY', 'microthemer'),
	'label' => esc_attr_x('scaleY', 'microthemer'),
	'tab_control' => 'transformscale',
	'css_func' => 1,
	'field-class' => 'css-func-prop icon-size-2',
	'select_options' => $decimals,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1
	),
	'rel' => 'scale',
	'icon' => '12, 12, B',
	// ref
	'ref_desc' => "<p>The scaleY() CSS function defines a transformation that resizes an element along the y-axis (vertically).</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/scaley',
		'quackit' => 'https://qhmit.com/css/functions/css_scaley_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// scaleZ
$propertyOptions['transform']['scalez_function'] = array(
	'sh' => array(
		array('scale3d', 2, array('initial' => '1')),
	),
	'short_label' => esc_html_x('scaleZ', 'microthemer'),
	'label' => esc_attr_x('scaleZ', 'microthemer'),
	'tab_control' => 'transformscale',
	'css_func' => 1,
	'field-class' => 'css-func-prop icon-size-2',
	'select_options' => $decimals,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1
	),
	'rel' => 'scale',
	'icon' => '13, 12, B',
	// ref
	'ref_desc' => "<p>The scaleZ() CSS function defines a transformation that resizes an element along the z-axis..</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/scalez',
		'quackit' => 'https://qhmit.com/css/functions/css_scalez_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);


/* ROTATE */

// rotateX
$propertyOptions['transform']['rotatex_function'] = array(
	/*'sh' => array(
		//array('rotate', 0, array('initial' => '1')), // same as rotateZ oddly
		//array('rotate3d', 0, array('initial' => '0')), // not easily interchangeable with rotateX, Y, Z and requires 4 value)
	),*/
	'short_label' => esc_html_x('rotateX', 'microthemer'),
	'label' => esc_attr_x('rotateX', 'microthemer'),
	'sub_label' => esc_html__('rotate', 'microthemer'),
	'sub_slug' => 'rotate',
	'tab_control' => 'transformrotate',
	'css_func' => '0deg',
	'field-class' => 'css-func-prop icon-size-2',
	'select_options' => $degrees,
	'default_unit' => 'deg',
	'special_units' => $angle_units,
	'sug_values' => array(
		'this' => 1,
		'min' => '',
		//'increment' => .1
	),
	'icon' => '7, 12, B',
	// ref
	'ref_desc' => "<p>The rotateX() CSS function defines a transformation that rotates an element around the abscissa (horizontal axis) without deforming it.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/rotatex',
		'quackit' => 'https://qhmit.com/css/functions/css_rotatex_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// rotateY
$propertyOptions['transform']['rotatey_function'] = array(
	/*'sh' => array(
		//array('rotate', 0, array('initial' => '1')), // same as rotateZ oddly
		//array('rotate3d', 1, array('initial' => '0')), // not easily interchangeable with rotateX, Y, Z and requires 4 value)
	),*/
	'short_label' => esc_html_x('rotateY', 'microthemer'),
	'label' => esc_attr_x('rotateY', 'microthemer'),
	'tab_control' => 'transformrotate',
	'css_func' => '0deg',
	'field-class' => 'css-func-prop icon-size-2',
	'select_options' => $degrees,
	'default_unit' => 'deg',
	'special_units' => $angle_units,
	'sug_values' => array(
		'this' => 1,
		'min' => '',
		//'increment' => .1
	),
	'icon' => '8, 12, B',
	// ref
	'ref_desc' => "<p>The rotateY() CSS function defines a transformation that rotates an element around the ordinate (vertical axis) without deforming it.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/rotatey',
		'quackit' => 'https://qhmit.com/css/functions/css_rotatey_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// rotateZ
$propertyOptions['transform']['rotatez_function'] = array(
	/*'sh' => array(
		//array('rotate', 0, array('initial' => '1')), // same as rotateZ oddly
		//array('rotate3d', 2, array('initial' => '0')), // not easily interchangeable with rotateX, Y, Z and requires 4 value)
	),*/
	'short_label' => esc_html_x('rotateZ', 'microthemer'),
	'label' => esc_attr_x('rotateZ', 'microthemer'),
	'tab_control' => 'transformrotate',
	'css_func' => '0deg',
	'field-class' => 'css-func-prop icon-size-2',
	'select_options' => $degrees,
	'default_unit' => 'deg',
	'special_units' => $angle_units,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '9, 12, B',
	// ref
	'ref_desc' => "<p>The rotateZ() CSS function defines a transformation that rotates an element around the z-axis without deforming it.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/rotatez',
		'quackit' => 'https://qhmit.com/css/functions/css_rotatez_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);


/* SKEW */

// skewX
$propertyOptions['transform']['skewx_function'] = array(
	'sh' => array(
		array('skew', 0, array('initial' => '1')),
	),
	'short_label' => esc_html_x('skewX', 'microthemer'),
	'label' => esc_attr_x('skewX', 'microthemer'),
	'sub_label' => esc_html__('skew', 'microthemer'),
	'sub_slug' => 'skew',
	'tab_control' => 'transformskew',
	'css_func' => '0deg',
	'field-class' => 'css-func-prop icon-size-2',
	'select_options' => $degrees,
	'default_unit' => 'deg',
	'special_units' => $angle_units,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '3, 12, B',
	// ref
	'ref_desc' => "<p>The skewX() CSS function defines a transformation that skews an element in the horizontal direction on the 2D plane.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/skewx',
		'quackit' => 'https://qhmit.com/css/functions/css_skewx_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);

// skewY
$propertyOptions['transform']['skewy_function'] = array(
	'sh' => array(
		array('skew', 1, array('initial' => '1')),
	),
	'short_label' => esc_html_x('skewY', 'microthemer'),
	'label' => esc_attr_x('skewY', 'microthemer'),
	'tab_control' => 'transformskew',
	'css_func' => '0deg',
	'field-class' => 'css-func-prop icon-size-2',
	'select_options' => $degrees,
	'default_unit' => 'deg',
	'special_units' => $angle_units,
	'sug_values' => array(
		'this' => 1,
		'min' => ''
	),
	'icon' => '4, 12, B',
	// ref
	'ref_desc' => "<p>The skewY() CSS function defines a transformation that skews an element in the vertical direction on the 2D plane.</p>",
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/skewy',
		'quackit' => 'https://qhmit.com/css/functions/css_skewy_function.cfm',
		'w3s' => 'https://www.w3schools.com/css/css3_2dtransforms.asp',
	),
	'tutorials' => $transform_tutorials,
	'group_tutorials' => 1
);









// Animation
// ------------------------------------------------------------

// animation name
$propertyOptions['animation']['animation_name'] = array(
	'sh' => array('animation', 0, array(
		'initial' => 'none',
		'optional' => 1,
		'prefixes' => array(
			'property' => array(
				'-webkit-animation',
			)
		),
	)),
	'short_label' => esc_html__('Name', 'microthemer'),
	'label' => esc_attr__('Animation Name', 'microthemer'),
	'pg_label' => esc_attr__('Animation', 'microthemer'),
	'new_pg_cat' => esc_attr__('Animate', 'microthemer'), // for delimiting property group categories
	// play button, trigger, add row icons
	'pg_controls' => array(
		'items' => array(
			'play' => array(
				'type' => 'icon',
				'wrapper' => 'anim-play-wrap',
				'class' => 'play-icon play-anim control-icon',
				'title'  => esc_attr__('Play animation', 'microthemer')
			),
			/* have clickable timetlime to show animation progress, and can be paused at moment.
			'timeline' => array(
				'type' => 'combobox',
				'class' => 'event-trigger property-input',
				'label'  => esc_html__('Trigger', 'microthemer'),
				'rel' => 'anim_triggers',
			)
			*/
		)
	),
	'sub_label' => esc_html__('Animation', 'microthemer'),
	'sub_slug' => 'animation',
	//'new_pg_cat' => esc_attr__('Animate', 'microthemer'),
	'field-class' => 'has-category-combo',
	'input-class' => 'size-8a',
	'type' => 'combobox',
	'sug_values' => array(
		'this' => 1,
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-animation-name',
		)
	),
	'icon' => '1, 4, B',
	// ref
	'ref_desc' => "<p>The name of an animation that has been defined using an <b>@key-frames</b> rule. Microthemer provides some ready made animations from <a href='https://daneden.github.io/animate.css/'>animate.css</a> by Daniel Eden. You can also define your own @keyframes via Microthemer's custom code editor, and then reference the name in the animation-name GUI field.</p>",
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/css-animation/embed/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-name',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_animation-name.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-name.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://robots.thoughtbot.com/css-animation-for-beginners',
			'title' => 'CSS Animation for beginners',
		),
		array(
			'url' => 'https://css-tricks.com/almanac/properties/a/animation/',
			'title' => 'CSS Tricks: Animation',
		),
		array(
			'url' => 'https://www.w3schools.com/css/css3_animations.asp',
			'title' => 'W3 Schools: CSS3 Animations',
		),
		array(
			'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Animations/Using_CSS_animations',
			'title' => 'Moz: Using CSS Animations',
		),
	),
	'group_tutorials' => 1
);

// animation duration
$propertyOptions['animation']['animation_duration'] = array(
	'sh' => array('animation', 1,
		array(
			'initial' => '0s',
			'optional' => 1
		)
	),
	'short_label' => esc_html__('Duration', 'microthemer'),
	'label' => esc_attr__('Animation Duration', 'microthemer'),
	'input-class' => 'size-0b animation_duration',
	//'type' => 'combobox',
	'select_options' => array(
		'0',
		'0.25',
		'0.5',
		'0.75',
		'1',
		'2',
		'3',
		'4',
		'5',
	),
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'time'
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-animation-duration',
		)
	),
	'default_unit' => 's',
	'special_units' => $time_units,
	'icon' => '2, 4, B',
	// ref
	'ref_desc' => "<p>The time taken for an animation to complete one cycle.</p>",
	'ref_values' => array(
		"(time)" => "e.g. '2s' would set the duration to 2 seconds. If you want to set the time in milliseconds you could enter '1500ms', equivalent to 1.5 seconds."
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-duration',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_animation-duration.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-duration.asp',
	),
	'tutorials' => $propertyOptions['animation']['animation_name']['tutorials'],
	'group_tutorials' => 1
);

// animation timing function
$propertyOptions['animation']['animation_timing_function'] = array(
	'sh' => array('animation', 2, array(
		'initial' => 'ease',
		'optional' => 1
	)),
	'short_label' => esc_html__('Timing Function', 'microthemer'),
	'label' => esc_attr__('Animation Timing Function', 'microthemer'),
	'input-class' => 'size-2',
	'sug_values' => array(
		'this' => 1,
		//'forceSug' => 1
		'root_cat' => 'timing_function'
	),
	//'type' => 'combobox',
	'select_options' => array(
		'ease',
		'ease-in',
		'ease-out',
		'ease-in-out',
		'linear',
		'step-start',
		'step-end',
		'steps(2, start)',
		'steps(4, end)',
		'steps(10)',
		/*'cubic-bezier(0.6, -0.28, 0.735, 0.045)',
		'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
		'cubic-bezier(0.68, -0.55, 0.265, 1.55)',*/
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-animation-timing-function',
		)
	),
	'icon' => '3, 4, B',
	// ref
	'ref_desc' => "<p>Specifies the speed curve of an animation. This is used to make one set of CSS styles change into another set smoothly or jerkily (using steps).</p>",
	'ref_values' => array(
		"ease" => "Default. The animation has a slow start, then fast, before it ends slowly",
		"ease-in" => "The animation starts slowly",
		"ease-out" => "The animation ends slowly",
		"ease-in-out" => "The animation starts and ends slowly",
		"linear" => "The animation has the same speed throughout",
		"step-start" => "The animation jerks from start to end rather than being smooth. Equivalent to 'steps(1, start)'",
		"step-end" => "The animation jerks from end to start rather than being smooth. Equivalent to 'steps(1, end)'",
		"steps(int, start|end)" => "Custom function for defining the number of steps and direction of the jerky animation. The first value, number of steps, must be a positive number. The second value must be either 'start' or 'end'. It specifies the direction.",
		"cubic-bezier(n,n,n,n)" => "Custom function for fine grained control over the speed of the animation. Microthemer provides some examples to get you started.",
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-timing-function',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_animation-timing-function.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-timing-function.asp',
	),
	// this property needs own tutorials too
	'tutorials' => array_merge(
		array(
			array(
				'url' => 'https://www.smashingmagazine.com/2014/04/understanding-css-timing-functions/',
				'title' => 'Understanding The CSS3 transition-timing-function Property',
			),
			array(
				'url' => 'https://designmodo.com/steps-css-animations/',
				'title' => 'How to Use steps() in CSS Animations',
			)
		),
		$propertyOptions['animation']['animation_name']['tutorials']
	),
	'group_tutorials' => 1
);

// animation delay
$propertyOptions['animation']['animation_delay'] = array(
	'sh' => array('animation', 3, array(
		'initial' => '0s',
	    'optional' => 1
	)),
	'short_label' => esc_html__('Delay', 'microthemer'),
	'label' => esc_attr__('Animation Delay', 'microthemer'),
	'input-class' => 'size-0b',
	//'type' => 'combobox',
	'select_options' => array(
		'0',
		'.25',
		'.5',
		'.75',
		'1',
		'2',
		'3',
		'-1',
	),
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'time',
		'min' => ''
	),

	'prefixes' => array(
		'property' => array(
			'-webkit-animation-delay',
		)
	),
	'default_unit' => 's',
	'special_units' => $time_units,
	'icon' => '4, 4, B',
	// ref
	'ref_desc' => "<p>The delay before an animation should start. Negative values are allowed. Negative values cause the animation to start part way through its cycle, rather than from the start.</p>",
	'ref_values' => array(
		"(time)" => "e.g. '2s' would start the animation 2 seconds after the animation is applied to the element. The default value of '0s' starts the animation immediately. And '-1s' would also start the animation immediately, but 1 second through its cycle, thus skipping the first part."
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-delay',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_animation-delay.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-delay.asp',
	),
	'tutorials' => $propertyOptions['animation']['animation_name']['tutorials'],
	'group_tutorials' => 1

);

// animation iteration count
$propertyOptions['animation']['animation_iteration_count'] = array(
	'sh' => array('animation', 4, array(
		'initial' => '1',
		'optional' => 1
	)),
	'short_label' => esc_html__('Iteration Count', 'microthemer'),
	'label' => esc_attr__('Animation Iteration Count', 'microthemer'),
	'input-class' => 'size-2',
	//'type' => 'combobox',
	'select_options' => array(
		'1',
		'2',
		'3.5',
		'infinite',
	),
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1,
		'increment' => .1
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-animation-iteration-count',
		)
	),
	'icon' => '5, 4, B',
	// ref
	'ref_desc' => "<p>The number of times an animation should play.</p>",
	'ref_values' => array(
		"(number)|infinite" => "e.g. '2' would play the animation twice. The keyword 'infinite' plays the animation continuously. Tip: it is common to set the animation-direction property to 'alternate' if an animation has been set to play more than once."
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-iteration-count',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_animation-iteration-count.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-iteration-count.asp',
	),
	'tutorials' => $propertyOptions['animation']['animation_name']['tutorials'],
	'group_tutorials' => 1
);

// animation direction
$propertyOptions['animation']['animation_direction'] = array(
	'sh' => array('animation', 5, array(
		'initial' => 'normal',
		'optional' => 1
	)),
	'short_label' => esc_html__('Direction', 'microthemer'),
	'label' => esc_attr__('Animation Direction', 'microthemer'),
	'input-class' => 'size-4',
	'type' => 'combobox',
	'select_options' => array(
		'normal',
		'reverse',
		'alternate',
		'alternate-reverse',
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-animation-direction',
		)
	),
	'icon' => '6, 4, B',
	// ref
	'ref_desc' => "<p>The direction the animation should play.</p>",
	'ref_values' => array(
		"normal" => "Default. The animation plays in the normal direction set by the keyframes",
		'reverse' => "The animation plays in reverse",
		'alternate' => "The animation plays normally on odd iterations, and in reverse on even",
		'alternate-reverse' => "The animation plays in reverse on odd iterations, and in normally on even",
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-direction',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_animation-direction.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-direction.asp',
	),
	'tutorials' => $propertyOptions['animation']['animation_name']['tutorials'],
	'group_tutorials' => 1
);

// animation fill mode
$propertyOptions['animation']['animation_fill_mode'] = array(
	'sh' => array('animation', 6, array(
		'initial' => 'none',
		'optional' => 1
	)),
	'short_label' => esc_html__('Fill Mode', 'microthemer'),
	'label' => esc_attr__('Animation Fill Mode', 'microthemer'),
	'input-class' => 'size-4a',
	'type' => 'combobox',
	'select_options' => array(
		'none',
		'forwards',
		'backwards',
		'both',
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-animation-fill-mode',
		)
	),
	'icon' => '7, 4, B',
	// ref
	'ref_desc' => "<p>Specifies if the animation styles should precede execution during the delay period (backwards) or remain after completion (forwards).</p>",
	'ref_values' => array(
		"none" => "Default. The animation will not apply any styles to the element when it's not executing.",
		'forwards' => "The CSS styles specified in the LAST keyframe will apply to the element AFTER the animation finishes.",
		'backwards' => "The CSS styles specified in the FIRST keyframe will apply to the element during the DELAY PERIOD before the animation starts. Note, the LAST keyframe will be used instead of the first if animation-direction is set to 'reverse' or 'alternate-reverse'.",
		'both' => "The animation will follow the rules for both forwards and backwards, thus extending the animation properties in both directions.",
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-fill-mode',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_animation-fill-mode.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-fill-mode.asp',
	),
	'tutorials' => $propertyOptions['animation']['animation_name']['tutorials'],
	'group_tutorials' => 1
);

// animation play state
$propertyOptions['animation']['animation_play_state'] = array(
	'sh' => array('animation', 7, array(
		'initial' => 'running',
		'optional' => 1
	)),
	'short_label' => esc_html__('Play State', 'microthemer'),
	'label' => esc_attr__('Animation Play State', 'microthemer'),
	'input-class' => 'size-2',
	'type' => 'combobox',
	'select_options' => array(
		'paused',
		'running',
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-animation-play-state',
		)
	),
	'icon' => '8, 4, B',
	// ref
	'ref_desc' => "<p>Specifies whether the animation is running or paused. This CSS value is often queried or set via JavaScript</p>",
	'ref_values' => array(
		"running" => "Default. The animation is currently playing.",
		'paused' => "The animation is currently paused.",
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/animation-play-state',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_animation-play-state.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_animation-play-state.asp',
	),
	'tutorials' => $propertyOptions['animation']['animation_name']['tutorials'],
	'group_tutorials' => 1
);

// animation event (custom)
$propertyOptions['animation']['event'] = array(
	'short_label' => esc_html__('Event', 'microthemer'),
	'label' => esc_attr__('Animation Event', 'microthemer'),
	'field-class' => 'is-event-field',
	'input-class' => 'size-6 mt-event-input',
	'type' => 'combobox',
	'icon' => '9, 4, B',
	// ref
	'ref_desc' => "<p>Custom Microthemer option. Choose an event that triggers the animation. If you leave this field blank, the animation will run as soon as the page loads.</p>
<p>Note: if you choose a JavaScript event (e.g. inView or click), Microthemer will include an extra JS file (animation-events.js) on your site to enable this behaviour.</p>",
	'ref_values' => array(
		'hover' => "(CSS event) The animation will run when a user hovers over the element.",
		'focus' => "(CSS event) For forms fields and other elements that can gain focus. The animation will run when the element gains focus.",
		'inView' => "(JS event) The animation will run <i>when ever</i> the element scrolls into view.",
		'inView (once)' => "(JS event) The animation will run <i>once</i> if the element scrolls into view.",
		'click' => "(JS event) The animation will run when a user clicks the element.",
	)
);


// use common dom relative explanation and ref values for anim/trans event targets
$dom_relative_text = '<p>But what if you only want the effect to apply to a single element, relative to the position of the triggering element? Imagine an FAQs page with multiple headings and answer blocks. You apply a click event to the headings to reveal answers, but you only want this to reveal the single answer immediately following the heading that was clicked - not all answers on the page. Microthemer supports this behaviour with jQuery-style syntax entered into the same input field separated with a pipe e.g. <i>.faq-answer|next()</i>. See below for details and more examples.</p>';
$event_target_ref_values = array(
	'(selector)' => "Enter a CSS selector e.g. <i>img.avatar</i>",
	'(selector|path syntax)' => "<p>Enter a CSS selector e.g. <i>img.avatar</i>, followed by a pip (|), followed by jQuery-style dom selection syntax e.g. <i>closest('article')</i>. All together, this would look like: <i>img.avatar|closest('article')</i>.</p>
<p>Chaining with dots (.) is supported. Quotes, brackets and selectors inside brackets are optional. Microthemer will default to using the selector entered before the pipe for the following directives: <i>closest, parents, find, children, siblings</i>. Microthemer also supports these directives (without defaulting to a selector): <i>prev, next, parent</i>.</p> 
<p>Example: <i>img.avatar|next.find</i> equates to <i>img.avatar|next().find('img.avatar')</i></p>",
);

// animation event target (custom)
$propertyOptions['animation']['event_target'] = array(
	'short_label' => esc_html__('JS Event Target', 'microthemer'),
	'label' => esc_attr__('Animation Event Target', 'microthemer'),
	'field-class' => 'event-target-field is-event-field',
	'input-class' => 'size-big event-target-input',
	'icon' => '10, 4, B',
	'sug_values' => array(
		'this' => 1,
	),
	// ref
	'ref_desc' => "<p>Custom Microthemer option. Choose which element the animation should be applied to once it is triggered by a JavaScript event (this will not work with CSS events). If you leave this field blank, the animation will be applied to the element the current selector targets - the single element that triggered the event. But sometimes you might want to specify a custom CSS selector, so you can apply the animation to different element(s). For example, clicking one element could animate a related element close by.</p>"
 . $dom_relative_text,
	'ref_values' => $event_target_ref_values
);


// Transition
// ------------------------------------------------------------

// transition property
$propertyOptions['transition']['transition_property'] = array(
	'sh' => array('transition', 0, array(
		'initial' => 'all',
		'optional' => 1,
		// we only need to add the shorthand prefixes to first item
		'prefixes' => array(
			'property' => array(
				'-webkit-transition',
			)
		),
	)),
	'short_label' => esc_html__('Property', 'microthemer'),
	'label' => esc_attr__('Transition Property', 'microthemer'),
	'pg_label' => esc_attr__('Transition', 'microthemer'),
	'sub_label' => esc_html__('Transition', 'microthemer'),
	'sub_slug' => 'transition',
	//'new_pg_cat' => esc_attr__('Animate', 'microthemer'),
	'field-class' => 'has-category-combo',
	'input-class' => 'size-big',
	'type' => 'combobox',
	'sug_values' => array(
		'this' => 1,
	),
	'prefixes' => array(
		'property' => array(
			'-o-transition-property',
			'-moz-transition-property',
			'-webkit-transition-property',
		)
	),
	'icon' => '12, 4, B',
	// ref
	'ref_desc' => "<p>The name of the CSS property that should transition when its value changes. CSS property values can change in response to user events, like hovering over an element. The background-color of a button might change on hover for instance. And if background-color was specified as a transition property, this color change would transition gracefully. Going from the original color to the new color via various colors in between.</p>
<p>Not all CSS properties are animatable and so eligible to transition. Microthemer lists those that can in the transition property menu. Properties that are not yet supported with GUI fields are not listed (e.g. columns), but can be entered manually.</p>",
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/#feat=css-transitions',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transition-property',
		'quackit' => 'https://www.quackit.com/css/css3/properties/css_transition-property.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_transition-property.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://marksheet.io/css-transitions.html',
			'title' => 'CSS transitions: From one rule to another',
		),
		array(
			'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Transitions/Using_CSS_transitions',
			'title' => 'Using CSS Transitions',
		),
		array(
			'url' => 'https://blog.alexmaccaw.com/css-transitions',
			'title' => 'All you need to know about CSS Transitions',
		),
		array(
			'url' => 'https://css-tricks.com/different-transitions-for-hover-on-hover-off/',
			'title' => 'Different Transitions for Hover On / Hover Off',
		),
	),
	'group_tutorials' => 1
);

// transition duration
$propertyOptions['transition']['transition_duration'] = array(
	'sh' => array('transition', 1,
		array(
			'initial' => '0s',
			'optional' => 1
		)
	),
	'short_label' => esc_html__('Duration', 'microthemer'),
	'label' => esc_attr__('Transition Duration', 'microthemer'),
	'input-class' => 'size-0b transition_duration',
	'select_options' => $propertyOptions['animation']['animation_duration']['select_options'],
	'sug_values' => array(
		'this' => 1,
		//'forceSug' => 1
		'root_cat' => 'time'
	),
	'prefixes' => array(
		'property' => array(
			'-o-transition-duration',
			'-moz-transition-duration',
			'-webkit-transition-duration',
		)
	),
	'default_unit' => 's',
	'special_units' => $time_units,
	'icon' => '2, 4, B',
	'icon-name' => 'animation-duration',
	// ref
	'ref_desc' => "<p>The time taken for the transition to complete one cycle.</p>",
	'ref_values' => array(
		"(time)" => "e.g. '2s' would set the duration to 2 seconds. If you want to set the time in milliseconds you could enter '1500ms', equivalent to 1.5 seconds."
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transition-duration',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_transition-duration.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_transition-duration.asp',
	),
	'tutorials' => $propertyOptions['transition']['transition_property']['tutorials'],
	'group_tutorials' => 1
);

// transition timing function
$propertyOptions['transition']['transition_timing_function'] = array(
	'sh' => array('transition', 2, array(
		'initial' => 'ease',
		'optional' => 1
	)),
	'short_label' => esc_html__('Timing Function', 'microthemer'),
	'label' => esc_attr__('Transition Timing Function', 'microthemer'),
	'input-class' => 'size-6',
	'sug_values' => array(
		'this' => 1,
		//'forceSug' => 1
		'root_cat' => 'timing_function'
	),
	//'type' => 'combobox',
	'select_options' => $propertyOptions['animation']['animation_timing_function']['select_options'],
	'prefixes' => array(
		'property' => array(
			'-webkit-transition-timing-function',
		)
	),
	'icon' => '3, 4, B',
	'icon-name' => 'animation-timing-function',
	// ref
	'ref_desc' => "<p>Specifies the speed curve of a transition. This is used to make the CSS styles change into another set smoothly or jerkily (using steps).</p>",
	'ref_values' => array(
		"ease" => "Default. The transition has a slow start, then fast, before it ends slowly",
		"ease-in" => "The transition starts slowly",
		"ease-out" => "The transition ends slowly",
		"ease-in-out" => "The transition starts and ends slowly",
		"linear" => "The transition has the same speed throughout",
		"step-start" => "The transition jerks from start to end rather than being smooth. Equivalent to 'steps(1, start)'",
		"step-end" => "The transition jerks from end to start rather than being smooth. Equivalent to 'steps(1, end)'",
		"steps(int, start|end)" => "Custom function for defining the number of steps and direction of the jerky transition. The first value, number of steps, must be a positive number. The second value must be either 'start' or 'end'. It specifies the direction.",
		"cubic-bezier(n,n,n,n)" => "Custom function for fine grained control over the speed of the transition. Microthemer provides some examples to get you started.",
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transition-timing-function',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_transition-timing-function.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_transition-timing-function.asp',
	),
	// this property needs own tutorials too
	'tutorials' => $propertyOptions['transition']['transition_property']['tutorials'],
	'group_tutorials' => 1
);

// transition delay
$propertyOptions['transition']['transition_delay'] = array(
	'sh' => array('transition', 3, array(
		'initial' => '0s',
		'optional' => 1
	)),
	'short_label' => esc_html__('Delay', 'microthemer'),
	'label' => esc_attr__('Transition Delay', 'microthemer'),
	'input-class' => 'size-0b',
	//'type' => 'combobox',
	'select_options' => $propertyOptions['animation']['animation_delay']['select_options'],
	'sug_values' => array(
		'this' => 1,
		'root_cat' => 'time',
		'min' => ''
	),
	'prefixes' => array(
		'property' => array(
			'-webkit-transition-delay',
		)
	),
	'default_unit' => 's',
	'special_units' => $time_units,
	'icon' => '4, 4, B',
	'icon-name' => 'animation-delay',
	// ref
	'ref_desc' => "<p>The delay before a transition should start. Negative values are allowed. Negative values cause the transition to start part way through its cycle, rather than from the start.</p>",
	'ref_values' => array(
		"(time)" => "e.g. '2s' would start the transition 2 seconds after the transition is applied to the element. The default value of '0s' starts the transition immediately. And '-1s' would also start the transition immediately, but 1 second through its cycle, thus skipping the first part."
	),
	'ref_links' => array(
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/transition-delay',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_transition-delay.cfm',
		'w3s' => 'https://www.w3schools.com/cssref/css3_pr_transition-delay.asp',
	),
	'tutorials' => $propertyOptions['transition']['transition_property']['tutorials'],
	'group_tutorials' => 1

);

// transition event (custom)
$propertyOptions['transition']['event'] = array(
	'short_label' => esc_html__('Event', 'microthemer'),
	'label' => esc_attr__('Transition Event', 'microthemer'),
	'field-class' => 'is-event-field',
	'input-class' => 'size-6 mt-event-input',
	'type' => 'combobox',
	'icon' => '9, 4, B',
	// ref
	'ref_desc' => "<p>Custom Microthemer option. Choose an event that triggers the transition. You can leave this field blank if you want to set up a separate selector for setting new styles (e.g. a hover selector).</p>
<p>Note: if you choose a JavaScript event (e.g. inView or click), Microthemer will include an extra JS file (animation-events.js) on your site to enable this behaviour.</p>",
	'ref_values' => array(
		'hover' => "(CSS event) The transition will run when a user hovers over the element.",
		'focus' => "(CSS event) For forms fields and other elements that can gain focus. The transition will run when the element gains focus.",
		'inView' => "(JS event) The transition will run when ever the element scrolls into view. It will run backwards when the element scrolls out of view so it can run again if the element comes into view again.",
		'inView (once)' => "(JS event) The transition will run <i>once</i> if the element scrolls into view.",
		'click' => "(JS event) The transition will run when a user clicks the element.",
	)
);

// transition event value (custom)
$propertyOptions['transition']['event_value'] = array(
	'short_label' => esc_html__('Event Value', 'microthemer'),
	'label' => esc_attr__('Transition Event Value', 'microthemer'),
	'field-class' => 'event-value-field is-event-field',
	'input-class' => 'size-big mt-event-value-input mt-variable-input',
	'default_unit' => 'px',
	'icon' => '11, 4, B',
	'sug_values' => array(
		'this' => 1,
	),
	// ref
	'ref_desc' => "<p>Custom Microthemer option. If you have selected a transition event, you must enter a new value for the transition property. This new value will be applied when the transition event occurs.</p>",
	'ref_values' => array(
		'(CSS property value)' => "Enter a new value for the transition property. For example, if the transition property was min-height, you might enter 600px as the new value.",
	)
);

// transition event target (custom)
$propertyOptions['transition']['event_target'] = array(
	'short_label' => esc_html__('JS Event Target', 'microthemer'),
	'label' => esc_attr__('Transition Event Target', 'microthemer'),
	'field-class' => 'event-target-field is-event-field',
	'input-class' => 'size-big event-target-input',
	'icon' => '10, 4, B',
	// ref
	'ref_desc' => "<p>Custom Microthemer option. Choose which element the transition should be applied to once it is triggered by a JavaScript event (this will not work with CSS events). If you leave this field blank, the transition will be applied to the element the current selector targets - the single element that triggered the event. But sometimes you might want to specify a custom CSS selector to apply the transition to different element(s). For example, clicking one element could transition the opacity of a related element close by.</p>"
	. $dom_relative_text,
	'ref_values' => $event_target_ref_values
);




// behaviour
// ------------------------------------------------------------

$propertyOptions['behaviour']['display'] = array(
	'short_label' => esc_html_x('Display', 'noun', 'microthemer'),
	'label' => esc_attr_x('Display', 'noun', 'microthemer'),
	'pg_label' => esc_attr__('Behaviour', 'microthemer'),
	'sub_label' => esc_html__('Behaviour', 'microthemer'),
	'new_pg_cat' => esc_attr__('Misc', 'microthemer'), // for delimiting property group categories
	'sub_slug' => 'behaviour',
	'input-class' => 'size-9a',
	'type' => 'combobox',
	'select_options' => array(
		'block',
		'flex',
		'grid',
		'inline',
		'inline-block',
		'inline-flex',
		'inline-grid',
		'inline-table',
		'list-item',
		'run-in',
		'table',
		'table-caption',
		'table-column-group',
		'table-header-group',
		'table-footer-group',
		'table-row-group',
		'table-cell',
		'table-column',
		'table-row',
		'none'
	),
	'prefixes' => $flex_display_prefixes,
	'icon' => '23, 14',
	// ref
	'ref_desc' => "<p>The display property specifies the type of box an element should generate.</p>",
	'ref_values' => $display_ref_values,
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/d/display/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/display',
		'quackit' => 'http://www.quackit.com/css/properties/css_display.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_class_display.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://themeover.com/fancy-form-styling/#display-inline-block',
			'title' => 'Quick summary of Inline VS Block',
		),
		array(
			'url' => 'http://www.css3.com/display/',
			'title' => 'CSS DISPLAY',
		),
		array(
			'url' => 'http://colintoh.com/blog/display-table-anti-hero',
			'title' => 'The Anti-hero of CSS Layout - "display:table"',
		),
	)
);

$propertyOptions['behaviour']['overflow'] = array(
	'short_label' => esc_html__('Overflow', 'microthemer'),
	'label' => esc_attr__('Overflow', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-2',
	'type' => 'combobox',
	'select_options' => array(
		'visible',
		'scroll',
		'auto',
		'hidden'
	),
	'icon' => '20, 14',
	// ref
	'ref_desc' => "<p>The overflow property specifies what happens if content overflows an element's box.</p>",
	'ref_values' => array(
		"visible" => "The overflow is not clipped. It renders outside the element's box. This is default",
		"scroll" => "The overflow is clipped, but a scroll-bar is added to see the rest of the content",
		"auto" => "If overflow is clipped, a scroll-bar should be added to see the rest of the content",
		"hidden" => "The overflow is clipped, and the rest of the content will be invisible"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/o/overflow/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/overflow',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_overflow.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_pos_overflow.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'https://css-tricks.com/the-css-overflow-property/',
			'title' => 'The CSS Overflow Property',
		),
		array(
			'url' => 'http://marksheet.io/css-height-width-overflow.html',
			'title' => 'Setting fixed dimensions to your rectangles',
		),
		array(
			'url' => 'http://www.wickham43.net/scrollingdivs.php',
			'title' => 'Scrolling divs and overflow: hidden',
		),
	)
);

$propertyOptions['behaviour']['overflow_x'] = array(
	'short_label' => esc_html__('Overflow X', 'microthemer'),
	'label' => esc_attr__('Overflow X', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-2',
	'type' => 'combobox',
	'select_options' => array(
		'visible',
		'scroll',
		'auto',
		'hidden'
	),
	'icon' => '29, 14',
	// ref
	'ref_desc' => "<p>The overflow-x property specifies what happens if content overflows an element's box on the <b>horizontal axis</b>. The same <a href='admin.php?page=tvr-docs.php&prop=overflow&prop_group=behaviour' target='_blank'>overflow values</a> can be used for overflow-x.</p>",
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/o/overflow/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/overflow-x',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_overflow-x.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_overflow-x.asp',
	),
	'tutorials' => $propertyOptions['behaviour']['overflow']['tutorials'],
	'related_tutorials' => $propertyOptions['behaviour']['overflow']['label']
);

$propertyOptions['behaviour']['overflow_y'] = array(
	'short_label' => esc_html__('Overflow Y', 'microthemer'),
	'label' => esc_attr__('Overflow Y', 'microthemer'),
	'field-class' => 'icon-size-4',
	'input-class' => 'size-2',
	'type' => 'combobox',
	'select_options' => array(
		'visible',
		'scroll',
		'auto',
		'hidden'
	),
	'icon' => '30, 14',
	// ref
	// ref
	'ref_desc' => "<p>The overflow-y property specifies what happens if content overflows an element's box on the <b>vertical axis</b>. The same <a href='admin.php?page=tvr-docs.php&prop=overflow&prop_group=behaviour' target='_blank'>overflow values</a> can be used for overflow-y.</p>",
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/o/overflow/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/overflow-y',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_overflow-y.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_overflow-y.asp',
	),
	'tutorials' => $propertyOptions['behaviour']['overflow']['tutorials'],
	'related_tutorials' => $propertyOptions['behaviour']['overflow']['label']
);

$propertyOptions['behaviour']['visibility'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Visibility', 'microthemer'),
	'label' => esc_attr__('Visibility', 'microthemer'),
	'type' => 'combobox',
	'field-class' => 'icon-size-2',
	'input-class' => 'size-3',
	'select_options' => array(
		'visible',
		'hidden',
		'collapse'
	),
	'icon' => '24, 14',
	// ref
	'ref_desc' => "<p>The visibility property specifies whether or not an element is visible. But unlike setting 'display' to 'none', if you set 'visibility' to 'hidden' the hidden element will still take up the same space on the page - it just won't be visible.</p>",
	'ref_values' => array(
		"visible" => "The element is visible. This is default",
		"hidden" => "The element is invisible (but still takes up space)",
		"collapse" => "Only for table elements. collapse removes a row or column, but it does not affect the table layout. The space taken up by the row or column will be available for other content. If collapse is used on other elements, it renders as 'hidden'"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=CSS%202.1%20properties',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/v/visibility/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/visibility',
		'quackit' => 'http://www.quackit.com/css/properties/css_visibility.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_class_visibility.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.tutorialrepublic.com/css-tutorial/css-visibility.php',
			'title' => 'CSS Visibility',
		),
	)
);

$propertyOptions['behaviour']['cursor'] = array(
	'short_label' => esc_html__('Cursor', 'microthemer'),
	'label' => esc_attr__('Cursor', 'microthemer'),
	'field-class' => 'icon-size-0a',
	'input-class' => 'size-4',
	'type' => 'combobox',
	'select_options' => array(
		'auto',
		'crosshair',
		'default',
		'e-resize',
		'help',
		'move',
		'n-resize',
		'ne-resize',
		'nw-resize',
		'pointer',
		'progress',
		's-resize',
		'se-resize',
		'sw-resize',
		'text',
		'w-resize',
		'wait'
	),
	'icon' => '22, 14',
	// ref
	'ref_desc' => "<p>The cursor property specifies the type of cursor to be displayed when pointing on an element.</p>",
	'ref_values' => array(
		"auto" => "	Default. The browser sets a cursor",
		"crosshair" => "The cursor render as a crosshair",
		"default" => "The default cursor",
		"e-resize" => "The cursor indicates that an edge of a box is to be moved right (east)",
		"help" => "The cursor indicates that help is available",
		"move" => "The cursor indicates something that should be moved",
		"n-resize" => "The cursor indicates that an edge of a box is to be moved up (north)",
		"ne-resize" => "The cursor indicates that an edge of a box is to be moved up and right (north/east)",
		"nw-resize" => "The cursor indicates that an edge of a box is to be moved up and left (north/west)",
		"pointer" => "The cursor render as a pointer",
		"progress" => "	The cursor indicates that the program is busy (in progress)",
		"s-resize" => "The cursor indicates that an edge of a box is to be moved down (south)",
		"se-resize" => "The cursor indicates that an edge of a box is to be moved down and right (south/east)",
		"sw-resize" => "The cursor indicates that an edge of a box is to be moved down and left (south/west)",
		"text" => "The cursor indicates text",
		"w-resize" => "The cursor indicates that an edge of a box is to be moved left (west)",
		"wait" => "The cursor indicates that the program is busy"
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#search=css3%20cursor',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/c/cursor/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/cursor',
		'quackit' => 'http://www.quackit.com/css/properties/css_cursor.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_class_visibility.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.sitepoint.com/css3-cursor-styles/',
			'title' => 'Introducing the New CSS Cursor Styles in CSS3',
		),
	)
);

$propertyOptions['behaviour']['opacity'] = array(
	'animatable' => 1,
	'short_label' => esc_html__('Opacity', 'microthemer'),
	'label' => esc_attr__('Opacity', 'microthemer'),
	'field-class' => 'icon-size-2',
	'input-class' => 'size-0b',
	'icon' => '4, 14',
	'select_options' => $opacity,
	'sug_values' => array(
		'this' => 1,
		'unitless_slider' => 1,
		'max' => 1
	),
	// ref
	'ref_desc' => "<p>The opacity property sets the opacity level for an element. You can enter any numeric value between 0 and 1 (e.g. 0.25 or 0.9)</p>",
	'ref_values' => array(
		"(decimal 0 - 1)" => "e.g. '0.5' would set the opacity to 50% (half transparent)."
	),
	'ref_links' => array(
		'can_i_use' => 'http://caniuse.com/#feat=css-opacity',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/o/opacity/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/opacity',
		'quackit' => 'http://www.quackit.com/css/css3/properties/css_opacity.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/css3_pr_opacity.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://xahlee.info/js/css_color_opacity.html',
			'title' => 'CSS: Opacity',
		),
		array(
			'url' => 'http://www.tutorialrepublic.com/css-tutorial/css-opacity.php',
			'title' => 'CSS Opacity',
		),
	)
);

$propertyOptions['behaviour']['content'] = array(
	'short_label' => esc_html__('Content', 'microthemer'),
	'label' => esc_attr__('Content', 'microthemer'),
	'field-class' => 'icon-size-3',
	//'input-class' => 'size-0b',
	'select_options' => array(
		'" "',
		'"any text"',
		'"\\0024"', // dollar
		'"\\20AC"', // euro
		'"\\00A3"', // pound
		'attr(href)',
		'open-quote',
		'close-quote',
		'no-open-quote',
		'no-close-quote',
		'url(/wp-content/plugins/microthemer/images/MT-logo.svg)',
		'counter(counterVar)',
		'none'
	),
	'sug_values' => array(
		'this' => 1,
	),
	'icon' => '20, 17',
	// ref
	'ref_desc' => "<p>The content property is used with the :before and :after pseudo-elements, to insert generated content.</p>",
	'ref_values' => array(
		"normal" => "Default value. Sets the content, if specified, to normal, which default is 'none' (which is nothing)",
		"none" => "Sets the content, if specified, to nothing",
		"counter" => "Sets the content as a counter",
		"attr(attribute)" => "Sets the content as one of the selector's attribute",
		"string" => "Sets the content to the text you specify",
		"open-quote" => "Sets the content to be an opening quote",
		"close-quote" => "Sets the content to be a closing quote",
		"no-open-quote" => "Removes the opening quote from the content, if specified",
		"no-close-quote" => "Removes the closing quote from the content, if specified",
		"url(url)" => "Sets the content to be some kind of media (an image, a sound, a video, etc.)"
	),
	'ref_links' => array(
		'can_i_use' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/content',
		'css_tricks' => 'https://css-tricks.com/almanac/properties/c/content/',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/content',
		'quackit' => 'http://www.quackit.com/css/properties/css_content.cfm',
		'w3s' => 'http://www.w3schools.com/cssref/pr_gen_content.asp',
	),
	'tutorials' => array(
		array(
			'url' => 'http://www.sitepoint.com/understanding-css-content-property/',
			'title' => 'Understanding the CSS ‘content’ Property',
		),
		array(
			'url' => 'http://www.hongkiat.com/blog/pseudo-element-before-after/',
			'title' => 'Understanding Pseudo-Element :before and :after',
		),
		array(
			'url' => 'http://tympanus.net/codrops/2013/07/05/using-custom-data-attributes-and-pseudo-elements/',
			'title' => 'Using Custom Data Attributes and Pseudo-Elements',
		),
	)
);

// container properties (for container queries)
$propertyOptions['behaviour']['container_type'] = array(
	'short_label' => esc_html__('Container Type', 'microthemer'),
	'label' => esc_attr__('Container Type', 'microthemer'),
	'type' => 'combobox',
	'select_options' => array(
		'size',
		'inline-size',
		'normal',
	),
	// ref
	'ref_desc' => "<p>The container-type CSS property must be set on a parent/ancestor of an element you style using a container query. Otherwise, selector rules you set on the container query will have no effect.</p>",
	'ref_values' => array(
		"size" => "Tells the browser to listen for changes in width and height, or more precisely the inline and block axis. Applies layout, style, and size containment.",
		"inline-size" => "Tells the browser to listen for changes in width if writing-mode is horizontal, or height if writing-mode is vertical (e.g. Japanese). Applies layout, style, and inline-size containment.",
		"normal" => "The element is a container for style queries, but not size queries.",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=container-type',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/container-type',
	),
	'tutorials' => array(
		array(
			'url' => 'https://www.smashingmagazine.com/2021/05/complete-guide-css-container-queries/',
			'title' => 'A Primer On CSS Container Queries',
		),
		array(
			'url' => 'https://web.dev/cq-stable/',
			'title' => 'Container queries land in stable browsers',
		),
		array(
			'url' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Container_Queries',
			'title' => 'CSS Container Queries',
		),
	)
);

$propertyOptions['behaviour']['container_name'] = array(
	'short_label' => esc_html__('Container Name', 'microthemer'),
	'label' => esc_attr__('Container Name', 'microthemer'),
	'sug_values' => array(
		'this' => 1,
	),
	// ref
	'ref_desc' => "<p>container-name sets a name, so that container queries can target its size more precisely, without accidentally responding to the size of other containers.</p>",
	'ref_values' => array(
		"name" => "An alphanumeric string, without quotes, that cannot be the reserved name: default",
	),
	'ref_links' => array(
		'can_i_use' => 'https://caniuse.com/?search=container-name',
		'mozilla' => 'https://developer.mozilla.org/en-US/docs/Web/CSS/container-name',
	),

);






// log moved groups/properties
// ------------------------------------------------------------

$legacy_groups['font'] = array(
	'forecolor' => array(
		'color' => 1 // 1 means same as key
	),
	'text' => array(
		'line_height' => 1,
		'text_decoration' => 1
	)
);

$legacy_groups['list'] = array(
	'text' => array(
		'list_style' => 'list_style_type' // define string when different
	)
);

$legacy_groups['shadow'] = array(
	'text' => array(
		'text_shadow_color' => 1,
		'text_shadow_x' => 1,
		'text_shadow_y' => 1,
		'text_shadow_blur' => 1
	),
	'CSS3' => array(
		'box_shadow_color' => 1,
		'box_shadow_x' => 1,
		'box_shadow_y' => 1,
		'box_shadow_blur' => 1
	)
);

/* // to be reliable, this needs to merge the value into one
$legacy_groups['background'] = array(
	'background' => array(
		'background_position_x' => 1,
		'background_position_y' => 1
	)
);
*/

$legacy_groups['padding_margin'] = array(
	'padding' =>array(
		'padding_top' => 1,
		'padding_right' => 1,
		'padding_bottom' => 1,
		'padding_left' => 1
	),
	'margin' => array(
		'margin_top' => 1,
		'margin_right' => 1,
		'margin_bottom' => 1,
		'margin_left' => 1
	)
);

$legacy_groups['border'] = array(
	'border' => array(
		'border_style' => array( // the only array - when the legacy value maps onto multiple props
			'border_top_style',
			'border_right_style',
			'border_bottom_style',
			'border_left_style'
		)
	),
	'CSS3' => array(
		'radius_top_left' => 'border_top_left_radius',
		'radius_top_right' => 'border_top_right_radius',
		'radius_bottom_right' => 'border_bottom_right_radius',
		'radius_bottom_left' => 'border_bottom_left_radius'
	)
	/* was only moved temp - version not released
	'border' => array(
		'radius_top_left',
		'radius_top_right',
		'radius_bottom_right',
		'radius_bottom_left'
	)*/
);

$legacy_groups['position'] = array(
	'behaviour' => array(
		'float' => 1,
		'clear' => 1
	)
);

$legacy_groups['gradient'] = array(
	'CSS3' => array(
		'gradient_a' => 1,
		'gradient_b' => 1,
		'gradient_b_pos' => 1,
		'gradient_c' => 1,
		'gradient_angle' => 1
	)
);
