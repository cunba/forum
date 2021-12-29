<?php
session_start();

require_once("../controllers/Category_controller.php");

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] == 'comments_admin') {
    header('Location:comments-view.php');
} elseif ($_SESSION['user'] != 'admin') {
    session_destroy();
    header('Location:login.php');
} else {
    require_once('../controllers/Category_controller.php');

    if (isset($_POST['atras'])) {
        header('Location:categories-view.php');
    }

    if (isset($_POST['create-category']) || isset($_POST['update-category'])) {
        $id = $_POST['id'];
        $category = $_POST['category'];
    }

    if (isset($_POST['update'])) {
        $category_update_id = $_POST['category_update_id'];
        $category_update_category = $_POST['category_update_category'];
    }

    if (isset($_POST['delete'])) {
        $category_delete_id = $_POST['category_delete_id'];
        $category_delete_category = $_POST['category_delete_category'];
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
        <div>

        </div>
    </header>
    <section class="first list">
        <h1>Categorías</h1>
        <?php
        if (isset($_GET['create'])) {
            ?>
            <div class="form">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create') ?>" method="post">
                    <h2>Añadir nueva categoría</h2>
                    <input type="text" name="category" value="<?php if (isset($category)) echo $category; ?>"
                           placeholder="Categoría">

                    <input type="submit" name="create-category" value="AÑADIR">
                    <input type="submit" name="atras" value="ATRÁS">
                    <?php
                    include("../controllers/validate.php");
                    ?>
                </form>
            </div>
            <?php
        } elseif (isset($_GET['update'])) {
            ?>
            <div class="form">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update') ?>" method="post">
                    <h2>Añadir nueva categoría</h2>
                    <input type="hidden" name="id" value="<?php echo $category_update_id; ?>">
                    <input type="text" name="category"
                           value="<?php if (isset($category)) echo $category; else echo $category_update_category; ?>">

                    <input type="submit" name="update-category" value="MODIFICAR">
                    <input type="submit" name="atras" value="ATRÁS">
                    <?php
                    include("../controllers/validate.php");
                    ?>
                </form>
            </div>
            <?php
        } else {
            ?>
            <div class="new">
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create'); ?>">Añadir</a>
            </div>
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
                    ?>
                    <div class="list-item">
                        <div class="list-information">
                            <h2><?php echo $category->category; ?></h2>
                            <p><?php echo "Contiene {$num_topics} temas" ?></p>
                        </div>
                        <div class="icons">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update') ?>"
                                  method="post">
                                <input type="hidden" name="category_update_id" value="<?php echo $category->id; ?>">
                                <input type="hidden" name="category_update_category"
                                       value="<?php echo $category->category; ?>">

                                <input type="submit" name="update" value="MODIFICAR">
                            </form>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete') ?>"
                                  method="post">
                                <input type="hidden" name="category_delete_id" value="<?php echo $category->id; ?>">
                                <input type="hidden" name="category_delete_category"
                                       value="<?php echo $category->category; ?>">

                                <input type="submit" name="delete" value="ELIMINAR">
                            </form>
                        </div>
                    </div>
                    <?php
                    if (isset($_GET['delete']) && $category_delete_id == $category->id) {
                        ?>
                        <div class="delete">
                            <p>¿Estás seguro que quieres eliminar la categoría <?php echo $category_delete_category; ?>,
                                incluyendo sus temas y comentarios?</p>
                            <a href="<?php Category_controller::delete($category_delete_id); ?>" class="yes">Sí</a>
                            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . ''); ?>" class="no">No</a>
                        </div>
                        <?php
                    }
                }
            }
        }
        ?>
    </section>
    </body>
    </html>
    <?php
}
?>