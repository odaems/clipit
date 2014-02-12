<?php
/**
 * Provide a way of setting your full name.
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();
if ($user) {
	$title = elgg_echo('user:name:label');
	$content = '<label>'.elgg_echo('name') . ':</label>';
    $content .= '<div class="col-sm-9">';
	$content .= elgg_view('input/text', array(
        'class' => 'form-control',
		'name' => 'name',
		'value' => $user->name,
	));
    $content .= '</div>';

	echo elgg_view_module('info', $title, $content);

	// need the user's guid to make sure the correct user gets updated
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $user->guid));
}
