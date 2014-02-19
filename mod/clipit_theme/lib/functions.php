<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 4/02/14
 * Time: 10:44
 * To change this template use File | Settings | File Templates.
 */

function clipit_student_events($rel_array){
    $content = "";
    foreach($rel_array as $relationship){
        $subtype = $relationship->object_subtype;
        $object_rel = get_relationship($relationship->object_id);
        $general_subtype = explode("-", $subtype);
        $general_subtype = (string)$general_subtype[0];
        // Group, Activity
        switch($general_subtype){
            case 'group':
                $content .= group_events($subtype, $object_rel, $relationship);
                break;
            case 'activity':
                $content .= activity_events($subtype, $object_rel, $relationship);
                break;
        }

    }
    return $content;
}

/*
 * Group events types
 */
function group_events($subtype, $object_rel, $relationship){
    $content = "";
    // Activity object
    $activity_id = ClipitGroup::get_activity($object_rel->guid_one);
    $activity = array_pop(ClipitActivity::get_by_id(array($activity_id)));
    // Group object
    $group = array_pop(ClipitGroup::get_by_id(array($object_rel->guid_one)));
    // User object
    $user = array_pop(ClipitUser::get_by_id(array($relationship->performed_by_guid)));
    $user = new ElggUser($user->id);
    switch($subtype){
        case "group-user":
            $items = array(
                'item' => array_pop(ClipitGroup::get_by_id(array($object_rel->guid_one))),
                'sub-item' => array_pop(ClipitUser::get_by_id(array($relationship->performed_by_guid))),
            );

            $params =  array(
                'title' => 'Nuevo miembro',
                'href'  => 'profile/'.$items['sub-item']->login,
                'icon'  => 'user',
                'color' => $activity->color,
                'time'  => $relationship->time_created,
                'details' => array(
                    'img'   => elgg_view('output/img', array(
                        'src' => $user->getIconURL('small'),
                        'alt' => $user->name,
                        'title' => elgg_echo('profile'),
                        'class' => 'img-thumb',
                    )),
                    'title' => $user->name,
                    'sub-title' => 'En '.$group->name,
                ),
            );
            $content .= elgg_view("page/components/timeline_event", $params);
            break;
        case "group-file":
            $items = array(
                'item' => array_pop(ClipitFile::get_by_id(array($object_rel->guid_two))),
                'sub-item' => array_pop(ClipitUser::get_by_id(array($relationship->performed_by_guid))),
            );
            $file = array_pop(ClipitFile::get_by_id(array($object_rel->guid_two)));
            $params =  array(
                'title' => 'Archivo subido',
                'href'  => 'clipit_activity/'.$activity->id.'/file/'.$file->id,
                'icon'  => 'file-text',
                'color' => $activity->color,
                'time'  => $relationship->time_created,
                'details' => array(
                    'icon'   => 'file-text',
                    'title' => $file->name,
                    'sub-title' => 'By '.$user->name,
                    'description' => $file->description,
                ),
            );
            $content .= elgg_view("page/components/timeline_event", $params);
            break;
    }
    return $content;
}
