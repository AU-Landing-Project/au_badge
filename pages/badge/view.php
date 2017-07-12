<?php
/**
 * View a badge
 *
 * @package ElggFile
 */

$badge = new BadgeFile(get_input('guid'));
if (!$badge->guid) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('badge'), 'badge/all');

$crumbs_title = $owner->name;
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($crumbs_title, "badge/owner/$owner->username");
}

$title = $badge->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($badge, array('full_view' => true));
$content .= elgg_view_comments($badge);
$content.="<h1> debug info </h1>";
$content .= "<p>class is: ".$badge->getSubtype()."</p>";
$content .= "<p>mimetype is: ".$badge->getMimeType()."</p>";
$content .= "<p>assignable is: ".$badge->award_access_id."</p>";
$content .= "<p>lifespan is: ".$badge->lifespan."</p>";
$content.="<p> inheritable is: {$badge->inheritable} </p>";
$content.="<p> must have is: {$badge->musthavebadge} </p>";
$content.="<p> userpoints is: {$badge->userpoints} </p>";
$badgesawarded= elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'award','metadata_name_value_pairs'=>array('badge_guid'=>$badge->guid,'claimed'=>TRUE)));
$arrayrecipients=array('1'); //initialise with something non-existent so no null array returned
foreach($badgesawarded as $key=>$value){
//if ($badgesawarded[$key]->claimed){
		$arrayrecipients[]=$badgesawarded[$key]->recipient_guid;
//	}
	
}
	$content.=elgg_view_title(elgg_echo('badge:recipients'));
$content .= elgg_list_entities(array('type'=>'user', 'guids'=>$arrayrecipients));

elgg_register_menu_item('title', array(
	'name' => 'download',
	'text' => elgg_echo('badge:download'),
	'href' => "badge/download/$badge->guid",
	'link_class' => 'elgg-button elgg-button-action',
));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
