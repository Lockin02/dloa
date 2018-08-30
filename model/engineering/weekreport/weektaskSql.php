<?php
/**
 * @author show
 * @Date 2013年10月14日 16:23:42
 * @version 1.0
 * @description:项目周任务进度 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.activityName ,c.activityId ,c.workloadDone ,c.process ,c.thisWorkloadDone ,c.thisWeekProcess ,c.prevWorkloadDone ,c.prevWeekProcess ,c.isTask  from oa_esm_project_weektask c where 1=1 "
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
		"name" => "activityName",
		"sql" => " and c.activityName=# "
	),
	array (
		"name" => "activityId",
		"sql" => " and c.activityId=# "
	),
	array (
		"name" => "workloadDone",
		"sql" => " and c.workloadDone=# "
	),
	array (
		"name" => "process",
		"sql" => " and c.process=# "
	),
	array (
		"name" => "thisWorkloadDone",
		"sql" => " and c.thisWorkloadDone=# "
	),
	array (
		"name" => "thisWeekProcess",
		"sql" => " and c.thisWeekProcess=# "
	),
	array (
		"name" => "prevWorkloadDone",
		"sql" => " and c.prevWorkloadDone=# "
	),
	array (
		"name" => "prevWeekProcess",
		"sql" => " and c.prevWeekProcess=# "
	),
	array (
		"name" => "isTask",
		"sql" => " and c.isTask=# "
	)
)
?>