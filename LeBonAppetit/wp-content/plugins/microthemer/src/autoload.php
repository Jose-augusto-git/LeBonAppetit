<?php

// Simple autoloader without using composer
// https://stackoverflow.com/questions/46162173/how-to-use-the-psr-4-autoload-in-my-classes-folder

spl_autoload_register(function ($class) {

	$prefix = 'Microthemer\\';
	$base_dir = dirname(__FILE__) . '/';
	$len = strlen($prefix);

	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}

	$relative_class = substr($class, $len);
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	if (file_exists($file)) {
		require $file;
	}

});