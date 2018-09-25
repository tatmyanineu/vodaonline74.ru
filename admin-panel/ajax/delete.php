<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../include/db_config.php';


switch ($_POST['action']) {
    case "exception":
        exception($_POST['id']);
        break;
}

function exception($id) {
    $sql = pg_query('DELETE FROM exception WHERE id=' . $id);
}
