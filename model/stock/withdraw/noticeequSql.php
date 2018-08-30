<?php
/**
 * @author Administrator
 * @Date 2012年11月20日 10:22:14
 * @version 1.0
 * @description:入库通知单清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.drawId ,c.mainId ,c.mainCode ,c.rObjCode ,c.docType ,c.docId ,
			c.docCode ,c.planEquId ,c.productId ,c.productCode ,c.productModel ,c.productType ,c.number ,
			c.unitName ,c.aidUnit ,c.converRate ,c.stockId ,c.stockCode ,c.stockName ,c.shelfLife ,
			c.prodDate ,c.validDate ,c.productName ,c.executedNum
		from oa_stock_innotice_equ c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "drawId",
		"sql" => " and c.drawId=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array (
		"name" => "mainCode",
		"sql" => " and c.mainCode=# "
	),
	array (
		"name" => "rObjCode",
		"sql" => " and c.rObjCode=# "
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
		"sql" => " and c.docCode=# "
	),
	array (
		"name" => "docEquId",
		"sql" => " and c.docEquId=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array (
		"name" => "productCode",
		"sql" => " and c.productCode=# "
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
	array(
		"name" => "notExecuted",
		"sql" => " and c.executedNum <> c.number "
	)
)
?>