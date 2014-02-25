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
    const DEFAULT_REL = "collection-item";

    /**
     * Deletes an instance from the system.
     *
     * @return bool True if success, false if error.
     */
    function delete(){
        $this->deleteItems();
        return parent::delete();
    }

    /**
     * Adds items to this collection.
     *
     * @param array $item_array Array of item ids to add to this collection.
     * @param string $rel_name Name of the relationship for added items.
     * @return bool Returns true if added correctly, or false in case of error.
     */
    function addItems($item_array, $rel_name = ""){
        if(empty($rel_name)){
            $rel_name = $this::DEFAULT_REL;
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
    function removeItems($item_array, $rel_name = ""){
        if(empty($rel_name)){
            $rel_name = $this::DEFAULT_REL;
        }
        foreach($item_array as $item_id){
            remove_entity_relationship($this->id, $rel_name, $item_id);
        }
        return true;
    }

    function deleteItems(){
        $rel_array = get_entity_relationships((int) $this->id);
        $delete_array = array();
        foreach($rel_array as $rel){
            $delete_array[] = $rel->guid_two;
        }
        if(!empty($delete_array)){
            UBItem::delete_by_id($delete_array);
        }
    }

    /**
     * Returns items from this collection.
     *
     * @param string $rel_name Name of the relationship to get items from.
     * @return int[] Array of items in this Collection.
     */
    function getItems($rel_name = ""){
        if(empty($rel_name)){
            $rel_name = $this::DEFAULT_REL;
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
     * @return int[]|bool Returns an array of Item IDs, or false if error.
     */
    static function get_items($id){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->getItems();
    }

}