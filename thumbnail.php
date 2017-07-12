<?php
/**
 * Elgg badge thumbnail
 *
 * @package ElggFile
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get badge GUID
$badge_guid = (int) get_input('badge_guid', 0);

// Get badge thumbnail size
$size = get_input('size', 'small');

$badge = new BadgeFile($badge_guid);
if (!$badge || $badge->getSubtype() != "badge") {
	exit;
}

$simpletype = $badge->simpletype;
if ($simpletype == "image") {

	// Get badge thumbnail
	switch ($size) {
		case "small":
			$thumbbadge = $badge->thumbnail;
			break;
		case "medium":
			$thumbbadge = $badge->smallthumb;
			break;
		case "large":
		default:
			$thumbbadge = $badge->largethumb;
			break;
	}

	// Grab the badge
	if ($thumbbadge && !empty($thumbbadge)) {
		$readbadge = new BadgeFile();
		$readbadge->owner_guid = $badge->owner_guid;
		$readbadge->setFilename($thumbbadge);
		$mime = $badge->getMimeType();
		$contents = $readbadge->grabFile();

		// caching images for 10 days
		header("Content-type: $mime");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("Content-Length: " . strlen($contents));

		echo $contents;
		exit;
	}
}
