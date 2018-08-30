<?php
$sql_arr = array ("select_check" => "select c.id,c.taskId,c.taskNo,c.beginDate,c.endDate,c.deptId,c.dept,c.manId,c.man,c.remark from oa_asset_check c where 1=1 " );
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id=#"
	),array (
		"name" => "taskId",
		"sql" => "and c.taskId=#"
	),
	array (
		"name" => "taskNo",
		"sql" => "and c.taskNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "taskNoEq",
		"sql" => "and c.taskNoEq =#"
	),
	array (
		"name" => "beginDate",
		"sql" => "and c.beginDate like CONCAT('%',#,'%')"
	),
	array(
		"name" => "endDate",
		"sql" => " and c.endDate = #"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId =#"
	),
	array (
		"name" => "dept",
		"sql" => "and c.dept like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptEq",
		"sql" => "and c.deptEq =#"
	),
	array(
		"name" => "manId",
		"sql" => " and c.manId = # "
	),
	array(
		"name" => "man",
		"sql" => " and  c.man = #"
	),
	 array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
);
?>
