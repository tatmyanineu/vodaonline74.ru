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
        <title>Архив обьекта: <?php echo $adr[0]['adr']; ?> </title>

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

                    <li class="nav-item mt-5" data-toggle="tooltip" data-placement="right" title="" data-original-title="Настройка пользователей">
                        <a class="nav-link" href="#">
                            <i class="fas fa-calendar-plus"></i>
                            <span class="nav-link-text">Добавить заявку</span>
                        </a>
                    </li>

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
                    <table id="view_table" class="mt-5" style="font-size: 12px; padding-right: 0px;">
                        <thead><tr></tr></thead>
                    </table>
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
    <script>

        $('[data-toggle="datepicker"]').datepicker({
            language: 'ru-RU',
            format: 'dd.mm.YYYY'
        });
        function ajaxRequest() {
            var res;
            var plc = <?php echo $_GET['id']; ?>;
            var type = $('#type_archive').val();
            var date1 = $('#date1').val(),
                    date2 = $('#date2').val();
            $.ajax({
                type: 'POST',
                cache: false,
                url: "ajax/objects/table_archive.php",
                data: {plc: plc, type: type, date1: date1, date2: date2},
                dataType: "json",
                async: false,
                success: function (data) {
                    res = data;
                }

            })
            return res;
        }
        function view_table() {
            var json = ajaxRequest();
            var tableName = '#view_table';
            $.each(json.columns, function (k, colObj) {
                str = '<th>' + colObj.title + '</th>';
                $(str).appendTo(tableName + '>thead>tr');
            });
            var tables = $(tableName).DataTable({
                destroy: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                "pageLength": 50,
                "autoWidth": false,
                oLanguage: {
                    "sLengthMenu": "Отображено _MENU_ записей на страницу",
                    "sSearch": "Поиск:",
                    "sZeroRecords": "Ничего не найдено - извините",
                    "sInfo": "Показано с _START_ по _END_ из _TOTAL_ записей",
                    "sInfoEmpty": "Показано с 0 по 0 из 0 записей",
                    "sInfoFiltered": "(filtered from _MAX_ total records)",
                    "oPaginate": {
                        "sFirst": "Первая",
                        "sLast": "Посл.",
                        "sNext": "След.",
                        "sPrevious": "Пред.",
                    }
                },
                columns: json.columns,
                data: json.data,
                footerCallback: function (row, data, start, end, display) {
                    $('#view_table>tfoot>').remove();
                    var footer = $("<tfoot></tfoot>").appendTo(tableName);
                    footer.addClass('bg-success text-white');
                    var footertr = $("<tr></tr>").appendTo(footer);
                    var api = this.api(), data;
                    var id = api.context[0].aoColumns;
                    for (i = 0; i < id.length; i++) {
                        if (i == 0) {
                            str = '<td><b>Итог за период</b></td>';
                        } else {
                            str = "<td></td>";
                        }

                        if (id[i].mData.indexOf('res_') + 1) {
                            var res = id[i].mData.slice(4);
                            str = '<td><b>' + json.summa[res] + '</b></td>';
                        }

                        $(str).appendTo(tableName + '>tfoot>tr');
                    }
                }
            });
//                  Функция использования рендера столбцов
//                data.columns[0].render = function (data, type, row) {
//                    return '<h4>' + data + '</h4>';
//                }




        }

        $(document).ready(function () {

            $('#archive_param').click(function () {
                $("th").remove();
                view_table();
            });
            view_table();
            $('#view_table')
                    .removeClass('display')
                    .addClass('table table-striped table-bordered');
        }
        );

    </script>

</html>