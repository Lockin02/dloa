<?php
$sql_arr = array(
	"select_default" => "select c.id,c.projectType,c.typeCode,c.templateName,c.templateId,c.typeDescription ".
	" from oa_rd_projecttype c" . " where 1=1"

);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	),

	array(
		"name" => "projectType",
		"sql" => "and c.projectType=#"
	),

	array(
		"name" => "typeCode",
		"sql" => "and c.typeCode=#"
	),

	array(
		"name" => "templateName",
		"sql" => "and c.templateName=#"
	),

	array(
		"name" => "templateId",
		"sql" => "and templateId=#"
	)
)



?>
