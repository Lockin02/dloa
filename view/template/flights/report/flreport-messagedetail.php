<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year=$_GET['thisYear'];  //年
$costBelongDeptId = $_GET['costBelongDeptId'];
$condition = "";
if(strpos($costBelongDeptId,';;') === false){//如果不含有全部权限，则加载过滤条件
	$condition .= " and c.costBelongDeptId in($costBelongDeptId)";
}
$detailType = $_GET['orgDetail'];
if(!empty($detailType)){//加入费用类型筛选
	$condition .= " and c.detailType = '$detailType'";
}
$projectCode = $_GET['projectCode'];
if(!empty($projectCode)){//项目编号
	$condition .= " and c.projectCode = '$projectCode'";
}
$contractCode = $_GET['contractCode'];
if(!empty($contractCode)){//合同编号
	$condition .= " and c.contractCode = '$contractCode'";
}
$chanceCode = $_GET['chanceCode'];
if(!empty($chanceCode)){//商机编号
	$condition .= " and c.chanceCode = '$chanceCode'";
}
$costBelonger = $_GET['costBelonger'];
if(!empty($costBelonger)){//费用归属人
	$condition .= " and c.costBelonger = '$costBelonger'";
}
$province = $_GET['province'];
if(!empty($province)){//省份
	$condition .= " and c.province = '$province'";
}
$beginMonth = $_GET['beginMonth'];
$endMonth = $_GET['endMonth'];
$sql = <<<QuerySQL
select c.id ,c.requireNo ,c.requireId ,c.airName ,c.airId ,c.startDate ,c.arriveDate ,c.flightNumber ,c.airline ,
	c.airlineId ,c.privatePay ,c.fullFare ,c.constructionCost ,c.fuelCcharge ,c.serviceCharge ,c.changeFees ,
	if (c.isLow = 1, '是', '否') AS isLow ,
	c.lowremark ,
	c.actualCost ,
	case c.businessState
		when 0 then '正常'
		when 1 then '已改签'
		when 2 then '已退票'
		when 3 then '改签'
		when 4 then '退票'
		when 5 then '正常'
	else '' end as businessState,
	c.auditState ,c.auditorId ,c.auditorName ,c.auditTime ,c.createId ,c.createName ,c.createTime ,
	c.updateId ,c.updateName ,c.updateTime ,c.organization ,c.organizationId ,c.changCount,c.costBelongDeptId,c.feeChange,
	c.feeBack,c.costBack,c.costPay,c.costDiff,c.beforeCost,c.msgType,c.ticketType,c.startPlace,c.middlePlace,c.twoDate,c.endPlace,c.comeDate,
	c.id as msgId,c.costPay as costPayShow,c.flightTime,c.arrivalTime,c.departPlace,c.arrivalPlace,c.auditDate,
	case c.detailType
	    when 1 then '部门费用'
		when 2 then '合同项目费用'
		when 3 then '研发费用'
		when 4 then '售前费用 '
		when 5 then '售后费用'
	else '' end as detailType,
		c.projectName,c.proManagerName,c.projectCode,c.chanceCode ,c.costBelongDeptName,c.costBelongCom,c.costBelonger,c.province,c.contractCode,
		d.outReason,d.requireReason
from
	oa_flights_balance b
		inner join
	oa_flights_balance_item i on b.id = i.mainId
		left join
	oa_flights_message c on i.msgId = c.id
		left join
	oa_flights_require d on d.id = c.requireId
where 1=1 and year(b.balanceDateB) = $year and MONTH(b.balanceDateB) >= $beginMonth and MONTH(b.balanceDateB) <= $endMonth
$condition
order by c.auditDate ;
QuerySQL;
GenAttrXmlData($sql,false);