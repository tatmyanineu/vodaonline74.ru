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
    </head>
    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <div id="content">


            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <!-- Navbar content -->
                <div class="navbar-header">
                    <a id="forBrand" class="navbar-brand" href="#">Измерительная система контроля и учета энергоресурсов</a>
                </div>
                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="user_setting.php?">Пользователь: <?php echo $_SESSION['login']; ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php">Выход</a></li>
                    </ul>
                </div>
            </nav>


            <div class="container-fluid h-100">
                <div class="row h-100">
                    <aside class="col-12 col-md-2 p-0 bg-dark">
                        <nav class="navbar navbar-expand navbar-dark bg-dark flex-md-column flex-row align-items-start py-2">
                            <div class="collapse navbar-collapse">
                                <ul class="flex-md-column flex-row navbar-nav w-100 justify-content-between">

                                    <?php include '../include/menu.php'; ?>


                                    <li class="nav-item mt-5">
                                        <a class="nav-link pl-0" href="#" id="addGroup" ><i class="fas fa-plus"></i> <span class="d-none d-md-inline"></span> Добавить новую группу</a>
                                    </li>
                                </ul>



                            </div>
                        </nav>
                    </aside>
                    <main class="col bg-light py-3">
                        <div class="pb-2 mt-4 mb-2 border-bottom">
                            <h3>Группы пользователей:</h3>
                        </div>

                        <div class="modal fade bd-example-modal-lg" id="ModalAddGroup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Добавить новую группу</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="form-group">
                                                <label for="AddName" class="col-form-label">Название группы:</label>
                                                <input type="text" class="form-control" id="AddName">
                                            </div>
                                            <div class="form-group">
                                                <label for="AddComment" class="col-form-label">Комментарий:</label>
                                                <textarea class="form-control" id="AddComment"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                        <button class="btn  btn-primary" id="addGroupButton">Добавить</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="" class="table-responsive">
                            <table id="view_table"></table>

                        </div>
                    </main>
                </div>
            </div>

    </body>

    <script type="text/javascript">


        function refresh_table() {
            $.ajax({
                type: 'POST',
                chase: false,
                url: 'ajax/users/users_ajax.php',
                data: 'key=' + $('#search-group').val(),
                beforeSend: function () {
                    $('#view').html('<div id="circularG"> <div id="circularG_1" class="circularG"> </div> <div id="circularG_2" class="circularG"> </div> <div id="circularG_3" class="circularG"> </div> <div id="circularG_4" class="circularG"> </div> <div id="circularG_5" class="circularG"> </div> <div id="circularG_6" class="circularG"> </div> <div id="circularG_7" class="circularG"> </div> <div id="circularG_8" class="circularG"> </div> </div>');
                },
                success: function (html) {
                    $('#view').html(html);
                    $('.deleteGroup').click(function () {
                        delete_group(this.id);
                    });
                    $('tbody td[data-href]').addClass('clickable').click(function () {
                        window.location = $(this).attr('data-href');
                    })

                }
            });
            return false;
        }


        $(document).ready(function () {

            var table1;
//            refresh_table();

            $.ajax({
                "url": "ajax/users/users_ajax.php",
                "dataType": "json",
                "success": function (json) {
                    console.log(json.columns);
                    console.log(json.data);


                    table1 = $('#view_table').DataTable({
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
                        columns: json.columns,
                        data: json.data,
                        columnDefs: [
                            {
                                targets: [3],
                                render: function (data, type, columns, meta) {
                                    data = '<a href="users.group.view.php?id=' + columns.id + '" class="btn btn-outline-secondary" ><i class="fas fa-edit"></i> Редактировать</a>';
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


            $('#addGroup').click(function () {
                $('#ModalAddGroup').modal('show');
                $("#addGroupButton").removeClass('disabled');
//                table1.ajax.reload;
            });


            $('#addGroupButton').click(function () {
                $.ajax({
                    type: 'POST',
                    chase: false,
                    url: 'ajax/users/users_addGroup.php',
                    data: {name: $('#AddName').val(), comment: $('#AddComment').val()},
                    success: function (html) {
                        $('#ModalAddGroup .modal-body').html(html);
                        $("#addGroupButton").addClass('disabled');
                        table1.ajax.reload;

                    }
                });
                return false;
            });


            $('.navbar-nav li a').each(function () {
                var location = window.location.href;
                var link = this.href;
                if (location == link) {
                    $(this).parent('li').addClass("active");
                }

            });



        });



    </script>
</html>

