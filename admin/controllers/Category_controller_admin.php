<?php

require_once('../models/Category_admin.php');
require_once('../models/Topic_admin.php');

class Category_controller_admin
{
    public function __construct()
    {
    }

    public static function get_all()
    {
        return Category_admin::get_all();
    }

    public static function get_first()
    {
        return Category_admin::get_first();
    }

    public static function count_topics($category_id)
    {
        return Category_admin::count_topics($category_id);
    }

    public static function update($id, $category)
    {
        return Category_admin::update($id, $category);
    }

    public static function create($category)
    {
        return Category_admin::create($category);
    }

    public static function delete($id)
    {
        $topics = Topic_admin::get_by_category($id);
        if (!(gettype($topics) == 'boolean')) {
            foreach ($topics as $topic) {
                Topic_admin::delete($topic->id);
            }
        }
        Category_admin::delete($id);
    }
}