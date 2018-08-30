
<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人sql
 *
 */
 $sql_arr = array (
	"select_default" => "select c.* from oa_rd_task_stop_history c where 1=1 "
);

$condition_arr = array (
	array(
		"name"=>"taskId",
		"sql"=>" and c.taskId=#"
	),
	array(
		"name"=>"isAct",
		"sql"=>" and c.isAct=#"
	)
);
?>
