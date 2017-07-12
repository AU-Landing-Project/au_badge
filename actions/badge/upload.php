<?php
/**
 * Elgg badge uploader/edit action
 *
 * @package ElggFile
 */

// Get variables
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$desc = get_input("description");
$access_id = (int) get_input("access_id");
$award_access_id = (int) get_input("award_access_id"); //who can award the badge
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('badge_guid');
$tags = get_input("tags");
$criteria=get_input('criteria'); // - free text, needed for open badge standard
$lifespan = get_input('lifespan'); // in days -  will use this to calculate expiry date on an award
$musthavebadge = get_input('musthavebadge'); // whether badge with given ID is needed before others can award this one
											// note two special inputs - 0 (no badge) and 'this' (this badge) otherwise
											//it's a guid
$userpoints = get_input('userpoints'); //number of elggx userpoints needed for autoaward. If 0 or not set, not autoawarded.											

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

elgg_make_sticky_form('badge');

//check whether admin only and not admin
if (elgg_get_plugin_setting('admin_only_badges')=='no' && !elgg_is_admin_logged_in()){
	register_error(elgg_echo('badge:nonewbadge'));
	forward(REFERER);

}

// check if upload failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	register_error(elgg_echo('badge:cannotload'));
	forward(REFERER);
}

// check whether this is a new badge or an edit
$new_badge = true;
if ($guid > 0) {
	$new_badge = false;
}

if ($new_badge) {
	// must have a badge if a new badge upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('badge:nobadge');
		register_error($error);
		forward(REFERER);
	}

	$badge = new BadgeFile();
	$badge->subtype = "badge";

	// if no title on new upload, grab badgename
	if (empty($title)) {
		$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
	}

} else {
	// load original badge object
	$badge = new BadgeFile($guid);
	if (!$badge) {
		register_error(elgg_echo('badge:cannotload'));
		forward(REFERER);
	}

	// user must be able to edit badge
	if (!$badge->canEdit()) {
		register_error(elgg_echo('badge:noaccess'));
		forward(REFERER);
	}

	if (!$title) {
		// user blanked title, but we need one
		$title = $badge->title;
	}
}

$badge->title = $title;
$badge->description = $desc;
$badge->criteria = $criteria;
$badge->access_id = $access_id;
$badge->container_guid = $container_guid;
$badge->tags = string_to_tag_array($tags);
$badge->award_access_id = $award_access_id;
$badge->lifespan = $lifespan;
$badge->musthavebadge = $musthavebadge;
$badge->userpoints = $userpoints;

// we have a badge upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "badge/";

	// if previous badge, delete it
	if ($new_badge == false) {
		$badgename = $badge->getFilenameOnFilestore();
		if (file_exists($badgename)) {
			unlink($badgename);
		}

		// use same badgename on the disk - ensures thumbnails are overwritten
		$badgestorename = $badge->getFilename();
		$badgestorename = elgg_substr($badgestorename, elgg_strlen($prefix));
	} else {
		$badgestorename = elgg_strtolower(time().$_FILES['upload']['name']);
	}
	$badgestorename = $badgestorename.".png";

	$badge->setFilename($prefix . $badgestorename);
	$mime_type = ElggFile::detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);
	$simpletype=badge_get_simple_type($mime_type);
	//we only allow images so return user to form if not an image
	if ($simpletype !== "image"){		
		register_error('badge:notimage');
		forward(REFERER);
	}
	




	// Open the badge to guarantee the directory exists
	$badge->open("write");
	$badge->close();
	move_uploaded_file($_FILES['upload']['tmp_name'], $badge->getFilenameOnFilestore());

	//it is a valid image but if it is not PNG, we need to make it so
	$filename= $badge->getFilenameOnFilestore();

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
	
	$badge->setMimeType('image/png');
	$badge->simpletype = 'image';
	$guid = $badge->save();
	
	//now need to check whether musthavebadge was
	// selected to be this one. If so, set it as the 'must have' option
	
	if ($musthavebadge=='this'){
		$badge->musthavebadge = $badge->getGUID();

	}

	// if image, we need to create thumbnails (this should be moved into a function)
	if ($guid && $badge->simpletype == "image") {
		$badge->icontime = time();
		
		$thumbnail = get_resized_image_from_existing_file($badge->getFilenameOnFilestore(), 60, 60, true);
		if ($thumbnail) {
			$thumb = new BadgeFile();
			$thumb->setMimeType('image/png');

			$thumb->setFilename($prefix."thumb".$badgestorename);
			$thumb->open("write");
			$thumb->write($thumbnail);
			$thumb->close();

			$badge->thumbnail = $prefix."thumb".$badgestorename;
			unset($thumbnail);
		}

// medium
		$thumbsmall = get_resized_image_from_existing_file($badge->getFilenameOnFilestore(), 90, 90, true);
		if ($thumbsmall) {
			$thumb->setFilename($prefix."smallthumb".$badgestorename);
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			$badge->smallthumb = $prefix."smallthumb".$badgestorename;
			unset($thumbsmall);
		}

// this is the correct openbadge size - naming it as open badge to distinguish from Elgg standard sizes
// not quite sure how to make Elgg make use of this yet
// one problem is the elgg function insists onreturning a jpeg
		$thumbopenbadge = get_resized_image_from_existing_file($badge->getFilenameOnFilestore(), 90, 90, true);

		if ($thumbopenbadge) {
			$thumb->setFilename($prefix."openbadge".$badgestorename);
			$thumb->open("write");
			$thumb->write($thumbopenbadge);
			$thumb->close();
			$badge->openbadge = $prefix."openbadge".$badgestorename;
			unset($thumbopenbadge);
		}

		$thumblarge = get_resized_image_from_existing_file($badge->getFilenameOnFilestore(), 600, 600, false);
		if ($thumblarge) {
			$thumb->setFilename($prefix."largethumb".$badgestorename);
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
			$badge->largethumb = $prefix."largethumb".$badgestorename;
			unset($thumblarge);
		}
	} elseif ($badge->icontime) {
		// if it is not an image, we do not need thumbnails
		unset($badge->icontime);
		
		$thumb = new ElggFile();
		
		$thumb->setFilename($prefix . "thumb" . $badgestorename);
		$thumb->delete();
		unset($badge->thumbnail);
		
		$thumb->setFilename($prefix . "smallthumb" . $badgestorename);
		$thumb->delete();
		unset($badge->smallthumb);
		
		$thumb->setFilename($prefix . "largethumb" . $badgestorename);
		$thumb->delete();
		unset($badge->largethumb);
	}
} else {
	// not saving a badge but still need to save the entity to push attributes to database
	if ($musthavebadge=='this'){
		$badge->musthavebadge = $badge->getGUID();

	}
	$badge->save();
}



// badge saved so clear sticky form
elgg_clear_sticky_form('badge');


// handle results differently for new badges and badge updates
if ($new_badge) {
	if ($guid) {
		$message = elgg_echo("badge:saved");
		system_message($message);
		add_to_river('river/object/badge/create', 'create', elgg_get_logged_in_user_guid(), $badge->guid);
	} else {
		// failed to save badge object - nothing we can do about this
		$error = elgg_echo("badge:uploadfailed");
		register_error($error);
	}


	forward("badge/owner/$container_guid");
	

} else {
	if ($guid) {
		system_message(elgg_echo("badge:saved"));
	} else {
		register_error(elgg_echo("badge:uploadfailed"));
	}

	forward($badge->getURL());
}
