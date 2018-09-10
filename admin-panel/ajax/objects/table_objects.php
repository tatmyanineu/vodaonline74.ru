<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include '../../../include/db_config.php';

switch ($_SESSION['auth']['role']) {
    case 0:
        $filename = $_SESSION['auth']['login'] . "_" . date('Y-m-d');
        $file = file_get_contents('../../../tmp/' . $filename . '.tmp');
        $array = unserialize($file);
        break;
    case 14:
        $filename = $_SESSION['auth']['login'] . "_" . date('Y-m-d');
        $file = file_get_contents('../../../tmp/' . $filename . '.tmp');
        $array = unserialize($file);
        break;
    case 21:
    case 31:
        $filename = "admin_" . date('Y-m-d');
        $file = file_get_contents('../../../tmp/' . $filename . '.tmp');
        $array = unserialize($file);
        break;
}

$tmp1 = Array();
foreach ($array as &$ma) {
    $tmp1[] = &$ma["adr"];
}
$tmp2 = Array();
foreach ($array as &$ma) {
    $tmp2[] = &$ma["param"];
}
array_multisort($tmp1, $tmp2, $array);


$sql_port = pg_query('SELECT 
  "Tepl"."ProperConnect_cnt"."ValueProp" AS numb,
  "Places_cnt3".plc_id
FROM
  "Tepl"."Places_cnt" "Places_cnt3"
  INNER JOIN "Tepl"."Connect_cnt" ON ("Places_cnt3".plc_id = "Tepl"."Connect_cnt".plc_id)
  INNER JOIN "Tepl"."Connect_cnt_Config" ON ("Tepl"."Connect_cnt".con_id = "Tepl"."Connect_cnt_Config".con_id)
  INNER JOIN "Tepl"."ProperConnect_cnt" ON ("Tepl"."Connect_cnt_Config"."Conf_id" = "Tepl"."ProperConnect_cnt"."Conf_id")
WHERE
  "Tepl"."ProperConnect_cnt".typ_id = 6');
$port = pg_fetch_all($sql_port);


$sql_resource = pg_query($conn, 'SELECT DISTINCT 
                    concat("Tepl"."Resourse_cnt"."Name", \': \', "Tepl"."ParametrResourse"."Name") AS resource,
                    "Tepl"."ParamResPlc_cnt"."ParamRes_id" AS id
                  FROM
                    "Tepl"."ParametrResourse"
                    INNER JOIN "Tepl"."ParamResPlc_cnt" ON ("Tepl"."ParametrResourse"."ParamRes_id" = "Tepl"."ParamResPlc_cnt"."ParamRes_id")
                    INNER JOIN "Tepl"."Resourse_cnt" ON ("Tepl"."ParametrResourse".res_id = "Tepl"."Resourse_cnt".res_id)
                    INNER JOIN "Tepl"."ParamResGroupRelations" ON ("Tepl"."ParamResGroupRelations".prp_id = "Tepl"."ParamResPlc_cnt".prp_id)
                    INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."GroupToUserRelations".grp_id = "Tepl"."ParamResGroupRelations".grp_id)
                    INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."User_cnt".usr_id = "Tepl"."GroupToUserRelations".usr_id)
                  WHERE

                    "Tepl"."User_cnt"."Login" = \'' . $_SESSION['auth']['login'] . '\'
                    ORDER BY
                    resource');
$res = pg_fetch_all($sql_resource);


$col_res = array();
for ($i = 0; $i < count($res); $i++) {
    array_push($col_res, array(
        'title' => $res[$i]['resource'],
        'data' => 'res_' . $res[$i]['id']
            )
    );
}

$column = array(
    array("title" => "№", "data" => "num"),
    array("title" => "Город", "data" => "city"),
    array("title" => "Адрес", "data" => "adr"),
    array("title" => "Дата", "data" => "date"),
);

$col_prop = array(
    array("title" => "Порт", "data" => "port"),
    array("title" => "Упр.Комп.", "data" => "uk"),
    array("title" => "С.О.", "data" => "serv")
);

$column = array_merge($column, $col_res);


$col['columns'] = array_merge($column, $col_prop);
unset($column);
unset($col_prop);
unset($col_res);

$sql_YK = pg_query('SELECT 
        "Tepl"."PropPlc_cnt"."ValueProp" as name,
        "Places_cnt3".plc_id
      FROM
        "Tepl"."Places_cnt" "Places_cnt3"
        INNER JOIN "Tepl"."PropPlc_cnt" ON ("Places_cnt3".plc_id = "Tepl"."PropPlc_cnt".plc_id)
      WHERE
        "Tepl"."PropPlc_cnt".prop_id = 27');

$uk = pg_fetch_all($sql_YK);

//var_dump($array);

$n = 1;
for ($i = 0; $i < count($array); $i++) {
    $plc[] = $array[$i]['plc_id'];
}

$obj = $plc;
$plc = array_unique($plc);
$plc = array_values($plc);
$n = 1;
for ($i = 0; $i < count($plc); $i++) {

    $k = array_search($plc[$i], array_column($array, 'plc_id'));
    if ($k !== FALSE) {
        unset($val);
        $val['num'] = $n;
        $val['plc_id'] = $plc[$i];
        $val['city'] = $array[$k]['city'];
        $val['adr'] = $array[$k]['adr'];

        $val['prp'] = $array[$k]['prp'];
        $keys = array_keys($obj, $plc[$i]);
        unset($value);
        $d = 0;
        for ($j = 0; $j < count($keys); $j++) {
            if ($array[$keys[$j]]['date'] != '-') {
                if (strtotime($d) < strtotime($array[$keys[$j]]['date'])) {
                    $d = $array[$keys[$j]]['date'];
                }
            }

            $value[] = array(
                'param' => $array[$keys[$j]]['param'],
                'value' => $array[$keys[$j]]['value'],
                'error' => $array[$keys[$j]]['error'],
                'date' => $array[$j]['date']
            );
        }
        if ($d == 0) {
            $val['date'] = '-';
        } else {
            $val['date'] = $d;
        }

        for ($j = 0; $j < count($res); $j++) {
            $v = array_search($res[$j]['id'], array_column($value, 'param'));
            if ($v !== FALSE) {
                $val['res_' . $res[$j]['id']] = $value[$v]['value'];
                $val['error_' . $res[$j]['id']] = $value[$v]['error'];
            } else {
                $val['res_' . $res[$j]['id']] = '';
                $val['error_' . $res[$j]['id']] = 0;
            }
        }
        $n++;

        $p = array_search($plc[$i], array_column($port, 'plc_id'));
        if ($p !== false) {
            $val['port'] = $port[$p]['numb'];
        } else {
            $val['port'] = '';
        }

        $u = array_search($plc[$i], array_column($uk, 'plc_id'));
        if ($u !== false) {
            $val['uk'] = $uk[$u]['name'];
        } else {
            $val['uk'] = '';
        }
        $val['serv'] = '';
        //var_dump($val);
        $data['data'][] = $val;
    }
}
$main = array();
$main = array_merge($main, $col);
$main = array_merge($main, $data);

echo json_encode($main, JSON_UNESCAPED_UNICODE);
