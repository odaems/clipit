<?php
/**
 * URJC Backend
 * PHP version:     >= 5.2
 * Creation date:   2013-11-01
 * Last update:     $Date$
 *
 * @author          Pablo Llinás Arnaiz <pebs74@gmail.com>, JuxtaLearn Project
 * @version         $Version$
 * @link            http://
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

class UBEvent{

    static function list_filters(){
        $filters["event_type"] = array("login", "create", "update", "delete", "logout");
        $filters["user_id"] = -1;
        $filters["object_id"] = -1;
        $filters["object_type"] = array("clipit_activity", "clipit_comment", "clipit_file", "clipit_group",
            "clipit_palette", "clipit_quiz", "clipit_quiz_question", "clipit_quiz_result", "clipit_sta",
            "clipit_storyboard", "clipit_stumbling_block", "clipit_task", "clipit_tricky_topic", "clipit_user",
            "clipit_video");
        $filters["begin_date"] = -1;
        $filters["end_date"] = -1;
        $filters["limit"] = -1;
        return $filters;
    }

    static function get_all($limit = 20){
        return get_system_log(
            null,           // $by_user = ""
            null,           // $event = ""
            null,           // $class = ""
            null,           // $type = ""
            null,           // $subtype = ""
            $limit,         // $limit = 10
            null,           // $offset = 0
            null,           // $count = false
            null,           // $timebefore = 0
            null,           // $timeafter = 0
            null,           // $object_id = 0
            null);          // $ip_address = ""
    }

    static function get_filtered(
                        $event_type = "",
                        $user_id = "",
                        $object_id = 0,
                        $object_type = "",
                        $begin_date = 0,
                        $end_date = 0,
                        $limit = 20){
        if($object_id != ""){
            switch ($object_type){
                case "clipit_user":
                    $type = "user";
                    $subtype = "";
                    break;
                default:
                    $type = "object";
                    $subtype = $object_type;
                    break;
            }
        } else{
            $type = "";
            $subtype = "";
        }
        return get_system_log(
            $user_id,       // $by_user = ""
            $event_type,    // $event = ""
            null,           // $class = ""
            $type,          // $type = ""
            $subtype,       // $subtype = ""
            $limit,         // $limit = 10
            null,           // $offset = 0
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

    static function get_from_user($user_id, $limit = 20){
        return get_system_log(
            $user_id,       // $by_user = ""
            null,           // $event = ""
            null,           // $class = ""
            null,           // $type = ""
            null,           // $subtype = ""
            $limit,         // $limit = 10
            null,           // $offset = 0
            null,           // $count = false
            null,           // $timebefore = 0
            null,           // $timeafter = 0
            null,           // $object_id = 0
            null);          // $ip_address = ""
    }

    static function get_from_object($object_id, $limit = 20){
        return get_system_log(
            null,           // $by_user = ""
            null,           // $event = ""
            null,           // $class = ""
            null,           // $type = ""
            null,           // $subtype = ""
            $limit,         // $limit = 10
            null,           // $offset = 0
            null,           // $count = false
            null,           // $timebefore = 0
            null,           // $timeafter = 0
            $object_id,     // $object_id = 0
            null);          // $ip_address = ""
    }

    static function get_from_object_type($object_type, $limit= 20){
        switch ($object_type){
            case "clipit_user":
                $type = "user";
                $subtype = "";
                break;
            default:
                $type = "object";
                $subtype = $object_type;
                break;
        }
        return get_system_log(
            null,           // $by_user = ""
            null,           // $event = ""
            null,           // $class = ""
            $type,          // $type = ""
            $subtype,       // $subtype = ""
            $limit,         // $limit = 10
            null,           // $offset = 0
            null,           // $count = false
            null,           // $timebefore = 0
            null,           // $timeafter = 0
            null,           // $object_id = 0
            null);          // $ip_address = ""
    }
}