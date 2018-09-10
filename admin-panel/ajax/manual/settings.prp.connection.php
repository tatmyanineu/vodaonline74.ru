<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../../include/db_config.php';
include '../get_objects.query.php';


$sql_tree = pg_query('SELECT DISTINCT 
  "Tepl"."Places_cnt".typ_id
FROM
  "Tepl"."PlaceGroupRelations"
  INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PlaceGroupRelations".plc_id = "Tepl"."Places_cnt".plc_id)
  INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
  INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
WHERE
  "Tepl"."User_cnt"."Login" = \'' . $_SESSION['auth']['login'] . '\'
ORDER BY
  "Tepl"."Places_cnt".typ_id');

$count = pg_fetch_all($sql_tree);
$result = query_objects_login($_SESSION['auth']['login']);
$sql = pg_query($result);

$data = pg_fetch_all($sql);

$sql = pg_query('SELECT 
  public.prop_connect.id,
  public.prop_connect.prp_id,
  public.prop_connect.id_connect,
  public.prop_connect.date,
  public.prop_connect.cnt_numb,
  public.prop_connect.plc_id,
  public.prop_connect.cdog
FROM
  public.prop_connect');

$conncect = pg_fetch_all($sql);


$n = 0;
for ($i = 0; $i < count($conncect); $i++) {
    $n++;
    $k = array_search($conncect[$i]['plc_id'], array_column($data, 'plc_id'));
    if ($k !== false) {
        $arrData['data'][] = array(
            'num' => $n,
            'plc_id' => $conncect[$i]['plc_id'],
            'adr' => $data[$k]['adr'],
            'prp' => $conncect[$i]['prp_id'],
            'number' => $conncect[$i]['cnt_numb'],
            'date' =>date('d.m.Y', strtotime($conncect[$i]['date'])),
            'cdog' => $conncect[$i]['cdog']
        );
    } 
}

echo json_encode($arrData, JSON_UNESCAPED_UNICODE);