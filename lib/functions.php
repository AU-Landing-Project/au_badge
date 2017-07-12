<?php
/**
 * File helper functions
 *
 * @package ElggFile
 */

/**
 * Prepare the upload/edit form variables
 *
 * @param BadgeFile $badge
 * @return array
 */
function badge_prepare_form_vars($badge = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'criteria'=>'',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'inheritable' => 'off',
		'award_access_id'=> ACCESS_PRIVATE,
		'lifespan'=>'0',
		'musthavebadge'=>'0',
		'userpoints'=>'0',
		'entity' => $badge,
	);

	if ($badge) {
		foreach (array_keys($values) as $field) {
			if (isset($badge->$field)) {
				$values[$field] = $badge->$field;
			}
		}
	}

	if (elgg_is_sticky_form('badge')) {
		$sticky_values = elgg_get_sticky_values('badge');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('badge');

	return $values;
}

/**
 * Dispatches badge pages.
 * URLs take the form of
 *  All badges:       badge/all
 *  User's badges:    badge/owner/<username>
 *  Friends' badges:  badge/friends/<username>
 *  View badge:       badge/view/<guid>/<title>
 *  New badge:        badge/add/<guid>
 *  Edit badge:       badge/edit/<guid>
 *  Download:        badge/download/<guid>
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function badge_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$badge_dir = elgg_get_plugins_path() . 'badge/pages/badge';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			badge_register_toggle();
			set_input('owner_guid',$page[1]);
			include "$badge_dir/owner.php";
			break;
		case 'friends':
			badge_register_toggle();
			include "$badge_dir/friends.php";
			break;
		case 'viewbadge':
			set_input('guid', $page[1]);
			include "$badge_dir/view.php";
			break;
		case 'viewaward':
			set_input('guid', $page[1]);
			include "$badge_dir/viewaward.php";
			break;
			
		case 'add':
			include "$badge_dir/upload.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$badge_dir/edit.php";
			break;
		case 'award':
			set_input('recipient', $page[1]);
			include "$badge_dir/award.php";
			break;
		case 'awarded':
			badge_register_toggle();
			set_input('guid', $page[1]);
			include "$badge_dir/awarded.php";	
			break;
		case 'awardedby':
			badge_register_toggle();
			set_input('guid', $page[1]);
			include "$badge_dir/awardedby.php";	
			break;
		case 'claimed':
			set_input('guid', $page[1]);
			include "$badge_dir/claimed.php";	
			break;
		case 'search':
			badge_register_toggle();
			include "$badge_dir/search.php";
			break;
		case 'all':
			badge_register_toggle();
			include "$badge_dir/world.php";
			break;
		case 'download':
			set_input('guid', $page[1]);
			include "$badge_dir/download.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Adds a toggle to extra menu for switching between list and gallery views
 */
function badge_register_toggle() {
	$url = elgg_http_remove_url_query_element(current_page_url(), 'list_type');

	if (get_input('list_type', 'list') == 'list') {
		$list_type = "gallery";
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = "list";
		$icon = elgg_view_icon('list');
	}

	if (substr_count($url, '?')) {
		$url .= "&list_type=" . $list_type;
	} else {
		$url .= "?list_type=" . $list_type;
	}


	elgg_register_menu_item('extras', array(
		'name' => 'badge_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("badge:list:$list_type"),
		'priority' => 1000,
	));
}


/**
 * Returns an overall badge type from the mimetype
 *
 * @param string $mimetype The MIME type
 * @return string The overall type
 */
function badge_get_simple_type($mimetype) {

	switch ($mimetype) {
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			return "document";
			break;
		case "application/pdf":
			return "document";
			break;
		case "application/ogg":
			return "audio";
			break;
	}

	if (substr_count($mimetype, 'text/')) {
		return "document";
	}

	if (substr_count($mimetype, 'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype, 'image/')) {
		return "image";
	}

	if (substr_count($mimetype, 'video/')) {
		return "video";
	}

	if (substr_count($mimetype, 'opendocument')) {
		return "document";
	}

	return "general";
}

// deprecated and will be removed
function get_general_badge_type($mimetype) {
	elgg_deprecated_notice('Use badge_get_simple_type() instead of get_general_badge_type()', 1.8);
	return badge_get_simple_type($mimetype);
}

/**
 * Returns a list of badgetypes
 *
 * @param int       $container_guid The GUID of the container of the badges
 * @param bool      $friends        Whether we're looking at the container or the container's friends
 * @return string The typecloud
 */
function badge_get_type_cloud($container_guid = "", $friends = false) {

	$container_guids = $container_guid;

	if ($friends) {
		// tags interface does not support pulling tags on friends' content so
		// we need to grab all friends
		$friend_entities = get_user_friends($container_guid, "", 999999, 0);
		if ($friend_entities) {
			$friend_guids = array();
			foreach ($friend_entities as $friend) {
				$friend_guids[] = $friend->getGUID();
			}
		}
		$container_guids = $friend_guids;
	}

	elgg_register_tag_metadata_name('simpletype');
	$options = array(
		'type' => 'object',
		'subtype' => 'badge',
		'container_guids' => $container_guids,
		'threshold' => 0,
		'limit' => 10,
		'tag_names' => array('simpletype')
	);
	$types = elgg_get_tags($options);

	$params = array(
		'friends' => $friends,
		'types' => $types,
	);

	return elgg_view('badge/typecloud', $params);
}

function get_badgetype_cloud($owner_guid = "", $friends = false) {
	elgg_deprecated_notice('Use badge_get_type_cloud instead of get_badgetype_cloud', 1.8);
	return badge_get_type_cloud($owner_guid, $friends);
}




/**
 * Check whether can award a given badge
 * called from award page that displays all the badges to award
 * @param badge entity, an array of access permissions for the awarder, awarder GUID, and recipient GUID
 */
function can_award_badge($badge,$awarder_id,$award_access_array,$recipicient_guid) {

	//check whether system settings allow self award and return false if not and
	// that is what they are trying to do
	if($awarder_id==$recipicient_guid && elgg_get_plugin_setting('self_award')!='yes'){
		return FALSE;
	}
	

	// if the owner, can edit
	if($badge->canEdit()){
		return TRUE;
	}
	
	
	
	// check whether badge is inheritable - means must have badge to award badge
	if ($badge->inheritable=='on'){
		if (!elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'award',
		'metadata_name_value_pair'=> array('recipient_guid'=>$awarder_id)))){
			//this is not allowed	
			return FALSE;
		}
	}
	//now look for prerequisite badge
	if ($badge->musthavebadge>0){
		if (!elgg_get_entities_from_metadata(array('type'=>'object','subtype'=>'award',
				'metadata_name_value_pair'=> array('recipient_guid'=>$awarder_id,'badge_guid'=>$badge->musthavebadge)))){
			//this is not allowed	
			return FALSE;
		}						
	}
	
	//now check whether user has permission
	if( !in_array($badge->award_access_id, $award_access_array)){
		//this is not allowed	
		return FALSE;
		
	}
	
	//check whether award has already been given - OK if plugin settings allow
	
	//check whether awarding self - OK if plugin settings allow
	
	// passed all checks, returning true
	return TRUE;

}


/* 
 used to return tag cloud of badges
*/


function badge_type_cloud_get_url($type, $friends) {
	$url = elgg_get_site_url() . 'badge/search?subtype=badge';

	if ($type->tag != "all") {
		$url .= "&md_type=simpletype&tag=" . urlencode($type->tag);
	}

	if ($friends) {
		$url .= "&friends=$friends";
	} 

	if ($type->tag == "image") {
		$url .= "&list_type=gallery";
	}

	if (elgg_get_page_owner_guid()) {
		$url .= "&page_owner=" . elgg_get_page_owner_guid();
	}

	return $url;
}


/*
 * using filename and mimetype, convert image to PNG of correct size for open badge format 
 */

function badge_make_open_badge_image($filename,$mimetype){
	
	switch($mime_type) {
	    case 'image/jpg':
	    case 'image/jpeg':
	       $image = imagecreatefromjpeg($filename);
	    break;
	    case 'image/gif':
	       $image = imagecreatefromgif($filename);
	       break;
	    case 'image/png':
	       $image = imagecreatefrompng($filename);
	       break;
	 }

	// make it the right size for openbadge (will distort non square badges)
	list($width, $height) = getimagesize($filename);
	$badgeimage = imagecreatetruecolor(90, 90);
	imagecopyresized($badgeimage, $image, 0, 0, 0, 0, 90, 90, $width, $height);
	
	 //now convert it
	 
	if (!$pngbadge= imagepng($badgeimage,$filename)){
		if (!$image){register_error('failed to create image');}
		register_error('failed to convert to PNG format');
	}
	return true;
}


/**
 * Notify $user that $liker liked his $entity.
 *
 * @param type $user
 * @param type $liker
 * @param type $entity 
 */
function award_notify_user(ElggUser $recipient, ElggUser $awarder, $url) {
	
	if (!$recipent instanceof ElggUser) {
		return false;
	}
	
	if (!$awarder instanceof ElggUser) {
		return false;
	}
	
	if (!$url) {
		return false;
	}
	
	$title_str = $url;

	$site = get_config('site');

	$subject = elgg_echo('badge:newaward', array(
					$awarder->name,
					$title_str
				));

	$body = elgg_echo('badge:award:notification', array(
					$recipient->name,
					$awarder->name,
					$title_str,
					$site->name,
					$url,
					$awarder->getURL()
				));
				
	$params = array(
		'object' => 'ElggEntity',
		'action' => 'award',
		'summary' => $title_str,
	);
	notify_user($recipient->guid,
				$awarder->guid,
				$subject,
				$body,
				$params
			);
}