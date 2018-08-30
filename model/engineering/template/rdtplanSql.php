<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人sql
 *
 */
 $sql_arr = array (
	"select_default" => "select c.id,c.planName,c.planId,c.templateName,c.status,c.createId,c.createName,c.createTime from oa_rd_template_plan c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "templateName",
		"sql" => " and c.templateName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "status",
		"sql" => " and c.status = # "
	)
);
?>
