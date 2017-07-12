<?php
/**
* Elgg badge delete
* 
* @package ElggFile
*/

$guid = (int) get_input('guid');

$badge = new BadgeFile($guid);
if (!$badge->guid) {
	register_error(elgg_echo("badge:deletefailed"));
	forward('badge/all');
}

if (!$badge->canEdit()) {
	register_error(elgg_echo("badge:deletefailed"));
	forward($badge->getURL());
}

$container = $badge->getContainerEntity();

if (!$badge->delete()) {
	register_error(elgg_echo("badge:deletefailed"));
} else {
	system_message(elgg_echo("badge:deleted"));
}


forward("badge/owner/$container->username");
