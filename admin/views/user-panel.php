<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location:login.php');
} elseif ($_SESSION['user'] != 'admin' && $_SESSION['user'] != 'comments_admin') {
    session_destroy();
    header('Location:login.php');
} else {

    require_once('../controllers/User_controller.php');
    require_once('../models/User.php');

    $user = User_controller::get_user($_SESSION['user'], $_SESSION['password']);

    if (isset($_POST['back'])) {
        header('Location:user-panel.php');
    }

    if (isset($_POST['update-password'])) {
        $old_password = $_POST['old-password'];
        $new_password = $_POST['new-password'];
        $confirmation_password = $_POST['confirmation-password'];
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
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {
                        ?>
                        <li><a href="home.php">Inicio</a></li>
                        <li><a href="categories-view.php">Categorías</a></li>
                        <li><a href="topics-view.php">Temas</a></li>
                        <li><a href="users-view.php">Usuarios</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="comments-view.php">Comentarios</a></li>
                    <li><a href="user-panel.php">Panel de usuario</a></li>
                    <li><a href="../controllers/logout.php">Cerrar sesión</a></li>
                </ul>
            </label>
        </nav>
        <h1>MERAKI ADMIN</h1>
        <div></div>
    </header>
    <section class="first list information">
        <h1>Panel de usuario</h1>
        <div class="information-user-first information-user">
            <h3>Usuario:</h3>
            <p><?php echo $user->user ?></p>
        </div>
        <div class="information-user">
            <h3>Correo:</h3>
            <p><?php echo $user->email ?></p>
        </div>
        <div class="information-user">
            <h3>Contraseña:</h3>
            <p>***********</p>
        </div>
        <div class="information-user">
            <h3>Fecha de creación:</h3>
            <p><?php echo date('H:i d/m/Y', strtotime($user->creation_date)); ?></p>
        </div>
        <?php
        if (isset($_GET['update-password'])) {
            ?>
            <div class="form user">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update-password') ?>" method="post">
                    <input type="password" name="old-password"
                           value="<?php if (isset($old_password)) echo $old_password; ?>"
                           placeholder="Contraseña">
                    <input type="password" name="new-password"
                           value="<?php if (isset($new_password)) echo $new_password; ?>"
                           placeholder="Nueva contraseña">
                    <input type="password" name="confirmation-password"
                           value="<?php if (isset($confirmation_password)) echo $confirmation_password; ?>"
                           placeholder="Cofirmar contraseña">

                    <input type="submit" name="update-password" value="MODIFICAR CONTRASEÑA">
                    <input type="submit" name="back" value="ATRÁS">
                    <?php
                    include("../controllers/validate.php");
                    ?>
                </form>
            </div>
            <?php
        } else {
            ?>
            <div class="update">
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update-password') ?>">
                    Modificar contraseña
                </a>
            </div>
            <?php
        }
        ?>
    </section>
    </body>
    </html>
    <?php
}
?>
