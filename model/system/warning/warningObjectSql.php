<?php
/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:21
 * @version 1.0
 * @description:通用预警功能详细设置 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id, c.objName, c.objCode
        from oa_system_warning_object c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "objName",
        "sql" => " and c.objName=# "
    ),
    array(
        "name" => "objCode",
        "sql" => " and c.objCode=# "
    )
);