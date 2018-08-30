<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year=$_GET['thisYear'];  //��
$costBelongDeptId = $_GET['costBelongDeptId'];
$condition = "";
if(strpos($costBelongDeptId,';;') === false){//���������ȫ��Ȩ�ޣ�����ع�������
	$condition .= " and c.costBelongDeptId in($costBelongDeptId)";
}
$detailType = $_GET['orgDetail'];
if(!empty($detailType)){//�����������ɸѡ
	$condition .= " and c.detailType = '$detailType'";
}
$projectCode = $_GET['projectCode'];
if(!empty($projectCode)){//��Ŀ���
	$condition .= " and c.projectCode = '$projectCode'";
}
$contractCode = $_GET['contractCode'];
if(!empty($contractCode)){//��ͬ���
	$condition .= " and c.contractCode = '$contractCode'";
}
$chanceCode = $_GET['chanceCode'];
if(!empty($chanceCode)){//�̻����
	$condition .= " and c.chanceCode = '$chanceCode'";
}
$costBelonger = $_GET['costBelonger'];
if(!empty($costBelonger)){//���ù�����
	$condition .= " and c.costBelonger = '$costBelonger'";
}
$province = $_GET['province'];
if(!empty($province)){//ʡ��
	$condition .= " and c.province = '$province'";
}
$beginMonth = $_GET['beginMonth'];
$endMonth = $_GET['endMonth'];
$sql = <<<QuerySQL
select c.id ,c.requireNo ,c.requireId ,c.airName ,c.airId ,c.startDate ,c.arriveDate ,c.flightNumber ,c.airline ,
	c.airlineId ,c.privatePay ,c.fullFare ,c.constructionCost ,c.fuelCcharge ,c.serviceCharge ,c.changeFees ,
	if (c.isLow = 1, '��', '��') AS isLow ,
	c.lowremark ,
	c.actualCost ,
	case c.businessState
		when 0 then '����'
		when 1 then '�Ѹ�ǩ'
		when 2 then '����Ʊ'
		when 3 then '��ǩ'
		when 4 then '��Ʊ'
		when 5 then '����'
	else '' end as businessState,
	c.auditState ,c.auditorId ,c.auditorName ,c.auditTime ,c.createId ,c.createName ,c.createTime ,
	c.updateId ,c.updateName ,c.updateTime ,c.organization ,c.organizationId ,c.changCount,c.costBelongDeptId,c.feeChange,
	c.feeBack,c.costBack,c.costPay,c.costDiff,c.beforeCost,c.msgType,c.ticketType,c.startPlace,c.middlePlace,c.twoDate,c.endPlace,c.comeDate,
	c.id as msgId,c.costPay as costPayShow,c.flightTime,c.arrivalTime,c.departPlace,c.arrivalPlace,c.auditDate,
	case c.detailType
	    when 1 then '���ŷ���'
		when 2 then '��ͬ��Ŀ����'
		when 3 then '�з�����'
		when 4 then '��ǰ���� '
		when 5 then '�ۺ����'
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