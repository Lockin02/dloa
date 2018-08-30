<?php
/**
 * @author Michael
 * @Date 2014年8月25日 11:05:34
 * @version 1.0
 * @description:生产任务配置清单 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.parentId ,c.colName ,c.colCode ,c.colContent  from oa_produce_taskconfig_item c where 1=1 ",
	"select_table"=>"select c.id ,c.parentId ,c.colName ,c.colCode ,c.colContent
		from oa_produce_taskconfig_item c
		left join oa_produce_taskconfig t on t.id=c.parentId
		where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array(
		"name" => "colName",
		"sql" => " and c.colName=# "
	),
	array(
		"name" => "colCode",
		"sql" => " and c.colCode=# "
	),
	array(
		"name" => "colContent",
		"sql" => " and c.colContent=# "
	),
	array(
		"name" => "taskId",
		"sql" => " and t.taskId=# "
	),
	array(
		"name" => "configCode",
		"sql" => " and t.configCode=# "
	)
)
?>