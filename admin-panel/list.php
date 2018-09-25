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
        <title>ЛИСТ справочника </title>

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


                    <li class="nav-item mt-3" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить элемент">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#add_element_modal">
                            <i class="fas fa-plus"></i>
                            <span class="nav-link-text">Добавить Элемент</span>
                        </a>
                    </li>

                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить элемент">
                        <a class="nav-link" href="#" id="delete_elements">
                            <i class="fas fa-trash-alt"></i>
                            <span class="nav-link-text">Удалить Элемент</span>
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
                <div class="modal fade" id="add_element_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Добавить элемент списка</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-lg-3 col-md-3 col-xs-12">Значение*</div>
                                    <div class="col-lg-9 col-md-9 col-xs-12"><input type="text" id="addNameValues" class="form-control"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                <button class="btn  btn-primary" id="addButton">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="manual.php">Справочники</a>
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
                                <th></th>
                                <th>id</th>
                                <th>Значение</th>
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
            var type = "<?php echo $_GET['type'] ?>";


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
                    url: "ajax/lists/view_tables.php",
                    "data": function (d) {
                        d.types = type;
                    }
                },
                columns: [
                    {data: null,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<input class="form-input" type="checkbox" id="' + oData.id + '">');
                        }
                    },
                    {data: "id"},
                    {data: "name"}

                ]
            });


            $('#addButton').click(function () {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: "ajax/lists/add.list.php",
                    data: {name: $('#addNameValues').val(), types: type},
                    success: function (data) {
                        table.ajax.reload();
                        $('#addNameValues').val('');
                    }
                });
                $('#add_element_modal').modal('hide');
            });


            $('#delete_elements').click(function () {
                table.$('input[type="checkbox"]').each(function () {
                    if (this.checked) {
                        $.ajax({
                            type: 'POST',
                            cache: false,
                            url: "ajax/lists/delete.list.php",
                            data: {id: this.id, types: type},
                            success: function (data) {
                                console.log(this.id);
                            }
                        });
                    }
                });
                table.ajax.reload();
            });

            $('#datatables')
                    .removeClass('display')
                    .addClass('table table-striped table-bordered');
        });

    </script>

</html>