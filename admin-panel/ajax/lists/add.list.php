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
        exceprion($_POST['name']);
        break;
    case 'tickets':
        //перечень работ по заявкам
        tickets($_POST['name']);
        break;
}

function exceprion($name) {
    $sql = pg_query('INSERT INTO list_exception(text_excep) VALUES (\'' . $name . '\')');
}
