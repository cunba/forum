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

    require_once("../controllers/Category_controller_admin.php");
    require_once("../controllers/Topic_controller_admin.php");

    if (isset($_POST['atras'])) {
        header('Location:topics-view.php');
    }

    if (isset($_POST['create-topic'])) {
        $topic = $_POST['topic'];
        $category_id = $_POST['category_id'];
        $_SESSION['category_id_selected'] = $category_id;
        $_SESSION['category_id_selected_topic'] = $category_id;
    }

    if (isset($_POST['update-topic'])) {
        $id = $_POST['id'];
        $topic = $_POST['topic'];
        $category_id = $_POST['category_id'];
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
            $categories = Category_controller_admin::get_all();
            if (gettype($categories) == 'boolean') {
                ?>
                <div class="empty">
                    <p>No hay categorías para mostrar</p>
                </div>
                <?php
            } else {
                foreach ($categories as $category) {
                    if (isset($_GET['selected-' . $category->id])) {
                        $_SESSION['category_id_selected'] = $category->id;
                        echo "<meta http-equiv='refresh' content='0; url=topics-view.php' >";
                    }

                    if ($_SESSION['category_id_selected'] == $category->id) {
                        ?>
                        <div class="left-list-item selected">
                            <h2><?php echo $category->category; ?></h2>
                        </div>
                        <?php
                    } else {
                        ?>
                        <a class="left-list-item" href="topics-view.php?selected-<?php echo $category->id ?>">
                            <h3><?php echo $category->category ?></h3>
                        </a>
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
                $categories = Category_controller_admin::get_all();
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
                    include("../controllers/validate_admin.php");
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
                        <input type="hidden" name="id" value="<?php echo $_SESSION['update_id']; ?>">
                        <input type="text" name="topic"
                               value="<?php if (isset($topic)) echo $topic; else echo $_SESSION['update_topic']; ?>">
                        <select name="category_id">
                            <?php
                            $categories = Category_controller_admin::get_all();
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
                        include("../controllers/validate_admin.php");
                        ?>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <div class="new"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create'); ?>">Añadir</a>
                </div>
                <?php
                $topics = Topic_controller_admin::get_by_category($_SESSION['category_id_selected']);
                if (gettype($topics) == 'boolean') {
                    ?>
                    <div class="empty">
                        <p>No hay categorías para mostrar</p>
                    </div>
                    <?php
                } else {
                    foreach ($topics as $topic) {
                        if (isset($_GET['update-' . $topic->id])) {
                            $_SESSION['update_id'] = $topic->id;
                            $_SESSION['update_topic'] = $topic->topic;
                            echo "<meta http-equiv='refresh' content='0; url=topics-view.php?update' >";
                        }

                        if (isset($_GET['delete-confirmation-' . $topic->id])) {
                            $true = Topic_controller_admin::delete($topic->id);
                            if ($true) {
                                echo "<h3 class='success' style='padding-left: 8px; padding-bottom: 20px'>Tema eliminado con éxito</h3>";
                                echo "<meta http-equiv='refresh' content='1.5; url=topics-view.php' >";
                            }
                        }

                        $num_topics = Topic_controller_admin::count_comments($topic->id);
                        ?>
                        <div class="list-item">
                            <div class="list-information">
                                <h2><?php echo $topic->topic; ?></h2>
                                <p><?php echo "Contiene {$num_topics} comentarios" ?></p>
                            </div>
                            <div class="icons">
                                <a class="update-icon" href="topics-view.php?update-<?php echo $topic->id ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a class="delete-icon" href="topics-view.php?delete-<?php echo $topic->id ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                        if (isset($_GET['delete-' . $topic->id])) {
                            ?>
                            <div class="delete">
                                <p>¿Estás seguro que quieres eliminar el tema, incluyendo sus comentarios?</p>
                                <div class='buttons'>
                                    <a class="yes"
                                       href="topics-view.php?delete-confirmation-<?php echo $topic->id ?>">Sí</a>
                                    <a class="no" href="topics-view.php">No</a>
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