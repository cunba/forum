<?php
require_once('../models/User.php');

class User_controller
{
    public function __construct()
    {

    }

    public static function login($user, $password)
    {
        $response = User::get_user($user, $password);

        if (gettype($response) == 'boolean') {
            return false;
        } else {
            return $response;
        }
    }

    public static function get_users()
    {
        return User::get_all_users();
    }

    public static function update_password($user)
    {
        return User::update_password($user);
    }

    public static function delete_user($id)
    {
        return User::delete_user($id);
    }
}