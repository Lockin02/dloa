<?php
/**
 * @author Show
 * @Date 2013年7月15日 10:44:32
 * @version 1.0
 * @description:日期设置 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.fieldName ,c.fieldCode ,c.fieldDesc  from oa_contract_dateconfig c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
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
		"name" => "fieldDesc",
		"sql" => " and c.fieldDesc=# "
	)
)
?>