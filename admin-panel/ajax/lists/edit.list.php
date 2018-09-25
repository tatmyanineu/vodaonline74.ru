<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../../include/db_config.php';


switch ($_POST['types']) {
    case 'exceptions':
        //исключения
        exceprion($_POST['id'], $_POST['name']);
        break;
    case 'tickets':
        //перечень работ по заявкам
        tickets($_POST['id'], $_POST['name']);
        break;
}
