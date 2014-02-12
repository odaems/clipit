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

    public $item_type = "user";
    public $item_subtype = "";

    static function add_users($id, $user_array){
        if(!$group = new ClipitGroup($id)){
            return false;
        }
        return $group->addItems($user_array);
    }

    static function remove_users($id, $user_array){
        if(!$group = new ClipitGroup($id)){
            return false;
        }
        return $group->removeItems($user_array);
    }

    static function get_users($id){
        if(!$group = new ClipitGroup($id)){
            return false;
        }
        return $group->getItems();
    }
}
