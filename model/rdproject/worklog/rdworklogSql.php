<?php
/*
 * Created on 2010-9-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	'dashboard_list' =>'select c.id,c.projectName,c.projectCode,c.projectId,c.taskId,c.taskName,c.weekId ,c.executionDate' .
			',c.effortRate ,t.effortRate as taskrff,t.warpRate ,c.workloadDay ,c.workloadSurplus ,c.planEndDate,t.projectId,t.status,c.updateTime,c.createName ' .
			'from oa_rd_worklog c left join oa_rd_task t on c.taskId = t.id where 1=1 ',
	'base_list' => 'select c.id,c.projectName,c.projectCode,c.projectId,c.taskId,c.taskName,c.weekId ,c.executionDate' .
			',c.effortRate ,c.warpRate ,c.workloadDay ,c.workloadSurplus ,c.planEndDate,c.updateTime,c.createName,c.description,c.problem  from oa_rd_worklog c where 1=1 ',
	'checkIsSet' => "select c.id from oa_rd_worklog c where 1=1 and now('Y-m-d') > c.weekBeginDate and now('Y-m-d') < c.weekEndDate ",
	'getWorkDay' =>'select c.id,c.executionDate' .
			',c.workloadDay ' .
			'from oa_rd_worklog c left join oa_rd_task t on c.taskId = t.id where 1=1 ',
	//任务详细日志 - 负责人看的部分（包含项目内参与人的日志）
	'readLogIntask' => 'select c.id,c.createName,c.executionDate,c.effortRate,c.workloadDay,c.description from oa_rd_worklog c' .
			' where 1=1 ',
	'getWorklogByUserGPid' => 'select sum(c.workloadDay) as workload,c.createName from oa_rd_worklog c where 1=1 ',
	'getAllWorklogByPjId'=>'select sum(c.workloadDay) as workload from oa_rd_worklog c where 1=1 ',
	'dashLoadSpead' => 'select c.projectName as dataName,sum(c.workloadDay) as dataNumber ,b.appraiseWorkload as appraiseWorkload from oa_rd_worklog c inner join oa_rd_task t on (t.id=c.taskId) inner join oa_rd_project b on(b.id=t.projectId )where 1=1 ',
	'dashLoadSpeadByTask' => 'select c.taskName as dataName,sum(c.workloadDay) as dataNumber ,t.appraiseWorkload as appraiseWorkload from oa_rd_worklog c inner join oa_rd_task t on (t.id=c.taskId) where 1=1 '
);

$condition_arr = array (
	array (
		"name" => "weekId",//合同状态
		"sql" => "and c.weekId = # "
	),
	array (
		"name" => "projectId",
		"sql" => "and c.projectId = # "
	),
	array (
		"name" => "projectIds",
		"sql" => "and c.projectId in(arr) "
	),
	array (
		"name" => "user_id",
		"sql" => "and c.createId  = # "
	),
	array (
		"name" => "memberIds",
		"sql" => "and c.createId  in(arr) "
	),
	array (
		"name" => "beginDate",
		"sql" => "and c.executionDate >= #"
	),
	array (
		"name" => "overDate",
		"sql" => "and c.executionDate <= #"
	),
	array (
		"name" => "ids",
		"sql" => "and c.id in(arr) "
	),
	array(
		"name" => "taskId",
		"sql" => " and c.taskId = #"
	),
	array(
		"name" => "s_projectName",
		"sql" => " and c.projectName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "s_taskName",
		"sql" => " and c.taskName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "laterDate",
		"sql" => " and c.executionDate > #"
	),
	array(
		"name" => "executionDate",
		"sql" => " and c.executionDate = #"
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId = #"
	)
);
?>
