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
 * Class ClipitEvent
 *
 * @package clipit
 */
class ClipitEvent extends UBEvent{

    static function get_recommended_events($user_id, $offset = 0, $limit = 10){
        $user_groups = ClipitUser::get_groups($user_id);
        $user_activities = array();
        foreach($user_groups as $group){
            if($activity_id = ClipitGroup::get_activity($group)){
                $user_activities[] = $activity_id;
            }
        }
        $object_array = array_merge($user_groups, $user_activities);
        return ClipitEvent::get_by_object($object_array, $offset, $limit);
    }
} 