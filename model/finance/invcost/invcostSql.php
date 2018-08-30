<?php
$sql_arr = array (
	"select_invcost" => "select c.id,c.objCode,c.objNo,amount,
	c.purType,c.payDate,c.supplierId,c.supplierName,c.bank,c.currency,c.adress,
	c.subjects,c.hookName,c.departments,c.status,c.acount,
	c.salesman,c.purcontId,c.purcontCode from oa_finance_invcost c where 1=1 ",
	"easy_list" => "select c.id,c.objCode,c.objNo,amount,
	c.supplierName,c.createTime,c.purcontId,c.purcontCode,c.payStatus from oa_finance_invcost c where 1=1"
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id =#"
	),
	array (
		"name" => "supplierId",
		"sql" => "and c.supplierId =#"
	),
	array (
		"name" => "objCode",
		"sql" => "and c.objCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "contractNumber",
		"sql" => "and c.contractNumber=#"
	),
	array (
		"name" => "supplierName",
		"sql" => "and c.supplierName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "status",
		"sql" => "and c.status = #"
	),
	array(
		"name" => "purcontId",
		"sql" => "and c.purcontId = #"
	),
	array(
		"name" => "payStatus",
		"sql" => "and c.payStatus = #"
	)
);
?>