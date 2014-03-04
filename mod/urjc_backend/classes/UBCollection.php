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
    const REL_DEFAULT = "collection-item";

    /**
     * Deletes an instance from the system.
     *
     * @param bool $delete_related If set, deletes all related items before deleting this Collection
     *
     * @return bool True if success, false if error.
     */
    function delete($delete_related = false){
        if($delete_related){
            $this->deleteRelatedItems();
        }
        return parent::delete();
    }

    function deleteRelatedItems(){
        $rel_array = get_entity_relationships((int)$this->id);
        foreach($rel_array as $rel){
            switch($rel->relationship){
                case $this::REL_DEFAULT:
                    $item_array[] = $rel->guid_two;
                    break;
            }
        }
        if(isset($item_array)){
            UBItem::delete_by_id($item_array);
        }
    }

    static function delete_by_id($id_array, $delete_related = false){
        $called_class = get_called_class();
        foreach($id_array as $id){
            if(!$item = new $called_class($id)){
                return false;
            }
            if(!$item->delete($delete_related)){
                return false;
            }
        }
        return true;
    }

    /**
     * Adds items to this collection.
     *
     * @param array  $item_array Array of item ids to add to this collection.
     * @param string $rel_name Name of the relationship for added items.
     * @param bool   $exclusive If set, checks that the relationship does not exist previously
     *
     * @return bool Returns true if added correctly, or false in case of error.
     */
    function addItems($item_array, $rel_name, $exclusive = false){
        if(is_null($rel_name)){
            $rel_name = $this::REL_DEFAULT;
        }
        foreach($item_array as $item_id){
            if($exclusive){
                if($rel_array = get_entity_relationships($item_id, true)){
                    foreach($rel_array as $rel){
                        if($rel->relationship == $rel_name){
                            throw new Exception("UBCollection: Exclusive relationship already exists: " .
                                                $rel->guid_one .
                                                "-" .
                                                $rel->relationship .
                                                "-" .
                                                $rel->guid_two);
                        }
                    }
                }
            }
            add_entity_relationship($this->id, $rel_name, $item_id);
        }
        return true;
    }

    /**
     * Removes items from this collection.
     *
     * @param array  $item_array Array of Item Ids to remove from this collection.
     * @param string $rel_name Name of the relationship to remove items from.
     *
     * @return bool Returns true if removed correctly, false if error.
     */
    function removeItems($item_array, $rel_name){
        if(is_null($rel_name)){
            $rel_name = $this::REL_DEFAULT;
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
     *
     * @return int[] Array of items in this Collection.
     */
    function getItems($rel_name){
        if(is_null($rel_name)){
            $rel_name = $this::REL_DEFAULT;
        }
        $rel_array = get_entity_relationships($this->id);
        $item_ids = array();
        foreach($rel_array as $rel){
            if($rel->relationship == $rel_name){
                $item_ids[] = (int)$rel->guid_two;
            }
        }
        return $item_ids;
    }

    /**
     * Adds Items to a Collection.
     *
     * @param int   $id Id from Collection to add Items to
     * @param array $item_array Array of Item Ids to add
     *
     * @return bool Returns true if success, false if error
     */
    static function add_items($id, $item_array, $rel_name, $exclusive = false){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->addItems($item_array, $rel_name, $exclusive);
    }

    /**
     * Remove Items from a Collection.
     *
     * @param int   $id Id from Collection to remove Items from.
     * @param array $item_array Array of Item Ids to remove.
     *
     * @return bool Returns true if success, false if error.
     */
    static function remove_items($id, $item_array, $rel_name){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->removeItems($item_array, $rel_name);
    }

    /**
     * Get Items from a Collection.
     *
     * @param int $id Id from Collection to get Items from.
     *
     * @return int[]|bool Returns an array of Item IDs, or false if error.
     */
    static function get_items($id, $rel_name){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->getItems($rel_name);
    }

}