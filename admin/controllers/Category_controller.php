<?php

require_once('../models/Category.php');

class Category_controller
{
    public function __construct()
    {
    }

    public static function get_all_categories()
    {
        return Category::get_all_categories();
    }

    public static function count_topics($category_id) {
        return Category::count_topics($category_id);
    }
}