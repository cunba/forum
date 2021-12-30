<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] != 'admin' && $_SESSION['user'] != 'comments_admin') {
    session_destroy();
    header('Location:login.php');
} elseif ($_SESSION['user'] == 'comments_admin') {
    header('Location:comments-view.php');
} else {
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
    <section class="first home">
        <h1>Bienvenido a la vista de administrador</h1>
        <a class="list-home" href="categories-view.php">
            <h2>Categorías</h2>
            <p>Haga click para ver, editar y eliminar las existentes y crear nuevas.</p>
        </a>
        <a class="list-home" href="topics-view.php">
            <h2>Temas</h2>
            <p>Haga click para ver, editar y eliminar los existentes y crear nuevos.</p>
        </a>
        <a class="list-home" href="users-view.php">
            <h2>Usuarios</h2>
            <p>Haga click para administrarlos y eliminar los que hayan incumplido las normas de Meraki</p>
        </a>
        <a class="list-home" href="comments-view.php">
            <h2>Comentarios</h2>
            <p>Haga click para supervisarlos y eliminar los que incumplan las normas de Meraki</p>
        </a>
        <a class="list-home" href="user-panel.php">
            <h2>Panel de usuario</h2>
            <p>Haga click para ver los detalles de usuario y cambiar la contraseña</p>
        </a>
        <a class="list-home logout" href="../controllers/logout.php">
            <h2>CERRAR SESIÓN</h2>
        </a>
    </section>
    </body>
    </html>
    <?php
}
?>