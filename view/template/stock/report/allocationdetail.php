<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php

$beginYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET ['beginMonth'], $_GET ['beginYear'] ); //������ж�����
$monthBeginDate = $_GET ['beginYear'] . "-" . $_GET ['beginMonth'] . "-1"; //�¿�ʼ����
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET ['endMonth'], $_GET ['endYear'] ); //������ж�����
$monthEndDate = $_GET ['endYear'] . "-" . $_GET ['endMonth'] . "-" . $endYearMonthNum; //�½�������
$productId = $_GET ['productId'];

$condition = " and a.auditDate BETWEEN '$monthBeginDate' and '$monthEndDate' ";

if (! empty ( $productId )) {
	$condition .= " and ai.productId =$productId ";
}

$QuerySQL = <<<QuerySQL
select DATE_FORMAT(a.auditDate,'%Y.%m') as datePeriod,a.`auditDate` as docDate,
		case a.`docStatus` when 'WSH' then 'δ���' else '�����' end as docStatus,
        a.`docCode` ,ai.exportStockName,ai.importStockName,ai.productCode ,ai.productName,ai.unitName,ai.pattern,ai.allocatNum,
				ai.cost,ai.subcost
 from  oa_stock_allocation a inner join oa_stock_allocation_item ai on(ai.mainId=a.id) where 1=1 $condition
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
