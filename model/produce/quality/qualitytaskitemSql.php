<?php

/**
 * @author Administrator
 * @Date 2013年4月3日 星期三 10:00:34
 * @version 1.0
 * @description:交检任务单清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.applyItemId ,c.productId ,c.productCode ,c.productName ,c.pattern ,
			c.unitName ,c.fittings ,c.assignNum ,c.assignNum as supportNum,c.standardNum ,c.checkStatus ,c.remark,c.checkTypeName,c.checkType,
            c.supplierName,c.supplierId,c.supportTime,c.purchaserName,c.purchaserId,c.thisCheckNum,c.checkedNum,c.objItemId,c.objItemId as mainDocItemId,c.realCheckNum
		from oa_produce_quality_taskitem c where 1=1 ",
	"select_forreport" => "select c.id ,c.mainId ,c.applyItemId ,c.productId ,c.productCode ,c.productName ,c.pattern ,
			c.unitName ,c.fittings ,c.assignNum ,c.assignNum as supportNum,c.standardNum ,c.checkStatus ,c.remark,c.checkTypeName,c.checkType,
            c.supplierName,c.supplierId,c.supportTime,c.purchaserName,c.purchaserId,t.docCode as relDocCode,t.id as relDocId,c.id as relItemId,
            c.objId,c.objCode,c.objType,c.objItemId,c.objItemId as mainDocItemId,c.thisCheckNum,c.checkedNum,c.thisCheckNum as samplingNum,c.realCheckNum
		from oa_produce_quality_taskitem c inner join oa_produce_quality_task t on c.mainId = t.id where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array (
		"name" => "applyItemId",
		"sql" => " and c.applyItemId=# "
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
		"name" => "assignNum",
		"sql" => " and c.assignNum=# "
	),
	array (
		"name" => "standardNum",
		"sql" => " and c.standardNum=# "
	),
	array (
		"name" => "checkStatus",
		"sql" => " and c.checkStatus=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
    array (
        "name" => "objItemId",
        "sql" => " and c.objItemId=# "
    ),
	array (
		"name" => "checkStatusNull",
		"sql" => " and c.thisCheckNum > 0"
	)
)
?>