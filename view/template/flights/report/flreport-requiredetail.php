<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year=$_GET['thisYear'];  //年
$costBelongDeptId = $_GET['costBelongDeptId'];
$beginMonth = $_GET['beginMonth'];
$endMonth = $_GET['endMonth'];
$condition = "";
if(strpos($costBelongDeptId,';;') === false){//如果不含有全部权限，则加载过滤条件
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
	when 12 then '联程'
	when 10 then '单程'
	when 11 then '往返'
else '' end as ticketType,
case c.ticketMsg
	when 1 then '已生成'
	when 0 then '未生成'
else '' end as ticketMsg,
case c.detailType
	when 1 then '部门费用'
	when 2 then '工程项目费用'
	when 3 then '研发项目费用'
	when 4 then '售前费用'
	when 5 then '售后费用'
else '' end as detailType,c.projectName,c.proManagerName,c.projectCode,c.chanceCode ,c.costBelongDeptName,c.costBelongCom,c.costBelonger,c.province,c.contractCode,
c.ExaDT,c.updateTime
from oa_flights_require c where 1=1 and MONTH(c.startDate) >= $beginMonth and MONTH(c.startDate) <= $endMonth $condition ;
QuerySQL;
GenAttrXmlData($sql,false);