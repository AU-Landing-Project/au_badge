<?php
/* 

* this is used in a lightbox to display badges that can be awarded for selected user

* requires reciptient and  url

*/

$recipient=get_input('recipient');
$recipient_entity=get_entity($recipient);
$entityfor=get_input('entityfor');
$targettext=elgg_echo('badge:selected_entity');
$user_guid=elgg_get_logged_in_user_guid();

//if no entity-for specified, make it the user profile
if(!$entityfor){	
	$entityfor=$recipient_entity->getGUID();
	$targettext = elgg_echo('badge:selected_user');
}

$title = elgg_echo('badge:award:to_for',array($recipient_entity->name,$targettext));

$awardable_badge = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'badge',
));

//only want to show badges with permissions to be awarded - used by can_award_badge()
$filter=get_access_array($user_guid);


foreach ($awardable_badge as $key=>$value){
	
	//only show if awardable
	//the owner or admin can always award a badge
	if(can_award_badge($awardable_badge[$key],$user_guid,$filter,$recipient)){
		$content.="<li>";	
		if (!$thisbadge=elgg_view_entity($awardable_badge[$key],array('full_view'=>false,'recipient'=>$recipient,'entityfor'=>$entityfor,
				'making_award'=>TRUE))){
			$thisbadge=$awardable_badge[$key]->guid;
		}
		$content.= $thisbadge;
		//elgg_view('output/url',array('href'=>'badge/award','text'=>$thisbadge));
		$content.="</li>";	
	}
}




if (!$content) {
	$content .= elgg_echo('badge:none');
}

$content=elgg_view_title($title)."<div class='badge-list'><ul>$content</ul></div>";

echo elgg_echo($content);

