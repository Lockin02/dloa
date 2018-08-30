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
count(if(c.winRate='100' && g.goodsName like '%Pilot Walktour%',true,null)) as walktour100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Walktour%',true,null)) as walktour80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Walktour%',true,null)) as walktour50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Walktour%',true,null)) as walktour25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Walktour Pack%',true,null)) as Pack100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Walktour Pack%',true,null)) as Pack80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Walktour Pack%',true,null)) as Pack50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Walktour Pack%',true,null)) as Pack25,

count(if(c.winRate='100' && g.goodsName like '%Pilot U-You%',true,null)) as You100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot U-You%',true,null)) as You80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot U-You%',true,null)) as You50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot U-You%',true,null)) as You25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Pioneer%',true,null)) as Pioneer100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Pioneer%',true,null)) as Pioneer80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Pioneer%',true,null)) as Pioneer50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Pioneer%',true,null)) as Pioneer25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Navigator%',true,null)) as Navigator100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Navigator%',true,null)) as Navigator80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Navigator%',true,null)) as Navigator50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Navigator%',true,null)) as Navigato25,

count(if(c.winRate='100' && g.goodsName like '%Pilot RCU%',true,null)) as RCU100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot RCU%',true,null)) as RCU80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot RCU%',true,null)) as RCU50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot RCU%',true,null)) as RCU25,

count(if(c.winRate='100' && g.goodsName like '%Pilot RCU Light%',true,null)) as Light100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot RCU Light%',true,null)) as Light80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot RCU Light%',true,null)) as Light50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot RCU Light%',true,null)) as Light25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Scout%',true,null)) as Scout100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Scout%',true,null)) as Scout80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Scout%',true,null)) as Scout50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Scout%',true,null)) as Scout25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Fleet Analyser%',true,null)) as Analyser100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Fleet Analyser%',true,null)) as Analyser80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Fleet Analyser%',true,null)) as Analyser50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Fleet Analyser%',true,null)) as Analyser25,

count(if(c.winRate='100' && g.goodsName like '%Pilot WaveSurfer%',true,null)) as WaveSurfer100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot WaveSurfer%',true,null)) as WaveSurfer80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot WaveSurfer%',true,null)) as WaveSurfer50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot WaveSurfer%',true,null)) as WaveSurfer25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Customer IQ%',true,null)) as IQ100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Customer IQ%',true,null)) as IQ80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Customer IQ%',true,null)) as IQ50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Customer IQ%',true,null)) as IQ25,

count(if(c.winRate='100' && g.goodsName like '%Pilot AutoTest%',true,null)) as AutoTest100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot AutoTest%',true,null)) as AutoTest80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot AutoTest%',true,null)) as AutoTest50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot AutoTest%',true,null)) as AutoTest25,

count(if(c.winRate='100' && g.goodsName like '%Pilot AirShark%',true,null)) as AirShark100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot AirShark%',true,null)) as AirShark80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot AirShark%',true,null)) as AirShark50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot AirShark%',true,null)) as AirShark25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Netsensor%',true,null)) as Netsensor100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Netsensor%',true,null)) as Netsensor80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Netsensor%',true,null)) as Netsensor50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Netsensor%',true,null)) as Netsensor25,

count(if(c.winRate='100' && g.goodsName like '%Pilot MTS%',true,null)) as MTS100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot MTS%',true,null)) as MTS80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot MTS%',true,null)) as MTS50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot MTS%',true,null)) as MTS25,

count(if(c.winRate='100' && g.goodsName like '%Phone&Screen%',true,null)) as Screen100,
count(if(c.winRate='80'  && g.goodsName like '%Phone&Screen%',true,null)) as Screen80,
count(if(c.winRate='50'  && g.goodsName like '%Phone&Screen%',true,null)) as Screen50,
count(if(c.winRate='25'  && g.goodsName like '%Phone&Screen%',true,null)) as Screen25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Mosight%',true,null)) as Mosight100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Mosight%',true,null)) as Mosight80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Mosight%',true,null)) as Mosight50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Mosight%',true,null)) as Mosight25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Insight%',true,null)) as Insight100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Insight%',true,null)) as Insight80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Insight%',true,null)) as Insight50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Insight%',true,null)) as Insight25,

count(if(c.winRate='100' && g.goodsName like '%ClouDil Insight%',true,null)) as cInsight100,
count(if(c.winRate='80'  && g.goodsName like '%ClouDil Insight%',true,null)) as cInsight80,
count(if(c.winRate='50'  && g.goodsName like '%ClouDil Insight%',true,null)) as cInsight50,
count(if(c.winRate='25'  && g.goodsName like '%ClouDil Insight%',true,null)) as cInsight25,

count(if(c.winRate='100' && g.goodsName like '%Pilot WireRunner%',true,null)) as WireRunner100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot WireRunner%',true,null)) as WireRunner80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot WireRunner%',true,null)) as WireRunner50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot WireRunner%',true,null)) as WireRunner25,

count(if(c.winRate='100' && g.goodsName like '%Pilot Insight-CDMA%',true,null)) as CDMA100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot Insight-CDMA%',true,null)) as CDMA80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot Insight-CDMA%',true,null)) as CDMA50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot Insight-CDMA%',true,null)) as CDMA25,

count(if(c.winRate='100' && g.goodsName like '%Pilot EU%',true,null)) as EU100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot EU%',true,null)) as EU80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot EU%',true,null)) as EU50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot EU%',true,null)) as EU25,

count(if(c.winRate='100' && g.goodsName like '%Pilot CapMax%',true,null)) as CapMax100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot CapMax%',true,null)) as CapMax80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot CapMax%',true,null)) as CapMax50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot CapMax%',true,null)) as CapMax25,

count(if(c.winRate='100' && g.goodsName like '%Pilot NetMonitor%',true,null)) as NetMonitor100,
count(if(c.winRate='80'  && g.goodsName like '%Pilot NetMonitor%',true,null)) as NetMonitor80,
count(if(c.winRate='50'  && g.goodsName like '%Pilot NetMonitor%',true,null)) as NetMonitor50,
count(if(c.winRate='25'  && g.goodsName like '%Pilot NetMonitor%',true,null)) as NetMonitor25
";

$sumStr = "
sum(if(c.winRate='100' && g.goodsName like '%Pilot Walktour%',c.chanceMoney,0)) as walktour100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Walktour%',c.chanceMoney,0)) as walktour80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Walktour%',c.chanceMoney,0)) as walktour50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Walktour%',c.chanceMoney,0)) as walktour25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Walktour Pack%',c.chanceMoney,0)) as Pack100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Walktour Pack%',c.chanceMoney,0)) as Pack80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Walktour Pack%',c.chanceMoney,0)) as Pack50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Walktour Pack%',c.chanceMoney,0)) as Pack25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot U-You%',c.chanceMoney,0)) as You100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot U-You%',c.chanceMoney,0)) as You80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot U-You%',c.chanceMoney,0)) as You50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot U-You%',c.chanceMoney,0)) as You25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Pioneer%',c.chanceMoney,0)) as Pioneer100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Pioneer%',c.chanceMoney,0)) as Pioneer80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Pioneer%',c.chanceMoney,0)) as Pioneer50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Pioneer%',c.chanceMoney,0)) as Pioneer25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Navigator%',c.chanceMoney,0)) as Navigator100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Navigator%',c.chanceMoney,0)) as Navigator80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Navigator%',c.chanceMoney,0)) as Navigator50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Navigator%',c.chanceMoney,0)) as Navigato25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot RCU%',c.chanceMoney,0)) as RCU100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot RCU%',c.chanceMoney,0)) as RCU80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot RCU%',c.chanceMoney,0)) as RCU50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot RCU%',c.chanceMoney,0)) as RCU25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot RCU Light%',c.chanceMoney,0)) as Light100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot RCU Light%',c.chanceMoney,0)) as Light80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot RCU Light%',c.chanceMoney,0)) as Light50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot RCU Light%',c.chanceMoney,0)) as Light25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Scout%',c.chanceMoney,0)) as Scout100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Scout%',c.chanceMoney,0)) as Scout80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Scout%',c.chanceMoney,0)) as Scout50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Scout%',c.chanceMoney,0)) as Scout25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Fleet Analyser%',c.chanceMoney,0)) as Analyser100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Fleet Analyser%',c.chanceMoney,0)) as Analyser80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Fleet Analyser%',c.chanceMoney,0)) as Analyser50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Fleet Analyser%',c.chanceMoney,0)) as Analyser25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot WaveSurfer%',c.chanceMoney,0)) as WaveSurfer100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot WaveSurfer%',c.chanceMoney,0)) as WaveSurfer80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot WaveSurfer%',c.chanceMoney,0)) as WaveSurfer50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot WaveSurfer%',c.chanceMoney,0)) as WaveSurfer25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Customer IQ%',c.chanceMoney,0)) as IQ100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Customer IQ%',c.chanceMoney,0)) as IQ80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Customer IQ%',c.chanceMoney,0)) as IQ50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Customer IQ%',c.chanceMoney,0)) as IQ25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot AutoTest%',c.chanceMoney,0)) as AutoTest100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot AutoTest%',c.chanceMoney,0)) as AutoTest80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot AutoTest%',c.chanceMoney,0)) as AutoTest50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot AutoTest%',c.chanceMoney,0)) as AutoTest25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot AirShark%',c.chanceMoney,0)) as AirShark100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot AirShark%',c.chanceMoney,0)) as AirShark80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot AirShark%',c.chanceMoney,0)) as AirShark50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot AirShark%',c.chanceMoney,0)) as AirShark25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Netsensor%',c.chanceMoney,0)) as Netsensor100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Netsensor%',c.chanceMoney,0)) as Netsensor80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Netsensor%',c.chanceMoney,0)) as Netsensor50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Netsensor%',c.chanceMoney,0)) as Netsensor25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot MTS%',c.chanceMoney,0)) as MTS100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot MTS%',c.chanceMoney,0)) as MTS80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot MTS%',c.chanceMoney,0)) as MTS50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot MTS%',c.chanceMoney,0)) as MTS25,

sum(if(c.winRate='100' && g.goodsName like '%Phone&Screen%',c.chanceMoney,0)) as Screen100,
sum(if(c.winRate='80'  && g.goodsName like '%Phone&Screen%',c.chanceMoney,0)) as Screen80,
sum(if(c.winRate='50'  && g.goodsName like '%Phone&Screen%',c.chanceMoney,0)) as Screen50,
sum(if(c.winRate='25'  && g.goodsName like '%Phone&Screen%',c.chanceMoney,0)) as Screen25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Mosight%',c.chanceMoney,0)) as Mosight100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Mosight%',c.chanceMoney,0)) as Mosight80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Mosight%',c.chanceMoney,0)) as Mosight50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Mosight%',c.chanceMoney,0)) as Mosight25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Insight%',c.chanceMoney,0)) as Insight100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Insight%',c.chanceMoney,0)) as Insight80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Insight%',c.chanceMoney,0)) as Insight50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Insight%',c.chanceMoney,0)) as Insight25,

sum(if(c.winRate='100' && g.goodsName like '%ClouDil Insight%',c.chanceMoney,0)) as cInsight100,
sum(if(c.winRate='80'  && g.goodsName like '%ClouDil Insight%',c.chanceMoney,0)) as cInsight80,
sum(if(c.winRate='50'  && g.goodsName like '%ClouDil Insight%',c.chanceMoney,0)) as cInsight50,
sum(if(c.winRate='25'  && g.goodsName like '%ClouDil Insight%',c.chanceMoney,0)) as cInsight25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot WireRunner%',c.chanceMoney,0)) as WireRunner100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot WireRunner%',c.chanceMoney,0)) as WireRunner80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot WireRunner%',c.chanceMoney,0)) as WireRunner50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot WireRunner%',c.chanceMoney,0)) as WireRunner25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot Insight-CDMA%',c.chanceMoney,0)) as CDMA100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot Insight-CDMA%',c.chanceMoney,0)) as CDMA80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot Insight-CDMA%',c.chanceMoney,0)) as CDMA50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot Insight-CDMA%',c.chanceMoney,0)) as CDMA25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot EU%',c.chanceMoney,0)) as EU100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot EU%',c.chanceMoney,0)) as EU80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot EU%',c.chanceMoney,0)) as EU50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot EU%',c.chanceMoney,0)) as EU25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot CapMax%',c.chanceMoney,0)) as CapMax100,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot CapMax%',c.chanceMoney,0)) as CapMax80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot CapMax%',c.chanceMoney,0)) as CapMax50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot CapMax%',c.chanceMoney,0)) as CapMax25,

sum(if(c.winRate='100' && g.goodsName like '%Pilot NetMonitor%',c.chanceMoney,0)) as NetMonitor00,
sum(if(c.winRate='80'  && g.goodsName like '%Pilot NetMonitor%',c.chanceMoney,0)) as NetMonitor80,
sum(if(c.winRate='50'  && g.goodsName like '%Pilot NetMonitor%',c.chanceMoney,0)) as NetMonitor50,
sum(if(c.winRate='25'  && g.goodsName like '%Pilot NetMonitor%',c.chanceMoney,0)) as NetMonitor25
";

$QuerySQL = <<<QuerySQL


select * from
(
/*全国*/
select date_format(c.predictContractDate,'%Y') as year,"1" as num,g.goodsName,g.chanceId,c.winRate,"全国" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%移动%" then '移动'
   when c.customerTypeName like "%联通%" then '联通'
   when c.customerTypeName like "%电信%" then '电信'
   when c.customerTypeName like "%系统商%" then '系统商'
   when c.customerTypeName like "%第三方%" then '第三方'
   else '未知' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'数量' as type,

$countStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'
group by c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"1" as num,g.goodsName,g.chanceId,c.winRate,"全国" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%移动%" then '移动'
   when c.customerTypeName like "%联通%" then '联通'
   when c.customerTypeName like "%电信%" then '电信'
   when c.customerTypeName like "%系统商%" then '系统商'
   when c.customerTypeName like "%第三方%" then '第三方'
   else '未知' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'金额' as type,

$sumStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'
group by c.province,c.customerTypeName,chanceQuarter

union all

/* 移动联通电信*/
select date_format(c.predictContractDate,'%Y') as year,"2" as num,g.goodsName,g.chanceId,c.winRate,c.Province,c.ProvinceId,
(case
   when c.customerTypeName like "%移动%" then '移动'
   when c.customerTypeName like "%联通%" then '联通'
   when c.customerTypeName like "%电信%" then '电信'
   else '未知' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'数量' as type,

$countStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and (c.customerTypeName like '%移动%' or c.customerTypeName like '%联通%' or c.customerTypeName like '%电信%')
group by c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"2" as num,g.goodsName,g.chanceId,c.winRate,c.Province,c.ProvinceId,
(case
   when c.customerTypeName like "%移动%" then '移动'
   when c.customerTypeName like "%联通%" then '联通'
   when c.customerTypeName like "%电信%" then '电信'
   else '未知' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'金额' as type,

$sumStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and (c.customerTypeName like '%移动%' or c.customerTypeName like '%联通%' or c.customerTypeName like '%电信%')
group by c.province,c.customerTypeName,chanceQuarter

union all
/*系统商*/
select date_format(c.predictContractDate,'%Y') as year,"3" as num,g.goodsName,g.chanceId,c.winRate,"系统商" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%爱立信%" then '爱立信'
   when c.customerTypeName like "%华为%" then '华为'
   when c.customerTypeName like "%诺西%" then '诺西'
   when c.customerTypeName like "%阿朗%" then '阿朗'
   when c.customerTypeName like "%中兴%" then '中兴'
   else '其他' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'数量' as type,

$countStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and c.customerTypeName like "%系统商%"
group by c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"3" as num,g.goodsName,g.chanceId,c.winRate,"系统商" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%爱立信%" then '爱立信'
   when c.customerTypeName like "%华为%" then '华为'
   when c.customerTypeName like "%诺西%" then '诺西'
   when c.customerTypeName like "%阿朗%" then '阿朗'
   when c.customerTypeName like "%中兴%" then '中兴'
   else '其他' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'金额' as type,

$sumStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'  and c.customerTypeName like "%系统商%"
group by c.province,c.customerTypeName,chanceQuarter
)c
$whereSql
group by province,customerTypeName,chanceQuarter,type
order by num,province,customerTypeName,chanceQuarter,type

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
