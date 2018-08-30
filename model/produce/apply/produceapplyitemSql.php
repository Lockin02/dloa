<?php
/**
 * @author huangzf
 * @Date 2012年5月17日 星期四 14:00:42
 * @version 1.0
 * @description:生产申请清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.relDocId ,c.relDocCode ,c.relDocItemId ,c.goodsId ,c.productId ,c.productCode ,c.needNum ,c.productName ,c.pattern ,c.unitName ,c.produceNum ,c.exeNum ,c.qualityNum ,c.qualifiedNum ,c.stockNum ,c.planEndDate ,c.actualEndDate ,c.goodsConfigId ,c.licenseConfigId ,c.remark ,c.originalId ,c.isTemp ,c.changeTips ,c.isDel ,c.state ,c.proType ,c.proTypeId ,d.shipPlanDate
		from oa_produce_produceapply_item c
		left join (
			SELECT o.shipPlanDate ,p.docId ,p.productId
			FROM (SELECT n.id ,n.shipPlanDate FROM oa_stock_outplan n ORDER BY n.id DESC) o
			LEFT JOIN oa_stock_outplan_product p ON o.id=p.mainId
			WHERE p.docType='oa_contract_contract'
			GROUP BY p.productId ,p.docId
			ORDER BY p.id DESC
		) d ON d.docId=c.relDocId AND d.productId=c.productId
		where 1=1 ",

	"select_contract" => "select c.id ,c.mainId ,c.relDocItemId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.produceNum ,c.exeNum ,c.qualityNum ,c.qualifiedNum ,c.stockNum ,c.planEndDate ,c.goodsConfigId ,c.licenseConfigId ,c.remark ,c.originalId ,c.isTemp ,c.changeTips ,c.isDel ,c.state ,e.number ,e.issuedProNum
		from oa_produce_produceapply_item c
		left join oa_produce_produceapply p on c.mainId=p.id
		left join oa_contract_equ e on e.contractId=p.relDocId
		where c.isTemp=0 ",

	//物料汇总需求
	"select_product" => "select c.id ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.proType ,c.proTypeId ,c.produceNum ,SUM(c.produceNum) as produceNumAll ,c.exeNum ,SUM(c.exeNum) as exeNumAll from oa_produce_produceapply_item c where c.state=0 AND c.isTemp=0 ",

	//关联主表
	"select_main" => "select c.id ,c.mainId ,c.relDocId ,c.relDocCode ,c.relDocItemId ,c.goodsId ,c.productId ,c.productCode ,c.needNum ,c.productName ,c.pattern ,c.unitName ,c.produceNum ,c.exeNum ,c.qualityNum ,c.qualifiedNum ,c.stockNum ,c.planEndDate ,c.actualEndDate ,c.goodsConfigId ,c.licenseConfigId ,c.remark ,c.originalId ,c.isTemp ,c.changeTips ,c.isDel ,c.state ,p.docCode ,p.docDate ,p.docType ,p.docTypeCode ,p.relDocType ,p.relDocTypeCode ,p.saleUserCode ,p.saleUserName ,p.saleUserId ,p.customerId ,p.customerName ,p.applyUserCode ,p.applyUserName ,p.applyUserId ,p.applyDate ,p.hopeDeliveryDate ,p.actualDeliveryDate ,p.docStatus ,p.progress ,p.ExaStatus ,p.ExaDT ,p.backReason ,p.backDate ,p.salesExplain ,t.docStatus as tDocStatus
		from oa_produce_produceapply_item c
		left join oa_produce_produceapply p on p.id=c.mainId
		left join oa_produce_producetask t on t.applyDocId=p.id AND t.productId=c.productId AND t.docStatus IN(0,1,2)
		where c.isTemp=0 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "idArr",
		"sql" => " and c.id in(arr) "
	),
	array(
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array(
		"name" => "mainIdArr",
		"sql" => " and c.mainId in(arr) "
	),
	array(
		"name" => "relDocCode",
		"sql" => " and c.relDocCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "relDocItemId",
		"sql" => " and c.relDocItemId=# "
	),
	array(
		"name" => "goodsId",
		"sql" => " and c.goodsId=# "
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
		"name" => "pattern",
		"sql" => " and c.pattern=# "
	),
	array(
		"name" => "unitName",
		"sql" => " and c.unitName=# "
	),
	array(
		"name" => "produceNum",
		"sql" => " and c.produceNum=# "
	),
	array(
		"name" => "exeNum",
		"sql" => " and c.exeNum=# "
	),
	array(
		"name" => "haveExeNum",
		"sql" => " and c.exeNum > 0 "
	),
	array(
		"name" => "qualityNum",
		"sql" => " and c.qualityNum=# "
	),
	array(
		"name" => "qualifiedNum",
		"sql" => " and c.qualifiedNum=# "
	),
	array(
		"name" => "stockNum",
		"sql" => " and c.stockNum=# "
	),
	array(
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
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
		"name" => "isDel",
		"sql" => " and c.isDel=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "stateIn",
		"sql" => " and c.state IN($) "
	),
	array(
		"name" => "startWeekDate",
		"sql" => " and c.planEndDate >= # "
	),
	array(
		"name" => "endWeekDate",
		"sql" => " and c.planEndDate <= # "
	),
	array(
		"name" => "canOrder",
		"sql" => " and c.produceNum - c.exeNum > 0 "
	),
	array(
		"name" => "proType",
		"sql" => " and c.proType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "proTypeId",
		"sql" => " and c.proTypeId=# "
	),
	array(
		"name" => "docCode",
		"sql" => " and p.docCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "customerName",
		"sql" => " and p.customerName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "saleUserName",
		"sql" => " and p.saleUserName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "applyUserName",
		"sql" => " and p.applyUserName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "pDocStatusIn",
		"sql" => " and p.docStatus IN($) "
	),
	array(
		"name" => "tDocStatusIn",
		"sql" => " and t.docStatus IN($) "
	)
)
?>