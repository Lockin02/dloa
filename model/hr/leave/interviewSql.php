<?php
/**
 * @author Administrator
 * @Date 2012-08-07 16:06:30
 * @version 1.0
 * @description:离职--面谈记录表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.leaveId ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.entryDate ,c.deptId ,c.jobName ,c.jobId ,c.quitDate ,c.interviewer,c.interviewerId ,c.interviewContent ,c.interviewDate ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime from oa_hr_leave_Interview c where 1=1 "
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "leaveId",
		"sql" => " and c.leaveId=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array(
		"name" => "userNoSearch",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array(
		"name" => "userNameSearch",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array(
		"name" => "deptNameSearch",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array(
		"name" => "jobNameSearch",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "quitDate",
		"sql" => " and c.quitDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "interviewer",
		"sql" => " and c.interviewer LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "interviewerId",
		"sql" => " and c.interviewerId=#   "
	),
	array(
		"name" => "interviewContent",
		"sql" => " and c.interviewContent=# "
	),
	array(
		"name" => "interviewDate",
		"sql" => " and c.interviewDate=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array(
		"name"=>"interviewerIdSearch",
		"sql"=>"and c.id in(select parentId from oa_hr_leave_interviewDetail where interviewerId=#)"
	)
)
?>