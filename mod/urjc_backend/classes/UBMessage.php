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

    const REL_MESSAGE_DESTINATION = "message-destination";
    const REL_MESSAGE_PARENT = "message-parent";

    public $read = false;
    public $destination = -1;
    public $parent = -1;


    /**
     * Loads an instance from the system.
     *
     * @param int $id Id of the instance to load from the system.
     *
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
        $this->destination = $this->getDestination();
        $this->parent = $this->getParent();
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
        $this->id = (int)$elgg_object->guid;
        $this->owner_id = (int)$elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        $this->setDestination();
        $this->setParent();
        return $this->id;
    }

    function getDestination(){
        $temp_array = get_entity_relationships($this->id);
        foreach($temp_array as $rel){
            if($rel->relationship == self::REL_MESSAGE_DESTINATION){
                $rel_array[] = $rel;
            }
        }
        if(empty($rel_array) || count($rel_array) != 1){
            return -1;
        }
        $rel = array_pop($rel_array);
        return $rel->guid_two;
    }

    function setDestination($destination_id = -1){
        if($rel_array = get_entity_relationships($this->id)){
            foreach($rel_array as $rel){
                if($rel->relationship == self::REL_MESSAGE_DESTINATION){
                    delete_relationship($rel->id);
                }
            }
        }
        if($destination_id == -1){
            $destination_id = $this->destination;
        }
        return add_entity_relationship($this->id, self::REL_MESSAGE_DESTINATION, $destination_id);
    }

    function getParent(){
        $temp_array = get_entity_relationships($this->id);
        foreach($temp_array as $rel){
            if($rel->relationship == self::REL_MESSAGE_PARENT){
                $rel_array[] = $rel;
            }
        }
        if(empty($rel_array) || count($rel_array) != 1){
            return -1;
        }
        $rel = array_pop($rel_array);
        return $rel->guid_two;
    }

    function setParent($parent_id = -1){
        if($rel_array = get_entity_relationships($this->id)){
            foreach($rel_array as $rel){
                if($rel->relationship == self::REL_MESSAGE_PARENT){
                    delete_relationship($rel->id);
                }
            }
        }
        if($parent_id == -1){
            $parent_id = $this->parent;
        }
        return add_entity_relationship($this->id, self::REL_MESSAGE_PARENT, $parent_id);
    }

    /* STATIC FUNCTIONS */

    static function get_destination($id){
        $called_class = get_called_class();
        $message = new $called_class($id);
        return $message->getDestination();
    }

    static function set_destination($id, $destination_id){
        $called_class = get_called_class();
        $message = new $called_class($id);
        return $message->setDestination($destination_id);
    }

    static function get_parent($id){
        $called_class = get_called_class();
        $message = new $called_class($id);
        return $message->getParent();
    }

    static function set_parent($id, $destination_id){
        $called_class = get_called_class();
        $message = new $called_class($id);
        return $message->setParent($destination_id);
    }

    static function get_by_sender($sender_array){
        $called_class = get_called_class();
        $object_array = array();
        foreach($sender_array as $sender_id){
            $elgg_object_array = elgg_get_entities(
                array(
                    "type" => $called_class::TYPE,
                    "subtype" => $called_class::SUBTYPE,
                    "owner_guid" => (int)$sender_id
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
                if($rel->relationship == self::REL_MESSAGE_DESTINATION){
                    $temp_array[] = new $called_class((int)$rel->guid_one);
                }
            }
            if(empty($temp_array)){
                $object_array[] = null;
            } else{
                $object_array[] = $temp_array;
            }
        }
        return $object_array;
    }

    static function get_read_status($id){
        $called_class = get_called_class();
        $prop_array[] = "read";
        return $called_class::get_properties($id, $prop_array);
    }

    static function set_read_status($id, $read = true){
        $called_class = get_called_class();
        $prop_value_array["read"] = (bool)$read;
        return $called_class::set_properties($id, $prop_value_array);
    }


}