<?php
/**
 * @author Administrator
 * @Date 2011年5月9日 22:10:00
 * @version 1.0
 * @description:入库单基本信息 sql配置文件 1.入库单类型：A.有采购入库
 * B.产品入库
 * C.其它入库
 * 2.单据状态：  未审核、已审核
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.docCode ,c.docType ,c.isRed ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ,c.contractId ,c.contractCode ,c.contractName ,c.contractType ,c.contractObjCode,c.inStockId ,c.inStockCode ,c.inStockName ,c.supplierId ,c.supplierName ,c.clientId ,c.clientName ,c.purchMethod ,c.payDate ,c.accountingCode ,c.docStatus ,c.catchStatus ,c.remark ,c.purchaserName ,c.purchaserCode ,c.acceptorName ,c.acceptorCode ,c.chargeName ,c.chargeCode ,c.custosName ,c.custosCode ,c.auditerName ,c.auditerCode ,c.auditDate ,c.accounterName ,c.accounterCode ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.purOrderCode,c.purOrderId  from oa_stock_instock c where 1=1 ",
    "select_listgrid" => "select  c.id ,c.docCode ,c.docType ,c.isRed ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ," .
        "c.contractId ,c.contractCode ,c.contractName ,c.contractType ,c.contractObjCode,c.inStockId ,c.inStockCode ,c.inStockName ," .
        "c.supplierId ,c.supplierName ,c.clientId ,c.clientName ,c.purchMethod ,c.payDate ,c.accountingCode ,c.docStatus ," .
        "c.catchStatus ,c.remark ,c.purchaserName ,c.purchaserCode ,c.acceptorName ,c.acceptorCode ,c.chargeName ," .
        "c.chargeCode ,c.custosName ,c.custosCode ,c.auditerName ,c.auditerCode ,c.auditDate ,c.accounterName ," .
        "c.accounterCode ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.purOrderCode," .
        "c.purOrderId  " .
        "from oa_stock_instock c  where 1=1 ",
    "select_purchgrid" => "select  c.id ,c.docCode ,c.docType ,c.isRed ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ," .
        "c.contractId ,c.contractCode ,c.contractName ,c.contractType,c.contractObjCode ,c.inStockId ,c.inStockCode ,c.inStockName ," .
        "c.supplierId ,c.supplierName ,c.clientId ,c.clientName ,c.purchMethod ,c.payDate ,c.accountingCode ,c.docStatus ," .
        "c.catchStatus ,c.remark ,c.purchaserName ,c.purchaserCode ,c.acceptorName ,c.acceptorCode ,c.chargeName ," .
        "c.chargeCode ,c.custosName ,c.custosCode ,c.auditerName ,c.auditerCode ,c.auditDate ,c.accounterName ," .
        "c.accounterCode ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.purOrderCode," .
        "c.purOrderId, if(iv.actNum>0 , '未录'  ,'已录' ) as invoiceStatus from oa_stock_instock c left join (select d.mainId,sum(d.actNum)  as actNum from " .
        "(select i.mainId,i.actNum from oa_stock_instock_item i union all select iv.objId as mainId, (- iv.number) as actNum " .
        "from oa_finance_invpurchase_detail iv where iv.objId is not null and iv.objId <> 0 and iv.objType = 'CGFPYD-02') d " .
        "group by d.mainId)iv on(iv.mainId=c.id)  where 1=1 ",
    "select_callist" => "select c.id as mainId,c.docCode,c.docType,c.docStatus,c.catchStatus,c.isRed,c.supplierId ," .
        "c.supplierName,c.purchaserName,c.purchaserCode,c.clientName,c.auditDate,c.auditerName,c.createName,c.remark,i.inStockName,i.productId,i.productName,i.productCode,i.unitName,i.id,i.price,i.pattern," .
        "if(c.isRed = 0, i.actNum , -i.actNum ) as actNum,if(c.isRed = 0, i.subPrice , -i.subPrice ) as subPrice," .
        "if(c.isRed = 0, i.unHookNumber , -i.unHookNumber ) as unHookNumber,if(c.isRed = 0, i.unHookAmount , -i.unHookAmount ) as unHookAmount," .
        "if(c.isRed = 0, i.hookNumber , -i.hookNumber ) as hookNumber,if(c.isRed = 0, i.hookAmount , -i.hookAmount ) as hookAmount " .
        "from oa_stock_instock c left join oa_stock_instock_item i on c.id = i.mainId where 1=1 ",
    "select_count" => "select count(distinct c.id) as countNum   from oa_stock_instock c left join oa_stock_instock_item i on(i.mainId=c.id)  where 1=1 ",
    "select_item" => "select c.id ,c.docCode ,c.docType ,c.isRed ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ,c.contractId ,c.contractCode ,c.contractName ,c.contractType ,c.inStockId ,c.inStockCode ,c.inStockName ,c.supplierId ,c.supplierName ,c.clientId ,c.clientName ,c.purchMethod ,c.payDate ,c.accountingCode ,c.docStatus ,c.catchStatus ,c.remark ,c.purchaserName ,c.purchaserCode ,c.acceptorName ,c.acceptorCode ,c.chargeName ,c.chargeCode ,c.custosName ,c.custosCode ,c.auditerName ,c.auditerCode ,c.auditDate ,c.accounterName ,c.accounterCode ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.purOrderCode,c.purOrderId,ci.*  from oa_stock_instock c left join oa_stock_instock_item ci on(ci.mainId=c.id) where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "ids",
        "sql" => " and c.id in($) "
    ),
    array(
        "name" => "docCode",
        "sql" => " and c.docCode like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "nDocCode",
        "sql" => " and c.docCode =# "
    ),
    array(
        "name" => "docType",
        "sql" => " and c.docType in(arr)"
    ),
    array(
        "name" => "relDocId",
        "sql" => " and c.relDocId=# "
    ),
    array(
        "name" => "relDocCode",
        "sql" => " and c.relDocCode like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "relDocName",
        "sql" => " and c.relDocName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "relDocType",
        "sql" => " and c.relDocType=# "
    ),
    array(
        "name" => "contractId",
        "sql" => " and c.contractId=# "
    ),
    array(
        "name" => "contractCode",
        "sql" => " and c.contractCode like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "contractName",
        "sql" => " and c.contractName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "inStockId",
        "sql" => " and c.inStockId=# "
    ),
    array(
        "name" => "inStockCode",
        "sql" => " and c.inStockCode like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "inStockName",
        "sql" => " and c.inStockName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "supplierId",
        "sql" => " and c.supplierId=# "
    ),
    array(
        "name" => "supplierName",
        "sql" => " and c.supplierName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "clientId",
        "sql" => " and c.clientId=# "
    ),
    array(
        "name" => "clientName",
        "sql" => " and c.clientName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "purchMethod",
        "sql" => " and c.purchMethod=# "
    ),
    array(
        "name" => "payDate",
        "sql" => " and c.payDate=# "
    ),
    array(
        "name" => "accountingCode",
        "sql" => " and c.accountingCode=# "
    ),
    array(
        "name" => "docStatus",
        "sql" => " and c.docStatus=# "
    ),
    array(//钩稽状态
        "name" => "catchStatusIn",
        "sql" => " and c.catchStatus in(arr) "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark like CONCAT('%',#,'%') "
    ),
    array (
        "name" => "remarkSearch",
        "sql" => " and c.remark like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "purchaserName",
        "sql" => " and c.purchaserName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "purchaserCode",
        "sql" => " and c.purchaserCode=# "
    ),
    array(
        "name" => "purchOrderIds",
        "sql" => " and c.purOrderId in($) "
    ),
    array(
        "name" => "acceptorName",
        "sql" => " and c.acceptorName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "acceptorCode",
        "sql" => " and c.acceptorCode=# "
    ),
    array(
        "name" => "chargeName",
        "sql" => " and c.chargeName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "chargeCode",
        "sql" => " and c.chargeCode=# "
    ),
    array(
        "name" => "custosName",
        "sql" => " and c.custosName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "custosCode",
        "sql" => " and c.custosCode=# "
    ),
    array(
        "name" => "auditerName",
        "sql" => " and c.auditerName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "auditerCode",
        "sql" => " and c.auditerCode=# "
    ),
    array(
        "name" => "isRed",
        "sql" => " and c.isRed = # "
    ),
    array(
        "name" => "auditDate",
        "sql" => " and c.auditDate=# "
    ),
    array(
        "name" => "accounterName",
        "sql" => " and c.accounterName=# "
    ),
    array(
        "name" => "accounterCode",
        "sql" => " and c.accounterCode=# "
    ),
    array(
        "name" => "createName",
        "sql" => " and c.createName=# "
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime=# "
    ),
    array(
        "name" => "updateName",
        "sql" => " and c.updateName=# "
    ),
    array(
        "name" => "updateId",
        "sql" => " and c.updateId=# "
    ),
    array(
        "name" => "updateTime",
        "sql" => " and c.updateTime=# "
    ),
    array(
        "name" => "dAuditDate",
        "sql" => " and c.auditDate >=# "
    ),
    array(
        "name" => "xAuditDate",
        "sql" => " and c.auditDate <=# "
    ),
    array(
        "name" => "catchStatusNo",
        "sql" => " and c.catchStatus <># "
    ),
    array(
        "name" => "catchStatus",
        "sql" => " and c.catchStatus = # "
    ),
    array(//单据日期 - 年
        "name" => "thisYear",
        "sql" => " and year(c.auditDate)=# "
    ),
    array(// 单据日期 - 月
        "name" => "thisMonth",
        "sql" => " and month(c.auditDate)=# "
    ),
    array(//单据日期 - 开始日期
        "name" => "beginDate",
        "sql" => " and c.auditDate >= # "
    ),
    array(// 单据日期 - 结束日期
        "name" => "endDate",
        "sql" => " and c.auditDate <= # "
    ),
    array(// 物料id
        "name" => "productId",
        "sql" => " and c.id in(select i.mainId from oa_stock_instock_item i where i.productId =# )"
    ),
    array(// 物料id
        "name" => "iProductId",
        "sql" => " and i.productId =# "
    ),
    array(// 物料名称
        "name" => "productName",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.productName like CONCAT('%',#,'%')) "
    ),
    array(// 物料代码
        "name" => "productCode",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.productCode like CONCAT('%',#,'%')) "
    ),
    array(//物料规格型号
        "name" => "pattern",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.pattern =# ) "
    ),
    array(//物料规格型号
        "name" => "iPattern",
        "sql" => " and i.pattern like CONCAT('%',#,'%') "
    ),
    array(//物料序列号
        "name" => "serialnoName",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.serialnoName like CONCAT('%',#,'%'))"
    ),
    array(//物料批次号
        "name" => "batchNum",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.batchNum like CONCAT('%',#,'%')) "
    ),
    array(//收料仓库id
        "name" => "itemInStockId",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.inStockId =# )"
    ),
    array(//收料仓库id
        "name" => "iInStockId",
        "sql" => " and i.inStockId =# "
    ),
    array(//数量
        "name" => "iActNum",
        "sql" => " and i.actNum =# "
    ),
    array(//单价
        "name" => "iPrice",
        "sql" => " and round(i.price,2) =# "
    ),
    array(//金额
        "name" => "iSubPrice",
        "sql" => " and round(i.subPrice,2) =# "
    ),
    array(
        "name" => "done",
        "sql" => " and iv.actNum <= 0"
    ),
    array(
        "name" => "searchPurOrderCode",
        "sql" => "and c.purOrderCode like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "undo",
        "sql" => " and iv.actNum > 0"
    ),
    array(
        "name" => "noPrice",
        "sql" => " and c.id in (select mainId from oa_stock_instock_item i where i.price = 0 and i.mainId = c.id) "
    ),
    array(//换货单据查看其他入库
        "name" => "exchangeId",
        "sql" => " and c.relDocId in (select id from oa_stock_innotice ie where ie.docType = 'oa_contract_exchange' and ie.docId = # ) "
    )
)
?>