<?php

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

// filter controls
$filters = array(
	'sections' => array(
		'query' => array(
			'title'   => esc_html__('Search', 'microthemer'),
			'inputs'  => array(
				array(
					'type' => 'text'
				),

			)
		),
		'category' => array(
			'title'   => esc_html__('Categories', 'microthemer'),
			'inputs'  => array(
				array(
					'type' => 'checkbox',
					'value' => 'serif',
					'label' => esc_html__('Serif', 'microthemer'),
				),
				array(
					'type' => 'checkbox',
					'value' => 'sans-serif',
					'label' => esc_html__('Sans Serif', 'microthemer'),
				),
				array(
					'type' => 'checkbox',
					'value' => 'display',
					'label' => esc_html__('Display', 'microthemer'),
				),
				array(
					'type' => 'checkbox',
					'value' => 'handwriting',
					'label' => esc_html__('Handwriting', 'microthemer'),
				),
				array(
					'type' => 'checkbox',
					'value' => 'monospace',
					'label' => esc_html__('Monospace', 'microthemer'),
				)
			)
		),
		'sort' => array(
			'title'   => esc_html__('Sorting', 'microthemer'),
			'inputs'  => array(
				array(
					'type' => 'select',
					'options' => array(
						array(
							'value' => 'popularity',
							'label' => esc_html__('Popular', 'microthemer'),
						),
						array(
							'value' => 'trending',
							'label' => esc_html__('Trending', 'microthemer'),
						),
						array(
							'value' => 'alpha',
							'label' => esc_html__('Alphabetical', 'microthemer'),
						),
						array(
							'value' => 'date',
							'label' => esc_html__('Date Added', 'microthemer'),
						),
						array(
							'value' => 'style',
							'label' => esc_html__('Number of styles', 'microthemer'),
						)
					)
				),

			)
		),
		'subset' => array(
			'title'   => esc_html__('Languages', 'microthemer'),
			'inputs'  => array(
				array(
					'type' => 'select',
					'options' => array(
						array(
							'value' => '',
							'label' => esc_html__('All Languages', 'microthemer'),
						),
						array(
							'value' => 'arabic',
							'label' => esc_html__('Arabic', 'microthemer'),
						),
						array(
							'value' => 'bengali',
							'label' => esc_html__('Bengali', 'microthemer'),
						),
						array(
							'value' => 'cyrillic',
							'label' => esc_html__('Cyrillic', 'microthemer'),
						),
						array(
							'value' => 'cyrillic-ext',
							'label' => esc_html__('Cyrillic Extended', 'microthemer'),
						),
						array(
							'value' => 'devanagari',
							'label' => esc_html__('Devanagari', 'microthemer'),
						),
						array(
							'value' => 'cyrillic-ext',
							'label' => esc_html__('Cyrillic Extended', 'microthemer'),
						),
						array(
							'value' => 'greek',
							'label' => esc_html__('Greek', 'microthemer'),
						),
						array(
							'value' => 'gujarati',
							'label' => esc_html__('Gujarati', 'microthemer'),
						),
						array(
							'value' => 'gurmukhi',
							'label' => esc_html__('Gurmukhi', 'microthemer'),
						),
						array(
							'value' => 'hebrew',
							'label' => esc_html__('Hebrew', 'microthemer'),
						),
						array(
							'value' => 'devanagari',
							'label' => esc_html__('Devanagari', 'microthemer'),
						),
						array(
							'value' => 'kannada',
							'label' => esc_html__('Kannada', 'microthemer'),
						),
						array(
							'value' => 'khmer',
							'label' => esc_html__('Khmer', 'microthemer'),
						),
						array(
							'value' => 'korean',
							'label' => esc_html__('Korean', 'microthemer'),
						),
						array(
							'value' => 'latin',
							'label' => esc_html__('Latin', 'microthemer'),
						),
						array(
							'value' => 'latin-ext',
							'label' => esc_html__('Latin Extended', 'microthemer'),
						),
						array(
							'value' => 'malayalam',
							'label' => esc_html__('Malayalam', 'microthemer'),
						),
						array(
							'value' => 'myanmar',
							'label' => esc_html__('Myanmar', 'microthemer'),
						),
						array(
							'value' => 'oriya',
							'label' => esc_html__('Oriya', 'microthemer'),
						),
						array(
							'value' => 'sinhala',
							'label' => esc_html__('Sinhala', 'microthemer'),
						),
						array(
							'value' => 'tamil',
							'label' => esc_html__('Tamil', 'microthemer'),
						),
						array(
							'value' => 'telugu',
							'label' => esc_html__('Telugu', 'microthemer'),
						),
						array(
							'value' => 'thai',
							'label' => esc_html__('Thai', 'microthemer'),
						),
					)
				),

			)
		)
	)
);

// user adjustment controls
$font_adjustments = array(
	'sections' => array(
		'preview_text' => array(
			'title'   => esc_html__('Content', 'microthemer'),
			'inputs'  => array(
				array(
					'type' => 'select',
					'options' => array(
						array(
							'value' => 'sentence',
							'label' => esc_html__('Sentence', 'microthemer'),
						),
						array(
							'value' => 'paragraph',
							'label' => esc_html__('Paragraph', 'microthemer'),
						),
						array(
							'value' => 'alphabet',
							'label' => esc_html__('Alphabet', 'microthemer'),
						),
						array(
							'value' => 'numerals',
							'label' => esc_html__('Numerals', 'microthemer'),
						),
						array(
							'value' => 'custom',
							'label' => esc_html__('Custom', 'microthemer'),
						)
					)
				),

			)
		),
		'variant' => array(
			'title'   => esc_html__('Styles', 'microthemer'),
			'inputs'  => array(
				array(
					'type' => 'select',
					'options' => array()
				),

			)
		),
		'font_size' => array(
			'title'   => esc_html__('Size', 'microthemer'),
			'inputs'  => array(
				array(
					'type' => 'number'
				),

			)
		),
	)
);

// convert form array data to HTML
function tvr_render_form_inputs($name, $section){

    foreach($section['inputs'] as $input){

        $value = !empty($input['value']) ? $input['value'] : '';

		switch ($input['type']) {

			// input
			case 'text':
			case 'number':
			    ?>
                <p>
                    <input type="<?php echo $input['type']; ?>" name="<?php echo $name; ?>" value="" placeholder="<?php echo $section['title']; ?>" />
                </p>
				<?php
				break;

			// checkbox
			case 'checkbox':
				?>
                <p>
                    <input type="checkbox" name="<?php echo $name; ?>[<?php echo $value; ?>]"
                           value="1" data-category="<?php echo $value; ?>" />

                    <?php
                    // can't use $this in this file
                   /* echo $this->iconFont('tick-box-unchecked', array(
                            'class' => 'fake-checkbox',
                            'data-action' => 'toggle-category'
                    ));*/
                    ?>
                    <span class="fake-checkbox mtif mtif-tick-box-unchecked" data-action="toggle-category"></span>
                    <label><?php echo $input['label']; ?></label>
                </p>
				<?php
				break;

			// select
			case 'select':
				?>
                <p>
                    <select name="<?php echo $name; ?>">
						<?php
						foreach($input['options'] as $option){
							echo '<option value="'.$option['value'].'">'.$option['label'].'</option>';
						}
						?>
                    </select>
                </p>
				<?php
				break;
		}
	}
}

$ui_class = '';
require_once('common-inline-assets.php');

?>

<div id="tvr" class='wrap tvr-wrap tvr-fonts <?php echo $ui_class; ?>'>
	<div id='tvr-fonts'>

        <div id="controls">

            <h3>
                <span class="font-count"></span>
                <span class="fonts-word">
		                <?php echo esc_html__('fonts', 'microthemer'); ?>
                    </span>
                <span id="clear-filters" class="clear-filters link" data-action="clear_filters">
		                <?php echo esc_html__('Clear filters', 'microthemer'); ?>
                    </span>
            </h3>

            <?php
            // output filter controls
            foreach($filters['sections'] as $name => $section){
                ?>
                <div id="<?php echo $name; ?>-filter" class="font-filter">
                    <h3><?php echo $section['title']; ?></h3>
                    <?php
                    tvr_render_form_inputs($name, $section);
                    ?>
                </div>
                <?php
            }
            ?>

        </div>

        <!-- area for displaying fonts -->
        <div id="gf-output"></div>


        <!-- pagination -->
        <div id="pagination">

            <div class="bottom-pagination">

                <div class="prev-controls">
                        <span class="first-page" data-value="first" data-action="pagination"
                              title="<?php echo esc_attr__('First page', 'microthemer'); ?>">&laquo;</span>
                    <span class="prev-page" data-value="prev" data-action="pagination"
                          title="<?php echo esc_attr__('Previous page', 'microthemer'); ?>">&lsaquo;</span>
                </div>

                <div class="page-controls">
                    <span class="page-word"><?php echo esc_attr__('Page', 'microthemer'); ?>: </span>
                    <input id="current-font-page" class="current-font-page pagination-input"
                           data-action="pagination" data-value="manual" name="current_page" value="1" />
                    <span class="page-slash"> / </span>
                    <span class="page-total"></span>
                </div>

                <div class="next-controls">
                        <span class="gf-last-page" data-value="last" data-action="pagination"
                              title="<?php echo esc_attr__('Last page', 'microthemer'); ?>">&raquo;</span>
                    <span class="gf-next-page" data-value="next" data-action="pagination"
                          title="<?php echo esc_attr__('Next page', 'microthemer'); ?>">&rsaquo;</span>
                </div>

            </div>

        </div>

        <!-- HTML template for font entry -->
        <ul id="font-entry-template">
            <li class="font-entry">
                <div class="font-name"></div>
                <div class="font-adjustments">
                    <?php
                    foreach($font_adjustments['sections'] as $name => $section){
	                    ?>
                        <div class="font-adjustment <?php echo $name; ?>-adjustment">
		                    <?php
		                    tvr_render_form_inputs($name, $section);
		                    ?>
                        </div>
	                    <?php
                    }
                    ?>
                    <span class="link apply-to-all" data-action="apply_to_all"><?php echo esc_html__('Apply to all fonts', 'microthemer'); ?></span>
                </div>

                <!-- This shows when language ISN'T set, as height/overflow has no glitches -->
                <div class="font div-font" contenteditable="true" data-action="show-textarea"></div>

                <!-- This shows when language IS set, as subsets don't load properly on editable div -->
                <textarea class="font textarea-font" name="font_textarea" spellcheck="false"></textarea>

                <span class="tvr-button use-font" data-action="insert-font"><?php echo esc_html__('Use font', 'microthemer'); ?></span>
            </li>
        </ul>


	</div><!-- end tvr-docs -->
</div><!-- end wrap -->