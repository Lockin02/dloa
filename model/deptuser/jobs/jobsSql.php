<?php
$sql_arr = array ("select" => "select 'jobs' as icon,'jobs' as type,c.id,c.dept_id,c.name,
c.func_id_str,c.level from user_jobs c where 1=1  ",
"selectForUser" => "select 'jobs' as icon,'jobs' as type,c.id,c.dept_id,c.name,
c.func_id_str,c.level ,
(select if(count(u.USER_ID)>0,1,0) from user u where u.jobs_id =c.id) as hasUser
from user_jobs c where 1=1 " );
$condition_arr = array (
	array (
		"name" => "name",
		"sql" => "and c.name like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.dept_id =#"
	)
);
?>
