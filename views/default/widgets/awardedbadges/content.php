<?php
/**
 * Elgg badge widget view
 * shows unclaimed badges only
 * @package ElggFile
 */


$num = $vars['entity']->num_display;

$pageowner=elgg_get_page_owner_entity();

$options = array(
	'type' => 'object',
	'subtype' => 'award',
	'metadata_name_value_pairs'=>array('recipient_guid'=>$pageowner->guid,'claimed'=>FALSE),
	'full_view'=> FALSE,
);
$content = elgg_list_entities_from_metadata($options);

echo $content;

if ($content) {
	$url = "badge/awarded/" . $pageowner->guid;
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('badge:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('badge:none');
}
