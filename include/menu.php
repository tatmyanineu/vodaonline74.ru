<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include './db_config.php';

switch ($_SESSION['priv']) {
    case 0:
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
        . '</li>'
        . '<li class="nav-item">'
        . '<a class="nav-link pl-0" href="users.group.php"><i class="fas fa-users-cog"></i> <span class="d-none d-md-inline">Настройка пользователей</span></a>'
        . '</li>';


        break;
    case 31:
        break;
}