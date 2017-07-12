<?php
/**
 * Upload a new badge
 *
 * @package ElggFile
 */

elgg_load_library('badgefunctions');

$owner = elgg_get_page_owner_entity();

gatekeeper();

$title = elgg_echo('badge:add');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('badge'), "badge/all");

elgg_push_breadcrumb($owner->name, "badge/owner/$owner->username");

elgg_push_breadcrumb($title);

// create form
$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = badge_prepare_form_vars();
$content = elgg_view_form('badge/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
