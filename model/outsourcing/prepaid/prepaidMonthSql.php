<?php
/**
 * @author Acan
 * @Date 2014年10月30日 11:03:34
 * @version 1.0
 * @description:外包预提_月份 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select * from oa_outsourcing_nprepaid_month c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "mainId",
        "sql" => " and c.mainId=# "
    )
);