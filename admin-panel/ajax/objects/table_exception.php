<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include '../../../include/db_config.php';


$sql = pg_query(' SELECT 
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
  INNER JOIN "Tepl"."Resourse_cnt" ON ("Tepl"."ParametrResourse".res_id = "Tepl"."Resourse_cnt".res_id)
WHERE 
    public.exception.plc_id = ' . $_POST['plc'] . '
');
$array['data'] = pg_fetch_all($sql);

echo json_encode($array, JSON_UNESCAPED_UNICODE);
