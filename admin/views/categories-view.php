<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] == 'comments-admin') {
    header('Location:comments-view.php');
} elseif ($_SESSION['user'] != 'admin') {
    header('Location:login.php');
} else {
    require_once('../controllers/Category_controller.php');

    if (isset($_POST['create-category']) || isset($_POST['update-category'])) {
        $category = $_POST['category'];
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
    <section class="first categories">
        <h1>Categorías</h1>
        <?php
        if (isset($_GET['create'])) {
            ?>
            <div class="form">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create') ?>" method="post">
                    <h2>Añadir nueva categoría</h2>
                    <input type="text" name="category" value="<?php if (isset($category)) echo $category; ?>"
                           placeholder="Categoría">

                    <input type="submit" name="create-category" value="Añadir">
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
                    <input type="text" name="category" value="<?php if (isset($category)) echo $category; ?>"
                           placeholder="Categoría">

                    <input type="submit" name="update-category" value="Modificar">
                    <?php
                    include("../controllers/validate.php");
                    ?>
                </form>
            </div>
            <?php
        } elseif (isset($_GET['delete'])) {
            ?>
            <?php
        } else {
            ?>
            <div class="new"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?create'); ?>">Añadir</a>
            </div>
            <?php
            $categories = Category_controller::get_all_categories();
            if (empty($categories)) {
                ?>
                <div class="empty">
                    <p>No hay categorías para mostrar</p>
                </div>
                <?php
            } else {
                foreach ($categories as $category) {
                    $num_topics = Category_controller::count_topics($category->id);
                    ?>
                    <div class="list">
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

                                <i class="fas fa-edit"><input type="submit" name="update" hidden="hidden"></i>
                            </form>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete') ?>"
                                  method="post">
                                <input type="hidden" name="category_delete_id" value="<?php echo $category->id; ?>">
                                <input type="hidden" name="category_delete_category"
                                       value="<?php echo $category->category; ?>">

                                <i class="fas fa-trash-alt"><input type="submit" name="delete" value="" hidden="hidden"></i>
                            </form>
                        </div>
                    </div>
                    <?php
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