<?php
/**
 * @author Show
 * @Date 2012年6月25日 星期一 19:10:38
 * @version 1.0
 * @description:付款申请费用分摊明细表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.payapplyId ,c.payapplyCode ,c.shareTypeName ,c.shareType ,c.shareObjName,c.shareObjCode,c.shareObjId,c.deptName ,c.deptId ,c.userName ,c.userId ,c.projectName ,c.projectCode ,c.projectId ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.shareMoney,c.feeType,c.feeTypeId  from oa_finance_payablesapply_cost_temp c where 1=1 ",
	"count_all" => "select sum(c.shareMoney) as shareMoney  from oa_finance_payablesapply_cost_temp c where 1=1 ",
	"count_shareObj" => "select c.shareObjCode,sum(c.shareMoney) as shareMoney from oa_finance_payablesapply_cost_temp c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "payapplyId",
		"sql" => " and c.payapplyId=# "
	),
	array (
		"name" => "payapplyCode",
		"sql" => " and c.payapplyCode=# "
	),
	array (
		"name" => "payapplyCodeSearch",
		"sql" => " and c.payapplyCode like concat('%',#,'%')"
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
		"name" => "shareObj",
		"sql" => " and c.shareObj=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptNameSearch",
		"sql" => " and c.deptName like concat('%',#,'%')"
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
		"name" => "userNameSearch",
		"sql" => " and c.userName like concat('%',#,'%')"
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
		"name" => "projectNameSearch",
		"sql" => " and c.projectName like concat('%',#,'%')"
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectCodeArr",
		"sql" => " and c.projectCode in(arr) "
	),
	array (
		"name" => "projectCodeSearch",
		"sql" => " and c.projectCode like concat('%',#,'%')"
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
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
		//add chenrf 查找临时导入信息
	array (
		'name' => 'payapplyIdNull',
		'sql' => ' and c.payapplyId is null and createId= # '
	)
)
?>