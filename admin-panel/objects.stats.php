<?php
session_start();
include '../include/db_config.php';
?>
<html>
    <head>
        <title></title>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script
            src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>

        <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>


        <link href="../vendor/datatables/datatables/media/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>

        <script src="../vendor/datatables/datatables/media/js/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../vendor/datatables/datatables/media/js/dataTables.bootstrap4.js" type="text/javascript"></script>

        <link href="css/sb-admin.css" rel="stylesheet" type="text/css"/>


    </head>
    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand" href="#">Измерительная система контроля и учета энергоресурсов</a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

                    <?php include '../include/menu.php'; ?>


                </ul>

                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="user_setting.php?">
                            <i class="fa fa-fw fa-sign-out"></i>Пользователь: <?php echo $_SESSION['auth']['login']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fa fa-fw fa-sign-out"></i>Выход</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content-wrapper">
            <div class="container-fluid">
                <!-- Breadcrumbs-->

                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="objects.view.php">Объекты</a>
                    </li>
                    <li class="breadcrumb-item active">Информация</li>
                </ol>

                <hr>

                <table id="view_table" style="font-size: 11px; padding-right: 0px;"></table>

                <div style="height: 800px;"></div>
            </div>
            <!-- /.container-fluid-->
            <!-- /.content-wrapper-->
            <footer class="sticky-footer">
                <div class="container">
                    <div class="text-center">
                        <small></small>
                    </div>
                </div>
            </footer>

        </div>
    </body>
    <script type="text/javascript">


        $(document).ready(function () {
           

        });

    </script>
</html>