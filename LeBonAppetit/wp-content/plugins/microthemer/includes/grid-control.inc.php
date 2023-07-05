<?php

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

// config
$grid_size = 24;
$col_labels = '';
$row_labels = '';

// generate columns and row labels
for ($x = 1; $x <= $grid_size; $x++) {
	$clabel = $x;
	$rlabel= $x;
	if (true){
		$clabel = 'C'.$x;
		$rlabel= 'R'.$x;
	}
	$col_labels.= '
	<li class="col-label">
		<span class="large-heading-label">'.$clabel.'</span>
	</li>';
	$row_labels.= '<li class="row-label">'.$rlabel.'</li>';
}

// grid icons (highlight / expand)

$grid_icons = '
<div class="grid-option-icons">';

	// toggle grid highlight
	$grid_icons.= $this->ui_toggle(
		'grid_highlight',
		esc_attr__('Enable grid highlight', 'microthemer'),
		esc_attr__('Disable grid highlight', 'microthemer'),
		!empty($this->preferences['grid_highlight']),
		'grid-highlight ' . $this->iconFont('spotlight', array(
			'onlyClass' => 1,
		)),
		'grid-highlight-toggle',
		array(
			'dataAtts' => array(
				'fhtml' => 1
			),
		)
	);

	// expand / collapse grid
	$grid_icons.= $this->ui_toggle(
		'expand_grid',
		esc_attr__('Expand grid', 'microthemer'),
		esc_attr__('Collapse grid', 'microthemer'),
		0, // always off initially
		'expand-grid ' . $this->iconFont('expand', array(
			'onlyClass' => 1,
		)),
		'grid-expand-toggle',
		array(
			'dataAtts' => array(
				'no-save' => 1
			),
		)
	)

    .'
</div>';

// grid control start
$grid_control = '
<div id="grid-control-wrap" class="grid-control-wrap">

	'.$grid_icons.'
	
	<div class="graph-area">
		<div class="grid-control">
		
			'.$this->iconFont('eraser', array(
				'class' => 'clear-all-grid-styles',
				'data-input-level' => 'group',
				'title' => esc_attr__('Clear all grid styles', 'microthemer'),
			)).'
		
			<ul class="col-labels">'.$col_labels.'</ul>
			<ul class="row-labels">'.$row_labels.'</ul>
			
			<div class="mt-grid-areas"></div>
			
			<div class="implicit-grid"></div>
		
			
			<div class="grid-canvas grid-stack"></div>
			
			<div class="explicit-grid">
				<div class="explicit-grid-toggle" title="'.esc_attr__('Drag grid template', 'microthemer').'"></div>
			</div>
			
			<div class="mt-lookup-grid"></div>
	
		</div>
		
	</div>
	
	<div class="scrollable-area mt-horizontal-scroll nth-item-radios tab-control tab-control-griditems">
		<span class="nth-item-heading">nth</span>
		<ul class="fake-radio-parent"></ul>
	</div>
		
</div>';