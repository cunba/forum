<?php

require_once('User_controller.php');
require_once('../models/User.php');

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
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $password;
            $_SESSION['user_id'] = $response->id;
            header('Location:home.php');
        }
    }
}

$validate = false;
if (isset($_POST['submit-register'])) {
    if (empty($user)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
    } elseif (User_controller::get_by_user($user)) {
        echo "<p class='error'>* El usuario ya existe</p>";
    } elseif (!preg_match('/^[a-zñÑ.-_]{3,20}+$/', $user)) {
        echo "<p class='error'>* El campo usuario sólo puede contener letras, números y caracterees como . y _ (mñinimo 3, máximo 20)</p>";
    } else {
        $validate = true;
    }

    if (empty($name)) {
        echo "<p class='error'>* El campo nombre no puede estar vacío</p>";
        $validate = false;
    } elseif (!preg_match('/^[A-Za-zäÄëËïÏöÖüÜáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{3,50}+$/', $name)) {
        echo "<p class='error'>* El campo nombre sólo puede contener letras (mñinimo 3, máximo 50)</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }

    if (empty($surname)) {
        echo "<p class='error'>* El campo apellido no puede estar vacío</p>";
        $validate = false;
    } elseif (!preg_match('/^[A-Za-zäÄëËïÏöÖüÜáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{3,50}+$/', $surname)) {
        echo "<p class='error'>* El campo apellido sólo puede contener letras (mñinimo 3, máximo 50)</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }

    if (empty($email)) {
        echo "<p class='error'>* El campo correo no puede estar vacío</p>";
        $validate = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error'>* Debes introducir un correo válido</p>";
        $validate = false;
    } elseif (strlen($email) > 100) {
        echo "<p class='error'>* Debes introducir un correo válido</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }

    if (empty($password)) {
        echo "<p class='error'>* El campo contraseña no puede estar vacío</p>";
        $validate = false;
    } elseif (!preg_match("/^[ñÑ!-¡]{4,20}+$/", $password)) {
        echo "<p class='error'>* La contraseña debe tener entre 4 y 20 caracteres</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }

    if (empty($confirmation_password)) {
        echo "<p class='error'>* Debe repetir la contraseña</p>";
        $validate = false;
    } elseif (!($password == $confirmation_password)) {
        echo "<p class='error'>* Las contraseñas no coinciden</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }
    if (empty($birthday)) {
        echo "<p class='error'>* El campo fecha de nacimiento no puede estar vacío</p>";
        $validate = false;
    }

    if ($validate) {
        $new_user = new User($user, $name, $surname, $birthday, $email, $password);
        $response = User_controller::create($new_user);

        if (!$response) {
            echo "<p class='error'>* Ha ocurrido un error, inténtelo más tarde</p>";
        } else {
            $response = User_controller::login($user, $password);
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $password;
            $_SESSION['user_id'] = $response->id;
            echo "<p class='success'>Usuario registrado con éxito</p>";
            echo "<meta http-equiv='refresh' content='2; url=home.php' >";
        }
    }
}

if (isset($_POST['create-comment'])) {
    if (empty($new_comment)) {
        echo "<p class='error'>* El campo comentario no puede estar vacío</p>";
    } elseif (strlen($new_comment) < 3 || strlen($new_comment) > 400) {
        echo '<p class="error">* El campo comentario tiene un mínimo de 3 y</p><p class="error">máximo de 400 caracteres</p>';
    } elseif (empty($topic_id)) {
        echo "<p class='error'>* El campo tema no puede estar vacío</p>";
    } elseif (empty($user_id)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
    } else {
        $new_comment = new Comment($new_comment, $topic_id, $user_id);
        if (!Comment_controller::create($new_comment)) {
            echo '<p class="error">* No se ha podido crear el comentario, inténtelo de nuevo</p>';
        } else {
            echo '<p class="success">Comentario creado con éxito</p>';
            echo "<meta http-equiv='refresh' content='1.5'>";
        }
    }
}

if (isset($_POST['update-password'])) {
    if (empty($old_password) || empty($new_password) || empty($confirmation_password)) {
        echo "<p class='error'>* Los campos no pueden estar vacíos</p>";
    } elseif (!($_SESSION['password'] == $old_password)) {
        echo "<p class='error'>* Contraseña actual incorrecta</p>";
    } elseif (!preg_match("/^[ñÑ!-¡]{4,20}+$/", $new_password)) {
        echo "<p class='error'>* La nueva contraseña debe tener entre 4 y 20 caracteres</p>";
    } elseif (!($new_password == $confirmation_password)) {
        echo "<p class='error'>* Las contraseñas nueva y de confirmación deben coincidir</p>";
    } elseif ($old_password == $new_password) {
        echo "<p class='error'>* Introduzca una contraseña diferente a la actual</p>";
    } else {
        if (User_controller::update_password($_SESSION['user_id'], $new_password)) {
            $_SESSION['password'] = $new_password;
            echo '<p class="success">Contraseña modificada con éxito.</p>';
            echo "<meta http-equiv='refresh' content='1.5; url=user-panel.php' >";
        } else {
            echo '<p class="error">* No se ha podido modificar la contraseña, inténtelo de nuevo.</p>';
        }
    }
}

$validate = false;
if (isset($_POST['update-user'])) {
    if (empty($username)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
        $validate = false;
    } elseif (!($username == $_SESSION['user']) && User_controller::get_by_user($username)) {
        echo "<p class='error'>* El usuario ya existe</p>";
        $validate = false;
    } elseif (!preg_match('/^[a-zñÑ.-_]{3,20}+$/', $username)) {
        echo "<p class='error'>* El campo usuario sólo puede contener letras, números y caracterees como . y _ (mñinimo 3, máximo 20)</p>";
        $validate = false;
    } else {
        $validate = true;
    }

    if (empty($name)) {
        echo "<p class='error'>* El campo nombre no puede estar vacío</p>";
        $validate = false;
    } elseif (!preg_match('/^[A-Za-zäÄëËïÏöÖüÜáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{3,50}+$/', $name)) {
        echo "<p class='error'>* El campo nombre sólo puede contener letras (mñinimo 3, máximo 50)</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }

    if (empty($surname)) {
        echo "<p class='error'>* El campo apellido no puede estar vacío</p>";
        $validate = false;
    } elseif (!preg_match('/^[A-Za-zäÄëËïÏöÖüÜáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{3,50}+$/', $surname)) {
        echo "<p class='error'>* El campo apellido sólo puede contener letras (mñinimo 3, máximo 50)</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }

    if (empty($email)) {
        echo "<p class='error'>* El campo correo no puede estar vacío</p>";
        $validate = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error'>* Debes introducir un correo válido</p>";
        $validate = false;
    } elseif (strlen($email) > 100) {
        echo "<p class='error'>* Debes introducir un correo válido</p>";
        $validate = false;
    } elseif ($validate) {
        $validate = true;
    }

    if ($validate) {
        $new_user = new User($username, $name, $surname, $birthday, $email, $_SESSION['password']);
        $new_user->set_id($_SESSION['user_id']);
        $response = User_controller::update($new_user);

        if (!$response) {
            echo "<p class='error'>* Ha ocurrido un error, inténtelo más tarde</p>";
        } else {
            $_SESSION['user'] = $username;
            echo "<p class='success'>Usuario modificado con éxito</p>";
            echo "<meta http-equiv='refresh' content='1.5; url=user-panel.php' >";
        }
    }
}
