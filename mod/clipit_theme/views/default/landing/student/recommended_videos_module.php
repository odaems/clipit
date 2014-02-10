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
<div class="wrapper separator">
    <img src="http://img.youtube.com/vi/bQVoAWSP7k4/2.jpg" class="pull-left">
    <div class="text">
        <h5 class="text-truncate">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h5>
        <small>12:00H, NOV 18, 2013</small>
    </div>
</div>
<!-- endforeach-->';

$all_link = elgg_view('output/url', array(
    'href' => "linkHref",
    'text' => elgg_echo('link:view:all'),
    'is_trusted' => true,
));
echo elgg_view('landing/module', array(
    'name'      => "recommended_videos",
    'title'     => "Recommended Videos",
    'content'   => $content,
    'all_link'  => $all_link,
));
?>

