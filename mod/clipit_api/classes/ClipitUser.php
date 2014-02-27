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
     * @const Relationship name for Users belonging to a Group
     */
    const REL_GROUP = "group-user";
    /**
     * @const Role name for Students
     */
    const ROLE_STUDENT = "student";
    /**
     * @const Role name for Teachers
     */
    const ROLE_TEACHER = "teacher";
    /**
     * @const Role name for Administrators
     */
    const ROLE_ADMIN = "admin";

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
            if($rel->relationship == self::REL_GROUP){
                $group_ids[] = (int) $rel->guid_one;
            }
        }
        return $group_ids;
    }

    /**
     * Sets a User role to Student.
     *
     * @param int $id User Id.
     * @return int Returns the User Id if set correctly.
     */
    static function set_role_student($id){
        $user = new ClipitUser($id);
        remove_user_admin($id);
        $prop_value_array["role"] = self::ROLE_STUDENT;
        return $user->setProperties($prop_value_array);
    }

    /**
     * Sets a User role to Teacher.
     *
     * @param int $id User Id.
     * @return int Returns the User Id if set correctly.
     */
    static function set_role_teacher($id){
        $user = new ClipitUser($id);
        make_user_admin($id);
        $prop_value_array["role"] = self::ROLE_TEACHER;
        return $user->setProperties($prop_value_array);
    }

    /**
     * Sets a User role to Admin.
     *
     * @param int $id User Id.
     * @return int Returns the User Id if set correctly.
     */
    static function set_role_admin($id){
        $user = new ClipitUser($id);
        make_user_admin($id);
        $prop_value_array["role"] = self::ROLE_ADMIN;
        return $user->setProperties($prop_value_array);
    }

}