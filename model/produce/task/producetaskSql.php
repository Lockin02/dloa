<?php
/**
 * @author huangzf
 * @Date 2012年5月17日 星期四 14:02:12
 * @version 1.0
 * @description:生产任务 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.docCode ,c.docType ,c.docDate ,c.docUserName ,c.docUserId ,c.docStatus ,c.applyDocCode ,c.applyDocId ,c.applyDocItemId ,c.objCode ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ,c.relDocTypeCode ,c.planStartDate ,c.planEndDate ,c.estimateHour ,c.estimateDay ,c.chargeUserName ,c.chargeUserId ,c.urgentLevel ,c.urgentLevelCode ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.taskNum ,c.qualityNum ,c.qualifiedNum ,c.stockNum ,c.goodsConfigId ,c.licenseConfigId ,c.purpose ,c.technology ,c.fileNo ,c.customerId ,c.customerName ,c.projectName ,c.productionBatch ,c.needDate ,c.saleUserName ,c.saleUserId ,c.salesExplain ,c.recipient ,c.recipientId ,c.recipientDate ,c.progressInfo ,c.remark ,c.weekly ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.originalId ,c.isTemp ,c.changeTips ,c.idDel ,c.proType ,c.isFirstInspection ,c.isMeetProduction from oa_produce_producetask c where 1=1 ",

	//物料汇总
	"select_product"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.docCode ,c.docType ,c.docDate ,c.docUserName ,c.docUserId ,c.docStatus ,c.applyDocCode ,c.applyDocId ,c.applyDocItemId ,c.objCode ,c.relDocId ,c.relDocCode ,c.relDocName ,c.relDocType ,c.relDocTypeCode ,c.planStartDate ,c.planEndDate ,c.estimateHour ,c.estimateDay ,c.chargeUserName ,c.chargeUserId ,c.urgentLevel ,c.urgentLevelCode ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,SUM(c.taskNum) AS taskNum ,c.qualityNum ,c.qualifiedNum ,c.stockNum ,c.goodsConfigId ,c.licenseConfigId ,c.purpose ,c.technology ,c.fileNo ,c.customerId ,c.customerName ,c.projectName ,c.productionBatch ,c.needDate ,c.saleUserName ,c.saleUserId ,c.salesExplain ,c.recipient ,c.recipientId ,c.recipientDate ,c.progressInfo ,c.remark ,c.weekly ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.originalId ,c.isTemp ,c.changeTips ,c.idDel ,d.planNum ,d.stockNum
		from oa_produce_producetask c
		left join(
			select p.productId ,SUM(p.planNum) AS planNum ,SUM(p.stockNum) AS stockNum from oa_produce_produceplan p group by p.productId
		)d on c.productId=d.productId
		where 1=1 ",
	//配置汇总
	"select_product_config"=>"select c.id,c.planId,c.taskId,c.productId,c.productCode,c.productName,c.pattern,c.unitName,SUM(c.num) AS taskNum,SUM(c.planNum) AS planNum,p.proType
				from oa_produce_taskconfig_product c
				left join  oa_produce_producetask p on c.taskId=p.id
			where 1=1 ",
	//配置汇总查看
	"select_product_view"=>"select p.id,c.planId,c.taskId,c.productId,c.productCode,c.productName,c.pattern,c.unitName,c.num AS taskNum,c.planNum,p.proType,p.relDocCode,p.docCode,
				p.relDocName,p.relDocType
				from oa_produce_taskconfig_product c
				left join  oa_produce_producetask p on c.taskId=p.id
			where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "docCode",
		"sql" => " and c.docCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "docType",
		"sql" => " and c.docType=# "
	),
	array(
		"name" => "docDate",
		"sql" => " and c.docDate=# "
	),
	array(
		"name" => "docDateSea",
		"sql" => " and c.docDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "docUserName",
		"sql" => " and c.docUserName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "docUserId",
		"sql" => " and c.docUserId=# "
	),
	array(
		"name" => "docStatus",
		"sql" => " and c.docStatus=# "
	),
	array(
		"name" => "docStatusIn",
		"sql" => " and c.docStatus in($) "
	),
	array(
		"name" => "docStatusArr",
		"sql" => " and c.docStatus in($) "
	),
	array(
		"name" => "relDocId",
		"sql" => " and c.relDocId=# "
	),
	array(
		"name" => "relDocCode",
		"sql" => " and c.relDocCode LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "relDocName",
		"sql" => " and c.relDocName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "relDocType",
		"sql" => " and c.relDocType LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "relDocTypeCode",
		"sql" => " and c.relDocTypeCode=# "
	),
	array(
		"name" => "applyDocCode",
		"sql" => " and c.applyDocCode LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "applyDocId",
		"sql" => " and c.applyDocId=# "
	),
	array(
		"name" => "applyDocItemId",
		"sql" => " and c.applyDocItemId=# "
	),
	array(
		"name" => "planStartDate",
		"sql" => " and c.planStartDate=# "
	),
	array(
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array(
		"name" => "estimateHour",
		"sql" => " and c.estimateHour=# "
	),
	array(
		"name" => "estimateDay",
		"sql" => " and c.estimateDay=# "
	),
	array(
		"name" => "chargeUserName",
		"sql" => " and c.chargeUserName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "chargeUserId",
		"sql" => " and c.chargeUserId=# "
	),
	array(
		"name" => "urgentLevel",
		"sql" => " and c.urgentLevel LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "urgentLevelCode",
		"sql" => " and c.urgentLevelCode=# "
	),
	array(
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array(
		"name" => "productCode",
		"sql" => " and c.productCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "productName",
		"sql" => " and c.productName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "proType",
		"sql" => " and c.proType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "proTypeTask",
		"sql" => " and p.proType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "pattern",
		"sql" => " and c.pattern LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "unitName",
		"sql" => " and c.unitName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "taskNum",
		"sql" => " and c.taskNum=# "
	),
	array(
		"name" => "qualifiedNum",
		"sql" => " and c.qualifiedNum=# "
	),
	array(
		"name" => "qualityNum",
		"sql" => " and c.qualityNum=# "
	),
	array(
		"name" => "stockNum",
		"sql" => " and c.stockNum=# "
	),
	array(
		"name" => "goodsConfigId",
		"sql" => " and c.goodsConfigId=# "
	),
	array(
		"name" => "licenseConfigId",
		"sql" => " and c.licenseConfigId=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "recipient",
		"sql" => " and c.recipient LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "recipientId",
		"sql" => " and c.recipientId=# "
	),
	array(
		"name" => "recipientDate",
		"sql" => " and c.recipientDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "weekly",
		"sql" => " and c.weekly=# "
	),
	array(
		"name" => "fileNo",
		"sql" => " and c.fileNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "saleUserName",
		"sql" => " and c.saleUserName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "saleUserId",
		"sql" => " and c.saleUserId=# "
	),
	array(
		"name" => "customerName",
		"sql" => " and c.customerName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "productionBatch",
		"sql" => " and c.productionBatch LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "customerId",
		"sql" => " and c.customerId=# "
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
		"name" => "originalId",
		"sql" => " and c.originalId=# "
	),
	array(
		"name" => "isTemp",
		"sql" => " and c.isTemp=# "
	),
	array(
		"name" => "changeTips",
		"sql" => " and c.changeTips=# "
	),
	array(
		"name" => "idDel",
		"sql" => " and c.idDel=# "
	),
	array(
		"name"=>"userId",
		"sql"=>" and (c.id in(select taskId from oa_produce_task_actor where actUserCode=#) or c.chargeUserId=#)  "
	),
	array(
		"name" => "taskTypeCode",
		"sql" => " and c.taskTypeCode=# "
	),
	array(
		"name" => "taskTypeName",
		"sql" => " and c.taskTypeName=# "
	)
)
?>