<?php
/**
 * Elgg badge plugin - uses Files plugin as basis, with stuff added and removed to make it work for badges
 *
 * @package ElggFile
 */

elgg_register_event_handler('init', 'system', 'badge_init');

/**
 * File plugin initialization functions.
 */
function badge_init() {

	// register a library of helper functions
	elgg_register_library('badgefunctions', elgg_get_plugins_path() . 'badge/lib/functions.php');
	elgg_register_library('badgehooks', elgg_get_plugins_path() . 'badge/lib/hooks.php');
	
	//use the libraries
	elgg_load_library('badgefunctions');
	elgg_load_library('badgehooks');
	
	// Site navigation
	$item = new ElggMenuItem('badge', elgg_echo('badge'), 'badge/all');
	elgg_register_menu_item('site', $item);

	$item = new ElggMenuItem('award', elgg_echo('award'), 'badge/awarded/all');
	elgg_register_menu_item('site', $item);

	// Extend CSS
	elgg_extend_view('css/elgg', 'badge/css');

	// add enclosure to rss item
	elgg_extend_view('extensions/item', 'badge/enclosure');
	
	elgg_extend_view('object/elements/summary','badge/awarderrecipient');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('badge', 'badge_page_handler');

	// Add a new badge widget
	elgg_register_widget_type('badgerepo', elgg_echo("badge"), elgg_echo("badge:widget:description"));

	// Add a new badge awarded widget
	elgg_register_widget_type('awardedbadges', elgg_echo("badge:awarded"), elgg_echo("badge:awarded:widget:description"));

	// Add a new badge claimed widget
	elgg_register_widget_type('claimedbadges', elgg_echo("badge:claimed"), elgg_echo("badge:claimed:widget:description"));


	// Register URL handlers for badges
	elgg_register_plugin_hook_handler('entity:url', 'object', 'badge_url_override');
	elgg_register_plugin_hook_handler('entity:url', 'object', 'award_url_override');
	
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'badge_icon_url_override');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'badge_entity_menu_setup', 600);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'award_entity_menu_setup', 600);


	// Register granular notification for this object type
	
	
	
	if(function_exists('elgg_get_version')){
	// Listen to notification events and supply a more useful message
	// 1.9
		elgg_register_notification_event ('object', 'badge', array('create'));
		elgg_register_notification_event ('object', 'award', array('create'));
	
	} else {
	// 1.8
		register_notification_object('object', 'badge', elgg_echo('badge:newupload'));
		register_notification_object('object', 'award', elgg_echo('badge:newaward'));
			
	}
	
	
	elgg_register_plugin_hook_handler('prepare', 'notify:entity:message', 'badge_notify_message');
	elgg_register_plugin_hook_handler('prepare','notify:entity:message', 'award_notify_message');

	//override icon for award (should return openbadge view)
//	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'award_icon_url_override');


	// Register entity type for search
	elgg_register_entity_type('object', 'badge');
	
	// Register entity type for search
	elgg_register_entity_type('object', 'award');
	
	// add a badge link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'badge_owner_block_menu');	
		
	if(elgg_is_logged_in()){
		// add an award link to owner blocks
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'award_badge_owner_block_menu');
		//add award badge option to user hover menu 
		elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'award_badge_user_hover_menu');
	}
	
	//if ElggxUserpoints is enabled, intercept userpoint creation and send to autobadge for evaluation
	if (elgg_is_active_plugin('elggx_userpoints')){
		    elgg_register_plugin_hook_handler('userpoints:update', 'all', 'badge_userpoints_handler');

	}
	

	// Register actions
	$action_path = elgg_get_plugins_path() . 'badge/actions/badge';
	elgg_register_action("badge/upload", "$action_path/upload.php");
	elgg_register_action("badge/delete", "$action_path/delete.php");
	// temporary - see #2010
	elgg_register_action("badge/download", "$action_path/download.php");
	//allow awarding
	elgg_register_action("badge/award", "$action_path/award.php");
	//allow claiming
	elgg_register_action("badge/claim", "$action_path/claim.php");
	elgg_register_action("badge/award/delete","$action_path/deleteaward.php");
}

