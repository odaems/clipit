<?php
/**
 * Walled garden login
 */

$title = elgg_get_site_entity()->name;
$welcome = elgg_echo('welcome');
//$welcome .= ': <br/>' . $title;

$menu = elgg_view_menu('walled_garden', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-general elgg-menu-hz',
));

$login_box = elgg_view('core/account/login_box', array('module' => 'walledgarden-login'));

echo <<<HTML
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		<h3 class="elgg-heading-walledgarden">
			$welcome
		</h3>
        <div class="elgg-login-logo">
            <img src="./mod/clipit/graphics/clipit.png" />
        </div>
		$menu
	</div>
</div>
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		$login_box
	</div>
</div>
HTML;
