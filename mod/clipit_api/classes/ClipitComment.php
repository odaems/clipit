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
 * Class ClipitComment
 *
 * @package clipit
 */
class ClipitComment extends UBItem{
    /**
     * @const string Elgg entity subtype for this class
     */
    const SUBTYPE = "clipit_comment";
    const REL_COMMENT_TARGET = "comment-target";
    /**
     * @var bool Overall rating opinion: true = good, false = bad
     */
    public $overall = false;
    /**
     * @var array Ratings in the form: rating_array["rating_name"]=>"rating_value"
     */
    public $rating_array = array();


    /**
     * Loads a ClipitComment instance from the system.
     *
     * @param int $id Id of Comment to load
     * @return ClipitComment|null Returns Comment instance, or null if error
     */
    protected function _load($id){
        if(!$elgg_object = new ElggObject((int)$id)){
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
        $this->overall = (bool)$elgg_object->overall;
        $this->rating_array = (array)$elgg_object->rating_array;
        return $this;
    }

    /**
     * Saves this instance to the system
     *
     * @return bool|int Returns the Id of the saved instance, or false if error
     */
    function save(){
        if($this->id == -1){
            $elgg_object = new ElggObject();
            $elgg_object->subtype = (string)$this::SUBTYPE;
        } elseif(!$elgg_object = new ElggObject($this->id)){
            return false;
        }
        $elgg_object->name = (string)$this->name;
        $elgg_object->description = (string)$this->description;
        $elgg_object->overall = (bool)$this->overall;
        $elgg_object->rating_array = (array)$this->rating_array;
        $elgg_object->access_id = ACCESS_PUBLIC;
        $elgg_object->save();
        $this->owner_id = (int)$elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        return $this->id = $elgg_object->guid;
    }

    static function get_by_target($target_array){
        foreach($target_array as $target_id){
            $rel_array = get_entity_relationships($target_id, true);
            foreach($rel_array as $rel){
                if($rel->relationship == ClipitComment::REL_COMMENT_TARGET){
                    $temp_array[] = new ClipitComment($rel->guid_one);
                }
            }
            if(isset($temp_array)){
                $comment_array[] = $temp_array;
            }
        }
        if(!isset($comment_array)){
            return array();
        }
        return $comment_array;
    }

    static function set_target($id, $target_id){
        if(!$comment = new ClipitComment($id)){
            return null;
        }
        return add_entity_relationship($comment->id, ClipitComment::REL_COMMENT_TARGET, $target_id);
    }

    static function get_target($id){
        if(!$comment = new ClipitComment($id)){
            return null;
        }
        $rel_array = get_entity_relationships($comment->id);
        if(empty($rel_array) || count($rel_array) != 1){
            return null;
        }
        $rel = array_pop($rel_array);
        if($rel->relationship != ClipitComment::REL_COMMENT_TARGET){
            return null;
        }
        return $rel->guid_two;
    }


}

