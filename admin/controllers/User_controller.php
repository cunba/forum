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

    public static function get_by_id($id)
    {
        return User::get_by_id($id);
    }

    public static function get_all()
    {
        return User::get_all();
    }

    public static function update_password($user)
    {
        return User::update_password($user);
    }

    public static function delete($id)
    {
        return User::delete($id);
    }

    public static function count_comments($user_id)
    {
        return USer::count_comments($user_id);
    }
}