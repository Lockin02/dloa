<?php
$sql_arr = array (
	"list" => "select p.id ,p.parentId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.units ,p.auxiliary ,p.amount ,p.deliveryDate ,p.transportation ,p.price ,p.tax,p.taxRate,p.moneyAll ,p.inquiryEquId ,p.takeEquId  from oa_purch_inquiry_suppequ p where 1=1  ",
	"list_basicEqu" => "select p.id ,p.parentId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.units ,p.auxiliary ,p.amount ,p.deliveryDate ,p.transportation ,p.price ,p.tax,p.taxRate,p.moneyAll ,p.inquiryEquId ,p.takeEquId  from oa_purch_inquiry_suppequ p where 1=1 ",
	"inquiry_suppequ_list"=>"select ise.id,ise.productName,ise.parentId,ise.price,ise.inquiryEquId,ise.taxRate,ie.id as ieId,s.suppName
								from oa_purch_inquiry_suppequ ise
									left join oa_purch_inquiry_equ ie on(ie.id=ise.inquiryEquId)
										left join oa_purch_inquiry_supp s on(s.id=ise.parentId)" .
											"where 1=1"
);

$condition_arr = array (
	array(
		"name" => "id",	//id 作为查询条件
		"sql" => " and p.id=# "
	),
	array(
		"name" => "parentId",	//id 作为查询条件
		"sql" => " and p.parentId=# "
	),
	array(
		"name" => "ieId",
		"sql" => " and ie.id=# "
	)
);
?>