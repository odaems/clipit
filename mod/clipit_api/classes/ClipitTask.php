<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 14/02/14
 * Time: 16:11
 */

class ClipitTask extends UBItem{

    const SUBTYPE = "clipit_task";


    static function get_activity($id){
        $temp_array = get_entity_relationships($id, true);
        foreach($temp_array as $rel){
            if($rel->relationship == ClipitActivity::REL_ACTIVITY_TASK){
                $rel_array[] = $rel;
            }
        }
        if(!isset($rel_array) || size($rel_array) != 1){
            return null;
        }
        return array_pop($rel_array)->guid_one;
    }

} 