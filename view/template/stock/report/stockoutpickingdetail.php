<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$beginYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET['beginMonth'], $_GET['beginYear']); //这个月有多少天
$monthBeginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";//月开始日期
$endYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear']); //这个月有多少天
$monthEndDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;//月结束日期
$productId = $_GET['productId'];
$deptName = trim($_GET['deptName']);
$pickCode = $_GET['pickCode'];
$priceLimit = $_GET['priceLimit'];
$subPriceLimit = $_GET['subPriceLimit'];

$condition = " and o.auditDate BETWEEN '$monthBeginDate' and '$monthEndDate' ";

if (!empty($productId)) {
    $condition .= " and oi.productId =$productId ";
}
if (!empty($deptName)) {
    $condition .= " and o.deptName ='$deptName' ";
}
if (!empty($pickCode)) {
    $condition .= " and (o.pickCode ='$pickCode' or u.USER_ID = '$pickCode')";
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

select o.id,DATE_FORMAT(auditDate,'%Y.%m') as datePeriod,o.`auditDate` as docDate,o.`auditDate`,
case o.`docStatus` when 'WSH' then '未审核' else '已审核' end as docStatus,
        o.`docCode`,o.`customerName`,oi.`stockName`,o.`deptName`,o.`pickName`,oi.serialnoName,oi.batchNum,
	    oi.k3Code, oi.`productCode`,oi.`productName`,oi.`unitName`,$cost,oi.proType,
        CASE `o`.`isRed` when '0' then oi.`actOutNum` else -oi.`actOutNum` end as actOutNum,
        CASE `o`.`isRed` when '0' then $subCost else -$subCost end as subCost,o.moduleName
	 from oa_stock_outstock o
	 LEFT JOIN `user` u on u.USER_NAME = o.pickName
	 inner join oa_stock_outstock_item oi  ON(o.id=oi.mainId)
     where o.`docType`='CKPICKING' $condition  order by o.`auditDate`,o.`docCode` asc;
QuerySQL;
GenAttrXmlData($QuerySQL, false);