<?php

require_once('User_controller.php');

if (isset($_POST['submit-login'])) {
    if (empty($user)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
    } elseif ($user == 'admin' || $user == 'comments_admin') {
        if (empty($password)) {
            echo "<p class='error'>* El campo contraseña no puede estar vacío</p>";
        } else {
            $response = User_controller::login($user, $password);

            if (gettype($response) == 'boolean') {
                echo "<p class='error'>* Contraseña incorrecta</p>";
            } else {
                session_start();
                $_SESSION['user'] = $_POST['user'];

                if ($user == 'admin') {
                    header("Location:home.php");
                } else {
                    header('Location:comments-view.php');
                }
            }
        }
    } else {
        echo "<p class='error'>* No tienes permisos de acceso </p>";
    }
}

    if (isset($_POST['create-category'])) {
        if (empty($category)) {
            echo "<p class='error'>* El campo categoría no puede estar vacío</p>";
        } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{2,100}+$/", $category)) {
            echo '<p class="error">* El campo categoría sólo permite letras y números (mínimo 3 y máximo 100 caracteres)</p>';
        } else {
            if (Category_controller::create($category)) {
                header('Location:categories-view.php');
            } else {
                echo '<p class="error">* No se ha podido crear la categoría, inténtelo de nuevo.</p>';
            }
        }
    } elseif (isset($_POST['update-category'])) {
        if (empty($category)) {
            echo "<p class='error'>* El campo categoría no puede estar vacío</p>";
        } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{2,100}+$/", $category)) {
            echo '<p class="error">* El campo categoría sólo permite letras y números (mínimo 3 y máximo 100 caracteres)</p>';
        } else {
            if (Category_controller::update($id, $category)) {
                header('Location:categories-view.php');
            } else {
                echo '<p class="error">* No se ha podido actualizar la categoría, inténtelo de nuevo.</p>';
            }
        }
    }