<?php
/**
 * Friends Files
 *
 * @package ElggFile
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('', '404');
}

elgg_push_breadcrumb(elgg_echo('badge'), "badge/all");
elgg_push_breadcrumb($owner->name, "badge/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$title = elgg_echo("badge:friends");

// offset is grabbed in list_user_friends_objects
$content = list_user_friends_objects($owner->guid, 'badge', 10, false);
if (!$content) {
	$content = elgg_echo("badge:none");
}

$sidebar = badge_get_type_cloud($owner->guid, true);

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
