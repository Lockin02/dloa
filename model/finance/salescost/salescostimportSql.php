<?php
/**
 * @author Show
 * @Date 2011年5月21日 星期六 14:47:06
 * @version 1.0
 * @description:财务会计期间表 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.exeDeptName,c.exeDeptCode,c.salesArea,c.salesAreaId,c.costMan,c.costManId,c.costAmount,c.belongMonth from oa_sales_costrecord c where 1 ",
);

$condition_arr = array(
    array(
        "name" => "exeDeptName",
        "sql" => " and c.exeDeptName=# "
    ),
    array(
        "name" => "exeDeptCode",
        "sql" => " and c.exeDeptCode=# "
    ),
    array(
        "name" => "salesArea",
        "sql" => " and c.salesArea=# "
    ),
    array(
        "name" => "salesAreaId",
        "sql" => " and c.salesAreaId=# "
    ),
    array(
        "name" => "costMan",
        "sql" => " and c.costMan=# "
    ),
    array(
        "name" => "costManId",
        "sql" => " and c.costManId=# "
    ),
    array(
        "name" => "belongMonth",
        "sql" => " and c.belongMonth=# "
    ),
    array(
        "name" => "paramYear",
        "sql" => " and c.belongYear=# "
    ),array(
        "name" => "theYear",
        "sql" => " and c.belongYear=# "
    ),
    array(
        "name" => "paramAreaId",
        "sql" => " and c.salesAreaId=# "
    ),
    array (
        "name" => "salesAreaSearch",
        "sql" => " and c.salesArea like CONCAT('%',#,'%') "
    ),
    array (
        "name" => "exeDeptNameSearch",
        "sql" => " and c.exeDeptName like CONCAT('%',#,'%') "
    ),
    array (
        "name" => "belongMonthSearch",
        "sql" => " and c.belongMonth like CONCAT('%',#,'%') "
    ),
    array (
        "name" => "costManSearch",
        "sql" => " and c.costMan like CONCAT('%',#,'%') "
    )
);