<?php
//award button - extends entity menu
// extends view when listing badges
//used for both awarding badges (if recipient specified) and claiming them (no recipient specified)

if (!isset($vars['entity'])) {
	return true;
}

//we are currently looking a badges so need to actually make an award when the button is clicked
if (elgg_in_context('badge')){

	$badge_guid = $vars['entity']->getGUID();
	// this view should have received a recipient guid if used in award window, otherwise this is just a normal badge view
	$recipient = get_input('recipient');
	$entityfor=get_input('entityfor');
	$user_guid=elgg_get_logged_in_user_guid();
	
	// only logged in users can award badges
	if (elgg_is_logged_in()) {
			
			//this is only shown if awarding a button
			if ($recipient){
				$url = elgg_get_site_url() . "action/badge/award?badge_guid={$badge_guid}&recipient={$recipient}";
				if ($entityfor){
					$url .= "&entityfor={$entityfor}";
				}
				$params = array(
					'href' => $url,
					'text' => elgg_echo('badge:awardthis').elgg_view_icon('round-plus'),
					'title' => elgg_echo('badge:awardthis'),
					'is_action' => true,
					'is_trusted' => true,
				);
				$award_button = elgg_view('output/url', $params);
			}
			
	}
	
	echo $award_button;

}else{
	
// this is being awarded via a post so we need to display the list of potential badges to be awarded
	// get the entity for which the award is being made
	$entity_guid = $vars['entity']->getGUID();
	// this view should have received a recipient guid if used in award window, otherwise this is just a normal badge view
	$recipient = $vars['recipient'];
	$user_guid=elgg_get_logged_in_user_guid();
	//$entityfor=$vars['url'];
	$award_button='test'.$entity_guid.$recipient.$user_guid.$entityfor;	
	// only logged in users can award badges
	if (elgg_is_logged_in()) {
			
			//this is only shown if awarding a button
			if ($recipient){
				$url = elgg_get_site_url() . "badge/award/{$recipient}&entityfor={$entity_guid}";
				elgg_load_js('lightbox');
				elgg_load_css('lightbox');
				$params = array(
					'href' => $url,
					'text' => elgg_echo('badge:award').elgg_view_icon('round-plus'),
					'title' => elgg_echo('badge:award'),
					'class' => 'elgg-lightbox',				);
				$award_button = elgg_view('output/url', $params);
			}
			
	}
	
	echo $award_button;
}