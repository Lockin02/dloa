<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = "";

//年
$thisYear = $_GET['thisYear'];
//季度
$thisQuarter = $_GET['thisQuarter'];

//上一年
$lastYear = $_GET['thisYear'] - 1;

//上个季度
if($thisQuarter == 1){
	$lastQuarter = 4;
}else{
	$lastQuarter = $thisQuarter - 1;
}

//物料信息
if (! empty ( $_GET ['productCode'] )) {
	$condition = $_GET ['productCode'];
}
//echo $condition;
$QuerySQL = <<<QuerySQL
select
	c.sSeasonCost as sSeasonCost,

	c.tSeasonCost as tSeasonCost,
	(c.sSeasonCost - c.tSeasonCost) as tChangeCost,

	c.hSeasonCost as hSeasonCost,
	(c.sSeasonCost - c.hSeasonCost) as hChangeCost,

	c.deptName,
	c.createTime
from
	(
	select
		sum(e.moneyAll) as sSeasonCost,
		0 as tSeasonCost,
		0 as hSeasonCost,
		e.applyDeptName as deptName,
		c.createTime
	from
		oa_purch_apply_basic c
		inner join
		oa_purch_apply_equ e
			on c.id = e.basicId
	where
		year(c.createTime) = $thisYear  and QUARTER(c.createTime) = $thisQuarter  and c.isTemp = 0 and  e.purchType = 'assets' and  (((c.state in (4, 7) and c.ExaStatus = '完成') or (c.state in (5, 8,10)))) and e.amountAll > 0
	group by  e.applyDeptName

	union

	select
		0 as sSeasonCost,
		sum(e.moneyAll) as tSeasonCost,
		0 as hSeasonCost,
		e.applyDeptName as deptName,
		c.createTime
	from
		oa_purch_apply_basic c
		inner join
		oa_purch_apply_equ e
			on c.id = e.basicId
	where
		year(c.createTime) = $lastYear  and QUARTER(c.createTime) = $thisQuarter  and c.isTemp = 0 and  e.purchType = 'assets' and  (((c.state in (4, 7) and c.ExaStatus = '完成') or (c.state in (5, 8,10)))) and e.amountAll > 0
	group by  e.applyDeptName

	union

	select
		0 as sSeasonCost,
		0 as tSeasonCost,
		sum(e.moneyAll) as hSeasonCost,
		e.applyDeptName as deptName,
		c.createTime
	from
		oa_purch_apply_basic c
		inner join
		oa_purch_apply_equ e
			on c.id = e.basicId
	where
		year(c.createTime) = $thisYear  and QUARTER(c.createTime) = $lastQuarter  and c.isTemp = 0 and  e.purchType = 'assets' and  (((c.state in (4, 7) and c.ExaStatus = '完成') or (c.state in (5, 8,10)))) and e.amountAll > 0
	group by  e.applyDeptName
) c
group by deptName
order by deptName
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
