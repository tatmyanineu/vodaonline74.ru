<?php
//Добавить шифрование пароля для передачи в скрипте ajax

include '../include/db_config.php';
session_start();

$sqlGroup = pg_query('SELECT DISTINCT 
  "Tepl"."UserGroups"."Name"
FROM
  "Tepl"."UserGroups"
WHERE
  "Tepl"."UserGroups".grp_id = ' . $_GET['id'] . '');

$name = pg_fetch_result($sqlGroup, 0, 0);
?>

<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script src="../vendor/components/jquery/jquery.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>

        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js" type="text/javascript"></script>
        <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>


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
                <div class="row">
                    <aside class="col-12 col-md-2 col-lg-2 p-0 bg-dark">
                        <nav class="navbar navbar-expand navbar-dark bg-dark flex-md-column flex-row align-items-start py-2">
                            <div class="collapse navbar-collapse">
                                <ul class="flex-md-column flex-row navbar-nav w-100 justify-content-between">
                                    <li class="nav-item mb-3">
                                        <a class="nav-link pl-0" href="users.group.php" id="addGroup" ><i class="fas fa-arrow-left"></i><span class="d-none d-md-inline"></span> Назад</a>
                                    </li>
                                    <?php include '../include/menu.php'; ?>

                                    <li class="nav-item mt-5">
                                        <a class="nav-link pl-0" href="#" id="addUser" ><i class="fas fa-user-plus"></i> <span class="d-none d-md-inline"></span> Добавить пользователя</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link pl-0" href="#" data-href="user" id="deleteUser"><i class="fas fa-user-times"></i> <span class="d-none d-md-inline"></span> Удалить пользователя</a>
                                    </li>

                                    <li class="nav-item mt-5">
                                        <a class="nav-link pl-0" href="#" id="addObjects" ><i class="fas fa-plus"></i> <span class="d-none d-md-inline"></span> Добавить обьект в группу</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link pl-0" href="#" data-href="objects"  id="deleteOjects"><i class="fas fa-minus"></i> <span class="d-none d-md-inline"></span> Удалить выбранные</a>
                                    </li>
                                </ul>



                            </div>
                        </nav>
                    </aside>
                    <main class="col bg-light py-3">

                        <!--                        success modal-->



                        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirm-delete-modal" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Подтвердить удаление</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body" id="modalLoadText">

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                        <a class="btn btn-danger btn-ok" id="delete">Удалить</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--                        user modal-->

                        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Добавление нового пользователя</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formWorkUsers">
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Логин*</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddLogin" class="form-control"/></div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Пароль*</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddPasswd" class="form-control"/></div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Фамилия</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddSurname" class="form-control"/></div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Имя</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddName" class="form-control"/></div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Права</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddRole" class="form-control" value="0"></div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                        <button type="button" id="addUserButton" data-href="0" class="btn btn-primary fromEditUser">Добавить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        object modal-->


                        <div class="pb-2 mt-4 mb-2 border-bottom">
                            <h3>Группа: <?php echo $name; ?></h3>
                        </div>

                        <div id="" class="table-responsive">
                            <table id="table_user">
                                <thead>
                                <th><input type="checkbox" id="SelectAllUser"  /></th>
                                <th>ID</th>
                                <th>Login</th>
                                <th>Password</th>
                                <th>Фамилия</th>
                                <th>Имя</th>
                                <th>Коментарий</th>
                                <th>Права</th>
                                <th></th>
                                </thead>
                            </table>
                        </div>
                        <div  id="" class="table-responsive">
                            <table id="table_objects">
                                <thead>
                                <th><input type="checkbox" id="SelectAllObjects"  /></th>
                                <th>ID</th>
                                <th>Город</th>
                                <th>Полный адрес</th>
                                <th>Коментарий</th>
                                </thead>
                            </table>
                        </div>
                    </main>

                </div>
            </div>
        </div>

        <!--Баковое меню -->

    </body>

    <script type="text/javascript">


        $(document).ready(function () {
            var strGET = window.location.search.substr(1), keys = {};
            strGET = strGET.split('=');
            var group_id = strGET[1];
            var users = [];
            var objects = [];
            console.log(group_id);
            var table_user = $('#table_user').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
                paging: false,
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
                ajax: {
                    type: "POST",
                    url: "ajax/users/table_user.php",
                    data: {group: group_id}
                },
                columns: [
                    {data: null,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<input class="form-input cUser" type="checkbox" id="' + oData.id + '" value="' + oData.login + '">');
                        }
                    },
                    {data: "id"},
                    {data: "login"},
                    {data: "passwd"},
                    {data: "name"},
                    {data: "surName"},
                    {data: "comments"},
                    {data: "priv"},
                    {data: null,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<button class="btn  btn-outline-success editUser" id="' + oData.id + '">Редактировать</button>');
                        }

                    },
                ]

            }
            );
            var table_object = $('#table_objects').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
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
                ajax: {
                    type: "POST",
                    url: "ajax/users/table_object.php",
                    data: {group: group_id}
                },
                columns: [
                    {data: null,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<input class="form-input cObjects" type="checkbox" id="' + oData.id + '" value="' + oData.address + '">');
                        }
                    },
                    {data: "id", searchable: false},
                    {data: "city"},
                    {data: "address"},
                    {data: "comm"},
                ]
            }
            );
            $('#table_user, #table_objects')
                    .removeClass('display')
                    .addClass('table table-striped table-bordered');

            $('#SelectAllUser').click(function () {
                var rows = table_user.rows({'search': 'applied'}).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            $('#SelectAllObjects').click(function () {
                var rows = table_object.rows({'search': 'applied'}).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });


            $('#deleteUser').click(function () {
                var text = "<p> Вы хотите удалить следующих пользователей: <br>";
                table_user.$('input[type="checkbox"]').each(function () {
                    if (this.checked) {
                        console.log(this.id + " " + this.value);
                        text += " ID = " + this.id + " login = " + this.value + "<br>";
                        users.push(this.id)
                    }
                });
                text += "</p>"
                if (users.length > 0) {
                    $('#modalLoadText').html(text);
                    $('#confirm-delete').modal('show');
                    $('#delete').attr('data-href', 'users');
                    table_user.$('input:checkbox').prop('checked', false);
                }
            });


            $('#deleteOjects').click(function () {
                var text = "<p> Вы хотите удалить иг группы следующие обьеты: <br>";
                table_object.$('input[type="checkbox"]').each(function () {
                    if (this.checked) {
                        console.log(this.id + " " + this.value);
                        text += " ID = " + this.id + " Адрес = " + this.value + "<br>";
                        objects.push(this.id)
                    }
                });
                text += "</p>"
                if (objects.length > 0) {
                    $('#modalLoadText').html(text);
                    $('#confirm-delete').modal('show');
                    $('#delete').attr('data-href', 'objects');
                    table_object.$('input:checkbox').prop('checked', false);
                }
            });

            $('#delete').click(function () {
                var atr = $('#delete').attr('data-href');
                if (atr == 'users') {
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: "ajax/users/delete_users.php",
                        data: {group: group_id, data: users},
                        success: function (html) {
                            table_user.ajax.reload();
                            users = [];
                            $('#delete').removeAttr('data-href');
                            $('#confirm-delete').modal('hide');
                        }
                    });
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: "ajax/users/delete_objects.php",
                        data: {group: group_id, data: objects},
                        success: function (html) {
                            table_object.ajax.reload();
                            objects = [];
                            $('#delete').removeAttr('data-href');
                            $('#confirm-delete').modal('hide');
                        }
                    });
                    return false;
                }
            });

            $('#addUser').click(function () {
                $('#addUserModal').modal('show');
            });
            $('#addUserButton').click(function () {
                var id_user = $(this).attr('data-href');


                var login = $('#AddLogin').val();
                var passwd = $('#AddPasswd').val();

                if (login == '' || passwd == '') {
                    alert("Не заполенено обяаельное поле");
                } else {


                    if (id_user == 0) {
                        $.ajax({
                            type: 'POST',
                            cache: false,
                            url: "ajax/users/add_user.php",
                            data: {login: login, passwd: passwd, group: group_id, name: $('#AddName').val(), surname: $('#AddSurname').val(), role: $('#AddRole').val(), id_user: id_user},
                            success: function (html) {
                                table_user.ajax.reload();
                                $('#addUserModal').modal('hide');
                            }
                        });
                        return false;
                    } else {
                        alert('ne ok');
                    }

                }
            });


            $('#table_user').on('click', '.editUser', function () {
                alert(this.id);
                $('#addUserModal').modal('show');
                $('.fromEditUser').attr('data-href', this.id);
                $.ajax({
                    type: 'POST',
                    chashe: false,
                    url: 'ajax/users/edit_user.php',
                    dataType: "json",
                    data: {id: this.id},
                    success: function (html) {
                        //(html == "") ? alert("Ссылки в поле CONTRAKT_ID не найдено") : $('#cdog').val(html);
                        $('#AddLogin').val(html.login);
                        $('#AddPasswd').val(html.password);
                        $('#AddSurname').val(html.surname);
                        $('#AddName').val(html.name);
                        $('#AddRole').val(html.role);
                    }
                });
                return false;


            });



        });



    </script>

</html>
