<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user'] == 'admin') {
        header('Location:views/home.php');
    } elseif ($_SESSION['user'] == 'comments_admin') {
        header('Location:views/comments-view.php');
    } else {
        session_destroy();
        header('Location:views/login.php');
    }
} else {
    header('Location:views/login.php');
}