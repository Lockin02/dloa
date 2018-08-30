<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$limit = isset($_GET['limit']) && $_GET['limit'] > 0 ? 'limit 0,'.$_GET['limit'] : ''; // ��¼������

if($_GET['searchType'] == 'sales'){ // ͳ������Ϊ������
    $condition = 'AND ((c.limits="�ͻ�" and c.salesName="'.$_GET['searchKey'].'") or (c.limits!="�ͻ�" and c.createName = "'.$_GET['searchKey'].'"))' ;
}elseif($_GET['searchType'] == 'product'){ // ͳ������Ϊ��Ʒ
    $condition = 'AND e.productName = "'.$_GET['searchKey'].'"' ;
}elseif($_GET['searchType'] == 'saleman'){ // ͳ������Ϊ����Ա
    $condition = 'AND c.salesName IS NOT NULL AND c.salesName <> "" AND c.salesName = "'.$_GET['searchKey'].'"' ;
}else{ // ͳ������Ϊ�ͻ�
    $condition = 'AND (c.customerName IS NOT NULL AND c.customerName <> "" AND c.customerName = "'.$_GET['searchKey'].'")' ;
}

$QuerySQL = <<<QuerySQL
SELECT
	c.id,c.Code,c.customerName,c.salesName,
	case c.limits
	when '�ͻ�'  then c.salesName
	else c.createName  end
	as createName,
	c.beginTime,c.closeTime,
	e.productNo,e.productName,e.productModel,e.executedNum,e.executedNum - e.backNum AS waitNum,e.backNum,
	IF(i.priCost IS NULL , 0 ,(e.executedNum - e.backNum)*i.priCost) AS equMoney,
	IF(i.priCost IS NULL , 0 , i.priCost) AS priCost,se.sequence
FROM
	oa_borrow_equ e LEFT JOIN oa_borrow_borrow c ON e.borrowId = c.id
	LEFT JOIN
	oa_stock_product_info i ON e.productId = i.id
		LEFT JOIN
	(SELECT GROUP_CONCAT(s.sequence) as sequence,s.relDocItemId
	FROM
	oa_stock_product_serialno s
	LEFT JOIN oa_borrow_equ be ON be.id=s.relDocItemId where s.relDocType='oa_borrow_borrow' and stockId=3  GROUP BY s.relDocItemId
	)se ON se.relDocItemId=e.id
WHERE c.isTemp = 0 AND c.backStatus <> 1 AND e.executedNum > 0 AND e.executedNum > backNum $condition
ORDER BY c.Code
QuerySQL;
file_put_contents('D:sql.text',$QuerySQL);
GenAttrXmlData($QuerySQL, false);