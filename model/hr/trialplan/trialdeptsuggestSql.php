<?php

/**
 * @author Show
 * @Date 2012年9月12日 星期三 16:25:18
 * @version 1.0
 * @description:员工使用部门建议表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobId ,c.jobName ,c.deptSuggest ,c.deptSuggestName ,c.suggestion ,c.permanentDate ,c.beforeSalary ,c.afterSalary ,c.afterPositionId ,c.afterPositionName ,c.levelName ,c.levelCode ,c.positionCode ,c.ExaStatus ,c.ExaDT ,c.status ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.personLevel ,c.personLevelId ,c.hrSalary ,c.beforePersonLv ,c.beforePersonLvId from oa_hr_trialdeptsuggest c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "userNo",
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
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
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
		"name" => "deptSuggest",
		"sql" => " and c.deptSuggest=# "
	),
	array (
		"name" => "deptSuggestName",
		"sql" => " and c.deptSuggestName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "suggestion",
		"sql" => " and c.suggestion LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "permanentDate",
		"sql" => " and c.permanentDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array (
		"name" => "beforeSalary",
		"sql" => " and c.beforeSalary LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "afterSalary",
		"sql" => " and c.afterSalary LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "hrSalary",
		"sql" => " and c.hrSalary LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "beforePersonLv",
		"sql" => " and c.beforePersonLv LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "personLevel",
		"sql" => " and c.personLevel LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "afterPositionId",
		"sql" => " and c.afterPositionId=# "
	),
	array (
		"name" => "afterPositionName",
		"sql" => " and c.afterPositionName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "levelName",
		"sql" => " and c.levelName=# "
	),
	array (
		"name" => "levelCode",
		"sql" => " and c.levelCode=# "
	),
	array (
		"name" => "positionCode",
		"sql" => " and c.positionCode=# "
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
		"name" => "createName",
		"sql" => " and c.createName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>