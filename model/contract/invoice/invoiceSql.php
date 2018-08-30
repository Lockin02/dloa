<?php
$sql_arr = array (
	"select_invoice" => "select c.contractId,c.,contNumber,c.contName,c.money,c.softM,c.iType,c.invDT,c.remark from oa_contract_invoice c where 1=1"
);
$condition_arr = array (
	array (
		"name" => "contractId",
		"sql" => "and c.contractId = #"
	)
);
?>
