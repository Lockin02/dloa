<?php

/**
 * @author Show
 * @Date 2012年5月26日 星期六 11:40:48
 * @version 1.0
 * @description:导师经历信息表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.userNo ,c.userAccount ,c.userName ,c.jobId ,c.jobName ,c.deptId ,c.deptName ,c.studentNo ,c.status,c.studentSuperior ,c.studentSuperiorId ,c.studentJob ,c.studentJobId ,c.studentAccount ,c.studentName ,c.studentDeptId ,c.studentDeptName ,c.beginDate ,c.endDate ,c.assessmentScore ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.remark,c.isWeekly,c.isCoachplan,c.closeReason,o.rewardPrice,p.becomeDate,p.realBecomeDate
	from oa_hr_tutor_records c
	left join oa_hr_tutor_rewardinfo o on c.id=o.tutorId
	left join oa_hr_personnel p on c.studentNo=p.userNo
	where 1=1 ",
	//连表查询导师奖励的审批状态,以及是否发布奖励信息
	"ExaStatus"=>"select c.id ,c.userNo ,c.userAccount ,c.userName ,c.jobId ,c.jobName ,c.deptId ,c.deptName ,c.studentNo ,c.status,c.studentSuperior ,c.studentSuperiorId ,c.studentJob ,c.studentJobId ,c.studentAccount ,c.studentName ,c.studentDeptId ,c.studentDeptName ,c.beginDate ,c.endDate ,c.assessmentScore ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.remark,c.isWeekly ,c.isCoachplan ,c.closeReason ,IF(c.rewardPrice!='',c.rewardPrice,o.rewardPrice) as rewardPrice ,p.becomeDate ,p.realBecomeDate ,b.ExaStatus ,b.isPublish ,s.selfgraded ,s.superiorgraded ,s.hrgraded ,s.assistantgraded ,s.staffgraded ,s.id as schemeId
	from oa_hr_tutor_records c
	left join oa_hr_tutor_scheme s on c.id=s.tutorId
	left join oa_hr_tutor_rewardinfo o on c.id=o.tutorId
	left join oa_hr_personnel p on c.studentNo=p.userNo
	left join oa_hr_tutor_reward b on b.id=o.rewardId
	where 1=1 "
);

$condition_arr = array (
	array(
		"name"=>"status",
		"sql"=>" and c.status=#"
	),
	array(
		"name"=>"statusArr",
		"sql"=>" and c.status in(arr)"
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "studentSuperiorId",
		"sql" => " and c.studentSuperiorId=#"
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userNo2",
		"sql" => " and (c.userNo=# or c.studentNo=#) "
	),
	array (
		"name" => "userNoArr",
		"sql" => " and c.userNo in(arr) "
	),
	array (
		"name" => "userNoM",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array (
		"name" => "userNameM",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array (
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "deptIdArr",
		"sql" => " and c.deptId in(arr) "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "studentNo",
		"sql" => " and c.studentNo=# "
	),
	array (
		"name" => "studentAccount",
		"sql" => " and c.studentAccount=# "
	),
	array (
		"name" => "studentNameM",
		"sql" => " and c.studentName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "studentName",
		"sql" => " and c.studentName=# "
	),
	array (
		"name" => "studentDeptId",
		"sql" => " and c.studentDeptId=# "
	),
	array (
		"name" => "studentDeptNameM",
		"sql" => " and c.studentDeptName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "studentDeptName",
		"sql" => " and c.studentDeptName=# "
	),
	array (
		"name" => "beginDate",
		"sql" => " and c.beginDate=# "
	),
	array (
		"name" => "endDate",
		"sql" => " and c.endDate=# "
	),
	array (
		"name" => "assessmentScore",
		"sql" => " and c.assessmentScore=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array (
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array (
		"name" => "personIdSearch",
		"sql" => "and (c.userAccount =# or c.studentAccount =#)"
	)
)
?>