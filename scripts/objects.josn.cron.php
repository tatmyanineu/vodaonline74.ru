<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include '../include/db_config.php';


$login = 'admin';


$date = date('Y-m-d', strtotime('-1 month'));

$sql_tree = pg_query('SELECT DISTINCT 
  "Tepl"."Places_cnt".typ_id
FROM
  "Tepl"."PlaceGroupRelations"
  INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PlaceGroupRelations".plc_id = "Tepl"."Places_cnt".plc_id)
  INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
  INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
WHERE
  "Tepl"."User_cnt"."Login" = \'' . $login . '\'
ORDER BY
  "Tepl"."Places_cnt".typ_id');

$count = pg_fetch_all($sql_tree);
$result = query_objects_login(count($count), $login);

$result = pg_query($result);
$plc = pg_fetch_all($result);

$sql_archive = pg_query('SELECT DISTINCT 
  "Tepl"."Arhiv_cnt"."DateValue" as date,
  "Tepl"."Arhiv_cnt"."DataValue" as value,
  "Tepl"."ParamResPlc_cnt"."ParamRes_id" as param,
  "Tepl"."ParamResPlc_cnt".prp_id as prp,
  "Tepl"."ParamResPlc_cnt".plc_id
FROM
  "Tepl"."ParamResPlc_cnt"
  LEFT OUTER JOIN "Tepl"."Arhiv_cnt" ON ("Tepl"."ParamResPlc_cnt".prp_id = "Tepl"."Arhiv_cnt".pr_id)
  INNER JOIN "Tepl"."PlaceGroupRelations" ON ("Tepl"."ParamResPlc_cnt".plc_id = "Tepl"."PlaceGroupRelations".plc_id)
  INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
  INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
WHERE
  "Tepl"."Arhiv_cnt".typ_arh = 2 AND 
  "Tepl"."User_cnt"."Login" = \'' . $login . '\'  AND 
  "Tepl"."Arhiv_cnt"."DateValue" > \'' . $date . '\'
ORDER BY
  "Tepl"."ParamResPlc_cnt".plc_id,
  "Tepl"."ParamResPlc_cnt"."ParamRes_id"');

$archive = pg_fetch_all($sql_archive);
//var_dump($archive);

$sql_prp = pg_query('SELECT 
  "Tepl"."ParamResPlc_cnt".prp_id,
  "Tepl"."ParamResPlc_cnt".plc_id,
  "Tepl"."ParamResPlc_cnt"."ParamRes_id" as param
FROM
  "Tepl"."GroupToUserRelations"
  INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
  INNER JOIN "Tepl"."ParamResGroupRelations" ON ("Tepl"."GroupToUserRelations".grp_id = "Tepl"."ParamResGroupRelations".grp_id)
  INNER JOIN "Tepl"."ParamResPlc_cnt" ON ("Tepl"."ParamResGroupRelations".prp_id = "Tepl"."ParamResPlc_cnt".prp_id)
WHERE
  "Tepl"."User_cnt"."Login" = \'' . $login . '\' ');
$prp = pg_fetch_all($sql_prp);


for ($i = 0; $i < count($archive); $i++) {
    if ($i == 0) {
        $val[] = array(
            'value' => $archive[$i]['value'],
            'date' => $archive[$i]['date']
        );
    } else {


        if ($archive[$i]['prp'] == $archive[$i - 1]['prp']) {
            $val[] = array(
                'value' => $archive[$i]['value'],
                'date' => $archive[$i]['date']
            );
        } else {
            if ($archive[$i - 1]['plc_id'] == 1603) {
                $g++;
            }
            $data = check_array($val);
            $k = array_search($archive[$i - 1]['plc_id'], array_column($plc, 'plc_id'));

            $main[] = array(
                'plc_id' => $archive[$i - 1]['plc_id'],
                'city' => $plc[$k]['city'],
                'adr' => $plc[$k]['adr'],
                'date' => date("d.m.Y", strtotime($data['date'])),
                'value' => number_format($data['value'], 0, ',', ' '),
                'error' => $data['error'],
                'param' => $archive[$i - 1]['param'],
                'prp' => $archive[$i - 1]['prp']
            );

            unset($val);
            unset($data);

            $val[] = array(
                'value' => $archive[$i]['value'],
                'date' => $archive[$i]['date']
            );
        }
    }
}


for ($i = 0; $i < count($prp); $i++) {
    $k = array_search($prp[$i]['prp_id'], array_column($main, 'prp'));
    if ($k === false) {
        $z = array_search($prp[$i]['plc_id'], array_column($plc, 'plc_id'));
        if ($z !== FALSE) {
            $main[] = array(
                'plc_id' => $prp[$i]['plc_id'],
                'city' => $plc[$z]['city'],
                'adr' => $plc[$z]['adr'],
                'date' => ' - ',
                'value' => 'нет данных',
                'error' => 3, //ошибка нет архива за последние 2(3) месяца
                'param' => $prp[$i]['param'],
                'prp' => $prp[$i]['prp_id']
            );
        } else {
            echo "error<br>";
        }
    }
}


var_dump($main);

//for ($i = 0; $i < count($plc); $i++) {
//    $k = array_search($plc[$i]['plc_id'], array_column($archive, 'plc_id'));
//
//    if ($k === false) {
//        $main[] = array(
//            'plc_id' => $plc[$i]['plc_id'],
//            'city' => $plc[$i]['city'],
//            'adr' => $plc[$i]['adr'],
//            'date' => 'нет данных',
//            'value' => 'нет данных',
//            'error' => 3,
//            'param' => '1',
//            'prp' => '0'
//        );
//    }
//}
$filename = $login . "_" . date('Y-m-d');
object2file($main, $filename);
var_dump($main);

function object2file($array, $filename) {
    $str_value = serialize($array);
    $f = fopen('../tmp/' . $filename . '.tmp', 'w');
    fwrite($f, $str_value);
    fclose($f);
}

function check_array($array) {
    $array = array_reverse($array);
    $error = 0;
    $data = array();

    $data = $array[0];
    $datetime1 = date_create(date("Y-m-d"));
    $datetime2 = date_create(date("Y-m-d", strtotime($array[0]['date'])));
    $interval = date_diff($datetime1, $datetime2);
    $days = $interval->format('%a');
    if ($days > 7) { //кол-во дней простоя показаний
        $error = 1; //нет покзааний за 7 дней
    } else {
        $sl = array_slice($array, 0, 7);
        $raz = array();
        for ($i = 0; $i < count($sl); $i++) {
            if ($i + 1 != count($sl)) {
                $raz[] = $sl[$i]['value'] - $sl[$i + 1]['value'];
            }
            $summ += $sl[$i]['value'];
        }
        $r = $summ / count($sl);

        if ($r == $sl[0]['value']) {
            $error = 2; //нет импульса - отключен за 5 дней
        } elseif (array_sum($raz) < 1) {
            $error = 4; //На обьекте возможна неисрпавность импульса
        }
    }
    return $d = array(
        'error' => $error,
        'date' => $data['date'],
        'value' => $data['value']
    );
}

function query_objects_login($count, $login) {

    $str = "";
    switch ($count) {
        case 1: $str = 'SELECT DISTINCT 
                            "Tepl"."Places_cnt"."Name",
                            "Tepl"."Places_cnt".plc_id
                          FROM
                            "Tepl"."PlaceGroupRelations"
                            INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                            INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PlaceGroupRelations".plc_id = "Tepl"."Places_cnt".plc_id)
                            INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
                          WHERE
                            "Tepl"."User_cnt"."Login" = \'' . $login . '\'';
            break;
        case 2:$str = 'SELECT DISTINCT 
                            "Tepl"."Places_cnt"."Name" as city,
                            "Places_cnt1"."Name" as adr,
                            "Places_cnt1".plc_id
                          FROM
                            "Tepl"."PlaceGroupRelations"
                            INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                            INNER JOIN "Tepl"."Places_cnt" "Places_cnt1" ON ("Places_cnt1".plc_id = "Tepl"."PlaceGroupRelations".plc_id)
                            INNER JOIN "Tepl"."Places_cnt" ON ("Places_cnt1".place_id = "Tepl"."Places_cnt".plc_id)
                            INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
                          WHERE
                            "Tepl"."User_cnt"."Login" = \'' . $login . '\'';
            break;
        case 3:$str = 'SELECT DISTINCT 
                        "Tepl"."Places_cnt"."Name" as city,
                        concat("Places_cnt1"."Name",  \', \', "Places_cnt2"."Name") as adr,
                        "Places_cnt2".plc_id
                      FROM
                        "Tepl"."PlaceGroupRelations"
                        INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt2" ON ("Places_cnt2".plc_id = "Tepl"."PlaceGroupRelations".plc_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt1" ON ("Places_cnt2".place_id = "Places_cnt1".plc_id)
                        INNER JOIN "Tepl"."Places_cnt" ON ("Places_cnt1".place_id = "Tepl"."Places_cnt".plc_id)
                        INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
                      WHERE
                        "Tepl"."User_cnt"."Login" = \'' . $login . '\'';
            break;
        case 4: $str = 'SELECT DISTINCT 
                            "Tepl"."Places_cnt"."Name" as city,
                            concat("Places_cnt1"."Name", \', \', "Places_cnt2"."Name", \', \', "Places_cnt3"."Name") as adr,
                            "Places_cnt3".plc_id
                          FROM
                            "Tepl"."PlaceGroupRelations"
                            INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                            INNER JOIN "Tepl"."Places_cnt" "Places_cnt3" ON ("Places_cnt3".plc_id = "Tepl"."PlaceGroupRelations".plc_id)
                            INNER JOIN "Tepl"."Places_cnt" "Places_cnt2" ON ("Places_cnt2".plc_id = "Places_cnt3".place_id)
                            INNER JOIN "Tepl"."Places_cnt" "Places_cnt1" ON ("Places_cnt1".plc_id = "Places_cnt2".place_id)
                            INNER JOIN "Tepl"."Places_cnt" ON ("Places_cnt1".place_id = "Tepl"."Places_cnt".plc_id)
                            INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
                          WHERE
                            "Tepl"."User_cnt"."Login" = \'' . $login . '\'';
            break;
    }


    return $str;
}
