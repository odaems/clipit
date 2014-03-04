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
 * Class ClipitActivity
 *
 * @package clipit
 */
class ClipitActivity extends UBCollection{
    /**
     * @const string Elgg entity subtype for this class
     */
    const SUBTYPE = "clipit_activity";

    const REL_ACTIVITY_USER = "activity-user";
    const REL_ACTIVITY_GROUP = "activity-group";
    const REL_ACTIVITY_TASK = "activity-task";
    const REL_ACTIVITY_VIDEO = "activity-video";
    const REL_ACTIVITY_FILE = "activity-file";

    const STATUS_ENROLL = "enroll";
    const STATUS_ACTIVE = "active";
    const STATUS_CLOSED = "closed";

    public $color = "";
    public $status = "";

    /**
     * Loads an instance from the system.
     *
     * @param int $id Id of the instance to load from the system.
     *
     * @return UBItem|bool Returns instance, or false if error.
     */
    protected function _load($id){
        if(!($elgg_object = new ElggObject((int)$id))){
            return null;
        }
        $elgg_type = $elgg_object->type;
        $elgg_subtype = get_subtype_from_id($elgg_object->subtype);
        if(($elgg_type != $this::TYPE) || ($elgg_subtype != $this::SUBTYPE)){
            return null;
        }
        $this->id = (int)$elgg_object->guid;
        $this->name = (string)$elgg_object->name;
        $this->description = (string)$elgg_object->description;
        $this->owner_id = (int)$elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        $this->color = (string)$elgg_object->color;
        $this->status = (string)$elgg_object->status;
        return $this;
    }

    /**
     * Saves this instance to the system.
     *
     * @return bool|int Returns id of saved instance, or false if error.
     */
    function save(){
        if($this->id == -1){
            $elgg_object = new ElggObject();
            $elgg_object->subtype = (string)$this::SUBTYPE;
        } elseif(!$elgg_object = new ElggObject((int)$this->id)){
            return false;
        }
        $elgg_object->name = (string)$this->name;
        $elgg_object->description = (string)$this->description;
        $elgg_object->color = (string)$this->color;
        $elgg_object->status = (string)$this->status;
        $elgg_object->access_id = ACCESS_PUBLIC;
        $elgg_object->save();
        $this->owner_id = (int)$elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        return $this->id = $elgg_object->guid;
    }

    function deleteRelatedItems(){
        $rel_array = get_entity_relationships((int)$this->id);
        foreach($rel_array as $rel){
            switch($rel->relationship){
                case ClipitActivity::REL_ACTIVITY_GROUP:
                    $group_array[] = $rel->guid_two;
                    break;
                case ClipitActivity::REL_ACTIVITY_VIDEO:
                    $video_array[] = $rel->guid_two;
                    break;
                case ClipitActivity::REL_ACTIVITY_FILE:
                    $file_array[] = $rel->guid_two;
                    break;
            }
        }
        if(isset($group_array)){
            ClipitGroup::delete_by_id($group_array);
        }
        if(isset($video_array)){
            ClipitVideo::delete_by_id($video_array);
        }
        if(isset($file_array)){
            ClipitFile::delete_by_id($file_array);
        }
    }

    static function get_status($id){
        $prop_array[] = "status";
        return ClipitActivity::get_properties($id, $prop_array);
    }

    static function set_status_enroll($id){
        $prop_value_array["status"] = ClipitActivity::STATUS_ENROLL;
        return ClipitActivity::set_properties($id, $prop_value_array);
    }

    static function set_status_active($id){
        $prop_value_array["status"] = ClipitActivity::STATUS_ACTIVE;
        return ClipitActivity::set_properties($id, $prop_value_array);
    }

    static function set_status_closed($id){
        $prop_value_array["status"] = ClipitActivity::STATUS_CLOSED;
        return ClipitActivity::set_properties($id, $prop_value_array);
    }

    static function get_from_user($user_id){
        if(!$group_ids = ClipitUser::get_groups($user_id)){
            return false;
        }
        foreach($group_ids as $group_id){
            $activity_ids[] = ClipitGroup::get_activity($group_id);
        }
        if(!isset($activity_ids)){
            return false;
        }
        return ClipitActivity::get_by_id($activity_ids);
    }

    // USERS
    static function add_called_users($id, $user_array){
        return ClipitActivity::add_items($id, $user_array, ClipitActivity::REL_ACTIVITY_USER);
    }

    static function remove_called_users($id, $user_array){
        return ClipitActivity::remove_items($id, $user_array, ClipitActivity::REL_ACTIVITY_USER);
    }

    static function get_called_users($id){
        return ClipitActivity::get_items($id, ClipitActivity::REL_ACTIVITY_USER);
    }

    // GROUPS
    static function add_groups($id, $group_array){
        return ClipitActivity::add_items($id, $group_array, ClipitActivity::REL_ACTIVITY_GROUP, true);
    }

    static function remove_groups($id, $group_array){
        return ClipitActivity::remove_items($id, $group_array, ClipitActivity::REL_ACTIVITY_GROUP);
    }

    static function get_groups($id){
        return ClipitActivity::get_items($id, ClipitActivity::REL_ACTIVITY_GROUP);
    }

    // TASKS
    static function add_tasks($id, $task_array){
        return ClipitActivity::add_items($id, $task_array, ClipitActivity::REL_ACTIVITY_TASK, true);
    }

    static function remove_tasks($id, $task_array){
        return ClipitActivity::remove_items($id, $task_array, ClipitActivity::REL_ACTIVITY_TASK);
    }

    static function get_tasks($id){
        return ClipitActivity::get_items($id, ClipitActivity::REL_ACTIVITY_TASK);
    }

    // VIDEOS
    static function add_videos($id, $video_array){
        return ClipitActivity::add_items($id, $video_array, ClipitActivity::REL_ACTIVITY_VIDEO);
    }

    static function remove_videos($id, $video_array){
        return ClipitActivity::remove_items($id, $video_array, ClipitActivity::REL_ACTIVITY_VIDEO);
    }

    static function get_videos($id){
        return ClipitActivity::get_items($id, ClipitActivity::REL_ACTIVITY_VIDEO);
    }

    // FILES
    static function add_files($id, $file_array){
        return ClipitActivity::add_items($id, $file_array, ClipitActivity::REL_ACTIVITY_FILE);
    }

    static function remove_files($id, $file_array){
        return ClipitActivity::remove_items($id, $file_array, ClipitActivity::REL_ACTIVITY_FILE);
    }

    static function get_files($id){
        return ClipitActivity::get_items($id, ClipitActivity::REL_ACTIVITY_FILE);
    }
}