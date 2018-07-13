<?php
session_start();
include '../include/db_config.php';
?>
<html>
    <head>
        <title></title>

        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">


    </head>
    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <div id="content">


            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <!-- Navbar content -->
                <div class="navbar-header">
                    <a id="forBrand" class="navbar-brand" href="#">Измерительная система контроля и учета энергоресурсов</a>
                </div>
                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="user_setting.php?">Пользователь: <?php echo $_SESSION['login']; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php">Выход</a></li>
                    </ul>
                </div>
            </nav>


            <div class="container-fluid h-100">
                <div class="row h-100">
                    <aside class="col-12 col-md-2 p-0 bg-dark">
                        <nav class="navbar navbar-expand navbar-dark bg-dark flex-md-column flex-row align-items-start py-2">
                            <div class="collapse navbar-collapse">
                                <ul class="flex-md-column flex-row navbar-nav w-100 justify-content-between">

                                    <?php include '../include/menu.php'; ?>

                                </ul>
                            </div>
                        </nav>
                    </aside>
                    <main class="col bg-light py-3">

                    </main>
                </div>
            </div>

    </body>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script
        src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
</html>