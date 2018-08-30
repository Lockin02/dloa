<?php
/**
 * @author Show
 * @Date 2011年4月9日 星期六 10:51:50
 * @version 1.0
 * @description:license基本信息 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.name ,c.oXmlFileName,c.remark ,c.nXmlFileName ,c.phpFileName ,c.xDocAddress ,c.pDocAddress ,c.createName ,c.createTime,c.createId ,c.updateName ,c.updateTime,c.updateId,c.allNodesNum,c.licenseType,c.isDefault,c.isUse  from oa_license_baseinfo c where 1=1 ",
    "easy_select" => "select c.id ,c.nXmlFileName ,c.phpFileName from oa_license_baseinfo c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "ids",
        "sql" => " and c.id in(arr) "
    ),
    array(
        "name" => "name",
        "sql" => " and c.name=# "
    ),
    array(
        "name" => "oXmlFileName",
        "sql" => " and c.oXmlFileName=# "
    ),
    array(
        "name" => "nXmlFileName",
        "sql" => " and c.nXmlFileName=# "
    ),
    array(
        "name" => "phpFileName",
        "sql" => " and c.phpFileName=# "
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(
        "name" => "updateId",
        "sql" => " and c.updateId=# "
    ),
    array(
        "name" => "isUse",
        "sql" => " and c.isUse=# "
    )
);