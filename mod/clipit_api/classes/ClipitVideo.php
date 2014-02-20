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
 * Class ClipitVideo
 *
 * @package clipit
 */
class ClipitVideo extends UBCollection{
    /**
     * @const string Elgg entity sybtype for this class
     */
    const SUBTYPE = "clipit_video";
    const REL_COMMENT = "video-comment";
    const REL_TAG = "video-tag";
    /**
     * @var string Link to where the video is hosted (Youtube, Vimeo...)
     */
    public $url = "";

    /**
     * Loads a ClipitVideo instance from the system.
     *
     * @param int $id Id of Video to load
     * @return ClipitVideo|null Returns Video instance, or null if error
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
        $this->description = $elgg_object->description;
        $this->owner_id = (int) $elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        $this->url = (int)$elgg_object->url;
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
        $elgg_object->url = (int)$this->url;
        $elgg_object->access_id = ACCESS_PUBLIC;
        $elgg_object->save();
        $this->owner_id = (int) $elgg_object->owner_guid;
        $this->time_created = (int)$elgg_object->time_created;
        return $this->id = $elgg_object->guid;
    }

    /**
     * Adds Comments to a Video, referenced by Id.
     *
     * @param int $id Id from the Video to add Comments to
     * @param array $comment_array Array of Comment Ids to be added to the Video
     * @return bool Returns true if success, false if error
     */
    static function add_comments($id, $comment_array){
        if(!$video = new ClipitVideo($id)){
            return false;
        }
        return $video->addItems($comment_array, self::REL_COMMENT);
    }

    /**
     * Remove Comments from a Video.
     *
     * @param int $id Id from Video to remove Comments from
     * @param array $comment_array Array of Comment Ids to remove from Video
     * @return bool Returns true if success, false if error
     */
    static function remove_comments($id, $comment_array){
        if(!$video = new ClipitVideo($id)){
            return false;
        }
        return $video->removeItems($comment_array, self::REL_COMMENT);
    }

    /**
     * Get all Comments for a Video
     *
     * @param int $id Id of the Video to get Comments from
     * @return array|bool Returns an array of ClipitComment items, or false if error
     */
    static function get_comments($id){
        if(!$video = new ClipitVideo($id)){
            return false;
        }
        return $video->getItems(self::REL_COMMENT);
    }

    /**
     * Adds Tags to a Video, referenced by Id.
     *
     * @param int $id Id from the Video to add Tags to
     * @param array $taxonomy_tag_array Array of Tag Ids to be added to the Video
     * @return bool Returns true if success, false if error
     */
    static function add_tags($id, $tag_array){
        if(!$video = new ClipitVideo($id)){
            return false;
        }
        return $video->addItems($tag_array, self::REL_TAG);
    }

    /**
     * Remove Tags from a Video.
     *
     * @param int $id Id from Video to remove Tags from
     * @param array $taxonomy_tag_array Array of Tag Ids to remove from Video
     * @return bool Returns true if success, false if error
     */
    static function remove_tags($id, $tag_array){
        if(!$video = new ClipitVideo($id)){
            return false;
        }
        return $video->removeItems($tag_array, self::REL_TAG);
    }

    /**
     * Get all Tags from a Video
     *
     * @param int $id Id of the Video to get Tags from
     * @return array|bool Returns an array of Tag items, or false if error
     */
    static function get_tags($id){
        if(!$video = new ClipitVideo($id)){
            return false;
        }
        return $video->getItems(self::REL_TAG);
    }

}