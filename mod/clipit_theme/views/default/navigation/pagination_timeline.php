<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 20/02/14
 * Time: 12:03
 * To change this template use File | Settings | File Templates.
 */
$guid = (int) get_input('guid');
$last_id = (int)get_input('last_id');
$offset = (int)get_input('offset');
$last_event_object = array_pop(ClipitEvent::get_by_id(array($last_id)));
$last_time_created =  $last_event_object->time_created;


$user_id = elgg_get_logged_in_user_guid();
$my_groups = ClipitUser::get_groups($user_id);
$members_group_list = array();
foreach($my_groups as $group){
    $members_group_list = array_merge($members_group_list, ClipitGroup::get_users($group));
}
$members_group = array_unique($members_group_list);
$items_relationship = array(
    // Groups
    'group-user', 'group-file',
    // Activity
    'activity-file'
);
$relationships = array();
foreach($items_relationship as $item_rel){
    $events_list = ClipitEvent::get_filtered(
        $event_type = 'create',
        $user_array = $members_group,
        $object_id = 0,
        $object_type = 'relationship',
        $relation_type = $item_rel,
        $begin_date = 0,
        $end_date = $last_time_created,
        $limit = 20
    );
    if(!empty($events_list)){
        $relationships = array_merge($relationships, $events_list);
    }
}
$timestamps = array();
foreach ($relationships as $key => $value) {
    $timestamps[$key] = $value->time_created;
}
array_multisort($timestamps, SORT_DESC, $relationships);
$relationships = array_slice($relationships, 0, 5); // Array limit
//echo json_encode(trim(clipit_student_events($relationships)));
echo '<ul class="events">'.clipit_student_events($relationships).'</ul>';
