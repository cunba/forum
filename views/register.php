<?php
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user'] == 'admin' || $_SESSION['user'] == 'comments_admin') {
        include('../controllers/logout.php');
    } else {
        header('Location:home.php');
    }
} else {

    if (isset($_POST['submit-register'])) {
        $user = $_POST['user'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmation_password = $_POST['confirmation-password'];
        $birthday = $_POST['birthday'];
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
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="register.php">Registrarse</a></li>
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
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?submit-register') ?>" method="post">
            <h1>Registro</h1>
            <input type="text" name="user" value="<?php if (isset($user)) echo $user; ?>" placeholder="Usuario">
            <input type="text" name="name" value="<?php if (isset($name)) echo $name; ?>" placeholder="Nombre">
            <input type="text" name="surname" value="<?php if (isset($surname)) echo $surname; ?>"
                   placeholder="Apellidos">
            <input type="date" name="birthday" value="<?php if (isset($birthday)) echo $birthday; ?>">
            <input type="text" name="email" value="<?php if (isset($email)) echo $email; ?>"
                   placeholder="Correo electrónico">
            <input type="password" name="password" value="<?php if (isset($password)) echo $password; ?>"
                   placeholder="Contraseña">
            <input type="password" name="confirmation-password"
                   value="<?php if (isset($confirmation_password)) echo $confirmation_password; ?>"
                   placeholder="Repite la contraseña">

            <input type="submit" name="submit-register" value="REGISTRARSE">
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