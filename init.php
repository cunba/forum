<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user'] == 'admin' || $_SESSION['user'] == 'comments_admin') {
        session_destroy();
        header('Location:views/login.php');
    } else {
        header('Location:views/home.php');
    }
} else {
    header('Location:views/login.php');
}