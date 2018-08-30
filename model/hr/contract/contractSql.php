<?php
/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userName ,c.userNo ,c.userAccount ,c.conNo ,c.conName ,c.conType ,c.conTypeName ,c.conState ,c.conStateName ,c.beginDate ,c.jobName ,c.jobId , c.trialBeginDate, c.trialEndDate ,c.closeDate ,c.conNum ,c.conNumName ,c.conContent ,c.recorderName ,c.recorderId ,c.recordDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,p.staffName ,p.highSchool ,p.professionalName ,p.companyType ,p.companyName ,p.deptName ,p.deptNameS ,p.deptNameT ,p.deptNameF ,p.personnelTypeName ,p.wageLevelName ,p.jobLevel
	from oa_hr_personnel_contract c
	left join oa_hr_personnel p on c.userNo=p.userNo
	where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userNoSelect",
		"sql" => " and c.userNo =# "
	),
	array(
		"name" => "userNoArr",
		"sql" => " and c.userNo in(arr) "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "conNo",
		"sql" => " and c.conNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "conNoEq",
		"sql" => " and c.conNo=# "
	),
	array(
		"name" => "conName",
		"sql" => " and c.conName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "conType",
		"sql" => " and c.conType=# "
	),
	array(
		"name" => "conTypeArr",
		"sql" => " and c.conType in(arr) "
	),
	array(
		"name" => "conTypeName",
		"sql" => " and c.conTypeName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "conState",
		"sql" => " and c.conState=# "
	),
	array(
		"name" => "conStateName",
		"sql" => " and c.conStateName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "beginDate",
		"sql" => " and c.beginDate >= BINARY # "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "closeDate",
		"sql" => " and c.closeDate <= BINARY # "
	),
	array(
		"name" => "conNum",
		"sql" => " and c.conNum=# "
	),
	array(
		"name" => "conNumName",
		"sql" => " and c.conNumName=# "
	),
	array(
		"name" => "conContent",
		"sql" => " and c.conContent=# "
	),
	array(
		"name" => "recorderName",
		"sql" => " and c.recorderName=# "
	),
	array(
		"name" => "recorderId",
		"sql" => " and c.recorderId=# "
	),
	array(
		"name" => "recordDate",
		"sql" => " and c.recordDate=# "
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
		"name" => "closeContract",
		"sql" => " and c.closeDate LIKE BINARY CONCAT('%',#,'%') "
	)
)
?>