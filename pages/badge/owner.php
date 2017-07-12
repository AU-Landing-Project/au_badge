<?php
/**
 * Individual's  badges
 *
 * @package ElggFile
 */



$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('', '404');
}

elgg_push_breadcrumb(elgg_echo('badge'), "badge/all");
elgg_push_breadcrumb($owner->name);

//only show add badge button to admins if plugin setting not yes
if (elgg_get_plugin_setting('admin_only_badges','badge')=='no' ||  elgg_is_admin_logged_in()){
	elgg_register_title_button();
}


$params = array();
$selected='allbadges';

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user looking at own badges
	$params['filter_context'] = 'mine';
	$selected='mybadges';
} else if (elgg_instanceof($owner, 'user')) {
	// someone else's badges
	// do not show select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
} else {
	// group badges
	$params['filter'] = '';
}

$title = elgg_echo("badge:user", array($owner->name));

// List badges
$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'badge',
	'container_guid' => $owner->guid,
	'full_view' => FALSE,
));
if (!$content) {
	$content = elgg_echo("badge:none");
}

$sidebar = badge_get_type_cloud(elgg_get_page_owner_guid());
$sidebar = elgg_view('badge/sidebar');

$params['content'] = $content;
$params['title'] = $title;
$params['sidebar'] = $sidebar;
$params['filter_override'] = elgg_view('badge/badgetabs', array('selected' => $selected));


$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
