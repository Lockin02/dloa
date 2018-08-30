<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$DATE = $_GET['year'] . "-" . $_GET['month'];
$year = $_GET['year'];
$month = $_GET['month'];
$QuerySQL = <<<QuerySQL
select $year as year,$month as month,rs.orderType,rs.orderNatureName, sum(addNum) as addNum,sum(orderMoney) as orderMoney,sum(tempNum) as tempNum,
sum(orderTempMoney) as orderTempMoney,sum(chanceNum) as chanceNum,sum(chanceMoney) as chanceMoney,sum(followChance) as followChance,
sum(followChanceMoney) as followChanceMoney,sum(shiftOrder) as shiftOrder,sum(shiftOrderMoney) as shiftOrderMoney,sum(beingNum) as beingNum,
sum(beingMoney) as beingMoney,sum(closeNum) as closeNum,sum(closeMoney) as closeMoney,sum(incomeMoney) as incomeMoney,sum(invoiceMoney) as invoiceMoney
from
(
/* 销售合同部分 开始*/
/* 新增合同数 , 签约合同金额  */
select '产品销售合同' as orderType,N.orderNatureName,count(N.id) as addNum,  sum(N.orderMoney) as orderMoney,0 as tempNum, 0 as orderTempMoney,0 as chanceNum,
0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order N  where N.orderMoney is not null and N.orderMoney <> '' and N.orderMoney <> '0' and N.state in ('2','3','4') and N.createTime like "$DATE%" and isTemp = 0 and N.ExaStatus = '完成'  and signinType <> 'service' group by N.orderNatureName
union all
/* 临时合同数 , 临时合同金额 */
select '产品销售合同' as orderType,P.orderNatureName,0 as addNum, 0 as orderMoney,count(P.id) as tempNum, sum(p.orderTempMoney) as orderTempMoney,
0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order P where (P.orderCode is null or P.orderCode = '' ) and P.state in ('2','3','4') and P.createTime like "$DATE%" and isTemp = 0 and P.ExaStatus = '完成' and signinType <> 'service' group by P.orderNatureName

union all
/*实施中的合同数，合同金额*/
select '产品销售合同' as orderType,caO.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,
0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,count(caO.id) as beingNum,
sum(IF((caO.orderMoney <> 0 or caO.orderMoney is not null or caO.orderMoney <> ''),caO.orderTempMoney,caO.orderMoney)) as beingMoney,0 as closeNum,
0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order caO where caO.state in ('2','4') and caO.createTime like "$DATE%" and isTemp = 0 and caO.ExaStatus = '完成' and signinType <> 'service' group by caO.orderNatureName

union all
/*关闭的合同数，合同金额*/
select '产品销售合同' as orderType,clO.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,
0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,count(clO.id) as closeNum,
sum(clO.orderMoney) as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order clO where clO.state in ('3') and clO.createTime like "$DATE%" and isTemp = 0 and clO.ExaStatus = '完成' and signinType <> 'service' group by clO.orderNatureName
/*服务合同转销售*/
union all
/* 新增合同数 , 签约合同金额  */
select '产品销售合同' as orderType,a.orderNatureName,count(a.id) as addNum,sum(a.orderMoney) as orderMoney,0 as tempNum, 0 as orderTempMoney,
0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service a where a.orderMoney is not null and a.orderMoney <> '' and a.orderMoney <> '0' and a.state in ('2','3','4') and a.createTime like "$DATE%" and isTemp = 0 and a.ExaStatus = '完成' and signinType = 'order' group by a.orderNatureName

union all
/* 临时合同数 , 临时合同金额 */
select '产品销售合同' as orderType,b.orderNatureName,0 as addNum, 0 as orderMoney,count(b.id) as tempNum, sum(b.orderTempMoney) as orderTempMoney,0 as chanceMum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service b where (b.orderCode is null or b.orderCode = '') and b.state in ('2','3','4') and b.createTime like "$DATE%" and isTemp = 0 and b.ExaStatus = '完成' and signinType = 'order' group by b.orderNatureName

union all
/*实施中的合同数，合同金额*/
select '产品销售合同' as orderType,caS.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,count(caS.id) as beingNum,sum(IF((caS.orderMoney <> 0 or caS.orderMoney is not null or caS.orderMoney <> ''),caS.orderTempMoney,caS.orderMoney)) as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service caS where caS.state in ('2','4') and caS.createTime like "$DATE%" and isTemp = 0 and caS.ExaStatus = '完成' and signinType = 'order' group by caS.orderNatureName

union all
/*关闭的合同数，合同金额*/
select '产品销售合同' as orderType,clS.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,count(clS.id) as closeNum,sum(clS.orderMoney) as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service clS where clS.state in ('3') and clS.createTime like "$DATE%" and isTemp = 0 and clS.ExaStatus = '完成' and signinType = 'order' group by clS.orderNatureName

union all
/*当月到款金额*/
select '产品销售合同' as orderType,inc.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,sum(inc.incomeMoney) as incomeMoney,0 as invoiceMoney

from (

select * from oa_sale_order o left join (
select a.objId,a.objType,sum(a.money) as incomeMoney,0 as invoiceMoney from oa_finance_income i
 inner join financeview_income_allot a on i.id = a.incomeId where a.objId <> 0 and date_format(i.incomeDate,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by a.objId,a.objType
)rs on o.id=rs.objId and rs.objType = 'oa_sale_order'

)inc

where  inc.createTime like "$DATE%" and isTemp = 0 group by inc.orderNatureName

union all
/*当月开票金额*/
select '产品销售合同' as orderType,inv.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,sum(inv.invoiceMoney) as invoiceMoney

from (

select * from oa_sale_order o left join (
select objId,objType,sum(invoiceMoney) as invoiceMoney from financeview_invoice where date_format(invoiceTime,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by objId,objType
)rt on o.id=rt.objId and rt.objType = 'oa_sale_order'

)inv

where  inv.createTime like "$DATE%" and isTemp = 0 group by inv.orderNatureName


union all
 /*新增商机数，商机金额  */
select '产品销售合同' as orderType,c.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,count(c.id) as chanceNum,
sum(c.chanceMoney) as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance c where c.chanceType = 'SJLX-XSXS' and c.createTime like "$DATE%"  group by c.orderNatureName

union all
 /*跟踪中的商机数，跟踪中的商机金额  */
select '产品销售合同' as orderType,f.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,
count(f.id) as followChance, sum(f.chanceMoney) as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance f where f.status in (0,5) and f.chanceType = 'SJLX-XSXS' and f.createTime like "$DATE%"  group by f.orderNatureName

union all
 /*商机转合同数量，商机转合同金额  */
select '产品销售合同' as orderType,s.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,0 as chanceMoney,
0 as followChance,0 as followChanceMoney,count(s.id) as shiftOrder,sum(s.chanceMoney) as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,
0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance s where s.status = '4'  and s.chanceType = 'SJLX-XSXS' and s.createTime like "$DATE%"  group by s.orderNatureName

 union all
 /*合同属性*/
select '产品销售合同' as orderType,sys.dataName as orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,
0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,
0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_system_datadict sys where sys.parentCode = 'XSHTSX' group by sys.dataName
/* 销售合同部分 结束*/

/* 服务合同部分 开始*/
union all
/* 新增合同数 , 签约合同金额  */
select '工程服务合同' as orderType,a.orderNatureName,count(a.id) as addNum,sum(a.orderMoney) as orderMoney,0 as tempNum, 0 as orderTempMoney,
0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service a where a.orderMoney is not null and a.orderMoney <> '' and a.orderMoney <> '0' and a.state in ('2','3','4') and a.createTime like "$DATE%" and isTemp = 0 and a.ExaStatus = '完成' and signinType <> 'order' group by a.orderNatureName

union all
/* 临时合同数 , 临时合同金额 */
select '工程服务合同' as orderType,b.orderNatureName,0 as addNum, 0 as orderMoney,count(b.id) as tempNum, sum(b.orderTempMoney) as orderTempMoney,0 as chanceMum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service b where (b.orderCode is null or b.orderCode = '') and b.state in ('2','3','4') and b.createTime like "$DATE%" and isTemp = 0 and b.ExaStatus = '完成' and signinType <> 'order' group by b.orderNatureName

union all
/*实施中的合同数，合同金额*/
select '工程服务合同' as orderType,caS.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,count(caS.id) as beingNum,sum(IF((caS.orderMoney <> 0 or caS.orderMoney is not null or caS.orderMoney <> ''),caS.orderTempMoney,caS.orderMoney)) as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service caS where caS.state in ('2','4') and caS.createTime like "$DATE%" and isTemp = 0 and caS.ExaStatus = '完成' and signinType <> 'order' group by caS.orderNatureName

union all
/*关闭的合同数，合同金额*/
select '工程服务合同' as orderType,clS.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,count(clS.id) as closeNum,sum(clS.orderMoney) as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_service clS where clS.state in ('3') and clS.createTime like "$DATE%" and isTemp = 0 and clS.ExaStatus = '完成' and signinType <> 'order' group by clS.orderNatureName
/*销售合同转服务*/
union all
select '工程服务合同' as orderType,N.orderNatureName,count(N.id) as addNum,  sum(N.orderMoney) as orderMoney,0 as tempNum, 0 as orderTempMoney,0 as chanceNum,
0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order N  where N.orderMoney is not null and N.orderMoney <> '' and N.orderMoney <> '0' and N.state in ('2','3','4') and N.createTime like "$DATE%" and isTemp = 0 and N.ExaStatus = '完成'  and signinType = 'service' group by N.orderNatureName
union all
/* 临时合同数 , 临时合同金额 */
select '工程服务合同' as orderType,P.orderNatureName,0 as addNum, 0 as orderMoney,count(P.id) as tempNum, sum(p.orderTempMoney) as orderTempMoney,
0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,
0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order P where (P.orderCode is null or P.orderCode = '' ) and P.state in ('2','3','4') and P.createTime like "$DATE%" and isTemp = 0 and P.ExaStatus = '完成' and signinType = 'service' group by P.orderNatureName

union all
/*实施中的合同数，合同金额*/
select '工程服务合同' as orderType,caO.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,
0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,count(caO.id) as beingNum,
sum(IF((caO.orderMoney <> 0 or caO.orderMoney is not null or caO.orderMoney <> ''),caO.orderTempMoney,caO.orderMoney)) as beingMoney,0 as closeNum,
0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order caO where caO.state in ('2','4') and caO.createTime like "$DATE%" and isTemp = 0 and caO.ExaStatus = '完成' and signinType = 'service' group by caO.orderNatureName

union all
/*关闭的合同数，合同金额*/
select '工程服务合同' as orderType,clO.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,
0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,count(clO.id) as closeNum,
sum(clO.orderMoney) as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_order clO where clO.state in ('3') and clO.createTime like "$DATE%" and isTemp = 0 and clO.ExaStatus = '完成' and signinType = 'service' group by clO.orderNatureName

union all
/*当月到款金额*/
select '工程服务合同' as orderType,inc1.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,sum(inc1.incomeMoney) as incomeMoney,0 as invoiceMoney

from (

select * from oa_sale_service o left join (
select a.objId,a.objType,sum(a.money) as incomeMoney,0 as invoiceMoney from oa_finance_income i
 inner join financeview_income_allot a on i.id = a.incomeId where a.objId <> 0 and date_format(i.incomeDate,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by a.objId,a.objType
)rs1 on o.id=rs1.objId and rs1.objType = 'oa_sale_service'

)inc1

where  inc1.createTime like "$DATE%" and isTemp = 0 group by inc1.orderNatureName

union all
/*当月开票金额*/
select '工程服务合同' as orderType,inv1.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,sum(inv1.invoiceMoney) as invoiceMoney

from (

select * from oa_sale_service o left join (
select objId,objType,sum(invoiceMoney) as invoiceMoney from financeview_invoice where date_format(invoiceTime,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by objId,objType
)rt1 on o.id=rt1.objId and rt1.objType = 'oa_sale_service'

)inv1

where  inv1.createTime like "$DATE%" and isTemp = 0 group by inv1.orderNatureName

union all
 /*新增商机数，商机金额  */
select '工程服务合同' as orderType,e.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,count(e.id) as chanceNum,sum(e.chanceMoney) as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance e where e.chanceType = 'SJLX-FWXM' and e.createTime like "$DATE%"  group by e.orderNatureName

union all
 /*跟踪中的商机数，跟踪中的商机金额  */
select '工程服务合同' as orderType,d.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney, count(d.id) as followChance, sum(d.chanceMoney) as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance d where d.status in (0,5) and d.chanceType = 'SJLX-FWXM' and d.createTime like "$DATE%" group by d.orderNatureName

union all
 /*商机转合同数量，商机转合同金额  */
select '工程服务合同' as orderType,g.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,count(g.id) as shiftOrder,sum(g.chanceMoney) as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance g where g.status = '4'  and g.chanceType = 'SJLX-FWXM' and g.createTime like "$DATE%" group by g.orderNatureName

union all
/*合同属性*/
select '工程服务合同' as orderType,sys1.dataName as orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_system_datadict sys1 where sys1.parentCode = 'FWHTSX' group by sys1.dataName

/* 服务合同部分 结束*/

/* 研发合同部分 开始*/
union all
/* 新增合同数 , 签约合同金额  */
select '委托开发合同' as orderType,m.orderNatureName,count(m.id) as addNum,sum(m.orderMoney) as orderMoney,0 as tempNum, 0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_rdproject m where m.orderMoney is not null and m.orderMoney <> '' and m.orderMoney <> '0' and m.state in ('2','3','4') and createTime like "$DATE%" and isTemp = 0 and m.ExaStatus = '完成' group by m.orderNatureName

union all
/* 临时合同数 , 临时合同金额 */
select '委托开发合同' as orderType,q.orderNatureName,0 as addNum, 0 as orderMoney,count(q.id) as tempNum, sum(q.orderTempMoney) as orderTempMoney,0 as chanceMum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_rdproject q where (q.orderCode is null or q.orderCode = '') and q.state in ('2','3','4') and createTime like "$DATE%" and isTemp = 0 and q.ExaStatus = '完成' group by q.orderNatureName

union all
/*实施中的合同数，合同金额*/
select '委托开发合同' as orderType,caL.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,count(caL.id) as beingNum,sum(IF((caL.orderMoney <> 0 or caL.orderMoney is not null or caL.orderMoney <> ''),caL.orderTempMoney,caL.orderMoney)) as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_rdproject caL where caL.state in ('2','4') and caL.createTime like "$DATE%" and isTemp = 0 and caL.ExaStatus = '完成' group by caL.orderNatureName

union all
/*关闭的合同数，合同金额*/
select '委托开发合同' as orderType,clR.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,count(clR.id) as closeNum,sum(clR.orderMoney) as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_rdproject clR where clR.state in ('3') and clR.createTime like "$DATE%" and isTemp = 0 and clR.ExaStatus = '完成' group by clR.orderNatureName

union all
/*当月到款金额*/
select '委托开发合同' as orderType,inc2.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,sum(inc2.incomeMoney) as incomeMoney,0 as invoiceMoney

from (

select * from oa_sale_rdproject o left join (
select a.objId,a.objType,sum(a.money) as incomeMoney,0 as invoiceMoney from oa_finance_income i
 inner join financeview_income_allot a on i.id = a.incomeId where a.objId <> 0 and date_format(i.incomeDate,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by a.objId,a.objType
)rs2 on o.id=rs2.objId and rs2.objType = 'oa_sale_rdproject'

)inc2

where  inc2.createTime like "$DATE%" and isTemp = 0 group by inc2.orderNatureName

union all
/*当月开票金额*/
select '委托开发合同' as orderType,inv2.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,sum(inv2.invoiceMoney) as invoiceMoney

from (

select * from oa_sale_rdproject o left join (
select objId,objType,sum(invoiceMoney) as invoiceMoney from financeview_invoice where date_format(invoiceTime,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by objId,objType
)rt2 on o.id=rt2.objId and rt2.objType = 'oa_sale_rdproject'

)inv2

where  inv2.createTime like "$DATE%" and isTemp = 0 group by inv2.orderNatureName

union all
 /*新增商机数，商机金额  */
select '委托开发合同' as orderType,x.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,count(x.id) as chanceNum,sum(x.chanceMoney) as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance x where x.chanceType = 'SJLX-YF' and x.createTime like "$DATE%" group by x.orderNatureName

union all
 /*跟踪中的商机数，跟踪中的商机金额  */
select '委托开发合同' as orderType,y.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney, count(y.id) as followChance, sum(y.chanceMoney) as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance y where y.status in (0,5) and y.chanceType = 'SJLX-YF' and y.createTime like "$DATE%" group by y.orderNatureName

union all
 /*商机转合同数量，商机转合同金额  */
select '委托开发合同' as orderType,z.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,count(z.id) as shiftOrder,sum(z.chanceMoney) as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance z where z.status = '4'  and z.chanceType = 'SJLX-YF' and z.createTime like "$DATE%" group by z.orderNatureName

union all
/*合同属性*/
select '委托开发合同' as orderType,sys2.dataName as orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_system_datadict sys2 where sys2.parentCode = 'YFHTSX' group by sys2.dataName
/* 研发合同部分 结束*/

/* 租赁合同部分 开始*/
union all
/* 新增合同数 , 签约合同金额  */
select '租赁合同' as orderType,h.orderNatureName,count(h.id) as addNum,sum(h.orderMoney) as orderMoney,0 as tempNum, 0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_lease h where h.orderMoney is not null and h.orderMoney <> '' and h.orderMoney <> '0' and h.state in ('2','3','4') and h.createTime like "$DATE%" and isTemp = 0 and h.ExaStatus = '完成' group by h.orderNatureName

union all
/* 临时合同数 , 临时合同金额 */
select '租赁合同' as orderType,i.orderNatureName,0 as addNum, 0 as orderMoney,count(i.id) as tempNum, sum(i.orderTempMoney) as orderTempMoney,0 as chanceMum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_lease i where (i.orderCode is null or i.orderCode = '') and i.state in ('2','3','4') and i.createTime like "$DATE%" and isTemp = 0 and i.ExaStatus = '完成' group by i.orderNatureName

union all
/*实施中的合同数，合同金额*/
select '租赁合同' as orderType,caR.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,count(caR.id) as beingNum,sum(IF((caR.orderMoney <> 0 or caR.orderMoney is not null or caR.orderMoney <> ''),caR.orderTempMoney,caR.orderMoney)) as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_lease caR where caR.state in ('2','4') and caR.createTime like "$DATE%" and isTemp = 0 and caR.ExaStatus = '完成' group by caR.orderNatureName

union all
/*关闭的合同数，合同金额*/
select '租赁合同' as orderType,clL.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,count(clL.id) as closeNum,sum(clL.orderMoney) as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_lease clL where clL.state in ('3') and clL.createTime like "$DATE%" and isTemp = 0 and clL.ExaStatus = '完成' group by clL.orderNatureName

union all
/*当月到款金额*/
select '租赁合同' as orderType,inc3.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,sum(inc3.incomeMoney) as incomeMoney,0 as invoiceMoney

from (

select * from oa_sale_lease o left join (
select a.objId,a.objType,sum(a.money) as incomeMoney,0 as invoiceMoney from oa_finance_income i
 inner join financeview_income_allot a on i.id = a.incomeId where a.objId <> 0 and date_format(i.incomeDate,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by a.objId,a.objType
)rs3 on o.id=rs3.objId and rs3.objType = 'oa_sale_lease'

)inc3

where  inc3.createTime like "$DATE%" and isTemp = 0 group by inc3.orderNatureName

union all
/*当月开票金额*/
select '租赁合同' as orderType,inv3.orderNatureName,0 as addNum, 0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,
0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,sum(inv3.invoiceMoney) as invoiceMoney

from (

select * from oa_sale_lease o left join (
select objId,objType,sum(invoiceMoney) as invoiceMoney from financeview_invoice where date_format(invoiceTime,"%Y%m") = date_format("$DATE.'-1'","%Y%m") group by objId,objType
)rt3 on o.id=rt3.objId and rt3.objType = 'oa_sale_lease'

)inv3

where  inv3.createTime like "$DATE%" and isTemp = 0 group by inv3.orderNatureName

union all
 /*新增商机数，商机金额  */
select '租赁合同' as orderType,j.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,count(j.id) as chanceNum,sum(j.chanceMoney) as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance j where j.chanceType = 'SJLX-ZL' and j.createTime like "$DATE%" group by j.orderNatureName

union all
 /*跟踪中的商机数，跟踪中的商机金额  */
select '租赁合同' as orderType,k.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney,0 as chanceNum,0 as chanceMoney, count(k.id) as followChance, sum(k.chanceMoney) as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance k where k.status in (0,5) and k.chanceType = 'SJLX-ZL' and k.createTime like "$DATE%" group by k.orderNatureName

union all
 /*商机转合同数量，商机转合同金额  */
select '租赁合同' as orderType,l.orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,count(l.id) as shiftOrder,sum(l.chanceMoney) as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_sale_chance l where l.status = '4'  and l.chanceType = 'SJLX-ZL' and l.createTime like "$DATE%" group by l.orderNatureName

union all
/*合同属性*/
select '租赁合同' as orderType,sys3.dataName as orderNatureName,0 as addNum,0 as orderMoney,0 as tempNum,0 as orderTempMoney, 0 as chanceNum,0 as chanceMoney,0 as followChance,0 as followChanceMoney,0 as shiftOrder,0 as shiftOrderMoney,0 as beingNum,0 as beingMoney,0 as closeNum,0 as closeMoney,0 as incomeMoney,0 as invoiceMoney from oa_system_datadict sys3 where sys3.parentCode = 'ZLHTSX' group by sys3.dataName

/* 租赁合同部分 结束*/

) rs
group by rs.orderNatureName ,rs.orderType order by rs.orderType,rs.orderNatureName desc
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>

