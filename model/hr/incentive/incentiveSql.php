<?php
/**
 * @author Show
 * @Date 2012年5月25日 星期五 14:55:28
 * @version 1.0
 * @description:奖惩管理 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.grantUnitId ,c.grantUnitName ,c.incentiveType ,c.incentiveTypeName ,c.incentiveDate ,c.rewardPeriod ,if(c.incentiveType = 'HRJLSS-01',c.incentiveMoney,-c.incentiveMoney) as incentiveMoney ,c.reason ,c.description ,c.remark ,c.ExaStatus ,c.ExaDT ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.recorderName,c.recorderId,c.recordDate,c.rewardDate,p.staffName,p.highSchool,p.professionalName,p.companyType,p.companyName,p.deptName,p.deptNameS,p.deptNameT,p.deptNameF,p.personnelTypeName,p.wageLevelName,p.jobLevel
	from oa_hr_personnel_incentive c
	left join oa_hr_personnel p on c.userNo=p.userNo
	where 1=1 ",
	"count_all" => "select sum(if(c.incentiveType = 'HRJLSS-01',c.incentiveMoney,-c.incentiveMoney)) as incentiveMoney
	from oa_hr_personnel_incentive c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userNoSearch",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
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
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "grantUnitId",
		"sql" => " and c.grantUnitId=# "
	),
	array (
		"name" => "recorderName",
		"sql" => " and c.recorderName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "grantUnitName",
		"sql" => " and c.grantUnitName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "incentiveType",
		"sql" => " and c.incentiveType=# "
	),
	array (
		"name" => "incentiveTypeName",
		"sql" => " and c.incentiveTypeName=# "
	),
	array (
		"name" => "incentiveDate",
		"sql" => " and c.incentiveDate=# "
	),
	array (
		"name" => "recordDateSearch",
		"sql" => " and c.recordDate LIKE BINARY CONCAT('%',#,'%')   "
	),
	array (
		"name" => "incentiveDateSearch",
		"sql" => " and c.incentiveDate LIKE BINARY CONCAT('%',#,'%')  "
	),
	array (
		"name" => "rewardPeriod",
		"sql" => " and c.rewardPeriod=# "
	),
	array (
		"name" => "incentiveMoney",
		"sql" => " and c.incentiveMoney=# "
	),
	array (
		"name" => "reason",
		"sql" => " and c.reason LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "description",
		"sql" => " and c.description LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
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
	)
)
?>