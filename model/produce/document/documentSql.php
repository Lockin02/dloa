<?php
$sql_arr = array (
	"select_default" => "select
			c.id,c.typeId,c.typeName,c.serviceId,c.serviceNo,c.serviceType,c.originalName,c.originalName as name,
			c.newName,c.uploadPath,c.tFileSize,c.createId,c.createName,c.createTime,c.updateId,
			c.updateName,c.updateTime,c.inDocument,c.styleOne,c.styleTwo,c.styleThree,c.originalId,c.isTemp
		from oa_uploadfile_manage c where c.typeId <> '' 
		and c.serviceType in('oa_produce_produceplan','oa_produce_quality_ereport','produce_document_document') ",
	"select_extend" => "select
			c.id,c.typeId,c.typeName,c.serviceId,c.serviceNo,c.serviceType,c.originalName,
			c.newName,c.uploadPath,c.tFileSize,c.createId,c.createName,c.createTime,c.updateId,
			c.updateName,c.updateTime,c.inDocument,c.styleOne,c.styleTwo,c.styleThree,c.originalId,c.isTemp,
			p.relDocId as contractId,p.relDocCode as contractCode,p.saleUserName,p.customerName,s.auditDate
		from oa_uploadfile_manage c 
		left join oa_produce_quality_ereport e on e.id = c.serviceId
		left join oa_produce_quality_apply a on e.applyId = a.id
		left join oa_produce_produceplan p on a.relDocId = p.id
		left join oa_stock_innotice i on i.docId = p.id
		left join oa_stock_instock s on s.relDocId = i.id and s.relDocType = 'RSCJHD' and s.docStatus = 'YSH'
		where c.typeId <> '' and c.serviceType in('oa_produce_produceplan','oa_produce_quality_ereport','produce_document_document') ",
);
$condition_arr = array (
	array (
		"name" => "originalName",
		"sql" => "and c.originalName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "createName",
		"sql" => "and c.createName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "newName",
		"sql" => "and c.newName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "Number",
		"sql" => "and Number like CONCAT('%',#,'%')"
	),
	array (
		"name" => "id",
		"sql" => "and c.id = #"
	),
	array (
		"name" => "ids",
		"sql" => "and c.id in($)"
	),
	array (
		"name" => "supplierId",
		"sql" => "and c.supplierId = #"
	),
	array (
		"name" => "serviceIds",
		"sql" => "and c.serviceId in($)"
	),
	array (
		"name" => "serviceId",
		"sql" => "and c.serviceId = #"
	),
	array (
		"name" => "serviceNo",
		"sql" => "and c.serviceNo = #"
	),
	array (
		"name" => "serviceType",
		"sql" => "and c.serviceType = #"
	),
	array (
		"name" => "serviceTypeArr",
		"sql" => "and c.serviceType in($)"
	),
	array (
		"name" => "objId",
		"sql" => " and c.serviceId=#"
	),
	array (
		"name" => "objTable",
		"sql" => " and c.serviceType=#"
	),
	array (
		"name" => "typeId",
		"sql" => " and c.typeId in($)"
	),
	array (
		"name" => "onlyProject",
		"sql" => " and (c.serviceType='oa_rd_project' and c.serviceId=#)"
	),
	array (
		"name" => "start",
		"sql" => " and ("
	),
	array (
		"name" => "project",
		"sql" => " (c.serviceType='oa_rd_project' and c.serviceId=#)"
	),
	array (
		"name" => "task",
		"sql" => " or (c.serviceType='oa_rd_task' and c.serviceId in($))"
	),
	array (
		"name" => "plan",
		"sql" => " or (c.serviceType='oa_rd_project_plan' and c.serviceId in($))"
	),
	array (
		"name" => "end",
		"sql" => " )"
	),
	array (
		"name" => "contractCode",
		"sql" => " and p.relDocCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "customerName",
		"sql" => " and p.customerName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "saleUserName",
		"sql" => " and p.saleUserName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "auditDate",
		"sql" => " and s.auditDate LIKE BINARY CONCAT('%',#,'%') "
	)
);
?>