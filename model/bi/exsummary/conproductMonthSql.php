<?php

/**
 * BI����ǩ��������ű�����
 */
$sql_arr = array(
    "select_default" => "select * from oa_bi_conproduct_month c where 1=1"
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id = # "
    )
);