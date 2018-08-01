<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include '../../../include/db_config.php';
include '../../query/query.object.list.php';

session_start();

$sql_typeObject = pg_query('SELECT DISTINCT 
  "Tepl"."Places_cnt".typ_id
FROM
  "Tepl"."PlaceGroupRelations"
  INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PlaceGroupRelations".plc_id = "Tepl"."Places_cnt".plc_id)
WHERE
  "Tepl"."PlaceGroupRelations".grp_id = ' . $_POST['group'] . '
ORDER BY
  "Tepl"."Places_cnt".typ_id');

$count = pg_fetch_all($sql_typeObject);



$result = query_objects(count($count), $_POST['group']);
$result = pg_query($result);
while ($row = pg_fetch_row($result)) {
    $data['data'][] = array(
        'id' => $row[2],
        'city'=>$row[0],
        'address'=>$row[1],
        'comm'=>''
    );
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);