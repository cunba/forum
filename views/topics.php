<?php
session_start();

require_once("../controllers/Category_controller.php");
require_once("../controllers/Topic_controller.php");

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
    <h1>MERAKI ADMIN</h1>
    <div></div>
</header>
<section class="first left">
    <div class="left-list">
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
            foreach ($categories as $category) {
                if (isset($_GET['selected-' . $category->id])) {
                    $_SESSION['category_id_selected'] = $category->id;
                    echo "<meta http-equiv='refresh' content='0; url=topics.php' >";
                }

                if ($_SESSION['category_id_selected'] == $category->id) {
                    ?>
                    <div class="left-list-item selected">
                        <h2><?php echo $category->category; ?></h2>
                    </div>
                    <?php
                } else {
                    ?>
                    <a class="left-list-item" href="topics.php?selected-<?php echo $category->id ?>">
                        <h3><?php echo $category->category ?></h3>
                    </a>
                    <?php
                }
            }
        }
        ?>
    </div>
    <div class="right-list">
        <h1>Temas</h1>
        <?php
        $topics = Topic_controller::get_by_category($_SESSION['category_id_selected']);
        if (gettype($topics) == 'boolean') {
            ?>
            <div class="empty">
                <p>No hay categorías para mostrar</p>
            </div>
            <?php
        } else {
            foreach ($topics as $topic) {
                $num_topics = Topic_controller::count_comments($topic->id);
                ?>
                <div class="list-item hover">
                    <a href="topics.php?selected<?php echo $topic->id ?>">
                        <h2><?php echo $topic->topic; ?></h2>
                        <p><?php echo "Contiene {$num_topics} comentarios" ?></p>
                    </a>
                </div>
                <?php
                if (isset($_GET['selected' . $topic->id])) {
                    $_SESSION['topic_id_selected'] = $topic->id;
                    $_SESSION['category_id_selected'] = $topic->category_id;
                    $_SESSION['category_id_selected_topic'] = $topic->category_id;
                    header('Location:home.php');
                }
            }
        }
        ?>
    </div>
</section>
</body>
</html>