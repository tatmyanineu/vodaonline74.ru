<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//справочник ФИАС кодов  и доступ к редактированию подключений счетчиков
session_start();
include '../include/db_config.php';

$date1 = date('21.m.Y', strtotime('-1 month'));
$date2 = date('21.m.Y');
?>

<html>
    <head>
        <title>Показания ОПУ за период </title>

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
                        <a class="nav-link" href="manual.php">
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
                        <a href="reports.php">Отчеты</a>
                    </li>
                    <li class="breadcrumb-item active">Показания ОПУ за период</li>
                </ol>
                <hr>
                <div>

                    <div class="btn-toolbar mx-auto justify-content-center mb-3" role="toolbar" aria-label="Toolbar with button groups">


                        <div class="input-group ml-3 ">
                            <div class="input-group-prepend">
                                <div class="input-group-text" id="btnGroupAddon">Район  <i class="fas fa-home ml-3"></i> </div>
                            </div>
                            <select class="form-control type_arch" id="id_dist">
                                <option value="0" selected>Все</option>
                                <?php
                                $sql = pg_query('SELECT 
                                            "Tepl"."Places_cnt"."Name",
                                            "Tepl"."Places_cnt".plc_id
                                          FROM
                                            "Tepl"."Places_cnt"
                                          WHERE
                                            "Tepl"."Places_cnt".typ_id = 10');

                                while ($row = pg_fetch_row($sql)) {
                                    echo '<option value="' . $row[1] . '">' . $row[0] . '</option>';
                                }
                                ?>
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

                    <table id="fias" class="mt-5">
                        <thead>
                        </thead>
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
            var dist = $('#id_dist').val();
            var date1 = $('#date1').val(),
                    date2 = $('#date2').val();
            $.ajax({
                type: 'POST',
                cache: false,
                url: "ajax/reports/value.month.php",
                data: {dist: dist, date1: date1, date2: date2},
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
            var tableName = '#fias';
            $.each(json.columns, function (k, colObj) {
                str = '<th>' + colObj.title + '</th>';
                $(str).appendTo(tableName + '>thead>tr');
            });
            var tables = $(tableName).DataTable({
                destroy: true,
                dom: 'Bfrtip',
                buttons: [
                    'excel',
                    {
                        text: 'DBF экспорт',
                        action: function (e, dt, node, config) {
                            window.open('ajax/reports/download_dbf.php');
                        }
                    }
                ],
                paging: false,
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
            });
        }

        $(document).ready(function () {

            $('#archive_param').click(function () {
                $("th").remove();
                view_table();
            });
            view_table();
//
//                var table = $('#fias').DataTable({
//                    dom: 'Bfrtip',
//                    buttons: [
//                        'excel'
//                    ],
//                    paging: false,
//                    "oLanguage": {
//                        "sLengthMenu": "Отображено _MENU_ записей на страницу",
//                        "sSearch": "Поиск:",
//                        "sZeroRecords": "Ничего не найдено - извините",
//                        "sInfo": "Показано с _START_ по _END_ из _TOTAL_ записей",
//                        "sInfoEmpty": "Показано с 0 по 0 из 0 записей",
//                        "sInfoFiltered": "(filtered from _MAX_ total records)",
//                        "oPaginate": {
//                            "sFirst": "Первая",
//                            "sLast": "Посл.",
//                            "sNext": "След.",
//                            "sPrevious": "Пред.",
//                        }
//                    },
//                    "ajax": {
//                        type: "POST",
//                        url: "ajax/reports/value.month.php",
//                    },
//                    columns: [
//                        {data: "plc_id", searchable: false},
//                        {data: "adr"},
//                        {data: "param_name", searchable: false},
//                        {data: "v1"},
//                        {data: "d1"},
//                        {data: "v2"},
//                        {data: "d2"},
//                        {data: "sum"},
//                        {data: "error"}
//                    ]
//                });

            $('#fias')
                    .removeClass('display')
                    .addClass('table table-striped table-bordered');
        }
        );

    </script>

</html>