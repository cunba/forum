<?php

if (isset($_POST['submit-login'])) {
    if (empty($user)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
    } elseif ($user != 'admin') {
        echo "<p class='error'>* No tienes permisos de acceso</p>";
    }

    if (empty($password)) {
        echo "<p class='error'>* El campo contraseña no puede estar vacío</p>";
    } elseif ($password != 'admin') {
        echo "<p class='error'>* Contraseña incorrecta</p>";
    } else {
        session_start();
        $_SESSION['user'] = $_POST['user'];
        header("Location:home.php");
    }
}

if (isset($_POST['create-category'])) {
    if (empty($category)) {
        echo "<p class='error'>* El campo categoría no puede estar vacío</p>";
    } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{2,100}+$/", $category)) {
        echo '<p class="error">* El campo categoría sólo permite letras y números (mínimo 3 y máximo 100 caracteres)</p>';
    } else {
        header('Location:categories-view.php');
    }
} elseif (isset($_POST['update-category'])) {
    if (empty($category)) {
        echo "<p class='error'>* El campo categoría no puede estar vacío</p>";
    } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{2,100}+$/", $category)) {
        echo '<p class="error">* El campo categoría sólo permite letras y números (mínimo 3 y máximo 100 caracteres)</p>';
    } else {
        header('Location:categories-view.php');
    }
}