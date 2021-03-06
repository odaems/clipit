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
    /**
     * @var int Id of item this comment is targeting
     */
    public $target = -1;
    /**
     * @var int Id of user who posted the comment
     */
    public $author = -1;
    /**
     * @var int Timestamp when the comment was posted
     */
    public $time_created = -1;
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
        $this->target = (int)$elgg_object->target;
        $this->author = (int)$elgg_object->author;
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
        $elgg_object->target = (int)$this->target;
        $elgg_object->author = (int)$this->author;
        $elgg_object->overall = (bool)$this->overall;
        $elgg_object->rating_array = (array)$this->rating_array;
        $elgg_object->save();
        return $this->id = $elgg_object->guid;
    }

    static function get_by_author($author_array){
        $comment_array = array();
        foreach($author_array as $author_id){
            $elgg_object_array = elgg_get_entities_from_metadata(
                array(
                     "type" => ClipitComment::TYPE,
                     "subtype" => ClipitComment::SUBTYPE,
                     "metadata_names" => array("author"),
                     "metadata_values" => ($author_id)
                )
            );
            if(!$elgg_object_array){
                $comment_array[] = null;
            } else{
                $temp_array = array();
                foreach($elgg_object_array as $elgg_object){
                    $temp_array[] = new ClipitComment($elgg_object->guid);
                }
                if(!$temp_array){
                    $comment_array[] = null;
                } else{
                    $comment_array[] = $temp_array;
                }
            }
        }
        return $comment_array;
    }
}

