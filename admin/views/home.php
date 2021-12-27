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
<section class="first home">
    <h1>Bienvenido a la vista de administrador</h1>
    <h2>Instrucciones:</h2>
    <p>Para acceder a la administración de las categorías, los temas y los comentarios pulsa arriba a la izquierda en
        Menú, aparecerá un menú desplegable con los capartados a los que puedes acceder.</p>
    <p>En el apartado de CATEGORÍAS se pueden crear nuevas, editar los nombres de las existentes y eliminarlas
        individualmente.</p>
    <p>En el apartado de TEMAS se pueden crear nuevos, editar los nombres y a la categoría a la que pertenecen y
        eliminarlos
        individualmente</p>
    <p>En el apartado de COMENTARIOS se pueden eliminar individualmente en caso de que incumplan las normas del foro</p>
    <p>En el apartado de PERFIL DE USUARIO aparecen los datos de la sesión y se permite cambiar la contrasña. No se
        recomienda su cambio.</p>
    <p>En caso de no querer realizar más cambios puedes cerrar la sesión, opción accesible desde cualquier pantalla
        abriendo el menú desplegable</p>
</section>
</body>
</html>
