<?php
require_once('../models/User_admin.php');

class User_controller_admin
{
    public function __construct()
    {

    }

    public static function login($user, $password)
    {
        $response = User_admin::get_user($user, $password);

        if (gettype($response) == 'boolean') {
            return false;
        } else {
            return $response;
        }
    }

    public static function get_user($user, $password)
    {
        return User_admin::get_user($user, $password);
    }

    public static function get_by_id($id)
    {
        return User_admin::get_by_id($id);
    }

    public static function get_all()
    {
        return User_admin::get_all();
    }

    public static function update_password($id, $password)
    {
        return User_admin::update_password($id, $password);
    }

    public static function delete($id)
    {
        return User_admin::delete($id);
    }

    public static function count_comments($user_id)
    {
        return User_admin::count_comments($user_id);
    }
}