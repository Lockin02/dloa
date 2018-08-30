<?php
$sql_arr = array ("select_mail" => "select c.remark,c.id,c.mailInfoId,c.productId,
c.productNo,c.productName,c.mailNum,c.serialNum from oa_mail_products c
where 1=1 " );
$condition_arr = array (
	array (
		"name" => "mailInfoId",
		"sql" => "and c.mailInfoId =#"
	),
	array (
		"name" => "mailIdArr",
		"sql" => "and c.mailInfoId in(arr)"
	)
);
?>
