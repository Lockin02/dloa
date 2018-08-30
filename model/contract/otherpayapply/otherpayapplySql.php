<?php

/**
 * @author Show
 * @Date 2012年3月31日 星期六 11:13:43
 * @version 1.0
 * @description:非销售类合同付款申请信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.contractId ,c.contractType ,c.bank ,c.account ,c.formDate ,c.payType ,c.feeDeptId ,
		c.feeDeptName ,c.payFor ,c.remark ,c.applyMoney,c.currency,c.place,c.currencyCode,c.rate,c.payee,c.isInvoice,c.comments,c.payForBusiness from oa_sale_otherpayapply c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array (
		"name" => "contractType",
		"sql" => " and c.contractType=# "
	),
	array (
		"name" => "bank",
		"sql" => " and c.bank=# "
	),
	array (
		"name" => "account",
		"sql" => " and c.account=# "
	),
	array (
		"name" => "formDate",
		"sql" => " and c.formDate=# "
	),
	array (
		"name" => "payType",
		"sql" => " and c.payType=# "
	),
	array (
		"name" => "feeDeptId",
		"sql" => " and c.feeDeptId=# "
	),
	array (
		"name" => "feeDeptName",
		"sql" => " and c.feeDeptName=# "
	),
	array (
		"name" => "payFor",
		"sql" => " and c.payFor=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "applyMoney",
		"sql" => " and c.applyMoney=# "
	),
	array (
		"name" => "payee",
		"sql" => " and c.payee=# "
	),
	array (
		"name" => "isInvoice",
		"sql" => " and c.isInvoice=# "
	),
	array (
		"name" => "comments",
		"sql" => " and c.comments=# "
	),
	array (
		"name" => "payForBusiness",
		"sql" => " and c.payForBusiness=# "
	)
)
?>