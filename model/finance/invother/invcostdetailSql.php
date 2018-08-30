<?php
/**
 * @author Show
 * @Date 2013年7月5日 星期五 14:59:59
 * @version 1.0
 * @description:其他发票费用分摊 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.invotherId ,c.shareTypeName ,c.shareType ,c.shareObjId ,c.shareObjName ,c.shareObjCode ,c.deptName ,c.deptId ,c.userName ,c.userId ,c.projectName ,c.projectCode ,c.projectId ,c.shareMoney ,c.feeType ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.feeTypeId ,c.payCostId  from oa_finance_invother_cost c where 1=1 ",
	"count_list" => "select sum(c.shareMoney) as shareMoney from oa_finance_invother_cost c where 1=1 ",
	"count_group" => "select c.shareType,c.shareObjName,c.feeType,sum(c.shareMoney) as shareMoney from oa_finance_invother_cost c where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "invotherId",
		"sql" => " and c.invotherId=# "
	),
	array (
		"name" => "invotherIdArr",
		"sql" => " and c.invotherId in(arr)"
	),
	array (
		"name" => "shareTypeName",
		"sql" => " and c.shareTypeName=# "
	),
	array (
		"name" => "shareType",
		"sql" => " and c.shareType=# "
	),
	array (
		"name" => "shareObjId",
		"sql" => " and c.shareObjId=# "
	),
	array (
		"name" => "shareObjName",
		"sql" => " and c.shareObjName=# "
	),
	array (
		"name" => "shareObjCode",
		"sql" => " and c.shareObjCode=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "userId",
		"sql" => " and c.userId=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "shareMoney",
		"sql" => " and c.shareMoney=# "
	),
	array (
		"name" => "feeType",
		"sql" => " and c.feeType=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
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
	),
	array (
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array (
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array (
		"name" => "feeTypeId",
		"sql" => " and c.feeTypeId=# "
	),
	array (
		"name" => "payCostId",
		"sql" => " and c.payCostId=# "
	)
)
?>