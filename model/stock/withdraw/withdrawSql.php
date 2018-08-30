<?php

/**
 * @author Administrator
 * @Date 2012年11月8日 11:04:46
 * @version 1.0
 * @description:收货通知单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.planCode ,c.docType ,c.rObjCode ,c.docId ,c.docCode ,c.docName ,c.week ,c.planIssuedDate ,c.stockId ,c.stockCode ,c.stockName ,c.type ,c.issuedStatus ,c.docStatus ,c.shipPlanDate ,c.isShipped ,c.isOnTime ,c.delayTypeCode ,c.delayType ,c.delayDetail ,c.delayReason ,c.createTime ,c.createName ,c.createId ,c.updateName ,c.updateTime ,c.updateId ,c.status ,c.customerName ,c.customerId ,c.docApplicant ,c.docApplicantId ,c.changeTips  from oa_stock_withdraw c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "planCode",
		"sql" => " and c.planCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "docType",
		"sql" => " and c.docType=# "
	),
	array (
		"name" => "rObjCode",
		"sql" => " and c.rObjCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "docId",
		"sql" => " and c.docId=# "
	),
	array (
		"name" => "docCode",
		"sql" => " and c.docCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "docName",
		"sql" => " and c.docName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "week",
		"sql" => " and c.week=# "
	),
	array (
		"name" => "planIssuedDate",
		"sql" => " and c.planIssuedDate=# "
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
		"name" => "type",
		"sql" => " and c.type=# "
	),
	array (
		"name" => "issuedStatus",
		"sql" => " and c.issuedStatus=# "
	),
	array (
		"name" => "docStatus",
		"sql" => " and c.docStatus=# "
	),
	array (
		"name" => "shipPlanDate",
		"sql" => " and c.shipPlanDate=# "
	),
	array (
		"name" => "isShipped",
		"sql" => " and c.isShipped=# "
	),
	array (
		"name" => "isOnTime",
		"sql" => " and c.isOnTime=# "
	),
	array (
		"name" => "delayTypeCode",
		"sql" => " and c.delayTypeCode=# "
	),
	array (
		"name" => "delayType",
		"sql" => " and c.delayType=# "
	),
	array (
		"name" => "delayDetail",
		"sql" => " and c.delayDetail=# "
	),
	array (
		"name" => "delayReason",
		"sql" => " and c.delayReason=# "
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
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusArr",
		"sql" => " and c.status in(arr) "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array (
		"name" => "docApplicant",
		"sql" => " and c.docApplicant=# "
	),
	array (
		"name" => "docApplicantId",
		"sql" => " and c.docApplicantId=# "
	),
	array (
		"name" => "changeTips",
		"sql" => " and c.changeTips=# "
	)
)
?>