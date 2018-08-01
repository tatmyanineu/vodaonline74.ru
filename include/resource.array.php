<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include './db_config.php';

function array_resource_plc($plc, $login) {
    $sql_resource = pg_query('SELECT DISTINCT 
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
                    "Tepl"."ParametrResourse"."Name" NOT LIKE \'%Время%\' AND 
                    "Tepl"."User_cnt"."Login" = \'' . $login . '\' AND
                    "Tepl"."ParamResPlc_cnt".plc_id = ' . $plc . '
                    ORDER BY
                    resource');
    
    return $res = pg_fetch_all($sql_resource);
}
