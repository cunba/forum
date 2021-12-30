<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] == 'comments_admin') {
    header('Location:comments-view.php');
} elseif ($_SESSION['user'] != 'admin') {
    session_destroy();
    header('Location:login.php');
} else {

    require_once("../controllers/Category_controller.php");
    require_once("../controllers/Topic_controller.php");

    if (isset($_POST['atras'])) {
        header('Location:topics-view.php');
    }

    if (isset($_POST['create-topic'])) {
        $topic = $_POST['topic'];
        $category_id = $_POST['category_id'];
        $_SESSION['category_id_selected'] = $category_id;
    }

    if (isset($_POST['update-topic'])) {
        $id = $_POST['id'];
        $topic = $_POST['topic'];
        $category_id = $_POST['category_id'];
    }

    if (isset($_POST['update'])) {
        $_SESSION['category_id_selected'] = $_POST['selected_category_id'];
        $topic_update_id = $_POST['topic_update_id'];
        $topic_update_topic = $_POST['topic_update_topic'];

    }

    if (isset($_POST['delete-topic-form'])) {
        $_SESSION['category_id_selected'] = $_POST['selected_category_id'];
        $topic_delete_id = $_POST['topic_delete_id'];
        $topic_delete_topic = $_POST['topic_delete_topic'];
    }

    if (isset($_POST['selected'])) {
        $_SESSION['category_id_selected'] = $_POST['category_id_selected'];
    }

    if (isset($_POST['delete'])) {
        $topic_delete_id = $_POST['id'];
        Topic_controller::delete($topic_delete_id);
        header('Location:topics-view.php');
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
                    <li><a href="users-view.php">Usuarios</a></li>
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
            <h1>Categorías</h1>
            <?php
            $categories = Category_controller::get_all();
            if (gettype($categories) == 'boolean') {
                ?>
                <div class="empty">
                    <p>No hay categorías para mostrar</p>
                </div>
                <?php
            } else {
                foreach ($categories as $category) {
                    $num_topics = Category_controller::count_topics($category->id);

                    if ($_SESSION['category_id_selected'] == $category->id) {
                        ?>
                        <div class="left-list-item selected">
                            <h2><?php echo $category->category; ?></h2>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="left-list-item">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?selected') ?>"
                                  method="post">
                                <input type="hidden" name="category_id_selected" value="<?php echo $category->id; ?>"
                                       placeholder="Categoría">

                                <input type="submit" name="selected" value="">
                                <label><?php echo "<h3>{$category->category}</h3>"; ?></label>
                            </form>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
        <div class="right-list">
            <h1>Temas</h1>
            <?php
            if (isset($_GET['create'])) {
                ?>
                <div class="form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create') ?>" method="post">
                <h2>Añadir nuevo tema</h2>
                <input type="text" name="topic" value="<?php if (isset($topic)) echo $topic; ?>"
                       placeholder="Categoría">
                <select name="category_id">
                <?php
                $categories = Category_controller::get_all();
                if (gettype($categories) == 'boolean') {
                    ?>
                    <option value="">No hay categorías</option>
                    <?php
                } else {
                    foreach ($categories as $category) {
                        if ($category->id == $_SESSION['category_id_selected']) {
                            ?>
                            <option value="<?php echo $category->id ?>"><?php echo $category->category ?></option>
                            <?php
                        }
                    }
                    foreach ($categories as $category) {
                        if (!($category->id == $_SESSION['category_id_selected'])) {
                            ?>
                            <option value="<?php echo $category->id ?>"><?php echo $category->category ?></option>
                            <?php
                        }
                    }
                    ?>
                    </select>

                    <input type="submit" name="create-topic" value="AÑADIR">
                    <input type="submit" name="atras" value="ATRÁS">
                    <?php
                    include("../controllers/validate.php");
                    ?>
                    </form>
                    </div>
                    <?php
                }
            } elseif (isset($_GET['update'])) {
                ?>
                <div class="form">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update') ?>" method="post">
                        <h2>Añadir nueva categoría</h2>
                        <input type="hidden" name="id" value="<?php echo $topic_update_id; ?>">
                        <input type="text" name="topic"
                               value="<?php if (isset($topic)) echo $topic; else echo $topic_update_topic; ?>">
                        <select name="category_id">
                            <?php
                            $categories = Category_controller::get_all();
                            if (gettype($categories) == 'boolean') {
                                ?>
                                <option value="">No hay categorías</option>
                                <?php
                            } else {
                                foreach ($categories as $category) {
                                    if ($_SESSION['category_id_selected'] == $category->id) {
                                        ?>
                                        <option value="<?php echo $category->id ?>"><?php echo $category->category ?></option>
                                        <?php
                                    }
                                }
                                foreach ($categories as $category) {
                                    if (!($_SESSION['category_id_selected'] == $category->id)) {
                                        ?>
                                        <option value="<?php echo $category->id ?>"><?php echo $category->category ?></option>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </select>

                        <input type="submit" name="update-topic" value="MODIFICAR">
                        <input type="submit" name="atras" value="ATRÁS">
                        <?php
                        include("../controllers/validate.php");
                        ?>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <div class="new"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create'); ?>">Añadir</a>
                </div>
                <?php
                $topics = Topic_controller::get_by_category($_SESSION['category_id_selected']);
                if (gettype($topics) == 'boolean') {
                    ?>
                    <div class="empty">
                        <p>No hay categorías para mostrar</p>
                    </div>
                    <?php
                } else {
                    foreach ($topics as $topic) {
                        $num_topics = Topic_controller::count_comments($topic->id);
                        ?>
                        <div class="list-item">
                            <div class="list-information">
                                <h2><?php echo $topic->topic; ?></h2>
                                <p><?php echo "Contiene {$num_topics} comentarios" ?></p>
                            </div>
                            <div class="icons">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update') ?>"
                                      method="post">
                                    <input type="hidden" name="topic_update_id" value="<?php echo $topic->id; ?>">
                                    <input type="hidden" name="topic_update_topic"
                                           value="<?php echo $topic->topic; ?>">
                                    <input type="hidden" name="selected_category_id"
                                           value="<?php echo $_SESSION['category_id_selected'] ?>">

                                    <input type="submit" name="update" value="MODIFICAR">
                                </form>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete-topic-form') ?>"
                                      method="post">
                                    <input type="hidden" name="topic_delete_id" value="<?php echo $topic->id; ?>">
                                    <input type="hidden" name="topic_delete_topic"
                                           value="<?php echo $topic->topic; ?>">
                                    <input type="hidden" name="selected_category_id"
                                           value="<?php echo $_SESSION['category_id_selected'] ?>">

                                    <input type="submit" name="delete-topic-form" value="ELIMINAR">
                                </form>
                            </div>
                        </div>
                        <?php
                        if (isset($_GET['delete-topic-form']) && $topic_delete_id == $topic->id) {
                            ?>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete') ?>"
                                  method="post">
                                <div class="delete">
                                    <p>¿Estás seguro que quieres eliminar el tema <?php echo $topic->topic ?>,
                                        incluyendo los comentarios añadidos?</p>
                                    <input type="hidden" name="id" value="<?php echo $topic->id; ?>">

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