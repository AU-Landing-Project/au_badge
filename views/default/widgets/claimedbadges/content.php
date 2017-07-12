<?php
/**
 * Elgg shows claimed badges only
 *
 * @package ElggFile
 */


$num = $vars['entity']->num_display;
$pageowner=elgg_get_page_owner_entity();
$options = array(
	'type' => 'object',
	'subtype' => 'award',
	'full_view' => FALSE,
	'metadata_name_value_pairs' => array('claimed'=>TRUE,'recipient_guid'=>$pageowner->guid),
);
$content = elgg_list_entities_from_metadata($options);

echo $content;

if ($content) {
	$url = "badge/claimed/" . $pageowner->guid;
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('badge:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('badge:none');
}
