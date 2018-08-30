<?php

/**
 * @author Show
 * @Date 2012年8月30日 星期四 15:57:17
 * @version 1.0
 * @description:员工试用计划模板明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.planId ,c.taskType ,c.taskTypeName ,c.taskName ,c.description ,c.managerName ,
			c.managerId ,c.weights ,c.taskScore ,c.beforeId ,c.beforeName,c.closeType,c.isNeed,c.isRule,c.id as taskId,
            c.beforeName as beforeTaskName
		from oa_hr_baseinfo_trialplantem_detail c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "planId",
		"sql" => " and c.planId=# "
	),
	array (
		"name" => "taskType",
		"sql" => " and c.taskType=# "
	),
	array (
		"name" => "taskTypeName",
		"sql" => " and c.taskTypeName=# "
	),
	array (
		"name" => "taskName",
		"sql" => " and c.taskName=# "
	),
	array (
		"name" => "taskNameSearch",
		"sql" => " and c.taskName like concat('%',#,'%')"
	),
	array (
		"name" => "description",
		"sql" => " and c.description=# "
	),
	array (
		"name" => "managerName",
		"sql" => " and c.managerName=# "
	),
	array (
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array (
		"name" => "weights",
		"sql" => " and c.weights=# "
	),
	array (
		"name" => "taskScore",
		"sql" => " and c.taskScore=# "
	),
	array (
		"name" => "beforeId",
		"sql" => " and c.beforeId=# "
	),
	array (
		"name" => "beforeName",
		"sql" => " and c.beforeName=# "
	)
)
?>