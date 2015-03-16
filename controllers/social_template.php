<?php

$title = "Boooobs in $controller";
$description = "Find all the boobs in " . ucwords($controller);

$css_files['header'][] = '/css/grids.css';
$css_files['header'][] = '/css/media-queries.css';
$css_files['header'][] = '/fonts/fontsaddict/Fontsaddict.css';
$css_files['header'][] = '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css';

$js_files['footer'][] = 'js/landing.js';

$view = $controller;

$search = new Search();
$search->set_max_items(100);

/**
 * Replace possible space replacements by '+' for the youtube search
 *
 * @param String $query
 *
 * @access private
 *
 * @return String
 */
function _sanitize_query($query) {
	$clean_query = str_replace('%20', '+', $query);
	$clean_query = str_replace('_', '+', $clean_query);
	$clean_query = str_replace('.', '+', $clean_query);
	return $clean_query;
}
