<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../../../include/db_config.php';
session_start();

$sqlUsersForGroup = pg_query('SELECT DISTINCT 
  "Tepl"."User_cnt".usr_id,
  "Tepl"."User_cnt"."Login",
  "Tepl"."User_cnt"."Password",
  "Tepl"."User_cnt"."SurName",
  "Tepl"."User_cnt"."PatronName",
  "Tepl"."User_cnt"."Comment",
  "Tepl"."User_cnt"."Privileges"
FROM
  "Tepl"."GroupToUserRelations"
  INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
WHERE
  "Tepl"."GroupToUserRelations".grp_id = ' . $_POST['group'] . '
');



while ($row = pg_fetch_row($sqlUsersForGroup)) {
    $data['data'][]=array(
        'id'=>$row[0],
        'login'=>$row[1],
        'passwd'=>$row[2],
        'name'=>$row[3],
        'surName'=>$row[4],
        'comments'=>$row[5],
        'priv'=>$row[6]
    );
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);