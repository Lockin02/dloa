<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
define('CLIENT_MULTI_RESULTS', 131072);
$condition='';
$productName=trim($_GET['productName']);
$productNo=trim($_GET['productNo']);
$theCodee=trim($_GET['theCode']);
$orderTypee=trim($_GET['orderType']);
if($productName){
	$condition.="and c.productName like '%{$productName}%' ";
}
if($productNo){
	$condition.="and c.productNo like'%{$productNo}%' ";
}
if($theCodee){
	$condition.="and c.theCode like'%{$theCodee}%' ";
}
if($orderTypee){
	$condition.="and c.orderType like'%{$orderTypee}%' ";
}
$QuerySQL = <<< MARK
SELECT c.id,c.theCode,c.orderType,c.productId,c.productName,c.productNo,n.actNum,c.number,p.totalNeedNum,(n.actNum-p.totalNeedNum) as remainNum,c.minNum,
IF((n.actNum-p.totalNeedNum)<c.minNum,'ÊÇ','·ñ') as isFill,if(c.minNum > (n.actNum-p.totalNeedNum),c.minNum- (n.actNum-p.totalNeedNum),0 )as fillup,c.leastOrderNum,c.purchPeriod
FROM(
SELECT c.id,c.borrowCode as theCode,'½èÊÔÓÃ' as orderType,c.productId,c.productName,c.productNo,c.number,IFNULL(o.minNum,0) as minNum,m.leastOrderNum,m.purchPeriod
 FROM oa_borrow_equ c 
LEFT JOIN oa_stock_safetystock o ON c.productId=o.productId 
INNER JOIN oa_stock_product_info m ON c.productId=m.id

UNION ALL

SELECT c.id,c.contractCode as theCode,c.contractTypeName as orderType,c.productId,c.productName,c.productCode,c.number,IFNULL(o.minNum,0) as minNum,m.leastOrderNum,m.purchPeriod
FROM oa_contract_equ c
LEFT JOIN oa_stock_safetystock o ON c.productId=o.productId
INNER JOIN oa_stock_product_info m ON c.productId=m.id
) c
LEFT JOIN 
(SELECT c.productId,IFNULL(SUM(c.actNum),0) as actNum 
FROM  oa_stock_inventory_info c INNER  JOIN oa_stock_syteminfo p ON c.stockCode != p.borrowStockCode GROUP BY c.productId ) n ON c.productId=n.productId
INNER JOIN
(SELECT c.productId,SUM(c.number) as totalNeedNum FROM 
(SELECT c.productId ,c.number FROM oa_contract_equ c 
UNION ALL
SELECT c.productId ,c.number FROM oa_borrow_equ c 
) c GROUP BY c.productId ORDER BY NULL 
) p ON c.productId=p.productId 
where 1=1 $condition order by c.productId,c.theCode
MARK;
//echo $QuerySQL;

GenAttrXmlData ( $QuerySQL, false );
?>
