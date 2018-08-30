<?php
$sql_arr = array (
	"sum_incont" => " select c.id,c.contOnlyId,c.productName,c.productId,c.ptype,c.contId,c.contName,c.productNumber,c.amount,c.amountIssued from oa_contract_sales_equ c where 1=1 "
);
$condition_arr = array (

	//********************2010-10-6修改*****************/
	array (
		"name" => "contnumber",
		"sql" => " and contNumber = # "
	),
	array (
		"name" => "contid",
		"sql" => " and contId = # "
	),
	array (
		"name" => "basicId",
		"sql" => "and c.basicId = # "
	),
	//contOnlyId为设备唯一编号
	array (
		"name" => "equIds",
		"sql" => " and contOnlyId in(arr) "
	),
	array (
		"name" => "productName",
		"sql"=>" and c.productName like CONCAT('%',#,'%') "
	)

	//**********************修改结束**********************/
);
?>