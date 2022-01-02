<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user'] == 'admin' || $_SESSION['user'] == 'comments_admin') {
        include('../controllers/logout.php');
    } else {
        header('Location:home.php');
    }
} else {

    if (isset($_POST['submit-login'])) {
        $user = $_POST['user'];
        $password = $_POST['password'];
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
                    <li><a href="categories.php">Categorías</a></li>
                    <li><a href="topics.php">Temas</a></li>
                    <?php
                    if (isset($_SESSION['user'])) {
                        ?>
                        <li><a href="comments.php">Tus comentarios</a></li>
                        <li><a href="user-panel.php">Panel de usuario</a></li>
                        <li><a href="../controllers/logout.php">Cerrar sesión</a></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="login.php">Iniciar sesión</a></li>
                        <li><a href="register.php">Registrarse</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </label>
        </nav>
        <h1>MERAKI</h1>
        <div class="login-register">
            <?php
            if (!(isset($_SESSION['user']))) {
                ?>
                <div class="login"><a href="login.php">Iniciar sesión</a></div>
                <div class="register"><a href="register.php">Registrarse</a></div>
                <?php
            }
            ?>
        </div>
    </header>
    <section class="first form">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <h1>Inicio de sesión</h1>
            <input type="text" name="user" value="<?php if (isset($user)) echo $user; ?>" placeholder="Usuario">
            <input type="password" name="password" value="<?php if (isset($password)) echo $password; ?>"
                   placeholder="Contraseña">

            <input type="submit" name="submit-login" value="ENTRAR">
            <?php
            include("../controllers/validate.php");
            ?>
        </form>
    </section>
    </body>
    </html>
    <?php
}
?>