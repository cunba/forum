<?php

require_once('User_controller.php');

if (isset($_POST['submit-login'])) {
    if (empty($user)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
    } elseif ($user == 'admin' || $user == 'comments_admin') {
        echo "<p class='error'>* No se puede acceder con esa cuenta al foro</p>";
    } elseif (empty($password)) {
        echo "<p class='error'>* El campo contraseña no puede estar vacío</p>";
    } else {
        $response = User_controller::login($user, $password);

        if (gettype($response) == 'boolean') {
            echo "<p class='error'>* Usuario y/o contraseña incorrectos</p>";
        } else {
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $password;
            header('Location:home.php');
        }
    }
}

if (isset($_POST['create-comment'])) {
    if (empty($comment)) {
        echo "<p class='error'>* El campo comentario no puede estar vacío</p>";
    } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ!-?]{2,400}+$/", $comment)) {
        echo '<p class="error">* El campo comentario tiene un mínimo de 3 y</p><p class="error">máximo de 400 caracteres</p>';
    } elseif (empty($topic_id)) {
        echo "<p class='error'>* El campo tema no puede estar vacío</p>";
    } else {
        $comment = new Comment($comment, $topic_id);
        if (!Comment_controller::create($comment)) {
            echo '<p class="error">* No se ha podido crear el comentario, inténtelo de nuevo.</p>';
        } else {
            echo '<p class="success">Comentario creado con éxito.</p>';
        }
    }
}

if (isset($_POST['update-password'])) {
    if (empty($old_password) || empty($new_password) || empty($confirmation_password)) {
        echo "<p class='error'>* Los campos no pueden estar vacíos</p>";
    } elseif (!($_SESSION['password'] == $old_password)) {
        echo "<p class='error'>* Contraseña actual incorrecta</p>";
    } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ!-?]{4,20}+$/", $new_password)) {
        echo "<p class='error'>* La nueva contraseña debe tener entre 4 y 20 caracteres</p>";
    } elseif (!($new_password == $confirmation_password)) {
        echo "<p class='error'>* Las contraseñas nueva y de confirmación deben coincidir</p>";
    } elseif ($old_password == $new_password) {
        echo "<p class='error'>* Introduzca una contraseña diferente a la actual</p>";
    } else {
        if (User_controller::update_password($user->id, $new_password)) {
            echo '<p class="success">Contraseña modificada con éxito.</p>';
            $_SESSION['password'] = $new_password;
        } else {
            echo '<p class="error">* No se ha podido modificar la contraseña, inténtelo de nuevo.</p>';
        }
    }
}