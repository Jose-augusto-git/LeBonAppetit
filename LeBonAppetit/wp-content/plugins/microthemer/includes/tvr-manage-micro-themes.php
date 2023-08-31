<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

// get valid design packs (not loose files or sass/scss folders)
$design_packs = $this->get_design_packs($this->file_structure);

// calc pagination stuff
$total_packs = $design_packs['count'];
$results_per_page = 20;
$total_pages = ceil($total_packs/$results_per_page);
if (isset($_GET['packs_page'])) {
	$page = htmlentities(intval($_GET['packs_page']));
} else {
	$page = 1;
}
// determine start and end
$start = (($page-1)*$results_per_page)+1;
$end = ($start+$results_per_page)-1;

if ($end > $total_packs) {
	$end = $total_packs;
}

$ui_class = '';
require_once('common-inline-assets.php');

?>
<div id="tvr" class='wrap tvr-wrap tvr-manage <?php echo $ui_class; ?>'>
	<div id='tvr-manage'>

		<?php
		// ajax loaders, meta spans, logs tmpl
		//$this->hidden_ajax_loaders();
		$this->manage_packs_meta();
		$this->manage_packs_header($this->microthemespage);
		?>

		<div id="full-logs">
			<?php echo $this->display_log(); ?>
		</div>

		<?php
		$this->pack_pagination($page, $total_pages, $total_packs, $start, $end);
		?>

		<ul id="all-packs">
			<?php
			// output packs
			$i = 0;
			foreach ($design_packs['directories'] as $pack => $array){

			    ++$i;
				if ($i < $start) {
					continue;
				} else if ($i > $end) {
					break;
				}
				?>
				<li>
					<div class="pack">
						<?php
						if (
						    !empty($array['screenshot'])
                            and file_exists($this->micro_root_dir . $pack.'/' . $array['screenshot'])
                        ) {
							$screenshot = $this->micro_root_url . $pack.'/' . $array['screenshot'];
						} else {
							$screenshot = $this->thispluginurl . 'images/screenshot-placeholder-small.gif';
						}
						$url = 'admin.php?page=' . $this->managesinglepage . '&design_pack=' . $pack;
						$name = $this->readable_name($pack);
						?>
						<a href="<?php echo $url ; ?>">
							<img src="<?php echo $screenshot; ?>" width="145" height="83"
								 title="<?php printf(esc_attr__('Edit %s', 'microthemer'), $name); ?>" />
						</a>
						<div class="pack-quick-icons mt-icon-line">

                            <?php

                            // delete
                            echo $this->iconFont('bin', array(
                                    'class' => 'delete-pack-multi download-icon',
                                    'rel' => 'delete',
                                    'data-dir-name' => $pack,
                                    'data-dir-label' => $name,
                                    'title' => sprintf(esc_attr__('Delete %s', 'microthemer'), $name)
                            ));

                            // download
                            echo $this->iconFont('cloud-download-alt', array(
	                            'class' => 'download-pack-multi',
	                            'rel' => 'download',
	                            'data-dir-name' => $pack,
	                            'title' => sprintf(esc_attr__('Download %s', 'microthemer'), $name)
                            ));

                            // Edit
                            echo $this->iconFont('edit', array(
                                'tag' => 'a',
	                            'href' => $url,
	                            //'class' => '',
	                            //'data-dir-name' => $pack,
	                            'title' => sprintf(esc_attr__('Edit %s', 'microthemer'), $name)
                            ));

                            // Import
                            echo $this->iconFont('import', array(
	                            'class' => 'quick-import show-parent-dialog',
	                            'rel' => 'import-from-pack',
	                            'data-pack-name' => $pack,
	                            'title' => sprintf(esc_attr__('Import %s into the Microthemer interface', 'microthemer'), $name)
                            ));
                            ?>

						</div>

						<?php
						// get meta info
						$meta_info = $this->read_meta_file($this->micro_root_dir . $pack . '/meta.txt');
						$h2 = '
						<a href="'.$url.'" class="title-text" title="' . sprintf(__('Edit %s', 'microthemer'), $name) . '">
							' . $this->readable_name($pack);
							// version
							if (!empty($meta_info['Version'])) {
								//$h2.=' <span class="version">'.$meta_info['Version'] . '</span>';
							}
							$h2.= '
						</a>';
						// author
						if (!empty($meta_info['Author'])) {
							$h2.= '<p class="author-name">' . sprintf( esc_html__( 'by %s', 'microthemer'), strip_tags($meta_info['Author']) ) . '</p>';
						}
						echo $h2;
						?>
					</div>
				</li>
				<?php
			}
			?>

		</ul>

		<?php
		$this->pack_pagination($page, $total_pages, $total_packs, $start, $end);
		?>

	</div>
</div><!-- end wrap -->
