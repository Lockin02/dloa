<?php
$sql_arr = array (
	"select_default" =>"select c.id,c.projectTaskId,c.projectTask,c.belongTask,c.belongTaskId,c.preTask,c.preTaskId,c.inspectInfo,c.isStone,c.markStoneName from oa_rd_task_advanced c where 1=1 "
);

$condition_arr = array (
			array (
		"name" => "projectTaskId",
		"sql" => "and c.projectTaskId=#"
	)
);
?>

