<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
//$areaName = $_GET['areaName'];
//if($areaName == ';;'){
//	$areaNameSql = " ";
//}else if($areaName == ''){
//	$areaNameSql = " and province = 'none'";
//}else{
//    $areaNameSql = " and province in ($areaName)";
//}
$whereSql =  " where 1=1 and year = '$year'";
$sql = "and year = '$year'";

$countStr = "
	count(if(c.winRate='100' && c.chanceStage='SJJD02',true,null)) as w1002,
	count(if(c.winRate='100' && c.chanceStage='SJJD03',true,null)) as w1003,
	count(if(c.winRate='100' && c.chanceStage='SJJD04',true,null)) as w1004,
	count(if(c.winRate='100' && c.chanceStage='SJJD05',true,null)) as w1005,

	count(if(c.winRate='80' && c.chanceStage='SJJD02',true,null)) as w802,
	count(if(c.winRate='80' && c.chanceStage='SJJD03',true,null)) as w803,
	count(if(c.winRate='80' && c.chanceStage='SJJD04',true,null)) as w804,
	count(if(c.winRate='80' && c.chanceStage='SJJD05',true,null)) as w805,

	count(if(c.winRate='50' && c.chanceStage='SJJD02',true,null)) as w502,
	count(if(c.winRate='50' && c.chanceStage='SJJD03',true,null)) as w503,
	count(if(c.winRate='50' && c.chanceStage='SJJD04',true,null)) as w504,
	count(if(c.winRate='50' && c.chanceStage='SJJD05',true,null)) as w505,

	count(if(c.winRate='25' && c.chanceStage='SJJD02',true,null)) as w252,
	count(if(c.winRate='25' && c.chanceStage='SJJD03',true,null)) as w253,
	count(if(c.winRate='25' && c.chanceStage='SJJD04',true,null)) as w254,
	count(if(c.winRate='25' && c.chanceStage='SJJD05',true,null)) as w255
";

$sumStr = "
	sum(if(c.winRate='100' && c.chanceStage='SJJD02',h.money,0)) as w1002,
	sum(if(c.winRate='100' && c.chanceStage='SJJD03',h.money,0)) as w1003,
	sum(if(c.winRate='100' && c.chanceStage='SJJD04',h.money,0)) as w1004,
	sum(if(c.winRate='100' && c.chanceStage='SJJD05',h.money,0)) as w1005,

	sum(if(c.winRate='80' && c.chanceStage='SJJD02',h.money,0)) as w802,
	sum(if(c.winRate='80' && c.chanceStage='SJJD03',h.money,0)) as w803,
	sum(if(c.winRate='80' && c.chanceStage='SJJD04',h.money,0)) as w804,
	sum(if(c.winRate='80' && c.chanceStage='SJJD05',h.money,0)) as w805,

	sum(if(c.winRate='50' && c.chanceStage='SJJD02',h.money,0)) as w502,
	sum(if(c.winRate='50' && c.chanceStage='SJJD03',h.money,0)) as w503,
	sum(if(c.winRate='50' && c.chanceStage='SJJD04',h.money,0)) as w504,
	sum(if(c.winRate='50' && c.chanceStage='SJJD05',h.money,0)) as w505,

	sum(if(c.winRate='25' && c.chanceStage='SJJD02',h.money,0)) as w252,
	sum(if(c.winRate='25' && c.chanceStage='SJJD03',h.money,0)) as w253,
	sum(if(c.winRate='25' && c.chanceStage='SJJD04',h.money,0)) as w254,
	sum(if(c.winRate='25' && c.chanceStage='SJJD05',h.money,0)) as w255
";

$QuerySQL = <<<QuerySQL


select * from
(
select date_format(c.predictContractDate,'%Y') as year,h.hardwareName,h.chanceId,c.winRate,c.chanceStage,"1" as num,
(case
   when predictContractDate < date_add(NOW(), interval 1 MONTH) then '未来一个月'
   when date_add(CURDATE(), interval 1 MONTH) < predictContractDate and predictContractDate < date_add(CURDATE(), interval 2 MONTH) then '未来二个月'
   when date_add(CURDATE(), interval 2 MONTH) < predictContractDate and predictContractDate < date_add(CURDATE(), interval 3 MONTH) then '未来三个月'
   else '未知' end) as preDT,
'金额' as type,

$sumStr

from oa_sale_chance_hardware h

  left join oa_sale_chance c on h.chanceId = c.id
where c.status in (0,5) and  c.predictContractDate != '0000-00-00'
GROUP BY hardwareName,preDT

union all

select date_format(c.predictContractDate,'%Y') as year,h.hardwareName,h.chanceId,c.winRate,c.chanceStage,"2" as num,
(case
   when predictContractDate < date_add(NOW(), interval 1 MONTH) then '未来一个月'
   when date_add(CURDATE(), interval 1 MONTH) < predictContractDate and predictContractDate < date_add(CURDATE(), interval 2 MONTH) then '未来二个月'
   when date_add(CURDATE(), interval 2 MONTH) < predictContractDate and predictContractDate < date_add(CURDATE(), interval 3 MONTH) then '未来三个月'
   else '未知' end) as preDT,
'数量' as type,

$countStr

from oa_sale_chance_hardware h

  left join oa_sale_chance c on h.chanceId = c.id
where c.status in (0,5) and  c.predictContractDate != '0000-00-00'
GROUP BY hardwareName,preDT
)c
$whereSql
group by hardwareName,preDT,type
order by hardwareName,preDT desc,type

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
