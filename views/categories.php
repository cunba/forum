<?php
session_start();

require_once('../controllers/Category_controller.php');

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
                <li><a href="comments.php">Comentarios</a></li>
                <?php
                if (isset($_SESSION['user'])) {
                    ?>
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
    <div>

    </div>
</header>
<section class="first list">
    <h1>Categorías</h1>
    <?php
    $categories = Category_controller::get_all();
    if (gettype($categories) == 'boolean') {
        ?>
        <div class="empty">
            <p>No hay categorías para mostrar</p>
        </div>
        <?php
    } else {
        foreach ($categories

                 as $category) {
            $num_topics = Category_controller::count_topics($category->id);
            ?>
            <div class="list-item">
                <a class="list-information a" href="categories.php?selected<?php echo $category->id ?>">
                    <h2><?php echo $category->category; ?></h2>
                    <p><?php echo "Contiene {$num_topics} temas" ?></p>
                </a>
            </div>
            <?php
            if (isset($_GET['selected' . $category->id])) {
                $_SESSION['category_id_selected'] = $category->id;
                $_SESSION['category_id_selected_topic'] = $category->id;
                header('Location:topics.php');
            }

        }
    }
    ?>
</section>
</body>
</html>