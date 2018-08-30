<?php

/**
 * @author eric
 * @Date 2013-4-16 13:39:53
 * @version 1.0
 * @description: 终端关联信息 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id,c.productId,c.terminalId,c.functionId,c.functionTip from oa_terminal_terminalfunction c where 1=1"
);
$condition_arr = array(

    array(
        "name" => "productId",
        "sql" => " and c.productId=#"
    ),
    array(
        "name" => "terminalId",
        "sql" => " and c.terminalId=#"
    ),
    array(
        "name" => "functionId",
        "sql" => " and c.functionId=#"
    )

);
?>
