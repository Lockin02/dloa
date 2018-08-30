<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
include '../../../../util/jsonUtil.php';

$conditionSql= isset($_GET['conditionSql']) ? util_jsonUtil::iconvUTF2GB($_GET['conditionSql']) : ' and g.thisYear = '.date('Y') ;

if(strpos($conditionSql,'thisYear') === false){
	$conditionSql .= " and g.thisYear = " . date('Y');
}

//锚定日期
$January = "01";
$February = "02";
$March = "03";
$April = "04";
$May = "05";
$June = "06";
$July = "07";
$August = "08";
$September = "09";
$October = "10";
$November = "11";
$December = "12";

//
////全部条件
//$allCondition = "";
//
//$orderCondition = "";
//$stockCondition = "";
////echo $_GET ['beginDate'];
//if (isset ( $_GET ['beginDate'] )) {
//	//开始日期
//	$beginDate = $_GET ['beginDate'];
//	//结束日期
////	$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET ['endMonth'], $_GET ['endYear'] );
//	$endDate = $_GET ['endDate'] ;
//
//	$orderCondition .= " and o.`auditDate` BETWEEN '$beginDate' and '$endDate'";
//	$stockCondition .= " and a.`auditDate` BETWEEN '$beginDate' and '$endDate'";
//
//}
//
//if(!empty($_GET['productId'])){//物料
//	$orderCondition .= " and oi.`productId`=".$_GET['productId'];
//	$stockCondition .= " and ai.`productId`=".$_GET['productId'];
//}
//
//if(!empty($_GET['customerId'])){//客户
//	$orderCondition .= " and o.`customerId`=".$_GET['customerId'];
//	$stockCondition .= " and a.`customerId`=".$_GET['customerId'];
//}
//
//if(!empty($_GET['contractId'])){//合同
//	$orderCondition .= " and vo.`id`='".$_GET['contractId']."'";
//	$stockCondition .= " and a.`contractId`='-1'";
//}

//echo $stockCondition;

//echo $orderCondition;
$QuerySQL = <<<QuerySQL

select
	g.productCode,g.productName,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$January",g.actOutNum,0)) as month1Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$February",g.actOutNum,0)) as month2Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$March",g.actOutNum,0)) as month3Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$April",g.actOutNum,0)) as month4Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$May",g.actOutNum,0)) as month5Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$June",g.actOutNum,0)) as month6Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$July",g.actOutNum,0)) as month7Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$August",g.actOutNum,0)) as month8Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$September",g.actOutNum,0)) as month9Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$October",g.actOutNum,0)) as month10Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$November",g.actOutNum,0)) as month11Num,
	sum(if(DATE_FORMAT(if(g.backDate = '',g.auditDate,g.backDate),'%m') = "$December",g.actOutNum,0)) as month12Num,
	sum(actOutNum) as actOutNum
from (
	select
		DATE_FORMAT(o.auditDate,'%Y.%m') as datePeriod,
		o.auditDate,
		year(o.auditDate) as thisYear,
		'' as backDate,
		oi.productCode,
		oi.productName,
		if(o.isRed = 0,oi.actOutNum,-oi.actOutNum) as actOutNum
	from
		oa_stock_outstock o
			inner join
		oa_stock_outstock_item oi
			on(oi.mainId=o.id)
	where o.docType ='CKSALES'

	union all

	select
		DATE_FORMAT(a.auditDate,'%Y.%m') as datePeriod,
		if(a.toUse = 'CHUKUJY',a.auditDate,(select al.auditDate from oa_stock_allocation al where al.id=a.relDocId and a.relDocType='DBDYDLXDB')) asauditDate,
		year(a.auditDate) as thisYear,
		if(a.toUse = 'CHUKUGUIH',a.auditDate,'') as backDate,
		ai.productCode,
		ai.productName,
		if(a.toUse = 'CHUKUJY',ai.allocatNum,-ai.allocatNum) as allocatNum
	from
		oa_stock_allocation a
			inner join
		oa_stock_allocation_item ai
			on(a.id=ai.`mainId`)
	WHERE a.toUse IN('CHUKUJY','CHUKUGUIH')
	) g
where 1=1 $conditionSql group by g.productCode

QuerySQL;
//echo $QuerySQL;
GenAttrXmlData ( $QuerySQL, false );



//select g.productCode,g.productName,g.actType,sum(actOutNum) as actOutNum from (
//select DATE_FORMAT(o.auditDate,'%Y.%m') as datePeriod,o.auditDate,'' as backDate,
//      `oi`.productCode,
//	  `oi`.productName,
//       case o.`isRed` WHEN 0 then oi.actOutNum else -`oi`.`actOutNum` END as actOutNum ,
//       o.`contractCode`,
//       o.`contractName`,
//       o.`customerName`,
//       vo.`prinvipalName` as chargeUserName,
//        case o.`isRed` WHEN 0 then '合同出库' else '合同退库' END as actType
//			from `oa_stock_outstock` o
//        		inner join oa_stock_outstock_item oi on(oi.mainId=o.id)
//                left join `view_oa_order` `vo` ON (o.`contractId`=vo.`orgId` and o.`contractType`=vo.`tablename`)
//                where o.`docType`='CKSALES'
//union all
//select  DATE_FORMAT(a.auditDate,'%Y.%m') as datePeriod,
//		case a.toUse when 'CHUKUJY' THEN a.`auditDate`
//        else (select
//        al.auditDate from `oa_stock_allocation` `al` where `al`.id=a.relDocId and a.relDocType='DBDYDLXDB')
//        end as auditDate,
//        case a.toUse when 'CHUKUGUIH' THEN a.`auditDate` else ''  END as backDate,
//		ai.`productCode`,
//        ai.`productName`,
//		CASE a.`toUse` WHEN 'CHUKUJY'  THEN ai.`allocatNum`
//                       when 'CHUKUGUIH' THEN -ai.`allocatNum`  END as allocatNum,
//		a.contractCode,
//        '' as contractName,
//        a.`customerName`,
//        a.pickName,
//        CASE a.`toUse` WHEN 'CHUKUJY'  THEN  '借用'
//                       when 'CHUKUGUIH' THEN '归还'  END as actType
//		from oa_stock_allocation a
//          inner join `oa_stock_allocation_item` ai
//			on(a.id=ai.`mainId`)
//                WHERE a.`toUse` IN('CHUKUJY','CHUKUGUIH')
//) g where 1=1 $conditionSql group by g.productCode
?>
