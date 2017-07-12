<?php
/**
 * Type cloud
 */




$types = elgg_extract('types', $vars, array());
if (!$types) {
	return true;
}

$friends = elgg_extract('friends', $vars, false);

$all = new stdClass;
$all->tag = "all";
elgg_register_menu_item('page', array(
	'name' => 'badge:all',
	'text' => elgg_echo('all'),
	'href' =>  badge_type_cloud_get_url($all, $friends),
));

foreach ($types as $type) {
	elgg_register_menu_item('page', array(
		'name' => "badge:$type->tag",
		'text' => elgg_echo("badge:type:$type->tag"),
		'href' =>  badge_type_cloud_get_url($type, $friends),
	));
}
