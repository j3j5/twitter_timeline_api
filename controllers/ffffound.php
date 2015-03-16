<?php

// Load the parent view
include(__DIR__ . '/social_template.php');

$default_user = 'mammout';

$user = !empty($query) ? $query : $default_user;
$title = 'BOOOOBS IN ffffound.com - ' . $user;
$description = 'Boobs in ffffound - user ' . $user ;
$results = $search->ffffound($user);



// Check there was no problem on URL extraction
if(!isset($results['images']) || empty($results['images'])) {
	// Load empty results page.
	var_dump('NO RESULTS!!'); exit;
}
$view_data['source'] = 'fa-caret-square-o-down';
$view_data['images'] = $results['images'];
$view_data['urls'] = $results['urls'];
$view_data['usernames'] = $results['usernames'];
$view = 'social/ffffound';
