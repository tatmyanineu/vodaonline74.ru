<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function query_objects($count, $group) {

    $str = "";
    switch ($count) {
        case 1: $str = 'SELECT DISTINCT 
                        "Tepl"."Places_cnt"."Name",
                        "Tepl"."Places_cnt".plc_id
                      FROM
                        "Tepl"."PlaceGroupRelations"
                        INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                        INNER JOIN "Tepl"."Places_cnt" ON ("Tepl"."PlaceGroupRelations".plc_id = "Tepl"."Places_cnt".plc_id)
                      WHERE
                        "Tepl"."PlaceGroupRelations".grp_id = ' . $group . '';
            break;
        case 2:$str = 'SELECT DISTINCT 
                        "Tepl"."Places_cnt"."Name",
                        "Places_cnt1"."Name",
                        "Places_cnt1".plc_id
                      FROM
                        "Tepl"."PlaceGroupRelations"
                        INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt1" ON ("Places_cnt1".plc_id = "Tepl"."PlaceGroupRelations".plc_id)
                        INNER JOIN "Tepl"."Places_cnt" ON ("Places_cnt1".place_id = "Tepl"."Places_cnt".plc_id)
                      WHERE
                        "Tepl"."PlaceGroupRelations".grp_id = ' . $group . '';
            break;
        case 3:$str = 'SELECT DISTINCT 
                        "Tepl"."Places_cnt"."Name",
                        concat("Places_cnt1"."Name", \', \', "Places_cnt2"."Name") AS "FIELD_1",
                        "Places_cnt2".plc_id
                      FROM
                        "Tepl"."PlaceGroupRelations"
                        INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt2" ON ("Places_cnt2".plc_id = "Tepl"."PlaceGroupRelations".plc_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt1" ON ("Places_cnt2".place_id = "Places_cnt1".plc_id)
                        INNER JOIN "Tepl"."Places_cnt" ON ("Places_cnt1".place_id = "Tepl"."Places_cnt".plc_id)
                      WHERE
                        "Tepl"."PlaceGroupRelations".grp_id = ' . $group . '';
            break;
        case 4: $str = 'SELECT DISTINCT 
                        "Tepl"."Places_cnt"."Name",
                        concat("Places_cnt1"."Name", \', \', "Places_cnt2"."Name", \', \', "Places_cnt3"."Name"),
                        "Places_cnt3".plc_id
                      FROM
                        "Tepl"."PlaceGroupRelations"
                        INNER JOIN "Tepl"."GroupToUserRelations" ON ("Tepl"."PlaceGroupRelations".grp_id = "Tepl"."GroupToUserRelations".grp_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt3" ON ("Places_cnt3".plc_id = "Tepl"."PlaceGroupRelations".plc_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt2" ON ("Places_cnt2".plc_id = "Places_cnt3".place_id)
                        INNER JOIN "Tepl"."Places_cnt" "Places_cnt1" ON ("Places_cnt1".plc_id = "Places_cnt2".place_id)
                        INNER JOIN "Tepl"."Places_cnt" ON ("Places_cnt1".place_id = "Tepl"."Places_cnt".plc_id)
                      WHERE
                        "Tepl"."PlaceGroupRelations".grp_id = ' . $group . '';
            break;
    }


    return $str;
}
