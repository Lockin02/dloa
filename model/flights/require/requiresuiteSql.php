<?php
/**
 * @author Show
 * @Date 2013年7月26日 星期五 13:54:26
 * @version 1.0
 * @description:随行人员表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.airId ,c.airName ,c.airPhone ,c.cardTypeName ,c.cardType ,c.cardNo,
			c.validDate ,c.birthDate ,c.nation ,c.tourAgencyName ,c.tourAgency ,c.tourCardNo ,c.companyId ,c.companyName ,
			c.deptId ,c.deptName,c.cardNoHidden,c.employeeType,c.employeeTypeName,c.sex
		from oa_flights_require_suite c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
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
		"name" => "cardNoHidden",
		"sql" => " and c.cardNoHidden=# "
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
	)
)
?>