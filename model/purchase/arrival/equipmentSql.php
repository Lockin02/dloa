<?php
/**
 * @author Administrator
 * @Date 2011年5月4日 21:40:04
 * @version 1.0
 * @description:收料通知单物料清单信息 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.arrivalId ,c.contractId ,c.businessType ,c.businessId ,c.productId ,c.productName ,c.sequence ,c.pattem ,c.batchNum ,c.units ,c.price ,c.moneyAll ,c.arrivalNum ,oldArrivalNum ,c.stockId ,c.stockName ,c.storageNum ,c.checkType,c.arrivalDate,c.month,c.qualityCode,c.qualityName,c.qualityPassNum,c.deliveredNum,c.completionTime ,c.isQualityBack from oa_purchase_arrival_equ c where 1=1 ",
	"select_stock"=>"select c.id ,c.arrivalId ,c.contractId ,c.businessType ,c.businessId ,c.productId ,c.productName ,c.sequence ,c.pattem ,c.batchNum ,c.units ,c.price ,c.moneyAll ,c.arrivalNum ,c.stockId ,c.stockName ,c.storageNum ,c.checkType,c.arrivalDate,c.month,c.qualityCode,c.qualityName,c.qualityPassNum,c.deliveredNum,c.completionTime
	from oa_purchase_arrival_equ c where ((c.qualityCode!='ZJSXCG' and c.arrivalNum>c.storageNum) or (c.qualityCode='ZJSXCG' and c.qualityPassNum>c.storageNum)) "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "arrivalId",
		"sql" => " and c.arrivalId=# "
	),
	array(
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array(
		"name" => "businessType",
		"sql" => " and c.businessType=# "
	),
	array(
		"name" => "businessId",
		"sql" => " and c.businessId=# "
	),
	array(
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array(
		"name" => "productName",
		"sql" => " and c.productName=# "
	),
	array(
		"name" => "sequence",
		"sql" => " and c.sequence=# "
	),
	array(
		"name" => "pattem",
		"sql" => " and c.pattem=# "
	),
	array(
		"name" => "batchNum",
		"sql" => " and c.batchNum=# "
	),
	array(
		"name" => "units",
		"sql" => " and c.units=# "
	),
	array(
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array(
		"name" => "moneyAll",
		"sql" => " and c.moneyAll=# "
	),
	array(
		"name" => "arrivalNum",
		"sql" => " and c.arrivalNum=# "
	),
	array(
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array(
		"name" => "stockName",
		"sql" => " and c.stockName=# "
	),
	array(
		"name" => "storageNum",
		"sql" => " and c.storageNum=# "
	),
	array(
		"name" => "checkType",
		"sql" => " and c.checkType=# "
	),
	array(
		"name" => "unstrock",
		"sql" => " and c.arrivalNum>c.storageNum "
	)
)
?>