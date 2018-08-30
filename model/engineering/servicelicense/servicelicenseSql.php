<?php
$sql_arr = array(
	"select_default" => "select c.id,c.softdogType,c.amount,c.licenseTypeIds,c.licenseType,c.validity," .
			"c.remark,c.contractId,c.contractNo,c.contractName,c.productLine,c.isSell " .
			"from oa_contract_service_license c where 1=1"
);

$condiction_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id = #"
	),
	array(
		"name" => "softdogType",
		"sql" => "and c.softdogType=#"
	),
	array(
		"name" => "licenseTypeIds",
		"sql" => "and c.licenseTypeIds=#"
	),
	array(
		"name" => "contractId",
		"sql" => "and c.contractId=#"
	),
	array(
		"name" => "contractName",
		"sql" => "and c.contractName=#"
	),

);
?>
