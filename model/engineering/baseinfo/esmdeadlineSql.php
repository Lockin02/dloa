<?php
/**
 * @author show
 * @Date 2014��5��13�� 15:39:29
 * @version 1.0
 * @description:��־����� sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select * from oa_esm_baseinfo_log_deadline c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "month",
        "sql" => " and c.month=# "
    ),
    array(
        "name" => "day",
        "sql" => " and c.day=# "
    ),
    array(
        "name" => "useRangeId",
        "sql" => " and c.useRangeId=# "
    ),
    array(
        "name" => "useRange",
        "sql" => " and c.useRange=# "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=# "
    )
);