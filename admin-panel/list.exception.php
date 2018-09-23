<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//справочник ФИАС кодов  и доступ к редактированию подключений счетчиков
session_start();
include '../include/db_config.php';
?>

<html>
    <head>
        <title>ЛИСТ справочника исключений </title>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script
            src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

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


                    <li class="nav-item mt-5" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить координаты">
                        <a class="nav-link" href="#" id="addLocation">
                            <i class="fas fa-map-pin"></i>
                            <span class="nav-link-text">Добавить координаты (API)</span>
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
                        <a href="manual.php">Справочники</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a  href="manual.exception.php">Исключения</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a>Список значений</a>
                    </li>
                </ol>
                <hr>
                <div>
                    <table id="datatables">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="SelectAll"  /></th>
                                <th>№</th>
                                <th>plc_id</th>
                                <th>Город</th>
                                <th>Адрес</th>
                                <th>Координаты</th>
                            </tr>
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


        $(document).ready(function () {

            var table = $('#datatables').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
                paging: false,
                "oLanguage": {
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
                "ajax": {
                    type: "POST",
                    url: "ajax/manual/settings.location.php",
                },
                columns: [
                    {data: null,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<input class="form-input cUser" type="checkbox" id="' + oData.plc_id + '"  value="' + oData.city + ', ' + oData.adr + '">');
                        }
                    },
                    {data: "numb", searchable: false},
                    {data: "plc_id", searchable: false},
                    {data: "city"},
                    {data: "adr"},
                    {data: "location"},
                ]
            });

            $('#SelectAll').click(function () {
                var rows = table.rows({'search': 'applied'}).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            $('#addLocation').click(function () {
                table.$('input[type="checkbox"]').each(function () {
                    if (this.checked) {
                        console.log(this.value);
                        var adr = this.value;
                        var plc = this.id;
                        ymaps.ready(function () {
                            var myGeocoder = ymaps.geocode("" + adr + "");
                            myGeocoder.then(
                                    function (res) {
                                        var str_location = res.geoObjects.get(0).geometry.getCoordinates();
                                        var location = str_location[0] + ". " + str_location[1];
                                        console.log('Координаты объекта :' + location);
                                        add_location(plc, location);
                                    },
                                    function (err) {
                                        console.log('Ошибка');
                                    }
                            );
                        });
                    }
                });
                table.ajax.reload();
            });

            var add_location = function (plc, location) {
                console.log('фунция записи в базу');
                var func = 'loc_check';
                $.ajax({
                    type: 'POST',
                    cache: false,
                    async: false,
                    url: "ajax/check.move.php",
                    data: {action: func, plc: plc, location: location},
                    success: function (html) {
                        
                    }

                });
                return false;
            }

            $('#datatables')
                    .removeClass('display')
                    .addClass('table table-striped table-bordered');
        });

    </script>

</html>