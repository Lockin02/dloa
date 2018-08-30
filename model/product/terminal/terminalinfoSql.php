<?php

/**
 * @author eric
 * @Date 2013-4-16 13:39:53
 * @version 1.0
 * @description: 终端信息 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id,c.terminalName , c.productName, c.productId, c.typeId, c.typeName, c.orderIndex, c.versionStatus, c.formalVersion, c.newVersion, c.softFunction, "
                        ." c.materialsId, c.materialsName, c.remark, c.createName, c.createId, c.createTime, c.updateName, c.updateId, c.updateTime ,c.deviceType,c.deviceTypeCode,c.os,c.osCode,c.supportNetwork,c.supportNetworkCode"
                        ." from oa_terminal_terminalinfo c where 1=1"
);
$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "terminalName",
        "sql" => " and c.terminalName like CONCAT('%',#,'%')  "
    ),
    array(
        "name" => "terminalNameEq",
        "sql" => " and c.terminalName= # "
    ),
    array(
        "name" => "versionStatus",
        "sql" => " and c.versionStatus= # "
    ),
    array(
        "name" => "softFunction",
        "sql" => " and c.softFunction  like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "typeName",
        "sql" => " and c.typeName like CONCAT('%',#,'%')  "
    ),
    array(
        "name" => "productName",
        "sql" => " and c.productName like CONCAT('%',#,'%')  "
    ),
    array(
        "name" => "productId",
        "sql" => " and CONCAT(',',productId,',') like '%,$,%'"
    ),
    array(
        "name" => "typeId",
        "sql" => " and CONCAT(',',typeId,',') like '%,$,%'"
    )

);
?>
