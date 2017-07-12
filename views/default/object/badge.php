<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, FALSE);
$ebadge = elgg_extract('entity', $vars, FALSE);

//if using the popup award form, pass the making award variable
// this is sent to the plugin hook to remove edit, delete etc buttons
// when selecting a badge to award from the list
$making_award=$vars['making_award'];


$badge=new BadgeFile($ebadge->guid);

if (!$badge) {
	return TRUE;
}

$owner = $badge->getOwnerEntity();
$container = $badge->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($badge->description);
$mime = $badge->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$owner_link = elgg_view('output/url', array(
	'href' => "badge/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));

$badge_icon = elgg_view_entity_icon($badge, 'small');

$date = elgg_view_friendly_time($badge->time_created);

$comments_count = $badge->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $badge->getURL() . '#badge-comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}


$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'badge',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	'making_award' => $making_award,
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {

	$extra = '';
	if (elgg_view_exists("badge/specialcontent/$mime")) {
		$extra = elgg_view("badge/specialcontent/$mime", $vars);
	} else if (elgg_view_exists("badge/specialcontent/$base_type/default")) {
		$extra = elgg_view("badge/specialcontent/$base_type/default", $vars);
	}

	$params = array(
		'entity' => $badge,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);
	
	//debug
	$describeit = "<h3>".elgg_echo('description')."</h3>";
	$describeit .= $badge->description;
	//." filename: ".$badge->getFilenameOnFilestore;
	$describeit .= "<h3>".elgg_echo('badge:criteria')."</h3>";
	$describeit .= $badge->criteria;
	
	// provide more information about the badge
	$describeit .= "<h3>".elgg_echo('badge:moreinfo')."</h3>";
	//who can award it
	$describeit .= "<p>". elgg_echo('badge:assign:display');
	if ($badge->award_access_id > 0){
		$describeit .= elgg_echo('badge:assign:anyone')."</p>";
		
	} else {
		$describeit .= elgg_echo('badge:assign:owner')."</p>";
	}
	
	// show the lifespan
	if ($badge->lifespan > 0){
		$describeit .= "<p> ".elgg_echo('badge:lifespan')." ". $badge->lifespan." ".elgg_echo('badge:lifespan:days')."</p>";		
	}else{
		$describeit .= "<p>".elgg_echo('badge:expires:never:display')."</p>";
		
	}

	if ($badge->musthavebadge>0){
		$prereqentity=get_entity($badge->musthavebadge);
		$prereqbadge = $prereqentity->title;
		$describeit.="<p>".elgg_echo('badge:musthavebadge')."<strong><em> $prereqbadge </em></strong> </p>";
		
	}else{
		$describeit.="<p>".elgg_echo('badge:musthavebadge:none')."</p>";
	}
	//auto award
	if ($badge->userpoints>0){
		$describeit.="<p>".elgg_echo('badge:userpoints:display')." {$badge->userpoints} </p>";		
	}else{
		$describeit .= "<p>".elgg_echo('badge:userpoints:none')."</p>";
	}


	$text = elgg_view('output/longtext', array('value' => $describeit));
	$body = "$text $extra";

	echo elgg_view('object/elements/full', array(
		'entity' => $badge,
		'icon' => $badge_icon,
		'summary' => $summary,
		'body' => $body,
	));

} elseif (elgg_in_context('gallery')) {
	echo '<div class="badge-gallery-item">';
	echo "<h3>" . $badge->title . "</h3>";
	echo elgg_view_entity_icon($badge, 'medium');
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
} else {
	// brief view

	$params = array(
		'entity' => $badge,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($badge_icon, $list_body);
}
