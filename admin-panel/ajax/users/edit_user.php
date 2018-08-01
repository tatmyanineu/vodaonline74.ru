<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../../../include/db_config.php';
session_start();


$sql = pg_query('SELECT *
FROM
  "Tepl"."User_cnt"
WHERE
  "Tepl"."User_cnt".usr_id = ' . $_POST['id']);
$usr = pg_fetch_all($sql);

$data = array(
    'login' => $usr[0]['Login'],
    'password' => $usr[0]['Password'],
    'name' => $usr[0]['SurName'],
    'surname' => $usr[0]['PatronName'],
    'role' => $usr[0]['Privileges']
);
$i++;
echo json_encode($data, JSON_UNESCAPED_UNICODE);