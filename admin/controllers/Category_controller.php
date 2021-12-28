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

    public static function count_topics($category_id)
    {
        return Category::count_topics($category_id);
    }

    public static function update_category($id, $category)
    {
        return Category::update_category($id, $category);
    }

    public static function create_category($category)
    {
        return Category::create_category($category);
    }

    public static function delete_category($id)
    {
        Category::delete_category($id);
        header('Location:../views/categories-view.php');
    }
}