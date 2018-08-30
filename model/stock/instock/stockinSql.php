<?php
/**
 * @author Administrator
 * @Date 2011��5��9�� 22:10:00
 * @version 1.0
 * @description:��ⵥ������Ϣ sql�����ļ� 1.��ⵥ���ͣ�A.�вɹ����
 * B.��Ʒ���
 * C.�������
 * 2.����״̬��  δ��ˡ������
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
        "c.purOrderId, if(iv.actNum>0 , 'δ¼'  ,'��¼' ) as invoiceStatus from oa_stock_instock c left join (select d.mainId,sum(d.actNum)  as actNum from " .
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
    array(//����״̬
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
    array(//�������� - ��
        "name" => "thisYear",
        "sql" => " and year(c.auditDate)=# "
    ),
    array(// �������� - ��
        "name" => "thisMonth",
        "sql" => " and month(c.auditDate)=# "
    ),
    array(//�������� - ��ʼ����
        "name" => "beginDate",
        "sql" => " and c.auditDate >= # "
    ),
    array(// �������� - ��������
        "name" => "endDate",
        "sql" => " and c.auditDate <= # "
    ),
    array(// ����id
        "name" => "productId",
        "sql" => " and c.id in(select i.mainId from oa_stock_instock_item i where i.productId =# )"
    ),
    array(// ����id
        "name" => "iProductId",
        "sql" => " and i.productId =# "
    ),
    array(// ��������
        "name" => "productName",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.productName like CONCAT('%',#,'%')) "
    ),
    array(// ���ϴ���
        "name" => "productCode",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.productCode like CONCAT('%',#,'%')) "
    ),
    array(//���Ϲ���ͺ�
        "name" => "pattern",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.pattern =# ) "
    ),
    array(//���Ϲ���ͺ�
        "name" => "iPattern",
        "sql" => " and i.pattern like CONCAT('%',#,'%') "
    ),
    array(//�������к�
        "name" => "serialnoName",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.serialnoName like CONCAT('%',#,'%'))"
    ),
    array(//�������κ�
        "name" => "batchNum",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.batchNum like CONCAT('%',#,'%')) "
    ),
    array(//���ϲֿ�id
        "name" => "itemInStockId",
        "sql" => " and  c.id in(select i.mainId from oa_stock_instock_item i where i.inStockId =# )"
    ),
    array(//���ϲֿ�id
        "name" => "iInStockId",
        "sql" => " and i.inStockId =# "
    ),
    array(//����
        "name" => "iActNum",
        "sql" => " and i.actNum =# "
    ),
    array(//����
        "name" => "iPrice",
        "sql" => " and round(i.price,2) =# "
    ),
    array(//���
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
    array(//�������ݲ鿴�������
        "name" => "exchangeId",
        "sql" => " and c.relDocId in (select id from oa_stock_innotice ie where ie.docType = 'oa_contract_exchange' and ie.docId = # ) "
    )
)
?>