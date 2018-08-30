<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$QuerySQL = <<<QuerySQL
select groupDocId1,docCode,pickName,productCode,productName,pattern,serialnoName,allocatNum,customerName,deptName,outStartDate,outEndDate,
case toUse when 'CHUKUGUIH' then '¹é»¹'  when 'CHUKUJY' then '½èÓÃ' when 'CHUKUWX' then  'Î¬ÐÞ'  when 'CHUKUSY' then 'ÊÔÓÃ'  else '' end  as toUse
from (
	select `a1`.id AS groupDocId1,a1.*,ai1.`productCode`,ai1.`productName`,ai1.`unitName`,ai1.`pattern`,
	case a1.toUse  when 'CHUKUGUIH'   then  -ai1.allocatNum  else ai1.allocatNum end  as allocatNum,ai1.`serialnoName`
	from oa_stock_allocation `a1`  right join oa_stock_allocation_item `ai1` on(a1.id=ai1.mainId)
where a1.id in(
      select sumGroupTab.docGroupId as docGroupId from (
       select  sumTab.docGroupId as docGroupId ,sum(sumTab.allocatNum) as allocatNum from (
        select  case a.toUse  when 'CHUKUGUIH'  then ai.relDocId else a.id end as docGroupId  ,
        	case a.toUse  when 'CHUKUGUIH'   then  -ai.allocatNum  else ai.allocatNum end  as allocatNum 
      	from oa_stock_allocation  a right join  oa_stock_allocation_item  ai  on(ai.mainId=a.id)   
        	where a.toUse in('CHUKUJY','CHUKUWX','CHUKUSY','CHUKUGUIH') and a.docStatus='YSH'
      ) sumTab  group by sumTab.docGroupId ) sumGroupTab  where sumGroupTab.allocatNum>'0'
) 
UNION all
select `ai1`.relDocId AS groupDocId1,a1.*,ai1.`productCode`,ai1.`productName`,ai1.`unitName`,ai1.`pattern`,
	case a1.toUse  when 'CHUKUGUIH'   then  -ai1.allocatNum  else ai1.allocatNum end  as allocatNum,ai1.`serialnoName`
	from oa_stock_allocation `a1`  right join oa_stock_allocation_item `ai1` on(a1.id=ai1.mainId)
where `ai1`.`relDocId` in(
     select sumGroupTab.docGroupId as docGroupId from (
       select  sumTab.docGroupId as docGroupId ,sum(sumTab.allocatNum) as allocatNum from (
        select  case a.toUse  when 'CHUKUGUIH'  then ai.relDocId else a.id end as docGroupId  ,
        	case a.toUse  when 'CHUKUGUIH'   then  -ai.allocatNum  else ai.allocatNum end  as allocatNum 
      	from oa_stock_allocation  a right join  oa_stock_allocation_item  ai  on(ai.mainId=a.id)   
        	where a.toUse in('CHUKUJY','CHUKUWX','CHUKUSY','CHUKUGUIH') and a.docStatus='YSH'
      ) sumTab  group by sumTab.docGroupId ) sumGroupTab  where sumGroupTab.allocatNum>'0'
) 
) di order by di.groupDocId1,di.productCode,di.pickName asc
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
