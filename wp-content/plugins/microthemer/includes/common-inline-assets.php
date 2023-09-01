<?php

// Code that should run on all MT plugin pages

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

// inline SVG sprite
echo $this->icon('sprite', array(
		'type' => 'svg',
		'dir' => '', // root images dir
		'id' => 'mt-svg-sprite',
		'style' => 'position:fixed;left:-999em'
	)
);

if (!isset($for_main_ui)){
	$this->preferences['mt_dark_mode'] ? $ui_class.= ' mt_dark_mode' : false;
	$this->preferences['hide_interface'] ? $ui_class.= ' hide_interface' : false;
	$this->preferences['show_rulers'] ? $ui_class.= ' show_rulers' : false;
	$this->preferences['specificity_preference'] ? $ui_class.= ' specificity_preference' : false;
}


/*echo $this->icon('defs', array(
		'type' => 'svg',
		'dir' => '/svg-sprites/svg/', // root images dir
		'id' => 'mt-svg-sprite',
		'style' => 'position:fixed;left:-999em'
	)
);*/