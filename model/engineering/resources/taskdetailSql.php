<?php
/**
 * @author show
 * @Date 2013年11月23日 14:30:59
 * @version 1.0
 * @description:项目设备任务单明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.taskId ,c.applyDetailId ,c.resourceId ,c.resourceName ,
			c.resourceTypeId ,c.resourceTypeName ,c.number ,c.exeNumber ,c.unit ,c.planBeginDate ,
			c.planEndDate ,c.actBeginDate ,c.actEndDate ,c.useDays ,c.remark ,c.awaitNumber ,c.number as maxNumber ,c.backNumber
		from oa_esm_resource_taskdetail c where 1=1 ",
	"select_area" => "select
			sum(c.number-c.exeNumber-c.backNumber) as waitNum,areaName,areaId
		from
			oa_esm_resource_taskdetail c
				inner join
			oa_esm_resource_task t
				on c.taskId = t.id
		where c.exeNumber+c.backNumber < c.number"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "taskId",
		"sql" => " and c.taskId=# "
	),
	array (
		"name" => "applyDetailId",
		"sql" => " and c.applyDetailId=# "
	),
	array (
		"name" => "resourceId",
		"sql" => " and c.resourceId=# "
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
		"name" => "unit",
		"sql" => " and c.unit=# "
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
		"name" => "actBeginDate",
		"sql" => " and c.actBeginDate=# "
	),
	array (
		"name" => "actEndDate",
		"sql" => " and c.actEndDate=# "
	),
	array (
		"name" => "useDays",
		"sql" => " and c.useDays=# "
	),
	array (
		"name" => "waitNumGt",//未发货数量条件
		"sql" => " and (c.number-c.exeNumber-c.backNumber) > # "
	)
)
?>