<?php
/**
 * @author show
 * @Date 2013年12月6日 16:31:26
 * @version 1.0
 * @description:项目周报告警记录表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.warningItemId ,c.warningItem ,c.warningLevel,
			c.warningLevelId ,c.coefficient ,c.analysis ,c.feedback,c.ruleDesc
		from oa_esm_project_weekwarning c where 1=1 "
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
		"name" => "warningItemId",
		"sql" => " and c.warningItemId=# "
	),
	array (
		"name" => "warningItem",
		"sql" => " and c.warningItem=# "
	),
	array (
		"name" => "warningLevel",
		"sql" => " and c.warningLevel=# "
	),
	array (
		"name" => "warningLevelId",
		"sql" => " and c.warningLevelId=# "
	),
	array (
		"name" => "coefficient",
		"sql" => " and c.coefficient=# "
	),
	array (
		"name" => "analysis",
		"sql" => " and c.analysis=# "
	),
	array (
		"name" => "feedback",
		"sql" => " and c.feedback=# "
	)
)
?>