<?php
$sql_arr = array (
    "select_task" => "select * from oa_esm_task c where 1=1 ",
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and like = #"
	),
	array (
		"name" => "name",
		"sql" => "and c.name like CONCAT('%',#,'%')"
	),
	array (
		"name" => "projectName",
		"sql" => "and c.projectName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "priority",
		"sql" => "and c.priority like CONCAT('%',#,'%')"
	),
	array (
		"name" => "status",
		"sql" => "and c.status = #"
	),
	array (
		"name" => "effortRate",
		"sql" => "and c.effortRate = #"
	),
	array (
		"name" => "warpRate",
		"sql" => "and c.warpRate = #"
	),
	array (
		"name" => "chargeName",
		"sql" => "and c.chargeName = #"
	),
	array (
		"name" => "publishName",
		"sql" => "and c.publishName = #"
	),
	array(
		"name"=>"projectId",
		"sql" => "and c.projectId = #"
	)

);
?>