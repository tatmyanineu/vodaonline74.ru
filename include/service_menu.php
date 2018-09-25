<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include './db_config.php';

switch ($_SESSION['auth']['role']) {
    case 21:
        echo ' <li class="nav-item mt-3" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить в исключения">'
        . '<a class="nav-link" href="object.exception.php?id=' . $_GET['id'] . '">'
        . '<i class="fas fa-exclamation-circle"></i>'
        . ' <span class="nav-link-text">Добавить в исключения</span>'
        . '</a>'
        . '';
        break;
    case 31:
        echo ' <li class="nav-item mt-3" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить в исключения">'
        . '<a class="nav-link" href="object.exception.php?id=' . $_GET['id'] . '">'
        . '<i class="fas fa-exclamation-circle"></i>'
        . ' <span class="nav-link-text">Добавить в исключения</span>'
        . '</a>'
        . '';
        echo ' <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Редактировать данные">'
        . '<a class="nav-link" href="object.settings.php?id=' . $_GET['id'] . '">'
        . '<i class="fas fa-edit"></i>'
        . ' <span class="nav-link-text">Объекты информация</span>'
        . '</a>'
        . '';
        break;
}