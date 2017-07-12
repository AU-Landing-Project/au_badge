<?php
//extends entity menu to allow a badge to be awarded for a page
// requires entity, recipient guid and user guid


$entity=elgg_extract('entity');
$user=elgg_get_logged_in_user_guid();
$owner = elgg_get_page_owner_guid()
if (!$user || !$entity || !$owner){
	register_error('badge:cannotaward'.$user.$owner)
	forward(REFERER);
}

$site_url=elgg_get_site_url();
$href=$entity->getURL();

echo elgg_view('output/url',array('href'=>"award?url=$href&recipient=$owner",'text'=>"give a badge for this"));
