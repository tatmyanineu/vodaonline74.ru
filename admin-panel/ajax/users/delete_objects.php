<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../../../include/db_config.php';
session_start();


$data = $_POST['data'];

for ($i = 0; $i < count($data); $i++) {

    $sql = pg_query('DELETE FROM "Tepl"."PlaceGroupRelations"
        WHERE plc_id = ' . $data[$i] . ' AND grp_id = ' . $_POST['group'] . '');
}
