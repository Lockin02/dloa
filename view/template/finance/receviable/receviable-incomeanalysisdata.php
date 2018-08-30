<?php
include '../../../../webreport/data/mysql_GenXmlData.php';


$contiditon = "";
//开始日期
$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";

//结束日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;

//客户ID
$customerId =  isset($_GET['customerId']) ? $_GET['customerId'] : "";
if($customerId){
	$contiditon .= " and c.customerId = $customerId ";
}

//合同
$orderCode =  isset($_GET['orderCode']) ? $_GET['orderCode'] : "";
if(!empty($orderCode)){
	$contiditon .= " and c.objCode like concat('%','$orderCode','%') ";
}

//区域
$areaName =  isset($_GET['areaName']) ? $_GET['areaName'] : "";
if(!empty($areaName)){
	$contiditon .= " and (c.areaName = '$areaName' or o.areaName = '$areaName' )";
}

//省份
$customerProvince =  isset($_GET['customerProvince']) ? $_GET['customerProvince'] : "";
if(!empty($customerProvince)){
	$contiditon .= " and c.province = '$customerProvince' ";
}

//客户类型
$customerType =  isset($_GET['customerType']) ? $_GET['customerType'] : "";
if(!empty($customerType)){
	$contiditon .= " and c.incomeUnitType = '$customerType' ";
}

//合同负责人
$prinvipalId =  isset($_GET['prinvipalId']) ? $_GET['prinvipalId'] : "";
if(!empty($prinvipalId)){
	$contiditon .= " and o.prinvipalId = '$prinvipalId'";
}

//区域负责人
$areaPrincipalId =  isset($_GET['areaPrincipalId']) ? $_GET['areaPrincipalId'] : "";
if(!empty($areaPrincipalId)){
	$contiditon .= " and (o.areaPrincipalId = '$areaPrincipalId' or c.managerId='$areaPrincipalId' )";
}

$QuerySQL = <<<QuerySQL

select
	c.customerId,c.customerName,c.incomeDate,c.thisYear,c.thisMonth,
	c.objId,c.objType,if(c.objId is null,CONVERT('No'  USING GBK),CONVERT('Yes'  USING GBK)) as status,
	c.incomeMoney,
	if(o.invoiceMoney is null ,0,o.invoiceMoney) as invoiceMoney,
	if(o.incomeMoney is null ,0,o.incomeMoney) as sumIncomeMoney,
	c.incomeDetail,
	null as thisOrderType,
	c.province as prov,
	d.dataName as customerType,
	c.objCode AS thisOrderCode,
	if(c.objId is null ,0,if(o.contractMoney > 0,o.contractMoney,o.contractTempMoney)) AS thisOrderMoney,
	o.prinvipalId,o.prinvipalName,
	if(c.objId is null,c.areaName,o.areaName) as areaName,
	if(c.objId is null,c.managerName,o.areaPrincipal) as areaPrincipal,
	if(c.objId is null,c.managerId,o.areaPrincipalId) as areaPrincipalId,
	o.createTime
from
	(
		select
			i.incomeDate,year(i.incomeDate) as thisYear,month(i.incomeDate) as thisMonth,i.customerId,i.customerName,
			i.objId,i.objType,i.objCode,i.province,i.incomeUnitType,i.managerId,i.managerName,i.areaName,i.areaId,
			sum(i.incomeMoney) as incomeMoney,
			i.incomeDetail
		from
		(
		select
			i.incomeDate,i.customerId,i.customerName,
			i.objId,i.objType,i.objCode,i.province,i.incomeUnitType,i.managerId,i.managerName,i.areaName,i.areaId,
			sum(i.incomeMoney) as incomeMoney,
			group_concat(concat(cast(i.incomeDate as char charset gbk),' , ',cast(i.incomeMoney as char charset gbk)) separator ' ; ') as incomeDetail,
			0 as invoiceMoney
		from
			(
			select
				i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,i.province,
				a.objId,a.objType,a.objCode,i.incomeUnitType,i.managerId,i.managerName,i.areaName,i.areaId,
				if(a.objId is null,i.incomeMoney ,if(i.formType <> 'YFLX-TKD' ,a.money,-a.money) ) as incomeMoney
			from
				oa_finance_income i
				left join
				oa_finance_income_allot a on i.id = a.incomeId
			where date_format(i.incomeDate,'%Y%m') between date_format("$beginDate",'%Y%m') and date_format("$endDate",'%Y%m')
			union all
			select
				i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,i.province,
				null as objId,null as objType,null as objCode,i.incomeUnitType,i.managerId,i.managerName,i.areaName,i.areaId,
				if(i.formType <> 'YFLX-TKD' ,i.allotAble,-i.allotAble) as incomeMoney
			from
				oa_finance_income i
			where i.status = 'DKZT-BFFP' and date_format(i.incomeDate,'%Y%m') between date_format("$beginDate",'%Y%m') and date_format("$endDate",'%Y%m')
			) i
		group by date_format(i.incomeDate,'%Y%m'),i.objType,i.objId,i.customerName

		) i
		group by date_format(i.incomeDate,'%Y%m'),i.objType,i.objId,i.customerName
	) c
	left join
	oa_contract_contract o on o.id = c.objId and c.objType = 'KPRK-12'
	left join
	(select dataCode,dataName from oa_system_datadict where parentCode = 'KHLX') d
		on c.incomeUnitType = d.dataCode
	where 1=1 $contiditon
QuerySQL;

//call INCOMEANALYSIS_REPORT1("$customerId","$beginDate","$endDate","$orderId","$areaName","$customerProvince","$customerType","$prinvipalId","$areaPrincipalId");
GenAttrXmlData($QuerySQL, false);
?>
