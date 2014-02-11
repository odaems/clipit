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
    <div class="bar" style="width:10%;background: #00a99d;">
        <h3>Empirical Formula</h3>
    </div>
    <div class="bar" style="width:30%;background: #f7931e;">
        <h3>Empirical Formula</h3>
    </div>
    <div class="bar" style="width:80%;background: #ed1e79;">
        <h3>Empirical Formula</h3>
    </div>
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
