<?php

// Load the parent view
include(__DIR__ . '/social_template.php');

$search->set_settings(array('twitter' => $twitter_settings));

if(empty($query)) {
	$results = $search->twitter('');
} else {
	$clean_query = _sanitize_query($query);
	$title = 'Twitter BOOOOBS IN ' . $clean_query;
	$description = 'Twitter boobs in ' . $clean_query . ', boobs everywhere.';
	$results= $search->twitter($clean_query);
}

// Check there was no problem on URL extraction
if(!isset($results['images']) || empty($results['images'])) {
	// Load empty results page.
	var_dump('NO RESULTS!!'); exit;
}
$view_data['source'] = 'fa-twitter';
$view_data['images'] = $results['images'];
$view_data['urls'] = $results['urls'];
$view_data['usernames'] = $results['usernames'];
$view = 'social/twitter';
