<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include '../../include/db_config.php';
include './add.php';
include './update.php';

switch ($_POST['action']) {
    case "fias_check":
        fias_check($_POST['plc'], $_POST['fias'], $_POST['cn']);
        break;
    case "prop.connec.edit":
        property_connection($_POST['json']);
        break;
    case "loc_check":
        location_check($_POST['plc'], $_POST['location']);
        break;
}

//ФИАС fias_cnt
function fias_check($plc_id, $fias, $cn_id) {
    $sql = pg_query('SELECT id
                        FROM fias_cnt 
                        WHERE plc_id=' . $plc_id . '');

    if (pg_num_rows($sql) == 0) {
        add_fias_table($plc_id, $fias, $cn_id);
    } else {
        $id = pg_fetch_row($sql, 0);
        update_fias_table($id[0], $plc_id, $fias, $cn_id);
    }
}

//подключения prop_connect 
function property_connection($data) {
    $sql_prp = pg_query('SELECT id, prp_id, id_connect, date, cnt_numb, plc_id, cdog
                          FROM prop_connect');
    $prp = pg_fetch_all($sql_prp);
    $json = $_POST['json'];
    for ($i = 0; $i < count($json); $i++) {
        $k = array_search($json[$i]['prp'], array_column($prp, 'prp_id'));
        if ($k !== false and $prp !== false) {
            update_prp_con_table($json[$i]['id_connect'], $json[$i]['date'], $json[$i]['numb'], $prp[$k]['id'], $json[$i]['cdog']);
        } else {
            add_prp_con_table($json[$i]['prp'], $json[$i]['id_connect'], $json[$i]['date'], $json[$i]['numb'], $json[$i]['plc'], $json[$i]['cdog']);
        }
    }
}

function location_check($plc, $location) {
    $sql = pg_query('SELECT 
                "Tepl"."PropPlc_cnt".pr_id
              FROM
                "Tepl"."PropPlc_cnt"
              WHERE
                "Tepl"."PropPlc_cnt".prop_id = 26 AND 
                "Tepl"."PropPlc_cnt".plc_id = ' . $plc . '');

    if (pg_num_rows($sql) == 0) {
        add_location($plc, $location);
    } else {
        $id = pg_fetch_row($sql, 0);
        update_location($id[0], $location);
    }
}
