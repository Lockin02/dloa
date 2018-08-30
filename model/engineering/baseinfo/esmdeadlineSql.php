<?php
/**
 * @author show
 * @Date 2014年5月13日 15:39:29
 * @version 1.0
 * @description:日志填报期限 sql配置文件
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