<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <title>Easy CMS</title>
</head>
<body>

    <?php 
    require_once('autoload.php');
    ?>
    
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php?controller=index">Easy CMS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    
                    <?php
                    if( isset( $_SESSION['userId'] ) ) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=profile"><strong>Tableau de bord</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=index&action=logout">Se déconnecter</a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=index&action=login">Se connecter</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <?php
                    $controllerPath = 'EasyCMS\\src\\Controller\\';

                    if (isset( $_REQUEST['controller'] )) {
                        $controllerClassName = $controllerPath . ucfirst( $_REQUEST['controller']) . 'Controller';
                        $controller = new  $controllerClassName();
                    } else {
                        $controller = new EasyCMS\src\Controller\IndexController();
                    }
                ?>
            </div>
        </div>
    </main>

</body>

</html>