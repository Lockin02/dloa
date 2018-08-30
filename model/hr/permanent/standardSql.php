<?php
/**
 * @author jianjungki
 * @Date 2012年8月6日 14:33:32
 * @version 1.0
 * @description:员工考核项目 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.standard ,c.standardCode ,c.standardType ,c.Content ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_hr_permanent_standard c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "standard",
		"sql" => " and c.standard LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "standardCode",
		"sql" => " and c.standardCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "standardType",
		"sql" => " and c.standardType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "Content",
		"sql" => " and c.Content=# "
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
		"name" => "standardEq",
		"sql" => " and c.standard=# "
	)
)
?>