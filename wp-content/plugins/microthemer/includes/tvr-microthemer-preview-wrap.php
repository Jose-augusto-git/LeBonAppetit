<?php

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

?>

<div id="v-frontend-wrap">

	<div id="rHighlight" class="ruler-stuff"></div>

    <?php require_once $this->thisplugindir . '/includes/default-ruler-markup.php'; ?>

	<div id="v-frontend">

		<?php
		// resolve iframe url
		$strpos = strpos($this->preferences['preview_url'], $this->home_url);
		// allow user updated URL, if they navigated to a valid page on own site
		if ( !empty($this->preferences['preview_url']) and ($strpos === 0)) {
			$iframe_url = esc_attr($this->preferences['preview_url']);
		} else {
			// default to home URL if invalid page
			$iframe_url = $this->home_url;
		}
		?>
		<iframe id="viframe" frameborder="0" name="viframe"
	        rel="<?php echo $iframe_url; ?>"
	        src="about:blank"></iframe>

		<div id="iframe-width-feedback"></div>

	</div>

	<div id="v-mq-controls" class="ruler-stuff">
		<span id="iframe-max-width"></span>
		<div id="v-mq-slider" class="tvr-slider"></div>
		<span id="iframe-min-width"></span>
	</div>


	<?php
	// do we show the mob devices preview?
	!$this->preferences['show_rulers'] ? $device_preview_class = 'hidden' : $device_preview_class = '';
	?>
	<div id="common-devices-preview" class="tvr-popright-wrap trigger-click ruler-stuff <?php echo $device_preview_class; ?>">
		<span class="tvr-pop-toggle" title="Toggle device previews"></span>
        <div class="tvr-popright">
			<div id="current-screen-width"></div>
			<div class="scrollable-area">
				<ul class="mob-preview-list">
					<?php
					foreach ($this->mob_preview as $i => $array){
						echo '
						<li id="mt-screen-preview-'.$i.'"
						class="mt-screen-preview" rel="'.$i.'">
						<span class="mt-screen-preview mob-wxh">'.$array[1].' x '.$array[2].'</span>
						<span class="mt-screen-preview mob-model">'.$array[0].'</span>
						</li>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>

	<div id="height-screen" class="hidden"></div>

</div>


