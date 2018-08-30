<?php

/**
 * ²¿ÃÅÓ³Éä½Å±¾ÅäÖÃ
 */
$sql_arr = array(
    "select_default" => "select * from oa_bi_dept_mapping c where 1=1"
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id = # "
    ),
    array(
        "name" => "businessSearch",
        "sql" => " and c.business LIKE CONCAT('%', #, '%') "
    ),
    array(
        "name" => "secondDeptSearch",
        "sql" => " and c.secondDept LIKE CONCAT('%', #, '%') "
    ),
    array(
        "name" => "thirdDeptSearch",
        "sql" => " and c.thirdDept LIKE CONCAT('%', #, '%') "
    ),
    array(
        "name" => "fourthDeptSearch",
        "sql" => " and c.fourthDept LIKE CONCAT('%', #, '%') "
    ),
    array(
        "name" => "module",
        "sql" => " and c.module = # "
    ),
    array(
        "name" => "mappingDeptSearch",
        "sql" => " and c.mappingDept LIKE CONCAT('%', #, '%') "
    ),
    array(
        "name" => "remarkSearch",
        "sql" => " and c.remark LIKE CONCAT('%', #, '%') "
    )
);