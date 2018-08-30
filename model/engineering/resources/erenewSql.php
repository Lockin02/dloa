<?php
/**
 * @author show
 * @Date 2013年12月9日 19:17:43
 * @version 1.0
 * @description:续借申请单 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.formNo ,c.applyUser ,c.applyUserId ,c.deptId ,c.deptName ,c.applyDate ,c.projectId ,c.projectCode ,c.projectName ,c.managerName ,c.managerId ,c.reason ,c.remark ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.confirmName ,c.confirmId ,c.confirmTime  from oa_esm_resource_erenew c where 1=1 "
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
        "name" => "formNo",
        "sql" => " and c.formNo=# "
    ),
    array(
        "name" => "applyUser",
        "sql" => " and c.applyUser=# "
    ),
    array(
        "name" => "applyUserId",
        "sql" => " and c.applyUserId=# "
    ),
    array(
        "name" => "deptId",
        "sql" => " and c.deptId=# "
    ),
    array(
        "name" => "deptName",
        "sql" => " and c.deptName=# "
    ),
    array(
        "name" => "applyDate",
        "sql" => " and c.applyDate=# "
    ),
    array(
        "name" => "projectId",
        "sql" => " and c.projectId=# "
    ),
    array(
        "name" => "projectCode",
        "sql" => " and c.projectCode=# "
    ),
    array(
        "name" => "projectName",
        "sql" => " and c.projectName=# "
    ),
    array(
        "name" => "managerName",
        "sql" => " and c.managerName=# "
    ),
    array(
        "name" => "managerId",
        "sql" => " and c.managerId=# "
    ),
    array(
        "name" => "reason",
        "sql" => " and c.reason=# "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=# "
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
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(
        "name" => "createName",
        "sql" => " and c.createName=# "
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime=# "
    ),
    array(
        "name" => "updateId",
        "sql" => " and c.updateId=# "
    ),
    array(
        "name" => "updateName",
        "sql" => " and c.updateName=# "
    ),
    array(
        "name" => "updateTime",
        "sql" => " and c.updateTime=# "
    ),
    array(
        "name" => "confirmName",
        "sql" => " and c.confirmName=# "
    ),
    array(
        "name" => "confirmId",
        "sql" => " and c.confirmId=# "
    ),
    array(
        "name" => "confirmTime",
        "sql" => " and c.confirmTime=# "
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
        "name" => "applyUserSch",
        "sql" => " and c.applyUser like concat('%',#,'%') "
    ),
    array(
        "name" => "charger",
        "sql" => " and (c.createId=# or applyUserId = #)"
    )
);