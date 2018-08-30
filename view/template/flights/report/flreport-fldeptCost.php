<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year=$_GET['thisYear'];  //年
$costBelongDeptId = $_GET['costBelongDeptId'];
$condition = "";
if(strpos($costBelongDeptId,';;') === false){//如果不含有全部权限，则加载过滤条件
	$condition .= "and c.costBelongDeptId in($costBelongDeptId)";
}
$sql = <<<QuerySQL
select
	c.costBelongDeptName,c.costBelongDeptId,
	sum(c.costPay) as total,
	sum(if(month(b.balanceDateB) = 1,c.costPay,0)) as Jan,
	sum(if(month(b.balanceDateB) = 2,c.costPay,0)) as Feb,
	sum(if(month(b.balanceDateB) = 3,c.costPay,0)) as Mar,
	sum(if(month(b.balanceDateB) = 4,c.costPay,0)) as Apl,
	sum(if(month(b.balanceDateB) = 5,c.costPay,0)) as May,
	sum(if(month(b.balanceDateB) = 6,c.costPay,0)) as Jun,
	sum(if(month(b.balanceDateB) = 7,c.costPay,0)) as Jul,
	sum(if(month(b.balanceDateB) = 8,c.costPay,0)) as Aug,
	sum(if(month(b.balanceDateB) = 9,c.costPay,0)) as Sep,
	sum(if(month(b.balanceDateB) = 10,c.costPay,0)) as Oct,
	sum(if(month(b.balanceDateB) = 11,c.costPay,0)) as Nov,
	sum(if(month(b.balanceDateB) = 12,c.costPay,0)) as Dece
from
	oa_flights_balance b
		inner join
	oa_flights_balance_item i on b.id = i.mainId
		left join
	oa_flights_message c on i.msgId = c.id
where
	year(b.balanceDateB) = $year and c.detailType = 1 $condition
GROUP BY c.costBelongDeptId;
QuerySQL;
GenAttrXmlData($sql,false);