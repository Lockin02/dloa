<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year=$_GET['thisYear'];  //��
$costBelongDeptId = $_GET['costBelongDeptId'];
$beginMonth = $_GET['beginMonth'];
$endMonth = $_GET['endMonth'];
$condition = "";
if(strpos($costBelongDeptId,';;') === false){//���������ȫ��Ȩ�ޣ�����ع�������
	$condition = "and c.costBelongDeptId in($costBelongDeptId)";
}
$sql = <<<QuerySQL
select c.id ,c.requireNo ,c.requireName,c.startPlace ,c.middlePlace,c.endPlace,c.startDate,
case c.twoDate
	when 0000-00-00 then ''
else c.twoDate end as twoDate,
case c.comeDate
	when 0000-00-00 then ''
else c.comeDate end as comeDate,
c.ExaStatus,
case c.ticketType
	when 12 then '����'
	when 10 then '����'
	when 11 then '����'
else '' end as ticketType,
case c.ticketMsg
	when 1 then '������'
	when 0 then 'δ����'
else '' end as ticketMsg,
case c.detailType
	when 1 then '���ŷ���'
	when 2 then '������Ŀ����'
	when 3 then '�з���Ŀ����'
	when 4 then '��ǰ����'
	when 5 then '�ۺ����'
else '' end as detailType,c.projectName,c.proManagerName,c.projectCode,c.chanceCode ,c.costBelongDeptName,c.costBelongCom,c.costBelonger,c.province,c.contractCode,
c.ExaDT,c.updateTime
from oa_flights_require c where 1=1 and MONTH(c.startDate) >= $beginMonth and MONTH(c.startDate) <= $endMonth $condition ;
QuerySQL;
GenAttrXmlData($sql,false);