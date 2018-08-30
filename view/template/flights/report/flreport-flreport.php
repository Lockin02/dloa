<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year=$_GET['thisYear'];  //��
$costBelongDeptId = $_GET['costBelongDeptId'];
$condition = "";
if(strpos($costBelongDeptId,';;') === false){//���������ȫ��Ȩ�ޣ�����ع�������
	$condition = "and c.costBelongDeptId in($costBelongDeptId)";
}
$sql = <<<QuerySQL
select
	case c.detailType
		when 1 then '���ŷ���'
		when 2 then '��ͬ��Ŀ����'
		when 3 then '�з�����'
		when 4 then '��ǰ����'
		when 5 then '�ۺ����'
	else '' end as detailType,
	c.detailType as orgDetail,
	sum(c.costPay) as allPay,
	sum(if(month(b.balanceDateB) = 1,c.costPay,0)) as JanTotal,
	sum(if(month(b.balanceDateB) = 2,c.costPay,0)) as FebTotal,
	sum(if(month(b.balanceDateB) = 3,c.costPay,0)) as MarTotal,
	sum(if(month(b.balanceDateB) = 4,c.costPay,0)) as AplTotal,
	sum(if(month(b.balanceDateB) = 5,c.costPay,0)) as MayTotal,
	sum(if(month(b.balanceDateB) = 6,c.costPay,0)) as JunTotal,
	sum(if(month(b.balanceDateB) = 7,c.costPay,0)) as JulTotal,
	sum(if(month(b.balanceDateB) = 8,c.costPay,0)) as AugTotal,
	sum(if(month(b.balanceDateB) = 9,c.costPay,0)) as SepTotal,
	sum(if(month(b.balanceDateB) = 10,c.costPay,0)) as OctTotal,
	sum(if(month(b.balanceDateB) = 11,c.costPay,0)) as NovTotal,
	sum(if(month(b.balanceDateB) = 12,c.costPay,0)) as DecTotal,
	sum(c.costPay) as total
from
	oa_flights_balance b
		inner join
	oa_flights_balance_item i on b.id = i.mainId
		left join
	oa_flights_message c on i.msgId = c.id
where year(b.balanceDateB) = $year $condition
group by c.detailType order by c.detailType
QuerySQL;
GenAttrXmlData($sql,false);