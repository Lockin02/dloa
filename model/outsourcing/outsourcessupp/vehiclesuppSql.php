<?php
/**
 * @author Michael
 * @Date 2014年1月7日 星期二 10:22:36
 * @version 1.0
 * @description:车辆供应商-基本信息 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.provinceId ,c.province ,c.cityId ,c.city ,c.suppCode ,c.suppName ,c.suppCategory ,c.suppCategoryName ,c.suppLevel ,c.registeredDate ,c.registeredFunds ,c.legalRepre ,c.businessDistribute ,c.businessDistributeId ,c.carAmount ,c.driverAmount ,c.invoice ,c.invoiceCode ,c.taxPoint ,c.isEquipDriver ,c.isDriveTest ,c.tentativeTalk ,c.companyProfile ,c.linkmanName ,c.linkmanJob ,c.linkmanPhone ,c.linkmanMail ,c.postcode ,c.address ,c.bankName ,c.bankAccount,c.bankReceiver ,c.blackReason ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_outsourcessupp_vehiclesupp c where 1=1 ",
	"select_excelOut"=>"select c.id ,c.suppCode ,c.suppName ,c.province ,c.city ,c.registeredDate ,c.registeredFunds ,c.legalRepre ,c.businessDistribute ,c.carAmount ,c.driverAmount ,c.tentativeTalk ,c.linkmanName ,c.linkmanJob ,c.linkmanPhone ,c.linkmanMail from oa_outsourcessupp_vehiclesupp c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array(
		"name" => "idArr",
		"sql" => " and c.id in(arr) "
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
		"name" => "provinceId",
		"sql" => " and c.provinceId=# "
	),
	array(
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array(
		"name" => "provinceSea",
		"sql" => " and c.province LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "cityId",
		"sql" => " and c.cityId=# "
	),
	array(
		"name" => "city",
		"sql" => " and c.city=# "
	),
	array(
		"name" => "citySea",
		"sql" => " and c.city LIKE CONCAT('%',#,'%') "
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
		"sql" => " and c.suppName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "suppNameEq",
		"sql" => " and c.suppName=# "
	),
	array(
		"name" => "suppCategory",
		"sql" => " and c.suppCategory=# "
	),
	array(
		"name" => "suppCategoryName",
		"sql" => " and c.suppCategoryName=# "
	),
	array(
		"name" => "suppLevel",
		"sql" => " and c.suppLevel=# "
	),
	array(
		"name" => "suppLevelNeq",
		"sql" => " and (c.suppLevel != # or c.suppLevel is null) "
	),
	array(
		"name" => "registeredDate",
		"sql" => " and c.registeredDate=# "
	),
	array(
		"name" => "registeredDateSea",
		"sql" => " and c.registeredDate  LIKE  BINARY  CONCAT('%',#,'%') "
	),
	array(
		"name" => "registeredFunds",
		"sql" => " and c.registeredFunds=# "
	),
	array(
		"name" => "businessDistribute",
		"sql" => " and c.businessDistribute LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "businessDistributeId",
		"sql" => " and c.businessDistributeId=# "
	),
	array(
		"name" => "carAmount",
		"sql" => " and c.carAmount=# "
	),
	array(
		"name" => "carAmountLower",
		"sql" => " and c.carAmount > # "
	),
	array(
		"name" => "carAmountCeiling",
		"sql" => " and c.carAmount < # "
	),
	array(
		"name" => "driverAmount",
		"sql" => " and c.driverAmount=# "
	),
	array(
		"name" => "driverAmountLower",
		"sql" => " and c.driverAmount > # "
	),
	array(
		"name" => "driverAmountCeiling",
		"sql" => " and c.driverAmount < # "
	),
	array(
		"name" => "invoice",
		"sql" => " and c.Invoice=# "
	),
	array(
		"name" => "invoiceCode",
		"sql" => " and c.InvoiceCode=# "
	),
	array(
		"name" => "taxPoint",
		"sql" => " and c.taxPoint=# "
	),
	array(
		"name" => "isEquipDriver",
		"sql" => " and c.isEquipDriver=# "
	),
	array(
		"name" => "isDriveTest",
		"sql" => " and c.isDriveTest=# "
	),
	array(
		"name" => "tentativeTalk",
		"sql" => " and c.tentativeTalk LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "companyProfile",
		"sql" => " and c.companyProfile=# "
	),
	array(
		"name" => "linkmanName",
		"sql" => " and c.linkmanName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "linkmanJob",
		"sql" => " and c.linkmanJob LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "linkmanPhone",
		"sql" => " and c.linkmanPhone LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "linkmanPhoneEq",
		"sql" => " and c.linkmanPhone=# "
	),
	array(
		"name" => "linkmanMail",
		"sql" => " and c.linkmanMail LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "postcode",
		"sql" => " and c.postcode=# "
	),
	array(
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array(
		"name" => "bankName",
		"sql" => " and c.bankName=# "
	),
	array(
		"name" => "bankAccount",
		"sql" => " and c.bankAccount=# "
	),
	array(
		"name" => "blackReason",
		"sql" => " and c.blackReason=# "
	),
	array(
		"name" => "blackReasonArr",
		"sql" => " and c.blackReason<># "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
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