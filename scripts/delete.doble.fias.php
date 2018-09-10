<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../include/db_config.php';

//удаление дублирующих обьектов в таблице fias_cnt

$sql = pg_query('SELECT id, plc_id, fias, cdog, id_cn
  FROM fias_cnt
');


$res = pg_fetch_all($sql);
$j = 0;
$d = 0;
while ($row = pg_fetch_row($sql)) {
    if($row[1]==1603){
        $g++;
    }
    $k = 0;
    for ($i = $j; $i < count($res); $i++) {
        if ($row[1] != "") {
            //echo $i." ".$row[1]." ".$res[$i]['plc_id']. "<br>";
            if ($row[2] == $res[$i]['fias'] and $row[1] == $res[$i]['plc_id']) {
                $k++;
                if ($k > 1) {
                    //echo $k." ".$row[0]." ". $row[1] . " " .$row[2]." ->" .$row[3]."<br>";
                    pg_query('DELETE FROM fias_cnt WHERE id =' . $row[0]);
                    $d++;
                }
            }
        }
    }
    $j++;
}

echo "Удалено " . $d . " дубликатов ФИАС ";