<?php
/**
 * award river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);
$recipient =get_entity($object->recipient_guid);
$excerpt .= "<br />".elgg_echo('badge:awardedto')." ".elgg_view_entity($recipient, array('full_view'=>FALSE));
$excerpt .= elgg_echo('badge:criteria')." : ". strip_tags(elgg_get_excerpt ($object->criteria));
$item=$vars['item'];

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => $excerpt,
));