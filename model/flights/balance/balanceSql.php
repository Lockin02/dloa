<?php
/**
 * @author Administrator
 * @Date 2013年7月11日 9:44:16
 * @version 1.0
 * @description:订单结算 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id,c.balanceDateB,c.balanceDateE,c.balanceSum,c.balanceCode,c.rebate,
			c.exchange,c.exchangeMoney,c.actualMoney,c.airId,c.airName,c.requirePhone,c.deptId,c.deptName,
			c.billCode,c.changeFees,c.feeForRefund,c.balanceStatus,c.createId,c.createName,c.createTime,
			c.updateId,c.updateName,c.updateTime,c.arrivePlace,c.fullFare,c.constructionCost,c.fuelCcharge,
			c.serviceCharge,c.actualCost,c.organizationId,c.organization,c.companyId,c.companyName
		from oa_flights_balance c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "balanceDateB",
		"sql" => " and c.balanceDateB=# "
	),
	array (
		"name" => "balanceDateE",
		"sql" => " and c.balanceDateE=# "
	),
	array (
		"name" => "balanceSum",
		"sql" => " and c.balanceSum=# "
	),
	array (
		"name" => "balanceCode",
		"sql" => " and c.balanceCode=# "
	),
	array (
		"name" => "rebate",
		"sql" => " and c.rebate=# "
	),
	array (
		"name" => "exchange",
		"sql" => " and c.exchange=# "
	),
	array (
		"name" => "exchangeMoney",
		"sql" => " and c.exchangeMoney=# "
	),
	array (
		"name" => "actualMoney",
		"sql" => " and c.actualMoney=# "
	),
	array (
		"name" => "airId",
		"sql" => " and c.airId=# "
	),
	array (
		"name" => "airName",
		"sql" => " and c.airName=# "
	),
	array (
		"name" => "requirePhone",
		"sql" => " and c.requirePhone=# "
	),
	array (
		"name" => "companyId",
		"sql" => " and c.companyId=# "
	),
	array (
		"name" => "companyName",
		"sql" => " and c.companyName=# "
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
		"name" => "billCode",
		"sql" => " and c.billCode=# "
	),
	array (
		"name" => "changeFees",
		"sql" => " and c.changeFees=# "
	),
	array (
		"name" => "feeForRefund",
		"sql" => " and c.feeForRefund=# "
	),
	array (
		"name" => "balanceStatus",
		"sql" => " and c.balanceStatus=# "
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
		"name" => "arrivePlace",
		"sql" => " and c.arrivePlace=# "
	),
	array (
		"name" => "fullFare",
		"sql" => " and c.fullFare=# "
	),
	array (
		"name" => "constructionCost",
		"sql" => " and c.constructionCost=# "
	),
	array (
		"name" => "fuelCcharge",
		"sql" => " and c.fuelCcharge=# "
	),
	array (
		"name" => "serviceCharge",
		"sql" => " and c.serviceCharge=# "
	),
	array (
		"name" => "actualCost",
		"sql" => " and c.actualCost=# "
	),
	array (
		"name" => "organizationId",
		"sql" => " and c.organizationId=# "
	),
	array (
		"name" => "organization",
		"sql" => " and c.organization=# "
	)
)
?>