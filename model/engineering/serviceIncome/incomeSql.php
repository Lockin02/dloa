<?php
$sql_arr = array(
	"select_default" => "select c.id,c.contractNo,c.contractID,c.contractName,c.money,c.payDT,c.pType,c.collectionTerms,c.isOver,c.overDT " .
			"from oa_contract_service_income c where 1=1"
);

$condiction_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id = #"
	),
	array(
		"name" => "contractNo",
		"sql" => "and c.contractNo = #"
	),
	array(
		"name" => "contractID",
		"sql" => "and c.contractID = #"
	),
	array(
		"name" => "contractName",
		"sql" => "and c.contractName = #"
	),
	array(
		"name" => "isOver",
		"sql" => "and c.isOver = #"
	),
);
?>
