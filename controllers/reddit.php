<?php

// Load the parent view
include(__DIR__ . '/social_template.php');

$default_subreddit = 'Boobies';
if(empty($query)) {
	$subreddit = $default_subreddit;
} else {
	$subreddit = $query;
}
$after = FALSE;

// Overwrite the attributes
$title = 'BOOOOBS IN reddit.com/r/' . $subreddit;
$description = 'Boobs in /r/' . $subreddit . ', Boobs everywhere.';
$results = $search->reddit('', $subreddit, $after);

// Check there was no problem on URL extraction
if(!isset($results['images']) || empty($results['images'])) {
	// Load empty results page.
	var_dump($subreddit);
	var_dump($results);
	var_dump(__LINE__);
	var_dump('NO RESULTS!!'); exit;
}

$view_data['query'] = $subreddit;
$view_data['images'] = $results['images'];
$view_data['urls'] = $results['urls'];
$view_data['usernames'] = $results['usernames'];
$view_data['source'] = 'fa-reddit';
$view = 'social/reddit';


