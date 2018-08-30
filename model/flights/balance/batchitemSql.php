<?php

/**
 * @author Administrator
 * @Date 2013年7月11日 9:44:16
 * @version 1.0
 * @description:订单结算 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id,c.mainId,c.airId,c.airName,c.fullFare,c.constructionCost,c.fuelCcharge,
			c.serviceCharge,c.actualCost,c.startDate,c.flightNumber,c.airlineId,c.airline,c.changeNum,c.arriveDate,
			c.arrivePlace,c.changeCost,c.ticketSum,c.changeReason,c.msgType,c.msgId,c.costPay,
			c.flightTime,c.arrivalTime,c.departPlace,c.arrivalPlace,c.auditDate
		from oa_flights_balance_item c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
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
		"name" => "startDate",
		"sql" => " and c.startDate=# "
	),
	array (
		"name" => "flightNumber",
		"sql" => " and c.flightNumber=# "
	),
	array (
		"name" => "airlineId",
		"sql" => " and c.airlineId=# "
	),
	array (
		"name" => "airline",
		"sql" => " and c.airline=# "
	),
	array (
		"name" => "changeNum",
		"sql" => " and c.changeNum=# "
	),
	array (
		"name" => "arriveDate",
		"sql" => " and c.arriveDate=# "
	),
	array (
		"name" => "arrivePlace",
		"sql" => " and c.arrivePlace=# "
	),
	array (
		"name" => "changeCost",
		"sql" => " and c.changeCost=# "
	),
	array (
		"name" => "ticketSum",
		"sql" => " and c.ticketSum=# "
	),
	array (
		"name" => "changeReason",
		"sql" => " and c.changeReason=# "
	),
	array (
		"name" => "msgType",
		"sql" => " and c.msgType=# "
	),
	array (
		"name" => "msgId",
		"sql" => " and c.msgId=# "
	)
)
?>