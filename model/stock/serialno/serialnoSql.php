<?php
/**
 * @author Administrator
 * @Date 2011年5月17日 14:10:08
 * @version 1.0
 * @description:物料序列号台账 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.inDocId ,c.inDocCode ,c.inDocItemId ,c.outDocCode ,c.outDocId ,c.outDocItemId ,c.productId ,c.productName ,c.productCode ,c.pattern ,c.stockId ,c.stockName ,c.stockCode ,c.batchNo ,c.sequence ,c.seqStatus ,c.shelfLife ,c.prodDate ,c.validDate ,c.remark ,c.relDocType ,c.relDocId ,c.relDocCode ,c.relDocName  from oa_stock_product_serialno c where 1=1 ",
	"select_serialno" => "select c.* from oa_serialnorecord_view c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "serialno",
		"sql" => " and c.serialnoName like CONCAT('%,',#,',%') "
	),
	array (
		"name" => "isTemp",
		"sql" => " and c.isTemp =#"
	),
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in($) "
	),
	array (
		"name" => "idsNot",
		"sql" => " and c.id not in($) "
	),
	array (
		"name" => "inDocId",
		"sql" => " and c.inDocId=# "
	),
	array (
		"name" => "inDocCode",
		"sql" => " and c.inDocCode=# "
	),
	array (
		"name" => "likeinDocCode",
		"sql" => " and c.inDocCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "likesequence",
		"sql" => " and c.sequence like CONCAT('%',#,'%') "
	),
	array (
		"name" => "inDocItemId",
		"sql" => " and c.inDocItemId=# "
	),
	array (
		"name" => "outDocCode",
		"sql" => " and c.outDocCode=# "
	),
	array (
			"name" => "likeoutDocCode",
			"sql" => " and c.outDocCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "outDocId",
		"sql" => " and c.outDocId=# "
	),
	array (
		"name" => "outDocItemId",
		"sql" => " and c.outDocItemId=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
    array (
        "name" => "productIdArr",
        "sql" => " and c.productId in(arr) "
    ),
	array (
		"name" => "productName",
		"sql" => " and c.productName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "productCode",
		"sql" => " and c.productCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "pattern",
		"sql" => " and c.pattern=# "
	),
	array (
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array (
		"name" => "stockName",
		"sql" => " and c.stockName=# "
	),
	array (
		"name" => "stockCode",
		"sql" => " and c.stockCode=# "
	),
	array (
		"name" => "batchNo",
		"sql" => " and c.batchNo=# "
	),
	array (
		"name" => "sequence",
		"sql" => " and c.sequence=# "
	),
	array (
		"name" => "notseqStatus",
		"sql" => " and c.seqStatus<># "
	),
	array (
		"name" => "seqStatus",
		"sql" => " and c.seqStatus=# "
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
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "relDocType",
		"sql" => " and c.relDocType=# "
	),
	array (
		"name" => "relDocId",
		"sql" => " and c.relDocId=# "
	),
	array (
		"name" => "relDocCode",
		"sql" => " and c.relDocCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "relDocName",
		"sql" => " and c.relDocName=# "
	),
	array (
		"name" => "relDocItemId",
		"sql" => " and c.relDocItemId=# "
	)
);