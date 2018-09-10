<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../../include/db_config.php';


$sql = pg_query('SELECT fias, id_cn
  FROM fias_cnt WHERE plc_id = ' . $_POST['plc'] . '');
$arr = pg_fetch_all($sql);
if (pg_num_rows($sql) > 0) {
    echo '<form id = "fiasDataForm">
    <div class = "row" style = "margin-bottom: 15px;">
    <div class = "col-lg-3 col-md-4 col-xs-12">ФИАС</div>
    <div class = "col-lg-3 col-md-4 col-xs-12"><input type = "text" id = "fias" class = "form-control" value="' . $arr[0]['fias'] . '"></div>
    </div>
    <div class = "row" style = "margin-bottom: 15px;">
    <div class = "col-lg-3 col-md-4 col-xs-12">ЦН идентификатор</div>
    <div class = "col-lg-3 col-md-4 col-xs-12"><input type = "text" id = "cnid" class = "form-control" value="' . $arr[0]['id_cn'] . '"></div>
    </div>
    </form>';
} else {
    echo '<form id = "fiasDataForm">
    <div class = "row" style = "margin-bottom: 15px;">
    <div class = "col-lg-3 col-md-4 col-xs-12">ФИАС</div>
    <div class = "col-lg-3 col-md-4 col-xs-12"><input type = "text" id = "fias" class = "form-control"></div>
    </div>
    <div class = "row" style = "margin-bottom: 15px;">
    <div class = "col-lg-3 col-md-4 col-xs-12">ЦН идентификатор</div>
    <div class = "col-lg-3 col-md-4 col-xs-12"><input type = "text" id = "cnid" class = "form-control"></div>
   </div>
    </form>';
}

echo '<div class="row" style="margin-bottom:  20px;">
          <div class="col-lg-3 col-lg-offset-3 col-md-3 col-md-offset-3 col-xs-12">
            <button class="btn btn-primary btn-save-fias btn-lg">Сохранить</button>
          </div> 
      </div>';
