<?php
/**
 * @author Show
 * @Date 2012年11月7日 星期三 19:23:17
 * @version 1.0
 * @description:项目设备申请表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.formNo ,c.projectId ,c.projectCode ,c.projectName ,c.managerName ,c.managerId ,
			c.applyUser ,c.applyUserId ,c.deptId ,c.deptName ,c.applyDate ,c.place,c.placeId,c.remark ,c.status ,c.ExaStatus ,
			c.ExaDT ,c.applyType ,c.applyTypeName ,c.getType,c.getTypeName ,c.createId ,c.createName ,c.createTime ,
			c.updateId ,c.updateName ,c.updateTime,c.confirmStatus,c.confirmId,c.confirmName,c.confirmTime,c.reason
		from oa_esm_resource_apply c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "formNo",
		"sql" => " and c.formNo=# "
	),
	array (
		"name" => "formNoSch",
		"sql" => " and c.formNo like concat('%',#,'%') "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectCodeSch",
		"sql" => " and c.projectCode like concat('%',#,'%') "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "projectNameSch",
		"sql" => " and c.projectName like concat('%',#,'%') "
	),
	array (
		"name" => "managerName",
		"sql" => " and c.managerName=# "
	),
	array (
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array (
		"name" => "applyUser",
		"sql" => " and c.applyUser=# "
	),
	array (
		"name" => "applyUserSearch",
		"sql" => " and c.applyUser like concat('%',#,'%') "
	),
	array (
		"name" => "applyUserId",
		"sql" => " and c.applyUserId=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "applyDate",
		"sql" => " and c.applyDate=# "
	),
	array (
		"name" => "applyType",
		"sql" => " and c.applyType=# "
	),
	array (
		"name" => "getType",
		"sql" => " and c.getType=# "
	),
	array (
		"name" => "place",
		"sql" => " and c.place=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusArr",
		"sql" => " and c.status in(arr) "
	),
	array (
		"name" => "confirmStatus",
		"sql" => " and c.confirmStatus =# "
	),
	array (
		"name" => "confirmStatusArr",
		"sql" => " and c.confirmStatus in(arr) "
	),
	array (
		"name" => "confirmStatusNotArr",
		"sql" => " and c.confirmStatus not in(arr) "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "charger",
		"sql" => " and (c.createId=# or applyUserId = #) "
	)
);