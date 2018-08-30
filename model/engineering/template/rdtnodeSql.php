<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人sql
 *
 */
 $sql_arr = array (
	"select_default" => "select c.id,c.templateId,c.templateName,c.nodeName,c.parentId,c.parentName,c.lft,c.rgt,c.nodeLeftKey,0 as leaf from oa_rd_template_taskNode c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "templateId",
		"sql" => " and c.templateId=#"
	),
	array (
		"name" => "parentId",
		"sql" => "and c.parentId = #"
	)
);
?>
