<?php

// Load the parent view
include(__DIR__ . '/social_template.php');
$search->set_settings(array('tumblr' => $tumblr_api_key));


if(empty($query)) {
	$results = $search->tumblr();
} else {
	$clean_query = _sanitize_query($query);
	$title = 'BOOOOBS IN tumblr.com - ' . $clean_query;
	$description = 'Boobs in Tumblr - ' . $clean_query . ', Boobs everywhere.';
	$results = $search->tumblr($clean_query);
}

// Check there was no problem on URL extraction
if(!isset($results['images']) || empty($results['images'])) {
	// Load empty results page.
	var_dump(__LINE__);
	var_dump('NO RESULTS!!'); exit;
}

$view_data['source'] = 'fa-tumblr';
$view_data['images'] = $results['images'];
$view_data['urls'] = $results['urls'];
$view_data['usernames'] = $results['usernames'];
$view = 'social/ffffound';
