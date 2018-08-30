<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$limit = isset($_GET['limit']) && $_GET['limit'] > 0 ? 'limit 0,'.$_GET['limit'] : ''; // 记录数限制

if($_GET['searchType'] == 'sales'){ // 统计类型为借用人
    $groupType = "if(c.limits='客户',c.salesName,c.createName)";
    $condition = '';
}elseif($_GET['searchType'] == 'product'){ // 统计类型为产品
    $groupType = 'e.productName';
    $condition = '';
}else if($_GET['searchType'] == 'saleman'){ // 统计类型为销售员
    $groupType = 'c.salesName';
    $condition = 'AND c.salesName IS NOT NULL AND c.salesName <> ""';
}else{ // 统计类型为客户
    $groupType = 'c.customerName';
    $condition = 'AND c.customerName IS NOT NULL AND c.customerName <> ""';
}
if($_GET['countType'] == 'num'){ // 数量
    $countStr = "SUM(e.executedNum - e.backNum)";
}else{ // 统计类型为客户
    $countStr = "SUM(IF(i.priCost IS NULL , 0 ,(e.executedNum - e.backNum)*i.priCost))";
}

$QuerySQL = <<<QuerySQL
SELECT
	$groupType  AS searchType,
	$countStr AS countType
FROM
	oa_borrow_equ e LEFT JOIN oa_borrow_borrow c ON e.borrowId = c.id
	LEFT JOIN
	oa_stock_product_info i ON e.productId = i.id
WHERE c.isTemp = 0 AND c.backStatus <> 1 AND e.executedNum > 0 AND e.executedNum > backNum
$condition
GROUP BY $groupType
ORDER BY $countStr DESC,$groupType $limit
QuerySQL;
file_put_contents('D:sql.text',$QuerySQL);
GenAttrXmlData($QuerySQL, false);