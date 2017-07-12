<?php
//claim button
// extends view when listing badges or awards
//used for claiming a badge


//


if (!isset($vars['entity'])|| !elgg_is_logged_in()) {
	return true;
}

$entity=$vars['entity'];
if ($entity->getSubtype()=='award' ||$entity->getSubtype()=='badge'){
	$award_guid = $entity->getGUID();
	$user_guid=elgg_get_logged_in_user_guid();
	// only logged in users can claim badges

	//if badge is claimable, provide link to claim it
	
	//get award entities for this badge and the current user
	$options= array('type'=>'object','subtype'=>'award','guid'=>$award_guid,
		'metadata_name_value_pairs'=>array('recipient_guid'=>$user_guid));

	$awarded=elgg_get_entities_from_metadata($options);	
	$claimed=false;		
	foreach($awarded as $key=>$value){
		if (!$awarded[$key]->claimed){
			$award_guid=$awarded[$key]->guid;
			$awarder=get_entity($awarded[$key]->awarder_guid);
			$awarder_name=$awarder->name;
			$url = elgg_get_site_url() . "action/badge/claim?award_guid={$award_guid}&badge_guid={$badge_guid}&recipient={$user_guid}";
			$params = array(
				'href' => $url,
				'text' => elgg_echo('badge:claim').elgg_view_icon('checkmark'),
				'title' => elgg_echo('badge:claim')." : ".elgg_echo('badge:awarder')." ".$awarder_name,
				'is_action' => true,
				'is_trusted' => true,
			);
			$claim_button = elgg_view('output/url', $params);
		}else{
			//not claimed
		}
	}		

}

echo $claim_button;

