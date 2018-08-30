<?php
/**
 * @author Show
 * @Date 2013年7月11日 星期四 13:30:34
 * @version 1.0
 * @description:通用邮件配置从表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.fieldName ,c.fieldCode ,c.showType,c.orderNum  from oa_system_mailconfig_item c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array (
		"name" => "fieldName",
		"sql" => " and c.fieldName=# "
	),
	array (
		"name" => "fieldCode",
		"sql" => " and c.fieldCode=# "
	),
	array (
		"name" => "showType",
		"sql" => " and c.showType=# "
	),
	array (
		"name" => "orderNum",
		"sql" => " and c.orderNum=# "
	)
)
?>