<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user'] == 'admin' || $_SESSION['user'] == 'comments_admin') {
        include('../controllers/logout.php');
    }
}

require_once("../controllers/Comment_controller.php");
require_once("../controllers/Topic_controller.php");
require_once("../controllers/Category_controller.php");
require_once("../controllers/User_controller.php");

if (isset($_POST['selected'])) {
    $_SESSION['topic_id_selected'] = $_POST['topic_id_selected'];
    $_SESSION['category_id_selected_topic'] = $_POST['category_id_selected_topic'];
}

if (isset($_POST['selected-category'])) {
    $_SESSION['category_id_selected'] = $_POST['category_id_selected'];
}

if (isset($_POST['create-comment'])) {
    $new_comment = $_POST['new_comment'];
    $topic_id = $_SESSION['topic_id_selected'];
    $user_id = $_SESSION['user_id'];
}

if (isset($_POST['delete-comment-form'])) {
    $comment_delete_id = $_POST['comment_delete_id'];
}

if (isset($_POST['delete'])) {
    $comment_delete_id = $_POST['id'];
    Comment_controller::delete($comment_delete_id);
    header('Location:home.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="../styles/style.css">
<head>
    <meta charset="UTF-8">
    <title>Foro</title>
</head>
<body>
<header class="header">
    <nav>
        <label for="check-menu">
            <input id="check-menu" type="checkbox">
            <div class="btn-menu">Menú</div>
            <ul class="ul-menu">
                <li><a href="home.php">Inicio</a></li>
                <li><a href="categories.php">Categorías</a></li>
                <li><a href="topics.php">Temas</a></li>
                <li><a href="comments.php">Comentarios</a></li>
                <?php
                if (isset($_SESSION['user'])) {
                    ?>
                    <li><a href="user-panel.php">Panel de usuario</a></li>
                    <li><a href="../controllers/logout.php">Cerrar sesión</a></li>
                    <?php
                } else {
                    ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="register.php">Registrarse</a></li>
                    <?php
                }
                ?>
            </ul>
        </label>
    </nav>
    <h1>MERAKI</h1>
    <div class="login-register">
        <?php
        if (!(isset($_SESSION['user']))) {
            ?>
            <div class="login"><a href="login.php">Iniciar sesión</a></div>
            <div class="register"><a href="register.php">Registrarse</a></div>
            <?php
        }
        ?>
    </div>
</header>
<section class="first left">
    <div class="left-list">
        <h1>Temas</h1>
        <?php
        $categories = Category_controller::get_all();
        if (gettype($categories) == 'boolean') {
            ?>
            <div class="empty comments">
                <p>No hay categorías para mostrar</p>
            </div>
            <?php
        } else {
            foreach ($categories as $category) {
                if (!($_SESSION['category_id_selected_topic'] == $category->id) && !($_SESSION['category_id_selected'] == $category->id)) {
                    ?>
                    <div class="left-list-item comments">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?selected-category') ?>"
                              method="post">
                            <input type="hidden" name="category_id_selected"
                                   value="<?php echo $category->id; ?>">

                            <input type="submit" name="selected-category" value="">
                            <label><?php echo "<h2>{$category->category} ▸</h2>"; ?></label>
                        </form>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="left-list-item comments">
                        <h2><?php echo $category->category; ?> ▾</h2>
                    </div>
                    <?php
                }
                if ($category->id == $_SESSION['category_id_selected'] || $category->id == $_SESSION['category_id_selected_topic']) {
                    $topics = Topic_controller::get_by_category($category->id);
                    if (gettype($topics) == 'boolean') {
                        ?>
                        <div class="empty">
                            <p>No hay temas para mostrar</p>
                        </div>
                        <?php
                    } else {
                        foreach ($topics as $topic) {
                            if ($_SESSION['topic_id_selected'] == $topic->id) {
                                ?>
                                <div class="subitem">
                                    <div class="left-list-item color selected">
                                        <p><?php echo $topic->topic; ?></p>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="subitem">
                                    <div class="left-list-item color">
                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?selected') ?>"
                                              method="post">
                                            <input type="hidden" name="topic_id_selected"
                                                   value="<?php echo $topic->id; ?>">
                                            <input type="hidden" name="category_id_selected_topic"
                                                   value="<?php echo $category->id; ?>">

                                            <input type="submit" name="selected" value="">
                                            <label><?php echo "<p>{$topic->topic}</p>"; ?></label>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                }
            }
        }
        ?>
    </div>
    <div class="right-list">
        <h1>Comentarios</h1>
        <?php
        $comments = Comment_controller::get_by_topic($_SESSION['topic_id_selected']);
        if (gettype($comments) == 'boolean') {
            ?>
            <div class="empty">
                <?php
                if (!isset($_SESSION['user'])) {
                    ?>
                    <p>No hay comentarios para mostrar</p>
                    <?php
                } else {
                    ?>
                    <p>¡Sé el primero en comentar!</p>
                    <?php
                }
                ?>
            </div>
            <?php
        } else {
            foreach ($comments as $comment) {
                $user = User_controller::get_by_id($comment->user_id);
                ?>
                <div class="list-item comment">
                    <div class="comment-header">
                        <h3><?php echo $user->user ?></h3>
                        <?php
                        if (isset($_SESSION['user'])) {
                            if ($_SESSION['user'] == $user->user) {
                                ?>
                                <div class="icons">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete-comment-form') ?>"
                                          method="post">
                                        <input type="hidden" name="comment_delete_id"
                                               value="<?php echo $comment->id; ?>">

                                        <input type="submit" name="delete-comment-form" value="ELIMINAR">
                                    </form>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <h4><?php echo date('H:i d/m/Y', strtotime($comment->creation_date)); ?></h4>
                    </div>
                    <p><?php echo $comment->comment; ?></p>
                </div>
                <?php
                if (isset($_GET['delete-comment-form']) && $comment_delete_id == $comment->id) {
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete') ?>"
                          method="post">
                        <div class="delete">
                            <p>¿Estás seguro que quieres eliminar el comentario?</p>
                            <input type="hidden" name="id" value="<?php echo $comment->id; ?>">

                            <div class='buttons'>
                                <input type="submit" name="delete" value="SÍ">
                                <input type="submit" name="back" value="NO">
                            </div>
                        </div>
                    </form>
                    <?php
                }
            }
        }
        if (isset($_SESSION['user'])) {
            ?>
            <div class="form-comment">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                      method="post">
                    <textarea type="text" name="new_comment" placeholder="Comentario"></textarea>

                    <input type="submit" name="create-comment" value="AÑADIR COMENTARIO">
                    <?php
                    include("../controllers/validate.php");
                    ?>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
</section>
</body>
</html>