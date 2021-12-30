<?php
session_start();

header('Location:views/home.php');

$_SESSION['category_id_selected'] = 1;
$_SESSION['topic_id_selected'] = 1;