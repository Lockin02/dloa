<?php
/*
 * Created on 2010-9-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	'base_list' => 'select c.id,c.weekTitle,c.weekBeginDate,c.weekEndDate,c.depName,c.createId,c.createName,c.updateTime from oa_rd_worklog_week c where 1=1 ',
	'checkIsSet' => "select c.id from oa_rd_worklog_week c where 1=1 ",
	'search_list' => 'select c.id,c.weekTitle,c.weekBeginDate,c.weekEndDate,c.depName,c.createName,c.updateTime,w.projectId ' .
			'from oa_rd_worklog_week c left join oa_rd_worklog w on c.id = w.weekId where 1=1 ',
	'select_inner' => 'select c.id,c.weekTitle,c.depName,w.projectId,w.executionDate,w.effortRate,w.workloadDay,w.workloadSurplus,w.projectName,w.taskName,w.updateTime,w.planEndDate,w.createName ' .
			'from oa_rd_worklog_week c inner join oa_rd_worklog w on c.id = w.weekId where 1=1 ',
	'subordinateLog' => 'select w.id,w.weekTitle,w.depName,w.updateTime,m.memberName,w.createId,w.createName from oa_rd_worklog_week w ,oa_rd_team_member m where w.createName = m.memberName ',
	'view_inproject' => 'select w.id,w.weekTitle ,w.createName,w.updateTime,w.depName,l.projectName,l.projectId from oa_rd_worklog_week w left join oa_rd_worklog l on w.id = l.weekId where 1=1 '
);

$condition_arr = array (
	array (
		"name" => "user_id",//合同状态
		"sql" => "and c.createId = # "
	),
	array (
		"name" => "startDate",//
		"sql" => "and c.weekBeginDate <= #"
	),
	array (
		"name" => "endDate",//
		"sql" => "and c.weekEndDate >= # "
	),
	array (//起始时间
		"name" => "beginDate",
		"sql" => "and c.weekEndDate >= #"
	),
	array (//结束时间
		"name" => "overDate",
		"sql" => "and c.weekBeginDate <= # "
	),
	array (//人员ID
		"name" => "personIds",
		"sql" => "and c.createId in ( # ) "
	),
	array (//执行日期
		"name" => "executionDate",
		"sql" => "and w.executionDate =# "
	),
	array (//填写人
		"name" => "wcreateName",
		"sql" => "and w.createName like CONCAT('%',#,'%')"
	),
	array (//任务名称
		"name" => "wtaskName",
		"sql" => "and w.taskName like CONCAT('%',#,'%')"
	),
	array (//项目名称
		"name" => "wprojectName",
		"sql" => "and w.projectName like CONCAT('%',#,'%')"
	),
	array(//部门ID
		"name" => "departmentIds",
		"sql" => "and c.depId in ( # )"
	),array(
		"name" => "w_projectId",
		"sql" => " and w.projectId = # "
	),
	array (//人员名称搜索
		"name" => "createName1",
		"sql" => " and w.createName like CONCAT('%',#,'%')"
	),
	array (//部门名称搜索
		"name" => "depName1",
		"sql" => " and w.depName like CONCAT('%',#,'%')"
	),
	array(//按人检索周志
		"name" => "createName",
		"sql" => " and c.createName like CONCAT('%',#,'%')"
	),
	array (//人员ID
		"name" => "projectId",
		"sql" => "and l.projectId = #  "
	)
);
?>
