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
 * Class ClipitFile
 *
 * @package clipit
 */
class ClipitFile extends UBFile{
    /**
     * @const string Elgg entity subtype for this class
     */
    const SUBTYPE = "clipit_file";
    /**
     * @var string File scope (STA, Group, Attachment)
     */
    public $scope = "";
    /**
     * @var int Id of linked object (Activity, Group, Video...)
     */
    public $linked_to = -1;
    /**
     * Loads an instance from the system.
     *
     * @param int $id ID of the instance to load.
     * @return UBFile|null Returns instance, or null if error.
     */
    protected function _load($id){
        if(!($elgg_file = new ElggFile((int)$id))){
            return null;
        }
        $this->id = (int)$elgg_file->guid;
        $this->type = (string)$elgg_file->type;
        $this->subtype = (string)get_subtype_from_id($elgg_file->subtype);
        $temp_name = explode($this::TIMESTAMP_DELIMITER, (string)$elgg_file->getFilename());
        if(empty($temp_name[1])){
            // no timestamp found
            $this->name = $temp_name[0];
        } else{
            $this->name = $temp_name[1];
        }
        $this->description = (string)$elgg_file->description;
        $this->data = $elgg_file->grabFile();
        $this->time_created = (int)$elgg_file->time_created;
        $this->scope = (string)$elgg_file->scope;
        $this->linked_to = (int)$elgg_file->linked_to;
        return $this;
    }

    /**
     * Saves this instance to the system.
     *
     * @return int|bool Returns id of saved instance, or false if error.
     */
    function save(){
        if($this->id == -1){
            $elgg_file = new ElggFile();
            $elgg_file->subtype = (string)$this::SUBTYPE;
        } elseif(!$elgg_file = new ElggFile((int)$this->id)){
            return false;
        }
        $date_obj = new DateTime();
        $elgg_file->setFilename((string)$date_obj->getTimestamp().$this::TIMESTAMP_DELIMITER.(string)$this->name);
        $elgg_file->description = (string)$this->description;
        $elgg_file->scope = (string)$this->scope;
        $elgg_file->linked_to = (int)$this->linked_to;
        $elgg_file->open("write");
        $elgg_file->write($this->data);
        $elgg_file->close();
        $elgg_file->save();
        $this->time_created = $elgg_file->time_created;
        $elgg_file->owner_guid = 0;
        $elgg_file->container_guid = 0;
        $elgg_file->access_id = ACCESS_PUBLIC;
        return $this->id = $elgg_file->guid;
    }

}
