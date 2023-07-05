<?php

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Please do not call this page directly.');
}

// get property options
include 'property-options.inc.php';

$ui_class = '';
require_once('common-inline-assets.php');
?>

<div id="tvr" class='wrap tvr-wrap tvr-docs <?php echo $ui_class; ?>'>
	<div id='tvr-docs'>

		<!-- Google Search -->
		<div id="g-search">
			<div class="support-header-wrap">
				<ul id="external-links">
					<li class="doc-item external">
						<a target="_blank" class="video" href="<?php echo $this->demo_video; ?>">Demo<br />Video</a>
					</li>
					<li class="doc-item external">
						<a target="_blank" href="http://themeover.com/support/">Online<br />Docs</a>
					</li>
					<li class="doc-item external">
						<a target="_blank" href="http://themeover.com/forum/">Support<br />Forum</a>
					</li>
				</ul>
				<script>
					(function() {
						var cx = '000071969491634751241:fbvdr_lfrxg';
						var gcse = document.createElement('script');
						gcse.type = 'text/javascript';
						gcse.async = true;
						gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
						'//www.google.com/cse/cse.js?cx=' + cx;
						// when
						gcse.onreadystatechange = gcse.onload = function() {

						};
						var s = document.getElementsByTagName('script')[0];
						s.parentNode.insertBefore(gcse, s);
					})();
				</script>
				<gcse:searchbox enableAutoComplete="true"></gcse:searchbox>
			</div>
		</div>

		<div id="docs-container">

			<div id="docs-content">

				<div id="g-results">
					<gcse:searchresults linkTarget="_blank"></gcse:searchresults>
				</div>

				<?php

				$prop_group = $property = false;

				// support index page - not currently using
				if (isset($_GET['docs_index'])) {
					?>
					<div id="docs-index" class='ref-details docs-box'>


					</div>
					<?php
				}

				// CSS Reference
				if (isset($_GET['prop_group']) or isset($_GET['prop'])) {

					// single property
					$prop_group = htmlentities($_GET['prop_group']);
					$property = htmlentities($_GET['prop']);
					$pg_arr = $propertyOptions[$prop_group];
					$p_arr = $propertyOptions[$prop_group][$property];
					$cssf = str_replace('_', '-', $property);
					$icon_name = !empty($array['icon-name']) ? $array['icon-name'] : $cssf;
					?>
					<div id='<?php echo $property; ?>' class='ref-details docs-box'>
						<h3 class="main-title">

							<span class="t-text">
								<?php echo $p_arr['label']; ?>
							</span>
							<?php
							echo $this->iconFont($icon_name, array(
								'class' => 'no-click'
							));
							?>

						</h3>
						<div class="inner-box">
							<p><?php echo $p_arr['ref_desc']; ?></p>
							<?php
							if (!empty($p_arr['ref_values']) and is_array($p_arr['ref_values'])){
								?>
								<table class="prop-vals">
									<thead>
									<tr class='heading'>
										<th class="value">Value</th>
										<th>Description</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$i = 1;
									foreach ($p_arr['ref_values'] as $value => $desc) {
										?>
										<tr <?php if ($i&1) { echo 'class="odd"'; } ?>>
											<td class="value"><?php echo $value; ?></td><td><?php echo $desc; ?></td>
										</tr>
										<?php
										++$i;
									}
									?>
									</tbody>
								</table>
								<?php
							}
							?>


						</div>
					</div>
					<div id="tutorials-refs" class="docs-box">

						<?php
						$ref_map = array(
							'can_i_use' => array(
								'name' => 'Can I Use',
								'icon' => ''
							),
							'css_tricks' => array(
								'name' => 'CSS Tricks',
								'icon' => ''
							),
							'mozilla' => array(
								'name' => 'Mozilla',
								'icon' => ''
							),
							'quackit' => array(
								'name' => 'Quackit',
								'icon' => ''
							),
							'w3s' => array(
								'name' => 'W3Schools',
								'icon' => ''
							)
						);
						// css ref
						if (!empty($p_arr['ref_links']) and is_array($p_arr['ref_links'])){
							echo '<h3>'.$p_arr['label'].' References</h3>
							<ul>';
							foreach ($p_arr['ref_links'] as $key => $url){
								echo '
								<li>
									<a target="_blank" href="'.$url.'">'
										.$ref_map[$key]['name'].': ' . str_replace('_', '-', $property ). '
									</a>
								</li>';
							}
							echo '</ul>';
						}
						// tutorials
						if (!empty($p_arr['tutorials']) and is_array($p_arr['tutorials'])){
							// are the tutorials specific to the property or the property group?
							if (!empty($p_arr['group_tutorials'])){
								$scope = $this->property_option_groups[$prop_group];
								//$scope = $p_arr['pg_label'];
							} elseif (!empty($p_arr['subgroup_tutorials'])){
								$keys = array_keys($pg_arr);
								$scope = $pg_arr[$keys[0]]['sub_label'];
							} elseif (!empty($p_arr['related_tutorials'])){
								$scope = $p_arr['related_tutorials'];
							} else {
								$scope = $p_arr['label'];
							}
							echo '<h3>'.$scope.' Tutorials</h3>
							<ul>';
							foreach ($p_arr['tutorials'] as $key => $arr){
								echo '
								<li>
									<a target="_blank" href="'.$arr['url'].'">'
									.$arr['title'].'
									</a>
								</li>';
							}
							echo '</ul>';
						}
						?>
					</div>
				<?php

				}
				?>

			</div><!-- end content -->
		</div><!-- end container -->

		<?php

		// side menu
		$this->docs_menu($propertyOptions, $prop_group, $property);

		?>



	</div><!-- end tvr-docs -->
</div><!-- end wrap -->

