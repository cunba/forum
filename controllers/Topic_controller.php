<?php

require_once('../models/Topic.php');

class Topic_controller
{
    public function __construct()
    {
    }

    public static function get_all()
    {
        return Topic::get_all();
    }

    public static function get_by_category($category_id)
    {
        return Topic::get_by_category($category_id);
    }

    public static function count_comments($topic_id)
    {
        return Topic::count_comments($topic_id);
    }

    public static function get_by_user($category_id, $user_id)
    {
        return Topic::get_by_user($category_id, $user_id);
    }

    public static function get_first($category_id)
    {
        return Topic::get_first($category_id);
    }
}