<?php
/**
 * @author Show
 * @Date 2011年5月31日 星期二 19:31:17
 * @version 1.0
 * @description:期初余额表 sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id ,c.thisYear ,c.thisMonth ,c.thisDate ,c.productId ,c.productModel ,c.productName ,
			c.productNo ,c.units ,c.pricing ,c.batchNo ,c.property ,c.clearingNum ,c.balanceAmount ,c.price ,
			c.stockName,c.stockId,c.stockCode,c.k3Code
		from oa_finance_stockbalance c where 1=1 ",
	'count_proin' => 'select
			c.id,c.purchaserName,c.purchaserCode,i.productId ,i.productCode,i.productName,i.pattern,i.unitName,
			sum(i.actNum) as actNum ,
			i.price as price, i.price as oldPrice , sum(i.subPrice) as subPrice ,group_concat(i.batchNum) as batchNum,
			pro.ext2 as k3Code
		from oa_stock_instock_item i
			inner join
			oa_stock_instock c
				on c.id = i.mainId
			left join
			oa_stock_product_info pro
				on i.productId = pro.id
		where 1',
	'count_overage' => 'select c.id,i.productId ,i.pattern,i.productName,i.productCode,sum(i.actNum) as actNum ,
			round(sum(i.subPrice) / sum(i.actNum),6) as price, i.price as oldPrice ,
			sum(i.subPrice) as subPrice
		from oa_stock_check_info c left join oa_stock_check_item i on c.id = i.checkId'
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
	array(
		"name" => "thisYear",
		"sql" => " and c.thisYear=# "
	),
	array(
		"name" => "thisMonth",
		"sql" => " and c.thisMonth=# "
	),
	array(
		"name" => "thisDate",
		"sql" => " and c.thisDate=# "
	),
	array(
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array(
		"name" => "iproductId",
		"sql" => " and i.productId=# "
	),
	array(
		"name" => "productNoBegin",
		"sql" => " and ( i.productCode between # "
	),
	array(
		"name" => "productNoEnd",
		"sql" => " and # )"
	),
	array(
		"name" => "productNo",
		"sql" => " and c.productNo=# "
	),
	array(
		"name" => "k3CodeLike",
		"sql" => " and c.k3Code like concat('%',#,'%') "
	),
	array(
		"name" => "productNoLike",
		"sql" => " and c.productNo like concat('%',#,'%') "
	),
	array(
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array(
		"name" => "stockName",
		"sql" => " and c.stockName like concat('%',#,'%') "
	),
	array(
		"name" => "inventoryId",
		"sql" => " and c.inventoryId=# "
	),
	array(
		"name" => "docType",
		"sql" => " and c.docType = # "
	),
	array(
		"name" => "isRed",
		"sql" => " and c.isRed = # "
	),
	array(
		"name" => "cThisYear",
		"sql" => " and year(c.auditDate) = #"
	),
	array(
		"name" => "cThisMonth",
		"sql" => " and month(c.auditDate) = #"
	),
	array(
		"name" => "cdocStatus",
		"sql" => " and c.docStatus = #"
	),
	array(
		"name" => "cauditStatus",
		"sql" => " and c.auditStatus = #"
	),
	array(
		"name" => "cDocDateYear",
		"sql" => " and year(c.docDate) = #"
	),
	array(
		"name" => "cDocDateMonth",
		"sql" => " and month(c.docDate) = #"
	),
	array(
		"name" => "cExaStatus",
		"sql" => " and c.ExaStatus = #"
	),
	array(
		"name" => "checkType",
		"sql" => " and c.checkType = #"
	),
	array(//产品入库核算用
		"name" => "purchaserCodes",
		"sql" => " and c.purchaserCode in(arr)"
	)
);