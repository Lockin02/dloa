<?php
/**
 * @author show
 * @Date 2013年10月17日 12:07:57
 * @version 1.0
 * @description:外包模板费用模板明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.costType ,c.costTypeName ,c.parent ,c.parentName  from oa_sale_outsourcing_templateitem c where 1=1 "
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
		"name" => "costType",
		"sql" => " and c.costType=# "
	),
	array (
		"name" => "costTypeName",
		"sql" => " and c.costTypeName=# "
	),
	array (
		"name" => "parent",
		"sql" => " and c.parent=# "
	),
	array (
		"name" => "parentName",
		"sql" => " and c.parentName=# "
	)
)
?>