<?php
/**
 * Created by PhpStorm.
 * User: Pablo Llinás
 * Date: 10/02/14
 * Time: 14:03
 */

class ClipitAuth {

    function get_token($username, $password, $timeout = 60) {
        if (true === elgg_authenticate($username, $password)) {
            $token = create_user_token($username, $timeout);
            if ($token) {
                return $token;
            }
        }
        throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }

    function remove_token($token){
        return remove_user_token($token, null);
    }
}