<?php

/**
 * @author Show
 * @Date 2012年8月2日 星期四 19:35:41
 * @version 1.0
 * @description:工程超权限申请 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formNo ,c.applyType ,c.applyTypeName ,c.applyMoney ,c.applyReson ,c.applyUserName ,
			c.applyUserId ,c.deptName ,c.deptId ,c.applyDate ,c.products ,c.rentalType ,c.rentalTypeName ,c.area ,c.areaId ,
			c.carNumber ,c.remark ,c.status ,c.ExaStatus ,c.ExaDT ,c.projectId ,c.projectCode ,c.projectName ,c.createId,
			c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.useRange,c.useRangeName
		from oa_esm_exceptionapply c where 1=1 "
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
		"name" => "formNoSearch",
		"sql" => " and c.formNo like concat('%',#,'%') "
	),
	array (
		"name" => "applyDateSearch",
		"sql" => " and c.applyDate like concat('%',#,'%') "
	),
	array (
		"name" => "applyType",
		"sql" => " and c.applyType=# "
	),
	array (
		"name" => "applyTypeName",
		"sql" => " and c.applyTypeName=# "
	),
	array (
		"name" => "applyMoney",
		"sql" => " and c.applyMoney=# "
	),
	array (
		"name" => "applyReson",
		"sql" => " and c.applyReson=# "
	),
    array (
        "name" => "applyResonSearch",
        "sql" => " and c.applyReson like concat('%',#,'%') "
    ),
	array (
		"name" => "products",
		"sql" => " and c.products=# "
	),
	array (
		"name" => "rentalType",
		"sql" => " and c.rentalType=# "
	),
	array (
		"name" => "rentalTypeName",
		"sql" => " and c.rentalTypeName=# "
	),
	array (
		"name" => "area",
		"sql" => " and c.area=# "
	),
	array (
		"name" => "areaId",
		"sql" => " and c.areaId=# "
	),
	array (
		"name" => "carNumber",
		"sql" => " and c.carNumber=# "
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
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
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
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "projectNameSearch",
		"sql" => " and c.projectName like concat('%',#,'%') "
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
		"name" => "ExaUserId",
		"sql" => " and c.ExaUserId=# "
	),
    array (
        "name" => "applyUserAndRange",
        "sql" => " and (c.applyUserId =# or c.useRange = '' )"
    ),
    array (
        "name" => "applyUserSearch",
        "sql" => " and c.applyUser like concat('%',#,'%')"
    )
)
?>