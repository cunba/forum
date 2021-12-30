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
    require_once('../controllers/User_controller.php');

    if (isset($_POST['atras'])) {
        header('Location:users-view.php');
    }

    if (isset($_POST['create-user']) || isset($_POST['update-user'])) {
        $id = $_POST['id'];
        $user = $_POST['user'];
    }

    if (isset($_POST['update'])) {
        $user_update_id = $_POST['user_update_id'];
        $user_update_user = $_POST['user_update_user'];
    }

    if (isset($_POST['delete-user-form'])) {
        $user_delete_id = $_POST['user_delete_id'];
        $user_delete_user = $_POST['user_delete_user'];
    }

    if (isset($_POST['delete'])) {
        $user_delete_id = $_POST['id'];
        User_controller::delete($user_delete_id);
        header('Location:users-view.php');
    }
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
        $users = User_controller::get_all();
        if (gettype($users) == 'boolean') {
            ?>
            <div class="empty">
                <p>No hay usuarios para mostrar</p>
            </div>
            <?php
        } else {
            foreach ($users as $user) {
                $num_comments = User_controller::count_comments($user->id);
                ?>
                <div class="list-item">
                    <div class="list-information">
                        <h2><?php echo $user->user; ?></h2>
                        <p><?php echo "Tiene {$num_comments} comentarios" ?></p>
                    </div>
                    <div class="icons">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete-user-form') ?>"
                              method="post">
                            <input type="hidden" name="user_delete_id" value="<?php echo $user->id; ?>">
                            <input type="hidden" name="user_delete_user"
                                   value="<?php echo $user->user; ?>">

                            <input type="submit" name="delete-user-form" value="ELIMINAR">
                        </form>
                    </div>
                </div>
                <?php
                if (isset($_GET['delete-user-form']) && $user_delete_id == $user->id) {
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?delete') ?>"
                          method="post">
                        <div class="delete">
                            <p>¿Estás seguro que quieres eliminar el usuario <?php echo $user->user ?>,
                                incluyendo los comentarios añadidos?</p>
                            <input type="hidden" name="id" value="<?php echo $user->id; ?>">

                            <div class='buttons'>
                                <input type="submit" name="delete" value="SÍ">
                                <input type="submit" name="back" value="NO">
                            </div>
                        </div>
                    </form>
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