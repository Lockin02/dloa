<?php
$sql_arr = array(

	'bankinfo' =>'select ' .
			'c.id,c.suppId,c.suppName,c.bankName,c.busiCode,c.depositbank,c.accountNum,c.remark ' .
			'from oa_supp_bankinfo c where 1=1',
);
$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.id = # "
	),
	array(
		"name" => "suppId",
		"sql" => " and c.suppId = #"
	),
	array(
		"name" => "busiCode",
		"sql" => " and c.busiCode = #"
	),
	array(
		"name" => "suppName",
		"sql" => " and c.suppName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "depositbank",
		"sql" => " and c.depositbank like CONCAT('%',#,'%') "
	),
	array(
		"name" => "accountNum",
		"sql" => "and c.accountNum like CONCAT('%',#,'%') "
	),
);
?>
