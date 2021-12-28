<?php

require_once('../models/Topic.php');

class Topic_controller
{
    public function __construct()
    {
    }

    public static function get_by_category()
    {
        return Topic::get_all();
    }

    public static function count_topics($topic_id)
    {
        return Topic::count_comments($topic_id);
    }

    public static function update($topic)
    {
        return Topic::update($topic);
    }

    public static function create($topic)
    {
        return Topic::create($topic);
    }

    public static function delete($id)
    {
        Topic::delete($id);
    }
}