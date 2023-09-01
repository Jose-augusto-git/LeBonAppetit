<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

// just use for image URL fields
$disable_spellcheck = '';

$data_atts = '';

// save prop data in smaller var
$prop_data = $this->propertyoptions[$property_group_name][$property];

$cssf = str_replace('_', '-', $property);

// is it a custom editor
$is_editor = false;
if (!empty($prop_data['type']) and $prop_data['type'] == 'editor'){
	$is_editor = true;
}

// add media query stem for form fields if necessary
$sel_imp_array = array();
$styles = array();
if ($con == 'mq') {
	if (!empty($this->options['non_section']['m_query'][$key][$section_name][$css_selector]['styles'])){
		$styles = $this->options['non_section']['m_query'][$key][$section_name][$css_selector]['styles'];
	}
	$mq_stem = '[non_section][m_query]['.$key.']';
	$imp_key = '[m_query]['.$key.']';
	$mq_extr_class = '-'.$key;
	// get the important val
	if (!empty($this->options['non_section']['important']['m_query'][$key][$section_name][$css_selector][$property_group_name][$property])) {
		$important_val = $this->options['non_section']['important']['m_query'][$key][$section_name][$css_selector][$property_group_name][$property];
	} else {
		$important_val = '';
	}
	// save the general selector important array for querying if legacy values are discovered
	if (!empty($this->options['non_section']['important']['m_query'][$key][$section_name][$css_selector])){
		$sel_imp_array = $this->options['non_section']['important']['m_query'][$key][$section_name][$css_selector];
	}

} else {
	if (!empty($this->options[$section_name][$css_selector]['styles'])){
		$styles = $this->options[$section_name][$css_selector]['styles'];
	}
	$mq_stem = '';
	$imp_key = '';
	$mq_extr_class = '-all-devices';
	// get the important val
	if ( !empty($this->options['non_section']['important'][$section_name][$css_selector][$property_group_name][$property])) {
		$important_val = $this->options['non_section']['important'][$section_name][$css_selector][$property_group_name][$property];
	} else {
		$important_val = '';
	}
	// save the general selector important array for querying if legacy values are discovered
	if (!empty($this->options['non_section']['important'][$section_name][$css_selector])){
		$sel_imp_array = $this->options['non_section']['important'][$section_name][$css_selector];
	}
}


// check if legacy value for prop exists
$legacy_adjusted = $this->populate_from_legacy_if_exists($styles, $sel_imp_array, $property);
if ($legacy_adjusted['value']){
	$value = $legacy_adjusted['value'];
	$important_val = $legacy_adjusted['imp'];
}

// account for old PHP versions with magic quotes
$value = $this->stripslashes($value);

/***
 * get variables from the config file
 */

// field class
$field_class = '';
if (!empty($prop_data['field-class']) ) {
	$field_class = $prop_data['field-class'];
}

// input class
$input_class = '';
if (!empty($prop_data['input-class']) ) {
	$input_class = $prop_data['input-class'];
}

$is_picker = !empty($prop_data['field-class']) && strpos($prop_data['field-class'], 'is-picker') !== false;
$is_unitless_slider = isset($prop_data['sug_values']['unitless_slider']);
$is_slider_field = (isset($prop_data['default_unit']) || $is_unitless_slider) && empty($prop_data['sug_values']['no_slider']);

// if combobox - replaces all select menus, for better styling and user flexibility
$combo_class = 'combobox'; // all should be comboboxes as some have suggested values
$combo_arrow = '';
if (
	!empty($prop_data['type']) and
	$prop_data['type'] == 'combobox') {
	$combo_class = 'combobox has-arrows';
	$combo_arrow = '<span class="combo-arrow tvr-field-arrow"></span>';
} else {
	// numerical field
	if (!$is_picker){

		$field_class.= ' mt-numeric';

		// has CSS unit
		if ( $is_slider_field ){
			$field_class.= ' mt-has-unit';
			$input_class.= ' mt-slider';
		}

		// unitless slider
		if ($is_unitless_slider){
			$field_class.= ' mt-has-unitless-slider';
			$input_class.= ' mt-unlitless-slider';
		}

	}

	// 3 dots dropdown
	$combo_class = 'combobox';

	if (!$is_picker){
		$combo_class.= ' has-suggestions';
	}

	$combo_arrow = '<span class="combo-arrow combo-dots tvr-field-arrow"></span>';
}

// have 'x' clear for input fields now that slider makes clear in select proplematic

if (!$is_picker){
	$combo_arrow.= '<span class="mt-clear-field"></span>'; // title="'.esc_attr__("Clear field", 'microthemer').'"
}


// determine if the user has applied a value for this field, adjust comp class accordingly

$comp_class = 'comp-style cprop-' . $cssf;
if (!empty($value) or $value === 0 or $value === '0') {
	$man_class = ' manual-val';

	if (!$is_picker){
		$comp_class.= ' hidden';
	}
} else {
	$man_class = '';
}

if ($is_picker) {
	$data_atts.= 'data-forpopup="picker"';
	$comp_class.= ' comp-for-picker';
}

// check if input is eligable for autofill
if (!empty($prop_data['rel'])) {
	$autofill_class = 'autofillable ';
	$autofill_rel = $prop_data['rel'];
}
else {
	$autofill_class = '';
	$autofill_rel = '';
}


// track if the input has variable fields in a line e.g. grid-template-rows
$variable_line = !empty($prop_data['variable_line']);

// does the input have array format values
$array_values = !empty($prop_data['array_values']);

// css property icon
$icon_name = !empty($prop_data['icon-name']) ? $prop_data['icon-name'] : $cssf;
$option_icon = '<span class="option-icon option-icon-'.$property.
               ' '.$this->iconFont($icon_name, array('onlyClass' => 1)).'"></span>';

/** Deal with property exceptions */
$extra_icon = '';

// add image insert button for bg image
if ($property == 'background_image' or $property == 'list_style_image' or $property == 'url_function' or $property == 'mask_image') {
	//$extra_icon = ' <span class="tvr-image-upload"></span>';
	$extra_icon = $this->iconFont('cloud-upload-alt', array(
		'class' => 'tvr-image-upload',
		'title' => esc_attr__('Browse media library', 'microthemer')
	));
	$disable_spellcheck = 'spellcheck="false"';
}

// add an 'Add' button for add template area
if ($property == 'grid_template_areas_add') {
	$extra_icon = ' <span class="tvr-button tvr-add-template-area">'.esc_html__('Add', 'microthemer').'</span>';
}

// strip font-family custom quotes for legacy reasons
if ($property == 'font_family') {
	$value = str_replace('cus-#039;', '&quot;', $value);
}
// allow user to edit their google fonts with a link
if ($property == 'google_font') {
	//$extra_icon = '<span class="g-font show-dialog" rel="google-fonts" title="Set Google Font"></span>';
	$extra_icon = $this->iconFont('search', array(
		'class' => 'g-font show-dialog',
		'rel' => 'google-fonts',
		'title' => esc_attr__('Browse Google fonts', 'Microthemer'),
	));

	// hide if font-family isn't set to Google Font
	if ($property_group_array['font_family'] != 'Google Font...') {
		$field_class.= ' hidden';

	}
}
// hide event target if event isn't set to JS event
if (!empty($property_group_array['event'])){
	$property_group_array['event'] = trim($property_group_array['event']);
}
/*if ($property == 'event_target' and
    ( empty($property_group_array['event'])
      or $property_group_array['event'] == ':hover'
      or $property_group_array['event'] == ':focus') ) {
	$field_class.= ' hidden';
}
// hide event value if no event is set
if ($property == 'event_value' and empty($property_group_array['event'])) {
	$field_class.= ' hidden';
}*/

// get the property label
$prop_label = $prop_data['label'];

// adjust for lang
if ($this->preferences['tooltip_en_prop']){

	$valid_syntax = $cssf;

	// adjust for any alias
	$valid_syntax = !empty($this->propAliases[$valid_syntax])
		? $this->propAliases[$valid_syntax]
		: $valid_syntax;

	// for english, the valid syntax is so similar to the capitalised label that we should just show the valid syntax
	// showing the valid syntax is now the default preference
	if ($this->is_en()){
		$prop_label = $valid_syntax;
	} else {
		$prop_label.= ' / '.$valid_syntax;
	}
}

// exception for google-font and add_template areas
$prop_label = ($prop_label == 'google-font') ? 'font-family': $prop_label;
$prop_label = ($prop_label == 'grid-template-areas-add') ? 'grid-template-areas': $prop_label;


/***
 * output the form fields
 */

/*if (!empty($prop_data['nth_item_radios'])){
	$html.= '
	<div class="nth-item-radios tab-control tab-control-griditems">
		<span class="nth-item-heading">nth</span>
		<ul class="fake-radio-parent">
			<li class="nth-item-option" data-item="0">
				<input class="nth-item-radio" type="radio" name="tvr_mcth'.$mq_stem.'['.$section_name.']['.$css_selector.'][nth_item]" value="0" />
				<span class="fake-radio nth-radio-control"></span>
				<span class="nth-item-label">0</span>
			</li>
		</ul>
	</div>';
}*/



// check for PG controls e.g. play button, new row icon etc
$controls = '';
if ( !empty($prop_data['pg_controls']) ){

	$controls.= '<div class="pg-controls">';

	// loop through controls
	foreach ($prop_data['pg_controls']['items'] as $item_key => $item){
		switch ($item['type']) {

			case 'icon':
				$control_icon = '<span class="'.$item['class'].'" title="'.$item['title'].'"></span>';
				$controls.= !empty($item['wrapper'])
					? '<span class="pg-control-wrapper '.$item['wrapper'].'">' . $control_icon . '</span>'
					: $control_icon;
				break;

			case 'timeline':
				$controls.= '
				<div class="mt-timeline">
					<span class="mt-timeline-bar"></span>
					<span class="mt-timeline-time"></span>
				</div>';
				break;

			/*case 'combobox':
				$trigger_value = !empty($array['animation']['trigger_event']) ? $array['animation']['trigger_event'] : '';
				$controls.= '
				<span class="pg-control-label">'.$item['label'].'</span>
				<span class="tvr-input-wrap">
					<input type="text" autocomplete="off" rel="'.$item['rel'].'" 
					class="'.$item['class'].' combobox has-arrows" 	
					name="tvr_mcth'.$mq_stem.'['.$section_name.']['.$css_selector.'][animation][trigger_event]" 
					value="'.esc_html($trigger_value).'">
					<span class="combo-arrow"></span>
				</span>';
				break;
			*/
		}
	}

	$controls.= '</div><div class="clear property-row-divider"></div>';
}

// determine if fields are controlled by tabs (e.g. CSS grid)
$tab_control_class = !empty($prop_data['tab_control'])
	? 'tab-control tab-control-'.$prop_data['tab_control']
	: '';

$tab_control_data = !empty($prop_data['tab_control'])
	? 'data-pgtabcontrol="'.$prop_data['tab_control'].'"'
	: '';

// check if it's a new sub group
$sub_label_html = '';
if (!empty($prop_data['sub_label'])){
	$subgroup_label = $prop_data['sub_label'];

	// save subgroup in global var for following iterations
	$this->subgroup = $prop_data['sub_slug'];
	$disabled = false;
	$dis_class = '';
	// exception for flexbox
	$dis_sub_group = $this->subgroup; // == 'flexcontainer' ? 'flexbox' : $this->subgroup;
	if (!empty($array['pg_disabled'][$dis_sub_group])) {
		$disabled = true;
		$dis_class.= ' item-disabled';
	}
	// disable icon
	$sub_dis_icon = $this->icon_control(
		false,
		'disabled',
		$disabled,
		'subgroup',
		$section_name,
		$css_selector,
		$key,
		$property_group_name,
		$dis_sub_group
	);

	// clear icon
	$sub_clear_icon = $this->clear_icon('subgroup');

	// chain icon
	$chained = false;
	if (!empty($array['pg_chained'][$this->subgroup])) {
		$chained = true;
	}

	$sub_chain_icon = $this->icon_control(
		false,
		'chained',
		$chained,
		'subgroup',
		$section_name,
		$css_selector,
		$key,
		$property_group_name,
		$this->subgroup);

	// info icon - just for editor
	// if editor, just show icon
	$info_icon = '';
	$colon = ':';
	if ($is_editor){
		$mode_icon = $this->preferences['allow_scss'] ? 'scss' : 'css';
		$subgroup_label = '<span class="'.$mode_icon.'-icon">'.$prop_data['sub_label'].'</span>';
		$info_icon = '<span class="info-icon css-info" rel="program-docs"
		title="'.esc_attr__('Click for info', 'microthemer').'"
				data-prop-group="'.$property_group_name.'" data-prop="'.$property.'"></span>';
		$colon = '';
	}

	// manual resize icon
	//$manual_resize_icon = $this->manual_resize_icon('inline-editor');

	// dynamic fields toggle
	$dyn_fields_toggle = empty( $prop_data['dynamic_fields'] ) ? ''
		: $this->icon_control(
			false,
			$prop_data['dynamic_fields'],
			!empty($array['pg_'.$prop_data['dynamic_fields']]),
			'group',
			$section_name,
			$css_selector,
			$key,
			$property_group_name);

	$opening_sub_label = '<div id="opts-'.$section_name.'-'.$css_selector.'-'.$property_group_name.'-'.$this->subgroup.'-subgroup'.$mq_extr_class.'"
	class="field-wrap sub-label sub-label-'.$property.' subgroup-tag subgroup-control-'.$this->subgroup.' '.$tab_control_class.'" data-subgroup="'.$this->subgroup.'" '.$tab_control_data.'>';

	// sub label html
	$sub_label_html = $opening_sub_label;

		$sub_label_html.= '
		<span class="quick-opts-wrap tvr-transition-in'.$dis_class.' tvr-field-quick-opts-wrap tvr-sub-quick-opts">
			<span class="subgroup-label">'. $subgroup_label .  '</span>' . '
			<span class="quick-opts">
				<div class="quick-opts-inner mt-icon-line">'
				. $sub_dis_icon
				. $sub_clear_icon
				. $info_icon
				. '
				</div>
			</span>
		</span>'. $dyn_fields_toggle ;

		// do we need a chain icon?
		if (!empty($prop_data['rel'])){
			//$rel = $prop_data['rel'];
			$sub_label_html.= $sub_chain_icon;
		}

		$sub_label_html.= '
	</div>';
}

// include any controls
$html.= $controls;

// add to $field_class manually as class crops up in unwanted places if set via property-optioins.inc.php
if ($is_editor){
	$field_class.= ' tvr-editor-area';
}

// if spacer div (useful for CSS grid layouts - on the grid PG in fact)
if (!empty( $prop_data['spacer_before'] )){
	$this->group_spacer_count++;
	for ($x = 0; $x < $prop_data['spacer_before']; $x++) {
		//include '';
		$html.= '<div class="spacer-item spacer-item-'.$this->group_spacer_count.'-'.$x.'"></div>';
	}
}

// if spacer div (useful for CSS grid layouts - on the grid PG in fact)
if (!empty( $prop_data['text_before'] )){
	$html.= '<div class="property-text-item">'.$prop_data['text_before'].'</div>';
}


// if tabs used to break up properties
if (!empty( $prop_data['tabs_before'] )){

	$current_pg_tab = !empty($array['pg_tab'][$property_group_name])
		? $array['pg_tab'][$property_group_name]
		: '';

	$html.= '
	<div class="query-tabs pg-tabs '.$property_group_name.'-tabs">';

		foreach($prop_data['tabs_before'] as $tab_class => $tab_label) {
			$html.= '
			<span class="pg-tab '.$property_group_name.'-tab mt-tab '.$property_group_name.'-tab-'.$tab_class.' quick-opts-wrap opts-show-above" rel="'.$tab_class.'" data-pg="'.$property_group_name.'">
				 <span class="mt-tab-txt pg-tab-text '.$property_group_name.'-tab-txt ">' . $tab_label. '</span>';

					// CSS grid has options for clearing whole tabs, transform doesn't need this as sub-labels cover all
					if (!empty($prop_data['tabs_has_options'])){
						$html.=
						'<span class="quick-opts tvr-dots dots-above">
						'.
						$this->iconFont('dots-horizontal', array(
							//'onlyClass' => 1,
						))
						.'
						
	                    <div class="quick-opts-inner mt-icon-line">'
								. $this->icon_control(false,'disabled', false, 'pgtab', '', '', '', '', '', '', 'tvr_mcth', $tab_class)
								. $this->clear_icon('pgtab', array(
									'key' => 'tab-group',
									'value' => $tab_class,
								)). '
	                    </div>';
					}

					$html.= '
                 </span>
            </span>';
		}

		// tabs label
		if (!empty($prop_data['tabs_label'] )){
			$html.= '<span class="mt-property-label pg-tabs-label">
				'.$prop_data['tabs_label'].'
			</span>';
		}

	$html.= '
	</div>';
}

// tab control class
$field_class.= ' '.$tab_control_class;

// variable line class
if ($variable_line){
	$field_class.= ' has-variable-line';
}

$gridTemplateProp = ($property === 'grid_template_rows' || $property === 'grid_template_columns');

// opening field wrap html
$field_wrap_html = '<div id="opts-'.$section_name.'-'.$css_selector.'-'.$property_group_name.'-'.$this->subgroup.'-'.$property. $mq_extr_class. '"
 class="property-tag field-wrap subgroup-'.$this->subgroup.' '.$field_class
	. ' field-'.$property_group_name.'-'.$property.'" '.$tab_control_data.'>';


	// render custom code editor
	if ($is_editor){

		// jquery $.ajax returns data that is already escaped, so set double encoding to false
		$css_code = htmlentities($value, ENT_QUOTES, 'UTF-8', false);

		$html.= $field_wrap_html . $option_icon  . '<span class="option-label css-info link" rel="program-docs"
				data-prop-group="'.$property_group_name.'" data-prop="'.$property.'">'.$prop_data['label'].'</span>' ;

		// set editor
		$ed_id = 'ed-opts-'. $section_name.'-'.$css_selector.'-'.$property_group_name.'-'.$this->subgroup.'-'.$property. $mq_extr_class;
		$html.= '
		<div class="css-code-wrap">
			<textarea autocomplete="off" rel="'.$property.'" class="property-input '.$input_class . '" name="tvr_mcth'.$mq_stem.'['.$section_name.']['.$css_selector.'][styles]['.$property_group_name.']['. $property.'][value]">'.$css_code.'</textarea>

		</div>';

		// <pre id="'.$ed_id.'" class="custom-css-pre pg-css-styles"></pre>

		$html.= '</div><!-- end field-wrap -->'
		. $sub_label_html ;

	}

	// render normal property input
	else {

		// output any subgroup label
		$html.= $sub_label_html;

		// start the field div
		$html.= $field_wrap_html;

		// css property text label
		/*$html.= '
		<div class="tvr-input-top">';



			$html.=	'<span class="mt-property-label">'. $prop_data['short_label'] .'</span>
 			'. $this->icon_control(false, 'important', $important_val, 'property', $section_name,
				        $css_selector, $key, $property_group_name, $this->subgroup, $property).'
		</div>';*/

		$html.=	'
		<span class="mt-property-label">
			<span class="mt-property-text-label">'. $prop_data['short_label'] .'</span>
			<span class="mt-prop-unit" data-forpopup="units"></span>
		</span>';


		// show the property icon, with quick options
		$html.= '
			<label class="quick-opts-wrap tvr-transition-in tvr-field-quick-opts-wrap tvr-property-quick-opts">';

		$html.= $option_icon; // . $text_label;

		$comp_value_hint = '';

		// explain why computed value not reported for transform functions
		$isFunc = (isset($prop_data['css_func']) and
		           ($property_group_name === 'transform' or $property_group_name === 'filter' ));
		if ($isFunc){

			// removing '_function'
			$prop_label = str_replace('-function', '', $prop_label);

			if ($property_group_name === 'transform'){
				//$comp_value_hint = ' title="Computed value not known"';
				// format e.g. rotateX with capital last letter (removing _function)
				$prop_label = str_replace('_function', '', $prop_label);
				$prop_label = preg_replace_callback(
					'/([xyz])$/',
					function ($matches) {
						return strtoupper ($matches[1]);
					},
					$prop_label
				);
			}


		}

		$html.= '
			<span class="quick-opts">
				<div class="quick-opts-inner">
					<span class="option-label css-info" rel="program-docs"
					data-prop-group="'.$property_group_name.'" data-prop="'.$property.'">'.$prop_label.'</span>
					<span class="option-value"'.$comp_value_hint.'></span>
					<div class="comp-mixed-wrap">
						<table class="comp-mixed-table">
							<tbody></tbody>
						</table>
					</div>
				</div>
			</span>';

		$html.= '
			</label>';

		// start variable line fields wrap
		$html.= $variable_line ? '<div class="mt-variable-line"><div class="mt-vl-inner">' : '';

		$input_wrap_html = '';

		$input_wrap_html.= '<span class="tvr-input-wrap tvr-field-input-wrap '.$man_class . '">';

			// if a variable input field, provide a color picker too
			if ($property === 'event_value'){
				/*$hidden_suppl =
					(strpos($property_group_array['transition_property'], 'color') !== false or
					strpos($property_group_array['transition_property'], 'shadow') !== false) ? '' : 'hidden';
				$picker_value = $hidden_suppl ? '' : $value;*/

				$input_wrap_html.= '<span class="suppl-picker-wrap">
				<input class="color mt-supplementary-picker mt-color-picker" name="mt_sup_picker" value="" />
				</span>';
			}

			// some fields take arrays as property values (e.g. transform) to set name accordinging
			$name_suffix = '[value]';
			$unit_name_suffix = '[unit]';
			if ($array_values){
				$name_suffix.= '[]';
				$unit_name_suffix.= '[]';
			}

			// grid template prop should be flagged with class
			if ($gridTemplateProp){
				$input_class.= ' mt-vl-input';
			}

			// grid item values are arrays of a different sort. 0 = container, 1, 2, 3 etc = nth-child
			if (!empty($prop_data['tab_control']) && $prop_data['tab_control'] === 'griditems'){
				$name_suffix.= '[1]'; // default to first child (unless item toggle is checked @todo)
			}

			$property_input = '<input type="text" 
			autocomplete="off" rel="'.$property.'" data-autofill="'.$autofill_rel.'" '.$disable_spellcheck.'
			data-appto="#style-components" '.$data_atts.'
			class="property-input '.$combo_class.' '.$input_class . ' ' . $autofill_class  .'"
			name="tvr_mcth'.$mq_stem.'['.$section_name.']['.$css_selector.'][styles]['.$property_group_name.']['. $property.']'.$name_suffix.'" value="'.$value.'" />';

			// grid template rows and columns need extra inputs for [line names]
			if ($property === 'grid_template_rows' || $property === 'grid_template_columns'){

				$line_label = '<span class="grid-line-label toggle-optional-input" title="Enter line name">L</span>';
				$line_input = '<input required type="text" autocomplete="off" rel="'.$property.'_extra" 
			data-appto="#style-components" data-isExtra="1"
			class="property-input vl-name-input '.$combo_class.' '.$input_class . '"
			name="tvr_mcth'.$mq_stem.'['.$section_name.']['.$css_selector.'][styles]['.$property_group_name.']['. $property.']'.$name_suffix.'" value="" />';
				$pre_line = '';
				$post_line = '';

				$input_wrap_html.= '<div class="vl-item vl-line-name vl-pre-line-name" data-input-filter="vl-pre-line-name">'.
						 $line_input. $line_label . $combo_arrow .
				        '</div>'.

				        '<div class="vl-item vl-main-input" data-input-filter="vl-main-input">'.
				        $property_input . $combo_arrow .
				        '</div>' .

				        '<div class="vl-item vl-line-name vl-post-line-name" data-input-filter="vl-post-line-name">'.
				         $line_input . $line_label . $combo_arrow .
				        '</div>';
			}

			else {
				// render combobox // data-appto=".styling-options"
				$input_wrap_html.= $property_input . $combo_arrow;
			}



			$input_wrap_html.= $extra_icon;



			$input_wrap_html.= '<span class="'.$comp_class.'"></span>';



			$input_wrap_html.= '</span>'; // end input wrap


		$html.= $input_wrap_html;

		// save input wrap for template/auto-rows/columns
		if ($variable_line && empty($this->input_wrap_templates[$property])){
			$this->input_wrap_templates[$property] = $input_wrap_html;
		}

		// end variable line fields wrap
		$html.= $variable_line ? '
			</div>
		</div>'. $this->iconFont('add', array(
			'class' => 'add-variable-field',
			'title' => 'Add field',
			)) : '';

		$html.= $this->icon_control(false, 'important', $important_val, 'property', $section_name,
			$css_selector, $key, $property_group_name, $this->subgroup, $property);

		// CSS unit
		//$html.= '<span class="input-unit-right">px</span>';

		// important
		/*$html.= $this->icon_control(false, 'important', $important_val, 'property', $section_name,
			$css_selector, $key, $property_group_name, $this->subgroup, $property);*/

		$html.= '</div><!-- end field-wrap -->';
	}




if (!empty($prop_data['linebreak']) and $prop_data['linebreak'] == '1') {
	$html.= '<div class="clear property-row-divider"></div>';
}

// global UI toggle for grid highlight
/*if (!empty($prop_data['highlight_grid_toggle'])) {




	$html .= '<div class="grid-option-icons">';

	// toggle grid highlight
	$html.= $this->ui_toggle(
         'grid_highlight',
		 esc_attr__('Enable highlight', 'microthemer'),
		 esc_attr__('Disable highlight', 'microthemer'),
         0,
         'grid-highlight',
         false,
         array(
	         'dataAtts' => array(
		         'fhtml' => 1
	         ),
		 )
    );

	// expand / collapse grid
	$html.= $this->ui_toggle(
		 'expand_grid',
		 esc_attr__('Expand grid', 'microthemer'),
		 esc_attr__('Collapse grid', 'microthemer'),
		 0,
		 'expand-grid',
		 false,
		 array(
		     'dataAtts' => array(
		         'no-save' => 1
		     ),
		 )
	)

	.'
    </div>';

}*/

// include interactive grid for css grid
/*if (!empty($prop_data['grid_control'] )){
	include $this->thisplugindir . 'includes/grid-control.inc.php';
}*/