<?php

/**
 * @author Administrator
 * @Date 2012年11月8日 11:04:46
 * @version 1.0
 * @description:收货通知物料清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.serialNo,c.id ,c.mainId ,c.rObjCode ,c.docType ,c.docId ,c.docCode ,c.productName,
			c.contEquId ,c.productId,c.productCode ,c.productModel ,c.productType ,c.number ,c.executedNum ,c.unitName ,c.aidUnit,
			c.converRate ,c.stockId ,c.stockCode ,c.stockName ,c.shelfLife ,c.prodDate ,c.validDate ,c.isDel ,c.changeTips,
			c.qualityNum,c.qPassNum,c.qBackNum,c.compensateNum
		from oa_stock_withdraw_equ c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "serialNo",
		"sql" => " and c.Id like CONCAT('%',#,'%') "
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array (
		"name" => "rObjCode",
		"sql" => " and c.rObjCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "docType",
		"sql" => " and c.docType=# "
	),
	array (
		"name" => "docId",
		"sql" => " and c.docId=# "
	),
	array (
		"name" => "docCode",
		"sql" => " and c.docCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "productName",
		"sql" => " and c.productName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "contEquId",
		"sql" => " and c.contEquId=# "
	),
	array (
		"name" => "productCode",
		"sql" => " and c.productCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "productModel",
		"sql" => " and c.productModel=# "
	),
	array (
		"name" => "productType",
		"sql" => " and c.productType=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "executedNum",
		"sql" => " and c.executedNum=# "
	),
	array (
		"name" => "unitName",
		"sql" => " and c.unitName=# "
	),
	array (
		"name" => "aidUnit",
		"sql" => " and c.aidUnit=# "
	),
	array (
		"name" => "converRate",
		"sql" => " and c.converRate=# "
	),
	array (
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array (
		"name" => "stockCode",
		"sql" => " and c.stockCode=# "
	),
	array (
		"name" => "stockName",
		"sql" => " and c.stockName=# "
	),
	array (
		"name" => "shelfLife",
		"sql" => " and c.shelfLife=# "
	),
	array (
		"name" => "prodDate",
		"sql" => " and c.prodDate=# "
	),
	array (
		"name" => "validDate",
		"sql" => " and c.validDate=# "
	),
	array (
		"name" => "isDel",
		"sql" => " and c.isDel=# "
	),
	array (
		"name" => "changeTips",
		"sql" => " and c.changeTips=# "
	),
	array (
		"name" => "numSql",
		"sql" => "$"
	)
)
?>