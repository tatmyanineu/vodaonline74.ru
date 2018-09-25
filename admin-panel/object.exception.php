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
?>
<html>
    <head>
        <title>Исключения обьекта: <?php echo $adr[0]['adr']; ?> </title>

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
                        <a href="objects.view.php">Объекты</a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo $adr[0]['adr']; ?></li>
                </ol>

                <hr>
                <div class="btn-toolbar mx-auto justify-content-center" role="toolbar" aria-label="Toolbar with button groups">

                    <div class="col-lg-3 col-md-3 col-xs-12">Добавить в исключения</div>
                    <div class="col-lg-9 col-md-9 col-xs-12 ">
                        <div class="row mt-3">
                            <div class="col-lg-3 col-md-3 col-xs-12">Ввод</div>
                            <div class="col-lg-5 col-md-5 col-xs-12">
                                <select id="vvod" class="form-control ml-3">
                                    <?php
                                    $sql = pg_query('SELECT 
                                            concat ("Tepl"."Resourse_cnt"."Name", \': \', "Tepl"."ParametrResourse"."Name", \' (Регистратор № \',  "Tepl"."Device_cnt"."Numbe", \' \', "Tepl"."Device_cnt"."Comment" , \')\' ) AS res,
                                            "Tepl"."ParamResPlc_cnt".prp_id                                          
                                          FROM
                                            "Tepl"."Resourse_cnt"
                                            INNER JOIN "Tepl"."ParametrResourse" ON ("Tepl"."Resourse_cnt".res_id = "Tepl"."ParametrResourse".res_id)
                                            INNER JOIN "Tepl"."ParamResPlc_cnt" ON ("Tepl"."ParametrResourse"."ParamRes_id" = "Tepl"."ParamResPlc_cnt"."ParamRes_id")
                                            INNER JOIN "Tepl"."PointRead" ON ("Tepl"."ParamResPlc_cnt".prp_id = "Tepl"."PointRead".prp_id)
                                            INNER JOIN "Tepl"."Device_cnt" ON ("Tepl"."PointRead".dev_id = "Tepl"."Device_cnt".dev_id)
                                          WHERE
                                            "Tepl"."ParamResPlc_cnt".plc_id = ' . $_GET['id']);
                                    while ($row = pg_fetch_row($sql)) {
                                        echo '<option value="' . $row[1] . '">' . $row[0] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-3 col-md-3 col-xs-12">Дата начала</div>
                            <div class="col-lg-5 col-md-5 col-xs-12">
                                <div class="input-group ml-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="btnGroupAddon"><i class="fas fa-calendar ml-3"></i> </div>
                                    </div>
                                    <input type="text" class="form-control" data-toggle="datepicker" id="date1" value="" placeholder="Начальная дата" aria-label="Input group example" aria-describedby="btnGroupAddon">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-3 col-md-3 col-xs-12">Дата окончания</div>
                            <div class="col-lg-5 col-md-5 col-xs-12">
                                <div class="input-group ml-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="btnGroupAddon"><i class="fas fa-calendar ml-3"></i> </div>
                                    </div>
                                    <input type="text" class="form-control" data-toggle="datepicker" id="date2" value="" placeholder="Конечная дата" aria-label="Input group example" aria-describedby="btnGroupAddon">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-3 col-md-3 col-xs-12">Причина исключения</div>
                            <div class="col-lg-5 col-md-5 col-xs-12">
                                <select id="excep_type" class="form-control ml-3">
                                    <?php
                                    $sql = pg_query('SELECT id, text_excep
                                                            FROM list_exception');
                                    while ($row = pg_fetch_row($sql)) {
                                        echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3">
                            <div class="col-lg-3 col-md-3 col-xs-12">Комментарий пользователя</div>
                            <div class="col-lg-5 col-md-5 col-xs-12"><input  class="form-control ml-3" id="comments"></div>
                        </div>
                    </div>

                    <div class="row">
                        <button class="btn btn-primary btn-lg" id="addExcep"><i class="fas fa-plus mr-3"></i>Добавить</button>
                    </div>

                </div>
                <div class="mt-5">

                    <div>
                        <table id="datatables">
                            <thead>
                            <th>№</th>
                            <th>Параметр</th>
                            <th>Дата Нач.</th>
                            <th>Дата Кон.</th>
                            <th>Причина</th>
                            <th>Комментарий</th>
                            <th>Пользователь</th>
                            <th>№ заявки</th>
                            <th></th>
                            </thead>
                        </table>
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


        $(document).ready(function () {
            var plc = <?php echo $_GET['id'] ?>;

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
                    url: "ajax/objects/table_exception.php",
                    "data": function (d) {
                        d.plc = plc;
                    }
                },
                columns: [
                    {data: "id"},
                    {data: "param"},
                    {data: "date_begin"},
                    {data: "date_end"},
                    {data: "text_excep"},
                    {data: "comments"},
                    {data: "user"},
                    {data: "id_ticket"},
                    {data: null,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<a class="delete" id="' + oData.id + '"><i class="far fa-trash-alt"></i></a>');
                        }
                    },
                ]
            });


            $('#datatables').on('click', '.delete', function () {
                alert(this.id);
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: "ajax/delete.php",
                    data: {action: "exception", id: this.id},
                    success: function (data) {
                        table.ajax.reload();
                    }
                });
            });


            $('#addExcep').click(function () {

                var array = new Object();
                array.prp = $('#vvod').val();
                array.plc = plc;
                array.date_begin = $('#date1').val();
                array.date_end = $('#date2').val();
                array.text = $('#excep_type option:selected').text();
                array.comment = $('#comments').val();
                console.log(array);
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: "ajax/check.move.php",
                    data: {action: "exception", data: array},
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        if (data.error == 0) {
                            table.ajax.reload();
                        } else {
                            alert(data.text);
                        }
                    }
                })
            });

            $('#datatables')
                    .removeClass('display')
                    .addClass('table table-striped table-bordered');
        }
        );

    </script>

</html>