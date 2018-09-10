<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../../include/db_config.php';
include '../get_objects.query.php';



$sql = pg_query(query_objects_login($_SESSION['auth']['login']));
$plc = pg_fetch_all($sql);


$sql_location = pg_query('SELECT 
  "Tepl"."PropPlc_cnt".plc_id,
  "Tepl"."PropPlc_cnt"."ValueProp"
FROM
  "Tepl"."PropPlc_cnt"
WHERE
  "Tepl"."PropPlc_cnt".prop_id = 26');
$loc = pg_fetch_all($sql_location);