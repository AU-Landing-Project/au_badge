<?php

// extends object/elements/summary view
//shows awarder and recipient of badge when viewing an award entity
$award=$vars['entity'];
if ($award->getSubtype()=='award'){
	$awarder=get_entity($award->awarder_guid);
	$recipient=get_entity($award->recipient_guid);
	echo elgg_echo('badge:awarder');
	echo elgg_view_entity_icon($awarder,'tiny');
	echo elgg_view_icon('arrow-right');
	if($award->claimed){
		echo elgg_echo('badge:claimedby');
	}else{
		echo elgg_echo('badge:unclaimedby');
	}
	echo elgg_view_icon('arrow-right');
	echo elgg_view_entity_icon($recipient,'tiny');
	
}