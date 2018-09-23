<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../../include/db_config.php';
include '../../../include/resource.array.php';
include '../../../include/voda.php';
$id_object = $_GET['plc'];
$type_arch = 2;
$date1 = $_GET['date1'];
$date2 = $_GET['date2'];


$sql_date = pg_query('SELECT DISTINCT 
        ("Tepl"."Arhiv_cnt"."DateValue") AS "FIELD_1"
      FROM
        "Tepl"."ParamResPlc_cnt"
        INNER JOIN "Tepl"."Arhiv_cnt" ON ("Tepl"."ParamResPlc_cnt".prp_id = "Tepl"."Arhiv_cnt".pr_id)
        INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."ParamResPlc_cnt".plc_id = "Tepl"."Places_cnt".plc_id)
      WHERE
        "Tepl"."Places_cnt".plc_id = ' . $id_object . ' AND 
        "Tepl"."Arhiv_cnt".typ_arh = ' . $type_arch . '  AND
        "Tepl"."Arhiv_cnt"."DateValue" >= \'' . $date1 . '\' AND 
        "Tepl"."Arhiv_cnt"."DateValue" <= \'' . $date2 . '\'
      ORDER BY
        "Tepl"."Arhiv_cnt"."DateValue"');



$res = array_resource_plc($id_object, $_SESSION['auth']['login']);

$n = 1;
$voda = array();
while ($aData = pg_fetch_row($sql_date)) {



    switch ($type_arch) {
        case 1:
            $val['date'] = date('d.m.Y', strtotime($aData[0]));
            $val['time'] = date('H:i', strtotime($aData[0]));
            $date = date('Y-m-d H:i', strtotime($aData[0]));
            break;
        case 2:
            $val['date'] = date('d.m.Y', strtotime($aData[0]));
            $val['time'] = date('H:i', strtotime($aData[0]));
            $date = date('Y-m-d', strtotime($aData[0]));

            break;
        case 3:
            $val['date'] = date('m.Y', strtotime($aData[0]));
            $val['time'] = date('H:i', strtotime($aData[0]));
            $date = date('Y-m-d', strtotime($aData[0]));
            break;
    }
    $arr['date'][] = date('d.m.Y', strtotime($date));
    unset($array_arch);
    $sql_archive = pg_query('SELECT 
                                "Tepl"."Arhiv_cnt"."DataValue" AS value,
                                "Tepl"."ParamResPlc_cnt"."ParamRes_id" AS param
                              FROM
                                "Tepl"."ParamResPlc_cnt"
                                INNER JOIN "Tepl"."Arhiv_cnt" ON ("Tepl"."ParamResPlc_cnt".prp_id = "Tepl"."Arhiv_cnt".pr_id)
                              WHERE
                                "Tepl"."ParamResPlc_cnt".plc_id = ' . $id_object . ' AND 
                                "Tepl"."Arhiv_cnt".typ_arh = ' . $type_arch . ' AND 
                                "Tepl"."Arhiv_cnt"."DateValue" = \'' . $date . '\'
                            ORDER BY
                              "Tepl"."ParamResPlc_cnt"."ParamRes_id"
      
                                ');

    $array_arch = pg_fetch_all($sql_archive);

    for ($i = 0; $i < count($res); $i++) {
        $k = array_search($res[$i]['id'], array_column($array_arch, 'param'));
        if ($k !== false) {
            $arr['value'][$i]['val'][] = floatval(sprintf("%.2f", $array_arch[$k]['value']));

            $val['res_' . $res[$i]['id']] = sprintf("%.2f", $array_arch[$k]['value']);
            $voda[] = array(
                'date' => $date,
                'param' => $array_arch[$k]['param'],
                'value' => sprintf("%.2f", $array_arch[$k]['value'])
            );
        } else {
            $arr['value'][$i]['val'][] = 0;
        }
    }
}

for ($i = 0; $i < count($res); $i++) {
    $arr['value'][$i]['name'] = $res[$i]['resource'];
    $arr['value'][$i]['data'] = voda_values($arr['value'][$i]['val']);
}

echo json_encode($arr, JSON_UNESCAPED_UNICODE);
