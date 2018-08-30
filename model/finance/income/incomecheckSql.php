<?php
/**
 * @author show
 * @Date 2013年8月13日 16:26:33
 * @version 1.0
 * @description:核销记录表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.incomeId ,c.incomeNo ,c.incomeType,c.contractId ,c.contractCode ,c.contractName ,
			c.payConId ,c.payConName ,c.checkMoney ,c.isRed ,c.auditStatus ,c.auditorId ,c.auditorName ,
			c.auditDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,
			c.checkDate,c.checkMoney,c.remark
		from oa_finance_income_check c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "incomeId",
		"sql" => " and c.incomeId=# "
	),
	array (
		"name" => "incomeType",
		"sql" => " and c.incomeType=# "
	),
	array (
		"name" => "incomeIdArr",
		"sql" => " and c.incomeId in(arr) "
	),
	array (
		"name" => "incomeNo",
		"sql" => " and c.incomeNo=# "
	),
	array (
		"name" => "incomeNoSearch",
		"sql" => " and c.incomeNo like concat('%',#,'%') "
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array (
		"name" => "contractIdArr",
		"sql" => " and c.contractId in(arr) "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode=# "
	),
	array (
		"name" => "contractCodeSearch",
		"sql" => " and c.contractCode like concat('%',#,'%') "
	),
	array (
		"name" => "contractName",
		"sql" => " and c.contractName=# "
	),
	array (
		"name" => "contractNameSearch",
		"sql" => " and c.contractName like concat('%',#,'%') "
	),
	array (
		"name" => "payConId",
		"sql" => " and c.payConId=# "
	),
	array (
		"name" => "payConIdArr",
		"sql" => " and c.payConId in(arr)"
	),
	array (
		"name" => "payConName",
		"sql" => " and c.payConName=# "
	),
	array (
		"name" => "checkMoney",
		"sql" => " and c.checkMoney=# "
	),
	array (
		"name" => "isRed",
		"sql" => " and c.isRed=# "
	),
	array (
		"name" => "auditStatus",
		"sql" => " and c.auditStatus=# "
	),
	array (
		"name" => "auditorId",
		"sql" => " and c.auditorId=# "
	),
	array (
		"name" => "auditorName",
		"sql" => " and c.auditorName=# "
	),
	array (
		"name" => "auditDate",
		"sql" => " and c.auditDate=# "
	),
	array (
		"name" => "remarkSearch",
		"sql" => " and c.remark like concat('%',#,'%') "
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