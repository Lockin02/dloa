<?php
$TMP=array('shiyuanoa'=>'sy','beiruanoa'=>'br','dloa'=>'dl');
$sql_arr = array (
	"select_linkman_info" => "select
			c.id,c.typeId,c.typeName,c.serviceId,c.serviceNo,c.serviceType,c.originalName,
			c.newName,c.uploadPath,c.tFileSize,c.createId,c.createName,c.createTime,c.updateId,
			c.updateName,c.updateTime,c.inDocument,c.styleOne,c.styleTwo,c.styleThree,c.originalId,c.isTemp
		from oa_uploadfile_manage c left join user u on (c.createId=u.user_id) where 
		".($_GET['gdbtable']=='shiyuanoa'?" u.company='sy' ": " 1 " ),
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
		"name" => "serviceIds",
		"sql" => "and c.serviceId in($)"
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
