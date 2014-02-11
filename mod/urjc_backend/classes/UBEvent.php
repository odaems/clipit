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

class UBEvent {
    public $id = -1;
    public $event_type = "";
    public $user_id = -1;
    public $object_id = -1;
    public $object_type = "";
    public $object_subtype = "";
    public $time_created = -1;

    /**
     * Constructor
     *
     * @param int|null $id If $id is null, create new instance; else load instance with id = $id.
     */
    function __construct($id = null){
        if($id){
            $this->_load((int)$id);
        }
    }

    /**
     * Loads an instance from the system.
     *
     * @param int $id Id of the instance to load from the system.
     * @return UBItem|bool Returns instance, or false if error.
     */
    protected function _load($id){
        $options['ids'] = array($id);
        $elgg_river_array = (array) elgg_get_river($options);
        if(empty($elgg_river_array)){
            return;
        }
        $elgg_river_item = array_pop($elgg_river_array);
        $this->id = $elgg_river_item->id;
        $this->event_type = $elgg_river_item->action_type;
        $this->user_id = $elgg_river_item->subject_guid;
        $this->object_id = $elgg_river_item->object_guid;
        $this->object_type = $elgg_river_item->type;
        $this->object_subtype = $elgg_river_item->subtype;
        $this->time_created = $elgg_river_item->posted;
    }

    /**
     * Lists the properties contained in this object
     *
     * @return array Array of properties with type and default value
     */
    static function list_properties(){
        return get_class_vars(get_called_class());
    }

    static function get_all($limit = 20){
        $called_class = get_called_class();
        $event_array = array();
        $options['limit'] = $limit;
        $elgg_river_array = elgg_get_river($options);
        foreach($elgg_river_array as $elgg_river){
            $event_array[] = new $called_class((int)$elgg_river->id);
        }
        if(empty($event_array)){
            return null;
        }
        return $event_array;
    }

    static function get_by_id($id_array){
        $called_class = get_called_class();
        $event_array = array();
        foreach($id_array as $event_id){
            $event = new $called_class((int) $event_id);
            if($event->id == -1){
                $event_array[] = null;
            } else{
                $event_array[] = $event;
            }
        }
        return $event_array;
    }

    static function get_by_user($user_array){
        $called_class = get_called_class();
        $event_array = array();
        foreach($user_array as $user_id){
            $options['subject_guids'] = $user_id;
            $elgg_river_array = elgg_get_river($options);
            $temp_array = array();
            foreach($elgg_river_array as $elgg_river){
                $temp_array[] = new $called_class((int)$elgg_river->id);
            }
            $event_array[] = $temp_array;
        }
        return $event_array;
    }
}