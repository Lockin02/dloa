<?php
$sql_arr = array(
	//Ä¬ÈÏËÑË÷Óï¾ä
	"select_defautl" => "select " .
			"c.id,c.objCode,c.inquiryEquId,c.basicNumb,c.productId,c.productNumb,c.amountAll,c.amountIssued," .
			"c.dateIssued,c.dateHope,c.dateEnd,c.remark,c.applyPrice,c.applyFactPrice,c.status " .
			"from oa_purch_apply_equ c where 1=1"
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.id = #"
	),
	array(
		"name" => "inquiryEquId",
		"sql" => " and c.inquiryEquId = #"
	),
	array(
		"name" => "productId",
		"sql" => " and c.productId = #"
	),
);
?>
