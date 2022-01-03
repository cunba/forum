<?php

require_once('User_controller_admin.php');
require_once('Category_controller_admin.php');
require_once('Topic_controller_admin.php');

if (isset($_POST['submit-login'])) {
    if (empty($user)) {
        echo "<p class='error'>* El campo usuario no puede estar vacío</p>";
    } elseif ($user == 'admin' || $user == 'comments_admin') {
        if (empty($password)) {
            echo "<p class='error'>* El campo contraseña no puede estar vacío</p>";
        } else {
            $response = User_controller_admin::login($user, $password);

            if (gettype($response) == 'boolean') {
                echo "<p class='error'>* Contraseña incorrecta</p>";
            } else {
                session_start();
                $_SESSION['user'] = $user;
                $_SESSION['password'] = $password;

                $category = Category_controller_admin::get_first();
                $topic = Topic_controller_admin::get_first($category->id);

                $_SESSION['category_id_selected'] = $category->id;
                $_SESSION['category_id_selected_topic'] = $category->id;
                $_SESSION['topic_id_selected'] = $topic->id;

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
    } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{3,100}+$/", $category)) {
        echo '<p class="error">* El campo categoría sólo permite letras y números (mínimo 3 y máximo 100 caracteres)</p>';
    } else {
        if (Category_controller_admin::create($category)) {
            echo '<p class="success">Categoría creada con éxito</p>';
            echo "<meta http-equiv='refresh' content='1.5; url=categories-view.php' >";
        } else {
            echo '<p class="error">* No se ha podido crear la categoría, inténtelo de nuevo.</p>';
        }
    }
} elseif (isset($_POST['update-category'])) {
    if (empty($category)) {
        echo "<p class='error'>* El campo categoría no puede estar vacío</p>";
    } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{3,100}+$/", $category)) {
        echo '<p class="error">* El campo categoría sólo permite letras y números (mínimo 3 y máximo 100 caracteres)</p>';
    } elseif (empty($id)) {

    } else {
        if (Category_controller_admin::update($id, $category)) {
            echo '<p class="success">Categoría modificada con éxito</p>';
            echo "<meta http-equiv='refresh' content='1.5; url=categories-view.php' >";
        } else {
            echo '<p class="error">* No se ha podido actualizar la categoría, inténtelo de nuevo.</p>';
        }
    }
}

if (isset($_POST['create-topic'])) {
    if (empty($topic)) {
        echo "<p class='error'>* El campo tema no puede estar vacío</p>";
    } elseif (!preg_match("/^[ A-Za-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{3,200}+$/", $topic)) {
        echo '<p class="error">* El campo tema sólo permite letras y números (mínimo 3 y máximo 200 caracteres)</p>';
    } elseif (empty($category_id)) {
        echo "<p class='error'>* El campo categoría no puede estar vacío</p>";
    } else {
        $topic = new Topic_admin($topic, $category_id);
        if (!Topic_controller_admin::create($topic)) {
            echo '<p class="error">* No se ha podido crear el tema, inténtelo de nuevo.</p>';
        } else {
            $_SESSION['category_id_selected'] = $category_id;
            echo '<p class="success">Tema creado con éxito</p>';
            echo "<meta http-equiv='refresh' content='1.5; url=topics-view.php' >";
        }
    }
} elseif (isset($_POST['update-topic'])) {
    if (empty($topic)) {
        echo "<p class='error'>* El campo tema no puede estar vacío</p>";
    } elseif (empty($category_id)) {
        echo "<p class='error'>* El campo categoría no puede estar vacío</p>";
    } else {
        $topic = new Topic_admin($topic, $category_id);
        $topic->set_id($id);
        if (Topic_controller_admin::update($topic)) {
            $_SESSION['category_id_selected'] = $category_id;

            echo '<p class="success">Tema modificado con éxito.</p>';
            echo "<meta http-equiv='refresh' content='1.5; url=topics-view.php' >";
        } else {
            echo '<p class="error">* No se ha podido crear el tema, inténtelo de nuevo.</p>';
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
        $new_comment = new Comment_admin($comment, $topic_id);
        if (!Comment_controller_admin::create($new_comment)) {
            echo '<p class="error">* No se ha podido crear el comentario, inténtelo de nuevo.</p>';
        } else {
            echo '<p class="success">Comentario creado con éxito.</p>';
            echo "<meta http-equiv='refresh' content='1.5; url=comments-view.php' >";
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
        if (User_controller_admin::update_password($user->id, $new_password)) {
            echo '<p class="success">Contraseña modificada con éxito.</p>';
            echo "<meta http-equiv='refresh' content='1.5; url=user-panel.php' >";
            $_SESSION['password'] = $new_password;
        } else {
            echo '<p class="error">* No se ha podido modificar la contraseña, inténtelo de nuevo.</p>';
        }
    }
}