<?php

/**
 * @author eric
 * @Date 2013-4-17 9:53:07
 * @version 1.0
 * @description:功能性信息 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id,c.functionName ,c.englishName, c.typeName,c.typeId , c.productName, c.productId, c.orderIndex, c.remark, c.createName, c.createId, c.createTime, c.updateName, "
                        ." c.updateId, c.updateTime from oa_terminal_functioninfo c where 1=1"
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
        "name" => "typeId",
        "sql" => " and c.typeId=# "
    ),
    array(
        "name" => "functionNameEq",
        "sql" => " and c.functionName=# "
    ),
    array(
        "name" => "functionName",
        "sql" => " and c.functionName like CONCAT('%',#,'%')  "
    ),
    array(
        "name" => "englishName",
        "sql" => " and c.englishName like CONCAT('%',#,'%')  "
    )
);
?>
