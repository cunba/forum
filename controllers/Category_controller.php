<?php

require_once('../models/Category.php');
require_once('../models/Topic.php');

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
}