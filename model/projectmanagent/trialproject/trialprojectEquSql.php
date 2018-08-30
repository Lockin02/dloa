<?php
/**
 * @author by liangjj
 * @Date 2014-06-11 13:34:07
 * @version 1.0
 * @description:试用项目 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select * from oa_trialproject_trialproject_item c where 1=1"
);

$condition_arr = array(
    array(
        "name" => "trialprojectId",
        "sql" => " and c.trialprojectId=# "
    ),
    array(
        "name" => "conProductName",
        "sql" => " and c.conProductName=# "
    ),
    array(
        "name" => "conProductId",
        "sql" => " and c.conProductId=# "
    )
);