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

    $categories = Category_controller::get_all_categories();
    echo $categories;
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <link rel="stylesheet" href="../styles/style.css">
    <head>
        <meta charset="UTF-8">
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
                    <li><a href="user-panel.php">Panel de usuario</a></li>
                    <li><a href="../controllers/logout.php">Cerrar sesión</a></li>
                </ul>
            </label>
        </nav>
        <h1>MERAKI ADMIN</h1>
        <div></div>
    </header>
    <section class="first categories">
        <?php if (empty($categories)) { ?>
            <div class="empty">
                <p>No hay categorías para mostrar</p>
            </div>
        <?php } else { ?>
<!--            --><?php //foreach ($categories as $category) { ?>
<!--                <div class="category">-->
<!--                    <h3>--><?php //echo $category->category; ?><!--</h3>-->
<!--                </div>-->
<!--            --><?php //}
        } ?>
    </section>
    </body>
    </html>

<?php } ?>