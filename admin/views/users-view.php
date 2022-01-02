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
    require_once('../controllers/User_controller_admin.php');
    
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
        <h1>Usuarios</h1>
        <?php
        $users = User_controller_admin::get_all();
        if (gettype($users) == 'boolean') {
            ?>
            <div class="empty">
                <p>No hay usuarios para mostrar</p>
            </div>
            <?php
        } else {
            foreach ($users as $user) {
                if (isset($_GET['delete-confirmation-' . $user->id])) {
                    User_controller_admin::delete($user->id);
                    echo "<h3 class='success' style='padding-left: 8px; padding-bottom: 20px'>Usuario eliminado con éxito</h3>";
                    echo "<meta http-equiv='refresh' content='1.5; url=users-view.php' >";
                }

                $num_comments = User_controller_admin::count_comments($user->id);
                ?>
                <div class="list-item">
                    <div class="list-information">
                        <h2><?php echo $user->user; ?></h2>
                        <p><?php echo "Tiene {$num_comments} comentarios" ?></p>
                    </div>
                    <a class="icons" href="users-view.php?delete-<?php echo $user->id ?>">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
                <?php
                if (isset($_GET['delete-' . $user->id])) {
                    ?>
                    <div class="delete">
                        <p>¿Estás seguro que quieres eliminar el comentario?</p>
                        <div class='buttons'>
                            <a class="yes" href="users-view.php?delete-confirmation-<?php echo $user->id ?>">Sí</a>
                            <a class="no" href="users-view.php">No</a>
                        </div>
                    </div>
                    <?php
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