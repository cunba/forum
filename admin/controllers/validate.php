<?php

$validate = false;
$login = false;

if (isset($_POST['submit-login'])) {
    if (empty($user)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
        $validate = false;
    } elseif ($user != 'admin') {
        echo "<p class='error'>* No tienes permisos de acceso</p>";
    } else {
        $validate = true;
    }

    if (empty($password)) {
        echo "<p class='error'>* El campo contraseña no puede estar vacío</p>";
        $validate = false;
    } elseif ($password != 'admin') {
        echo "<p class='error'>* Contraseña incorrecta</p>";
    } else {
        $validate = true;
        $login = true;
    }
}

if ($validate && $login) {
    session_start();
    $_SESSION['user'] = $_POST['user'];
    header("Location:home.php");
}