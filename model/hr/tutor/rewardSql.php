<?php
/**
 * @author Administrator
 * @Date 2012-08-29 17:09:19
 * @version 1.0
 * @description:导师奖励管理 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.isGrant,c.code ,c.name ,c.dept ,c.deptId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,date_format(c.createTime,'%Y-%m-%d') as createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.isPublish from oa_hr_tutor_reward c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "code",
		"sql" => " and c.code LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "name",
		"sql" => " and c.name LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "dept",
		"sql" => " and c.dept LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName LIKE CONCAT('%',#,'%')  "
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
	)
)
?>