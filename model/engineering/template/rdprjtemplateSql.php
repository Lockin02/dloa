<?php
$sql_arr = array(
	//模板表
		"select_default" => "select c.id,c.milestoneNumb,c.milestoneplanTemplateName,c.isrelease,c.plantemplateDescription," .
			"c.createId,c.createTime,c.createName,c.updateId,c.updateName,c.updateTime,c.projectType " .
			"from oa_rd_milestoneplantemplate c " . "where 1=1",
	//模板内容
	"select_contenttbl" => "select a.id,a.projectId,a.templateName,a.templateId,a.templateContent,a.templateCode," .
			"a.exTemplateCode,a.templateDescription from " . " oa_rd_template_prjcontent a " . "where 1=1"
);


$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array(
		"name" => "id",
		"sql" => "and a.id=#"
	),
	array(
		"name" => "c.parentId",
		"sql" => "and c.parentId=#"
	),
	array(
		"name" => "a.milestoneNumb",
		"sql" => "and a.milestoneNumb=#"
	),
	array(
		"name" => "a.milestoneplanTemplateName",
		"sql" => "and a.milestoneplanTemplateName=#"
	),
	array(
		"name" => "c.createId",
		"sql" => "and c.createId=#"
	),
	array(
		"name" => "c.updateId",
		"sql" => "and c.updateId=#"
	),
	array(
		"name" => "c.projectType",
		"sql" => "c.projectType=#"
	)
)
?>
