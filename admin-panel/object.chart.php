<?php
session_start();
include '../include/db_config.php';

$sql_name = pg_query('SELECT 
 concat( "Tepl"."Places_cnt"."Name",\', \', "Places_cnt1"."Name") as adr
FROM
  "Tepl"."Places_cnt" "Places_cnt1"
  INNER JOIN "Tepl"."Places_cnt" ON ("Places_cnt1".place_id = "Tepl"."Places_cnt".plc_id)
WHERE
  "Places_cnt1".plc_id = ' . $_GET['id'] . '');

$adr = pg_fetch_all($sql_name);

$num = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
$date1 = date('01.m.Y');
$date2 = date($num . '.m.Y');
?>
<html>
    <head>
        <title>График обьекта: <?php echo $adr[0]['adr']; ?> </title>

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

                    <li class="nav-item mt-5" data-toggle="tooltip" data-placement="right" title="" data-original-title="Заявка на обслуживание">
                        <a class="nav-link" href="#">
                            <i class="fas fa-calendar-plus"></i>
                            <span class="nav-link-text">Заявка на обслуживание</span>
                        </a>
                    </li>
                    <?php include '../include/service_menu.php'; ?>
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
                    <li class="breadcrumb-item active"><?php echo $adr[0]['adr']; ?></li>
                </ol>

                <hr>
                <div class="btn-toolbar mx-auto justify-content-center" role="toolbar" aria-label="Toolbar with button groups">


                    <div class="input-group ml-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text" id="btnGroupAddon">Архив <i class="far fa-clock ml-3"></i> </div>
                        </div>
                        <select class="form-control type_arch" id="type_archive">
                            <option value="1">Часовой</option>
                            <option value="2">Суточный</option>
                            <option value="3">Месячный</option>
                        </select>
                    </div>

                    <div class="input-group ml-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text" id="btnGroupAddon">Нач. дата <i class="fas fa-calendar ml-3"></i> </div>
                        </div>
                        <input type="text" class="form-control" data-toggle="datepicker" id="date1" value="<?php echo $date1; ?>" placeholder="" aria-label="Input group example" aria-describedby="btnGroupAddon">
                    </div>
                    <div class="input-group  ml-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text" id="btnGroupAddon">Кон. дата <i class="fas fa-calendar ml-3"></i> </div>
                        </div>
                        <input type="text" class="form-control" data-toggle="datepicker" id="date2" value="<?php echo $date2; ?>" placeholder="" aria-label="Input group example" aria-describedby="btnGroupAddon">
                    </div>
                    <div class="input-group  ml-3">

                        <button class=" btn btn-md btn-success" id="archive_param">Сформировать</button>
                    </div>
                </div>
                <div class="mt-5">

                    <ul class="nav nav-tabs  nav-justified mt-5" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" href="object.view.php?id=<?php echo $_GET['id']; ?>" role="tab" >Архив: таблица</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="object.chart.php?id=<?php echo $_GET['id']; ?>" role="tab" >Архив: график</a>
                        </li>
                    </ul>

                    <div id="charts">

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

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script>

        $('[data-toggle="datepicker"]').datepicker({
            language: 'ru-RU',
            format: 'dd.mm.YYYY'
        });

        function charts(plc, date1, date2) {

            $.getJSON('ajax/objects/charts_archive.php', {plc: plc, date1: date1, date2: date2}, function (chartData) {
                console.log(chartData.value);
                $('#charts').highcharts({
                    chart: {
                        type: 'line',
                        height: 600
                    },
                    title: {
                        text: 'Потребление водоснабжения'
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: false
                        }
                    },
                    yAxis: {
                        title: {text: 'объем'}
                    },
                    xAxis: {
                        categories: chartData.date
                    },

                    series: chartData.value

                });
            });
        }

        $(document).ready(function () {
            var plc = <?php echo $_GET['id']; ?>;
            charts(plc, $('#date1').val(), $('#date2').val());

            $('#archive_param').click(function () {
               charts(plc, $('#date1').val(), $('#date2').val());
            });
        }
        );

    </script>

</html>