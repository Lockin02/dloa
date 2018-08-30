<?php
/**
 * @author Show
 * @Date 2012年11月27日 星期二 11:40:15
 * @version 1.0
 * @description:考核指标表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.name ,c.sortNo ,c.detail ,c.remark ,c.upperLimit,c.lowerLimit  from oa_esm_ass_index c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
	array (
		"name" => "name",
		"sql" => " and c.name=# "
	),
	array (
		"name" => "nameSearch",
		"sql" => " and c.name like concat('%',#,'%') "
	),
	array (
		"name" => "sortNo",
		"sql" => " and c.sortNo=# "
	),
	array (
		"name" => "detail",
		"sql" => " and c.detail=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "upperLimit",
		"sql" => " and c.upperLimit=# "
	),
	array (
		"name" => "lowerLimit",
		"sql" => " and c.lowerLimit=# "
	)
)
?>