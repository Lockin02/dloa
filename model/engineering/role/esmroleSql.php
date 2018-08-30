<?php

/**
 * @author Show
 * @Date 2012年7月13日 星期五 10:48:12
 * @version 1.0
 * @description:项目角色(oa_esm_project_role) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.roleName ,c.projectId ,c.projectCode ,c.projectName ,c.jobDescription ,c.createId ,
			c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.parentId ,c.parentName ,c.lft ,c.rgt ,
			c.activityName ,c.activityId,c.memberName,c.memberId,c.isManager
		from oa_esm_project_role c where 1=1 ",
	"treelist" => "select c.id,c.roleName,c.lft ,c.rgt,c.jobDescription,c.activityName ,c.activityId,
			c.parentId,c.parentId as _parentId,c.memberName,c.memberId,c.isManager,c.fixedRate
		from oa_esm_project_role c where c.id<>-1 and 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "roleName",
		"sql" => " and c.roleName=# "
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
		"name" => "jobDescription",
		"sql" => " and c.jobDescription=# "
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
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array (
		"name" => "parentName",
		"sql" => " and c.parentName=# "
	),
	array (
		"name" => "lft",
		"sql" => " and c.lft=# "
	),
	array (
		"name" => "rgt",
		"sql" => " and c.rgt=# "
	),
	array (
		"name" => "isLeaf",
		"sql" => " and c.isLeaf=# "
	),
	array (
		"name" => "activityName",
		"sql" => " and c.activityName=# "
	),
	array (
		"name" => "activityId",
		"sql" => " and c.activityId=# "
	),
	array (
		"name" => "isManager",
		"sql" => " and c.isManager=# "
	)
)
?>