<?php
/**
 * @author Administrator
 * @Date 2011年5月21日 17:21:11
 * @version 1.0
 * @description:调拨单基本信息 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.docCode ,c.docType ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ,c.contractId ,c.contractName ,c.contractCode ,c.outStartDate ,c.outEndDate ,c.toUse ,c.customerName ,c.customerId ,c.linkmanId ,c.linkmanName ,c.exportStockId ,c.exportStockCode ,c.exportStockName ,c.importStockId ,c.importStockCode ,c.importStockName ,c.deptName ,c.deptCode ,c.docStatus ,c.remark ,c.pickName ,c.pickCode ,c.auditDate ,c.auditerCode ,c.auditerName ,c.custosCode ,c.custosName ,c.chargeCode ,c.chargeName ,c.acceptorCode ,c.acceptorName ,c.accounterCode ,c.accounterName ,c.updateId ,c.updateTime ,c.updateName ,c.createId ,c.createName ,c.createTime  from oa_stock_allocation c where 1=1 ",
    "select_listgrid" => "select c.id ,c.docCode ,c.docType ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ,c.contractId ,c.contractName ,c.contractCode ,c.outStartDate ,c.outEndDate ,c.toUse ,c.customerName ,c.customerId ,c.linkmanId ,c.linkmanName ,c.exportStockId ,c.exportStockCode ,c.exportStockName ,c.importStockId ,c.importStockCode ,c.importStockName ,c.deptName ,c.deptCode ,c.docStatus ,c.remark ,c.pickName ,c.pickCode ,c.auditDate ,c.auditerCode ,c.auditerName ,c.custosCode ,c.custosName ,c.chargeCode ,c.chargeName ,c.acceptorCode ,c.acceptorName ,c.accounterCode ,c.accounterName ,c.updateId ,c.updateTime ,c.updateName ,c.createId ,c.createName ,c.createTime  from oa_stock_allocation c where 1=1 ",
    "select_count" => "select count(distinct c.id)  as countNum  from oa_stock_allocation c left join oa_stock_allocation_item i on(i.mainId=c.id) where 1=1  ",
    //未归还物料导出
    "select_item" => "select ai.productCode,ai.productName,ai.productId,ai.pattern,ai.unitName,ai.allocatNum,
			ai.serialnoName,ai.serialnoId,ai.serialnoName,c.id ,c.docCode ,c.docType ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ,
			c.contractId ,c.contractName ,c.contractCode ,c.outStartDate ,c.outEndDate ,c.toUse ,c.customerName ,
			c.customerId ,c.linkmanId ,c.linkmanName ,c.exportStockId ,c.exportStockCode ,c.exportStockName ,
			c.importStockId ,c.importStockCode ,c.importStockName ,c.deptName ,c.deptCode ,c.docStatus ,c.remark ,
			c.pickName ,c.pickCode ,c.auditDate ,c.auditerCode ,c.auditerName ,c.custosCode ,c.custosName ,
			c.chargeCode ,c.chargeName ,c.acceptorCode ,c.acceptorName ,c.accounterCode ,c.accounterName ,c.updateId ,
			c.updateTime ,c.updateName ,c.createId ,c.createName ,c.createTime,ai.borrowItemId
		from oa_stock_allocation c  right join oa_stock_allocation_item ai on(ai.mainId=c.id)  where 1=1 ",
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id = # "
    ),
	array(
		"name" => "idNot",
		"sql" => " and c.id <> # "
	),
    array(
        "name" => "idArr",
        "sql" => " and c.id in(arr) "
    ),
    array(
        "name" => "docCode",
        "sql" => " and c.docCode like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "docType",
        "sql" => " and c.docType=# "
    ),
    array(
        "name" => "relDocId",
        "sql" => " and c.relDocId=# "
    ),
    array(
        "name" => "relDocIdArr",
        "sql" => " and c.relDocId in(arr) "
    ),
    array(
        "name" => "relDocCode",
        "sql" => " and c.relDocCode=# "
    ),
    array(
        "name" => "relDocName",
        "sql" => " and c.relDocName=# "
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
        "name" => "contractIdArr",
        "sql" => " and c.contractId in(arr) "
    ),
	array(
		"name" => "contractType",
		"sql" => " and c.contractType=# "
	),
    array(
        "name" => "contractTypeArr",
        "sql" => " and c.contractType in(arr) "
    ),
    array(
        "name" => "contractName",
        "sql" => " and c.contractName=# "
    ),
    array(
        "name" => "contractCode",
        "sql" => " and c.contractCode=# "
    ),
    array(
        "name" => "contractCodeLike",
        "sql" => " and c.contractCode like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "outStartDate",
        "sql" => " and c.outStartDate=# "
    ),
    array(
        "name" => "outEndDate",
        "sql" => " and c.outEndDate=# "
    ),
    array(
        "name" => "toUse",
        "sql" => " and c.toUse=# "
    ),
    array(
        "name" => "notToUse",
        "sql" => " and c.toUse !=# "
    ),
    array(
        "name" => "toUseArr",
        "sql" => " and c.toUse in(arr) "
    ),
    array(
        "name" => "customerName",
        "sql" => " and c.customerName=# "
    ),
    array(
        "name" => "customerId",
        "sql" => " and c.customerId=# "
    ),
    array(
        "name" => "linkmanId",
        "sql" => " and c.linkmanId=# "
    ),
    array(
        "name" => "linkmanName",
        "sql" => " and c.linkmanName=# "
    ),
    array(
        "name" => "exportStockId",
        "sql" => " and c.exportStockId=# "
    ),
    array(
        "name" => "exportStockCode",
        "sql" => " and c.exportStockCode=# "
    ),
    array(
        "name" => "exportStockName",
        "sql" => " and c.exportStockName=# "
    ),
    array(
        "name" => "importStockId",
        "sql" => " and c.importStockId=# "
    ),
    array(
        "name" => "importStockIdArr",
        "sql" => " and c.importStockId in(arr) "
    ),
    array(
        "name" => "importStockCode",
        "sql" => " and c.importStockCode=# "
    ),
    array(
        "name" => "importStockName",
        "sql" => " and c.importStockName=# "
    ),
    array(
        "name" => "deptName",
        "sql" => " and c.deptName=# "
    ),
    array(
        "name" => "deptCode",
        "sql" => " and c.deptCode=# "
    ),
    array(
        "name" => "docStatus",
        "sql" => " and c.docStatus=# "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=# "
    ),
    array(
        "name" => "pickName",
        "sql" => " and c.pickName=# "
    ),
    array(
        "name" => "pickCode",
        "sql" => " and c.pickCode=# "
    ),
    array(
        "name" => "auditDate",
        "sql" => " and c.auditDate=# "
    ),
    array(
        "name" => "auditerCode",
        "sql" => " and c.auditerCode=# "
    ),
    array(
        "name" => "auditerName",
        "sql" => " and c.auditerName=# "
    ),
    array(
        "name" => "custosCode",
        "sql" => " and c.custosCode=# "
    ),
    array(
        "name" => "custosName",
        "sql" => " and c.custosName=# "
    ),
    array(
        "name" => "chargeCode",
        "sql" => " and c.chargeCode=# "
    ),
    array(
        "name" => "chargeName",
        "sql" => " and c.chargeName=# "
    ),
    array(
        "name" => "acceptorCode",
        "sql" => " and c.acceptorCode=# "
    ),
    array(
        "name" => "acceptorName",
        "sql" => " and c.acceptorName=# "
    ),
    array(
        "name" => "accounterCode",
        "sql" => " and c.accounterCode=# "
    ),
    array(
        "name" => "accounterName",
        "sql" => " and c.accounterName=# "
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
        "name" => "updateName",
        "sql" => " and c.updateName=# "
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(
        "name" => "createName",
        "sql" => " and c.createName=# "
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime=# "
    ),
    array( //单据日期 - 开始日期
        "name" => "beginDate",
        "sql" => " and c.auditDate >= # "
    ),
    array( // 单据日期 - 结束日期
        "name" => "endDate",
        "sql" => " and c.auditDate <= # "
    ),
    array( // 物料名称
        "name" => "productName",
        "sql" => " and c.id in(select i.mainId from oa_stock_allocation_item i where i.productName like CONCAT('%',#,'%')) "
    ),
    array( // 物料编码
        "name" => "productCode",
        "sql" => " and c.id in(select i.mainId from oa_stock_allocation_item i where i.productCode like CONCAT('%',#,'%')) "
    ),
	array( // 物料K3编码
		"name" => "k3Code",
		"sql" => " and c.id in(select i.mainId from oa_stock_allocation_item i where i.k3Code like CONCAT('%',#,'%')) "
	),
    array( //物料规格型号
        "name" => "pattern",
        "sql" => " and  c.id in(select i.mainId from oa_stock_allocation_item i where i.pattern=# ) "
    ),
    array( //物料id
        "name" => "productId",
        "sql" => " and  c.id in(select i.mainId from oa_stock_allocation_item i where i.productId=# )"
    ),
    array( //物料影响仓库id
        "name" => "itemStockId",
        "sql" => " and  c.id in(select i.mainId from oa_stock_allocation_item i where i.importStockId =# or i.exportStockId=#  ) "
    ),
    array( //物料序列号
        "name" => "serialnoName",
        "sql" => " and c.id in(select i.mainId from oa_stock_allocation_item i where i.serialnoName like CONCAT('%',#,'%')) "
    )
)
?>