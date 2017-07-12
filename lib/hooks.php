<?php

/* badge hooks */

/**
 * Override the default entity icon for badges
 *
 * Plugins can override or extend the icons using the plugin hook: 'badge:icon:url', 'override'
 *
 * @return string Relative URL
 */
function badge_icon_url_override($hook, $type, $returnvalue, $params) {
	$badge = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($badge, 'object', 'badge')) {

		// thumbnails get first priority
		if ($badge->thumbnail) {
			$ts = (int)$badge->icontime;
			return "mod/badge/thumbnail.php?badge_guid=$badge->guid&size=$size&icontime=$ts";
		}

		$mapping = array(
			'application/excel' => 'excel',
			'application/msword' => 'word',
			'application/ogg' => 'music',
			'application/pdf' => 'pdf',
			'application/powerpoint' => 'ppt',
			'application/vnd.ms-excel' => 'excel',
			'application/vnd.ms-powerpoint' => 'ppt',
			'application/vnd.oasis.opendocument.text' => 'openoffice',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
			'application/x-gzip' => 'archive',
			'application/x-rar-compressed' => 'archive',
			'application/x-stuffit' => 'archive',
			'application/zip' => 'archive',

			'text/directory' => 'vcard',
			'text/v-card' => 'vcard',

			'application' => 'application',
			'audio' => 'music',
			'text' => 'text',
			'video' => 'video',
		);

		$mime = $badge->mimetype;
		if ($mime) {
			$base_type = substr($mime, 0, strpos($mime, '/'));
		} else {
			$mime = 'none';
			$base_type = 'none';
		}

		if (isset($mapping[$mime])) {
			$type = $mapping[$mime];
		} elseif (isset($mapping[$base_type])) {
			$type = $mapping[$base_type];
		} else {
			$type = 'general';
		}

		if ($size == 'large') {
			$ext = '_lrg';
		} else {
			$ext = '';
		}
		
		$url = "mod/badge/graphics/icons/{$type}{$ext}.gif";
		$url = elgg_trigger_plugin_hook('badge:icon:url', 'override', $params, $url);
		return $url;
	}
}

/**
 * Populates the ->getUrl() method for badge objects
 *
 * @param ElggEntity $entity File entity
 * @return string badge URL
 */
function badge_url_override($hook,$type,$url,$params) {
	$entity=$params['entity'];
	if ($entity->getSubtype() !== 'badge'){
		return;
	}
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "badge/viewbadge/" . $entity->getGUID() . "/" . $title;
}

/**
 * Populates the ->getUrl() method for award objects
 *
 * @param ElggEntity $entity File entity
 * @return string award URL
 */
function award_url_override($hook,$type,$url,$params) {
	$entity=$params['entity'];
	if ($entity->getSubtype() !== 'award'){
		return;
	}
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "badge/viewaward/" . $entity->getGUID() . "/" . $title;
}





/**
 * Setup user hover menu
 *
 * @param string $hook		Equals 'register'
 * @param string $type		Equals 'menu:user_hover'
 * @param array $return		Current menu
 * @param array $params		Additional params
 * @return array			Updated menu
 */
function award_badge_user_hover_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	
	if ($entity->guid==elgg_get_logged_in_user_guid() && elgg_get_plugin_setting('self_award','badge')!='yes') {
		//system settings do not allow self-award
		return $return;
	}
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	if (elgg_get_logged_in_user_guid()!=="") {
		
		$award = array(
			'name' => 'badge_award',
			'text' => elgg_echo('badge:award'),
			'href' => "/badge/award/$entity->guid",
			'link_class' => 'elgg-lightbox',
			//'section' => 'actions'
		);
		$return[] = ElggMenuItem::factory($award);
	}

	return $return;
}

/**
 * Setup user hover menu
 *
 * @param string $hook		Equals 'register'
 * @param string $type		Equals 'menu:user_hover'
 * @param array $return		Current menu
 * @param array $params		Additional params
 * @return array			Updated menu
 */
function award_badge_owner_block_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	
	if (elgg_is_admin_logged_in()) {
		//
	}
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	if (elgg_get_logged_in_user_guid()!=="" && elgg_instanceof($entity,'user')) {
		
		$award = array(
			'name' => 'badge_award',
			'text' => elgg_echo('badge:award'),
			'href' => "/badge/award/$entity->guid",
			'link_class' => 'elgg-lightbox',
			//'section' => 'actions'
		);
		$return[] = ElggMenuItem::factory($award);
	}

/*		$url = "/badge/award/$entity->guid";
		$item = new ElggMenuItem('badge_award', elgg_echo('badge:award'), $url);
		$return[] = $item;	}
*/		
	return $return;
}

/**
 * Add award link to entity menu at end of the menu
 * This should only appear when looking at badges 
 */
function badge_entity_menu_setup($hook, $type, $return, $params) {
	/*if (elgg_in_context('widgets')||!elgg_in_context('badge')) {
		return $return;
	}
	*/
	$entity = $params['entity'];
	

	// if this is a badge, then we should be in pop up award-form view and have received a recipient ID
	if ($entity->getSubtype()=='badge'){
		$recipient = $params['recipient'];
		//get rid of the rest of the menu if we are looking at badges to award
		// we should have set making_award when calling the view
		if ($params['making_award']==TRUE){
			foreach ($return as $key=>$value){
				unset($return[$key]);
			}
		}
	}else{
	// if not, we need to get the recipient from the entity owner
	// because we are awarding a badge for a post or other page
		$recipient=$entity->getOwnerGUID();

	}
		//system settings do not allow self-award, do nothing
		// only check when NOT selecting a badge - we want awarding possible when viewing awardable badges
	if ($entity->getOwnerGUID()==elgg_get_logged_in_user_guid() && 
			elgg_get_plugin_setting('badge_self_award','badge')=='no' && 
			$entity->getSubtype()!='badge' ) {
		return $return;
	}

	// award button
	$options = array(
		'name' => 'award',
		'text' => elgg_view('badge/awardbutton', array('entity' => $entity,'recipient'=>$recipient, 'awardfor' => $entity->getGUID())),
		'href' => false,
		'priority' => 1000,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Add 'claim award' link to entity menu at end of the menu
 * should only be visible when there is a possibility of making a claim
 */
function award_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')||!elgg_in_context('badge')) {
		return $return;
	}
	//want to remove the edit button and re-allocated the delete button if this is an award
	$entity = $params['entity'];
	if($entity->getSubtype()=='award'){
		foreach($return as $key=>$value){
			switch ($value->getName()){
				
				case "edit" :
					unset($return[$key]);
					break;
				case "delete" :
					// delete link
					unset($return[$key]);
					$options = array(
						'name' => 'delete',
						'text' => elgg_view_icon('delete'),
						'title' => elgg_echo('delete:this'),
						'href' => "action/badge/award/delete?guid={$entity->getGUID()}",
						'confirm' => elgg_echo('deleteconfirm'),
						'priority' => 300,
					);
					$return[] = ElggMenuItem::factory($options);		
					break;		
			}
		}
	
	
		// award button
		$options = array(
			'name' => 'awarded',
			'text' => elgg_view('badge/claimbutton', array('entity' => $entity)),
			'title' =>elgg_echo('badge:claim'),
			'href' => false,
			'priority' => 1000,
		);
		$return[] = ElggMenuItem::factory($options);
	}
	return $return;
}


/**
 * Override the default entity icon for award entity
 * 
 * @return string Relative URL
 */
function award_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	$size = $params['size'];
	$guid = $entity->guid;
	$icontime = $entity->icontime;

	if ($icontime && $entity->subtype==='award') {
		// return thumbnail
		return "badge/download/$guid";
	}
}
/**
 * Creates the notification message body
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 */
function badge_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'badge')) {
		$descr = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		return elgg_echo('badge:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}


/**
 * Creates the notification message body for an award
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 */
function award_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'award')) {
		$descr = $entity->description.$entity->criteria;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		return elgg_echo('badge:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}

/**
 * Add a menu item to the user ownerblock
 */
function badge_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "badge/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('badge', elgg_echo('badge'), $url);
		$return[] = $item;
	} 

	return $return;
}


/*
* if elggx_userpoints is enabled, this checks to see whether threshold has been met
* and, if so, awards a badge 
*/
function badge_userpoints_handler($hook, $type, $return, $params) {
    //get the current number of user points for this user
    $points = $params['entity']->userpoints_points;
    $recipient_guid = $params['entity']->guid;
    
    //get list of badges that have a userpoint threshold 
    $options = array('type'=>'object','subtype'=>'badge', 'metadata_name_value_pairs'=>array('name'=>'userpoints','value'=>0,'operand'=>'>'));
    
	$badges = elgg_get_entities_from_metadata($options);
	// scan through the badges     
    foreach ($badges as $key=>$value){
    
		//if the threshold has been met, check that it is not already awarded	    
	    if ($points>=$badges[$key]->userpoints){
	
			//check whether it is already awarded and OK to award. If not already awarded, award it
			$options=array('type'=>'object','subtype'=>'award','metadata_name_value_pairs'=>array(
										array('name'=>'badge_guid', 'value'=>$badges[$key]->getGUID()),
										array('name'=>'recipient_guid', 'value'=>$recipient_guid)));
			if(!elgg_get_entities_from_metadata($options)){
					if ($badges[$key]->owner_guid==$recipient_guid && elgg_get_plugin_setting('self_award')!='yes') {
						//system settings do not allow self-award so return without making changes
						return $return;
					}

				// all OK to award the badge

				system_message('you have received an award: '. $badges[$key]->title. 'go to the badges page to see awards you can accept');
				forward  (elgg_add_action_tokens_to_url("action/badge/award/?recipient=".elgg_get_logged_in_user_guid()."&badge_guid=".$badges[$key]->getGUID()."&awarder=".$badges[$key]->owner_guid));
			}							
		    
	    }
	    
    }
    
    
    
    // TODO: need to provide a page that shows the evidence and use that as the evidence URL
    // for now, we will just point to the profile of the recipient
    
    // note that the awarder is always the creator of the badge
    
    
    return $return;
    

}