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
        <link href="../module/Buttons-1.5.2/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"/>

        <link href="css/sb-admin.css" rel="stylesheet" type="text/css"/>


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
        <script src="js/userEdit.js" type="text/javascript"></script>

        <script>


            $(document).ready(function () {
                var id = $('#forUserEdit').attr("data-id");
                $('#forUserEdit').on('click', newModal(id));
//            refresh_table();
                var table;
                $.ajax({
                    "url": "ajax/objects/table_objects.php",
                    "dataType": "json",
                    "success": function (json) {
                        var id = new Object();
                        console.log(json.columns);
                        var i = 0;

                        console.log(json.data);
                        table = $('#view_table').DataTable({

                            dom: 'Bfprtip',
                            buttons: [
                                'copy', 'excel', 'pdf'
                            ],
                            pageLength: 100,

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
                            "processing": true,
                            "orderMulti": false,
                            columns: json.columns,
                            data: json.data,
                            "columnDefs": [
                                {targets: [2],
                                    render: function (data, type, row, met) {

                                        data = '<a href="object.view.php?id=' + row.plc_id + '">' + row.adr + '</a>';

                                        return data;
                                    }
                                },
                                {
                                    // The `data` parameter refers to the data for the cell (defined by the
                                    // `data` option, which defaults to the column being worked with, in
                                    // this case `data: 0`.
                                    targets: '_all',
                                    render: function (data, type, row, met) {
                                        var name = met.settings.aoColumns[met.col].mData;
                                        if (name.indexOf('res_') + 1) {
                                            var n = met.settings.aoColumns[met.col].mData;
                                            var id = name.slice(4);

                                            //console.log(row["error_" + id]);
                                            switch (row["error_" + id]) {
                                                case 0:
                                                    met.settings.aoColumns[met.col].sClass = "";
                                                    break;
                                                case 1:
                                                    met.settings.aoColumns[met.col].sClass = "bg-danger";
                                                    break;
                                                case 2:
                                                    met.settings.aoColumns[met.col].sClass = "bg-info";
                                                    break;
                                                case 3:
                                                    met.settings.aoColumns[met.col].sClass = "bg-warning";
                                                    break;
                                                case 4:
                                                    met.settings.aoColumns[met.col].sClass = "bg-secondary";
                                                    break;
                                            }

                                        }
                                        i++;

                                        return data;

                                    }

                                }
                            ]
                        }
                        );

                    }

                })

                $('#view_table')
                        .removeClass('display')
                        .addClass('table table-striped table-bordered');
            });

        </script>

    </head>
    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand" href="#">Измерительная система контроля и учета энергоресурсов</a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

                    <?php include '../include/menu.php'; ?>

                    <li class="nav-item mt-5" data-toggle="tooltip" data-placement="right" title="" data-original-title="Настройка пользователей">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#legendModal">
                            <i class="fas fa-book"></i>
                            <span class="nav-link-text">Легенда таблицы</span>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <a class="nav-link" id="forUserEdit" href="#" data-id="<?php echo $_SESSION['auth']['id']; ?>" data-toggle="modal" data-target="#myModal">
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

                <div class="modal fade" id="legendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Обозначение полей таблицы</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="p-3 mb-2 bg-info text-white">На обьекте отсутствует импульс (более 7 дней)</div>
                                <div class="p-3 mb-2 bg-danger text-white">От обьекта не поступают данные (более 7 дней)</div>
                                <div class="p-3 mb-2 bg-warning text-white">Нет архива за последние 2 месяца</div>
                                <div class="p-3 mb-2 bg-secondary text-white">Неисправность, маленький расход за период(7 дней) </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Breadcrumbs-->

                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Объекты</a>
                    </li>
                    <li class="breadcrumb-item active">Список</li>
                </ol>

                <hr>

                <table id="view_table" style="font-size: 12px; padding-right: 0px;"></table>

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
</html>