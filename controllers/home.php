<?php

$title = "Boooobs.in";
$description = "Boooobs.in";
$css_files['header'][] = '/css/landing.css';
$css_files['header'][] = '/css/media-queries.css';
$css_files['header'][] = '/fonts/fontsaddict/Fontsaddict.css';
$css_files['header'][] = '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css';

$js_files['footer'][] = 'js/landing.js';

$view = 'landing';

$search = new Search();
$search->set_max_items(5);
$search_twitter = $search->twitter('');
$search_reddit = $search->reddit('', 'boobs');
$search_tumblr = $search->tumblr();

$view_data['images']['reddit'] = $search_reddit['images'];
$view_data['images']['tumblr'] = $search_tumblr['images'];
$view_data['images']['twitter'] = $search_twitter['images'];

$view_data['usernames']['reddit'] = $search_reddit['usernames'];
$view_data['usernames']['tumblr'] = $search_tumblr['usernames'];
$view_data['usernames']['twitter'] = $search_twitter['usernames'];

$view_data['urls']['reddit'] = $search_reddit['urls'];
$view_data['urls']['tumblr'] = $search_tumblr['urls'];
$view_data['urls']['twitter'] = $search_twitter['urls'];


