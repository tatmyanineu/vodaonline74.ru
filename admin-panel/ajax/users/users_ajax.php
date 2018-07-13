<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../../../include/db_config.php';
session_start();

$sqlAllGroup = pg_query('SELECT DISTINCT  * FROM "Tepl"."UserGroups" ORDER BY "Tepl"."UserGroups".grp_id');
$i=1;
while ($row= pg_fetch_row($sqlAllGroup)){
    $arrData['data'][] = array(
        'num'=>$i,
        'id'=>$row[0],
        'name'=>$row[1],
        'comm'=>$row[2],
    );
    $i++;
}

$main = array();

$column['columns'] = array(
    array("title" => "№", "data" => "num"),
    array("title" => "Название группы", "data" => "name"),
    array("title" => "Комментарий", "data" => "comm"),
    array("title" => "", "data" => null, )
);

$main = array_merge($main, $column);
$main = array_merge($main, $arrData);
echo json_encode($main, JSON_UNESCAPED_UNICODE);


