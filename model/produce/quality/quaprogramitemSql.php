<?php
/**
 * @author Show
 * @Date 2013��5��20�� ����һ 13:48:57
 * @version 1.0
 * @description:�ʼ췽����¼ sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.dimensionId ,c.dimensionName ,c.examTypeId ,c.examTypeName  from oa_produce_quality_programitem c where 1=1 "
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
		"name" => "dimensionId",
		"sql" => " and c.dimensionId=# "
	),
	array (
		"name" => "dimensionName",
		"sql" => " and c.dimensionName=# "
	),
	array (
		"name" => "examTypeId",
		"sql" => " and c.examTypeId=# "
	),
	array (
		"name" => "examTypeName",
		"sql" => " and c.examTypeName=# "
	)
)
?>