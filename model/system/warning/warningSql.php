<?php
/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:21
 * @version 1.0
 * @description:通用预警功能 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id,c.objName,c.description,c.executeSql,c.isUsing,c.mailCode,
			c.inKeys,c.isMailManager,c.receiverIdKey,c.receiverNameKey,c.lastTime,c.intervalDay,
			c.regularPlan,c.executeClass,c.executeFun
        from oa_system_warning c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "objName",
        "sql" => " and c.objName=# "
    ),
    array(
        "name" => "objNameSearch",
        "sql" => " and c.objName like concat('%',#,'%') "
    ),
    array(
        "name" => "description",
        "sql" => " and c.description=# "
    ),
    array(
        "name" => "executeSql",
        "sql" => " and c.executeSql=# "
    ),
    array(
        "name" => "isUsing",
        "sql" => " and c.isUsing=# "
    ),
    array(
        "name" => "mailCode",
        "sql" => " and c.mailCode=# "
    ),
    array(
        "name" => "inKeys",
        "sql" => " and c.inKeys=# "
    ),
    array(
        "name" => "isMailManager",
        "sql" => " and c.isMailManager=# "
    ),
    array(
        "name" => "lastTime",
        "sql" => " and c.lastTime=# "
    ),
    array(
        "name" => "intervalDay",
        "sql" => " and c.intervalDay=# "
    ),
    array(
        "name" => "regularPlan",
        "sql" => " and c.regularPlan=# "
    )
);