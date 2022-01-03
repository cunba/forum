<?php

require_once('../models/Category.php');

class Category_controller
{
    public function __construct()
    {
    }

    public static function get_all()
    {
        return Category::get_all();
    }

    public static function count_topics($category_id)
    {
        return Category::count_topics($category_id);
    }

    public static function get_by_user($user_id)
    {
        return Category::get_by_user($user_id);
    }

    public static function get_first()
    {
        return Category::get_first();
    }
}