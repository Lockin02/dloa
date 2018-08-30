<?php
/**
 * @author show
 * @Date 2013年11月15日 16:10:52
 * @version 1.0
 * @description:项目设备申请明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.resourceId ,c.resourceName ,
			c.resourceTypeId ,c.resourceTypeName ,c.number ,c.exeNumber ,c.backNumber ,
			c.unit ,c.price ,c.amount ,c.planBeginDate ,c.planEndDate ,c.useDays ,c.remark ,c.feeBack ,c.status
		 from oa_esm_resource_applydetail c where 1=1 ",
	"select_count" => "select sum(c.amount) as amount
		 from oa_esm_resource_applydetail c where 1"
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
		"name" => "resourceId",
		"sql" => " and c.resourceId=# "
	),
	array (
		"name" => "resourceCode",
		"sql" => " and c.resourceCode=# "
	),
	array (
		"name" => "resourceName",
		"sql" => " and c.resourceName=# "
	),
	array (
		"name" => "resourceTypeId",
		"sql" => " and c.resourceTypeId=# "
	),
	array (
		"name" => "resourceTypeCode",
		"sql" => " and c.resourceTypeCode=# "
	),
	array (
		"name" => "resourceTypeName",
		"sql" => " and c.resourceTypeName=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "exeNumber",
		"sql" => " and c.exeNumber=# "
	),
	array (
		"name" => "backNumber",
		"sql" => " and c.backNumber=# "
	),
	array (
		"name" => "unit",
		"sql" => " and c.unit=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "amount",
		"sql" => " and c.amount=# "
	),
	array (
		"name" => "planBeginDate",
		"sql" => " and c.planBeginDate=# "
	),
	array (
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array (
		"name" => "useDays",
		"sql" => " and c.useDays=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		'name' => 'isConfirm',
		'sql' => ' and c.resourceId <> 0'
	),
	array(
		'name' => 'isNotConfirm',
		'sql' => ' and c.resourceId = 0'
	),
	array(
		'name' => 'isNotDetail',
		'sql' => ' and c.number > c.exeNumber'
	),
	array(
		'name' => 'status',
		'sql' => ' and c.status=# '
	)
)
?>