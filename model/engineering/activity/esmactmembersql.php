<?php

/**
 * @author Show
 * @Date 2012年7月27日 星期五 16:23:53
 * @version 1.0
 * @description:项目任务成员 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.activityId ,c.activityName ,c.memberName ,
			c.memberId ,c.actBeginDate ,c.actEndDate ,c.personLevel ,c.personLevelId ,c.feeDay ,c.feePeople ,c.feePerson ,c.price ,
			c.coefficient ,c.roleId ,c.roleName
		from oa_esm_project_activitymember c where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "activityId",
		"sql" => " and c.activityId=# "
	),
	array (
		"name" => "activityName",
		"sql" => " and c.activityName=# "
	),
	array (
		"name" => "memberName",
		"sql" => " and c.memberName=# "
	),
	array (
		"name" => "memberId",
		"sql" => " and c.memberId=# "
	),
	array (
		"name" => "memberIdArr",
		"sql" => " and c.memberId in(arr)"
	),
	array (
		"name" => "actBeginDate",
		"sql" => " and c.actBeginDate=# "
	),
	array (
		"name" => "actEndDate",
		"sql" => " and c.actEndDate=# "
	)
)
?>