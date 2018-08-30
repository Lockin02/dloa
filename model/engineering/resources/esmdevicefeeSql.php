<?php
/**
 * @author show
 * @Date 2014年9月26日 15:15:21
 * @version 1.0
 * @description:工程设备折旧 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.listId ,c.borrowInfoId ,c.borrowIsOver ,c.projectId ,c.beginDate ,c.endDate ,
            c.days ,c.fee ,c.createId ,c.createName ,c.createTime
        from oa_esm_resource_fee c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "listId",
        "sql" => " and c.listId=# "
    ),
    array(
        "name" => "borrowInfoId",
        "sql" => " and c.borrowInfoId=# "
    ),
    array(
        "name" => "borrowIsOver",
        "sql" => " and c.borrowIsOver=# "
    ),
    array(
        "name" => "projectId",
        "sql" => " and c.projectId=# "
    ),
    array(
        "name" => "beginDate",
        "sql" => " and c.beginDate=# "
    ),
    array(
        "name" => "endDate",
        "sql" => " and c.endDate=# "
    ),
    array(
        "name" => "days",
        "sql" => " and c.days=# "
    ),
    array(
        "name" => "fee",
        "sql" => " and c.fee=# "
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(
        "name" => "createName",
        "sql" => " and c.createName=# "
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime=# "
    )
);