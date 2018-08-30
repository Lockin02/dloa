<?php
/**
 * @author Show
 * @Date 2011年11月28日 星期一 15:05:47
 * @version 1.0
 * @description:项目状态报告(oa_esm_project_statusreport) sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.weekProcess ,c.handupDate," .
		"c.feeAll ,c.budgetAll ,c.feeAllProcess ,c.projectProcess ,c.description ,c.warningNum ," .
		"c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.budgetField," .
		"c.feeField,c.feeFieldProcess,c.confirmName,c.confirmId,c.confirmDate,c.planEndDate,c.actEndDate,c.exgross," .
		"c.ExaStatus,c.feeEqu,c.feePerson,c.feeOutsourcing,c.weekNo,c.ExaDT,c.score,c.warningLevel,c.warningLevelId ,c.beginDate ,c.endDate " .
		"from oa_esm_project_statusreport c where 1 ",
	"select_report" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.weekProcess ,c.handupDate," .
		"c.feeAll ,c.budgetAll ,c.feeAllProcess ,c.projectProcess ,c.description ,c.warningNum ," .
		"c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.budgetField," .
		"c.feeField,c.feeFieldProcess,c.confirmName,c.confirmId,c.confirmDate,c.planEndDate,c.actEndDate,p.officeId,p.officeName," .
		"c.exgross,c.ExaStatus,c.feeEqu,c.feePerson,c.feeOutsourcing,c.ExaDT,c.score " .
		"from oa_esm_project_statusreport c left join oa_esm_project p on c.projectId = p.id where 1=1 ",
	"select_audit" => "select
			c.id ,c.projectId ,c.projectCode ,c.projectName ,c.weekProcess ,c.handupDate,c.projectProcess ,c.weekNo,c.ExaDT,c.score,c.beginDate ,c.endDate,
			p.id as spid,c.warningLevel,c.warningLevelId,c.exgross,c.warningNum
		from
			oa_esm_project_statusreport c
			left join
			wf_task w on c.id = w.Pid
			inner join
			flow_step_partent p on w.task = p.wf_task_id
			left join (
				SELECT
					group_concat( to_id)  as to_ids  , from_id
				FROM
					power_set
				where
					to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1
				group by FROM_ID
			)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
		where w.examines = '' ",
	"select_audited" => "select
			c.id ,c.projectId ,c.projectCode ,c.projectName ,c.weekProcess ,c.handupDate,c.projectProcess ,c.weekNo,c.ExaDT,c.score,
			p.id as spid,p.content,p.Endtime,c.warningLevel,c.warningLevelId ,c.beginDate ,c.endDate,c.exgross,c.warningNum
			from
			    oa_esm_project_statusreport c
			left join
			wf_task w on c.id = w.Pid
			inner join
			flow_step_partent p on w.task = p.wf_task_id
			left join (
				SELECT
					group_concat( to_id)  as to_ids  , from_id
				FROM
					power_set
				where
					to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1
				group by FROM_ID
			)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
		where 1 "
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array(
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array(
		"name" => "weekProcess",
		"sql" => " and c.weekProcess=# "
	),
	array(
		"name" => "handupDate",
		"sql" => " and c.handupDate=# "
	),
	array(
		"name" => "handupDateSearch",
		"sql" => " and c.handupDate like BINARY concat('%',#,'%') "
	),
	array(
		"name" => "feeAll",
		"sql" => " and c.feeAll=# "
	),
	array(
		"name" => "budgetAll",
		"sql" => " and c.budgetAll=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array(
		"name" => "statusIn",
		"sql" => " and c.status in(arr) "
	),
	array(
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr) "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName like concat('%',#,'%') "
	),
	array(
		"name" => "officeIds",
		"sql" => " and p.officeId in(arr) "
	),
	array(
		"name" => "wcode",
		"sql" => " and w.code =# "
	),
	array(
		"name" => "pflag",
		"sql" => " and p.Flag =# "
	),
	array(
		"name" => "findInName",//负责人
		"sql" => " and ( find_in_set( # , p.User ) > 0 or find_in_set( # , powi.to_ids ) > 0) "
	)
);