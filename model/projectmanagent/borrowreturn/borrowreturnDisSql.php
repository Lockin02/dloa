<?php
/**
 * @author Administrator
 * @Date 2013年1月14日 14:04:03
 * @version 1.0
 * @description:仓管归还操作单据表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.compensateType,c.compensateTypeName,c.damageLevel,c.damageLevelName,c.id ,
			c.compensateState,c.money,c.ExaStatus,c.ExaDT,c.Code ,c.borrowreturnId ,c.borrowreturnCode ,
			c.borrowId ,c.borrowCode ,c.borrowLimit ,c.remark ,c.disposeIdea ,c.disposeType ,c.state ,
			c.disposeState ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId,
			c.affirmMoney,c.applyType,c.applyTypeName,c.chargerId,c.chargerName,c.deptId,c.deptName,c.borrowreturnManId,
			c.borrowreturnMan,c.customerId,c.customerName
		from oa_borrow_return_dispose c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "compensateState",
		"sql" => " and c.compensateState=#"
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "Code",
		"sql" => " and c.Code like CONCAT('%',#,'%') "
	),
	array (
		"name" => "borrowreturnId",
		"sql" => " and c.borrowreturnId=# "
	),
	array (
		"name" => "borrowreturnCode",
		"sql" => " and c.borrowreturnCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "borrowId",
		"sql" => " and c.borrowId=# "
	),
	array (
		"name" => "borrowCode",
		"sql" => " and c.borrowCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "borrowLimit",
		"sql" => " and c.borrowLimit=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "disposeIdea",
		"sql" => " and c.disposeIdea=# "
	),
	array (
		"name" => "disposeType",
		"sql" => " and c.disposeType=# "
	),
	array (
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array (
		"name" => "states",
		"sql" => " and c.state in(arr) "
	),
	array (
		"name" => "disposeState",
		"sql" => " and c.disposeState=# "
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
		"name" => "borrowreturnMan",
		"sql" => " and c.borrowreturnMan like CONCAT('%',#,'%') "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName like CONCAT('%',#,'%') "
	),
	array(//物料序列号
		"name"=>"serialName",
		"sql" => " and c.id in(select e.disposeId from oa_borrow_return_dispose_equ e where e.serialName like CONCAT('%',#,'%')) "
	)
)
?>