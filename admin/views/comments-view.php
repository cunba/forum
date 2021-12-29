<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] == 'comments-admin') {
    header('Location:comments-view.php');
} elseif ($_SESSION['user'] != 'admin') {
    header('Location:login.php');
} else {

    require_once("../controllers/Comment_controller.php");
    require_once("../controllers/Topic_controller.php");
    require_once("../controllers/Category_controller.php");

    $topic_id_selected = 1;

    if (isset($_POST['atras'])) {
        header('Location:comments-view.php');
    }

    if (isset($_POST['create-comment'])) {
        $comment = $_POST['comment'];
        $topic_id = $_POST['topic_id'];
        $topic_id_selected = $topic_id;
    }

    if (isset($_POST['delete'])) {
        $topic_id_selected = $_POST['selected_topic_id'];
        $comment_delete_id = $_POST['comment_delete_id'];
        $comment_delete_comment = $_POST['comment_delete_comment'];
    }

    if (isset($_POST['selected'])) {
        $topic_id_selected = $_POST['topic_id_selected'];
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
                    <li><a href="users-view.php">Usuarios</a></li>
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
                        <textarea type="text" name="comment" placeholder="Commentario">
                            <?php if (!empty($comment)) echo $comment; ?>
                        </textarea>
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
                        <p>No hay categorías para mostrar</p>
                    </div>
                    <?php
                } else {
                    foreach ($comments as $comment) {
                        ?>
                        <div class="list-item">
                            <div class="list-information">
                                <p><?php echo $comment->comment; ?></p>
                            </div>
                            <div class="icons">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete') ?>"
                                      method="post">
                                    <input type="hidden" name="comment_delete_id"
                                           value="<?php echo $comment->id; ?>">
                                    <input type="hidden" name="comment_delete_comment"
                                           value="<?php echo $comment->comment; ?>">
                                    <input type="hidden" name="selected_topic_id"
                                           value="<?php echo $topic_id_selected ?>">

                                    <input type="submit" name="delete" value="ELIMINAR">
                                </form>
                            </div>
                        </div>
                        <?php
                        if (isset($_GET['delete']) && $comment_delete_id == $comment->id) {
                            ?>
                            <div class="delete">
                                <p>¿Estás seguro que quieres eliminar el
                                    comentario?</p>
                                <a href="<?php Comment_controller::delete($comment_delete_id);
                                echo htmlspecialchars($_SERVER['PHP_SELF'] . ''); ?>"
                                   class="yes">Sí</a>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . ''); ?>"
                                   class="no">No</a>
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