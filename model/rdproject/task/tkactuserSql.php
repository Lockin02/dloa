<?php

/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人sql
 *
 */
$sql_arr = array (
	"select_default" => "select c.* from oa_rd_task_act_user c where 1=1 ",
	"select_taskid" => "select c.taskId from oa_rd_task_act_user c where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "actUserId",
		"sql" => " and c.actUserId=#"
	),
	array (
		"name" => "userName",
		" sql" => " and c.userName=#"
	),
	array (
		"name" => "isActUser",
		"sql" => " and c.isActUser=#"
	),
	array (
		"name" => "taskId",
		"sql" => " and c.taskId=#"
	)
);
?>
