<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$beginYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET['beginMonth'], $_GET['beginYear']); //������ж�����
$monthBeginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";//�¿�ʼ����
$endYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear']); //������ж�����
$monthEndDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;//�½�������
$objCode = $_GET['objCode'];
$customerId = $_GET['customerId'];
$isRed = $_GET['isRed'];
$priceLimit = $_GET['priceLimit'];
$subPriceLimit = $_GET['subPriceLimit'];
$productCode = $_GET['productCode'];
$k3Code = $_GET['k3Code'];

$condition = " and o.auditDate BETWEEN '$monthBeginDate' and '$monthEndDate' ";

if (!empty($objCode)) {
    $condition .= " and o.contractCode like '%$objCode%' ";
}
if (!empty($customerId)) {
    $condition .= " and o.customerId =$customerId ";
}
if (!empty($isRed)) {
    $condition .= " and o.isRed =$isRed ";
}
if (!empty($productCode)) {
    $condition .= " and oi.productCode LIKE '%$productCode%' ";
}
if (!empty($k3Code)) {
    $condition .= " and oi.k3Code LIKE '%$k3Code%' ";
}
$priceLimitArr = explode(",", $priceLimit);
if (!empty($priceLimitArr) && in_array("cost", $priceLimitArr)) {
    $cost = "oi.`cost`";
} else {
    $cost = "0 as cost";
}

$subPriceLimitArr = explode(",", $subPriceLimit);
if (!empty($subPriceLimitArr) && in_array("subCost", $subPriceLimitArr)) {
    $subCost = "oi.`subCost`";
} else {
    $subCost = "0 ";
}

$QuerySQL = <<<QuerySQL
select o.id,DATE_FORMAT(auditDate,'%Y.%m') as datePeriod,o.`auditDate` as docDate,
		case o.`docStatus` when 'WSH' then 'δ���' else '�����' end as docStatus,
        o.`docCode`,o.`customerName`,oi.stockName,oi.serialnoName,
    	 o.`contractCode`,oi.proType,
	    oi.k3Code, oi.`productCode`,oi.`productName`,
        CASE `o`.`isRed` when '0' then oi.`actOutNum` else -oi.`actOutNum` end as actOutNum,
       $cost,
        CASE `o`.`isRed` when '0' then $subCost else -$subCost end as subCost,o.moduleName
	 from oa_stock_outstock o inner join oa_stock_outstock_item oi  ON(o.id=oi.mainId)
     where o.`docType`='CKSALES' $condition  order by o.`auditDate`,o.`docCode` asc
QuerySQL;
GenAttrXmlData($QuerySQL, false);