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
 * Class ClipitActivity
 *
 * @package clipit
 */
class ClipitActivity extends UBCollection{
    /**
     * @const string Elgg entity subtype for this class
     */
    const SUBTYPE = "clipit_activity";

    const GROUP_REL = "activity_group";

    static function add_groups($id, $group_array){
        if(!$activity = new ClipitActivity($id)){
            return false;
        }
        return $activity->addItems($group_array, self::GROUP_REL);
    }

    static function remove_groups($id, $group_array){
        if(!$activity = new ClipitActivity($id)){
            return false;
        }
        return $activity->removeItems($group_array, self::GROUP_REL);
    }

    static function get_groups($id){
        if(!$activity = new ClipitActivity($id)){
            return false;
        }
        return $activity->getItems(self::GROUP_REL);
    }

}