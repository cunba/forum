<?php

session_start();
session_destroy();
session_start();

require_once('Category_controller.php');
require_once('Topic_controller.php');

$category = Category_controller::get_first();
$topic = Topic_controller::get_first($category->id);

$_SESSION['category_id_selected'] = $category->id;
$_SESSION['category_id_selected_topic'] = $category->id;
$_SESSION['topic_id_selected'] = $topic->id;

header('Location:../views/home.php');