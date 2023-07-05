<?php

namespace Microthemer;

use Microthemer\Dependencies\Minify;

trait FileTrait {

	function setup_micro_themes_dir($activated = false){

		// create micro-themes directory and some subdirectories
		// the parent directory is created so just need define subdirectories
		$directories = array(
			/*$this->micro_root_dir,
			$this->micro_root_dir . 'mt/',
			$this->micro_root_dir . 'mt/conditional/',*/
			$this->micro_root_dir . 'mt/conditional/draft/',
			$this->micro_root_dir . 'mt/conditional/active/'
		);

		foreach ($directories as $path){

			if ( !is_dir($path) ) {

				// create micro-themes dirs or log an error
				if ( !wp_mkdir_p($path) ) {

					$this->log(
						esc_html__('/micro-themes create directory error', 'microthemer'),
						'<p>' . sprintf(
							esc_html__('WordPress was not able to create the directory: %s', 'microthemer'),
							$this->root_rel($path)
						) . $this->permissionshelp . '</p>'
					);

					return false;
				}
			}
		}

		/*else {
			 //micro-themes dir does exist, clean lose pack files that may exist due to past bug
			$this->maybe_clean_micro_root(); // 7.7.2016 - we can remove after a few months.
		}*/

		// create _scss dir if it doesn't exist (at some point)

		// copy animation-events over if needed
		if (!$this->maybe_copy_to_micro_root($activated)) {
			return false;
		};

		// also create blank active-styles else 404 before user adds styles
		/*$prime_files = array(
			$this->micro_root_dir.'active-styles.css',
			$this->micro_root_dir.'min.active-styles.css',
			$this->micro_root_dir.'draft-styles.css',
			$this->micro_root_dir.'min.draft-styles.css',
			$this->micro_root_dir.'active-styles.scss',
		);

		if (!$this->maybe_create_stylesheet($prime_files)) {
			return false;
		}*/

		// all good
		return true;
	}

	function dir_loop($dir, $result = array()) {

		foreach(scandir($dir) as $filename) {

			if ($filename[0] === '.' || $filename === 'stock' || $filename === 'mt') {
				continue;
			}

			//wp_die('$filename: <pre>'.print_r($filename, 1).'</pre>' );

			$filePath = $dir . DIRECTORY_SEPARATOR . $filename;

			if (is_dir($filePath)) {
				$result[$filename] = $this->dir_loop($filePath);
			}

			else {
				if ($this->is_screenshot($filename)){
					$result['screenshot'] = $filename;
				} else {
					$result[$filename] = 1;
				}
			}
		}

		// sort alphabetically
		if (is_array($result)) {
			ksort($result);
		}

		return $result;
	}

	// get extension
	function has_extension($file) {
		return count(explode('.', $file)) > 1;
		//return preg_match('/\..+$/', explode('?', $file)[0]);
	}

	// create active-styles if it doesn't already exist
	function maybe_create_stylesheet($prime_files){
		if (is_array($prime_files)){
			foreach($prime_files as $key => $file){
				if (!file_exists($file)) {
					if (!$write_file = @fopen($file, 'w')) {
						$this->log(
							esc_html__('Create stylesheet error', 'microthemer'),
							'<p>' . esc_html__('WordPress does not have permission to create: ', 'microthemer') .
							$this->root_rel($file) . '. '.$this->permissionshelp.'</p>'
						);
						return false;
					}
					fclose($write_file);
				}
			}
		}
		return true;
	}

	// copy files from Microthemer plugin dir to micro-themes for use when MT is inactive
	function maybe_copy_to_micro_root($activated){

		$orig_files = array(
			'/js-min/animation-events.js',
			'/stock/stock.zip',
			'/src/AssetLoad.php',
			'/src/Logic.php',
			'/includes/animation/animation-code.inc.php'
		);
		$new_files = array(
			'animation-events.js',
			'stock.zip',
			'AssetLoad.php',
			'Logic.php',
			'animation-code.inc.php'
		);
		$i = 0;

		foreach($orig_files as $file){

			$orig = $this->thisplugindir .  $file;
			$newFileName = $new_files[$i];
			$new = $this->micro_root_dir . $newFileName;
			$i++;

			// if we are updating / activating
			if ($activated

			    // or it's stock.zip and the folder hasn't already been extracted
			    || ($newFileName === 'stock.zip' && !is_dir($this->micro_root_dir.'stock'))

			    // or it's any other file, which doesn't exist
			    || ($newFileName !== 'stock.zip' && !file_exists($new))
			){

				//wp_die('We need to copy file: '. $this->micro_root_dir.'stock');

				// attempt copy, and notify if it fails
				if (!copy($orig, $new)){
					$this->log(
						esc_html__('File not copied', 'microthemer'),
						'<p>' . sprintf(
							esc_html__('Plugin file (%s) could not be copied to the 
								/micro-themes directory.', 'microthemer'),
							$file
						) . '</p>',
						'error'
					);
					return false;
				}

				// copy was successful
				else {

					//wp_die('Copy successful, try extracting '. $newFileName);

					if ($newFileName === 'stock.zip'){

						//mkdir($this->micro_root_dir.'stock/', 0755);
						$error_logged = false;
						$success = false;

						// try native PHP function if it exists
						//$success = $this->extract_files_native($this->micro_root_dir, $new);

						// try WordPress function fallback
						if (!$success){

							$result = $this->wp_extract_files($this->micro_root_dir, $new);
							$success = $result['success'];

							if (!$success){

								//wp_die('Extract WP attempt'. print_r($result, true));

								$this->log(
									esc_html__('Extract zip error', 'microthemer'),
									'<p>' . $newFileName . ' could not be extracted to ' . $this->root_rel($this->micro_root_dir.'stock/') .'</p>' . '<p><pre>'. print_r($result['data']->errors, true).'</pre></p>'
								);

								$error_logged = true;
							}

						}

						// try PCLZip library fallback - no this returns true even though nothing happens
						// maye need to update the condition, but WP does this anyway.
						/*if (!$success){
									$success = $this->extract_files($this->micro_root_dir, $new);
									$error_logged = true;
								}*/

						if ($success){
							//wp_die('Extract successful: '. $this->micro_root_dir.'stock');
							@unlink($new);
						}

						else {
							if (!$error_logged){
								$this->log(
									esc_html__('Extract zip error', 'microthemer'),
									'<p>' . $newFileName . ' could not be extracted to ' . $this->root_rel($this->micro_root_dir.'stock/') .'</p>'
								);
							}

						}


					}
				}
			}
		}

		return true;
	}

	// Copy an entire folder
	function copyFolder($from, $to, $minifyCSS = false, $minifyJS = false) {

		if (!is_dir($from)) {
			return false; // bail if no directory to copy
		} if (!is_dir($to)) {
			if (!mkdir($to)) {
				return false; // bail if we can't create destination folder
			};
		}

		// Copy folder files recursively
		$dir = opendir($from);
		while (($ff = readdir($dir)) !== false) {
			if ($ff != "." && $ff != "..") {
				if (is_dir("$from$ff")) {
					$this->copyFolder("$from$ff/", "$to$ff/");
				} else {

					// copy or copy and minify the file
					$ext = $this->get_extension($ff);
					$doMinify = $ext === 'css' && $minifyCSS || $ext === 'js' && $minifyJS;
					if ($doMinify){
						$this->minify("$from$ff", $ext, "$to$ff");
					} else {
						copy("$from$ff", "$to$ff");
					}
				}
			}
		}

		closedir($dir);
	}

	// clean lose pack files that may exist due to past bug
	function maybe_clean_micro_root(){
		$files = array(
			'meta.txt',
			'debug-save.txt',
			'debug-current.txt',
			'debug-pulled-data.txt',
			'debug-selective-export.txt',
			'debug-merge.txt',
			'debug-overwrite.txt'
		);
		foreach ($files as $key => $file){
			$file = $this->micro_root_dir . $file;
			if (file_exists($file)){
				unlink($file);
			}
		}
	}


	function wp_extract_files($dest, $zip_file){

		global $wp_filesystem;

		// ensure file_system object is initiated
		if (!$wp_filesystem){

			// call file system manually if function not loaded yet
			if (!function_exists('WP_Filesystem')){

				$fileSystemClass = ABSPATH . 'wp-admin/includes/file.php';

				if (!file_exists($fileSystemClass)){
					return false;
				}

				// include class manually
				require_once($fileSystemClass);
			}

			// initiate file_system, which unzip_file assumes is set up
			\WP_Filesystem();

		}

		$result =  unzip_file($zip_file, $dest);

		//wp_die('Res: '. print_r($res, true));

		return array(
			'success' => ($result === TRUE),
			'data' => $result
		);
	}

	function extract_files_native($dest, $zip_file){

		$res = false;

		if (class_exists( 'ZipArchive')){
			$zip = new \ZipArchive;
			$res = $zip->open($zip_file);
		}

		if ($res === TRUE) {
			$zip->extractTo($dest);
			$zip->close();
			return true;
		} else {
			return false;
		}
	}

	// handle zip extraction
	function extract_files($dir, $file) {

		// tap into native WP zip handling
		if( !class_exists('PclZip')) {
			require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		}


		$archive = new \PclZip($file);
		// extract all files in one folder - the callback functions
		// (tvr_microthemer_getOnlyValid)
		// have to be external to this class
		if ($archive->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_REMOVE_ALL_PATH,
				PCLZIP_CB_PRE_EXTRACT, '\Microthemer\tvr_microthemer_getOnlyValid') == 0) {


			$this->log(
				esc_html__('Extract zip error', 'microthemer'),
				'<p>' . esc_html__('Error : ', 'microthemer') . $archive->errorInfo(true).'</p>'
			);

			return false;
		}

		return true;
	}

	// handle zip archiving
	function create_zip($path_to_dir, $dir_name, $zip_store) {
		$error = false;
		if( !class_exists('PclZip')) {
			require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		}
		// check if the /zip-exports dir is writable first
		if (is_writable($zip_store)) {
			$archive = new \PclZip($zip_store.$dir_name.'.zip');
			// create zip
			$v_list = $archive->create($path_to_dir.$dir_name,
				PCLZIP_OPT_REMOVE_PATH, $path_to_dir);

			if ($v_list == 0) {
				$error = true;
				$this->log(
					esc_html__('Create zip error', 'microthemer'),
					'<p>' . esc_html__('Error : ', 'microthemer') . $archive->errorInfo(true).'</p>'
				);
			}
			else {
				$this->log(
					esc_html__('Zip package created', 'microthemer'),
					'<p>' . esc_html__('Zip package successfully created.', 'microthemer') .
					'<a href="'.$this->thispluginurl.'zip-exports/'.$dir_name.'.zip">' .
					esc_html__('Download zip file', 'microthemer') . '</a>
						</p>',
					'notice'
				);
			}
		}
		else {
			$error = true;
			$this->log(
				esc_html__('Zip store error', 'microthemer'),
				'<p>' . sprintf(
					esc_html__('The directory %s is not writable.', 'microthemer'),
					$this->root_rel($zip_store)
				) . $this->permissionshelp . '</p>'
			);
		}
		// verdict
		if ($error){
			return false;
		} else {
			return true;
		}
	}

	function getDirectoryFileList($directory){
		return array_diff(scandir($directory), array('..', '.'));
	}

	function maybeLoadMinify(){

		if (!class_exists('Microthemer\Dependencies\Minify')){

			$path = dirname(__FILE__) . '/Dependencies/Minify';
			require_once $path . '/minify/src/Minify.php';
			require_once $path . '/minify/src/CSS.php';
			require_once $path . '/minify/src/JS.php';
			require_once $path . '/minify/src/Exception.php';
			require_once $path . '/minify/src/Exceptions/BasicException.php';
			require_once $path . '/minify/src/Exceptions/FileImportException.php';
			require_once $path . '/minify/src/Exceptions/IOException.php';
			require_once $path . '/path-converter/src/ConverterInterface.php';
			require_once $path . '/path-converter/src/Converter.php';
		}

	}

	function minify($data = null, $type = 'css', $file = false){

		$this->maybeLoadMinify();

		// setup minifier for CSS or JavaScript
		if ($type === 'css'){
			$minifier = new Minify\CSS($data);
		} else {
			$minifier = new Minify\JS($data);
		}

		// return minified data or write directly to a file
		if ($file){
			$minifier->minify($file);
			return true;
		} else {
			return $minifier->minify();
		}

	}

	// write to a file
	// the file will be created if it doesn't exist. Otherwise, it is overwritten.
	function write_file($file, $data, $minify = false, $dataType = false){
		
		$write_file = @fopen($file, 'w');
		$write_data = &$data; 
		
		// perhaps we minify
		if ($minify){
			$write_data = $this->minify($data, $dataType);
		}

		// if write is unsuccessful for some reason
		if (false === fwrite($write_file, $write_data)) {
			
			fclose($write_file);
			
			$this->log(
				esc_html__('File write error', 'microthemer'),
				'<p>' . sprintf(esc_html__('Writing to %s failed.', 'microthemer'),
					$this->root_rel($file)) . $this->permissionshelp.'</p>'
			);
			
			return false;
		}
		
		fclose($write_file);
		
		return true;
	}


}
