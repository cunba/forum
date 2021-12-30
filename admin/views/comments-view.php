<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] != 'admin' && $_SESSION['user'] != 'comments_admin') {
    session_destroy();
    header('Location:login.php');
} else {

    require_once("../controllers/Comment_controller.php");
    require_once("../controllers/Topic_controller.php");
    require_once("../controllers/Category_controller.php");
    require_once("../controllers/User_controller.php");

    $topic_id_selected = 1;

    if (isset($_POST['atras'])) {
        header('Location:comments-view.php');
    }

    if (isset($_POST['create-comment'])) {
        $comment = $_POST['comment'];
        $topic_id = $_POST['topic_id'];
        $topic_id_selected = $topic_id;
    }

    if (isset($_POST['delete-comment-form'])) {
        $topic_id_selected = $_POST['selected_topic_id'];
        $comment_delete_id = $_POST['comment_delete_id'];
        $comment_delete_comment = $_POST['comment_delete_comment'];
    }

    if (isset($_POST['selected'])) {
        $topic_id_selected = $_POST['topic_id_selected'];
    }

    if (isset($_POST['delete'])) {
        $comment_delete_id = $_POST['id'];
        Comment_controller::delete($comment_delete_id);
        header('Location:comments-view.php');
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
                            if ($topic_id_selected == $topic->id) {
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
                                    <div class="left-list-item">
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
            if (isset($_GET['create'])) {
                ?>
                <div class="form">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create') ?>"
                          method="post">
                        <h2>Añadir nuevo comentario</h2>
                        <textarea type="text" name="comment" placeholder="Comentario"></textarea>
                        <select name="topic_id">
                            <?php
                            $topics = Topic_controller::get_all();
                            if (gettype($topics) == 'boolean') {
                                ?>
                                <option value="">No hay categorías</option>
                                <?php
                            } else {
                                foreach ($topics as $topic) {
                                    ?>
                                    <option value="<?php echo $topic->id ?>"><?php echo $topic->topic ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

                        <input type="submit" name="create-comment" value="AÑADIR">
                        <input type="submit" name="atras" value="ATRÁS">
                        <?php
                        include("../controllers/validate.php");
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
                $comments = Comment_controller::get_by_topic($topic_id_selected);
                if (gettype($comments) == 'boolean') {
                    ?>
                    <div class="empty">
                        <p>No hay comentarios para mostrar</p>
                    </div>
                    <?php
                } else {
                    foreach ($comments as $comment) {
                        $user = User_controller::get_by_id($comment->user_id);
                        ?>
                        <div class="list-item comment">
                            <div class="comment-header">
                                <h3><?php echo $user->user ?></h3>
                                <div class="icons">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete-comment-form') ?>"
                                          method="post">
                                        <input type="hidden" name="comment_delete_id"
                                               value="<?php echo $comment->id; ?>">
                                        <input type="hidden" name="comment_delete_comment"
                                               value="<?php echo $comment->comment; ?>">
                                        <input type="hidden" name="selected_topic_id"
                                               value="<?php echo $topic_id_selected ?>">

                                        <input type="submit" name="delete-comment-form" value="ELIMINAR">
                                    </form>
                                </div>
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
            }
            ?>
        </div>
    </section>
    </body>
    </html>
    <?php
}
?>