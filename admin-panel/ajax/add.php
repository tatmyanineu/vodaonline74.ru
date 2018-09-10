<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../../include/db_config.php';
session_start();

//Добавление данные в таблицу с ФИАС кодом
function add_fias_table($plc_id, $fias, $cn_id) {
    pg_query('INSERT INTO fias_cnt(fias, plc_id, id_cn)  VALUES (\'' . $fias . '\', ' . $plc_id . ' ,\'' . $cn_id . '\' );');
}


function add_prp_con_table($prp, $id_connect, $date, $numb, $plc, $cdog ) {
    $sql = pg_query('INSERT INTO prop_connect(prp_id, id_connect, date, cnt_numb, plc_id, cdog)
                VALUES (' . $prp . ', \'' . $id_connect . '\', \'' .$date . '\', \'' . $numb . '\', ' . $plc . ', \'' . $cdog . '\')');

}


function add_location($plc, $location){
    $sql= pg_query('SELECT max(pr_id) FROM "Tepl"."PropPlc_cnt";');
    $id = pg_fetch_row($sql, 0);
    $id[0]++;
    pg_query('INSERT INTO "Tepl"."PropPlc_cnt"(pr_id, "ValueProp", plc_id, prop_id)
                VALUES ('.$id[0].', \''.$location.'\', '.$plc.', 26)');
}