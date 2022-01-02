<?php

require_once('../models/Comment.php');
require_once('../models/User.php');

class Comment_controller
{
    public function __construct()
    {
    }

    public static function get_by_topic($topic_id)
    {
        return Comment::get_by_topic($topic_id);
    }

    public static function create($comment)
    {
        return Comment::create($comment);
    }

    public static function delete($id)
    {
        Comment::delete($id);
    }

    public static function get_by_topic_and_user($topic_id, $user_id) {
        return Comment::get_by_topic_and_user($topic_id, $user_id);
    }
}