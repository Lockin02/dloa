<?php

/**
 * @author eric
 * @Date 2013-4-15 14:33:13
 * @version 1.0
 * @description: 终端产品管理 Sql配置文件 
 */
$sql_arr = array(
    "select_default" => "select c.id, c.productName, c.productCode, c.orderIndex, c.remark, c.createName, c.createId, c.createTime, c.updateName, c.updateId, c.updateTime "
    . " from oa_terminal_product c where 1=1",
    "select_product" => "select c.id,c.productCode as code,c.productName as name from oa_terminal_product c where  1=1 "
);
$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "productNameEq",
        "sql" => " and c.productName=# "
    ),
    array(
        "name" => "productCodeEq",
        "sql" => " and c.productCode=# "
    ),
    array(
        "name" => "productName",
        "sql" => " and c.productName like CONCAT('%',#,'%')  "
    ),
    array(
        "name" => "productCode",
        "sql" => " and c.productCode like CONCAT('%',#,'%') "
    ),
);
?>
