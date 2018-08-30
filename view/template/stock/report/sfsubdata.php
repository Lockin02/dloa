<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php

$condition="";
if( $_GET['productCode'] !='' ){
	$condition.="and productCode='".trim($_GET['productCode'])."'";
}

if( $_GET['productName'] !='' ){
	$condition.="and productName  like BINARY CONCAT('%','".trim($_GET['productName'])."','%')";
}

$beginYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['beginMonth'], $_GET['beginYear'] ); //这个月有多少天
$monthBeginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";//月开始日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] ); //这个月有多少天
$monthEndDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;//月结束日期

$condition1=" where i.docStatus='YSH' and i.auditDate between '$monthBeginDate' and '$monthEndDate'";
$condition2=" where o.docStatus='YSH' and o.auditDate between '$monthBeginDate' and '$monthEndDate'";
$condition3=" where b.thisDate between '$monthBeginDate' and '$monthEndDate'";
$condition4=" where a.docStatus='YSH' and a.auditDate between '$monthBeginDate' and '$monthEndDate'";

$QuerySQL = <<<QuerySQL
select docDate, 
     productCode,productName,unitName,pattern,'小计' as batchNo,
     sum(sProNum) as sProNum,
     round(sum(sSubCost) / sProNum,2) as sPrice,
     round(sum(sSubCost),2) as sSubCost,
     sum(iProNum) as iProNum,
     round(sum(iSubCost) / iProNum,2) as iPrice,
     round(sum(iSubCost),2) as iSubCost,
     sum(oProNum) as oProNum,
     round(sum(oSubCost) / oProNum,2) as oPrice,
     round(sum(oSubCost),2) as oSubCost,
     sum(sProNum) + sum(iProNum) - sum(oProNum) as eProNum,
     round((sum(sSubCost) + sum(iSubCost) - sum(oSubCost)) /(sum(sProNum) + sum(
     iProNum) - sum(oProNum)),2) as ePrice,
     round((sum(sSubCost) + sum(iSubCost) - sum(oSubCost)),2) as eSubCost
 from (
select DATE_FORMAT(auditDate,'%Y.%m') as docDate,
	   auditDate,
       productCode,
       productName,
       unitName,
       pattern,
       sum(sProNum) as sProNum,
       round(sum(sSubCost) / sProNum,2) as sPrice,
       round(sum(sSubCost),2) as sSubCost,
       sum(iProNum) as iProNum,
       round(sum(iSubCost) / iProNum,2) as iPrice,
       round(sum(iSubCost),2) as iSubCost,
       sum(oProNum) as oProNum,
       round(sum(oSubCost) / oProNum,2) as oPrice,
       round(sum(oSubCost),2) as oSubCost,
       sum(sProNum) + sum(iProNum) - sum(oProNum) as eProNum,
       round((sum(sSubCost) + sum(iSubCost) - sum(oSubCost)) /(sum(sProNum) + sum(
       iProNum) - sum(oProNum)),2) as ePrice,
       round((sum(sSubCost) + sum(iSubCost) - sum(oSubCost)),2) as eSubCost
from (
       select i.`auditDate`,
              `ii`.productCode,
              `ii`.`productName`,
              ii.`unitName`,
              `ii`.`pattern`,
              ''              as sProNum,
              ''              as sPrice,
              ''              as sSubCost,
              CASE i.`isRed`
                WHEN 0 then ii.`actNum`
                else - ii.`actNum`
              END as iProNum,
              ii.`price` as iPrice,
              ii.`subPrice` as iSubCost,
              ''              as oProNum,
              ''              as oPrice,
              ''              as oSubCost,
              ''              as eProNum,
              ''              as ePrice,
              ''              as eSubCost
       from oa_stock_instock_item `ii`
            INNER join `oa_stock_instock` i ON (i.id = `ii`.`mainId`) $condition1
       UNION all
       select o.`auditDate`,
              oi.`productCode`,
              oi.`productName`,
              oi.`unitName`,
              oi.`pattern`,
              ''              as sProNum,
              ''              as sPrice,
              ''              as sSubCost,
              ''              as iProNum,
              ''              as iPrice,
              ''              as iSubCost,
              case o.`isRed`
                WHEN 0 then oi.`actOutNum`
                else - oi.`actOutNum`
              END as oProNum,
              oi.`cost` as oPrice,
              oi.`subCost` as oSubCost,
              ''              as eProNum,
              ''              as ePrice,
              ''              as eSubCost
       from oa_stock_outstock_item `oi`
            INNER join `oa_stock_outstock` o on (o.`id` = oi.`mainId`) $condition2
       UNION all
       select b.`thisDate`,
              b.`productNo`,
              b.`productName`,
              b.`units`,
              b.`productModel`,
              sum(b.clearingNum) as sProNum,
              b.price as sPrice,
              sum(b.`balanceAmount`) as sSubCost,
              ''              as iProNum,
              ''              as iPrice,
              ''              as iSubCost,
              ''              as oProNum,
              ''              as oPrice,
              ''              as oSubCost,
              ''              as eProNum,
              ''              as ePrice,
              ''              as eSubCost
       from oa_finance_stockbalance b $condition3  group by b.productId 
       UNION all
       select a.auditDate,
              ai.productCode,
              ai.productName,
              ai.unitName,
              ai.pattern,
              ''              as sProNum,
              ''              as sPrice,
              ''              as sSubCost,
              ai.allocatNum    as iProNum,
              ai.cost          as iPrice,
              ai.subCost       as iSubCost,
              ai.allocatNum as oProNum,
              ai.cost as oPrice,
              ai.subCost as oSubCost,
              ''              as eProNum,
              ''              as ePrice,
              ''              as eSubCost
       from oa_stock_allocation_item ai
            INNER join oa_stock_allocation a on (a.id = ai.mainId)
         $condition4
     ) detail
group by auditDate,productCode 
)dayDetail where  1=1 $condition  group by productCode,docDate order by docDate,productCode
QuerySQL;
file_put_contents("d:sql.log", $QuerySQL);
GenAttrXmlData ( $QuerySQL, false);