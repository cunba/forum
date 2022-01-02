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
    require_once('../controllers/Category_controller_admin.php');

    if (isset($_POST['back'])) {
        header('Location:categories-view.php');
    }

    if (isset($_POST['create-category'])) {
        $category = $_POST['category'];
    }

    if (isset($_POST['update-category'])) {
        $id = $_POST['id'];
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
                    <input type="submit" name="back" value="ATRÁS">
                    <?php
                    include("../controllers/validate_admin.php");
                    ?>
                </form>
            </div>
            <?php
        } elseif (isset($_GET['update'])) {
            ?>
            <div class="form">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update') ?>" method="post">
                    <h2>Añadir nueva categoría</h2>
                    <input type="hidden" name="id" value="<?php echo $_SESSION['update_id']; ?>">
                    <input type="text" name="category"
                           value="<?php if (isset($category)) echo $category; else echo $_SESSION['update_category']; ?>">

                    <input type="submit" name="update-category" value="MODIFICAR">
                    <input type="submit" name="back" value="ATRÁS">
                    <?php
                    include("../controllers/validate_admin.php");
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
            $categories = Category_controller_admin::get_all();
            if (gettype($categories) == 'boolean') {
                ?>
                <div class="empty">
                    <p>No hay categorías para mostrar</p>
                </div>
                <?php
            } else {
                foreach ($categories as $category) {
                    if (isset($_GET['update-' . $category->id])) {
                        $_SESSION['update_id'] = $category->id;
                        $_SESSION['update_category'] = $category->category;
                        echo "<meta http-equiv='refresh' content='0; url=categories-view.php?update' >";
                    }

                    if (isset($_GET['delete-confirmation-' . $category->id])) {
                        Category_controller_admin::delete($category->id);
                        echo "<h3 class='success' style='padding-left: 8px; padding-bottom: 20px'>Comentario eliminado con éxito</h3>";
                        echo "<meta http-equiv='refresh' content='1.5; url=categories-view.php' >";
                    }
                    
                    $num_topics = Category_controller_admin::count_topics($category->id);
                    ?>
                    <div class="list-item">
                        <div class="list-information">
                            <h2><?php echo $category->category; ?></h2>
                            <p><?php echo "Contiene {$num_topics} temas" ?></p>
                        </div>
                        <div class="icons">
                            <a class="update-icon" href="categories-view.php?update-<?php echo $category->id ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a class="delete-icon" href="categories-view.php?delete-<?php echo $category->id ?>">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php
                    if (isset($_GET['delete-' . $category->id])) {
                        ?>
                        <div class="delete">
                            <p>¿Estás seguro que quieres eliminar la categoría, incluyendo sus temas y comentarios de
                                cada tema?</p>
                            <div class='buttons'>
                                <a class="yes"
                                   href="categories-view.php?delete-confirmation-<?php echo $category->id ?>">Sí</a>
                                <a class="no" href="categories-view.php">No</a>
                            </div>
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