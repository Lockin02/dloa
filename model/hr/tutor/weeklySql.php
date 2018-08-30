<?php

/**
 * @author Administrator
 * @Date 2012-08-24 14:38:04
 * @version 1.0
 * @description:新员工周报表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.isSign,c.state,c.tutorId ,c.studentDeptId ,c.studentDeptName ,c.studentNo ,c.studentName ,c.studentAccount ,c.studentJob ,c.studentJobId ,c.userNo ,c.userAccount ,c.userName ,c.lastweekSummary ,c.nextweekSummary ,c.problem ,c.idea ,c.guideIdea ,c.signDate ,c.createId ,c.createName ,date_format(c.createTime,'%Y-%m-%d') as createTime ,c.updateId ,c.updateTime ,c.updateName ,c.submitDate from oa_hr_tutor_weekly c where 1=1 "
);

$condition_arr = array (
	array(
		"name"=>"isSign",
		"sql"=>" and c.isSign=#"
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "tutorId",
		"sql" => " and c.tutorId=# "
	),
	array (
		"name" => "studentDeptId",
		"sql" => " and c.studentDeptId=# "
	),
	array (
		"name" => "studentDeptName",
		"sql" => " and c.studentDeptName=# "
	),
	array (
		"name" => "studentDeptNameSearch",
		"sql" => " and c.studentDeptName LIKE CONCAT('%',#,'%')"
	),
	array (
		"name" => "studentNo",
		"sql" => " and c.studentNo=# "
	),
	array (
		"name" => "studentName",
		"sql" => " and c.studentName=# "
	),
	array (
		"name" => "studentNameSearch",
		"sql" => " and c.studentName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "studentAccount",
		"sql" => " and c.studentAccount=# "
	),
	array (
		"name" => "studentJob",
		"sql" => " and c.studentJob=# "
	),
	array (
		"name" => "studentJobSearch",
		"sql" => " and c.studentJob LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "studentJobId",
		"sql" => " and c.studentJobId=# "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "userNameSearch",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "lastweekSummary",
		"sql" => " and c.lastweekSummary=# "
	),
	array (
		"name" => "nextweekSummary",
		"sql" => " and c.nextweekSummary=# "
	),
	array (
		"name" => "problem",
		"sql" => " and c.problem=# "
	),
	array (
		"name" => "idea",
		"sql" => " and c.idea=# "
	),
	array (
		"name" => "state",
		"sql" => " and c.state in(arr) "
	),
	array (
		"name" => "guideIdea",
		"sql" => " and c.guideIdea=# "
	),
	array (
		"name" => "signDate",
		"sql" => " and c.signDate=# "
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
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	)
)
?>