<?php
/**
 * @author Show
 * @Date 2013年5月23日 星期四 10:54:51
 * @version 1.0
 * @description:质检报告清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.relDocId ,c.relDocCode ,c.relItemId,c.relDocType ,c.productId ,c.productCode ,
			c.productName ,c.pattern ,c.unitName ,c.supplierName ,c.supplierId ,c.supportNum ,c.supportTime ,
			c.purchaserName ,c.purchaserId ,c.priority ,c.priorityName ,c.qualitedNum ,c.produceNum ,c.remark,
			c.passNum,c.receiveNum,c.backNum,c.objId,c.objCode,c.objType,c.objItemId,c.completionTime,c.thisCheckNum,c.samplingNum,c.mainDocItemId,c.serialnoId,c.serialnoName
		from oa_produce_quality_ereportequitem c where 1=1 ",
	"select_docCount" => "select
			c.objId,c.objCode,c.objType,c.productId,c.productCode,c.productName,c.pattern,c.unitName,c.thisCheckNum,
			sum(c.supportNum) as supportNum,sum(c.qualitedNum) as qualitedNum,sum(c.produceNum) as produceNum,
			sum(c.receiveNum) as receiveNum,sum(c.backNum) as backNum,sum(c.passNum) as passNum,
			GROUP_CONCAT(e.docCode) as reportCodes,GROUP_CONCAT(cast(e.id as char(20))) as reportIds,
			GROUP_CONCAT(cast(c.relItemId as char(20))) as relItemIds
		from
			oa_produce_quality_ereportequitem c inner join oa_produce_quality_ereport e on c.mainId = e.id
		where 1 ",
    "select_qualitedRate" => "SELECT c.productId,c.productCode,c.productName,e.qualityType, sum(c.qualitedNum) AS sumQualitedNum, sum(c.produceNum) AS sumProduceNum, SUM(c.thisCheckNum) AS sumThisCheckNum,
	case (e.qualityType = 'ZJFSCJ' and sum(c.produceNum) <= 0)
	when 1 then FORMAT(100,2)
	ELSE
	FORMAT(
		(SUM(c.samplingNum) - SUM(c.produceNum)) / sum(c.samplingNum) * 100,
		2
	) 
	END AS qualitedRate
    FROM oa_produce_quality_ereportequitem c
    LEFT JOIN oa_produce_quality_ereport e ON e.id=c.mainId
    where 1=1  "
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
		"name" => "relDocId",
		"sql" => " and c.relDocId=# "
	),
	array (
		"name" => "relDocCode",
		"sql" => " and c.relDocCode=# "
	),
	array (
		"name" => "relDocType",
		"sql" => " and c.relDocType=# "
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
		"name" => "supplierName",
		"sql" => " and c.supplierName=# "
	),
	array (
		"name" => "supplierId",
		"sql" => " and c.supplierId=# "
	),
	array (
		"name" => "supportNum",
		"sql" => " and c.supportNum=# "
	),
	array (
		"name" => "supportTime",
		"sql" => " and c.supportTime=# "
	),
	array (
		"name" => "purchaserName",
		"sql" => " and c.purchaserName=# "
	),
	array (
		"name" => "purchaserId",
		"sql" => " and c.purchaserId=# "
	),
	array (
		"name" => "priority",
		"sql" => " and c.priority=# "
	),
	array (
		"name" => "priorityName",
		"sql" => " and c.priorityName=# "
	),
	array (
		"name" => "qualitedNum",
		"sql" => " and c.qualitedNum=# "
	),
	array (
		"name" => "produceNum",
		"sql" => " and c.produceNum=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "objId",
		"sql" => " and c.objId=# "
	),
	array (
		"name" => "objType",
		"sql" => " and c.objType=# "
	),
    array (
        "name" => "objItemId",
        "sql" => " and c.objItemId=# "
    ),
    array (
        "name" => "relItemId",
        "sql" => " and c.relItemId=# "
    ),
    array (
        "name" => "ExaStatus",
        "sql" => " and e.ExaStatus=# "
    )
)
?>