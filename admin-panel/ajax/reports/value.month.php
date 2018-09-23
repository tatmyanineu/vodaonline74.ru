<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Отчет МУП ПОВВ по разности расходов счечи

session_start();
include '../../../include/db_config.php';
include '../get_objects.query.php';

$date1 = $_POST['date1'];
$date2 = $_POST['date2'];

//$date1 = '2018-07-21';
//$date2 = '2018-08-21';
$sql_tree = pg_query('SELECT DISTINCT 
  "Tepl"."Places_cnt".typ_id
FROM
  "Tepl"."PlaceGroupRelations"
  INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PlaceGroupRelations".plc_id = "Tepl"."Places_cnt".plc_id)
  INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
  INNER JOIN "Tepl"."User_cnt" ON ("Tepl"."GroupToUserRelations".usr_id = "Tepl"."User_cnt".usr_id)
WHERE
  "Tepl"."User_cnt"."Login" = \'' . $_SESSION['auth']['login'] . '\'
ORDER BY
  "Tepl"."Places_cnt".typ_id');

$count = pg_fetch_all($sql_tree);
$result = query_objects_login($_SESSION['auth']['login']);
$sql = pg_query($result);

$plc = pg_fetch_all($sql);

$sql_prp = pg_query('SELECT 
  concat( "Tepl"."Resourse_cnt"."Name", \': \',"Tepl"."ParametrResourse"."Name") as resource,
  "Tepl"."ParamResPlc_cnt".prp_id,
  "Tepl"."ParamResPlc_cnt"."ParamRes_id" as param_id,
  "Tepl"."ParamResPlc_cnt".plc_id
FROM
  "Tepl"."User_cnt"
  INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."User_cnt".usr_id = "Tepl"."GroupToUserRelations".usr_id)
  INNER JOIN "Tepl"."ParamResGroupRelations" ON ("Tepl"."GroupToUserRelations".grp_id = "Tepl"."ParamResGroupRelations".grp_id)
  INNER JOIN "Tepl"."ParamResPlc_cnt" ON ("Tepl"."ParamResGroupRelations".prp_id = "Tepl"."ParamResPlc_cnt".prp_id)
  INNER JOIN "Tepl"."ParametrResourse" ON ("Tepl"."ParamResPlc_cnt"."ParamRes_id" = "Tepl"."ParametrResourse"."ParamRes_id")
  INNER JOIN "Tepl"."Resourse_cnt" ON ("Tepl"."ParametrResourse".res_id = "Tepl"."Resourse_cnt".res_id)
WHERE
  "Tepl"."User_cnt"."Login" = \'' . $_SESSION['auth']['login'] . '\' AND 
  "Tepl"."ParamResPlc_cnt"."ParamRes_id" != 386');

$param = pg_fetch_all($sql_prp);

$sql_archive = pg_query('SELECT 
  "Tepl"."Arhiv_cnt"."DateValue" AS date,
  "Tepl"."Arhiv_cnt"."DataValue" AS value,
  "Tepl"."ParamResPlc_cnt"."ParamRes_id" AS param,
  "Tepl"."ParamResPlc_cnt".prp_id,
  "Tepl"."ParamResPlc_cnt".plc_id,
  concat("Tepl"."Resourse_cnt"."Name", \': \', "Tepl"."ParametrResourse"."Name") AS param_name
FROM
  "Tepl"."User_cnt"
  INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."User_cnt".usr_id = "Tepl"."GroupToUserRelations".usr_id)
  INNER JOIN "Tepl"."ParamResGroupRelations" ON ("Tepl"."GroupToUserRelations".grp_id = "Tepl"."ParamResGroupRelations".grp_id)
  INNER JOIN "Tepl"."ParamResPlc_cnt" ON ("Tepl"."ParamResGroupRelations".prp_id = "Tepl"."ParamResPlc_cnt".prp_id)
  INNER JOIN "Tepl"."Arhiv_cnt" ON ("Tepl"."ParamResPlc_cnt".prp_id = "Tepl"."Arhiv_cnt".pr_id)
  INNER JOIN "Tepl"."ParametrResourse" ON ("Tepl"."ParamResPlc_cnt"."ParamRes_id" = "Tepl"."ParametrResourse"."ParamRes_id")
  INNER JOIN "Tepl"."Resourse_cnt" ON ("Tepl"."ParametrResourse".res_id = "Tepl"."Resourse_cnt".res_id)
WHERE
  "Tepl"."User_cnt"."Login" = \'' . $_SESSION['auth']['login'] . '\' AND 
  "Tepl"."ParamResPlc_cnt"."ParamRes_id" != 386 AND 
  "Tepl"."Arhiv_cnt".typ_arh = 2 AND 
  "Tepl"."Arhiv_cnt"."DateValue" >= \'' . $date1 . '\' AND 
  "Tepl"."Arhiv_cnt"."DateValue" <= \'' . $date2 . '\'
ORDER BY
  "Tepl"."ParamResPlc_cnt".prp_id,
  "Tepl"."ParamResPlc_cnt".plc_id,
  date');

$archive = pg_fetch_all($sql_archive);


$tr = false;
for ($i = 0; $i < count($archive); $i++) {
    if ($archive[$i]['plc_id'] == 450) {
        $g++;
    }

    if ($i == 0) {
        $val[] = array(
            'date' => $archive[$i]['date'],
            'value' => sprintf("%.2f", $archive[$i]['value'])
        );
    } else {
        if ($archive[$i]['prp_id'] == $archive[$i - 1]['prp_id']) {
            $val[] = array(
                'date' => $archive[$i]['date'],
                'value' => sprintf("%.2f", $archive[$i]['value'])
            );
        } else {
            if ($archive[$i]['prp_id'] != $archive[$i + 1]['prp_id']) {
                if (count($val) > 1) {
                    //echo $archive[$i]['plc_id'] . "<br>";
                    $pl = $archive[$i]['plc_id'];
                    $k = array_search($archive[$i - 1]['plc_id'], array_column($plc, 'plc_id'));
                    $data = voda($val, $date2, $plc[$k]['plc_id']);
                    if ($k !== FALSE) {
                        $main['data'][] = array(
                            'plc_id' => $plc[$k]['plc_id'],
                            'adr' => $plc[$k]['adr'],
                            'distr_id' => $plc[$k]['dist_id'],
                            'param_name' => $archive[$i - 1]['param_name'],
                            'prp' => $archive[$i - 1]['prp_id'],
                            'param' => $archive[$i - 1]['param'],
                            'v1' => sprintf("%.2f", $data['v1']),
                            'd1' => date('d.m.Y', strtotime($data['d1'])),
                            'v2' => sprintf("%.2f", $data['v2']),
                            'd2' => date('d.m.Y', strtotime($data['d2'])),
                            'sum' => $data['sum'],
                            'error' => $data['error']
                        );
                    }

                    unset($val);
                }
                $val[] = array(
                    'date' => $archive[$i]['date'],
                    'value' => $archive[$i]['value']
                );
                $k = array_search($archive[$i]['plc_id'], array_column($plc, 'plc_id'));
                $data = voda($val, $date2, $plc[$k]['plc_id']);
                if ($k !== FALSE) {
                    $main['data'][] = array(
                        'plc_id' => $plc[$k]['plc_id'],
                        'adr' => $plc[$k]['adr'],
                        'distr_id' => $plc[$k]['dist_id'],
                        'param_name' => $archive[$i - 1]['param_name'],
                        'prp' => $archive[$i]['prp_id'],
                        'param' => $archive[$i]['param'],
                        'v1' => sprintf("%.2f", $data['v1']),
                        'd1' => date('d.m.Y', strtotime($data['d1'])),
                        'v2' => sprintf("%.2f", $data['v2']),
                        'd2' => date('d.m.Y', strtotime($data['d2'])),
                        'sum' => $data['sum'],
                        'error' => $data['error']
                    );
                }
                unset($val);
                $tr = true;
            } else {
                if ($tr == true) {
                    $val[] = array(
                        'date' => $archive[$i]['date'],
                        'value' => $archive[$i]['value']
                    );
                    $tr = false;
                } else {
                    $pl = $archive[$i]['plc_id'];
                    $k = array_search($archive[$i - 1]['plc_id'], array_column($plc, 'plc_id'));
                    $data = voda($val, $date2, $plc[$k]['plc_id']);
                    if ($k !== FALSE) {
                        $main['data'][] = array(
                            'plc_id' => $plc[$k]['plc_id'],
                            'adr' => $plc[$k]['adr'],
                            'distr_id' => $plc[$k]['dist_id'],
                            'param_name' => $archive[$i - 1]['param_name'],
                            'prp' => $archive[$i - 1]['prp_id'],
                            'param' => $archive[$i - 1]['param'],
                            'v1' => sprintf("%.2f", $data['v1']),
                            'd1' => date('d.m.Y', strtotime($data['d1'])),
                            'v2' => sprintf("%.2f", $data['v2']),
                            'd2' => date('d.m.Y', strtotime($data['d2'])),
                            'sum' => $data['sum'],
                            'error' => $data['error']
                        );
                    }


                    unset($val);

                    $val[] = array(
                        'date' => $archive[$i]['date'],
                        'value' => sprintf("%.2f", $archive[$i]['value'])
                    );
                }
            }
        }
    }
    if ($i + 1 == count($archive)) {
        $val[] = array(
            'date' => $archive[$i]['date'],
            'value' => sprintf("%.2f", $archive[$i]['value'])
        );
        $k = array_search($archive[$i - 1]['plc_id'], array_column($plc, 'plc_id'));
        $data = voda($val, $date2, $plc[$k]['plc_id']);
        if ($k !== FALSE) {
            $main['data'][] = array(
                'plc_id' => $plc[$k]['plc_id'],
                'adr' => $plc[$k]['adr'],
                'distr_id' => $plc[$k]['dist_id'],
                'param_name' => $archive[$i - 1]['param_name'],
                'prp' => $archive[$i - 1]['prp_id'],
                'param' => $archive[$i - 1]['param'],
                'v1' => sprintf("%.2f", $data['v1']),
                'd1' => date('d.m.Y', strtotime($data['d1'])),
                'v2' => sprintf("%.2f", $data['v2']),
                'd2' => date('d.m.Y', strtotime($data['d2'])),
                'sum' => $data['sum'],
                'error' => $data['error']
            );
        }
    }
}


for ($i = 0; $i < count($param); $i++) {
    $k = array_search($param[$i]['prp_id'], array_column($archive, 'prp_id'));
    if ($k === false) {
        $p = array_search($param[$i]['plc_id'], array_column($plc, 'plc_id'));
        //echo $param[$i]['plc_id'].' '.$plc[$p]['adr'].' '. $plc[$p]['name'].'<br>' ;
        $main['data'][] = array(
            'plc_id' => $plc[$p]['plc_id'],
            'adr' => $plc[$p]['adr'],
            'distr_id' => $plc[$p]['dist_id'],
            'param_name' => $param[$i]['resource'],
            'prp' => $param[$i]['prp_id'],
            'param' => $param[$i]['param_id'],
            'v1' => null,
            'd1' => null,
            'v2' => null,
            'd2' => null,
            'sum' => null,
            'error' => '5'
        );
    }
}

//$column['columns'] = array(
//    array("title" => "plc_id", "data" => "plc_id"),
//    array("title" => "Адрес", "data" => "adr"),
//    array("title" => "Параметр", "data" => "param_name"),
//    array("title" => "Объем1 (м3)", "data" => "v1"),
//    array("title" => "Дата Нач.", "data" => "d1"),
//    array("title" => "Объем2 (м3)", "data" => "v2"),
//    array("title" => "Дата Кон.", "data" => "d2"),
//    array("title" => "Итого", "data" => "sum"),
//    array("title" => "Код. ошибки", "data" => "error")
//);


$data = array();
//$data = array_merge($data, $column);


if ($_POST['dist'] == 0) {
    $data = array_merge($data, $main);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    for ($i = 0; $i < count($main['data']); $i++) {
        if ($main['data'][$i]['distr_id'] == $_POST['dist']) {
            $arrData['data'][] = array(
                'plc_id' => $main['data'][$i]['plc_id'],
                'adr' => $main['data'][$i]['adr'],
                'distr_id' => $main['data'][$i]['dist_id'],
                'param_name' => $main['data'][$i]['param_name'],
                'prp' => $main['data'][$i]['prp'],
                'param' => $main['data'][$i]['param'],
                'v1' => $main['data'][$i]['v1'],
                'd1' => $main['data'][$i]['d1'],
                'v2' => $main['data'][$i]['v2'],
                'd2' => $main['data'][$i]['d2'],
                'sum' => $main['data'][$i]['sum'],
                'error' => $main['data'][$i]['error']
            );
        }
    }
    unset($main);
    $main = $arrData;
    $data = array_merge($data, $main);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}




$sql_fias = pg_query('SELECT 
  public.fias_cnt.id,
  public.fias_cnt.plc_id,
  public.fias_cnt.fias,
  public.fias_cnt.id_cn
FROM
  public.fias_cnt');
while ($row = pg_fetch_row($sql_fias)) {
    $f[] = array(
        'plc_id' => $row[1],
        'fias' => $row[2],
        'cn' => $row[3]
    );
}


$sql_prop = pg_query('SELECT 
  public.prop_connect.id,
  public.prop_connect.prp_id,
  public.prop_connect.id_connect,
  public.prop_connect.date,
  public.prop_connect.cnt_numb,
  public.prop_connect.plc_id,
  public.prop_connect.cdog
FROM
  public.prop_connect');

while ($row = pg_fetch_row($sql_prop)) {
    $prop[] = array(
        'prp' => $row[1],
        'id_con' => $row[2],
        'numb' => $row[4],
        'cdog' => $row[6]
    );
}


$def = array(
    array("HOUSE_ID", "C", 16),
    array("HOUSE_FIAS", "C", 36),
    array("STNAME", "C", 128),
    array("H_NOMER", "C", 32),
    array("DEVICE", "C", 16),
    array("D_NOMER", "C", 32),
    array("PREV_VOL", "N", 10, 0),
    array("PREV_DATA", "D"),
    array("CURR_VOL", "N", 10, 0),
    array("CURR_DATA", "D"),
    array("RASHOD", "N", 10, 0),
    array("VOLUME1", "N", 15, 4),
    array("VOLUME0", "N", 15, 4),
    array("ER_CODE", "N", 10, 0),
    array("ER_NAME", "C", 128),
    array("ER_INFO", "C", 128),
    array("CONTRACTID", "C", 11),
    array("KV", "C", 32),
    array("PLACE", "C", 32)
);


$dbf_name = "export/report_".$_SESSION['auth']['login']."_" . $date2 . "_" . $date1 . ".dbf";
$_SESSION['file_name'] = $dbf_name;
unlink($dbf_name);
if (!dbase_create($dbf_name, $def)) {
    die("Error, can't create the database");
}

$dbf = dbase_open($dbf_name, 2);

for ($i = 0; $i < count($main['data']); $i++) {

    if ($main['data'][$i]['plc_id'] == 450) {
        $g++;
    }
    $k = array_search($main['data'][$i]['plc_id'], array_column($f, 'plc_id'));
    $plc = $main['data'][$i]['plc_id'];
    $adr = $main['data'][$i]['adr'];
    $house_id = 70000000 + $main['data'][$i]['plc_id'];
    if ($k !== false) {
        $fias_code = $f[$k]['fias'];
        $cn = $f[$k]['cn'];
    } else {
        $fias_code = "";
        $cn = "";
    }

    $e = array_search($main['data'][$i]['prp'], array_column($prop, 'prp'));
    if ($e !== false) {
        $con = $prop[$e]['id_con'];
        $num = $prop[$e]['numb'];
        $dog = $prop[$e]['cdog'];
        // $numb =;
    } else {
        $con = "";
        $num = "";
        $dog = "";
        //$numb =;
    }

    $adr = explode(", ", $main['data'][$i]['adr']);

    switch ($main['data'][$i]['error']) {
        case 0:
            $e = "";
            $t = "";
            break;
        case 1:
            $e = $main['data'][$i]['error'];
            $t = "Не полный архив за период";
            break;
        case 2:
            $e = $main['data'][$i]['error'];
            $t = "Нет импульса, расход равен 0";
            break;
        case 3:
            $e = $main['data'][$i]['error'];
            $t = "Аномально маленький расход";
        case 4:
            $e = $main['data'][$i]['error'];
            $t = "Коррекция показаний";
            break;
        case 5:
            $e = $main['data'][$i]['error'];
            $t = "отсутствует архив прибора";
            break;
    }

    dbase_add_record($dbf, array(
        iconv('UTF-8', 'CP866', $house_id), //Идентификатор
        iconv('UTF-8', 'CP866', $fias_code), //ФИАС код
        iconv('UTF-8', 'CP866', $adr[1]), // Улица
        iconv('UTF-8', 'CP866', $adr[2]), //Дом
        iconv('UTF-8', 'CP866', $con), //ID счетчика в системе поставщика
        iconv('UTF-8', 'CP866', $num), // заводской номер счечтика
        iconv('UTF-8', 'CP866', $main['data'][$i]['v1']), // Предыдущие показания
        iconv('UTF-8', 'CP866', date('Ymd', strtotime($main['data'][$i]['d1']))), // Дата снятия предыдущего показания
        iconv('UTF-8', 'CP866', $main['data'][$i]['v2']), // Текущие покзаания
        iconv('UTF-8', 'CP866', date('Ymd', strtotime($main['data'][$i]['d2']))), // Дата снятия текущий покзааний
        iconv('UTF-8', 'CP866', $main['data'][$i]['sum']), // Расход
        iconv('UTF-8', 'CP866', $main['data'][$i]['v2']), // объем потребления по ИПУ
        iconv('UTF-8', 'CP866', 0), // Объем потребления по нормативу
        iconv('UTF-8', 'CP866', $e), // Код несправности
        iconv('UTF-8', 'CP866', $t), // Описание неисправности
        iconv('UTF-8', 'CP866', ''), // Доп инфомрация об ошибке
        iconv('UTF-8', 'CP866', $dog), //код договора
        iconv('UTF-8', 'CP866', ''), // Диапазон квартир
        iconv('UTF-8', 'CP866', '') // место установки ПУ
            )
    );
}

//echo json_encode($main, JSON_UNESCAPED_UNICODE);

function voda($array, $post_date, $plc) {
    if ($plc == 99) {
        $asd++;
    }
    $nan = false;
    $data['v1'] = $array[0]['value'];
    $data['d1'] = $array[0]['date'];
    $data['v2'] = $array[count($array) - 1]['value'];
    $data['d2'] = $array[count($array) - 1]['date'];
    $sum = 0;
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i]['value'] == 'NaN') {
            $data['error'] = 2;
            $data['v1'] = 0;
            $data['v2'] = 0;
            $nan = true;
            break;
        }
        if ($i != 0) {
            $r = $array[$i]['value'] - $array[$i - 1]['value'];
            $sum += $r;
        }
    }
    $data['sum'] = sprintf("%.2f", $sum);
    if ($nan == FALSE) {
        if (strtotime($data['d2']) != strtotime($post_date)) {
            if ($sum == 0) {
                $data['error'] = 2;
            } else {
                $data['error'] = 1;
            }
        } else {
            if ($sum == 0) {
                $data['error'] = 2;
            } elseif ($sum < 20) {
                $data['error'] = 3;
            } else {
                $data['error'] = 0;
            }
        }
    }

    return $data;
}
