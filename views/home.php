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
}

if (isset($_POST['create-comment'])) {
    $comment = $_POST['comment'];
    $topic_id = $_SESSION['topic_id_selected'];
    $user = User_controller::get_id_by_user($_SESSION['user']);
    $user_id = strval($user->id);
}
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
                <li><a href="categories-view.php">Categorías</a></li>
                <li><a href="topics-view.php">Temas</a></li>
                <li><a href="comments-view.php">Comentarios</a></li>
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
                ?>
                <div class="left-list-item comments">
                    <h2><?php echo $category->category; ?></h2>
                </div>
                <?php
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
                                               value="<?php echo $topic->id; ?>"
                                               placeholder="Categoría">

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
                                        <input type="hidden" name="comment_delete_comment"
                                               value="<?php echo $comment->comment; ?>">
                                        <input type="hidden" name="selected_topic_id"
                                               value="<?php echo $_SESSION['topic_id_selected'] ?>">

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
            }
        }
        if (isset($_SESSION['user'])) {
            ?>
            <div class="form-comment">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create-comment') ?>"
                      method="post">
                    <textarea type="text" id="textarea" name="comment" placeholder="Comentario"></textarea>

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