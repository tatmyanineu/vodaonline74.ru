<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../../include/db_config.php';
include '../get_objects.query.php';


$sql = pg_query(query_objects_login($_SESSION['auth']['login']));
$data = pg_fetch_all($sql);


$sql = pg_query('SELECT 
  "Tepl"."User_cnt"."Login" as user,
  concat("Tepl"."Resourse_cnt"."Name", \': \',  "Tepl"."ParametrResourse"."Name") as param,
  public.exception.id,
  public.exception.plc_id,
  public.exception.prp_id,
  public.exception.date_begin,
  public.exception.date_end,
  public.exception.text_excep,
  public.exception.comments,
  public.exception.id_ticket
FROM
  "Tepl"."User_cnt"
  INNER JOIN public.exception ON ("Tepl"."User_cnt".usr_id = public.exception.us_id)
  INNER JOIN "Tepl"."ParamResPlc_cnt" ON (public.exception.prp_id = "Tepl"."ParamResPlc_cnt".prp_id)
  INNER JOIN "Tepl"."ParametrResourse" ON ("Tepl"."ParamResPlc_cnt"."ParamRes_id" = "Tepl"."ParametrResourse"."ParamRes_id")
  INNER JOIN "Tepl"."Resourse_cnt" ON ("Tepl"."ParametrResourse".res_id = "Tepl"."Resourse_cnt".res_id)');

$excep = pg_fetch_all($sql);

for ($i = 0; $i < count($excep); $i++) {
    $k = array_search($excep[$i]['plc_id'], array_column($data, 'plc_id'));
    if ($k !== false) {
        
        if($excep[$i]['date_end'] == ""){
            $date_end = "";  
        }else{
           $date_end = date("d.m.Y", strtotime($excep[$i]['date_end'])); 
        }
        
        
        $array['data'][] = array(
            'id' => $excep[$i]['id'],
            'adr' => $data[$k]['adr'],
            'plc' => $excep[$i]['plc'],
            'param' => $excep[$i]['param'],
            'date_begin' => date("d.m.Y", strtotime($excep[$i]['date_begin'])),
            'date_end' => $date_end,
            'text_excep' => $excep[$i]['text_excep'],
            'comments' => $excep[$i]['comments'],
            'user' => $excep[$i]['user'],
            'id_ticket' => $excep[$i]['id_ticket'],
        );
    }
}

echo json_encode($array, JSON_UNESCAPED_UNICODE);