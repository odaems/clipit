<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 12/02/14
 * Time: 13:10
 */

class UBSite {
    static function api_list(){
        return list_all_apis();
    }
    static function get_token($username, $password, $timeout = 60) {
        if (true === elgg_authenticate($username, $password)) {
            $token = create_user_token($username, $timeout);
            if ($token) {
                return $token;
            }
        }
        throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }
    static function remove_token($token){
        return remove_user_token($token, null);
    }
    static function lookup($id){
        $elgg_object = new ElggObject((int)$id);
        $object['type'] = (string)$elgg_object->type;
        $object['subtype'] = (string)get_subtype_from_id($elgg_object->subtype);
        $object['name'] = (string)$elgg_object->name;
        $object['description'] = (string)$elgg_object->description;
        return $object;
    }
} 