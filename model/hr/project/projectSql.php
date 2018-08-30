<?php
/**
 * @author Administrator
 * @Date 2012-05-30 19:25:55
 * @version 1.0
 * @description:项目经历 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobName ,c.jobId ,c.projectName ,c.projectPlace ,c.projectManager ,c.projectManagerId ,c.beginDate ,c.closeDate ,c.projectRole ,c.projectContent ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,p.staffName,p.highSchool,p.professionalName,p.companyType,p.companyName,p.deptNameS,p.deptNameT,p.deptNameF,p.personnelTypeName,p.wageLevelName,p.jobLevel
	from oa_hr_personnel_project c
	left join oa_hr_personnel p on c.userNo=p.userNo
	where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo =# "
	),
	array(
		"name" => "userNoArr",
		"sql" => " and c.userNo in(arr) "
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
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array(
		"name" => "deptNameSearch",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%')  "
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
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array(
		"name" => "projectNameSearch",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectPlace",
		"sql" => " and c.projectPlace LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "projectManager",
		"sql" => " and c.projectManager=# "
	),
	array(
		"name" => "projectManagerSearch",
		"sql" => " and c.projectManager LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "projectManagerId",
		"sql" => " and c.projectManagerId=# "
	),
	array(
		"name" => "beginDate",
		"sql" => " and c.beginDate=# "
	),
	array(
		"name" => "beginDateSearch",
		"sql" => " and c.beginDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "closeDate",
		"sql" => " and c.closeDate=# "
	),
	array(
		"name" => "closeDateSearch",
		"sql" => " and c.closeDate LIKE BINARY CONCAT('%',#,'%')"
	),
	array(
		"name" => "projectRole",
		"sql" => " and c.projectRole=# "
	),
	array(
		"name" => "projectContent",
		"sql" => " and c.projectContent LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array(
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array(
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array(
		"name" => "staffNameSearch",
		"sql" => " and c.staffNameSearch LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "staffNameSearch",
		"sql" => " and c.staffNameSearch=# "
	)
)
?>