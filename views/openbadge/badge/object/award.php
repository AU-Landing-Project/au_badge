<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, FALSE);
$eaward = elgg_extract('entity', $vars, FALSE);

$award=new AwardFile($eaward->guid);

if (!$award) {
	return TRUE;
}

$owner = $award->getOwnerEntity();
$container = $award->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($award->description).elgg_get_excerpt($award->criteria);

//note that this should always be PNG
$mime = $award->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$owner_link = elgg_view('output/url', array(
	'href' => "badge/claimed/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));

$badge_icon = elgg_view_entity_icon($award, 'small');

$date = elgg_view_friendly_time($award->time_created);

$comments_count = $award->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $award->getURL() . '#badge-comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'award',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {

	$extra = '';


	$params = array(
		'entity' => $award,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);
	
	//debug
	$describeit = "<h3>".elgg_echo('description')."</h3>";
	$describeit .= $award->description;
	//." filename: ".$badge->getFilenameOnFilestore;
	$describeit .= "<h3>".elgg_echo('badge:criteria')."</h3>";
	if ($award->criteria){
		$describeit .= $award->criteria;
	}else{
		$describeit .= elgg_echo('badge:nocriteria');
	}	
	$describeit .= "<h3>".elgg_echo('badge:awardedfor')."</h3>";
	$describeit .= elgg_view('output/url', array('href'=>$award->entityfor, 'text'=>$award->entityfor, 'is_trusted'=> true));
	
	$text = elgg_view('output/longtext', array('value' => $describeit));
	$body = "$text $extra";

	$text = elgg_view('output/longtext', array('value' => $describeit));
	$body = "$text $extra";

	echo elgg_view('object/elements/full', array(
		'entity' => $award,
		'icon' => $badge_icon,
		'summary' => $summary,
		'body' => $body,
	));

} elseif (elgg_in_context('gallery')) {
	echo '<div class="badge-gallery-item">';
	echo "<h3>" . $award->title . "</h3>";
	echo elgg_view_entity_icon($award, 'medium');
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
} else {
	// brief view

	$params = array(
		'entity' => $award,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($badge_icon, $list_body);
}
