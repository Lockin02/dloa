<?php
/**
 * @author Show
 * @Date 2013��7��15�� 11:31:24
 * @version 1.0
 * @description:������������ sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.configName ,c.dateName ,c.dateCode ,c.days ,c.description ,c.isNeedDate,c.schePct  from oa_contract_payconfig c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "configName",
		"sql" => " and c.configName=# "
	),
	array (
		"name" => "dateName",
		"sql" => " and c.dateName=# "
	),
	array (
		"name" => "dateCode",
		"sql" => " and c.dateCode=# "
	),
	array (
		"name" => "days",
		"sql" => " and c.days=# "
	),
	array (
		"name" => "description",
		"sql" => " and c.description=# "
	),
	array (
		"name" => "isNeedDate",
		"sql" => " and c.isNeedDate=# "
	),
	array (
		"name" => "schePct",
		"sql" => " and c.schePct=# "
	)
)
?>