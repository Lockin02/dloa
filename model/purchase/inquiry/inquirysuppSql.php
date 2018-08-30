<?php
$sql_arr = array (
	"list" => " select c.id ,c.parentId ,c.suppName ,c.suppId ,c.suppTel ,c.quote,c.payRatio,c.paymentCondition,c.paymentConditionName,c.remark  from oa_purch_inquiry_supp c where 1=1",
	"inqu_supp" => "select p.id,p.suppName,p.suppId,p.suppTel,w.price, " .
			"w.deliveryDate,w.transportation,w.inquiryEquId,w.takeEquId  from oa_purch_inquiry_supp p " .
			"left join oa_purch_inquiry_suppequ w on p.id = w.parentId where 1=1",
	"lin_supp" => "select p.id,p.suppName,p.suppId,p.suppTel,p.parentId " .
			"from oa_purch_inquiry_supp p " .
			"left join oa_purch_inquiry i on p.id = i.suppId where 1=1"
);

$condition_arr = array (
	array(
		"name" => "id",	//id 作为查询条件
		"sql" => " and p.id=# "
	)
	,array(
		"name" => "parentId",
		"sql" => " and p.parentId = # "
	)
	,array(
		"name" => "parentId",
		"sql" => " and w.parentId = # "
	)
	,array(
		"name" => "suppId",
		"sql" => " and p.suppId = # "
	)
);
?>