<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] != 'admin') {
     header('Location:comments-view.php');
}
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

</section>
</body>
</html>