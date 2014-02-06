<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 6/02/14
 * Time: 12:30
 * To change this template use File | Settings | File Templates.
 */
$content = '
<!-- foreach-->
<div class="wrapper separator">
    Gráfica sobre el estado de las acvitidades
</div>
<!-- endforeach-->';

$all_link = elgg_view('output/url', array(
    'href' => "linkHref",
    'text' => elgg_echo('link:view:all'),
    'is_trusted' => true,
));
echo elgg_view('landing/module', array(
    'name'      => "activity_status",
    'title'     => "Activity status",
    'content'   => $content,
    'all_link'  => $all_link,
));
