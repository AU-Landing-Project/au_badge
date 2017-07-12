<?php

//lists badges that have been awarded and claimed

$user_guid=$vars['guid'];
 if($user_guid){
	 $filter=array('awarder_guid'=>$user_guid);
 }else{
 	 $filter='';
 }

//show claimed badges
$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'award',
	'full_view' => FALSE,
	'metadata_name_value_pairs' => $filter ,	
));




if (!$content) {
	$content = elgg_echo('badge:none');
}

echo $content;