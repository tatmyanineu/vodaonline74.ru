<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include './db_config.php';

function voda_raz($array) {

    $tmp1 = Array();
    foreach ($array as &$ma) {
        $tmp1[] = &$ma["param"];
    }

    array_multisort($tmp1, $array);

    $array = array_reverse($array);
    $sum = 0;
    for ($i = 0; $i < count($array); $i++) {
        if ($i != 0) {
            if ($array[$i]['param'] == $array[$i - 1]['param']) {
                $sum += sprintf("%.2f", $array[$i - 1]['value']) - sprintf("%.2f", $array[$i]['value']);
            } else {
                $data[$array[$i - 1]['param']] = $sum;
                $sum = 0;
            }
        }
        if ($i + 1 == count($array)) {
            $data[$array[$i]['param']] = sprintf("%.2f", $sum);
        }
    }
    return $data;
}

function voda_values($array) {
    $array = array_reverse($array);
    for ($i = 0; $i < count($array); $i++) {

        if ($i + 1 != count($array)) {
            if ($array[$i] == 0) {
                $x = 0;
                $data[] = floatval(sprintf("%.2f", $x));
            } else {
                $x = $array[$i] - $array[$i + 1];
                $data[] = floatval(sprintf("%.2f", $x));
            }
        }
    }
    return array_reverse($data);
}
