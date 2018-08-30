<?php
$sql_arr = array (
	"product_list_page" => "select id,productNumber,productName,productId,productModel," .
			"contId,contName,contNumber,amount,beforeChangeAmount,alreadyCarryAmount," .
			"notCarryAmount,byWayAmount,stockAmount,canCarryAmount,alreadyStockAmount" .
			" from oa_contract_sales_equ where 1=1",
	"product_list" =>
				"select " .
				"id,contOnlyId,equipListId,higherList,productName," .
				"productId,productNumber,productModel,ptype,projArraDate," .
				"storageTypeId,storageTypeName,storageId,storageNumb,storageName," .
				"contNumber,contId,contName,version,beforeChangeAmount," .
				"amount,alreadyCarryAmount,notCarryAmount,byWayAmount,stockAmount," .
				"canCarryAmount,alreadyStockAmount,alreadyFlwgfollowing,amountIssuedActual,amountIssued," .
				"remark,price,countMoney," .
				"warrantyPeriod,productListId " .
				" from " .
					"oa_contract_sales_equ " .
				"where 1=1",
	"list_page" => "select " .
			//"oa_contract_sales_equ.* " .
					"oa_contract_sales_equ.id,contOnlyId,productNumber,storageId,canCarryAmount," .
					"productId,productName,equipListId,beforeChangeAmount,alreadyCarryAmount," .
					"amount,oa_contract_sales_equ.contNumber,oa_contract_sales_equ.contName,alreadyFlwgfollowing,byWayAmount   " .
				"from " .
					"oa_contract_sales left join oa_contract_sales_equ on( oa_contract_sales.id=oa_contract_sales_equ.contId and oa_contract_sales.contStatus = '1' and oa_contract_sales.isUsing='1' ) " .
				"where 1=1"



);
$condition_arr = array (
	array (
		"name" => "productName",
		"sql" => " and productName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "groupBy",
		"sql" => " group By # "
	),
	array(
		"name" => "productNumber",
		"sql" => " and productNumber=# "
	),
	array(
		"name" => "storageId",
		"sql" => " and storageId=# "
	),
	array(
		"name" => "productId",
		"sql" => " and productId=# "
	),
	array(
		"name" => "storageId",
		"sql" => " and storageId=# "
	),
	array(
		"name" => "selectEqu",
		"sql" => " and id in(arr)"
	)
//	,array(
//		"name" => "isUsing",
//		"sql" => " and oa_contract_sales.isUsing='1' "
//	)
);
?>