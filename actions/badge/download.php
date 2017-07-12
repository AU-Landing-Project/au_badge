<?php
/**
 * Elgg badge browser download action.
 *
 * @package ElggFile
 */

// @todo this is here for backwards compatibility (first version of embed plugin?)
$download_page_handler = elgg_get_plugins_path() . 'badge/download.php';

include $download_page_handler;
