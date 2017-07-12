<?php
/**
 * Elgg badge download.
 * 
 * @package ElggFile
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the guid
$badge_guid = get_input("badge_guid");

forward("badge/download/$badge_guid");
