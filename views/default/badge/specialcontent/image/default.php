<?php
/**
 * Display an image
 *
 * @uses $vars['entity']
 */

$ebadge = $vars['entity'];
$badge=new BadgeFile($ebadge->guid);

$image_url = $badge->getIconURL('large');
$image_url = elgg_format_url($image_url);
$download_url = elgg_get_site_url() . "badge/download/{$badge->getGUID()}";

if ($vars['full_view']) {
	echo <<<HTML
		<div class="badge-photo">
			<a href="$download_url"><img class="elgg-photo" src="$image_url" /></a>
		</div>
HTML;
}
