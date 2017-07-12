<?php
/**
* Elgg badge delete
* 
* @package ElggFile
*/

$guid = (int) get_input('guid');

$award = new AwardFile($guid);
if (!$award->guid) {
	register_error(elgg_echo("badge:award:deletefailed")." -no award specified");
	forward('badge/all');
}

if (!$award->canEdit()) {
	register_error(elgg_echo("badge:award:notallowed"));
	forward($award->getURL());
}

$container = $award->getContainerEntity();

if (!$award->delete()) {
	register_error(elgg_echo("badge:award:deletefailed")." -unknown error");
} else {
	system_message(elgg_echo("badge:deleted"));
}

forward(REFERER);