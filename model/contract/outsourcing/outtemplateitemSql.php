<?php
/**
 * @author show
 * @Date 2013��10��17�� 12:07:57
 * @version 1.0
 * @description:���ģ�����ģ����ϸ sql�����ļ�
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