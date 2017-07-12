<?php
//list badges awarded by selected user 

if($guid=get_input('guid')){
	$selected='myawarded';
}else{
	$selected='allawarded';
}

//might have entered a username instead of GUID
if (!is_numeric($guid)){
	if($user=get_user_by_username($guid)){
		$guid = $user->getGUID();
	}
}

$title = elgg_echo('badge:award:all');

$content=elgg_view('badge/listawardedbadges',array('guid'=>$guid));


$sidebar = badge_get_type_cloud();
$sidebar = elgg_view('badge/sidebar');

$body = elgg_view_layout('content', array(
	'filter_context' => 'allawarded',
	'filter_override' => elgg_view('badge/badgetabs', array('selected' => $selected)),
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
