<?php

//allow user to claim a badge
//recipient should be current user

if (!$recipient_guid=get_input('recipient')){
	register_error('badge:error:norecipient');
	forward (REFERER);
}

if (!$awardedbadge=get_input('award_guid')){
	register_error('badge:error:nobadge');
	forward (REFERER);
}

//check that recipient exists. 
if (!$this_guid=elgg_get_logged_in_user_guid()){
	register_error('badge:error:notuser');
	forward (REFERER);
}

$award=get_entity($awardedbadge);

if ($award->recipient_guid==$recipient_guid){
	
	$award->claimed=TRUE;
	system_message(elgg_echo('badge:claim:success')." recipient $recipient_guid badge $badge awarder $awarder_guid");
	
	//should send something to the river here and notify awarder of acceptance

} else {
	register_error(elgg_echo('badge:claim:failure')." recipient $recipient_guid badge $badge awarder $awarder_guid");	
}


forward (REFERER);
//set the awarded flag on the award



//create claimed relationship between badge and recipient
/*
if(!$relationship=check_entity_relationship($badge,'badge_claimed_by',$recipient_guid)){
	
	if(add_entity_relationship($badge,'badge_claimed_by',$recipient_guid)){
		system_message(elgg_echo('badge:claim:success')."recipient $recipient_guid badge $badge awarder $awarder_guid");
	}else{
		register_error(elgg_echo('badge:claim:failure')."recipient $recipient_guid badge $badge awarder $awarder_guid");

	}
}else{
		register_error(elgg_echo('badge:claim:duplicate')."recipient $recipient_guid badge $badge awarder $awarder_guid 
			original awarder {$relationship->owner_guid}");
}

*/
//alert user - note need to claim the badge

