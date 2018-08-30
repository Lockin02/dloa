<?php

/**
 * @author Show
 * @Date 2012年8月23日 星期四 9:40:13
 * @version 1.0
 * @description:任职资格等级认证评价表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.applyId ,c.modelId ,c.modelName ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,
			c.jobId ,c.jobName ,c.nowDirection ,c.nowDirectionName ,c.nowLevel ,c.nowLevelName ,c.nowGrade ,c.nowGradeName ,
			c.careerDirection ,c.careerDirectionName ,c.baseLevel ,c.baseLevelName ,c.baseGrade ,c.baseGradeName ,c.status ,
			c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,
			c.sysCompanyId,c.managerName,c.managerId,c.memberId,c.memberName,c.scoreAll
		from oa_hr_certifyapplyassess c where 1=1 ",
	"select_forresult" => "select c.id ,c.applyId ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,
			c.jobId ,c.jobName ,c.nowDirection ,c.nowDirectionName ,c.nowLevel ,c.nowLevelName ,c.nowGrade ,c.nowGradeName ,
			c.careerDirection ,c.careerDirectionName ,c.baseLevel ,c.baseLevelName ,c.baseGrade ,c.baseGradeName,
			a.jobName,a.jobName,a.graduationDate,a.highEducationName,a.entryDate,c.scoreAll
		from oa_hr_certifyapplyassess c left join oa_hr_personnel_certifyapply a on c.applyId = a.id where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.Id in(arr) "
	),
	array (
		"name" => "applyId",
		"sql" => " and c.applyId=# "
	),
	array (
		"name" => "modelId",
		"sql" => " and c.modelId=# "
	),
	array (
		"name" => "modelName",
		"sql" => " and c.modelName=# "
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
		"sql" => " and c.userName like concat('%',#,'%') "
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
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array (
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array (
		"name" => "nowDirection",
		"sql" => " and c.nowDirection=# "
	),
	array (
		"name" => "nowDirectionName",
		"sql" => " and c.nowDirectionName=# "
	),
	array (
		"name" => "nowLevel",
		"sql" => " and c.nowLevel=# "
	),
	array (
		"name" => "nowLevelName",
		"sql" => " and c.nowLevelName=# "
	),
	array (
		"name" => "nowGrade",
		"sql" => " and c.nowGrade=# "
	),
	array (
		"name" => "nowGradeName",
		"sql" => " and c.nowGradeName=# "
	),
	array (
		"name" => "careerDirection",
		"sql" => " and c.careerDirection=# "
	),
	array (
		"name" => "careerDirectionName",
		"sql" => " and c.careerDirectionName=# "
	),
	array (
		"name" => "baseLevel",
		"sql" => " and c.baseLevel=# "
	),
	array (
		"name" => "baseLevelName",
		"sql" => " and c.baseLevelName=# "
	),
	array (
		"name" => "baseGrade",
		"sql" => " and c.baseGrade=# "
	),
	array (
		"name" => "baseGradeName",
		"sql" => " and c.baseGradeName=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
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
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
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
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	)
)
?>