<?php
/**
 * @author show
 * @Date 2016年05月12日 16:07:45
 * @version 1.0
 * @description:项目机票决算 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id, c.projectId, c.thisYear, c.thisMonth,
            c.fee, c.createId, c.createName, c.createTime
        from oa_esm_records_flights c where 1=1 ",
    "select_count"=>"select SUM(c.fee) AS fee
        from oa_esm_records_flights c where 1=1 "
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "projectId",
        "sql" => " and c.projectId=# "
    ),
    array(
        "name" => "thisYear",
        "sql" => " and c.thisYear=# "
    ),
    array(
        "name" => "thisMonth",
        "sql" => " and c.thisMonth=# "
    )
);