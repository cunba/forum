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

    public static function get_user($user, $password)
    {
        return User::get_user($user, $password);
    }

    public static function get_by_id($id)
    {
        return User::get_by_id($id);
    }

    public static function get_by_user($user)
    {
        return User::get_by_user($user);
    }

    public static function get_id_by_user($user)
    {
        return User::get_id_by_user($user);
    }

    public static function get_all()
    {
        return User::get_all();
    }

    public static function create($user)
    {
        return User::create($user);
    }

    public static function update_password($id, $password)
    {
        return User::update_password($id, $password);
    }

    public static function delete($id)
    {
        return User::delete($id);
    }

    public static function count_comments($user_id)
    {
        return User::count_comments($user_id);
    }

    public static function update($user)
    {
        return User::update($user);
    }
}