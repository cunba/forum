<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] == 'admin' || $_SESSION['user'] == 'comments_admin') {
    include('../controllers/logout.php');
}

require_once('../controllers/User_controller.php');

$user = User_controller::get_user($_SESSION['user'], $_SESSION['password']);

if (isset($_POST['back'])) {
    header('Location:user-panel.php');
}

if (isset($_POST['update-password'])) {
    $old_password = $_POST['old-password'];
    $new_password = $_POST['new-password'];
    $confirmation_password = $_POST['confirmation-password'];
}

if (isset($_POST['update-user'])) {
    $user = $_POST['user'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];
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
    <div></div>
</header>
<section class="first list information">
    <h1>Panel de usuario</h1>
    <?php
    if (isset($_GET['update-user'])) {
        ?>
        <div class="form user">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <input type="text" name="user" value="<?php if (isset($user)) echo $user; else echo $user->user ?>">
                <input type="text" name="name" value="<?php if (isset($name)) echo $name; else echo $user->name; ?>">
                <input type="text" name="surname"
                       value="<?php if (isset($surname)) echo $surname; else echo $user->surname ?>">
                <input type="date" name="birthday"
                       value="<?php if (isset($birthday)) echo $birthday; else echo $user->birthday ?>">
                <input type="text" name="email" value="<?php if (isset($email)) echo $email; else echo $user->email ?>">

                <input type="submit" name="update-user" value="MODIFICAR USUARIO">
                <input type="submit" name="back" value="ATRÁS">
                <?php
                include("../controllers/validate.php");
                ?>
            </form>
        </div>
        <?php
    } else {
        ?>
        <div class="information-user-first information-user">
            <h3>Usuario:</h3>
            <p><?php echo $user->user ?></p>
        </div>
        <div class="information-user">
            <h3>Nombre:</h3>
            <p><?php echo $user->name ?></p>
        </div>
        <div class="information-user">
            <h3>Apellido:</h3>
            <p><?php echo $user->surname ?></p>
        </div>
        <div class="information-user">
            <h3>Fecha de nacimiento:</h3>
            <p><?php echo date('d/m/Y', strtotime($user->birthday)); ?></p>
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
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update-user') ?>">
                    Modificar usuario
                </a>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?update-password') ?>">
                    Modificar contraseña
                </a>
            </div>
            <?php
        }
    }
    ?>
</section>
</body>
</html>
