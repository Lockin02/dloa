<?php
/**
 * @author huangzf
 * @Date 2011年5月14日 9:44:17
 * @version 1.0
 * @description:出库单基本信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.isWarranty,c.isWarrantyName,c.docCode ,c.docType ,c.isRed ,c.relDocId ,c.relDocName ,c.relDocCode ,c.rObjCode,c.relDocType ,c.contractId ,c.contractName ,c.contractCode ,c.contractObjCode,c.contractType,c.contractObjCode,c.stockId ,c.stockCode ,c.stockName ,c.customerName ,c.customerId ,c.saleAddress ,c.linkmanId ,c.linkmanName ,c.linkmanTel ,c.pickingType ,c.deptName ,c.deptCode ,c.toUse ,c.outStartDate ,c.outEndDate ,c.salesmanCode ,c.salesmanName ,c.otherSubjects ,c.docStatus ,c.catchStatus ,c.remark ,c.pickName ,c.pickCode ,c.auditDate ,c.auditerCode ,c.auditerName ,c.custosCode ,c.custosName ,c.chargeCode ,c.chargeName ,c.acceptorCode ,c.acceptorName ,c.accounterCode ,c.accounterName ,c.createTime ,c.createName ,c.createId ,c.updateName ,c.updateTime ,c.updateId ,c.module ,c.moduleName from oa_stock_outstock c where 1=1 ",
	"select_listgrid" => "select  c.id ,c.isWarranty,c.isWarrantyName,c.docCode ,c.docType ,c.isRed ,c.relDocId ,c.relDocName ,c.relDocCode ,c.rObjCode,c.relDocType ,c.contractId ,c.contractName ,c.contractCode ,c.contractObjCode,c.contractType,c.contractObjCode,c.stockId ,c.stockCode ,c.stockName ,c.customerName ,c.customerId ,c.saleAddress ,c.linkmanId ,c.linkmanName ,c.linkmanTel ,c.pickingType ,c.deptName ,c.deptCode ,c.toUse ,c.outStartDate ,c.outEndDate ,c.salesmanCode ,c.salesmanName ,c.otherSubjects ,c.docStatus ,c.catchStatus ,c.remark ,c.pickName ,c.pickCode ,c.auditDate ,c.auditerCode ,c.auditerName ,c.custosCode ,c.custosName ,c.chargeCode ,c.chargeName ,c.acceptorCode ,c.acceptorName ,c.accounterCode ,c.accounterName ,c.createTime ,c.createName ,c.createId ,c.updateName ,c.updateTime ,c.updateId ,c.module ,c.moduleName from oa_stock_outstock c  where 1=1 ",
	"select_callist" => "select c.id as mainId,c.isWarranty,c.isWarrantyName,c.docCode,c.rObjCode,c.docType,c.docStatus,c.catchStatus,c.isRed,c.customerId ,c.customerName,c.contractId ,c.contractName ,c.contractCode ,c.deptName ,c.deptCode,c.pickName ,c.pickCode,c.auditDate,c.module ,c.moduleName ,c.toUse ,c.remark ,i.stockName ,i.productId,i.productName,i.productCode,i.unitName,i.id,i.cost,if(c.isRed = 0, i.actOutNum , -i.actOutNum ) as actOutNum ,if(c.isRed = 0, i.subCost , -i.subCost ) as subCost ,i.pattern ,i.serialnoName from oa_stock_outstock c left join oa_stock_outstock_item i on c.id = i.mainId where 1=1 ",
	"select_count" => "select count(distinct c.id) as countNum  from oa_stock_outstock c left join oa_stock_outstock_item i on(i.mainId=c.id) where 1=1 ",
	"select_subcost" => "select (select SUM(oi.`subCost`) from oa_stock_outstock_item `oi` where oi.mainId=c.id GROUP by oi.mainId )as subCost,c.id ,c.docCode ,c.docType ,c.isRed ,c.relDocId ,c.relDocName ,c.relDocCode ,c.relDocType ,c.contractId ,c.contractName ,c.contractCode ,c.contractType,c.stockId ,c.stockCode ,c.stockName ,c.customerName ,c.customerId ,c.saleAddress ,c.linkmanId ,c.linkmanName ,c.linkmanTel ,c.pickingType ,c.deptName ,c.deptCode ,c.toUse ,c.outStartDate ,c.outEndDate ,c.salesmanCode ,c.salesmanName ,c.otherSubjects ,c.docStatus ,c.catchStatus ,c.remark ,c.pickName ,c.pickCode ,c.auditDate ,c.auditerCode ,c.auditerName ,c.custosCode ,c.custosName ,c.chargeCode ,c.chargeName ,c.acceptorCode ,c.acceptorName ,c.accounterCode ,c.accounterName ,c.createTime ,c.createName ,c.createId ,c.updateName ,c.updateTime ,c.updateId  from oa_stock_outstock c where 1=1 ",
	"select_export" => "select c.isWarranty,c.isWarrantyName,c.docCode ,c.docType ,c.isRed ,c.relDocName ,c.relDocCode ,c.relDocType,c.contractName ,c.contractCode ,c.contractType,c.stockId ,c.stockCode ,c.stockName ,c.customerName ,c.saleAddress ,c.linkmanName ,c.linkmanTel ,c.pickingType ,c.deptName ,c.toUse ,c.outStartDate ,c.outEndDate ,c.salesmanName ,c.otherSubjects ,c.docStatus ,c.catchStatus ,c.remark ,c.pickName ,c.auditDate ,c.auditerName ,c.chargeName,c.accounterName,c.module ,c.moduleName,oi.*  from oa_stock_outstock c left join oa_stock_outstock_item oi on(oi.mainId=c.id)  where 1=1 ",
	"count_num" => "select i.productId,i.productCode,i.productName,sum(if(isRed = 0,actOutNum,-actOutNum)) as actOutNum from oa_stock_outstock c inner join oa_stock_outstock_item i on c.id = i.mainId where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "docCode",
		"sql" => " and c.docCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "docType",
		"sql" => " and c.docType=# "
	),
	array (
		"name" => "isRed",
		"sql" => " and c.isRed=# "
	),
	array (
		"name" => "relDocId",
		"sql" => " and c.relDocId=# "
	),
	array (
		"name" => "relDocIdArr",
		"sql" => " and c.relDocId in(arr)"
	),
	array (
		"name" => "relDocName",
		"sql" => " and c.relDocName=# "
	),
	array (
		"name" => "relDocCode",
		"sql" => " and c.relDocCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "relDocType",
		"sql" => " and c.relDocType=# "
	),
	array (
		"name" => "contractType",
		"sql" => " and c.contractType=# "
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array (
		"name" => "contractName",
		"sql" => " and c.contractName=# "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "contractCodeEq",
		"sql" => " and c.contractCode = # "
	),
	array (
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array (
		"name" => "stockCode",
		"sql" => " and c.stockCode=# "
	),
	array (
		"name" => "stockName",
		"sql" => " and c.stockName=# "
	),
	array (
		"name" => "stockNameLike",
		"sql" => " and c.stockName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName=# "
	),
	array (
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array (
		"name" => "saleAddress",
		"sql" => " and c.saleAddress=# "
	),
	array (
		"name" => "linkmanId",
		"sql" => " and c.linkmanId=# "
	),
	array (
		"name" => "linkmanName",
		"sql" => " and c.linkmanName=# "
	),
	array (
		"name" => "linkmanTel",
		"sql" => " and c.linkmanTel=# "
	),
	array (
		"name" => "pickingType",
		"sql" => " and c.pickingType=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptCode",
		"sql" => " and c.deptCode=# "
	),
	array (
		"name" => "toUse",
		"sql" => " and c.toUse=# "
	),
	array (
		"name" => "toUseLike",
		"sql" => " and c.toUse like CONCAT('%',#,'%') "
	),
	array (
		"name" => "salesmanCode",
		"sql" => " and c.salesmanCode=# "
	),
	array (
		"name" => "salesmanName",
		"sql" => " and c.salesmanName=# "
	),
	array (
		"name" => "otherSubjects",
		"sql" => " and c.otherSubjects=# "
	),
	array (
		"name" => "docStatus",
		"sql" => " and c.docStatus=# "
	),
	array (
		"name" => "catchStatus",
		"sql" => " and c.catchStatus=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "remarkSearch",
		"sql" => " and c.remark like CONCAT('%',#,'%') "
	),
	array (
		"name" => "pickName",
		"sql" => " and c.pickName=# "
	),
	array (
		"name" => "pickCode",
		"sql" => " and c.pickCode=# "
	),
	array (
		"name" => "auditDate",
		"sql" => " and c.auditDate=# "
	),
	array (
		"name" => "auditerCode",
		"sql" => " and c.auditerCode=# "
	),
	array (
		"name" => "auditerName",
		"sql" => " and c.auditerName=# "
	),
	array (
		"name" => "custosCode",
		"sql" => " and c.custosCode=# "
	),
	array (
		"name" => "custosName",
		"sql" => " and c.custosName=# "
	),
	array (
		"name" => "chargeCode",
		"sql" => " and c.chargeCode=# "
	),
	array (
		"name" => "chargeName",
		"sql" => " and c.chargeName=# "
	),
	array (
		"name" => "acceptorCode",
		"sql" => " and c.acceptorCode=# "
	),
	array (
		"name" => "acceptorName",
		"sql" => " and c.acceptorName=# "
	),
	array (
		"name" => "accounterCode",
		"sql" => " and c.accounterCode=# "
	),
	array (
		"name" => "accounterName",
		"sql" => " and c.accounterName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (//单据日期 - 年
		"name" => "thisYear",
		"sql" => " and year(c.auditDate)=# "
	),
	array (// 单据日期 - 月
		"name" => "thisMonth",
		"sql" => " and month(c.auditDate)=# "
	),
	array (//单据日期 - 开始日期
		"name" => "beginDate",
		"sql" => " and c.auditDate >= # "
	),
	array (// 单据日期 - 结束日期
		"name" => "endDate",
		"sql" => " and c.auditDate <= # "
	),
	array (// 单据日期 - 结束日期
		"name" => "prodcutNoInSB",
		"sql" => " and i.cost = 0 "
	),
	array (// 物料名称
		"name" => "productName",
		"sql" => " and c.id in(select i.mainId from oa_stock_outstock_item i where i.productName like CONCAT('%',#,'%')) "
	),
	array (// 物料代码
		"name" => "productCode",
		"sql" => " and c.id in(select i.mainId from oa_stock_outstock_item i where i.productCode like CONCAT('%',#,'%'))"
	),
	array (//物料id
		"name" => "productId",
		"sql" => " and c.id in(select i.mainId from oa_stock_outstock_item i where i.productId=# )"
	),
	array (//物料id
		"name" => "iProductId",
		"sql" => " and i.productId=# "
	),
	array (//发料仓库id
		"name" => "itemStockId",
		"sql" => " and  c.id in(select i.mainId from oa_stock_outstock_item i where i.stockId=# )"
	),
	array (//物料规格型号
		"name" => "pattern",
		"sql" => " and  c.id in(select i.mainId from oa_stock_outstock_item i where i.pattern=# )"
	),
	array (//物料规格型号
		"name" => "iPattern",
		"sql" => " and i.pattern like CONCAT('%',#,'%') "
	),
	array (//物料序列号
		"name" => "serialnoName",
		"sql" => " and  c.id in(select i.mainId from oa_stock_outstock_item i where i.serialnoName like CONCAT('%',#,'%'))"
	),
	array (//物料序列号
		"name" => "iSerialnoName",
		"sql" => " and i.serialnoName like CONCAT('%',#,'%') "
	),
	array (//物料数量
		"name" => "iActOutNum",
		"sql" => " and i.actOutNum = # "
	),
	array (//物料单价
		"name" => "iCost",
		"sql" => " and round(i.cost,2) = # "
	),
	array (//物料金额
		"name" => "iSubCost",
		"sql" => " and round(i.subCost,2) = # "
	),
	array (
		"name" => "deptCode",
		"sql" => " and c.deptCode = # "
	),
	array (//領料部門
		"name" => "pickCode",
		"sql" => " and c.pickCode = # "
	),
	array (//合同号自定义条件
		"name" => "contractIdCondition",
		"sql" => "$"
	),
	array(//换货单据查看其他出库
		"name" => "exchangeId",
		"sql" => " and c.relDocId in (select id from oa_stock_outplan o where o.docType = 'oa_contract_exchangeapply' and o.docId = # ) "
	)
);