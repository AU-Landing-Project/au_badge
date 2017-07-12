<?php
/**
 * View an award
 *
 * @package ElggFile
 */

$award = new AwardFile(get_input('guid'));
if (!$award->guid) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('award'), 'badge/awardedby');

$crumbs_title = $owner->name;

elgg_push_breadcrumb($crumbs_title, "badge/owner/$owner->username");

$title = $award->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($award, array('full_view' => true));
$content .= elgg_view_comments($award);


$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
