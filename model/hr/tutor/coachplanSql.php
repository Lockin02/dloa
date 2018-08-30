<?php
/**
 * @author Administrator
 * @Date 2012-08-23 17:15:29
 * @version 1.0
 * @description:员工辅导计划表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.tutorId ,c.studentDeptId ,c.studentDeptName ,c.studentNo ,c.studentName ,c.studentAccount ,c.studentJob ,c.studentJobId ,c.userNo ,c.userAccount ,c.userName ,c.jobId ,c.jobName ,c.deptId ,c.deptName ,c.goal ,c.studentSuperior ,c.studentSuperiorId ,DATE_FORMAT(c.createTime,'%Y-%m-%d') as createTime ,c.ExaStatus from oa_hr_tutor_coachplan c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "tutorId",
		"sql" => " and c.tutorId=# "
	),
	array(
		"name" => "studentDeptId",
		"sql" => " and c.studentDeptId=# "
	),
	array(
		"name" => "studentDeptName",
		"sql" => " and c.studentDeptName=#"
	),
	array(
		"name" => "studentDeptNameSearch",
		"sql" => " and c.studentDeptName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "studentNo",
		"sql" => " and c.studentNo=# "
	),
	array(
		"name" => "studentName",
		"sql" => " and c.studentName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "studentNameSearch",
		"sql" => " and c.studentName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "studentAccount",
		"sql" => " and c.studentAccount=# "
	),
	array(
		"name" => "studentJob",
		"sql" => " and c.studentJob=# "
	),
	array(
		"name" => "studentJobSearch",
		"sql" => " and c.studentJob LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "studentJobId",
		"sql" => " and c.studentJobId=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
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
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array(
		"name" => "jobNameSearch",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
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
		"name" => "goal",
		"sql" => " and c.goal=# "
	)
)
?>