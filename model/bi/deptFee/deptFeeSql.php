<?php

/**
 * ���ŷ��ýű�����
 */
$sql_arr = array(
    "select_default" => "select * from oa_bi_dept_fee c where 1=1"
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id = # "
    ),
    array(
        "name" => "isImport",
        "sql" => " and c.isImport = # "
    ),
    array(
        "name" => "thisYear",
        "sql" => " and c.thisYear = # "
    )
);