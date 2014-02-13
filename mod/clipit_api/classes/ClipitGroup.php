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
 * Class ClipitGroup
 *
 * @package clipit
 */
class ClipitGroup extends UBCollection{
    /**
     * @const string Elgg entity subtype for this class
     */
    const SUBTYPE = "clipit_group";
    /**
     * @const string Details name for collection relationships.
     */
    const DEFAULT_RELATIONSHIP = "group_contains";

    /**
     * Add Users to a Group.
     *
     * @param int $id Id of the Group to add Users to.
     * @param array $user_array Array of User Ids to add to the Group.
     * @return bool Returns true if added correctly, or false if error.
     */
    static function add_users($id, $user_array){
        if(!$group = new ClipitGroup($id)){
            return false;
        }
        return $group->addItems($user_array);
    }
    /**
     * Remove Users from a Group.
     *
     * @param int $id Id of the Group to remove Users from.
     * @param array $user_array Array of User Ids to remove from the Group.
     * @return bool Returns true if removed correctly, or false if error.
     */
    static function remove_users($id, $user_array){
        if(!$group = new ClipitGroup($id)){
            return false;
        }
        return $group->removeItems($user_array);
    }
    /**
     * Get User Ids from a Group.
     *
     * @param int $id Id of the Group to get Users from.
     * @return bool Returns array of User Ids, or false if error.
     */
    static function get_users($id){
        if(!$group = new ClipitGroup($id)){
            return false;
        }
        return $group->getItems();
    }
}
