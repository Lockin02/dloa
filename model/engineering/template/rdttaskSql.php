<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人sql
 *
 */
 $sql_arr = array (
	"select_default" => "select c.id,c.name,c.priority,c.taskType,c.planDuration,c.appraiseWorkload,c.templateId,c.templateName," .
			"c.belongNode,c.belongNodeId,c.inspectInfo,c.nodeLeftKey from oa_rd_template_task c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "templateId",
		"sql" => " and c.templateId = # "
	),
	array (
		"name" => "belongNodeId",
		"sql" => " and c.belongNodeId = # "
	)
);
?>
