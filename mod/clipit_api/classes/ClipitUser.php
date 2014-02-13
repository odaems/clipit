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
 * Class ClipitUser
 *
 * @package clipit
 */
class ClipitUser extends UBUser{

    /**
     * Get all Group Ids in which a user is member of.
     *
     * @param int $id Id of the user to get groups from.
     * @return array Returns an array of Group Ids the user is member of.
     */
    static function get_groups($id){
        $rel_array = get_entity_relationships($id, true);
        $group_ids = array();
        foreach($rel_array as $rel){
            if($rel->relationship == "group_contains"){
                $group_ids[] = (int) $rel->guid_one;
            }
        }
        return $group_ids;
    }

}