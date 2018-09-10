<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../../../include/db_config.php';
include '../get_objects.query.php';


session_start();

$sql_loc = pg_query('SELECT 
  "Tepl"."PropPlc_cnt"."ValueProp" AS data,
  "Tepl"."Places_cnt".plc_id
FROM
  "Tepl"."PropPlc_cnt"
  INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PropPlc_cnt".plc_id = "Tepl"."Places_cnt".plc_id)
WHERE
  "Tepl"."PropPlc_cnt".prop_id = 26');

$loc = pg_fetch_all($sql_loc);
$query = query_objects_login($_SESSION['auth']['login']);
$sql = pg_query($query);

$plc = pg_fetch_all($sql);
$n = 0;
for ($i = 0; $i < count($plc); $i++) {
    $n++;
    $k = array_search($plc[$i]['plc_id'], array_column($loc, 'plc_id'));
    $s = ' ';
    $pos = strpos($plc[$i]['adr'], $s);
    $adr = substr($plc[$i]['adr'], $pos);
    if ($k !== false) {
        $arrData['data'][] = array(
            'numb' => $n,
            'plc_id'=>$plc[$i]['plc_id'],
            'city' => $plc[$i]['city'],
            'adr' => $adr,
            'location' => $loc[$k]['data']
        );
    } else {
        $arrData['data'][] = array(
            'numb' => $n,
            'plc_id'=>$plc[$i]['plc_id'],
            'city' => $plc[$i]['city'],
            'adr' => $adr,
            'location' => 'НЕТ ДАННЫХ'
        );
    }
}


echo json_encode($arrData, JSON_UNESCAPED_UNICODE);