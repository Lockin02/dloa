<?php
/**
 * @author Administrator
 * @Date 2012年5月15日 星期二 14:51:00
 * @version 1.0
 * @description:生产申请单 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.docCode ,c.docDate ,c.docType ,c.docTypeCode ,c.objCode ,c.relDocId ,c.relDocCode ,c.relDocName ,c.projectName ,c.relDocType ,c.relDocTypeCode ,c.saleUserCode ,c.saleUserName ,c.saleUserId ,c.customerId ,c.customerName ,c.applyUserCode ,c.applyUserName ,c.applyUserId ,c.applyDate ,c.hopeDeliveryDate ,c.actualDeliveryDate ,c.docStatus ,c.progress ,c.ExaStatus ,c.ExaDT ,c.backReason ,c.backDate ,c.salesExplain ,c.remark ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.originalId ,c.isTemp ,c.changeTips from oa_produce_produceapply c where c.isTemp = 0 ",

	"select_page"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.docCode ,c.docDate ,c.docType ,c.docTypeCode ,c.objCode ,c.relDocId ,c.relDocCode ,c.relDocName ,c.projectName ,c.relDocType ,c.relDocTypeCode ,c.saleUserCode ,c.saleUserName ,c.saleUserId ,c.customerId ,c.customerName ,c.applyUserCode ,c.applyUserName ,c.applyUserId ,c.applyDate ,c.hopeDeliveryDate ,c.actualDeliveryDate ,c.docStatus ,c.progress ,c.ExaStatus ,c.ExaDT ,c.backReason ,c.backDate ,c.salesExplain ,c.remark ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.originalId ,c.isTemp ,c.changeTips ,GROUP_CONCAT(i.productName) as productName ,GROUP_CONCAT(i.productCode) as productCode ,GROUP_CONCAT(i.pattern) as pattern
		from oa_produce_produceapply c
		left join oa_produce_produceapply_item i on c.id=i.mainId AND i.isTemp=0
		where c.isTemp = 0 ",

	// 用来判断是否完成需求（入库是否完全）
	"select_need"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.docCode ,c.docDate ,c.docType ,c.docTypeCode ,c.objCode ,c.relDocId ,c.relDocCode ,c.relDocName ,c.projectName ,c.relDocType ,c.relDocTypeCode ,c.saleUserCode ,c.saleUserName ,c.saleUserId ,c.customerId ,c.customerName ,c.applyUserCode ,c.applyUserName ,c.applyUserId ,c.applyDate ,c.hopeDeliveryDate ,c.actualDeliveryDate ,c.docStatus ,c.progress ,c.ExaStatus ,c.ExaDT ,c.backReason ,c.backDate ,c.salesExplain ,c.remark ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.originalId ,c.isTemp ,c.changeTips ,i.produceNumAll ,i.exeNumAll ,i.stockNumAll ,GROUP_CONCAT(t.productName) as productName ,GROUP_CONCAT(t.productCode) as productCode ,GROUP_CONCAT(t.pattern) as pattern
		from oa_produce_produceapply c
		left join (
			select t.mainId ,SUM(t.produceNum) as produceNumAll ,SUM(t.exeNum) as exeNumAll ,SUM(t.stockNum) as stockNumAll
			from oa_produce_produceapply_item t where t.isTemp=0 and t.state=0 group by t.mainId
		)i on c.id=i.mainId
		left join oa_produce_produceapply_item t on c.id=t.mainId AND t.isTemp=0
		where c.isTemp = 0 ",

	"select_product"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.docCode ,c.docDate ,c.docType ,c.docTypeCode ,c.objCode ,c.relDocId ,c.relDocCode ,c.relDocName ,c.projectName ,c.relDocType ,c.relDocTypeCode ,c.saleUserCode ,c.saleUserName ,c.saleUserId ,c.customerId ,c.customerName ,c.applyUserCode ,c.applyUserName ,c.applyUserId ,c.applyDate ,c.hopeDeliveryDate ,c.actualDeliveryDate ,c.docStatus ,c.progress ,c.ExaStatus ,c.ExaDT ,c.backReason ,c.backDate ,c.salesExplain ,c.remark ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.originalId ,c.isTemp ,c.changeTips ,SUM(i.produceNum) as produceNum ,i.productId
		from oa_produce_produceapply c
		left join oa_produce_produceapply_item i on c.id=i.mainId AND i.isTemp=0
		where c.isTemp = 0 "
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
		"name" => "docDate",
		"sql" => " and c.docDate=# "
	),
	array(
		"name" => "docType",
		"sql" => " and c.docType=# "
	),
	array(
		"name" => "relDocType",
		"sql" => " and c.relDocType=# "
	),
	array(
		"name" => "relDocTypeCode",
		"sql" => " and c.relDocTypeCode=# "
	),
	array(
		"name" => "relDocTypeCodeCondition",
		"sql" => "$"
	),
	array(
		"name" => "relDocCode",
		"sql" => " and c.relDocCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "relDocName",
		"sql" => " and c.relDocName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "relDocId",
		"sql" => " and c.relDocId=# "
	),
	array(
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array(
		"name" => "customerName",
		"sql" => " and c.customerName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "saleUserCode",
		"sql" => " and c.saleUserCode=# "
	),
	array(
		"name" => "saleUserName",
		"sql" => " and c.saleUserName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "applyUserCode",
		"sql" => " and c.applyUserCode=# "
	),
	array(
		"name" => "applyUserId",
		"sql" => " and c.applyUserId=# "
	),
	array(
		"name" => "applyUserName",
		"sql" => " and c.applyUserName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "applyDate",
		"sql" => " and c.applyDate LIKE CONCAT('%',#,'%') "
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
		"name" => "progress",
		"sql" => " and c.progress=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%',#,'%') "
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
		"name" => "hopeDeliveryDate",
		"sql" => " and c.hopeDeliveryDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "actualDeliveryDate",
		"sql" => " and c.actualDeliveryDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "startWeekDate",
		"sql" => " and c.hopeDeliveryDate >= # "
	),
	array(
		"name" => "endWeekDate",
		"sql" => " and c.hopeDeliveryDate <= # "
	),
	array(
		"name" => "productId",
		"sql" => " and i.productId=# "
	),
	array(
		"name" => "productCode",
		"sql" => " and i.productCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "productName",
		"sql" => " and i.productName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "pattern",
		"sql" => " and i.pattern LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "proType",
		"sql" => " and i.proType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "tproductCode",
		"sql" => " and t.productCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "tproductName",
		"sql" => " and t.productName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "tpattern",
		"sql" => " and t.pattern LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "tproType",
		"sql" => " and t.proType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "isFinish",
		"sql" => " and i.produceNumAll <= i.stockNumAll "
	),
	array(
		"name" => "noFinish",
		"sql" => " and i.produceNumAll > i.stockNumAll "

	)
)
?>