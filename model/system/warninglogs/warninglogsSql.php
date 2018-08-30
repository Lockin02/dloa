<?php
/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:50
 * @version 1.0
 * @description:预警执行记录 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.objId ,c.objName ,c.executeSql ,c.executeTime  from oa_system_warning_logs c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "objId",
        "sql" => " and c.objId=# "
    ),
    array(
        "name" => "objName",
        "sql" => " and c.objName=# "
    ),
    array(
        "name" => "excuteSql",
        "sql" => " and c.excuteSql=# "
    ),
    array(
        "name" => "excuteTime",
        "sql" => " and c.excuteTime=# "
    )
);