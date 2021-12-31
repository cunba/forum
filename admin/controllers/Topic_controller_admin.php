<?php

require_once('../models/Topic_admin.php');
require_once('../models/Comment_admin.php');

class Topic_controller_admin
{
    public function __construct()
    {
    }

    public static function get_all()
    {
        return Topic_admin::get_all();
    }

    public static function get_by_category($category_id)
    {
        return Topic_admin::get_by_category($category_id);
    }

    public static function count_comments($topic_id)
    {
        return Topic_admin::count_comments($topic_id);
    }

    public static function update($topic)
    {
        return Topic_admin::update($topic);
    }

    public static function create($topic)
    {
        return Topic_admin::create($topic);
    }

    public static function delete($id)
    {
        $comments = Comment_admin::get_by_topic($id);
        foreach($comments as $comment) {
            Comment_admin::delete($comment->id);
        }
        Topic_admin::delete($id);
    }
}