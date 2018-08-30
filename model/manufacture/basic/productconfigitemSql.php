<?php
/**
 * @author Michael
 * @Date 2015年3月23日 16:29:21
 * @version 1.0
 * @description:基础物料配置数据 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.parentId ,c.colName ,c.colCode ,c.colContent ,c.rowNum  from oa_manufacture_productconfig_item c where 1=1 ",
	"select_table"=>"select c.id ,c.parentId ,c.colName ,c.colCode ,c.colContent ,c.rowNum
		from oa_manufacture_productconfig_item c
		left join oa_manufacture_productconfig a on a.id=c.parentId
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
		"name" => "rowNum",
		"sql" => " and c.rowNum=# "
	),
	array(
		"name" => "processId",
		"sql" => " and a.processId=# "
	)
)
?>