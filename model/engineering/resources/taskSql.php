<?php
/**
 * @author show
 * @Date 2013年11月23日 16:38:15
 * @version 1.0
 * @description:项目设备任务单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.taskCode ,c.applyId ,c.applyNo ,c.projectId ,c.projectCode ,
           c.projectName ,c.managerName ,c.managerId ,c.applyUser ,c.applyUserId ,c.deptId ,c.deptName ,
           c.areaName ,c.areaId ,c.taskManager ,c.taskManagerId ,c.place ,c.remark ,c.status ,c.receiverId ,
           c.receiverName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.applyDate ,
		   c.applyTypeName,c.getTypeName,c.address,c.reason,c.expressId,c.expressName,c.expressNo,c.mailDate,c.mailRemark,c.mobile
        from oa_esm_resource_task c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "taskCode",
		"sql" => " and c.taskCode=# "
	),
	array (
		"name" => "taskCodeSch",
		"sql" => " and c.taskCode like concat('%',#,'%')"
	),
	array (
		"name" => "applyId",
		"sql" => " and c.applyId=# "
	),
	array (
		"name" => "applyNo",
		"sql" => " and c.applyNo=# "
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
		"name" => "applyUserSch",
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
		"name" => "areaName",
		"sql" => " and c.areaName=# "
	),
	array (
		"name" => "areaId",
		"sql" => " and c.areaId=# "
	),
	array (
		"name" => "areaIdArr",
		"sql" => " and c.areaId in(arr)"
	),
	array (
		"name" => "taskManager",
		"sql" => " and c.taskManager=# "
	),
	array (
		"name" => "taskManagerId",
		"sql" => " and c.taskManagerId=# "
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
		"name" => "receiverId",
		"sql" => " and c.receiverId=# "
	),
	array (
		"name" => "receiverName",
		"sql" => " and c.receiverName=# "
	),
	array (
		"name" => "receiverNameSch",
		"sql" => " and c.receiverName like concat('%',#,'%') "
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
		"name" => "createTime",
		"sql" => " and c.createTime=# "
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
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>