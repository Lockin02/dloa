<?php
$sql_arr = array ("select_mail" => "select i.mailNo,i.mailApplyId,c.id,c.mailInfoId,c.signMan,
c.signDate,c.signPath,c.remark from oa_mail_sign c
left join oa_mail_info i on i.id=c.mailInfoId
where 1=1 " );
$condition_arr = array (
	array (
		"name" => "mailInfoId",
		"sql" => "and c.mailInfoId =#"
	),
	array (
		"name" => "mailNo",
		"sql" => "and i.mailNo like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"invoiceapply",
		"sql"=>"and (i.mailApplyId  in(select id from oa_mail_apply where applyType =#))"
	),
	array(
		"name"=>"notInvoiceapply",
		"sql"=>"and (i.mailApplyId not in(select id from oa_mail_apply where applyType =#))"
	)
);
?>
