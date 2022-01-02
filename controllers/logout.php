<?php
session_start();
session_destroy();

session_start();
$_SESSION['category_id_selected'] = 1;
$_SESSION['topic_id_selected'] = 1;
$_SESSION['category_id_selected_topic'] = 1;

header('Location:../views/home.php');