<?php
/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:50
 * @version 1.0
 * @description:Ԥ��ִ�м�¼ sql�����ļ�
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