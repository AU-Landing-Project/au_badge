<?php

//lists badges that have been awarded

$user_guid=$vars['guid'];
 if($user_guid){
	 $filter=array('claimed'=>TRUE, 'recipient_guid'=>$user_guid);
 }else{
 	 $filter=array('claimed'=>TRUE);
 }

//now show claimable badges
$content .= elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'award',
	'full_view' => FALSE,
	'metadata_name_value_pairs' => $filter,
));



if (!$content) {
	$content = elgg_echo('badge:none');
}

echo $content;