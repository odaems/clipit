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

class UBMessage extends UBItem{

    const SUBTYPE = "message";

    const REL_DESTINATION = "message-destination";

    public $read = false;

    /**
     * Loads an instance from the system.
     *
     * @param int $id Id of the instance to load from the system.
     * @return UBItem|null Returns instance, or null if error.
     */
    protected function _load($id){
        if(!($elgg_object = new ElggObject((int)$id))){
            return null;
        }
        $elgg_type = $elgg_object->type;
        $elgg_subtype = $elgg_object->getSubtype();
        if(($elgg_type != $this::TYPE) || ($elgg_subtype != $this::SUBTYPE)){
            return null;
        }
        $this->id = (int)$elgg_object->guid;
        $this->description = (string)$elgg_object->description;
        $this->name = (string)$elgg_object->name;
        $this->owner_id = (int)$elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        $this->read = (bool)$elgg_object->read;
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
        $elgg_object->read = (bool)$this->read;
        $elgg_object->access_id = ACCESS_PUBLIC;
        $elgg_object->save();
        $this->owner_id = (int) $elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        return $this->id = (int) $elgg_object->guid;
    }

    static function get_destination($id){
        $called_class = get_called_class();
        $message = new $called_class($id);
        $rel_array = get_entity_relationships($message->id);
        if(empty($rel_array) || count($rel_array) != 1){
            return null;
        }
        $rel = array_pop($rel_array);
        if($rel->relationship != self::REL_DESTINATION){
            return null;
        }
        return $rel->guid_two;
    }

    static function set_destination($id, $destination_id){
        $called_class = get_called_class();
        $message = new $called_class($id);
        return add_entity_relationship($message->id, self::REL_DESTINATION, $destination_id);
    }

    static function get_by_sender($sender_array){
        $called_class = get_called_class();
        $object_array = array();
        foreach($sender_array as $sender_id){
            $elgg_object_array = elgg_get_entities(
                array(
                    "type" => $called_class::TYPE,
                    "subtype" => $called_class::SUBTYPE,
                    "owner_guid" => (int) $sender_id
                )
            );
            $temp_array = array();
            foreach($elgg_object_array as $elgg_object){
                $temp_array[] = new $called_class((int)$elgg_object->guid);
            }
            if(!empty($temp_array)){
                $object_array[] = $temp_array;
            } else{
                $object_array[] = null;
            }
        }
        return $object_array;
    }

    static function get_by_destination($destination_array){
        $called_class = get_called_class();
        $object_array = array();
        foreach($destination_array as $destination_id){
            $rel_array = get_entity_relationships($destination_id, true);
            $temp_array = array();
            foreach($rel_array as $rel){
                $temp_array[] = new $called_class((int)$rel->guid_one);
            }
            if(!empty($temp_array)){
                $object_array[] = $temp_array;
            } else{
                $object_array[] = null;
            }
        }
        return $object_array;
    }

    static function get_read_status($id){
        $called_class = get_called_class();
        if(!$comment = new $called_class($id)){
            return null;
        }
        return $comment->read;
    }

    static function set_read_status($id, $read = true){
        $called_class = get_called_class();
        if(!$comment = new $called_class($id)){
            return null;
        }
        $prop_value_array["read"] = (bool)$read;
        return $comment->setProperties($prop_value_array);
    }


}