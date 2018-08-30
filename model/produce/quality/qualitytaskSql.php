<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 15:09:09
 * @version 1.0
 * @description:交检任务单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.docCode ,c.applyCode ,c.applyId ,c.chargeUserName ,c.chargeUserCode ,
			c.acceptStatus ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.acceptTime,
			c.complatedTime,c.relDocType,c.relDocTypeName
		from oa_produce_quality_task c where 1=1 ",
	'select_detail'=>'select  c.id ,c.docCode ,c.applyCode ,c.applyId ,c.chargeUserName ,c.chargeUserCode ,
			c.acceptStatus ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.acceptTime,
			c.complatedTime,p.productCode,p.productName,p.pattern,p.checkStatus,p.checkTypeName,p.unitName,c.relDocType,c.relDocTypeName
        from oa_produce_quality_task c LEFT JOIN oa_produce_quality_taskitem p ON c.id=p.mainId'
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
		"sql" => " and c.docCode like concat('%',#,'%')"
	),
	array (
		"name" => "applyCode",
		"sql" => " and c.applyCode=# "
	),
	array (
		"name" => "applyCodeSearch",
		"sql" => " and c.applyCode like concat('%',#,'%')"
	),
	array (
		"name" => "applyId",
		"sql" => " and c.applyId=# "
	),
	array (
		"name" => "chargeUserName",
		"sql" => " and c.chargeUserName=# "
	),
	array (
		"name" => "chargeUserCode",
		"sql" => " and c.chargeUserCode=# "
	),
	array (
		"name" => "acceptStatus",
		"sql" => " and c.acceptStatus=# "
	),
	array (
		"name" => "acceptStatusArr",
		"sql" => " and c.acceptStatus in(arr) "
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
		"name" => "createName",
		"sql" => " and c.createName=# "
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
		"name" => "productCodeSearch",
		"sql" => " and p.productCode like concat('%',#,'%') "
	),
	array (
		"name" => "productNameSearch",
		"sql" => " and p.productName like concat('%',#,'%') "
	),
	array (
		"name" => "relDocType",
		"sql" => " and c.relDocType=# "
	)
);