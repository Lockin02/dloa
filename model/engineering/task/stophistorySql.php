<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:��Ŀ���������sql
 *
 */
 $sql_arr = array (
	"select_default" => "select * from oa_rd_task_stop_history c where 1=1 "
);

$condition_arr = array (
	array(
		"name"=>"taskId",
		"sql"=>" and c.taskId=#"
	),
	array(
		"name"=>"id",
		"sql"=>" and c.id=#"
	),
	array(
		"name"=>"isAct",
		"sql"=>" and c.isAct=#"
	)
);
?>
