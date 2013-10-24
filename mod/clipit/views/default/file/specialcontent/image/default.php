<?php
/**
 * Display an image
 *
 * @uses $vars['entity']
 */

$file = $vars['entity'];
header("content-type:image/jpg");
$image_url = $file->getIconURL('large');
$image_url = elgg_format_url($image_url);
$download_url = elgg_get_site_url() . "file/download/{$file->getGUID()}";

if ($vars['full_view']) {
	echo <<<HTML
		<div class="file-photo">
			<a target="_blank" href="$download_url"><img class="elgg-photo" src="$image_url" /></a>
		</div>
HTML;
}