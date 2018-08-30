<?php
$sql_arr = array ("select" => "select u.DEPT_ID as deptId,d.DEPT_NAME as deptName," .
		"c.id,c.formCode,c.userId,c.userName,u.jobs_id as jobId," .
		"c.selectTime,c.selectUserId,c.selectUserName from oa_user_select c" .
		" left join user u on u.USER_ID=c.selectUserId " .
		" left join  department d on u.DEPT_ID=d.DEPT_ID" );
$condition_arr = array (
	array (
		"name" => "formCode",
		"sql" => "and c.formCode =#"
	),
	array (
		"name" => "userId",
		"sql" => "and c.userId =#"
	),
	array (
		"name" => "selectUserId",
		"sql" => "and c.selectUserId =#"
	)
);
?>
