<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$contiditon = "";
//开始日期
$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";
if($beginDate){
	$contiditon .= " and c.invoiceTime >= '$beginDate' ";
}

//结束日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
if($endDate){
	$contiditon .= " and c.invoiceTime <= '$endDate' ";
}

//客户ID
$customerId =  isset($_GET['customerId']) ? $_GET['customerId'] : "";
if($customerId){
	$contiditon .= " and c.InvoiceUnitId = $customerId ";
}

//合同
$orderCode =  isset($_GET['orderCode']) ? $_GET['orderCode'] : "";
if(!empty($orderCode)){
	$contiditon .= " and c.objCode like concat('%','$orderCode','%') ";
}

//区域
$areaName =  isset($_GET['areaName']) ? $_GET['areaName'] : "";
if(!empty($areaName)){
	$contiditon .= " and (c.areaName = '$areaName' or con.areaName = '$areaName' )";
}

//省份
$customerProvince =  isset($_GET['customerProvince']) ? $_GET['customerProvince'] : "";
if(!empty($customerProvince)){
	$contiditon .= " and c.invoiceUnitProvince = '$customerProvince' ";
}

//客户类型
$customerType =  isset($_GET['customerType']) ? $_GET['customerType'] : "";
if(!empty($customerType)){
	$contiditon .= " and c.invoiceUnitType = '$customerType' ";
}

//合同负责人
$prinvipalId =  isset($_GET['prinvipalId']) ? $_GET['prinvipalId'] : "";
if(!empty($prinvipalId)){
	$contiditon .= " and (con.prinvipalId = '$prinvipalId' or c.salesmanId = '$prinvipalId')";
}

//区域负责人
$areaPrincipalId =  isset($_GET['areaPrincipalId']) ? $_GET['areaPrincipalId'] : "";
if(!empty($areaPrincipalId)){
	$contiditon .= " and (con.areaPrincipalId = '$areaPrincipalId' or c.managerId='$areaPrincipalId' )";
}

$QuerySQL = <<<QuerySQL
select
	date_format(c.invoiceTime,'%Y') as thisYear,date_format(c.invoiceTime,'%m') as thisMonth,
	c.invoiceUnitProvince as prov,c.invoiceUnitType,c.objCode as thisOrderCode,c.InvoiceUnitName as customerName,
	c.invoiceMoney,c.psType,c.remark,c.invoiceContent as detail,
	if(con.contractMoney > 0,con.contractMoney,con.contractTempMoney) as thisOrderMoney,
	con.invoiceMoney as sumInvoiceMoney,
	if(con.contractMoney > 0,con.contractMoney,con.contractTempMoney) - con.invoiceMoney as unInvoiceMoney,
	if(con.areaPrincipal is null,c.managerName,con.areaPrincipal) as areaPrincipal,
	if(con.areaName is null,c.areaName,con.areaName) as areaName,
	if(con.prinvipalName is null,c.salesman,con.prinvipalName) as prinvipalName,
	d.dataName as customerType
from
	oa_finance_invoice c
	left join
	(select
		id,contractMoney,contractTempMoney,invoiceMoney,areaPrincipal,areaPrincipalId,
		prinvipalId,prinvipalName,areaName
	from
		oa_contract_contract
	where
		isTemp = 0
	) con
		on c.objId = con.id
	left join
	(select dataCode,dataName from oa_system_datadict where parentCode = 'KHLX') d
		on c.invoiceUnitType = d.dataCode
where 1=1 $contiditon
order by date_format(c.invoiceTime,'%Y%m'),c.invoiceUnitName,c.objId,c.objType
QuerySQL;

//call INCOMEDETAIL_REPORT("$customerId","$beginDate","$endDate","$orderId","$areaName","$customerProvince","$customerType","$prinvipalId","$areaPrincipalId");
GenAttrXmlData($QuerySQL, false);
?>
