<?php
/**
 * Elgg badge download.
 *
 * @package ElggFile
 */

// Get the guid
$badge_guid = get_input("guid");

// Get the badge
$badge = new BadgeFile($badge_guid);
if (!$badge->guid) {
	register_error(elgg_echo("badge:downloadfailed"));
	forward();
}


$mime = $badge->getMimeType();
if (!$mime) {
	$mime = "application/octet-stream";
}

$badgename = $badge->originalbadgename;

// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime");
if (strpos($mime, "image/") !== false || $mime == "application/pdf") {
	header("Content-Disposition: inline; badgename=\"$badgename\"");
} else {
	header("Content-Disposition: attachment; badgename=\"$badgename\"");
}

ob_clean();
flush();
readfile($badge->getFilenameOnFilestore());
exit;
