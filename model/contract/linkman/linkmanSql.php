<?php
$sql_arr = array (
	"select_invoice" => "select c.contID,c.,contNumber,c.contName,c.linkmanId,c.linkmanName,c.QQ,c.telephone,c.Email,c.remark from oa_contract_invoice c where 1=1"
);
$condition_arr = array (
	array (
		"name" => "contID",
		"sql" => "and c.contID = #"
	)
);
?>
