<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务前置任务sql
 *
 */
 $sql_arr = array (
	"select_default" => "select c.* from oa_rd_task_front c where 1=1 "
);

$condition_arr = array (
	array(
		"name"=>"taskId",
		"sql"=>" and c.taskId=#"
	),
	array(
		"name" => "taskIds",
		"sql" => " and c.taskId in(arr)"
	),
	array(
		"name" => "noequal",
		"sql" => " and c.frontTaskId != #"
	)
);
?>
