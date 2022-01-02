<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] != 'admin' && $_SESSION['user'] != 'comments_admin') {
    session_destroy();
    header('Location:login.php');
} else {

    require_once("../controllers/Comment_controller_admin.php");
    require_once("../controllers/Topic_controller_admin.php");
    require_once("../controllers/Category_controller_admin.php");
    require_once("../controllers/User_controller_admin.php");

    if (isset($_POST['atras'])) {
        header('Location:comments-view.php');
    }

    if (isset($_POST['create-comment'])) {
        $comment = $_POST['comment'];
        $topic_id = $_POST['topic_id'];
        $_SESSION['topic_id_selected'] = $topic_id;
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
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {
                        ?>
                        <li><a href="home.php">Inicio</a></li>
                        <li><a href="categories-view.php">Categorías</a></li>
                        <li><a href="topics-view.php">Temas</a></li>
                        <li><a href="users-view.php">Usuarios</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="comments-view.php">Comentarios</a></li>
                    <li><a href="user-panel.php">Panel de usuario</a></li>
                    <li><a href="../controllers/logout.php">Cerrar sesión</a></li>
                </ul>
            </label>
        </nav>
        <h1>MERAKI ADMIN</h1>
        <div></div>
    </header>
    <section class="first left">
        <div class="left-list">
            <h1>Temas</h1>
            <?php
            $categories = Category_controller_admin::get_all();
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
                        <a class="left-list-item comments" href="comments-view.php?selected-category-<?php echo $category->id; ?>">
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
                        $topics = Topic_controller_admin::get_by_category($category->id);
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
                                    echo "<meta http-equiv='refresh' content='0; url=comments-view.php' >";
                                }
                                if ($_SESSION['topic_id_selected'] == $topic->id) {
                                    ?>
                                    <div class="subitem">
                                        <div class="left-list-item selected">
                                            <p><?php echo $topic->topic; ?></p>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="subitem">
                                        <a class="left-list-item color"
                                           href="comments-view.php?selected-topic-<?php echo $topic->id ?>">
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
            <h1>Comentarios</h1>
            <?php
            if (isset($_GET['create'])) {
                ?>
                <div class="form">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create') ?>"
                          method="post">
                        <h2>Añadir nuevo comentario</h2>
                        <textarea type="text" name="comment" placeholder="Comentario"></textarea>
                        <select name="topic_id">
                            <?php
                            $topics = Topic_controller_admin::get_all();
                            if (gettype($topics) == 'boolean') {
                                ?>
                                <option value="">No hay categorías</option>
                                <?php
                            } else {
                                foreach ($topics as $topic) {
                                    if ($_SESSION['topic_id_selected'] == $topic->id) {
                                        ?>
                                        <option value="<?php echo $topic->id ?>"><?php echo $topic->topic ?></option>
                                        <?php
                                    }
                                }
                                foreach ($topics as $topic) {
                                    if (!($_SESSION['topic_id_selected'] == $topic->id)) {
                                        ?>
                                        <option value="<?php echo $topic->id ?>"><?php echo $topic->topic ?></option>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </select>

                        <input type="submit" name="create-comment" value="AÑADIR">
                        <input type="submit" name="atras" value="ATRÁS">
                        <?php
                        include("../controllers/validate_admin.php");
                        ?>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <div class="new"><a
                            href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create'); ?>">Añadir</a>
                </div>
                <?php
                $comments = Comment_controller_admin::get_by_topic($_SESSION['topic_id_selected']);
                if (gettype($comments) == 'boolean') {
                    ?>
                    <div class="empty">
                        <p>No hay comentarios para mostrar</p>
                    </div>
                    <?php
                } else {
                    foreach ($comments as $comment) {
                        if (isset($_GET['delete-confirmation-' . $comment->id])) {
                            Comment_controller_admin::delete($comment->id);
                            echo "<h3 class='success' style='padding-left: 8px; padding-bottom: 20px'>Comentario eliminado con éxito</h3>";
                            echo "<meta http-equiv='refresh' content='1.5; url=comments-view.php' >";
                        }
                        $user = User_controller_admin::get_by_id($comment->user_id);
                        ?>
                        <div class="list-item comment">
                            <div class="comment-header">
                                <h3><?php echo $user->user ?></h3>
                                <a class="icons" href="comments-view.php?delete-<?php echo $comment->id ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
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
                                    <a class="yes" href="comments-view.php?delete-confirmation-<?php echo $comment->id ?>">Sí</a>
                                    <a class="no" href="comments-view.php">No</a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            }
            ?>
        </div>
    </section>
    </body>
    </html>
    <?php
}
?>