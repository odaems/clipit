
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 6/02/14
 * Time: 12:37
 * To change this template use File | Settings | File Templates.
 */
$content = '
<div class="margin-bar"></div>
<ul class="events">
    <!-- foreach-->
    <li class="event">
        <div class="circle-activity" style="background: #00a99d;"></div>
        <div class="event-details">
            <a><i class="fa fa-floppy-o" style="color: #00a99d;"></i> STA available</a>
            <div style="margin-top: 10px;background: #f1f2f7;overflow: hidden; border-radius: 3px; padding: 8px;color: #797979; font-size: 14px; ">
                <i class="fa fa-file-text" style="font-size: 20px;float:left;margin-right: 15px;color: #797979;"></i>
                <div style="overflow:hidden;">
                    <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;color: #119BCD;">Lorem ipsum dolor sit amet consectetur</div>
                    <div style="font-size: 11px;">
                        By
                        <a style="color: #119BCD;">Juan Perez Montero</a>
                    </div>
                    <div style="font-size: 12px;margin-top: 5px;">Duis et ante turpis. Praesent risusligula, porta vitae hendrerit quis</div>
                </div>
            </div>
            <small class="text-right event-date">12:00h, NOV 18, 2013</small>
        </div>
    </li>
    <!-- endforeach-->
    <!-- foreach-->
    <li class="event">
        <div class="circle-activity" style="background: #f7931e;"></div>
        <div class="event-details">
            <a><i class="fa fa-comment" style="color: #f7931e;"></i> Your video has been commented</a>
            <div class="recommended" style="overflow:hidden;border-radius: 3px; padding: 8px; margin-top: 10px;background: #f1f2f7;">
                <div style="overflow:hidden;">
                    <img class="thumb-video" style="height: 55px;width: 70px;float: left;margin-right: 10px;" src="http://img.youtube.com/vi/bQVoAWSP7k4/2.jpg">
                    <div class="info_" style="overflow: hidden;  text-overflow: ellipsis;  white-space: nowrap;">
                        <span class="title" style="color: #119BCD;font-size: 14px;">Lorem ipsum dolor sit amet consectetur</span>
                        <span style="color: #119BCD;font-size: 11px;display:block;">Biology</span>
                        <div style="font-size: 14px;color: #e7d333;">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 5px;background: #FFF;padding: 5px;">
                    <div style="font-size: 14px;float:right;color: #e7d333;">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <span style="color: #119BCD;font-size: 11px;display:block;">Pepe Martinez Gonzalez</span>
                    <div style="font-size: 12px;margin-top: 5px;">Duis et ante turpis. Praesent risusligula, porta vitae hendrerit quis</div>
                </div>
            </div>
            <small class="text-right event-date">12:00h, NOV 18, 2013</small>
        </div>
    </li>
    <!-- endforeach-->
</ul>
';
//////
$db_prefix = elgg_get_config('dbprefix');
$defaults = array(
    'offset'     => (int) max(get_input('offset', 0), 0),
    'limit'      => (int) max(get_input('limit', 20), 0),
    'pagination' => TRUE,
    'list_class' => 'elgg-list-river elgg-river', // @todo remove elgg-river in Elgg 1.9
);

$options = array(
    'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
    'wheres' => array("e.container_guid = 137")
);
$options = array_merge($defaults, $options);

$options['count'] = TRUE;
$count = elgg_get_river($options);

$options['count'] = FALSE;
$items = elgg_get_river($options);

$options['count'] = $count;
$options['items'] = $items;

//$content = elgg_view('landing/student/events_list', $options);
//$content = elgg_view('page/components/list', $options);
//////
$events = new ClipitEvent();
//print_r($events->get_by_user(array($guid_user)));




echo elgg_view('landing/module', array(
    'name'      => 'events',
    'title'     => "Events",
    'content'   => $content,
));
