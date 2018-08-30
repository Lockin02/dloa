<?php
/**
 * @author Michael
 * @Date 2014年1月7日 星期二 10:27:48
 * @version 1.0
 * @description:车辆供应商-车辆资源库 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.suppId ,c.suppCode ,c.suppName ,c.suppCategoryName ,c.suppCategory ,c.suppLevel ,c.place ,c.carNumber ,c.carModel ,c.brand ,c.displacement ,c.powerSupply ,c.oilWear ,c.buyDate ,c.driver ,c.phoneNum ,c.idNumber ,c.drivingLicence ,c.vehicleLicense ,c.insurance ,c.annualExam ,c.rentPrice ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_outsourcessupp_vehicle c where 1=1 " ,
	"select_excelOut"=>"select c.id ,c.suppCode ,c.suppName ,c.place ,c.carNumber ,c.carModel ,c.brand ,c.displacement ,c.powerSupply ,c.oilWear ,c.buyDate ,c.driver ,c.phoneNum ,c.idNumber ,c.drivingLicence ,c.vehicleLicense ,c.insurance ,c.annualExam ,c.rentPrice from oa_outsourcessupp_vehicle c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "formBelong",
		"sql" => " and c.formBelong=# "
	),
	array(
		"name" => "formBelongName",
		"sql" => " and c.formBelongName=# "
	),
	array(
		"name" => "businessBelong",
		"sql" => " and c.businessBelong=# "
	),
	array(
		"name" => "businessBelongName",
		"sql" => " and c.businessBelongName=# "
	),
	array(
		"name" => "suppId",
		"sql" => " and c.suppId=# "
	),
	array(
		"name" => "suppCode",
		"sql" => " and c.suppCode=# "
	),
	array(
		"name" => "suppCodeSea",
		"sql" => " and c.suppCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "suppName",
		"sql" => " and c.suppName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "suppCategoryName",
		"sql" => " and c.suppCategoryName=# "
	),
	array(
		"name" => "suppCategory",
		"sql" => " and c.suppCategory=# "
	),
	array(
		"name" => "suppLevel",
		"sql" => " and c.suppLevel=# "
	),
	array(
		"name" => "place",
		"sql" => " and c.place LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "carNumber",
		"sql" => " and c.carNumber LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "carModel",
		"sql" => " and c.carModel LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "brand",
		"sql" => " and c.brand LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "displacement",
		"sql" => " and c.displacement LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "powerSupply",
		"sql" => " and c.powerSupply LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "oilWear",
		"sql" => " and c.oilWear LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "buyDate",
		"sql" => " and c.buyDate=# "
	),
	array(
		"name" => "buyDateSea",
		"sql" => " and c.buyDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "buyDateSta",
		"sql" => " and c.buyDate > BINARY # "
	),
	array(
		"name" => "buyDateEnd",
		"sql" => " and c.buyDate < BINARY # "
	),
	array(
		"name" => "driver",
		"sql" => " and c.driver LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "phoneNum",
		"sql" => " and c.phoneNum LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "idNumber",
		"sql" => " and c.idNumber LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "drivingLicence",
		"sql" => " and c.drivingLicence LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "vehicleLicense",
		"sql" => " and c.vehicleLicense LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "insurance",
		"sql" => " and c.insurance LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "annualExam",
		"sql" => " and c.annualExam LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "rentPrice",
		"sql" => " and c.rentPrice=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>