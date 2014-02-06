<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 6/02/14
 * Time: 12:37
 * To change this template use File | Settings | File Templates.
 */
$content = '
<!-- foreach-->
<div class="wrapper">
    Log de últimos eventos
</div>
<!-- endforeach-->';

echo elgg_view('landing/module', array(
    'name'      => 'events',
    'title'     => "Events",
    'content'   => $content,
));
