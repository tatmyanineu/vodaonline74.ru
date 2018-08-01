<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include '../../../include/db_config.php';
session_start();


$data = $_POST['data'];
$group = $_POST['group'];


for ($i = 0; $i < count($data); $i++) {
    $sql_delUser = pg_query('DELETE FROM "Tepl"."User_cnt"
                                WHERE usr_id=' . $data[$i] . '');
    $sql_delUserGroup = pg_query('DELETE FROM "Tepl"."GroupToUserRelations"
                                WHERE usr_id = ' . $data[$i] . ' and grp_id =' . $group . '');
}




