<?php
/**
 * Elgg footer
 * The standard HTML footer that displays across the site
 *
 * @package Elgg
 * @subpackage Core
 *
 */

echo'
<div class="col-sm-6">
    <div class="contact">
        <h2>Hola!</h2>
        <img src="'.$CONFIG->wwwroot.'mod/clipit_theme/graphics/mail.png">
    </div>
</div>
';

echo elgg_view_menu('footer_clipit', array('sort_by' => 'priority', 'class' => 'site-map col-sm-6'));

