<?php
session_start();
include '../include/db_config.php';

if (isset($_POST['submit'])) {
    if (isset($_POST['login']) and isset($_POST['passwd'])) {

        $sql = pg_query('SELECT *
                FROM
                  "Tepl"."User_cnt"
                WHERE
                  "Tepl"."User_cnt"."Login" = \'' . $_POST['login'] . '\' AND 
                  "Tepl"."User_cnt"."Password" = \'' . $_POST['passwd'] . '\'');

        if (pg_num_rows($sql) > 0) {
            while ($row = pg_fetch_row($sql)) {
                $role = $row[7];
                $_SESSION['auth'] = array(
                    'id' => $row[0],
                    'login' => $row[1],
                    'role' => $row[7]
                );
            }
            switch ($role) {
                case 0:
                    include '../scripts/objects.json.php';
                    header("Location: objects.view.php");
                    break;
                case 14:
                    include '../scripts/objects.json.php';
                    header("Location: objects.view.php");
                    break;
                case 21:
                    header("Location: objects.stats.php");
                    break;
                case 31:
                    header("Location: objects.stats.php");
                    break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>МУП ПОВВ</title>
        <!-- Bootstrap -->
        <script
            src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body style="font-family: 'Exo 2', sans-serif;" class="bg-dark">
        <div class="container">

            <div class="card card-login mt-5 mx-auto">
                <div class="card-header text-center"><b>Авторизация</b></div>
                <div class="card-body">
                    <form class="form-signin" method="post" action="#" role="form" autocomplete="off">
                        <div class="form-group">
                            <label for="">Пользователь</label>
                            <input class="form-control" id="lgn" name="login" type="text" aria-describedby="emailHelp" placeholder="Имя пользователя">
                        </div>
                        <div class="form-group">
                            <label for="">Пароль</label>
                            <input class="form-control" id="pwd" name="passwd"  type="password" aria-describedby="emailHelp" placeholder="Пароль">
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" id="lite" name="lite" type="checkbox" checked="true"> lite-версия (Обновление от )</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-lg btn-block btn-primary" name="submit" type="submit" id="login">Войти</button>
                        </div>

                        <div class="text-center">
                            <a class="d-block small" href="forgot-password.php">Востановить пароль</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>