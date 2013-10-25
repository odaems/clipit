<?php namespace clipit\user;

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

// Alias so classes outside of this namespace can be used without path.
use \ElggUser as ElggUser;

/**
 * Class ClipitUser
 *
 * @package clipit\user
 */
class ClipitUser{
    /**
     * @var int ClipitUser instance unique ID (-1 = unsaved)
     */
    public $id = -1;
    /**
     * @var string Login name used to authenticate
     */
    public $login = "";
    /**
     * @var string Login password (md5 of password + password_hash)
     */
    public $password = "";
    /**
     * @var string Random string to encode password
     */
    private $password_hash = "";
    /**
     * @var string Free text for user description (optional)
     */
    public $description = "";
    /**
     * @var string User email
     */
    public $email = "";
    /**
     * @var string Full user name
     */
    public $name = "";
    /**
     * @var string User role: student, teacher, admin
     */
    public $role = "user";
    /**
     * @var int Timestamp when the user was first saved
     */
    public $time_created = -1;

    /**
     * ClipitUser constructor function
     *
     * @param int|null $id If $id is 'null' then create new instance, else load instance with id = $id.
     */
    function __construct($id = null){
        if($id){
            $this->load($id);
        }
    }

    /**
     * Loads user from the system.
     *
     * @param int|null $id
     * @return $this|bool Returns the ClipitUser instance with id = $id. Returns false in case of error.
     */
    function load($id = null){
        $elgg_user = null;
        if($id){
            $elgg_user = new ElggUser($id);
        }
        if(!$elgg_user){
            return false;
        }
        $this->description = $elgg_user->description;
        $this->email = $elgg_user->email;
        $this->name = $elgg_user->name;
        $this->id = $elgg_user->guid;
        $this->login = $elgg_user->username;
        $this->password = $elgg_user->password;
        $this->password_hash = $elgg_user->salt;
        $this->role = $elgg_user->role;
        $this->time_created = $elgg_user->time_created;
        return $this;
    }

    /**
     * Saves user to the system.
     *
     * @return bool|int Returns new user id
     */
    function save(){
        if($this->id == -1){
            $elgg_user = new ElggUser();
            $this->id = $elgg_user->save();
        } else{
            $elgg_user = new ElggUser($this->id);
        }
        if(!$elgg_user){
            return false;
        }
        $elgg_user->description = $this->description;
        $elgg_user->email = $this->email;
        $elgg_user->name = $this->name;
        $elgg_user->username = $this->login;
        $elgg_user->password = $this->password;
        $elgg_user->salt = $this->password_hash;
        $elgg_user->role = $this->role;
        return $elgg_user->save();
    }

    /**
     * Deletes a user from the system.
     *
     * @return bool 'true' if success, 'false' if error.
     */
    function delete(){
        $elgg_user = get_user($this->id);
        if(!$elgg_user){
            return false;
        }
        return $elgg_user->delete();
    }

    /**
     * Creates an encoded user password using a random hash for encoding.
     *
     * @param string $password The new user password in clear text.
     * @return bool 'true' if success, 'false' if error.
     */
    function setPassword($password){
        if(!$password){
            return false;
        }
        $this->password_hash = generate_random_cleartext_password();
        $this->password = md5($password.$this->password_hash);
        return true;
    }
}