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
count(if(c.winRate='100' && g.goodsName='�ճ�����',true,null)) as rcpg100,
count(if(c.winRate='80'  && g.goodsName='�ճ�����',true,null)) as rcpg80,
count(if(c.winRate='50'  && g.goodsName='�ճ�����',true,null)) as rcpg50,
count(if(c.winRate='25'  && g.goodsName='�ճ�����',true,null)) as rcpg25,

count(if(c.winRate='100' && g.goodsName='������Ѳ��',true,null)) as dsf100,
count(if(c.winRate='80'  && g.goodsName='������Ѳ��',true,null)) as dsf80,
count(if(c.winRate='50'  && g.goodsName='������Ѳ��',true,null)) as dsf50,
count(if(c.winRate='25'  && g.goodsName='������Ѳ��',true,null)) as dsf25,

count(if(c.winRate='100' && g.goodsName='Ͷ�ߴ������ά',true,null)) as ts100,
count(if(c.winRate='80'  && g.goodsName='Ͷ�ߴ������ά',true,null)) as ts80,
count(if(c.winRate='50'  && g.goodsName='Ͷ�ߴ������ά',true,null)) as ts50,
count(if(c.winRate='25'  && g.goodsName='Ͷ�ߴ������ά',true,null)) as ts25,

count(if(c.winRate='100' && g.goodsName='ATU��ά',true,null)) as atu100,
count(if(c.winRate='80'  && g.goodsName='ATU��ά',true,null)) as atu80,
count(if(c.winRate='50'  && g.goodsName='ATU��ά',true,null)) as atu50,
count(if(c.winRate='25'  && g.goodsName='ATU��ά',true,null)) as atu25,

count(if(c.winRate='100' && g.goodsName='�ճ�����',true,null)) as rcwy100,
count(if(c.winRate='80'  && g.goodsName='�ճ�����',true,null)) as rcwy80,
count(if(c.winRate='50'  && g.goodsName='�ճ�����',true,null)) as rcwy50,
count(if(c.winRate='25'  && g.goodsName='�ճ�����',true,null)) as rcwy25,

count(if(c.winRate='100' && g.goodsName='MOS����ר��',true,null)) as mos100,
count(if(c.winRate='80'  && g.goodsName='MOS����ר��',true,null)) as mos80,
count(if(c.winRate='50'  && g.goodsName='MOS����ר��',true,null)) as mos50,
count(if(c.winRate='25'  && g.goodsName='MOS����ר��',true,null)) as mos25,

count(if(c.winRate='100' && g.goodsName='��Ƶר��',true,null)) as bpzx100,
count(if(c.winRate='80'  && g.goodsName='��Ƶר��',true,null)) as bpzx80,
count(if(c.winRate='50'  && g.goodsName='��Ƶר��',true,null)) as bpzx50,
count(if(c.winRate='25'  && g.goodsName='��Ƶר��',true,null)) as bpzx25,

count(if(c.winRate='100' && g.goodsName='���ٸ���',true,null)) as gsgt100,
count(if(c.winRate='80'  && g.goodsName='���ٸ���',true,null)) as gsgt80,
count(if(c.winRate='50'  && g.goodsName='���ٸ���',true,null)) as gsgt50,
count(if(c.winRate='25'  && g.goodsName='���ٸ���',true,null)) as gsgt25,

count(if(c.winRate='100' && g.goodsName='������Эͬ�Ż�',true,null)) as snw100,
count(if(c.winRate='80'  && g.goodsName='������Эͬ�Ż�',true,null)) as snw80,
count(if(c.winRate='50'  && g.goodsName='������Эͬ�Ż�',true,null)) as snw50,
count(if(c.winRate='25'  && g.goodsName='������Эͬ�Ż�',true,null)) as snw25,

count(if(c.winRate='100' && g.goodsName='�����Ż�',true,null)) as sjyh100,
count(if(c.winRate='80'  && g.goodsName='�����Ż�',true,null)) as sjyh80,
count(if(c.winRate='50'  && g.goodsName='�����Ż�',true,null)) as sjyh50,
count(if(c.winRate='25'  && g.goodsName='�����Ż�',true,null)) as sjyh25,

count(if(c.winRate='100' && g.goodsName='��Ʒ�ۺ�',true,null)) as cpsh100,
count(if(c.winRate='80'  && g.goodsName='��Ʒ�ۺ�',true,null)) as cpsh80,
count(if(c.winRate='50'  && g.goodsName='��Ʒ�ۺ�',true,null)) as cpsh50,
count(if(c.winRate='25'  && g.goodsName='��Ʒ�ۺ�',true,null)) as cpsh25,

count(if(c.winRate='100' && g.goodsName='A+Abis����',true,null)) as abis100,
count(if(c.winRate='80'  && g.goodsName='A+Abis����',true,null)) as abis80,
count(if(c.winRate='50'  && g.goodsName='A+Abis����',true,null)) as abis50,
count(if(c.winRate='25'  && g.goodsName='A+Abis����',true,null)) as abis25,

count(if(c.winRate='100' && g.goodsName='CapMax����ר��',true,null)) as cap100,
count(if(c.winRate='80'  && g.goodsName='CapMax����ר��',true,null)) as cap80,
count(if(c.winRate='50'  && g.goodsName='CapMax����ר��',true,null)) as cap50,
count(if(c.winRate='25'  && g.goodsName='CapMax����ר��',true,null)) as cap25,

count(if(c.winRate='100' && g.goodsName='�������Ż�ר��',true,null)) as tkx100,
count(if(c.winRate='80'  && g.goodsName='�������Ż�ר��',true,null)) as tkx80,
count(if(c.winRate='50'  && g.goodsName='�������Ż�ר��',true,null)) as tkx50,
count(if(c.winRate='25'  && g.goodsName='�������Ż�ר��',true,null)) as tkx25,

count(if(c.winRate='100' && g.goodsName='��װ����',true,null)) as aztc100,
count(if(c.winRate='80'  && g.goodsName='��װ����',true,null)) as aztc80,
count(if(c.winRate='50'  && g.goodsName='��װ����',true,null)) as aztc50,
count(if(c.winRate='25'  && g.goodsName='��װ����',true,null)) as aztc25,

count(if(c.winRate='100' && g.goodsName='��������',true,null)) as qtfw100,
count(if(c.winRate='80'  && g.goodsName='��������',true,null)) as qtfw80,
count(if(c.winRate='50'  && g.goodsName='��������',true,null)) as qtfw50,
count(if(c.winRate='25'  && g.goodsName='��������',true,null)) as qtfw25
";

$sumStr = "
sum(if(c.winRate='100' && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcpg100,
sum(if(c.winRate='80'  && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcpg80,
sum(if(c.winRate='50'  && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcpg50,
sum(if(c.winRate='25'  && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcpg25,

sum(if(c.winRate='100' && g.goodsName='������Ѳ��',c.chanceMoney,0)) as dsf100,
sum(if(c.winRate='80'  && g.goodsName='������Ѳ��',c.chanceMoney,0)) as dsf80,
sum(if(c.winRate='50'  && g.goodsName='������Ѳ��',c.chanceMoney,0)) as dsf50,
sum(if(c.winRate='25'  && g.goodsName='������Ѳ��',c.chanceMoney,0)) as dsf25,

sum(if(c.winRate='100' && g.goodsName='Ͷ�ߴ������ά',c.chanceMoney,0)) as ts100,
sum(if(c.winRate='80'  && g.goodsName='Ͷ�ߴ������ά',c.chanceMoney,0)) as ts80,
sum(if(c.winRate='50'  && g.goodsName='Ͷ�ߴ������ά',c.chanceMoney,0)) as ts50,
sum(if(c.winRate='25'  && g.goodsName='Ͷ�ߴ������ά',c.chanceMoney,0)) as ts25,

sum(if(c.winRate='100' && g.goodsName='ATU��ά',c.chanceMoney,0)) as atu100,
sum(if(c.winRate='80'  && g.goodsName='ATU��ά',c.chanceMoney,0)) as atu80,
sum(if(c.winRate='50'  && g.goodsName='ATU��ά',c.chanceMoney,0)) as atu50,
sum(if(c.winRate='25'  && g.goodsName='ATU��ά',c.chanceMoney,0)) as atu25,

sum(if(c.winRate='100' && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcwy100,
sum(if(c.winRate='80'  && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcwy80,
sum(if(c.winRate='50'  && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcwy50,
sum(if(c.winRate='25'  && g.goodsName='�ճ�����',c.chanceMoney,0)) as rcwy25,

sum(if(c.winRate='100' && g.goodsName='MOS����ר��',c.chanceMoney,0)) as mos100,
sum(if(c.winRate='80'  && g.goodsName='MOS����ר��',c.chanceMoney,0)) as mos80,
sum(if(c.winRate='50'  && g.goodsName='MOS����ר��',c.chanceMoney,0)) as mos50,
sum(if(c.winRate='25'  && g.goodsName='MOS����ר��',c.chanceMoney,0)) as mos25,

sum(if(c.winRate='100' && g.goodsName='��Ƶר��',c.chanceMoney,0)) as bpzx100,
sum(if(c.winRate='80'  && g.goodsName='��Ƶר��',c.chanceMoney,0)) as bpzx80,
sum(if(c.winRate='50'  && g.goodsName='��Ƶר��',c.chanceMoney,0)) as bpzx50,
sum(if(c.winRate='25'  && g.goodsName='��Ƶר��',c.chanceMoney,0)) as bpzx25,

sum(if(c.winRate='100' && g.goodsName='���ٸ���',c.chanceMoney,0)) as gsgt100,
sum(if(c.winRate='80'  && g.goodsName='���ٸ���',c.chanceMoney,0)) as gsgt80,
sum(if(c.winRate='50'  && g.goodsName='���ٸ���',c.chanceMoney,0)) as gsgt50,
sum(if(c.winRate='25'  && g.goodsName='���ٸ���',c.chanceMoney,0)) as gsgt25,

sum(if(c.winRate='100' && g.goodsName='������Эͬ�Ż�',c.chanceMoney,0)) as snw100,
sum(if(c.winRate='80'  && g.goodsName='������Эͬ�Ż�',c.chanceMoney,0)) as snw80,
sum(if(c.winRate='50'  && g.goodsName='������Эͬ�Ż�',c.chanceMoney,0)) as snw50,
sum(if(c.winRate='25'  && g.goodsName='������Эͬ�Ż�',c.chanceMoney,0)) as snw25,

sum(if(c.winRate='100' && g.goodsName='�����Ż�',c.chanceMoney,0)) as sjyh100,
sum(if(c.winRate='80'  && g.goodsName='�����Ż�',c.chanceMoney,0)) as sjyh80,
sum(if(c.winRate='50'  && g.goodsName='�����Ż�',c.chanceMoney,0)) as sjyh50,
sum(if(c.winRate='25'  && g.goodsName='�����Ż�',c.chanceMoney,0)) as sjyh25,

sum(if(c.winRate='100' && g.goodsName='��Ʒ�ۺ�',c.chanceMoney,0)) as cpsh100,
sum(if(c.winRate='80'  && g.goodsName='��Ʒ�ۺ�',c.chanceMoney,0)) as cpsh80,
sum(if(c.winRate='50'  && g.goodsName='��Ʒ�ۺ�',c.chanceMoney,0)) as cpsh50,
sum(if(c.winRate='25'  && g.goodsName='��Ʒ�ۺ�',c.chanceMoney,0)) as cpsh25,

sum(if(c.winRate='100' && g.goodsName='A+Abis����',c.chanceMoney,0)) as abis100,
sum(if(c.winRate='80'  && g.goodsName='A+Abis����',c.chanceMoney,0)) as abis80,
sum(if(c.winRate='50'  && g.goodsName='A+Abis����',c.chanceMoney,0)) as abis50,
sum(if(c.winRate='25'  && g.goodsName='A+Abis����',c.chanceMoney,0)) as abis25,

sum(if(c.winRate='100' && g.goodsName='CapMax����ר��',c.chanceMoney,0)) as cap100,
sum(if(c.winRate='80'  && g.goodsName='CapMax����ר��',c.chanceMoney,0)) as cap80,
sum(if(c.winRate='50'  && g.goodsName='CapMax����ר��',c.chanceMoney,0)) as cap50,
sum(if(c.winRate='25'  && g.goodsName='CapMax����ר��',c.chanceMoney,0)) as cap25,

sum(if(c.winRate='100' && g.goodsName='�������Ż�ר��',c.chanceMoney,0)) as tkx100,
sum(if(c.winRate='80'  && g.goodsName='�������Ż�ר��',c.chanceMoney,0)) as tkx80,
sum(if(c.winRate='50'  && g.goodsName='�������Ż�ר��',c.chanceMoney,0)) as tkx50,
sum(if(c.winRate='25'  && g.goodsName='�������Ż�ר��',c.chanceMoney,0)) as tkx25,

sum(if(c.winRate='100' && g.goodsName='��װ����',c.chanceMoney,0)) as aztc100,
sum(if(c.winRate='80'  && g.goodsName='��װ����',c.chanceMoney,0)) as aztc80,
sum(if(c.winRate='50'  && g.goodsName='��װ����',c.chanceMoney,0)) as aztc50,
sum(if(c.winRate='25'  && g.goodsName='��װ����',c.chanceMoney,0)) as aztc25,

sum(if(c.winRate='100' && g.goodsName='��������',c.chanceMoney,0)) as qtfw100,
sum(if(c.winRate='80'  && g.goodsName='��������',c.chanceMoney,0)) as qtfw80,
sum(if(c.winRate='50'  && g.goodsName='��������',c.chanceMoney,0)) as qtfw50,
sum(if(c.winRate='25'  && g.goodsName='��������',c.chanceMoney,0)) as qtfw25
";

$QuerySQL = <<<QuerySQL


select * from
(
/*ȫ��*/
select date_format(c.predictContractDate,'%Y') as year,"1" as num,"ȫ��" as officeName,"ȫ��" as mainManager,g.goodsName,g.chanceId,c.winRate,"ȫ��" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%�ƶ�%" then '�ƶ�'
   when c.customerTypeName like "%��ͨ%" then '��ͨ'
   when c.customerTypeName like "%����%" then '����'
   when c.customerTypeName like "%ϵͳ��%" then 'ϵͳ��'
   when c.customerTypeName like "%������%" then '������'
   else 'δ֪' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'����' as type,

$countStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"1" as num,"ȫ��" as officeName,"ȫ��" as mainManager,g.goodsName,g.chanceId,c.winRate,"ȫ��" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%�ƶ�%" then '�ƶ�'
   when c.customerTypeName like "%��ͨ%" then '��ͨ'
   when c.customerTypeName like "%����%" then '����'
   when c.customerTypeName like "%ϵͳ��%" then 'ϵͳ��'
   when c.customerTypeName like "%������%" then '������'
   else 'δ֪' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'���' as type,

$sumStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

/* �ƶ���ͨ����*/
select date_format(c.predictContractDate,'%Y') as year,"2" as num,o.officeName,o.mainManager,g.goodsName,g.chanceId,c.winRate,c.Province,c.ProvinceId,
(case
   when c.customerTypeName like "%�ƶ�%" then '�ƶ�'
   when c.customerTypeName like "%��ͨ%" then '��ͨ'
   when c.customerTypeName like "%����%" then '����'
   else 'δ֪' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'����' as type,

$countStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and (c.customerTypeName like '%�ƶ�%' or c.customerTypeName like '%��ͨ%' or c.customerTypeName like '%����%')
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"2" as num,o.officeName,o.mainManager,g.goodsName,g.chanceId,c.winRate,c.Province,c.ProvinceId,
(case
   when c.customerTypeName like "%�ƶ�%" then '�ƶ�'
   when c.customerTypeName like "%��ͨ%" then '��ͨ'
   when c.customerTypeName like "%����%" then '����'
   else 'δ֪' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'���' as type,

$sumStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and (c.customerTypeName like '%�ƶ�%' or c.customerTypeName like '%��ͨ%' or c.customerTypeName like '%����%')
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all
/*ϵͳ��*/
select date_format(c.predictContractDate,'%Y') as year,"3" as num,"ϵͳ��" as officeName,"ϵͳ��" as mainManager,g.goodsName,g.chanceId,c.winRate,"ϵͳ��" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%������%" then '������'
   when c.customerTypeName like "%��Ϊ%" then '��Ϊ'
   when c.customerTypeName like "%ŵ��%" then 'ŵ��'
   when c.customerTypeName like "%����%" then '����'
   when c.customerTypeName like "%����%" then '����'
   else '����' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'����' as type,

$countStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00' and c.customerTypeName like "%ϵͳ��%"
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter

union all

select date_format(c.predictContractDate,'%Y') as year,"3" as num,"ϵͳ��" as officeName,"ϵͳ��" as mainManager,g.goodsName,g.chanceId,c.winRate,"ϵͳ��" as Province,c.ProvinceId,
(case
   when c.customerTypeName like "%������%" then '������'
   when c.customerTypeName like "%��Ϊ%" then '��Ϊ'
   when c.customerTypeName like "%ŵ��%" then 'ŵ��'
   when c.customerTypeName like "%����%" then '����'
   when c.customerTypeName like "%����%" then '����'
   else '����' end) as customerTypeName,
QUARTER(c.predictContractDate) as chanceQuarter,'���' as type,

$sumStr

from oa_sale_chance_goods g

  left join oa_sale_chance c on g.chanceId = c.id
  left join oa_esm_office_baseinfo o on FIND_IN_SET(c.Province,o.rangeName)
where c.status in (0,5) and c.province != '' and c.predictContractDate != '0000-00-00'  and c.customerTypeName like "%ϵͳ��%"
group by o.officeName,o.mainManager,c.province,c.customerTypeName,chanceQuarter
)c
$whereSql
group by officeName,mainManager,province,customerTypeName,chanceQuarter,type
order by num,officeName,mainManager,province,customerTypeName,chanceQuarter,type

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
