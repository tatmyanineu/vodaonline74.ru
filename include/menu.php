<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include './db_config.php';

switch ($_SESSION['auth']['role']) {
    case 0:
        echo '<li class="nav-item">'
        . '<a class="nav-link pl-0" href="objects.stats.php"><i class="fas fa-chalkboard"></i> <span class="d-none d-md-inline">Обьекты информация</span></a>'
        . '</li>'
        . '<li class="nav-item">'
        . '<a class="nav-link pl-0"><i class="fas fa-file-signature"></i> <span class="d-none d-md-inline">Отчеты</span></a>'
        . '</li>';


        break;


    case 14:
        echo '<li class="nav-item">'
        . '<a class="nav-link pl-0" href="objects.stats.php"><i class="fas fa-chalkboard"></i> <span class="d-none d-md-inline">Обьекты информация</span></a>'
        . '</li>'
        . '<li class="nav-item">'
        . '<a class="nav-link pl-0"><i class="fas fa-file-signature"></i> <span class="d-none d-md-inline">Отчеты</span></a>'
        . '</li>'
        . '<li class="nav-item">'
        . '<a class="nav-link pl-0" href="objects.maps.php"><i class="fas fa-map-marked-alt"></i> <span class="d-none d-md-inline">Обьекты на карте</span></a>'
        . '</li>'
        . '<li class="nav-item">'
        . '<a class="nav-link pl-0" href="tikets.php"><i class="fas fa-bookmark"></i> <span class="d-none d-md-inline">Заявки на обслуживание</span></a>'
        . '</li>';


        break;
    case 31:
    case 21:
        echo
        ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Объекты информация">'
        . '<a class="nav-link" href="objects.stats.php">'
        . '<i class="fas fa-chalkboard"></i>'
        . ' <span class="nav-link-text">Объекты информация</span>'
        . '</a>'
        . '</li>'
        . ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Объекты список">'
        . '<a class="nav-link" href="objects.view.php">'
        . '<i class="far fa-list-alt"></i></i>'
        . ' <span class="nav-link-text">Объекты список</span>'
        . '</a>'
        . '</li>'
        . ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Отчеты">'
        . '<a class="nav-link" href="reports.php">'
        . '<i class="fas fa-file-signature"></i>'
        . ' <span class="nav-link-text">Отчеты</span>'
        . '</a>'
        . '</li>'
        . ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Обьекты на карте">'
        . '<a class="nav-link" href="objects.maps.php">'
        . '<i class="fas fa-map-marked-alt"></i>'
        . ' <span class="nav-link-text">Обьекты на карте</span>'
        . '</a>'
        . '</li>'
        . ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Заявки на обслуживание">'
        . '<a class="nav-link" href="tikets.php">'
        . '<i class="fas fa-bookmark"></i>'
        . ' <span class="nav-link-text">Заявки на обслуживание</span>'
        . '</a>'
        . '</li>'
        . ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Настройка пользователей">'
        . '<a class="nav-link" href="users.group.php">'
        . '<i class="fas fa-users-cog"></i> '
        . ' <span class="nav-link-text">Настройка пользователей</span>'
        . '</a>'
        . '</li>'
        . ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Настройка пользователей">'
        . '<a class="nav-link" href="manual.php">'
        . '<i class="fas fa-cog"></i>'
        . ' <span class="nav-link-text">Справочники</span>'
        . '</a>'
        . '</li>'
        . '';
        break;
}