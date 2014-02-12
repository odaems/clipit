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
     * @var array Array of Item ids contained in this Collection
     */
    public $item_array = array();
    /**
     * @var string Type of the contained Items in this Collection
     */
    public $item_type = "object";
    /**
     * @var string Subtype of the contained Items in this Collection
     */
    public $item_subtype = "item";
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
        $this->item_array = (array)$elgg_object->item_array;
        $this->item_type = (string)$elgg_object->item_type;
        $this->item_subtype = (string)$elgg_object->item_subtype;
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
        $elgg_object->item_array = (array)$this->item_array;
        $elgg_object->item_type = (string)$this->item_type;
        $elgg_object->item_subtype = (string)$this->item_subtype;
        $elgg_object->save();
        return $this->id = $elgg_object->guid;
    }
    /**
     * Adds items to this collection.
     *
     * @param array $item_array Array of item ids to add to the collection.
     * @return bool Returns true if added correctly, or false in case of error.
     */
    function addItems($item_array){
        if(!$this->item_array){
            $this->item_array = $item_array;
        } else{
            $this->item_array = array_merge($this->item_array, $item_array);
        }
        if(!$this->save()){
            return false;
        }
        return true;
    }

    /**
     * Removes items from this collection.
     *
     * @param $item_array
     * @return bool
     */
    function removeItems($item_array){
        if(!$this->item_array){
            return false;
        }
        foreach($item_array as $item){
            $key = array_search($item, $this->item_array);
            if(isset($key)){
                unset($this->item_array[$key]);
            } else{
                return false;
            }
        }
        if(!$this->save()){
            return false;
        }
        return true;
    }

    /**
     * Adds Items to a Collection.
     *
     * @param int $id Id from Collection to add Items to
     * @param array $item_array Array of Items to add
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
     * @param int $id Id from Collection to remove Items from
     * @param array $item_array Array of Items to remove
     * @return bool Returns true if success, false if error
     */
    static function remove_items($id, $item_array){
        $called_class = get_called_class();
        if(!$collection = new $called_class($id)){
            return false;
        }
        return $collection->removeItems($item_array);
    }

    /**
     * Get all Objects of this TYPE/SUBTYPE from the system.
     *
     * @param int $limit Number of results to show, default= 0 [no limit] (optional)
     * @return array Returns an array of Objects
     */
    static function get_all($limit = 0){
        $called_class = get_called_class();
        $elgg_object_array = elgg_get_entities(
            array(
                'type' => $called_class::TYPE,
                'subtype' => $called_class::SUBTYPE,
                'limit' => $limit));
        $object_array = array();
        if(empty($elgg_object_array)){
            return $object_array;
        }
        foreach($elgg_object_array as $elgg_object){
            $object_array[] = new $called_class((int)$elgg_object->guid);
        }
        return $object_array;
    }

}