<?php
/**
 * All badges
 *
 * @package ElggFile
 */

elgg_push_breadcrumb(elgg_echo('badge'));

//only show add badge button to admins if plugin setting not yes

if (elgg_get_plugin_setting('admin_only_badges','badge')=='no' ||  elgg_is_admin_logged_in()){
	elgg_register_title_button();
}


$title = elgg_echo('badge:all');

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'badge',
	'full_view' => FALSE
));
if (!$content) {
	$content = elgg_echo('badge:none');
}

$sidebar = badge_get_type_cloud();
$sidebar = elgg_view('badge/sidebar');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'filter_override' => elgg_view('badge/badgetabs', array('selected' => 'allbadges')),
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
