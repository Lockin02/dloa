<?php
/**
 * @author show
 * @Date 2013��11��25�� 10:56:46
 * @version 1.0
 * @description:(��license)���ñ�ע��Ϣ sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.formId ,c.titleName ,c.titleId ,c.optionName ,c.optionId ,c.tips ,c.isDisable  from oa_license_category_tips c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "formId",
		"sql" => " and c.formId=# "
	),
	array (
		"name" => "titleName",
		"sql" => " and c.titleName=# "
	),
	array (
		"name" => "titleId",
		"sql" => " and c.titleId=# "
	),
	array (
		"name" => "optionName",
		"sql" => " and c.optionName=# "
	),
	array (
		"name" => "optionId",
		"sql" => " and c.optionId=# "
	),
	array (
		"name" => "tips",
		"sql" => " and c.tips=# "
	),
	array (
		"name" => "isDisable",
		"sql" => " and c.isDisable=# "
	)
)
?>