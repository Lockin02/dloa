<?php

/**
 * @author eric
 * @Date 2013-4-17 9:29:49
 * @version 1.0
 * @description: 功能性分类 sql配置文件 
 */
$sql_arr = array(
    "select_default" => "select c.id, c.typeName, c.productName, c.productId, c.orderIndex, c.remark, c.createName, c.createId, c.createTime, c.updateName, "
                        ." c.updateId, c.updateTime from oa_terminal_functiontype c where 1=1"
);
$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "productId",
        "sql" => " and c.productId=# "
    ),
    array(
        "name" => "typeNameEq",
        "sql" => " and c.typeName=# "
    ),
    array(
        "name" => "typeName",
        "sql" => " and c.typeName like CONCAT('%',#,'%')  "
    )
);
?>
