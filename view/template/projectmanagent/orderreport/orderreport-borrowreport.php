<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$limit = isset($_GET['limit']) && $_GET['limit'] > 0 ? 'limit 0,'.$_GET['limit'] : ''; // ��¼������

if($_GET['searchType'] == 'sales'){ // ͳ������Ϊ������
    $groupType = "if(c.limits='�ͻ�',c.salesName,c.createName)";
    $condition = '';
}elseif($_GET['searchType'] == 'product'){ // ͳ������Ϊ��Ʒ
    $groupType = 'e.productName';
    $condition = '';
}else if($_GET['searchType'] == 'saleman'){ // ͳ������Ϊ����Ա
    $groupType = 'c.salesName';
    $condition = 'AND c.salesName IS NOT NULL AND c.salesName <> ""';
}else{ // ͳ������Ϊ�ͻ�
    $groupType = 'c.customerName';
    $condition = 'AND c.customerName IS NOT NULL AND c.customerName <> ""';
}
if($_GET['countType'] == 'num'){ // ����
    $countStr = "SUM(e.executedNum - e.backNum)";
}else{ // ͳ������Ϊ�ͻ�
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