<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../include/db_config.php';
$login = "admin";


$filename = "admin_" . date('Y-m-d');
$file = file_get_contents('../tmp/' . $filename . '.tmp');
$array = unserialize($file);


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

                    "Tepl"."User_cnt"."Login" = \'' . $login . '\'
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

$sql_location = pg_query('SELECT 
  "Tepl"."PropPlc_cnt"."ValueProp" AS data,
  "Tepl"."Places_cnt".plc_id
FROM
  "Tepl"."PropPlc_cnt"
  INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PropPlc_cnt".plc_id = "Tepl"."Places_cnt".plc_id)
WHERE
  "Tepl"."PropPlc_cnt".prop_id = 26');

$location = pg_fetch_all($sql_location);


$sql_pd = pg_query('SELECT 
        "Tepl"."PropPlc_cnt"."ValueProp" as name,
        "Places_cnt3".plc_id
      FROM
        "Tepl"."Places_cnt" "Places_cnt3"
        INNER JOIN "Tepl"."PropPlc_cnt" ON ("Places_cnt3".plc_id = "Tepl"."PropPlc_cnt".plc_id)
      WHERE
        "Tepl"."PropPlc_cnt".prop_id = 32');

$pd = pg_fetch_all($sql_pd);

$sql_jk = pg_query('SELECT 
        "Tepl"."PropPlc_cnt"."ValueProp" as name,
        "Places_cnt3".plc_id
      FROM
        "Tepl"."Places_cnt" "Places_cnt3"
        INNER JOIN "Tepl"."PropPlc_cnt" ON ("Places_cnt3".plc_id = "Tepl"."PropPlc_cnt".plc_id)
      WHERE
        "Tepl"."PropPlc_cnt".prop_id = 28');

$jk = pg_fetch_all($sql_jk);

$sql_YK = pg_query('SELECT 
        "Tepl"."PropPlc_cnt"."ValueProp" as name,
        "Places_cnt3".plc_id
      FROM
        "Tepl"."Places_cnt" "Places_cnt3"
        INNER JOIN "Tepl"."PropPlc_cnt" ON ("Places_cnt3".plc_id = "Tepl"."PropPlc_cnt".plc_id)
      WHERE
        "Tepl"."PropPlc_cnt".prop_id = 27');

$uk = pg_fetch_all($sql_YK);


for ($i = 0; $i < count($array); $i++) {
    $plc[] = $array[$i]['plc_id'];
}

$obj = $plc;
$plc = array_unique($plc);
$plc = array_values($plc);



for ($i = 0; $i < count($plc); $i++) {

    $k = array_search($plc[$i], array_column($array, 'plc_id'));
    if ($k !== FALSE) {
        unset($val);
        $val['plc_id'] = $plc[$i];
        $val['city'] = $array[$k]['city'];
        $val['adr'] = $array[$k]['adr'];

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
        $err = 0;
        for ($j = 0; $j < count($res); $j++) {
            $v = array_search($res[$j]['id'], array_column($value, 'param'));
            if ($v !== FALSE) {

                $val['values'][] = array(
                    'name' => $res[$j]['resource'],
                    'volume' => $value[$v]['value'],
                    'error' => $value[$v]['error']
                );

                if ($err != $value[$v]['error']) {
                    $err = $value[$v]['error'];
                }
            }
        }
        $n++;
        $val['error'] = $err;

        $l = array_search($plc[$i], array_column($location, 'plc_id'));
        if ($l !== false) {
            $val['location'] = $location[$l]['data'];
        } else {
            $val['location'] = '';
        }

        $m = array_search($plc[$i], array_column($jk, 'plc_id'));
        if ($m !== false) {
            $val['jk'] = $jk[$m]['name'];
        } else {
            $val['jk'] = 'нет данных';
        }

        $n = array_search($plc[$i], array_column($pd, 'plc_id'));
        if ($m !== false) {
            $val['pd'] = $pd[$n]['name'];
        } else {
            $val['pd'] = 'нет данных';
        }

        $u = array_search($plc[$i], array_column($uk, 'plc_id'));
        if ($u !== false) {
            $val['uk'] = $uk[$u]['name'];
        } else {
            $val['uk'] = 'нет данных';
        }

        //var_dump($val);
        $data[] = $val;
    }
}


$tmp1 = Array();
foreach ($data as &$ma) {
    $tmp1[] = &$ma["adr"];
}

array_multisort($tmp1, $data);



var_dump($data);

$str_value = serialize($data);
$fp = fopen('../tmp/location.json', 'w');
fwrite($fp, $str_value);
fclose($fp);
