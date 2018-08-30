<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
include '../../../../util/jsonUtil.php';
//全部条件
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

$conditionSql= isset($_GET['conditionSql']) ? util_jsonUtil::iconvUTF2GB($_GET['conditionSql']) : ' and g.thisYear = '.date('Y') ;

if(strpos($conditionSql,'thisYear') === false){
	$conditionSql .= " and g.thisYear = " . date('Y');
}
//echo $conditionSql;
//die();

$QuerySQL = <<<QuerySQL
select g.datePeriod,g.auditDate,g.backDate,g.productCode,g.productName,g.actOutNum,g.contractCode,g.contractName,
g.chargeUserName,g.actType,g.customerName,g.docCode
from
(
	select
		DATE_FORMAT(o.auditDate,'%Y.%m') as datePeriod,
		year(o.auditDate) as thisYear,
		month(o.auditDate) as thisMonth,
		o.auditDate,
		'' as backDate,
		oi.productCode,
		oi.productName,
		if(o.isRed = 0,oi.actOutNum,-oi.actOutNum) as actOutNum,
		o.contractCode,
		o.contractName,
		o.customerName,
		vo.prinvipalName as chargeUserName,
		if(o.isRed = 0,'合同出库','合同退库') as actType,
		o.docCode
	from
		oa_stock_outstock o
			inner join
		oa_stock_outstock_item oi
			on(oi.mainId=o.id)
			left join
		view_oa_order vo
			ON (o.contractId=vo.orgId and o.contractType=vo.tablename)
	where o.docType='CKSALES'

	union all

	select
		DATE_FORMAT(a.auditDate,'%Y.%m') as datePeriod,
		year(a.auditDate) as thisYear,
		month(a.auditDate) as thisMonth,
		if(a.toUse = 'CHUKUJY',a.auditDate,(select al.auditDate from oa_stock_allocation al where al.id=a.relDocId and a.relDocType='DBDYDLXDB')) as auditDate,
		if(a.toUse = 'CHUKUGUIH',a.auditDate,'') as backDate,
		ai.productCode,
		ai.productName,
		if(a.toUse = 'CHUKUJY',ai.allocatNum,-ai.allocatNum) as allocatNum,
		a.contractCode,
		'' as contractName,
		a.customerName,
		a.pickName,
		if(a.toUse = 'CHUKUJY','借用','归还') as actType,
		a.docCode
	from
		oa_stock_allocation a
			inner join
		oa_stock_allocation_item ai
			on(a.id=ai.mainId)
	WHERE a.toUse IN('CHUKUJY','CHUKUGUIH')
)g where 1=1 $conditionSql
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
