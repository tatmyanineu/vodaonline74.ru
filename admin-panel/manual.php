<?php
session_start();
include '../include/db_config.php';
?>
<html>
    <head>
        <title>Настройки </title>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script
            src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>

        <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>


        <link href="../module/Buttons-1.5.2/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"/>


        <script src="../vendor/datatables/datatables/media/js/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../vendor/datatables/datatables/media/js/dataTables.bootstrap4.js" type="text/javascript"></script>

        <link href="css/sb-admin.css" rel="stylesheet" type="text/css"/>
        <script src="js/datepicker.js" type="text/javascript"></script>
        <link href="css/datepicker.css" rel="stylesheet" type="text/css"/>
        <script src="js/datepicker.ru-RU.js" type="text/javascript"></script>

        <link href="../vendor/datatables/datatables/media/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>



    </head>
    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand" href="#">Измерительная система контроля и учета энергоресурсов</a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <li class="nav-item mb-3" data-toggle="tooltip" data-placement="right" title="" data-original-title="Настройка пользователей">
                        <a class="nav-link" href="objects.view.php">
                            <i class="fas fa-arrow-left"></i>
                            <span class="nav-link-text">Назад</span>
                        </a>
                    </li>
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
                        <a href="settings.php">Справочники</a>
                    </li>
                    <li class="breadcrumb-item active">Основные</li>
                </ol>

                <hr>
                <div class="my-3 p-3 bg-white rounded shadow-sm">



                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Справочник ФИАС</h5>
                            <h6 class="card-subtitle mb-2 text-muted">fias</h6>
                            <p class="card-text">Связка идентификаторов plc_id с ФИАС кодом, и кодом из ЦН</p>
                            <a href="manual.fias.php" class="card-link">Перейти</a>
                        </div>
                    </div>
                    
                     <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Справочник Координат</h5>
                            <h6 class="card-subtitle mb-2 text-muted">location</h6>
                            <p class="card-text">Координаты для маркеров на карте. Функция Добавить координаты работает Y.maps автоматом из списка выбранных обьектов </p>
                            <a href="manual.location.php" class="card-link">Перейти</a>
                        </div>
                    </div>
                    
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Справочник Подключений счечтиков</h5>
                            <h6 class="card-subtitle mb-2 text-muted">property connection</h6>
                            <p class="card-text">Связка параметров ресурсов с данные для МУП ПОВВ </p>
                            <a href="manual.connect.php" class="card-link">Перейти</a>
                        </div>
                    </div>
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
        </div>
    </body>
    <script src="../module/JSZip-2.5.0/jszip.min.js" type="text/javascript"></script>
    <script src="../module/pdfmake-0.1.36/pdfmake.min.js" type="text/javascript"></script>
    <script src="../module/pdfmake-0.1.36/vfs_fonts.js" type="text/javascript"></script>
    <script src="../module/Buttons-1.5.2/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="../module/Buttons-1.5.2/js/buttons.bootstrap.js" type="text/javascript"></script>
    <script src="../module/Buttons-1.5.2/js/buttons.bootstrap4.js" type="text/javascript"></script>
    <script src="../module/Buttons-1.5.2/js/buttons.colVis.min.js" type="text/javascript"></script>
    <script src="../module/Buttons-1.5.2/js/buttons.flash.min.js" type="text/javascript"></script>
    <script src="../module/Buttons-1.5.2/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="../module/Buttons-1.5.2/js/buttons.print.js" type="text/javascript"></script>
    <script>

        $('[data-toggle="datepicker"]').datepicker({
            language: 'ru-RU',
            format: 'dd.mm.YYYY'
        });

        $(document).ready(function () {


        });

    </script>

</html>