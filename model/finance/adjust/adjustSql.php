<?php
/**
 * @author Show
 * @Date 2011��1��13�� ������ 17:22:36
 * @version 1.0
 * @description:��� sql�����ļ�
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.adjustCode ,c.supplierName ,c.supplierId ,c.relatedId ,c.status ,c.stockName,c.formDate ,c.amount ,c.createId ,c.createName ,c.createTime  from oa_finance_adjustment c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
    ),
    array(
   		"name" => "adjustCode",
   		"sql" => " and c.adjustCode=# "
   	),
    array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId=# "
   	),
    array(
   		"name" => "relatedId",
   		"sql" => " and c.relatedId=# "
   	),
    array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	),
    array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
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
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
	),
    array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%') "
   	),
    array(
   		"name" => "formDate",
   		"sql" => " and c.formDate like BINARY CONCAT('%',#,'%') "
   	)
);