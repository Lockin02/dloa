<?php

/**
 * @author Show
 * @Date 2013年5月20日 星期一 11:55:49
 * @version 1.0
 * @description:质检方案 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.programName ,c.standardName ,c.standardId ,c.isClose ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_produce_quality_program c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "programName",
		"sql" => " and c.programName=# "
	),
	array (
		"name" => "standardName",
		"sql" => " and c.standardName=# "
	),
	array (
		"name" => "standardId",
		"sql" => " and c.standardId=# "
	),
	array (
		"name" => "isClose",
		"sql" => " and c.isClose=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
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