<?php
/**
 * @author Show
 * @Date 2012��12��10�� ����һ 14:20:05
 * @version 1.0
 * @description:��Ŀָ����ϸ sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.assessId ,c.indexName ,c.upperLimit ,c.lowerLimit ,c.isNeed  from oa_esm_project_assindex c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "assessId",
		"sql" => " and c.assessId=# "
	),
	array (
		"name" => "indexName",
		"sql" => " and c.indexName=# "
	),
	array (
		"name" => "upperLimit",
		"sql" => " and c.upperLimit=# "
	),
	array (
		"name" => "lowerLimit",
		"sql" => " and c.lowerLimit=# "
	),
	array (
		"name" => "isNeed",
		"sql" => " and c.isNeed=# "
	)
)
?>