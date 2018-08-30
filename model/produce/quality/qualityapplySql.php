<?php

/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 10:47:28
 * @version 1.0
 * @description:质检申请单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.docCode ,c.relDocType ,c.relDocCode ,c.relDocId ,c.applyUserName ,c.applyUserCode ,
			c.workDetail ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.status,c.closeTime,
			c.closeUserName,c.closeUserId,c.supplierId,c.supplierName
		from oa_produce_quality_apply c where 1=1 ",
	"select_detail" => "select c.docCode ,c.relDocType ,c.relDocCode ,c.relDocId ,c.applyUserName ,c.applyUserCode ,
			c.workDetail ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.status,c.closeTime,
			c.closeUserName,c.closeUserId,c.supplierId,c.supplierName,i.id,i.mainId,i.relDocItemId ,i.productId ,i.productCode ,
			i.productName ,i.pattern ,i.unitName ,i.fittings ,i.qualityNum ,i.assignNum ,i.standardNum ,i.planEndDate ,i.orderNum,
			i.status as detailStatus,i.dealUserName,i.dealUserId,i.checkType,i.checkTypeName,i.dealTime,i.complatedNum,i.passReason,
			i.batchNum,i.serialId,i.serialName,i.receiveStatus,i.receiveId,i.receiveName,i.receiveTime
		from oa_produce_quality_apply c inner join oa_produce_qualityapply_item i on c.id = i.mainId where 1=1"
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
		"name" => "relDocType",
		"sql" => " and c.relDocType=# "
	),
	array (
		"name" => "relDocTypeArr",
		"sql" => " and c.relDocType in(arr) "
	),
	array (
		"name" => "relDocCode",
		"sql" => " and c.relDocCode=# "
	),
	array (
		"name" => "relDocCodeSearch",
		"sql" => " and c.relDocCode like concat('%',#,'%') "
	),
	array (
		"name" => "relDocId",
		"sql" => " and c.relDocId=# "
	),
	array (
		"name" => "applyUserName",
		"sql" => " and c.applyUserName=# "
	),
	array (
		"name" => "applyUserCode",
		"sql" => " and c.applyUserCode=# "
	),
	array (
		"name" => "workDetail",
		"sql" => " and c.workDetail=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createNameSearch",
		"sql" => " and c.createName like concat('%',#,'%') "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusArr",
		"sql" => " and c.status in(arr)"
	),
	array (
		"name" => "detailStatusArr",
		"sql" => " and i.status in(arr)"
	),
	array (
		"name" => "iProductNameSearch",
		"sql" => " and i.productName like concat('%',#,'%') "
	),
	array (
		"name" => "iProductCodeSearch",
		"sql" => " and i.productCode like concat('%',#,'%') "
	),
    array (
        "name" => "iStatus",
        "sql" => " and i.status = #"
    )
);