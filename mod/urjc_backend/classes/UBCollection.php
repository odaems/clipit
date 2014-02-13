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

/**
 * Class UBCollection
 *
 * @package urjc_backend
 */
abstract class UBCollection extends UBItem{
    /**
     * @const string Elgg entity SUBTYPE for this class
     */
    const SUBTYPE = "collection";
    /**
     * @const string Details name for collection relationships.
     */
    const DEFAULT_RELATIONSHIP = "collection_contains";
    /**
     * Loads an instance from the system.
     *
     * @param int $id Id of the instance to load from the system.
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
        $this->description = (string)$elgg_object->description;
        $this->name = (string)$elgg_object->name;
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
        $elgg_object->owner_guid = 0;
        $elgg_object->container_guid = 0;
        $elgg_object->access_id = ACCESS_PUBLIC;
        $elgg_object->save();
        return $this->id = $elgg_object->guid;
    }
    /**
     * Adds items to this collection.
     *
     * @param array $item_array Array of item ids to add to this collection.
     * @param string $rel_name Name of the relationship for added items.
     * @return bool Returns true if added correctly, or false in case of error.
     */
    function addItems($item_array, $rel_name = null){
        if(!$rel_name){
            $rel_name = $this::DEFAULT_RELATIONSHIP;
        }
        foreach($item_array as $item_id){
            add_entity_relationship($this->id, $rel_name, $item_id);
        }
        return true;
    }

    /**
     * Removes items from this collection.
     *
     * @param array $item_array Array of Item Ids to remove from this collection.
     * @param string $rel_name Name of the relationship to remove items from.
     * @return bool Returns true if removed correctly, false if error.
     */
    function removeItems($item_array, $rel_name = null){
        if(!$rel_name){
            $rel_name = $this::DEFAULT_RELATIONSHIP;
        }
        foreach($item_array as $item_id){
            remove_entity_relationship($this->id, $rel_name, $item_id);
        }
        return true;
    }

    /**
     * Returns items from this collection.
     *
     * @param string $rel_name Name of the relationship to get items from.
     * @return array Array of items in this Collection.
     */
    function getItems($rel_name = null){
        if(!$rel_name){
            $rel_name = $this::DEFAULT_RELATIONSHIP;
        }
        $rel_array = get_entity_relationships($this->id);
        $item_ids = array();
        foreach($rel_array as $rel){
            if($rel->relationship == $rel_name){
                $item_ids[] = (int) $rel->guid_two;
            }
        }
        return $item_ids;
    }

    /**
     * Adds Items to a Collection.
     *
     * @param int $id Id from Collection to add Items to
     * @param array $item_array Array of Item Ids to add
     * @return bool Returns true if success, false if error
     */
    static function add_items($id, $item_array){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->addItems($item_array);
    }

    /**
     * Remove Items from a Collection.
     *
     * @param int $id Id from Collection to remove Items from.
     * @param array $item_array Array of Item Ids to remove.
     * @return bool Returns true if success, false if error.
     */
    static function remove_items($id, $item_array){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->removeItems($item_array);
    }

    /**
     * Get Items from a Collection.
     *
     * @param int $id Id from Collection to get Items from.
     * @return array|bool Returns an array of Item IDs, or false if error.
     */
    static function get_items($id){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->getItems();
    }

}