<?php

/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 16:46:27
 * @version 1.0
 * @description:检验报告 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.docCode ,c.docDate ,c.mainId ,c.mainItemId ,c.applyId ,c.applyItemId ,c.productId ,
			c.productCode ,c.productName ,c.pattern ,c.unitName ,c.checkNum ,c.qualitedNum ,c.produceNum ,c.exmineResult ,
			c.auditStatus ,c.auditOpion ,c.remark ,c.examineUserName ,c.examineUserId,c.mainCode,c.ExaStatus,c.ExaDT,c.relDocType,c.relDocTypeName ,
			c.guideDocId ,c.guideDocName,c.qualityTypeName
		from oa_produce_quality_ereport c where 1=1 ",
	'select_detail'=>'select  c.id ,c.docCode ,c.docDate ,c.mainId ,c.mainItemId ,c.applyId ,c.applyItemId ,c.productId ,c.qualityTypeName,
			c.productCode ,c.productName ,c.pattern ,c.unitName ,c.checkNum ,c.qualitedNum ,c.produceNum ,c.exmineResult ,
			c.auditStatus ,c.auditOpion ,c.remark ,c.examineUserName ,c.examineUserId,c.mainCode,c.ExaStatus,c.ExaDT,c.relDocType,c.relDocType as relDocTypeCode,c.relDocTypeName,p.relDocId ,p.relDocCode ,p.relItemId,p.relDocType ,p.productId ,p.productCode ,
			p.productName ,p.pattern ,p.unitName ,p.supplierName ,p.supplierId ,p.supportNum ,p.supportTime ,p.samplingNum, 
			p.purchaserName ,p.purchaserId ,p.priority ,p.priorityName ,p.qualitedNum ,p.produceNum ,p.remark as productRemark,p.thisCheckNum,
			p.receiveNum from oa_produce_quality_ereport c LEFT JOIN oa_produce_quality_ereportequitem p on c.id=p.mainId where 1=1 '
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "docCode",
		"sql" => " and c.docCode=# "
	),
	array (
		"name" => "docCodeSearch",
		"sql" => " and c.docCode like concat('%',#,'%') "
	),
	array (
		"name" => "docDate",
		"sql" => " and c.docDate=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array (
		"name" => "mainCode",
		"sql" => " and c.mainCode=# "
	),
	array (
		"name" => "mainCodeSearch",
		"sql" => " and c.mainCode like concat('%',#,'%') "
	),
	array (
		"name" => "mainItemId",
		"sql" => " and c.mainItemId=# "
	),
	array (
		"name" => "applyId",
		"sql" => " and c.applyId=# "
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
		"name" => "checkNum",
		"sql" => " and c.checkNum=# "
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
		"name" => "exmineResult",
		"sql" => " and c.exmineResult=# "
	),
	array (
		"name" => "auditStatus",
		"sql" => " and c.auditStatus=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "auditOpion",
		"sql" => " and c.auditOpion=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
    array (
        "name" => "relItemId",
        "sql" => " and p.relItemId=# "
    ),
    array (
        "name" => "objType",
        "sql" => " and p.objType=# "
    ),
    array (
        "name" => "objItemId",
        "sql" => " and p.objItemId=# "
    ),
	array (
		"name" => "examineUserName",
		"sql" => " and c.examineUserName=# "
	),
	array (
		"name" => "examineUserNameSearch",
		"sql" => " and c.examineUserName like concat('%',#,'%') "
	),
	array (
		"name" => "examineUserId",
		"sql" => " and c.examineUserId=# "
	),
	array (// 由于PMS2386的处理, 结果为不合格的呆料报废单属于已完成报告
		"name" => "completeStatus",
		"sql" => " and (((c.auditStatus='YSH' or c.ExaStatus ='完成') and c.ExaStatus <> '待提交' or (c.relDocType = 'ZJSQDLBF' and c.auditStatus='BHG' and c.ExaStatus = '待提交')) and c.relDocType != 'ZJSQDLBF')"
	),
	array (// 由于PMS2386的处理,会出现质检合格,但审批为待提交的单据. 结果为不合格的呆料报废单属于已完成报告 (2017-05-04修改,质检报告只有个人的未完成的才能看到呆料报废的)
		"name" => "noComplete",
		"sql" => " and (((c.auditStatus!='YSH' and c.relDocType != 'ZJSQDLBF') or (c.auditStatus = 'YSH' and c.ExaStatus = '待提交'))  and (c.ExaStatus !='完成' or c.ExaStatus is null) and c.auditStatus !='BC' and c.relDocType != 'ZJSQDLBF') "
	),
	array (
		"name" => "productCodeSearch",
		"sql" => " and p.productCode like  concat('%',#,'%') "
	),
	array (
		"name" => "productNameSearch",
		"sql" => " and p.productName like  concat('%',#,'%') "
	),
	array (
		"name" => "supplierNameSearch",
		"sql" => " and p.supplierName like  concat('%',#,'%')  "
	)
)
?>