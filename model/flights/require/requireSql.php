<?php

/**
 * @author Show
 * @Date 2013��7��26�� ������ 13:44:29
 * @version 1.0
 * @description:��Ʊ���� sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.requireNo ,c.requireId ,c.requireName ,c.requirePhone ,c.requireTime ,c.companyId ,.
			c.companyName ,c.deptId ,c.deptName ,c.airId ,c.airName ,c.airPhone ,c.cardTypeName ,c.cardType ,c.cardNo ,
			c.validDate ,c.birthDate ,c.nation ,c.airCompanyId ,c.airCompany ,c.tourAgencyName ,c.tourAgency ,c.tourCardNo ,
			c.flyTime ,c.flyStartTime ,c.flyEndTime ,c.startPlace ,c.ticketType ,c.requireReason ,c.middlePlace ,c.endPlace ,
			c.startDate ,c.comeDate ,c.firstDate ,c.twoDate ,c.ticketMsg ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,
			c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.detailType ,c.costBelongCom ,c.costBelongComId ,
			c.costBelongDeptName ,c.costBelongDeptId ,c.projectId ,c.projectCode ,c.projectName ,c.proManagerName ,c.proManagerId ,
			c.chanceId ,c.chanceCode ,c.chanceName ,c.contractId ,c.contractCode ,c.contractName ,c.customerId ,c.customerName ,
			c.province ,c.city ,c.customerType ,c.costBelongerId ,c.costBelongDeptName,c.cardNoHidden,c.costBelonger,c.outReason,
			c.projectType
		from oa_flights_require c where 1=1 "
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
		"name" => "requireNoSearch",
		"sql" => " and c.requireNo like concat('%',#,'%')"
	),
	array (
		"name" => "requireId",
		"sql" => " and c.requireId=# "
	),
	array (
		"name" => "requireName",
		"sql" => " and c.requireName=# "
	),
	array (
		"name" => "requireName",
		"sql" => " and c.requireName like concat('%',#,'%')"
	),
	array (
		"name" => "requirePhone",
		"sql" => " and c.requirePhone=# "
	),
	array (
		"name" => "requireTime",
		"sql" => " and c.requireTime=# "
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
		"name" => "airId",
		"sql" => " and c.airId=# "
	),
	array (
		"name" => "airName",
		"sql" => " and c.airName=# "
	),
	array (
		"name" => "airNameSearch",
		"sql" => " and id in (select mainId from oa_flights_require_suite where airName like concat('%',#,'%')) "
	),
	array (
		"name" => "airPhone",
		"sql" => " and c.airPhone=# "
	),
	array (
		"name" => "cardTypeName",
		"sql" => " and c.cardTypeName=# "
	),
	array (
		"name" => "cardType",
		"sql" => " and c.cardType=# "
	),

	array (
		"name" => "validDate",
		"sql" => " and c.validDate=# "
	),
	array (
		"name" => "birthDate",
		"sql" => " and c.birthDate=# "
	),
	array (
		"name" => "nation",
		"sql" => " and c.nation=# "
	),
	array (
		"name" => "airCompanyId",
		"sql" => " and c.airCompanyId=# "
	),
	array (
		"name" => "airCompany",
		"sql" => " and c.airCompany=# "
	),
	array (
		"name" => "tourAgencyName",
		"sql" => " and c.tourAgencyName=# "
	),
	array (
		"name" => "tourAgency",
		"sql" => " and c.tourAgency=# "
	),
	array (
		"name" => "tourCardNo",
		"sql" => " and c.tourCardNo=# "
	),
	array (
		"name" => "flyTime",
		"sql" => " and c.flyTime=# "
	),
	array (
		"name" => "flyStartTime",
		"sql" => " and c.flyStartTime=# "
	),
	array (
		"name" => "flyEndTime",
		"sql" => " and c.flyEndTime=# "
	),
	array (
		"name" => "startPlace",
		"sql" => " and c.startPlace=# "
	),
	array (
		"name" => "startPlaceSearch",
		"sql" => " and c.startPlace like concat('%',#,'%')"
	),
	array (
		"name" => "ticketType",
		"sql" => " and c.ticketType=# "
	),
	array (
		"name" => "requireReason",
		"sql" => " and c.requireReason=# "
	),
	array (
		"name" => "middlePlace",
		"sql" => " and c.middlePlace=# "
	),
	array (
		"name" => "middlePlaceSearch",
		"sql" => " and c.middlePlace like concat('%',#,'%')"
	),
	array (
		"name" => "endPlace",
		"sql" => " and c.endPlace=# "
	),
	array (
		"name" => "endPlaceSearch",
		"sql" => " and c.endPlace like concat('%',#,'%')"
	),
	array (
		"name" => "startDate",
		"sql" => " and c.startDate=# "
	),
	array (
		"name" => "comeDate",
		"sql" => " and c.comeDate=# "
	),
	array (
		"name" => "firstDate",
		"sql" => " and c.firstDate=# "
	),
	array (
		"name" => "twoDate",
		"sql" => " and c.twoDate=# "
	),
	array (
		"name" => "ticketMsg",
		"sql" => " and c.ticketMsg=# "
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
		"name" => "detailType",
		"sql" => " and c.detailType=# "
	),
	array (
		"name" => "costBelongCom",
		"sql" => " and c.costBelongCom=# "
	),
	array (
		"name" => "costBelongComId",
		"sql" => " and c.costBelongComId=# "
	),
	array (
		"name" => "costBelongDeptName",
		"sql" => " and c.costBelongDeptName=# "
	),
	array (
		"name" => "costBelongDeptId",
		"sql" => " and c.costBelongDeptId=# "
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
		"name" => "proManagerName",
		"sql" => " and c.proManagerName=# "
	),
	array (
		"name" => "proManagerId",
		"sql" => " and c.proManagerId=# "
	),
	array (
		"name" => "chanceId",
		"sql" => " and c.chanceId=# "
	),
	array (
		"name" => "chanceCode",
		"sql" => " and c.chanceCode=# "
	),
	array (
		"name" => "chanceName",
		"sql" => " and c.chanceName=# "
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode=# "
	),
	array (
		"name" => "contractName",
		"sql" => " and c.contractName=# "
	),
	array (
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName=# "
	),
	array (
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array (
		"name" => "city",
		"sql" => " and c.city=# "
	),
	array (
		"name" => "customerType",
		"sql" => " and c.customerType=# "
	),
	array (
		"name" => "costBelongerId",
		"sql" => " and c.costBelongerId=# "
	),
	array (
		"name" => "costBelongDeptName",
		"sql" => " and c.costBelongDeptName=# "
	),
	array (
		"name" => "c.cardNoHidden",
		"sql" => " and c.cardNoHidden=# "
	)
)
?>