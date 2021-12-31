<?php

require_once('../models/Comment_admin.php');

class Comment_controller_admin
{
    public function __construct()
    {
    }

    public static function get_by_topic($topic_id)
    {
        return Comment_admin::get_by_topic($topic_id);
    }

    public static function create($comment)
    {
        return Comment_admin::create($comment);
    }

    public static function delete($id)
    {
        Comment_admin::delete($id);
    }
}