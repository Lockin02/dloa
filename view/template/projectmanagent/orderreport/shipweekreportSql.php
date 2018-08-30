<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
//$beginDate = $_GET['beginDate'];
//$endDate = $_GET['endDate'];
$condition="";
//if(isset($beginDate)){
//	$condition.=" and o.ExaDT>= '". $beginDate . "' ";
//}
//if(isset($endDate)){
//	$condition.=" and o.ExaDT<= '". $endDate . "' ";
//}
//echo "SELECT
//	o.orderCode,
//	o.orderTempCode,
//	o.orderName,
//	o.orgid AS orderId,
//	o.customerName,
//	o.ExaDT,
//	o.deliveryDate,
//	sv.id AS pid,
//	sv.productName,
//	sum(sv.number) AS number,
//	SUM(sv.executedNum) AS executedNum,
//	SUM(sv.issuedProNum) AS issuedProNum,
//	SUM(sv.issuedPurNum) AS issuedPurNum,
//	spi.exeNum,
//	spi.stockName,
//	IFNULL(0,SUM(pae.amountIssued)) AS amountIssued
//FROM
//	view_oa_order o
//INNER JOIN oa_shipequ_view sv ON (
//	o.orgid = sv.orderOrgid
//	AND o.tablename = sv.tablename
//)
//INNER JOIN oa_stock_inventory_info spi ON (sv.productId = spi.productId)
//LEFT JOIN oa_stock_syteminfo ss ON (
//	spi.stockCode = ss.salesStockCode
//)
//LEFT JOIN oa_purch_apply_equ pae ON (
//	pae.sourceID=o.orgid
//	AND pae.purchType=o.tablename
//	AND pae.productId=sv.productId
//	AND pae.isTemp=0
//)
//WHERE
//	o.ExaStatus IN ('完成', '变更审批中')
//AND o.isTemp = 0
//AND sv.number>sv.executedNum
//AND ss.id = '1' $condition
//GROUP BY
//	o.orgid,
//	o.tablename,
//	sv.productId
//ORDER BY
//	o.ExaDT ASC";
$QuerySQL = <<<QuerySQL
SELECT
	o.orderCode,
	o.orderTempCode,
	o.orderName,
	o.orgid AS orderId,
	o.customerName,
	o.ExaDT,
	o.deliveryDate,
	sv.id AS pid,
	sv.productName,
	sum(sv.number) AS number,
	SUM(sv.executedNum) AS executedNum,
	SUM(sv.issuedProNum) AS issuedProNum,
	SUM(sv.issuedPurNum) AS issuedPurNum,
	spi.exeNum,
	spi.stockName,
	IFNULL(0,SUM(pae.amountIssued)) AS amountIssued
FROM
	view_oa_order o
INNER JOIN oa_shipequ_view sv ON (
	o.orgid = sv.orderOrgid
	AND o.tablename = sv.tablename
)
INNER JOIN oa_stock_inventory_info spi ON (sv.productId = spi.productId)
LEFT JOIN oa_stock_syteminfo ss ON (
	spi.stockCode = ss.salesStockCode
)
LEFT JOIN oa_purch_apply_equ pae ON (
	pae.sourceID=o.orgid
	AND pae.purchType=o.tablename
	AND pae.productId=sv.productId
	AND pae.isTemp=0
)
WHERE
	o.ExaStatus IN ('完成', '变更审批中')
AND o.isTemp = 0
AND sv.number>sv.executedNum
AND ss.id = '1' $condition
GROUP BY
	o.orgid,
	o.tablename,
	sv.productId
ORDER BY
	o.ExaDT ASC
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
