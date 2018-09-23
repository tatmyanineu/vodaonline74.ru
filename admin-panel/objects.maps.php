<?php
session_start();
include '../include/db_config.php';


$file = file_get_contents('../tmp/location.json');
$array = unserialize($file);
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
        <script src="https://maps.api.2gis.ru/2.0/loader.js"></script>
    </head>
    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand" href="#">Измерительная система контроля и учета энергоресурсов</a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

                    <?php include '../include/menu.php'; ?>

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



                <!-- Breadcrumbs-->

                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Карта</a>
                    </li>
                    <li class="breadcrumb-item active">Обьекты</li>
                    <input class="form-control mr-auto">
                </ol>

                <img src="css/img/Green.png" alt=""/> -  Рабочие объекты;  
                <img src="css/img/Black.png" alt=""/> - Нерабочие объекты;
                <img src="css/img/Blue.png" alt=""/> - Нет импульса на одном из вводов;
                <img src="css/img/Red.png" alt=""/> - Нет данных за последние 7 дней;
                <img src="css/img/Orange.png" alt=""/> - Возможна неисрпавность импульса;
                <hr>

                <div id="map" style="width: 100%; height: 80vh;"></div>
                <script>

                    $(document).ready(function () {

                        var json = <?php echo json_encode($array, JSON_UNESCAPED_UNICODE); ?>;
                        console.log(json);
                        DG.then(function () {

                            var myIconOk = DG.icon({
                                iconUrl: 'css/img/Green.png',
                                iconSize: [22, 34]
                            });
                            var myIconError = DG.icon({
                                iconUrl: 'css/img/Black.png',
                                iconSize: [22, 34]
                            });
                            var myIconImp = DG.icon({
                                iconUrl: 'css/img/Blue.png',
                                iconSize: [22, 34]
                            });
                            var myIconDang = DG.icon({
                                iconUrl: 'css/img/Red.png',
                                iconSize: [22, 34]
                            });
                            var myIconWarn = DG.icon({
                                iconUrl: 'css/img/Orange.png',
                                iconSize: [22, 34]
                            });
                            var map = DG.map('map', {
                                center: [55.16828, 61.433487],
                                zoom: 11
                            });
                            for (var i = 0; i < json.length; i++) {
                                if (json[i].location != "") {
                                    var k = json[i].location;
                                    k = k.split('. ');
                                    var values = json[i].values;
                                    var data = "";
                                    for (var j = 0; j < values.length; j++) {
                                        data += "_______________________________<br>" + values[j].name + " <br> Дата: " + json[i].date + " <br> Показания: " + values[j].volume + " м3 <br>";
                                    }
                                    var title = "<b> Адрес: " + json[i].adr + "</b><br>\n\
                                                УК: " + json[i].uk + " <br>\n\
                                                ЖЭК: " + json[i].jk + " <br>\n\
                                                место установки : " + json[i].pd + " <br>\n\
                                                " + data + " ";


                                    switch (json[i].error) {
                                        case 0:
                                            var tochka = DG.marker([k[0], k[1]], {icon: myIconOk}, {title: title});
                                            break;
                                        case 1:
                                            var tochka = DG.marker([k[0], k[1]], {icon: myIconDang}, {title: title});
                                            break;
                                        case 2:
                                            var tochka = DG.marker([k[0], k[1]], {icon: myIconImp}, {title: title});
                                            break;
                                        case 3:
                                            var tochka = DG.marker([k[0], k[1]], {icon: myIconError}, {title: title});
                                            break;
                                        case 4:
                                            var tochka = DG.marker([k[0], k[1]], {icon: myIconWarn}, {title: title});
                                            break;
                                    }


                                    tochka.bindPopup(title, {
                                        maxWidth: 420,
                                        sprawling: true
                                    });
                                    tochka.addTo(map);

                                } else {
                                    console.log("Обьект -> " + json[i].adr + " не выведен на карту");
                                }

                            }


                        });





                    });
                </script>

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