<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] == 'admin' || $_SESSION['user'] == 'comments_admin') {
    include('../controllers/logout.php');
}

require_once("../controllers/Comment_controller.php");
require_once("../controllers/Topic_controller.php");
require_once("../controllers/Category_controller.php");
require_once("../controllers/User_controller.php");

?>

<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="../styles/style.css">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/6d2edab8c4.js" crossorigin="anonymous"></script>
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
                <li><a href="comments.php">Tus comentarios</a></li>
                <li><a href="user-panel.php">Panel de usuario</a></li>
                <li><a href="../controllers/logout.php">Cerrar sesión</a></li>
            </ul>
        </label>
    </nav>
    <h1>MERAKI</h1>
    <div class="login-register">
    </div>
</header>
<section class="first left">
    <div class="left-list">
        <h1>Temas</h1>
        <?php
        $categories = Category_controller::get_by_user($_SESSION['user_id']);
        if (gettype($categories) == 'boolean') {
            ?>
            <div class="empty comments">
                <p>No hay categorías para mostrar</p>
            </div>
            <?php
        } else {
            foreach ($categories as $category) {
                if (isset($_GET['selected-category-' . $category->id])) {
                    $_SESSION['category_id_selected'] = $category->id;
                }
                if (!($_SESSION['category_id_selected_topic'] == $category->id) && !($_SESSION['category_id_selected'] == $category->id)) {
                    ?>
                    <a class="left-list-item comments"
                       href="comments.php?selected-category-<?php echo $category->id; ?>">
                        <h2><?php echo $category->category ?> ▸</h2>
                    </a>
                    <?php
                } else {
                    ?>
                    <div class="left-list-item comments">
                        <h2><?php echo $category->category; ?> ▾</h2>
                    </div>
                    <?php
                }
                if ($category->id == $_SESSION['category_id_selected'] || $category->id == $_SESSION['category_id_selected_topic']) {
                    $topics = Topic_controller::get_by_user($category->id, $_SESSION['user_id']);
                    if (gettype($topics) == 'boolean') {
                        ?>
                        <div class="empty">
                            <p>No hay temas para mostrar</p>
                        </div>
                        <?php
                    } else {
                        foreach ($topics as $topic) {
                            if (isset($_GET['selected-topic-' . $topic->id])) {
                                $_SESSION['topic_id_selected'] = $topic->id;
                                $_SESSION['category_id_selected_topic'] = $category->id;
                                echo "<meta http-equiv='refresh' content='0; url=comments.php' >";
                            }
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
                                    <a class="left-list-item color"
                                       href="comments.php?selected-topic-<?php echo $topic->id ?>">
                                        <p><?php echo $topic->topic ?></p>
                                    </a>
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
        <h1>Tus comentarios</h1>
        <?php
        $comments = Comment_controller::get_by_topic_and_user($_SESSION['topic_id_selected'], $_SESSION['user_id']);
        if (gettype($comments) == 'boolean') {
            ?>
            <div class="empty">
                <p>No hay comentarios para mostrar</p>
            </div>
            <?php
        } else {
            foreach ($comments as $comment) {
                if (isset($_GET['delete-confirmation-' . $comment->id])) {
                    Comment_controller::delete($comment->id);
                    echo "<h3 class='success' style='padding-left: 8px; padding-bottom: 20px'>Comentario eliminado con éxito</h3>";
                    echo "<meta http-equiv='refresh' content='1.5; url=comments.php' >";
                }

                $user = User_controller::get_by_id($comment->user_id);
                ?>
                <div class="list-item comment">
                    <div class="comment-header">
                        <h3><?php echo $user->user ?></h3>
                        <?php
                        if (isset($_SESSION['user'])) {
                            if ($_SESSION['user'] == $user->user) {
                                ?>
                                <a class="icons" href="comments.php?delete-<?php echo $comment->id ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                                <?php
                            }
                        }
                        ?>
                        <h4><?php echo date('H:i d/m/Y', strtotime($comment->creation_date)); ?></h4>
                    </div>
                    <p><?php echo $comment->comment; ?></p>
                </div>
                <?php
                if (isset($_GET['delete-' . $comment->id])) {
                    ?>
                    <div class="delete">
                        <p>¿Estás seguro que quieres eliminar el comentario?</p>
                        <div class='buttons'>
                            <a class="yes" href="comments.php?delete-confirmation-<?php echo $comment->id ?>">Sí</a>
                            <a class="no" href="comments.php">No</a>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</section>
</body>
</html>