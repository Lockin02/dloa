<?php
$sql_arr = array (
	"sum_incont" => " select c.id,c.contOnlyId,c.productName,c.productId,c.ptype,c.contId,c.contName,c.productNumber,c.amount,c.amountIssued from oa_contract_sales_equ c where 1=1 "
);
$condition_arr = array (

	//********************2010-10-6�޸�*****************/
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
	//contOnlyIdΪ�豸Ψһ���
	array (
		"name" => "equIds",
		"sql" => " and contOnlyId in(arr) "
	),
	array (
		"name" => "productName",
		"sql"=>" and c.productName like CONCAT('%',#,'%') "
	)

	//**********************�޸Ľ���**********************/
);
?>