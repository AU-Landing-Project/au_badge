<?php
/**
 * Edit a badge
 *
 * @package ElggFile
 */

elgg_load_library('badgefunctions');

gatekeeper();

$badge_guid = (int) get_input('guid');
$badge = new BadgeFile($badge_guid);
if (!$badge) {
	forward();
}
if (!$badge->canEdit()) {
	forward();
}

$title = elgg_echo('badge:edit');

elgg_push_breadcrumb(elgg_echo('badge'), "badge/all");
elgg_push_breadcrumb($badge->title, $badge->getURL());
elgg_push_breadcrumb($title);

elgg_set_page_owner_guid($badge->getContainerGUID());

$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = badge_prepare_form_vars($badge);

$content = elgg_view_form('badge/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
