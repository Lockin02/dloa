<?php
$sql_arr = array (
	"select_default" => "select c.id,c.userId,c.userName,c.detpId,c.deptName from oa_rd_project_permission c where 1=1"//获取某成员在某个项目中的权限集合
);

$condition_arr = array (
	array(
		"name" => "userId",
		"sql" => "and c.userId=#"
	),
	array(
		"name" => "userName",
		"sql" => "and c.userName = #"
	)
	,array (
		"name" => "detpId",
		"sql" => "and c.detpId like CONCAT('%',#,'%')"
	)
	,array (
		"name" => "detpName",
		"sql" => "and c.detpName =#"
	),
	array (
		"name" => "id",
		"sql" => "and c.id =#"
	),
);
?>

