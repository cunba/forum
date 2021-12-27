<?php
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
<header class="header-login">
    <h1>ACCESO A FUNCIONES DE ADMINISTRADOR</h1>
</header>
<section class="first form">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <h1>Login</h1>
        <input type="text" name="user" value="<?php if (isset($user)) echo $user; ?>" placeholder="Usuario">
        <input type="password" name="password" value="<?php if (isset($password)) echo $password; ?>"
               placeholder="Contraseña">

        <input type="submit" name="submit-login" value="Iniciar sesión">
        <?php
        include("../controllers/validate.php");
        ?>
    </form>
</section>
</body>
</html>
