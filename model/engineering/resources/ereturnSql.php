<?php
$sql_arr = array(
    "select_default" => "select c.id,c.formNo,c.projectId,c.projectName,c.projectCode,c.managerName,
   			c.managerId,c.applyUser,c.applyUserId,c.deptId,c.deptName,c.applyDate,c.areaName,c.areaId,c.expressName,
			c.expressId,c.expressNo,c.remark,c.status,c.receiverId,c.receiverName,c.receiverTime,c.createId,c.createName,
			c.createTime,c.updateId,c.updateName,c.updateTime,c.mailDate,c.deviceDeptId,c.deviceDeptName,c.reason
		from oa_esm_resource_ereturn c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "deviceDeptIds",
        "sql" => " and c.deviceDeptId in(arr)"
    ),
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "projectId",
        "sql" => " and c.projectId=# "
    ),
    array(
        "name" => "projectName",
        "sql" => " and c.projectName=# "
    ),
    array(
        "name" => "status",
        "sql" => " and c.status in(arr) "
    ),
	array(
		"name" => "statusArr",
		"sql" => " and c.status in(arr) "
	),
    array(
        "name" => "applyUserSch",
        "sql" => " and c.applyUser like concat('%',#,'%') "
    ),
    array(
        "name" => "areaNameSch",
        "sql" => " and c.areaName like concat('%',#,'%') "
    ),
    array(
        "name" => "projectCodeSch",
        "sql" => " and c.projectCode like concat('%',#,'%') "
    ),
    array(
        "name" => "projectNameSch",
        "sql" => " and c.projectName like concat('%',#,'%') "
    ),
    array(
        "name" => "formNoSch",
        "sql" => " and c.formNo like concat('%',#,'%') "
    ),
    array(
        "name" => "charger",
        "sql" => " and (c.createId=# or applyUserId = #)"
    )
);