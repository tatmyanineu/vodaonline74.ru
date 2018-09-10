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
        <title>Настройки обьекта: <?php echo $adr[0]['adr']; ?> </title>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script
            src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>

        <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>

        <script src="js/jquery.maskedinput.min.js" type="text/javascript"></script>

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
                        <a class="nav-link" href="object.view.php?id=<?php echo $_GET['id']; ?>">
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
                        <a href="object.view.php?id=<?php echo $_GET['id']; ?>">Объект</a>
                    </li>
                    <li class="breadcrumb-item"><a href="manual.fias.php">Справочник: Информация</a></li>
                    <li class="breadcrumb-item active"><?php echo $adr[0]['adr']; ?></li>
                </ol>

                <hr>

                <div class="mt-5 ml-3">
                    <div id="fias_data"></div>

                    <div id="prp_data" ></div>
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

    <script>

        var fias_data_refresh = function (plc) {
            $.ajax({
                type: 'POST',
                chashe: false,
                url: 'ajax/property/data_fias.php',
                data: {plc: plc},
                success: function (html) {
                    $('#fias_data').html(html);

                }
            });
            return false;
        }


        var prp_data_refresh = function (plc) {
            $.ajax({
                type: 'POST',
                chashe: false,
                url: 'ajax/property/data_connection.php',
                data: {plc: plc},
                success: function (html) {
                    $('#prp_data').html(html);
                    $('.date').mask('99.99.9999');
                }
            });
            return false;
        }

        $(document).ready(function () {
            var plc_id = <?php echo $_GET['id']; ?>;
            prp_data_refresh(plc_id);
            fias_data_refresh(plc_id);


            $(document).on('click', '.btn-save-fias', function () {
                var fias = $('#fias').val();
                var cdog = $('#cdog').val();
                var cn = $('#cnid').val();
                var func = 'fias_check';

                $.ajax({
                    type: 'POST',
                    chashe: false,
                    url: 'ajax/check.move.php',
                    data: {fias: fias, plc: plc_id, cn: cn, action: func},
                    success: function (html) {

                    }
                });
                return false;
            });


            $(document).on('click', '.btn-save-prop', function () {
                var i = 0;
                var jsonObj = [];
                var func = "prop.connec.edit";
                var value = $('h2').each(function () {
                    var array = new Object();
                    console.log(this.id);
                    array.prp = this.id;
                    array.id_connect = $('#id_connect_' + this.id).val();
                    array.numb = $('#counter_numb_' + this.id).val();
                    array.date = $('#date_' + this.id).val();
                    array.cdog = $('#cdog_' + this.id).val();
                    array.plc = plc_id;
                    jsonObj.push(array);
                    i++;
                })

                $.ajax({
                    type: 'POST',
                    chashe: false,
                    url: 'ajax/check.move.php',
                    data: {json:jsonObj, action: func},
                    success: function (html) {

                    }
                });

                console.log(jsonObj);
                return false;
            });


        });

    </script>

</html>