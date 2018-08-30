<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$beginYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET ['beginMonth'], $_GET ['beginYear'] ); //这个月有多少天
$monthBeginDate = $_GET ['beginYear'] . "-" . $_GET ['beginMonth'] . "-1"; //月开始日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET ['endMonth'], $_GET ['endYear'] ); //这个月有多少天
$monthEndDate = $_GET ['endYear'] . "-" . $_GET ['endMonth'] . "-" . $endYearMonthNum; //月结束日期
$productId = $_GET ['productId'];
$docStatus = $_GET ['docStatus'];
$isRed = $_GET ['isRed'];
$moneyLimit=$_GET['moneyLimit'];

$condition = " and i.auditDate BETWEEN '$monthBeginDate' and '$monthEndDate' ";

if (! empty ( $productId )) {
	$condition .= " and ii.productId =$productId ";
}
if (! empty ( $docStatus )) {
	$condition .= " and i.docStatus ='$docStatus' ";
}
if (! empty ( $isRed )) {
	$condition .= " and i.isRed =$isRed ";
}
if ($moneyLimit) {
    $subPrice= "  ii.`subPrice`";
    $price= "  ii.`price`";
}else{
    $subPrice= " 0 as subPrice";
    $price= " 0 as price";
}

$QuerySQL = <<<QuerySQL

	select i.id,DATE_FORMAT(auditDate,'%Y.%m') as datePeriod,i.`auditDate`,i.`docCode`,i.`relDocName`,ii.`inStockName`,
			i.`purchaserName`,i.`clientName`,i.`createName`,i.`auditerName`,ii.k3Code, ii.`productCode`,ii.`productName`,ii.`unitName`, $price,$subPrice,
			case i.`docStatus` when 'WSH' then '未审核' else '已审核' end as docStatus ,ii.proType,
			 case i.`isRed` when '0' then ii.`actNum` else -ii.`actNum` end as actNum
			 from oa_stock_instock i  inner join oa_stock_instock_item ii on(ii.mainId=i.id)
			where i.docType='RKPRODUCT' $condition ORDER BY  i.`auditDate`,i.`docCode` asc;
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );