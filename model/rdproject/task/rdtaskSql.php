<?php
$sql_arr = array (
	"select_default" => "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName," .
			"c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration," .
			"c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName," .
			"c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime ,p.finishGrade".
			" from oa_rd_task c left join (select db.taskId,db.finishGrade from ( select o.taskId,o.finishGrade from oa_rd_task_over o order by o.id desc) db group by db.taskId) p on c.id=p.taskId  where 1=1 ",

	"select_gridinfo"=> "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName," .
			"c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration," .
			"c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName," .
			"c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,".
			" 1 as leaf from oa_rd_task c where 1=1 ",

	"schedule_list" => "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.status,c.warpRate,c.chargeName,c.effortRate,c.appraiseWorkload,c.putWorkload " .
			" from oa_rd_task c where 1=1 ",

	"select_tk_actuser"=>"select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.status,c.warpRate,c.chargeName,c.effortRate,c.appraiseWorkload,c.putWorkload," .
			"c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate" .
			" from oa_rd_task c where c.id in(select distinct u.taskId from oa_rd_task_act_user u where ",

	"select_myreceived"=>"select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName," .
			"c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration," .
			"c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName," .
			"c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime".
			" from oa_rd_task c where c.status<>'WFB' and c.id in(select distinct u.taskId from oa_rd_task_act_user u where ",

	"work_schedule" =>"select
						c.id,a.userName,c.effortRate ,c.projectName,c.name ,c.status,c.warpRate,c.updateTime,a.actUserId
						from
						oa_rd_task c left join
						oa_rd_task_act_user a  on c.id  = a.taskId ,user u
						where
						u.USER_ID = a.actUserId ",
	"taskJsonForLoad" => "select
						c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName,
						c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration,
						c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.createId,c.createName,c.createTime,c.updateTime
						from
						oa_rd_task c left join
						oa_rd_task_act_user u on c.id = u.taskId where" .
								"  c.status <> 'TG' and c.status <> 'QZZZ' and c.status <> 'WFB' ",
	"getCountByPlanId" => "select count(c.id) as tasknum from oa_rd_task c where 1=1 ",
	"select_pjtask"=>"select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.planBeginDate,c.planEndDate from oa_rd_task c where 1=1 "
);

$condition_arr = array (
	array(
		"name"=>"ids",
		"sql"=>" and c.id in(arr)"
	),
	array(
		"name"=>"status",
		"sql"=>" and c.status=#"
	),
	array(
		"name"=>"priority",
		"sql"=>" and c.priority=#"
	),
	array (
		"name" => "chargeId",
		"sql" => " and c.chargeId=#"
	),
	array (
		"name" => "publishId",
		"sql" => " and c.publishId=#"
	),
	array(
		"name"=>"createId",
		"sql"=>" and c.createId=#"
	),
	array(
		"name"=>"belongNodeId",
		"sql"=>" and c.belongNodeId=#"
	),
	array(
		"name" => "taskIds",
		"sql" => " and c.id in(arr)"
	),
	array(
		"name"=>"actUserId",
		"sql"=>" and u.actUserId =#"
	),
	array(
		"name"=>"o_actUserId",
		"sql"=>" or u.actUserId =#"
	),
	array(
		"name"=>"u_actUserId",
		"sql"=>" u.actUserId=#)"
		),
	array(
		"name"=>"actEndDateD",
		"sql"=>" and c.actEndDate>=#"
	),
	array(
		"name"=>"actEndDateX",
		"sql"=>" and c.actEndDate<=#"
	),
	// 工作进展
	array(
		"name" => "updateTimeD",
		"sql" => " and date_format(c.updateTime,'%Y-%m-%d') >= # "
	),
	array(
		"name" => "updateTimeX",
		"sql" => " and date_format(c.updateTime,'%Y-%m-%d') <= # "
	),
	array(
		"name" => "projectId",
		"sql" => "and c.projectId = #"
	),
	array(
		"name" => "projectIds",
		"sql" => "and c.projectId in(arr)"
	),
	array(
		"name" => "personIds",
		"sql" => "and a.actUserId in(arr) "
	),
	array(
		"name" => "depId",
		"sql" => " and u.DEPT_ID = # "
	),
	//工作进展,
	array(
		"name"=>"pCreateId",
		"sql"=>" or (c.planId is null and c.createId=#)"
	),
	array(
		"name"=>"oPublishId",
		"sql"=>" and (c.status!='WFB' and c.publishId=#) "
	),
	array(
		"name"=>"instatus",
		"sql"=>" and c.status in(arr)"
	),
	//我审核的任务导出 查询条件 start
	array(
		"name"=>"ids2",
		"sql"=>" and id in(arr)"
	),
	array(
		"name"=>"instatus2",
		"sql"=>" and status in(arr)"
	)
	//end
	,array(
		"name" => "ajaxTaskName",
		"sql" => " and c.name=# "
	),
	array(
		"name"=>"name",
		"sql"=>" and c.name like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"planName",
		"sql"=>" and c.planName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"projectName",
		"sql"=>" and c.projectName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"planId",
		"sql"=>" and c.planId=#"
	),
	array(
		"name"=>"publishName",
		"sql"=>" and c.publishName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"chargeName",
		"sql"=>" and c.chargeName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"d_planBeginDate",
		"sql"=>" and c.planBeginDate >=#"
	),
	array(
		"name"=>"x_planEndDate",
		"sql"=>" and c.planEndDate <=#"
	),
	array(
		"name"=>"pjId",
		"sql"=>" and c.projectId =#"
	),
	array(
		"name"=>"ExaDT",
		"sql"=>" and c.ExaDT =#"
	)
);
?>