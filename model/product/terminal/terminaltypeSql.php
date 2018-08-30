<?php

/**
 * @author eric
 * @Date 2013-4-15 17:12:57
 * @version 1.0
 * @description: 终端分类 SQL配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id, c.typeName, c.productName, c.productId, c.orderIndex, c.remark, c.createName, c.createId, c.createTime, c.updateName, "
                        ." c.updateId, c.updateTime from oa_terminal_terminaltype c where 1=1"
);
$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "typeNameEq",
        "sql" => " and c.typeName=# "
    ),
    array(
        "name" => "productId",
        "sql" => " and c.productId=# "
    ),
    array(
        "name" => "typeName",
        "sql" => " and c.typeName like CONCAT('%',#,'%')  "
    ),
    array(
        "name" => "productIds",
        "sql" => " and c.productId in($) "
    )
);
?>
