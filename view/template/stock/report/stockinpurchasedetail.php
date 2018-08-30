<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$beginYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET ['beginMonth'], $_GET ['beginYear']); //这个月有多少天
$monthBeginDate = $_GET ['beginYear'] . "-" . $_GET ['beginMonth'] . "-1"; //月开始日期
$endYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET ['endMonth'], $_GET ['endYear']); //这个月有多少天
$monthEndDate = $_GET ['endYear'] . "-" . $_GET ['endMonth'] . "-" . $endYearMonthNum; //月结束日期
$productId = $_GET ['productId'];
$supplierId = $_GET ['supplierId'];
$isRed = $_GET ['isRed'];
$catchStatus = $_GET ['catchStatus'];
$remark = $_GET['remark'];
$moneyLimit = $_GET['moneyLimit'];

$condition = " and i.auditDate BETWEEN '$monthBeginDate' and '$monthEndDate' ";

if (!empty ($productId)) {
    $condition .= " and ii.productId =$productId ";
}
if (!empty ($supplierId)) {
    $condition .= " and i.supplierId =$supplierId ";
}
if (!empty ($isRed)) {
    $condition .= " and i.isRed =$isRed ";
}
if (!empty ($catchStatus)) {
    $condition .= " and i.catchStatus ='$catchStatus' ";
}
if (!empty ($remark)) {
    $condition .= " and i.remark  like '%$remark%'";
}
if ($moneyLimit) {
    $subPrice = "  ii.`subPrice`";
    $price = "  ii.`price`";
    $unHookAmount = "ii.`unHookAmount`";
    $hookAmount = " ii.`hookAmount`";
    $thisHookAmount = " d.thisHookAmount";
} else {
    $subPrice = " 0";
    $price = " 0 as price";
    $unHookAmount = " 0 ";
    $hookAmount = " 0 as hookAmount";
    $thisHookAmount = "0 as thisHookAmount";
}
//echo $condition;


$QuerySQL = <<<QuerySQL
select i.id,DATE_FORMAT(auditDate,'%Y.%m') as datePeriod,i.`auditDate` as docDate,
		case i.`docStatus` when 'WSH' then '未审核' else '已审核' end as docStatus,
		i.`docCode`,i.`supplierName`,
	    case i.`catchStatus` when 'CGFPZT-WGJ' then '未勾稽' when 'CGFPZT-BFGJ' then  '部分勾稽' else '已勾稽' END as catchStatus,
		ii.`inStockName`,ii.k3Code, ii.`productCode`,ii.`productName`,
        ii.`pattern`,ii.`batchNum`,ii.`unitName`,
        case i.`isRed` when '0' then ii.`actNum` else -ii.`actNum` end as actNum,
       $price,ii.proType,
        case i.`isRed` when '0' then$subPrice else -$subPrice end as subPrice,
        case i.`isRed` when '0' then ii.`unHookNumber` else -ii.`unHookNumber` end as unHookNumber,
        case i.`isRed` when '0' then $unHookAmount else -$unHookAmount end as unHookAmount,
        ii.`hookNumber`,$hookAmount,
        d.thisHookNumber ,$thisHookAmount,i.remark,i.purchaserName
		 from oa_stock_instock_item ii inner join oa_stock_instock i ON(ii.mainId=i.id)
		 left join (
			select
				d.hookId,sum(d.hookNumber) as thisHookNumber,sum(d.amount) as thisHookAmount
			from
				oa_finance_related_detail d
			where
				d.hookObj = 'storage' and date_format(d.hookDate,'%Y%m') = 201203
			group by d.hookId
		 ) d on ii.id = d.hookId
         where i.docType='RKPURCHASE'$condition
        ORDER BY  i.`auditDate`,i.`docCode` asc
QuerySQL;
GenAttrXmlData($QuerySQL, false);