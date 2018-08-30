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
count(if(c.winRate='100' && g.goodsName='日常评估',true,null)) as rcpg100,
count(if(c.winRate='80'  && g.goodsName='日常评估',true,null)) as rcpg80,
count(if(c.winRate='50'  && g.goodsName='日常评估',true,null)) as rcpg50,
count(if(c.winRate='25'  && g.goodsName='日常评估',true,null)) as rcpg25,

count(if(c.winRate='100' && g.goodsName='第三方巡检',true,null)) as dsf100,
count(if(c.winRate='80'  && g.goodsName='第三方巡检',true,null)) as dsf80,
count(if(c.winRate='50'  && g.goodsName='第三方巡检',true,null)) as dsf50,
count(if(c.winRate='25'  && g.goodsName='第三方巡检',true,null)) as dsf25,

count(if(c.winRate='100' && g.goodsName='投诉处理与代维',true,null)) as ts100,
count(if(c.winRate='80'  && g.goodsName='投诉处理与代维',true,null)) as ts80,
count(if(c.winRate='50'  && g.goodsName='投诉处理与代维',true,null)) as ts50,
count(if(c.winRate='25'  && g.goodsName='投诉处理与代维',true,null)) as ts25,

count(if(c.winRate='100' && g.goodsName='ATU代维',true,null)) as atu100,
count(if(c.winRate='80'  && g.goodsName='ATU代维',true,null)) as atu80,
count(if(c.winRate='50'  && g.goodsName='ATU代维',true,null)) as atu50,
count(if(c.winRate='25'  && g.goodsName='ATU代维',true,null)) as atu25,

count(if(c.winRate='100' && g.goodsName='日常网优',true,null)) as rcwy100,
count(if(c.winRate='80'  && g.goodsName='日常网优',true,null)) as rcwy80,
count(if(c.winRate='50'  && g.goodsName='日常网优',true,null)) as rcwy50,
count(if(c.winRate='25'  && g.goodsName='日常网优',true,null)) as rcwy25,

count(if(c.winRate='100' && g.goodsName='MOS提升专项',true,null)) as mos100,
count(if(c.winRate='80'  && g.goodsName='MOS提升专项',true,null)) as mos80,
count(if(c.winRate='50'  && g.goodsName='MOS提升专项',true,null)) as mos50,
count(if(c.winRate='25'  && g.goodsName='MOS提升专项',true,null)) as mos25,

count(if(c.winRate='100' && g.goodsName='变频专项',true,null)) as bpzx100,
count(if(c.winRate='80'  && g.goodsName='变频专项',true,null)) as bpzx80,
count(if(c.winRate='50'  && g.goodsName='变频专项',true,null)) as bpzx50,
count(if(c.winRate='25'  && g.goodsName='变频专项',true,null)) as bpzx25,

count(if(c.winRate='100' && g.goodsName='高速高铁',true,null)) as gsgt100,
count(if(c.winRate='80'  && g.goodsName='高速高铁',true,null)) as gsgt80,
count(if(c.winRate='50'  && g.goodsName='高速高铁',true,null)) as gsgt50,
count(if(c.winRate='25'  && g.goodsName='高速高铁',true,null)) as gsgt25,

count(if(c.winRate='100' && g.goodsName='室内外协同优化',true,null)) as snw100,
count(if(c.winRate='80'  && g.goodsName='室内外协同优化',true,null)) as snw80,
count(if(c.winRate='50'  && g.goodsName='室内外协同优化',true,null)) as snw50,
count(if(c.winRate='25'  && g.goodsName='室内外协同优化',true,null)) as snw25,

count(if(c.winRate='100' && g.goodsName='数据优化',true,null)) as sjyh100,
count(if(c.winRate='80'  && g.goodsName='数据优化',true,null)) as sjyh80,
count(if(c.winRate='50'  && g.goodsName='数据优化',true,null)) as sjyh50,
count(if(c.winRate='25'  && g.goodsName='数据优化',true,null)) as sjyh25,

count(if(c.winRate='100' && g.goodsName='产品售后',true,null)) as cpsh100,
count(if(c.winRate='80'  && g.goodsName='产品售后',true,null)) as cpsh80,
count(if(c.winRate='50'  && g.goodsName='产品售后',true,null)) as cpsh50,
count(if(c.winRate='25'  && g.goodsName='产品售后',true,null)) as cpsh25,

count(if(c.winRate='100' && g.goodsName='A+Abis服务',true,null)) as abis100,
count(if(c.winRate='80'  && g.goodsName='A+Abis服务',true,null)) as abis80,
count(if(c.winRate='50'  && g.goodsName='A+Abis服务',true,null)) as abis50,
count(if(c.winRate='25'  && g.goodsName='A+Abis服务',true,null)) as abis25,

count(if(c.winRate='100' && g.goodsName='CapMax调整专项',true,null)) as cap100,
count(if(c.winRate='80'  && g.goodsName='CapMax调整专项',true,null)) as cap80,
count(if(c.winRate='50'  && g.goodsName='CapMax调整专项',true,null)) as cap50,
count(if(c.winRate='25'  && g.goodsName='CapMax调整专项',true,null)) as cap25,

count(if(c.winRate='100' && g.goodsName='天馈线优化专项',true,null)) as tkx100,
count(if(c.winRate='80'  && g.goodsName='天馈线优化专项',true,null)) as tkx80,
count(if(c.winRate='50'  && g.goodsName='天馈线优化专项',true,null)) as tkx50,
count(if(c.winRate='25'  && g.goodsName='天馈线优化专项',true,null)) as tkx25,

count(if(c.winRate='100' && g.goodsName='安装调测',true,null)) as aztc100,
count(if(c.winRate='80'  && g.goodsName='安装调测',true,null)) as aztc80,
count(if(c.winRate='50'  && g.goodsName='安装调测',true,null)) as aztc50,
count(if(c.winRate='25'  && g.goodsName='安装调测',true,null)) as aztc25,

count(if(c.winRate='100' && g.goodsName='其他服务',true,null)) as qtfw100,
count(if(c.winRate='80'  && g.goodsName='其他服务',true,null)) as qtfw80,
count(if(c.winRate='50'  && g.goodsName='其他服务',true,null)) as qtfw50,
count(if(c.winRate='25'  && g.goodsName='其他服务',true,null)) as qtfw25
";

$sumStr = "
sum(if(c.winRate='100' && g.goodsName='日常评估',c.chanceMoney,0)) as rcpg100,
sum(if(c.winRate='80'  && g.goodsName='日常评估',c.chanceMoney,0)) as rcpg80,
sum(if(c.winRate='50'  && g.goodsName='日常评估',c.chanceMoney,0)) as rcpg50,
sum(if(c.winRate='25'  && g.goodsName='日常评估',c.chanceMoney,0)) as rcpg25,

sum(if(c.winRate='100' && g.goodsName='第三方巡检',c.chanceMoney,0)) as dsf100,
sum(if(c.winRate='80'  && g.goodsName='第三方巡检',c.chanceMoney,0)) as dsf80,
sum(if(c.winRate='50'  && g.goodsName='第三方巡检',c.chanceMoney,0)) as dsf50,
sum(if(c.winRate='25'  && g.goodsName='第三方巡检',c.chanceMoney,0)) as dsf25,

sum(if(c.winRate='100' && g.goodsName='投诉处理与代维',c.chanceMoney,0)) as ts100,
sum(if(c.winRate='80'  && g.goodsName='投诉处理与代维',c.chanceMoney,0)) as ts80,
sum(if(c.winRate='50'  && g.goodsName='投诉处理与代维',c.chanceMoney,0)) as ts50,
sum(if(c.winRate='25'  && g.goodsName='投诉处理与代维',c.chanceMoney,0)) as ts25,

sum(if(c.winRate='100' && g.goodsName='ATU代维',c.chanceMoney,0)) as atu100,
sum(if(c.winRate='80'  && g.goodsName='ATU代维',c.chanceMoney,0)) as atu80,
sum(if(c.winRate='50'  && g.goodsName='ATU代维',c.chanceMoney,0)) as atu50,
sum(if(c.winRate='25'  && g.goodsName='ATU代维',c.chanceMoney,0)) as atu25,

sum(if(c.winRate='100' && g.goodsName='日常网优',c.chanceMoney,0)) as rcwy100,
sum(if(c.winRate='80'  && g.goodsName='日常网优',c.chanceMoney,0)) as rcwy80,
sum(if(c.winRate='50'  && g.goodsName='日常网优',c.chanceMoney,0)) as rcwy50,
sum(if(c.winRate='25'  && g.goodsName='日常网优',c.chanceMoney,0)) as rcwy25,

sum(if(c.winRate='100' && g.goodsName='MOS提升专项',c.chanceMoney,0)) as mos100,
sum(if(c.winRate='80'  && g.goodsName='MOS提升专项',c.chanceMoney,0)) as mos80,
sum(if(c.winRate='50'  && g.goodsName='MOS提升专项',c.chanceMoney,0)) as mos50,
sum(if(c.winRate='25'  && g.goodsName='MOS提升专项',c.chanceMoney,0)) as mos25,

sum(if(c.winRate='100' && g.goodsName='变频专项',c.chanceMoney,0)) as bpzx100,
sum(if(c.winRate='80'  && g.goodsName='变频专项',c.chanceMoney,0)) as bpzx80,
sum(if(c.winRate='50'  && g.goodsName='变频专项',c.chanceMoney,0)) as bpzx50,
sum(if(c.winRate='25'  && g.goodsName='变频专项',c.chanceMoney,0)) as bpzx25,

sum(if(c.winRate='100' && g.goodsName='高速高铁',c.chanceMoney,0)) as gsgt100,
sum(if(c.winRate='80'  && g.goodsName='高速高铁',c.chanceMoney,0)) as gsgt80,
sum(if(c.winRate='50'  && g.goodsName='高速高铁',c.chanceMoney,0)) as gsgt50,
sum(if(c.winRate='25'  && g.goodsName='高速高铁',c.chanceMoney,0)) as gsgt25,

sum(if(c.winRate='100' && g.goodsName='室内外协同优化',c.chanceMoney,0)) as snw100,
sum(if(c.winRate='80'  && g.goodsName='室内外协同优化',c.chanceMoney,0)) as snw80,
sum(if(c.winRate='50'  && g.goodsName='室内外协同优化',c.chanceMoney,0)) as snw50,
sum(if(c.winRate='25'  && g.goodsName='室内外协同优化',c.chanceMoney,0)) as snw25,

sum(if(c.winRate='100' && g.goodsName='数据优化',c.chanceMoney,0)) as sjyh100,
sum(if(c.winRate='80'  && g.goodsName='数据优化',c.chanceMoney,0)) as sjyh80,
sum(if(c.winRate='50'  && g.goodsName='数据优化',c.chanceMoney,0)) as sjyh50,
sum(if(c.winRate='25'  && g.goodsName='数据优化',c.chanceMoney,0)) as sjyh25,

sum(if(c.winRate='100' && g.goodsName='产品售后',c.chanceMoney,0)) as cpsh100,
sum(if(c.winRate='80'  && g.goodsName='产品售后',c.chanceMoney,0)) as cpsh80,
sum(if(c.winRate='50'  && g.goodsName='产品售后',c.chanceMoney,0)) as cpsh50,
sum(if(c.winRate='25'  && g.goodsName='产品售后',c.chanceMoney,0)) as cpsh25,

sum(if(c.winRate='100' && g.goodsName='A+Abis服务',c.chanceMoney,0)) as abis100,
sum(if(c.winRate='80'  && g.goodsName='A+Abis服务',c.chanceMoney,0)) as abis80,
sum(if(c.winRate='50'  && g.goodsName='A+Abis服务',c.chanceMoney,0)) as abis50,
sum(if(c.winRate='25'  && g.goodsName='A+Abis服务',c.chanceMoney,0)) as abis25,

sum(if(c.winRate='100' && g.goodsName='CapMax调整专项',c.chanceMoney,0)) as cap100,
sum(if(c.winRate='80'  && g.goodsName='CapMax调整专项',c.chanceMoney,0)) as cap80,
sum(if(c.winRate='50'  && g.goodsName='CapMax调整专项',c.chanceMoney,0)) as cap50,
sum(if(c.winRate='25'  && g.goodsName='CapMax调整专项',c.chanceMoney,0)) as cap25,

sum(if(c.winRate='100' && g.goodsName='天馈线优化专项',c.chanceMoney,0)) as tkx100,
sum(if(c.winRate='80'  && g.goodsName='天馈线优化专项',c.chanceMoney,0)) as tkx80,
sum(if(c.winRate='50'  && g.goodsName='天馈线优化专项',c.chanceMoney,0)) as tkx50,
sum(if(c.winRate='25'  && g.goodsName='天馈线优化专项',c.chanceMoney,0)) as tkx25,

sum(if(c.winRate='100' && g.goodsName='安装调测',c.chanceMoney,0)) as aztc100,
sum(if(c.winRate='80'  && g.goodsName='安装调测',c.chanceMoney,0)) as aztc80,
sum(if(c.winRate='50'  && g.goodsName='安装调测',c.chanceMoney,0)) as aztc50,
sum(if(c.winRate='25'  && g.goodsName='安装调测',c.chanceMoney,0)) as aztc25,

sum(if(c.winRate='100' && g.goodsName='其他服务',c.chanceMoney,0)) as qtfw100,
sum(if(c.winRate='80'  && g.goodsName='其他服务',c.chanceMoney,0)) as qtfw80,
sum(if(c.winRate='50'  && g.goodsName='其他服务',c.chanceMoney,0)) as qtfw50,
sum(if(c.winRate='25'  && g.goodsName='其他服务',c.chanceMoney,0)) as qtfw25
";

$QuerySQL = <<<QuerySQL


select * from
(
/*全国*/
select date_format(c.predictContractDate,'%Y') as year,"1" as num,"全国" as officeName,"全国" as mainManager,g.goodsName,g.chanceId,c.winRate,"全国" as Province,c.ProvinceId,
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
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"1" as num,"全国" as officeName,"全国" as mainManager,g.goodsName,g.chanceId,c.winRate,"全国" as Province,c.ProvinceId,
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
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

/* 移动联通电信*/
select date_format(c.predictContractDate,'%Y') as year,"2" as num,o.officeName,o.mainManager,g.goodsName,g.chanceId,c.winRate,c.Province,c.ProvinceId,
(case
   when c.customerTypeName like "%移动%" then '移动'
   when c.customerTypeName like "%联通%" then '联通'
   when c.customerTypeName like "%电信%" then '电信'
   else '未知' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'数量' as type,

$countStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and (c.customerTypeName like '%移动%' or c.customerTypeName like '%联通%' or c.customerTypeName like '%电信%')
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"2" as num,o.officeName,o.mainManager,g.goodsName,g.chanceId,c.winRate,c.Province,c.ProvinceId,
(case
   when c.customerTypeName like "%移动%" then '移动'
   when c.customerTypeName like "%联通%" then '联通'
   when c.customerTypeName like "%电信%" then '电信'
   else '未知' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'金额' as type,

$sumStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and (c.customerTypeName like '%移动%' or c.customerTypeName like '%联通%' or c.customerTypeName like '%电信%')
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all
/*系统商*/
select date_format(c.predictContractDate,'%Y') as year,"3" as num,"系统商" as officeName,"系统商" as mainManager,g.goodsName,g.chanceId,c.winRate,"系统商" as Province,c.ProvinceId,
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
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and c.customerTypeName like "%系统商%"
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"3" as num,"系统商" as officeName,"系统商" as mainManager,g.goodsName,g.chanceId,c.winRate,"系统商" as Province,c.ProvinceId,
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
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'  and c.customerTypeName like "%系统商%"
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter
)c
$whereSql
group by officeName,mainManager,province,customerTypeName,chanceQuarter,type
order by num,officeName,mainManager,province,customerTypeName,chanceQuarter,type

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
