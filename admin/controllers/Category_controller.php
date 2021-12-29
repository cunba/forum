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

    public static function update($id, $category)
    {
        return Category::update($id, $category);
    }

    public static function create($category)
    {
        return Category::create($category);
    }

    public static function delete($id)
    {
        $topics = Topic::get_by_category($id);
        foreach($topics as $topic) {
            Comment::delete($topic->id);
        }
        Category::delete($id);
    }
}