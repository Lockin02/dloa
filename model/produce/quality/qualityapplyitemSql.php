<?php

/**
 * @author Administrator
 * @Date 2013年4月2日 星期二 15:15:43
 * @version 1.0
 * @description:质检申请单清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.relDocItemId ,c.productId ,c.productCode ,c.productName ,c.pattern ,
			c.unitName ,c.fittings ,c.qualityNum ,c.assignNum ,c.standardNum ,c.planEndDate ,c.remark ,c.orderNum,c.status,
			c.dealUserName,c.dealUserId,c.checkType,c.checkTypeName,c.dealTime,c.complatedNum,c.passReason,c.batchNum,c.serialId,c.serialName
		from oa_produce_qualityapply_item c where 1=1 ",
	"select_confirmpass" => "select c.id ,c.mainId ,c.relDocItemId ,c.productId ,c.productCode ,c.productName ,c.pattern ,
			c.unitName ,c.fittings ,c.qualityNum ,c.assignNum ,c.standardNum ,c.planEndDate ,c.remark ,c.orderNum,c.status,
			c.dealUserName,c.dealUserId,c.checkType,c.checkTypeName,c.dealTime,c.complatedNum,q.applyUserName,q.relDocCode,
			q.applyUserCode,q.relDocId,q.relDocType,q.docCode,(c.qualityNum - c.assignNum) as canAssignNum,c.id as applyItemId,
			q.supplierId,q.supplierName,q.createTime as supportTime,q.applyUserName as purchaserName,q.applyUserCode as purchaserId,
			q.id as applyId,q.docCode as applyCode,q.relDocId as objId,q.relDocCode as objCode,q.relDocType as objType,
			c.relDocItemId as objItemId,c.passReason,c.batchNum,c.serialId,c.serialName
		from oa_produce_qualityapply_item c inner join oa_produce_quality_apply q on c.mainId = q.id where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "idArr",
		"sql" => " and c.id in(arr) "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array (
		"name" => "relDocItemId",
		"sql" => " and c.relDocItemId=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array (
		"name" => "productCode",
		"sql" => " and c.productCode=# "
	),
	array (
		"name" => "productName",
		"sql" => " and c.productName=# "
	),
	array (
		"name" => "pattern",
		"sql" => " and c.pattern=# "
	),
	array (
		"name" => "unitName",
		"sql" => " and c.unitName=# "
	),
	array (
		"name" => "fittings",
		"sql" => " and c.fittings=# "
	),
	array (
		"name" => "qualityNum",
		"sql" => " and c.qualityNum=# "
	),
	array (
		"name" => "assignNum",
		"sql" => " and c.assignNum=# "
	),
	array (
		"name" => "standardNum",
		"sql" => " and c.standardNum=# "
	),
	array (
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "orderNum",
		"sql" => " and c.orderNum=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	)
)
?>