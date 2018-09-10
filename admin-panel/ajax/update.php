<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../../include/db_config.php';

function update_fias_table($id, $plc_id, $fias, $cn_id) {
    pg_query('UPDATE fias_cnt SET fias=\'' . $fias . '\', id_cn=\'' . $cn_id . '\' WHERE id=' . $id . '');
}

function update_prp_con_table($id_connect, $date, $numb, $id, $cdog) {
    $sql = pg_query('UPDATE prop_connect SET id_connect=\'' . $id_connect . '\', date=\'' . $date . '\', cnt_numb=\'' . $numb . '\' ,cdog = \'' . $cdog . '\'
                        WHERE id=' . $id);
    $i++;
}

function update_location($id, $location) {
    $sql = pg_query('UPDATE "Tepl"."PropPlc_cnt" SET  "ValueProp"=\'' . $location . '\' WHERE pr_id=' . $id . '');
}
