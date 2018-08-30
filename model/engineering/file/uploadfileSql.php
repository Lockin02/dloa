<?php
$sql_arr = array (
	"select_linkman_info" => "select * from oa_esm_uploadfile c where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "originalName",
		"sql" => "and c.originalName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "createName",
		"sql" => "and c.createName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "newName",
		"sql" => "and c.newName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "Number",
		"sql" => "and Number like CONCAT('%',#,'%')"
	),
	array (
		"name" => "id",
		"sql" => "and c.id = #"
	),
	array (
		"name" => "ids",
		"sql" => "and c.id in($)"
	),
	array (
		"name" => "supplierId",
		"sql" => "and c.supplierId = #"
	),
	array (
		"name" => "serviceId",
		"sql" => "and c.serviceId = #"
	),
	array (
		"name" => "serviceNo",
		"sql" => "and c.serviceNo = #"
	),
	array (
		"name" => "serviceType",
		"sql" => "and c.serviceType = #"
	),
	array (
		"name" => "objId",
		"sql" => " and c.serviceId=#"
	),
	array (
		"name" => "objTable",
		"sql" => " and c.serviceType=#"
	),
	array (
		"name" => "typeId",
		"sql" => " and c.typeId=#"
	),
	array (
		"name" => "onlyProject",
		"sql" => " and (c.serviceType='oa_rd_project' and c.serviceId=#)"
	),
	array (
		"name" => "start",
		"sql" => " and ("
	),
	array (
		"name" => "project",
		"sql" => " (c.serviceType='oa_rd_project' and c.serviceId=#)"
	),
	array (
		"name" => "task",
		"sql" => " or (c.serviceType='oa_rd_task' and c.serviceId in($))"
	),
	array (
		"name" => "plan",
		"sql" => " or (c.serviceType='oa_rd_project_plan' and c.serviceId in($))"
	),
	array (
		"name" => "end",
		"sql" => " )"
	)
);
?>
