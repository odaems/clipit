<?php
/**
 * ClipIt - JuxtaLearn Web Space
 * PHP version:     >= 5.2
 * Creation date:   2013-10-10
 * Last update:     $Date$
 *
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, JuxtaLearn Project
 * @version         $Version$
 * @link            http://juxtalearn.org
 * @license         GNU Affero General Public License v3
 *                  (http://www.gnu.org/licenses/agpl-3.0.txt)
 *                  This program is free software: you can redistribute it and/or modify
 *                  it under the terms of the GNU Affero General Public License as
 *                  published by the Free Software Foundation, version 3.
 *                  This program is distributed in the hope that it will be useful,
 *                  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *                  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *                  GNU Affero General Public License for more details.
 *                  You should have received a copy of the GNU Affero General Public License
 *                  along with this program. If not, see
 *                  http://www.gnu.org/licenses/agpl-3.0.txt.
 */

/**
 * Class ClipitEvent
 *
 * @package clipit
 */
class ClipitEvent extends UBEvent {
    /**
     * Lists the available filters to be applied then retrieving Events with 'get_filtered'.
     *
     * @return array Array with 'key' => 'value' where 'key' is the filter name and 'value' shows the type and options
     * for each filter.
     */
    static function list_filters(){
        $filters["event_type"] = array("login", "create", "update", "delete", "logout");
        $filters["user_array"] = array();
        $filters["object_id"] = -1;
        $filters["object_type"] = array(
            "clipit_activity",
            "clipit_comment",
            "clipit_file",
            "clipit_group",
            "clipit_palette",
            "clipit_quiz",
            "clipit_quiz_question",
            "clipit_quiz_result",
            "clipit_sta",
            "clipit_storyboard",
            "clipit_stumbling_block",
            "clipit_task",
            "clipit_tricky_topic",
            "clipit_user",
            "clipit_video",
            "relationship");
        $filters["relationship_type"] = array(
            "activity-user",
            "activity-group",
            "activity-video",
            "activity-file",
            "group-user",
            "group-file");
        $filters["begin_date"] = -1;
        $filters["end_date"] = -1;
        $filters["limit"] = -1;
        return $filters;
    }

    static function get_filtered(
        $event_type = "",
        $user_array = "",
        $object_id = 0,
        $object_type = "",
        $relationship_type = "",
        $begin_date = 0,
        $end_date = 0,
        $limit = 10,
        $offset = 0){

        if(empty($object_type)){
            $type = $subtype = "";
        } else{
            switch ($object_type){
                case "clipit_user":
                    $type = "user";
                    $subtype = "";
                    break;
                case "relationship":
                    $type = "relationship";
                    $subtype = $relationship_type;
                    break;
                default:
                    $type = "object";
                    $subtype = $object_type;
                    break;
            }
        }

        return get_system_log(
            $user_array,    // $by_user = ""
            $event_type,    // $event = ""
            null,           // $class = ""
            $type,          // $type = ""
            $subtype,       // $subtype = ""
            $limit,         // $limit = 10
            $offset,        // $offset = 0
            null,           // $count = false
            $end_date,      // $timebefore = 0
            $begin_date,    // $timeafter = 0
            $object_id,     // $object_id = 0
            null);          // $ip_address = ""
    }

    static function get_by_id($id_array){
        foreach($id_array as $event_id){
            $log_event = get_log_entry($event_id);
            if(empty($log_event)){
                $event_array[] = null;
            } else{
                $event_array[] = $log_event;
            }
        }
        return $event_array;
    }

} 