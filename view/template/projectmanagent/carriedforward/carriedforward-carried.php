<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$QuerySQL = <<<QuerySQL
select
o.customerName,if(o.sign='ÊÇ',o.orderCode,orderTempCode) as contractCode,o.contractMoney,d.dataName as invoiceType , invThisMonth.invoiceMoney as invMoneyThisMonth, invAll.invoiceMoney as invAllMoney,
o.contractMoney -  invAll.invoiceMoney as unContractMoney,invAll.psType,outSub.docDate,outSub.docCode,outSub.outMoney

from invoice_order_view o

left join
oa_system_datadict d on o.invoiceType = d.dataCode

left join

(SELECT
c.objId,
c.objType,
sum(c.invoiceMoney) as invoiceMoney,
case  c.objType
    when 'KPRK-01' then 'oa_sale_order'
    when 'KPRK-02' then 'oa_sale_order'
    when 'KPRK-03' then 'oa_sale_service'
    when 'KPRK-04' then 'oa_sale_service'
    when 'KPRK-05' then 'oa_sale_lease'
    when 'KPRK-06' then 'oa_sale_lease'
    when 'KPRK-07' then 'oa_sale_rdproject'
    else 'oa_sale_rdproject' end as orderObjType
from financeView_invoice_detail c where year(c.invoiceTime) = 2011 and month(c.invoiceTime) = 7 group by c.objId,c.objType
) invThisMonth on o.orgId = invThisMonth.objId and o.thisObjName = invThisMonth.orderObjType

left join

(SELECT
c.objId,
c.objType,
sum(c.invoiceMoney) as invoiceMoney,
case  c.objType
    when 'KPRK-01' then 'oa_sale_order'
    when 'KPRK-02' then 'oa_sale_order'
    when 'KPRK-03' then 'oa_sale_service'
    when 'KPRK-04' then 'oa_sale_service'
    when 'KPRK-05' then 'oa_sale_lease'
    when 'KPRK-06' then 'oa_sale_lease'
    when 'KPRK-07' then 'oa_sale_rdproject'
    else 'oa_sale_rdproject' end as orderObjType,
group_concat(c.psType) as psType
from financeView_invoice_detail c group by c.objId,c.objType
) invAll on o.orgId = invAll.objId and o.thisObjName = invAll.orderObjType

left join
(select
group_concat(CAST(sub.docDate as char)) as docDate ,group_concat(sub.docCode) as docCode , sum(sub.subCost) as outMoney,sub.contractId
from
    (select innerSub.subCost, innerSub.docCode, innerSub.docDate, innerSub.contractId from oa_sale_carriedforward ca left join
        (select  it.id,'oa_sale_order' as objType,ot.auditDate as docDate,ot.docCode,sum(it.subCost) as subCost,
            ot.contractId  as contractId
        from
            oa_stock_outstock ot  inner join  oa_stock_outstock_item it  on(it.mainId=ot.id) where ot.docStatus = 'YSH' and ot.docType= 'CKSALES' and ot.contractId is not null group by  it.id ) innerSub
    on ca.outStockId = innerSub.id ) sub
    group  by sub.contractId
) outSub on o.orgId = outSub.contractId

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
