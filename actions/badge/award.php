<?php

/* award badge
requires recipient, awarder and badge
creates relationship between badge and person
if and only if the awarder has rights to do so

@uses:
recipient_guid (required)
badge_guid (required)
entityfor (optional - defaults to current user profile)
awarder (optional - defaults to current logged in user)
award_guid (optional - only used if editing)

*/

if (!$recipient_guid=get_input('recipient')){
	register_error('badge:error:norecipient');
	forward (REFERER);
}

$recipient_entity=get_entity($recipient_guid);

if (!$badge_guid=get_input('badge_guid')){
	register_error('badge:error:nobadge');
	forward (REFERER);
}

if (!$entityfor=get_input('entityfor')){ //if no entityfor specified
	// the url is the profile of the user - it's a generic badge
	$entityfor=$recipient_entity->getGUID();
}

//get the badge entity that will be used to create this award

$badge= new BadgeFile($badge_guid);

//check that awarder guid has been sent and, if not, assume current user is awarder
$awarder_guid = get_input('awarder');
if (!$awarder_guid){
	//if no guid sent, assume current user (if any)
	if (!$awarder=elgg_get_logged_in_user_entity()){
		register_error('badge:error:notuser');
		forward (REFERER);
	} else {
	//if there is a current user, make that the guid
		$awarder_guid=$awarder->getGUID();
	}
}

//check whether new award or edit of old one
// note that we don't actually allow editing of awards
//should delete and start afresh if changes are needed
if ($award_guid=get_input('award_guid')){
	$exists=TRUE;
	//
	$award=get_entity('award_guid');
	$title=get_input('title');
	$description = get_input('description');
	$access_id = get_input('access_id');
	$container_guid=get_input('container_guid');
	$tags=get_input('tags');
	$criteria = get_input('criteria');
	$expiry_date = get_input('expiry_date');
}else{
	//this is a new award
	$exists=FALSE;
	$award= new AwardFile();
	$award->subtype='award';
}

//check whether awarder can award this badge

//is inherited right?

//has permission to award?

//If not, may be automatic. Check for auto flag

//If not, no rights, return with error


// create a new award - essentially clone a badge then add awarder and awardee
// we are cloning this so that the award persists even if the badge changes or is removed
//note that this makes the recipient the owner of the badge - can then do what they wish with it
//and it is no longer directly tied to the original badge (though can still query
// to discover who has received the badge etc

// note that right now we don't want it to be possible to edit an award
// if it's wrong, simply delete and start again

if ($exists && $award){
	$award->title = $title;
	$award->description = $description;
	$award->access_id = $access_id;
	$award->container_guid = $container_guid;
	$award->tags = $tags;
	$award->awarder_guid = $awarder_guid;
	$award->recipient_guid = $recipient_guid;
	$award->badge_guid = $badge_guid;
	$award->openbadge = $badge->openbadge;
	$award->claimed = $award->claimed;	
	$award->entityfor = $entityfor;
	$award->criteria = $criteria;
	$award->expiry_date = ($badge->lifespan>0)?date('U')+($badge->lifespan*60*60*24):0;
	
}else{
	//make a new award
	$award->title = $badge->title;
	$award->description = $badge->description;
	$award->access_id = $badge->access_id;
	$award->save();
	$award->container_guid = $badge->container_guid;
	$award->tags = $badge->tags;
	$award->awarder_guid = $awarder_guid;
	$award->recipient_guid = $recipient_guid;
	$award->badge_guid = $badge_guid; //need this to match up again when querying badges
	$award->claimed = FALSE;
	$award->entityfor = $entityfor;	
	$award->criteria = $badge->criteria;
	//set expiry date based on lifespan set in badge. 0 means unlimited
	$award->expiry_date = ($badge->lifespan>0)?date('U')+($badge->lifespan*60*60*24):0;

	//now copy file to the right place
	// note we are deliberately duplicating the image, not linking
	// because awards are permanent even if badges are deleted
		//first get the image
	$prefix="badge/award/{$award->guid}";
	$badgefile=$badge->openbadge;
	$path=pathinfo($badgefile);
	$award->setFilename($prefix.$path['filename'].".".$path['extension']);
	$award->open("write");
	$award->write($badge->grabFile($badgefile));
	$award->openbadge = $award->getFilename();	
	$award->setMimeType($badge->getMimeType());
	$award->close();
}


	//save it and retrieve guid
$award_guid = $award->save();
if ($award_guid){

	// notify if awarder wasn't recipient
	if ($award->owner_guid != $award->recipient_guid) {
	
		award_notify_user($awarder, $recipient_entity,$award->entityfor);
	}
	add_to_river('river/object/award/create', 'create', elgg_get_logged_in_user_guid(), $award->guid);
	
	system_message(elgg_echo('badge:award:success'). $award->title);
	
}else{
	register_error(elgg_echo('badge:award:failure')."recipient $recipient_guid badge $badge awarder $awarder_guid award $award_guid");
}



//alert user - note need to claim the badge

