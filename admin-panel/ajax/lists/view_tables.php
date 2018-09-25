<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Работа со списками

session_start();
include '../../../include/db_config.php';

switch ($_POST['types']) {
    case 'exceptions':
        //исключения
        view_exceprion();
        break;
    case 'tickets':
        //перечень работ по заявкам
        view_tickets();
        break;
}

function view_exceprion() {
    $sql = pg_query('SELECT 
            public.list_exception.id,
            public.list_exception.text_excep as name
          FROM
            public.list_exception');
    
    $array['data']= pg_fetch_all($sql);
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}
