<?php

/**
 * @author sony
 * @Date 2013年7月10日 17:29:50
 * @version 1.0
 * @description:订票信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.requireNo ,c.requireId ,c.airName ,c.airId ,c.startDate ,c.arriveDate ,c.flightNumber ,c.airline ,
			c.airlineId ,c.privatePay ,c.fullFare ,c.constructionCost ,c.fuelCcharge ,c.serviceCharge ,c.changeFees ,c.isLow ,c.lowremark ,
			c.actualCost ,c.businessState ,c.auditState ,c.auditorId ,c.auditorName ,c.auditTime ,c.createId ,c.createName ,c.createTime ,
			c.updateId ,c.updateName ,c.updateTime ,c.organization ,c.organizationId ,c.changCount ,c.costBelongDeptName,c.feeChange,
			c.feeBack,c.costBack,c.costPay,c.costDiff,c.beforeCost,c.msgType,c.ticketType,c.startPlace,c.middlePlace,c.twoDate,c.endPlace,c.comeDate,
			c.id as msgId,c.costPay as costPayShow,c.flightTime,c.arrivalTime,c.departPlace,c.arrivalPlace,c.auditDate
		from oa_flights_message c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "requireNo",
		"sql" => " and c.requireNo=# "
	),
	array (
		"name" => "requireId",
		"sql" => " and c.requireId=# "
	),
	array (
		"name" => "airName",
		"sql" => " and c.airName=# "
	),
	array (
		"name" => "startDate",
		"sql" => " and c.startDate=# "
	),
	array (
		"name" => "arriveDate",
		"sql" => " and c.arriveDate=# "
	),
	array (
		"name" => "auditDate",
		"sql" => " and c.auditDate=# "
	),
	array (
		"name" => "flightNumber",
		"sql" => " and c.flightNumber=# "
	),
	array (
		"name" => "flightNumberSearch",
		"sql" => " and c.flightNumber like concat('%',#,'%') "
	),
	array (
		"name" => "airline",
		"sql" => " and c.airline=# "
	),
	array (
		"name" => "privatePay",
		"sql" => " and c.privatePay=# "
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
		"name" => "changeFees",
		"sql" => " and c.changeFees=# "
	),
	array (
		"name" => "isLow",
		"sql" => " and c.isLow=# "
	),
	array (
		"name" => "lowremark",
		"sql" => " and c.lowremark=# "
	),
	array (
		"name" => "actualCost",
		"sql" => " and c.actualCost=# "
	),
	array (
		"name" => "businessState",
		"sql" => " and c.businessState=# "
	),
	array (
		"name" => "auditState",
		"sql" => " and c.auditState=# "
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
		"name" => "auditTime",
		"sql" => " and c.auditTime=# "
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
		"name" => "createNameSearch",
		"sql" => " and c.createName like concat('%',#,'%')"
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
		"name" => "organization",
		"sql" => " and c.organization=# "
	),
	array (
		"name" => "organizationId",
		"sql" => " and c.organizationId=# "
	),
	array (
		"name" => "airId",
		"sql" => " and c.airId=# "
	),
	array (
		"name" => "changCount",
		"sql" => " and c.changCount=# "
	),
	array (
		"name" => "airNameSearch",
		"sql" => " and c.airName like concat('%',#,'%')"
	),
	array (
		"name" => "flightNumberSearch",
		"sql" => " and c.flightNumber like concat('%',#,'%')"
	),
	array (
		"name" => "ids",
		"sql" => " and c.Id in(arr) ",

	),
	array (
		"name" => "airlineId",
		"sql" => "and c.airlineId=# "
	),
	array (
		"name" => "airlineIdArr",
		"sql" => "and c.airlineId in(arr)"
	),
	array (
		"name" => "costBelongDeptName",
		"sql" => "and c.costBelongDeptName=# "
	),
	array (
		"name" => "ticketType",
		"sql" => "and c.ticketType=# "
	),
	array (
		"name" => "startPlace",
		"sql" => "and c.startPlace=# "
	),
	array (
		"name" => "middlePlace",
		"sql" => "and c.middlePlace=# "
	),
	array (
		"name" => "twoDate",
		"sql" => "and c.twoDate=# "
	),
	array (
		"name" => "endPlace",
		"sql" => "and c.endPlace=# "
	),
	array (
		"name" => "comeDate",
		"sql" => "and c.comeDate=# "
	),
	array (
		"name" => "beginDateThen",
		"sql" => " and c.auditDate >= # "
	),
	array (
		"name" => "endDateThen",
		"sql" => " and c.auditDate <= # "
	),
	array (
		"name" => "flightTime",
		"sql" => "and c.flightTime=# "
	),
	array (
		"name" => "arrivalTime",
		"sql" => "and c.arrivalTime=# "
	),
	array (
		"name" => "departPlace",
		"sql" => "and c.departPlace=# "
	),
	array (
		"name" => "arrivalPlace",
		"sql" => "and c.arrivalPlace=# "
	)
)
?>