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
}