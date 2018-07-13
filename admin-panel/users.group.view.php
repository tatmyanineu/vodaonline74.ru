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
                                    <li class="nav-item mb-3">
                                        <a class="nav-link pl-0" href="users.group.php" id="addGroup" ><i class="fas fa-arrow-left"></i><span class="d-none d-md-inline"></span> Назад</a>
                                    </li>
                                    <?php include '../include/menu.php'; ?>



                                </ul>



                            </div>
                        </nav>
                    </aside>
                    <main class="col bg-light py-3">

                        <div class="pb-2 mt-4 mb-2 border-bottom">
                            <h3>Группа: <?php echo $name; ?></h3>
                        </div>

                        <div id="" class="table-responsive">
                            <table id="table_user">
                                <thead>
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
                                <th>ID</th>
                                <th>Полный адрес</th>
                                <th>Коментарий</th>
                                <th></th>
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
                    url: "ajax/users/users_info.php",
                    data: {group: group_id}
                },
                columns: [
                    {data: "id", searchable: false},
                    {data: "login"},
                    {data: "passwd"},
                    {data: "name"},
                    {data: "surName"},
                    {data: "comments"},
                    {data: "priv"},
                    {data: null},
                ]
            }
            );


            var table_object = $('#table_objects').DataTable({
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
                    url: "ajax/users/users_info.php",
                    data: {group: group_id}
                },
                columns: [
                    {data: "id", searchable: false},
                    {data: "login"},
                    {data: "passwd"},
                    {data: "name"},
                    {data: "surName"},
                    {data: "comments"},
                    {data: "priv"},
                    {data: null},
                ]
            }
            );

            $('#table_user, #table_objects')
                    .removeClass('display')
                    .addClass('table table-striped table-bordered');


//            $('#demo1').tagEditor({
//                initialTags: [],
//                placeholder: 'Пользователи',
//                onChange: function (field, editor, tags) {
//                    $('#response').prepend('Tags changed to: <i>' + (tags.length ? tags.join(', ') : '----') + '</i><hr>');
//                },
//                beforeTagDelete: function (field, editor, tags, val) {
//                    var v = val.toUpperCase();
//                    //                    var q = confirm('Remove tag "' + val + '"?');
//                    //                    if (q)
//                    //                        $('#response').prepend('Tag <i>' + val + '</i> deleted.<hr>');
            //                    else
            //                        $('#response').prepend('Removal of <i>' + val + '</i> discarded.<hr>');
//                    //                    return q;
//                    delete userId[v];
//                    console.log(userId);
//                }
//            });
//
//            $('#demo2').tagEditor({
//                initialTags: [],
//                placeholder: 'Обьекты',
//                onChange: function (field, editor, tags) {
//                    $('#response').prepend('Tags changed to: <i>' + (tags.length ? tags.join(', ') : '----') + '</i><hr>');
//                },
//                beforeTagDelete: function (field, editor, tags, val) {
//                    //                    var q = confirm('Remove tag "' + val.toUpperCase() + '"?');
//                    var v = val.toUpperCase();
//                    //                    if (q)
//                    //                        $('#response').prepend('Tag <i>' + val.toUpperCase() + '</i> deleted.<hr>');
            //                    else
            //                        $('#response').prepend('Removal of <i>' + val.toUpperCase() + '</i> discarded.<hr>');
//                    //                    return q;
//                    delete objectID[v];
//                    console.log(objectID);
//                }
//            });
        });



    </script>

</html>
