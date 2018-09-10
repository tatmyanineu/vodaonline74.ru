<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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


$dbf_name = "export/report_" . $date2 . "_" . $date1 . ".dbf";
$_SESSION['file_name'] = $dbf_name;
unlink($dbf_name);
if (!dbase_create($dbf_name, $def)) {
    die("Error, can't create the database");
}

$dbf = dbase_open($dbf_name, 2);

for ($i = 0; $i < count($main); $i++) {

    if ($main[$i]['plc_id'] == 99) {
        $g++;
    }
    $k = array_search($main[$i]['plc_id'], array_column($f, 'plc_id'));
    $plc = $main[$i]['plc_id'];
    $adr = $main[$i]['adr'];
    $house_id = 70000000 + $main[$i]['plc_id'];
    if ($k !== false) {
        $fias_code = $f[$k]['fias'];
        $cn = $f[$k]['cn'];
    } else {
        $fias_code = "";
        $cn = "";
    }

    $e = array_search($main[$i]['prp'], array_column($prop, 'prp'));
    if ($e !== false) {
        $con = $prop[$e]['id_con'];
        $num = $prop[$e]['numb'];
        $cdog =$prop[$e]['cdog'];
        // $numb =;
    } else {
        $con = "";
        $num = "";
        $cdog ="";
        //$numb =;
    }

    $adr = explode(", ", $main[$i]['adr']);

    switch ($main[$i]['error']) {
        case 0:
            $e = "";
            $t = "";
            break;
        case 1:
            $e = $main[$i]['error'];
            $t = "Не полный архив за период";
            break;
        case 2:
            $e = $main[$i]['error'];
            $t = "NaN значения в архиве";
            break;
        case 4:
            $e = $main[$i]['error'];
            $t = "Коррекция показаний";
            break;
        case 5:
            $e = $main[$i]['error'];
            $t = "отсутствует архив прибора";
            break;
    }

    dbase_add_record($dbf, array(
        iconv('UTF-8', 'CP866', $house_id), //Идентификатор
        iconv('UTF-8', 'CP866', $fias_code), //ФИАС код
        iconv('UTF-8', 'CP866', $adr[0]), // Улица
        iconv('UTF-8', 'CP866', $adr[1]), //Дом
        iconv('UTF-8', 'CP866', $con), //ID счетчика в системе поставщика
        iconv('UTF-8', 'CP866', $num), // заводской номер счечтика
        iconv('UTF-8', 'CP866', $main[$i]['v1']), // Предыдущие показания
        iconv('UTF-8', 'CP866', date('Ymd', strtotime($main[$i]['d1']))), // Дата снятия предыдущего показания
        iconv('UTF-8', 'CP866', $main[$i]['v2']), // Текущие покзаания
        iconv('UTF-8', 'CP866', date('Ymd', strtotime($main[$i]['d2']))), // Дата снятия текущий покзааний
        iconv('UTF-8', 'CP866', $main[$i]['sum']), // Расход
        iconv('UTF-8', 'CP866', $main[$i]['v2']), // объем потребления по ИПУ
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
