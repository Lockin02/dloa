<?php
$sql_arr = array(
	//默认sql语句
	"select_default" => "select c.id,c.milestoneName,c.exMilestoneName,c.createName," .
			"c.createId,c.createTime,c.milestoneDescription,c.numb,c.frontNumb,c.parentId " .
			"from oa_rd_milestone_info c " . "where 1=1",
	"slect_template" => "slect a.id,a.projectName,a.projectId,a.templateName,a.exTemplate,a.templateDescription," .
			"a.status,a.createId,a.projectType,a.createName,a.createTime " .
			 " from oa_rd_template_project a" . " where 1=1",
//	"select_default" => "select c.id,c.milestoneName,c.exMilestoneName,c.projectType,c.createName,c.createId,c.createTime,c.milestoneDescription " . "from oa_rd_milestone_info c " . "where 1=1",
//	"select_filtermilestone" => "select c.id,c.milestoneName,c.exMilestoneName,c.projectType,c.createname,c.createTime,c.milestonedescription " . "from oa_rd_milestone_info c " . "where c.projectType = '" . $_GET['projectType'] . "'"
);

$condition_arr = array(
	//通过Id查询
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array(
		"name" => "projectType",
		"sql" => "and c.projectType=#"
	),
	array(
		"name" => "milestoneName",
		"sql" => "and c.milestoneName=#"
	),
	array(
		"name" => "numb",
		"sql" =>"and c.numb=#"
	),
	array(
		"name" => "parentId",
		"sql" => "and c.parentId=#"
	),
	array(
		"name" => "templateName",
		"sql" => "and a.templateName=#"
	),
	array(
		"name" => "status",
		"sql" => "and c.status=#"
	)
);
?>
