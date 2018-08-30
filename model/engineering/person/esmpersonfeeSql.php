<?php
/**
 * @author show
 * @Date 2013年10月29日 17:16:28
 * @version 1.0
 * @description:项目人力决算 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.projectId ,c.projectCode ,c.thisYear ,c.thisMonth ,c.projectName ,c.userId ,c.userName ,c.inWorkRate ,c.feePerson ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_project_personfee c where 1=1 "
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
		"name" => "thisYear",
		"sql" => " and c.thisYear=# "
	),
	array (
		"name" => "thisMonth",
		"sql" => " and c.thisMonth=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "userId",
		"sql" => " and c.userId=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "inWorkRate",
		"sql" => " and c.inWorkRate=# "
	),
	array (
		"name" => "feePerson",
		"sql" => " and c.feePerson=# "
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
	)
)
?>