<?php

// CLI execution
if(PHP_SAPI == 'cli') {
	$arguments_structure = array(
		'controller',
		'username',
	);
	if($argc >= 2) {
		foreach($arguments_structure AS $key => $component) {
			if(isset($argv[$key+1])) {
				${$component} = $argv[$key+1];
			}
		}
		if(!is_file(__DIR__ . DIRECTORY_SEPARATOR . "cli" . DIRECTORY_SEPARATOR . "$controller.php")) {
			$controller = '404';
		}
	} else {
		$controller = '404';
		$username = '';
	}
	$controller = "cli" . DIRECTORY_SEPARATOR ."$controller.php";
// WEBSERVER
} else {
	// The url is as follows http://yourserver.com/controller/query
	$url_structure = array(
		'controller',
		'query'
	);

	$uri_segments = FALSE;
	if(mb_strlen($_SERVER['REQUEST_URI']) > 1) { // Homepage has '/' as REQUEST_URI
		$uri_segments = explode('/', $_SERVER['REQUEST_URI']);
	}
	if(is_array($uri_segments)) {
		foreach($url_structure AS $key => $component) {
			if(isset($uri_segments[$key+1])) {
				${$component} = $uri_segments[$key+1];
			}
		}
		if(!is_file(__DIR__ . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . "$controller.php")) {
			$controller = '404';
		}
	} else {
		// Default controllers
		$controller = 'home';
		$username = '';
	}
	$controller = "controllers" . DIRECTORY_SEPARATOR ."$controller.php";
}
