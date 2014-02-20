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
        $filters["user_array"] = array();
        $filters["object_id"] = -1;
        $filters["object_type"] = array("user", "relationship", "object");
        $filters["relationship_type"] = "";
        $filters["begin_date"] = -1;
        $filters["end_date"] = -1;
        $filters["limit"] = -1;
        return $filters;
    }

    static function get_all($limit = 10){
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
                        $event_type,
                        $user_array,
                        $object_id ,
                        $object_type,
                        $relationship_type,
                        $begin_date,
                        $end_date,
                        $limit = 10){
        if(empty($event_type)){
            $event_type = "";
        }
        if(empty($user_array)){
            $user_array = "";
        }
        if(empty($object_id)){
            $object_id = 0;
        }
        if(empty($object_type)){
            $type = $subtype = "";
        } else{
            switch ($object_type){
                case "user":
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
        if(empty($begin_date)){
            $begin_date = 0;
        }
        if(empty($end_date)){
            $end_date = 0;
        }

    return get_system_log(
        $user_array,    // $by_user = ""
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
}