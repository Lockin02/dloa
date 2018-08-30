<?php
/**
 * @author Administrator
 * @Date 2012-12-21 10:43:11
 * @version 1.0
 * @description:借试用归还管理 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.compensateType,c.compensateTypeName,c.damageLevel,c.damageLevelName,
			c.disposeState,c.id ,c.Code ,c.borrowId ,c.borrowCode ,c.borrowLimit ,c.remark ,c.state ,
			c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId,c.applyTypeName,
			c.applyType,c.deptName,c.deptId,c.customerName,c.customerId,ExaStatus,ExaDT,c.salesId,c.salesName,
			c.editReason,c.backReason,c.receiveStatus,c.receiveId,c.receiveName,c.receiveTime
		from oa_borrow_return c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "disposeStateS",
		"sql" => " and c.disposeState in(arr)"
	),
	array (
		"name" => "disposeStateNot",
		"sql" => " and c.disposeState not in(arr)"
	),
	array (
		"name" => "Code",
		"sql" => " and c.Code like CONCAT('%',#,'%') "
	),
	array (
		"name" => "borrowId",
		"sql" => " and c.borrowId=# "
	),
	array (
		"name" => "borrowCode",
		"sql" => " and c.borrowCode=# "
	),
	array (
		"name" => "borrowCodeSearch",
		"sql" => " and c.borrowCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "borrowLimit",
		"sql" => " and c.borrowLimit=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array (
		"name" => "disposeState",
		"sql" => " and c.disposeState=# "
	),
	array (
		"name" => "disposeStates",
		"sql" => " and c.disposeState in(arr) "
	),
	array (
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr) "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus =# "
	),
	array (
		"name" => "states",
		"sql" => " and c.state in(arr) "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "salesId",
		"sql" => " and c.salesId=# "
	),
	array (
		"name" => "salesName",
		"sql" => " and c.salesName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName like CONCAT('%',#,'%') "
	),
	array(//物料序列号
		"name"=>"serialName",
		"sql" => " and c.id in(select e.returnId from oa_borrow_return_equ e where e.serialName like CONCAT('%',#,'%')) "
	)
);
?>